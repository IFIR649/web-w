<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Editar Nivel - Lingus</title>
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
            const levelId = urlParams.get("id");

            if (!levelId) {
                alert("No se proporcionó un ID de nivel.");
                window.location.href = "../levels/index.html";
                return;
            }

            try {
                // Simula la llamada al backend
                const response = await fetch(`../../backend/get-level.php?id=${levelId}`);
                const data = await response.json();

                // Rellenar los campos del formulario
                document.getElementById("cefr_level").value = data.cefr_level;
                document.getElementById("name").value = data.name;
                document.getElementById("modu").value = data.modu;
                document.getElementById("unite").value = data.unite;
                document.getElementById("id_libro").value = data.id_libro;
            } catch (error) {
                alert("Error al cargar los datos del nivel.");
                console.error(error);
                window.location.href = "../levels/index.html";
            }
        };
    </script>
</head>

<body>
    <div class="form-container mt-5">
        <h3 class="form-title">Editar Nivel</h3>
        <form action="../../backend/edit-level.php" method="POST">
            <!-- CEFR Level -->
            <div class="mb-4">
                <label for="cefr_level" class="form-label required">CEFR Level</label>
                <input type="text" id="cefr_level" name="cefr_level" class="form-control" required>
            </div>

            <!-- Nombre del Nivel -->
            <div class="mb-4">
                <label for="name" class="form-label required">Nombre del Nivel</label>
                <input type="text" id="name" name="name" class="form-control" required>
            </div>

            <!-- Modularidad -->
            <div class="mb-4">
                <label for="modu" class="form-label required">Módulo</label>
                <input type="number" id="modu" name="modu" class="form-control" required>
            </div>

            <!-- Unidades -->
            <div class="mb-4">
                <label for="unite" class="form-label required">Unidades</label>
                <input type="number" id="unite" name="unite" class="form-control" required>
            </div>

            <!-- Libro Relacionado -->
            <div class="mb-4">
                <label for="id_libro" class="form-label required">Libro</label>
                <select id="id_libro" name="id_libro" class="form-select" required>
                    <option value="">Seleccione un libro</option>
                    <option value="1">English Starter</option>
                    <option value="2">Intermediate Guide</option>
                    <option value="3">Advanced Mastery</option>
                </select>
            </div>

            <!-- Botones -->
            <div class="d-flex justify-content-between mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
                <a href="../levels/index.html" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Cancelar
                </a>
            </div>
        </form>
    </div>

    <script src="../../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>
