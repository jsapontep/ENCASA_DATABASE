<?php

try {
    // Conectar sin especificar una base de datos
    $pdo = new PDO("mysql:host=localhost", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Crear la base de datos si no existe
    $pdo->exec("CREATE DATABASE IF NOT EXISTS IglesiaEnCasa CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    
    echo "✅ Base de datos 'IglesiaEnCasa' verificada/creada correctamente\n";
    
    // Seleccionar la base de datos
    $pdo->exec("USE IglesiaEnCasa");
    
    // Crear tabla de Roles si no existe (necesaria para el script permisos.php)
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS Roles (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nombre VARCHAR(50) NOT NULL UNIQUE,
            descripcion VARCHAR(255) NOT NULL,
            nivel_acceso INT NOT NULL DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )
    ");
    
    // Insertar rol de administrador si no existe
    $stmt = $pdo->prepare("INSERT IGNORE INTO Roles (id, nombre, descripcion, nivel_acceso) VALUES (1, 'Administrador', 'Control total del sistema', 100)");
    $stmt->execute();
    
    echo "✅ Tabla de Roles creada y rol de administrador verificado\n";
    
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}

echo "\n✅ Configuración de base de datos completada. Ahora puedes ejecutar otros scripts.\n";