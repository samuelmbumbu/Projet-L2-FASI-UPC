<?php
function createFraudAlert(PDO $pdo, int $transactionId, float $risk, string $message): void {
    $stmt = $pdo->prepare('INSERT INTO fraud_logs (transaction_id, niveau_risque, message, statut, date_alerte) VALUES (?, ?, ?, "non traitée", NOW())');
    $stmt->execute([$transactionId, $risk, $message]);
}
