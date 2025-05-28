<?php
// filepath: c:\xampp\htdocs\ENCASA_DATABASE\app\config\database.php

// Eliminar estas líneas que causan la redefinición de constantes
// define('DB_HOST', 'localhost');
// define('DB_NAME', 'IglesiaEnCasa');
// define('DB_USER', 'root');
// define('DB_PASS', '');

// Solo mantener las que no están en config.php
define('DB_PORT', '3306');
define('DB_CHARSET', 'utf8mb4');

// Clase Singleton para conexión a la base de datos
class Database {
    private static $instance = null;
    private $conn;
    
    private function __construct() {
        // Usar las constantes ya definidas en config.php
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            $this->conn = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            die('Error de conexión: ' . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->conn;
    }
}

// Crear una instancia de la base de datos global para uso general
$database = Database::getInstance();
$db = $database->getConnection();