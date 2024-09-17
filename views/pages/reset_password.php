<?php
// Iniciar la sesión
session_start();

require_once '../../config/database.php'; // Asegúrate de que la ruta es correcta

// Inicializar variables
$error_message = '';
$success_message = '';

// Verificar si se ha proporcionado un token
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Conexión a la base de datos
    $database = new Database();
    $db = $database->getConnection();

    // Buscar el token en la base de datos
    $query = "SELECT * FROM users WHERE reset_token = ? AND reset_token_expire > NOW()";
    $stmt = $db->prepare($query);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $user_id = $row['id_user'];

        // Verificar si el formulario ha sido enviado
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $new_password = $_POST['new_password'];
            $confirm_password = $_POST['confirm_password'];

            // Validar las contraseñas
            if (empty($new_password) || empty($confirm_password)) {
                $error_message = "Ambos campos de contraseña son obligatorios.";
            } elseif ($new_password !== $confirm_password) {
                $error_message = "Las contraseñas no coinciden.";
            } elseif (strlen($new_password) < 6) {
                $error_message = "La contraseña debe tener al menos 6 caracteres.";
            } else {
                // Encriptar la nueva contraseña y actualizar la base de datos
                $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

                $query = "UPDATE users SET password_user = ?, reset_token = NULL, reset_token_expire = NULL WHERE id_user = ?";
                $stmt = $db->prepare($query);
                $stmt->bind_param("si", $hashed_password, $user_id);

                if ($stmt->execute()) {
                    $success_message = "Tu contraseña ha sido restablecida con éxito.";
                } else {
                    $error_message = "Error al restablecer la contraseña. Inténtalo nuevamente.";
                }
            }
        }
    } else {
        $error_message = "El enlace de restablecimiento es inválido o ha expirado.";
    }
} else {
    $error_message = "Token no válido.";
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../public/assets/css/login.css">
    <title>Restablecer Contraseña</title>
</head>

<body>
    <div class="login-container">
        <!-- Mostrar el mensaje de error o éxito si existe -->
        <?php if (!empty($error_message)): ?>
            <div class="error-message">
                <?php echo $error_message; ?>
            </div>
        <?php elseif (!empty($success_message)): ?>
            <div class="success-message">
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>

        <?php if (empty($success_message)): ?>
            <form action="" method="POST">
                <label for="new_password">Nueva contraseña:</label>
                <input type="password" name="new_password" required>

                <label for="confirm_password">Confirmar nueva contraseña:</label>
                <input type="password" name="confirm_password" required>

                <input type="submit" value="Restablecer Contraseña">
            </form>
        <?php endif; ?>
    </div>
</body>

</html>