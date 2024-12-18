<?php
// Incluir la conexión a la base de datos
include 'php/conexion.php';

// Verificar si se envió la solicitud para eliminar
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_teacher'])) {
    $teacherId = intval($_POST['delete_teacher']);

    // Desactivar restricciones de claves foráneas
    $conn->query("SET FOREIGN_KEY_CHECKS=0;");

    // Eliminar el maestro
    $query = "DELETE FROM maestros WHERE id_maestro = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $teacherId);

    if ($stmt->execute()) {
        echo "<script>alert('Maestro eliminado exitosamente.'); window.location.href = 'teachers.php';</script>";
    } else {
        echo "<script>alert('Error al eliminar el maestro.');</script>";
    }

    $stmt->close();

    // Reactivar restricciones de claves foráneas
    $conn->query("SET FOREIGN_KEY_CHECKS=1;");
}
?>

<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Maestros - Lingus</title>
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
                    <h3 class="text-dark mb-4">Maestros</h3>
                    <div class="card shadow">
                        <!-- Botones y Búsqueda -->
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <p class="text-primary m-0 fw-bold">Lista de Maestros</p>
                            <div>
                                <input type="text" id="searchInput" class="form-control form-control-sm d-inline-block" placeholder="Buscar...">
                                <a href="add-teacher.html" class="btn btn-success btn-sm mt-2">
                                    <i class="fas fa-plus"></i> Agregar Maestro
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nombre</th>
                                            <th>Correo</th>
                                            <th>Certificación</th>
                                            <th>Horas Totales</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Consulta para obtener los datos de los maestros
                                        $query = "SELECT id_maestro, CONCAT(nombre, ' ', apellido_paterno, ' ', apellido_materno) AS nombre_completo, 
                                                  correo, certificado, horas_tot FROM maestros";
                                        $result = $conn->query($query);

                                        // Verificar si hay resultados
                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<tr>
                                                    <td>{$row['id_maestro']}</td>
                                                    <td>{$row['nombre_completo']}</td>
                                                    <td>{$row['correo']}</td>
                                                    <td>{$row['certificado']}</td>
                                                    <td>{$row['horas_tot']}</td>
                                                    <td>
                                                        <a href='view-teacher.php?id={$row['id_maestro']}' class='btn btn-sm btn-info'>
                                                            <i class='fas fa-eye'></i> Ver
                                                        </a>
                                                        <a href='edit-teacher.php?id={$row['id_maestro']}' class='btn btn-sm btn-warning'>
                                                            <i class='fas fa-edit'></i> Editar
                                                        </a>
                                                        <form method='POST' style='display:inline;' onsubmit='return confirm(\"¿Estás seguro de eliminar este maestro?\");'>
                                                            <input type='hidden' name='delete_teacher' value='{$row['id_maestro']}'>
                                                            <button type='submit' class='btn btn-sm btn-danger'>
                                                                <i class='fas fa-trash-alt'></i> Eliminar
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='6' class='text-center'>No hay maestros registrados.</td></tr>";
                                        }
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
        // Filtrado de búsqueda dinámico
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
