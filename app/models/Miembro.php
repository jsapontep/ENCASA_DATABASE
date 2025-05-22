<?php
namespace App\Models;

class Miembro extends Model {
    protected $table = 'InformacionGeneral';
    protected $fillable = [
        'nombres', 'apellidos', 'celular', 'localidad', 'barrio', 
        'fecha_nacimiento', 'invitado_por', 'conector', 'estado_espiritual',
        'recorrido_espiritual', 'foto', 'habeas_data'
    ];
    
    /**
     * Obtiene todos los miembros con información básica
     */
    public function getAllWithBasicInfo() {
        $sql = "SELECT m.id, m.nombres, m.apellidos, m.celular, m.localidad, 
                m.barrio, m.fecha_ingreso, m.estado_espiritual
                FROM {$this->table} m
                ORDER BY m.apellidos, m.nombres";
                
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Obtiene un miembro con toda su información relacionada
     */
    public function getFullProfile($id) {
        // Información general
        $miembro = $this->findById($id);
        
        if (!$miembro) {
            return null;
        }
        
        // Información de contacto
        $sql = "SELECT * FROM Contacto WHERE miembro_id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $miembro['contacto'] = $stmt->fetch();
        
        // Ministerios
        $sql = "SELECT mm.*, m.nombre as ministerio_nombre, r.nombre as rol_nombre
                FROM MiembrosMinisterios mm
                JOIN Ministerios m ON mm.ministerio_id = m.id
                JOIN Roles r ON mm.rol_id = r.id
                WHERE mm.miembro_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $miembro['ministerios'] = $stmt->fetchAll();
        
        return $miembro;
    }
    
    /**
     * Guarda un nuevo miembro con su información de contacto
     */
    public function saveWithContacto($miembroData, $contactoData) {
        try {
            $this->db->beginTransaction();
            
            // Crear miembro
            $miembroId = $this->create($miembroData);
            
            if (!$miembroId) {
                throw new \Exception("Error al crear el miembro");
            }
            
            // Crear contacto
            $contactoData['miembro_id'] = $miembroId;
            $contactoModel = new \App\Models\Contacto();
            $contactoId = $contactoModel->create($contactoData);
            
            if (!$contactoId) {
                throw new \Exception("Error al crear la información de contacto");
            }
            
            $this->db->commit();
            return $miembroId;
            
        } catch (\Exception $e) {
            $this->db->rollBack();
            // Registrar el error
            error_log($e->getMessage());
            return false;
        }
    }
}