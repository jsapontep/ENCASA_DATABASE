# Plan de Implementación del Sistema CRUD - Iglesia En Casa

## 1. Preparación del Entorno

Primero, vamos a configurar la estructura de carpetas y archivos base según la arquitectura definida:

```bash
mkdir -p app/{config,core,controllers,models,views/{layouts,miembros,ministerios,tareas,auth}}
mkdir -p public/{assets/{css,js,img},uploads/photos}
```

## 2. Creación de Nuevas Tablas

Primero, implementemos las nuevas tablas necesarias para completar nuestro sistema:

```sql
-- Tabla de roles
CREATE TABLE `Roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL COMMENT 'Pastor, Líder, etc',
  `descripcion` text DEFAULT NULL COMMENT 'Descripción detallada',
  `nivel_acceso` int(11) NOT NULL COMMENT 'Nivel jerárquico: 1-5',
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabla de ministerios
CREATE TABLE `Ministerios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL COMMENT 'Nombre del ministerio',
  `descripcion` text DEFAULT NULL COMMENT 'Descripción del ministerio',
  `lider_id` int(11) DEFAULT NULL COMMENT 'ID del miembro líder',
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado` varchar(20) DEFAULT 'Activo' COMMENT 'Estado del ministerio',
  PRIMARY KEY (`id`),
  KEY `lider_id` (`lider_id`),
  CONSTRAINT `ministerios_ibfk_1` FOREIGN KEY (`lider_id`) REFERENCES `InformacionGeneral` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabla de relación miembros-ministerios
CREATE TABLE `MiembrosMinisterios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `miembro_id` int(11) NOT NULL,
  `ministerio_id` int(11) NOT NULL,
  `rol_id` int(11) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date DEFAULT NULL COMMENT 'Si está vacío, sigue activo',
  PRIMARY KEY (`id`),
  KEY `miembro_id` (`miembro_id`),
  KEY `ministerio_id` (`ministerio_id`),
  KEY `rol_id` (`rol_id`),
  CONSTRAINT `miembrosministerios_ibfk_1` FOREIGN KEY (`miembro_id`) REFERENCES `InformacionGeneral` (`id`) ON DELETE CASCADE,
  CONSTRAINT `miembrosministerios_ibfk_2` FOREIGN KEY (`ministerio_id`) REFERENCES `Ministerios` (`id`) ON DELETE CASCADE,
  CONSTRAINT `miembrosministerios_ibfk_3` FOREIGN KEY (`rol_id`) REFERENCES `Roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabla de tareas
CREATE TABLE `Tareas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ministerio_id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_limite` date DEFAULT NULL,
  `estado` varchar(20) DEFAULT 'Pendiente',
  `creador_id` int(11) DEFAULT NULL COMMENT 'Miembro que creó la tarea',
  PRIMARY KEY (`id`),
  KEY `ministerio_id` (`ministerio_id`),
  KEY `creador_id` (`creador_id`),
  CONSTRAINT `tareas_ibfk_1` FOREIGN KEY (`ministerio_id`) REFERENCES `Ministerios` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tareas_ibfk_2` FOREIGN KEY (`creador_id`) REFERENCES `InformacionGeneral` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabla de asignación de tareas
CREATE TABLE `AsignacionTareas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tarea_id` int(11) NOT NULL,
  `miembro_id` int(11) NOT NULL,
  `fecha_asignacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_completada` timestamp NULL DEFAULT NULL,
  `comentarios` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tarea_id` (`tarea_id`),
  KEY `miembro_id` (`miembro_id`),
  CONSTRAINT `asignaciontareas_ibfk_1` FOREIGN KEY (`tarea_id`) REFERENCES `Tareas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `asignaciontareas_ibfk_2` FOREIGN KEY (`miembro_id`) REFERENCES `InformacionGeneral` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabla de usuarios
CREATE TABLE `Usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `miembro_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL COMMENT 'Contraseña hasheada',
  `rol_id` int(11) NOT NULL,
  `ultimo_acceso` timestamp NULL DEFAULT NULL,
  `estado` varchar(20) DEFAULT 'Activo' COMMENT 'Activo, Bloqueado, etc.',
  `intentos_fallidos` int(11) DEFAULT 0,
  `token_reset` varchar(100) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `miembro_id` (`miembro_id`),
  KEY `rol_id` (`rol_id`),
  CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`miembro_id`) REFERENCES `InformacionGeneral` (`id`) ON DELETE CASCADE,
  CONSTRAINT `usuarios_ibfk_2` FOREIGN KEY (`rol_id`) REFERENCES `Roles` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insertar roles básicos
INSERT INTO `Roles` (`nombre`, `descripcion`, `nivel_acceso`) VALUES
('Pastor', 'Acceso completo al sistema', 5),
('Copastor', 'Acceso completo al sistema', 5),
('Líder de Ministerio', 'Acceso a su ministerio y miembros', 4),
('Servidor', 'Acceso limitado a sus tareas', 3),
('Miembro', 'Acceso sólo a su información', 2),
('Visitante', 'Acceso público', 1);
```

## 3. Implementación del Núcleo MVC

### 3.1 Punto de entrada principal

```php
<?php
// filepath: c:\xampp\htdocs\ENCASA_DATABASE\public\index.php

// Definir la ruta base de la aplicación
define('BASE_PATH', dirname(__DIR__));

// Cargar el sistema de autoload
require_once BASE_PATH . '/app/config/config.php';

// Inicializar el router
$router = new Core\Router();

// Cargar todas las rutas
require_once BASE_PATH . '/app/config/routes.php';

// Ejecutar la aplicación
$router->dispatch();
```

### 3.2 Configuración

```php
<?php 
// filepath: c:\xampp\htdocs\ENCASA_DATABASE\app\config\config.php

// Configuración de entorno
define('ENV', 'development'); // 'development' o 'production'
define('DEBUG', ENV === 'development');

// Configuración de tiempo
date_default_timezone_set('America/Bogota');
setlocale(LC_ALL, 'es_CO.UTF-8');

// Configuración de URL
define('BASE_URL', 'http://localhost/ENCASA_DATABASE/public');
define('ASSETS_URL', BASE_URL . '/assets');

// Configuración de sesión
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
if (ENV === 'production') {
    ini_set('session.cookie_secure', 1);
}
session_start();

// Configuración de errores
if (DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', BASE_PATH . '/logs/error.log');
}

// Autoload de clases
spl_autoload_register(function ($class) {
    // Convertir namespace a ruta de archivo
    $prefix = '';
    $base_dir = BASE_PATH . '/app/';
    
    // Reemplazar namespace por directorio
    $file = $base_dir . str_replace('\\', '/', $class) . '.php';
    
    // Si existe el archivo, cargarlo
    if (file_exists($file)) {
        require $file;
        return true;
    }
    
    return false;
});

// Configuración JWT
define('JWT_SECRET', 'clave_secreta_encasa_cambiar_en_produccion');
define('JWT_EXPIRATION', 3600); // 1 hora en segundos

// Incluir archivos de configuración adicionales
require_once 'db.php';
```

### 3.3 Conexión a Base de Datos

```php
<?php
// filepath: c:\xampp\htdocs\ENCASA_DATABASE\app\config\db.php

namespace Config;

class Database {
    private static $instance = null;
    private $conn;
    
    private $host = 'localhost';
    private $user = 'root';
    private $pass = '';
    private $name = 'IglesiaEnCasa';
    private $charset = 'utf8mb4';
    
    private function __construct() {
        try {
            $this->conn = new \PDO(
                "mysql:host=$this->host;dbname=$this->name;charset=$this->charset",
                $this->user,
                $this->pass,
                [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                    \PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
        } catch (\PDOException $e) {
            throw new \Exception("Error de conexión a la base de datos: " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance->conn;
    }
    
    // Prevenir clonación
    private function __clone() {}
    
    // Método para sanitizar entradas
    public static function sanitize($input) {
        if (is_array($input)) {
            foreach ($input as $key => $value) {
                $input[$key] = self::sanitize($value);
            }
            return $input;
        }
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
}
```

### 3.4 Sistema de Rutas

```php
<?php
// filepath: c:\xampp\htdocs\ENCASA_DATABASE\app\config\routes.php

// Rutas para sistema de autenticación
$router->add('/login', ['controller' => 'Auth', 'action' => 'login']);
$router->add('/logout', ['controller' => 'Auth', 'action' => 'logout']);
$router->add('/reset-password', ['controller' => 'Auth', 'action' => 'resetPassword']);

// Rutas para el dashboard
$router->add('/', ['controller' => 'Dashboard', 'action' => 'index']);
$router->add('/dashboard', ['controller' => 'Dashboard', 'action' => 'index']);

// Rutas para miembros
$router->add('/miembros', ['controller' => 'Miembros', 'action' => 'index']);
$router->add('/miembros/create', ['controller' => 'Miembros', 'action' => 'create']);
$router->add('/miembros/edit/{id:\d+}', ['controller' => 'Miembros', 'action' => 'edit']);
$router->add('/miembros/view/{id:\d+}', ['controller' => 'Miembros', 'action' => 'view']);
$router->add('/miembros/delete/{id:\d+}', ['controller' => 'Miembros', 'action' => 'delete']);

// Rutas para ministerios
$router->add('/ministerios', ['controller' => 'Ministerios', 'action' => 'index']);
$router->add('/ministerios/create', ['controller' => 'Ministerios', 'action' => 'create']);
$router->add('/ministerios/edit/{id:\d+}', ['controller' => 'Ministerios', 'action' => 'edit']);
$router->add('/ministerios/view/{id:\d+}', ['controller' => 'Ministerios', 'action' => 'view']);
$router->add('/ministerios/delete/{id:\d+}', ['controller' => 'Ministerios', 'action' => 'delete']);

// Rutas para miembros de ministerios
$router->add('/ministerios/{id:\d+}/miembros', ['controller' => 'MiembrosMinisterios', 'action' => 'listByMinisterio']);
$router->add('/ministerios/{id:\d+}/miembros/add', ['controller' => 'MiembrosMinisterios', 'action' => 'add']);
$router->add('/ministerios/{id:\d+}/miembros/remove/{miembro_id:\d+}', ['controller' => 'MiembrosMinisterios', 'action' => 'remove']);

// Rutas para tareas
$router->add('/tareas', ['controller' => 'Tareas', 'action' => 'index']);
$router->add('/tareas/create', ['controller' => 'Tareas', 'action' => 'create']);
$router->add('/tareas/edit/{id:\d+}', ['controller' => 'Tareas', 'action' => 'edit']);
$router->add('/tareas/view/{id:\d+}', ['controller' => 'Tareas', 'action' => 'view']);
$router->add('/tareas/delete/{id:\d+}', ['controller' => 'Tareas', 'action' => 'delete']);

// Rutas para asignaciones de tareas
$router->add('/tareas/{id:\d+}/asignar', ['controller' => 'AsignacionTareas', 'action' => 'asignar']);
$router->add('/tareas/mis-tareas', ['controller' => 'AsignacionTareas', 'action' => 'misTareas']);
$router->add('/tareas/completar/{id:\d+}', ['controller' => 'AsignacionTareas', 'action' => 'completar']);

// Rutas para API - AJAX
$router->add('/api/miembros', ['controller' => 'API', 'action' => 'getMiembros']);
$router->add('/api/ministerios', ['controller' => 'API', 'action' => 'getMinisterios']);
$router->add('/api/tareas', ['controller' => 'API', 'action' => 'getTareas']);
```

