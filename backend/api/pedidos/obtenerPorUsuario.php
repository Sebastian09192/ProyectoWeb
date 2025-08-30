<?php
// Iniciar sesión
session_start();

// Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

// Incluir la base de datos y el modelo
include_once '../../config/Database.php';
include_once '../../models/Pedido.php';

// Verificar sesión
if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo json_encode(["mensaje" => "Acceso denegado. Inicie sesión."]);
    exit();
}

// Conexión a la base de datos
$database = new Database();
$db = $database->getConnection();

// Crear objeto Pedido
$pedido = new Pedido($db);
$usuario_id = $_SESSION['usuario_id'];

// Obtener todos los pedidos del usuario
$query = "SELECT * FROM pedidos WHERE usuario_id = :usuario_id ORDER BY fecha_pedido DESC";
$stmt = $db->prepare($query);
$stmt->bindParam(':usuario_id', $usuario_id);
$stmt->execute();

$pedidos = [];
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    // Obtener items de cada pedido
    $query_items = "SELECT p.nombre, pd.cantidad, pd.precio_unitario
                    FROM pedido_detalles pd
                    JOIN productos p ON pd.producto_id = p.id
                    WHERE pd.pedido_id = :pedido_id";
    $stmt_items = $db->prepare($query_items);
    $stmt_items->bindParam(':pedido_id', $row['id']);
    $stmt_items->execute();

    $items = [];
    while($item = $stmt_items->fetch(PDO::FETCH_ASSOC)) {
        $items[] = [
            "name" => $item['nombre'],
            "qty" => $item['cantidad'],
            "price" => $item['precio_unitario']
        ];
    }

    $pedidos[] = [
        "orderId" => $row['id'],
        "tracking" => 'CR' . str_pad($row['id'], 8, '0', STR_PAD_LEFT),
        "date" => $row['fecha_pedido'],
        "total" => floatval($row['total']),
        "estado" => $row['estado'],
        "items" => $items
    ];
}

// Devolver JSON
echo json_encode($pedidos);
?>