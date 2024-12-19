<?php
include '../../../php/conexion.php'; // Cambia la ruta si es necesario

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_grupo = intval($_POST['id_grupo']);
    $matricula = intval($_POST['matricula']);

    // Eliminar el alumno del grupo
    $query = "DELETE FROM grupo_participantes WHERE id_grupo = ? AND matricula = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ii', $id_grupo, $matricula);

    if ($stmt->execute()) {
        echo "<script>alert('Alumno eliminado exitosamente.'); window.location.href = 'edit-group.php?id=$id_grupo';</script>";
    } else {
        echo "<script>alert('Error al eliminar el alumno.'); window.location.href = 'edit-group.php?id=$id_grupo';</script>";
    }

    $stmt->close();
}

$conn->close();
?>