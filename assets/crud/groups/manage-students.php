<?php
include '../../../php/conexion.php'; // Cambia la ruta si es necesario

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_grupo = intval($_POST['id_grupo']);
    $id_maestro = intval($_POST['id_maestro']); // Asegúrate de que el id_maestro se pase correctamente

    $conn->begin_transaction();

    try {
        // Eliminar los alumnos existentes si están marcados para eliminación
        if (!empty($_POST['existing_alumnos'])) {
            foreach ($_POST['existing_alumnos'] as $matricula) {
                $query = "DELETE FROM grupo_participantes WHERE id_grupo = ? AND matricula = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param('ii', $id_grupo, $matricula);
                $stmt->execute();
                $stmt->close();
            }
        }

        // Insertar los nuevos alumnos
        if (!empty($_POST['new_alumnos'])) {
            foreach ($_POST['new_alumnos'] as $matricula) {
                // Verificar si el alumno ya existe en el grupo
                $queryCheck = "SELECT COUNT(*) FROM grupo_participantes WHERE id_grupo = ? AND matricula = ?";
                $stmtCheck = $conn->prepare($queryCheck);
                $stmtCheck->bind_param('ii', $id_grupo, $matricula);
                $stmtCheck->execute();
                $stmtCheck->bind_result($count);
                $stmtCheck->fetch();
                $stmtCheck->close();

                if ($count == 0) {
                    $query = "INSERT INTO grupo_participantes (id_grupo, id_maestro, matricula) VALUES (?, ?, ?)";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param('iii', $id_grupo, $id_maestro, $matricula);
                    $stmt->execute();
                    $stmt->close();
                }
            }
        }

        $conn->commit();
        echo "<script>alert('Alumnos actualizados exitosamente.'); window.location.href = '../../../groups.php';</script>";
    } catch (Exception $e) {
        $conn->rollback();
        echo "<script>alert('Error al actualizar los alumnos: " . $e->getMessage() . "'); window.location.href = 'edit-students.php?id=$id_grupo';</script>";
    }

    $conn->close();
}
?>