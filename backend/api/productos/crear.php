<?php

// --- AGREGA ESTAS DOS LÍNEAS PARA VER ERRORES ---
ini_set('display_errors', 1);
error_reporting(E_ALL);
// ---------------------------------------------------

// Headers requeridos
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
// ... el resto de tu código sigue igual ...

header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Incluir la base de datos y el modelo
include_once '../../utils/auth.php';
include_once '../../config/Database.php';
include_once '../../models/Producto.php';

verificarAdmin();

$database = new Database();
$db = $database->getConnection();
$producto = new Producto($db);

// Obtener los datos enviados
$data = json_decode(file_get_contents("php://input"));

// Validar que los datos no estén vacíos
if (
    !empty($data->nombre) &&
    !empty($data->precio) &&
    isset($data->stock)
) {
    // Asignar los valores al objeto producto
    $producto->nombre = $data->nombre;
    $producto->precio = $data->precio;
    $producto->stock = $data->stock;
    $producto->descripcion = !empty($data->descripcion) ? $data->descripcion : '';
    $producto->imagen_url = !empty($data->imagen_url) ? $data->imagen_url : '';

    // Intentar crear el producto
    if ($producto->crear()) {
        http_response_code(201); // 201 Creado
        echo json_encode(array("mensaje" => "El producto fue creado exitosamente."));
    } else {
        http_response_code(503); // 503 Servicio no disponible
        echo json_encode(array("mensaje" => "No se pudo crear el producto."));
    }
} else {
    http_response_code(400); // 400 Solicitud incorrecta
    echo json_encode(array("mensaje" => "No se pudo crear el producto. Datos incompletos."));
}
?>