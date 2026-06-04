<?php
require_once '../../backend-php/middleware/authMiddleware.php';
require_once '../../backend-php/config/db.php';

$userId = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);

$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profil - SyAnFraud</title>

  <?php include '../components/header.php'; ?>
</head>

<body>

<div class="app-shell">

  <?php include '../components/sidebar.php'; ?>

  <main class="main">

    <div class="page-title">
      <h2>Profil utilisateur</h2>
      <p>Gérez votre nom, photo de profil et code PIN de transaction.</p>
    </div>

    <br>

    <?php if(isset($_GET['ok'])): ?>
      <div class="alert success">
        Profil mis à jour avec succès.
      </div>
    <?php endif; ?>

    <?php if(isset($_GET['error'])): ?>
      <div class="alert error">
        <?= htmlspecialchars($_GET['error']) ?>
      </div>
    <?php endif; ?>

    <section class="card profile-card" id="profileBox">

      <form 
        method="POST" 
        action="../../backend-php/api/updateProfile.php"
        enctype="multipart/form-data"
      >

        <div class="form-grid">

          <div class="form-group">
            <label>Nom complet</label>

            <input 
              type="text"
              name="nom"
              id="profile_nom"
              value="<?= htmlspecialchars($user['nom'] ?? '') ?>"
              required
            >
          </div>

          <div class="form-group">
            <label>Nouveau PIN</label>

            <input 
              type="password"
              name="pin"
              minlength="4"
              maxlength="8"
              placeholder="Ex : 1234"
            >

            <small class="hint">
              Laissez vide si vous ne voulez pas changer le PIN.
            </small>
          </div>

          <div class="form-group">
            <label>Photo de profil</label>

            <input 
              type="file"
              name="photo"
              accept="image/*"
            >
          </div>

          <div class="form-group">
            <label>Solde du compte</label>

            <input 
              type="text"
              id="profile_solde"
              value="<?= htmlspecialchars($user['solde_compte'] ?? '0') ?> $"
              disabled
            >
          </div>

        </div>

        <div class="actions">

          <button class="btn btn-gold" type="submit">
            <i class="fa-solid fa-user-check"></i>
            Enregistrer
          </button>

        </div>

      </form>

    </section>

  </main>

</div>

<?php include '../components/footer.php'; ?>

</body>
</html>
