<?php
// filepath: c:\xampp\htdocs\ENCASA_DATABASE\tools\replace_urls.php

// Activar manejo de errores
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Función para registrar mensajes
function log_message($message, $type = 'INFO') {
    echo "[$type] $message\n";
}

// Verificar si la función url() existe
if (!function_exists('url')) {
    log_message("Creando función url() para pruebas", "SETUP");
    function url($path) {
        return "URL_BASE/$path";
    }
}

// Define directorios a escanear
$directories = [
    __DIR__ . '/../app/views',
    __DIR__ . '/../app/Controllers'
];

$count = 0;
$files = 0;
$errors = 0;

// Verificar que los directorios existen
foreach ($directories as $index => $directory) {
    if (!is_dir($directory)) {
        log_message("El directorio no existe: $directory", "ERROR");
        unset($directories[$index]);
        $errors++;
    } else {
        log_message("Escaneando directorio: $directory", "INFO");
    }
}

// Proceder solo si hay directorios válidos
if (empty($directories)) {
    log_message("No hay directorios válidos para escanear", "ERROR");
    exit(1);
}

// Modo de prueba (sin modificar archivos) - cambia a false para aplicar cambios
$test_mode = false;

foreach ($directories as $directory) {
    try {
        $it = new RecursiveDirectoryIterator($directory);
        $it = new RecursiveIteratorIterator($it);
        $it = new RegexIterator($it, '/\.php$/');
        
        foreach ($it as $file) {
            try {
                log_message("Procesando: " . $file, "INFO");
                
                // Verificar permisos de lectura
                if (!is_readable($file)) {
                    log_message("No se puede leer el archivo: $file", "ERROR");
                    $errors++;
                    continue;
                }
                
                $content = file_get_contents($file);
                if ($content === false) {
                    log_message("Error al leer el archivo: $file", "ERROR");
                    $errors++;
                    continue;
                }
                
                $original = $content;
                
                // Reemplazar patrones simples primero
                $simple_patterns = [
                    '<?= APP_URL ?>/miembros' => '<?= url("miembros") ?>',
                    '<?= APP_URL ?>/public' => '<?= url("public") ?>',
                    '<?= APP_URL ?>/assets' => '<?= url("assets") ?>',
                    '<?= APP_URL ?>/usuarios' => '<?= url("usuarios") ?>'
                ];
                
                foreach ($simple_patterns as $pattern => $replacement) {
                    $content = str_replace($pattern, $replacement, $content);
                }
                
                // Verificar si hubo cambios
                if ($content !== $original) {
                    $changes = substr_count($original, 'APP_URL') - substr_count($content, 'APP_URL');
                    log_message("Se encontraron $changes cambios en: $file", "SUCCESS");
                    
                    // Aplicar cambios si no estamos en modo de prueba
                    if (!$test_mode) {
                        if (!is_writable($file)) {
                            log_message("No se puede escribir en el archivo: $file", "ERROR");
                            $errors++;
                            continue;
                        }
                        
                        if (file_put_contents($file, $content) !== false) {
                            $count += $changes;
                            $files++;
                            log_message("Archivo actualizado: $file", "SUCCESS");
                        } else {
                            log_message("Error al escribir en el archivo: $file", "ERROR");
                            $errors++;
                        }
                    } else {
                        log_message("Cambios simulados (modo prueba): no se modificó el archivo", "TEST");
                        $count += $changes;
                        $files++;
                    }
                } else {
                    log_message("Sin cambios en: $file", "INFO");
                }
            } catch (Exception $e) {
                log_message("Error procesando archivo {$file->getPathname()}: " . $e->getMessage(), "ERROR");
                $errors++;
            }
        }
    } catch (Exception $e) {
        log_message("Error al procesar directorio $directory: " . $e->getMessage(), "ERROR");
        $errors++;
    }
}

// Resumen final
echo "\n=== RESUMEN ===\n";
echo "Modo: " . ($test_mode ? "PRUEBA (no se modificaron archivos)" : "REAL (archivos modificados)") . "\n";
echo "Reemplazadas $count ocurrencias de APP_URL en $files archivos\n";
echo "Ocurrieron $errors errores durante el proceso\n";

if ($test_mode) {
    echo "\nEste fue un test. Para aplicar los cambios, cambia \$test_mode = true a \$test_mode = false\n";
}