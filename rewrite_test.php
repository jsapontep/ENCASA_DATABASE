<?php

echo "<h1>Prueba de mod_rewrite</h1>";

if (function_exists('apache_get_modules')) {
    $modules = apache_get_modules();
    echo "<p>mod_rewrite está " . (in_array('mod_rewrite', $modules) ? "habilitado" : "deshabilitado") . "</p>";
} else {
    echo "<p>No se puede determinar si mod_rewrite está habilitado en este servidor.</p>";
}

echo "<p>Verificación de .htaccess: ";
$htaccess_path = __DIR__ . '/.htaccess';
if (file_exists($htaccess_path)) {
    echo "El archivo .htaccess existe ✓</p>";
    echo "<pre>" . htmlspecialchars(file_get_contents($htaccess_path)) . "</pre>";
} else {
    echo "El archivo .htaccess NO existe ✗</p>";
}