### 3.5 Core MVC

```php
<?php
// filepath: c:\xampp\htdocs\ENCASA_DATABASE\app\core\Router.php

namespace Core;

class Router {
    private $routes = [];
    private $params = [];
    
    public function add($route, $params = []) {
        // Convertir la ruta a expresión regular para captura de parámetros
        $route = preg_replace('/\//', '\\/', $route);
        $route = preg_replace('/\{([a-z]+):([^\}]+)\}/', '(?P<\1>\2)', $route);
        $route = '/^' . $route . '$/i';
        
        $this->routes[$route] = $params;
    }
    
    public function match($url) {
        foreach ($this->routes as $route => $params) {
            if (preg_match($route, $url, $matches)) {
                foreach ($matches as $key => $match) {
                    if (is_string($key)) {
                        $params[$key] = $match;
                    }
                }
                $this->params = $params;
                return true;
            }
        }
        return false;
    }
    
    public function dispatch() {
        $url = $this->removeQueryStringVariables($_SERVER['QUERY_STRING']);
        
        if ($this->match($url)) {
            $controller = $this->getNamespace() . $this->params['controller'] . 'Controller';
            
            if (class_exists($controller)) {
                $controller_object = new $controller($this->params);
                
                $action = $this->params['action'];
                
                if (method_exists($controller_object, $action)) {
                    $controller_object->$action();
                } else {
                    throw new \Exception("Método $action no encontrado en el controlador $controller");
                }
            } else {
                throw new \Exception("Controlador $controller no encontrado");
            }
        } else {
            $controller = $this->getNamespace() . 'ErrorController';
            $controller_object = new $controller();
            $controller_object->pageNotFound();
        }
    }
    
    protected function removeQueryStringVariables($url) {
        if ($url != '') {
            $parts = explode('&', $url, 2);
            
            if (strpos($parts[0], '=') === false) {
                $url = $parts[0];
            } else {
                $url = '';
            }
        }
        
        return $url;
    }
    
    protected function getNamespace() {
        $namespace = 'Controllers\\';
        
        if (array_key_exists('namespace', $this->params)) {
            $namespace .= $this->params['namespace'] . '\\';
        }
        
        return $namespace;
    }
}
```

```php
<?php
// filepath: c:\xampp\htdocs\ENCASA_DATABASE\app\core\Controller.php

namespace Core;

abstract class Controller {
    protected $route_params = [];
    
    public function __construct($route_params) {
        $this->route_params = $route_params;
    }
    
    public function __call($name, $args) {
        $method = $name . 'Action';
        
        if (method_exists($this, $method)) {
            if ($this->before() !== false) {
                call_user_func_array([$this, $method], $args);
                $this->after();
            }
        } else {
            throw new \Exception("Método $method no encontrado en el controlador " . get_class($this));
        }
    }
    
    // Método que se ejecuta antes de la acción principal
    protected function before() {}
    
    // Método que se ejecuta después de la acción principal
    protected function after() {}
    
    // Método para renderizar vistas
    protected function render($view, $data = []) {
        View::render($view, $data);
    }
    
    // Método para renderizar vistas AJAX
    protected function renderPartial($view, $data = []) {
        View::renderPartial($view, $data);
    }
    
    // Método para redireccionar
    protected function redirect($url) {
        header('Location: ' . BASE_URL . '/' . $url);
        exit;
    }
    
    // Método para enviar respuesta JSON
    protected function jsonResponse($data, $statusCode = 200) {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }
}
```

```php
<?php
// filepath: c:\xampp\htdocs\ENCASA_DATABASE\app\core\View.php

namespace Core;

class View {
    // Renderiza una vista completa con layout
    public static function render($view, $data = []) {
        // Extraer los datos para que estén disponibles como variables
        extract($data);
        
        // Primero, incluir el contenido de la vista
        $viewFile = self::getViewPath($view);
        if (!file_exists($viewFile)) {
            throw new \Exception("Vista $view no encontrada");
        }
        
        // Capturar el contenido de la vista en un buffer
        ob_start();
        require_once $viewFile;
        $content = ob_get_clean();
        
        // Determinar el layout a usar (si está en sesión, usuario autenticado)
        $layout = isset($_SESSION['user_id']) ? 'main' : 'auth';
        
        // Incluir el layout con el contenido de la vista
        $layoutFile = BASE_PATH . "/app/views/layouts/$layout.php";
        if (file_exists($layoutFile)) {
            require_once $layoutFile;
        } else {
            echo $content;
        }
    }
    
    // Renderiza solo el contenido de la vista (para AJAX)
    public static function renderPartial($view, $data = []) {
        extract($data);
        
        $viewFile = self::getViewPath($view);
        if (!file_exists($viewFile)) {
            throw new \Exception("Vista $view no encontrada");
        }
        
        require_once $viewFile;
    }
    
    // Obtiene la ruta completa a un archivo de vista
    private static function getViewPath($view) {
        return BASE_PATH . "/app/views/$view.php";
    }
}
```

```php
<?php
// filepath: c:\xampp\htdocs\ENCASA_DATABASE\app\core\Auth.php

namespace Core;

use Config\Database;
use \Firebase\JWT\JWT;

class Auth {
    // Verificar si el usuario está autenticado
    public static function isLoggedIn() {
        if (isset($_SESSION['user_id'])) {
            return true;
        }
        
        // Si no hay sesión pero hay token en cookie, intentar autenticar
        if (isset($_COOKIE['auth_token'])) {
            try {
                $token = $_COOKIE['auth_token'];
                $decoded = JWT::decode($token, JWT_SECRET, ['HS256']);
                
                if ($decoded && $decoded->exp > time()) {
                    self::setUserSession($decoded->user_id);
                    return true;
                }
            } catch (\Exception $e) {
                // Token inválido o expirado
                self::logout();
            }
        }
        
        return false;
    }
    
    // Obtener información del usuario actual
    public static function getCurrentUser() {
        if (!self::isLoggedIn()) {
            return null;
        }
        
        $db = Database::getInstance();
        $stmt = $db->prepare("
            SELECT u.*, r.nombre as rol_nombre, r.nivel_acceso 
            FROM Usuarios u
            JOIN Roles r ON u.rol_id = r.id
            WHERE u.id = ?
        ");
        $stmt->execute([$_SESSION['user_id']]);
        
        $user = $stmt->fetch();
        
        if ($user) {
            // Añadir información del miembro
            $stmt = $db->prepare("
                SELECT id, nombres, apellidos, foto
                FROM InformacionGeneral
                WHERE id = ?
            ");
            $stmt->execute([$user['miembro_id']]);
            $member = $stmt->fetch();
            
            if ($member) {
                $user = array_merge($user, $member);
            }
        }
        
        return $user;
    }
    
    // Verificar si el usuario tiene permiso para una acción
    public static function hasPermission($permission) {
        $user = self::getCurrentUser();
        
        if (!$user) {
            return false;
        }
        
        // Permisos según nivel de acceso
        switch ($permission) {
            case 'ver_todos_ministerios':
            case 'crear_ministerios':
            case 'editar_cualquier_ministerio':
            case 'ver_todos_miembros':
                return $user['nivel_acceso'] >= 5; // Solo pastores y copastores
                
            case 'ver_su_ministerio':
            case 'editar_su_ministerio':
            case 'ver_miembros_ministerio':
                return $user['nivel_acceso'] >= 4; // Líderes y superiores
                
            case 'ver_sus_tareas':
            case 'completar_tareas':
                return $user['nivel_acceso'] >= 3; // Servidores y superiores
                
            case 'ver_info_personal':
                return $user['nivel_acceso'] >= 2; // Todos los usuarios autenticados
                
            default:
                return false;
        }
    }
    
    // Requerir login o redireccionar
    public static function requireLogin() {
        if (!self::isLoggedIn()) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
    }
    
    // Autenticar usuario
    public static function login($username, $password) {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM Usuarios WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        
        if ($user && $user['estado'] === 'Activo' && password_verify($password, $user['password'])) {
            // Resetear intentos fallidos
            $stmt = $db->prepare("UPDATE Usuarios SET intentos_fallidos = 0, ultimo_acceso = NOW() WHERE id = ?");
            $stmt->execute([$user['id']]);
            
            // Crear la sesión
            self::setUserSession($user['id']);
            
            // Crear token JWT para recordar sesión
            $token = self::generateJWT($user['id']);
            setcookie('auth_token', $token, time() + 60*60*24*30, '/', '', false, true); // 30 días
            
            return true;
        } else if ($user) {
            // Incrementar intentos fallidos
            $intentos = $user['intentos_fallidos'] + 1;
            $stmt = $db->prepare("UPDATE Usuarios SET intentos_fallidos = ? WHERE id = ?");
            $stmt->execute([$intentos, $user['id']]);
            
            // Bloquear después de 5 intentos
            if ($intentos >= 5) {
                $stmt = $db->prepare("UPDATE Usuarios SET estado = 'Bloqueado' WHERE id = ?");
                $stmt->execute([$user['id']]);
            }
        }
        
        return false;
    }
    
    // Cerrar sesión
    public static function logout() {
        // Destruir sesión
        $_SESSION = [];
        
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }
        
        session_destroy();
        
        // Eliminar cookie de token
        setcookie('auth_token', '', time() - 3600, '/');
    }
    
    // Establecer sesión de usuario
    private static function setUserSession($user_id) {
        $_SESSION['user_id'] = $user_id;
        $_SESSION['last_activity'] = time();
    }
    
    // Generar token JWT
    private static function generateJWT($user_id) {
        $issuedAt = time();
        $expirationTime = $issuedAt + JWT_EXPIRATION * 30; // 30 veces más largo que lo normal para la cookie
        
        $payload = [
            'iat' => $issuedAt,
            'exp' => $expirationTime,
            'user_id' => $user_id
        ];
        
        return JWT::encode($payload, JWT_SECRET);
    }
}
```

