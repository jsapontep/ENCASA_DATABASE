<?php
// filepath: /Applications/XAMPP/xamppfiles/htdocs/Encasa_Database/app/config/routes.php

use App\Helpers\Router;

// Instancia del router
$router = new Router();

// Página principal
$router->get('', 'Home', 'index');
$router->get('home', 'Home', 'index');

// Rutas de miembros
$router->get('miembros', 'Miembros', 'index', ['Auth']);
$router->get('miembros/crear', 'Miembros', 'crear', ['Auth']);
$router->post('miembros/guardar', 'Miembros', 'guardar', ['Auth']);
$router->get('miembros/{id}', 'Miembros', 'ver', ['Auth']);
$router->get('miembros/editar/{id}', 'Miembros', 'editar', ['Auth']);
$router->post('miembros/actualizar/{id}', 'Miembros', 'actualizar', ['Auth']);

// Rutas de ministerios
$router->get('ministerios', 'Ministerios', 'index', ['Auth']);
$router->get('ministerios/crear', 'Ministerios', 'crear', ['Auth']);
$router->post('ministerios/guardar', 'Ministerios', 'guardar', ['Auth']);
$router->get('ministerios/{id}', 'Ministerios', 'ver', ['Auth']);
$router->get('ministerios/{id}/editar', 'Ministerios', 'editar', ['Auth']);
$router->post('ministerios/{id}/actualizar', 'Ministerios', 'actualizar', ['Auth']);
$router->post('ministerios/{id}/eliminar', 'Ministerios', 'eliminar', ['Auth', 'AdminOnly']);

// Rutas de autenticación
$router->get('login', 'Auth', 'login');
$router->post('auth/login', 'Auth', 'authenticate');
$router->get('logout', 'Auth', 'logout');
$router->get('registro', 'Auth', 'register');
$router->post('auth/registro', 'Auth', 'store');

// Rutas de verificación
$router->get('auth/verify', 'Auth', 'verify');
$router->post('auth/verify', 'Auth', 'verify');
$router->get('auth/resendCode', 'Auth', 'resendCode');

// Rutas de verificación 2FA para login
$router->get('auth/verify-login', 'Auth', 'verifyLogin');
$router->post('auth/verify-login', 'Auth', 'verifyLogin');
$router->get('auth/resend-login-code', 'Auth', 'resendLoginCode');

// Rutas de error
$router->setNotFound(function() {
    include VIEW_PATH . '/errors/404.php';
});

return $router;