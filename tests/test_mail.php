<?php
// filepath: c:\xampp\htdocs\ENCASA_DATABASE\test_mail.php
require_once 'index.php';

// Crear instancia del servicio de email
$emailService = new \App\Helpers\EmailService();

// Intentar enviar un correo de prueba
$result = $emailService->sendVerificationCode(
    'rafa.gzfr@gmail.com', // Reemplaza con tu correo
    'Usuario de Prueba', 
    '123456'
);

echo $result ? '✅ Correo enviado correctamente' : '❌ Error al enviar el correo';