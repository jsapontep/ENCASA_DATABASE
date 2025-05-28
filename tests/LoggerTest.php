<?php

// Incluir el autoloader
require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../app/helpers/Logger.php';
require_once __DIR__ . '/../app/helpers/functions.php';

use App\Helpers\Logger;

// Clase de pruebas simple
class LoggerTest {
    public $logFile; // Cambiar de private a public
    
    public function __construct() {
        $this->logFile = dirname(__DIR__) . '/app/logs/' . date('Y-m-d') . '.log';
    }
    
    // Añadir este método getter
    public function getLogFile() {
        return $this->logFile;
    }
    
    public function testSingleton() {
        $instance1 = Logger::getInstance();
        $instance2 = Logger::getInstance();
        
        // Verificar que ambas instancias son el mismo objeto
        echo "Prueba Singleton: ";
        echo ($instance1 === $instance2) ? "PASÓ ✓" : "FALLÓ ✗";
        echo "\n";
    }
    
    public function testLogLevels() {
        // Limpiar archivo de log para la prueba
        if (file_exists($this->logFile)) {
            $initialSize = filesize($this->logFile);
        } else {
            $initialSize = 0;
        }
        
        // Registrar mensajes en diferentes niveles
        log_error("Mensaje de error de prueba");
        log_warning("Mensaje de advertencia de prueba");
        log_info("Mensaje informativo de prueba");
        log_debug("Mensaje de depuración de prueba");
        
        // Verificar que se escribieron los logs
        clearstatcache();
        $newSize = file_exists($this->logFile) ? filesize($this->logFile) : 0;
        
        echo "Prueba Logging: ";
        echo ($newSize > $initialSize) ? "PASÓ ✓" : "FALLÓ ✗";
        echo "\n";
        
        // Verificar contenido
        $content = file_get_contents($this->logFile);
        
        echo "Contenido Error: ";
        echo (strpos($content, "ERROR") !== false) ? "PASÓ ✓" : "FALLÓ ✗";
        echo "\n";
        
        echo "Contenido Warning: ";
        echo (strpos($content, "WARNING") !== false) ? "PASÓ ✓" : "FALLÓ ✗";
        echo "\n";
    }
    
    public function runTests() {
        $this->testSingleton();
        $this->testLogLevels();
    }
}

// Ejecutar pruebas
$test = new LoggerTest();
$test->runTests();

echo "\nPruebas completadas. Verifica el archivo de log en: " . $test->logFile;