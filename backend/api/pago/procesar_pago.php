<?php
// backend/api/pago/procesar_pago.php
session_start();
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Credentials: true");

// --- INCLUYE ESTAS LÍNEAS PARA VER ERRORES DE PHP DIRECTAMENTE EN LA RESPUESTA ---
ini_set('display_errors', 1);
error_reporting(E_ALL);
// ---------------------------------------------------------------------------------

include_once '../../utils/auth.php';
verificarAutenticacion();

include_once '../../config/Database.php';
include_once '../../models/Carrito.php';
include_once '../../models/Pedido.php';

$database = new Database();
$db = $database->getConnection();
$carrito = new Carrito($db);
$pedido = new Pedido($db);

$usuario_id = obtenerUsuarioId();
$carrito->usuario_id = $usuario_id;

$stmt_carrito = $carrito->obtenerContenido();
if ($stmt_carrito->rowCount() == 0) {
    http_response_code(400);
    echo json_encode(["mensaje" => "No se puede procesar un pedido de un carrito vacío."]);
    exit();
}

$items_pedido = [];
$subtotal = 0;
while ($row = $stmt_carrito->fetch(PDO::FETCH_ASSOC)) {
    $subtotal += $row['precio'] * $row['cantidad'];
    array_push($items_pedido, ['producto_id' => $row['producto_id'], 'cantidad' => $row['cantidad'], 'precio' => $row['precio']]);
}

// --- NUEVO CÁLCULO FINAL CON COSTO DE ENVÍO ---
$costo_envio = 5; // Debe ser el mismo valor que en obtener.php
$impuesto_iva = $subtotal * 0.13;
$total_final = $subtotal + $impuesto_iva + $costo_envio;

$data = json_decode(file_get_contents("php://input"));

// --- ¡VALIDACIÓN CORREGIDA Y MEJORADA! ---
if (empty($data->direccion_envio) || empty($data->metodo_pago) || empty($data->terms) || $data->terms !== true) {
    http_response_code(400);
    echo json_encode(["mensaje" => "Faltan datos o no se aceptaron los términos y condiciones."]);
    exit();
}

$pedido->usuario_id = $usuario_id;
$pedido->total = $total_final; // Ahora el total ya incluye el costo de envío
$pedido->estado = 'Pagado';
$pedido->metodo_pago = $data->metodo_pago;
$pedido->direccion_envio = $data->direccion_envio;
$pedido->items = $items_pedido;

if ($pedido->crear()) {
    $carrito->vaciar();
    http_response_code(201);
    echo json_encode(["mensaje" => "Pedido creado exitosamente.", "pedido_id" => $pedido->id]);
} else {
    http_response_code(503);
    echo json_encode(["mensaje" => "No se pudo crear el pedido en la base de datos."]);
}
?>