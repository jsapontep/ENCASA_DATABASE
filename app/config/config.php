<?php
// Configuración general de la aplicación
define('APP_NAME', 'Iglesia En Casa');
define('APP_URL', 'http://localhost/Encasa_Database');
define('APP_ENV', 'development'); // 'development' o 'production'

// Configuración de zona horaria
date_default_timezone_set('America/Bogota');

// Configuración de correo electrónico
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'rafa.gzfr@gmail.com'); // Cambia esto por tu email real
define('SMTP_PASS', 'gvxo crnm bmeh rail'); // Usa contraseña de aplicación para Gmail
define('MAIL_FROM', 'noreply@iglesiaencasa.org');
define('MAIL_FROM_NAME', 'Iglesia En Casa');

// Controles para la verificación
define('REQUIRE_EMAIL_VERIFICATION', true); // Cambiar a true cuando estés listo para activar
define('REQUIRE_2FA_LOGIN', true);

// Constantes de Base de Datos
define('DB_HOST', 'localhost');
define('DB_NAME', 'IglesiaEnCasa');
define('DB_USER', 'root');
define('DB_PASS', '');