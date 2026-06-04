<?php require_once '../../backend-php/middleware/authMiddleware.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard - SyAnFraud</title>
  <?php include '../components/header.php'; ?>
</head>
<body>
<div class="app-shell">
  <?php include '../components/sidebar.php'; ?>
  <main class="main">
    <div class="topbar">
      <div class="page-title">
        <h2>Dashboard</h2>
        <p><?= isAdmin() ? 'Vue globale combinée des clients' : 'Vue de votre activité transactionnelle' ?></p>
      </div>
      <div class="user-pill"><i class="fa-solid fa-circle-check" style="color:var(--gold)"></i> <?= isAdmin() ? 'Admin' : 'Client' ?> en ligne</div>
    </div>

    <section class="kpi-grid">
      <div class="card kpi"><div class="label">Total transactions</div><div class="value" id="kpi-total">0</div><div class="trend">activité suivie</div></div>
      <div class="card kpi"><div class="label">Fraudes détectées</div><div class="value" id="kpi-blocked">0</div><div class="trend" style="color:#fca5a5">alertes générées</div></div>
      <div class="card kpi"><div class="label">Taux de fraude</div><div class="value" id="kpi-rate">0%</div><div class="trend">rapport fraude / total</div></div>
      <div class="card kpi"><div class="label">Score moyen risque</div><div class="value" id="kpi-risk">0%</div><div class="trend">moyenne du scoring</div></div>
    </section>

    <section class="grid-2">
      <div class="card"><div class="section-title"><h3>Évolution des transactions</h3><span style="color:var(--muted)">7 derniers jours</span></div><div class="chart-box"><canvas id="transactionsLine"></canvas></div></div>
      <div class="card"><div class="section-title"><h3>Transactions par type</h3></div><div class="chart-box"><canvas id="typeBar"></canvas></div></div>
    </section><br>

    <section class="grid-2">
      <div class="card"><div class="section-title"><h3>Répartition fraude / normal</h3></div><div class="chart-box"><canvas id="fraudPie"></canvas></div></div>
      <div class="card"><div class="section-title"><h3>Dernières transactions</h3><?php if(!isAdmin()): ?><a href="transaction.php" style="color:var(--gold)">Nouvelle transaction</a><?php endif; ?></div><div class="table-wrap"><table id="recentTable"><thead><tr><th>Client</th><th>Montant</th><th>Type</th><th>Risque</th><th>Statut</th></tr></thead><tbody></tbody></table></div></div>
    </section>
  </main>
</div>
<?php include '../components/footer.php'; ?>
<script src="../js/charts.js"></script>
<?php include '../components/footer.php'; ?>
</body>
</html>
