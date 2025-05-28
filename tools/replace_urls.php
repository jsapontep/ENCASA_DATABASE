<?php
// filepath: c:\xampp\htdocs\ENCASA_DATABASE\tools\replace_urls.php

// Define directorios a escanear
$directories = [
    __DIR__ . '/../app/views',
    __DIR__ . '/../app/Controllers'
];

$count = 0;
$files = 0;

foreach ($directories as $directory) {
    $it = new RecursiveDirectoryIterator($directory);
    $it = new RecursiveIteratorIterator($it);
    $it = new RegexIterator($it, '/\.php$/');
    
    foreach ($it as $file) {
        $content = file_get_contents($file);
        $original = $content;
        
        // Reemplazar diferentes patrones de APP_URL
        $content = preg_replace('/(?<=\<\?=\s)APP_URL(\s*\.\s*[\'"]\/)/', 'url(\'', $content);
        $content = preg_replace('/(?<=\<\?=\s)APP_URL(\s*\.\s*[\'"])/', 'url(\'', $content);
        $content = preg_replace('/([\'"])(\s*\.\s*)APP_URL(\s*\.\s*[\'"]\/)/', '$1 . url(\'', $content);
        
        // Cerrar los paréntesis y comillas
        $content = str_replace('/"', '\')"', $content);
        $content = str_replace('\'/', '\')', $content);
        
        if ($content !== $original) {
            file_put_contents($file, $content);
            $count += substr_count($original, 'APP_URL') - substr_count($content, 'APP_URL');
            $files++;
            echo "Actualizado: " . $file . "\n";
        }
    }
}

echo "Reemplazadas $count ocurrencias de APP_URL en $files archivos\n";