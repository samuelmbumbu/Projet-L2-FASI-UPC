<?php
session_start();
require_once '../config/db.php';
require_once '../controllers/authController.php';
if (($_SESSION['role'] ?? '') !== 'admin') { header('Location: ../../frontend/pages/dashboard.php'); exit; }

$nom = trim($_POST['nom'] ?? '');
$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$solde = (float)($_POST['solde'] ?? 25000);

if ($nom === '' || $username === '' || strlen($password) < 4) {
    header('Location: ../../frontend/pages/users.php?error=1'); exit;
}

$ok = createClient($pdo, $nom, $username, $email, $password, $solde);
header('Location: ../../frontend/pages/users.php?' . ($ok ? 'created=1' : 'error=1'));
exit;
