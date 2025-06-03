<?php
// Mostrar todos los errores
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "=== DIAGNÓSTICO PARA ERRORES DE JSON ===\n\n";

// Simular una respuesta JSON correcta
header('Content-Type: application/json');
echo json_encode([
    'success' => true,
    'message' => 'Esta es una respuesta JSON válida',
    'timestamp' => date('Y-m-d H:i:s')
]);
exit;