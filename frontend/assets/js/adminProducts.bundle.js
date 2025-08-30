// Admin de productos (CRUD) con 2 modos: API real o Demo (localStorage)
(function(){
  // Lee config del proyecto si existe, si no define mínimos
  let CONFIG = { apiEnabled:false, baseUrl:'', endpoints:{
    productos:'/api/productos'
  }, currency:'CRC', currencyLocale:'es-CR' };
  try{ // intenta importar en runtime
    // no usamos modules; solo detectamos flags si están en window.__CONFIG
    if (window.__CONFIG) CONFIG = Object.assign(CONFIG, window.__CONFIG);
  }catch{}

  function fmt(n){
    try{ return new Intl.NumberFormat(CONFIG.currencyLocale,{style:'currency',currency:CONFIG.currency}).format(+n||0); }
    catch{ return `₡${(+n||0).toFixed(0)}`; }
  }
  const $ = sel => document.querySelector(sel);
  const tbody = $('#prodTableBody');
  const btnNew = $('#btnNew');
  const modeLabel = $('#modeLabel');

  // Modal y campos
  const modalEl = $('#prodModal');
  const modal = new bootstrap.Modal(modalEl);
  const form = $('#prodForm');
  const f_id = $('#f_id'), f_name = $('#f_name'), f_desc = $('#f_desc'),
        f_price = $('#f_price'), f_cat = $('#f_cat'), f_img = $('#f_img');
  let editingId = null;

  // ====== Fuente de datos ======
  const Store = {
    async list(){
      if (CONFIG.apiEnabled){
        const url = (CONFIG.baseUrl||'') + CONFIG.endpoints.productos;
        const res = await fetch(url, {credentials:'include'});
        if(!res.ok) throw new Error('No se pudo obtener productos (API)');
        return res.json();
      }else{
        // DEMO: usa localStorage "admin_products". Si está vacío, siembra con JSON mock si existe.
        const key = 'admin_products';
        const raw = localStorage.getItem(key);
        if (raw) return JSON.parse(raw);
        // intenta leer mock (opcional)
        try{
          const mock = await fetch('assets/js/data/products.json');
          const arr = await mock.json();
          localStorage.setItem(key, JSON.stringify(arr));
          return arr;
        }catch{
          const arr = [];
          localStorage.setItem(key, JSON.stringify(arr));
          return arr;
        }
      }
    },
    async create(p){
      if (CONFIG.apiEnabled){
        const url=(CONFIG.baseUrl||'')+CONFIG.endpoints.productos;
        const res=await fetch(url,{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify(p),credentials:'include'});
        if(!res.ok) throw new Error('No se pudo crear (API)');
        return res.json();
      }else{
        const key='admin_products';
        const arr=await Store.list();
        if (arr.some(x=>x.id===p.id)) throw new Error('ID ya existe');
        arr.push(p); localStorage.setItem(key, JSON.stringify(arr)); return p;
      }
    },
    async update(id, p){
      if (CONFIG.apiEnabled){
        const url=(CONFIG.baseUrl||'')+CONFIG.endpoints.productos+'/'+encodeURIComponent(id);
        const res=await fetch(url,{method:'PUT',headers:{'Content-Type':'application/json'},body:JSON.stringify(p),credentials:'include'});
        if(!res.ok) throw new Error('No se pudo actualizar (API)');
        return res.json();
      }else{
        const key='admin_products';
        const arr=await Store.list();
        const i=arr.findIndex(x=>x.id===id);
        if(i<0) throw new Error('No encontrado');
        arr[i]=Object.assign({}, arr[i], p); localStorage.setItem(key, JSON.stringify(arr)); return arr[i];
      }
    },
    async remove(id){
      if (CONFIG.apiEnabled){
        const url=(CONFIG.baseUrl||'')+CONFIG.endpoints.productos+'/'+encodeURIComponent(id);
        const res=await fetch(url,{method:'DELETE',credentials:'include'});
        if(!res.ok) throw new Error('No se pudo eliminar (API)');
        return true;
      }else{
        const key='admin_products';
        const arr=await Store.list();
        const out=arr.filter(x=>x.id!==id);
        localStorage.setItem(key, JSON.stringify(out));
        return true;
      }
    }
  };

  // ====== UI ======
  async function render(){
    try{
      modeLabel.textContent = CONFIG.apiEnabled ? 'API real' : 'Demo local';
      const data = await Store.list();
      tbody.innerHTML = '';
      data.forEach(p=>{
        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td>${p.id}</td>
          <td>${p.name}</td>
          <td>${p.category||''}</td>
          <td class="text-end">${fmt(p.price)}</td>
          <td><img src="${p.image||'assets/img/placeholder.png'}" alt="" style="width:48px;height:36px;object-fit:cover" onerror="this.src='assets/img/placeholder.png'"></td>
          <td class="text-end">
            <button class="btn btn-sm btn-outline-primary me-1" data-act="edit" data-id="${p.id}"><i class="fa fa-pen"></i></button>
            <button class="btn btn-sm btn-outline-danger" data-act="del" data-id="${p.id}"><i class="fa fa-trash"></i></button>
          </td>
        `;
        tbody.appendChild(tr);
      });
    }catch(err){
      tbody.innerHTML = `<tr><td colspan="6"><div class="alert alert-danger">Error: ${err.message}</div></td></tr>`;
    }
  }

  function openNew(){
    editingId = null; form.reset();
    $('#modalTitle').textContent = 'Nuevo producto';
    f_id.removeAttribute('readonly');
    modal.show();
  }
  async function openEdit(id){
    const data = await Store.list();
    const p = data.find(x=>x.id===id); if(!p) return;
    editingId = id;
    $('#modalTitle').textContent = `Editar: ${id}`;
    f_id.value = p.id; f_id.setAttribute('readonly','readonly');
    f_name.value = p.name||''; f_desc.value = p.description||'';
    f_price.value = p.price||0; f_cat.value = p.category||'';
    f_img.value = p.image||'';
    modal.show();
  }

  // Eventos
  btnNew.addEventListener('click', openNew);

  tbody.addEventListener('click', async (e)=>{
    const btn = e.target.closest('button[data-act]');
    if(!btn) return;
    const id = btn.getAttribute('data-id');
    const act = btn.getAttribute('data-act');
    if (act==='edit') return openEdit(id);
    if (act==='del'){
      if (!confirm('¿Eliminar este producto?')) return;
      try{ await Store.remove(id); await render(); alert('Producto eliminado'); }
      catch(err){ alert('Error: '+err.message); }
    }
  });

  form.addEventListener('submit', async (e)=>{
    e.preventDefault();
    const payload = {
      id: f_id.value.trim(),
      name: f_name.value.trim(),
      description: f_desc.value.trim(),
      price: Number(f_price.value||0),
      category: f_cat.value,
      image: f_img.value.trim() || 'assets/img/placeholder.png'
    };
    try{
      if (editingId){
        await Store.update(editingId, payload);
        alert('Producto actualizado');
      }else{
        await Store.create(payload);
        alert('Producto creado');
      }
      modal.hide(); await render();
    }catch(err){
      alert('Error: '+err.message);
    }
  });

  // Init
  if (document.readyState==='loading'){
    document.addEventListener('DOMContentLoaded', render);
  } else {
    render();
  }
})();
