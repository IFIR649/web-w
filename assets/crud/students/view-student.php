<?php
include '../../../php/conexion.php'; // Cambia la ruta si es necesario

// Verificar si se proporciona el ID de la matrícula
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $matricula = $_GET['id'];

    // Consultar los detalles del estudiante
    $query = "SELECT matricula, CONCAT(nombre, ' ', apellido_paterno, ' ', apellido_materno) AS nombre_completo, correo, estado, fecha_inscripcion, num_alumno 
              FROM alumnos 
              WHERE matricula = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $matricula);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $estudiante = $result->fetch_assoc();
    } else {
        echo "<script>alert('Estudiante no encontrado.'); window.location.href = '../../../students.php';</script>";
        exit;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<script>alert('No se proporcionó un ID válido.'); window.location.href = '../../../students.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Ver Estudiante - Lingus</title>
    <link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/Nunito.css">
    <link rel="stylesheet" href="../../fonts/fontawesome-all.min.css">
    <style>
        body {
            background-color: #f4f4f9;
        }

        .form-container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-label {
            font-weight: bold;
            color: #333;
        }

        .form-control-plaintext {
            border: none;
            padding: 0;
            color: #1f3c88;
            font-weight: 600;
        }

        .btn-secondary {
            background-color: #d3d3d3;
            border: none;
        }

        .btn-secondary:hover {
            background-color: #b5b5b5;
        }

        .form-title {
            text-align: center;
            font-size: 1.6rem;
            color: #1f3c88;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <h3 class="form-title">Detalles del Estudiante</h3>
        <div class="mb-3">
            <label class="form-label">Matrícula:</label>
            <p class="form-control-plaintext"><?php echo $estudiante['matricula']; ?></p>
        </div>
        <div class="mb-3">
            <label class="form-label">Nombre Completo:</label>
            <p class="form-control-plaintext"><?php echo $estudiante['nombre_completo']; ?></p>
        </div>
        <div class="mb-3">
            <label class="form-label">Correo Electrónico:</label>
            <p class="form-control-plaintext"><?php echo $estudiante['correo'] ?? 'No disponible'; ?></p>
        </div>
        <div class="mb-3">
            <label class="form-label">Estado:</label>
            <p class="form-control-plaintext"><?php echo $estudiante['estado'] === 'activo' ? 'Activo' : 'Inactivo'; ?></p>
        </div>
        <div class="mb-3">
            <label class="form-label">Fecha de Inscripción:</label>
            <p class="form-control-plaintext"><?php echo $estudiante['fecha_inscripcion']; ?></p>
        </div>
        <div class="mb-3">
            <label class="form-label">Número de Alumno:</label>
            <p class="form-control-plaintext"><?php echo $estudiante['num_alumno']; ?></p>
        </div>

        <div class="d-flex justify-content-end">
            <a href="../../../students.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <script src="../../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>
