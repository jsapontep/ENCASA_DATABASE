<?php


echo "<h1>Diagnóstico de rutas</h1>";

// Analizar URL actual
$requestUri = $_SERVER['REQUEST_URI'] ?? 'No disponible';
$scriptName = $_SERVER['SCRIPT_NAME'] ?? 'No disponible';
$base = str_replace('/index.php', '', $scriptName);
$url = str_replace($base, '', $requestUri);
$url = ltrim($url, '/');
$url = explode('?', $url)[0];

echo "<h2>Información de la URL actual:</h2>";
echo "<ul>";
echo "<li><strong>REQUEST_URI:</strong> " . htmlspecialchars($requestUri) . "</li>";
echo "<li><strong>SCRIPT_NAME:</strong> " . htmlspecialchars($scriptName) . "</li>";
echo "<li><strong>Base detectada:</strong> " . htmlspecialchars($base) . "</li>";
echo "<li><strong>URL procesada:</strong> " . htmlspecialchars($url) . "</li>";
echo "</ul>";

// Verificar carga de archivos críticos
echo "<h2>Verificación de archivos críticos:</h2>";
echo "<ul>";
$filesRequired = [
    'index.php' => __DIR__ . '/index.php',
    'config.php' => __DIR__ . '/app/config/config.php',
    'routes.php' => __DIR__ . '/app/config/routes.php',
    'functions.php' => __DIR__ . '/app/helpers/functions.php',
    'Router.php' => __DIR__ . '/app/helpers/Router.php',
    '.htaccess' => __DIR__ . '/.htaccess',
];

foreach ($filesRequired as $name => $path) {
    echo "<li>";
    if (file_exists($path)) {
        echo "✅ <strong>$name:</strong> Existe";
    } else {
        echo "❌ <strong>$name:</strong> NO existe";
    }
    echo "</li>";
}
echo "</ul>";
?>