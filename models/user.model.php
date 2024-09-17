<?php
class User
{
    // Conexión a la base de datos
    private $conn;
    private $table_name = "users";

    // Propiedades del usuario
    public $id_user;
    public $name_user;
    public $lastname_user;
    public $email_user;
    public $password_user;
    public $created_at_user;
    public $updated_at_user;

    // Constructor con conexión a la base de datos
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Método para crear un usuario
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  (name_user, lastname_user, email_user, password_user, created_at_user) 
                  VALUES (?, ?, ?, ?, NOW())";
    
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssss", $this->name_user, $this->lastname_user, $this->email_user, $this->password_user);
    
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    



    // Método para leer todos los usuarios
    public function read()
    {
        $query = "SELECT * FROM " . $this->table_name;
        $result = $this->conn->query($query);
        return $result;
    }

    // Método para actualizar un usuario
    public function update()
    {
        $query = "UPDATE " . $this->table_name . " 
                  SET name_user = ?, lastname_user = ?, email_user = ?, password_user = ?, updated_at_user = NOW()
                  WHERE id_user = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssssi", $this->name_user, $this->lastname_user, $this->email_user, $this->password_user, $this->id_user);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Método para eliminar un usuario
    public function delete()
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_user = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $this->id_user);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
