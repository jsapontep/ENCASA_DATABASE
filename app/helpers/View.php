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