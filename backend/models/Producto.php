<?php
class Producto
{
    private $conn;
    private $table_name = "productos";

    // Propiedades del objeto
    public $id;
    public $nombre;
    public $descripcion;
    public $precio;
    public $stock;
    public $imagen_url;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // LEER todos los productos
    // Dentro de la clase Producto, en el método leerTodos()
    public function leerTodos()
    {
        $query = "SELECT id, nombre, descripcion, precio, categoria, stock, imagen_url FROM " . $this->table_name . " ORDER BY nombre ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    // LEER un solo producto por ID
    public function leerUno()
    {
        $query = "SELECT nombre, descripcion, precio, stock, imagen_url FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->nombre = $row['nombre'];
            $this->descripcion = $row['descripcion'];
            $this->precio = $row['precio'];
            $this->stock = $row['stock'];
            $this->imagen_url = $row['imagen_url'];
            return true;
        }
        return false;
    }

    // CREAR un nuevo producto
    public function crear()
    {
        $query = "INSERT INTO " . $this->table_name . " SET nombre=:nombre, descripcion=:descripcion, precio=:precio, stock=:stock, imagen_url=:imagen_url";
        $stmt = $this->conn->prepare($query);

        // Limpiar datos para evitar XSS
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
        $this->precio = htmlspecialchars(strip_tags($this->precio));
        $this->stock = htmlspecialchars(strip_tags($this->stock));
        $this->imagen_url = htmlspecialchars(strip_tags($this->imagen_url));

        // Vincular parámetros
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":descripcion", $this->descripcion);
        $stmt->bindParam(":precio", $this->precio);
        $stmt->bindParam(":stock", $this->stock);
        $stmt->bindParam(":imagen_url", $this->imagen_url);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // ACTUALIZAR un producto existente
    public function actualizar()
    {
        $query = "UPDATE " . $this->table_name . "
        SET nombre = :nombre, descripcion = :descripcion, precio = :precio, stock = :stock, imagen_url = :imagen_url
                WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        // Limpiar datos
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
        $this->precio = htmlspecialchars(strip_tags($this->precio));
        $this->stock = htmlspecialchars(strip_tags($this->stock));
        $this->imagen_url = htmlspecialchars(strip_tags($this->imagen_url));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Vincular parámetros
        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':descripcion', $this->descripcion);
        $stmt->bindParam(':precio', $this->precio);
        $stmt->bindParam(':stock', $this->stock);
        $stmt->bindParam(':imagen_url', $this->imagen_url);
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // ELIMINAR un producto
    public function eliminar()
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);

        // Limpiar ID
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Vincular ID
        $stmt->bindParam(1, $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
