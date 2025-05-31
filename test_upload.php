<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h1>Prueba de subida de archivos</h1>";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar que se subió un archivo
    if (empty($_FILES['test_file']['name'])) {
        echo "<p style='color:red'>No se seleccionó ningún archivo.</p>";
        echo "<a href='verificar_directorios.php'>Volver</a>";
        exit;
    }
    
    // Información del archivo
    echo "<h2>Información del archivo subido:</h2>";
    echo "<pre>";
    print_r($_FILES['test_file']);
    echo "</pre>";
    
    // Directorio de destino
    $uploadDir = __DIR__ . '/uploads/miembros/';
    $uploadFile = $uploadDir . basename($_FILES['test_file']['name']);
    
    echo "<p>Intentando guardar en: <code>$uploadFile</code></p>";
    
    // Intentar guardar el archivo
    if (move_uploaded_file($_FILES['test_file']['tmp_name'], $uploadFile)) {
        echo "<p style='color:green'>✓ Archivo subido correctamente.</p>";
        echo "<p><img src='/ENCASA_DATABASE/uploads/miembros/" . htmlspecialchars(basename($_FILES['test_file']['name'])) . "' style='max-height:200px;'></p>";
    } else {
        echo "<p style='color:red'>❌ Error al subir el archivo.</p>";
        
        // Información de error
        $errorCode = $_FILES['test_file']['error'];
        $errorMessages = [
            UPLOAD_ERR_INI_SIZE => 'El archivo excede el tamaño máximo permitido en php.ini',
            UPLOAD_ERR_FORM_SIZE => 'El archivo excede el tamaño máximo permitido en el formulario',
            UPLOAD_ERR_PARTIAL => 'El archivo se subió parcialmente',
            UPLOAD_ERR_NO_FILE => 'No se subió ningún archivo',
            UPLOAD_ERR_NO_TMP_DIR => 'Falta la carpeta temporal',
            UPLOAD_ERR_CANT_WRITE => 'No se pudo escribir en el disco',
            UPLOAD_ERR_EXTENSION => 'Una extensión de PHP detuvo la subida'
        ];
        
        if (isset($errorMessages[$errorCode])) {
            echo "<p>Error específico: " . $errorMessages[$errorCode] . "</p>";
        }
        
        // Verificar permisos
        echo "<p>Permisos del directorio: " . substr(sprintf('%o', fileperms($uploadDir)), -4) . "</p>";
    }
    
    echo "<p><a href='verificar_directorios.php'>Volver</a></p>";
} else {
    echo "<p>No se recibieron datos de formulario.</p>";
    echo "<p><a href='verificar_directorios.php'>Volver</a></p>";
}
?>