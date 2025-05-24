<?php

namespace App\Middleware;

use App\Models\Usuario;
use App\Models\Rol;

class RoleMiddleware {
    private $requiredLevel;
    
    public function __construct($level) {
        $this->requiredLevel = $level;
    }
    
    public function handle() {
        // Verificar que el usuario está autenticado
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . APP_URL . '/login');
            exit;
        }
        
        // Obtener información del rol del usuario
        $userModel = new Usuario();
        $user = $userModel->findById($_SESSION['user_id']);
        
        if (!$user) {
            $_SESSION['flash_message'] = 'Sesión inválida';
            $_SESSION['flash_type'] = 'danger';
            header('Location: ' . APP_URL . '/logout');
            exit;
        }
        
        // Cargar el rol
        $rolModel = new Rol();
        $rol = $rolModel->findById($user['rol_id']);
        
        // Verificar si el nivel de acceso es suficiente
        if (!$rol || $rol['nivel_acceso'] < $this->requiredLevel) {
            // Redirect a página de acceso denegado
            header('Location: ' . APP_URL . '/acceso-denegado');
            exit;
        }
        
        return true;
    }
}