## 4. Modelo Base y Modelo de Miembros

```php
<?php
// filepath: c:\xampp\htdocs\ENCASA_DATABASE\app\models\Model.php

namespace Models;

use Config\Database;

abstract class Model {
    protected static $table;
    protected static $primaryKey = 'id';
    
    // Obtener todos los registros
    public static function getAll($orderBy = null, $limit = null, $offset = null) {
        $db = Database::getInstance();
        
        $sql = "SELECT * FROM " . static::$table;
        
        if ($orderBy) {
            $sql .= " ORDER BY " . $orderBy;
        }
        
        if ($limit) {
            $sql .= " LIMIT " . (int)$limit;
            
            if ($offset) {
                $sql .= " OFFSET " . (int)$offset;
            }
        }
        
        $stmt = $db->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    // Obtener por ID
    public static function getById($id) {
        $db = Database::getInstance();
        
        $sql = "SELECT * FROM " . static::$table . " WHERE " . static::$primaryKey . " = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);
        
        return $stmt->fetch();
    }
    
    // Crear nuevo registro
    public static function create($data) {
        $db = Database::getInstance();
        
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        
        $sql = "INSERT INTO " . static::$table . " (" . $columns . ") VALUES (" . $placeholders . ")";
        $stmt = $db->prepare($sql);
        $stmt->execute(array_values($data));
        
        return $db->lastInsertId();
    }
    
    // Actualizar registro
    public static function update($id, $data) {
        $db = Database::getInstance();
        
        $set = [];
        foreach ($data as $column => $value) {
            $set[] = $column . " = ?";
        }
        
        $sql = "UPDATE " . static::$table . " SET " . implode(', ', $set) . " WHERE " . static::$primaryKey . " = ?";
        $values = array_values($data);
        $values[] = $id;
        
        $stmt = $db->prepare($sql);
        
        return $stmt->execute($values);
    }
    
    // Eliminar registro
    public static function delete($id) {
        $db = Database::getInstance();
        
        $sql = "DELETE FROM " . static::$table . " WHERE " . static::$primaryKey . " = ?";
        $stmt = $db->prepare($sql);
        
        return $stmt->execute([$id]);
    }
    
    // Buscar registros
    public static function findBy($column, $value) {
        $db = Database::getInstance();
        
        $sql = "SELECT * FROM " . static::$table . " WHERE " . $column . " = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$value]);
        
        return $stmt->fetchAll();
    }
    
    // Contar registros
    public static function count($where = null, $params = []) {
        $db = Database::getInstance();
        
        $sql = "SELECT COUNT(*) as total FROM " . static::$table;
        
        if ($where) {
            $sql .= " WHERE " . $where;
        }
        
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        
        return $result['total'];
    }
}
```

