<?php

require_once __DIR__ . '/app/config/config.php';
require_once __DIR__ . '/app/config/database.php';

// Interfaz de usuario simple
if (!isset($_GET['key']) || !isset($_GET['username'])) {
    echo '
    <form method="get">
        <h2>Restablecer contraseña</h2>
        <input type="hidden" name="key" value="admin_reset_2025">
        <p>Usuario: <input type="text" name="username" value="hernando"></p>
        <button type="submit">Restablecer</button>
    </form>';
    exit;
}

// Verificar clave
if ($_GET['key'] !== 'admin_reset_2025') {
    die("Acceso no autorizado");
}

$username = $_GET['username'] ?? 'hernando'; // Usuario a resetear
$newPassword = '12345!';  // Nueva contraseña

// Crear hash compatible
$hash = password_hash($newPassword, PASSWORD_DEFAULT);

// Actualizar en la base de datos - CORREGIDO SIN NAMESPACE
try {
    // Obtener la conexión sin usar namespace (ajustado al patrón de tu proyecto)
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare("UPDATE usuarios SET password = ? WHERE username = ?");
    $result = $stmt->execute([$hash, $username]);
    
    echo $result ? 
        "<h3>Contraseña actualizada correctamente para $username.</h3><p>La nueva contraseña es: <strong>$newPassword</strong></p>" : 
        "<h3>Error al actualizar la contraseña</h3>";
        
} catch (Exception $e) {
    echo "<h3>Error:</h3><p>" . $e->getMessage() . "</p>";
}