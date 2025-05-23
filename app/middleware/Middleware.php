<?php
// filepath: /Applications/XAMPP/xamppfiles/htdocs/Encasa_Database/app/middleware/Middleware.php
namespace App\Middleware;

abstract class Middleware {
    /**
     * Método que se debe implementar en cada middleware
     */
    abstract public function handle();
}