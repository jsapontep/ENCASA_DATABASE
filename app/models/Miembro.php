<?php
namespace App\Models;

class Miembro extends Model {
    protected $table = 'InformacionGeneral';
    protected $fillable = [
        'nombres', 'apellidos', 'celular', 'localidad', 'barrio', 
        'fecha_nacimiento', 'invitado_por', 'conector', 'estado_espiritual',
        'recorrido_espiritual', 'foto', 'habeas_data', 'fecha_ingreso_iglesia',
        'estado_miembro', 'fecha_modificacion'
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
    public function getFullProfile($id) 
    {
        // Consulta principal para obtener datos del miembro
        $query = "SELECT * FROM informaciongeneral WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        $miembro = $stmt->fetch();
        
        if (!$miembro) {
            return null;
        }
        
        // Cargar datos relacionados
        $tablas = [
            'contacto' => 'contacto',
            'estudiostrabajo' => 'estudiostrabajo', 
            'tallas' => 'tallas',
            'saludemergencias' => 'saludemergencias',
            'carrerabiblica' => 'carrerabiblica'
        ];
        
        foreach ($tablas as $key => $tabla) {
            $query = "SELECT * FROM {$tabla} WHERE miembro_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$id]);
            $relacion = $stmt->fetch();
            
            // Asignar datos relacionados o array vacío si no existen
            $miembro[$key] = $relacion ?: [];
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
    public function guardarContacto($datos)
    {
        try {
            // Extraer miembro_id del array
            $miembroId = $datos['miembro_id'];
            
            // Verificar si ya existe un registro para este miembro
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM Contacto WHERE miembro_id = ?");
            $stmt->execute([$miembroId]);
            $existe = (bool)$stmt->fetchColumn();

            if ($existe) {
                // Actualizar registro existente
                $campos = [];
                $valores = [];
                
                foreach ($datos as $campo => $valor) {
                    if ($campo !== 'miembro_id') {
                        $campos[] = "$campo = ?";
                        $valores[] = $valor;
                    }
                }
                
                if (empty($campos)) {
                    return false; // No hay campos para actualizar
                }
                
                $valores[] = $miembroId; // Para la condición WHERE
                
                $sql = "UPDATE Contacto SET " . implode(', ', $campos) . " WHERE miembro_id = ?";
                $stmt = $this->db->prepare($sql);
                return $stmt->execute($valores);
            } else {
                // Insertar nuevo registro
                $campos = array_keys($datos);
                $placeholders = array_fill(0, count($campos), '?');
                
                $sql = "INSERT INTO Contacto (" . implode(', ', $campos) . ") VALUES (" . implode(', ', $placeholders) . ")";
                $stmt = $this->db->prepare($sql);
                return $stmt->execute(array_values($datos));
            }
        } catch (\PDOException $e) {
            error_log("Error al guardar datos de contacto: " . $e->getMessage());
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
            
            // Verificar primero si el miembro existe
            $checkSql = "SELECT id FROM {$this->table} WHERE id = ?";
            $checkStmt = $this->db->prepare($checkSql);
            $checkStmt->execute([$id]);
            
            if ($checkStmt->rowCount() === 0) {
                error_log("Error en actualizar(): El miembro con ID $id no existe");
                return false;
            }
            
            // Construir la consulta de actualización
            $setClausulas = [];
            $valores = [];
            
            foreach ($datos as $campo => $valor) {
                // Solo incluir campos permitidos
                if (in_array($campo, $this->fillable)) {
                    $setClausulas[] = "$campo = ?";
                    $valores[] = $valor;
                    error_log("Campo a actualizar: $campo = $valor");
                } else {
                    error_log("Campo ignorado (no en fillable): $campo");
                }
            }
            
            if (empty($setClausulas)) {
                error_log("Error en actualizar(): No hay campos válidos para actualizar");
                return false;
            }
            
            // Añadir ID al final de los valores
            $valores[] = $id;
            
            $sql = "UPDATE {$this->table} SET " . implode(', ', $setClausulas) . " WHERE id = ?";
            error_log("SQL de actualización: " . $sql);
            error_log("Valores para actualizar: " . implode(", ", $valores));
            
            $stmt = $this->db->prepare($sql);
            $resultado = $stmt->execute($valores);
            
            error_log("Resultado de actualización de miembro ID $id: " . ($resultado ? "éxito" : "fallido"));
            return $resultado;
        } catch (\PDOException $e) {
            error_log("Error en actualizar(): " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Guarda o actualiza los datos de estudios y trabajo de un miembro
     * 
     * @param int $miembroId ID del miembro
     * @param array $datos Datos de estudios y trabajo
     * @return bool Resultado de la operación
     */
    public function guardarEstudiosTrabajo($miembroId, $datos)
    {
        try {
            // Verificar si ya existe un registro para este miembro
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM EstudiosTrabajo WHERE miembro_id = ?");
            $stmt->execute([$miembroId]);
            $existe = (bool)$stmt->fetchColumn();

            if ($existe) {
                // Actualizar registro existente
                $sql = "UPDATE EstudiosTrabajo SET 
                    nivel_estudios = ?, 
                    profesion = ?, 
                    otros_estudios = ?, 
                    empresa = ?, 
                    direccion_empresa = ?, 
                    emprendimientos = ?
                    WHERE miembro_id = ?";
                
                $stmt = $this->db->prepare($sql);
                return $stmt->execute([
                    $datos['nivel_estudios'] ?? null,
                    $datos['profesion'] ?? null,
                    $datos['otros_estudios'] ?? null,
                    $datos['empresa'] ?? null,
                    $datos['direccion_empresa'] ?? null,
                    $datos['emprendimientos'] ?? null,
                    $miembroId
                ]);
            } else {
                // Insertar nuevo registro
                $sql = "INSERT INTO EstudiosTrabajo (
                    miembro_id, nivel_estudios, profesion, otros_estudios, 
                    empresa, direccion_empresa, emprendimientos
                ) VALUES (?, ?, ?, ?, ?, ?, ?)";
                
                $stmt = $this->db->prepare($sql);
                return $stmt->execute([
                    $miembroId,
                    $datos['nivel_estudios'] ?? null,
                    $datos['profesion'] ?? null,
                    $datos['otros_estudios'] ?? null,
                    $datos['empresa'] ?? null,
                    $datos['direccion_empresa'] ?? null,
                    $datos['emprendimientos'] ?? null
                ]);
            }
        } catch (\PDOException $e) {
            error_log("Error al guardar datos de estudios y trabajo: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Guarda o actualiza las tallas de un miembro
     * 
     * @param array $datos Datos de tallas
     * @return bool Resultado de la operación
     */
    public function guardarTallas($datos)
    {
        try {
            // Extraer miembro_id del array
            $miembroId = $datos['miembro_id'];
            
            // Verificar si ya existe un registro para este miembro
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM Tallas WHERE miembro_id = ?");
            $stmt->execute([$miembroId]);
            $existe = (bool)$stmt->fetchColumn();

            if ($existe) {
                // Actualizar registro existente
                $campos = [];
                $valores = [];
                
                foreach ($datos as $campo => $valor) {
                    if ($campo !== 'miembro_id') {
                        $campos[] = "$campo = ?";
                        $valores[] = $valor;
                    }
                }
                
                if (empty($campos)) {
                    return false; // No hay campos para actualizar
                }
                
                $valores[] = $miembroId; // Para la condición WHERE
                
                $sql = "UPDATE Tallas SET " . implode(', ', $campos) . " WHERE miembro_id = ?";
                $stmt = $this->db->prepare($sql);
                return $stmt->execute($valores);
            } else {
                // Insertar nuevo registro
                $campos = array_keys($datos);
                $placeholders = array_fill(0, count($campos), '?');
                
                $sql = "INSERT INTO Tallas (" . implode(', ', $campos) . ") VALUES (" . implode(', ', $placeholders) . ")";
                $stmt = $this->db->prepare($sql);
                return $stmt->execute(array_values($datos));
            }
        } catch (\PDOException $e) {
            error_log("Error al guardar datos de tallas: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Guarda o actualiza los datos de carrera bíblica de un miembro
     * 
     * @param array $datos Datos de carrera bíblica
     * @return bool Resultado de la operación
     */
    public function guardarCarreraBiblica($datos)
    {
        try {
            // Extraer miembro_id del array
            $miembroId = $datos['miembro_id'];
            
            // Verificar si ya existe un registro para este miembro
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM CarreraBiblica WHERE miembro_id = ?");
            $stmt->execute([$miembroId]);
            $existe = (bool)$stmt->fetchColumn();

            if ($existe) {
                // Actualizar registro existente
                $campos = [];
                $valores = [];
                
                foreach ($datos as $campo => $valor) {
                    if ($campo !== 'miembro_id') {
                        $campos[] = "$campo = ?";
                        $valores[] = $valor;
                    }
                }
                
                if (empty($campos)) {
                    return false; // No hay campos para actualizar
                }
                
                $valores[] = $miembroId; // Para la condición WHERE
                
                $sql = "UPDATE CarreraBiblica SET " . implode(', ', $campos) . " WHERE miembro_id = ?";
                $stmt = $this->db->prepare($sql);
                return $stmt->execute($valores);
            } else {
                // Insertar nuevo registro
                $campos = array_keys($datos);
                $placeholders = array_fill(0, count($campos), '?');
                
                $sql = "INSERT INTO CarreraBiblica (" . implode(', ', $campos) . ") VALUES (" . implode(', ', $placeholders) . ")";
                $stmt = $this->db->prepare($sql);
                return $stmt->execute(array_values($datos));
            }
        } catch (\PDOException $e) {
            error_log("Error al guardar datos de carrera bíblica: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Verifica si un ID de miembro existe en la base de datos
     */
    public function existeId($id)
    {
        try {
            $id = (int)$id;
            $sql = "SELECT COUNT(*) FROM {$this->table} WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            return (int)$stmt->fetchColumn() > 0;
        } catch (\PDOException $e) {
            error_log("Error al verificar ID: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtiene una lista simplificada de todos los miembros (ID, nombres, apellidos)
     * @return array Lista de miembros
     */
    public static function obtenerTodosSimple() {
        try {
            $db = \Database::getInstance()->getConnection();
            $stmt = $db->query("SELECT id, nombres, apellidos FROM informaciongeneral ORDER BY nombres, apellidos");
            return $stmt->fetchAll();
        } catch (\Exception $e) {
            error_log("Error al obtener lista simple de miembros: " . $e->getMessage());
            return [];
        }
    }
}