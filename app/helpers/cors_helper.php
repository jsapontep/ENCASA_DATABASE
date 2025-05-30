<?php
// filepath: c:\xampp\htdocs\ENCASA_DATABASE\app\helpers\cors_helper.php

/**
 * Configuración dinámica de CORS para formularios
 * Detecta automáticamente si estamos en túnel o localhost
 */
function setup_cors() {
    // Detectar el entorno
    $is_https = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
    $current_host = $_SERVER['HTTP_HOST'];
    $is_tunnel = (strpos($current_host, 'localto.net') !== false || 
                 strpos($current_host, 'loca.lt') !== false || 
                 strpos($current_host, 'serveo.net') !== false);
    
    // Establecer cabeceras CORS apropiadas
    if ($is_tunnel) {
        // Permitir el origen actual en túneles
        $protocol = $is_https ? 'https://' : 'http://';
        $origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : $protocol . $current_host;
        header("Access-Control-Allow-Origin: $origin");
    } else {
        // En localhost ser menos restrictivo
        header("Access-Control-Allow-Origin: *");
    }
    
    // Cabeceras CORS comunes
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
    header("Access-Control-Allow-Credentials: true");
    
    // Manejar solicitudes preflight OPTIONS
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        header("HTTP/1.1 200 OK");
        exit;
    }
}

/**
 * Genera URLs correctas según el entorno
 */
function form_url($path = '') {
    $current_host = $_SERVER['HTTP_HOST'];
    $is_tunnel = (strpos($current_host, 'localto.net') !== false || 
                 strpos($current_host, 'loca.lt') !== false || 
                 strpos($current_host, 'serveo.net') !== false);
    
    // Construir la base de la URL
    if ($is_tunnel) {
        // En túneles siempre usar HTTPS
        $base_url = "https://$current_host/encasa_database";
    } else {
        // En localhost usar HTTP
        $base_url = "http://$current_host/ENCASA_DATABASE";
    }
    
    // Formatear la URL
    $base_url = rtrim($base_url, '/');
    $path = $path ? '/' . ltrim($path, '/') : '';
    
    return $base_url . $path;
}

// Aplicar CORS automáticamente
setup_cors();