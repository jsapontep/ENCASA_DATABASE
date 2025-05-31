<?php

// Script para depurar la actualización de miembros
require_once __DIR__ . '/app/config/config.php';
require_once __DIR__ . '/app/config/database.php';

// ID a verificar
$id = isset($_GET['id']) ? (int)$_GET['id'] : 2;

echo "<h1>Depuración de actualización para miembro ID: {$id}</h1>";

try {
    // Obtener conexión directa
    $db = Database::getInstance()->getConnection();
    
    // Verificar si el miembro existe
    $stmt = $db->prepare("SELECT * FROM InformacionGeneral WHERE id = ?");
    $stmt->execute([$id]);
    $miembro = $stmt->fetch();
    
    if (!$miembro) {
        echo "<div style='color:red'>Miembro no encontrado</div>";
        exit;
    }
    
    echo "<h2>Datos actuales del miembro:</h2>";
    echo "<pre>";
    print_r($miembro);
    echo "</pre>";
    
    // Verificar tablas relacionadas
    $tablas = ['Contacto', 'EstudiosTrabajo', 'Tallas', 'SaludEmergencias', 'CarreraBiblica'];
    
    foreach ($tablas as $tabla) {
        $stmt = $db->prepare("SELECT * FROM {$tabla} WHERE miembro_id = ?");
        $stmt->execute([$id]);
        $datos = $stmt->fetch();
        
        echo "<h3>Datos de {$tabla}:</h3>";
        if ($datos) {
            echo "<pre>";
            print_r($datos);
            echo "</pre>";
        } else {
            echo "<p>No hay datos en esta tabla para este miembro</p>";
            
            // Mostrar estructura de la tabla para referencia
            echo "<p>Estructura de la tabla {$tabla}:</p>";
            $stmt = $db->query("DESCRIBE {$tabla}");
            $estructura = $stmt->fetchAll();
            echo "<pre>";
            print_r($estructura);
            echo "</pre>";
        }
    }
    
} catch (Exception $e) {
    echo "<div style='color:red'>Error: " . $e->getMessage() . "</div>";
}
?>