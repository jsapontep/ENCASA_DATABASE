<?php
// filepath: c:\xampp\htdocs\ENCASA_DATABASE\login-test.php

// Mostrar errores
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "Inicio del script<br>";

// Configuración inicial
require_once __DIR__ . '/app/config/config.php';
echo "Config cargado<br>";

require_once __DIR__ . '/app/helpers/functions.php';
echo "Helpers cargados<br>";

// Autoload con diagnóstico
spl_autoload_register(function($className) {
    $classFile = str_replace('\\', '/', $className) . '.php';
    $filepath = __DIR__ . '/' . $classFile;
    
    echo "Intentando cargar: $filepath<br>";
    
    if (file_exists($filepath)) {
        require_once $filepath;
        echo "Clase $className cargada<br>";
    } else {
        echo "Clase $className no encontrada<br>";
    }
});

try {
    // Cargar controlador directamente
    echo "Intentando crear instancia de AuthController<br>";
    $controller = new App\Controllers\AuthController();
    echo "Instancia creada<br>";
    $controller->login();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}