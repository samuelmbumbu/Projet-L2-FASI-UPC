const API_BASE = '../../backend-php/api/';
async function fetchJSON(endpoint){
  const res = await fetch(API_BASE + endpoint);
  return await res.json();
}
function badge(status){
  const cls = status === 'bloquée' ? 'danger' : 'ok';
  return `<span class="badge ${cls}">${status}</span>`;
}
function money(v){ return new Intl.NumberFormat('fr-FR',{style:'currency',currency:'USD'}).format(Number(v||0)); }
function risk(v){ return `${(Number(v||0)*100).toFixed(1)}%`; }
