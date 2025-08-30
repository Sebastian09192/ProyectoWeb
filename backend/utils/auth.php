<?php
/**
 * Inicia la sesión de forma segura.
 * Debe llamarse al principio de cualquier script que maneje sesiones.
 */
function iniciarSesionSegura() {
    // Si ya hay una sesión, no hacemos nada
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }
    
    // Configuración de cookies de sesión para mayor seguridad
    $cookieParams = session_get_cookie_params();
    session_set_cookie_params([
        'lifetime' => $cookieParams['lifetime'],
        'path' => $cookieParams['path'],
        'domain' => $_SERVER['HTTP_HOST'],
        'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',  // Solo enviar cookies sobre HTTPS (importante en producción)
        'httponly' => true, // Prevenir acceso a cookies desde JavaScript
        'samesite' => 'Strict' // Prevenir ataques CSRF
    ]);
    
    session_start();
}

/**
 * Verifica si el usuario actual ha iniciado sesión.
 * Si no ha iniciado sesión, detiene la ejecución del script y devuelve un error 401.
 */
function verificarAutenticacion() {
    iniciarSesionSegura(); // Nos aseguramos de que la sesión esté iniciada

    if (!isset($_SESSION['usuario_id'])) {
        // Si no existe el ID de usuario en la sesión, el acceso es denegado
        header("Content-Type: application/json; charset=UTF-8");
        http_response_code(401); // No autorizado
        echo json_encode(array("mensaje" => "Acceso denegado. Se requiere autenticación."));
        exit(); // Detenemos la ejecución del script por completo
    }
}

/**
 * Obtiene el ID del usuario que ha iniciado sesión.
 * Asume que verificarAutenticacion() ya ha sido llamado.
 * @return int|null El ID del usuario o null si no se encuentra.
 */
function obtenerUsuarioId() {
    return isset($_SESSION['usuario_id']) ? (int)$_SESSION['usuario_id'] : null;
}

function verificarAdmin() {
    // Primero, nos aseguramos de que el usuario haya iniciado sesión.
    verificarAutenticacion();

    // Luego, verificamos si el rol guardado en la sesión es 'admin'.
    if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] !== 'admin') {
        // 403 Forbidden: Estás logueado, pero no tienes permiso.
        header("Content-Type: application/json; charset=UTF-8");
        http_response_code(403); 
        echo json_encode(array("mensaje" => "Acceso denegado. Se requiere rol de administrador."));
        exit(); 
    }
}
?>