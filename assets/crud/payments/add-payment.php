<?php
include '../../../php/conexion.php'; // Cambia la ruta si es necesario

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $matricula = $_POST['matricula'];
    $monto = $_POST['monto']; // Total que se debe pagar
    $cantidad_pago = $_POST['cantidad_pago']; // Pago realizado
    $forma_pago = $_POST['forma_pago'];
    $tipo_pago = $_POST['tipo_pago'];
    $fecha_pago = $_POST['fecha_pago'];

    // Validar la matrícula ingresada
    $query_check = "SELECT matricula FROM alumnos WHERE matricula = ?";
    $stmt_check = $conn->prepare($query_check);
    $stmt_check->bind_param('s', $matricula);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        // Insertar un nuevo registro en la tabla `pago`
        $query_pago = "INSERT INTO pago (matricula, monto, forma_pago, tipo_pago) VALUES (?, ?, ?, ?)";
        $stmt_pago = $conn->prepare($query_pago);
        $stmt_pago->bind_param('sdss', $matricula, $monto, $forma_pago, $tipo_pago);
        $stmt_pago->execute();
        $id_pago = $stmt_pago->insert_id;

        // Insertar un nuevo registro en la tabla `pago_total`
        $query_pago_total = "INSERT INTO pago_total (id_pago, cantidad_pago, fecha_pago) VALUES (?, ?, ?)";
        $stmt_pago_total = $conn->prepare($query_pago_total);
        $stmt_pago_total->bind_param('ids', $id_pago, $cantidad_pago, $fecha_pago);
        $stmt_pago_total->execute();

        echo "<script>alert('Pago registrado correctamente.'); window.location.href = '../../../payments.php';</script>";
    } else {
        echo "<script>alert('La matrícula ingresada no existe.'); window.history.back();</script>";
    }

    $stmt_check->close();
    $stmt_pago->close();
    $stmt_pago_total->close();
    $conn->close();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Agregar Pago Completo - Lingus</title>
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
        <h3 class="form-title">Agregar Pago Completo</h3>
        <form id="addCompletePaymentForm" action="" method="POST">
            <!-- Ingresar Matrícula -->
            <div class="mb-4">
                <label for="matricula" class="form-label required">Matrícula del Alumno</label>
                <input type="text" id="matricula" name="matricula" class="form-control" placeholder="Ingrese la matrícula del alumno" required>
            </div>

            <!-- Pago Total -->
            <h4 class="text-secondary">Detalles del Total</h4>
            <div class="mb-4">
                <label for="monto" class="form-label required">Monto (Total a Pagar)</label>
                <input type="number" step="0.01" id="monto" name="monto" class="form-control" placeholder="Ejemplo: 5000.00" required>
            </div>

            <!-- Pago Realizado -->
            <h4 class="text-secondary">Detalles del Pago</h4>
            <div class="mb-4">
                <label for="cantidad_pago" class="form-label required">Cantidad Pagada</label>
                <input type="number" step="0.01" id="cantidad_pago" name="cantidad_pago" class="form-control" placeholder="Ejemplo: 1500.50" required>
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
                <input type="text" id="tipo_pago" name="tipo_pago" class="form-control" placeholder="Ejemplo: Inscripción, Mensualidad" required>
            </div>
            <div class="mb-4">
                <label for="fecha_pago" class="form-label required">Fecha de Pago</label>
                <input type="date" id="fecha_pago" name="fecha_pago" class="form-control" required>
            </div>

            <!-- Botones -->
            <div class="d-flex justify-content-between mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar
                </button>
                <a href="../../../payments.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Cancelar
                </a>
            </div>
        </form>
    </div>

    <script src="../../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>
