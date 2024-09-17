<form action="../../controllers/user.controller.php" method="POST">
    <label for="name_user">Nombre:</label>
    <input type="text" name="name_user" required>

    <label for="lastname_user">Apellido:</label>
    <input type="text" name="lastname_user" required>

    <label for="email_user">Email:</label>
    <input type="email" name="email_user" required>

    <label for="password_user">Contraseña:</label>
    <input type="password" name="password_user" required>

    <input type="submit" value="Crear Usuario">
</form>