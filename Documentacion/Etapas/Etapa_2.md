# Etapa 2: Núcleo MVC y Enrutamiento

Esta etapa implementa la estructura central del patrón Modelo-Vista-Controlador y el sistema de enrutamiento para manejar las solicitudes HTTP.

## Componentes a implementar:

1. **Sistema de enrutamiento avanzado**
   - Mapeo de URLs a controladores y acciones
   - Soporte para parámetros en rutas
   - Gestión de rutas protegidas

2. **Controladores**
   - Controlador base abstracto
   - Controladores específicos para cada entidad
   - Métodos CRUD estándar

3. **Modelos base**
   - Modelo abstracto con métodos genéricos
   - Validación de datos
   - Manejo de relaciones

4. **Sistema de vistas**
   - Motor de plantillas básico
   - Layouts reutilizables
   - Parciales y componentes

   # Implementación de la Etapa 2: Núcleo MVC y Enrutamiento

Ahora que la Etapa 1 está completa y funcionando, vamos a implementar la estructura MVC y el sistema de enrutamiento avanzado. Te guiaré paso a paso:

## 1. Sistema de Enrutamiento Avanzado

### 1.1 Clase Router mejorada

```php
<?php
namespace App\Helpers;

class Router {
    private $routes = [];
    private $params = [];
    private $notFoundCallback;
    
    /**
     * Añade una ruta al sistema
     */
    public function add($route, $controller, $action, $methods = ['GET'], $middleware = []) {
        // Convertir ruta a expresión regular para capturar parámetros
        $route = preg_replace('/\//', '\\/', $route);
        $route = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z0-9-]+)', $route);
        $route = '/^' . $route . '$/i';
        
        $this->routes[] = [
            'route' => $route,
            'controller' => $controller,
            'action' => $action,
            'methods' => $methods,
            'middleware' => $middleware
        ];
    }
    
    /**
     * Registra callback para rutas no encontradas
     */
    public function setNotFound($callback) {
        $this->notFoundCallback = $callback;
    }
    
    /**
     * Método abreviado para rutas GET
     */
    public function get($route, $controller, $action, $middleware = []) {
        $this->add($route, $controller, $action, ['GET'], $middleware);
    }
    
    /**
     * Método abreviado para rutas POST
     */
    public function post($route, $controller, $action, $middleware = []) {
        $this->add($route, $controller, $action, ['POST'], $middleware);
    }
    
    /**
     * Método abreviado para rutas PUT
     */
    public function put($route, $controller, $action, $middleware = []) {
        $this->add($route, $controller, $action, ['PUT'], $middleware);
    }
    
    /**
     * Método abreviado para rutas DELETE
     */
    public function delete($route, $controller, $action, $middleware = []) {
        $this->add($route, $controller, $action, ['DELETE'], $middleware);
    }
    
    /**
     * Despacha la ruta actual
     */
    public function dispatch() {
        $url = $this->getUrl();
        $method = $_SERVER['REQUEST_METHOD'];
        
        // Si se envía _method en un formulario, usar ese método
        if ($method == 'POST' && isset($_POST['_method'])) {
            $method = strtoupper($_POST['_method']);
        }
        
        foreach ($this->routes as $route) {
            if (preg_match($route['route'], $url, $matches)) {
                // Verificar método HTTP
                if (!in_array($method, $route['methods'])) {
                    continue; // Método no permitido, seguir buscando
                }
                
                // Extraer los parámetros de la URL
                foreach ($matches as $key => $value) {
                    if (is_string($key)) {
                        $this->params[$key] = $value;
                    }
                }
                
                // Ejecutar middleware si existe
                foreach ($route['middleware'] as $middleware) {
                    $middlewareClass = "App\\Middleware\\$middleware";
                    if (class_exists($middlewareClass)) {
                        $middlewareObj = new $middlewareClass();
                        $result = $middlewareObj->handle();
                        if (!$result) {
                            // Si el middleware retorna falso, detener la ejecución
                            return;
                        }
                    }
                }
                
                // Cargar el controlador
                $controllerName = "App\\Controllers\\{$route['controller']}Controller";
                if (class_exists($controllerName)) {
                    $controller = new $controllerName();
                    $action = $route['action'];
                    
                    if (method_exists($controller, $action)) {
                        // Ejecutar la acción del controlador
                        $controller->$action($this->params);
                        return;
                    }
                }
                
                // Si llegamos aquí, el controlador o acción no existen
                $this->handleNotFound();
                return;
            }
        }
        
        // No se encontró ninguna ruta
        $this->handleNotFound();
    }
    
    /**
     * Maneja la situación de ruta no encontrada
     */
    private function handleNotFound() {
        if ($this->notFoundCallback) {
            call_user_func($this->notFoundCallback);
        } else {
            header("HTTP/1.0 404 Not Found");
            echo '<h1>404 - Página no encontrada</h1>';
            echo '<p>La página que estás buscando no existe.</p>';
        }
    }
    
    /**
     * Obtiene la URL actual
     */
    private function getUrl() {
        $url = isset($_GET['url']) ? $_GET['url'] : '';
        $url = rtrim($url, '/');
        $url = filter_var($url, FILTER_SANITIZE_URL);
        return $url;
    }
    
    /**
     * Obtiene los parámetros de la URL
     */
    public function getParams() {
        return $this->params;
    }
    
    /**
     * Redirige a una URL
     */
    public static function redirect($url) {
        header('Location: ' . APP_URL . '/' . $url);
        exit;
    }
}
```

