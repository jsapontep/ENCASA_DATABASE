<?php
// filepath: c:\xampp\htdocs\ENCASA_DATABASE\app\config\app.php

// Añadir al final del archivo o donde sea apropiado
// Asegúrate de que se genere un token CSRF en cada carga de página
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}