<?php
include '../../../php/conexion.php';

// Depuración: verifica conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Manejar la solicitud POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cefr_level = $_POST['cefr_level'];
    $name = $_POST['name'];
    $modu = $_POST['modu'];
    $unite = $_POST['unite'];
    $id_libro = $_POST['id_libro'];

    if (!empty($cefr_level) && !empty($name) && !empty($modu) && !empty($unite) && !empty($id_libro)) {
        $query = "INSERT INTO level (cefr_level, name, modu, unite, id_libro) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ssiii', $cefr_level, $name, $modu, $unite, $id_libro);

        if ($stmt->execute()) {
            echo "<script>alert('Nivel agregado correctamente.'); window.location.href = '../../../levels.php';</script>";
        } else {
            echo "<script>alert('Error al agregar el nivel.'); window.history.back();</script>";
        }
        $stmt->close();
        exit;
    } else {
        echo "<script>alert('Todos los campos son obligatorios.'); window.history.back();</script>";
        exit;
    }
}

// Consultar libros
$query_books = "SELECT id_libro, nombre FROM libro";
$result_books = $conn->query($query_books);

// Depuración: verifica la consulta
if (!$result_books) {
    die("Error en la consulta de libros: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Agregar Nivel - Lingus</title>
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
</head>

<body>
    <div class="form-container mt-5">
        <h3 class="form-title">Agregar Nuevo Nivel</h3>
        <form action="" method="POST">
            <div class="mb-4">
                <label for="cefr_level" class="form-label required">CEFR Level</label>
                <input type="text" id="cefr_level" name="cefr_level" class="form-control" placeholder="Ejemplo: A1, B2, C1" required>
            </div>
            <div class="mb-4">
                <label for="name" class="form-label required">Nombre del Nivel</label>
                <input type="text" id="name" name="name" class="form-control" placeholder="Ejemplo: Beginner, Intermediate" required>
            </div>
            <div class="mb-4">
                <label for="modu" class="form-label required">Módulo</label>
                <input type="number" id="modu" name="modu" class="form-control" placeholder="Número de módulos" required>
            </div>
            <div class="mb-4">
                <label for="unite" class="form-label required">Unidades</label>
                <input type="number" id="unite" name="unite" class="form-control" placeholder="Cantidad de unidades" required>
            </div>
            <div class="mb-4">
                <label for="id_libro" class="form-label required">Libro</label>
                <select id="id_libro" name="id_libro" class="form-select" required>
                    <option value="">Seleccione un libro</option>
                    <?php
                    while ($book = $result_books->fetch_assoc()) {
                        echo "<option value='{$book['id_libro']}'>{$book['nombre']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="d-flex justify-content-between mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar
                </button>
                <a href="../../../levels.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
    <script src="../../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>
