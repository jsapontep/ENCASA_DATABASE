<?php

// Configuración básica
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h1>Prueba de conexión a la base de datos</h1>";

// 1. Verificar conexión PDO directa
try {
    echo "<h2>Prueba 1: Conexión PDO directa</h2>";
    $pdo = new PDO("mysql:host=localhost;dbname=IglesiaEnCasa;charset=utf8", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<p style='color:green'>✅ Conexión PDO directa exitosa</p>";
    
    // Verificar tablas
    $tablas = ['InformacionGeneral', 'Contacto', 'EstudiosTrabajo', 'Tallas', 'SaludEmergencias', 'CarreraBiblica'];
    echo "<h3>Verificación de tablas:</h3><ul>";
    
    foreach ($tablas as $tabla) {
        $stmt = $pdo->query("SHOW TABLES LIKE '$tabla'");
        if ($stmt->rowCount() > 0) {
            echo "<li>✅ $tabla: <span style='color:green'>Existe</span></li>";
        } else {
            echo "<li>❌ $tabla: <span style='color:red'>No existe</span></li>";
        }
    }
    echo "</ul>";
    
} catch (PDOException $e) {
    echo "<p style='color:red'>❌ Error en conexión PDO directa: " . $e->getMessage() . "</p>";
}

// 2. Verificar clase Database
try {
    echo "<h2>Prueba 2: Clase Database</h2>";
    
    // Incluir archivo
    $configFile = __DIR__ . '/app/config/database.php';
    if (file_exists($configFile)) {
        require_once $configFile;
        echo "<p>✅ Archivo de configuración encontrado</p>";
        
        if (class_exists('Database')) {
            echo "<p>✅ Clase Database definida</p>";
            
            $db = \Database::getInstance()->getConnection();
            echo "<p style='color:green'>✅ Conexión a través de Database::getInstance() exitosa</p>";
        } else {
            echo "<p style='color:red'>❌ Clase Database NO definida</p>";
        }
    } else {
        echo "<p style='color:red'>❌ Archivo de configuración NO encontrado</p>";
    }
} catch (Exception $e) {
    echo "<p style='color:red'>❌ Error en clase Database: " . $e->getMessage() . "</p>";
}
?>