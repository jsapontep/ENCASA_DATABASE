<?php
// filepath: c:\xampp\htdocs\ENCASA_DATABASE\app\models\CarreraBiblica.php
namespace App\Models;

class CarreraBiblica extends Model {
    protected $table = 'CarreraBiblica';
    protected $fillable = [
        'miembro_id', 'carrera_biblica', 'miembro_de', 'casa_de_palabra_y_vida',
        'cobertura', 'estado', 'anotaciones', 'recorrido_espiritual'
    ];
    
    /**
     * Encuentra la información de carrera bíblica por ID de miembro
     */
    public function findByMiembro($miembroId) {
        $sql = "SELECT * FROM {$this->table} WHERE miembro_id = :miembro_id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['miembro_id' => $miembroId]);
        return $stmt->fetch();
    }
    
    /**
     * Actualiza o crea un registro de carrera bíblica para un miembro
     */
    public function actualizarOCrear($miembroId, $datos) {
        $actual = $this->findByMiembro($miembroId);
        
        if ($actual) {
            return $this->update($actual['id'], $datos);
        } else {
            $datos['miembro_id'] = $miembroId;
            return $this->create($datos);
        }
    }
}