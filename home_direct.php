<?php
// filepath: c:\xampp\htdocs\ENCASA_DATABASE\home_direct.php

// Configuración básica
define('BASE_PATH', __DIR__);
define('APP_PATH', BASE_PATH . '/app');

// Iniciar sesión
session_start();

// Verificar autenticación
if (!isset($_SESSION['user_id'])) {
    header("Location: direct_access.php");
    exit;
}

// Datos básicos del usuario actual
$username = htmlspecialchars($_SESSION['user_name']);
$role = htmlspecialchars($_SESSION['user_role']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Principal - Iglesia En Casa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Iglesia En Casa</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <?= $username ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="logout_direct.php">Cerrar Sesión</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="jumbotron">
            <h1>Bienvenido, <?= $username ?></h1>
            <p>Has iniciado sesión correctamente en el modo de acceso directo.</p>
            <p>Tu rol es: <?= $role ?></p>
            
            <div class="alert alert-info mt-4">
                <p><strong>Nota:</strong> Estás usando una versión simplificada de la aplicación para evitar el problema de redirecciones infinitas.</p>
                <p>Cuando el problema se resuelva, podrás volver a usar la aplicación completa.</p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>