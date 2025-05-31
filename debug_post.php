<?php

// Script para depurar los datos POST enviados desde el formulario

// Activar visualización de errores
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h1>Datos POST recibidos</h1>";

// Comprobar si hay datos POST
if (empty($_POST)) {
    echo "<p>No se recibieron datos POST. Use este script como destino de un formulario.</p>";
    
    // Crear formulario de prueba
    echo "<h2>Formulario de prueba</h2>";
    echo "<form action='' method='post'>";
    echo "<div class='mb-3'>";
    echo "<label>Campo contacto[nombre]</label>";
    echo "<input type='text' name='contacto[nombre]' class='form-control'>";
    echo "</div>";
    echo "<div class='mb-3'>";
    echo "<label>Campo estudios[nivel]</label>";
    echo "<input type='text' name='estudios[nivel]' class='form-control'>";
    echo "</div>";
    echo "<button type='submit' class='btn btn-primary'>Enviar</button>";
    echo "</form>";
    
    exit;
}

// Mostrar los datos POST
echo "<h2>Todos los datos POST:</h2>";
echo "<pre>";
print_r($_POST);
echo "</pre>";

// Mostrar datos específicos para tablas relacionadas
$tablas = ['contacto', 'estudios', 'tallas', 'salud', 'carrera'];

foreach ($tablas as $tabla) {
    echo "<h2>Datos para tabla '$tabla':</h2>";
    if (isset($_POST[$tabla]) && is_array($_POST[$tabla])) {
        echo "<pre>";
        print_r($_POST[$tabla]);
        echo "</pre>";
        
        // Mostrar cómo se construiría la SQL
        echo "<h3>SQL que se ejecutaría:</h3>";
        $campos = array_keys($_POST[$tabla]);
        $placeholders = array_fill(0, count($campos), '?');
        
        echo "<code>INSERT INTO " . ucfirst($tabla) . " (" . implode(', ', $campos) . ", miembro_id) 
              VALUES (" . implode(', ', $placeholders) . ", 123);</code>";
    } else {
        echo "<p>No se encontraron datos para esta tabla.</p>";
    }
}