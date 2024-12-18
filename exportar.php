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

    // Obtener todas las tablas
    $sql = "SHOW TABLES";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $tablas = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Nombre del archivo
    $nombreArchivo = 'base_de_datos_exportada.csv';

    // Abrir salida como un archivo temporal
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $nombreArchivo . '"');
    header('Cache-Control: max-age=0');

    $output = fopen('php://output', 'w');

    foreach ($tablas as $tabla) {
        // Escribir el nombre de la tabla como encabezado
        fputcsv($output, ["Tabla: $tabla"]);

        // Obtener los datos de la tabla
        $sql = "SELECT * FROM $tabla";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($datos)) {
            // Escribir encabezados de columna
            fputcsv($output, array_keys($datos[0]));

            // Escribir los datos de la tabla
            foreach ($datos as $row) {
                fputcsv($output, $row);
            }
        } else {
            // Indicar que la tabla está vacía
            fputcsv($output, ["La tabla $tabla no tiene datos."]);
        }

        // Espacio entre tablas
        fputcsv($output, []);
    }

    fclose($output);
    exit;
} catch (PDOException $e) {
    die("Error en la conexión: " . $e->getMessage());
}
