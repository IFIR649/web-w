<?php
// Configuración de la base de datos
$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "lingus"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Establecer UTF-8
$conn->set_charset("utf8");

// Obtener parámetro
$type = isset($_GET['type']) ? $_GET['type'] : 'alumnos';

$tables = [
    'alumnos' => 'SELECT * FROM alumnos',
    'maestros' => 'SELECT * FROM maestros',
    'grupos' => 'SELECT * FROM grupos',
    'pago' => 'SELECT * FROM pago',
    'idioma' => 'SELECT * FROM idioma',
    'level' => 'SELECT * FROM level',
    'libro' => 'SELECT * FROM libro'
];

if ($type === 'todas') {
    $filename = "todas_las_tablas.csv";
    
    // Encabezados UTF-8 con BOM
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=' . $filename);
    
    $output = fopen('php://output', 'w');
    fwrite($output, "\xEF\xBB\xBF"); // BOM UTF-8
    
    foreach ($tables as $tableName => $query) {
        // Escribir un encabezado para la tabla
        fputcsv($output, ["Tabla: " . ucfirst($tableName)]);
        
        // Ejecutar consulta
        $result = $conn->query($query);
        if ($result && $result->num_rows > 0) {
            // Escribir los nombres de las columnas
            $fields = $result->fetch_fields();
            $headers = [];
            foreach ($fields as $field) {
                $headers[] = $field->name;
            }
            fputcsv($output, $headers);
            
            // Escribir las filas
            while ($row = $result->fetch_assoc()) {
                fputcsv($output, $row);
            }
        } else {
            fputcsv($output, ["No hay datos en la tabla $tableName"]);
        }
        
        // Espacio entre tablas
        fputcsv($output, []);
    }

    fclose($output);
    $conn->close();
    exit;
}

// Exportar tabla individual
if (array_key_exists($type, $tables)) {
    $query = $tables[$type];
    $filename = $type . ".csv";
    $result = $conn->query($query);

    if (!$result) {
        die("Error en la consulta: " . $conn->error);
    }

    // Encabezados UTF-8 con BOM
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=' . $filename);

    $output = fopen('php://output', 'w');
    fwrite($output, "\xEF\xBB\xBF");

    if ($result->num_rows > 0) {
        // Escribir nombres de columnas
        $fields = $result->fetch_fields();
        $headers = [];
        foreach ($fields as $field) {
            $headers[] = $field->name;
        }
        fputcsv($output, $headers);

        // Escribir filas
        while ($row = $result->fetch_assoc()) {
            fputcsv($output, $row);
        }
    } else {
        echo "No hay datos.";
    }

    fclose($output);
    $conn->close();
    exit;
}

echo "Tipo no válido.";
$conn->close();
exit;
?>
