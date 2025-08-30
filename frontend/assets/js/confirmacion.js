// frontend/assets/js/confirmacion.js
import { formatCurrency } from './utils.js';

/**
 * Carga los datos de la confirmación del pedido desde la API.
 */
async function loadConfirmation() {
    // Obtenemos las referencias a los elementos del DOM que vamos a rellenar
    const orderIdEl = document.getElementById('orderId');
    const trackingEl = document.getElementById('tracking');
    const orderDateEl = document.getElementById('orderDate');
    const orderTotalEl = document.getElementById('orderTotal');
    const itemsListEl = document.getElementById('itemsList');
    const invoiceBtn = document.getElementById('invoiceBtn');
    const mainContent = document.querySelector('main');

    // Leemos el ID del pedido desde la URL (ej: confirmacion.php?id=6)
    const params = new URLSearchParams(location.search);
    const orderId = params.get('id');

    if (!orderId) {
        mainContent.innerHTML = '<div class="container my-5"><div class="alert alert-warning">No se especificó un número de pedido.</div></div>';
        return;
    }

    try {
        // Hacemos la llamada al mismo endpoint que usa la factura para obtener los detalles
        const response = await fetch(`../backend/api/pedidos/obtener.php?id=${orderId}`, {
            credentials: 'include' // Importante para enviar la cookie de sesión
        });

        const data = await response.json();
        if (!response.ok) {
            throw new Error(data.mensaje || 'No se pudo cargar la información del pedido.');
        }

        // Si la API responde con éxito, llenamos la página con los datos
        orderIdEl.textContent = data.orderId;
        trackingEl.textContent = data.tracking;
        orderDateEl.textContent = new Date(data.date).toLocaleDateString('es-CR');
        orderTotalEl.textContent = formatCurrency(data.total);

        // Creamos la lista de productos del pedido
        const ul = document.createElement('ul');
        ul.className = 'list-group list-group-flush';
        (data.items || []).forEach(it => {
            const li = document.createElement('li');
            li.className = 'list-group-item d-flex justify-content-between align-items-center px-0';
            const subtotal = (it.price != null) ? formatCurrency(it.price * it.qty) : '';
            li.innerHTML = `<span>${it.name} <span class="text-muted">× ${it.qty}</span></span><strong>${subtotal}</strong>`;
            ul.appendChild(li);
        });
        itemsListEl.innerHTML = ''; // Limpiamos cualquier contenido previo
        itemsListEl.appendChild(ul);

        // Hacemos que el botón "Ver factura" apunte a la factura correcta
        invoiceBtn.href = `factura.php?id=${data.orderId}`;

    } catch (err) {
        mainContent.innerHTML = `<div class="container my-5"><div class="alert alert-danger">Error: ${err.message}</div></div>`;
    }
}

// Ejecutamos la función cuando el HTML de la página esté listo
document.addEventListener('DOMContentLoaded', loadConfirmation);