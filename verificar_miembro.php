<?php

require_once __DIR__ . '/app/config/config.php';
require_once __DIR__ . '/app/config/database.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 1;

try {
    $db = Database::getInstance()->getConnection();
    
    // Consulta directa
    $stmt = $db->prepare("SELECT * FROM InformacionGeneral WHERE id = ?");
    $stmt->execute([$id]);
    $miembro = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "<h1>Verificación directa de miembro ID: $id</h1>";
    
    if ($miembro) {
        echo "<p style='color:green'>✅ El miembro SÍ existe en la base de datos</p>";
        echo "<pre>";
        print_r($miembro);
        echo "</pre>";
    } else {
        echo "<p style='color:red'>❌ El miembro NO existe en la base de datos</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color:red'>Error: " . $e->getMessage() . "</p>";
}
?>