```php
<?php
// filepath: c:\xampp\htdocs\ENCASA_DATABASE\app\models\Miembro.php

namespace Models;

use Config\Database;

class Miembro extends Model {
    protected static $table = 'InformacionGeneral';
    
    // Obtener miembro completo con todas sus relaciones
    public static function getMiembroCompleto($id) {
        $db = Database::getInstance();
        $miembro = [];
        
        // Obtener información general
        $stmt = $db->prepare("SELECT * FROM InformacionGeneral WHERE id = ?");
        $stmt->execute([$id]);
        $miembro['general'] = $stmt->fetch();
        
        if (!$miembro['general']) {
            return null;
        }
        
        // Obtener contacto
        $stmt = $db->prepare("SELECT * FROM Contacto WHERE miembro_id = ?");
        $stmt->execute([$id]);
        $miembro['contacto'] = $stmt->fetch();
        
        // Obtener carrera bíblica
        $stmt = $db->prepare("SELECT * FROM CarreraBiblica WHERE miembro_id = ?");
        $stmt->execute([$id]);
        $miembro['carreraBiblica'] = $stmt->fetch();
        
        // Obtener estudios y trabajo
        $stmt = $db->prepare("SELECT * FROM EstudiosTrabajo WHERE miembro_id = ?");
        $stmt->execute([$id]);
        $miembro['estudiosTrabajo'] = $stmt->fetch();
        
        // Obtener salud y emergencias
        $stmt = $db->prepare("SELECT * FROM SaludEmergencias WHERE miembro_id = ?");
        $stmt->execute([$id]);
        $miembro['saludEmergencias'] = $stmt->fetch();
        
        // Obtener tallas
        $stmt = $db->prepare("SELECT * FROM Tallas WHERE miembro_id = ?");
        $stmt->execute([$id]);
        $miembro['tallas'] = $stmt->fetch();
        
        // Obtener ministerios
        $stmt = $db->prepare("
            SELECT m.*, mm.rol_id, r.nombre as rol_nombre
            FROM MiembrosMinisterios mm
            JOIN Ministerios m ON mm.ministerio_id = m.id
            JOIN Roles r ON mm.rol_id = r.id
            WHERE mm.miembro_id = ? AND (mm.fecha_fin IS NULL OR mm.fecha_fin > CURDATE())
        ");
        $stmt->execute([$id]);
        $miembro['ministerios'] = $stmt->fetchAll();
        
        return $miembro;
    }
    
    // Crear un miembro completo con todas sus tablas relacionadas
    public static function crearMiembroCompleto($data) {
        $db = Database::getInstance();
        
        try {
            $db->beginTransaction();
            
            // Insertar información general
            $stmt = $db->prepare("
                INSERT INTO InformacionGeneral 
                (nombres, apellidos, celular, localidad, barrio, fecha_nacimiento, 
                invitado_por, conector, recorrido_espiritual, estado_espiritual, foto, habeas_data)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $data['general']['nombres'],
                $data['general']['apellidos'],
                $data['general']['celular'],
                $data['general']['localidad'] ?? null,
                $data['general']['barrio'] ?? null,
                $data['general']['fecha_nacimiento'] ?? null,
                $data['general']['invitado_por'] ?? null,
                $data['general']['conector'] ?? null,
                $data['general']['recorrido_espiritual'] ?? null,
                $data['general']['estado_espiritual'] ?? 'Nuevo',
                $data['general']['foto'] ?? null,
                $data['general']['habeas_data'] ?? null
            ]);
            
            $miembro_id = $db->lastInsertId();
            
            // Insertar contacto
            if (isset($data['contacto'])) {
                $stmt = $db->prepare("
                    INSERT INTO Contacto 
                    (miembro_id, tipo_documento, numero_documento, telefono, pais, ciudad, 
                    direccion, estado_civil, correo_electronico, instagram, facebook, notas, familiares)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([
                    $miembro_id,
                    $data['contacto']['tipo_documento'] ?? null,
                    $data['contacto']['numero_documento'] ?? null,
                    $data['contacto']['telefono'] ?? null,
                    $data['contacto']['pais'] ?? null,
                    $data['contacto']['ciudad'] ?? null,
                    $data['contacto']['direccion'] ?? null,
                    $data['contacto']['estado_civil'] ?? null,
                    $data['contacto']['correo_electronico'] ?? null,
                    $data['contacto']['instagram'] ?? null,
                    $data['contacto']['facebook'] ?? null,
                    $data['contacto']['notas'] ?? null,
                    $data['contacto']['familiares'] ?? null
                ]);
            }
            
            // Insertar carrera bíblica
            if (isset($data['carreraBiblica'])) {
                $stmt = $db->prepare("
                    INSERT INTO CarreraBiblica 
                    (miembro_id, carrera_biblica, miembro_de, casa_de_palabra_y_vida, 
                    cobertura, estado, anotaciones, recorrido_espiritual)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([
                    $miembro_id,
                    $data['carreraBiblica']['carrera_biblica'] ?? null,
                    $data['carreraBiblica']['miembro_de'] ?? null,
                    $data['carreraBiblica']['casa_de_palabra_y_vida'] ?? null,
                    $data['carreraBiblica']['cobertura'] ?? null,
                    $data['carreraBiblica']['estado'] ?? null,
                    $data['carreraBiblica']['anotaciones'] ?? null,
                    $data['carreraBiblica']['recorrido_espiritual'] ?? null
                ]);
            }
            
            // Insertar estudios y trabajo
            if (isset($data['estudiosTrabajo'])) {
                $stmt = $db->prepare("
                    INSERT INTO EstudiosTrabajo 
                    (miembro_id, nivel_estudios, profesion, otros_estudios, 
                    empresa, direccion_empresa, emprendimientos)
                    VALUES (?, ?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([
                    $miembro_id,
                    $data['estudiosTrabajo']['nivel_estudios'] ?? null,
                    $data['estudiosTrabajo']['profesion'] ?? null,
                    $data['estudiosTrabajo']['otros_estudios'] ?? null,
                    $data['estudiosTrabajo']['empresa'] ?? null,
                    $data['estudiosTrabajo']['direccion_empresa'] ?? null,
                    $data['estudiosTrabajo']['emprendimientos'] ?? null
                ]);
            }
            
            // Insertar salud y emergencias
            if (isset($data['saludEmergencias'])) {
                $stmt = $db->prepare("
                    INSERT INTO SaludEmergencias 
                    (miembro_id, rh, acudiente1, telefono1, acudiente2, telefono2, eps)
                    VALUES (?, ?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([
                    $miembro_id,
                    $data['saludEmergencias']['rh'] ?? null,
                    $data['saludEmergencias']['acudiente1'] ?? null,
                    $data['saludEmergencias']['telefono1'] ?? null,
                    $data['saludEmergencias']['acudiente2'] ?? null,
                    $data['saludEmergencias']['telefono2'] ?? null,
                    $data['saludEmergencias']['eps'] ?? null
                ]);
            }
            
            // Insertar tallas
            if (isset($data['tallas'])) {
                $stmt = $db->prepare("
                    INSERT INTO Tallas 
                    (miembro_id, talla_camisa, talla_camiseta, talla_pantalon, talla_zapatos)
                    VALUES (?, ?, ?, ?, ?)
                ");
                $stmt->execute([
                    $miembro_id,
                    $data['tallas']['talla_camisa'] ?? null,
                    $data['tallas']['talla_camiseta'] ?? null,
                    $data['tallas']['talla_pantalon'] ?? null,
                    $data['tallas']['talla_zapatos'] ?? null
                ]);
            }
            
            $db->commit();
            return $miembro_id;
            
        } catch (\PDOException $e) {
            $db->rollBack();
            throw new \Exception("Error al crear miembro: " . $e->getMessage());
        }
    }
    
    // Actualizar un miembro completo
    public static function actualizarMiembroCompleto($id, $data) {
        $db = Database::getInstance();
        
        try {
            $db->beginTransaction();
            
            // Actualizar información general
            if (isset($data['general'])) {
                $stmt = $db->prepare("
                    UPDATE InformacionGeneral SET
                    nombres = ?, apellidos = ?, celular = ?, localidad = ?, barrio = ?,
                    fecha_nacimiento = ?, invitado_por = ?, conector = ?,
                    recorrido_espiritual = ?, estado_espiritual = ?, habeas_data = ?
                    WHERE id = ?
                ");
                $stmt->execute([
                    $data['general']['nombres'],
                    $data['general']['apellidos'],
                    $data['general']['celular'],
                    $data['general']['localidad'] ?? null,
                    $data['general']['barrio'] ?? null,
                    $data['general']['fecha_nacimiento'] ?? null,
                    $data['general']['invitado_por'] ?? null,
                    $data['general']['conector'] ?? null,
                    $data['general']['recorrido_espiritual'] ?? null,
                    $data['general']['estado_espiritual'] ?? null,
                    $data['general']['habeas_data'] ?? null,
                    $id
                ]);
                
                // Actualizar foto solo si se proporciona
                if (!empty($data['general']['foto'])) {
                    $stmt = $db->prepare("UPDATE InformacionGeneral SET foto = ? WHERE id = ?");
                    $stmt->execute([$data['general']['foto'], $id]);
                }
            }
            
            // Actualizar contacto
            if (isset($data['contacto'])) {
                // Verificar si existe el registro
                $stmt = $db->prepare("SELECT id FROM Contacto WHERE miembro_id = ?");
                $stmt->execute([$id]);
                
                if ($stmt->fetch()) {
                    // Actualizar
                    $stmt = $db->prepare("
                        UPDATE Contacto SET
                        tipo_documento = ?, numero_documento = ?, telefono = ?, pais = ?,
                        ciudad = ?, direccion = ?, estado_civil = ?, correo_electronico = ?,
                        instagram = ?, facebook = ?, notas = ?, familiares = ?
                        WHERE miembro_id = ?
                    ");
                    $stmt->execute([
                        $data['contacto']['tipo_documento'] ?? null,
                        $data['contacto']['numero_documento'] ?? null,
                        $data['contacto']['telefono'] ?? null,
                        $data['contacto']['pais'] ?? null,
                        $data['contacto']['ciudad'] ?? null,
                        $data['contacto']['direccion'] ?? null,
                        $data['contacto']['estado_civil'] ?? null,
                        $data['contacto']['correo_electronico'] ?? null,
                        $data['contacto']['instagram'] ?? null,
                        $data['contacto']['facebook'] ?? null,
                        $data['contacto']['notas'] ?? null,
                        $data['contacto']['familiares'] ?? null,
                        $id
                    ]);
                } else {
                    // Insertar nuevo
                    $stmt = $db->prepare("
                        INSERT INTO Contacto
                        (miembro_id, tipo_documento, numero_documento, telefono, pais,
                        ciudad, direccion, estado_civil, correo_electronico, instagram,
                        facebook, notas, familiares)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                    ");
                    $stmt->execute([
                        $id,
                        $data['contacto']['tipo_documento'] ?? null,
                        $data['contacto']['numero_documento'] ?? null,
                        $data['contacto']['telefono'] ?? null,
                        $data['contacto']['pais'] ?? null,
                        $data['contacto']['ciudad'] ?? null,
                        $data['contacto']['direccion'] ?? null,
                        $data['contacto']['estado_civil'] ?? null,
                        $data['contacto']['correo_electronico'] ?? null,
                        $data['contacto']['instagram'] ?? null,
                        $data['contacto']['facebook'] ?? null,
                        $data['contacto']['notas'] ?? null,
                        $data['contacto']['familiares'] ?? null
                    ]);
                }
            }
            
            // [Continuar con la actualización de las otras tablas de manera similar]
            
            $db->commit();
            return true;
            
        } catch (\PDOException $e) {
            $db->rollBack();
            throw new \Exception("Error al actualizar miembro: " . $e->getMessage());
        }
    }
    
    // Buscar miembros con filtros
    public static function buscarMiembros($filtros = [], $pagina = 1, $limite = 10) {
        $db = Database::getInstance();
        
        $condiciones = [];
        $parametros = [];
        
        // Aplicar filtros
        if (!empty($filtros['busqueda'])) {
            $condiciones[] = "(nombres LIKE ? OR apellidos LIKE ? OR celular LIKE ?)";
            $busqueda = "%" . $filtros['busqueda'] . "%";
            $parametros[] = $busqueda;
            $parametros[] = $busqueda;
            $parametros[] = $busqueda;
        }
        
        if (!empty($filtros['estado'])) {
            $condiciones[] = "estado_espiritual = ?";
            $parametros[] = $filtros['estado'];
        }
        
        if (!empty($filtros['ministerio_id'])) {
            $condiciones[] = "id IN (SELECT miembro_id FROM MiembrosMinisterios WHERE ministerio_id = ? AND (fecha_fin IS NULL OR fecha_fin > CURDATE()))";
            $parametros[] = $filtros['ministerio_id'];
        }
        
        // Construir WHERE
        $where = "";
        if (!empty($condiciones)) {
            $where = "WHERE " . implode(" AND ", $condiciones);
        }
        
        // Calcular offset para paginación
        $offset = ($pagina - 1) * $limite;
        
        // Consulta paginada
        $sql = "
            SELECT id, nombres, apellidos, celular, localidad, estado_espiritual, fecha_ingreso
            FROM InformacionGeneral
            $where
            ORDER BY apellidos, nombres
            LIMIT ? OFFSET ?
        ";
        
        $parametros[] = $limite;
        $parametros[] = $offset;
        
        $stmt = $db->prepare($sql);
        $stmt->execute($parametros);
        $miembros = $stmt->fetchAll();
        
        // Contar total para paginación
        $sqlTotal = "SELECT COUNT(*) as total FROM InformacionGeneral $where";
        $stmt = $db->prepare($sqlTotal);
        
        // Quitar parámetros de paginación
        array_pop($parametros);
        array_pop($parametros);
        $stmt->execute($parametros);
        
        $totalRegistros = $stmt->fetch()['total'];
        $totalPaginas = ceil($totalRegistros / $limite);
        
        return [
            'miembros' => $miembros,
            'paginacion' => [
                'total' => $totalRegistros,
                'pagina' => $pagina,
                'limite' => $limite,
                'paginas' => $totalPaginas
            ]
        ];
    }
}
```

## 5. Controlador de Miembros

