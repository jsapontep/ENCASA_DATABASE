<?php
// filepath: c:\xampp\htdocs\ENCASA_DATABASE\debug_miembro.php

// Script para verificar directamente un miembro en la base de datos
require_once __DIR__ . '/app/config/config.php';
require_once __DIR__ . '/app/config/database.php';

// ID a verificar (opcional por GET)
$id = isset($_GET['id']) ? (int)$_GET['id'] : 2;

echo "<h1>Depuración de Miembro ID: {$id}</h1>";

try {
    // Conexión directa a la base de datos
    $db = Database::getInstance()->getConnection();
    
    // Consulta básica para obtener datos del miembro
    $stmt = $db->prepare("SELECT * FROM InformacionGeneral WHERE id = ?");
    $stmt->execute([$id]);
    $miembro = $stmt->fetch();
    
    if ($miembro) {
        echo "<h2>Miembro encontrado:</h2>";
        echo "<pre>";
        print_r($miembro);
        echo "</pre>";
        
        // Mostrar datos relacionados
        $tablas = ['Contacto', 'EstudiosTrabajo', 'Tallas', 'CarreraBiblica'];
        
        foreach ($tablas as $tabla) {
            $stmt = $db->prepare("SELECT * FROM {$tabla} WHERE miembro_id = ?");
            $stmt->execute([$id]);
            $datos = $stmt->fetch();
            
            echo "<h3>Datos de {$tabla}:</h3>";
            echo "<pre>";
            print_r($datos ?: "No hay datos relacionados");
            echo "</pre>";
        }
        
        echo "<p><a href='" . APP_URL . "/miembros/{$id}' target='_blank'>Ver en el sistema</a></p>";
    } else {
        echo "<p style='color:red'>No se encontró ningún miembro con ID {$id}</p>";
    }
} catch (Exception $e) {
    echo "<p style='color:red'>Error: " . $e->getMessage() . "</p>";
}