<?php
// filepath: c:\xampp\htdocs\ENCASA_DATABASE\app\config\cors.php
/**
 * Configuración global de CORS para la aplicación
 * Detecta automáticamente entornos y configura las cabeceras adecuadamente
 */

// Obtener el origen de la solicitud
$origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';
$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';

// Función para extraer el dominio base
function getDomainFromUrl($url) {
    $parsedUrl = parse_url($url);
    return isset($parsedUrl['host']) ? $parsedUrl['host'] : '';
}

// Detectar si estamos usando ngrok o localtunnel
$isTunnel = false;
$tunnelDomain = '';

// Detectar ngrok
if (strpos($origin, 'ngrok-free.app') !== false) {
    $isTunnel = true;
    $tunnelDomain = $origin;
} elseif (strpos($referer, 'ngrok-free.app') !== false) {
    $isTunnel = true;
    $tunnelDomain = preg_replace('/^(https?:\/\/[^\/]+).*$/', '$1', $referer);
}

// Detectar localtunnel
if (!$isTunnel) {
    if (strpos($origin, 'loca.lt') !== false || strpos($origin, 'localto.net') !== false) {
        $isTunnel = true;
        $tunnelDomain = $origin;
    } elseif (strpos($referer, 'loca.lt') !== false || strpos($referer, 'localto.net') !== false) {
        $isTunnel = true;
        $tunnelDomain = preg_replace('/^(https?:\/\/[^\/]+).*$/', '$1', $referer);
    }
}

// Configurar los encabezados CORS según el entorno
if ($isTunnel) {
    // Si es un túnel, permitir ese origen específico
    header("Access-Control-Allow-Origin: $tunnelDomain");
    error_log("CORS: Permitido origen de túnel: $tunnelDomain");
} elseif (in_array($_SERVER['SERVER_NAME'] ?? '', ['localhost', '127.0.0.1'])) {
    // Entorno de desarrollo local
    header('Access-Control-Allow-Origin: *');
    error_log("CORS: Permitido cualquier origen (desarrollo local)");
} else {
    // Entorno de producción - aquí puedes definir dominios específicos
    $allowedDomains = [
        'iglesiaencasa.org',
        'www.iglesiaencasa.org'
    ];
    
    $domain = getDomainFromUrl($origin);
    if (in_array($domain, $allowedDomains)) {
        header("Access-Control-Allow-Origin: $origin");
        error_log("CORS: Permitido origen de producción: $origin");
    }
}

// Configurar otros encabezados CORS
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, X-XSRF-TOKEN');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Max-Age: 86400'); // 24 horas

// Si es una solicitud OPTIONS (preflight), terminar aquí
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}