```php
<?php
// filepath: c:\xampp\htdocs\ENCASA_DATABASE\app\controllers\MiembrosController.php

namespace Controllers;

use Core\Controller;
use Core\Auth;
use Models\Miembro;
use Models\Rol;
use Models\Ministerio;

class MiembrosController extends Controller {
    
    public function __construct($route_params) {
        parent::__construct($route_params);
        // Verificar autenticación para todas las acciones
        Auth::requireLogin();
    }
    
    // Listar miembros con filtros y paginación
    public function index() {
        $filtros = [
            'busqueda' => isset($_GET['busqueda']) ? $_GET['busqueda'] : '',
            'estado' => isset($_GET['estado']) ? $_GET['estado'] : '',
            'ministerio_id' => isset($_GET['ministerio_id']) ? $_GET['ministerio_id'] : ''
        ];
        
        $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
        
        // Verificar permisos
        $usuario = Auth::getCurrentUser();
        $permisoTotal = Auth::hasPermission('ver_todos_miembros');
        
        // Si es líder y no tiene permiso total, solo puede ver su ministerio
        if (!$permisoTotal && $usuario['nivel_acceso'] == 4) {
            $ministeriosLider = Ministerio::getMinisteriosByLider($usuario['miembro_id']);
            if (!empty($ministeriosLider)) {
                $filtros['ministerio_id'] = $ministeriosLider[0]['id'];
            } else {
                // Si no es líder de ningún ministerio, solo puede ver su propia información
                $this->redirect('miembros/view/' . $usuario['miembro_id']);
                return;
            }
        } else if (!$permisoTotal && $usuario['nivel_acceso'] < 4) {
            // Si es usuario regular, solo ve su propia información
            $this->redirect('miembros/view/' . $usuario['miembro_id']);
            return;
        }
        
        // Obtener miembros según filtros
        $resultado = Miembro::buscarMiembros($filtros, $pagina);
        
        // Obtener lista de ministerios para el filtro
        $ministerios = [];
        if ($permisoTotal) {
            $ministerios = Ministerio::getAll('nombre');
        } else if ($usuario['nivel_acceso'] == 4) {
            $ministerios = Ministerio::getMinisteriosByLider($usuario['miembro_id']);
        }
        
        // Renderizar vista
        $this->render('miembros/index', [
            'miembros' => $resultado['miembros'],
            'paginacion' => $resultado['paginacion'],
            'filtros' => $filtros,
            'ministerios' => $ministerios,
            'permisoTotal' => $permisoTotal
        ]);
    }
    
    // Ver perfil de miembro
    public function view() {
        $id = $this->route_params['id'] ?? 0;
        $miembro = Miembro::getMiembroCompleto($id);
        
        if (!$miembro) {
            $this->redirect('miembros?error=miembro_no_encontrado');
            return;
        }
        
        // Verificar permisos
        $usuario = Auth::getCurrentUser();
        $permisoTotal = Auth::hasPermission('ver_todos_miembros');
        
        // Si no tiene permiso total y no es el mismo usuario
        if (!$permisoTotal && $usuario['miembro_id'] != $id) {
            // Verificar si el usuario es líder de algún ministerio al que pertenece este miembro
            $esLider = false;
            
            if ($usuario['nivel_acceso'] == 4) {
                $ministeriosLider = Ministerio::getMinisteriosByLider($usuario['miembro_id']);
                
                foreach ($miembro['ministerios'] as $ministerio) {
                    foreach ($ministeriosLider as $ministerioLider) {
                        if ($ministerio['id'] == $ministerioLider['id']) {
                            $esLider = true;
                            break 2;
                        }
                    }
                }
            }
            
            if (!$esLider) {
                $this->redirect('miembros/view/' . $usuario['miembro_id']);
                return;
            }
        }
        
        // Obtener datos adicionales (invitador, etc.)
        if ($miembro['general']['invitado_por']) {
            $invitador = Miembro::getById($miembro['general']['invitado_por']);
            $miembro['invitador'] = $invitador;
        }
        
        $this->render('miembros/view', [
            'miembro' => $miembro,
            'puedeEditar' => $permisoTotal || $usuario['miembro_id'] == $id
        ]);
    }
    
    // Formulario para crear miembro
    public function create() {
        // Verificar permisos
        if (!Auth::hasPermission('ver_todos_miembros')) {
            $this->redirect('miembros?error=permiso_denegado');
            return;
        }
        
        // Obtener datos para el formulario
        $invitadores = Miembro::getAll('nombres, apellidos');
        $roles = Rol::getAll();
        $ministerios = Ministerio::getAll('nombre');
        
        $this->render('miembros/form', [
            'invitadores' => $invitadores,
            'roles' => $roles,
            'ministerios' => $ministerios,
            'accion' => 'crear'
        ]);
    }
    
    // Procesar creación de miembro
    public function store() {
        // Verificar permisos
        if (!Auth::hasPermission('ver_todos_miembros')) {
            $this->jsonResponse(['error' => 'Permiso denegado'], 403);
            return;
        }
        
        // Validar request AJAX
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['error' => 'Método no permitido'], 405);
            return;
        }
        
        // Obtener datos del formulario
        $datos = json_decode(file_get_contents('php://input'), true);
        
        // Validar datos obligatorios
        if (empty($datos['general']['nombres']) || empty($datos['general']['apellidos']) || empty($datos['general']['celular'])) {
            $this->jsonResponse(['error' => 'Faltan campos obligatorios'], 400);
            return;
        }
        
        try {
            // Procesar foto si existe
            if (!empty($datos['general']['foto_data'])) {
                $foto = $this->procesarFoto($datos['general']['foto_data']);
                $datos['general']['foto'] = $foto;
            }
            unset($datos['general']['foto_data']);
            
            // Crear miembro
            $id = Miembro::crearMiembroCompleto($datos);
            
            // Procesar ministerios si existen
            if (!empty($datos['ministerios'])) {
                foreach ($datos['ministerios'] as $ministerio) {
                    Ministerio::agregarMiembro($ministerio['id'], $id, $ministerio['rol_id']);
                }
            }
            
            $this->jsonResponse([
                'success' => true,
                'mensaje' => 'Miembro creado exitosamente',
                'id' => $id
            ]);
            
        } catch (\Exception $e) {
            $this->jsonResponse(['error' => $e->getMessage()], 500);
        }
    }
    
    // Formulario para editar miembro
    public function edit() {
        $id = $this->route_params['id'] ?? 0;
        $miembro = Miembro::getMiembroCompleto($id);
        
        if (!$miembro) {
            $this->redirect('miembros?error=miembro_no_encontrado');
            return;
        }
        
        // Verificar permisos
        $usuario = Auth::getCurrentUser();
        $permisoTotal = Auth::hasPermission('ver_todos_miembros');
        
        // Si no tiene permiso total y no es el mismo usuario
        if (!$permisoTotal && $usuario['miembro_id'] != $id) {
            $this->redirect('miembros/view/' . $usuario['miembro_id']);
            return;
        }
        
        // Obtener datos para el formulario
        $invitadores = Miembro::getAll('nombres, apellidos');
        $roles = Rol::getAll();
        $ministerios = Ministerio::getAll('nombre');
        
        $this->render('miembros/form', [
            'miembro' => $miembro,
            'invitadores' => $invitadores,
            'roles' => $roles,
            'ministerios' => $ministerios,
            'accion' => 'editar'
        ]);
    }
    
    // Procesar actualización de miembro
    public function update() {
        // Obtener ID del miembro
        $id = $this->route_params['id'] ?? 0;
        
        // Verificar permisos
        $usuario = Auth::getCurrentUser();
        $permisoTotal = Auth::hasPermission('ver_todos_miembros');
        
        // Si no tiene permiso total y no es el mismo usuario
        if (!$permisoTotal && $usuario['miembro_id'] != $id) {
            $this->jsonResponse(['error' => 'Permiso denegado'], 403);
            return;
        }
        
        // Validar request AJAX
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['error' => 'Método no permitido'], 405);
            return;
        }
        
        // Obtener datos del formulario
        $datos = json_decode(file_get_contents('php://input'), true);
        
        // Validar datos obligatorios
        if (empty($datos['general']['nombres']) || empty($datos['general']['apellidos']) || empty($datos['general']['celular'])) {
            $this->jsonResponse(['error' => 'Faltan campos obligatorios'], 400);
            return;
        }
        
        try {
            // Procesar foto si existe
            if (!empty($datos['general']['foto_data'])) {
                $foto = $this->procesarFoto($datos['general']['foto_data']);
                $datos['general']['foto'] = $foto;
            }
            unset($datos['general']['foto_data']);
            
            // Actualizar miembro
            Miembro::actualizarMiembroCompleto($id, $datos);
            
            // Si el usuario tiene permisos, actualizar ministerios
            if ($permisoTotal && !empty($datos['ministerios'])) {
                // Eliminar asignaciones actuales
                Ministerio::eliminarAsignacionesMiembro($id);
                
                // Crear nuevas asignaciones
                foreach ($datos['ministerios'] as $ministerio) {
                    Ministerio::agregarMiembro($ministerio['id'], $id, $ministerio['rol_id']);
                }
            }
            
            $this->jsonResponse([
                '<?php
// filepath: c:\xampp\htdocs\ENCASA_DATABASE\app\controllers\MiembrosController.php

namespace Controllers;

use Core\Controller;
use Core\Auth;
use Models\Miembro;
use Models\Rol;
use Models\Ministerio;

class MiembrosController extends Controller {
    
    public function __construct($route_params) {
        parent::__construct($route_params);
        // Verificar autenticación para todas las acciones
        Auth::requireLogin();
    }
    
    // Listar miembros con filtros y paginación
    public function index() {
        $filtros = [
            'busqueda' => isset($_GET['busqueda']) ? $_GET['busqueda'] : '',
            'estado' => isset($_GET['estado']) ? $_GET['estado'] : '',
            'ministerio_id' => isset($_GET['ministerio_id']) ? $_GET['ministerio_id'] : ''
        ];
        
        $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
        
        // Verificar permisos
        $usuario = Auth::getCurrentUser();
        $permisoTotal = Auth::hasPermission('ver_todos_miembros');
        
        // Si es líder y no tiene permiso total, solo puede ver su ministerio
        if (!$permisoTotal && $usuario['nivel_acceso'] == 4) {
            $ministeriosLider = Ministerio::getMinisteriosByLider($usuario['miembro_id']);
            if (!empty($ministeriosLider)) {
                $filtros['ministerio_id'] = $ministeriosLider[0]['id'];
            } else {
                // Si no es líder de ningún ministerio, solo puede ver su propia información
                $this->redirect('miembros/view/' . $usuario['miembro_id']);
                return;
            }
        } else if (!$permisoTotal && $usuario['nivel_acceso'] < 4) {
            // Si es usuario regular, solo ve su propia información
            $this->redirect('miembros/view/' . $usuario['miembro_id']);
            return;
        }
        
        // Obtener miembros según filtros
        $resultado = Miembro::buscarMiembros($filtros, $pagina);
        
        // Obtener lista de ministerios para el filtro
        $ministerios = [];
        if ($permisoTotal) {
            $ministerios = Ministerio::getAll('nombre');
        } else if ($usuario['nivel_acceso'] == 4) {
            $ministerios = Ministerio::getMinisteriosByLider($usuario['miembro_id']);
        }
        
        // Renderizar vista
        $this->render('miembros/index', [
            'miembros' => $resultado['miembros'],
            'paginacion' => $resultado['paginacion'],
            'filtros' => $filtros,
            'ministerios' => $ministerios,
            'permisoTotal' => $permisoTotal
        ]);
    }
    
    // Ver perfil de miembro
    public function view() {
        $id = $this->route_params['id'] ?? 0;
        $miembro = Miembro::getMiembroCompleto($id);
        
        if (!$miembro) {
            $this->redirect('miembros?error=miembro_no_encontrado');
            return;
        }
        
        // Verificar permisos
        $usuario = Auth::getCurrentUser();
        $permisoTotal = Auth::hasPermission('ver_todos_miembros');
        
        // Si no tiene permiso total y no es el mismo usuario
        if (!$permisoTotal && $usuario['miembro_id'] != $id) {
            // Verificar si el usuario es líder de algún ministerio al que pertenece este miembro
            $esLider = false;
            
            if ($usuario['nivel_acceso'] == 4) {
                $ministeriosLider = Ministerio::getMinisteriosByLider($usuario['miembro_id']);
                
                foreach ($miembro['ministerios'] as $ministerio) {
                    foreach ($ministeriosLider as $ministerioLider) {
                        if ($ministerio['id'] == $ministerioLider['id']) {
                            $esLider = true;
                            break 2;
                        }
                    }
                }
            }
            
            if (!$esLider) {
                $this->redirect('miembros/view/' . $usuario['miembro_id']);
                return;
            }
        }
        
        // Obtener datos adicionales (invitador, etc.)
        if ($miembro['general']['invitado_por']) {
            $invitador = Miembro::getById($miembro['general']['invitado_por']);
            $miembro['invitador'] = $invitador;
        }
        
        $this->render('miembros/view', [
            'miembro' => $miembro,
            'puedeEditar' => $permisoTotal || $usuario['miembro_id'] == $id
        ]);
    }
    
    // Formulario para crear miembro
    public function create() {
        // Verificar permisos
        if (!Auth::hasPermission('ver_todos_miembros')) {
            $this->redirect('miembros?error=permiso_denegado');
            return;
        }
        
        // Obtener datos para el formulario
        $invitadores = Miembro::getAll('nombres, apellidos');
        $roles = Rol::getAll();
        $ministerios = Ministerio::getAll('nombre');
        
        $this->render('miembros/form', [
            'invitadores' => $invitadores,
            'roles' => $roles,
            'ministerios' => $ministerios,
            'accion' => 'crear'
        ]);
    }
    
    // Procesar creación de miembro
    public function store() {
        // Verificar permisos
        if (!Auth::hasPermission('ver_todos_miembros')) {
            $this->jsonResponse(['error' => 'Permiso denegado'], 403);
            return;
        }
        
        // Validar request AJAX
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['error' => 'Método no permitido'], 405);
            return;
        }
        
        // Obtener datos del formulario
        $datos = json_decode(file_get_contents('php://input'), true);
        
        // Validar datos obligatorios
        if (empty($datos['general']['nombres']) || empty($datos['general']['apellidos']) || empty($datos['general']['celular'])) {
            $this->jsonResponse(['error' => 'Faltan campos obligatorios'], 400);
            return;
        }
        
        try {
            // Procesar foto si existe
            if (!empty($datos['general']['foto_data'])) {
                $foto = $this->procesarFoto($datos['general']['foto_data']);
                $datos['general']['foto'] = $foto;
            }
            unset($datos['general']['foto_data']);
            
            // Crear miembro
            $id = Miembro::crearMiembroCompleto($datos);
            
            // Procesar ministerios si existen
            if (!empty($datos['ministerios'])) {
                foreach ($datos['ministerios'] as $ministerio) {
                    Ministerio::agregarMiembro($ministerio['id'], $id, $ministerio['rol_id']);
                }
            }
            
            $this->jsonResponse([
                'success' => true,
                'mensaje' => 'Miembro creado exitosamente',
                'id' => $id
            ]);
            
        } catch (\Exception $e) {
            $this->jsonResponse(['error' => $e->getMessage()], 500);
        }
    }
    
    // Formulario para editar miembro
    public function edit() {
        $id = $this->route_params['id'] ?? 0;
        $miembro = Miembro::getMiembroCompleto($id);
        
        if (!$miembro) {
            $this->redirect('miembros?error=miembro_no_encontrado');
            return;
        }
        
        // Verificar permisos
        $usuario = Auth::getCurrentUser();
        $permisoTotal = Auth::hasPermission('ver_todos_miembros');
        
        // Si no tiene permiso total y no es el mismo usuario
        if (!$permisoTotal && $usuario['miembro_id'] != $id) {
            $this->redirect('miembros/view/' . $usuario['miembro_id']);
            return;
        }
        
        // Obtener datos para el formulario
        $invitadores = Miembro::getAll('nombres, apellidos');
        $roles = Rol::getAll();
        $ministerios = Ministerio::getAll('nombre');
        
        $this->render('miembros/form', [
            'miembro' => $miembro,
            'invitadores' => $invitadores,
            'roles' => $roles,
            'ministerios' => $ministerios,
            'accion' => 'editar'
        ]);
    }
    
    // Procesar actualización de miembro
    public function update() {
        // Obtener ID del miembro
        $id = $this->route_params['id'] ?? 0;
        
        // Verificar permisos
        $usuario = Auth::getCurrentUser();
        $permisoTotal = Auth::hasPermission('ver_todos_miembros');
        
        // Si no tiene permiso total y no es el mismo usuario
        if (!$permisoTotal && $usuario['miembro_id'] != $id) {
            $this->jsonResponse(['error' => 'Permiso denegado'], 403);
            return;
        }
        
        // Validar request AJAX
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['error' => 'Método no permitido'], 405);
            return;
        }
        
        // Obtener datos del formulario
        $datos = json_decode(file_get_contents('php://input'), true);
        
        // Validar datos obligatorios
        if (empty($datos['general']['nombres']) || empty($datos['general']['apellidos']) || empty($datos['general']['celular'])) {
            $this->jsonResponse(['error' => 'Faltan campos obligatorios'], 400);
            return;
        }
        
        try {
            // Procesar foto si existe
            if (!empty($datos['general']['foto_data'])) {
                $foto = $this->procesarFoto($datos['general']['foto_data']);
                $datos['general']['foto'] = $foto;
            }
            unset($datos['general']['foto_data']);
            
            // Actualizar miembro
            Miembro::actualizarMiembroCompleto($id, $datos);
            
            // Si el usuario tiene permisos, actualizar ministerios
            if ($permisoTotal && !empty($datos['ministerios'])) {
                // Eliminar asignaciones actuales
                Ministerio::eliminarAsignacionesMiembro($id);
                
                // Crear nuevas asignaciones
                foreach ($datos['ministerios'] as $ministerio) {
                    Ministerio::agregarMiembro($ministerio['id'], $id, $ministerio['rol_id']);
                }
            }
            
            $this->jsonResponse([
                'success' => true,
                'mensaje' => 'Miembro actualizado exitosamente',
                'id' => $id
            ]);
            
        } catch (\Exception $e) {
            $this->jsonResponse(['error' => $e->getMessage()], 500);
        }
    }
    
    // Eliminar miembro
    public function delete() {
        $id = $this->route_params['id'] ?? 0;
        
        // Verificar permisos (solo administradores pueden eliminar)
        if (!Auth::hasPermission('ver_todos_miembros')) {
            $this->redirect('miembros?error=permiso_denegado');
            return;
        }
        
        // Confirmar que existe
        $miembro = Miembro::getById($id);
        if (!$miembro) {
            $this->redirect('miembros?error=miembro_no_encontrado');
            return;
        }
        
        // Si se envió el formulario de confirmación
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmar'])) {
            try {
                // Eliminar foto si existe
                if (!empty($miembro['foto'])) {
                    $ruta_foto = BASE_PATH . '/public/uploads/photos/' . $miembro['foto'];
                    if (file_exists($ruta_foto)) {
                        unlink($ruta_foto);
                    }
                }
                
                // Eliminar el miembro (las relaciones se eliminarán en cascada)
                Miembro::delete($id);
                
                // Redirigir con mensaje de éxito
                $this->redirect('miembros?success=miembro_eliminado');
                
            } catch (\Exception $e) {
                $this->redirect('miembros?error=' . urlencode($e->getMessage()));
            }
            
        } else {
            // Mostrar página de confirmación
            $this->render('miembros/delete', [
                'miembro' => $miembro
            ]);
        }
    }
    
    // Procesar subida de foto
    private function procesarFoto($foto_data) {
        // Decodificar datos base64
        if (preg_match('/^data:image\/(\w+);base64,/', $foto_data, $type)) {
            $foto_data = substr($foto_data, strpos($foto_data, ',') + 1);
            $type = strtolower($type[1]); // jpg, png, gif
            
            if (!in_array($type, ['jpg', 'jpeg', 'png', 'gif'])) {
                throw new \Exception('Formato de imagen no válido');
            }
            
            $foto_data = str_replace(' ', '+', $foto_data);
            $foto_data = base64_decode($foto_data);
            
            if ($foto_data === false) {
                throw new \Exception('Error al decodificar la imagen');
            }
        } else {
            throw new \Exception('Formato de datos de imagen no válido');
        }
        
        // Generar nombre único
        $nombre_foto = uniqid() . '.' . $type;
        $ruta_destino = BASE_PATH . '/public/uploads/photos/' . $nombre_foto;
        
        // Guardar archivo
        if (!file_put_contents($ruta_destino, $foto_data)) {
            throw new \Exception('Error al guardar la imagen');
        }
        
        return $nombre_foto;
    }
    
    // API para obtener lista de miembros (para selectores)
    public function getMiembrosSelect() {
        // Verificar que sea una petición AJAX
        if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || $_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') {
            $this->jsonResponse(['error' => 'Acceso no permitido'], 403);
            return;
        }
        
        $busqueda = $_GET['q'] ?? '';
        
        // Buscar miembros que coincidan con la búsqueda
        $db = \Config\Database::getInstance();
        $sql = "
            SELECT id, CONCAT(nombres, ' ', apellidos) as text
            FROM InformacionGeneral
            WHERE nombres LIKE ? OR apellidos LIKE ?
            ORDER BY apellidos, nombres
            LIMIT 30
        ";
        
        $stmt = $db->prepare($sql);
        $param = '%' . $busqueda . '%';
        $stmt->execute([$param, $param]);
        
        $miembros = $stmt->fetchAll();
        
        $this->jsonResponse([
            'results' => $miembros
        ]);
    }
    
    // Validar datos de miembro
    private function validarDatosMiembro($datos) {
        $errores = [];
        
        // Validar campos obligatorios
        if (empty($datos['general']['nombres'])) {
            $errores[] = 'El nombre es obligatorio';
        }
        
        if (empty($datos['general']['apellidos'])) {
            $errores[] = 'Los apellidos son obligatorios';
        }
        
        if (empty($datos['general']['celular'])) {
            $errores[] = 'El celular es obligatorio';
        } elseif (!preg_match('/^\+?[0-9]{8,15}$/', $datos['general']['celular'])) {
            $errores[] = 'El formato del celular no es válido';
        }
        
        // Validar email si está presente
        if (!empty($datos['contacto']['correo_electronico']) && 
            !filter_var($datos['contacto']['correo_electronico'], FILTER_VALIDATE_EMAIL)) {
            $errores[] = 'El formato del correo electrónico no es válido';
        }
        
        // Validar fecha de nacimiento
        if (!empty($datos['general']['fecha_nacimiento'])) {
            $fecha = \DateTime::createFromFormat('Y-m-d', $datos['general']['fecha_nacimiento']);
            if (!$fecha || $fecha->format('Y-m-d') !== $datos['general']['fecha_nacimiento']) {
                $errores[] = 'El formato de la fecha de nacimiento debe ser YYYY-MM-DD';
            }
        }
        
        return $errores;
    }
}
```

