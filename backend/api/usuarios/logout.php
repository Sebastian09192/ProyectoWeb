<?php
// Iniciar la sesión para poder destruirla
session_start();

// Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Eliminar todas las variables de sesión
$_SESSION = array();

// Destruir la sesión
if (session_destroy()) {
    http_response_code(200);
    echo json_encode(array("mensaje" => "Sesión cerrada exitosamente."));
} else {
    http_response_code(500);
    echo json_encode(array("mensaje" => "Error al cerrar la sesión."));
}
?>