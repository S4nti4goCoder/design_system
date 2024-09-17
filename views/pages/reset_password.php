<?php
// Iniciar la sesión
session_start();

require_once '../../config/database.php'; // Asegúrate de que la ruta sea correcta
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php'; // Si estás usando Composer para instalar PHPMailer

// Inicializar variables
$error_message = '';
$success_message = '';

// Verificar si el token está presente en la URL
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Conexión a la base de datos
    $database = new Database();
    $db = $database->getConnection();

    // Verificar si el token es válido y no ha expirado
    $query = "SELECT * FROM users WHERE reset_token = ? AND reset_token_expire > NOW()";
    $stmt = $db->prepare($query);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // Verificar si se envió el formulario para cambiar la contraseña
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validar las contraseñas ingresadas
            if (empty($_POST['password_user']) || empty($_POST['confirm_password'])) {
                $error_message = "Ambos campos de contraseña son obligatorios.";
            } elseif ($_POST['password_user'] !== $_POST['confirm_password']) {
                $error_message = "Las contraseñas no coinciden.";
            } elseif (strlen($_POST['password_user']) < 6) {
                $error_message = "La contraseña debe tener al menos 6 caracteres.";
            } else {
                // Actualizar la contraseña en la base de datos
                $new_password = password_hash($_POST['password_user'], PASSWORD_BCRYPT);
                $query = "UPDATE users SET password_user = ?, reset_token = NULL, reset_token_expire = NULL WHERE id_user = ?";
                $stmt = $db->prepare($query);
                $stmt->bind_param("si", $new_password, $user['id_user']);
                if ($stmt->execute()) {
                    $success_message = "Tu contraseña ha sido actualizada correctamente.";
                } else {
                    $error_message = "Error al actualizar la contraseña. Por favor, inténtalo de nuevo.";
                }
            }
        }
    } else {
        $error_message = "El enlace de restablecimiento es inválido o ha expirado.";
    }
} else {
    $error_message = "Token de restablecimiento faltante o inválido.";
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña</title>
    <link rel="stylesheet" href="../../public/assets/css/reset_password.css">
</head>

<body>
    <div class="reset-container">
        <?php if (!empty($error_message)): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <?php if (!empty($success_message)): ?>
            <div class="success-message"><?php echo $success_message; ?></div>
            <p><a href="login.php">Volver al inicio de sesión</a></p>
        <?php else: ?>
            <h2>Restablecer tu contraseña</h2>
            <form action="" method="POST">
                <label for="password_user">Nueva Contraseña:</label>
                <input type="password" name="password_user" required>

                <label for="confirm_password">Confirmar Contraseña:</label>
                <input type="password" name="confirm_password" required>

                <input type="submit" value="Restablecer Contraseña">
            </form>
        <?php endif; ?>
    </div>
</body>

</html>