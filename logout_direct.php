<?php
// filepath: c:\xampp\htdocs\ENCASA_DATABASE\logout_direct.php

// Iniciar sesión
session_start();

// Destruir la sesión
$_SESSION = array();
session_destroy();

// Redirigir a la página de login directo
header("Location: direct_access.php");
exit;