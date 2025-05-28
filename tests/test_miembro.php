<?php
// filepath: c:\xampp\htdocs\ENCASA_DATABASE\test_miembro.php

require_once __DIR__ . '/app/config/config.php';
require_once __DIR__ . '/app/config/database.php';
require_once __DIR__ . '/app/models/Model.php';
require_once __DIR__ . '/app/models/Miembro.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 2;

echo "<h1>Prueba de miembro ID: {$id}</h1>";

try {
    // Inicializar modelo
    $miembroModel = new \App\Models\Miembro();
    
    // Verificar existencia
    $exists = $miembroModel->checkMemberExists($id);
    echo "<p>¿Existe el miembro?: " . ($exists ? 'SÍ' : 'NO') . "</p>";
    
    if ($exists) {
        // Obtener perfil
        $miembro = $miembroModel->getFullProfile($id);
        
        echo "<h2>Datos del perfil:</h2>";
        echo "<pre>";
        print_r($miembro);
        echo "</pre>";
        
        echo "<p>URL de perfil: <a href='" . APP_URL . "/miembros/{$id}' target='_blank'>Ver perfil en sistema</a></p>";
    }
} catch (Exception $e) {
    echo "<p style='color:red'>Error: " . $e->getMessage() . "</p>";
}