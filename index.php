<?php
// api/index.php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

require_once 'modelo.php';

$metodo = $_SERVER['REQUEST_METHOD'];
$path = $_SERVER['REQUEST_URI']; // si usas rewrite, parsea ruta
$input = json_decode(file_get_contents('php://input'), true);

switch ($metodo) {
    case 'GET':
        $tareas = obtenerTareas();
        echo json_encode($tareas);
        break;
    case 'POST':
        if (!isset($input['titulo'])) {
            http_response_code(400);
            echo json_encode(['error'=>'Falta campo titulo']);
            break;
        }
        $id = crearTarea($input['titulo']);
        echo json_encode(['mensaje'=>'Tarea creada', 'id' => $id]);
        break;
    case 'PUT':
        // esperar id y campos a actualizar
        if (!isset($input['id'])) {
            http_response_code(400);
            echo json_encode(['error'=>'Falta id']);
            break;
        }
        actualizarTarea($input['id'], $input['titulo'] ?? null, $input['completada'] ?? null);
        echo json_encode(['mensaje'=>'Tarea actualizada']);
        break;
    case 'DELETE':
        // si viene ?id=... en querystring
        $id = $_GET['id'] ?? ($input['id'] ?? null);
        if (!$id) { http_response_code(400); echo json_encode(['error'=>'Falta id']); break; }
        eliminarTarea($id);
        echo json_encode(['mensaje'=>'Tarea eliminada']);
        break;
    default:
        http_response_code(405);
        echo json_encode(['error'=>'Método no permitido']);
        break;
}
