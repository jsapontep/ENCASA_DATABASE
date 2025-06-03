<?php

// Mostrar todos los errores
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Incluir configuración
require_once __DIR__ . '/app/config/config.php';
require_once __DIR__ . '/app/config/database.php';

// Obtener parámetros
$id = isset($_GET['id']) ? (int)$_GET['id'] : 1;
$campo = $_GET['campo'] ?? 'nombres';
$valor = $_GET['valor'] ?? 'Nombre Actualizado';

echo "<h1>Actualización forzada para miembro ID: $id</h1>";

try {
    // Conexión directa a la base de datos
    $db = Database::getInstance()->getConnection();
    
    // Verificar si el miembro existe
    $stmt = $db->prepare("SELECT * FROM InformacionGeneral WHERE id = ?");
    $stmt->execute([$id]);
    $miembro = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$miembro) {
        echo "<p style='color:red'>❌ No existe ningún miembro con ID $id</p>";
        echo "<p>Miembros disponibles:</p>";
        $stmt = $db->query("SELECT id, nombres, apellidos FROM InformacionGeneral");
        echo "<ul>";
        while ($row = $stmt->fetch()) {
            echo "<li><a href='?id={$row['id']}'>{$row['nombres']} {$row['apellidos']} (ID: {$row['id']})</a></li>";
        }
        echo "</ul>";
        exit;
    }
    
    echo "<p>Verificación directa de miembro ID: $id</p>";
    echo "<p style='color:green'>✅ El miembro SÍ existe en la base de datos</p>";
    
    echo "<pre>";
    print_r($miembro);
    echo "</pre>";
    
    // Realizar actualización directa
    echo "<h2>Actualizando campo '$campo' a '$valor'...</h2>";
    
    $sql = "UPDATE InformacionGeneral SET $campo = ? WHERE id = ?";
    $stmt = $db->prepare($sql);
    $result = $stmt->execute([$valor, $id]);
    
    if ($result) {
        echo "<p style='color:green'>✅ Actualización exitosa del campo '$campo' para miembro ID $id</p>";
        echo "<p><a href='miembros/$id'>Ver perfil del miembro</a></p>";
    } else {
        echo "<p style='color:red'>❌ Falló la actualización</p>";
    }
} catch (Exception $e) {
    echo "<p style='color:red'>Error: " . $e->getMessage() . "</p>";
}