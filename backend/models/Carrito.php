<?php
class Carrito
{
    private $conn;
    private $table_name = "carrito_items";

    // Propiedades del objeto
    public $id;
    public $usuario_id;
    public $producto_id;
    public $cantidad;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // 🔄 Agregar producto al carrito (actualiza si ya existe)
    public function agregar()
    {
        $query_check = "SELECT id, cantidad FROM {$this->table_name} WHERE usuario_id = :usuario_id AND producto_id = :producto_id";
        $stmt_check = $this->conn->prepare($query_check);
        $stmt_check->bindParam(":usuario_id", $this->usuario_id);
        $stmt_check->bindParam(":producto_id", $this->producto_id);
        $stmt_check->execute();

        $item_existente = $stmt_check->fetch(PDO::FETCH_ASSOC);

        if ($item_existente) {
            $nueva_cantidad = $item_existente['cantidad'] + $this->cantidad;
            $query_update = "UPDATE {$this->table_name} SET cantidad = :cantidad WHERE id = :id";
            $stmt_update = $this->conn->prepare($query_update);
            $stmt_update->bindParam(":cantidad", $nueva_cantidad);
            $stmt_update->bindParam(":id", $item_existente['id']);
            return $stmt_update->execute();
        } else {
            $query_insert = "INSERT INTO {$this->table_name} (usuario_id, producto_id, cantidad) VALUES (:usuario_id, :producto_id, :cantidad)";
            $stmt_insert = $this->conn->prepare($query_insert);

            $this->usuario_id = htmlspecialchars(strip_tags($this->usuario_id));
            $this->producto_id = htmlspecialchars(strip_tags($this->producto_id));
            $this->cantidad = htmlspecialchars(strip_tags($this->cantidad));

            $stmt_insert->bindParam(":usuario_id", $this->usuario_id);
            $stmt_insert->bindParam(":producto_id", $this->producto_id);
            $stmt_insert->bindParam(":cantidad", $this->cantidad);

            return $stmt_insert->execute();
        }
    }

    // 📋 Obtener contenido del carrito
    public function obtenerContenido()
    {
        $query = "SELECT p.nombre, p.precio, p.imagen_url, c.producto_id, c.cantidad 
                  FROM {$this->table_name} c
                  LEFT JOIN productos p ON c.producto_id = p.id
                  WHERE c.usuario_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->usuario_id);
        $stmt->execute();
        return $stmt;
    }

    // ❌ Eliminar un producto específico del carrito
    public function eliminar()
    {
        $query = "DELETE FROM {$this->table_name} WHERE usuario_id = :usuario_id AND producto_id = :producto_id";
        $stmt = $this->conn->prepare($query);

        $this->usuario_id = htmlspecialchars(strip_tags($this->usuario_id));
        $this->producto_id = htmlspecialchars(strip_tags($this->producto_id));

        $stmt->bindParam(":usuario_id", $this->usuario_id);
        $stmt->bindParam(":producto_id", $this->producto_id);

        return $stmt->execute();
    }

    // 🧹 Vaciar el carrito completamente
    public function vaciar()
    {
        $query = "DELETE FROM {$this->table_name} WHERE usuario_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->usuario_id);
        return $stmt->execute();
    }

    // ✏️ Actualizar cantidad de un producto en el carrito
    public function actualizarCantidad()
    {
        $query = "UPDATE {$this->table_name} SET cantidad = :cantidad 
                  WHERE usuario_id = :usuario_id AND producto_id = :producto_id";
        $stmt = $this->conn->prepare($query);

        $this->cantidad = htmlspecialchars(strip_tags($this->cantidad));
        $this->usuario_id = htmlspecialchars(strip_tags($this->usuario_id));
        $this->producto_id = htmlspecialchars(strip_tags($this->producto_id));

        $stmt->bindParam(':cantidad', $this->cantidad);
        $stmt->bindParam(':usuario_id', $this->usuario_id);
        $stmt->bindParam(':producto_id', $this->producto_id);

        return $stmt->execute();
    }
}