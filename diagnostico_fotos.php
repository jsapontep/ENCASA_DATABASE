<?php

// Herramienta de diagnóstico para carga de fotos
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h1>Diagnóstico de Carga de Fotos</h1>";

// 1. Verificar configuración de PHP
echo "<h2>1. Configuración de PHP para carga de archivos</h2>";
echo "<table border='1' cellpadding='5'>";
echo "<tr><th>Configuración</th><th>Valor</th><th>Estado</th></tr>";

// Comprobar upload_max_filesize
$upload_max = ini_get('upload_max_filesize');
echo "<tr><td>upload_max_filesize</td><td>$upload_max</td><td>";
echo (return_bytes($upload_max) >= 2*1024*1024) ? "✅ OK" : "⚠️ Muy bajo";
echo "</td></tr>";

// Comprobar post_max_size
$post_max = ini_get('post_max_size');
echo "<tr><td>post_max_size</td><td>$post_max</td><td>";
echo (return_bytes($post_max) >= return_bytes($upload_max)) ? "✅ OK" : "❌ Debe ser mayor que upload_max_filesize";
echo "</td></tr>";

// Comprobar file_uploads
$file_uploads = ini_get('file_uploads');
echo "<tr><td>file_uploads</td><td>$file_uploads</td><td>";
echo ($file_uploads == 1) ? "✅ OK" : "❌ Carga de archivos deshabilitada";
echo "</td></tr>";
echo "</table>";

// 2. Verificar rutas y permisos
echo "<h2>2. Verificación de rutas y permisos</h2>";
echo "<table border='1' cellpadding='5'>";
echo "<tr><th>Ruta</th><th>Existe</th><th>Permisos</th><th>Escribible</th></tr>";

$rutas = [
    'Directorio raíz' => __DIR__,
    'Directorio public' => __DIR__ . '/public',
    'Directorio uploads' => __DIR__ . '/public/uploads',
    'Directorio miembros' => __DIR__ . '/public/uploads/miembros'
];

foreach ($rutas as $desc => $ruta) {
    $existe = file_exists($ruta) ? "✅ Sí" : "❌ No";
    $permisos = file_exists($ruta) ? substr(sprintf('%o', fileperms($ruta)), -4) : 'N/A';
    $escribible = file_exists($ruta) ? (is_writable($ruta) ? "✅ Sí" : "❌ No") : 'N/A';
    
    echo "<tr><td>$desc<br><small>$ruta</small></td><td>$existe</td><td>$permisos</td><td>$escribible</td></tr>";
    
    // Intentar crear la carpeta si no existe
    if (!file_exists($ruta)) {
        $creado = @mkdir($ruta, 0755, true);
        echo "<tr><td colspan='4' style='background:#ffe;'>";
        echo $creado ? "✅ Carpeta creada automáticamente" : "❌ No se pudo crear la carpeta";
        echo "</td></tr>";
    }
}

echo "</table>";

// 3. Formulario de prueba
echo "<h2>3. Prueba de carga simple</h2>";
echo "<form action='" . $_SERVER['PHP_SELF'] . "' method='POST' enctype='multipart/form-data'>";
echo "<input type='file' name='test_foto' accept='image/*'>";
echo "<button type='submit' name='test_upload'>Probar Carga</button>";
echo "</form>";

// Procesar formulario de prueba
if (isset($_POST['test_upload'])) {
    echo "<h3>Resultado de la prueba:</h3>";
    
    if (!empty($_FILES['test_foto']['name'])) {
        $error = $_FILES['test_foto']['error'];
        $nombre = htmlspecialchars($_FILES['test_foto']['name']);
        
        echo "<p>Archivo: $nombre</p>";
        echo "<p>Tamaño: " . format_bytes($_FILES['test_foto']['size']) . "</p>";
        echo "<p>Código de error: " . ($error == 0 ? "0 (Sin errores)" : "$error - " . upload_error_message($error)) . "</p>";
        
        if ($error == 0) {
            // Intentar guardar el archivo
            $dir_test = __DIR__ . '/public/uploads/test/';
            if (!file_exists($dir_test)) {
                mkdir($dir_test, 0755, true);
            }
            
            $test_path = $dir_test . $_FILES['test_foto']['name'];
            if (move_uploaded_file($_FILES['test_foto']['tmp_name'], $test_path)) {
                echo "<p>✅ Archivo guardado en: $test_path</p>";
                echo "<p>URL de acceso: <a href='/ENCASA_DATABASE/public/uploads/test/" . urlencode($_FILES['test_foto']['name']) . "' target='_blank'>Ver imagen</a></p>";
                echo "<p><img src='/ENCASA_DATABASE/public/uploads/test/" . urlencode($_FILES['test_foto']['name']) . "' style='max-width:300px;'></p>";
            } else {
                echo "<p>❌ Error al mover el archivo</p>";
            }
        }
    } else {
        echo "<p>❌ No se seleccionó ningún archivo</p>";
    }
}

