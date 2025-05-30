# Tabla de Salud y Emergencias para Iglesia en Casa

Aquí tienes la estructura organizada para la tabla de Salud y Emergencias según tus especificaciones:

````sql
CREATE TABLE SaludEmergencias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    miembro_id INT NOT NULL,
    
    /* INFORMACIÓN MÉDICA */
    rh VARCHAR(5) COMMENT 'Tipo de sangre (A+, O-, etc.)',
    
    /* CONTACTOS DE EMERGENCIA */
    acudiente1 VARCHAR(100) COMMENT 'Nombre del primer contacto de emergencia',
    telefono1 VARCHAR(20) COMMENT 'Teléfono del primer contacto en formato internacional',
    
    acudiente2 VARCHAR(100) COMMENT 'Nombre del segundo contacto de emergencia',
    telefono2 VARCHAR(20) COMMENT 'Teléfono del segundo contacto en formato internacional',
    
    /* SEGURIDAD SOCIAL */
    eps VARCHAR(50) COMMENT 'Entidad Promotora de Salud',
    
    /* METADATOS */
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    /* RELACIONES */
    FOREIGN KEY (miembro_id) REFERENCES InformacionGeneral(id) ON DELETE CASCADE
);
````

## Características de la tabla

- **Información médica básica**: Incluye el grupo sanguíneo del miembro
- **Contactos de emergencia**: Almacena datos de dos personas a contactar en caso de emergencia
- **Seguridad social**: Registro de la EPS a la que está afiliado el miembro
- **Actualización automática**: Registra la última fecha de modificación de los datos
- **Vínculo directo**: Relacionada con la tabla principal de información general

Esta tabla es especialmente útil para eventos y actividades que involucren riesgos físicos o para tener información crítica en caso de emergencias médicas.