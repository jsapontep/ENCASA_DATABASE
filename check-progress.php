<?php

// Script para verificar el progreso del proyecto

echo "<h1>Verificación de Progreso del Sistema</h1>";

// Verificar componentes críticos
$components = [
    'Conexión BD' => function() {
        return class_exists('PDO');
    },
    'Router' => function() {
        return file_exists('app/helpers/Router.php');
    },
    'Autenticación' => function() {
        return file_exists('app/controllers/AuthController.php');
    },
    'Modelo Usuario' => function() {
        return file_exists('app/models/Usuario.php');
    },
    'Login Funcional' => function() {
        return file_exists('app/views/auth/login.php');
    },
    'Modelo Miembro' => function() {
        return file_exists('app/models/Miembro.php') || file_exists('app/models/InformacionGeneral.php');
    },
    // Añadir más verificaciones para cada etapa
];

// Mostrar resultados
echo "<table border='1' style='border-collapse:collapse'>";
echo "<tr><th>Componente</th><th>Estado</th></tr>";

$completedCount = 0;
foreach ($components as $name => $checkFn) {
    $status = $checkFn() ? "✅ Implementado" : "❌ Pendiente";
    $style = $checkFn() ? "background-color: #d4edda;" : "background-color: #f8d7da;";
    echo "<tr style='$style'><td>$name</td><td>$status</td></tr>";
    
    if ($checkFn()) $completedCount++;
}

$percent = round(($completedCount / count($components)) * 100);
echo "</table>";
echo "<p>Progreso general: <b>$percent%</b></p>";