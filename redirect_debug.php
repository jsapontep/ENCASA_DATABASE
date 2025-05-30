<?php

session_start();
echo "<h1>Diagnóstico de redirecciones</h1>";

echo "<h2>Headers de respuesta:</h2>";
echo "<pre>";
var_dump(headers_list());
echo "</pre>";

echo "<h2>Variables de sesión:</h2>";
echo "<pre>";
var_dump($_SESSION);
echo "</pre>";

echo "<h2>Cookies:</h2>";
echo "<pre>";
var_dump($_COOKIE);
echo "</pre>";

echo "<h2>Variables de servidor:</h2>";
echo "<pre>";
$serverVars = [
    'HTTP_HOST', 'HTTPS', 'REQUEST_URI', 'SCRIPT_NAME'
];
foreach ($serverVars as $var) {
    echo "$var: " . ($_SERVER[$var] ?? 'No definido') . "\n";
}
echo "</pre>";
?>