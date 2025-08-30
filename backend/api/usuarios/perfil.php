<?php
// Iniciar la sesión para poder acceder a las variables de $_SESSION
session_start();

// Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

// Incluir la base de datos y el modelo
include_once '../../config/Database.php';
include_once '../../models/Usuario.php';

// Primero, verificar si el usuario está autenticado (si existe la sesión)
if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401); // No autorizado
    echo json_encode(array("mensaje" => "Acceso denegado. Por favor, inicie sesión."));
    exit(); // Detener la ejecución del script
}

// Si está autenticado, proceder a obtener sus datos
$database = new Database();
$db = $database->getConnection();
$usuario = new Usuario($db);

// Usar el ID guardado en la sesión
$usuario->id = $_SESSION['usuario_id'];

// Intentar obtener los datos del usuario
if ($usuario->obtenerPorId()) {
    $perfil_usuario = array(
        "id" => $usuario->id,
        "nombre" => $usuario->nombre,
        "email" => $usuario->email,
        "direccion" => $usuario->direccion,
        "telefono" => $usuario->telefono
    );
    http_response_code(200); // OK
    echo json_encode($perfil_usuario);
} else {
    http_response_code(404); // No encontrado
    echo json_encode(array("mensaje" => "No se encontró el usuario asociado a esta sesión."));
}
?>