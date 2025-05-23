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
        // Validar el formulario
        $validation = $this->validate($_POST, [
            'username' => ['required'],
            'password' => ['required']
        ]);
        
        if (!$validation) {
            $_SESSION['flash_message'] = 'Por favor completa todos los campos';
            $_SESSION['flash_type'] = 'danger';
            $this->redirect('login');
            return;
        }
        
        $username = trim($_POST['username']);
        $password = $_POST['password'];
        
        // Intentar autenticar
        $user = $this->userModel->authenticate($username, $password);
        
        if (!$user) {
            $_SESSION['flash_message'] = 'Credenciales incorrectas';
            $_SESSION['flash_type'] = 'danger';
            $this->redirect('login');
            return;
        }
        
        // Verificar si el usuario está activo
        if (!$user['activo']) {
            $_SESSION['flash_message'] = 'Tu cuenta está desactivada';
            $_SESSION['flash_type'] = 'warning';
            $this->redirect('login');
            return;
        }
        
        // Iniciar sesión
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['nombre_completo'] = $user['nombre_completo'];
        
        $_SESSION['flash_message'] = 'Has iniciado sesión correctamente';
        $_SESSION['flash_type'] = 'success';
        
        // Redirigir al dashboard
        $this->redirect('');
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
        // Validar el formulario
        $validation = $this->validate($_POST, [
            'username' => ['required', 'min:4'],
            'email' => ['required', 'email'],
            'password' => ['required', 'min:6'],
            'password_confirmation' => ['required']
        ]);
        
        if (!$validation) {
            $_SESSION['flash_message'] = 'Por favor verifica los campos del formulario';
            $_SESSION['flash_type'] = 'danger';
            $this->redirect('registro');
            return;
        }
        
        // Verificar que las contraseñas coinciden
        if ($_POST['password'] !== $_POST['password_confirmation']) {
            $_SESSION['flash_message'] = 'Las contraseñas no coinciden';
            $_SESSION['flash_type'] = 'danger';
            $this->redirect('registro');
            return;
        }
        
        // Preparar datos para registro
        $userData = [
            'username' => trim($_POST['username']),
            'email' => trim($_POST['email']),
            'password' => $this->userModel->hashPassword($_POST['password']),
            'nombre_completo' => trim($_POST['nombre_completo'] ?? ''),
            'activo' => 1
        ];
        
        // Intentar registrar
        $userId = $this->userModel->register($userData);
        
        if (!$userId) {
            $_SESSION['flash_message'] = 'No se pudo crear el usuario. Puede que el nombre de usuario o email ya existan';
            $_SESSION['flash_type'] = 'danger';
            $this->redirect('registro');
            return;
        }
        
        $_SESSION['flash_message'] = 'Usuario registrado correctamente. Ahora puedes iniciar sesión';
        $_SESSION['flash_type'] = 'success';
        $this->redirect('login');
    }
}