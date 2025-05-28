<?php
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Iglesia En Casa' ?></title>
    
    <!-- Solución temporal: Tailwind desde CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Tus estilos existentes -->
    <link rel="stylesheet" href="<?= APP_URL ?>/app/public/css/styles.css">
    
    <!-- Bootstrap CSS (opcional - puedes eliminarlo cuando migres completamente a Tailwind) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <?php if (isset($page_specific_css)): ?>
    <!-- CSS específico de página -->
    <link rel="stylesheet" href="<?= APP_URL ?>/css/pages/<?= $page_specific_css ?>.css">
    <?php endif; ?>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Contenido principal de la página se insertará aquí -->