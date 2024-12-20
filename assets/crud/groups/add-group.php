<?php
include '../../../php/conexion.php'; // Incluir la conexión a la base de datos
// Consultar idiomas
$queryIdiomas = "SELECT id_idioma, nombre FROM idioma";
$resultIdiomas = $conn->query($queryIdiomas);
$idiomas = [];
if ($resultIdiomas->num_rows > 0) {
    while ($row = $resultIdiomas->fetch_assoc()) {
        $idiomas[] = $row;
    }
}

// Consultar libros
$queryLibros = "SELECT id_libro, nombre FROM libro";
$resultLibros = $conn->query($queryLibros);
$libros = [];
if ($resultLibros->num_rows > 0) {
    while ($row = $resultLibros->fetch_assoc()) {
        $libros[] = $row;
    }
}

// Consultar niveles
$queryNiveles = "SELECT id_level, name FROM level";
$resultNiveles = $conn->query($queryNiveles);
$niveles = [];
if ($resultNiveles->num_rows > 0) {
    while ($row = $resultNiveles->fetch_assoc()) {
        $niveles[] = $row;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capturar datos del formulario
    $num = $_POST['num'];
    $costo_hora = $_POST['costo_hora'];
    $intensidad = $_POST['intensidad']; // Capturar intensidad manualmente
    $id_idioma = $_POST['id_idioma'];
    $id_libro = $_POST['id_libro'];
    $id_level = $_POST['id_level'];
    $horas_tot = $_POST['horas_tot'];
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];

    // Iniciar una transacción
    $conn->begin_transaction();

    try {
        // Insertar grupo
        $stmt_grupo = $conn->prepare("INSERT INTO grupos (num, costo_hora, intensidad, id_idioma, id_libro, id_level, horas_tot, fecha_inicio, fecha_fin) 
                                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt_grupo->bind_param("sdsiiisss", $num, $costo_hora, $intensidad, $id_idioma, $id_libro, $id_level, $horas_tot, $fecha_inicio, $fecha_fin);
        $stmt_grupo->execute();
        $id_grupo = $conn->insert_id;

        // Insertar horarios
        $dias_semana = $_POST['dia_semana'];
        $horas_inicio = $_POST['hora_inicio'];
        $horas_fin = $_POST['hora_fin'];

        $query_horario = "INSERT INTO horarios (id_grupo, dia_semana, hora_inicio, hora_fin) VALUES (?, ?, ?, ?)";
        $stmt_horario = $conn->prepare($query_horario);

        for ($i = 0; $i < count($dias_semana); $i++) {
            $stmt_horario->bind_param('isss', $id_grupo, $dias_semana[$i], $horas_inicio[$i], $horas_fin[$i]);
            $stmt_horario->execute();
        }

        // Insertar participantes en grupo_participantes
        if (!empty($_POST['participantes'])) {
            $stmt_participante = $conn->prepare("INSERT INTO grupo_participantes (id_grupo, id_maestro, matricula) VALUES (?, ?, ?)");

            foreach ($_POST['participantes'] as $participante) {
                $id_maestro = intval($participante['id_maestro']);
                $matricula = intval($participante['matricula']);
                $stmt_participante->bind_param("iii", $id_grupo, $id_maestro, $matricula);
                $stmt_participante->execute();
            }

            $stmt_participante->close();
        }

        // Confirmar la transacción
        $conn->commit();
        echo "<script>alert('Grupo, horarios y participantes agregados exitosamente.'); window.location.href = '../../../groups.php';</script>";
    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        $conn->rollback();
        echo "<script>alert('Error al registrar los datos: " . $e->getMessage() . "');</script>";
    }

    $stmt_grupo->close();
    $stmt_horario->close();
}

$conn->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Agregar Grupo - Lingus</title>
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
        <h3 class="form-title">Agregar Nuevo Grupo</h3>
        <form id="addGroupForm" action="" method="POST">
            <!-- Número de Grupo -->
            <div class="mb-4">
                <label for="num" class="form-label required">Nombre de Grupo</label>
                <input type="text" id="num" name="num" class="form-control" placeholder="Ejemplo: 101" required>
            </div>

            <!-- Costo por Hora -->
            <div class="mb-4">
                <label for="costo_hora" class="form-label required">Costo por Hora</label>
                <input type="number" id="costo_hora" name="costo_hora" class="form-control" placeholder="Ejemplo: 150.50" step="0.01" required>
            </div>

            <!-- Intensidad -->
            <div class="mb-4">
                <label for="intensidad" class="form-label required">Intensidad</label>
                <input 
                    type="text" 
                    id="intensidad" 
                    name="intensidad" 
                    class="form-control" 
                    placeholder="Escribe la intensidad (ejemplo: Alta, Media, Baja)" 
                    required>
            </div>


            <!-- Idioma -->
            <div class="mb-4">
                <label for="id_idioma" class="form-label required">Idioma</label>
                <select id="id_idioma" name="id_idioma" class="form-select" required>
                    <option value="">Seleccione un idioma</option>
                    <?php foreach ($idiomas as $idioma): ?>
                        <option value="<?php echo $idioma['id_idioma']; ?>">
                            <?php echo htmlspecialchars($idioma['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>


            <!-- Libro -->
            <div class="mb-4">
                <label for="id_libro" class="form-label required">Libro</label>
                <select id="id_libro" name="id_libro" class="form-select" required>
                    <option value="">Seleccione un libro</option>
                    <?php foreach ($libros as $libro): ?>
                        <option value="<?php echo $libro['id_libro']; ?>">
                            <?php echo htmlspecialchars($libro['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>


            <!-- Nivel -->
            <div class="mb-4">
                <label for="id_level" class="form-label required">Nivel</label>
                <select id="id_level" name="id_level" class="form-select" required>
                    <option value="">Seleccione un nivel</option>
                    <?php foreach ($niveles as $nivel): ?>
                        <option value="<?php echo $nivel['id_level']; ?>">
                            <?php echo htmlspecialchars($nivel['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>


            <!-- Horas Totales -->
            <div class="mb-4">
                <label for="horas_tot" class="form-label required">Horas Totales</label>
                <input type="number" id="horas_tot" name="horas_tot" class="form-control" placeholder="Ejemplo: 40" >
            </div>

            <!-- Fechas -->
            <div class="mb-4">
                <label for="fecha_inicio" class="form-label required">Fecha de Inicio</label>
                <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control" >
            </div>
            <div class="mb-4">
                <label for="fecha_fin" class="form-label required">Fecha de Fin</label>
                <input type="date" id="fecha_fin" name="fecha_fin" class="form-control" >
            </div>

            <!-- Horarios -->
            <div class="mb-4">
                <label class="form-label required">Horarios</label>
                <div id="horariosContainer">
                    <!-- Horarios se agregarán aquí -->
                </div>
                <button type="button" class="btn btn-secondary" onclick="addHorario()">Agregar Horario</button>
            </div>

            <!-- Participantes -->
            <div class="mb-4">
                <label class="form-label required">Participantes</label>
                <div id="participantesContainer">
                    <!-- Participantes se agregarán aquí -->
                </div>
                <button type="button" class="btn btn-secondary" onclick="addParticipante()">Agregar Participante</button>
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

        function addParticipante() {
            const container = document.getElementById('participantesContainer');
            const index = container.children.length;
            const div = document.createElement('div');
            div.className = 'd-flex mb-2 participante-item';
            div.innerHTML = `
                <label>ID Maestro:</label>
                <input type="number" name="participantes[${index}][id_maestro]" class="form-control me-2" required>
                <label>Matrícula Alumno:</label>
                <input type="number" name="participantes[${index}][matricula]" class="form-control me-2" required>
                <button type="button" class="btn btn-danger" onclick="removeParticipante(this)">Eliminar</button>
            `;
            container.appendChild(div);
        }

        function removeParticipante(button) {
            const div = button.parentElement;
            div.remove();
        }
    </script>
</body>

</html>