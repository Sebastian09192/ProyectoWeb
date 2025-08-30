<?php
// backend/models/Pedido.php
class Pedido {
    private $conn;
    private $table_name = "pedidos";

    // --- ¡AQUÍ ESTÁ LA CLAVE! ---
    // Asegúrate de que todas estas propiedades públicas existan.
    public $id;
    public $usuario_id;
    public $total;
    public $estado;
    public $metodo_pago;
    public $direccion_envio;
    public $fecha_pedido;
    public $items = [];
    public $rol; // Propiedad temporal para el login en el modelo Usuario

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Crea un nuevo pedido usando una transacción de base de datos.
     */
    public function crear() {
        $this->conn->beginTransaction();
        try {
            $query_pedido = "INSERT INTO " . $this->table_name . " SET usuario_id=:usuario_id, total=:total, estado=:estado, metodo_pago=:metodo_pago, direccion_envio=:direccion_envio";
            $stmt_pedido = $this->conn->prepare($query_pedido);

            // Limpiar datos
            $this->usuario_id = htmlspecialchars(strip_tags($this->usuario_id));
            $this->total = htmlspecialchars(strip_tags($this->total));
            $this->estado = htmlspecialchars(strip_tags($this->estado));
            $this->metodo_pago = htmlspecialchars(strip_tags($this->metodo_pago));
            $this->direccion_envio = htmlspecialchars(strip_tags($this->direccion_envio));

            $stmt_pedido->bindParam(":usuario_id", $this->usuario_id);
            $stmt_pedido->bindParam(":total", $this->total);
            $stmt_pedido->bindParam(":estado", $this->estado);
            $stmt_pedido->bindParam(":metodo_pago", $this->metodo_pago);
            $stmt_pedido->bindParam(":direccion_envio", $this->direccion_envio);
            
            $stmt_pedido->execute();
            $this->id = $this->conn->lastInsertId();

            $query_detalle = "INSERT INTO pedido_detalles SET pedido_id=:pedido_id, producto_id=:producto_id, cantidad=:cantidad, precio_unitario=:precio_unitario";
            $stmt_detalle = $this->conn->prepare($query_detalle);

            foreach($this->items as $item) {
                $stmt_detalle->bindParam(":pedido_id", $this->id);
                $stmt_detalle->bindParam(":producto_id", $item['producto_id']);
                $stmt_detalle->bindParam(":cantidad", $item['cantidad']);
                $stmt_detalle->bindParam(":precio_unitario", $item['precio']);
                $stmt_detalle->execute();
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    /**
     * Obtiene los detalles de un pedido y el nombre del cliente.
     */
    public function obtenerDetallesPorId($pedido_id, $usuario_id) {
        $query_check = "SELECT p.*, u.nombre as nombre_cliente 
                        FROM pedidos p
                        JOIN usuarios u ON p.usuario_id = u.id
                        WHERE p.id = :pedido_id AND p.usuario_id = :usuario_id";
        
        $stmt_check = $this->conn->prepare($query_check);
        $stmt_check->bindParam(':pedido_id', $pedido_id);
        $stmt_check->bindParam(':usuario_id', $usuario_id);
        $stmt_check->execute();
        
        $pedido_info = $stmt_check->fetch(PDO::FETCH_ASSOC);

        if (!$pedido_info) {
            return null;
        }

        $query_items = "SELECT p.nombre, pd.cantidad, pd.precio_unitario 
                        FROM pedido_detalles pd
                        JOIN productos p ON pd.producto_id = p.id
                        WHERE pd.pedido_id = :pedido_id";
        $stmt_items = $this->conn->prepare($query_items);
        $stmt_items->bindParam(':pedido_id', $pedido_id);
        $stmt_items->execute();
        
        $items = [];
        while($row = $stmt_items->fetch(PDO::FETCH_ASSOC)){
            array_push($items, ['name' => $row['nombre'], 'qty' => $row['cantidad'], 'price' => $row['precio_unitario']]);
        }
        
        return [
            'orderId' => $pedido_info['id'],
            'billTo' => $pedido_info['nombre_cliente'],
            'tracking' => 'CR' . str_pad($pedido_info['id'], 8, '0', STR_PAD_LEFT),
            'date' => $pedido_info['fecha_pedido'],
            'total' => $pedido_info['total'],
            'subtotal' => $pedido_info['total'] / 1.13,
            'tax' => $pedido_info['total'] - ($pedido_info['total'] / 1.13),
            'shipping' => 0,
            'items' => $items
        ];
    }
}
?>