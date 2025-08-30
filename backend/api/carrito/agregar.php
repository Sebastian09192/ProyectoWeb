<?php
// Iniciar la sesión para poder acceder a las variables de $_SESSION
session_start();

// Headers requeridos
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Incluir la base de datos y los modelos
include_once '../../config/Database.php';
// --- ¡LA LÍNEA QUE FALTABA ESTÁ AQUÍ! ---
include_once '../../models/Carrito.php'; 
// ------------------------------------------

// --- PASO 1: VERIFICAR AUTENTICACIÓN ---
if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401); // No autorizado
    echo json_encode(array("mensaje" => "Acceso denegado. Debe iniciar sesión para agregar productos al carrito."));
    exit(); 
}

// --- PASO 2: PROCESAR LA PETICIÓN ---
$database = new Database();
$db = $database->getConnection();
$carrito = new Carrito($db); // Ahora PHP ya sabe qué es la clase "Carrito"

// Obtener los datos enviados
$data = json_decode(file_get_contents("php://input"));

// Validar que se envió el ID del producto y la cantidad
if (!empty($data->producto_id) && !empty($data->cantidad)) {
    
    $carrito->usuario_id = $_SESSION['usuario_id']; 
    $carrito->producto_id = $data->producto_id;
    $carrito->cantidad = $data->cantidad;

    if ($carrito->agregar()) {
        http_response_code(200); // OK
        echo json_encode(array("mensaje" => "Producto agregado al carrito."));
    } else {
        http_response_code(503); // Servicio no disponible
        echo json_encode(array("mensaje" => "No se pudo agregar el producto al carrito."));
    }

} else {
    http_response_code(400); // Solicitud incorrecta
    echo json_encode(array("mensaje" => "Datos incompletos. Se requiere 'producto_id' y 'cantidad'."));
}
?>