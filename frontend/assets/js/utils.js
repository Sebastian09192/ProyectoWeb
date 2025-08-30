import { CONFIG } from './config.js';

/** Formatea un número como moneda */
export function formatCurrency(amount){
  try{
    return new Intl.NumberFormat(CONFIG.currencyLocale, { style:'currency', currency: CONFIG.currency }).format(amount);
  }catch(e){
    return `₡${amount.toFixed(0)}`;
  }
}

/** Escapa texto para evitar XSS al inyectar en el DOM */
export function escapeHTML(str){
  return String(str).replace(/[&<>"'`=\/]/g, s => ({
    '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;','/':'&#x2F;','`':'&#x60;','=':'&#x3D;'
  })[s]);
}

/** Genera un ID pseudo-único basado en tiempo + aleatorio (para demo frontend) */
export function generateId(prefix='ORD'){
  const ts = Date.now().toString(36);
  const rnd = Math.random().toString(36).slice(2,8);
  return `${prefix}-${ts}-${rnd}`.toUpperCase();
}

/** Luhn check para tarjetas */
export function luhnCheck(number){
  const sanitized = (number||'').replace(/\D/g,'');
  let sum = 0, shouldDouble = false;
  for(let i=sanitized.length - 1; i>=0; i--){
    let digit = parseInt(sanitized.charAt(i),10);
    if(shouldDouble){
      digit *= 2;
      if(digit > 9) digit -= 9;
    }
    sum += digit;
    shouldDouble = !shouldDouble;
  }
  return sanitized.length >= 13 && (sum % 10) === 0;
}

/** Valida fecha de expiración MM/AAAA no pasada */
export function validExpiry(mmYYYY){
  const m = /^\s*(\d{2})\/(\d{4})\s*$/.exec(mmYYYY||'');
  if(!m) return false;
  const mm = parseInt(m[1],10), yyyy = parseInt(m[2],10);
  if(mm<1 || mm>12) return false;
  const now = new Date();
  const lastDay = new Date(yyyy, mm, 0);
  return lastDay >= new Date(now.getFullYear(), now.getMonth(), 1);
}

/** Valida CVV 3-4 dígitos */
export function validCVV(cvv){
  return /^\d{3,4}$/.test(String(cvv||'').trim());
}

/** Carga lista de productos (desde backend si apiEnabled, si no, mock local) */
export async function loadProducts(){
  if (CONFIG.apiEnabled) {
    const url = (CONFIG.baseUrl || '') + CONFIG.endpoints.productos;
    const res = await fetch(url, { credentials: 'include' });
    if(!res.ok) throw new Error('No se pudo cargar el catálogo (API)');
    return res.json();
  } else {
    const res = await fetch('assets/js/data/products.json');
    if(!res.ok) throw new Error('No se pudo cargar el catálogo (mock)');
    return res.json();
  }
}
