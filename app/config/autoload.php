<?php
// Autoload básico para cargar clases
spl_autoload_register(function ($className) {
    $className = str_replace('App\\', '', $className);
    $className = str_replace('\\', '/', $className);
    $file = APP_PATH . '/' . $className . '.php';
    
    if (file_exists($file)) {
        require_once $file;
        return true;
    }
    return false;
});