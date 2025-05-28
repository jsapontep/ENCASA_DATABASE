<?php

require_once __DIR__ . '/../app/config/database.php';

try {
    $db = Database::getInstance()->getConnection();
    
    // Crear tabla de Permisos
    $db->exec("
        CREATE TABLE IF NOT EXISTS Permisos (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nombre VARCHAR(50) NOT NULL UNIQUE,
            descripcion VARCHAR(255) NOT NULL,
            categoria VARCHAR(50) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )
    ");
    
    // Crear tabla pivot RolesPermisos
    $db->exec("
        CREATE TABLE IF NOT EXISTS RolesPermisos (
            rol_id INT,
            permiso_id INT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (rol_id, permiso_id),
            FOREIGN KEY (rol_id) REFERENCES Roles(id) ON DELETE CASCADE,
            FOREIGN KEY (permiso_id) REFERENCES Permisos(id) ON DELETE CASCADE
        )
    ");
    
    // Insertar permisos básicos
    $permisos = [
        // Miembros
        ['ver_miembros', 'Ver listado de miembros', 'Miembros'],
        ['crear_miembro', 'Crear nuevos miembros', 'Miembros'],
        ['editar_miembro', 'Editar información de miembros', 'Miembros'],
        ['eliminar_miembro', 'Eliminar miembros del sistema', 'Miembros'],
        
        // Ministerios
        ['ver_ministerios', 'Ver listado de ministerios', 'Ministerios'],
        ['crear_ministerio', 'Crear nuevos ministerios', 'Ministerios'],
        ['editar_ministerio', 'Editar información de ministerios', 'Ministerios'],
        ['eliminar_ministerio', 'Eliminar ministerios', 'Ministerios'],
        
        // Sistema
        ['gestionar_roles', 'Gestionar roles y permisos', 'Sistema'],
        ['ver_logs', 'Ver registros del sistema', 'Sistema'],
        ['configurar_sistema', 'Configurar parámetros del sistema', 'Sistema']
    ];
    
    $stmt = $db->prepare("INSERT IGNORE INTO Permisos (nombre, descripcion, categoria) VALUES (?, ?, ?)");
    
    foreach ($permisos as $permiso) {
        $stmt->execute($permiso);
    }
    
    // Asignar todos los permisos al rol Admin (rol_id = 1)
    $db->exec("
        INSERT IGNORE INTO RolesPermisos (rol_id, permiso_id)
        SELECT 1, id FROM Permisos
    ");
    
    echo "✅ Tablas de permisos creadas y datos iniciales insertados correctamente\n";
    
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}