## 2. Implementación del Modelo Ministerio

```php
<?php
// filepath: c:\xampp\htdocs\ENCASA_DATABASE\app\models\Ministerio.php

namespace Models;

use Config\Database;

class Ministerio extends Model {
    protected static $table = 'Ministerios';
    
    // Obtener ministerios por líder
    public static function getMinisteriosByLider($lider_id) {
        $db = Database::getInstance();
        
        $sql = "SELECT * FROM Ministerios WHERE lider_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$lider_id]);
        
        return $stmt->fetchAll();
    }
    
    // Agregar miembro a ministerio
    public static function agregarMiembro($ministerio_id, $miembro_id, $rol_id) {
        $db = Database::getInstance();
        
        $sql = "
            INSERT INTO MiembrosMinisterios 
            (miembro_id, ministerio_id, rol_id, fecha_inicio) 
            VALUES (?, ?, ?, CURDATE())
        ";
        
        $stmt = $db->prepare($sql);
        return $stmt->execute([$miembro_id, $ministerio_id, $rol_id]);
    }
    
    // Eliminar asignaciones de un miembro
    public static function eliminarAsignacionesMiembro($miembro_id) {
        $db = Database::getInstance();
        
        $sql = "DELETE FROM MiembrosMinisterios WHERE miembro_id = ?";
        $stmt = $db->prepare($sql);
        
        return $stmt->execute([$miembro_id]);
    }
    
    // Verificar si un miembro es líder de un ministerio
    public static function esLider($ministerio_id, $miembro_id) {
        $db = Database::getInstance();
        
        $sql = "SELECT COUNT(*) as es_lider FROM Ministerios WHERE id = ? AND lider_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$ministerio_id, $miembro_id]);
        
        $resultado = $stmt->fetch();
        return $resultado['es_lider'] > 0;
    }
    
    // Obtener todos los miembros de un ministerio
    public static function getMiembros($ministerio_id) {
        $db = Database::getInstance();
        
        $sql = "
            SELECT m.*, ig.nombres, ig.apellidos, ig.celular, ig.foto, r.nombre as rol_nombre
            FROM MiembrosMinisterios m
            JOIN InformacionGeneral ig ON m.miembro_id = ig.id
            JOIN Roles r ON m.rol_id = r.id
            WHERE m.ministerio_id = ? AND (m.fecha_fin IS NULL OR m.fecha_fin > CURDATE())
            ORDER BY r.nivel_acceso DESC, ig.apellidos, ig.nombres
        ";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([$ministerio_id]);
        
        return $stmt->fetchAll();
    }
    
    // Obtener tareas de un ministerio
    public static function getTareas($ministerio_id) {
        $db = Database::getInstance();
        
        $sql = "
            SELECT t.*, COUNT(at.id) as asignaciones_totales,
            SUM(CASE WHEN at.fecha_completada IS NOT NULL THEN 1 ELSE 0 END) as asignaciones_completadas
            FROM Tareas t
            LEFT JOIN AsignacionTareas at ON t.id = at.tarea_id
            WHERE t.ministerio_id = ?
            GROUP BY t.id
            ORDER BY t.fecha_creacion DESC
        ";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([$ministerio_id]);
        
        return $stmt->fetchAll();
    }
}
```

