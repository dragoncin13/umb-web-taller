<?php
// api/db.php
// Cargar variables de entorno (en Render las configuras en el dashboard)
$database_url = getenv('SUPABASE_DB_URL'); // o construir desde partes

if (!$database_url) {
    // alternativa: leer partes desde env
    $host = getenv('SUPABASE_HOST');
    $port = getenv('SUPABASE_PORT') ?: 5432;
    $dbname = getenv('SUPABASE_DB');
    $user = getenv('SUPABASE_USER');
    $pass = getenv('SUPABASE_PASS');
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
} else {
    // parseamos DATABASE_URL tipo postgres://user:pass@host:port/dbname
    $url = parse_url($database_url);
    $user = $url['user'];
    $pass = $url['pass'];
    $host = $url['host'];
    $port = $url['port'];
    $dbname = ltrim($url['path'],'/');
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
}

try {
    $conexion = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error de conexión a la base de datos: ' . $e->getMessage()]);
    exit;
}
