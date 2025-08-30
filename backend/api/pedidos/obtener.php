<?php
// backend/api/pedidos/obtener.php
session_start();
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");

include_once '../../utils/auth.php';
verificarAutenticacion();

include_once '../../config/Database.php';
include_once '../../models/Pedido.php'; 

$database = new Database();
$db = $database->getConnection();

if(!$db){
    http_response_code(503);
    echo json_encode(array("mensaje" => "No se pudo conectar a la base de datos."));
    exit();
}

// --- ¡AQUÍ ESTÁ LA LÍNEA QUE PROBABLEMENTE ESTABA MAL! ---
$pedido = new Pedido($db); // Debe tener $db adentro.

$pedido_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($pedido_id <= 0) {
    http_response_code(400);
    echo json_encode(array("mensaje" => "ID de pedido inválido."));
    exit();
}
$usuario_id = obtenerUsuarioId();

$detalles_pedido = $pedido->obtenerDetallesPorId($pedido_id, $usuario_id);

if ($detalles_pedido) {
    http_response_code(200);
    echo json_encode($detalles_pedido);
} else {
    http_response_code(404);
    echo json_encode(array("mensaje" => "Pedido no encontrado o no tienes permiso para verlo."));
}
?>