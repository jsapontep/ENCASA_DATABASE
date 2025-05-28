<?php
// filepath: c:\xampp\htdocs\ENCASA_DATABASE\test_router_simple.php

// Script simplificado para probar la gestión de rutas
require_once __DIR__ . '/app/config/config.php';

// Mostrar la URI actual para depuración
echo "<h1>Información de la URL actual</h1>";
echo "<p>REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "</p>";
echo "<p>URL para prueba: <a href='miembros/2'>miembros/2</a></p>";

// Analizar manualmente la URL para simular el comportamiento del router
$requestUri = $_SERVER['REQUEST_URI'];
$basePath = '/Encasa_Database/';
$route = str_replace($basePath, '', $requestUri);

echo "<p>Ruta extraída: " . htmlspecialchars($route) . "</p>";

// Analizar parámetros de URL
if (preg_match('#^miembros/(\d+)$#', $route, $matches)) {
    echo "<p>ID detectado: " . $matches[1] . "</p>";
}