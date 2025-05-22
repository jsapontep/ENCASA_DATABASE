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