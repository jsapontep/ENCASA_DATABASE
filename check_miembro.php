<?php
// filepath: c:\xampp\htdocs\ENCASA_DATABASE\check_miembro.php

require_once __DIR__ . '/app/config/config.php';
require_once __DIR__ . '/app/config/database.php';
require_once __DIR__ . '/app/models/Model.php';
require_once __DIR__ . '/app/models/Miembro.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 2;
$model = new \App\Models\Miembro();
$miembro = $model->getFullProfile($id);

echo "<h1>Verificación del modelo con ID: {$id}</h1>";
echo "<pre>";
print_r($miembro);
echo "</pre>";
echo "<p><a href='miembros/{$id}'>Ver página de perfil</a></p>";