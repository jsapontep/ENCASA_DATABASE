<?php

namespace App\Models;

class Permiso extends Model {
    protected $table = 'Permisos';
    protected $fillable = ['nombre', 'descripcion', 'categoria'];
    
    /**
     * Obtiene todos los permisos agrupados por categoría
     */
    public function getAllByCategory() {
        $sql = "SELECT * FROM {$this->table} ORDER BY categoria, nombre";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $permisos = $stmt->fetchAll();
        
        $result = [];
        foreach ($permisos as $permiso) {
            $categoria = $permiso['categoria'];
            if (!isset($result[$categoria])) {
                $result[$categoria] = [];
            }
            $result[$categoria][] = $permiso;
        }
        
        return $result;
    }
    
    /**
     * Verifica si un usuario tiene un permiso específico
     */
    public function usuarioTienePermiso($userId, $permisoNombre) {
        $sql = "SELECT COUNT(*) FROM Permisos p 
                JOIN RolesPermisos rp ON p.id = rp.permiso_id 
                JOIN Roles r ON r.id = rp.rol_id
                JOIN Usuarios u ON u.rol_id = r.id
                WHERE u.id = ? AND p.nombre = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId, $permisoNombre]);
        return (int)$stmt->fetchColumn() > 0;
    }
}