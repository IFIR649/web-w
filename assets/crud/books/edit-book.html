<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Editar Libro - Lingus</title>
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

                // Rellenar los campos del formulario
                document.getElementById("nombre").value = data.nombre;
                document.getElementById("book_id").value = bookId; // Para enviar el ID al backend
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
        <h3 class="form-title">Editar Libro</h3>
        <form action="../../backend/edit-book.php" method="POST">
            <!-- ID oculto -->
            <input type="hidden" id="book_id" name="book_id">

            <!-- Nombre del Libro -->
            <div class="mb-4">
                <label for="nombre" class="form-label required">Nombre del Libro</label>
                <input type="text" id="nombre" name="nombre" class="form-control" required>
            </div>

            <!-- Botones -->
            <div class="d-flex justify-content-between mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
                <a href="../books/index.html" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Cancelar
                </a>
            </div>
        </form>
    </div>

    <script src="../../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>
