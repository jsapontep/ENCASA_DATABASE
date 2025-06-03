<?php

// Mostrar todos los errores
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Incluir archivos necesarios
require_once __DIR__ . '/app/config/config.php';
require_once __DIR__ . '/app/config/database.php';

// Obtener ID del miembro a verificar
$id = isset($_GET['id']) ? (int)$_GET['id'] : 1;
$campo = $_GET['campo'] ?? 'nombres';
$valor = $_GET['valor'] ?? 'Actualizado-' . date('His');

echo "<h1>Diagnóstico de actualización para ID: $id</h1>";

try {
    // Conexión directa a la BD
    $db = Database::getInstance()->getConnection();
    
    // 1. Verificar que el miembro existe
    $stmt = $db->prepare("SELECT * FROM informaciongeneral WHERE id = ?");
    $stmt->execute([$id]);
    $miembro = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$miembro) {
        echo "<p style='color:red;font-weight:bold;'>ERROR: No existe miembro con ID $id</p>";
        // Listar IDs disponibles
        $stmt = $db->query("SELECT id, nombres, apellidos FROM informaciongeneral ORDER BY id");
        echo "<p>Miembros disponibles:</p>";
        echo "<ul>";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<li><a href='?id={$row['id']}'>{$row['nombres']} {$row['apellidos']} (ID: {$row['id']})</a></li>";
        }
        echo "</ul>";
        exit;
    }
    
    echo "<p style='color:green;'>✓ Miembro encontrado: {$miembro['nombres']} {$miembro['apellidos']}</p>";
    
    // 2. Mostrar valores actuales
    echo "<h2>Valores actuales:</h2>";
    echo "<pre>";
    print_r($miembro);
    echo "</pre>";
    
    // 3. Intentar actualizar un campo específico
    echo "<h2>Actualización directa:</h2>";
    
    if (isset($_GET['actualizar']) && $_GET['actualizar'] == '1') {
        $sql = "UPDATE informaciongeneral SET $campo = ? WHERE id = ?";
        $stmt = $db->prepare($sql);
        $resultado = $stmt->execute([$valor, $id]);
        
        if ($resultado) {
            echo "<p style='color:green;'>✓ Actualización exitosa del campo '$campo' a '$valor'</p>";
            
            // Mostrar valores actualizados
            $stmt = $db->prepare("SELECT * FROM informaciongeneral WHERE id = ?");
            $stmt->execute([$id]);
            $actualizado = $stmt->fetch(PDO::FETCH_ASSOC);
            
            echo "<h3>Valores actualizados:</h3>";
            echo "<pre>";
            print_r($actualizado);
            echo "</pre>";
            
            echo "<p><a href='miembros/{$id}'>Ver perfil actualizado</a></p>";
        } else {
            echo "<p style='color:red;'>✗ Error al actualizar</p>";
        }
    } else {
        echo "<p><a href='?id=$id&campo=$campo&valor=$valor&actualizar=1' style='padding:10px;background:#007bff;color:white;text-decoration:none;'>
              Actualizar campo '$campo' a '$valor'</a></p>";
    }
    
    echo "<h3>Probar otras actualizaciones:</h3>";
    echo "<ul>
        <li><a href='?id=$id&campo=nombres&valor=Nombre-Nuevo'>Actualizar nombres</a></li>
        <li><a href='?id=$id&campo=apellidos&valor=Apellido-Prueba'>Actualizar apellidos</a></li>
        <li><a href='?id=$id&campo=estado_espiritual&valor=Consolidado'>Actualizar estado espiritual</a></li>
        <li><a href='?id=$id&campo=tipo_miembro&valor=Activo'>Actualizar tipo de miembro</a></li>
    </ul>";
    
} catch (Exception $e) {
    echo "<p style='color:red;'>Error: " . $e->getMessage() . "</p>";
    echo "<p>Detalles: <pre>" . $e->getTraceAsString() . "</pre></p>";
}
?>

<style>
    body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }
    pre { background: #f5f5f5; padding: 10px; border: 1px solid #ddd; }
    h1, h2, h3 { color: #333; }
</style>