<?php
// filepath: c:\xampp\htdocs\ENCASA_DATABASE\test_upload.php

// Script para probar la carga de imágenes
require_once __DIR__ . '/app/config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['test_image'])) {
    // Directorio de destino
    $uploadDir = __DIR__ . '/public/uploads/miembros/';
    
    // Asegurarse de que el directorio existe
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    // Generar nombre aleatorio
    $fileName = uniqid() . '_' . $_FILES['test_image']['name'];
    $targetFile = $uploadDir . $fileName;
    
    // Intentar mover el archivo
    if (move_uploaded_file($_FILES['test_image']['tmp_name'], $targetFile)) {
        $message = "✅ Archivo subido exitosamente a: $targetFile";
        $imageUrl = APP_URL . '/public/uploads/miembros/' . $fileName;
    } else {
        $message = "❌ Error al subir el archivo";
        $imageUrl = null;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Prueba de Carga de Imágenes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Prueba de Carga de Imágenes</h1>
        
        <?php if (isset($message)): ?>
            <div class="alert <?= strpos($message, '✅') !== false ? 'alert-success' : 'alert-danger' ?>">
                <?= $message ?>
            </div>
            
            <?php if ($imageUrl): ?>
                <div class="card mb-4">
                    <div class="card-header">Imagen Cargada</div>
                    <div class="card-body text-center">
                        <img src="<?= $imageUrl ?>" class="img-fluid" style="max-height: 300px;">
                    </div>
                </div>
                
                <div class="card mb-4">
                    <div class="card-header">URLs de prueba</div>
                    <div class="card-body">
                        <p>URL 1: <a href="<?= $imageUrl ?>" target="_blank"><?= $imageUrl ?></a></p>
                        <p>URL 2: <a href="<?= str_replace('/public', '', $imageUrl) ?>" target="_blank"><?= str_replace('/public', '', $imageUrl) ?></a></p>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>
        
        <div class="card">
            <div class="card-header">Formulario de Prueba</div>
            <div class="card-body">
                <form method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="test_image" class="form-label">Seleccionar Imagen</label>
                        <input type="file" class="form-control" id="test_image" name="test_image" accept="image/*" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Subir Imagen</button>
                </form>
            </div>
        </div>
        
        <div class="mt-4">
            <h3>Información del Sistema</h3>
            <ul>
                <li>BASE_PATH: <?= BASE_PATH ?></li>
                <li>APP_URL: <?= APP_URL ?></li>
                <li>Directorio de subida: <?= __DIR__ . '/public/uploads/miembros/' ?></li>
                <li>Permisos: <?= substr(sprintf('%o', fileperms(__DIR__ . '/public')), -4) ?></li>
            </ul>
        </div>
    </div>
</body>
</html>