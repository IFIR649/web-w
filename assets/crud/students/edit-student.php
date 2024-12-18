<?php
include '../../../php/conexion.php'; // Cambia la ruta si es necesario

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Verificar si se proporciona el ID del estudiante
    if (isset($_GET['id'])) {
        $matricula = intval($_GET['id']); // Sanitizar el ID del estudiante

        // Consultar los datos del estudiante
        $query = "SELECT matricula, nombre, apellido_paterno, apellido_materno, correo, estado, fecha_inscripcion, num_alumno 
                  FROM alumnos 
                  WHERE matricula = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $matricula);
        $stmt->execute();
        $result = $stmt->get_result();

        // Verificar si se encontró al estudiante
        if ($result->num_rows > 0) {
            $estudiante = $result->fetch_assoc();
        } else {
            echo "<script>alert('Estudiante no encontrado.'); window.location.href = '../../../students.php';</script>";
            exit;
        }
        $stmt->close();
    } else {
        echo "<script>alert('No se proporcionó un ID válido.'); window.location.href = '../../../students.php';</script>";
        exit;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Procesar la actualización del estudiante
    $matricula = $_POST['hiddenMatricula'];
    $nombre = $_POST['nombre'];
    $apellido_paterno = $_POST['apellido_paterno'];
    $apellido_materno = $_POST['apellido_materno'];
    $correo = $_POST['correo'];
    $estado = $_POST['estado'];
    $fecha_inscripcion = $_POST['fecha_inscripcion'];
    $num_alumno = $_POST['num_alumno'];

    // Validar si la matrícula está presente
    if (empty($matricula)) {
        echo "<script>alert('Matrícula no válida.'); window.history.back();</script>";
        exit;
    }

    // Preparar la consulta de actualización
    $query = "UPDATE alumnos SET 
                nombre = ?, 
                apellido_paterno = ?, 
                apellido_materno = ?, 
                correo = ?, 
                estado = ?, 
                fecha_inscripcion = ?, 
                num_alumno = ? 
              WHERE matricula = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param(
        "sssssssi",
        $nombre,
        $apellido_paterno,
        $apellido_materno,
        $correo,
        $estado,
        $fecha_inscripcion,
        $num_alumno,
        $matricula
    );

    // Ejecutar la consulta y verificar si fue exitosa
    if ($stmt->execute()) {
        echo "<script>alert('Estudiante actualizado correctamente.'); window.location.href = '../../../students.php';</script>";
    } else {
        echo "<script>alert('Error al actualizar el estudiante.'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
    exit;
} else {
    echo "<script>alert('Método de solicitud no válido.'); window.history.back();</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Editar Estudiante - Lingus</title>
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
    </style>
</head>

<body>
    <div class="form-container">
        <h3 class="form-title">Editar Estudiante</h3>
        <form id="editStudentForm" action="" method="POST">
            <!-- Matrícula (Deshabilitada para edición) -->
            <div class="mb-4">
                <label for="matricula" class="form-label">Matrícula</label>
                <input type="text" id="matricula" class="form-control" value="<?php echo $estudiante['matricula']; ?>" disabled>
                <input type="hidden" id="hiddenMatricula" name="hiddenMatricula" value="<?php echo $estudiante['matricula']; ?>">
            </div>

            <!-- Nombre Completo -->
            <div class="mb-4">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" id="nombre" name="nombre" class="form-control" value="<?php echo $estudiante['nombre']; ?>" required>
            </div>
            <div class="mb-4">
                <label for="apellido_paterno" class="form-label">Apellido Paterno</label>
                <input type="text" id="apellido_paterno" name="apellido_paterno" class="form-control" value="<?php echo $estudiante['apellido_paterno']; ?>" required>
            </div>
            <div class="mb-4">
                <label for="apellido_materno" class="form-label">Apellido Materno</label>
                <input type="text" id="apellido_materno" name="apellido_materno" class="form-control" value="<?php echo $estudiante['apellido_materno']; ?>">
            </div>

            <!-- Correo Electrónico -->
            <div class="mb-4">
                <label for="correo" class="form-label">Correo Electrónico</label>
                <input type="email" id="correo" name="correo" class="form-control" value="<?php echo $estudiante['correo']; ?>">
            </div>

            <!-- Estado -->
            <div class="mb-4">
                <label for="estado" class="form-label">Estado</label>
                <select id="estado" name="estado" class="form-control" required>
                    <option value="activo" <?php echo $estudiante['estado'] === 'activo' ? 'selected' : ''; ?>>Activo</option>
                    <option value="inactivo" <?php echo $estudiante['estado'] === 'inactivo' ? 'selected' : ''; ?>>Inactivo</option>
                </select>
            </div>

            <!-- Fecha de Inscripción -->
            <div class="mb-4">
                <label for="fecha_inscripcion" class="form-label">Fecha de Inscripción</label>
                <input type="date" id="fecha_inscripcion" name="fecha_inscripcion" class="form-control" value="<?php echo $estudiante['fecha_inscripcion']; ?>" required>
            </div>

            <!-- Número de Alumno -->
            <div class="mb-4">
                <label for="num_alumno" class="form-label">Número de Alumno</label>
                <input type="text" id="num_alumno" name="num_alumno" class="form-control" value="<?php echo $estudiante['num_alumno']; ?>" required>
            </div>

            <!-- Botones -->
            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
                <a href="../../../students.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</body>

</html>
