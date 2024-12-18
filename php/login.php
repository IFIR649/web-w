<?php
session_start();

// Configuración de la base de datos
$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "lingus"; 

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener datos del formulario
$user = $_POST['username'];
$pass = $_POST['password'];

// Preparar la consulta para evitar inyección SQL
$stmt = $conn->prepare("SELECT * FROM usuarios WHERE correo = ? LIMIT 1");
$stmt->bind_param("s", $user);
$stmt->execute();
$result = $stmt->get_result();

// Verificar si el usuario existe
if ($row = $result->fetch_assoc()) {
    // Comparar contraseñas directamente
    if ($pass === $row['contrasena']) {
        // Almacenar el nombre de usuario en la sesión
        $_SESSION['usuario'] = $row['nombre'];
        header("Location: ../students.php"); // Redirigir al panel de estudiantes
        exit();
    } else {
        echo "<script>
            alert('Contraseña incorrecta.');
            window.location.href = '../index.html';
        </script>";
    }
} else {
    echo "<script>
        alert('Usuario no encontrado.');
        window.location.href = '../index.html';
    </script>";
}

// Cerrar conexión
$stmt->close();
$conn->close();
?>
