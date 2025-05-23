<?php
// filepath: /Applications/XAMPP/xamppfiles/htdocs/Encasa_Database/app/middleware/AdminOnly.php
namespace App\Middleware;

class AdminOnly extends Middleware {
    public function handle() {
        if (!isset($_SESSION['roles']) || !in_array('Admin', $_SESSION['roles'])) {
            $_SESSION['flash_message'] = 'No tienes permiso para acceder a esta área';
            $_SESSION['flash_type'] = 'danger';
            
            header('Location: ' . APP_URL);
            exit;
        }
        
        return true;
    }
}