<?php
// filepath: c:\xampp\htdocs\ENCASA_DATABASE\app\models\EstudiosTrabajo.php
namespace App\Models;

class EstudiosTrabajo extends Model {
    protected $table = 'EstudiosTrabajo';
    protected $fillable = [
        'miembro_id', 'nivel_estudios', 'profesion', 'otros_estudios',
        'empresa', 'direccion_empresa', 'emprendimientos'
    ];
    
    /**
     * Encuentra la información de estudios y trabajo por ID de miembro
     */
    public function findByMiembro($miembroId) {
        $sql = "SELECT * FROM {$this->table} WHERE miembro_id = :miembro_id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['miembro_id' => $miembroId]);
        return $stmt->fetch();
    }
    
    /**
     * Actualiza o crea un registro de estudios/trabajo para un miembro
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