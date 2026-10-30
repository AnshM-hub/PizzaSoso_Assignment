document.getElementById('burger')?.addEventListener('click', () => document.getElementById('links').classList.toggle('open'));
const f = document.getElementById('cust');
if (f) {
  const calc = () => {
    let t = parseFloat(f.dataset.base);
    f.querySelectorAll('input[data-add]').forEach(i => { if (i.checked) t += parseFloat(i.dataset.add); });
    const q = Math.max(1, Math.min(20, parseInt(document.getElementById('qty').value) || 1));
    document.getElementById('total').textContent = (t * q).toFixed(2);
  };
  f.addEventListener('change', calc); f.addEventListener('input', calc);
}
