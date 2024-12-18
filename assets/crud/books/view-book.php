<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Ver Libro - Lingus</title>
    <link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/Nunito.css">
    <link rel="stylesheet" href="../../fonts/fontawesome-all.min.css">
    <style>
        body {
            background-color: #f4f4f9;
        }

        .form-container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .section-title {
            font-size: 1.3rem;
            color: #1f3c88;
            margin-top: 20px;
            border-bottom: 2px solid #1f3c88;
            padding-bottom: 5px;
        }

        .form-label {
            font-weight: bold;
            color: #1f3c88;
        }

        .form-value {
            font-size: 1.1rem;
            color: #333;
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
    <script>
        window.onload = async function () {
            const urlParams = new URLSearchParams(window.location.search);
            const bookId = urlParams.get("id");

            if (!bookId) {
                alert("No se proporcionó un ID de libro.");
                window.location.href = "../books/index.html";
                return;
            }

            try {
                const response = await fetch(`../../backend/get-book.php?id=${bookId}`);
                const data = await response.json();

                // Mostrar los datos del libro
                document.getElementById("nombre").textContent = data.nombre || "No disponible";
            } catch (error) {
                console.error(error);
                alert("Error al cargar los datos del libro.");
                window.location.href = "../books/index.html";
            }
        };
    </script>
</head>

<body>
    <div class="form-container mt-5">
        <h3 class="form-title">Detalles del Libro</h3>

        <!-- Nombre del Libro -->
        <div class="mb-3">
            <label class="form-label">Nombre del Libro:</label>
            <p id="nombre" class="form-value"></p>
        </div>

        <!-- Botón para regresar -->
        <div class="text-end mt-4">
            <a href="../books/index.html" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <script src="../../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>