// 4. Análisis del formulario real en editar.php
echo "<h2>4. Verificación del formulario en editar.php</h2>";

$formulario_path = __DIR__ . '/app/views/miembros/editar.php';
if (file_exists($formulario_path)) {
    $contenido = file_get_contents($formulario_path);
    
    // Verificar elementos críticos
    $tiene_enctype = strpos($contenido, 'enctype="multipart/form-data"') !== false;
    $tiene_input_file = strpos($contenido, 'type="file"') !== false;
    
    echo "<p>" . ($tiene_enctype ? "✅" : "❌") . " enctype=\"multipart/form-data\"</p>";
    echo "<p>" . ($tiene_input_file ? "✅" : "❌") . " Input type=\"file\"</p>";
    
    // Buscar URL del formulario
    preg_match('/form[^>]+action\s*=\s*["\']([^"\']+)["\']/', $contenido, $matches);
    $form_action = isset($matches[1]) ? $matches[1] : "No encontrada";
    echo "<p>URL de acción: $form_action</p>";
    
    // Verificar cómo se maneja el submit del formulario
    $usa_fetch = strpos($contenido, 'fetch(') !== false;
    echo "<p>" . ($usa_fetch ? "✅" : "❌") . " Usa fetch para envío AJAX</p>";
} else {
    echo "<p>❌ No se encontró el archivo editar.php</p>";
}

// 5. Verificación del controlador
echo "<h2>5. Verificación del controlador</h2>";

$controller_path = __DIR__ . '/app/Controllers/MiembrosController.php';
if (file_exists($controller_path)) {
    $contenido = file_get_contents($controller_path);
    
    // Verificar si el método actualizar procesa $_FILES
    $procesa_files = strpos($contenido, '$_FILES') !== false &&
                     preg_match('/function\s+actualizar[^{]*\{.*\$_FILES/s', $contenido);
                     
    $usa_procesarFoto = strpos($contenido, 'procesarFoto') !== false &&
                        preg_match('/function\s+actualizar[^{]*\{.*procesarFoto/s', $contenido);
    
    echo "<p>" . ($procesa_files ? "✅" : "❌") . " El método actualizar procesa \$_FILES</p>";
    echo "<p>" . ($usa_procesarFoto ? "✅" : "❌") . " El método actualizar llama a procesarFoto()</p>";
    
    // Verificar directorios de guardado
    preg_match('/\$directorio\s*=\s*[\'"](.+?)[\'"]/', $contenido, $matches);
    $directorio_usado = isset($matches[1]) ? $matches[1] : "No encontrado";
    echo "<p>Directorio usado: $directorio_usado</p>";
    
    // Extraer código del método procesarFoto para revisión
    preg_match('/private\s+function\s+procesarFoto\s*\([^{]*\{(.+?)(private|public|protected|\/\*\*)/s', $contenido, $matches);
    if (isset($matches[1])) {
        echo "<details>";
        echo "<summary>Ver código de procesarFoto()</summary>";
        echo "<pre>" . htmlspecialchars($matches[1]) . "</pre>";
        echo "</details>";
    }
} else {
    echo "<p>❌ No se encontró el archivo MiembrosController.php</p>";
}

// 6. Herramienta de carga manual para depuración directa
echo "<h2>6. Herramienta de carga manual</h2>";
echo "<p>Use esta herramienta para subir una foto directamente a la base de datos:</p>";

echo "<form action='" . $_SERVER['PHP_SELF'] . "' method='POST' enctype='multipart/form-data'>";
echo "<div><label>ID del miembro: <input type='number' name='miembro_id' required></label></div>";
echo "<div style='margin:10px 0;'><label>Foto: <input type='file' name='foto' accept='image/*' required></label></div>";
echo "<button type='submit' name='cargar_manual'>Cargar directamente</button>";
echo "</form>";

