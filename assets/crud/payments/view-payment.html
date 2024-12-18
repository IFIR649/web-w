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
            <!-- Detalles cargados dinámicamente -->
        </div>

        <div class="d-flex justify-content-end mt-4">
            <a href="../payments/index.html" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
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

                const detailsContainer = document.getElementById('paymentDetails');

                detailsContainer.innerHTML = `
                    <div class="detail-value"><span class="detail-label">Alumno:</span> ${data.alumno}</div>
                    <div class="detail-value"><span class="detail-label">Forma de Pago:</span> ${data.forma_pago}</div>
                    <div class="detail-value"><span class="detail-label">Monto:</span> $${data.monto}</div>
                    <div class="detail-value"><span class="detail-label">Tipo de Pago:</span> ${data.tipo_pago}</div>
                    <div class="detail-value"><span class="detail-label">Fecha del Pago:</span> ${data.fecha_pago}</div>
                    <hr>
                    <div class="detail-value"><span class="detail-label">Cantidad Total:</span> $${data.cantidad_pago}</div>
                    <div class="detail-value"><span class="detail-label">Fecha del Pago Total:</span> ${data.fecha_pago_total}</div>
                `;
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
