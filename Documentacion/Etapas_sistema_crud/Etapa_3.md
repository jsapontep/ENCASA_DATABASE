# Etapa 3: Autenticación y Gestión de Usuarios

Ahora que tenemos la estructura MVC y el sistema de enrutamiento funcionando correctamente, continuemos con la Etapa 3 que implementará la autenticación de usuarios y sus funcionalidades relacionadas.

## Paso 1: Crear la documentación de la Etapa 3

Primero, vamos a crear el archivo de documentación:

```markdown
# Etapa 3: Autenticación y Gestión de Usuarios

Esta etapa implementa el sistema de autenticación, autorización y gestión de usuarios para controlar el acceso a las diferentes funcionalidades de la aplicación.

## Componentes a implementar:

1. **Sistema de autenticación**
   - Login y logout
   - Registro de usuarios
   - Recuperación de contraseña
   - Protección contra ataques comunes

2. **Middleware de autorización**
   - Control de acceso basado en roles
   - Protección de rutas
   - Verificación de permisos

3. **Gestión de usuarios**
   - CRUD completo de usuarios
   - Asignación de roles
   - Activación/desactivación de cuentas

4. **Perfiles de usuario**
   - Información personal
   - Cambio de contraseña
   - Preferencias del usuario
```

## Paso 2: Implementación del Sistema de Autenticación

### 2.1 Modelo de Usuario

```php
<?php
// filepath: /Applications/XAMPP/xamppfiles/htdocs/Encasa_Database/app/models/Usuario.php
namespace App\Models;

class Usuario extends Model {
    protected $table = 'Usuarios';
    protected $fillable = ['username', 'email', 'password', 'nombre_completo', 'ultimo_login', 'activo'];
    protected $guarded = ['id', 'created_at', 'updated_at'];
    
    /**
     * Encuentra un usuario por su nombre de usuario
     */
    public function findByUsername($username) {
        return $this->findOneWhere('username', $username);
    }
    
    /**
     * Encuentra un usuario por su correo electrónico
     */
    public function findByEmail($email) {
        return $this->findOneWhere('email', $email);
    }
    
    /**
     * Autentica un usuario por su nombre de usuario y contraseña
     */
    public function authenticate($username, $password) {
        $user = $this->findByUsername($username);
        
        if (!$user) {
            // Intentar con email
            $user = $this->findByEmail($username);
        }
        
        if (!$user || !$this->verifyPassword($password, $user['password'])) {
            return false;
        }
        
        // Actualizar último login
        $this->update($user['id'], [
            'ultimo_login' => date('Y-m-d H:i:s')
        ]);
        
        return $user;
    }
    
    /**
     * Hashea una contraseña
     */
    public function hashPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }
    
    /**
     * Verifica si una contraseña coincide con su hash
     */
    public function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }
    
    /**
     * Crea un nuevo usuario con contraseña hasheada
     */
    public function register($data) {
        // Verificar si el usuario o email ya existe
        if ($this->findByUsername($data['username']) || $this->findByEmail($data['email'])) {
            return false;
        }
        
        // Hashear contraseña
        $data['password'] = $this->hashPassword($data['password']);
        
        // Establecer estado activo por defecto
        if (!isset($data['activo'])) {
            $data['activo'] = 1;
        }
        
        return $this->create($data);
    }
    
    /**
     * Obtiene los roles del usuario
     */
    public function getRoles($userId) {
        $sql = "SELECT r.* FROM RolesUsuario ru
                JOIN Roles r ON ru.rol_id = r.id
                WHERE ru.usuario_id = :user_id";
                
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $userId);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    /**
     * Verifica si un usuario tiene un rol específico
     */
    public function hasRole($userId, $roleName) {
        $sql = "SELECT COUNT(*) as count FROM RolesUsuario ru
                JOIN Roles r ON ru.rol_id = r.id
                WHERE ru.usuario_id = :user_id AND r.nombre = :role_name";
                
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $userId);
        $stmt->bindValue(':role_name', $roleName);
        $stmt->execute();
        
        $result = $stmt->fetch();
        return (int) $result['count'] > 0;
    }
}
```

### 2.2 Controlador de Autenticación

```php
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
        
        // Obtener y guardar roles
        $roles = $this->userModel->getRoles($user['id']);
        $_SESSION['roles'] = array_column($roles, 'nombre');
        
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
            'password' => $_POST['password'],
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
```

## Paso 3: Implementación de Middleware para Autenticación

### 3.1 Middleware Base

```php
<?php
// filepath: /Applications/XAMPP/xamppfiles/htdocs/Encasa_Database/app/middleware/Middleware.php
namespace App\Middleware;

abstract class Middleware {
    /**
     * Método que se debe implementar en cada middleware
     */
    abstract public function handle();
}
```

### 3.2 Middleware de Autenticación

```php
<?php
// filepath: /Applications/XAMPP/xamppfiles/htdocs/Encasa_Database/app/middleware/Auth.php
namespace App\Middleware;

class Auth extends Middleware {
    public function handle() {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['flash_message'] = 'Debes iniciar sesión para acceder';
            $_SESSION['flash_type'] = 'warning';
            
            // Guardar URL intentada para redirección después del login
            $_SESSION['intended_url'] = $_SERVER['REQUEST_URI'];
            
            header('Location: ' . APP_URL . '/login');
            exit;
        }
        
        return true;
    }
}
```

