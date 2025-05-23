<?php
// Archivo temporal para depuración: /Applications/XAMPP/xamppfiles/htdocs/Encasa_Database/debug_users.php

// Incluir configuración básica
require_once 'index.php';

// Obtener instancia del modelo
$userModel = new \App\Models\Usuario();

// Mostrar todos los usuarios
echo "<h2>Usuarios registrados:</h2>";
$users = $userModel->getAll();

echo "<pre>";
foreach ($users as $user) {
    // Ocultar la contraseña completa por seguridad
    $user['password'] = substr($user['password'], 0, 10) . '...';
    print_r($user);
}
echo "</pre>";