<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: pages/dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - SyAnFraud</title>

    <link rel="stylesheet" href="../css/home.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body class="home-body">

<video autoplay muted loop playsinline class="bg-video">
    <source src="../assets/videos/bg1.mp4" type="video/mp4">
</video>

<div class="overlay"></div>

<header class="home-header">
    <div class="logo">
        <i class="fa-solid fa-shield-halved"></i>
        <h1>SyAn<span>Fraud</span></h1>
    </div>

    <nav>
        <a href="../auth/login.php">Connexion</a>
    </nav>
</header>

<main class="hero">
    <div class="hero-content">

        <span class="badge">
            <i class="fa-solid fa-lock"></i>
            Sécurité financière prédictive
        </span>

        <h2>Système intelligent de détection de fraude financière</h2>

        <p>
            SyAnFraud combine Machine Learning et règles métier afin d’analyser
            les transactions, détecter les comportements suspects et renforcer
            la sécurité des opérations financières.
        </p>

        <div class="hero-buttons">
            <a href="../auth/login.php" class="btn-primary">
                <i class="fa-solid fa-right-to-bracket"></i>
                Commencer
            </a>

        </div>

    </div>
</main>

<section class="features" id="features">

    <div class="feature-card">
        <i class="fa-solid fa-brain"></i>
        <h3>Machine Learning</h3>
        <p>Analyse prédictive du risque de fraude à partir des données transactionnelles.</p>
    </div>

    <div class="feature-card">
        <i class="fa-solid fa-scale-balanced"></i>
        <h3>Règles métier</h3>
        <p>Renforcement du score par des règles liées au PIN, au montant et à la fréquence.</p>
    </div>

    <div class="feature-card">
        <i class="fa-solid fa-chart-line"></i>
        <h3>Scoring hybride</h3>
        <p>Fusion pondérée entre le score ML et les règles métier pour décider du blocage.</p>
    </div>

</section>

<footer class="home-footer">
    Projet académique UPC FASI - Tous droits réservés.

</footer>

</body>
</html>