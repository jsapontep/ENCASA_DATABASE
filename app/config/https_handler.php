<?php
// filepath: c:\xampp\htdocs\ENCASA_DATABASE\app\config\https_handler.php

/**
 * Mejorar manejo de HTTPS para evitar problemas de formularios
 */
function configure_https() {
    // Forzar HSTS para conexiones seguras
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
        header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
    }
    
    // Configuración CORS básica
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
    
    // Forzar recursos seguros
    header('Content-Security-Policy: upgrade-insecure-requests');
}

// Aplicar configuración
configure_https();