<?php
include '../../../php/conexion.php'; // Cambia la ruta si es necesario

// Manejar la solicitud GET para cargar datos
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
        } else {
            echo "<script>alert('Pago no encontrado.'); window.location.href = '../../../payments.php';</script>";
            exit;
        }
        $stmt->close();
    } else {
        echo "<script>alert('No se proporcionó un ID válido.'); window.location.href = '../../../payments.php';</script>";
        exit;
    }
}

// Manejar la solicitud POST para actualizar o insertar datos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_pago = $_POST['id_pago'];
    $pago_realizado = $_POST['pago_realizado'];
    $forma_pago = $_POST['forma_pago'];
    $tipo_pago = $_POST['tipo_pago'];
    $cantidad_total = $_POST['cantidad_total'];
    $fecha_pago = $_POST['fecha_pago'];

    if (empty($id_pago)) {
        echo "<script>alert('ID de pago no válido.'); window.history.back();</script>";
        exit;
    }

    // Iniciar transacción
    $conn->begin_transaction();

    try {
        // Verificar si existe un registro en pago_total
        $query_check_total = "SELECT id_pago FROM pago_total WHERE id_pago = ?";
        $stmt_check_total = $conn->prepare($query_check_total);
        $stmt_check_total->bind_param('i', $id_pago);
        $stmt_check_total->execute();
        $result_check_total = $stmt_check_total->get_result();

        if ($result_check_total->num_rows > 0) {
            // Actualizar el registro existente en pago_total
            $query_total = "UPDATE pago_total 
                            SET cantidad_pago = ?, fecha_pago = ? 
                            WHERE id_pago = ?";
            $stmt_total = $conn->prepare($query_total);
            $stmt_total->bind_param('dsi', $pago_realizado, $fecha_pago, $id_pago);
        } else {
            // Insertar un nuevo registro en pago_total
            $query_total = "INSERT INTO pago_total (id_pago, cantidad_pago, fecha_pago) 
                            VALUES (?, ?, ?)";
            $stmt_total = $conn->prepare($query_total);
            $stmt_total->bind_param('ids', $id_pago, $pago_realizado, $fecha_pago);
        }
        $stmt_total->execute();

        // Actualizar el registro del pago
        $query_pago = "UPDATE pago 
                       SET monto = ?, forma_pago = ?, tipo_pago = ? 
                       WHERE id_pago = ?";
        $stmt_pago = $conn->prepare($query_pago);
        $stmt_pago->bind_param('dssi', $cantidad_total, $forma_pago, $tipo_pago, $id_pago);
        $stmt_pago->execute();

        // Confirmar transacción
        $conn->commit();
        echo "<script>alert('Pago actualizado correctamente.'); window.location.href = '../../../payments.php';</script>";
    } catch (Exception $e) {
        $conn->rollback();
        echo "<script>alert('Error al actualizar el pago.'); window.history.back();</script>";
    }

    $stmt_pago->close();
    $stmt_total->close();
    $stmt_check_total->close();
    $conn->close();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Editar Pago - Lingus</title>
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
            const paymentMade = parseFloat(document.getElementById('pago_realizado').value) || 0;
            const remaining = totalAmount - paymentMade;

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
        <h3 class="form-title">Editar Pago</h3>
        <form id="editPaymentForm" action="" method="POST">
            <input type="hidden" id="id_pago" name="id_pago" value="<?php echo $pago['id_pago']; ?>">

            <!-- Alumno (solo lectura) -->
            <div class="mb-4">
                <label for="alumno" class="form-label">Alumno</label>
                <input type="text" id="alumno" name="alumno" class="form-control" value="<?php echo $pago['alumno']; ?>" readonly>
            </div>

            <!-- Total -->
            <h4 class="text-secondary">Detalles del Total</h4>
            <div class="mb-4">
                <label for="cantidad_total" class="form-label required">Cantidad Total</label>
                <input type="number" step="0.01" id="cantidad_total" name="cantidad_total" class="form-control" value="<?php echo $pago['cantidad_total']; ?>" required oninput="calculateRemainingAmount()">
            </div>

            <!-- Pago -->
            <h4 class="text-secondary">Detalles del Pago</h4>
            <div class="mb-4">
                <label for="pago_realizado" class="form-label required">Pago Realizado</label>
                <input type="number" step="0.01" id="pago_realizado" name="pago_realizado" class="form-control" value="<?php echo $pago['pago_realizado']; ?>" required oninput="calculateRemainingAmount()">
            </div>
            <div class="mb-4">
                <label for="forma_pago" class="form-label required">Forma de Pago</label>
                <select id="forma_pago" name="forma_pago" class="form-select" required>
                    <option value="Efectivo" <?php echo $pago['forma_pago'] === 'Efectivo' ? 'selected' : ''; ?>>Efectivo</option>
                    <option value="Tarjeta de Crédito" <?php echo $pago['forma_pago'] === 'Tarjeta de Crédito' ? 'selected' : ''; ?>>Tarjeta de Crédito</option>
                    <option value="Transferencia Bancaria" <?php echo $pago['forma_pago'] === 'Transferencia Bancaria' ? 'selected' : ''; ?>>Transferencia Bancaria</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="tipo_pago" class="form-label required">Tipo de Pago</label>
                <input type="text" id="tipo_pago" name="tipo_pago" class="form-control" value="<?php echo $pago['tipo_pago']; ?>" required>
            </div>
            <div class="mb-4">
                <label for="fecha_pago" class="form-label required">Fecha de Pago</label>
                <input type="date" id="fecha_pago" name="fecha_pago" class="form-control" value="<?php echo $pago['fecha_pago_total']; ?>" required>
            </div>

            <!-- Remaining Amount -->
            <div class="mt-4">
                <p id="remainingAmount" class="text-info"></p>
            </div>

            <!-- Botones -->
            <div class="d-flex justify-content-between mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
                <a href="../../../payments.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</body>

</html>
