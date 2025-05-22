<?php
// filepath: /Applications/XAMPP/xamppfiles/htdocs/Encasa_Database/app/views/errors/404.php
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Página no encontrada | <?= APP_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6 text-center">
                <h1 class="display-1">404</h1>
                <p class="lead">Página no encontrada</p>
                <p>La página que estás buscando no existe o ha sido movida.</p>
                <a href="<?= APP_URL ?>" class="btn btn-primary">Volver al inicio</a>
            </div>
        </div>
    </div>
</body>
</html>