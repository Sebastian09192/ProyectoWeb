<?php
// Iniciar la sesión para poder acceder a las variables de $_SESSION
session_start();

// Headers requeridos
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

// Incluir la base de datos y los modelos
include_once '../../config/Database.php';
include_once '../../models/Carrito.php';

// --- PASO 1: VERIFICAR AUTENTICACIÓN ---
if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401); // No autorizado
    echo json_encode(array("mensaje" => "Acceso denegado. Debe iniciar sesión para ver el carrito."));
    exit(); 
}

// --- PASO 2: OBTENER DATOS DEL CARRITO ---
$database = new Database();
$db = $database->getConnection();
$carrito = new Carrito($db);

// Asignamos el ID del usuario de la sesión al objeto carrito
$carrito->usuario_id = $_SESSION['usuario_id'];

// Ejecutamos el método para obtener el contenido
$stmt = $carrito->obtenerContenido();
$num = $stmt->rowCount();

if ($num > 0) {
    $items_carrito = array();
    $items_carrito["items"] = array();
    $subtotal = 0;

    // Recorremos los resultados
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        
        // El subtotal es el precio del producto por la cantidad
        $subtotal_item = $precio * $cantidad;

        $item = array(
            "producto_id" => $producto_id,
            "nombre" => $nombre,
            "precio" => $precio,
            "cantidad" => $cantidad,
            "imagen_url" => $imagen_url,
            "subtotal" => $subtotal_item
        );

        array_push($items_carrito["items"], $item);
        // Sumamos el subtotal al total general
        $subtotal += $subtotal_item;
    }

    // --- NUEVO CÁLCULO DE COSTOS ---
    $costo_envio = 5; // Puedes cambiar este valor
    $impuesto_iva = $subtotal * 0.13;
    $total_final = $subtotal + $impuesto_iva + $costo_envio;
    
    $items_carrito["subtotal"] = number_format($subtotal, 2, '.', '');
    $items_carrito["impuesto"] = number_format($impuesto_iva, 2, '.', '');
    $items_carrito["envio"] = number_format($costo_envio, 2, '.', '');
    $items_carrito["total"] = number_format($total_final, 2, '.', '');

    http_response_code(200); // OK
    echo json_encode($items_carrito);

} else {
    // Si el carrito está vacío
    http_response_code(200); // OK, pero devolvemos una respuesta indicando que está vacío
    echo json_encode(
        array(
            "items" => [],
            "subtotal" => 0,
            "impuesto" => 0,
            "envio" => 0,
            "total" => 0,
            "mensaje" => "El carrito está vacío."
        )
    );
}
?>