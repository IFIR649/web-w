<?php
include '../../../php/conexion.php'; // Cambia la ruta si es necesario

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_idioma = $_POST['nombre_idioma'];

    // Validar que el campo no esté vacío
    if (!empty($nombre_idioma)) {
        // Insertar un nuevo idioma en la base de datos
        $query = "INSERT INTO idioma (nombre) VALUES (?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('s', $nombre_idioma);

        if ($stmt->execute()) {
            echo "<script>alert('Idioma agregado correctamente.'); window.location.href = '../../../languages.php';</script>";
        } else {
            echo "<script>alert('Error al agregar el idioma.'); window.history.back();</script>";
        }

        $stmt->close();
        $conn->close();
        exit;
    } else {
        echo "<script>alert('El campo de nombre del idioma es obligatorio.'); window.history.back();</script>";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Agregar Idioma - Lingus</title>
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
        <h3 class="form-title">Agregar Idioma</h3>
        <form id="addLanguageForm" action="" method="POST">
            <!-- Nombre del Idioma -->
            <div class="mb-4">
                <label for="nombre_idioma" class="form-label required">Nombre del Idioma</label>
                <input type="text" id="nombre_idioma" name="nombre_idioma" class="form-control" placeholder="Ejemplo: Inglés, Francés" required>
            </div>

            <!-- Botones -->
            <div class="d-flex justify-content-between mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar
                </button>
                <a href="../../../languages.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Cancelar
                </a>
            </div>
        </form>
    </div>

    <script src="../../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>
