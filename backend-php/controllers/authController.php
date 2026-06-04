<?php
function loginUser(PDO $pdo, string $login, string $password): bool {
    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ? OR email = ? LIMIT 1');
    $stmt->execute([$login, $login]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['mot_de_passe'])) {
        $_SESSION['user_id'] = (int)$user['id'];
        $_SESSION['nom'] = $user['nom'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['photo_profil'] = $user['photo_profil'] ?? 'default.png';
        return true;
    }
    return false;
}

function createClient(PDO $pdo, string $nom, string $username, string $email, string $password, float $solde): bool {
    $stmt = $pdo->prepare('SELECT id FROM users WHERE username = ? OR email = ? LIMIT 1');
    $stmt->execute([$username, $email]);
    if ($stmt->fetch()) return false;

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare('INSERT INTO users (nom, username, email, mot_de_passe, role, solde_compte, date_creation) VALUES (?, ?, ?, ?, "client", ?, NOW())');
    return $stmt->execute([$nom, $username, $email ?: null, $hash, $solde]);
}