### 1.2 Configuración de rutas

```php
<?php
// filepath: /Applications/XAMPP/xamppfiles/htdocs/Encasa_Database/app/config/routes.php

use App\Helpers\Router;

// Instancia del router
$router = new Router();

// Página principal
$router->get('', 'Home', 'index');
$router->get('home', 'Home', 'index');

// Rutas de miembros
$router->get('miembros', 'Miembros', 'index', ['Auth']);
$router->get('miembros/crear', 'Miembros', 'crear', ['Auth']);
$router->post('miembros/guardar', 'Miembros', 'guardar', ['Auth']);
$router->get('miembros/{id}', 'Miembros', 'ver', ['Auth']);
$router->get('miembros/{id}/editar', 'Miembros', 'editar', ['Auth']);
$router->post('miembros/{id}/actualizar', 'Miembros', 'actualizar', ['Auth']);
$router->post('miembros/{id}/eliminar', 'Miembros', 'eliminar', ['Auth', 'AdminOnly']);

// Rutas de ministerios
$router->get('ministerios', 'Ministerios', 'index', ['Auth']);
$router->get('ministerios/crear', 'Ministerios', 'crear', ['Auth']);
$router->post('ministerios/guardar', 'Ministerios', 'guardar', ['Auth']);
$router->get('ministerios/{id}', 'Ministerios', 'ver', ['Auth']);
$router->get('ministerios/{id}/editar', 'Ministerios', 'editar', ['Auth']);
$router->post('ministerios/{id}/actualizar', 'Ministerios', 'actualizar', ['Auth']);
$router->post('ministerios/{id}/eliminar', 'Ministerios', 'eliminar', ['Auth', 'AdminOnly']);

// Rutas de autenticación
$router->get('login', 'Auth', 'login');
$router->post('auth/login', 'Auth', 'authenticate');
$router->get('logout', 'Auth', 'logout');
$router->get('registro', 'Auth', 'register');
$router->post('auth/registro', 'Auth', 'store');

// Rutas de error
$router->setNotFound(function() {
    include VIEW_PATH . '/errors/404.php';
});

return $router;
```

## 2. Implementación de Controladores

### 2.1 Controlador Base

