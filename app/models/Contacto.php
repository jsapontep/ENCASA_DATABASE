<?php
// filepath: c:\xampp\htdocs\ENCASA_DATABASE\app\models\Contacto.php
namespace App\Models;

class Contacto extends Model {
    protected $table = 'Contacto';
    protected $fillable = [
        'miembro_id', 'tipo_documento', 'numero_documento', 'telefono', 'pais',
        'ciudad', 'direccion', 'estado_civil', 'correo_electronico',
        'instagram', 'facebook', 'notas', 'familiares'
    ];
    
    /**
     * Encuentra el contacto por ID de miembro
     */
    public function findByMiembro($miembroId) {
        $sql = "SELECT * FROM {$this->table} WHERE miembro_id = :miembro_id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['miembro_id' => $miembroId]);
        return $stmt->fetch();
    }
    
    /**
     * Actualiza o crea un registro de contacto para un miembro
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