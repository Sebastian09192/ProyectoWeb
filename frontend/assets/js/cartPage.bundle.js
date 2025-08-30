// === Carrito “bundle” sin imports (compatible con tu CSP) ===
(function () {
  // Config de totales
  const CONFIG = {
    currency: 'CRC',
    currencyLocale: 'es-CR',
    taxRate: 0.13,
    shippingFlat: 2500,
    freeShippingOver: 50000,
  };

  // Utils
  function formatCurrency(amount) {
    try {
      return new Intl.NumberFormat(CONFIG.currencyLocale, {
        style: 'currency', currency: CONFIG.currency
      }).format(+amount || 0);
    } catch {
      return `₡${(+amount || 0).toFixed(0)}`;
    }
  }
  function escapeHTML(str) {
    return String(str).replace(/[&<>"'`=\\/]/g, s => ({
      '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;','/':'&#x2F;','`':'&#x60;','=':'&#x3D;'
    })[s]);
  }

  // Capa de datos (localStorage)
  const Cart = {
    load() {
      try {
        const raw = localStorage.getItem('cart');
        const arr = raw ? JSON.parse(raw) : [];
        return Array.isArray(arr) ? arr : [];
      } catch { return []; }
    },
    save(items) { localStorage.setItem('cart', JSON.stringify(items || [])); },
    totalItems(items) { return (items || []).reduce((a,b)=>a+(+b.qty||0),0); },
    compute(items){
      const subtotal=(items||[]).reduce((a,b)=>a+(+b.price||0)*(+b.qty||0),0);
      const tax=Math.round(subtotal*CONFIG.taxRate);
      const shipping=(subtotal===0||subtotal>=CONFIG.freeShippingOver)?0:CONFIG.shippingFlat;
      return { subtotal, tax, shipping, total: subtotal+tax+shipping };
    },
    inc(items,id){ const it=items.find(i=>i.id===id); if(it) it.qty=(+it.qty||1)+1; },
    dec(items,id){ const it=items.find(i=>i.id===id); if(it) it.qty=Math.max(1,(+it.qty||1)-1); },
    setQty(items,id,qty){ const it=items.find(i=>i.id===id); if(it) it.qty=Math.max(1,parseInt(qty,10)||1); },
    remove(items,id){ const i=items.findIndex(x=>x.id===id); if(i>=0) items.splice(i,1); }
  };

  // DOM refs
  const tbody = document.getElementById('cartTableBody');
  const subtotalEl = document.getElementById('subtotal');
  const taxEl = document.getElementById('tax');
  const shipEl = document.getElementById('shipping');
  const totalEl = document.getElementById('total');
  const badge = document.getElementById('cart-count');
  const yearEl = document.getElementById('year');

  function render(){
    const items = Cart.load();

    // tabla
    if (tbody){
      tbody.innerHTML = '';
      items.forEach(it=>{
        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td>
            <div class="d-flex align-items-center gap-2">
              <img src="${escapeHTML(it.image||'')}" alt="" class="rounded"
                   style="width:48px;height:48px;object-fit:cover"
                   onerror="this.src='assets/img/placeholder.png'">
              <div>${escapeHTML(it.name||'')}</div>
            </div>
          </td>
          <td class="text-center">
            <div class="btn-group" role="group" aria-label="qty">
              <button class="btn btn-sm btn-outline-secondary" data-action="dec" data-id="${escapeHTML(it.id)}">-</button>
              <input class="form-control form-control-sm text-center d-inline-block" style="width:60px"
                     value="${Number(it.qty)||1}" data-action="qty" data-id="${escapeHTML(it.id)}">
              <button class="btn btn-sm btn-outline-secondary" data-action="inc" data-id="${escapeHTML(it.id)}">+</button>
            </div>
          </td>
          <td class="text-end">${formatCurrency(Number(it.price)||0)}</td>
          <td class="text-end">${formatCurrency((Number(it.price)||0)*(Number(it.qty)||0))}</td>
          <td class="text-end">
            <button class="btn btn-sm btn-outline-danger" data-action="del" data-id="${escapeHTML(it.id)}">
              <i class="fa fa-trash"></i>
            </button>
          </td>`;
        tbody.appendChild(tr);
      });

      // delegación: inc/dec/qty/del
      tbody.onclick = (e)=>{
        const btn = e.target.closest('button[data-action]');
        const inp = e.target.closest('input[data-action="qty"]');
        let list = Cart.load();
        if(btn){
          const id = btn.getAttribute('data-id');
          const act = btn.getAttribute('data-action');
          if(act==='inc') Cart.inc(list,id);
          if(act==='dec') Cart.dec(list,id);
          if(act==='del') Cart.remove(list,id);
          Cart.save(list); render(); return;
        }
        if(inp){
          const id = inp.getAttribute('data-id');
          const q = parseInt(inp.value||'1',10);
          Cart.setQty(list,id,isNaN(q)?1:q);
          Cart.save(list); render();
        }
      };
    }

    // totales
    const t = Cart.compute(items);
    if (subtotalEl) subtotalEl.textContent = formatCurrency(t.subtotal);
    if (taxEl) taxEl.textContent = formatCurrency(t.tax);
    if (shipEl) shipEl.textContent = formatCurrency(t.shipping);
    if (totalEl) totalEl.textContent = formatCurrency(t.total);

    // badge + año footer
    if (badge) badge.textContent = Cart.totalItems(items).toString();
    if (yearEl) yearEl.textContent = new Date().getFullYear();
  }

  // Re-render si cambia desde otra pestaña
  window.addEventListener('storage', render);

  // Cargar al entrar
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', render);
  } else {
    render();
  }
})();
