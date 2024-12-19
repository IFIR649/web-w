<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Ver Grupo - Lingus</title>
    <link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/Nunito.css">
    <link rel="stylesheet" href="../../fonts/fontawesome-all.min.css">
    <style>
        body {
            background-color: #f4f4f9;
        }

        .form-container {
            max-width: 900px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .section-title {
            font-size: 1.3rem;
            color: #1f3c88;
            margin-top: 20px;
            border-bottom: 2px solid #1f3c88;
            padding-bottom: 5px;
        }

        .btn-secondary {
            background-color: #d3d3d3;
            border: none;
        }

        .btn-secondary:hover {
            background-color: #b5b5b5;
        }

        table thead {
            background-color: #1f3c88;
            color: white;
        }

        table tbody tr:nth-child(odd) {
            background-color: #f8f9fa;
        }
    </style>
</head>

<body>
    <?php
    include '../../../php/conexion.php'; // Incluir la conexión a la base de datos

    // Verificar si se proporciona el ID del grupo
    if (!isset($_GET['id'])) {
        echo "<script>alert('No se proporcionó un ID de grupo.'); window.location.href = '../groups/index.html';</script>";
        exit;
    }

    $groupId = intval($_GET['id']);

    // Obtener datos del grupo
    $queryGroup = "SELECT num, costo_hora, intensidad FROM grupos WHERE id_grupo = ?";
    $stmtGroup = $conn->prepare($queryGroup);
    $stmtGroup->bind_param('i', $groupId);
    $stmtGroup->execute();
    $resultGroup = $stmtGroup->get_result();
    $group = $resultGroup->fetch_assoc();

    if (!$group) {
        echo "<script>alert('Grupo no encontrado.'); window.location.href = '../groups/index.html';</script>";
        exit;
    }

    // Obtener datos del maestro
    $queryTeacher = "SELECT CONCAT(nombre, ' ', apellido_paterno, ' ', apellido_materno) AS nombre_completo
                     FROM maestros
                     JOIN grupo_participantes ON maestros.id_maestro = grupo_participantes.id_maestro
                     WHERE grupo_participantes.id_grupo = ?";
    $stmtTeacher = $conn->prepare($queryTeacher);
    $stmtTeacher->bind_param('i', $groupId);
    $stmtTeacher->execute();
    $resultTeacher = $stmtTeacher->get_result();
    $teacher = $resultTeacher->fetch_assoc();
    $teacherName = $teacher ? $teacher['nombre_completo'] : 'No asignado';

    // Obtener alumnos del grupo
    $queryStudents = "SELECT alumnos.matricula, CONCAT(alumnos.nombre, ' ', alumnos.apellido_paterno, ' ', alumnos.apellido_materno) AS nombre, alumnos.correo
                      FROM alumnos
                      JOIN grupo_participantes ON alumnos.matricula = grupo_participantes.matricula
                      WHERE grupo_participantes.id_grupo = ?";
    $stmtStudents = $conn->prepare($queryStudents);
    $stmtStudents->bind_param('i', $groupId);
    $stmtStudents->execute();
    $resultStudents = $stmtStudents->get_result();

    // Obtener horarios del grupo
    $querySchedule = "SELECT dia_semana, hora_inicio, hora_fin FROM horarios WHERE id_grupo = ?";
    $stmtSchedule = $conn->prepare($querySchedule);
    $stmtSchedule->bind_param('i', $groupId);
    $stmtSchedule->execute();
    $resultSchedule = $stmtSchedule->get_result();
    ?>
    <div class="form-container mt-5">
        <h3 class="text-center text-primary mb-4">Detalles del Grupo</h3>

        <!-- Información General del Grupo -->
        <h4 class="section-title">Información del Grupo</h4>
        <div class="mb-3"><strong>Número de Grupo:</strong> <?php echo htmlspecialchars($group['num']); ?></div>
        <div class="mb-3"><strong>Costo por Hora:</strong> $<?php echo htmlspecialchars($group['costo_hora']); ?></div>
        <div class="mb-3"><strong>Intensidad:</strong> <?php echo htmlspecialchars($group['intensidad']); ?></div>

        <!-- Información del Maestro -->
        <h4 class="section-title">Maestro</h4>
        <div class="mb-3"><strong>Nombre del Maestro:</strong> <?php echo htmlspecialchars($teacherName); ?></div>

        <!-- Horarios -->
        <h4 class="section-title">Horarios</h4>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Día de la Semana</th>
                        <th>Hora de Inicio</th>
                        <th>Hora de Fin</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($schedule = $resultSchedule->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($schedule['dia_semana']); ?></td>
                            <td><?php echo htmlspecialchars($schedule['hora_inicio']); ?></td>
                            <td><?php echo htmlspecialchars($schedule['hora_fin']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Alumnos del Grupo -->
        <h4 class="section-title">Alumnos en el Grupo</h4>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Matrícula</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($student = $resultStudents->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($student['matricula']); ?></td>
                            <td><?php echo htmlspecialchars($student['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($student['correo']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Botón para regresar -->
        <div class="text-end mt-4">
            <a href="../../../groups.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <script src="../../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>
