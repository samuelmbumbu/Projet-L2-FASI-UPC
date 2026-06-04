<?php
header('Content-Type: application/json');
require_once '_auth.php';
require_once '../config/db.php';
[$where, $params] = adminFilter('t');
$stmt = $pdo->prepare("SELECT t.*, u.nom FROM transactions t JOIN users u ON u.id=t.user_id $where ORDER BY t.date_transaction DESC LIMIT 100");
$stmt->execute($params);
echo json_encode($stmt->fetchAll());
