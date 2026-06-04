<?php
session_start();

require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../frontend/auth/login.php');
    exit;
}

$userId = $_SESSION['user_id'];

$nom = trim($_POST['nom'] ?? '');
$pin = trim($_POST['pin'] ?? '');

$photoName = null;

/*
|--------------------------------------------------------------------------
| Upload photo
|--------------------------------------------------------------------------
*/

if(isset($_FILES['photo']) && $_FILES['photo']['error'] === 0){

    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/SyAnFraud/frontend/assets/profile/';

    if(!is_dir($uploadDir)){
        mkdir($uploadDir, 0777, true);
    }

    $extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);

    $photoName = 'profile_' . time() . '.' . $extension;

    move_uploaded_file(
        $_FILES['photo']['tmp_name'],
        $uploadDir . $photoName
    );
}

/*
|--------------------------------------------------------------------------
| Construction requête SQL
|--------------------------------------------------------------------------
*/

$sql = "UPDATE users SET nom = :nom";

$params = [
    ':nom' => $nom,
    ':id' => $userId
];

/*
|--------------------------------------------------------------------------
| Mise à jour PIN
|--------------------------------------------------------------------------
*/

if(!empty($pin)){
    $sql .= ", pin_hash = :pin";
    $params[':pin'] = password_hash($pin, PASSWORD_DEFAULT);
}

/*
|--------------------------------------------------------------------------
| Mise à jour photo
|--------------------------------------------------------------------------
*/

if($photoName){
    $sql .= ", photo_profil = :photo";
    $params[':photo'] = $photoName;

    $_SESSION['photo_profil'] = $photoName;
}

$sql .= " WHERE id = :id";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

/*
|--------------------------------------------------------------------------
| Mise à jour session
|--------------------------------------------------------------------------
*/

$_SESSION['nom'] = $nom;

header('Location: ../../frontend/pages/profile.php?ok=1');
exit;
?>