### 3.3 Middleware de Administrador

```php
<?php
// filepath: /Applications/XAMPP/xamppfiles/htdocs/Encasa_Database/app/middleware/AdminOnly.php
namespace App\Middleware;

class AdminOnly extends Middleware {
    public function handle() {
        if (!isset($_SESSION['roles']) || !in_array('Admin', $_SESSION['roles'])) {
            $_SESSION['flash_message'] = 'No tienes permiso para acceder a esta área';
            $_SESSION['flash_type'] = 'danger';
            
            header('Location: ' . APP_URL);
            exit;
        }
        
        return true;
    }
}
```

## Paso 4: Vistas para Autenticación

### 4.1 Layout de Autenticación

```php
<?php
// filepath: /Applications/XAMPP/xamppfiles/htdocs/Encasa_Database/app/views/layouts/auth.php
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title . ' - ' . APP_NAME : APP_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="text-center mb-4">
                    <h1><?= APP_NAME ?></h1>
                    <p class="text-muted">Sistema de gestión de información</p>
                </div>
                
                <?php if (isset($_SESSION['flash_message'])): ?>
                    <div class="alert alert-<?= $_SESSION['flash_type'] ?? 'info' ?> alert-dismissible fade show">
                        <?= $_SESSION['flash_message'] ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['flash_message'], $_SESSION['flash_type']); ?>
                <?php endif; ?>
                
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <?= $content ?>
                    </div>
                </div>
                
                <div class="text-center mt-4">
                    <p class="text-muted">&copy; <?= date('Y') ?> - <?= APP_NAME ?></p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
```

### 4.2 Vista de Login

```php
<?php
// filepath: /Applications/XAMPP/xamppfiles/htdocs/Encasa_Database/app/views/auth/login.php
?>
<h2 class="text-center mb-4">Iniciar Sesión</h2>

<form action="<?= APP_URL ?>/auth/login" method="post">
    <div class="mb-3">
        <label for="username" class="form-label">Usuario o Email</label>
        <input type="text" class="form-control" id="username" name="username" required autofocus>
    </div>
    
    <div class="mb-3">
        <label for="password" class="form-label">Contraseña</label>
        <input type="password" class="form-control" id="password" name="password" required>
    </div>
    
    <div class="d-grid gap-2">
        <button type="submit" class="btn btn-primary btn-lg">Iniciar Sesión</button>
    </div>
</form>

<div class="text-center mt-3">
    <a href="<?= APP_URL ?>/registro">¿No tienes cuenta? Regístrate</a>
</div>
```

### 4.3 Vista de Registro

```php
<?php
// filepath: /Applications/XAMPP/xamppfiles/htdocs/Encasa_Database/app/views/auth/register.php
?>
<h2 class="text-center mb-4">Registro de Usuario</h2>

<form action="<?= APP_URL ?>/auth/registro" method="post">
    <div class="mb-3">
        <label for="nombre_completo" class="form-label">Nombre Completo</label>
        <input type="text" class="form-control" id="nombre_completo" name="nombre_completo" required>
    </div>
    
    <div class="mb-3">
        <label for="username" class="form-label">Nombre de Usuario</label>
        <input type="text" class="form-control" id="username" name="username" required>
        <div class="form-text">Mínimo 4 caracteres, sin espacios</div>
    </div>
    
    <div class="mb-3">
        <label for="email" class="form-label">Correo Electrónico</label>
        <input type="email" class="form-control" id="email" name="email" required>
    </div>
    
    <div class="mb-3">
        <label for="password" class="form-label">Contraseña</label>
        <input type="password" class="form-control" id="password" name="password" required>
        <div class="form-text">Mínimo 6 caracteres</div>
    </div>
    
    <div class="mb-3">
        <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
    </div>
    
    <div class="d-grid gap-2">
        <button type="submit" class="btn btn-primary btn-lg">Registrarse</button>
    </div>
</form>

<div class="text-center mt-3">
    <a href="<?= APP_URL ?>/login">¿Ya tienes cuenta? Iniciar sesión</a>
</div>
```

## Paso 5: Actualizaciones para completar la implementación

### 5.1 Actualiza el `routes.php` si es necesario

Asegúrate de que las rutas para autenticación ya están definidas correctamente:

```php
// Rutas de autenticación
$router->get('login', 'Auth', 'login');
$router->post('auth/login', 'Auth', 'authenticate');
$router->get('logout', 'Auth', 'logout');
$router->get('registro', 'Auth', 'register');
$router->post('auth/registro', 'Auth', 'store');
```

### 5.2 Actualiza el index.php para asegurarte que las sesiones estén iniciadas

```php
// Configuración de sesiones
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.use_strict_mode', 1);
if (APP_ENV === 'production') {
    ini_set('session.cookie_secure', 1);
}

// Iniciar sesión
session_start();
```

¿Quieres que te ayude a implementar alguna parte específica de esta Etapa 3?

Código similar encontrado con 4 tipos de licencias