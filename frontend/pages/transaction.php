<?php require_once '../../backend-php/middleware/authMiddleware.php'; if(isAdmin()){ header('Location: dashboard.php'); exit; } ?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Transaction - SyAnFraud</title>
  <?php include '../components/header.php'; ?>
</head>
<body>

<div class="app-shell">

  <?php include '../components/sidebar.php'; ?>

  <main class="main">

    <div class="topbar">
      <div class="page-title">
        <h2>Nouvelle transaction</h2>
        <p>Simulation d’une opération analysée par le moteur anti-fraude</p>
      </div>
    </div>

    <?php if(isset($_GET['error'])): ?>
      <div class="result-box danger">
        <strong>Erreur :</strong>
        <?= htmlspecialchars($_GET['error']) ?>
      </div>
    <?php endif; ?>

    <?php if(isset($_GET['done'])): 
      $cls = ($_GET['decision'] ?? '') === 'bloquée' ? 'danger' : 'success';
    ?>
      <div class="result-box <?= $cls ?>">
        <strong>Résultat :</strong>
        Transaction <?= htmlspecialchars($_GET['decision']) ?>
        — Risque estimé : <?= htmlspecialchars($_GET['risk']) ?>%
      </div>
    <?php endif; ?>

    <section class="card">

      <form method="POST"
            action="../../backend-php/api/saveTransaction.php"
            id="transactionForm">

        <div class="form-grid">

          <div class="form-group">
            <label>Montant</label>
            <input type="number"
                   step="0.01"
                   name="montant"
                   placeholder="Ex : 2500"
                   required>
          </div>

          <div class="form-group">
            <label>Type de transaction</label>

            <select name="type_transaction">
              <option>PAYMENT</option>
              <option>TRANSFER</option>
              <option>CASH_OUT</option>
              <option>CASH_IN</option>
            </select>
          </div>

          <div class="form-group">
            <label>Lieu</label>

            <select name="lieu">
              <option>Kin</option>
              <option>Autre</option>
            </select>
          </div>

          <div class="form-group">
            <label>Bénéficiaire</label>

            <input type="text"
                   name="beneficiaire"
                   placeholder="Ex : M2044282225"
                   required>
          </div>

          <div class="form-group">
            <label>PIN</label>

            <input type="password"
                   name="pin"
                   id="pin_input"
                   placeholder="Entrer votre PIN"
                   required>

            <small class="hint">
              Le temps de validation et les tentatives sont calculés automatiquement.
            </small>
          </div>

          <input type="hidden"
                 name="temps_validation"
                 id="temps_validation"
                 value="0">

          <input type="hidden"
                 name="tentatives_pin"
                 id="tentatives_pin"
                 value="1">

        </div>

        <div class="actions">

          <button type="submit" class="btn btn-gold">
            <i class="fa-solid fa-shield-halved"></i>
            Effectuer la transaction
          </button>

          <button type="reset" class="btn btn-dark">
            Réinitialiser
          </button>

        </div>

      </form>

    </section>

    <br>

    <section class="card">

      <div class="section-title">
        <h3>Historique récent</h3>
      </div>

      <div class="table-wrap">

        <table class="data-transactions">

          <thead>
            <tr>
              <th>Client</th>
              <th>Montant</th>
              <th>Type</th>
              <th>Lieu</th>
              <th>Risque</th>
              <th>Statut</th>
              <th>Date</th>
            </tr>
          </thead>

          <tbody></tbody>

        </table>

      </div>

    </section>

  </main>

</div>

<script>

let debutPin = null;

const pinInput = document.getElementById('pin_input');
const form = document.getElementById('transactionForm');

pinInput.addEventListener('focus', function () {

    debutPin = Date.now();

});

form.addEventListener('submit', function () {

    if (debutPin !== null) {

        const temps = ((Date.now() - debutPin) / 1000).toFixed(2);

        document.getElementById('temps_validation').value = temps;

        console.log("Temps PIN :", temps, "secondes");
    }

});

</script>

<?php include '../components/footer.php'; ?>

</body>
</html>
