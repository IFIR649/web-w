<?php
include '../../../php/conexion.php'; // Cambia la ruta si es necesario

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id_pago = intval($_GET['id']); // Sanitizar el ID del pago

    // Consultar los detalles del pago
    $query = "SELECT p.id_pago, p.monto, p.forma_pago, p.tipo_pago, 
                     pt.fecha_pago AS fecha_pago_total, pt.cantidad_pago, 
                     CONCAT(a.nombre, ' ', a.apellido_paterno, ' ', a.apellido_materno) AS alumno 
              FROM pago p
              LEFT JOIN pago_total pt ON p.id_pago = pt.id_pago
              JOIN alumnos a ON p.matricula = a.matricula
              WHERE p.id_pago = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id_pago);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $pago = $result->fetch_assoc();
        // Calcular el resto
        $resto = $pago['monto'] - $pago['cantidad_pago'];
    } else {
        $pago = ['error' => 'No se encontraron detalles para este pago.'];
    }

    $stmt->close();
} else {
    $pago = ['error' => 'Solicitud no válida o falta de parámetros.'];
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
                <div class="detail-value"><span class="detail-label">Fecha del Último Pago:</span> <?php echo $pago['fecha_pago_total'] ?? 'N/A'; ?></div>
                <hr>
                <div class="detail-value"><span class="detail-label">Cantidad Total Pagada:</span> $<?php echo number_format($pago['cantidad_pago'], 2); ?></div>
                <div class="detail-value">
                    <span class="detail-label">
                        <?php if ($resto > 0): ?>
                            Monto Restante:
                        <?php elseif ($resto < 0): ?>
                            Cambio:
                        <?php else: ?>
                            Pagado:
                        <?php endif; ?>
                    </span> 
                    $<?php echo number_format(abs($resto), 2); ?>
                </div>
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
