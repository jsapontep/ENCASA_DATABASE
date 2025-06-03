<?php
// Diagnosticar problemas con respuestas AJAX
header('Content-Type: application/json');

try {
    // Simular una respuesta JSON exitosa
    echo json_encode([
        'success' => true,
        'message' => 'Esta es una respuesta JSON válida de prueba',
        'timestamp' => date('Y-m-d H:i:s')
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error al generar JSON: ' . $e->getMessage()
    ]);
}