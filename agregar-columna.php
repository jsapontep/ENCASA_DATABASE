<?php
// Mostrar todos los errores
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h1>Agregar columna fecha_modificacion</h1>";

try {
    // Cargar la clase Database
    require_once 'app/config/Database.php';
    $db = Database::getInstance();
    $pdo = $db->getConnection();
    
    // Verificar si la columna ya existe
    $stmt = $pdo->query("SHOW COLUMNS FROM informaciongeneral LIKE 'fecha_modificacion'");
    $columnaExiste = $stmt->fetch();
    
    if (!$columnaExiste) {
        // Agregar la columna
        $pdo->exec("ALTER TABLE informaciongeneral ADD fecha_modificacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");
        echo "<p style='color:green'>✓ Columna 'fecha_modificacion' agregada correctamente</p>";
    } else {
        echo "<p>La columna 'fecha_modificacion' ya existe en la tabla.</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color:red'>Error: " . $e->getMessage() . "</p>";
}
?>