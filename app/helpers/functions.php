<?php
/**
 * Funciones auxiliares globales para logging
 */

if (!function_exists('log_error')) {
    function log_error($message, $context = []) {
        \App\Helpers\Logger::getInstance()->error($message, $context);
    }
}

if (!function_exists('log_warning')) {
    function log_warning($message, $context = []) {
        \App\Helpers\Logger::getInstance()->warning($message, $context);
    }
}

if (!function_exists('log_info')) {
    function log_info($message, $context = []) {
        \App\Helpers\Logger::getInstance()->info($message, $context);
    }
}

if (!function_exists('log_debug')) {
    function log_debug($message, $context = []) {
        \App\Helpers\Logger::getInstance()->debug($message, $context);
    }
}

/**
 * Obtiene o genera un token CSRF
 */
function csrf_token() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Genera un campo HTML con el token CSRF
 */
function csrf_field() {
    return '<input type="hidden" name="csrf_token" value="' . csrf_token() . '">';
}

/**
 * Genera una URL completa basada en la URL base de la aplicación
 *
 * @param string $path Ruta relativa (sin slash inicial)
 * @return string URL completa
 */
function url($path = '') {
    if (!function_exists('url')) {
        $base = APP_URL;
        // Eliminar slash final si existe
        $base = rtrim($base, '/');
        
        // Agregar slash inicial al path si no existe y el path no está vacío
        if ($path && substr($path, 0, 1) !== '/') {
            $path = '/' . $path;
        }
        
        return $base . $path;
    }
}