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
    public function get($route, $controllerAction, $middleware = []) {
        if (strpos($controllerAction, '@') !== false) {
            list($controller, $action) = explode('@', $controllerAction);
            $this->add($route, $controller, $action, ['GET'], $middleware);
        } else {
            // Mantener compatibilidad con la forma antigua
            $controller = $controllerAction;
            $action = func_get_arg(2);
            $middleware = func_num_args() > 3 ? func_get_arg(3) : [];
            $this->add($route, $controller, $action, ['GET'], $middleware);
        }
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
        // Obtener la ruta solicitada
        $url = $_SERVER['REQUEST_URI'] ?? '';
        
        // Eliminar la base del proyecto de la URL
        $base = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']);
        $url = str_replace($base, '', $url);
        
        // Eliminar la barra inicial y parámetros GET
        $url = ltrim($url, '/');
        $url = explode('?', $url)[0];
        
        // Debug para ver qué URL se está procesando
        error_log("URL a procesar: '$url'");
        
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
                        // Si esperamos un ID, extraerlo del array de parámetros
                        if ($action === 'ver' || $action === 'editar' || $action === 'actualizar' || $action === 'eliminar') {
                            // Extraer ID del array de parámetros
                            $id = !empty($this->params) ? array_values($this->params)[0] : null;
                            $controller->$action($id);
                        } else {
                            // Para otras acciones que no requieren ID
                            // Extraer parámetros si existen
                            if (!empty($this->params)) {
                                // Convertir parámetros asociativos a indexados para pasarlos como argumentos
                                $params = array_values($this->params);
                                $controller->$action(...$params);
                            } else {
                                // Sin parámetros
                                $controller->$action();
                            }
                        }
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
     * Obtiene la URL current
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
     * @param string $url Ruta relativa a la que redirigir
     */
    public static function redirect($url) {
        // Usar la función url() para generar URLs consistentes
        if (function_exists('url')) {
            header('Location: ' . url($url));
        } else {
            // Fallback si la función url() no existe
            $baseUrl = defined('APP_URL') ? APP_URL : '/ENCASA_DATABASE';
            header('Location: ' . rtrim($baseUrl, '/') . '/' . ltrim($url, '/'));
        }
        exit;
    }
}
