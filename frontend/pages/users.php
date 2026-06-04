<?php require_once '../../backend-php/middleware/authMiddleware.php'; requireAdmin(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Comptes clients - SyAnFraud</title>
  <?php include '../components/header.php'; ?>
</head>
<body>
<div class="app-shell">
  <?php include '../components/sidebar.php'; ?>
  <main class="main">
    <div class="page-title"><h2>Comptes clients</h2><p>Création et consultation des comptes clients par l’administrateur.</p></div><br>
    <?php if(isset($_GET['created'])): ?><div class="alert success">Compte client créé avec succès.</div><?php endif; ?>
    <?php if(isset($_GET['error'])): ?><div class="alert error">Impossible de créer le compte : nom d’utilisateur ou email déjà utilisé.</div><?php endif; ?>
    <section class="card">
      <h3>Créer un client</h3><br>
      <form method="POST" action="../../backend-php/api/createUser.php">
        <div class="form-grid">
          <div class="form-group"><label>Nom complet</label><input type="text" name="nom" required></div>
          <div class="form-group"><label>Nom d’utilisateur</label><input type="text" name="username" required></div>
          <div class="form-group"><label>Email</label><input type="email" name="email"></div>
          <div class="form-group"><label>Mot de passe initial</label><input type="text" name="password" placeholder="Ex : client123" required></div>
          <div class="form-group"><label>Solde initial</label><input type="number" step="0.01" name="solde" value="25000" required></div>
        </div>
        <div class="actions"><button class="btn btn-gold" type="submit"><i class="fa-solid fa-user-plus"></i> Créer le compte</button></div>
      </form>
    </section><br>
    <section class="card">
      <div class="section-title"><h3>Liste des clients</h3></div>
      <div class="table-wrap"><table id="usersTable"><thead><tr><th>Nom</th><th>Username</th><th>Email</th><th>Solde</th><th>Créé le</th></tr></thead><tbody></tbody></table></div>
    </section>
  </main>
</div>
<?php include '../components/footer.php'; ?>
</body>
</html>
