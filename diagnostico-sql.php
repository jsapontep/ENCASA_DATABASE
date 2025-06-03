<?php

// Mostrar todos los errores
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h1>Diagnóstico de SQL</h1>";

try {
    // Cargar la clase Database para usar su configuración
    require_once 'app/config/Database.php';
    
    echo "<p>Intentando conectar a MySQL usando la clase Database...</p>";
    
    // Obtener la instancia y conexión de la clase Database
    $db = Database::getInstance();
    $pdo = $db->getConnection();
    
    echo "<p style='color:green'>✓ Conexión establecida correctamente</p>";
    
    // Mostrar la lista de todas las tablas disponibles
    echo "<h2>Tablas disponibles en la base de datos:</h2>";
    $stmt = $pdo->query("SHOW TABLES");
    $tablas = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "<ul>";
    foreach ($tablas as $tabla) {
        echo "<li>$tabla</li>";
    }
    echo "</ul>";
    
    // Verificar si existe la tabla 'informaciongeneral'
    echo "<h2>Verificando tabla informaciongeneral:</h2>";
    if (in_array('informaciongeneral', $tablas)) {
        // Primero ver la estructura de la tabla
        $stmt = $pdo->query("DESCRIBE informaciongeneral");
        $estructura = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "<p>Estructura de la tabla informaciongeneral:</p>";
        echo "<pre>" . print_r($estructura, true) . "</pre>";
        
        // Verificar total de registros
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM informaciongeneral");
        $totalRegistros = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        echo "<p>Total de registros en la tabla informaciongeneral: <strong>$totalRegistros</strong></p>";
        
        // Saber cuál es el campo de fecha en esta tabla
        $columnasFecha = [];
        foreach ($estructura as $columna) {
            if (strpos(strtolower($columna['Type']), 'date') !== false || 
                strpos(strtolower($columna['Field']), 'fecha') !== false) {
                $columnasFecha[] = $columna['Field'];
            }
        }
        
        if (!empty($columnasFecha)) {
            echo "<p>Columnas de fecha encontradas: " . implode(", ", $columnasFecha) . "</p>";
            
            // Usar la primera columna de fecha encontrada para ordenar
            $columnaFechaOrdenacion = $columnasFecha[0];
            $stmt = $pdo->query("SELECT id, nombres, apellidos, $columnaFechaOrdenacion FROM informaciongeneral ORDER BY $columnaFechaOrdenacion DESC LIMIT 1");
            $ultimoRegistro = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($ultimoRegistro) {
                echo "<p>Último registro modificado (ordenado por $columnaFechaOrdenacion):</p>";
                echo "<pre>" . print_r($ultimoRegistro, true) . "</pre>";
            }
        } else {
            // Sin columnas de fecha, mostrar un registro cualquiera
            echo "<p>No se encontraron columnas de fecha en la tabla.</p>";
            $stmt = $pdo->query("SELECT * FROM informaciongeneral LIMIT 1");
            $registro = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($registro) {
                echo "<p>Ejemplo de un registro de la tabla:</p>";
                echo "<pre>" . print_r($registro, true) . "</pre>";
            }
        }
    } else {
        echo "<p style='color:orange'>⚠️ La tabla 'informaciongeneral' no existe en la base de datos</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color:red'>Error: " . $e->getMessage() . "</p>";
}
?>