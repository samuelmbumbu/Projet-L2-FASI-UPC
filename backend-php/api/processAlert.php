<?php
session_start();

require_once '../config/db.php';
require_once '_auth.php';

if (currentRole() !== 'admin') {
    header('Location: ../../frontend/pages/dashboard.php');
    exit;
}

$alertId = (int)($_POST['alert_id'] ?? 0);
$decision = $_POST['decision_admin'] ?? '';
$commentaire = trim($_POST['commentaire_admin'] ?? '');
$adminId = (int)($_SESSION['user_id'] ?? 0);

if ($alertId <= 0) {
    header('Location: ../../frontend/pages/blocked.php?error=alerte_invalide');
    exit;
}

/* récupérer la transaction liée à l’alerte */
$stmt = $pdo->prepare("
    SELECT transaction_id
    FROM fraud_logs
    WHERE id = ?
");
$stmt->execute([$alertId]);
$alert = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$alert) {
    header('Location: ../../frontend/pages/blocked.php?error=alerte_introuvable');
    exit;
}

$transactionId = (int)$alert['transaction_id'];

/* mise à jour de l’alerte */
$stmt = $pdo->prepare("
    UPDATE fraud_logs
    SET statut = 'traitée',
        decision_admin = ?,
        commentaire_admin = ?,
        date_traitement = NOW(),
        traite_par = ?
    WHERE id = ?
");
$stmt->execute([$decision, $commentaire, $adminId, $alertId]);

/* mise à jour du statut de la transaction */
if ($decision === 'fausse alerte') {
    $stmt = $pdo->prepare("
        UPDATE transactions
        SET resultat = 'validée',
            explication = CONCAT(explication, ' | Décision admin : fausse alerte')
        WHERE id = ?
    ");
    $stmt->execute([$transactionId]);
} else {
    $stmt = $pdo->prepare("
        UPDATE transactions
        SET resultat = 'bloquée',
            explication = CONCAT(explication, ' | Décision admin : fraude confirmée')
        WHERE id = ?
    ");
    $stmt->execute([$transactionId]);
}

header('Location: ../../frontend/pages/blocked.php?treated=1');
exit;