```php
<?php
namespace App\Controllers;

abstract class Controller {
    protected $view;
    protected $model;
    
    public function __construct() {
        $this->view = new \App\Helpers\View();
    }
    
    /**
     * Método para renderizar una vista
     */
    protected function render($view, $data = []) {
        $this->view->render($view, $data);
    }
    
    /**
     * Método para renderizar una vista con un layout
     */
    protected function renderWithLayout($view, $layout = 'default', $data = []) {
        $this->view->renderWithLayout($view, $layout, $data);
    }
    
    /**
     * Método para responder con JSON (API)
     */
    protected function jsonResponse($data, $statusCode = 200) {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }
    
    /**
     * Método para validar datos de formulario
     */
    protected function validate($data, $rules) {
        $validator = new \App\Helpers\Validator();
        return $validator->validate($data, $rules);
    }
    
    /**
     * Método para redireccionar
     */
    protected function redirect($url) {
        \App\Helpers\Router::redirect($url);
    }
    
    /**
     * Método para obtener el usuario actual
     */
    protected function getCurrentUser() {
        if (isset($_SESSION['user_id'])) {
            $userModel = new \App\Models\Usuario();
            return $userModel->findById($_SESSION['user_id']);
        }
        return null;
    }
    
    /**
     * Método para verificar si el usuario está autenticado
     */
    protected function isAuthenticated() {
        return isset($_SESSION['user_id']);
    }
}
```

### 2.2 Controlador de ejemplo: HomeController

```php
<?php
namespace App\Controllers;

class HomeController extends Controller {
    public function index() {
        $title = 'Bienvenido a Iglesia En Casa';
        $this->renderWithLayout('home/index', 'default', [
            'title' => $title,
            'user' => $this->getCurrentUser()
        ]);
    }
}
```

## 3. Implementación de Modelos Base

### 3.1 Modelo Base Abstracto

```php
<?php
namespace App\Models;

abstract class Model {
    protected $db;
    protected $table;
    protected $primaryKey = 'id';
    protected $fillable = []; // Campos que se pueden asignar masivamente
    protected $guarded = []; // Campos protegidos contra asignación masiva
    
    public function __construct() {
        $this->db = \Database::getInstance()->getConnection();
    }
    
    /**
     * Encuentra un registro por su ID
     */
    public function findById($id) {
        $query = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    /**
     * Obtiene todos los registros
     */
    public function getAll() {
        $query = "SELECT * FROM {$this->table}";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Crea un nuevo registro
     */
    public function create(array $data) {
        // Filtrar solo los campos permitidos
        $data = $this->filterFields($data);
        
        if (empty($data)) {
            return false;
        }
        
        $fields = array_keys($data);
        $placeholders = array_map(function($field) {
            return ':' . $field;
        }, $fields);
        
        $query = "INSERT INTO {$this->table} (" . implode(', ', $fields) . ") 
                  VALUES (" . implode(', ', $placeholders) . ")";
        
        $stmt = $this->db->prepare($query);
        
        foreach ($data as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }
        
        $stmt->execute();
        return $this->db->lastInsertId();
    }
    
    /**
     * Actualiza un registro existente
     */
    public function update($id, array $data) {
        // Filtrar solo los campos permitidos
        $data = $this->filterFields($data);
        
        if (empty($data)) {
            return false;
        }
        
        $fields = array_map(function($field) {
            return $field . ' = :' . $field;
        }, array_keys($data));
        
        $query = "UPDATE {$this->table} SET " . implode(', ', $fields) . " 
                  WHERE {$this->primaryKey} = :id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $id);
        
        foreach ($data as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }
        
        return $stmt->execute();
    }
    
    /**
     * Elimina un registro
     */
    public function delete($id) {
        $query = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $id);
        return $stmt->execute();
    }
    
    /**
     * Filtra los campos según las reglas de fillable y guarded
     */
    protected function filterFields(array $data) {
        if (!empty($this->fillable)) {
            return array_intersect_key($data, array_flip($this->fillable));
        }
        
        if (!empty($this->guarded)) {
            return array_diff_key($data, array_flip($this->guarded));
        }
        
        return $data;
    }
    
    /**
     * Encuentra registros por condición
     */
    public function findWhere($field, $value) {
        $query = "SELECT * FROM {$this->table} WHERE {$field} = :value";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':value', $value);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Encuentra un único registro por condición
     */
    public function findOneWhere($field, $value) {
        $query = "SELECT * FROM {$this->table} WHERE {$field} = :value LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':value', $value);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    /**
     * Cuenta registros totales
     */
    public function count() {
        $query = "SELECT COUNT(*) as count FROM {$this->table}";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch();
        return (int) $result['count'];
    }
    
    /**
     * Ejecuta una consulta personalizada
     */
    public function query($sql, $params = []) {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
}
```

