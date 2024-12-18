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
        <h3 class="form-title">Editar Pago</h3>
        <form id="editPaymentForm" action="../../backend/edit-payment.php" method="POST">
            <input type="hidden" id="id_pago" name="id_pago">
            <input type="hidden" id="id_pago_total" name="id_pago_total">

            <!-- Alumno (solo lectura) -->
            <div class="mb-4">
                <label for="alumno" class="form-label">Alumno</label>
                <input type="text" id="alumno" name="alumno" class="form-control" readonly>
            </div>

            <!-- Pago Inicial -->
            <h4 class="text-secondary">Detalles del Pago</h4>
            <div class="mb-4">
                <label for="monto" class="form-label required">Monto</label>
                <input type="number" step="0.01" id="monto" name="monto" class="form-control" required>
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

            <!-- Pago Total -->
            <h4 class="text-secondary">Detalles del Total</h4>
            <div class="mb-4">
                <label for="cantidad_pago" class="form-label required">Cantidad Total</label>
                <input type="number" step="0.01" id="cantidad_pago" name="cantidad_pago" class="form-control" required>
            </div>
            <div class="mb-4">
                <label for="fecha_pago" class="form-label required">Fecha de Pago</label>
                <input type="date" id="fecha_pago" name="fecha_pago" class="form-control" required>
            </div>

            <!-- Botones -->
            <div class="d-flex justify-content-between mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
                <a href="../payments/index.html" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Cancelar
                </a>
            </div>
        </form>
    </div>

    <script src="../../bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
        // Función para cargar los detalles del pago
        async function loadPaymentDetails() {
            const urlParams = new URLSearchParams(window.location.search);
            const paymentId = urlParams.get('id');

            if (!paymentId) {
                alert("No se proporcionó un ID de pago.");
                window.location.href = "../payments/index.html";
                return;
            }

            try {
                const response = await fetch(`../../backend/get-payment.php?id=${paymentId}`);
                const data = await response.json();

                document.getElementById('id_pago').value = data.id_pago;
                document.getElementById('id_pago_total').value = data.id_pago_total;
                document.getElementById('alumno').value = data.alumno;
                document.getElementById('monto').value = data.monto;
                document.getElementById('forma_pago').value = data.forma_pago;
                document.getElementById('tipo_pago').value = data.tipo_pago;
                document.getElementById('cantidad_pago').value = data.cantidad_pago;
                document.getElementById('fecha_pago').value = data.fecha_pago_total;
            } catch (error) {
                console.error('Error al cargar los detalles del pago:', error);
                alert('Error al cargar los detalles del pago.');
            }
        }

        // Cargar detalles al iniciar la página
        window.onload = loadPaymentDetails;
    </script>
</body>

</html>
