<?php

namespace App\Middleware;

class AuthMiddleware {
    public function handle() {
        // Iniciar sesión si no está iniciada
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Verificar si el usuario está autenticado
        if (!isset($_SESSION['user_id'])) {
            // Guardar la URL solicitada para redirigir después del login
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
            
            // Mensaje de error
            $_SESSION['flash_message'] = 'Debes iniciar sesión para acceder a esta página';
            $_SESSION['flash_type'] = 'warning';
            
            // Redirigir al login
            header('Location: ' . APP_URL . '/login');
            exit;
        }
        
        return true;
    }
}