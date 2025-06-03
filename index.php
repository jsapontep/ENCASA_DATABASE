<?php
// Al inicio del archivo index.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Captura todos los errores de PHP para asegurar respuestas JSON correctas
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    // Si es una solicitud AJAX, devolver JSON
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => "Error PHP: $errstr en $errfile:$errline"
        ]);
        exit;
    }
    // De lo contrario, usar el manejador de errores predeterminado
    return false;
});

// Definir constantes del sistema
define('BASE_PATH', __DIR__);
define('APP_PATH', BASE_PATH . '/app');
define('CONTROLLER_PATH', APP_PATH . '/controllers');
define('MODEL_PATH', APP_PATH . '/models');
define('VIEW_PATH', APP_PATH . '/views');
define('CONFIG_PATH', APP_PATH . '/config');

// IMPORTANTE: Detectar ngrok ANTES de cargar config.php
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';

// Detectar si estamos usando ngrok u otro túnel
$is_tunnel = strpos($host, 'ngrok-free.app') !== false || 
             strpos($host, 'ngrok.io') !== false ||
             strpos($host, 'localto.net') !== false || 
             strpos($host, 'loca.lt') !== false;

// Configurar APP_URL según el entorno
if ($is_tunnel) {
    define('APP_URL', $protocol . $host . '/ENCASA_DATABASE');
    define('APP_ENV', 'tunnel');
} else {
    define('APP_URL', 'http://localhost/ENCASA_DATABASE');
    define('APP_ENV', 'development');
}

// IMPORTANTE: Configurar sesiones ANTES de iniciarla
// Configuración de sesiones
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.use_strict_mode', 1);
ini_set('session.cookie_samesite', 'Lax');

// NO forzar cookies seguras para ngrok - esto causa redirecciones infinitas
if (APP_ENV === 'production') {
    ini_set('session.cookie_secure', 1);
} else {
    ini_set('session.cookie_secure', 0);
}

// Ahora iniciar sesión DESPUÉS de configurarla
session_start();

// Cargar helpers antes de configuración
if (file_exists(APP_PATH . '/helpers/functions.php')) {
    require_once APP_PATH . '/helpers/functions.php';
}

// Cargar configuración
require_once CONFIG_PATH . '/config.php';
require_once CONFIG_PATH . '/database.php';
require_once CONFIG_PATH . '/autoload.php';
require_once CONFIG_PATH . '/mail_autoload.php';

// Manejador de excepciones no capturadas
set_exception_handler(function($exception) {
    // Si es una solicitud AJAX, devolver JSON
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => 'Error en el servidor: ' . $exception->getMessage()
        ]);
        exit;
    }
    // De lo contrario, mostrar página de error
    echo '<pre>';
    echo 'Error: ' . $exception->getMessage();
    echo '<br>En archivo: ' . $exception->getFile() . ' línea: ' . $exception->getLine();
    echo '</pre>';
    die();
});

// Cargar y ejecutar el router
$router = require_once CONFIG_PATH . '/routes.php';
try {
    $router->dispatch();
} catch (Exception $e) {
    echo '<pre>';
    echo 'Error: ' . $e->getMessage();
    echo '<br>En archivo: ' . $e->getFile() . ' línea: ' . $e->getLine();
    echo '<br>Stack trace:<br>';
    echo $e->getTraceAsString();
    echo '</pre>';
    die();
}