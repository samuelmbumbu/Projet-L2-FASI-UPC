<?php
header('Content-Type: application/json');
require_once '_auth.php';
require_once '../config/db.php';
[$where, $params] = adminFilter('t');

$stmt = $pdo->prepare("SELECT COUNT(*) FROM transactions t $where");
$stmt->execute($params); $total = (int)$stmt->fetchColumn();
$stmt = $pdo->prepare("SELECT COUNT(*) FROM transactions t $where" . ($where ? " AND" : " WHERE") . " t.resultat='bloquée'");
$stmt->execute($params); $blocked = (int)$stmt->fetchColumn();
$stmt = $pdo->prepare("SELECT COALESCE(AVG(probabilite),0) FROM transactions t $where");
$stmt->execute($params); $avgRisk = (float)$stmt->fetchColumn();
$stmt = $pdo->prepare("SELECT type_transaction AS label, COUNT(*) AS total FROM transactions t $where GROUP BY type_transaction");
$stmt->execute($params); $byType = $stmt->fetchAll();
$stmt = $pdo->prepare("SELECT DATE(date_transaction) AS label, COUNT(*) AS total FROM transactions t $where GROUP BY DATE(date_transaction) ORDER BY label LIMIT 7");
$stmt->execute($params); $byDay = $stmt->fetchAll();

echo json_encode([
  'total'=>$total,
  'blocked'=>$blocked,
  'fraud_rate'=>$total ? round($blocked/$total*100,2) : 0,
  'avg_risk'=>round($avgRisk*100,2),
  'byType'=>$byType,
  'byDay'=>$byDay
]);