## 3. Implementación del Modelo Rol

```php
<?php
// filepath: c:\xampp\htdocs\ENCASA_DATABASE\app\models\Rol.php

namespace Models;

class Rol extends Model {
    protected static $table = 'Roles';
    
    // Obtener roles por nivel de acceso mínimo
    public static function getRolesByNivelMinimo($nivel) {
        $db = \Config\Database::getInstance();
        
        $sql = "SELECT * FROM Roles WHERE nivel_acceso >= ? ORDER BY nivel_acceso DESC";
        $stmt = $db->prepare($sql);
        $stmt->execute([$nivel]);
        
        return $stmt->fetchAll();
    }
    
    // Verificar si un rol tiene un permiso específico
    public static function tienePermiso($rol_id, $permiso) {
        $db = \Config\Database::getInstance();
        
        $sql = "
            SELECT nivel_acceso 
            FROM Roles 
            WHERE id = ?
        ";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([$rol_id]);
        $rol = $stmt->fetch();
        
        if (!$rol) {
            return false;
        }
        
        // Mapeo de permisos a niveles mínimos requeridos
        $permisos_niveles = [
            'ver_todos_ministerios' => 5,
            'crear_ministerios' => 5,
            'editar_cualquier_ministerio' => 5,
            'ver_todos_miembros' => 5,
            'ver_su_ministerio' => 4,
            'editar_su_ministerio' => 4,
            'ver_miembros_ministerio' => 4,
            'ver_sus_tareas' => 3,
            'completar_tareas' => 3,
            'ver_info_personal' => 2
        ];
        
        $nivel_requerido = $permisos_niveles[$permiso] ?? 999; // Nivel imposible si no existe
        
        return $rol['nivel_acceso'] >= $nivel_requerido;
    }
}
```

## 4. Vista de Miembros (Listado)

