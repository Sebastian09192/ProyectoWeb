<?php
// Iniciar la sesión ANTES de cualquier salida de HTML o PHP
session_start();

// Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

// Incluir la base de datos y el modelo
include_once '../../config/Database.php';
include_once '../../models/Usuario.php';

$database = new Database();
$db = $database->getConnection();
$usuario = new Usuario($db);

// Obtener los datos enviados
$data = json_decode(file_get_contents("php://input"));

// Validar datos
if (!empty($data->email) && !empty($data->password)) {
    $usuario->email = $data->email;
    $usuario->password = $data->password;

    // Intentar iniciar sesión
    // Dentro de login.php, en la parte donde el login es exitoso
    if ($usuario->login()) {
        // Si el login es exitoso, creamos las variables de sesión
        $_SESSION['usuario_id'] = $usuario->id;
        $_SESSION['usuario_nombre'] = $usuario->nombre;
        $_SESSION['usuario_rol'] = $usuario->rol; // <-- LÍNEA NUEVA IMPORTANTE

        http_response_code(200); // OK
        echo json_encode(array(
            "mensaje" => "Inicio de sesión exitoso.",
            "usuario" => array(
                "id" => $usuario->id,
                "nombre" => $usuario->nombre,
                "rol" => $usuario->rol // Devolvemos el rol también
            )
        ));
    } else {
        http_response_code(401); // 401 No autorizado
        echo json_encode(array("mensaje" => "Credenciales incorrectas."));
    }
} else {
    http_response_code(400); // Solicitud incorrecta
    echo json_encode(array("mensaje" => "Email y contraseña son requeridos."));
}
