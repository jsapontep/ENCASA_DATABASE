<?php
// filepath: c:\xampp\htdocs\ENCASA_DATABASE\test_router.php

// Script para probar la gestión de parámetros en rutas
require_once __DIR__ . '/app/config/config.php';
require_once __DIR__ . '/app/helpers/Router.php';

echo "<h1>Prueba de Router y Parámetros</h1>";

// Clase de prueba simple
class TestController {
    public function test($id = null) {
        echo "<p>Método test() recibió ID: " . ($id ?? "null") . "</p>";
        return true;
    }
}

// Crear router de prueba
$router = new \App\Helpers\Router();
$router->get('test/{id}', 'Test', 'test');

// Simular solicitudes
echo "<h2>Probando ruta: test/123</h2>";
$_SERVER['REQUEST_URI'] = '/Encasa_Database/test/123';
$router->setControllerTestMode(new TestController());
$router->dispatch();

echo "<p><a href='miembros/2'>Ir a miembros/2</a></p>";