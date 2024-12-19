<?php
include '../../../php/conexion.php'; // Incluir la conexión a la base de datos

if (isset($_GET['id'])) {
    $teacherId = intval($_GET['id']);

    // Preparar la consulta SQL para obtener los detalles del maestro
    $query = "SELECT id_maestro, nombre, apellido_paterno, apellido_materno, correo, horas_tot, certificado FROM maestros WHERE id_maestro = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $teacherId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $teacher = $result->fetch_assoc();
    } else {
        echo "<script>alert('Maestro no encontrado.'); window.location.href = '../../../teachers.php';</script>";
        exit();
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<script>alert('ID de maestro no proporcionado.'); window.location.href = '../../../teachers.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Ver Maestro - Lingus</title>
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
            color: #1f3c88;
        }

        .form-value {
            font-size: 1.1rem;
            color: #333;
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
    <div class="form-container mt-5">
        <h3 class="form-title">Detalles del Maestro</h3>

        <!-- Detalles del Maestro -->
        <div class="mb-3">
            <label class="form-label">Nombre:</label>
            <p class="form-value"><?php echo htmlspecialchars($teacher['nombre']); ?></p>
        </div>
        <div class="mb-3">
            <label class="form-label">Apellido Paterno:</label>
            <p class="form-value"><?php echo htmlspecialchars($teacher['apellido_paterno']); ?></p>
        </div>
        <div class="mb-3">
            <label class="form-label">Apellido Materno:</label>
            <p class="form-value"><?php echo htmlspecialchars($teacher['apellido_materno']); ?></p>
        </div>
        <div class="mb-3">
            <label class="form-label">Correo:</label>
            <p class="form-value"><?php echo htmlspecialchars($teacher['correo']); ?></p>
        </div>
        <div class="mb-3">
            <label class="form-label">Horas Totales:</label>
            <p class="form-value"><?php echo htmlspecialchars($teacher['horas_tot']); ?></p>
        </div>
        <div class="mb-3">
            <label class="form-label">Certificado:</label>
            <p class="form-value"><?php echo htmlspecialchars($teacher['certificado']); ?></p>
        </div>

        <!-- Botón para regresar -->
        <div class="text-end mt-4">
            <a href="../../../teachers.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <script src="../../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>