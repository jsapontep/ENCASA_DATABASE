<?php
// filepath: c:\xampp\htdocs\ENCASA_DATABASE\direct_login.php

// Este archivo procesa el login directamente sin usar el sistema de rutas
// Configuración básica
define('BASE_PATH', __DIR__);
define('APP_PATH', BASE_PATH . '/app');
define('CONFIG_PATH', APP_PATH . '/config');
define('MODEL_PATH', APP_PATH . '/models');
define('VIEW_PATH', APP_PATH . '/views');

// Cargar solo lo necesario
require_once CONFIG_PATH . '/database.php';
require_once APP_PATH . '/models/User.php';

// Iniciar sesión 
session_start();

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email_or_username = $_POST['email_or_username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Validación básica
    if (empty($email_or_username) || empty($password)) {
        die("Por favor completa todos los campos");
    }
    
    try {
        // Conectar a la BD
        $db = new PDO(
            'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8',
            DB_USER,
            DB_PASS
        );
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Consulta para verificar credenciales
        $stmt = $db->prepare(
            "SELECT * FROM users WHERE (email = :credential OR username = :credential) LIMIT 1"
        );
        $stmt->execute(['credential' => $email_or_username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            // Login exitoso
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['username'];
            $_SESSION['user_role'] = $user['role'];
            
            // Redirigir a la página de inicio (sin sistema de rutas)
            header("Location: home_direct.php");
            exit;
        } else {
            die("Credenciales incorrectas. <a href='direct_access.php'>Volver a intentar</a>");
        }
        
    } catch (PDOException $e) {
        die("Error de base de datos: " . $e->getMessage());
    }
}

// Mostrar la vista de login directamente
echo "<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Iniciar Sesión - Acceso Directo</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
</head>
<body class='bg-light'>
    <div class='container py-5'>
        <div class='row justify-content-center'>
            <div class='col-md-6'>
                <div class='card shadow'>
                    <div class='card-header bg-primary text-white'>
                        <h2 class='my-2'>Iniciar Sesión (Acceso Directo)</h2>
                    </div>
                    <div class='card-body'>";

// Incluir el formulario de login
if (file_exists(VIEW_PATH . '/auth/login.php')) {
    include_once VIEW_PATH . '/auth/login.php';
} else {
    echo "<div class='alert alert-danger'>No se encontró el archivo de vista login.php</div>";
    echo "<p>Ruta buscada: " . VIEW_PATH . "/auth/login.php</p>";
}

echo "        </div>
                </div>
            </div>
        </div>
    </div>
    <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js'></script>
</body>
</html>";
?>