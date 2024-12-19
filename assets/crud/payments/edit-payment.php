<?php
include '../../../php/conexion.php'; // Cambia la ruta si es necesario

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id'])) {
        $id_pago = intval($_GET['id']); // Sanitizar el ID del pago

        // Consultar los detalles del pago
        $query = "SELECT p.id_pago, p.monto AS cantidad_total, p.forma_pago, p.tipo_pago, 
                         pt.fecha_pago AS fecha_pago_total, pt.cantidad_pago AS pago_realizado, 
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

            // Calcular el total acumulado de los pagos
            $query_total = "SELECT SUM(cantidad_pago) AS total_acumulado 
                            FROM pago_total 
                            WHERE id_pago = ?";
            $stmt_total = $conn->prepare($query_total);
            $stmt_total->bind_param('i', $id_pago);
            $stmt_total->execute();
            $result_total = $stmt_total->get_result();
            $total_acumulado = $result_total->fetch_assoc()['total_acumulado'] ?? 0;

            $pago['total_acumulado'] = $total_acumulado;
        } else {
            echo "<script>alert('Pago no encontrado.'); window.location.href = '../../../payments.php';</script>";
            exit;
        }
        $stmt->close();
    } else {
        echo "<script>alert('No se proporcionó un ID válido.'); window.location.href = '../../../payments.php';</script>";
        exit;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_pago = $_POST['id_pago'];
    $pago_realizado = $_POST['pago_realizado'];
    $forma_pago = $_POST['forma_pago'];
    $tipo_pago = $_POST['tipo_pago'];
    $fecha_pago = $_POST['fecha_pago'];

    if (empty($id_pago)) {
        echo "<script>alert('ID de pago no válido.'); window.history.back();</script>";
        exit;
    }

    try {
        // Insertar un nuevo registro en pago_total
        $query_insert = "INSERT INTO pago_total (id_pago, cantidad_pago, fecha_pago) 
                         VALUES (?, ?, ?)";
        $stmt_insert = $conn->prepare($query_insert);
        $stmt_insert->bind_param('ids', $id_pago, $pago_realizado, $fecha_pago);
        $stmt_insert->execute();

        echo "<script>alert('Nuevo pago registrado correctamente.'); window.location.href = '../../../payments.php';</script>";
    } catch (Exception $e) {
        echo "<script>alert('Error al registrar el pago.'); window.history.back();</script>";
    }
    $stmt_insert->close();
    $conn->close();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Registrar Pago - Lingus</title>
    <link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/Nunito.css">
    <link rel="stylesheet" href="../../fonts/fontawesome-all.min.css">
    <style>
        body {
            background-color: #f4f4f9;
        }

        .form-container {
            max-width: 700px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-label {
            font-weight: bold;
            color: #1f3c88;
        }
    </style>
    <script>
        function calculateRemainingAmount() {
            const totalAmount = parseFloat(document.getElementById('cantidad_total').value) || 0;
            const accumulated = parseFloat(document.getElementById('total_acumulado').value) || 0;
            const payment = parseFloat(document.getElementById('pago_realizado').value) || 0;

            const remaining = totalAmount - (accumulated + payment);

            let resultText = '';
            if (remaining > 0) {
                resultText = `Monto restante: $${remaining.toFixed(2)}`;
            } else if (remaining === 0) {
                resultText = 'Pago completo.';
            } else {
                resultText = `Cambio: $${Math.abs(remaining).toFixed(2)}`;
            }

            document.getElementById('remainingAmount').textContent = resultText;
        }
    </script>
</head>

<body>
    <div class="form-container mt-5">
        <h3 class="form-title">Registrar Nuevo Pago</h3>
        <form id="newPaymentForm" action="" method="POST">
            <input type="hidden" id="id_pago" name="id_pago" value="<?php echo $pago['id_pago']; ?>">
            <input type="hidden" id="total_acumulado" name="total_acumulado" value="<?php echo $pago['total_acumulado']; ?>">

            <!-- Alumno (solo lectura) -->
            <div class="mb-4">
                <label for="alumno" class="form-label">Alumno</label>
                <input type="text" id="alumno" name="alumno" class="form-control" value="<?php echo $pago['alumno']; ?>" readonly>
            </div>

            <!-- Total -->
            <h4 class="text-secondary">Detalles del Total</h4>
            <div class="mb-4">
                <label for="cantidad_total" class="form-label required">Cantidad Total</label>
                <input type="number" step="0.01" id="cantidad_total" name="cantidad_total" class="form-control" value="<?php echo $pago['cantidad_total']; ?>" readonly>
            </div>

            <!-- Pago -->
            <h4 class="text-secondary">Registrar Pago</h4>
            <div class="mb-4">
                <label for="pago_realizado" class="form-label required">Pago Realizado</label>
                <input type="number" step="0.01" id="pago_realizado" name="pago_realizado" class="form-control" required oninput="calculateRemainingAmount()">
            </div>
            <div class="mb-4">
                <label for="forma_pago" class="form-label required">Forma de Pago</label>
                <select id="forma_pago" name="forma_pago" class="form-select" required>
                    <option value="Efectivo">Efectivo</option>
                    <option value="Tarjeta de Crédito">Tarjeta de Crédito</option>
                    <option value="Transferencia Bancaria">Transferencia Bancaria</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="tipo_pago" class="form-label required">Tipo de Pago</label>
                <input type="text" id="tipo_pago" name="tipo_pago" class="form-control" required>
            </div>
            <div class="mb-4">
                <label for="fecha_pago" class="form-label required">Fecha de Pago</label>
                <input type="date" id="fecha_pago" name="fecha_pago" class="form-control" required>
            </div>

            <!-- Remaining Amount -->
            <div class="mt-4">
                <p id="remainingAmount" class="text-info"></p>
            </div>

            <!-- Botones -->
            <div class="d-flex justify-content-between mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar Pago
                </button>
                <a href="../../../payments.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</body>

</html>
