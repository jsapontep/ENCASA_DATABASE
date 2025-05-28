<?php
// database/update_usuarios.php
require_once __DIR__ . '/../app/config/database.php';

try {
    $db = Database::getInstance()->getConnection();
    
    // Añadir campos necesarios para JWT y reset de contraseña
    $db->exec("
        ALTER TABLE Usuarios 
        ADD COLUMN remember_token VARCHAR(255) NULL,
        ADD COLUMN token_reset VARCHAR(255) NULL,
        ADD COLUMN token_expira DATETIME NULL
    ");
    
    echo "✅ Campos para JWT y reset de contraseña añadidos a la tabla Usuarios\n";
    
} catch (PDOException $e) {
    if (strpos($e->getMessage(), "Duplicate column name") !== false) {
        echo "ℹ️ Las columnas ya existen en la tabla Usuarios\n";
    } else {
        echo "❌ Error: " . $e->getMessage() . "\n";
    }
}