<?php

namespace App\Models;

class SaludEmergencias {
    private $db;
    
    public function __construct() {
        $this->db = \Database::getInstance()->getConnection();
    }
    
    public function actualizarPorMiembroId($miembroId, $datos) {
        // Verificar si ya existe un registro
        $stmt = $this->db->prepare("SELECT id FROM SaludEmergencias WHERE miembro_id = ?");
        $stmt->execute([$miembroId]);
        $existente = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        if ($existente) {
            // Actualizar registro existente
            $campos = [];
            $valores = [];
            
            foreach ($datos as $campo => $valor) {
                $campos[] = "$campo = ?";
                $valores[] = $valor;
            }
            
            $valores[] = $miembroId; // Para el WHERE
            
            $sql = "UPDATE SaludEmergencias SET " . implode(', ', $campos) . " WHERE miembro_id = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($valores);
        } else {
            // Crear nuevo registro
            $datos['miembro_id'] = $miembroId;
            
            $campos = array_keys($datos);
            $placeholders = array_fill(0, count($campos), '?');
            
            $sql = "INSERT INTO SaludEmergencias (" . implode(', ', $campos) . ") VALUES (" . implode(', ', $placeholders) . ")";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute(array_values($datos));
        }
    }
}