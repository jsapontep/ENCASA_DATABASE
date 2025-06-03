<?php

// Script para verificar conexión y datos en la base de datos
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'app/config/Database.php';

echo "<h1>Verificación de Datos</h1>";

try {
    $db = Database::getInstance()->getConnection();
    echo "<p style='color:green'>✅ Conexión a base de datos exitosa</p>";
    
    // Verificar tablas
    $tablas = [
        'informaciongeneral', 'contacto', 'estudiostrabajo', 
        'tallas', 'saludemergencias', 'carrerabiblica'
    ];
    
    echo "<h2>Estructura de tablas</h2>";
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Tabla</th><th>Existe</th><th>Cantidad de registros</th></tr>";
    
    foreach ($tablas as $tabla) {
        // Verificar si la tabla existe
        try {
            $stmt = $db->query("SHOW TABLES LIKE '{$tabla}'");
            $existe = $stmt->rowCount() > 0;
            
            // Contar registros si la tabla existe
            $cantidad = 0;
            if ($existe) {
                $stmt = $db->query("SELECT COUNT(*) as total FROM {$tabla}");
                $resultado = $stmt->fetch();
                $cantidad = $resultado['total'];
            }
            
            $color = $existe ? 'green' : 'red';
            $icon = $existe ? '✅' : '❌';
            
            echo "<tr><td>{$tabla}</td><td style='color:{$color}'>{$icon} " . ($existe ? 'Sí' : 'No') . "</td><td>{$cantidad}</td></tr>";
            
        } catch (PDOException $e) {
            echo "<tr><td>{$tabla}</td><td colspan='2' style='color:red'>Error: " . $e->getMessage() . "</td></tr>";
        }
    }
    
    echo "</table>";
    
    // Verificar un miembro específico
    echo "<h2>Verificar un miembro específico</h2>";
    echo "<form method='GET' action=''>";
    echo "<label>ID del miembro: <input type='number' name='id' value='1'></label>";
    echo "<button type='submit'>Verificar</button>";
    echo "</form>";
    
    if (isset($_GET['id'])) {
        $id = (int)$_GET['id'];
        $stmt = $db->prepare("SELECT * FROM informaciongeneral WHERE id = ?");
        $stmt->execute([$id]);
        $miembro = $stmt->fetch();
        
        if ($miembro) {
            echo "<h3>Datos del miembro ID: {$id}</h3>";
            echo "<pre>" . print_r($miembro, true) . "</pre>";
            
            // Verificar relaciones
            echo "<h3>Relaciones</h3>";
            echo "<ul>";
            
            foreach ($tablas as $tabla) {
                if ($tabla == 'informaciongeneral') continue;
                
                $stmt = $db->prepare("SELECT * FROM {$tabla} WHERE miembro_id = ?");
                $stmt->execute([$id]);
                $datos = $stmt->fetch();
                
                if ($datos) {
                    echo "<li><strong>{$tabla}:</strong> <span style='color:green'>✅ Datos encontrados</span></li>";
                } else {
                    echo "<li><strong>{$tabla}:</strong> <span style='color:orange'>⚠️ Sin datos</span></li>";
                }
            }
            
            echo "</ul>";
        } else {
            echo "<p style='color:red'>❌ No se encontró ningún miembro con ID {$id}</p>";
        }
    }
    
} catch (Exception $e) {
    echo "<p style='color:red'>❌ ERROR: " . $e->getMessage() . "</p>";
}
?>