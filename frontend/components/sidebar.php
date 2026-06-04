<?php
$current = basename($_SERVER['PHP_SELF']);

function active($file, $current) {
    return $file === $current ? 'active' : '';
}

$role = $_SESSION['role'] ?? 'client';
$nom = $_SESSION['nom'] ?? 'Utilisateur';

$photo = $_SESSION['photo_profil'] ?? 'default.png';

$photoPath = "/SyAnFraud/frontend/assets/profile/" . htmlspecialchars($photo);
?>

<aside class="sidebar">

  <div class="brand">
    <div class="brand-badge">
      <i class="fa-solid fa-shield-halved"></i>
    </div>

    <div>
      <h1>SyAn<span>Fraud</span></h1>
      <p>Sécurité financière prédictive</p>
    </div>
  </div>

  <nav class="nav">

    <a class="<?= active('dashboard.php',$current) ?>" href="dashboard.php">
      <i class="fa-solid fa-chart-line"></i>
      Dashboard
    </a>

    <?php if($role === 'admin'): ?>

      <a class="<?= active('users.php',$current) ?>" href="users.php">
        <i class="fa-solid fa-users-gear"></i>
        Comptes clients
      </a>

      <a class="<?= active('blocked.php',$current) ?>" href="blocked.php">
        <i class="fa-solid fa-ban"></i>
        Transactions bloquées
      </a>

      <a class="<?= active('flowmap.php',$current) ?>" href="flowmap.php">
        <i class="fa-solid fa-diagram-project"></i>
        Carte des flux
      </a>

      <a class="<?= active('analytics.php',$current) ?>" href="analytics.php">
        <i class="fa-solid fa-chart-pie"></i>
        Analytique
      </a>

    <?php else: ?>

      <a class="<?= active('transaction.php',$current) ?>" href="transaction.php">
        <i class="fa-solid fa-money-bill-transfer"></i>
        Transactions
      </a>

      <a class="<?= active('blocked.php',$current) ?>" href="blocked.php">
        <i class="fa-solid fa-ban"></i>
        Transactions bloquées
      </a>

      <a class="<?= active('alerts.php',$current) ?>" href="alerts.php">
        <i class="fa-solid fa-triangle-exclamation"></i>
        Alertes
      </a>

      <a class="<?= active('flowmap.php',$current) ?>" href="flowmap.php">
        <i class="fa-solid fa-diagram-project"></i>
        Carte des flux
      </a>

      <a class="<?= active('analytics.php',$current) ?>" href="analytics.php">
        <i class="fa-solid fa-chart-pie"></i>
        Analytique
      </a>

    <?php endif; ?>

  </nav>

  <div class="sidebar-footer">

    <a class="profile-link <?= active('profile.php',$current) ?>" href="profile.php">

      <img src="<?= $photoPath ?>" class="avatar" alt="Photo profil">

      <span><?= htmlspecialchars($nom) ?></span>

    </a>

    <a class="logout-link" href="../../backend-php/api/logout.php">
      <i class="fa-solid fa-right-from-bracket"></i>
      Déconnexion
    </a>

  </div>

</aside>