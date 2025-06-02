<?php
namespace App\Models;

use App\Core\Database;

class EducationModel {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    // Obtener datos educativos por país
    public function getEducationDataByCountry($pais) {
        // Structure to hold all education data
        $educationData = [
            'Primaria' => ['instituciones' => []],
            'Bachillerato' => ['instituciones' => []],
            'Técnico' => ['instituciones' => []],
            'Tecnólogo' => ['instituciones' => []],
            'Universitario' => ['instituciones' => []],
            'Especialización' => ['instituciones' => []],
            'Maestría' => ['instituciones' => []],
            'Doctorado' => ['instituciones' => []]
        ];
        
        // Get institutions by country and level
        $sql = "SELECT id, nivel_educativo, nombre FROM instituciones_educativas 
                WHERE pais = ? ORDER BY nivel_educativo, nombre";
        
        $this->db->query($sql);
        $this->db->bind(1, $pais);
        $instituciones = $this->db->resultSet();
        
        foreach ($instituciones as $institucion) {
            $nivel = $institucion['nivel_educativo'];
            
            // Get professions for this institution
            $sqlProfessions = "SELECT id, nombre FROM profesiones 
                              WHERE institucion_id = ? ORDER BY nombre";
            $this->db->query($sqlProfessions);
            $this->db->bind(1, $institucion['id']);
            $profesiones = $this->db->resultSet();
            
            // Add to data structure
            if (isset($educationData[$nivel])) {
                $educationData[$nivel]['instituciones'][] = [
                    'id' => $institucion['id'],
                    'nombre' => $institucion['nombre'],
                    'profesiones' => $profesiones
                ];
            }
        }
        
        return $educationData;
    }
    
    // Guardar nueva institución
    public function saveInstitution($pais, $nivel, $nombre) {
        // Verificar si ya existe
        $sql = "SELECT id FROM instituciones_educativas 
                WHERE pais = ? AND nivel_educativo = ? AND nombre = ?";
        $this->db->query($sql);
        $this->db->bind(1, $pais);
        $this->db->bind(2, $nivel);
        $this->db->bind(3, $nombre);
        $existente = $this->db->single();
        
        if ($existente) {
            return ['id' => $existente['id'], 'nombre' => $nombre, 'existe' => true];
        }
        
        // Insertar nueva institución
        $sql = "INSERT INTO instituciones_educativas (pais, nivel_educativo, nombre) 
                VALUES (?, ?, ?)";
        $this->db->query($sql);
        $this->db->bind(1, $pais);
        $this->db->bind(2, $nivel);
        $this->db->bind(3, $nombre);
        
        if ($this->db->execute()) {
            $newId = $this->db->lastInsertId();
            return ['id' => $newId, 'nombre' => $nombre, 'existe' => false];
        } else {
            return ['error' => 'No se pudo guardar la institución'];
        }
    }
    
    // Guardar nueva profesión
    public function saveProfession($pais, $nivel, $institucionId, $nombre) {
        // Verificar si ya existe
        $sql = "SELECT id FROM profesiones 
                WHERE institucion_id = ? AND nombre = ?";
        $this->db->query($sql);
        $this->db->bind(1, $institucionId);
        $this->db->bind(2, $nombre);
        $existente = $this->db->single();
        
        if ($existente) {
            return ['id' => $existente['id'], 'nombre' => $nombre, 'existe' => true];
        }
        
        // Insertar nueva profesión
        $sql = "INSERT INTO profesiones (institucion_id, nombre) 
                VALUES (?, ?)";
        $this->db->query($sql);
        $this->db->bind(1, $institucionId);
        $this->db->bind(2, $nombre);
        
        if ($this->db->execute()) {
            $newId = $this->db->lastInsertId();
            return ['id' => $newId, 'nombre' => $nombre, 'existe' => false];
        } else {
            return ['error' => 'No se pudo guardar la profesión'];
        }
    }
}