<?php
// filepath: c:\xampp\htdocs\ENCASA_DATABASE\direct_access.php

// Este archivo permite acceder directamente a la aplicación sin redirecciones
// Configuración básica sin redirecciones

// Definir constantes del sistema
define('BASE_PATH', __DIR__);
define('APP_PATH', BASE_PATH . '/app');
define('CONTROLLER_PATH', APP_PATH . '/controllers');
define('MODEL_PATH', APP_PATH . '/models');
define('VIEW_PATH', APP_PATH . '/views');
define('CONFIG_PATH', APP_PATH . '/config');

// Detectar entorno pero SIN redirecciones
$current_host = $_SERVER['HTTP_HOST'] ?? '';
$is_tunnel = strpos($current_host, 'localto.net') !== false;

// Forzar configuración sin importar el protocolo actual
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
define('APP_URL', $protocol . $current_host . '/encasa_database');

// Cargar configuración
require_once CONFIG_PATH . '/config.php';
require_once CONFIG_PATH . '/database.php';
require_once CONFIG_PATH . '/autoload.php';

// Iniciar sesión con configuración básica
ini_set('session.cookie_httponly', 1);
session_start();

// Mostrar formulario de login directamente sin usar el sistema de rutas
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Directo - Iglesia En Casa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h2 class="my-2">Acceso Directo - Iniciar Sesión</h2>
                    </div>
                    <div class="card-body">
                        <form action="<?= APP_URL ?>/direct_login.php" method="post" autocomplete="on">
                            <div class="mb-3">
                                <label for="email_or_username" class="form-label">Email o nombre de usuario</label>
                                <input type="text" class="form-control" id="email_or_username" name="email_or_username" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>