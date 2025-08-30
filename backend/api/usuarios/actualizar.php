<?php
// Iniciar sesión
session_start();

// Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

// Incluir la base de datos y el modelo
include_once '../../config/Database.php';
include_once '../../models/Usuario.php';

// Verificar sesión
if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo json_encode(["exito" => false, "mensaje" => "Acceso denegado. Inicie sesión."]);
    exit();
}

// Obtener los datos enviados en JSON
$datos = json_decode(file_get_contents("php://input"), true);

// Verificar que existan los campos requeridos
if (!isset($datos['nombre']) || !isset($datos['email'])) {
    http_response_code(400);
    echo json_encode(["exito" => false, "mensaje" => "Faltan datos requeridos."]);
    exit();
}

// Conexión a la base de datos
$database = new Database();
$db = $database->getConnection();

// Crear objeto Usuario
$usuario = new Usuario($db);
$usuario->id = $_SESSION['usuario_id'];
$usuario->nombre = $datos['nombre'];
$usuario->email = $datos['email'];
$usuario->direccion = $datos['direccion'] ?? "";
$usuario->telefono = $datos['telefono'] ?? "";

// Intentar actualizar
if ($usuario->actualizar()) {
    echo json_encode(["exito" => true, "mensaje" => "Perfil actualizado correctamente."]);
} else {
    http_response_code(500);
    echo json_encode(["exito" => false, "mensaje" => "No se pudo actualizar el perfil."]);
}
?>
