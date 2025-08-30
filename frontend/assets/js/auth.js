
// frontend/assets/js/auth.js
const API_BASE_URL = '../backend/api';

// Validación de correo con regex simple
function isValidEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

// Validación de contraseña (mínimo 6 caracteres)
function isValidPassword(pw) {
    return pw.length >= 6;
}

// Validación de nombre (solo letras y espacios, 3-100 caracteres)
function isValidName(name) {
    return /^[a-zA-ZÀ-ÿ\s]{3,100}$/.test(name.trim());
}

export async function handleRegister(event) {
    event.preventDefault();

    const form = event.target;
    const formData = new FormData(form);
    const data = Object.fromEntries(formData.entries());

    // Validaciones
    if (!isValidName(data.nombre)) {
        alert('El nombre debe tener solo letras y espacios (3-100 caracteres).');
        return;
    }
    if (!isValidEmail(data.email)) {
        alert('Correo electrónico inválido.');
        return;
    }
    if (!isValidPassword(data.password)) {
        alert('La contraseña debe tener al menos 6 caracteres.');
        return;
    }
    if (data.password !== data.confirm_password) {
        alert('Las contraseñas no coinciden.');
        return;
    }

    try {
        const response = await fetch(`${API_BASE_URL}/usuarios/registro.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                nombre: data.nombre.trim(),
                email: data.email.trim(),
                password: data.password
            })
        });

        const result = await response.json();

        if (response.status === 201) {
            alert(result.mensaje);
            window.location.href = 'login.php';
        } else {
            throw new Error(result.mensaje || 'Error en el registro.');
        }
    } catch (error) {
        alert(`Error: ${error.message}`);
    }
}

export async function handleLogin(event) {
    event.preventDefault();

    const formData = new FormData(event.target);
    const data = Object.fromEntries(formData.entries());

    try {
        const response = await fetch(`${API_BASE_URL}/usuarios/login.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (response.ok) {
            alert(result.mensaje);

            // Guardar login en sessionStorage
            sessionStorage.setItem('isLoggedIn', 'true');
            sessionStorage.setItem('usuarioRol', result.usuario.rol); 

            // Redirigir según el rol
            if (result.usuario.rol === 'admin') {
                window.location.href = 'admin.php';
            } else {
                window.location.href = 'index.php';
            }
        } else {
            throw new Error(result.mensaje || 'Error en el inicio de sesión.');
        }
    } catch (error) {
        alert(`Error: ${error.message}`);
    }
}
export async function logout() {
    try {
        await fetch(`${API_BASE_URL}/usuarios/logout.php`);
        sessionStorage.removeItem('isLoggedIn');
        alert('Has cerrado sesión.');
        window.location.href = 'index.php';
    } catch {
        alert('Error al cerrar sesión.');
    }
}

export function checkLoginStatus() {
    const isLoggedIn = sessionStorage.getItem('isLoggedIn') === 'true';
    const loginMenuItem = document.getElementById('login-menu-item');
    const registerMenuItem = document.getElementById('register-menu-item');
    const profileMenuItem = document.getElementById('profile-menu-item');
    const logoutMenuItem = document.getElementById('logout-menu-item');

    if (isLoggedIn) {
        loginMenuItem?.classList.add('d-none');
        registerMenuItem?.classList.add('d-none');
        profileMenuItem?.classList.remove('d-none');
        logoutMenuItem?.classList.remove('d-none');
    } else {
        loginMenuItem?.classList.remove('d-none');
        registerMenuItem?.classList.remove('d-none');
        profileMenuItem?.classList.add('d-none');
        logoutMenuItem?.classList.add('d-none');
    }
}