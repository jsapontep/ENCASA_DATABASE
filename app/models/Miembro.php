<?php
namespace App\Models;

class Miembro extends Model {
    protected $table = 'InformacionGeneral';
    protected $fillable = [
        'nombres', 'apellidos', 'celular', 'localidad', 'barrio', 
        'fecha_nacimiento', 'invitado_por', 'conector', 'estado_espiritual',
        'recorrido_espiritual', 'foto', 'habeas_data', 'fecha_ingreso'
    ];
    
    /**
     * Obtiene todos los miembros con información básica
     */
    public function getAll($order = 'apellidos', $dir = 'ASC') {
        $sql = "SELECT * FROM {$this->table} ORDER BY {$order} {$dir}";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Busca miembros por criterios
     */
    public function buscar($termino) {
        $termino = "%{$termino}%";
        $sql = "SELECT * FROM {$this->table} 
                WHERE nombres LIKE ? 
                OR apellidos LIKE ? 
                OR celular LIKE ?
                OR barrio LIKE ?
                OR localidad LIKE ?
                ORDER BY apellidos, nombres";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$termino, $termino, $termino, $termino, $termino]);
        return $stmt->fetchAll();
    }
    
    /**
     * Obtiene un listado básico de miembros para selector
     */
    public function getParaSelector() {
        $sql = "SELECT id, CONCAT(nombres, ' ', apellidos) as nombre_completo 
                FROM {$this->table} 
                ORDER BY apellidos, nombres";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Crea un nuevo miembro con sus datos relacionados
     */
    public function crearCompleto($datos) {
        try {
            $this->db->beginTransaction();
            
            // 1. Crear registro principal en InformacionGeneral
            $miembroId = $this->create($datos['informacion_general']);
            
            if (!$miembroId) {
                $this->db->rollBack();
                return false;
            }
            
            // 2. Crear registro de contacto si hay datos
            if (!empty($datos['contacto'])) {
                $datos['contacto']['miembro_id'] = $miembroId;
                $contactoModel = new Contacto();
                if (!$contactoModel->create($datos['contacto'])) {
                    $this->db->rollBack();
                    return false;
                }
            }
            
            // 3. Crear registro de estudios/trabajo si hay datos
            if (!empty($datos['estudios_trabajo'])) {
                $datos['estudios_trabajo']['miembro_id'] = $miembroId;
                $estudiosModel = new EstudiosTrabajo();
                if (!$estudiosModel->create($datos['estudios_trabajo'])) {
                    $this->db->rollBack();
                    return false;
                }
            }
            
            // 4. Crear registro de tallas si hay datos
            if (!empty($datos['tallas'])) {
                $datos['tallas']['miembro_id'] = $miembroId;
                $tallasModel = new Tallas();
                if (!$tallasModel->create($datos['tallas'])) {
                    $this->db->rollBack();
                    return false;
                }
            }
            
            // 5. Crear registro de carrera bíblica si hay datos
            if (!empty($datos['carrera_biblica'])) {
                $datos['carrera_biblica']['miembro_id'] = $miembroId;
                $carreraModel = new CarreraBiblica();
                if (!$carreraModel->create($datos['carrera_biblica'])) {
                    $this->db->rollBack();
                    return false;
                }
            }
            
            $this->db->commit();
            return $miembroId;
        } catch (\Exception $e) {
            $this->db->rollBack();
            error_log("Error al crear miembro: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Obtiene el perfil completo de un miembro
     */
    public function getFullProfile($id) {
        try {
            // Asegurar que el ID sea un entero
            $id = (int)$id;
            
            // Log para depuración
            error_log("Buscando miembro con ID: $id");
            
            // Usar sentencia preparada en lugar de consulta directa
            $sql = "SELECT * FROM {$this->table} WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            $miembro = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            if (!$miembro) {
                error_log("No se encontró ningún miembro con ID $id");
                return null;
            }
            
            // Obtener datos de tablas relacionadas y añadirlos al array principal
            $tablas = ['Contacto', 'EstudiosTrabajo', 'Tallas', 'SaludEmergencias', 'CarreraBiblica'];
            
            foreach ($tablas as $tabla) {
                $sql = "SELECT * FROM $tabla WHERE miembro_id = ?";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$id]);
                $datos = $stmt->fetch(\PDO::FETCH_ASSOC);
                
                if ($datos) {
                    $miembro[strtolower($tabla)] = $datos;
                }
            }
            
            return $miembro;
        } catch (\PDOException $e) {
            error_log("Error en getFullProfile: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Verifica si un miembro existe en la base de datos
     */
    public function checkMemberExists($id) {
        $sql = "SELECT id FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->rowCount() > 0;
    }
    
    /**
     * Crea un nuevo registro en la tabla de información general
     */
    public function crear($datos) {
        try {
            // Preparar consulta
            $campos = array_keys($datos);
            $valores = array_values($datos);
            
            $campos_str = implode(', ', $campos);
            $placeholders = implode(', ', array_fill(0, count($campos), '?'));
            
            $sql = "INSERT INTO InformacionGeneral ($campos_str) VALUES ($placeholders)";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($valores);
            
            return $this->db->lastInsertId();
        } catch (\PDOException $e) {
            error_log("Error al crear miembro: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Guarda datos de contacto de un miembro
     */
    public function guardarContacto($datos) {
        try {
            // Verificar si ya existe un registro para este miembro
            $stmt = $this->db->prepare("SELECT id FROM Contacto WHERE miembro_id = ?");
            $stmt->execute([$datos['miembro_id']]);
            $existe = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            if ($existe) {
                // Actualizar registro existente
                $id = $existe['id'];
                
                // Eliminar miembro_id del array para la actualización
                $miembro_id = $datos['miembro_id'];
                unset($datos['miembro_id']);
                
                $sets = [];
                foreach ($datos as $campo => $valor) {
                    $sets[] = "$campo = ?";
                }
                $sets_str = implode(', ', $sets);
                
                $sql = "UPDATE Contacto SET $sets_str WHERE id = ?";
                
                $stmt = $this->db->prepare($sql);
                $stmt->execute([...array_values($datos), $id]);
            } else {
                // Insertar nuevo registro
                $campos = array_keys($datos);
                $valores = array_values($datos);
                
                $campos_str = implode(', ', $campos);
                $placeholders = implode(', ', array_fill(0, count($campos), '?'));
                
                $sql = "INSERT INTO Contacto ($campos_str) VALUES ($placeholders)";
                
                $stmt = $this->db->prepare($sql);
                $stmt->execute($valores);
            }
            
            return true;
        } catch (\PDOException $e) {
            error_log("Error al guardar contacto: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Actualiza la información de un miembro
     */
    public function actualizar($id, $datos)
    {
        try {
            // Asegurar que el ID sea un entero
            $id = (int)$id;
            
            // Verificar primero si el miembro existe para evitar actualizar un registro inexistente
            $checkSql = "SELECT id FROM {$this->table} WHERE id = :id";
            $checkStmt = $this->db->prepare($checkSql);
            $checkStmt->bindValue(':id', $id);
            $checkStmt->execute();
            
            if ($checkStmt->rowCount() === 0) {
                error_log("Error en actualizar(): El miembro con ID $id no existe");
                return false;
            }
            
            // Construir la consulta de actualización
            $campos = [];
            foreach ($datos as $campo => $valor) {
                if (in_array($campo, $this->fillable)) {
                    $campos[] = "$campo = :$campo";
                }
            }
            
            if (empty($campos)) {
                error_log("Error en actualizar(): No hay campos válidos para actualizar");
                return false;
            }
            
            $sql = "UPDATE {$this->table} SET " . implode(', ', $campos) . " WHERE id = :id";
            error_log("SQL de actualización: " . $sql);
            
            $stmt = $this->db->prepare($sql);
            
            // Vincular los valores
            foreach ($datos as $campo => $valor) {
                if (in_array($campo, $this->fillable)) {
                    $stmt->bindValue(":$campo", $valor);
                }
            }
            $stmt->bindValue(':id', $id);
            
            // Ejecutar la consulta
            $resultado = $stmt->execute();
            error_log("Resultado de actualización de miembro ID $id: " . ($resultado ? "éxito" : "fallido"));
            return $resultado;
        } catch (\PDOException $e) {
            error_log("Error en actualizar(): " . $e->getMessage());
            return false;
        }
    }
    
    // Implementar métodos similares para las otras tablas:
    // - guardarEstudiosTrabajo()
    // - guardarTallas()
    // - guardarCarreraBiblica()
}