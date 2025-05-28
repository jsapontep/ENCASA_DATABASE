<?php
// filepath: c:\xampp\htdocs\ENCASA_DATABASE\app\helpers\EmailService.php
namespace App\Helpers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailService {
    private $mailer;
    
    /**
     * Constructor: configura el servicio de email
     */
    public function __construct() {
        // Inicializar PHPMailer
        $this->mailer = new PHPMailer(true);
        
        // Cambiar esto:
        if (APP_ENV === 'development') {
            $this->mailer->SMTPDebug = 1; // 1 = errores y mensajes
        }
        
        // Por esto:
        if (APP_ENV === 'development') {
            $this->mailer->SMTPDebug = 0; // 0 = sin depuración
        }
        
        // Esto debería usarse solo en desarrollo
        $this->mailer->SMTPOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ];
        
        // Configurar servidor SMTP
        $this->mailer->isSMTP();
        $this->mailer->Host = SMTP_HOST;
        $this->mailer->SMTPAuth = SMTP_AUTH; // Usar la constante en lugar de hardcodear true
        $this->mailer->Username = SMTP_USER;
        $this->mailer->Password = SMTP_PASS;
        $this->mailer->SMTPSecure = SMTP_SECURE; // Usar la constante en lugar de hardcodear 'tls'
        $this->mailer->Port = SMTP_PORT;
        $this->mailer->CharSet = 'UTF-8';
        
        // Configuración del remitente (usar el mismo que SMTP_USER)
        $this->mailer->setFrom(SMTP_USER, MAIL_FROM_NAME);
    }
    
    /**
     * Envía un código de verificación por email
     * @param string $email Correo del destinatario
     * @param string $name Nombre del destinatario
     * @param string $code Código de verificación
     * @return bool True si se envió correctamente, false en caso contrario
     */
    public function sendVerificationCode($email, $name, $code) {
        try {
            $this->mailer->clearAddresses(); // Limpiar destinatarios previos
            $this->mailer->addAddress($email, $name);
            $this->mailer->isHTML(true);
            $this->mailer->Subject = 'Código de verificación - Iglesia En Casa';
            
            // Contenido del correo
            $body = "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                <h2 style='color: #3949ab;'>Iglesia En Casa - Verificación</h2>
                <p>Hola {$name},</p>
                <p>Tu código de verificación es:</p>
                <div style='background-color: #f5f5f5; padding: 15px; text-align: center; font-size: 24px; letter-spacing: 5px; font-weight: bold;'>
                    {$code}
                </div>
                <p>Este código expirará en 1 hora.</p>
                <p>Si no solicitaste este código, puedes ignorar este correo.</p>
                <p>Gracias,<br>Equipo de Iglesia En Casa</p>
            </div>";
            
            $this->mailer->Body = $body;
            $this->mailer->AltBody = "Tu código de verificación es: {$code}. Este código expirará en 1 hora.";
            
            $this->mailer->send();
            
            // Registrar el éxito si existe la función log_info
            if (function_exists('log_info')) {
                log_info("Código de verificación enviado a $email");
            }
            return true;
        } catch (Exception $e) {
            // Registrar error si existe la función log_error
            if (function_exists('log_error')) {
                log_error("Error al enviar correo de verificación: " . $this->mailer->ErrorInfo);
            }
            return false;
        }
    }
}