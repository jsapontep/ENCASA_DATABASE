<?php
// filepath: /Applications/XAMPP/xamppfiles/htdocs/Encasa_Database/app/middleware/Auth.php
namespace App\Middleware;

class Auth extends Middleware {
    public function handle() {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['flash_message'] = 'Debes iniciar sesión para acceder';
            $_SESSION['flash_type'] = 'warning';
            
            // Guardar URL intentada para redirección después del login
            $_SESSION['intended_url'] = $_SERVER['REQUEST_URI'];
            
            header('Location: ' . APP_URL . '/login');
            exit;
        }
        
        return true;
    }
}