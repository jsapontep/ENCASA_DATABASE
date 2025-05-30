<?php
// NO incluir index.php completo

// Definir constantes básicas
define('BASE_PATH', __DIR__);
define('APP_PATH', BASE_PATH . '/app');

// Detectar entorno
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$is_ngrok = strpos($host, 'ngrok-free.app') !== false || strpos($host, 'ngrok.io') !== false;

// Crear una versión simplificada de url() para pruebas
function test_url($path = '') {
    global $protocol, $host, $is_ngrok;
    
    if ($is_ngrok) {
        $baseUrl = $protocol . $host . '/ENCASA_DATABASE';
    } else {
        $baseUrl = 'http://localhost/ENCASA_DATABASE';
    }
    
    $baseUrl = rtrim($baseUrl, '/');
    $path = ltrim($path, '/');
    
    return $baseUrl . ($path ? '/' . $path : '');
}

echo "<!DOCTYPE html>
<html>
<head>
    <title>Prueba de ngrok</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }
        .card { border: 1px solid #ddd; padding: 15px; margin-bottom: 20px; border-radius: 4px; }
        pre { background: #f5f5f5; padding: 10px; border-radius: 4px; }
    </style>
</head>
<body>
    <h1>Prueba de configuración de ngrok</h1>
    
    <div class='card'>
        <h2>Información básica</h2>
        <p><strong>Host:</strong> {$host}</p>
        <p><strong>Protocolo:</strong> {$protocol}</p>
        <p><strong>¿Es ngrok?:</strong> " . ($is_ngrok ? 'SÍ' : 'NO') . "</p>
        <p><strong>URL base generada:</strong> " . test_url() . "</p>
    </div>
    
    <div class='card'>
        <h2>Enlaces de prueba:</h2>
        <ul>
            <li><a href='" . test_url('login') . "'>Ir a login</a></li>
            <li><a href='" . test_url('miembros') . "'>Ir a miembros</a></li>
        </ul>
    </div>
</body>
</html>";
?>