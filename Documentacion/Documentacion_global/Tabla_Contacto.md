# Tabla de Contacto para Iglesia en Casa

Aquí tienes la estructura organizada para la tabla de Contacto según tus especificaciones:

````sql
CREATE TABLE Contacto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    miembro_id INT NOT NULL,
    
    /* DOCUMENTACIÓN */
    tipo_documento VARCHAR(30) COMMENT 'Cédula, Pasaporte, etc.',
    numero_documento VARCHAR(30) NOT NULL COMMENT 'Número de identificación',
    
    /* INFORMACIÓN DE CONTACTO */
    telefono VARCHAR(20) COMMENT 'Teléfono fijo formato internacional',
    
    /* UBICACIÓN */
    pais VARCHAR(100) COMMENT 'País de residencia',
    ciudad VARCHAR(100) COMMENT 'Ciudad de residencia',
    direccion VARCHAR(255) COMMENT 'Dirección completa de residencia',
    
    /* ESTADO CIVIL */
    estado_civil VARCHAR(20) COMMENT 'Soltero, Casado, etc.',
    
    /* CONTACTO DIGITAL */
    correo_electronico VARCHAR(100) COMMENT 'Formato email',
    instagram VARCHAR(50) COMMENT 'Usuario de Instagram',
    facebook VARCHAR(100) COMMENT 'Perfil de Facebook',
    
    /* INFORMACIÓN ADICIONAL */
    notas TEXT COMMENT 'Observaciones adicionales',
    familiares VARCHAR(255) COMMENT 'Tipos de familiares en la iglesia',
    
    /* METADATOS */
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    /* RELACIONES */
    FOREIGN KEY (miembro_id) REFERENCES InformacionGeneral(id) ON DELETE CASCADE
);
````

## Características de la tabla

- **Documentación completa**: Mantiene los datos de identificación oficial
- **Datos de ubicación**: Incluye información geográfica del miembro
- **Contacto por múltiples canales**: Cubre medios tradicionales y redes sociales
- **Nuevo campo Facebook**: Añadido según tu especificación
- **Campo de notas**: Para información adicional que no encaje en otros campos
- **Actualización automática**: Registra la última fecha de modificación de los datos
- **Relación con miembro**: Vinculado a la tabla principal de información general

Esta tabla complementa perfectamente la información básica de los miembros, permitiendo un registro detallado de todas sus formas de contacto y ubicación.