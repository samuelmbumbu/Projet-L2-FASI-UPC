<?php
function getClientStats(PDO $pdo, int $userId): array {
    $stmt = $pdo->prepare('SELECT COUNT(*) AS n, COALESCE(AVG(montant), 0) AS moyenne FROM transactions WHERE user_id = ?');
    $stmt->execute([$userId]);
    return $stmt->fetch() ?: ['n' => 0, 'moyenne' => 0];
}

function getRecentFrequency(PDO $pdo, int $userId): int {
    // Fréquence 24h : nombre de transactions du client sur les dernières 24 heures.
    $stmt = $pdo->prepare('SELECT COUNT(*) AS total FROM transactions WHERE user_id = ? AND date_transaction >= DATE_SUB(NOW(), INTERVAL 1 DAY)');
    $stmt->execute([$userId]);
    $row = $stmt->fetch();
    return (int)($row['total'] ?? 0);
}

function isNewBeneficiary(PDO $pdo, int $userId, string $beneficiary): int {
    $stmt = $pdo->prepare('SELECT id FROM transactions WHERE user_id = ? AND beneficiaire = ? LIMIT 1');
    $stmt->execute([$userId, $beneficiary]);
    return $stmt->fetch() ? 0 : 1;
}
