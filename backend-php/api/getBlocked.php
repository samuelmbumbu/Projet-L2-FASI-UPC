<?php
header('Content-Type: application/json');
require_once '_auth.php';
require_once '../config/db.php';
[$where, $params] = adminFilter('t');
$extra = ($where ? " AND" : " WHERE") . " t.resultat='bloquée'";
$sql = "SELECT t.*, u.nom, f.id AS alert_id, f.statut AS alerte_statut, f.decision_admin, f.commentaire_admin, f.date_traitement
        FROM transactions t
        JOIN users u ON u.id=t.user_id
        LEFT JOIN fraud_logs f ON f.transaction_id=t.id
        $where $extra
        ORDER BY t.date_transaction DESC LIMIT 100";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($rows as &$r) $r['can_treat'] = currentRole() === 'admin' && ($r['alerte_statut'] ?? '') !== 'traitée';
echo json_encode($rows);
