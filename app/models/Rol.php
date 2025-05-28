<?php
namespace App\Models;

class Rol extends Model {
    protected $table = 'Roles';
    protected $fillable = ['nombre', 'descripcion', 'nivel_acceso'];
    
    /**
     * Obtiene todos los permisos asociados a este rol
     */
    public function getPermisos($rolId) {
        $sql = "SELECT p.* FROM Permisos p 
                JOIN RolesPermisos rp ON p.id = rp.permiso_id 
                WHERE rp.rol_id = ?";
                
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$rolId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Verifica si el rol tiene un permiso específico
     */
    public function tienePermiso($rolId, $permisoNombre) {
        $sql = "SELECT COUNT(*) FROM Permisos p 
                JOIN RolesPermisos rp ON p.id = rp.permiso_id 
                WHERE rp.rol_id = ? AND p.nombre = ?";
                
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$rolId, $permisoNombre]);
        return (int)$stmt->fetchColumn() > 0;
    }
    
    /**
     * Asigna un permiso al rol
     */
    public function asignarPermiso($rolId, $permisoId) {
        $sql = "INSERT IGNORE INTO RolesPermisos (rol_id, permiso_id) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$rolId, $permisoId]);
    }
    
    /**
     * Revoca un permiso del rol
     */
    public function revocarPermiso($rolId, $permisoId) {
        $sql = "DELETE FROM RolesPermisos WHERE rol_id = ? AND permiso_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$rolId, $permisoId]);
    }
    
    /**
     * Elimina todos los permisos de un rol
     */
    public function eliminarTodosPermisos($rolId) {
        $sql = "DELETE FROM RolesPermisos WHERE rol_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$rolId]);
    }
    
    /**
     * Obtiene los usuarios asignados a un rol
     */
    public function getUsuarios($rolId) {
        $sql = "SELECT u.* FROM Usuarios u WHERE u.rol_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$rolId]);
        return $stmt->fetchAll();
    }
}