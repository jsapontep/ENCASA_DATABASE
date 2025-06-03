<?php

class MiembroModel {
    private $db;
    
    public function __construct($database) {
        $this->db = $database;
    }

    /**
     * Actualiza la información de un miembro
     */
    public function actualizar($id, $datos)
    {
        try {
            // Preparar los campos a actualizar - solo para la tabla informaciongeneral
            $camposPermitidos = [
                'nombres', 'apellidos', 'celular', 'localidad', 'barrio',
                'fecha_nacimiento', 'invitado_por', 'conector',
                'recorrido_espiritual', 'estado_espiritual', 
                'fecha_ingreso_iglesia', 'habeas_data', 'estado_miembro'
            ];
            
            $updates = [];
            $values = [];
            
            // Procesar solo los campos permitidos
            foreach ($camposPermitidos as $campo) {
                if (isset($datos[$campo])) {
                    $updates[] = "$campo = ?";
                    $values[] = $datos[$campo];
                }
            }
            
            // Si hay campos para actualizar
            if (!empty($updates)) {
                // Agregar el ID al final de los valores
                $values[] = $id;
                
                // Construir la consulta SQL
                $sql = "UPDATE informaciongeneral SET " . implode(', ', $updates) . " WHERE id = ?";
                
                // Preparar y ejecutar la consulta
                $stmt = $this->db->prepare($sql);
                $resultado = $stmt->execute($values);
                
                // Registrar la consulta para depuración
                $valoresToLog = $values;
                array_pop($valoresToLog); // Quitar el ID para mejor legibilidad
                error_log("SQL ejecutado: " . $sql . " | Valores: " . implode(", ", $valoresToLog) . " | ID: $id");
                error_log("Resultado de actualización: " . ($resultado ? "Exitoso" : "Fallido") . " | Filas afectadas: " . $stmt->rowCount());
                
                return $resultado;
            }
            
            return false;
        } catch (\PDOException $e) {
            error_log("Error en MiembroModel::actualizar: " . $e->getMessage());
            throw new \Exception("Error al actualizar el miembro: " . $e->getMessage());
        }
    }

    /**
     * Obtiene un miembro por ID
     */
    public function obtenerPorId($id) {
        try {
            // CORREGIDO: Usar nombre de tabla correcto
            $stmt = $this->db->prepare("SELECT * FROM informaciongeneral WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (\PDOException $e) {
            error_log("Error al obtener miembro por ID: " . $e->getMessage());
            return false;
        }
    }
}