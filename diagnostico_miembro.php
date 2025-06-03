<?php


// Mostrar todos los errores
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Establecer tiempo máximo de ejecución más largo para análisis completo
set_time_limit(300);

// Incluir archivos necesarios
require_once 'app/config/config.php';
require_once 'app/config/Database.php';

// Estilos para una mejor visualización
echo '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diagnóstico de Miembros</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 1200px; margin: 0 auto; padding: 20px; line-height: 1.6; }
        h1, h2, h3 { color: #333; }
        pre { background: #f5f5f5; padding: 15px; border-radius: 5px; overflow: auto; }
        .success { color: green; font-weight: bold; }
        .warning { color: orange; font-weight: bold; }
        .error { color: red; font-weight: bold; }
        .query { background: #e8f4ff; padding: 10px; border-left: 4px solid #0066cc; margin: 10px 0; }
        table { border-collapse: collapse; width: 100%; margin: 15px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .test-block { background: #f9f9f9; padding: 15px; margin: 20px 0; border-radius: 5px; border: 1px solid #ddd; }
        .btn { display: inline-block; padding: 8px 15px; background: #0066cc; color: white; text-decoration: none; border-radius: 4px; }
        .result-block { margin-top: 10px; padding: 10px; border-radius: 5px; }
        .result-success { background: #d4edda; border: 1px solid #c3e6cb; }
        .result-error { background: #f8d7da; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>
    <h1>Diagnóstico para Actualización de Miembros</h1>';

try {
    // Obtener la instancia y conexión de la base de datos
    $db = Database::getInstance();
    $pdo = $db->getConnection();
    echo '<p class="success">✓ Conexión a la base de datos establecida correctamente</p>';
    
    // Obtener ID del miembro desde parámetro GET o usar un valor predeterminado
    $miembro_id = isset($_GET['id']) ? (int)$_GET['id'] : null;
    
    // Si no se proporciona ID, mostrar listado de miembros disponibles
    if (!$miembro_id) {
        echo '<h2>Miembros disponibles</h2>';
        $stmt = $pdo->query("SELECT id, nombres, apellidos, fecha_registro_sistema, fecha_modificacion FROM informaciongeneral ORDER BY id");
        $miembros = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo '<table>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Fecha registro</th>
                    <th>Última modificación</th>
                    <th>Acción</th>
                </tr>';
        
        foreach ($miembros as $miembro) {
            echo '<tr>
                    <td>'.$miembro['id'].'</td>
                    <td>'.$miembro['nombres'].'</td>
                    <td>'.$miembro['apellidos'].'</td>
                    <td>'.$miembro['fecha_registro_sistema'].'</td>
                    <td>'.$miembro['fecha_modificacion'].'</td>
                    <td><a href="?id='.$miembro['id'].'" class="btn">Diagnosticar</a></td>
                </tr>';
        }
        
        echo '</table>';
        echo '<p>Seleccione un miembro para realizar el diagnóstico de actualización.</p>';
        exit;
    }
    
    // Si hay un ID, proceder con el diagnóstico
    echo "<h2>Diagnóstico para miembro ID: $miembro_id</h2>";
    
    // Verificar si el miembro existe
    $stmt = $pdo->prepare("SELECT * FROM informaciongeneral WHERE id = ?");
    $stmt->execute([$miembro_id]);
    $miembro = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$miembro) {
        echo "<p class='error'>❌ El miembro con ID $miembro_id no existe en la base de datos.</p>";
        echo '<p><a href="diagnostico_miembro.php">Volver a la lista de miembros</a></p>';
        exit;
    }
    
    echo "<h3>Datos actuales del miembro:</h3>";
    echo "<pre>" . print_r($miembro, true) . "</pre>";
    
    // Verificar tablas relacionadas
    $tablas_relacionadas = [
        'contacto' => 'Contacto',
        'estudiostrabajo' => 'Estudios y Trabajo',
        'tallas' => 'Tallas',
        'saludemergencias' => 'Salud y Emergencias',
        'carrerabiblica' => 'Carrera Bíblica'
    ];
    
    echo "<h3>Datos en tablas relacionadas:</h3>";
    
    foreach ($tablas_relacionadas as $tabla => $nombre) {
        $stmt = $pdo->prepare("SELECT * FROM $tabla WHERE miembro_id = ?");
        $stmt->execute([$miembro_id]);
        $datos = $stmt->fetch(PDO::FETCH_ASSOC);
        
        echo "<h4>$nombre:</h4>";
        if ($datos) {
            echo "<pre>" . print_r($datos, true) . "</pre>";
        } else {
            echo "<p class='warning'>⚠️ No hay datos para este miembro en la tabla $tabla</p>";
        }
    }
    
    // Sección de pruebas de actualización
    echo '<h2>Prueba de actualización manual</h2>';
    
    // Si se envía el formulario de prueba
    if (isset($_POST['test_update']) && $_POST['test_update'] == 'true') {
        echo '<div class="test-block">';
        echo '<h3>Ejecutando prueba de actualización...</h3>';
        
        // Obtener valores enviados
        $campo = $_POST['campo'];
        $valor = $_POST['valor'];
        $tabla = $_POST['tabla'];
        
        // Ejecutar actualización según la tabla
        try {
            if ($tabla == 'informaciongeneral') {
                // Actualizar la tabla principal
                $sql = "UPDATE informaciongeneral SET $campo = ? WHERE id = ?";
                $stmt = $pdo->prepare($sql);
                echo "<p class='query'>Consulta SQL: UPDATE informaciongeneral SET $campo = '$valor' WHERE id = $miembro_id</p>";
                $stmt->execute([$valor, $miembro_id]);
            } else {
                // Actualizar tabla relacionada
                $sql = "UPDATE $tabla SET $campo = ? WHERE miembro_id = ?";
                $stmt = $pdo->prepare($sql);
                echo "<p class='query'>Consulta SQL: UPDATE $tabla SET $campo = '$valor' WHERE miembro_id = $miembro_id</p>";
                $stmt->execute([$valor, $miembro_id]);
            }
            
            echo "<p>Filas afectadas: " . $stmt->rowCount() . "</p>";
            
            if ($stmt->rowCount() > 0) {
                echo "<p class='success'>✓ Actualización ejecutada correctamente.</p>";
            } else {
                echo "<p class='warning'>⚠️ La consulta se ejecutó sin errores, pero ninguna fila fue modificada. Razones posibles:</p>";
                echo "<ul>";
                echo "<li>El valor actual ya es igual al nuevo valor.</li>";
                echo "<li>La columna no existe en la tabla.</li>";
                echo "<li>No hay registro para este miembro en la tabla relacionada.</li>";
                echo "</ul>";
            }
            
            // Verificar el valor actual después de la actualización
            if ($tabla == 'informaciongeneral') {
                $sql = "SELECT $campo FROM informaciongeneral WHERE id = ?";
            } else {
                $sql = "SELECT $campo FROM $tabla WHERE miembro_id = ?";
            }
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$miembro_id]);
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            
            echo "<div class='result-block " . ($resultado ? "result-success" : "result-error") . "'>";
            echo "<h4>Valor después de la actualización:</h4>";
            
            if ($resultado) {
                echo "<p>Nuevo valor de '$campo': <strong>" . $resultado[$campo] . "</strong></p>";
                
                if ($resultado[$campo] == $valor) {
                    echo "<p class='success'>✓ El valor se actualizó correctamente.</p>";
                } else {
                    echo "<p class='error'>❌ El valor no coincide con el valor esperado.</p>";
                }
            } else {
                echo "<p class='error'>❌ No se pudo obtener el valor actual.</p>";
            }
            echo "</div>";
            
        } catch (PDOException $e) {
            echo "<p class='error'>❌ Error en la actualización: " . $e->getMessage() . "</p>";
        }
        
        echo '</div>';
    }
    
    // Formulario para probar actualización manual
    echo '<div class="test-block">';
    echo '<h3>Ejecutar actualización de prueba</h3>';
    echo '<form method="POST">';
    echo '<input type="hidden" name="test_update" value="true">';
    
    echo '<p><label for="tabla">Selecciona la tabla:</label><br>';
    echo '<select name="tabla" id="tabla" required>';
    echo '<option value="informaciongeneral">informaciongeneral (Principal)</option>';
    foreach ($tablas_relacionadas as $tabla => $nombre) {
        echo "<option value=\"$tabla\">$tabla</option>";
    }
    echo '</select></p>';
    
    echo '<p><label for="campo">Nombre del campo a actualizar:</label><br>';
    echo '<input type="text" name="campo" id="campo" required placeholder="Ejemplo: nombres"></p>';
    
    echo '<p><label for="valor">Nuevo valor:</label><br>';
    echo '<input type="text" name="valor" id="valor" required placeholder="Ejemplo: Juan"></p>';
    
    echo '<p><button type="submit" class="btn">Ejecutar Actualización</button></p>';
    echo '</form>';
    echo '</div>';
    
    // Verificar permisos y configuración
    echo '<h2>Verificación de permisos y configuración</h2>';
    
    // Comprobar permisos de escritura en la base de datos
    echo '<h3>Permisos de base de datos:</h3>';
    try {
        // Intentar crear y eliminar una tabla temporal para comprobar permisos
        $pdo->exec("CREATE TABLE IF NOT EXISTS test_permisos (id INT)");
        echo "<p class='success'>✓ Permisos de creación OK</p>";
        $pdo->exec("DROP TABLE IF EXISTS test_permisos");
        echo "<p class='success'>✓ Permisos de eliminación OK</p>";
    } catch (PDOException $e) {
        echo "<p class='error'>❌ Error en permisos de base de datos: " . $e->getMessage() . "</p>";
    }
    
    // Verificar configuración de PDO
    echo '<h3>Configuración de PDO:</h3>';
    $attributes = [
        PDO::ATTR_ERRMODE => 'Modo de error',
        PDO::ATTR_EMULATE_PREPARES => 'Emulación de preparadas',
        PDO::ATTR_DEFAULT_FETCH_MODE => 'Modo de fetch por defecto'
    ];
    
    foreach ($attributes as $attr => $name) {
        try {
            echo "<p>$name: <strong>" . $pdo->getAttribute($attr) . "</strong></p>";
        } catch (Exception $e) {
            echo "<p>$name: No disponible</p>";
        }
    }
    
    // Instrucciones para solucionar problemas comunes
    echo '<h2>Soluciones a problemas comunes</h2>';
    echo '<ol>';
    echo '<li><strong>Si no actualiza ningún campo:</strong> Verifica que el controlador use el nombre correcto de la tabla (informaciongeneral) y que los nombres de campo coincidan exactamente.</li>';
    echo '<li><strong>Si actualiza algunos campos pero no otros:</strong> Asegúrate de que todos los campos están incluidos en la consulta SQL de actualización.</li>';
    echo '<li><strong>Si las tablas relacionadas no se actualizan:</strong> Verifica que la relación está correctamente establecida con miembro_id y que los registros existen.</li>';
    echo '<li><strong>Si las fechas no se actualizan:</strong> Revisa el formato de fecha que estás enviando, debe ser compatible con MySQL (YYYY-MM-DD).</li>';
    echo '</ol>';
    
    echo '<p><a href="diagnostico_miembro.php" class="btn">Volver a la lista de miembros</a></p>';
    
} catch (Exception $e) {
    echo "<p class='error'>Error general: " . $e->getMessage() . "</p>";
    echo "<p>Traza de error: <pre>" . $e->getTraceAsString() . "</pre></p>";
}

echo '</body></html>';
?>