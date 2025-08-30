import { CartManager } from './CartManager.js';
import { formatCurrency } from './utils.js';

const form = document.getElementById('cardForm');
const payBtn = document.getElementById('payBtn');

async function refreshSummary() {
    // La API de carrito ya nos devuelve todos los totales calculados
    const cart = await CartManager.fetchCart();

    if (!cart || !cart.items || cart.items.length === 0) {
        document.querySelector('main').innerHTML = `<div class="alert alert-warning">Tu carrito está vacío. <a href="products.php">Vuelve al catálogo</a> para añadir productos.</div>`;
        return;
    }

    const totalItems = cart.items.reduce((sum, item) => sum + parseInt(item.cantidad), 0);

    // NUEVO CÓDIGO: Usamos directamente los valores del backend
    document.getElementById('summaryItems').textContent = totalItems;
    document.getElementById('summarySubtotal').textContent = formatCurrency(cart.subtotal);
    document.getElementById('summaryTax').textContent = formatCurrency(cart.impuesto);
    document.getElementById('summaryShipping').textContent = formatCurrency(cart.envio);
    document.getElementById('summaryTotal').textContent = formatCurrency(cart.total);
}

form?.addEventListener('submit', async (e) => {
    e.preventDefault();
    payBtn.disabled = true;
    payBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Procesando...';

    // Objeto de datos COMPLETO que se enviará al backend
    const paymentData = {
        direccion_envio: document.getElementById('direccion_envio')?.value || "No especificada",
        metodo_pago: document.querySelector('input[name="payMethod"]:checked').value,
        terms: document.getElementById('terms').checked
    };

    try {
        const response = await fetch('../backend/api/pago/procesar_pago.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            credentials: 'include',
            body: JSON.stringify(paymentData)
        });
        const result = await response.json();
        if (!response.ok) throw new Error(result.mensaje);

        window.location.href = `confirmacion.php?id=${result.pedido_id}`;

    } catch (err) {
        alert('Error: ' + err.message);
        payBtn.disabled = false;
        payBtn.innerHTML = '<i class="fa fa-credit-card me-1"></i> Pagar ahora';
    }
});

document.addEventListener('DOMContentLoaded', refreshSummary);