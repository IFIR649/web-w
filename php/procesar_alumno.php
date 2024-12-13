<?php
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $apellido_paterno = $_POST['apellido_paterno'];
    $apellido_materno = $_POST['apellido_materno'];
    $correo = $_POST['correo'];
    $num_alumno = $_POST['num_alumno'];
    $estado = $_POST['estado'];
    $fecha_inscripcion = date('Y-m-d');

    $query = "INSERT INTO alumnos (nombre, apellido_paterno, apellido_materno, correo, fecha_inscripcion, estado, num_alumno)
              VALUES ('$nombre', '$apellido_paterno', '$apellido_materno', '$correo', '$fecha_inscripcion', '$estado', '$num_alumno')";

    if ($conn->query($query) === TRUE) {
        header("Location: http://localhost/lingus/web-w/students.php"); // Cambia esta página por la que prefieras
        exit(); // Asegura que el script se detenga después de la redirección
    } else {
        echo "Error al guardar los datos: " . $conn->error;
    }

    $conn->close();
}
?>
