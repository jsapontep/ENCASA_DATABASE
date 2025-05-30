<?php
// filepath: c:\xampp\htdocs\ENCASA_DATABASE\app\Core\Session.php

class Session {
    public static function start() {
        // No volver a configurar la sesión si ya está activa
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }
        
        // Configuración básica de seguridad
        ini_set('session.use_only_cookies', 1);
        ini_set('session.use_strict_mode', 1);
        ini_set('session.cookie_httponly', 1);
        
        // Solo habilitar cookies seguras cuando estamos en HTTPS
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
            ini_set('session.cookie_secure', 1);
        }
        
        // Nombre de sesión consistente
        session_name('ENCASASESSID');
        
        // Iniciar la sesión
        session_start();
        
        // Generar token CSRF si no existe
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }
    
    // El resto de métodos...
}
