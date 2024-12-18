<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Ver Maestro - Lingus</title>
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

        .form-control-plaintext {
            border: none;
            padding: 0;
            color: #1f3c88;
            font-weight: 600;
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
        // Función para cargar los detalles del maestro
        async function loadTeacherDetails() {
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

                // Rellenar los campos con los datos
                document.getElementById("id_maestro").textContent = teacherData.id_maestro;
                document.getElementById("nombre").textContent = `${teacherData.nombre} ${teacherData.apellido_paterno} ${teacherData.apellido_materno}`;
                document.getElementById("correo").textContent = teacherData.correo || "No disponible";
                document.getElementById("horas_tot").textContent = teacherData.horas_tot || "N/A";
                document.getElementById("certificado").textContent = teacherData.certificado || "Sin certificación";
            } catch (error) {
                alert("Error al cargar los datos del maestro.");
                console.error(error);
                window.location.href = "../teachers/index.html";
            }
        }

        // Cargar datos al iniciar la página
        window.onload = loadTeacherDetails;
    </script>
</head>

<body>
    <div class="form-container">
        <h3 class="form-title">Detalles del Maestro</h3>
        <div class="mb-3">
            <label class="form-label">ID:</label>
            <p id="id_maestro" class="form-control-plaintext"></p>
        </div>
        <div class="mb-3">
            <label class="form-label">Nombre Completo:</label>
            <p id="nombre" class="form-control-plaintext"></p>
        </div>
        <div class="mb-3">
            <label class="form-label">Correo Electrónico:</label>
            <p id="correo" class="form-control-plaintext"></p>
        </div>
        <div class="mb-3">
            <label class="form-label">Horas Totales:</label>
            <p id="horas_tot" class="form-control-plaintext"></p>
        </div>
        <div class="mb-3">
            <label class="form-label">Certificación:</label>
            <p id="certificado" class="form-control-plaintext"></p>
        </div>

        <!-- Botón para volver -->
        <div class="d-flex justify-content-end">
            <a href="../teachers/index.html" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <script src="../../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>
