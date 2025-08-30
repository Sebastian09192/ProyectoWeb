// frontend/assets/js/CartManager.js

const API_BASE_URL = '../backend/api/carrito/';

/**
 * Objeto que encapsula toda la lógica para comunicarse con la API del carrito en el backend.
 */
export const CartManager = {

    /**
     * Llama a la API para agregar un producto al carrito del usuario logueado.
     * @param {number|string} productoId - El ID del producto a agregar.
     * @param {number} [cantidad=1] - La cantidad a agregar.
     */
    async add(productoId, cantidad = 1) {
        try {
            const response = await fetch(`${API_BASE_URL}agregar.php`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ producto_id: productoId, cantidad: cantidad })
            });

            const result = await response.json();

            if (!response.ok) {
                throw new Error(result.mensaje || 'Error desconocido al agregar el producto.');
            }

            console.log('Producto agregado:', result.mensaje);
            // Después de agregar, actualizamos el contador en la barra de navegación.
            await this.updateCartCount();

        } catch (error) {
            console.error('Error en CartManager.add:', error);

            // Si el error indica que se requiere autenticación, informamos al usuario.
            if (error.message.toLowerCase().includes("autenticación") || error.message.toLowerCase().includes("iniciar sesión")) {
                alert("Debes iniciar sesión para agregar productos al carrito.");
                window.location.href = 'login.php'; // Redirigir a la página de login
            } else {
                alert(`Error: ${error.message}`);
            }
        }
    },

    /**
     * Llama a la API para obtener el contenido completo del carrito del usuario.
     * @returns {Promise<object|null>} Los datos del carrito o null si hay un error.
     */
    async fetchCart() {
        try {
            const response = await fetch(`${API_BASE_URL}obtener.php`);
            if (response.status === 401) { // No está logueado
                return { items: [], total: 0 };
            }
            if (!response.ok) {
                throw new Error('No se pudo obtener el contenido del carrito.');
            }
            return await response.json();
        } catch (error) {
            console.error('Error en CartManager.fetchCart:', error);
            return null;
        }
    },

    /**
     * Llama a la API para eliminar un producto del carrito.
     * @param {number|string} productoId - El ID del producto a eliminar.
     */
    async remove(productoId) {
        try {
            const response = await fetch(`${API_BASE_URL}eliminar.php`, {
                method: 'POST', // Nuestro endpoint de eliminar usa POST para recibir un body
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ producto_id: productoId })
            });

            const result = await response.json();
            if (!response.ok) {
                throw new Error(result.mensaje || 'Error al eliminar el producto.');
            }

            console.log('Producto eliminado:', result.mensaje);
            return true; // Indicar que la operación fue exitosa

        } catch (error) {
            console.error('Error en CartManager.remove:', error);
            alert(`Error: ${error.message}`);
            return false; // Indicar que la operación falló
        }
    },

    /**
     * Actualiza la cantidad de un producto en el carrito
     * @param {string} productId - ID del producto a actualizar
     * @param {number} quantity - Nueva cantidad
     * @returns {Promise<boolean>} - True si fue exitoso
     */
    async updateQuantity(productId, quantity) {
        try {
            const response = await fetch(`${API_BASE_URL}actualizar_cantidad.php`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    producto_id: productId,
                    cantidad: quantity
                })
            });
            
            const result = await response.json();
            
            if (!response.ok) {
                throw new Error(result.mensaje || 'Error al actualizar la cantidad.');
            }
            
            console.log('Cantidad actualizada:', result.mensaje);
            return true;
        } catch (error) {
            console.error('Error en CartManager.updateQuantity:', error);
            
            // Manejo específico de errores de autenticación
            if (error.message.toLowerCase().includes("autenticación") || error.message.toLowerCase().includes("iniciar sesión")) {
                alert("Debes iniciar sesión para modificar el carrito.");
                window.location.href = 'login.php';
            } else {
                alert(`Error: ${error.message}`);
            }
            
            return false;
        }
    },

    /**
     * Obtiene el conteo actual de items en el carrito
     * @returns {Promise<number>} - Número de items en el carrito
     */
    async getCartCount() {
        try {
            const cartData = await this.fetchCart();
            if (cartData && cartData.items) {
                return cartData.items.reduce((sum, item) => sum + parseInt(item.cantidad, 10), 0);
            }
            return 0;
        } catch (error) {
            console.error('Error en CartManager.getCartCount:', error);
            return 0;
        }
    },

    /**
     * Actualiza el indicador de conteo de carrito en la interfaz
     */
    async updateCartCount() {
        const count = await this.getCartCount();
        const cartCountElement = document.getElementById('cart-count');
        if (cartCountElement) {
            cartCountElement.textContent = count;
            cartCountElement.style.display = count > 0 ? 'inline-block' : 'none';
        }
    }
};