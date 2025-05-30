<?php

// Script para diagnosticar y solucionar problemas de URL en túneles

// 1. Mostrar información del entorno
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$is_tunnel = strpos($host, 'localto.net') !== false || 
             strpos($host, 'loca.lt') !== false ||
             strpos($host, 'ngrok-free.app') !== false;

echo "<h1>Diagnóstico Global de URLs</h1>";

echo "<h2>Información del Entorno</h2>";
echo "<pre>";
echo "Host: $host\n";
echo "Protocol: $protocol\n";
echo "Request URI: $requestUri\n";
echo "¿Es túnel?: " . ($is_tunnel ? "SÍ" : "NO") . "\n";
echo "</pre>";

// 2. Verificar configuraciones existentes
if (defined('APP_URL')) {
    echo "<h2>Constantes Actuales</h2>";
    echo "<pre>";
    echo "APP_URL: " . APP_URL . "\n";
    if (defined('APP_ENV')) echo "APP_ENV: " . APP_ENV . "\n";
    echo "</pre>";
}

// 3. Generar una URL de prueba con la función url() si existe
echo "<h2>Prueba de Generación de URL</h2>";
if (function_exists('url')) {
    echo "<p>Función url() existe.</p>";
    echo "<p>url('login') genera: " . url('login') . "</p>";
} else {
    echo "<p style='color:red'>⚠️ Función url() no encontrada. Esto puede causar problemas.</p>";
}

// 4. Verificar archivos críticos
$files_to_check = [
    'index.php',
    'app/helpers/functions.php',
    'app/helpers/Router.php',
    'app/views/layouts/default.php',
    'app/views/auth/login.php'
];

echo "<h2>Verificación de Archivos Críticos</h2>";
echo "<ul>";
foreach ($files_to_check as $file) {
    $path = __DIR__ . '/' . $file;
    if (file_exists($path)) {
        echo "<li>✅ $file <span style='color:green'>existe</span></li>";
    } else {
        echo "<li>❌ $file <span style='color:red'>no existe</span></li>";
    }
}
echo "</ul>";

// 5. Proporcionar solución integral
echo "<h2>Solución Recomendada</h2>";
echo "<p>Para resolver los problemas de URL en túneles, sigue estos pasos:</p>";
echo "<ol>";
echo "<li>En index.php, asegúrate de que APP_URL se configure adecuadamente para túneles.</li>";
echo "<li>En todas las vistas y controladores, usa la función url() en lugar de URLs absolutas.</li>";
echo "<li>Verifica que el Router esté usando APP_URL para las redirecciones.</li>";
echo "<li>Actualiza los enlaces de la barra de navegación para usar url().</li>";
echo "</ol>";

// 6. Proporcionar código para solucionarlo inmediatamente
echo "<h2>Código para Solucionar el Problema</h2>";

echo "<h3>1. Función url() en app/helpers/functions.php</h3>";
echo '<pre style="background-color: #f5f5f5; padding: 10px; border: 1px solid #ddd; overflow: auto;">';
echo htmlspecialchars('<?php
// En app/helpers/functions.php
/**
 * Genera una URL completa para la aplicación
 * @param string $path Ruta relativa (sin / inicial)
 * @return string URL completa
 */
function url($path = \'\')
{
    // Asegurar que APP_URL existe
    if (!defined(\'APP_URL\')) {
        // Determinar URL base si no está definida
        $protocol = isset($_SERVER[\'HTTPS\']) && $_SERVER[\'HTTPS\'] === \'on\' ? \'https://\' : \'http://\';
        $host = $_SERVER[\'HTTP_HOST\'] ?? \'localhost\';
        $is_tunnel = strpos($host, \'localto.net\') !== false || 
                     strpos($host, \'loca.lt\') !== false ||
                     strpos($host, \'ngrok-free.app\') !== false;
                     
        // Configurar base URL
        $base_url = $protocol . $host;
        if ($is_tunnel) {
            $base_url .= \'/encasa_database\'; 
        } else {
            $base_url .= \'/ENCASA_DATABASE\';
        }
    } else {
        $base_url = APP_URL;
    }
    
    // Formatear URL
    $base_url = rtrim($base_url, \'/\');
    $path = ltrim($path, \'/\');
    
    return $base_url . ($path ? \'/\' . $path : \'\');
}');
echo '</pre>';

echo "<h3>2. Método redirect en Router.php</h3>";
echo '<pre style="background-color: #f5f5f5; padding: 10px; border: 1px solid #ddd; overflow: auto;">';
echo htmlspecialchars('// En app/helpers/Router.php - Método redirect
/**
 * Redirige a una URL
 */
public static function redirect($url) {
    // Usar la función url() para generar URLs consistentes
    if (function_exists(\'url\')) {
        header(\'Location: \' . url($url));
    } else {
        // Fallback si la función url() no existe
        $baseUrl = defined(\'APP_URL\') ? APP_URL : \'/ENCASA_DATABASE\';
        header(\'Location: \' . rtrim($baseUrl, \'/\') . \'/\' . ltrim($url, \'/\'));
    }
    exit;
}');
echo '</pre>';

// 7. Proporcionar enlaces de prueba
echo "<h2>Enlaces de Prueba</h2>";
$base = $protocol . $host . ($is_tunnel ? '/encasa_database' : '/ENCASA_DATABASE');
echo "<p><a href='$base/login' target='_blank'>Probar login</a></p>";
echo "<p><a href='$base/direct_access.php' target='_blank'>Usar direct_access.php</a> (bypass del sistema de rutas)</p>";
echo "<p><a href='$base/direct_test.php' target='_blank'>Probar base de datos directamente</a></p>";
?>