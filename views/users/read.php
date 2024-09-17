<?php
// Incluir el archivo de la base de datos y el modelo User
require_once '../../config/database.php';
require_once '../../models/user.model.php';

// Crear una instancia de la base de datos
$database = new Database();
$db = $database->getConnection();

// Crear una instancia del modelo User
$user = new User($db);

// Obtener todos los usuarios
$result = $user->read();

if ($result->num_rows > 0) {
    echo "<table border='1'>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Email</th>
                <th>Acciones</th>
            </tr>";

    // Iterar sobre los resultados
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['id_user'] . "</td>";
        echo "<td>" . $row['name_user'] . "</td>";
        echo "<td>" . $row['lastname_user'] . "</td>";
        echo "<td>" . $row['email_user'] . "</td>";
        echo "<td><a href='update.php?id=" . $row['id_user'] . "'>Editar</a> | 
                  <a href='delete.php?id=" . $row['id_user'] . "'>Eliminar</a></td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No se encontraron usuarios.";
}
