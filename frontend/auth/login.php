<?php
session_start();

if(isset($_SESSION['user_id'])){
    header('Location: ../pages/dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - SyAnFraud</title>

    <!-- Font Awesome -->
    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- CSS principal -->
    <link rel="stylesheet" href="../css/style.css">

    <!-- CSS login -->
    <link rel="stylesheet" href="../css/auth.css">
</head>

<body class="auth-body">
    <video autoplay muted loop playsinline class="bg-video">
        <source src="../assets/videos/bg1.mp4" type="video/mp4">
    </video>

<div class="video-overlay"></div>
    <a href="../pages/index.php" class="back-home">
        Accueil
    </a>
    <main class="auth-card">

        <div class="brand">

            <div class="brand-badge">
                <i class="fa-solid fa-shield-halved"></i>
            </div>

            <div>
                <h1>SyAn<span>Fraud</span></h1>
                
            </div>

        </div>

        <h2>Connectez-vous</h2>

        <p>
            

        
        </p>

        <?php if(isset($_GET['error'])): ?>
            <div class="alert error">
                Nom d’utilisateur ou mot de passe incorrect.
            </div>
        <?php endif; ?>

        <form method="POST" action="../../backend-php/api/login.php">

            <div class="form-group">
                <label>Nom d’utilisateur ou email</label>

                <input 
                    type="text"
                    name="login"
                    placeholder="Entrer votre identifiant"
                    required
                >
            </div>

            <br>

            <div class="form-group">
                <label>Mot de passe</label>

                <input 
                    type="password"
                    name="password"
                    placeholder="Entrer votre mot de passe"
                    required
                >
            </div>

            <div style="display:flex; justify-content:center; margin-top:25px;">

                <button type="submit" class="btn btn-gold">
                    <i class="fa-solid fa-lock"></i>
                    Se connecter
                </button>

            </div>

        </form>

    </main>
</body>
</html>
