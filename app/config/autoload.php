<?php
// Autoload mejorado para manejar problemas de mayúsculas/minúsculas en directorios
spl_autoload_register(function ($className) {
    // Normalizar separadores y quitar prefijo de namespace
    $className = str_replace('App\\', '', $className);
    $path = str_replace('\\', '/', $className);
    
    // Primera opción: ruta exacta
    $file = APP_PATH . '/' . $path . '.php';
    if (file_exists($file)) {
        require_once $file;
        return true;
    }
    
    // Segunda opción: directorio en minúsculas, nombre de archivo normal
    $dir = dirname($path);
    $filename = basename($path);
    $file = APP_PATH . '/' . strtolower($dir) . '/' . $filename . '.php';
    if (file_exists($file)) {
        require_once $file;
        return true;
    }
    
    // Tercera opción: comprobar existencia de Controllers vs controllers
    $file = APP_PATH . '/Controllers/' . $filename . '.php';
    if (file_exists($file)) {
        require_once $file;
        return true;
    }
    
    // Cuarta opción: comprobar existencia de controllers vs Controllers
    $file = APP_PATH . '/controllers/' . $filename . '.php';
    if (file_exists($file)) {
        require_once $file;
        return true;
    }
    
    return false;
});