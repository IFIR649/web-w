<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Editar Grupo - Lingus</title>
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
            color: #333;
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
        // Cargar datos del grupo al iniciar
        window.onload = async function () {
            const urlParams = new URLSearchParams(window.location.search);
            const groupId = urlParams.get("id");

            if (!groupId) {
                alert("No se proporcionó un ID de grupo.");
                window.location.href = "../groups/index.html";
                return;
            }

            try {
                const response = await fetch(`../../backend/get-group.php?id=${groupId}`);
                const groupData = await response.json();

                // Rellenar los campos con los datos obtenidos
                document.getElementById("num").value = groupData.num;
                document.getElementById("costo_hora").value = groupData.costo_hora;
                document.getElementById("intensidad").value = groupData.intensidad;
                document.getElementById("id_idioma").value = groupData.id_idioma;
                document.getElementById("id_libro").value = groupData.id_libro;
                document.getElementById("id_level").value = groupData.id_level;
                document.getElementById("horas_tot").value = groupData.horas_tot;
                document.getElementById("fecha_inicio").value = groupData.fecha_inicio;
                document.getElementById("fecha_fin").value = groupData.fecha_fin;

            } catch (error) {
                alert("Error al cargar los datos del grupo.");
                console.error(error);
                window.location.href = "../groups/index.html";
            }
        };
    </script>
</head>

<body>
    <div class="form-container mt-5">
        <h3 class="form-title">Editar Grupo</h3>
        <form id="editGroupForm" action="../../backend/edit-group.php" method="POST">
            <!-- Número de Grupo -->
            <div class="mb-4">
                <label for="num" class="form-label required">Número de Grupo</label>
                <input type="number" id="num" name="num" class="form-control" required>
            </div>

            <!-- Costo por Hora -->
            <div class="mb-4">
                <label for="costo_hora" class="form-label required">Costo por Hora</label>
                <input type="number" id="costo_hora" name="costo_hora" class="form-control" step="0.01" required>
            </div>

            <!-- Intensidad -->
            <div class="mb-4">
                <label for="intensidad" class="form-label required">Intensidad</label>
                <select id="intensidad" name="intensidad" class="form-select" required>
                    <option value="Baja">Baja</option>
                    <option value="Media">Media</option>
                    <option value="Alta">Alta</option>
                </select>
            </div>

            <!-- Idioma -->
            <div class="mb-4">
                <label for="id_idioma" class="form-label required">Idioma</label>
                <select id="id_idioma" name="id_idioma" class="form-select" required>
                    <option value="1">Inglés</option>
                    <option value="2">Francés</option>
                    <option value="3">Alemán</option>
                </select>
            </div>

            <!-- Libro -->
            <div class="mb-4">
                <label for="id_libro" class="form-label required">Libro</label>
                <select id="id_libro" name="id_libro" class="form-select" required>
                    <option value="1">English Starter</option>
                    <option value="2">Intermediate Guide</option>
                </select>
            </div>

            <!-- Nivel -->
            <div class="mb-4">
                <label for="id_level" class="form-label required">Nivel</label>
                <select id="id_level" name="id_level" class="form-select" required>
                    <option value="1">A1 - Beginner</option>
                    <option value="2">A2 - Elementary</option>
                    <option value="3">B1 - Intermediate</option>
                </select>
            </div>

            <!-- Horas Totales -->
            <div class="mb-4">
                <label for="horas_tot" class="form-label required">Horas Totales</label>
                <input type="number" id="horas_tot" name="horas_tot" class="form-control" required>
            </div>

            <!-- Fechas -->
            <div class="mb-4">
                <label for="fecha_inicio" class="form-label required">Fecha de Inicio</label>
                <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control" required>
            </div>
            <div class="mb-4">
                <label for="fecha_fin" class="form-label required">Fecha de Fin</label>
                <input type="date" id="fecha_fin" name="fecha_fin" class="form-control" required>
            </div>

            <!-- Botones -->
            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
                <a href="../groups/index.html" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Cancelar
                </a>
            </div>
        </form>
    </div>

    <script src="../../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>
