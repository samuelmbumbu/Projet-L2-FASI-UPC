document.addEventListener('DOMContentLoaded', async()=>{
  const rows = await fetchJSON('getTransactions.php');
  const box = document.getElementById('flowList');
  box.innerHTML = rows.slice(0,25).map(r=>`
    <div class="flow-item">
      <strong>${r.nom}</strong>
      <span class="flow-arrow">→</span>
      <span>${r.beneficiaire}</span>
      <span>${money(r.montant)}</span>
      ${badge(r.resultat)}
    </div>`).join('') || '<p>Aucun flux disponible.</p>';
});
