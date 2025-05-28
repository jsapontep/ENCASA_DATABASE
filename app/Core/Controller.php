<?php
// filepath: c:\xampp\htdocs\ENCASA_DATABASE\app\Core\Controller.php

class Controller {
    // En el método redirectWithMessage o similar:
    protected function redirect($url, $message = null, $type = 'info') {
        if ($message) {
            $_SESSION['flash_message'] = $message;
            $_SESSION['flash_type'] = $type;
        }
        
        // Regenerar token CSRF después de redirecciones importantes
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        
        header('Location: ' . APP_URL . '/' . $url);
        exit;
    }
}