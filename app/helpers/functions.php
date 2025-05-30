<?php
/**
 * Funciones auxiliares globales para logging y generación de URLs
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
<<<<<<< HEAD
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
=======
 * Genera una URL completa para la aplicación
 * @param string $path Ruta relativa
 * @return string URL completa
 */
if (!function_exists('url')) {
    function url($path = '', $force_https = true)
    {
        // Usar APP_URL si está definida
        if (defined('APP_URL')) {
            $baseUrl = APP_URL;
            
            // Si es un túnel y queremos forzar HTTPS, asegurarnos que use https://
            if ($force_https && defined('APP_ENV') && APP_ENV === 'tunnel') {
                $baseUrl = str_replace('http://', 'https://', $baseUrl);
            }
        } else {
            // Si no está definida, detectar el entorno
            $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
            $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
            
            // Detectar si estamos usando un túnel
            $is_tunnel = strpos($host, 'ngrok-free.app') !== false || 
                       strpos($host, 'ngrok.io') !== false ||
                       strpos($host, 'localto.net') !== false || 
                       strpos($host, 'loca.lt') !== false;
            
            if ($is_tunnel) {
                // Forzar HTTPS para túneles
                $baseUrl = ($force_https ? 'https://' : $protocol) . $host . '/ENCASA_DATABASE';
            } else {
                $baseUrl = 'http://localhost/ENCASA_DATABASE';
            }
        }
        
        // Formatear URL correctamente
        $baseUrl = rtrim($baseUrl, '/');
        $path = ltrim($path, '/');
        
        return $baseUrl . ($path ? '/' . $path : '');
    }
}

/**
 * Genera una URL para recursos estáticos (CSS, JS, imágenes)
 * @param string $path Ruta relativa al archivo dentro de la carpeta /public
 * @return string URL completa al recurso
 */
if (!function_exists('asset')) {
    function asset($path = '')
    {
        return url('public/' . ltrim($path, '/'));
>>>>>>> main
    }
}