<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h1>Verificación de directorios de subida</h1>";

// Directorio de uploads
$uploadDir = __DIR__ . '/uploads/miembros';

echo "<p>Verificando directorio: <code>$uploadDir</code></p>";

if (!is_dir($uploadDir)) {
    echo "<p>El directorio no existe. Intentando crear...</p>";
    
    if (mkdir($uploadDir, 0755, true)) {
        echo "<p style='color:green'>✓ Directorio creado exitosamente.</p>";
    } else {
        echo "<p style='color:red'>❌ No se pudo crear el directorio. Error: " . error_get_last()['message'] . "</p>";
    }
} else {
    echo "<p style='color:green'>✓ El directorio existe.</p>";
}

// Verificar permisos
if (is_writable($uploadDir)) {
    echo "<p style='color:green'>✓ El directorio tiene permisos de escritura.</p>";
} else {
    echo "<p style='color:red'>❌ El directorio NO tiene permisos de escritura.</p>";
    echo "<p>Ejecute este comando en la línea de comandos:<br>";
    echo "<code>chmod -R 755 $uploadDir</code></p>";
}

// Verificar configuración PHP para subida de archivos
echo "<h2>Configuración de PHP para subida de archivos</h2>";
echo "<ul>";
echo "<li>upload_max_filesize: " . ini_get('upload_max_filesize') . "</li>";
echo "<li>post_max_size: " . ini_get('post_max_size') . "</li>";
echo "<li>max_file_uploads: " . ini_get('max_file_uploads') . "</li>";
echo "</ul>";

// Crear formulario de prueba
echo "<h2>Formulario de prueba de subida</h2>";
echo "<form action='test_upload.php' method='post' enctype='multipart/form-data'>";
echo "<div><input type='file' name='test_file'></div>";
echo "<div style='margin-top:10px'><button type='submit'>Probar subida</button></div>";
echo "</form>";
?>