// Procesar carga manual
if (isset($_POST['cargar_manual']) && !empty($_FILES['foto']['name']) && !empty($_POST['miembro_id'])) {
    echo "<h3>Resultado de carga manual:</h3>";
    
    $miembro_id = (int)$_POST['miembro_id'];
    $directorio = __DIR__ . '/public/uploads/miembros/';
    
    if (!file_exists($directorio)) {
        mkdir($directorio, 0755, true);
    }
    
    $nombre_archivo = 'miembro_' . $miembro_id . '_' . time() . '_' . basename($_FILES['foto']['name']);
    $ruta_completa = $directorio . $nombre_archivo;
    
    if (move_uploaded_file($_FILES['foto']['tmp_name'], $ruta_completa)) {
        echo "<p>✅ Archivo subido correctamente a: $ruta_completa</p>";
        
        // Actualizar base de datos
        try {
            require_once 'app/config/Database.php';
            $db = \Database::getInstance()->getConnection();
            
            $stmt = $db->prepare("UPDATE InformacionGeneral SET foto = ? WHERE id = ?");
            $resultado = $stmt->execute([$nombre_archivo, $miembro_id]);
            
            if ($resultado) {
                echo "<p>✅ Base de datos actualizada correctamente</p>";
                echo "<p>Vista previa de la imagen:</p>";
                echo "<img src='/ENCASA_DATABASE/public/uploads/miembros/$nombre_archivo' style='max-width:300px;'>";
            } else {
                echo "<p>❌ Error al actualizar la base de datos</p>";
            }
        } catch (\Exception $e) {
            echo "<p>❌ Error de base de datos: " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p>❌ Error al mover el archivo</p>";
    }
}

// 7. Resumen y recomendaciones
echo "<h2>7. Recomendaciones</h2>";

echo "<h3>Corregir método 'actualizar' en MiembrosController.php</h3>";
echo "<pre style='background:#f5f5f5;padding:10px;'>";
echo htmlspecialchars('public function actualizar($id)
{
    try {
        // Código existente...
        
        // Procesar la foto si se subió una nueva
        if (!empty($_FILES["foto"]) && $_FILES["foto"]["error"] === UPLOAD_ERR_OK) {
            $foto = $this->procesarFoto($_FILES["foto"]);
            if ($foto) {
                $datosGenerales["foto"] = $foto;
                
                // Si había foto anterior, eliminarla
                if (!empty($miembro["foto"])) {
                    $rutaAnterior = "../public/uploads/miembros/" . $miembro["foto"];
                    if (file_exists($rutaAnterior)) {
                        unlink($rutaAnterior);
                    }
                }
            }
        }
        
        // Continuar con la actualización...
    }
}');
echo "</pre>";

// Funciones auxiliares
function return_bytes($val) {
    $val = trim($val);
    $last = strtolower($val[strlen($val)-1]);
    $val = (int) $val;
    switch($last) {
        case 'g': $val *= 1024;
        case 'm': $val *= 1024;
        case 'k': $val *= 1024;
    }
    return $val;
}

function format_bytes($bytes, $precision = 2) { 
    $units = ['B', 'KB', 'MB', 'GB', 'TB']; 
    $bytes = max($bytes, 0); 
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
    $pow = min($pow, count($units) - 1); 
    $bytes /= (1 << (10 * $pow)); 
    return round($bytes, $precision) . ' ' . $units[$pow]; 
}

function upload_error_message($code) {
    $messages = [
        UPLOAD_ERR_INI_SIZE => 'El archivo excede el tamaño permitido en php.ini',
        UPLOAD_ERR_FORM_SIZE => 'El archivo excede el tamaño permitido en el formulario',
        UPLOAD_ERR_PARTIAL => 'El archivo se subió parcialmente',
        UPLOAD_ERR_NO_FILE => 'No se seleccionó ningún archivo',
        UPLOAD_ERR_NO_TMP_DIR => 'Falta la carpeta temporal',
        UPLOAD_ERR_CANT_WRITE => 'Error al escribir el archivo',
        UPLOAD_ERR_EXTENSION => 'Una extensión de PHP detuvo la carga'
    ];
    
    return isset($messages[$code]) ? $messages[$code] : "Error desconocido";
}
?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }
table { border-collapse: collapse; margin-bottom: 20px; width: 100%; }
th { background: #f0f0f0; }
pre { background: #f5f5f5; padding: 10px; overflow-x: auto; }
h1 { color: #333; }
h2 { color: #555; border-bottom: 1px solid #ddd; padding-bottom: 5px; margin-top: 30px; }
</style>