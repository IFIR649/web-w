<?php
include '../../../php/conexion.php'; // Cambia la ruta si es necesario

// Manejar la solicitud POST para insertar datos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $apellido_paterno = $_POST['apellido_paterno'];
    $apellido_materno = $_POST['apellido_materno'];
    $correo = $_POST['correo'];
    $estado = $_POST['estado'];
    $fecha_inscripcion = $_POST['fecha_inscripcion'];
    $num_alumno = $_POST['num_alumno'];

    $query = "INSERT INTO alumnos (nombre, apellido_paterno, apellido_materno, correo, estado, fecha_inscripcion, num_alumno) 
              VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssss", $nombre, $apellido_paterno, $apellido_materno, $correo, $estado, $fecha_inscripcion, $num_alumno);

    if ($stmt->execute()) {
        echo "<script>alert('Alumno agregado correctamente.'); window.location.href = '../../../students.php';</script>";
        exit;
    } else {
        $error = "Error al agregar el alumno. Por favor, intenta nuevamente.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Agregar Alumno - Lingus</title>
    <link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/Nunito.css">
    <link rel="stylesheet" href="../../fonts/fontawesome-all.min.css">
    <style>
        body {
            background-color: #f4f4f9;
        }

        .form-container {
            max-width: 700px;
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

        .form-control {
            border: none;
            border-bottom: 2px solid #d0d0d0;
            border-radius: 0;
            box-shadow: none;
        }

        .form-control:focus {
            border-color: #1f3c88;
            box-shadow: none;
        }

        .btn-primary {
            background-color: #1f3c88;
            border: none;
        }

        .btn-primary:hover {
            background-color: #1d2d50;
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
        <h3 class="form-title">Agregar Alumno</h3>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger text-center">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        <form id="addStudentForm" method="POST">
            <!-- Datos del Alumno -->
            <div class="mb-4">
                <label for="nombre" class="form-label required">Nombre</label>
                <input type="text" id="nombre" name="nombre" class="form-control" placeholder="Nombre del alumno" required>
            </div>
            <div class="mb-4">
                <label for="apellido_paterno" class="form-label required">Apellido Paterno</label>
                <input type="text" id="apellido_paterno" name="apellido_paterno" class="form-control" placeholder="Apellido paterno" required>
            </div>
            <div class="mb-4">
                <label for="apellido_materno" class="form-label">Apellido Materno</label>
                <input type="text" id="apellido_materno" name="apellido_materno" class="form-control" placeholder="Apellido materno">
            </div>
            <div class="mb-4">
                <label for="correo" class="form-label required">Correo Electrónico</label>
                <input type="email" id="correo" name="correo" class="form-control" placeholder="Correo del alumno" >
            </div>

            <!-- Estado -->
            <div class="mb-4">
                <label for="estado" class="form-label required">Estado</label>
                <select id="estado" name="estado" class="form-select" required>
                    <option value="activo">Activo</option>
                    <option value="inactivo">Inactivo</option>
                </select>
            </div>

            <!-- Fecha de Inscripción -->
            <div class="mb-4">
                <label for="fecha_inscripcion" class="form-label required">Fecha de Inscripción</label>
                <input type="date" id="fecha_inscripcion" name="fecha_inscripcion" class="form-control" >
            </div>

            <!-- Número de Alumno -->
            <div class="mb-4">
                <label for="num_alumno" class="form-label required">Número de Alumno</label>
                <input type="text" id="num_alumno" name="num_alumno" class="form-control" placeholder="Número único del alumno" >
            </div>

            <!-- Botones -->
            <div class="d-flex justify-content-between mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar
                </button>
                <a href="../../../students.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Cancelar
                </a>
            </div>
        </form>
    </div>

    <script src="../../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>
