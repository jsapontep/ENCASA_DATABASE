<?php
// filepath: c:\xampp\htdocs\ENCASA_DATABASE\api_proxy.php

// Configuración básica y manejo de errores
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Habilitar CORS en todas las respuestas
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Credentials: true");

// Si es una solicitud OPTIONS (preflight), responder inmediatamente
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Obtener la ruta solicitada desde el parámetro 'path'
$path = isset($_GET['path']) ? $_GET['path'] : '';

// URL base de tu API local
$target_base = 'http://localhost/ENCASA_DATABASE/';

// Construir la URL completa
$target_url = $target_base . $path;

// Inicializar cURL
$ch = curl_init($target_url);

// Configurar opciones de cURL
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

// Transferir el método HTTP
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $_SERVER['REQUEST_METHOD']);

// Transferir las cabeceras
$headers = [];
foreach (getallheaders() as $name => $value) {
    if (!in_array(strtolower($name), ['host', 'origin', 'referer'])) {
        $headers[] = "$name: $value";
    }
}
if (!empty($headers)) {
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
}

// Para solicitudes POST/PUT, transferir el cuerpo
if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'PUT') {
    $input = file_get_contents('php://input');
    if (!empty($input)) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, $input);
    } else if (!empty($_POST)) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($_POST));
    }
}

// Ejecutar la solicitud
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// Establecer el código de estado HTTP
http_response_code($http_code);

// Cerrar la conexión cURL
curl_close($ch);

// Devolver la respuesta
echo $response;
