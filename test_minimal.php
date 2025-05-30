<?php
// filepath: c:\xampp\htdocs\ENCASA_DATABASE\test_minimal.php

// NO incluir nada más, sin autoload, sin configuraciones
// Mostrar información básica del servidor
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Mínimo</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .info { background: #f0f0f0; padding: 20px; border-radius: 5px; }
    </style>
</head>
<body>
    <h1>Prueba mínima - Sin redirecciones</h1>
    <div class="info">
        <p><strong>Servidor:</strong> <?php echo $_SERVER['SERVER_NAME']; ?></p>
        <p><strong>URL actual:</strong> <?php echo $_SERVER['REQUEST_URI']; ?></p>
        <p><strong>Protocolo:</strong> <?php echo isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'HTTPS' : 'HTTP'; ?></p>
        <p><strong>Hora del servidor:</strong> <?php echo date('H:i:s'); ?></p>
    </div>
    
    <h2>Formulario de prueba</h2>
    <form action="test_process.php" method="post">
        <div>
            <label>Nombre:</label>
            <input type="text" name="nombre" value="Test">
        </div>
        <div style="margin-top: 10px;">
            <button type="submit">Enviar prueba</button>
        </div>
    </form>
</body>
</html>