### 3.2 Ejemplo de modelo específico: Miembro

```php
<?php
namespace App\Models;

class Miembro extends Model {
    protected $table = 'InformacionGeneral';
    protected $fillable = [
        'nombres', 'apellidos', 'celular', 'localidad', 'barrio', 
        'fecha_nacimiento', 'invitado_por', 'conector', 'estado_espiritual',
        'recorrido_espiritual', 'foto', 'habeas_data'
    ];
    
    /**
     * Obtiene todos los miembros con información básica
     */
    public function getAllWithBasicInfo() {
        $sql = "SELECT m.id, m.nombres, m.apellidos, m.celular, m.localidad, 
                m.barrio, m.fecha_ingreso, m.estado_espiritual
                FROM {$this->table} m
                ORDER BY m.apellidos, m.nombres";
                
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Obtiene un miembro con toda su información relacionada
     */
    public function getFullProfile($id) {
        // Información general
        $miembro = $this->findById($id);
        
        if (!$miembro) {
            return null;
        }
        
        // Información de contacto
        $sql = "SELECT * FROM Contacto WHERE miembro_id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $miembro['contacto'] = $stmt->fetch();
        
        // Ministerios
        $sql = "SELECT mm.*, m.nombre as ministerio_nombre, r.nombre as rol_nombre
                FROM MiembrosMinisterios mm
                JOIN Ministerios m ON mm.ministerio_id = m.id
                JOIN Roles r ON mm.rol_id = r.id
                WHERE mm.miembro_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $miembro['ministerios'] = $stmt->fetchAll();
        
        return $miembro;
    }
    
    /**
     * Guarda un nuevo miembro con su información de contacto
     */
    public function saveWithContacto($miembroData, $contactoData) {
        try {
            $this->db->beginTransaction();
            
            // Crear miembro
            $miembroId = $this->create($miembroData);
            
            if (!$miembroId) {
                throw new \Exception("Error al crear el miembro");
            }
            
            // Crear contacto
            $contactoData['miembro_id'] = $miembroId;
            $contactoModel = new \App\Models\Contacto();
            $contactoId = $contactoModel->create($contactoData);
            
            if (!$contactoId) {
                throw new \Exception("Error al crear la información de contacto");
            }
            
            $this->db->commit();
            return $miembroId;
            
        } catch (\Exception $e) {
            $this->db->rollBack();
            // Registrar el error
            error_log($e->getMessage());
            return false;
        }
    }
}
```

## 4. Implementación del Sistema de Vistas

### 4.1 Clase View

```php
<?php
namespace App\Helpers;

class View {
    /**
     * Renderiza una vista
     */
    public function render($view, $data = []) {
        // Extraer variables para que estén disponibles en la vista
        extract($data);
        
        // Incluir la vista
        $viewPath = VIEW_PATH . '/' . $view . '.php';
        
        if (file_exists($viewPath)) {
            include $viewPath;
        } else {
            throw new \Exception("Vista no encontrada: {$view}");
        }
    }
    
    /**
     * Renderiza una vista con un layout
     */
    public function renderWithLayout($view, $layout = 'default', $data = []) {
        // Extraer variables para que estén disponibles en la vista y el layout
        extract($data);
        
        // Capturar el contenido de la vista
        ob_start();
        $this->render($view, $data);
        $content = ob_get_clean();
        
        // Incluir el layout
        $layoutPath = VIEW_PATH . '/layouts/' . $layout . '.php';
        
        if (file_exists($layoutPath)) {
            include $layoutPath;
        } else {
            throw new \Exception("Layout no encontrado: {$layout}");
        }
    }
    
    /**
     * Incluye una vista parcial
     */
    public static function partial($partial, $data = []) {
        extract($data);
        
        $partialPath = VIEW_PATH . '/partials/' . $partial . '.php';
        
        if (file_exists($partialPath)) {
            include $partialPath;
        } else {
            throw new \Exception("Partial no encontrado: {$partial}");
        }
    }
}
```

