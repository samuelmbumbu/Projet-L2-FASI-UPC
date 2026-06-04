<?php
header('Content-Type: application/json');
require_once '_auth.php';
require_once '../config/db.php';
[$where, $params] = adminFilter('t');
$stmt = $pdo->prepare("SELECT resultat AS label, COUNT(*) AS total FROM transactions t $where GROUP BY resultat");
$stmt->execute($params); $fraudNormal = $stmt->fetchAll();
$stmt = $pdo->prepare("SELECT type_transaction AS label, SUM(resultat='bloquée') AS total FROM transactions t $where GROUP BY type_transaction");
$stmt->execute($params); $typeFraud = $stmt->fetchAll();
$stmt = $pdo->prepare("SELECT heure AS label, ROUND(AVG(probabilite)*100,2) AS total FROM transactions t $where GROUP BY heure ORDER BY heure");
$stmt->execute($params); $riskByHour = $stmt->fetchAll();
echo json_encode(['fraudNormal'=>$fraudNormal,'typeFraud'=>$typeFraud,'riskByHour'=>$riskByHour]);
