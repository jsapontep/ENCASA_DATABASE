<?php
// filepath: /Applications/XAMPP/xamppfiles/htdocs/Encasa_Database/app/controllers/AuthController.php
namespace App\Controllers;

class AuthController extends Controller {
    private $userModel;
    
    public function __construct() {
        parent::__construct();
        $this->userModel = new \App\Models\Usuario();
    }
    
    /**
     * Muestra el formulario de login
     */
    public function login() {
        // Si ya está autenticado, redirigir al dashboard
        if ($this->isAuthenticated()) {
            $this->redirect('');
            return;
        }
        
        $this->renderWithLayout('auth/login', 'auth', [
            'title' => 'Iniciar Sesión'
        ]);
    }
    
    /**
     * Procesa el intento de login
     */
    public function authenticate() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('login');
            return;
        }

        $email_or_username = trim($_POST['email_or_username'] ?? ''); // Añadido trim()
        $password = $_POST['password'] ?? '';

        if (empty($email_or_username) || empty($password)) {
            return $this->renderWithLayout('auth/login', 'auth', [
                'error' => 'Por favor ingrese su correo/usuario y contraseña',
                'title' => 'Iniciar Sesión'
            ]);
        }

        $userModel = new \App\Models\Usuario();
        $user = $userModel->findByEmailOrUsername($email_or_username);
        
        // Añadir logging para diagnóstico
        error_log("Intento de login para: $email_or_username");
        
        if (!$user) {
            error_log("Usuario no encontrado: $email_or_username");
            return $this->renderWithLayout('auth/login', 'auth', [
                'error' => 'Credenciales incorrectas [U]',
                'title' => 'Iniciar Sesión'
            ]);
        }
        
        // Verificar la contraseña
        $password_match = password_verify($password, $user['password']);
        error_log("Usuario encontrado. ID: {$user['id']}, Estado: {$user['estado']}, Password match: " . ($password_match ? "SI" : "NO"));
        
        if ($password_match) {
            // Si las credenciales son correctas, verificar el estado del usuario
            if (strtolower($user['estado']) != 'activo') {
                // Usuario no activo - Mostrar mensaje de verificación pendiente
                $_SESSION['pending_verification'] = [
                    'user_id' => $user['id'],
                    'email' => $user['email'],
                    'name' => $user['nombre_completo']
                ];
                
                $_SESSION['flash_message'] = 'Tu cuenta aún no ha sido verificada. Por favor revisa tu correo electrónico para el código de verificación.';
                $_SESSION['flash_type'] = 'warning';
                
                // Añadir un enlace para reenviar el código
                $_SESSION['flash_action'] = '<a href="'.APP_URL.'/auth/resendCode" class="btn btn-sm btn-primary mt-2">Reenviar código</a>';
                
                return $this->renderWithLayout('auth/login', 'auth', [
                    'error' => 'Tu cuenta aún no ha sido verificada.',
                    'title' => 'Iniciar Sesión'
                ]);
            }
            
            // Añadir verificación de 2FA
            if (defined('REQUIRE_2FA_LOGIN') && REQUIRE_2FA_LOGIN) {
                // Guardar información del usuario en sesión para el segundo paso
                $_SESSION['pending_2fa'] = [
                    'user_id' => $user['id'],
                    'email' => $user['email'],
                    'name' => $user['nombre_completo']
                ];
                
                // Generar código de verificación
                $verificationModel = new \App\Models\VerificationCode();
                $code = $verificationModel->createForUser($user['id'], 'login_verification');
                
                // Enviar código por correo
                $emailService = new \App\Helpers\EmailService();
                $emailService->sendVerificationCode($user['email'], $user['nombre_completo'], $code);
                
                // Redirigir a la página de verificación 2FA
                $this->redirect('auth/verify-login');
                return;
            }
            
            // Si no se requiere 2FA, continuar con el flujo normal de autenticación
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['nombre_completo'];
            $_SESSION['user_role'] = $user['rol_id'];
            
            // Redireccionar al panel adecuado
            $this->redirect(''); // Redirige a la página de inicio
            return;
        }
        
        // Si llegamos aquí, las credenciales son incorrectas
        return $this->renderWithLayout('auth/login', 'auth', [
            'error' => 'Credenciales incorrectas',
            'title' => 'Iniciar Sesión'
        ]);
    }
    
    /**
     * Cierra la sesión
     */
    public function logout() {
        // Destruir la sesión
        session_unset();
        session_destroy();
        
        // Redirigir a login
        $this->redirect('login');
    }
    
    /**
     * Muestra el formulario de registro
     */
    public function register() {
        // Si ya está autenticado, redirigir al dashboard
        if ($this->isAuthenticated()) {
            $this->redirect('');
            return;
        }
        
        $this->renderWithLayout('auth/register', 'auth', [
            'title' => 'Registro de Usuario'
        ]);
    }
    
    /**
     * Procesa el registro de usuario
     */
    public function store() {
        // Verificar que sea una petición POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            // Redirigir a la página de registro si no es POST
            header('Location: ' . APP_URL . '/registro');
            exit;
        }
        
        // Obtener datos del formulario
        $data = [
            'nombre_completo' => $_POST['nombre_completo'] ?? '',
            'username' => $_POST['username'] ?? '',
            'email' => $_POST['email'] ?? '',
            'password' => $_POST['password'] ?? '',
            'password_confirmation' => $_POST['password_confirmation'] ?? '',
            'miembro_id' => 1,
            'rol_id' => 5,
            // Cambiar esto:
            'estado' => defined('REQUIRE_EMAIL_VERIFICATION') && REQUIRE_EMAIL_VERIFICATION ? 'Pendiente' : 'Activo'
        ];
        
        // Validar datos
        $errors = [];
        
        if (empty($data['username'])) {
            $errors[] = "El nombre de usuario es obligatorio";
        }
        
        if (empty($data['email'])) {
            $errors[] = "El correo electrónico es obligatorio";
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "El formato del correo electrónico no es válido";
        }
        
        if (empty($data['password'])) {
            $errors[] = "La contraseña es obligatoria";
        } elseif (strlen($data['password']) < 6) {
            $errors[] = "La contraseña debe tener al menos 6 caracteres";
        } elseif ($data['password'] !== $data['password_confirmation']) {
            $errors[] = "Las contraseñas no coinciden";
        }
        
        // Si hay errores, mostrar formulario con errores
        if (!empty($errors)) {
            return $this->renderWithLayout('auth/register', 'auth', [
                'errors' => $errors,
                'title' => 'Registro de Usuario',
                'data' => $data // Para mantener los datos ingresados
            ]);
        }
        
        // Intentar registrar al usuario
        error_log('Estado del usuario al registrar: ' . $data['estado']);

        $userModel = new \App\Models\Usuario();
        $result = $userModel->register($data);
        
        if ($result) {
            $userId = $result;
            
            // Verificar si se requiere verificación por correo
            if (defined('REQUIRE_EMAIL_VERIFICATION') && REQUIRE_EMAIL_VERIFICATION) {
                $userModel->update($userId, ['estado' => 'Pendiente']);
                
                // Generar código de verificación
                $verificationModel = new \App\Models\VerificationCode();
                $code = $verificationModel->createForUser($userId);
                
                // Guardar datos en sesión para la verificación
                $_SESSION['pending_verification'] = [
                    'user_id' => $userId,
                    'email' => $data['email'],  // Asegúrate de que esta variable existe en tu contexto
                    'name' => $data['nombre_completo']   // Asegúrate de que esta variable existe en tu contexto
                ];
                
                // Enviar código por correo
                $emailService = new \App\Helpers\EmailService();
                $emailService->sendVerificationCode($data['email'], $data['nombre_completo'], $code);
                
                // Redirigir a la página de verificación
                $this->redirect('auth/verify');
                return;
            } else {
                // Si no se requiere verificación, continuar con el flujo normal
                $_SESSION['flash_message'] = 'Registro exitoso. Ya puedes iniciar sesión.';
                $_SESSION['flash_type'] = 'success';
                $this->redirect('login');
                return;
            }
        } else {
            // Error en el registro
            $errors[] = "No se pudo completar el registro. El usuario o correo ya existe.";
            return $this->renderWithLayout('auth/register', 'auth', [
                'errors' => $errors,
                'title' => 'Registro de Usuario',
                'data' => $data // Para mantener los datos ingresados
            ]);
        }
    }
    
    /**
     * Muestra y procesa la verificación del código
     */
    public function verify() {
        // Si no hay verificación pendiente, redirigir a login
        if (!isset($_SESSION['pending_verification'])) {
            $this->redirect('login');
            return;
        }
        
        // Si es POST, verificar el código
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $code = trim($_POST['code'] ?? '');
            $userId = $_SESSION['pending_verification']['user_id'];
            
            if (empty($code)) {
                return $this->renderWithLayout('auth/verify', 'auth', [
                    'error' => 'Ingrese el código de verificación',
                    'email' => $_SESSION['pending_verification']['email'],
                    'title' => 'Verificación de Cuenta'
                ]);
            }
            
            // Verificar el código
            $verificationModel = new \App\Models\VerificationCode();
            $isValid = $verificationModel->verifyCode($userId, $code);
            
            if ($isValid) {
                // Activar el usuario
                $userModel = new \App\Models\Usuario();
                $userModel->update($userId, ['estado' => 'Activo']);
                
                // Limpiar datos de verificación pendiente
                unset($_SESSION['pending_verification']);
                
                // Mensaje de éxito
                $_SESSION['flash_message'] = 'Cuenta verificada correctamente. Ya puedes iniciar sesión.';
                $_SESSION['flash_type'] = 'success';
                
                $this->redirect('login');
                return;
            } else {
                return $this->renderWithLayout('auth/verify', 'auth', [
                    'error' => 'Código de verificación inválido o expirado',
                    'email' => $_SESSION['pending_verification']['email'],
                    'title' => 'Verificación de Cuenta'
                ]);
            }
        }
        
        // Mostrar formulario de verificación
        return $this->renderWithLayout('auth/verify', 'auth', [
            'email' => $_SESSION['pending_verification']['email'],
            'title' => 'Verificación de Cuenta'
        ]);
    }
    
    /**
     * Reenvía el código de verificación
     */
    public function resendCode() {
        if (!isset($_SESSION['pending_verification'])) {
            $this->redirect('login');
            return;
        }
        
        $userId = $_SESSION['pending_verification']['user_id'];
        $email = $_SESSION['pending_verification']['email'];
        $name = $_SESSION['pending_verification']['name'];
        
        // Generar nuevo código
        $verificationModel = new \App\Models\VerificationCode();
        $code = $verificationModel->createForUser($userId);
        
        // Enviar código
        $emailService = new \App\Helpers\EmailService();
        $sent = $emailService->sendVerificationCode($email, $name, $code);
        
        if ($sent) {
            $_SESSION['flash_message'] = 'Código de verificación reenviado correctamente.';
            $_SESSION['flash_type'] = 'success';
        } else {
            $_SESSION['flash_message'] = 'Error al enviar el código. Inténtelo de nuevo.';
            $_SESSION['flash_type'] = 'danger';
        }
        
        $this->redirect('auth/verify');
    }
    
    /**
     * Muestra y procesa la verificación del código de 2FA
     */
    public function verifyLogin() {
        // Si no hay verificación 2FA pendiente, redirigir a login
        if (!isset($_SESSION['pending_2fa'])) {
            $this->redirect('login');
            return;
        }
        
        // Si es POST, verificar el código
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $code = trim($_POST['code'] ?? '');
            $userId = $_SESSION['pending_2fa']['user_id'];
            
            if (empty($code)) {
                return $this->renderWithLayout('auth/verify-login', 'auth', [
                    'error' => 'Ingrese el código de verificación',
                    'email' => $_SESSION['pending_2fa']['email'],
                    'title' => 'Verificación de Inicio de Sesión'
                ]);
            }
            
            // Verificar el código
            $verificationModel = new \App\Models\VerificationCode();
            $isValid = $verificationModel->verifyCode($userId, $code, 'login_verification');
            
            if ($isValid) {
                // Recuperar datos del usuario y establecer sesión
                $userModel = new \App\Models\Usuario();
                $user = $userModel->findById($userId);
                
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['nombre_completo'];
                $_SESSION['user_role'] = $user['rol_id'];
                
                // Actualizar último acceso
                $userModel->update($user['id'], [
                    'ultimo_acceso' => date('Y-m-d H:i:s')
                ]);
                
                // Limpiar datos de verificación pendiente
                unset($_SESSION['pending_2fa']);
                
                // Mensaje de éxito
                $_SESSION['flash_message'] = 'Inicio de sesión completado correctamente.';
                $_SESSION['flash_type'] = 'success';
                
                $this->redirect('');
                return;
            } else {
                return $this->renderWithLayout('auth/verify-login', 'auth', [
                    'error' => 'Código de verificación inválido o expirado',
                    'email' => $_SESSION['pending_2fa']['email'],
                    'title' => 'Verificación de Inicio de Sesión'
                ]);
            }
        }
        
        // Mostrar formulario de verificación
        return $this->renderWithLayout('auth/verify-login', 'auth', [
            'email' => $_SESSION['pending_2fa']['email'],
            'title' => 'Verificación de Inicio de Sesión'
        ]);
    }
    
    /**
     * Reenvía el código de verificación para 2FA
     */
    public function resendLoginCode() {
        if (!isset($_SESSION['pending_2fa'])) {
            $this->redirect('login');
            return;
        }
        
        $userId = $_SESSION['pending_2fa']['user_id'];
        $email = $_SESSION['pending_2fa']['email'];
        $name = $_SESSION['pending_2fa']['name'];
        
        // Generar nuevo código
        $verificationModel = new \App\Models\VerificationCode();
        $code = $verificationModel->createForUser($userId, 'login_verification');
        
        // Enviar código
        $emailService = new \App\Helpers\EmailService();
        $sent = $emailService->sendVerificationCode($email, $name, $code);
        
        if ($sent) {
            $_SESSION['flash_message'] = 'Código de verificación reenviado correctamente.';
            $_SESSION['flash_type'] = 'success';
        } else {
            $_SESSION['flash_message'] = 'Error al enviar el código. Inténtelo de nuevo.';
            $_SESSION['flash_type'] = 'danger';
        }
        
        $this->redirect('auth/verify-login');
    }
}