<?php
include '../../../php/conexion.php'; // Cambia la ruta si es necesario

// Inicializar variables
$level = [];
$books = [];

// Verificar si se proporciona el ID del nivel
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id_level = intval($_GET['id']); // Sanitizar el ID del nivel

    // Consultar los detalles del nivel
    $query = "SELECT l.cefr_level, l.name, l.modu, l.unite, l.id_libro FROM level l WHERE l.id_level = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id_level);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $level = $result->fetch_assoc();
    } else {
        echo "<script>alert('Nivel no encontrado.'); window.location.href = '../levels/index.html';</script>";
        exit;
    }

    $stmt->close();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Manejar la edición del nivel
    $id_level = intval($_POST['id_level']);
    $cefr_level = $_POST['cefr_level'];
    $name = $_POST['name'];
    $modu = intval($_POST['modu']);
    $unite = intval($_POST['unite']);
    $id_libro = intval($_POST['id_libro']);

    // Actualizar los detalles del nivel
    $update_query = "UPDATE level SET cefr_level = ?, name = ?, modu = ?, unite = ?, id_libro = ? WHERE id_level = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param('ssiiii', $cefr_level, $name, $modu, $unite, $id_libro, $id_level);

    if ($stmt->execute()) {
        echo "<script>alert('Nivel actualizado correctamente.'); window.location.href = '../../../levels.php';</script>";
    } else {
        echo "<script>alert('Error al actualizar el nivel.'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
    exit;
}

// Consultar los libros disponibles
$query_books = "SELECT id_libro, nombre FROM libro";
$result_books = $conn->query($query_books);
if ($result_books->num_rows > 0) {
    while ($row = $result_books->fetch_assoc()) {
        $books[] = $row;
    }
}

$conn->close();
?>

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
</head>

<body>
    <div class="form-container mt-5">
        <h3 class="form-title">Editar Nivel</h3>
        <form method="POST">
            <input type="hidden" name="id_level" value="<?php echo htmlspecialchars($id_level); ?>">

            <!-- CEFR Level -->
            <div class="mb-4">
                <label for="cefr_level" class="form-label required">CEFR Level</label>
                <input type="text" id="cefr_level" name="cefr_level" class="form-control" value="<?php echo htmlspecialchars($level['cefr_level']); ?>" required>
            </div>

            <!-- Nombre del Nivel -->
            <div class="mb-4">
                <label for="name" class="form-label required">Nombre del Nivel</label>
                <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($level['name']); ?>" required>
            </div>

            <!-- Modularidad -->
            <div class="mb-4">
                <label for="modu" class="form-label required">Módulo</label>
                <input type="number" id="modu" name="modu" class="form-control" value="<?php echo htmlspecialchars($level['modu']); ?>" required>
            </div>

            <!-- Unidades -->
            <div class="mb-4">
                <label for="unite" class="form-label required">Unidades</label>
                <input type="number" id="unite" name="unite" class="form-control" value="<?php echo htmlspecialchars($level['unite']); ?>" required>
            </div>

            <!-- Libro Relacionado -->
            <div class="mb-4">
                <label for="id_libro" class="form-label required">Libro</label>
                <select id="id_libro" name="id_libro" class="form-select" required>
                    <option value="">Seleccione un libro</option>
                    <?php foreach ($books as $book): ?>
                        <option value="<?php echo $book['id_libro']; ?>" <?php echo ($level['id_libro'] == $book['id_libro']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($book['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Botones -->
            <div class="d-flex justify-content-between mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar Cambios
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
                        