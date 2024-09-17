<?php
// Iniciar la sesión
session_start();

require_once '../../config/database.php'; // Asegúrate de que la ruta sea correcta
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php'; // Si estás usando Composer para instalar PHPMailer

// Habilitar mensajes de error
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inicializar variables
$error_message = '';
$success_message = '';

// Verificar si el formulario ha sido enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validar el email
    if (empty($_POST['email_user'])) {
        $error_message = "El campo de correo es obligatorio.";
    } elseif (!filter_var($_POST['email_user'], FILTER_VALIDATE_EMAIL)) {
        $error_message = "El correo no tiene un formato válido.";
    } else {
        // Conexión a la base de datos
        $database = new Database();
        $db = $database->getConnection();

        $email_user = $_POST['email_user'];

        // Verificar si el email existe en la base de datos
        $query = "SELECT * FROM users WHERE email_user = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("s", $email_user);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            // El email existe, generar un token de restablecimiento
            $token = bin2hex(random_bytes(50)); // Generar un token único
            $reset_link = "http://localhost/design_system/views/pages/reset_password.php?token=" . $token;

            // Guardar el token en la base de datos
            $query = "UPDATE users SET reset_token = ?, reset_token_expire = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email_user = ?";
            $stmt = $db->prepare($query);
            $stmt->bind_param("ss", $token, $email_user);
            $stmt->execute();

            // Configurar y enviar el correo electrónico usando PHPMailer
            $mail = new PHPMailer(true);

            try {
                // Configuración del servidor de correo (Mailtrap)
                $mail->isSMTP();
                $mail->Host = 'sandbox.smtp.mailtrap.io';  // Mailtrap SMTP
                $mail->SMTPAuth = true;
                $mail->Username = ''; // Tu Username de Mailtrap
                $mail->Password = ''; // Tu Password de Mailtrap
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 2525; // Puerto de Mailtrap

                // Configuración del correo
                $mail->setFrom('no-reply@example.com', 'Sistema de Recuperación');
                $mail->addAddress($email_user); // El correo del usuario

                // Contenido del correo
                $mail->isHTML(true);
                $mail->Subject = 'Restablecimiento de contraseña';
                $mail->Body    = 'Haz clic en el siguiente enlace para restablecer tu contraseña: <a href="' . $reset_link . '">Restablecer Contraseña</a>';

                // Enviar el correo
                $mail->send();

                // Redirigir al login después de enviar el correo
                $_SESSION['success_message'] = "Se ha enviado un enlace de recuperación a tu correo electrónico.";
                header("Location: login.php");
                exit;
            } catch (Exception $e) {
                $error_message = "No se pudo enviar el correo. Error de Mailer: {$mail->ErrorInfo}";
            }
        } else {
            $error_message = "No se encontró un usuario con ese correo electrónico.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../public/assets/css/login.css">
    <title>Recuperar Contraseña</title>
</head>

<body>
    <div class="login-container">
        <h2>Recuperar Contraseña</h2>

        <!-- Mostrar el mensaje de éxito si existe -->
        <?php if (!empty($success_message)): ?>
            <div class="success-message">
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>

        <!-- Mostrar el mensaje de error si existe -->
        <?php if (!empty($error_message)): ?>
            <div class="error-message">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <form action="forgot_password.php" method="POST">
            <label for="email_user">Ingresa tu correo electrónico:</label>
            <input type="email" name="email_user" required>

            <input type="submit" value="Enviar Enlace de Recuperación">
        </form>

        <p><a href="login.php">Volver al inicio de sesión</a></p>
    </div>
</body>

</html>