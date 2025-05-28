<?php
namespace App\Models;

abstract class Model {
    protected $db;
    protected $table;
    protected $fillable = [];
    
    public function __construct() {
        $database = \Database::getInstance(); // Usamos el namespace global
        $this->db = $database->getConnection();
    }
    
    /**
     * Encuentra un registro por su ID
     */
    public function findById($id) {
        $query = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1";
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
    public function update($id, $data) {
        $fields = [];
        foreach ($data as $key => $value) {
            $fields[] = "{$key} = :{$key}";
        }
        
        $query = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = $this->db->prepare($query);
        
        foreach ($data as $key => $value) {
            $stmt->bindValue(":{$key}", $value);
        }
        
        $stmt->bindValue(':id', $id);
        return $stmt->execute();
    }
    
    /**
     * Elimina un registro
     */
    public function delete($id) {
        $query = "DELETE FROM {$this->table} WHERE id = :id";
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
    
    /**
     * Obtiene una fila de la base de datos
     * 
     * @param string $sql Consulta SQL
     * @param array $params Parámetros para la consulta
     * @return array|null Fila encontrada o null
     */
    protected function getRow($sql, $params = []) {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}