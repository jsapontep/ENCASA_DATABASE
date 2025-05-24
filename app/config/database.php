<?php

// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_NAME', 'IglesiaEnCasa');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_PORT', '3306');
define('DB_CHARSET', 'utf8mb4');

// Clase Singleton para conexión a la base de datos
class Database {
    private static $instance = null;
    private $connection;
    
    private function __construct() {
        try {
            $dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=".DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ];
            $this->connection = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            $this->logError($e->getMessage()); // Pasamos el mensaje de error
            die("Error de conexión a la base de datos: " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->connection;
    }
    
    private function logError($errorMessage) {
        // Verificar si APP_PATH está definido, si no, usar una ruta relativa
        $logPath = defined('APP_PATH') ? APP_PATH . '/logs/database_error.log' : __DIR__ . '/../../logs/database_error.log';
        
        // Asegurarse que el directorio existe
        $logDir = dirname($logPath);
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        // Usar el mensaje recibido como parámetro en lugar de connection->errorInfo
        $logMessage = date('Y-m-d H:i:s') . " - MySQL Error: " . $errorMessage . PHP_EOL;
        file_put_contents($logPath, $logMessage, FILE_APPEND);
    }
}