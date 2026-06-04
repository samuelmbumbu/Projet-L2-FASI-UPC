<?php
header('Content-Type: application/json');
require_once '_auth.php';
require_once '../config/db.php';
$where = '';$params=[];
if (currentRole() !== 'admin') { $where = ' WHERE t.user_id = ? '; $params[] = currentUserId(); }
$sql = 'SELECT f.*, t.montant, t.type_transaction, t.resultat, u.nom, admin.nom AS traite_par_nom
        FROM fraud_logs f
        JOIN transactions t ON t.id=f.transaction_id
        JOIN users u ON u.id=t.user_id
        LEFT JOIN users admin ON admin.id=f.traite_par ' . $where . ' ORDER BY f.date_alerte DESC LIMIT 100';
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
echo json_encode($stmt->fetchAll());
