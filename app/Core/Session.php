<?php
// filepath: c:\xampp\htdocs\ENCASA_DATABASE\app\Core\Session.php

class Session {
    /**
     * Inicializa la sesión y configura las medidas de seguridad
     */
    public static function start() {
        // Configuración básica de seguridad
        ini_set('session.use_only_cookies', 1);
        ini_set('session.use_strict_mode', 1);
        ini_set('session.cookie_httponly', 1);
        
        // Configuración condicional para HTTPS
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
            ini_set('session.cookie_secure', 1);
        }
        
        // Nombre de sesión consistente
        session_name('ENCASASESSID');
        
        // Iniciar la sesión
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Generar token CSRF para protección
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        
        // Resto del código...
    }
}