```php
<?php 
// filepath: c:\xampp\htdocs\ENCASA_DATABASE\app\views\miembros\index.php
$pageTitle = 'Miembros de la Iglesia';
?>

<div class="container-fluid px-4">
    <h1 class="mt-4"><?= $pageTitle ?></h1>
    
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php 
            switch ($_GET['success']) {
                case 'miembro_creado':
                    echo 'El miembro ha sido creado exitosamente.';
                    break;
                case 'miembro_actualizado':
                    echo 'La información del miembro ha sido actualizada exitosamente.';
                    break;
                case 'miembro_eliminado':
                    echo 'El miembro ha sido eliminado exitosamente.';
                    break;
                default:
                    echo 'Operación completada exitosamente.';
            }
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php 
            switch ($_GET['error']) {
                case 'miembro_no_encontrado':
                    echo 'El miembro solicitado no fue encontrado.';
                    break;
                case 'permiso_denegado':
                    echo 'No tiene permisos para realizar esta acción.';
                    break;
                default:
                    echo 'Ha ocurrido un error: ' . htmlspecialchars($_GET['error']);
            }
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    <?php endif; ?>
    
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-users me-1"></i>
                    Listado de miembros
                </div>
                <?php if ($permisoTotal): ?>
                <div>
                    <a href="<?= BASE_URL ?>/miembros/create" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-1"></i> Nuevo miembro
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="card-body">
            <form action="<?= BASE_URL ?>/miembros" method="GET" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" class="form-control" placeholder="Buscar por nombre o celular" name="busqueda" value="<?= htmlspecialchars($filtros['busqueda']) ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" name="estado">
                            <option value="">-- Todos los estados --</option>
                            <option value="Activo" <?= ($filtros['estado'] === 'Activo') ? 'selected' : '' ?>>Activo</option>
                            <option value="Inactivo" <?= ($filtros['estado'] === 'Inactivo') ? 'selected' : '' ?>>Inactivo</option>
                            <option value="Nuevo" <?= ($filtros['estado'] === 'Nuevo') ? 'selected' : '' ?>>Nuevo</option>
                            <option value="Intermitente" <?= ($filtros['estado'] === 'Intermitente') ? 'selected' : '' ?>>Intermitente</option>
                        </select>
                    </div>
                    <?php if (count($ministerios) > 0): ?>
                    <div class="col-md-3">
                        <select class="form-select" name="ministerio_id">
                            <option value="">-- Todos los ministerios --</option>
                            <?php foreach ($ministerios as $ministerio): ?>
                                <option value="<?= $ministerio['id'] ?>" <?= ($filtros['ministerio_id'] == $ministerio['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($ministerio['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php endif; ?>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Nombre completo</th>
                            <th>Celular</th>
                            <th>Localidad</th>
                            <th>Estado</th>
                            <th>Fecha de ingreso</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($miembros) > 0): ?>
                            <?php foreach ($miembros as $miembro): ?>
                                <tr>
                                    <td>
                                        <?php if (!empty($miembro['foto'])): ?>
                                            <img src="<?= BASE_URL ?>/uploads/photos/<?= htmlspecialchars($miembro['foto']) ?>" class="avatar-mini me-2" alt="Foto">
                                        <?php else: ?>
                                            <div class="avatar-mini-placeholder me-2">
                                                <?= strtoupper(substr($miembro['nombres'], 0, 1) . substr($miembro['apellidos'], 0, 1)) ?>
                                            </div>
                                        <?php endif; ?>
                                        <?= htmlspecialchars($miembro['nombres'] . ' ' . $miembro['apellidos']) ?>
                                    </td>
                                    <td><?= htmlspecialchars($miembro['celular']) ?></td>
                                    <td><?= htmlspecialchars($miembro['localidad'] ?? 'N/A') ?></td>
                                    <td>
                                        <span class="badge bg-<?= getEstadoBadge($miembro['estado_espiritual']) ?>">
                                            <?= htmlspecialchars($miembro['estado_espiritual']) ?>
                                        </span>
                                    </td>
                                    <td><?= date('d/m/Y', strtotime($miembro['fecha_ingreso'])) ?></td>
                                    <td>
                                        <a href="<?= BASE_URL ?>/miembros/view/<?= $miembro['id'] ?>" class="btn btn-info btn-sm" title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <?php if ($permisoTotal): ?>
                                            <a href="<?= BASE_URL ?>/miembros/edit/<?= $miembro['id'] ?>" class="btn btn-primary btn-sm" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="<?= BASE_URL ?>/miembros/delete/<?= $miembro['id'] ?>" class="btn btn-danger btn-sm" title="Eliminar">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">No se encontraron miembros</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if ($paginacion['paginas'] > 1): ?>
                <nav>
                    <ul class="pagination justify-content-center">
                        <?php for ($i = 1; $i <= $paginacion['paginas']; $i++): ?>
                            <li class="page-item <?= ($i == $paginacion['pagina']) ? 'active' : '' ?>">
                                <a class="page-link" href="<?= BASE_URL ?>/miembros?pagina=<?= $i ?>&busqueda=<?= urlencode($filtros['busqueda']) ?>&estado=<?= urlencode($filtros['estado']) ?>&ministerio_id=<?= urlencode($filtros['ministerio_id']) ?>">
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
// Función auxiliar para asignar color a estado
function getEstadoBadge($estado) {
    switch ($estado) {
        case 'Activo':
            return 'success';
        case 'Inactivo':
            return 'danger';
        case 'Nuevo':
            return 'info';
        case 'Intermitente':
            return 'warning';
        default:
            return 'secondary';
    }
}
?>
```

## 5. Configuración del .htaccess para Reescritura de URLs

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    
    # Si el archivo o directorio solicitado existe, usar directamente
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    
    # Redirigir todas las demás solicitudes a index.php
    RewriteRule ^(.*)$ index.php?$1 [L,QSA]
</IfModule>

# Prevenir listado de directorios
Options -Indexes

# Configuraciones de seguridad
<IfModule mod_headers.c>
    # Protección contra XSS
    Header set X-XSS-Protection "1; mode=block"
    # Prevenir sniffing MIME
    Header set X-Content-Type-Options "nosniff"
    # Política de seguridad de contenido
    Header set Content-Security-Policy "default-src 'self'; script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com https://cdn.jsdelivr.net; img-src 'self' data:;"
</IfModule>

# Configurar caché para archivos estáticos
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType image/jpg "access plus 1 year"
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType image/gif "access plus 1 year"
    ExpiresByType image/webp "access plus 1 year"
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType text/javascript "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
</IfModule>
```

## 6. Layout Principal con Panel Lateral

```php
<?php 
// filepath: c:\xampp\htdocs\ENCASA_DATABASE\app\views\layouts\main.php

use Core\Auth;
$usuario = Auth::getCurrentUser();

// Verificar permisos
$permisoTotal = Auth::hasPermission('ver_todos_miembros');
$permisoMinisterios = Auth::hasPermission('ver_su_ministerio');
$permisoTareas = Auth::hasPermission('ver_sus_tareas');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= isset($pageTitle) ? $pageTitle . ' - ' : '' ?>Iglesia En Casa</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <!-- Estilos personalizados -->
    <link href="<?= ASSETS_URL ?>/css/styles.css" rel="stylesheet">
</head>
<body class="sb-nav-fixed">
    <!-- Barra de navegación superior -->
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Logo -->
        <a class="navbar-brand ps-3" href="<?= BASE_URL ?>">
            <img src="<?= ASSETS_URL ?>/img/logo.png" alt="Logo" height="30" class="d-inline-block align-text-top me-2">
            Iglesia En Casa
        </a>
        
        <!-- Botón de toggle para menú lateral en móviles -->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!">
            <i class="fas fa-bars"></i>
        </button>
        
        <!-- Buscador -->
        <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
            <div class="input-group">
                <input class="form-control" type="text" placeholder="Buscar..." aria-label="Buscar..." aria-describedby="btnNavbarSearch">
                <button class="btn btn-primary" id="btnNavbarSearch" type="button">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>
        
        <!-- Menú de usuario -->
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <?php if (!empty($usuario['foto'])): ?>
                        <img src="<?= BASE_URL ?>/uploads/photos/<?= htmlspecialchars($usuario['foto']) ?>" class="avatar-mini me-1" alt="Foto de perfil">
                    <?php else: ?>
                        <i class="fas fa-user fa-fw"></i>
                    <?php endif; ?>
                    <?= htmlspecialchars($usuario['nombres']) ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li>
                        <a class="dropdown-item" href="<?= BASE_URL ?>/miembros/view/<?= $usuario['miembro_id'] ?>">
                            <i class="fas fa-user-circle fa-fw me-2"></i>Mi perfil
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item" href="<?= BASE_URL ?>/logout">
                            <i class="fas fa-sign-out-alt fa-fw me-2"></i>Cerrar sesión
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
    
    <!-- Contenedor principal con menú lateral -->
    <div id="layoutSidenav">
        <!-- Menú lateral -->
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Principal</div>
                        <a class="nav-link" href="<?= BASE_URL ?>/dashboard">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard
                        </a>
                        
                        <!-- Miembros -->
                        <div class="sb-sidenav-menu-heading">Gestión</div>
                        <a class="nav-link" href="<?= BASE_URL ?>/miembros">
                            <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                            Miembros
                        </a>
                        
                        <!-- Ministerios -->
                        <?php if ($permisoMinisterios): ?>
                        <a class="nav-link" href="<?= BASE_URL ?>/ministerios">
                            <div class="sb-nav-link-icon"><i class="fas fa-hands-helping"></i></div>
                            Ministerios
                        </a>
                        <?php endif; ?>
                        
                        <!-- Tareas -->
                        <?php if ($permisoTareas): ?>
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseTareas" aria-expanded="false" aria-controls="collapseTareas">
                            <div class="sb-nav-link-icon"><i class="fas fa-tasks"></i></div>
                            Tareas
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseTareas" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <?php if ($permisoTotal): ?>
                                <a class="nav-link" href="<?= BASE_URL ?>/tareas">Todas las tareas</a>
                                <?php endif; ?>
                                <a class="nav-link" href="<?= BASE_URL ?>/tareas/mis-tareas">Mis tareas</a>
                            </nav>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Administración -->
                        <?php if ($permisoTotal): ?>
                        <div class="sb-sidenav-menu-heading">Administración</div>
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseAdmin" aria-expanded="false" aria-controls="collapseAdmin">
                            <div class="sb-nav-link-icon"><i class="fas fa-cogs"></i></div>
                            Configuración
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseAdmin" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="<?= BASE_URL ?>/usuarios">Usuarios</a>
                                <a class="nav-link" href="<?= BASE_URL ?>/roles">Roles</a>
                            </nav>
                        </div>
                        
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseReportes" aria-expanded="false" aria-controls="collapseReportes">
                            <div class="sb-nav-link-icon"><i class="fas fa-chart-bar"></i></div>
                            Reportes
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseReportes" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="<?= BASE_URL ?>/reportes/miembros">Miembros</a>
                                <a class="nav-link" href="<?= BASE_URL ?>/reportes/ministerios">Ministerios</a>
                                <a class="nav-link" href="<?= BASE_URL ?>/reportes/tareas">Tareas</a>
                            </nav>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Conectado como:</div>
                    <?= htmlspecialchars($usuario['rol_nombre']) ?>
                </div>
            </nav>
        </div>
        
        <!-- Contenido principal -->
        <div id="layoutSidenav_content">
            <main>
                <?= $content ?>
            </main>
            
            <!-- Pie de página -->
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; Iglesia En Casa <?= date('Y') ?></div>
                        <div>
                            <a href="#">Política de privacidad</a>
                            &middot;
                            <a href="#">Términos &amp; Condiciones</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core JS -->
    <script src="<?= ASSETS_URL ?>/js/scripts.js"></script>
</body>
</html>
```

Con la implementación de estos componentes, hemos completado un sistema CRUD completo para la gestión de miembros de la Iglesia En Casa, que incluye:

1. Una estructura MVC robusta con enrutamiento dinámico
2. Sistema de autenticación y autorización basado en roles
3. CRUD completo para miembros con relaciones a múltiples tablas
4. Gestión de ministerios y asignación de miembros
5. Interfaz con panel lateral fijo y carga dinámica de contenido
6. Protección de acceso según el rol del usuario

Este sistema permite a pastores, líderes de ministerio y miembros gestionar la información de manera eficiente y segura, adaptando las vistas según su nivel de acceso en la estructura de la iglesia.

Código similar encontrado con 3 tipos de licencias