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

        // Consultar los niveles disponibles
        $queryLevels = "SELECT id_level, name FROM level";
        $resultLevels = $conn->query($queryLevels);
        $levels = [];
        if ($resultLevels->num_rows > 0) {
            while ($row = $resultLevels->fetch_assoc()) {
                $levels[] = $row;
            }
        }

        $queryLibros = "SELECT id_libro, nombre FROM libro";
        $resultLibros = $conn->query($queryLibros);
        $libros = [];
        if ($resultLibros->num_rows > 0) {
            while ($row = $resultLibros->fetch_assoc()) {
                $libros[] = $row;
            }
        }

        // Consultar los idiomas disponibles
        $queryIdiomas = "SELECT id_idioma, nombre FROM idioma";
        $resultIdiomas = $conn->query($queryIdiomas);
        $idiomas = [];
        if ($resultIdiomas->num_rows > 0) {
            while ($row = $resultIdiomas->fetch_assoc()) {
                $idiomas[] = $row;
            }
        }


        // Verificar si se encontró el grupo
        if ($result->num_rows > 0) {
            $grupo = $result->fetch_assoc();
        } else {
            echo "<script>alert('Grupo no encontrado.'); window.location.href = '../../../groups.php';</script>";
            exit;
        }
        $stmt->close();

        // Consultar los horarios del grupo
        $queryHorarios = "SELECT id_horario, dia_semana, hora_inicio, hora_fin FROM horarios WHERE id_grupo = ?";
        $stmtHorarios = $conn->prepare($queryHorarios);
        $stmtHorarios->bind_param('i', $id_grupo);
        $stmtHorarios->execute();
        $resultHorarios = $stmtHorarios->get_result();
        $horarios = [];
        while ($row = $resultHorarios->fetch_assoc()) {
            $horarios[] = $row;
        }
        $stmtHorarios->close();
    } else {
        echo "<script>alert('No se proporcionó un ID válido.'); window.location.href = '../../../groups.php';</script>";
        exit;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_grupo = intval($_POST['id_grupo']);
    $num = intval($_POST['num']);
    $costo_hora = floatval($_POST['costo_hora']);
    $intensidad = $conn->real_escape_string($_POST['intensidad']);
    $id_idioma = intval($_POST['id_idioma']);
    $id_libro = intval($_POST['id_libro']);
    $id_level = intval($_POST['id_level']);
    $horas_tot = intval($_POST['horas_tot']);
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];

    // Actualizar los datos del grupo
    $query = "UPDATE grupos SET num = ?, costo_hora = ?, intensidad = ?, id_idioma = ?, id_libro = ?, id_level = ?, horas_tot = ?, fecha_inicio = ?, fecha_fin = ? WHERE id_grupo = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('issiiisssi', $num, $costo_hora, $intensidad, $id_idioma, $id_libro, $id_level, $horas_tot, $fecha_inicio, $fecha_fin, $id_grupo);

    if ($stmt->execute()) {
        // Eliminar los horarios existentes
        $queryDeleteHorarios = "DELETE FROM horarios WHERE id_grupo = ?";
        $stmtDeleteHorarios = $conn->prepare($queryDeleteHorarios);
        $stmtDeleteHorarios->bind_param('i', $id_grupo);
        $stmtDeleteHorarios->execute();
        $stmtDeleteHorarios->close();

        // Insertar los nuevos horarios
        $dias_semana = $_POST['dia_semana'];
        $horas_inicio = $_POST['hora_inicio'];
        $horas_fin = $_POST['hora_fin'];

        $query_horario = "INSERT INTO horarios (id_grupo, dia_semana, hora_inicio, hora_fin) VALUES (?, ?, ?, ?)";
        $stmt_horario = $conn->prepare($query_horario);

        for ($i = 0; $i < count($dias_semana); $i++) {
            $stmt_horario->bind_param('isss', $id_grupo, $dias_semana[$i], $horas_inicio[$i], $horas_fin[$i]);
            $stmt_horario->execute();
        }

        $stmt_horario->close();
        echo "<script>alert('Grupo y horarios actualizados exitosamente.'); window.location.href = '../../../groups.php';</script>";
    } else {
        echo "<script>alert('Error al actualizar el grupo.');</script>";
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

        .form-label {
            font-weight: bold;
            color: #333;
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

        .btn-danger {
            background-color: #dc3545;
            border: none;
        }

        .btn-danger:hover {
            background-color: #c82333;
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
        <h3 class="form-title">Editar Grupo</h3>
        <form id="editGroupForm" action="" method="POST">
            <input type="hidden" name="id_grupo" value="<?php echo htmlspecialchars($grupo['id_grupo']); ?>">

            <!-- Número de Grupo -->
            <div class="mb-4">
                <label for="num" class="form-label required">Nombre de Grupo</label>
                <input type="text" id="num" name="num" class="form-control" value="<?php echo htmlspecialchars($grupo['num']); ?>" required>
            </div>

            <!-- Costo por Hora -->
            <div class="mb-4">
                <label for="costo_hora" class="form-label required">Costo por Hora</label>
                <input type="number" id="costo_hora" name="costo_hora" class="form-control" value="<?php echo htmlspecialchars($grupo['costo_hora']); ?>" step="0.01" required>
            </div>

            <!-- Intensidad -->
            <div class="mb-3">
                <label for="intensidad" class="form-label">Intensidad</label>
                <input 
                    type="text" 
                    id="intensidad" 
                    name="intensidad" 
                    class="form-control" 
                    placeholder="Escribe la intensidad" 
                    value="<?php echo isset($grupo['intensidad']) ? htmlspecialchars($grupo['intensidad']) : ''; ?>" 
                    required>
            </div>


            <!-- Idioma -->
            <div class="mb-3">
                <label for="id_idioma" class="form-label">Idioma</label>
                <select id="id_idioma" name="id_idioma" class="form-control" required>
                    <option value="" disabled selected>Seleccione un idioma</option>
                    <?php foreach ($idiomas as $idioma): ?>
                        <option value="<?php echo $idioma['id_idioma']; ?>" <?php echo ($grupo['id_idioma'] == $idioma['id_idioma']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($idioma['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Libro -->
            <div class="mb-3">
                <label for="id_libro" class="form-label">Libro</label>
                <select id="id_libro" name="id_libro" class="form-control" required>
                    <option value="" disabled selected>Seleccione un libro</option>
                    <?php foreach ($libros as $libro): ?>
                        <option value="<?php echo $libro['id_libro']; ?>" <?php echo ($grupo['id_libro'] == $libro['id_libro']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($libro['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Nivel -->
            <div class="mb-3">
            <label for="id_level" class="form-label">Nivel</label>
            <select id="id_level" name="id_level" class="form-control" required>
                <option value="" disabled selected>Seleccione un nivel</option>
                <?php foreach ($levels as $level): ?>
                    <option value="<?php echo $level['id_level']; ?>" <?php echo ($grupo['id_level'] == $level['id_level']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($level['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>


            <!-- Horas Totales -->
            <div class="mb-4">
                <label for="horas_tot" class="form-label required">Horas Totales</label>
                <input type="number" id="horas_tot" name="horas_tot" class="form-control" value="<?php echo htmlspecialchars($grupo['horas_tot']); ?>" required>
            </div>

            <!-- Fechas -->
            <div class="mb-4">
                <label for="fecha_inicio" class="form-label required">Fecha de Inicio</label>
                <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control" value="<?php echo htmlspecialchars($grupo['fecha_inicio']); ?>" required>
            </div>
            <div class="mb-4">
                <label for="fecha_fin" class="form-label required">Fecha de Fin</label>
                <input type="date" id="fecha_fin" name="fecha_fin" class="form-control" value="<?php echo htmlspecialchars($grupo['fecha_fin']); ?>" required>
            </div>

            <!-- Horarios -->
            <div class="mb-4">
                <label class="form-label required">Horarios</label>
                <div id="horariosContainer">
                    <?php foreach ($horarios as $horario): ?>
                        <div class="d-flex mb-2 horario-item">
                            <select name="dia_semana[]" class="form-select me-2" required>
                                <option value="">Día de la semana</option>
                                <option value="lunes" <?php echo $horario['dia_semana'] == 'lunes' ? 'selected' : ''; ?>>Lunes</option>
                                <option value="martes" <?php echo $horario['dia_semana'] == 'martes' ? 'selected' : ''; ?>>Martes</option>
                                <option value="miércoles" <?php echo $horario['dia_semana'] == 'miércoles' ? 'selected' : ''; ?>>Miércoles</option>
                                <option value="jueves" <?php echo $horario['dia_semana'] == 'jueves' ? 'selected' : ''; ?>>Jueves</option>
                                <option value="viernes" <?php echo $horario['dia_semana'] == 'viernes' ? 'selected' : ''; ?>>Viernes</option>
                                <option value="sábado" <?php echo $horario['dia_semana'] == 'sábado' ? 'selected' : ''; ?>>Sábado</option>
                                <option value="domingo" <?php echo $horario['dia_semana'] == 'domingo' ? 'selected' : ''; ?>>Domingo</option>
                            </select>
                            <input type="time" name="hora_inicio[]" class="form-control me-2" value="<?php echo htmlspecialchars($horario['hora_inicio']); ?>" required>
                            <input type="time" name="hora_fin[]" class="form-control me-2" value="<?php echo htmlspecialchars($horario['hora_fin']); ?>" required>
                            <button type="button" class="btn btn-danger" onclick="removeHorario(this)">Eliminar</button>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="btn btn-secondary" onclick="addHorario()">Agregar Horario</button>
            </div>

            <!-- Botones -->
            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar
                </button>
                <a href="../../../groups.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Cancelar
                </a>
            </div>
        </form>
    </div>

    <script src="../../bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
        function addHorario() {
            const container = document.getElementById('horariosContainer');
            const div = document.createElement('div');
            div.className = 'd-flex mb-2 horario-item';
            div.innerHTML = `
                <select name="dia_semana[]" class="form-select me-2" required>
                    <option value="">Día de la semana</option>
                    <option value="lunes">Lunes</option>
                    <option value="martes">Martes</option>
                    <option value="miércoles">Miércoles</option>
                    <option value="jueves">Jueves</option>
                    <option value="viernes">Viernes</option>
                    <option value="sábado">Sábado</option>
                    <option value="domingo">Domingo</option>
                </select>
                <input type="time" name="hora_inicio[]" class="form-control me-2" required>
                <input type="time" name="hora_fin[]" class="form-control me-2" required>
                <button type="button" class="btn btn-danger" onclick="removeHorario(this)">Eliminar</button>
            `;
            container.appendChild(div);
        }

        function removeHorario(button) {
            const div = button.parentElement;
            div.remove();
        }
    </script>
</body>

</html>