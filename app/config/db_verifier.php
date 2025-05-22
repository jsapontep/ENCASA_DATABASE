<?php

require_once __DIR__ . '/database.php';

class DBVerifier {
    private $db;
    private $errors = [];
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function verifyDatabase() {
        try {
            // Verificar si la base de datos existe
            $this->db->query("USE IglesiaEnCasa");
            $this->verifyTables();
            $this->verifyConstraints();
            $this->verifyIndexes();
            
            if (empty($this->errors)) {
                return true;
            } else {
                $this->logErrors();
                return false;
            }
        } catch (PDOException $e) {
            $this->errors[] = "Error de base de datos: " . $e->getMessage();
            $this->logErrors();
            return false;
        }
    }
    
    private function verifyTables() {
        $tables = [
            'InformacionGeneral', 'Contacto', 'Roles', 'Ministerios', 
            'MiembrosMinisterios', 'Usuarios'
        ];
        
        foreach ($tables as $table) {
            try {
                $result = $this->db->query("SELECT 1 FROM `$table` LIMIT 1");
            } catch (PDOException $e) {
                $this->errors[] = "La tabla '$table' no existe o no es accesible.";
            }
        }
    }
    
    private function verifyConstraints() {
        $constraints = [
            ['InformacionGeneral', 'informacion_general_ibfk_1'],
            ['Contacto', 'contacto_ibfk_1'],
            ['Ministerios', 'ministerios_ibfk_1'],
            ['MiembrosMinisterios', 'miembrosministerios_ibfk_1'],
            ['MiembrosMinisterios', 'miembrosministerios_ibfk_2'],
            ['MiembrosMinisterios', 'miembrosministerios_ibfk_3'],
            ['Usuarios', 'usuarios_ibfk_1'],
            ['Usuarios', 'usuarios_ibfk_2']
        ];
        
        foreach ($constraints as $constraint) {
            try {
                $query = "
                    SELECT * FROM information_schema.TABLE_CONSTRAINTS 
                    WHERE CONSTRAINT_SCHEMA = 'IglesiaEnCasa' 
                    AND TABLE_NAME = ? 
                    AND CONSTRAINT_NAME = ?
                ";
                $stmt = $this->db->prepare($query);
                $stmt->execute([$constraint[0], $constraint[1]]);
                
                if ($stmt->rowCount() === 0) {
                    $this->errors[] = "La restricción '{$constraint[1]}' no existe en la tabla '{$constraint[0]}'.";
                }
            } catch (PDOException $e) {
                $this->errors[] = "Error verificando restricciones: " . $e->getMessage();
            }
        }
    }
    
    private function verifyIndexes() {
        $indexes = [
            ['InformacionGeneral', 'PRIMARY'],
            ['Contacto', 'PRIMARY'],
            ['Roles', 'PRIMARY'],
            ['Ministerios', 'PRIMARY'],
            ['Ministerios', 'lider_id'],
            ['MiembrosMinisterios', 'PRIMARY']
        ];
        
        foreach ($indexes as $index) {
            try {
                $query = "
                    SELECT * FROM information_schema.STATISTICS 
                    WHERE TABLE_SCHEMA = 'IglesiaEnCasa' 
                    AND TABLE_NAME = ? 
                    AND INDEX_NAME = ?
                ";
                $stmt = $this->db->prepare($query);
                $stmt->execute([$index[0], $index[1]]);
                
                if ($stmt->rowCount() === 0) {
                    $this->errors[] = "El índice '{$index[1]}' no existe en la tabla '{$index[0]}'.";
                }
            } catch (PDOException $e) {
                $this->errors[] = "Error verificando índices: " . $e->getMessage();
            }
        }
    }
    
    private function logErrors() {
        $logFile = APP_PATH . '/logs/db_verification.log';
        $directory = dirname($logFile);
        
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
        
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[$timestamp] Verificación de base de datos:" . PHP_EOL;
        
        foreach ($this->errors as $error) {
            $logMessage .= "- $error" . PHP_EOL;
        }
        
        file_put_contents($logFile, $logMessage, FILE_APPEND);
    }
    
    public function getErrors() {
        return $this->errors;
    }
}