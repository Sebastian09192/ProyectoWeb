<?php
session_start();
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST"); // Usamos POST para enviar un body

include_once '../../utils/auth.php';
verificarAutenticacion();

include_once '../../config/Database.php';
include_once '../../models/Carrito.php';

$database = new Database();
$db = $database->getConnection();
$carrito = new Carrito($db);

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->producto_id)) {
    $carrito->usuario_id = obtenerUsuarioId();
    $carrito->producto_id = $data->producto_id;

    if ($carrito->eliminar()) {
        http_response_code(200);
        echo json_encode(array("mensaje" => "Producto eliminado del carrito."));
    } else {
        http_response_code(503);
        echo json_encode(array("mensaje" => "No se pudo eliminar el producto."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("mensaje" => "Datos incompletos. Se requiere producto_id."));
}
?>