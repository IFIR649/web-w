<?php
// Incluir la conexión a la base de datos
include 'php/conexion.php';

// Manejar la eliminación del estudiante
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_student'])) {
    $matricula = intval($_POST['delete_student']); // Sanitizar la entrada

    $query_delete = "DELETE FROM alumnos WHERE matricula = ?";
    $stmt_delete = $conn->prepare($query_delete);
    $stmt_delete->bind_param("i", $matricula);

    if ($stmt_delete->execute()) {
        echo "<script>alert('Estudiante eliminado correctamente.'); window.location.href = 'students.php';</script>";
    } else {
        echo "<script>alert('Error al eliminar el estudiante.'); window.location.href = 'students.php';</script>";
    }

    $stmt_delete->close();
}

// Manejar el estado solicitado
$estado_filtro = isset($_GET['estado']) ? $_GET['estado'] : 'activo';
?>

<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Estudiantes - Lingus</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/Nunito.css">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body id="page-top">
    <div id="wrapper">
        <div id="navbar-placeholder"></div>
        <script>
            $(function () {
                $("#navbar-placeholder").load("assets/navbar.html");
            });
        </script>
        <div class="d-flex flex-column" id="content-wrapper">
            <div id="content">
                <nav class="navbar navbar-expand bg-white shadow mb-4 topbar">
                    <div class="container-fluid">
                        <button class="btn btn-link d-md-none rounded-circle me-3" id="sidebarToggleTop" type="button">
                            <i class="fas fa-bars"></i>
                        </button>
                        <ul class="navbar-nav flex-nowrap ms-auto">
                            <li class="nav-item dropdown no-arrow">
                                <div class="nav-item dropdown no-arrow">
                                    <a class="dropdown-toggle nav-link" aria-expanded="false" data-bs-toggle="dropdown" href="#">
                                        <span class="d-none d-lg-inline me-2 text-gray-600 small">Admin</span>
                                        <img class="border rounded-circle img-profile" src="assets/img/logo_letra.png">
                                    </a>
                                    <div class="dropdown-menu shadow dropdown-menu-end animated--grow-in">
                                        <a class="dropdown-item" href="#"><i class="fas fa-user fa-sm fa-fw me-2 text-gray-400"></i>&nbsp;Perfil</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="logout.html"><i class="fas fa-sign-out-alt fa-sm fa-fw me-2 text-gray-400"></i>&nbsp;Cerrar sesión</a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
                <div class="container-fluid">
                    <h3 class="text-dark mb-4">Estudiantes</h3>
                    <div class="card shadow">
                        <!-- Botones de Filtro y Búsqueda -->
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <p class="text-primary m-0 fw-bold">Lista de Estudiantes</p>
                            <div>
                                <!-- Botón Filtrar Activos -->
                                <a href="?estado=activo" class="btn btn-info btn-sm <?php echo $estado_filtro === 'activo' ? 'disabled' : ''; ?>">
                                    Activos
                                </a>
                                <!-- Botón Filtrar Inactivos -->
                                <a href="?estado=inactivo" class="btn btn-warning btn-sm <?php echo $estado_filtro === 'inactivo' ? 'disabled' : ''; ?>">
                                    Inactivos
                                </a>
                                <!-- Barra de Búsqueda -->
                                <input type="text" id="searchInput" class="form-control form-control-sm d-inline-block" placeholder="Buscar...">
                                <!-- Botón Agregar Estudiante -->
                                <a href="assets/crud/students/add-students.php" class="btn btn-success btn-sm">
                                    <i class="fas fa-plus"></i> Agregar Estudiante
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th>Matrícula</th>
                                            <th>Nombre Completo</th>
                                            <th>Correo</th>
                                            <th>Estado</th>
                                            <th>Fecha Inscripción</th>
                                            <?php if ($estado_filtro === 'inactivo'): ?>
                                                <th>Fecha de Baja</th>
                                            <?php endif; ?>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Consulta para obtener los datos de los estudiantes
                                        $query = "SELECT matricula, CONCAT(nombre, ' ', apellido_paterno, ' ', apellido_materno) AS nombre_completo, correo, estado, fecha_inscripcion, fecha_baja 
                                                  FROM alumnos 
                                                  WHERE estado = ?";
                                        $stmt = $conn->prepare($query);
                                        $stmt->bind_param("s", $estado_filtro);
                                        $stmt->execute();
                                        $result = $stmt->get_result();

                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<tr>
                                                    <td>{$row['matricula']}</td>
                                                    <td>{$row['nombre_completo']}</td>
                                                    <td>{$row['correo']}</td>
                                                    <td>{$row['estado']}</td>
                                                    <td>{$row['fecha_inscripcion']}</td>";
                                                if ($estado_filtro === 'inactivo') {
                                                    echo "<td>{$row['fecha_baja']}</td>";
                                                }
                                                echo "<td>
                                                        <!-- Botón Ver -->
                                                        <a href='assets/crud/students/view-student.php?id={$row['matricula']}' class='btn btn-sm btn-info'>
                                                            <i class='fas fa-eye'></i> Ver
                                                        </a>
                                                        <!-- Botón Editar -->
                                                        <a href='assets/crud/students/edit-student.php?id={$row['matricula']}' class='btn btn-sm btn-warning'>
                                                            <i class='fas fa-edit'></i> Editar
                                                        </a>
                                                        <!-- Botón Eliminar -->
                                                        <form method='POST' style='display:inline;' onsubmit='return confirm(\"¿Estás seguro de eliminar este estudiante?\");'>
                                                            <input type='hidden' name='delete_student' value='{$row['matricula']}'>
                                                            <button type='submit' class='btn btn-sm btn-danger'>
                                                                <i class='fas fa-trash-alt'></i> Eliminar
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='7' class='text-center'>No hay estudiantes $estado_filtro registrados.</td></tr>";
                                        }
                                        $stmt->close();
                                        $conn->close();
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <footer class="bg-white sticky-footer">
                <div class="container my-auto">
                    <div class="text-center my-auto copyright"><span>Copyright © Lingus 2024</span></div>
                </div>
            </footer>
        </div>
    </div>
    <script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
        // Filtrado de búsqueda dinámica
        document.getElementById("searchInput").addEventListener("input", function () {
            const filter = this.value.toLowerCase();
            const rows = document.querySelectorAll("#dataTable tbody tr");

            rows.forEach(row => {
                const cells = row.querySelectorAll("td");
                let rowContent = "";
                cells.forEach(cell => rowContent += cell.textContent.toLowerCase() + " ");
                row.style.display = rowContent.includes(filter) ? "" : "none";
            });
        });
    </script>
</body>

</html>
