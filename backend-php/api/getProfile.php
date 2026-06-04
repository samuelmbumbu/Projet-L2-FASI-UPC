<?php
header('Content-Type: application/json');
require_once '_auth.php';
require_once '../config/db.php';
$stmt = $pdo->prepare('SELECT id, nom, username, email, solde_compte, photo_profil, pin_hash FROM users WHERE id=?');
$stmt->execute([currentUserId()]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if ($user) $user['pin_configure'] = !empty($user['pin_hash']);
unset($user['pin_hash']);
echo json_encode($user ?: []);
