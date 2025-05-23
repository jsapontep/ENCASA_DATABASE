<?php
// Al inicio del archivo index.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Configurar visualización de errores para desarrollo
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Definir constantes del sistema
define('BASE_PATH', __DIR__);
define('APP_PATH', BASE_PATH . '/app');
define('CONTROLLER_PATH', APP_PATH . '/controllers');
define('MODEL_PATH', APP_PATH . '/models');
define('VIEW_PATH', APP_PATH . '/views');
define('CONFIG_PATH', APP_PATH . '/config');

// Cargar configuración
require_once CONFIG_PATH . '/config.php';
require_once CONFIG_PATH . '/database.php';
require_once CONFIG_PATH . '/autoload.php';

// Configuración de sesiones
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.use_strict_mode', 1);
if (APP_ENV === 'production') {
    ini_set('session.cookie_secure', 1);
}

// Iniciar sesión
session_start();

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