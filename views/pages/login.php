<?php
// Configurar las cookies de sesión seguras antes de iniciar la sesión
session_set_cookie_params([
    'lifetime' => 0, // La cookie de sesión se eliminará al cerrar el navegador
    'path' => '/',
    'domain' => '', // Ajusta esto si tienes un dominio
    'secure' => true, // Solo envía la cookie a través de HTTPS
    'httponly' => true, // Impide el acceso a la cookie desde JavaScript
    'samesite' => 'Strict' // Evita el envío de cookies en solicitudes cruzadas
]);

session_start(); // Ahora puedes iniciar la sesión

// Si ya hay sesión, redirigir al dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

require_once '../../config/database.php'; // Asegúrate de que la ruta es correcta
require_once '../../models/user.model.php';

// Evitar el almacenamiento en caché
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$error_message = ''; // Variable para almacenar el mensaje de error

// Crear una instancia de la base de datos
$database = new Database();
$db = $database->getConnection();

// Verificar si la conexión a la base de datos es válida
if (!$db) {
    die("Error: No se pudo conectar a la base de datos.");
}

// Verificar si el formulario ha sido enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validación de entrada
    if (empty($_POST['email_user'])) {
        $error_message = "El campo de correo es obligatorio.";
    } elseif (!filter_var($_POST['email_user'], FILTER_VALIDATE_EMAIL)) {
        $error_message = "El correo no tiene un formato válido.";
    }

    if (empty($_POST['password_user'])) {
        $error_message = "La contraseña es obligatoria.";
    } elseif (strlen($_POST['password_user']) < 6) {
        $error_message = "La contraseña debe tener al menos 6 caracteres.";
    }

    // Si no hay errores, proceder con la lógica del login
    if (empty($error_message)) {
        $email_user = $_POST['email_user'];
        $password_user = $_POST['password_user'];

        // Consulta para buscar al usuario por su email
        $query = "SELECT * FROM users WHERE email_user = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("s", $email_user);
        $stmt->execute();
        $result = $stmt->get_result();

        // Verificar si se encontró el usuario
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();

            // Verificar la contraseña usando password_verify
            if (password_verify($password_user, $row['password_user'])) {
                // Contraseña correcta, iniciar sesión
                session_regenerate_id(true);
                $_SESSION['user_id'] = $row['id_user'];
                $_SESSION['email_user'] = $row['email_user'];
                header("Location: dashboard.php");
                exit;
            } else {
                $error_message = "Contraseña incorrecta.";
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
    <title>Iniciar Sesión</title>
</head>

<body>
    <div class="login-container">
        <!-- Mostrar el mensaje de error si existe -->
        <?php if (!empty($error_message)): ?>
            <div class="error-message">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <label for="email_user">Email:</label>
            <input type="email" name="email_user" required>

            <label for="password_user">Contraseña:</label>
            <input type="password" name="password_user" required>

            <input type="submit" value="Iniciar Sesión">
        </form>
    </div>
</body>

</html>