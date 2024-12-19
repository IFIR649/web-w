<?php
include '../../../php/conexion.php'; // Cambia la ruta si es necesario

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Verificar si se proporciona el ID del grupo
    if (isset($_GET['id'])) {
        $id_grupo = intval($_GET['id']); // Sanitizar el ID del grupo

        // Consultar los datos del grupo
        $query = "SELECT id_grupo, num, costo_hora, intensidad, id_idioma, id_libro, id_level, horas_tot, fecha_inicio, fecha_fin 
                  FROM grupos 
                  WHERE id_grupo = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $id_grupo);
        $stmt->execute();
        $result = $stmt->get_result();

        // Verificar si se encontró el grupo
        if ($result->num_rows > 0) {
            $grupo = $result->fetch_assoc();
        } else {
            echo "<script>alert('Grupo no encontrado.'); window.location.href = '../../../groups.php';</script>";
            exit;
        }
        $stmt->close();
    } else {
        echo "<script>alert('No se proporcionó un ID válido.'); window.location.href = '../../../groups.php';</script>";
        exit;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Procesar la actualización del grupo
    $id_grupo = $_POST['id_grupo'];
    $num = $_POST['num'];
    $costo_hora = $_POST['costo_hora'];
    $intensidad = $_POST['intensidad'];
    $id_idioma = $_POST['id_idioma'];
    $id_libro = $_POST['id_libro'];
    $id_level = $_POST['id_level'];
    $horas_tot = $_POST['horas_tot'];
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];

    // Validar si el ID del grupo está presente
    if (empty($id_grupo)) {
        echo "<script>alert('ID de grupo no válido.'); window.history.back();</script>";
        exit;
    }

    // Preparar la consulta de actualización
    $query = "UPDATE grupos SET 
                num = ?, 
                costo_hora = ?, 
                intensidad = ?, 
                id_idioma = ?, 
                id_libro = ?, 
                id_level = ?, 
                horas_tot = ?, 
                fecha_inicio = ?, 
                fecha_fin = ? 
              WHERE id_grupo = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param(
        "dssiiiiisi",
        $num,
        $costo_hora,
        $intensidad,
        $id_idioma,
        $id_libro,
        $id_level,
        $horas_tot,
        $fecha_inicio,
        $fecha_fin,
        $id_grupo
    );

    // Ejecutar la consulta y verificar si fue exitosa
    if ($stmt->execute()) {
        echo "<script>alert('Grupo actualizado correctamente.'); window.location.href = '../../../groups.php';</script>";
    } else {
        echo "<script>alert('Error al actualizar el grupo.'); window.history.back();</script>";
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
    <title>Editar Grupo - Lingus</title>
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
    <div class="form-container mt-5">
        <h3 class="form-title">Editar Grupo</h3>
        <form id="editGroupForm" action="" method="POST">
            <!-- ID del Grupo -->
            <input type="hidden" name="id_grupo" value="<?php echo $grupo['id_grupo']; ?>">

            <!-- Número de Grupo -->
            <div class="mb-4">
                <label for="num" class="form-label required">Número de Grupo</label>
                <input type="number" id="num" name="num" class="form-control" value="<?php echo $grupo['num']; ?>" required>
            </div>

            <!-- Costo por Hora -->
            <div class="mb-4">
                <label for="costo_hora" class="form-label required">Costo por Hora</label>
                <input type="number" id="costo_hora" name="costo_hora" class="form-control" step="0.01" value="<?php echo $grupo['costo_hora']; ?>" required>
            </div>

            <!-- Intensidad -->
            <div class="mb-4">
                <label for="intensidad" class="form-label required">Intensidad</label>
                <select id="intensidad" name="intensidad" class="form-select" required>
                    <option value="Baja" <?php echo $grupo['intensidad'] === 'Baja' ? 'selected' : ''; ?>>Baja</option>
                    <option value="Media" <?php echo $grupo['intensidad'] === 'Media' ? 'selected' : ''; ?>>Media</option>
                    <option value="Alta" <?php echo $grupo['intensidad'] === 'Alta' ? 'selected' : ''; ?>>Alta</option>
                </select>
            </div>

            <!-- Idioma -->
            <div class="mb-4">
                <label for="id_idioma" class="form-label required">Idioma</label>
                <select id="id_idioma" name="id_idioma" class="form-select" required>
                    <option value="1" <?php echo $grupo['id_idioma'] == 1 ? 'selected' : ''; ?>>Inglés</option>
                    <option value="2" <?php echo $grupo['id_idioma'] == 2 ? 'selected' : ''; ?>>Francés</option>
                    <option value="3" <?php echo $grupo['id_idioma'] == 3 ? 'selected' : ''; ?>>Alemán</option>
                </select>
            </div>

            <!-- Libro -->
            <div class="mb-4">
                <label for="id_libro" class="form-label required">Libro</label>
                <select id="id_libro" name="id_libro" class="form-select" required>
                    <option value="1" <?php echo $grupo['id_libro'] == 1 ? 'selected' : ''; ?>>English Starter</option>
                    <option value="2" <?php echo $grupo['id_libro'] == 2 ? 'selected' : ''; ?>>Intermediate Guide</option>
                </select>
            </div>

            <!-- Nivel -->
            <div class="mb-4">
                <label for="id_level" class="form-label required">Nivel</label>
                <select id="id_level" name="id_level" class="form-select" required>
                    <option value="1" <?php echo $grupo['id_level'] == 1 ? 'selected' : ''; ?>>A1 - Beginner</option>
                    <option value="2" <?php echo $grupo['id_level'] == 2 ? 'selected' : ''; ?>>A2 - Elementary</option>
                    <option value="3" <?php echo $grupo['id_level'] == 3 ? 'selected' : ''; ?>>B1 - Intermediate</option>
                </select>
            </div>

            <!-- Horas Totales -->
            <div class="mb-4">
                <label for="horas_tot" class="form-label required">Horas Totales</label>
                <input type="number" id="horas_tot" name="horas_tot" class="form-control" value="<?php echo $grupo['horas_tot']; ?>" required>
            </div>

            <!-- Fechas -->
            <div class="mb-4">
                <label for="fecha_inicio" class="form-label required">Fecha de Inicio</label>
                <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control" value="<?php echo $grupo['fecha_inicio']; ?>" required>
            </div>
            <div class="mb-4">
                <label for="fecha_fin" class="form-label required">Fecha de Fin</label>
                <input type="date" id="fecha_fin" name="fecha_fin" class="form-control" value="<?php echo $grupo['fecha_fin']; ?>" required>
            </div>

            <!-- Botones -->
            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
                <a href="../../../groups.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</body>

</html>