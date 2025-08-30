// frontend/assets/js/products-page.js

import { CartManager } from './CartManager.js'; // Importamos el gestor del carrito

const API_PRODUCTS_URL = '../backend/api/productos/leer.php';
const container = document.getElementById('productsGrid'); // El ID de tu contenedor de productos

/**
 * Carga los productos desde la API y los muestra en la página.
 */
async function cargarProductos() {
    if (!container) {
        console.error("El contenedor 'productsGrid' no fue encontrado.");
        return;
    }
    container.innerHTML = '<p>Cargando productos...</p>'; // Mensaje de carga

    try {
        const response = await fetch(API_PRODUCTS_URL);
        if (!response.ok) throw new Error('Error de red al obtener los productos.');

        const data = await response.json();
        container.innerHTML = ''; // Limpiamos el mensaje de carga

        if (data.productos && data.productos.length > 0) {
            data.productos.forEach(producto => {
                const productCard = `
                    <div class="col">
                        <div class="card h-100 shadow-sm">
                            <img src="${producto.imagen_url || 'assets/img/placeholder.png'}" class="card-img-top" alt="${producto.nombre}">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">${producto.nombre}</h5>
                                <p class="card-text text-muted small flex-grow-1">${producto.descripcion || ''}</p>
                                <h6 class="card-subtitle mb-2 fw-bold">$${parseFloat(producto.precio).toFixed(2)}</h6>
                            </div>
                            <div class="card-footer bg-white border-top-0 p-3">
                                <button class="btn btn-primary w-100 add-to-cart-btn" data-product-id="${producto.id}">
                                    <i class="fa-solid fa-cart-plus me-1"></i> Agregar al Carrito
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                container.insertAdjacentHTML('beforeend', productCard);
            });
        } else {
            container.innerHTML = '<p class="text-center col-12">No hay productos disponibles en este momento.</p>';
        }
    } catch (error) {
        console.error('Error al cargar productos:', error);
        container.innerHTML = '<p class="text-center text-danger col-12">No se pudieron cargar los productos. Intente de nuevo más tarde.</p>';
    }
}

/**
 * Escucha los clics en el contenedor de productos.
 * Si se hace clic en un botón "Agregar al Carrito", llama al CartManager.
 */
container.addEventListener('click', (event) => {
    // Buscamos si el clic (o uno de sus padres) es el botón que nos interesa
    const button = event.target.closest('.add-to-cart-btn');

    if (button) {
        const productId = button.getAttribute('data-product-id');
        // Llamamos al método 'add' de nuestro gestor del carrito
        CartManager.add(productId, 1);
    }
});

// Ejecutamos la función para cargar los productos cuando el DOM esté listo.
document.addEventListener('DOMContentLoaded', cargarProductos);