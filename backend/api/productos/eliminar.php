<?php
// Headers requeridos
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");
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

// Obtener los datos enviados (solo necesitamos el ID)
$data = json_decode(file_get_contents("php://input"));

// Validar que se envió un ID
if (!empty($data->id)) {
    $producto->id = $data->id;

    // Intentar eliminar el producto
    if ($producto->eliminar()) {
        http_response_code(200); // OK
        echo json_encode(array("mensaje" => "El producto fue eliminado."));
    } else {
        http_response_code(503); // Servicio no disponible
        echo json_encode(array("mensaje" => "No se pudo eliminar el producto."));
    }
} else {
    http_response_code(400); // Solicitud incorrecta
    echo json_encode(array("mensaje" => "No se pudo eliminar el producto. ID no proporcionado."));
}
?>