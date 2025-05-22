<?php
// Configuración general de la aplicación
define('APP_NAME', 'Iglesia En Casa');
define('APP_URL', 'http://localhost/Encasa_Database');
define('APP_ENV', 'development'); // 'development' o 'production'

// Configuración de sesiones
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.use_strict_mode', 1);
if (APP_ENV === 'production') {
    ini_set('session.cookie_secure', 1);
}

// Configuración de zona horaria
date_default_timezone_set('America/Bogota');