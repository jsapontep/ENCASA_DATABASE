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
     * Método para redireccionar
     */
    protected function redirect($url) {
        \App\Helpers\Router::redirect($url);
    }
    
    /**
     * Método para obtener el usuario actual
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