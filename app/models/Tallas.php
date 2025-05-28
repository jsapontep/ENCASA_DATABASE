<?php
// filepath: c:\xampp\htdocs\ENCASA_DATABASE\app\models\Tallas.php
namespace App\Models;

class Tallas extends Model {
    protected $table = 'Tallas';
    protected $fillable = [
        'miembro_id', 'talla_camisa', 'talla_camiseta', 'talla_pantalon',
        'talla_zapatos'
    ];
    
    /**
     * Encuentra la información de tallas por ID de miembro
     */
    public function findByMiembro($miembroId) {
        $sql = "SELECT * FROM {$this->table} WHERE miembro_id = :miembro_id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['miembro_id' => $miembroId]);
        return $stmt->fetch();
    }
    
    /**
     * Actualiza o crea un registro de tallas para un miembro
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