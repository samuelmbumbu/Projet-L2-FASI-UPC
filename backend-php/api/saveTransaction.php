<?php
session_start();

require_once '../config/db.php';
require_once '../controllers/transactionController.php';
require_once '../controllers/alertController.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../frontend/auth/login.php');
    exit;
}

if (($_SESSION['role'] ?? '') === 'admin') {
    header('Location: ../../frontend/pages/dashboard.php');
    exit;
}

$userId = (int) $_SESSION['user_id'];

$stmt = $pdo->prepare('
    SELECT solde_compte, pin_hash, DATEDIFF(NOW(), date_creation) AS anciennete
    FROM users
    WHERE id = ?
');
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$montant = (float) ($_POST['montant'] ?? 0);
$type = $_POST['type_transaction'] ?? 'PAYMENT';
$lieu = $_POST['lieu'] ?? 'Kin';
$beneficiaire = trim($_POST['beneficiaire'] ?? 'BENEF-001');
$pin = trim($_POST['pin'] ?? '');
$tempsValidation = (float) ($_POST['temps_validation'] ?? 0);

if ($montant <= 0) {
    header('Location: ../../frontend/pages/transaction.php?error=' . urlencode('Montant invalide'));
    exit;
}

if (empty($user['pin_hash'])) {
    header('Location: ../../frontend/pages/transaction.php?error=' . urlencode('Veuillez configurer votre PIN dans le profil'));
    exit;
}

$stats = getClientStats($pdo, $userId);

$moyenne = max(1, (float) $stats['moyenne']);
$ecart = $montant - $moyenne;
$frequence = getRecentFrequency($pdo, $userId);
$benefNouveau = isNewBeneficiary($pdo, $userId, $beneficiaire);

$heure = (int) date('G');
$jour = (int) date('N');
$solde = (float) ($user['solde_compte'] ?? 0);
$anciennete = (int) ($user['anciennete'] ?? 0);

if (!isset($_SESSION['pin_attempts'])) {
    $_SESSION['pin_attempts'] = 0;
}

$pinCorrect = password_verify($pin, $user['pin_hash']);

if (!$pinCorrect) {
    $_SESSION['pin_attempts']++;

    $tentativesPin = (int) $_SESSION['pin_attempts'];

    header(
        'Location: ../../frontend/pages/transaction.php?error=' .
        urlencode('Code PIN incorrect. Tentative ' . $tentativesPin)
    );
    exit;
}

$tentativesPin = (int) ($_SESSION['pin_attempts'] ?? 0);
$_SESSION['pin_attempts'] = 0;

if ($montant > $solde) {
    $risk = 0.95;
    $message = 'montant supérieur au solde disponible';

    $stmt = $pdo->prepare('
        INSERT INTO transactions
        (user_id, montant, type_transaction, lieu, beneficiaire, heure, frequence, temps_validation,
        solde_compte, anciennete_compte, montant_moyen, ecart_montant, tentatives_pin,
        beneficiaire_nouveau, jour_semaine, probabilite, resultat, explication, date_transaction)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, "bloquée", ?, NOW())
    ');

    $stmt->execute([
        $userId, $montant, $type, $lieu, $beneficiaire, $heure, $frequence, $tempsValidation,
        $solde, $anciennete, $moyenne, $ecart, $tentativesPin,
        $benefNouveau, $jour, $risk, $message
    ]);

    $transactionId = (int) $pdo->lastInsertId();
    createFraudAlert($pdo, $transactionId, $risk, $message);

    header('Location: ../../frontend/pages/transaction.php?done=1&risk=95&decision=bloquée');
    exit;
}

$typeForModel = match ($type) {
    'TRANSFER' => 'Bank Transfer',
    'CASH_OUT' => 'ATM Withdrawal',
    'CASH_IN' => 'POS',
    default => 'Online'
};

$payload = [
    'montant' => $montant,
    'type_transaction' => $typeForModel,
    'lieu' => $lieu,
    'solde_compte' => $solde,
    'frequence_24h' => $frequence,
    'montant_moyen' => $moyenne,
    'tentatives_pin' => $tentativesPin,
    'heure' => $heure
];

$ch = curl_init('http://127.0.0.1:5001/predict');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_TIMEOUT, 5);

