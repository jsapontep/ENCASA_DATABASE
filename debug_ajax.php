<?php

// Archivo para depurar problemas con respuestas JSON

// Análisis para detectar salida antes de los headers

// NO iniciar buffer todavía

// Verificar si ya se ha enviado algún contenido
if (headers_sent($filename, $linenum)) {
    echo "<h1>¡Error! Los encabezados ya fueron enviados</h1>";
    echo "<p>Los encabezados HTTP ya se enviaron desde el archivo $filename en la línea $linenum</p>";
    echo "<p>Esto puede estar causando que las respuestas JSON no sean válidas.</p>";
    exit;
}

// Ahora iniciamos buffer para capturar cualquier salida no deseada
ob_start();

// Incluir archivos necesarios
require_once 'app/config/config.php';
require_once 'app/config/database.php';

// Verificar si hubo salida durante las inclusiones
$output = ob_get_clean();
if (!empty($output)) {
    echo "<h1>¡Advertencia! Se detectó salida durante la inclusión de archivos</h1>";
    echo "<p>Esta salida podría estar interfiriendo con las respuestas JSON</p>";
    echo "<h2>Contenido detectado:</h2>";
    echo "<pre>" . htmlspecialchars($output) . "</pre>";
    exit;
}

// Si llegamos aquí, está todo limpio para enviar JSON
header('Content-Type: application/json');
echo json_encode([
    'success' => true,
    'message' => 'Esta es una respuesta JSON limpia y válida',
    'timestamp' => date('Y-m-d H:i:s')
]);