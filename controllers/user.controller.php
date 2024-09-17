<?php
// Incluir el archivo de la base de datos y el modelo User
require_once '../config/database.php';
require_once '../models/user.model.php';

// Crear una instancia de la base de datos
$database = new Database();
$db = $database->getConnection();

// Crear una instancia del modelo User
$user = new User($db);

// Verificar si se envió el formulario para crear un usuario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Asignar los valores del formulario al objeto User
    $user->name_user = $_POST['name_user'];
    $user->lastname_user = $_POST['lastname_user'];
    $user->email_user = $_POST['email_user'];
    $user->password_user = password_hash($_POST['password_user'], PASSWORD_BCRYPT); // Encriptar la contraseña

    // Intentar crear el usuario
    if ($user->create()) {
        echo "Usuario creado exitosamente.";
    } else {
        echo "Error al crear el usuario.";
    }
}
