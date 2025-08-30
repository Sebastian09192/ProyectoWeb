<?php
class Usuario
{
    private $conn;
    private $table_name = "usuarios";

    // Propiedades del objeto
    public $id;
    public $nombre;
    public $email;
    public $password;
    public $direccion;
    public $telefono;
    public $rol;

    public function __construct($db)
    {
        $this->conn = $db;
        $this->crearUsuarioAdmin(); // <-- al construir la clase, siempre verifica si hay admin
    }

    // Crear usuario administrador quemado en el código
    private function crearUsuarioAdmin()
    {
        // Comprobar si ya existe un usuario admin con el correo
        $query = "SELECT id FROM " . $this->table_name . " WHERE email = 'admin@gmail.com' LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        if ($stmt->rowCount() == 0) {
            // Si no existe, lo insertamos
            $query = "INSERT INTO " . $this->table_name . " 
                      (nombre, email, password, rol, direccion, telefono) 
                      VALUES (:nombre, :email, :password, :rol, '', '')";
            $stmt = $this->conn->prepare($query);

            $nombre = "Administrador";
            $email = "admin@gmail.com";
            $password = password_hash("admin123", PASSWORD_BCRYPT); // Hasheada en PHP
            $rol = "admin";

            $stmt->bindParam(":nombre", $nombre);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":password", $password);
            $stmt->bindParam(":rol", $rol);

            $stmt->execute();
        }
    }

    // Registrar un nuevo usuario
    public function registrar()
    {
        // Verificar primero si el email ya existe
        if ($this->emailExiste()) {
            return false;
        }

        $query = "INSERT INTO " . $this->table_name . " 
                  SET nombre=:nombre, email=:email, password=:password, direccion=:direccion, telefono=:telefono, rol=:rol";

        $stmt = $this->conn->prepare($query);

        // Limpiar datos
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->direccion = htmlspecialchars(strip_tags($this->direccion));
        $this->telefono = htmlspecialchars(strip_tags($this->telefono));
        $this->rol = htmlspecialchars(strip_tags($this->rol));

        // Hashear la contraseña
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);

        // Vincular parámetros
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":direccion", $this->direccion);
        $stmt->bindParam(":telefono", $this->telefono);
        $stmt->bindParam(":rol", $this->rol);

        if ($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    // Iniciar sesión
    public function login()
    {
        $query = "SELECT id, nombre, password, rol FROM " . $this->table_name . " WHERE email = :email LIMIT 0,1";
        $stmt = $this->conn->prepare($query);

        $this->email = htmlspecialchars(strip_tags($this->email));
        $stmt->bindParam(':email', $this->email);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row && password_verify($this->password, $row['password'])) {
            $this->id = $row['id'];
            $this->nombre = $row['nombre'];
            $this->rol = $row['rol']; 
            return true;
        }
        return false;
    }

    // Verificar si un email ya existe
    public function emailExiste()
    {
        $query = "SELECT id FROM " . $this->table_name . " WHERE email = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);

        $this->email = htmlspecialchars(strip_tags($this->email));
        $stmt->bindParam(1, $this->email);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    // Obtener datos de un usuario por su ID
    public function obtenerPorId()
    {
        $query = "SELECT nombre, email, direccion, telefono, rol FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->nombre = $row['nombre'];
            $this->email = $row['email'];
            $this->direccion = $row['direccion'];
            $this->telefono = $row['telefono'];
            $this->rol = $row['rol'];
            return true;
        }
        return false;
    }

    public function actualizar()
    {
        $query = "UPDATE " . $this->table_name . " 
                  SET nombre=:nombre, email=:email, direccion=:direccion, telefono=:telefono 
                  WHERE id=:id";

        $stmt = $this->conn->prepare($query);

        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->direccion = htmlspecialchars(strip_tags($this->direccion));
        $this->telefono = htmlspecialchars(strip_tags($this->telefono));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":direccion", $this->direccion);
        $stmt->bindParam(":telefono", $this->telefono);
        $stmt->bindParam(":id", $this->id);

        return $stmt->execute();
    }
}
