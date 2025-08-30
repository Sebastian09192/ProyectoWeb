<?php
// Iniciar sesión
session_start();

// Headers para la API
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

// Incluir la base de datos y el modelo
// RUTA CORREGIDA
include_once '../../config/Database.php';
include_once '../../models/Usuario.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo json_encode(["exito" => false, "mensaje" => "Acceso denegado. Inicie sesión."]);
    exit();
}

// Conexión a la base de datos
$database = new Database();
$db = $database->getConnection();

// Crear objeto Usuario y asignarle el ID de la sesión
$usuario = new Usuario($db);
$usuario->id = $_SESSION['usuario_id'];

// Intentar obtener los datos del usuario
if ($usuario->obtenerPorId()) {
    // Los datos se obtuvieron correctamente, devolverlos en JSON
    $datos_usuario = [
        "nombre" => $usuario->nombre,
        "email" => $usuario->email,
        "direccion" => $usuario->direccion,
        "telefono" => $usuario->telefono
    ];
    http_response_code(200);
    echo json_encode($datos_usuario);
} else {
    // Error al obtener los datos
    http_response_code(404);
    echo json_encode(["exito" => false, "mensaje" => "Usuario no encontrado."]);
}