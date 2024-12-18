<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Editar Maestro - Lingus</title>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Función para cargar datos del maestro
        async function loadTeacherData() {
            const urlParams = new URLSearchParams(window.location.search);
            const teacherId = urlParams.get("id");

            if (!teacherId) {
                alert("No se proporcionó un ID de maestro.");
                window.location.href = "../teachers/index.html";
                return;
            }

            try {
                // Simulación de llamada al backend
                const response = await fetch(`../../backend/get-teacher.php?id=${teacherId}`);
                const teacherData = await response.json();

                // Rellenar el formulario con los datos
                document.getElementById("hiddenId").value = teacherData.id_maestro;
                document.getElementById("nombre").value = teacherData.nombre;
                document.getElementById("apellido_paterno").value = teacherData.apellido_paterno;
                document.getElementById("apellido_materno").value = teacherData.apellido_materno || "";
                document.getElementById("correo").value = teacherData.correo;
                document.getElementById("horas_tot").value = teacherData.horas_tot;
                document.getElementById("certificado").value = teacherData.certificado || "";
            } catch (error) {
                alert("Error al cargar los datos del maestro.");
                console.error(error);
                window.location.href = "../teachers/index.html";
            }
        }

        // Cargar datos al iniciar la página
        window.onload = loadTeacherData;
    </script>
</head>

<body>
    <div class="form-container">
        <h3 class="form-title">Editar Maestro</h3>
        <form id="editTeacherForm" action="../../backend/edit-teacher.php" method="POST">
            <!-- ID Oculto -->
            <input type="hidden" id="hiddenId" name="id_maestro">

            <!-- Nombre Completo -->
            <div class="mb-4">
                <label for="nombre" class="form-label required">Nombre</label>
                <input type="text" id="nombre" name="nombre" class="form-control" placeholder="Nombre del maestro" required>
            </div>
            <div class="mb-4">
                <label for="apellido_paterno" class="form-label required">Apellido Paterno</label>
                <input type="text" id="apellido_paterno" name="apellido_paterno" class="form-control" placeholder="Apellido paterno" required>
            </div>
            <div class="mb-4">
                <label for="apellido_materno" class="form-label">Apellido Materno</label>
                <input type="text" id="apellido_materno" name="apellido_materno" class="form-control" placeholder="Apellido materno">
            </div>

            <!-- Correo Electrónico -->
            <div class="mb-4">
                <label for="correo" class="form-label required">Correo Electrónico</label>
                <input type="email" id="correo" name="correo" class="form-control" placeholder="Correo del maestro" required>
            </div>

            <!-- Horas Totales -->
            <div class="mb-4">
                <label for="horas_tot" class="form-label required">Horas Totales</label>
                <input type="number" id="horas_tot" name="horas_tot" class="form-control" placeholder="Ejemplo: 40" required>
            </div>

            <!-- Certificación -->
            <div class="mb-4">
                <label for="certificado" class="form-label">Certificación</label>
                <input type="text" id="certificado" name="certificado" class="form-control" placeholder="Ejemplo: TESOL, CELTA">
            </div>

            <!-- Botones -->
            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
                <a href="../teachers/index.html" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Cancelar
                </a>
            </div>
        </form>
    </div>

    <script src="../../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>
