// frontend/assets/js/app.js
import { handleLogin, handleRegister, checkLoginStatus, logout } from './auth.js';
import { CartManager } from './CartManager.js';

document.addEventListener('DOMContentLoaded', () => {
    
    // 1. Revisar estado de login para mostrar/ocultar enlaces del menú
    checkLoginStatus();

    // 2. Actualizar contador del carrito
    CartManager.updateCartCount();

    // 3. Lógica del año en el footer
    const yearEl = document.getElementById('year');
    if (yearEl) {
        yearEl.textContent = new Date().getFullYear();
    }

    // --- Asignación de eventos ---
    const loginForm = document.getElementById('login-form');
    if (loginForm) {
        loginForm.addEventListener('submit', handleLogin);
    }

    const registerForm = document.getElementById('register-form');
    if (registerForm) {
        registerForm.addEventListener('submit', handleRegister);
    }

    // 4. Conectar el botón de logout con el ID CORRECTO
    const logoutLink = document.getElementById('logout-menu-item-link');
    if (logoutLink) {
        logoutLink.addEventListener('click', (event) => {
            event.preventDefault();
            logout();
        });
    }
});