# Tabla de Información General para Iglesia en Casa

Aquí tienes la estructura organizada para la tabla de Información General:

````sql
CREATE DATABASE IF NOT EXISTS IglesiaEnCasa;
USE IglesiaEnCasa;

CREATE TABLE InformacionGeneral (
    id INT AUTO_INCREMENT PRIMARY KEY,
    
    /* DATOS BÁSICOS */
    nombres VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    celular VARCHAR(20) NOT NULL COMMENT 'Formato internacional: +123456789',
    
    /* UBICACIÓN */
    localidad VARCHAR(50) COMMENT 'Localidad de Bogotá',
    barrio VARCHAR(100) COMMENT 'Barrio de Bogotá',
    
    /* FECHAS */
    fecha_nacimiento DATE COMMENT 'Formato: YYYY-MM-DD',
    fecha_ingreso TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha de registro en la iglesia',
    
    /* CONEXIONES */
    invitado_por INT COMMENT 'ID de la persona que lo invitó',
    conector VARCHAR(50) COMMENT 'Tipo de conexión con la iglesia',
    
    /* INFORMACIÓN ESPIRITUAL */
    recorrido_espiritual TEXT COMMENT 'Observaciones del recorrido espiritual',
    estado_espiritual VARCHAR(50) COMMENT 'Activo, Inactivo, Intermitente, Nuevo, etc.',
    
    /* OTROS */
    foto VARCHAR(255) COMMENT 'Ruta de la imagen subida',
    habeas_data TEXT COMMENT 'Consentimiento para el tratamiento de datos',
    
    /* RELACIONES */
    FOREIGN KEY (invitado_por) REFERENCES InformacionGeneral(id) ON DELETE SET NULL
);
````

## Características de la tabla

- **Organización temática**: Los campos están agrupados lógicamente con comentarios para mejor legibilidad
- **Campos obligatorios**: Solo nombres, apellidos y celular son NOT NULL
- **Formatos definidos**: Cada campo tiene una descripción de su formato esperado
- **Auto-referencia**: El campo "invitado_por" permite vincular miembros entre sí
- **Fecha automática**: La fecha de ingreso se registra automáticamente
- **Soporte para multimedia**: Campo para almacenar la ruta de la foto del miembro

Esta tabla puede ser el punto central de tu sistema, y puedes relacionarla con otras tablas más específicas según tu esquema general de base de datos.