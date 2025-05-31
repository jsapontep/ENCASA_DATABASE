<?php
// Configuración general de la aplicación
if (!defined('APP_NAME'))    define('APP_NAME', 'Iglesia En Casa');
if (!defined('APP_URL'))     define('APP_URL', 'http://localhost/Encasa_Database');
if (!defined('APP_ENV'))     define('APP_ENV', 'development'); // 'development' o 'production'

// URL base y ruta base
if (!defined('BASE_URL'))    define('BASE_URL', '/ENCASA_DATABASE');
if (!defined('BASE_PATH'))   define('BASE_PATH', dirname(__DIR__, 2));

// Directorio de carga de archivos
if (!defined('UPLOAD_DIR'))  define('UPLOAD_DIR', __DIR__ . '/../../uploads');

// Configuración de zona horaria
date_default_timezone_set('America/Bogota');

// Configuración de correo electrónico con cuenta de Gmail para autenticación
if (!defined('SMTP_HOST'))   define('SMTP_HOST', 'smtp.gmail.com');
if (!defined('SMTP_PORT'))   define('SMTP_PORT', 587);
if (!defined('SMTP_SECURE')) define('SMTP_SECURE', 'tls');
if (!defined('SMTP_AUTH'))   define('SMTP_AUTH', true);
if (!defined('SMTP_USER'))   define('SMTP_USER', 'iglesiaencasautenticador@gmail.com');
if (!defined('SMTP_PASS'))   define('SMTP_PASS', 'uhko nczq nclq uzkx');
if (!defined('MAIL_FROM'))   define('MAIL_FROM', 'iglesiaencasautenticador@gmail.com');
if (!defined('MAIL_FROM_NAME')) define('MAIL_FROM_NAME', 'Iglesia En Casa');

// Controles para la verificación
if (!defined('REQUIRE_EMAIL_VERIFICATION')) define('REQUIRE_EMAIL_VERIFICATION', true);
if (!defined('REQUIRE_2FA_LOGIN'))          define('REQUIRE_2FA_LOGIN', true);

// Constantes de Base de Datos
if (!defined('DB_HOST'))     define('DB_HOST', 'localhost');
if (!defined('DB_NAME'))     define('DB_NAME', 'iglesiaencasa');
if (!defined('DB_USER'))     define('DB_USER', 'root');
if (!defined('DB_PASS'))     define('DB_PASS', '');

// Configuración del manejo de errores
ini_set('display_errors', 1);
error_reporting(E_ALL);
ini_set('error_log', __DIR__ . '/../../logs/php_error.log');

// Asegurarse de que existe el directorio de logs
if (!is_dir(__DIR__ . '/../../logs')) {
    mkdir(__DIR__ . '/../../logs', 0777, true);
}