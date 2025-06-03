<?php

// Mostrar todos los errores
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Incluir archivos necesarios
require_once __DIR__ . '/app/config/config.php';
require_once __DIR__ . '/app/config/database.php';

// ID a probar
$id = isset($_GET['id']) ? (int)$_GET['id'] : 2;

echo "<h1>Diagnóstico de edición para miembro ID: $id</h1>";

try {
    // Obtener conexión a la BD
    $db = Database::getInstance()->getConnection();
    
    // 1. Verificar si el miembro existe
    $stmt = $db->prepare("SELECT * FROM informaciongeneral WHERE id = ?");
    $stmt->execute([$id]);
    $miembro = $stmt->fetch(\PDO::FETCH_ASSOC);
    
    if (!$miembro) {
        echo "<p style='color:red'>Error: El miembro con ID $id no existe</p>";
        exit;
    }
    
    echo "<h2>Datos actuales del miembro:</h2>";
    echo "<pre>";
    print_r($miembro);
    echo "</pre>";
    
    // 2. Prueba de actualización
    if (isset($_GET['test']) && $_GET['test'] === '1') {
        $nuevoNombre = "Nombre".time(); // Nombre único para prueba
        
        $sql = "UPDATE informaciongeneral SET nombres = ? WHERE id = ?";
        $stmt = $db->prepare($sql);
        $result = $stmt->execute([$nuevoNombre, $id]);
        
        if ($result) {
            echo "<p style='color:green'>✅ Actualización exitosa directamente en la BD</p>";
            
            // Verificar que se actualizó correctamente
            $stmt = $db->prepare("SELECT nombres FROM informaciongeneral WHERE id = ?");
            $stmt->execute([$id]);
            $nombreActualizado = $stmt->fetchColumn();
            
            echo "<p>Nombre ahora es: <strong>$nombreActualizado</strong></p>";
            
            if ($nombreActualizado === $nuevoNombre) {
                echo "<p style='color:green'>✅ La verificación coincide</p>";
            } else {
                echo "<p style='color:red'>❌ ¡La verificación NO coincide!</p>";
            }
        } else {
            echo "<p style='color:red'>❌ Error al actualizar</p>";
        }
    }
    
    // 3. Link para realizar la prueba
    echo "<p><a href='?id=$id&test=1' class='btn btn-primary'>Ejecutar prueba de actualización</a></p>";
    
} catch (Exception $e) {
    echo "<p style='color:red'>Error: " . $e->getMessage() . "</p>";
}
?>