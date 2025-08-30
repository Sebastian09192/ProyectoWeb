<?php
// Headers requeridos para que cualquier origen pueda leer los datos
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// NO DEBE HABER NINGÚN session_start() O include_once '../../utils/auth.php' AQUÍ

// Incluir la base de datos y el modelo de Producto
include_once '../../config/Database.php';
include_once '../../models/Producto.php';

// Instanciar la base de datos y el objeto producto
$database = new Database();
$db = $database->getConnection();
$producto = new Producto($db);

// Lógica para obtener los productos (esta parte no necesita saber si el usuario está logueado)
$stmt = $producto->leerTodos();
$num = $stmt->rowCount();

if ($num > 0) {
    $productos_arr = array();
    $productos_arr["productos"] = array();

    // Dentro de leer.php, en el bucle while
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $producto_item = array(
            "id" => $id,
            "nombre" => $nombre,
            "descripcion" => $descripcion,
            "precio" => $precio,
            "categoria" => $categoria, // <-- ¡AÑADIR ESTA LÍNEA!
            "stock" => $stock,
            "imagen_url" => $imagen_url
        );
        array_push($productos_arr["productos"], $producto_item);
    }
    http_response_code(200); // OK
    echo json_encode($productos_arr);
} else {
    http_response_code(404); // No encontrado
    echo json_encode(array("mensaje" => "No se encontraron productos."));
}
?>