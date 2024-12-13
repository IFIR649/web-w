
<?php
// Configuración de la base de datos
$host = "localhost"; // Servidor de la base de datos
$user = "root"; // Usuario de la base de datos
$password = ""; // Contraseña del usuario
$dbname = "lingus"; // Nombre de la base de datos

// Crear conexión
$conn = new mysqli($host, $user, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Conexión exitosa
?>
