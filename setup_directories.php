<?php
// filepath: c:\xampp\htdocs\ENCASA_DATABASE\setup_directories.php

// Definir directorio base
define('BASE_PATH', __DIR__);

// Directorios necesarios
$directories = [
    BASE_PATH . '/public',
    BASE_PATH . '/public/uploads',
    BASE_PATH . '/public/uploads/miembros',
];

// Crear directorios con permisos adecuados
foreach ($directories as $directory) {
    if (!file_exists($directory)) {
        if (mkdir($directory, 0777, true)) {
            echo "✅ Directorio creado: $directory\n";
        } else {
            echo "❌ Error al crear directorio: $directory\n";
        }
    } else {
        echo "📁 Directorio ya existe: $directory\n";
        
        // Verificar permisos
        if (!is_writable($directory)) {
            chmod($directory, 0777);
            echo "🔑 Permisos actualizados para: $directory\n";
        }
    }
}

echo "\n✅ Configuración de directorios completada.\n";