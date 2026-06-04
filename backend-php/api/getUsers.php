<?php
header('Content-Type: application/json');
require_once '_auth.php';
require_once '../config/db.php';
if (currentRole() !== 'admin') { echo json_encode([]); exit; }
$stmt = $pdo->query("SELECT id, nom, username, email, solde_compte, date_creation FROM users WHERE role='client' ORDER BY date_creation DESC");
echo json_encode($stmt->fetchAll());
