# Cómo subir tu repositorio de base de datos

Entiendo que quieres subir tu repositorio con los archivos de definición de tablas. Hay dos interpretaciones posibles:

## Opción 1: Subir a GitHub u otro servicio Git

Si quieres subir tu repositorio a GitHub:

1. **Inicializa un repositorio Git** (si aún no lo has hecho):
   ```bash
   cd /Applications/XAMPP/xamppfiles/htdocs/Encasa_Database
   git init
   ```

2. **Añade tus archivos al stage**:
   ```bash
   git add *.md
   ```

3. **Realiza tu primer commit**:
   ```bash
   git commit -m "Primera versión de tablas para Iglesia en Casa"
   ```

4. **Crea un repositorio en GitHub** (desde la web de GitHub)

5. **Conecta y sube tu repositorio local**:
   ```bash
   git remote add origin https://github.com/jsapontep/ENCASA_DATABASE.git
   git branch -M main
   git push -u origin main
   ```

## Opción 2: Implementar todas las tablas en MySQL

Si quieres implementar todas las tablas definidas en tus archivos:

1. **Crea un archivo SQL consolidado**:
   ```bash
   cd /Applications/XAMPP/xamppfiles/htdocs/Encasa_Database
   
   # Extrae y combina código SQL de todos los archivos MD
   cat > todas_las_tablas.sql << 'EOL'
   CREATE DATABASE IF NOT EXISTS IglesiaEnCasa;
   USE IglesiaEnCasa;
   
   -- Tabla InformacionGeneral
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
   
   -- Tabla EstudiosTrabajo
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
   EOL
   ```

2. **Ejecuta el archivo SQL**:
   ```bash
   /Applications/XAMPP/xamppfiles/bin/mysql -u root -p < todas_las_tablas.sql
   ```

¿Cuál de las dos opciones necesitas? ¿O prefieres otro método?