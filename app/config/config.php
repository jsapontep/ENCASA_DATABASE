<?php
// Configuración general de la aplicación
if (!defined('APP_NAME')) define('APP_NAME', 'Iglesia En Casa');
if (!defined('APP_URL')) define('APP_URL', 'http://localhost/Encasa_Database');
if (!defined('APP_ENV')) define('APP_ENV', 'development'); // 'development' o 'production'

// URL base
define('BASE_URL', '/ENCASA_DATABASE');

// Directorio de carga de archivos
define('UPLOAD_DIR', __DIR__ . '/../../uploads');

// Configuración de zona horaria
date_default_timezone_set('America/Bogota');

// Configuración de correo electrónico con cuenta de Gmail para autenticación
define('SMTP_HOST', 'smtp.gmail.com');  // Servidor SMTP de Gmail
define('SMTP_PORT', 587);  // Puerto para TLS
define('SMTP_SECURE', 'tls');  // Tipo de seguridad
define('SMTP_AUTH', true);  // Requiere autenticación
define('SMTP_USER', 'iglesiaencasautenticador@gmail.com');  // Nueva cuenta de correo
define('SMTP_PASS', 'uhko nczq nclq uzkx');  // Nueva contraseña de aplicación
define('MAIL_FROM', 'iglesiaencasautenticador@gmail.com');  // Actualizado
define('MAIL_FROM_NAME', 'Iglesia En Casa');

// Controles para la verificación
define('REQUIRE_EMAIL_VERIFICATION', true);
define('REQUIRE_2FA_LOGIN', true);

// Constantes de Base de Datos
define('DB_HOST', 'localhost');
define('DB_NAME', 'IglesiaEnCasa');
define('DB_USER', 'root');
define('DB_PASS', '');

// Agregar esto al final del archivo para mejorar el registro de errores
ini_set('display_errors', 1);
error_reporting(E_ALL);
ini_set('error_log', __DIR__ . '/../../logs/php_error.log');

// Asegurarse de que existe el directorio de logs
if (!is_dir(__DIR__ . '/../../logs')) {
    mkdir(__DIR__ . '/../../logs', 0777, true);
}