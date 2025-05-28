<?php
namespace App\Controllers;

abstract class Controller {
    protected $view;
    protected $model;
    
    public function __construct() {
        $this->view = new \App\Helpers\View();
    }
    
    /**
     * Método para renderizar una vista
     */
    protected function render($view, $data = []) {
        $this->view->render($view, $data);
    }
    
    /**
     * Método para renderizar una vista con un layout
     */
    protected function renderWithLayout($view, $layout = 'default', $data = []) {
        $this->view->renderWithLayout($view, $layout, $data);
    }
    
    /**
     * Método para validar datos de formulario
     */
    protected function validate($data, $rules) {
        $validator = new \App\Helpers\Validator();
        return $validator->validate($data, $rules);
    }
    
    /**
     * Redirecciona a otra página con un mensaje opcional
     */
    protected function redirect($url, $message = null, $type = 'info') {
        if ($message) {
            $_SESSION['flash_message'] = $message;
            $_SESSION['flash_type'] = $type;
        }
        
        // Regenerar token CSRF después de redirecciones importantes
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        
        // Si la URL no comienza con http(s), construirla con la URL base
        if (strpos($url, 'http') !== 0) {
            $url = url(ltrim($url, '/'));
        }
        
        header('Location: ' . $url);
        exit;
    }
    
    /**
     * Método para obtener el usuario current
     */
    protected function getCurrentUser() {
        if (isset($_SESSION['user_id'])) {
            $userModel = new \App\Models\Usuario();
            return $userModel->findById($_SESSION['user_id']);
        }
        return null;
    }
    
    /**
     * Método para verificar si el usuario está autenticado
     */
    protected function isAuthenticated() {
        return isset($_SESSION['user_id']);
    }
}