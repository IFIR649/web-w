<?php
// Incluir conexión a la base de datos
include '../../../php/conexion.php';

// Verificar si se proporciona un ID válido
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<script>alert('ID de nivel no válido.'); window.location.href = '../../../levels.php';</script>";
    exit;
}

$id_level = intval($_GET['id']);

// Consultar los detalles del nivel
$query = "SELECT l.cefr_level, l.name, l.modu, l.unite, b.nombre AS libro
          FROM level l
          LEFT JOIN libro b ON l.id_libro = b.id_libro
          WHERE l.id_level = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $id_level);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $nivel = $result->fetch_assoc();
} else {
    echo "<script>alert('Nivel no encontrado.'); window.location.href = '../../../levels.php';</script>";
    exit;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Ver Nivel - Lingus</title>
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
</head>

<body>
    <div class="form-container mt-5">
        <h3 class="form-title">Detalles del Nivel</h3>

        <!-- CEFR Level -->
        <div class="mb-3">
            <label class="form-label">CEFR Level:</label>
            <p class="form-value"><?php echo htmlspecialchars($nivel['cefr_level'] ?? 'N/A'); ?></p>
        </div>

        <!-- Nombre del Nivel -->
        <div class="mb-3">
            <label class="form-label">Nombre del Nivel:</label>
            <p class="form-value"><?php echo htmlspecialchars($nivel['name'] ?? 'N/A'); ?></p>
        </div>

        <!-- Módulo -->
        <div class="mb-3">
            <label class="form-label">Módulo:</label>
            <p class="form-value"><?php echo htmlspecialchars($nivel['modu'] ?? 'N/A'); ?></p>
        </div>

        <!-- Unidades -->
        <div class="mb-3">
            <label class="form-label">Unidades:</label>
            <p class="form-value"><?php echo htmlspecialchars($nivel['unite'] ?? 'N/A'); ?></p>
        </div>

        <!-- Libro Relacionado -->
        <div class="mb-3">
            <label class="form-label">Libro:</label>
            <p class="form-value"><?php echo htmlspecialchars($nivel['libro'] ?? 'N/A'); ?></p>
        </div>

        <!-- Botón para regresar -->
        <div class="text-end mt-4">
            <a href="../../../levels.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <script src="../../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>