$response = curl_exec($ch);
$curlError = curl_error($ch);
curl_close($ch);


$result = json_decode($response, true);

if (!$result || isset($result['error'])) {
    $scoreML = 50;
    $messageML = 'modèle ML indisponible : transaction à vérifier';
} else {
    $scoreML = (float) ($result['risk_percent'] ?? 50);
    $messageML = $result['message'] ?? 'analyse ML effectuée';
}

$scoreRegles = 0;
$raisons = [];

if ($tempsValidation > 0 && $tempsValidation < 1) {
    $scoreRegles += 10;
    $raisons[] = 'validation PIN trop rapide';
}

if ($tempsValidation > 10 && $tempsValidation < 15) {
    $scoreRegles += 35;
    $raisons[] = 'validation PIN trop lente';
}
if ($tempsValidation >= 15) {
    $scoreRegles += 45;
    $raisons[] = 'temps extrêmement long de validation PIN';
}

if ($solde > 0 && $montant > 0.8 * $solde) {
    $scoreRegles += 15;
    $raisons[] = 'montant élevé par rapport au solde';
}

if ($benefNouveau) {
    $scoreRegles += 10;
    $raisons[] = 'nouveau bénéficiaire';
}

if ($frequence > 5) {
    $scoreRegles += 10;
    $raisons[] = 'fréquence élevée';
}

if ($moyenne > 0 && $montant > 3 * $moyenne) {
    $scoreRegles += 15;
    $raisons[] = 'montant inhabituel';
}
if ($tentativesPin == 1) {
    $scoreRegles += 10;
    $raisons[] = 'une tentative PIN incorrecte avant validation';
}

if ($tentativesPin == 2) {
    $scoreRegles += 25;
    $raisons[] = 'deux tentatives PIN incorrectes avant validation';
}

if ($tentativesPin >= 3) {
    $scoreRegles += 45;
    $raisons[] = 'plusieurs tentatives PIN incorrectes avant validation';
}

$scoreFinal = ($scoreML * 0.6) + ($scoreRegles * 0.4);

$risk = $scoreFinal / 100;

if ($scoreFinal >= 75) {
    $decision = 'bloquée';
}

elseif ($scoreFinal >= 50) {
    $decision = 'à vérifier';
}

else {
    $decision = 'validée';
}

$messageRegles = !empty($raisons) ? implode(', ', $raisons) : 'aucune anomalie métier majeure';
$messageFinal = 'ML : ' . $messageML . ' | Règles : ' . $messageRegles;

$stmt = $pdo->prepare('
    INSERT INTO transactions
    (user_id, montant, type_transaction, lieu, beneficiaire, heure, frequence, temps_validation,
    solde_compte, anciennete_compte, montant_moyen, ecart_montant, tentatives_pin,
    beneficiaire_nouveau, jour_semaine, probabilite, resultat, explication, date_transaction)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
');

$stmt->execute([
    $userId, $montant, $type, $lieu, $beneficiaire, $heure, $frequence, $tempsValidation,
    $solde, $anciennete, $moyenne, $ecart, $tentativesPin,
    $benefNouveau, $jour, $risk, $decision, $messageFinal
]);

$transactionId = (int) $pdo->lastInsertId();

if ($decision === 'bloquée') {
    createFraudAlert($pdo, $transactionId, $risk, $messageFinal);
} else {
    $newSolde = $solde - $montant;

    $stmt = $pdo->prepare('UPDATE users SET solde_compte = ? WHERE id = ?');
    $stmt->execute([$newSolde, $userId]);
}

header('Location: ../../frontend/pages/transaction.php?done=1&risk=' . urlencode(round($scoreFinal, 2)) . '&decision=' . urlencode($decision));
exit;
