<?php
// filepath: c:\xampp\htdocs\ENCASA_DATABASE\app\Core\Session.php

class Session {
<<<<<<< HEAD
    /**
     * Inicializa la sesión y configura las medidas de seguridad
     */
    public static function start() {
=======
    public static function start() {
        // No volver a configurar la sesión si ya está activa
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }
        
>>>>>>> main
        // Configuración básica de seguridad
        ini_set('session.use_only_cookies', 1);
        ini_set('session.use_strict_mode', 1);
        ini_set('session.cookie_httponly', 1);
        
<<<<<<< HEAD
        // Configuración condicional para HTTPS
=======
        // Solo habilitar cookies seguras cuando estamos en HTTPS
>>>>>>> main
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
            ini_set('session.cookie_secure', 1);
        }
        
        // Nombre de sesión consistente
        session_name('ENCASASESSID');
        
        // Iniciar la sesión
<<<<<<< HEAD
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
=======
        session_start();
        
        // Generar token CSRF si no existe
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }
    
    // El resto de métodos...
}
>>>>>>> main
