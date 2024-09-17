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

session_start(); // Iniciar la sesión después de configurar las cookies de sesión

if (!isset($_SESSION['user_id'])) {
    header("Location: ../pages/login.php"); // Redirigir al login si no hay sesión
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="/design_system/public/assets/css/header.css">
    <link rel="stylesheet" href="/design_system/public/assets/css/sidebar.css">
    <link rel="stylesheet" href="/design_system/public/assets/css/footer.css">
    <link rel="stylesheet" href="/design_system/public/assets/css/dashboard.css">
</head>

<body>
    <div class="wrapper">
        <!-- Incluir el header -->
        <?php include '../includes/header.php'; ?>

        <div class="content-wrapper">
            <!-- Incluir el sidebar -->
            <?php include '../includes/sidebar.php'; ?>

            <div class="main-content">
                <h1>Bienvenido al Dashboard</h1>
                <p>Has iniciado sesión correctamente.</p>
            </div>
        </div>

        <!-- Incluir el footer -->
        <?php include '../includes/footer.php'; ?>
    </div>
</body>

</html>