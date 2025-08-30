<?php
session_start();
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

include_once '../../utils/auth.php';
verificarAutenticacion();

include_once '../../config/Database.php';
include_once '../../models/Carrito.php';

$database = new Database();
$db = $database->getConnection();
$carrito = new Carrito($db);

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->producto_id) && isset($data->cantidad)) {
    $carrito->usuario_id = obtenerUsuarioId();
    $carrito->producto_id = $data->producto_id;
    $carrito->cantidad = $data->cantidad;

    // Añadimos un método simple al modelo Carrito.php para actualizar
    // Nota: Necesitarás añadir el método 'actualizarCantidad' a tu modelo.
    if ($carrito->actualizarCantidad()) { // Suponiendo que este método existe
        http_response_code(200);
        echo json_encode(array("mensaje" => "Cantidad actualizada."));
    } else {
        http_response_code(503);
        echo json_encode(array("mensaje" => "No se pudo actualizar la cantidad."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("mensaje" => "Datos incompletos."));
}
?>