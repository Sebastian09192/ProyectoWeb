<?php
class Database {
    private $host = 'localhost';
    private $db_name = 'tienda_virtual';
    private $username = 'root';
    // Coloca aquí la misma contraseña que usas para entrar en Workbench
    private $password = '12345';
    private $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            // La conexión se hace directamente al servidor MySQL
            $this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo 'Error de conexión: ' . $e->getMessage();
        }
        return $this->conn;
    }
}
?>