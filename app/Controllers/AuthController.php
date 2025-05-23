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
        if ($user['estado'] !== 'Activo') {
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
            // Añadir campos obligatorios para la BD
            'miembro_id' => 1,  // Asegúrate de que este ID exista en InformacionGeneral
            'rol_id' => 5,      // Asegúrate de que este ID exista en Roles
            'estado' => 'Activo'
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
            return $this->view('auth/register', ['errors' => $errors]);
        }
        
        // Intentar registrar al usuario
        $userModel = new \App\Models\Usuario();
        $result = $userModel->register($data);
        
        if ($result) {
            // Registro exitoso
            $_SESSION['success_message'] = "Registro exitoso. Ahora puedes iniciar sesión.";
            header('Location: ' . APP_URL . '/login');
            exit;
        } else {
            // Error en el registro
            $errors[] = "No se pudo completar el registro. El usuario o correo ya existe.";
            return $this->view('auth/register', ['errors' => $errors]);
        }
    }
}