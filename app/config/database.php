<?php
// filepath: c:\xampp\htdocs\ENCASA_DATABASE\app\config\database.php

// Definir constantes si no existen
if (!defined('DB_HOST')) define('DB_HOST', 'localhost');
if (!defined('DB_USER')) define('DB_USER', 'root'); // Usuario predeterminado de XAMPP
if (!defined('DB_PASS')) define('DB_PASS', ''); // Contraseña en blanco por defecto en XAMPP
if (!defined('DB_NAME')) define('DB_NAME', 'iglesiaencasa'); // Nombre de tu base de datos (cambio importante)

// Solo mantener las que no están en config.php
define('DB_PORT', '3306');
define('DB_CHARSET', 'utf8mb4');

/**
 * Clase Database con patrón singleton para la conexión
 */
class Database {
    private static $instance = null;
    private $connection = null;
    
    private function __construct() {
        try {
            // Usar las constantes en vez de valores hardcodeados
            $this->connection = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", 
                DB_USER,
                DB_PASS
            );
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            file_put_contents(__DIR__ . '/../../db_error.log', date('Y-m-d H:i:s') . ' - ' . $e->getMessage() . "\n", FILE_APPEND);
            throw new Exception("Error en la conexión a la base de datos: " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->connection;
    }
}

// Crear una instancia de la base de datos global para uso general
$database = Database::getInstance();
$db = $database->getConnection();