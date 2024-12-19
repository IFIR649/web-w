<?php
include '../../../php/conexion.php'; // Cambia la ruta si es necesario

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Verificar si se proporciona el ID del maestro
    if (isset($_GET['id'])) {
        $id_maestro = intval($_GET['id']); // Sanitizar el ID del maestro

        // Consultar los datos del maestro
        $query = "SELECT id_maestro, nombre, apellido_paterno, apellido_materno, correo, horas_tot, certificado 
                  FROM maestros 
                  WHERE id_maestro = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $id_maestro);
        $stmt->execute();
        $result = $stmt->get_result();

        // Verificar si se encontró al maestro
        if ($result->num_rows > 0) {
            $maestro = $result->fetch_assoc();
        } else {
            echo "<script>alert('Maestro no encontrado.'); window.location.href = '../../../teachers.php';</script>";
            exit;
        }
        $stmt->close();
    } else {
        echo "<script>alert('No se proporcionó un ID válido.'); window.location.href = '../../../teachers.php';</script>";
        exit;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Procesar la actualización del maestro
    $id_maestro = $_POST['id_maestro'];
    $nombre = $_POST['nombre'];
    $apellido_paterno = $_POST['apellido_paterno'];
    $apellido_materno = $_POST['apellido_materno'];
    $correo = $_POST['correo'];
    $horas_tot = $_POST['horas_tot'];
    $certificado = $_POST['certificado'];

    // Validar si el ID del maestro está presente
    if (empty($id_maestro)) {
        echo "<script>alert('ID de maestro no válido.'); window.history.back();</script>";
        exit;
    }

    // Preparar la consulta de actualización
    $query = "UPDATE maestros SET 
                nombre = ?, 
                apellido_paterno = ?, 
                apellido_materno = ?, 
                correo = ?, 
                horas_tot = ?, 
                certificado = ? 
              WHERE id_maestro = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param(
        "ssssdsi",
        $nombre,
        $apellido_paterno,
        $apellido_materno,
        $correo,
        $horas_tot,
        $certificado,
        $id_maestro
    );

    // Ejecutar la consulta y verificar si fue exitosa
    if ($stmt->execute()) {
        echo "<script>alert('Maestro actualizado correctamente.'); window.location.href = '../../../teachers.php';</script>";
    } else {
        echo "<script>alert('Error al actualizar el maestro.'); window.history.back();</script>";
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
    <title>Editar Maestro - Lingus</title>
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
        <h3 class="form-title">Editar Maestro</h3>
        <form id="editTeacherForm" action="" method="POST">
            <!-- ID Oculto -->
            <input type="hidden" id="hiddenId" name="id_maestro" value="<?php echo $maestro['id_maestro']; ?>">

            <!-- Nombre Completo -->
            <div class="mb-4">
                <label for="nombre" class="form-label required">Nombre</label>
                <input type="text" id="nombre" name="nombre" class="form-control" value="<?php echo $maestro['nombre']; ?>" required>
            </div>
            <div class="mb-4">
                <label for="apellido_paterno" class="form-label required">Apellido Paterno</label>
                <input type="text" id="apellido_paterno" name="apellido_paterno" class="form-control" value="<?php echo $maestro['apellido_paterno']; ?>" required>
            </div>
            <div class="mb-4">
                <label for="apellido_materno" class="form-label">Apellido Materno</label>
                <input type="text" id="apellido_materno" name="apellido_materno" class="form-control" value="<?php echo $maestro['apellido_materno']; ?>">
            </div>

            <!-- Correo Electrónico -->
            <div class="mb-4">
                <label for="correo" class="form-label required">Correo Electrónico</label>
                <input type="email" id="correo" name="correo" class="form-control" value="<?php echo $maestro['correo']; ?>" required>
            </div>

            <!-- Horas Totales -->
            <div class="mb-4">
                <label for="horas_tot" class="form-label required">Horas Totales</label>
                <input type="number" id="horas_tot" name="horas_tot" class="form-control" value="<?php echo $maestro['horas_tot']; ?>" required>
            </div>

            <!-- Certificación -->
            <div class="mb-4">
                <label for="certificado" class="form-label">Certificación</label>
                <input type="text" id="certificado" name="certificado" class="form-control" value="<?php echo $maestro['certificado']; ?>">
            </div>

            <!-- Botones -->
            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
                <a href="../../../teachers.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</body>

</html>
