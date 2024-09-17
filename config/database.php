<?php
class Database
{
    private $host = "localhost";
    private $db_name = "design_system_db"; // Nombre de la base de datos
    private $username = "root"; // Usuario de MySQL
    private $password = ""; // Contraseña de MySQL

    public $conn;

    // Conexión a la base de datos usando MySQLi
    public function getConnection()
    {
        $this->conn = null; // Asegurar que esté vacío inicialmente

        try {
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);

            // Verificar si la conexión fue exitosa
            if ($this->conn->connect_error) {
                throw new Exception("Error de conexión: " . $this->conn->connect_error);
            }
        } catch (Exception $e) {
            echo "Error al intentar conectar a la base de datos: " . $e->getMessage();
        }

        return $this->conn;
    }
}
