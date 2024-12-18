<?php
<<<<<<< HEAD
include 'php/conexion.php'; // Incluir la conexión a la base de datos

// Verificar si se envió la solicitud para eliminar
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_payment'])) {
    $paymentId = intval($_POST['delete_payment']);

    // Desactivar restricciones de claves foráneas
    $conn->query("SET FOREIGN_KEY_CHECKS=0;");

    // Eliminar el pago
    $query = "DELETE FROM pago WHERE id_pago = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $paymentId);

    if ($stmt->execute()) {
        echo "<script>alert('Pago eliminado exitosamente.'); window.location.href = 'payments.php';</script>";
    } else {
        echo "<script>alert('Error al eliminar el pago.');</script>";
    }

    $stmt->close();

    // Reactivar restricciones de claves foráneas
    $conn->query("SET FOREIGN_KEY_CHECKS=1;");
}
=======
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.html");
    exit();
}

include 'php/conexion.php';

// Manejar el estado del pago
$estado_filtro = isset($_GET['estado']) ? $_GET['estado'] : 'todos';
>>>>>>> 0160614562c44bafe7414556a7ee269fbf35b367
?>

<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Pagos - Lingus</title>
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
                    <h3 class="text-dark mb-4">Pagos</h3>
                    <div class="card shadow">
                        <!-- Botones de Filtro y Registrar -->
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <p class="text-primary m-0 fw-bold">Lista de Pagos</p>
<<<<<<< HEAD
                            <a class="btn btn-success btn-sm mt-2" href="add-payment.html">
                                <i class="fas fa-plus"></i> Registrar Pago
                            </a>
=======
                            <div>
                                <a href="?estado=todos" class="btn btn-info btn-sm <?php echo $estado_filtro === 'todos' ? 'disabled' : ''; ?>">Todos</a>
                                <a href="?estado=pendiente" class="btn btn-warning btn-sm <?php echo $estado_filtro === 'pendiente' ? 'disabled' : ''; ?>">Pendientes</a>
                                <a href="?estado=pagado" class="btn btn-success btn-sm <?php echo $estado_filtro === 'pagado' ? 'disabled' : ''; ?>">Pagados</a>
                                <a href="assets/crud/payments/add-payment.php" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i> Registrar Pago
                                </a>
                            </div>
>>>>>>> 0160614562c44bafe7414556a7ee269fbf35b367
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Alumno</th>
                                            <th>Matrícula</th>
                                            <th>Forma de Pago</th>
                                            <th>Monto</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
<<<<<<< HEAD
                                        // Consulta para obtener la información de pagos
=======
                                        // Consulta para obtener pagos con filtro
                                        $where = ($estado_filtro === 'pendiente') ? "p.tipo_pago = 'pendiente'" : (($estado_filtro === 'pagado') ? "p.tipo_pago = 'pagado'" : "1=1");
>>>>>>> 0160614562c44bafe7414556a7ee269fbf35b367
                                        $query = "SELECT p.id_pago, 
                                                         CONCAT(a.nombre, ' ', a.apellido_paterno, ' ', a.apellido_materno) AS alumno, 
                                                         a.matricula, 
                                                         p.forma_pago, 
                                                         p.monto
                                                  FROM pago p
                                                  JOIN alumnos a ON p.matricula = a.matricula
                                                  WHERE $where";
                                        $result = $conn->query($query);

                                        // Mostrar resultados
                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<tr>
                                                    <td>{$row['id_pago']}</td>
                                                    <td>{$row['alumno']}</td>
                                                    <td>{$row['matricula']}</td>
                                                    <td>{$row['forma_pago']}</td>
                                                    <td>\${$row['monto']}</td>
                                                    <td>
                                                        <a href='assets/crud/payments/view-payment.php?id={$row['id_pago']}' class='btn btn-sm btn-info'>
                                                            <i class='fas fa-eye'></i> Ver
                                                        </a>
                                                        <a href='assets/crud/payments/edit-payment.php?id={$row['id_pago']}' class='btn btn-sm btn-warning'>
                                                            <i class='fas fa-edit'></i> Editar
                                                        </a>
<<<<<<< HEAD
                                                        <form method='POST' style='display:inline;' onsubmit='return confirm(\"¿Estás seguro de eliminar este pago?\");'>
                                                            <input type='hidden' name='delete_payment' value='{$row['id_pago']}'>
                                                            <button type='submit' class='btn btn-sm btn-danger'>
                                                                <i class='fas fa-trash-alt'></i> Eliminar
                                                            </button>
                                                        </form>
=======
                                                        <a href='assets/crud/payments/delete-payment.php?id={$row['id_pago']}' class='btn btn-sm btn-danger' onclick='return confirm(\"¿Estás seguro de eliminar este pago?\")'>
                                                            <i class='fas fa-trash-alt'></i> Eliminar
                                                        </a>
>>>>>>> 0160614562c44bafe7414556a7ee269fbf35b367
                                                    </td>
                                                </tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='6' class='text-center'>No hay pagos registrados.</td></tr>";
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
