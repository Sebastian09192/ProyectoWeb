<?php
// Headers para permitir peticiones CORS (desde tu frontend a tu backend) y para indicar que la respuesta es JSON
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

// Incluir archivos de la base de datos y del modelo
include_once '../../config/Database.php';
include_once '../../models/Usuario.php';

// Instanciar la base de datos y obtener la conexión
$database = new Database();
$db = $database->getConnection();

// Preparar el objeto Usuario
$usuario = new Usuario($db);

// Obtener los datos enviados (por ejemplo, desde un fetch en JavaScript)
$data = json_decode(file_get_contents("php://input"));

// Asignar los valores al objeto
$usuario->nombre = $data->nombre;
$usuario->email = $data->email;
$usuario->password = $data->password;

// Intentar registrar el usuario
if ($usuario->registrar()) {
    http_response_code(201); // 201 Created
    echo json_encode(array("mensaje" => "Usuario registrado exitosamente."));
} else {
    http_response_code(503); // 503 Service Unavailable
    echo json_encode(array("mensaje" => "No se pudo registrar el usuario."));
}
?>