<?php
// Configuración de conexión a la base de datos
$host = 'localhost';
$dbname = 'lingus';
$username = 'root';
$password = '';

try {
    // Conexión a la base de datos
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Configurar las cabeceras para Excel
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=base_de_datos.xls");
    header("Cache-Control: max-age=0");

    echo '<html>';
    echo '<head><meta charset="UTF-8"></head>';
    echo '<body>';

    // Obtener todas las tablas
    $sql = "SHOW TABLES";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $tablas = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Crear una sección para cada tabla
    foreach ($tablas as $tabla) {
        // Título de la tabla
        echo '<h3 style="text-align:left;">Tabla: ' . htmlspecialchars($tabla) . '</h3>';

        // Iniciar tabla HTML
        echo '<table border="1">';
        echo '<tr><th colspan="100">Tabla: ' . htmlspecialchars($tabla) . '</th></tr>';

        // Obtener los datos de la tabla
        $sql = "SELECT * FROM $tabla";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($datos)) {
            // Escribir encabezados de columna
            echo '<tr>';
            foreach (array_keys($datos[0]) as $columna) {
                echo '<th>' . htmlspecialchars($columna) . '</th>';
            }
            echo '</tr>';

            // Escribir los datos de la tabla
            foreach ($datos as $row) {
                echo '<tr>';
                foreach ($row as $cell) {
                    echo '<td>' . htmlspecialchars($cell) . '</td>';
                }
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="100">La tabla está vacía.</td></tr>';
        }

        // Cerrar tabla
        echo '</table><br>'; // Espacio entre tablas
    }

    echo '</body>';
    echo '</html>';
    exit;
} catch (PDOException $e) {
    die("Error en la conexión: " . $e->getMessage());
}
