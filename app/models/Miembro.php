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
        // Asegurar que el ID es un entero
        $id = (int)$id;
        
        // Preparar consulta principal
        $sql = "SELECT * FROM InformacionGeneral WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $miembro = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        if (!$miembro) {
            return null;
        }
        
        // Obtener datos de contacto
        $sql = "SELECT * FROM Contacto WHERE miembro_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $contacto = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($contacto) {
            $miembro['contacto'] = $contacto;
        }
        
        // Obtener datos de estudios y trabajo
        $sql = "SELECT * FROM EstudiosTrabajo WHERE miembro_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $estudios = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($estudios) {
            $miembro['estudios'] = $estudios;
        }
        
        // Obtener datos de tallas
        $sql = "SELECT * FROM Tallas WHERE miembro_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $tallas = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($tallas) {
            $miembro['tallas'] = $tallas;
        }
        
        // Obtener datos de carrera bíblica
        $sql = "SELECT * FROM CarreraBiblica WHERE miembro_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $carrera = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($carrera) {
            $miembro['carrera'] = $carrera;
        }
        
        // Obtener datos de salud y emergencias
        $sql = "SELECT * FROM SaludEmergencias WHERE miembro_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $salud = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($salud) {
            $miembro['salud'] = $salud;
        }
        
        return $miembro;
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
     * Guarda los datos de estudios y trabajo de un miembro
     */
    public function guardarEstudiosTrabajo($datos) {
        try {
            // Verificar si ya existe un registro para este miembro
            $stmt = $this->db->prepare("SELECT id FROM EstudiosTrabajo WHERE miembro_id = ?");
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
                
                $sql = "UPDATE EstudiosTrabajo SET $sets_str WHERE id = ?";
                
                $stmt = $this->db->prepare($sql);
                $stmt->execute([...array_values($datos), $id]);
            } else {
                // Insertar nuevo registro
                $campos = array_keys($datos);
                $valores = array_values($datos);
                
                $campos_str = implode(', ', $campos);
                $placeholders = implode(', ', array_fill(0, count($campos), '?'));
                
                $sql = "INSERT INTO EstudiosTrabajo ($campos_str) VALUES ($placeholders)";
                
                $stmt = $this->db->prepare($sql);
                $stmt->execute($valores);
            }
            
            return true;
        } catch (\PDOException $e) {
            error_log("Error al guardar estudios y trabajo: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Guarda las tallas de un miembro
     */
    public function guardarTallas($datos) {
        try {
            // Verificar si ya existe un registro para este miembro
            $stmt = $this->db->prepare("SELECT id FROM Tallas WHERE miembro_id = ?");
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
                
                $sql = "UPDATE Tallas SET $sets_str WHERE id = ?";
                
                $stmt = $this->db->prepare($sql);
                $stmt->execute([...array_values($datos), $id]);
            } else {
                // Insertar nuevo registro
                $campos = array_keys($datos);
                $valores = array_values($datos);
                
                $campos_str = implode(', ', $campos);
                $placeholders = implode(', ', array_fill(0, count($campos), '?'));
                
                $sql = "INSERT INTO Tallas ($campos_str) VALUES ($placeholders)";
                
                $stmt = $this->db->prepare($sql);
                $stmt->execute($valores);
            }
            
            return true;
        } catch (\PDOException $e) {
            error_log("Error al guardar tallas: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Guarda la información de carrera bíblica de un miembro
     */
    public function guardarCarreraBiblica($datos) {
        try {
            // Verificar si ya existe un registro para este miembro
            $stmt = $this->db->prepare("SELECT id FROM CarreraBiblica WHERE miembro_id = ?");
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
                
                $sql = "UPDATE CarreraBiblica SET $sets_str WHERE id = ?";
                
                $stmt = $this->db->prepare($sql);
                $stmt->execute([...array_values($datos), $id]);
            } else {
                // Insertar nuevo registro
                $campos = array_keys($datos);
                $valores = array_values($datos);
                
                $campos_str = implode(', ', $campos);
                $placeholders = implode(', ', array_fill(0, count($campos), '?'));
                
                $sql = "INSERT INTO CarreraBiblica ($campos_str) VALUES ($placeholders)";
                
                $stmt = $this->db->prepare($sql);
                $stmt->execute($valores);
            }
            
            return true;
        } catch (\PDOException $e) {
            error_log("Error al guardar carrera bíblica: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Guarda la información de salud y emergencias de un miembro
     */
    public function guardarSaludEmergencias($datos) {
        try {
            // Verificar si ya existe un registro para este miembro
            $stmt = $this->db->prepare("SELECT id FROM SaludEmergencias WHERE miembro_id = ?");
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
                
                $sql = "UPDATE SaludEmergencias SET $sets_str WHERE id = ?";
                
                $stmt = $this->db->prepare($sql);
                $stmt->execute([...array_values($datos), $id]);
            } else {
                // Insertar nuevo registro
                $campos = array_keys($datos);
                $valores = array_values($datos);
                
                $campos_str = implode(', ', $campos);
                $placeholders = implode(', ', array_fill(0, count($campos), '?'));
                
                $sql = "INSERT INTO SaludEmergencias ($campos_str) VALUES ($placeholders)";
                
                $stmt = $this->db->prepare($sql);
                $stmt->execute($valores);
            }
            
            return true;
        } catch (\PDOException $e) {
            error_log("Error al guardar salud y emergencias: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Elimina un miembro y todos sus registros relacionados
     */
    public function eliminarCompleto($id) {
        try {
            // Iniciar transacción para asegurar integridad
            $this->db->beginTransaction();
            
            error_log("Iniciando eliminación del miembro ID: $id");
            
            // Eliminar registros relacionados en todas las tablas
            $tablasRelacionadas = [
                'Contacto', 
                'EstudiosTrabajo', 
                'Tallas', 
                'CarreraBiblica', 
                'SaludEmergencias'
            ];
            
            foreach ($tablasRelacionadas as $tabla) {
                $stmt = $this->db->prepare("DELETE FROM $tabla WHERE miembro_id = ?");
                $stmt->execute([$id]);
                error_log("Tabla $tabla: " . $stmt->rowCount() . " filas eliminadas");
            }
            
            // Verificar si el miembro tiene foto para eliminarla
            $stmt = $this->db->prepare("SELECT foto FROM InformacionGeneral WHERE id = ?");
            $stmt->execute([$id]);
            $foto = $stmt->fetchColumn();
            
            if ($foto) {
                $rutaFoto = BASE_PATH . '/public/uploads/miembros/' . $foto;
                if (file_exists($rutaFoto)) {
                    if (unlink($rutaFoto)) {
                        error_log("Foto eliminada: $rutaFoto");
                    } else {
                        error_log("Error al eliminar foto: $rutaFoto");
                    }
                } else {
                    error_log("Archivo de foto no encontrado: $rutaFoto");
                }
            }
            
            // Verificar si hay otras relaciones con este miembro (por ejemplo, en ministerios)
            $otrasTablas = ['Ministerios', 'Eventos', 'Asistencia']; // Ajusta según tu estructura
            foreach ($otrasTablas as $tabla) {
                // Verificar si la tabla existe antes de intentar eliminar
                $stmtCheck = $this->db->prepare("SHOW TABLES LIKE ?");
                $stmtCheck->execute([$tabla]);
                if ($stmtCheck->rowCount() > 0) {
                    $stmt = $this->db->prepare("DELETE FROM $tabla WHERE miembro_id = ?");
                    $stmt->execute([$id]);
                    error_log("Tabla adicional $tabla: " . $stmt->rowCount() . " filas eliminadas");
                }
            }
            
            // Finalmente, eliminar el registro principal con verificación
            $stmt = $this->db->prepare("DELETE FROM InformacionGeneral WHERE id = ?");
            $stmt->execute([$id]);
            
            $filasAfectadas = $stmt->rowCount();
            error_log("InformacionGeneral: $filasAfectadas filas eliminadas");
            
            if ($filasAfectadas == 0) {
                error_log("ADVERTENCIA: No se eliminó ningún registro de InformacionGeneral");
                // Verificar si el registro existe
                $check = $this->db->prepare("SELECT id FROM InformacionGeneral WHERE id = ?");
                $check->execute([$id]);
                if ($check->fetch()) {
                    error_log("ERROR: El registro ID $id sigue existiendo");
                } else {
                    error_log("El registro ID $id no existe (posiblemente ya fue eliminado)");
                }
            }
            
            // Confirmar transacción
            $this->db->commit();
            error_log("Transacción completada para eliminar miembro ID: $id");
            
            return $filasAfectadas > 0;
        } catch (\Exception $e) {
            // Revertir cambios en caso de error
            $this->db->rollBack();
            error_log("Error al eliminar miembro: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Obtiene un miembro por su ID
     * 
     * @param int $id ID del miembro
     * @return array|bool Array con datos del miembro o false si no existe
     */
    public function getById($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM InformacionGeneral WHERE id = ?");
            $stmt->execute([$id]);
            $miembro = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            return $miembro ? $miembro : false;
        } catch (\PDOException $e) {
            error_log("Error al obtener miembro por ID: " . $e->getMessage());
            return false;
        }
    }
}