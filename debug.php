<?php
// filepath: /Applications/XAMPP/xamppfiles/htdocs/Encasa_Database/debug.php

// Configuración
define('BASE_PATH', __DIR__);
define('APP_PATH', BASE_PATH . '/app');
define('CONFIG_PATH', APP_PATH . '/config');

// Cargar autoloader
require_once CONFIG_PATH . '/config.php';
require_once CONFIG_PATH . '/database.php';
require_once CONFIG_PATH . '/autoload.php';

// Crear instancia del modelo Usuario
$userModel = new \App\Models\Usuario();

// Función para mostrar resultados
function showResult($title, $data) {
    echo "<h3>$title</h3>";
    echo "<pre>";
    print_r($data);
    echo "</pre>";
    echo "<hr>";
}

// 1. Mostrar todos los usuarios
$users = $userModel->getAll();
foreach ($users as &$user) {
    // Ocultar contraseña completa
    $user['password'] = substr($user['password'], 0, 15) . '...';
}
showResult("Usuarios registrados", $users);

// 2. Crear un usuario de prueba (descomentar si necesitas crear uno)
/*
$newUser = [
    'username' => 'testuser',
    'email' => 'test@example.com',
    'password' => '123456',
    'nombre_completo' => 'Usuario de Prueba',
    'activo' => 1
];

$userID = $userModel->register($newUser);
showResult("Resultado de creación de usuario", $userID ? "Usuario creado con ID: $userID" : "Error al crear usuario");
*/

// Descomentar esta sección para crear un usuario nuevo
$newUser = [
    'username' => 'admin2',
    'email' => 'admin2@ejemplo.com',
    'password' => 'password123',
    'nombre_completo' => 'Administrador 2',
    'activo' => 1
];

$userID = $userModel->register($newUser);
showResult("Resultado de creación de usuario", $userID ? "Usuario creado con ID: $userID" : "Error al crear usuario");

// 3. Probar autenticación con un usuario existente
$testAuth = $userModel->authenticate('rafa.gzfr@gmail.com', 'Amor2025+'); // cambia por credenciales reales
showResult("Prueba de autenticación", $testAuth ? "Autenticación exitosa" : "Autenticación fallida");

// 4. Ver registro específico
if (!empty($users)) {
    $firstUser = $users[0];
    $userId = $firstUser['id'];
    $user = $userModel->findById($userId);
    showResult("Usuario con ID $userId", $user);
}

// 1. Primero, cambia la contraseña
$userId = 1;
$newPassword = 'Amor2025+';

$stmt = $userModel->db->prepare("UPDATE Usuarios SET password = :password WHERE id = :id");
$stmt->bindValue(':password', $userModel->hashPassword($newPassword));
$stmt->bindValue(':id', $userId);
$result = $stmt->execute();

showResult("Cambio de contraseña para usuario ID $userId", 
    $result ? "Contraseña actualizada correctamente" : "Error al actualizar contraseña");

// 2. Luego, prueba la autenticación con la nueva contraseña
$testAuth = $userModel->authenticate('rafa.gzfr@gmail.com', 'Amor2025+');
showResult("Prueba de autenticación", $testAuth ? "Autenticación exitosa" : "Autenticación fallida");
?>