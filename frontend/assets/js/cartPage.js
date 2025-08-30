// frontend/assets/js/cartPage.js
import { CartManager } from './CartManager.js';
import { formatCurrency } from './utils.js';

// Obtenemos las referencias a los elementos HTML que vamos a manipular
const tableBody = document.getElementById('cartTableBody');
const subtotalEl = document.getElementById('subtotal');
const taxEl = document.getElementById('tax');
const shippingEl = document.getElementById('shipping');
const totalEl = document.getElementById('total');
const checkoutBtn = document.getElementById('checkoutBtn');

/**
 * Dibuja la tabla del carrito y los totales a partir de los datos de la API.
 */
async function renderCart() {
    if (!tableBody) return; // Si no estamos en la página del carrito, no hacer nada.

    const cartData = await CartManager.fetchCart();

    // Si el carrito no existe o está vacío
    if (!cartData || !cartData.items || cartData.items.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="5" class="text-center py-4">Tu carrito está vacío.</td></tr>';
        subtotalEl.textContent = '₡0.00';
        taxEl.textContent = '₡0.00';
        shippingEl.textContent = '₡0.00';
        totalEl.textContent = '₡0.00';
        checkoutBtn.classList.add('disabled');
        return;
    }

    tableBody.innerHTML = ''; // Limpiar la tabla antes de dibujar
    
    cartData.items.forEach(item => {
        const itemSubtotal = parseFloat(item.precio) * parseInt(item.cantidad, 10);
        
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>
                <div class="d-flex align-items-center">
                    <img src="${item.imagen_url || 'assets/img/placeholder.png'}" alt="${item.nombre}" 
                            style="width: 50px; height: 50px; object-fit: cover;" class="me-3 rounded">
                    <div>${item.nombre}</div>
                </div>
            </td>
            <td>
                <div class="d-flex justify-content-center align-items-center">
                    <button class="btn btn-sm btn-outline-secondary decrease-btn" 
                            data-product-id="${item.producto_id}" ${item.cantidad <= 1 ? 'disabled' : ''}>
                        <i class="fa fa-minus"></i>
                    </button>
                    <span class="mx-2 quantity-display">${item.cantidad}</span>
                    <button class="btn btn-sm btn-outline-secondary increase-btn" 
                            data-product-id="${item.producto_id}">
                        <i class="fa fa-plus"></i>
                    </button>
                </div>
            </td>
            <td class="text-end">₡${parseFloat(item.precio).toFixed(2)}</td>
            <td class="text-end item-subtotal">₡${itemSubtotal.toFixed(2)}</td>
            <td class="text-end">
                <button class="btn btn-sm btn-outline-danger remove-btn" data-product-id="${item.producto_id}" 
                        title="Eliminar producto">
                    <i class="fa fa-trash"></i>
                </button>
            </td>
        `;
        tableBody.appendChild(row);
    });

    // Ahora, usa los valores calculados por el backend
    subtotalEl.textContent = `₡${cartData.subtotal}`;
    taxEl.textContent = `₡${cartData.impuesto}`;
    shippingEl.textContent = `₡${cartData.envio}`;
    totalEl.textContent = `₡${cartData.total}`;
    checkoutBtn.classList.remove('disabled');
}

/**
 * Actualiza la cantidad de un producto en el carrito
 * @param {string} productId - ID del producto
 * @param {number} newQuantity - Nueva cantidad
 */
async function updateProductQuantity(productId, newQuantity) {
    if (newQuantity < 1) {
        return await CartManager.remove(productId);
    }
    
    const success = await CartManager.updateQuantity(productId, newQuantity);
    return success;
}

/**
 * Actualiza la visualización de un producto específico en la tabla
 * @param {HTMLElement} row - Fila de la tabla
 * @param {number} newQuantity - Nueva cantidad
 * @param {number} price - Precio unitario
 */
function updateRowDisplay(row, newQuantity, price) {
    const quantityDisplay = row.querySelector('.quantity-display');
    quantityDisplay.textContent = newQuantity;
    
    const newSubtotal = price * newQuantity;
    const subtotalCell = row.querySelector('.item-subtotal');
    subtotalCell.textContent = `₡${newSubtotal.toFixed(2)}`;
    
    const decreaseBtn = row.querySelector('.decrease-btn');
    decreaseBtn.disabled = newQuantity <= 1;
}

// --- Manejo de Eventos ---

tableBody.addEventListener('click', async (event) => {
    const target = event.target;
    const button = target.closest('button');
    
    if (!button) return;
    
    const productId = button.getAttribute('data-product-id');
    const row = button.closest('tr');
    const priceText = row.querySelector('td:nth-child(3)').textContent;
    const price = parseFloat(priceText.replace('₡', '').replace(',', ''));
    let currentQuantity = parseInt(row.querySelector('.quantity-display').textContent, 10);
    
    if (button.classList.contains('remove-btn')) {
        const success = await CartManager.remove(productId);
        if (success) {
            await renderCart();
            await CartManager.updateCartCount();
        }
    } 
    else if (button.classList.contains('increase-btn')) {
        const newQuantity = currentQuantity + 1;
        const success = await updateProductQuantity(productId, newQuantity);
        if (success) {
            updateRowDisplay(row, newQuantity, price);
            await renderCart(); // Llama a renderCart para actualizar todos los totales
            await CartManager.updateCartCount();
        }
    } 
    else if (button.classList.contains('decrease-btn')) {
        const newQuantity = currentQuantity - 1;
        const success = await updateProductQuantity(productId, newQuantity);
        if (success) {
            updateRowDisplay(row, newQuantity, price);
            await renderCart(); // Llama a renderCart para actualizar todos los totales
            await CartManager.updateCartCount();
        }
    }
});

// Nota: Eliminar la función 'updateCartTotals' ya que renderCart lo hace todo.

// Ejecutamos la función principal cuando la página cargue
document.addEventListener('DOMContentLoaded', renderCart);