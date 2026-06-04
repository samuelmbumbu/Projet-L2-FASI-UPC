document.addEventListener('DOMContentLoaded', async () => {
  if (document.querySelector('.data-transactions')) loadTransactions('.data-transactions tbody');
  if (document.querySelector('#blockedTable')) loadBlocked();
  if (document.querySelector('#alertsTable')) loadAlerts();
  if (document.querySelector('#usersTable')) loadUsers();
  initPinTimer();
});

async function loadTransactions(selector) {
  const rows = await fetchJSON('getTransactions.php');
  const tbody = document.querySelector(selector);
  if (!tbody) return;
  tbody.innerHTML = rows.slice(0, 20).map(r => `<tr><td>${r.nom}</td><td>${money(r.montant)}</td><td>${r.type_transaction}</td><td>${r.lieu}</td><td>${risk(r.probabilite)}</td><td>${badge(r.resultat)}</td><td>${r.date_transaction}</td></tr>`).join('') || '<tr><td colspan="7">Aucune transaction.</td></tr>';
}

async function loadBlocked() {
  const rows = await fetchJSON('getBlocked.php');
  const body = document.querySelector('#blockedTable tbody');
  if (!body) return;
  body.innerHTML = rows.map(r => {
    const action = r.can_treat ? `
      <form method="POST" action="../../backend-php/api/processAlert.php" class="inline-form">
        <input type="hidden" name="alert_id" value="${r.alert_id || ''}">
        <select name="decision_admin">
          <option>fraude confirmée</option>
          <option>fausse alerte</option>
          <option>surveillance</option>
        </select>
        <input type="text" name="commentaire_admin" placeholder="Commentaire">
        <button class="btn-mini" type="submit">Traiter</button>
      </form>` : `<span class="badge warn">${r.alerte_statut || 'non traitée'}</span>${r.decision_admin ? '<br><small>' + r.decision_admin + '</small>' : ''}`;
    return `<tr><td>${r.nom}</td><td>${money(r.montant)}</td><td>${r.type_transaction}</td><td>${r.beneficiaire}</td><td>${risk(r.probabilite)}</td><td>${r.explication || ''}</td><td>${action}</td><td>${r.date_transaction}</td></tr>`;
  }).join('') || '<tr><td colspan="8">Aucune transaction bloquée.</td></tr>';
}

async function loadAlerts() {
  const rows = await fetchJSON('getAlerts.php');
  const body = document.querySelector('#alertsTable tbody');
  if (!body) return;
  body.innerHTML = rows.map(r => `<tr><td>${r.nom}</td><td>${money(r.montant)}</td><td>${r.type_transaction}</td><td>${risk(r.niveau_risque)}</td><td>${r.message || ''}${r.commentaire_admin ? '<br><small>Admin : ' + r.commentaire_admin + '</small>' : ''}</td><td><span class="badge warn">${r.statut}</span>${r.decision_admin ? '<br><small>' + r.decision_admin + '</small>' : ''}</td><td>${r.date_alerte}</td></tr>`).join('') || '<tr><td colspan="7">Aucune alerte.</td></tr>';
}

async function loadUsers() {
  const rows = await fetchJSON('getUsers.php');
  const body = document.querySelector('#usersTable tbody');
  if (!body) return;
  body.innerHTML = rows.map(r => `<tr><td>${r.nom}</td><td>${r.username}</td><td>${r.email || ''}</td><td>${money(r.solde_compte)}</td><td>${r.date_creation}</td></tr>`).join('') || '<tr><td colspan="5">Aucun client.</td></tr>';
}

async function loadProfilePage() {
  const data = await fetchJSON('getProfile.php');
  const nom = document.getElementById('profile_nom');
  const solde = document.getElementById('profile_solde');
  if (nom) nom.value = data.nom || '';
  if (solde) solde.value = money(data.solde_compte || 0);
}

function initPinTimer() {
  const form = document.getElementById('transactionForm');
  const pinInput = document.getElementById('pin_input');
  const tempsField = document.getElementById('temps_validation');
  const tentativesField = document.getElementById('tentatives_pin');
  if (!form || !pinInput || !tempsField || !tentativesField) return;

  let startTime = 0;
  pinInput.addEventListener('focus', () => { startTime = Date.now(); });

  form.addEventListener('submit', () => {
    const endTime = Date.now();
    const seconds = startTime ? Math.max(0.1, (endTime - startTime) / 1000) : 0;
    tempsField.value = seconds.toFixed(2);
    // La vraie logique des tentatives est calculée côté PHP via la session.
    // Ce champ reste à 1 quand la saisie part du formulaire.
    tentativesField.value = 1;
  });
}
