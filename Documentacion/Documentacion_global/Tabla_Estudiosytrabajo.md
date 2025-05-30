## 4. EstudiosTrabajo

````sql
CREATE TABLE EstudiosTrabajo (
    id INT AUTO_INCREMENT PRIMARY KEY,
    miembro_id INT NOT NULL,
    
    /* FORMACIÓN ACADÉMICA */
    nivel_estudios VARCHAR(50) COMMENT 'Primaria, Secundaria, Pregrado, etc.',
    profesion VARCHAR(100) COMMENT 'Profesión o campo de estudio',
    otros_estudios TEXT COMMENT 'Descripción de estudios adicionales',
    
    /* INFORMACIÓN LABORAL */
    empresa VARCHAR(150) COMMENT 'Empresa donde trabaja actualmente',
    direccion_empresa VARCHAR(255) COMMENT 'Dirección de la empresa en formato internacional',
    emprendimientos TEXT COMMENT 'Descripción de emprendimientos personales',
    
    /* METADATOS */
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    /* RELACIONES */
    FOREIGN KEY (miembro_id) REFERENCES InformacionGeneral(id) ON DELETE CASCADE
);
````