<?php
// api/modelo.php
require_once 'db.php';

// CREATE
function crearTarea($titulo) {
    global $conexion;
    $sql = "INSERT INTO tareas (titulo) VALUES (:titulo)";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([':titulo' => $titulo]);
    return $conexion->lastInsertId('tareas_id_seq'); // opcional
}

// READ
function obtenerTareas() {
    global $conexion;
    $sql = "SELECT id, titulo, completada FROM tareas ORDER BY id DESC";
    $stmt = $conexion->query($sql);
    return $stmt->fetchAll();
}

// UPDATE (toggle completada o editar título)
function actualizarTarea($id, $titulo = null, $completada = null) {
    global $conexion;
    $set = [];
    $params = [':id' => $id];
    if (!is_null($titulo)) {
        $set[] = "titulo = :titulo";
        $params[':titulo'] = $titulo;
    }
    if (!is_null($completada)) {
        $set[] = "completada = :completada";
        $params[':completada'] = $completada ? 'TRUE' : 'FALSE';
    }
    if (empty($set)) return false;
    $sql = "UPDATE tareas SET " . implode(', ', $set) . " WHERE id = :id";
    $stmt = $conexion->prepare($sql);
    return $stmt->execute($params);
}

// DELETE
function eliminarTarea($id) {
    global $conexion;
    $sql = "DELETE FROM tareas WHERE id = :id";
    $stmt = $conexion->prepare($sql);
    return $stmt->execute([':id' => $id]);
}
