<?php
// filepath: c:\xampp\htdocs\ENCASA_DATABASE\proxy.php

// Configuración básica y manejo de errores
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Orígenes permitidos
$allowed_origins = [
    'http://localhost',
    'https://localhost',
    'https://uvca8jwlr.localto.net',
    'http://uvca8jwlr.localto.net',
    'http://127.0.0.1'
];

// Capturar el origen de la solicitud
$origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';

// Configurar cabeceras CORS
if (in_array($origin, $allowed_origins)) {
    header("Access-Control-Allow-Origin: $origin");
} else {
    header("Access-Control-Allow-Origin: *");
}

header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Credentials: true");

// Si es una solicitud OPTIONS (preflight), responder inmediatamente
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header("HTTP/1.1 200 OK");
    exit;
}

// Construir la URL de destino
// Esta será la URL interna donde se encuentra realmente tu aplicación
$target_base = 'http://localhost/ENCASA_DATABASE';
$request_uri = $_SERVER['REQUEST_URI'];

// Eliminar la parte del proxy de la URI si existe
$request_path = str_replace('/proxy.php', '', $request_uri);

// URL final a la que se enviará la solicitud
$target_url = $target_base . $request_path;

// Crear contexto para la solicitud con todos los parámetros y headers originales
$context_options = [
    'http' => [
        'method' => $_SERVER['REQUEST_METHOD']
    ]
];

// Transferir headers de la solicitud original
$headers = [];
foreach (getallheaders() as $name => $value) {
    // No transferir headers relacionados con CORS o host
    if (!in_array(strtolower($name), ['host', 'origin', 'referer'])) {
        $headers[] = "$name: $value";
    }
}

// Si hay headers, añadirlos al contexto
if (!empty($headers)) {
    $context_options['http']['header'] = implode("\r\n", $headers);
}

// Para solicitudes POST/PUT, transferir el cuerpo de la solicitud
$input = file_get_contents('php://input');
if (!empty($input)) {
    $context_options['http']['content'] = $input;
}

// Crear el contexto de flujo
$context = stream_context_create($context_options);

// Enviar la solicitud al servidor interno y capturar la respuesta
try {
    $response = file_get_contents($target_url, false, $context);
    
    // Transferir todos los headers de respuesta
    foreach ($http_response_header as $header) {
        // No transferir headers relacionados con CORS
        if (!strpos(strtolower($header), 'access-control')) {
            header($header);
        }
    }
    
    // Devolver el contenido de la respuesta
    echo $response;
} catch (Exception $e) {
    // En caso de error
    header("HTTP/1.1 500 Internal Server Error");
    echo json_encode([
        'error' => true,
        'message' => 'Proxy error: ' . $e->getMessage(),
        'target_url' => $target_url
    ]);
}