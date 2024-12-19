<?php
include '../../../php/conexion.php'; // Cambia la ruta si es necesario

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id'])) {
        $id_grupo = intval($_GET['id']);

        // Consultar los alumnos del grupo
        $queryAlumnos = "SELECT gp.matricula, CONCAT(a.nombre, ' ', a.apellido_paterno, ' ', a.apellido_materno) AS nombre
                         FROM grupo_participantes gp
                         JOIN alumnos a ON gp.matricula = a.matricula
                         WHERE gp.id_grupo = ?";
        $stmtAlumnos = $conn->prepare($queryAlumnos);
        $stmtAlumnos->bind_param('i', $id_grupo);
        $stmtAlumnos->execute();
        $resultAlumnos = $stmtAlumnos->get_result();
        $alumnos = [];
        while ($row = $resultAlumnos->fetch_assoc()) {
            $alumnos[] = $row;
        }
        $stmtAlumnos->close();

        // Consultar todos los alumnos para el dropdown
        $queryAllAlumnos = "SELECT matricula, CONCAT(nombre, ' ', apellido_paterno, ' ', apellido_materno) AS nombre_completo FROM alumnos";
        $resultAllAlumnos = $conn->query($queryAllAlumnos);
        $allAlumnosList = [];
        while ($row = $resultAllAlumnos->fetch_assoc()) {
            $allAlumnosList[] = $row;
        }

        // Consultar el id_maestro del grupo
        $queryMaestro = "SELECT id_maestro FROM grupo_participantes WHERE id_grupo = ? LIMIT 1";
        $stmtMaestro = $conn->prepare($queryMaestro);
        $stmtMaestro->bind_param('i', $id_grupo);
        $stmtMaestro->execute();
        $resultMaestro = $stmtMaestro->get_result();
        $maestro = $resultMaestro->fetch_assoc();
        $id_maestro = $maestro['id_maestro'];
        $stmtMaestro->close();
    } else {
        echo "<script>alert('No se proporcionó un ID válido.'); window.location.href = '../../../groups.php';</script>";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Editar Alumnos - Lingus</title>
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
        <h3 class="form-title">Editar Alumnos</h3>
        <form id="editStudentsForm" action="manage-students.php" method="POST">
            <input type="hidden" name="id_grupo" value="<?php echo htmlspecialchars($id_grupo); ?>">
            <input type="hidden" name="id_maestro" value="<?php echo htmlspecialchars($id_maestro); ?>">

            <!-- Alumnos -->
            <div class="mb-4">
                <label class="form-label required">Alumnos</label>
                <div id="alumnosContainer">
                    <?php foreach ($alumnos as $alumno): ?>
                        <div class="d-flex mb-2 alumno-item">
                            <input type="text" class="form-control me-2" value="<?php echo htmlspecialchars($alumno['nombre']); ?>" readonly>
                            <input type="hidden" name="existing_alumnos[]" value="<?php echo $alumno['matricula']; ?>">
                         
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="btn btn-secondary" onclick="addAlumno()">Agregar Alumno</button>
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
        function addAlumno() {
            const container = document.getElementById('alumnosContainer');
            const div = document.createElement('div');
            div.className = 'd-flex mb-2 alumno-item';
            div.innerHTML = `
                <select name="new_alumnos[]" class="form-select me-2" required>
                    <option value="">Seleccione un alumno</option>
                    <?php foreach ($allAlumnosList as $alumno): ?>
                        <option value="<?php echo $alumno['matricula']; ?>"><?php echo htmlspecialchars($alumno['nombre_completo']); ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="button" class="btn btn-danger" onclick="removeAlumno(this)">Eliminar</button>
            `;
            container.appendChild(div);
        }

        function removeAlumno(button) {
            const div = button.parentElement;
            div.remove();
        }
    </script>
</body>

</html>