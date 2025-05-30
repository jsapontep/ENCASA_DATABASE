<?php
// filepath: c:\xampp\htdocs\ENCASA_DATABASE\test_process.php

// NO incluir nada más, sin autoload, sin configuraciones
// Solo mostrar los datos recibidos
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Procesador de prueba</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .info { background: #f0f0f0; padding: 20px; border-radius: 5px; }
    </style>
</head>
<body>
    <h1>Datos recibidos</h1>
    <div class="info">
        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
            <p><strong>Nombre enviado:</strong> <?php echo htmlspecialchars($_POST['nombre'] ?? 'No enviado'); ?></p>
        <?php else: ?>
            <p>No se recibieron datos POST.</p>
        <?php endif; ?>
        
        <p><strong>Servidor:</strong> <?php echo $_SERVER['SERVER_NAME']; ?></p>
        <p><strong>URL actual:</strong> <?php echo $_SERVER['REQUEST_URI']; ?></p>
        <p><strong>Protocolo:</strong> <?php echo isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'HTTPS' : 'HTTP'; ?></p>
    </div>
    
    <p><a href="test_minimal.php">Volver a la prueba</a></p>
</body>
</html>