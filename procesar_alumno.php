<?php
include 'conexion.php'; // Incluye el archivo de conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Captura de datos del formulario
    $nombre = $_POST['nombre'];
    $apellido_paterno = $_POST['apellido_paterno'];
    $apellido_materno = $_POST['apellido_materno'];
    $correo = $_POST['correo'];
    $num_alumno = $_POST['num_alumno'];
    $fecha_inscripcion = $_POST['fecha_inscripcion'];
    $estado = $_POST['estado'];

    // Inserción de datos a la tabla alumnos
    $query = "INSERT INTO alumnos (nombre, apellido_paterno, apellido_materno, correo, fecha_inscripcion, estado, num_alumno) 
              VALUES ('$nombre', '$apellido_paterno', '$apellido_materno', '$correo', '$fecha_inscripcion', '$estado', '$num_alumno')";

    if ($conn->query($query) === TRUE) {
        echo "Alumno agregado exitosamente.";
    } else {
        echo "Error al agregar el alumno: " . $conn->error;
    }

    // Cierra la conexión
    $conn->close();
}
?>
