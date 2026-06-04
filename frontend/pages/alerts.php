<?php require_once '../../backend-php/middleware/authMiddleware.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Alertes - SyAnFraud</title><?php include '../components/header.php'; ?></head>
<body><div class="app-shell"><?php include '../components/sidebar.php'; ?><main class="main"><div class="page-title"><h2>Alertes</h2><p>Journal des événements suspects et décisions de traitement.</p></div><br><section class="card"><div class="table-wrap"><table id="alertsTable"><thead><tr><th>Client</th><th>Montant</th><th>Type</th><th>Niveau risque</th><th>Message</th><th>Statut / décision</th><th>Date</th></tr></thead><tbody></tbody></table></div></section></main></div><?php include '../components/footer.php'; ?></body></html>


