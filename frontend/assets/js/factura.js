// frontend/assets/js/factura.js
import { formatCurrency } from './utils.js';

async function loadInvoice() {
    const mainContent = document.querySelector('main');
    const params = new URLSearchParams(location.search);
    const orderId = params.get('id');

    if (!orderId) {
        mainContent.innerHTML = '<div class="alert alert-danger">ID de factura no encontrado.</div>';
        return;
    }
    try {
        const response = await fetch(`../backend/api/pedidos/obtener.php?id=${orderId}`, { credentials: 'include' });
        const data = await response.json();
        if (!response.ok) throw new Error(data.mensaje);

        // --- ¡AQUÍ ESTÁ LA CORRECCIÓN! ---
        document.getElementById('billTo').textContent = data.billTo; // Mostramos el nombre del cliente

        document.getElementById('invDate').textContent = new Date(data.date).toLocaleDateString('es-CR');
        document.getElementById('invId').textContent = `FAC-${data.orderId}`;
        document.getElementById('invOrderId').textContent = data.orderId;
        document.getElementById('invTracking').textContent = data.tracking;

        const invItems = document.getElementById('invItems');
        invItems.innerHTML = '';
        (data.items || []).forEach(it => {
            const tr = `<tr><td>${it.name}</td><td class="text-center">${it.qty}</td><td class="text-end">${formatCurrency(it.price)}</td><td class="text-end">${formatCurrency(it.price * it.qty)}</td></tr>`;
            invItems.insertAdjacentHTML('beforeend', tr);
        });

        document.getElementById('invSubtotal').textContent = formatCurrency(data.subtotal);
        document.getElementById('invTax').textContent = formatCurrency(data.tax);
        document.getElementById('invShipping').textContent = formatCurrency(data.shipping);
        document.getElementById('invTotal').textContent = formatCurrency(data.total);

    } catch(err) {
        mainContent.innerHTML = `<div class="alert alert-danger">${err.message}</div>`;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    loadInvoice();
    const printButton = document.getElementById('print-btn');
    if(printButton) printButton.addEventListener('click', () => window.print());
    const downloadButton = document.getElementById('download-pdf-btn');
    if(downloadButton) downloadButton.addEventListener('click', () => window.print());
});