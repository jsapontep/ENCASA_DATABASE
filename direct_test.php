<?php
// filepath: c:\xampp\htdocs\ENCASA_DATABASE\direct_test.php

// Script para probar la extracción directa de un miembro
require_once __DIR__ . '/app/config/config.php';
require_once __DIR__ . '/app/config/database.php';

echo "<h1>Prueba directa de acceso a la base de datos</h1>";

// ID a verificar
$id = isset($_GET['id']) ? (int)$_GET['id'] : 2;

echo "<p>Buscando miembro con ID: {$id}</p>";

try {
    // Conectar directamente a la BD
    $db = Database::getInstance()->getConnection();
    
    // Consulta SQL directa
    $sql = "SELECT * FROM InformacionGeneral WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$id]);
    $miembro = $stmt->fetch(\PDO::FETCH_ASSOC);
    
    if ($miembro) {
        echo "<h2>Datos básicos encontrados:</h2>";
        echo "<pre>";
        print_r($miembro);
        echo "</pre>";
        
        // Recuperar datos relacionados
        $tablas = ['Contacto', 'EstudiosTrabajo', 'Tallas', 'CarreraBiblica'];
        
        foreach ($tablas as $tabla) {
            $sql = "SELECT * FROM {$tabla} WHERE miembro_id = ?";
            $stmt = $db->prepare($sql);
            $stmt->execute([$id]);
            $datos = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            echo "<h3>Datos de {$tabla}:</h3>";
            echo "<pre>";
            print_r($datos ?: "No hay datos");
            echo "</pre>";
        }
        
        // Link para ver la visualización normal
        echo "<p><a href='miembros/{$id}?force=true' target='_blank'>Ver perfil en sistema</a></p>";
    } else {
        echo "<p style='color:red'>No se encontró ningún miembro con ID {$id}</p>";
    }
} catch (Exception $e) {
    echo "<p style='color:red'>Error: " . $e->getMessage() . "</p>";
}