### 4.2 Layout básico (default.php)

```php
<?php
// filepath: /Applications/XAMPP/xamppfiles/htdocs/Encasa_Database/app/views/layouts/default.php
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title . ' - ' . APP_NAME : APP_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= APP_URL ?>/app/public/css/styles.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="<?= APP_URL ?>">Iglesia En Casa</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= APP_URL ?>">Inicio</a>
                    </li>
                    <?php if (isset($user)): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= APP_URL ?>/miembros">Miembros</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= APP_URL ?>/ministerios">Ministerios</a>
                        </li>
                    <?php endif; ?>
                </ul>
                <ul class="navbar-nav">
                    <?php if (isset($user)): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <?= htmlspecialchars($user['nombres']) ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="<?= APP_URL ?>/perfil">Mi Perfil</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?= APP_URL ?>/logout">Cerrar Sesión</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= APP_URL ?>/login">Iniciar Sesión</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container my-4">
        <?php if (isset($_SESSION['flash_message'])): ?>
            <div class="alert alert-<?= $_SESSION['flash_type'] ?? 'info' ?> alert-dismissible fade show">
                <?= $_SESSION['flash_message'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['flash_message'], $_SESSION['flash_type']); ?>
        <?php endif; ?>
        
        <?= $content ?>
    </main>

    <footer class="bg-dark text-white py-4 mt-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <p>&copy; <?= date('Y') ?> - Iglesia En Casa</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p>Desarrollado con ❤️</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= APP_URL ?>/app/public/js/main.js"></script>
</body>
</html>
```

### 4.3 Vista de ejemplo: home/index.php

```php
<?php
// filepath: /Applications/XAMPP/xamppfiles/htdocs/Encasa_Database/app/views/home/index.php
?>
<div class="jumbotron bg-light p-5 rounded">
    <h1 class="display-4">Bienvenido a Iglesia En Casa</h1>
    <p class="lead">Sistema de gestión de información para la comunidad</p>
    <hr class="my-4">
    
    <?php if (isset($user)): ?>
        <p>¿Qué te gustaría hacer hoy?</p>
        <div class="row">
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Miembros</h5>
                        <p class="card-text">Gestionar la información de los miembros de la iglesia.</p>
                        <a href="<?= APP_URL ?>/miembros" class="btn btn-primary">Ver miembros</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Ministerios</h5>
                        <p class="card-text">Administrar los ministerios y sus miembros.</p>
                        <a href="<?= APP_URL ?>/ministerios" class="btn btn-primary">Ver ministerios</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Mi Perfil</h5>
                        <p class="card-text">Ver y editar tu información personal.</p>
                        <a href="<?= APP_URL ?>/perfil" class="btn btn-primary">Ver perfil</a>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <p>Por favor, inicia sesión para acceder al sistema.</p>
        <a class="btn btn-primary btn-lg" href="<?= APP_URL ?>/login" role="button">Iniciar sesión</a>
    <?php endif; ?>
</div>
```

## 5. Actualización del index.php (punto de entrada)

```php
<?php
// filepath: /Applications/XAMPP/xamppfiles/htdocs/Encasa_Database/index.php

// Configurar visualización de errores para desarrollo
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Iniciar sesión
session_start();

// Definir constantes del sistema
define('BASE_PATH', __DIR__);
define('APP_PATH', BASE_PATH . '/app');
define('CONTROLLER_PATH', APP_PATH . '/controllers');
define('MODEL_PATH', APP_PATH . '/models');
define('VIEW_PATH', APP_PATH . '/views');
define('CONFIG_PATH', APP_PATH . '/config');

// Cargar configuración
require_once CONFIG_PATH . '/config.php';
require_once CONFIG_PATH . '/database.php';
require_once CONFIG_PATH . '/autoload.php';

// Cargar y ejecutar el router
$router = require_once CONFIG_PATH . '/routes.php';
$router->dispatch();
```

¿Quieres que te ayude a implementar alguna parte específica de la Etapa 2?

Código similar encontrado con 2 tipos de licencias