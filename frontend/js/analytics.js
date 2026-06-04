const cGold = '#facc15', cRed = '#ef4444', cGreen = '#22c55e';
document.addEventListener('DOMContentLoaded', async()=>{
  const d = await fetchJSON('getAnalytics.php');
  new Chart(document.getElementById('anaFraudPie'), {type:'doughnut', data:{labels:d.fraudNormal.map(x=>x.label), datasets:[{data:d.fraudNormal.map(x=>x.total), backgroundColor:[cGreen,cRed]}]}, options:{responsive:true,maintainAspectRatio:false}});
  new Chart(document.getElementById('anaTypeBar'), {type:'bar', data:{labels:d.typeFraud.map(x=>x.label), datasets:[{data:d.typeFraud.map(x=>x.total), backgroundColor:cGold, borderRadius:8}]}, options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}}}});
  new Chart(document.getElementById('anaHourLine'), {type:'line', data:{labels:d.riskByHour.map(x=>x.label+'h'), datasets:[{data:d.riskByHour.map(x=>x.total), borderColor:cGold, backgroundColor:'rgba(250,204,21,.18)', tension:.4, fill:true}]}, options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}}}});
});
