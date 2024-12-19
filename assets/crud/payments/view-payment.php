<?php
include '../../../php/conexion.php'; // Cambia la ruta si es necesario

$pago = [];
$pagos_totales = [];

// Manejar eliminación de pago relacionado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $id_pago_total = intval($_POST['delete_id']);

    // Eliminar el pago relacionado
    $query_delete = "DELETE FROM pago_total WHERE id_pago_total = ?";
    $stmt_delete = $conn->prepare($query_delete);
    $stmt_delete->bind_param('i', $id_pago_total);

    if ($stmt_delete->execute()) {
        echo "<script>alert('Pago eliminado correctamente.'); window.location.href = window.location.href;</script>";
    } else {
        echo "<script>alert('Error al eliminar el pago.'); window.location.href = window.location.href;</script>";
    }
    $stmt_delete->close();
}

// Consultar detalles del pago principal
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id_pago = intval($_GET['id']); // Sanitizar el ID del pago

    $query = "SELECT p.id_pago, p.monto, p.forma_pago, p.tipo_pago, 
                     CONCAT(a.nombre, ' ', a.apellido_paterno, ' ', a.apellido_materno) AS alumno 
              FROM pago p
              JOIN alumnos a ON p.matricula = a.matricula
              WHERE p.id_pago = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id_pago);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $pago = $result->fetch_assoc();

        // Consultar los pagos relacionados en pago_total
        $query_totales = "SELECT id_pago_total, fecha_pago, cantidad_pago 
                          FROM pago_total 
                          WHERE id_pago = ?";
        $stmt_totales = $conn->prepare($query_totales);
        $stmt_totales->bind_param('i', $id_pago);
        $stmt_totales->execute();
        $result_totales = $stmt_totales->get_result();

        while ($row = $result_totales->fetch_assoc()) {
            $pagos_totales[] = $row;
        }

        // Calcular el total pagado y el resto
        $total_pagado = array_sum(array_column($pagos_totales, 'cantidad_pago'));
        $resto = $pago['monto'] - $total_pagado;

        $pago['total_pagado'] = $total_pagado;
        $pago['resto'] = $resto;
    } else {
        $pago['error'] = 'No se encontraron detalles para este pago.';
    }

    $stmt->close();
    $stmt_totales->close();
} else {
    $pago['error'] = 'Solicitud no válida o falta de parámetros.';
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Ver Pago - Lingus</title>
    <link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/Nunito.css">
    <link rel="stylesheet" href="../../fonts/fontawesome-all.min.css">
    <style>
        body {
            background-color: #f4f4f9;
        }

        .details-container {
            max-width: 700px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .details-title {
            text-align: center;
            font-size: 1.6rem;
            color: #1f3c88;
            margin-bottom: 20px;
        }

        .detail-label {
            font-weight: bold;
            color: #1f3c88;
        }

        .detail-value {
            margin-bottom: 15px;
            font-size: 1.1rem;
        }

        .btn-secondary {
            background-color: #d3d3d3;
            border: none;
        }

        .btn-secondary:hover {
            background-color: #b5b5b5;
        }

        .table {
            margin-top: 20px;
            color: #333;
        }

        .table th {
            color: #1f3c88;
        }
    </style>
</head>

<body>
    <div class="details-container mt-5">
        <h3 class="details-title">Detalles del Pago</h3>
        <div id="paymentDetails">
            <?php if (isset($pago['error'])): ?>
                <div class="alert alert-warning text-center">
                    <?php echo $pago['error']; ?>
                </div>
            <?php else: ?>
                <div class="detail-value"><span class="detail-label">Alumno:</span> <?php echo $pago['alumno']; ?></div>
                <div class="detail-value"><span class="detail-label">Forma de Pago:</span> <?php echo $pago['forma_pago']; ?></div>
                <div class="detail-value"><span class="detail-label">Monto a Pagar:</span> $<?php echo number_format($pago['monto'], 2); ?></div>
                <div class="detail-value"><span class="detail-label">Tipo de Pago:</span> <?php echo $pago['tipo_pago']; ?></div>
                <hr>
                <div class="detail-value"><span class="detail-label">Total Pagado:</span> $<?php echo number_format($pago['total_pagado'], 2); ?></div>
                <div class="detail-value">
                    <span class="detail-label">
                        <?php if ($pago['resto'] > 0): ?>
                            Monto Restante:
                        <?php elseif ($pago['resto'] < 0): ?>
                            Cambio:
                        <?php else: ?>
                            Pagado:
                        <?php endif; ?>
                    </span>
                    $<?php echo number_format(abs($pago['resto']), 2); ?>
                </div>

                <h4 class="details-title mt-4">Pagos Relacionados</h4>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Fecha del Pago</th>
                            <th>Monto del Pago</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($pagos_totales) > 0): ?>
                            <?php foreach ($pagos_totales as $pago_total): ?>
                                <tr>
                                    <td><?php echo $pago_total['fecha_pago']; ?></td>
                                    <td>$<?php echo number_format($pago_total['cantidad_pago'], 2); ?></td>
                                    <td>
                                        <form method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este pago?');" style="display:inline;">
                                            <input type="hidden" name="delete_id" value="<?php echo $pago_total['id_pago_total']; ?>">
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash-alt"></i> Eliminar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="text-center">No hay pagos registrados.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <div class="d-flex justify-content-end mt-4">
            <a href="../../../payments.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <script src="../../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>
