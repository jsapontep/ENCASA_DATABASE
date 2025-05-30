<?php

// Este archivo permite probar la función url() directamente

// Definir constantes del sistema básicas
define('BASE_PATH', __DIR__);
define('APP_PATH', BASE_PATH . '/app');

// Cargar la función url()
require_once APP_PATH . '/helpers/functions.php';

// Detectar entorno para información
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$is_tunnel = strpos($host, 'localto.net') !== false || 
             strpos($host, 'loca.lt') !== false ||
             strpos($host, 'ngrok-free.app') !== false;

echo "<h1>Prueba de función url()</h1>";
echo "<p><strong>Host:</strong> $host</p>";
echo "<p><strong>Protocolo:</strong> $protocol</p>";
echo "<p><strong>¿Es túnel?:</strong> " . ($is_tunnel ? "SÍ" : "NO") . "</p>";

// Probar la generación de URLs
$urls = [
    '' => 'URL base',
    'login' => 'URL de login',
    'miembros' => 'URL de miembros',
    'miembros/crear' => 'URL para crear miembro'
];

echo "<h2>URLs generadas:</h2>";
echo "<ul>";
foreach ($urls as $path => $description) {
    echo "<li><strong>$description:</strong> " . url($path) . "</li>";
}
echo "</ul>";

echo "<h2>Enlaces de prueba:</h2>";
echo "<ul>";
foreach ($urls as $path => $description) {
    echo "<li><a href='" . url($path) . "' target='_blank'>$description</a></li>";
}
echo "</ul>";
?>