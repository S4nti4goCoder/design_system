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
    // Obtener el usuario por ID
    $user->id_user = $_GET['id'];
    $result = $user->read();

    if ($result->num_rows == 1) {
        // Rellenar los datos del usuario
        $row = $result->fetch_assoc();
        $user->name_user = $row['name_user'];
        $user->lastname_user = $row['lastname_user'];
        $user->email_user = $row['email_user'];
    }
}

// Verificar si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Asignar los valores del formulario al objeto User
    $user->name_user = $_POST['name_user'];
    $user->lastname_user = $_POST['lastname_user'];
    $user->email_user = $_POST['email_user'];

    // Intentar actualizar el usuario
    if ($user->update()) {
        echo "Usuario actualizado exitosamente.";
    } else {
        echo "Error al actualizar el usuario.";
    }
}
?>

<form action="" method="POST">
    <label for="name_user">Nombre:</label>
    <input type="text" name="name_user" value="<?php echo $user->name_user; ?>" required>

    <label for="lastname_user">Apellido:</label>
    <input type="text" name="lastname_user" value="<?php echo $user->lastname_user; ?>" required>

    <label for="email_user">Email:</label>
    <input type="email" name="email_user" value="<?php echo $user->email_user; ?>" required>

    <input type="submit" value="Actualizar Usuario">
</form>