export const CONFIG = {
  currency: 'CRC',
  currencyLocale: 'es-CR',
  taxRate: 0.13,
  shippingFlat: 2500,
  freeShippingOver: 50000,

  // ===== Integración Backend =====
  apiEnabled: false,              // <--- pon en true cuando el backend esté listo
  baseUrl: '',                   // '' = mismo dominio (XAMPP). Ej: 'https://tu-dominio.com'
  endpoints: {
    productos: '/api/productos',               // GET lista productos
    checkout: '/api/checkout',                 // POST crear orden
    pedido:   (id) => `/api/pedidos/${id}`,    // GET detalle de pedido
    factura:  (id) => `/api/factura/${id}`     // GET detalle de factura (JSON)
  }
};
