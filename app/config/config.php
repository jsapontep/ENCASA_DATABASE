<?php
// Configuración general de la aplicación
define('APP_NAME', 'Iglesia En Casa');

// Reemplazar la definición actual de APP_URL con una detección automática
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'];

// Detectar si estamos en el túnel o en localhost
if (strpos($host, 'localto.net') !== false) {
    // Estamos en el túnel
    define('APP_URL', $protocol . $host . '/encasa_database');
    define('APP_ENV', 'tunnel');
} else {
    // Estamos en localhost normal
    define('APP_URL', $protocol . $host . '/ENCASA_DATABASE');
    define('APP_ENV', 'local');
}

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