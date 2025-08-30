// frontend/assets/js/filters.js

import { CartManager } from './CartManager.js';
import { escapeHTML, formatCurrency } from './utils.js';

const params = new URLSearchParams(location.search);

const searchEl = document.getElementById('search');
const catEl = document.getElementById('category');
const priceEl = document.getElementById('priceMax');
const grid = document.getElementById('productsGrid');
const info = document.getElementById('resultsInfo');

let all = [];
let filtered = [];

// Inicializa inputs con valores de la URL si existen
if (catEl && params.get('cat')) catEl.value = params.get('cat');
if (searchEl && params.get('q')) searchEl.value = params.get('q');
if (priceEl && params.get('priceMax')) priceEl.value = params.get('priceMax');

// Función para cargar productos desde la API
async function loadProductsFromAPI() {
  const API_URL = '../backend/api/productos/leer.php';
  try {
    const response = await fetch(API_URL);
    if (response.status === 404) return [];
    if (!response.ok) throw new Error('Error de red al cargar productos.');
    const data = await response.json();
    return data.productos || [];
  } catch (err) {
    console.error("Error en loadProductsFromAPI:", err);
    throw err;
  }
}

// Renderiza productos en el grid
function render() {
  grid.innerHTML = '';

  if (filtered.length === 0) {
    grid.innerHTML = `<div class="alert alert-warning col-12">No hay productos que coincidan con los filtros.</div>`;
    info.textContent = '0 resultado(s)';
    return;
  }

  filtered.forEach(p => {
    const col = document.createElement('div');
    col.className = 'col';
    col.innerHTML = `
      <div class="card h-100 shadow-sm">
        <img src="${escapeHTML(p.imagen_url || 'assets/img/placeholder.png')}" class="card-img-top" alt="${escapeHTML(p.nombre)}">
        <div class="card-body d-flex flex-column">
          <h5 class="card-title">${escapeHTML(p.nombre)}</h5>
          <p class="card-text text-muted small flex-grow-1">${escapeHTML(p.descripcion || '')}</p>
          <div class="mt-auto d-flex justify-content-between align-items-center">
            <strong>${formatCurrency(p.precio)}</strong>
            <button class="btn btn-sm btn-primary add-to-cart-btn" data-id="${escapeHTML(p.id)}"><i class="fa fa-cart-plus me-1"></i> Agregar</button>
          </div>
        </div>
      </div>`;
    grid.appendChild(col);
  });

  info.textContent = `${filtered.length} resultado(s)`;
}

// Actualiza la URL con los filtros sin recargar la página
function updateURL() {
  const url = new URL(window.location);
  const q = searchEl.value.trim();
  const cat = catEl.value.trim();
  const priceMax = priceEl.value.trim();

  if (q) url.searchParams.set('q', q);
  else url.searchParams.delete('q');

  if (cat) url.searchParams.set('cat', cat);
  else url.searchParams.delete('cat');

  if (priceMax) url.searchParams.set('priceMax', priceMax);
  else url.searchParams.delete('priceMax');

  window.history.replaceState({}, '', url);
}

// Aplica filtros de búsqueda, categoría y precio
function applyFilters() {
  const q = (searchEl.value || '').toLowerCase().trim();
  const cat = (catEl.value || '').toLowerCase().trim();
  const priceMax = parseFloat(priceEl.value || '0');

  filtered = all.filter(p => {
    const matchesQ = !q || p.nombre.toLowerCase().includes(q);
    const matchesCat = !cat || (p.categoria && p.categoria.toString().trim().toLowerCase() === cat);
    const matchesPrice = !priceMax || parseFloat(p.precio) <= priceMax;
    return matchesQ && matchesCat && matchesPrice;
  });

  updateURL();
  render();
}

// Vincula eventos a inputs y botones
function bindEvents() {
  searchEl?.addEventListener('input', applyFilters);
  catEl?.addEventListener('change', applyFilters);
  priceEl?.addEventListener('input', applyFilters);

  document.getElementById('clearFilters')?.addEventListener('click', () => {
    searchEl.value = '';
    catEl.value = '';
    priceEl.value = '';
    applyFilters();
  });

  grid.addEventListener('click', (e) => {
    const btn = e.target.closest('button.add-to-cart-btn');
    if (!btn) return;

    const id = btn.getAttribute('data-id');
    CartManager.add(id, 1);

    btn.classList.remove('btn-primary');
    btn.classList.add('btn-success');
    btn.innerHTML = '<i class="fa fa-check me-1"></i> Añadido';
    setTimeout(() => {
      if (btn) {
        btn.classList.add('btn-primary');
        btn.classList.remove('btn-success');
        btn.innerHTML = '<i class="fa fa-cart-plus me-1"></i> Agregar';
      }
    }, 1200);
  });
}

// Inicialización
(async function init() {
  try {
    all = await loadProductsFromAPI();
    filtered = all.slice();
    bindEvents();
    applyFilters(); // Aplica filtros iniciales (categoría, búsqueda, precio)
  } catch (err) {
    grid.innerHTML = `<div class="alert alert-danger col-12">Error al cargar productos. ${escapeHTML(err.message)}</div>`;
  }
})();
