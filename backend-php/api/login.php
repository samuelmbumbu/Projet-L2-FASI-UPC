<?php
session_start();
require_once '../config/db.php';
require_once '../controllers/authController.php';

$login = trim($_POST['login'] ?? $_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (loginUser($pdo, $login, $password)) {
    header('Location: ../../frontend/pages/dashboard.php');
} else {
    header('Location: ../../frontend/auth/login.php?error=1');
}
exit;
