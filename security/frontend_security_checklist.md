# Checklist de Seguridad Frontend (Interfaz)

- [x] Validación en cliente para formularios (tarjeta, CVV, expiración, términos).
- [x] Sanitización / escape de texto antes de inyectar al DOM (previene XSS).
- [x] Sin `innerHTML` con datos no confiables (se usa escapeHTML o textContent).
- [x] Content Security Policy via `<meta http-equiv="Content-Security-Policy">` (scripts/estilos desde self + cdnjs/jsdelivr).
- [x] Uso de `localStorage` para carrito sin datos sensibles de pago.
- [x] HTTPS en hosting (GitHub Pages provee SSL automáticamente).

> Nota: El cifrado de contraseñas y protección de sesiones se realizan en el **backend**.
