# Tabla de Tallas para Iglesia en Casa

Aquí tienes la estructura organizada para la tabla de Tallas:

````sql
CREATE TABLE Tallas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    miembro_id INT NOT NULL,
    
    /* TALLAS DE ROPA SUPERIOR */
    talla_camisa VARCHAR(10) COMMENT 'XS, S, M, L, XL, etc.',
    talla_camiseta VARCHAR(10) COMMENT 'XS, S, M, L, XL, etc.',
    
    /* TALLAS DE ROPA INFERIOR */
    talla_pantalon VARCHAR(10) COMMENT 'Numérico (30, 32) o letra (S, M, L)',
    
    /* CALZADO */
    talla_zapatos VARCHAR(10) COMMENT 'Numeración de calzado',
    
    /* METADATOS */
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    /* RELACIONES */
    FOREIGN KEY (miembro_id) REFERENCES InformacionGeneral(id) ON DELETE CASCADE
);
````

## Características de la tabla

- **Organización por tipo de prenda**: Agrupa las tallas según correspondan a parte superior, inferior o calzado
- **Formato flexible**: Permite registrar tallas tanto en formato alfabético (S, M, L) como numérico
- **Actualización automática**: Registra la última fecha de modificación de los datos
- **Relación con miembro**: Vinculada a la tabla principal de información general

Esta tabla es útil para la organización de eventos que requieran uniformes, la preparación de ropa para campamentos o la distribución de material promocional con tallas específicas.