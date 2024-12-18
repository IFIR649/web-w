<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Grupos - Lingus</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/Nunito.css">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
</head>

<body id="page-top">
    <div id="wrapper">
        <div id="navbar-placeholder"></div>
        <script src="assets/js/query.js"></script>
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
                                        <a class="dropdown-item" href="profile.html"><i class="fas fa-user fa-sm fa-fw me-2 text-gray-400"></i>&nbsp;Perfil</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="logout.html"><i class="fas fa-sign-out-alt fa-sm fa-fw me-2 text-gray-400"></i>&nbsp;Cerrar sesión</a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
                <div class="container-fluid">
                    <h3 class="text-dark mb-4">Grupos</h3>
                    <div class="card shadow">
                        <div class="card-header py-3">
                            <p class="text-primary m-0 fw-bold">Lista de Grupos</p>
                            <a class="btn btn-success btn-sm mt-2" href="add-group.html"><i class="fas fa-plus"></i> Agregar Grupo</a>
                            <a class="btn btn-primary btn-sm mt-2" href="exportar.php"><i class="fas fa-file-excel"></i> Exportar Base de Datos</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Número</th>
                                            <th>Intensidad</th>
                                            <th>Idioma</th>
                                            <th>Nivel</th>
                                            <th>Fecha Inicio</th>
                                            <th>Fecha Fin</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Incluir la conexión
                                        include 'php/conexion.php';

                                        // Consulta para obtener la información de grupos
                                        $query = "SELECT g.id_grupo, g.num, g.intensidad, g.fecha_inicio, g.fecha_fin, 
                                                         i.nombre AS idioma, le.name AS nivel
                                                  FROM grupos g
                                                  JOIN idioma i ON g.id_idioma = i.id_idioma
                                                  JOIN level le ON g.id_level = le.id_level";
                                        $result = $conn->query($query);

                                        // Generar filas de la tabla
                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<tr>
                                                    <td>{$row['id_grupo']}</td>
                                                    <td>{$row['num']}</td>
                                                    <td>{$row['intensidad']}</td>
                                                    <td>{$row['idioma']}</td>
                                                    <td>{$row['nivel']}</td>
                                                    <td>{$row['fecha_inicio']}</td>
                                                    <td>{$row['fecha_fin']}</td>
                                                    <td>
                                                            <a href='assets/crud/groups/view-group.php?id={$row['id_grupo']}' class='btn btn-sm btn-info'>
                                                                <i class='fas fa-eye'></i> Ver
                                                        </a>
                                                        <a href='edit-group.php?id={$row['id_grupo']}' class='btn btn-sm btn-warning'>
                                                            <i class='fas fa-edit'></i> Editar
                                                        </a>
                                                        <a href='delete-group.php?id={$row['id_grupo']}' class='btn btn-sm btn-danger' onclick='return confirm(\"¿Estás seguro de eliminar este grupo?\")'>
                                                            <i class='fas fa-trash-alt'></i> Eliminar
                                                        </a>
                                                    </td>
                                                </tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='8' class='text-center'>No hay grupos registrados.</td></tr>";
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
                    <div class="text-center my-auto copyright">
                        <span>Copyright © Lingus 2024</span>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>
