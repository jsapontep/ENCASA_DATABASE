<?php
// filepath: c:\xampp\htdocs\ENCASA_DATABASE\verify_router.php

require_once __DIR__ . '/app/config/config.php';
require_once __DIR__ . '/app/Controllers/MiembrosController.php';
require_once __DIR__ . '/app/models/Miembro.php';
require_once __DIR__ . '/app/config/database.php';

echo "<h1>Verificación de Router y Controlador</h1>";

// Simular una solicitud a miembros/2
$_SERVER['REQUEST_URI'] = '/Encasa_Database/miembros/2';

echo "<p>Simulando acceso a URI: {$_SERVER['REQUEST_URI']}</p>";

// Extracción manual del ID como lo haría el router
if (preg_match('#/miembros/(\d+)#', $_SERVER['REQUEST_URI'], $matches)) {
    $id = (int)$matches[1];
    echo "<p>ID extraído: {$id}</p>";
    
    // Crear instancia del controlador y llamar al método directamente
    try {
        $controller = new \App\Controllers\MiembrosController();
        echo "<p>Controlador instanciado correctamente</p>";
        
        // Llamar al método ver() directamente
        $result = $controller->ver($id);
        echo "<p>Método ver() ejecutado con resultado: " . ($result ? "OK" : "Error") . "</p>";
    } catch (Exception $e) {
        echo "<p style='color:red'>Error al ejecutar el controlador: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p style='color:red'>No se pudo extraer ID de la URI</p>";
}

echo "<p><a href='miembros/2?nocache=" . time() . "' target='_blank'>Intentar acceso normal</a></p>";