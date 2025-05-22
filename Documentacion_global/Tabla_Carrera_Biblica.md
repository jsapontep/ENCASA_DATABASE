# Tabla de Carrera Bíblica para Iglesia en Casa

Aquí tienes la estructura organizada para la tabla de Carrera Bíblica:

````sql
CREATE TABLE CarreraBiblica (
    id INT AUTO_INCREMENT PRIMARY KEY,
    miembro_id INT NOT NULL,
    
    /* FORMACIÓN Y PARTICIPACIÓN */
    carrera_biblica VARCHAR(100) COMMENT 'Nivel o curso bíblico actual',
    miembro_de VARCHAR(100) COMMENT 'Grupo o ministerio al que pertenece',
    casa_de_palabra_y_vida VARCHAR(100) COMMENT 'Casa o grupo pequeño asignado',
    
    /* COBERTURA Y ESTADO */
    cobertura VARCHAR(100) COMMENT 'Líder o pastor que le cubre espiritualmente',
    estado VARCHAR(20) COMMENT 'Estado de participación: Activo, Inactivo, Intermitente, Nuevo',
    
    /* OBSERVACIONES */
    anotaciones TEXT COMMENT 'Observaciones adicionales',
    recorrido_espiritual TEXT COMMENT 'Registro del crecimiento espiritual',
    
    /* METADATOS */
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    /* RELACIONES */
    FOREIGN KEY (miembro_id) REFERENCES InformacionGeneral(id) ON DELETE CASCADE
);
````

## Características de la tabla

- **Seguimiento espiritual**: Permite documentar el progreso en la formación bíblica
- **Participación eclesial**: Registra los grupos y ministerios en los que sirve el miembro
- **Control de asistencia**: El campo estado permite clasificar el nivel de participación
- **Mentoreo**: Identifica al líder o pastor responsable de su crecimiento espiritual
- **Histórico**: Mantiene un registro cronológico del recorrido espiritual
- **Doble registro temporal**: Guarda tanto la fecha de inicio como la última actualización
- **Relación con miembro**: Vinculada a la tabla principal de información general

Esta tabla es fundamental para el seguimiento pastoral y el desarrollo del discipulado dentro de la iglesia.