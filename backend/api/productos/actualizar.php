<?php
// Headers requeridos
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT"); // Indicamos que este endpoint acepta el método PUT
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

// Obtener los datos enviados en el cuerpo de la petición
$data = json_decode(file_get_contents("php://input"));

// Validar que los datos no estén vacíos y que se incluya el ID
if (
    !empty($data->id) &&
    !empty($data->nombre) &&
    !empty($data->precio) &&
    isset($data->stock)
) {
    // Asignar los valores al objeto producto
    $producto->id = $data->id;
    $producto->nombre = $data->nombre;
    $producto->precio = $data->precio;
    $producto->stock = $data->stock;
    $producto->descripcion = !empty($data->descripcion) ? $data->descripcion : '';
    $producto->imagen_url = !empty($data->imagen_url) ? $data->imagen_url : '';

    // Intentar actualizar el producto
    if ($producto->actualizar()) {
        http_response_code(200); // OK
        echo json_encode(array("mensaje" => "El producto fue actualizado."));
    } else {
        http_response_code(503); // Servicio no disponible
        echo json_encode(array("mensaje" => "No se pudo actualizar el producto."));
    }
} else {
    http_response_code(400); // Solicitud incorrecta
    echo json_encode(array("mensaje" => "No se pudo actualizar el producto. Datos incompletos, asegúrate de incluir el ID."));
}
?>