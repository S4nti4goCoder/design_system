<?php
// Incluir el archivo de la base de datos y el modelo User
require_once '../../config/database.php';
require_once '../../models/user.model.php';

// Crear una instancia de la base de datos
$database = new Database();
$db = $database->getConnection();

// Crear una instancia del modelo User
$user = new User($db);

// Verificar si se envió un ID en la URL
if (isset($_GET['id'])) {
    // Asignar el ID al objeto User
    $user->id_user = $_GET['id'];

    // Intentar eliminar el usuario
    if ($user->delete()) {
        echo "Usuario eliminado exitosamente.";
    } else {
        echo "Error al eliminar el usuario.";
    }
}
