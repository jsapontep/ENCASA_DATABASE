<?php
// filepath: c:\xampp\htdocs\ENCASA_DATABASE\tunnel_fix.php

// 1. Eliminar todas las cookies para limpiar sesión
if (isset($_GET['clear_cookies'])) {
    $past = time() - 3600;
    foreach ($_COOKIE as $key => $value) {
        setcookie($key, '', $past, '/');
    }
    echo "<p>Cookies eliminadas. <a href='?'>Continuar</a></p>";
    exit;
}

// 2. Mostrar información de diagnóstico
echo "<h1>Diagnóstico de Tunnel</h1>";

// Información del servidor
echo "<h2>Información del servidor</h2>";
echo "<pre>";
echo "HTTP_HOST: " . ($_SERVER['HTTP_HOST'] ?? 'No definido') . "\n";
echo "REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'No definido') . "\n"; 
echo "HTTPS: " . (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'on' : 'off') . "\n";
echo "</pre>";

// 3. Enlace para acceder directamente al login
$host = $_SERVER['HTTP_HOST'];
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
$login_url = $protocol . $host . "/ENCASA_DATABASE/login";
$ignored_url = $protocol . $host . "/encasa_database/login?bypass_redirect=1";

echo "<h2>Enlaces de prueba:</h2>";
echo "<p><a href='$login_url'>Acceso normal al login</a></p>";
echo "<p><a href='$ignored_url'>Acceso al login sin redirecciones</a></p>";
echo "<p><a href='?clear_cookies'>Limpiar cookies y sesión</a></p>";

// 4. Proporcionar instrucciones
echo "<h2>Instrucciones:</h2>";
echo "<ol>";
echo "<li>Primero, haz clic en 'Limpiar cookies y sesión'</li>";
echo "<li>Luego intenta el 'Acceso al login sin redirecciones'</li>";
echo "<li>Si funciona, necesitas modificar tu index.php según las instrucciones abajo</li>";
echo "</ol>";

echo "<h2>Modificación recomendada para index.php:</h2>";
echo "<pre style='background:#f8f9fa;padding:10px;'>";
echo '// Añadir esta condición antes de cualquier redirección
if (isset($_GET["bypass_redirect"])) {
    // No hacer ninguna redirección
    define("BYPASS_REDIRECTS", true);
}

// Modificar la redirección existente:
if (!defined("BYPASS_REDIRECTS") && $is_tunnel && empty($_SERVER["HTTPS"])) {
    $redirect_url = "https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
    header("Location: $redirect_url");
    exit;
}';
echo "</pre>";

// 5. Diagnóstico de Router y URL
echo "<h2>Diagnóstico del Router:</h2>";
echo "<p>Tu Router.php está usando esta línea para redirecciones:</p>";
echo "<code>header('Location: ' . APP_URL . '/' . \$url);</code>";
echo "<p>Si APP_URL está establecido incorrectamente, causará bucles de redirección.</p>";

echo "<p>APP_URL actual (o esperado): ";
if (defined('APP_URL')) {
    echo APP_URL;
} else {
    echo "No definido aquí";
}
echo "</p>";