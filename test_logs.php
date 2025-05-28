<?php
// filepath: c:\xampp\htdocs\ENCASA_DATABASE\test_logs.php

// Script para probar la configuración de logs
echo "<h1>Diagnóstico de logs de PHP</h1>";

// Mostrar configuración actual de error_log
echo "<h2>Configuración de logs</h2>";
echo "<p>error_log configurado en: " . ini_get('error_log') . "</p>";
echo "<p>error_reporting: " . ini_get('error_reporting') . "</p>";
echo "<p>display_errors: " . ini_get('display_errors') . "</p>";
echo "<p>log_errors: " . ini_get('log_errors') . "</p>";

// Intentar crear un directorio de logs si no existe
$logDir = "C:/xampp/php/logs";
if (!file_exists($logDir)) {
    echo "<p>Directorio de logs no existe. Intentando crear...</p>";
    mkdir($logDir, 0777, true);
    echo "<p>Directorio creado: " . (file_exists($logDir) ? "SÍ" : "NO") . "</p>";
}

// Escribir en el log para probarlo
error_log("Prueba de escritura en log desde test_logs.php");
echo "<p>Se ha intentado escribir en el log. Verifica si el archivo se ha creado.</p>";

// Intentar con una ruta alternativa
$altLogPath = __DIR__ . '/debug.log';
error_log("Prueba de escritura en log alternativo", 3, $altLogPath);
echo "<p>Log alternativo creado en: {$altLogPath}</p>";

// Mostrar los archivos en el directorio de logs
echo "<h2>Archivos en el directorio de logs</h2>";
echo "<pre>";
if (file_exists($logDir)) {
    print_r(scandir($logDir));
} else {
    echo "El directorio de logs no existe.";
}
echo "</pre>";