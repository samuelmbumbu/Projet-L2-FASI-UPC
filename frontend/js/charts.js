const gold = '#facc15', goldSoft = 'rgba(250,204,21,.25)', red = '#ef4444', green = '#22c55e', grid = 'rgba(148,163,184,.15)', text = '#cbd5e1';
Chart.defaults.color = text; Chart.defaults.borderColor = grid;
function makeLine(id, labels, data){ new Chart(document.getElementById(id), {type:'line', data:{labels,datasets:[{label:'Transactions',data,borderColor:gold,backgroundColor:goldSoft,tension:.45,fill:true}]}, options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}}}}); }
function makeBar(id, labels, data){ new Chart(document.getElementById(id), {type:'bar', data:{labels,datasets:[{label:'Volume',data,backgroundColor:gold,borderRadius:8}]}, options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}}}}); }
function makePie(id, labels, data){ new Chart(document.getElementById(id), {type:'doughnut', data:{labels,datasets:[{data,backgroundColor:[green,red,gold]}]}, options:{responsive:true,maintainAspectRatio:false}}); }
document.addEventListener('DOMContentLoaded', async()=>{
  const d = await fetchJSON('getDashboard.php');
  document.getElementById('kpi-total').textContent = d.total;
  document.getElementById('kpi-blocked').textContent = d.blocked;
  document.getElementById('kpi-rate').textContent = d.fraud_rate + '%';
  document.getElementById('kpi-risk').textContent = d.avg_risk + '%';
  makeLine('transactionsLine', d.byDay.map(x=>x.label), d.byDay.map(x=>x.total));
  makeBar('typeBar', d.byType.map(x=>x.label), d.byType.map(x=>x.total));
  makePie('fraudPie', ['Validées','Bloquées'], [Math.max(d.total-d.blocked,0), d.blocked]);
  const tx = await fetchJSON('getTransactions.php');
  const body = document.querySelector('#recentTable tbody');
  if(body) body.innerHTML = tx.slice(0,8).map(r=>`<tr><td>${r.nom}</td><td>${money(r.montant)}</td><td>${r.type_transaction}</td><td>${risk(r.probabilite)}</td><td>${badge(r.resultat)}</td></tr>`).join('') || '<tr><td colspan="5">Aucune donnée.</td></tr>';
});
