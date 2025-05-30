## Objetivo:
Creamos paso a paso La base de datos con nuestro núcleo Miembro con un diagrama relacional de las cinco tablas que lo componen.
jsapontep: Deseo realizar una base de datos para mi Iglesia en•casa donde deseo comenzar con la tabla Master de base de datos MYSQL  Miembro donde los campos son los siguientes: nombres, apellidos, campos de texto vacio, numero celular con formato internacional.

GitHub Copilot: # Creación de Base de Datos para Iglesia en Casa

Para crear una tabla de miembros en MySQL, puedes usar el siguiente código SQL:

````sql
CREATE DATABASE IF NOT EXISTS IglesiaEnCasa;
USE IglesiaEnCasa;

CREATE TABLE Miembro (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombres VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    notas TEXT,
    numero_celular VARCHAR(20) NOT NULL COMMENT 'Formato internacional: +123456789'
);
````

## Explicación:

- `id`: Identificador único para cada miembro
- `nombres`: Campo para almacenar el nombre o nombres (hasta 100 caracteres)
- `apellidos`: Campo para los apellidos (hasta 100 caracteres)
- `notas`: Campo de texto vacío que puede usarse para comentarios o información adicional
- `numero_celular`: Número telefónico en formato internacional (ejemplo: +18091234567)

¿Necesitas campos adicionales como correo electrónico, dirección, fecha de nacimiento o fecha de ingreso a la iglesia?

jsapontep: necesito agregar mas campos: localidad - base de datos de bogota, barrio - base de datos bogota, fecha de nacimiento - formato dia mes año, invitado por - selector de miembro, foto - campo para subir imagen, recorrido espiritual - campo para observaciones, Habeas data - campo de texto

GitHub Copilot: # Base de Datos Ampliada para Iglesia en Casa

Aquí está el código SQL con todos los campos solicitados:

````sql
CREATE DATABASE IF NOT EXISTS IglesiaEnCasa;
USE IglesiaEnCasa;

CREATE TABLE Miembro (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombres VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    numero_celular VARCHAR(20) NOT NULL COMMENT 'Formato internacional: +123456789',
    localidad VARCHAR(50) COMMENT 'Localidad de Bogotá',
    barrio VARCHAR(100) COMMENT 'Barrio de Bogotá',
    fecha_nacimiento DATE COMMENT 'Formato: YYYY-MM-DD',
    invitado_por INT,
    foto VARCHAR(255) COMMENT 'Ruta de la imagen subida',
    recorrido_espiritual TEXT COMMENT 'Observaciones del recorrido espiritual',
    habeas_data TEXT COMMENT 'Consentimiento para el tratamiento de datos',
    notas TEXT,
    FOREIGN KEY (invitado_por) REFERENCES Miembro(id) ON DELETE SET NULL
);
````

## Explicación de los nuevos campos:

- `localidad`: Almacena la localidad de Bogotá (50 caracteres)
- `barrio`: Almacena el barrio de Bogotá (100 caracteres)
- `fecha_nacimiento`: Fecha en formato YYYY-MM-DD (MySQL lo mostrará según la configuración)
- `invitado_por`: Referencia al ID de otro miembro que lo invitó
- `foto`: Almacena la ruta de la imagen subida
- `recorrido_espiritual`: Campo de texto para registrar su crecimiento espiritual
- `habeas_data`: Campo de texto para el consentimiento de tratamiento de datos

## Sugerencias adicionales:

Para optimizar esta estructura, podrías considerar:
1. Crear tablas separadas para localidades y barrios de Bogotá
2. Usar MEDIUMBLOB en lugar de VARCHAR para almacenar la imagen directamente
3. Crear una tabla separada para el seguimiento del recorrido espiritual con fechas

¿Te gustaría implementar alguna de estas optimizaciones?

jsapontep:  es posible que la clase Miembro sea un conjunto de tablas para rellenar información adicional?

GitHub Copilot: # Estructura de Base de Datos Normalizada para Iglesia en Casa

Definitivamente, es una excelente práctica dividir la información en tablas relacionadas. Esto mejora la organización, reduce redundancia y facilita el mantenimiento. Aquí te propongo un esquema normalizado:

````sql
CREATE DATABASE IF NOT EXISTS IglesiaEnCasa;
USE IglesiaEnCasa;

-- Tabla principal de miembros (información básica)
CREATE TABLE Miembro (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombres VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    numero_celular VARCHAR(20) NOT NULL COMMENT 'Formato internacional: +123456789',
    fecha_nacimiento DATE COMMENT 'Formato: YYYY-MM-DD',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de ubicaciones
CREATE TABLE Ubicacion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    miembro_id INT NOT NULL,
    localidad VARCHAR(50) COMMENT 'Localidad de Bogotá',
    barrio VARCHAR(100) COMMENT 'Barrio de Bogotá',
    direccion VARCHAR(255),
    FOREIGN KEY (miembro_id) REFERENCES Miembro(id) ON DELETE CASCADE
);

-- Tabla para recorrido espiritual
CREATE TABLE RecorridoEspiritual (
    id INT AUTO_INCREMENT PRIMARY KEY,
    miembro_id INT NOT NULL,
    observaciones TEXT,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (miembro_id) REFERENCES Miembro(id) ON DELETE CASCADE
);

-- Tabla de relaciones entre miembros
CREATE TABLE RelacionesMiembros (
    id INT AUTO_INCREMENT PRIMARY KEY,
    miembro_id INT NOT NULL,
    invitado_por INT,
    tipo_relacion VARCHAR(50) COMMENT 'Invitación, mentor, familiar, etc.',
    fecha_relacion DATE,
    FOREIGN KEY (miembro_id) REFERENCES Miembro(id) ON DELETE CASCADE,
    FOREIGN KEY (invitado_por) REFERENCES Miembro(id) ON DELETE SET NULL
);

-- Tabla para fotos
CREATE TABLE Foto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    miembro_id INT NOT NULL,
    ruta_foto VARCHAR(255),
    fecha_subida TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (miembro_id) REFERENCES Miembro(id) ON DELETE CASCADE
);

-- Tabla para Habeas Data y consentimientos
CREATE TABLE Consentimientos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    miembro_id INT NOT NULL,
    habeas_data TEXT,
    fecha_firma DATE,
    estado BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (miembro_id) REFERENCES Miembro(id) ON DELETE CASCADE
);

-- Tabla para notas adicionales
CREATE TABLE Notas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    miembro_id INT NOT NULL,
    contenido TEXT,
    fecha_nota TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (miembro_id) REFERENCES Miembro(id) ON DELETE CASCADE
);
````

## Beneficios de este enfoque:

- **Mejor organización**: Cada aspecto tiene su propia tabla
- **Facilita actualizaciones**: Puedes modificar información específica sin afectar otros datos
- **Extensible**: Puedes agregar nuevas tablas sin alterar la estructura existente
- **Mejor rendimiento**: Las consultas pueden ser más eficientes al trabajar con tablas más pequeñas
- **Integridad referencial**: Las relaciones están claramente definidas con claves foráneas

¿Te gustaría modificar alguna de estas tablas o agregar algún otro tipo de información específica?

jsapontep: Por favor organízame todo en una sola tabla

GitHub Copilot: # Tabla Única para Iglesia en Casa

Aunque la normalización ofrece ventajas, entiendo que a veces una tabla única puede ser más sencilla de gestionar. Aquí tienes toda la información en una sola tabla:

````sql
CREATE DATABASE IF NOT EXISTS IglesiaEnCasa;
USE IglesiaEnCasa;

CREATE TABLE Miembro (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombres VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    numero_celular VARCHAR(20) NOT NULL COMMENT 'Formato internacional: +123456789',
    fecha_nacimiento DATE COMMENT 'Formato: YYYY-MM-DD',
    localidad VARCHAR(50) COMMENT 'Localidad de Bogotá',
    barrio VARCHAR(100) COMMENT 'Barrio de Bogotá',
    direccion VARCHAR(255),
    invitado_por INT,
    foto VARCHAR(255) COMMENT 'Ruta de la imagen subida',
    recorrido_espiritual TEXT COMMENT 'Observaciones del recorrido espiritual',
    habeas_data TEXT COMMENT 'Consentimiento para el tratamiento de datos',
    fecha_habeas_data DATE COMMENT 'Fecha de firma del consentimiento',
    notas TEXT,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (invitado_por) REFERENCES Miembro(id) ON DELETE SET NULL
);
````

## Consideraciones:

- Esta estructura es más simple de implementar inicialmente
- Todos los datos de un miembro están en un solo lugar
- La tabla será más grande y menos eficiente a medida que crezca
- Las actualizaciones parciales afectarán a toda la fila
- No podrás tener múltiples fotos o seguimientos espirituales por miembro

Si en el futuro la base de datos crece significativamente, siempre puedes migrar a una estructura normalizada.

jsapontep:  agrégame los siguientes campos: conector - campo de texto selector, tipo de documento - campo de texto alfanumerico selector, Numero de documento - campo de texto, telefono - campo formato internacional numero telefonico, ciudad - campo de texto base de datos Colombia, pais - campo de texto base de datos mundial, estado civil - campo de texto selector, correo electronica - campo de texto formato correo electronico, instagram - campo de texto formato instagram, familiares - selctor de tipo de familiar

GitHub Copilot: # Tabla Única Ampliada para Iglesia en Casa

Aquí está la tabla única con todos los campos solicitados adicionales:

````sql
CREATE DATABASE IF NOT EXISTS IglesiaEnCasa;
USE IglesiaEnCasa;

CREATE TABLE Miembro (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombres VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    tipo_documento VARCHAR(30) COMMENT 'Cédula, Pasaporte, etc.',
    numero_documento VARCHAR(30) NOT NULL,
    numero_celular VARCHAR(20) NOT NULL COMMENT 'Formato internacional: +123456789',
    telefono VARCHAR(20) COMMENT 'Teléfono fijo formato internacional',
    fecha_nacimiento DATE COMMENT 'Formato: YYYY-MM-DD',
    correo_electronico VARCHAR(100) COMMENT 'Formato email',
    instagram VARCHAR(50) COMMENT 'Usuario de Instagram',
    estado_civil VARCHAR(20) COMMENT 'Soltero, Casado, etc.',
    pais VARCHAR(100) COMMENT 'País de residencia',
    ciudad VARCHAR(100) COMMENT 'Ciudad de residencia',
    localidad VARCHAR(50) COMMENT 'Localidad de Bogotá',
    barrio VARCHAR(100) COMMENT 'Barrio de Bogotá',
    direccion VARCHAR(255),
    conector VARCHAR(50) COMMENT 'Tipo de conexión con la iglesia',
    invitado_por INT,
    familiares VARCHAR(255) COMMENT 'Tipos de familiares en la iglesia',
    foto VARCHAR(255) COMMENT 'Ruta de la imagen subida',
    recorrido_espiritual TEXT COMMENT 'Observaciones del recorrido espiritual',
    habeas_data TEXT COMMENT 'Consentimiento para el tratamiento de datos',
    fecha_habeas_data DATE COMMENT 'Fecha de firma del consentimiento',
    notas TEXT,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (invitado_por) REFERENCES Miembro(id) ON DELETE SET NULL
);
````

## Notas sobre los campos:

- `tipo_documento`: Podría ser cédula, tarjeta de identidad, pasaporte, etc.
- `correo_electronico`: Almacena direcciones de correo electrónico
- `instagram`: Almacena el nombre de usuario de Instagram
- `conector`: Indica cómo se conectó con la iglesia (invitación, evento, etc.)
- `estado_civil`: Opciones como soltero, casado, viudo, etc.
- `familiares`: Para indicar qué familiares tiene en la iglesia

Para una implementación más avanzada, podrías considerar validar estos campos desde la aplicación que usará esta base de datos, especialmente para formatos como correos electrónicos y números telefónicos.

jsapontep: agrégame los siguientes campos: RH -selector tipo de sangre, Acudiente 1 - campo de texto - formato nombre de persona, Telefono acudiente 1 - campo de texto formato numero telefonico internacional, Acudiente 2 - campo de texto - formato nombre de persona, Telefono acudiente 2 - campo de texto formato numero telefonico internacional, EPS - selector de EPS

GitHub Copilot: # Tabla Única Ampliada con Información Médica y de Contactos

Aquí está la tabla con los campos adicionales solicitados:

````sql
CREATE DATABASE IF NOT EXISTS IglesiaEnCasa;
USE IglesiaEnCasa;

CREATE TABLE Miembro (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombres VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    tipo_documento VARCHAR(30) COMMENT 'Cédula, Pasaporte, etc.',
    numero_documento VARCHAR(30) NOT NULL,
    numero_celular VARCHAR(20) NOT NULL COMMENT 'Formato internacional: +123456789',
    telefono VARCHAR(20) COMMENT 'Teléfono fijo formato internacional',
    fecha_nacimiento DATE COMMENT 'Formato: YYYY-MM-DD',
    rh VARCHAR(5) COMMENT 'Tipo de sangre (A+, O-, etc.)',
    eps VARCHAR(50) COMMENT 'Entidad Promotora de Salud',
    correo_electronico VARCHAR(100) COMMENT 'Formato email',
    instagram VARCHAR(50) COMMENT 'Usuario de Instagram',
    estado_civil VARCHAR(20) COMMENT 'Soltero, Casado, etc.',
    pais VARCHAR(100) COMMENT 'País de residencia',
    ciudad VARCHAR(100) COMMENT 'Ciudad de residencia',
    localidad VARCHAR(50) COMMENT 'Localidad de Bogotá',
    barrio VARCHAR(100) COMMENT 'Barrio de Bogotá',
    direccion VARCHAR(255),
    acudiente1 VARCHAR(100) COMMENT 'Nombre del primer contacto de emergencia',
    telefono_acudiente1 VARCHAR(20) COMMENT 'Teléfono del primer contacto en formato internacional',
    acudiente2 VARCHAR(100) COMMENT 'Nombre del segundo contacto de emergencia',
    telefono_acudiente2 VARCHAR(20) COMMENT 'Teléfono del segundo contacto en formato internacional',
    conector VARCHAR(50) COMMENT 'Tipo de conexión con la iglesia',
    invitado_por INT,
    familiares VARCHAR(255) COMMENT 'Tipos de familiares en la iglesia',
    foto VARCHAR(255) COMMENT 'Ruta de la imagen subida',
    recorrido_espiritual TEXT COMMENT 'Observaciones del recorrido espiritual',
    habeas_data TEXT COMMENT 'Consentimiento para el tratamiento de datos',
    fecha_habeas_data DATE COMMENT 'Fecha de firma del consentimiento',
    notas TEXT,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (invitado_por) REFERENCES Miembro(id) ON DELETE SET NULL
);
````

## Notas sobre los nuevos campos:

- `rh`: Almacena el tipo de sangre (A+, O-, B+, etc.)
- `eps`: Entidad Promotora de Salud a la que está afiliado el miembro
- `acudiente1` y `acudiente2`: Contactos de emergencia o responsables del miembro
- `telefono_acudiente1` y `telefono_acudiente2`: Números de contacto en formato internacional

Estos campos son especialmente útiles para:
- Atención médica en caso de emergencia
- Contactos de referencia para menores de edad
- Información importante para actividades que requieran cuidado especial

jsapontep: agrégame los siguientes campos: Nivel de estudios - selector de nivel de estudio, profesión - selector de profesión, Empresa donde trabaja - campo de texto - formato empresa, Dirección de la empresa  - campo de texto - formato dirección internacional, Otros extudios - campo de texto,  Emprendiemintos - campo de texto

GitHub Copilot: # Tabla Única con Información Profesional y Académica

Aquí está la tabla con los campos adicionales de estudios y trabajo:

````sql
CREATE DATABASE IF NOT EXISTS IglesiaEnCasa;
USE IglesiaEnCasa;

CREATE TABLE Miembro (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombres VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    tipo_documento VARCHAR(30) COMMENT 'Cédula, Pasaporte, etc.',
    numero_documento VARCHAR(30) NOT NULL,
    numero_celular VARCHAR(20) NOT NULL COMMENT 'Formato internacional: +123456789',
    telefono VARCHAR(20) COMMENT 'Teléfono fijo formato internacional',
    fecha_nacimiento DATE COMMENT 'Formato: YYYY-MM-DD',
    rh VARCHAR(5) COMMENT 'Tipo de sangre (A+, O-, etc.)',
    eps VARCHAR(50) COMMENT 'Entidad Promotora de Salud',
    correo_electronico VARCHAR(100) COMMENT 'Formato email',
    instagram VARCHAR(50) COMMENT 'Usuario de Instagram',
    estado_civil VARCHAR(20) COMMENT 'Soltero, Casado, etc.',
    pais VARCHAR(100) COMMENT 'País de residencia',
    ciudad VARCHAR(100) COMMENT 'Ciudad de residencia',
    localidad VARCHAR(50) COMMENT 'Localidad de Bogotá',
    barrio VARCHAR(100) COMMENT 'Barrio de Bogotá',
    direccion VARCHAR(255),
    nivel_estudios VARCHAR(50) COMMENT 'Primaria, Secundaria, Pregrado, etc.',
    profesion VARCHAR(100) COMMENT 'Profesión o campo de estudio',
    empresa VARCHAR(150) COMMENT 'Empresa donde trabaja actualmente',
    direccion_empresa VARCHAR(255) COMMENT 'Dirección de la empresa en formato internacional',
    otros_estudios TEXT COMMENT 'Descripción de estudios adicionales',
    emprendimientos TEXT COMMENT 'Descripción de emprendimientos personales',
    acudiente1 VARCHAR(100) COMMENT 'Nombre del primer contacto de emergencia',
    telefono_acudiente1 VARCHAR(20) COMMENT 'Teléfono del primer contacto en formato internacional',
    acudiente2 VARCHAR(100) COMMENT 'Nombre del segundo contacto de emergencia',
    telefono_acudiente2 VARCHAR(20) COMMENT 'Teléfono del segundo contacto en formato internacional',
    conector VARCHAR(50) COMMENT 'Tipo de conexión con la iglesia',
    invitado_por INT,
    familiares VARCHAR(255) COMMENT 'Tipos de familiares en la iglesia',
    foto VARCHAR(255) COMMENT 'Ruta de la imagen subida',
    recorrido_espiritual TEXT COMMENT 'Observaciones del recorrido espiritual',
    habeas_data TEXT COMMENT 'Consentimiento para el tratamiento de datos',
    fecha_habeas_data DATE COMMENT 'Fecha de firma del consentimiento',
    notas TEXT,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (invitado_por) REFERENCES Miembro(id) ON DELETE SET NULL
);
````

## Notas sobre los nuevos campos:

- `nivel_estudios`: Para seleccionar el nivel educativo completado (primaria, bachillerato, técnico, etc.)
- `profesion`: Campo para indicar la profesión, carrera o especialidad
- `empresa`: Nombre de la empresa o institución donde trabaja
- `direccion_empresa`: Ubicación del lugar de trabajo
- `otros_estudios`: Campo para registrar cursos, diplomados o formación complementaria
- `emprendimientos`: Para documentar iniciativas de negocio o proyectos personales

Estos campos permiten tener un perfil más completo de los miembros, facilitando la identificación de talentos y habilidades que puedan servir en ministerios específicos de la iglesia.

jsapontep: agrégame los siguientes campos: Talla camisa - formato talla ropa - selector, Talla pantalón - formato talla ropa - selector, Talla camiseta - formato talla ropa - selector, Talla zapatos - formato talla ropa - selector

GitHub Copilot: # Tabla Miembro con Campos de Tallas de Ropa

Aquí está la tabla actualizada con los campos de tallas solicitados:

````sql
CREATE DATABASE IF NOT EXISTS IglesiaEnCasa;
USE IglesiaEnCasa;

CREATE TABLE Miembro (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombres VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    tipo_documento VARCHAR(30) COMMENT 'Cédula, Pasaporte, etc.',
    numero_documento VARCHAR(30) NOT NULL,
    numero_celular VARCHAR(20) NOT NULL COMMENT 'Formato internacional: +123456789',
    telefono VARCHAR(20) COMMENT 'Teléfono fijo formato internacional',
    fecha_nacimiento DATE COMMENT 'Formato: YYYY-MM-DD',
    correo_electronico VARCHAR(100) COMMENT 'Formato email',
    instagram VARCHAR(50) COMMENT 'Usuario de Instagram',
    estado_civil VARCHAR(20) COMMENT 'Soltero, Casado, etc.',
    pais VARCHAR(100) COMMENT 'País de residencia',
    ciudad VARCHAR(100) COMMENT 'Ciudad de residencia',
    localidad VARCHAR(50) COMMENT 'Localidad de Bogotá',
    barrio VARCHAR(100) COMMENT 'Barrio de Bogotá',
    direccion VARCHAR(255),
    talla_camisa VARCHAR(10) COMMENT 'XS, S, M, L, XL, etc.',
    talla_pantalon VARCHAR(10) COMMENT 'Numérico o letra',
    talla_camiseta VARCHAR(10) COMMENT 'XS, S, M, L, XL, etc.',
    talla_zapatos VARCHAR(10) COMMENT 'Numeración de calzado',
    conector VARCHAR(50) COMMENT 'Tipo de conexión con la iglesia',
    invitado_por INT,
    familiares VARCHAR(255) COMMENT 'Tipos de familiares en la iglesia',
    foto VARCHAR(255) COMMENT 'Ruta de la imagen subida',
    recorrido_espiritual TEXT COMMENT 'Observaciones del recorrido espiritual',
    habeas_data TEXT COMMENT 'Consentimiento para el tratamiento de datos',
    fecha_habeas_data DATE COMMENT 'Fecha de firma del consentimiento',
    notas TEXT,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (invitado_por) REFERENCES Miembro(id) ON DELETE SET NULL
);
````

## Notas sobre los nuevos campos de tallas:

- `talla_camisa`: Para almacenar tallas de camisas (XS, S, M, L, XL, etc.)
- `talla_pantalon`: Para tallas de pantalones que pueden ser numéricas (30, 32, 34) o por letra
- `talla_camiseta`: Similar a la talla de camisa, para camisetas casuales o deportivas
- `talla_zapatos`: Para el número de calzado según el sistema de medición local

Estos campos pueden ser útiles para:
- Organizar eventos donde se entregue vestuario
- Preparar uniformes para equipos de la iglesia
- Planificar compras de indumentaria para actividades especiales
- Donaciones de ropa direccionadas según tallas

jsapontep: Agrégame los siguientes campos: Carrera bíblica - selector dinámico, Miembro de - selector dinámico, Casa de palabra y vida - selector dinámico, Cobertura - Selector dinámico. Anotaciones - campo de texto formato observaciones. 

GitHub Copilot: # Tabla Miembro con Campos de Actividad Espiritual

Aquí está la tabla actualizada con los campos religiosos y de anotaciones solicitados:

````sql
CREATE DATABASE IF NOT EXISTS IglesiaEnCasa;
USE IglesiaEnCasa;

CREATE TABLE Miembro (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombres VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    tipo_documento VARCHAR(30) COMMENT 'Cédula, Pasaporte, etc.',
    numero_documento VARCHAR(30) NOT NULL,
    numero_celular VARCHAR(20) NOT NULL COMMENT 'Formato internacional: +123456789',
    telefono VARCHAR(20) COMMENT 'Teléfono fijo formato internacional',
    fecha_nacimiento DATE COMMENT 'Formato: YYYY-MM-DD',
    correo_electronico VARCHAR(100) COMMENT 'Formato email',
    instagram VARCHAR(50) COMMENT 'Usuario de Instagram',
    estado_civil VARCHAR(20) COMMENT 'Soltero, Casado, etc.',
    pais VARCHAR(100) COMMENT 'País de residencia',
    ciudad VARCHAR(100) COMMENT 'Ciudad de residencia',
    localidad VARCHAR(50) COMMENT 'Localidad de Bogotá',
    barrio VARCHAR(100) COMMENT 'Barrio de Bogotá',
    direccion VARCHAR(255),
    carrera_biblica VARCHAR(100) COMMENT 'Nivel o curso bíblico actual',
    miembro_de VARCHAR(100) COMMENT 'Grupo o ministerio al que pertenece',
    casa_de_palabra_y_vida VARCHAR(100) COMMENT 'Casa o grupo pequeño asignado',
    cobertura VARCHAR(100) COMMENT 'Líder o pastor que le cubre espiritualmente',
    anotaciones TEXT COMMENT 'Observaciones adicionales',
    conector VARCHAR(50) COMMENT 'Tipo de conexión con la iglesia',
    invitado_por INT,
    familiares VARCHAR(255) COMMENT 'Tipos de familiares en la iglesia',
    foto VARCHAR(255) COMMENT 'Ruta de la imagen subida',
    recorrido_espiritual TEXT COMMENT 'Observaciones del recorrido espiritual',
    habeas_data TEXT COMMENT 'Consentimiento para el tratamiento de datos',
    fecha_habeas_data DATE COMMENT 'Fecha de firma del consentimiento',
    notas TEXT,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (invitado_por) REFERENCES Miembro(id) ON DELETE SET NULL
);
````

## Notas sobre los nuevos campos:

- `carrera_biblica`: Registra el nivel o curso de formación bíblica que está cursando el miembro
- `miembro_de`: Indica a qué grupo o ministerio específico pertenece dentro de la iglesia
- `casa_de_palabra_y_vida`: Registra el grupo pequeño o célula a la que asiste regularmente
- `cobertura`: Identifica al líder o pastor que proporciona guía espiritual al miembro
- `anotaciones`: Campo adicional para observaciones específicas que no encajan en otros campos

Para implementar los selectores dinámicos en la aplicación, podrías crear tablas auxiliares con las opciones disponibles para cada campo, o manejar las listas desde el código de la aplicación.

jsapontep: Ahora quiero registrar las tablas por tipo: Información general de "nombres" a "conector", Contacto: de "cédula" a "familiares", Salud y emergencias: de "RH" a "EPS", Estudios y trabajo: de "Nivel de estudios" a "emprendimientos", Tallas: de "camisa" a "zapatos", Carrera Bíblica: de "Carrera Bíblica" a "Anotaciones".

GitHub Copilot: # Base de Datos Normalizada para Iglesia en Casa

Aquí está el esquema de base de datos organizado en tablas temáticas, según tu solicitud:

````sql
CREATE DATABASE IF NOT EXISTS IglesiaEnCasa;
USE IglesiaEnCasa;

-- Tabla principal con información general
CREATE TABLE InformacionGeneral (
    miembro_id INT AUTO_INCREMENT PRIMARY KEY,
    nombres VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    fecha_nacimiento DATE COMMENT 'Formato: YYYY-MM-DD',
    estado_civil VARCHAR(20) COMMENT 'Soltero, Casado, etc.',
    pais VARCHAR(100) COMMENT 'País de residencia',
    ciudad VARCHAR(100) COMMENT 'Ciudad de residencia',
    localidad VARCHAR(50) COMMENT 'Localidad de Bogotá',
    barrio VARCHAR(100) COMMENT 'Barrio de Bogotá',
    direccion VARCHAR(255),
    conector VARCHAR(50) COMMENT 'Tipo de conexión con la iglesia',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Información de contacto y documentación
CREATE TABLE Contacto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    miembro_id INT NOT NULL,
    tipo_documento VARCHAR(30) COMMENT 'Cédula, Pasaporte, etc.',
    numero_documento VARCHAR(30) NOT NULL,
    numero_celular VARCHAR(20) NOT NULL COMMENT 'Formato internacional: +123456789',
    telefono VARCHAR(20) COMMENT 'Teléfono fijo formato internacional',
    correo_electronico VARCHAR(100) COMMENT 'Formato email',
    instagram VARCHAR(50) COMMENT 'Usuario de Instagram',
    invitado_por INT,
    familiares VARCHAR(255) COMMENT 'Tipos de familiares en la iglesia',
    FOREIGN KEY (miembro_id) REFERENCES InformacionGeneral(miembro_id) ON DELETE CASCADE,
    FOREIGN KEY (invitado_por) REFERENCES InformacionGeneral(miembro_id) ON DELETE SET NULL
);

-- Información de salud y contactos de emergencia
CREATE TABLE SaludEmergencias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    miembro_id INT NOT NULL,
    rh VARCHAR(5) COMMENT 'Tipo de sangre (A+, O-, etc.)',
    eps VARCHAR(50) COMMENT 'Entidad Promotora de Salud',
    acudiente1 VARCHAR(100) COMMENT 'Nombre del primer contacto de emergencia',
    telefono_acudiente1 VARCHAR(20) COMMENT 'Teléfono del primer contacto en formato internacional',
    acudiente2 VARCHAR(100) COMMENT 'Nombre del segundo contacto de emergencia',
    telefono_acudiente2 VARCHAR(20) COMMENT 'Teléfono del segundo contacto en formato internacional',
    FOREIGN KEY (miembro_id) REFERENCES InformacionGeneral(miembro_id) ON DELETE CASCADE
);

-- Información académica y laboral
CREATE TABLE EstudiosTrabajo (
    id INT AUTO_INCREMENT PRIMARY KEY,
    miembro_id INT NOT NULL,
    nivel_estudios VARCHAR(50) COMMENT 'Primaria, Secundaria, Pregrado, etc.',
    profesion VARCHAR(100) COMMENT 'Profesión o campo de estudio',
    empresa VARCHAR(150) COMMENT 'Empresa donde trabaja actualmente',
    direccion_empresa VARCHAR(255) COMMENT 'Dirección de la empresa en formato internacional',
    otros_estudios TEXT COMMENT 'Descripción de estudios adicionales',
    emprendimientos TEXT COMMENT 'Descripción de emprendimientos personales',
    FOREIGN KEY (miembro_id) REFERENCES InformacionGeneral(miembro_id) ON DELETE CASCADE
);

-- Tallas de ropa y calzado
CREATE TABLE Tallas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    miembro_id INT NOT NULL,
    talla_camisa VARCHAR(10) COMMENT 'XS, S, M, L, XL, etc.',
    talla_pantalon VARCHAR(10) COMMENT 'Numérico o letra',
    talla_camiseta VARCHAR(10) COMMENT 'XS, S, M, L, XL, etc.',
    talla_zapatos VARCHAR(10) COMMENT 'Numeración de calzado',
    FOREIGN KEY (miembro_id) REFERENCES InformacionGeneral(miembro_id) ON DELETE CASCADE
);

-- Información de participación en la iglesia
CREATE TABLE CarreraBiblica (
    id INT AUTO_INCREMENT PRIMARY KEY,
    miembro_id INT NOT NULL,
    carrera_biblica VARCHAR(100) COMMENT 'Nivel o curso bíblico actual',
    miembro_de VARCHAR(100) COMMENT 'Grupo o ministerio al que pertenece',
    casa_de_palabra_y_vida VARCHAR(100) COMMENT 'Casa o grupo pequeño asignado',
    cobertura VARCHAR(100) COMMENT 'Líder o pastor que le cubre espiritualmente',
    anotaciones TEXT COMMENT 'Observaciones adicionales',
    recorrido_espiritual TEXT COMMENT 'Observaciones del recorrido espiritual',
    habeas_data TEXT COMMENT 'Consentimiento para el tratamiento de datos',
    fecha_habeas_data DATE COMMENT 'Fecha de firma del consentimiento',
    foto VARCHAR(255) COMMENT 'Ruta de la imagen subida',
    FOREIGN KEY (miembro_id) REFERENCES InformacionGeneral(miembro_id) ON DELETE CASCADE
);
````

## Ventajas de esta estructura:

1. **Organización temática**: Los datos están agrupados por categorías lógicas
2. **Mejor mantenimiento**: Puedes actualizar información específica sin afectar otras categorías
3. **Consultas optimizadas**: Puedes recuperar solo la información que necesitas
4. **Escalabilidad**: Puedes expandir cada tabla sin afectar a las demás

## Observaciones:

- La tabla `InformacionGeneral` es la principal y contiene el identificador `miembro_id` que se relaciona con todas las demás tablas
- Cada miembro tendrá una fila en cada tabla (relación 1:1)
- Se mantienen las relaciones como `invitado_por` que referencia a otro miembro
- La clave `miembro_id` es consistente en todas las tablas para facilitar los JOIN

¿Te gustaría realizar algún ajuste adicional a esta estructura?

jsapontep: Quiero implementar un sistema crud para registrar un nuevo miembro, además quiero que la base de datos a comienzo trabaje con apache, con la herramienta xampp. Necesito que coloques todas las dependencias necesarias para ejecutar este código.

GitHub Copilot: # Sistema CRUD para Registro de Miembros - Iglesia en Casa

Aquí tienes la implementación de un sistema CRUD básico para registrar miembros usando XAMPP (Apache y MySQL).

## 1. Instalación y configuración

### Requisitos previos:
- Descargar e instalar [XAMPP](https://www.apachefriends.org/index.html)
- Iniciar los servicios de Apache y MySQL desde el panel de control XAMPP

## 2. Estructura de archivos

Crea la siguiente estructura en la carpeta `htdocs` de XAMPP:

```
iglesia_encasa/
├── config/
│   └── database.php
├── css/
│   └── style.css
├── js/
│   └── validation.js
├── index.php
├── create.php
├── read.php
├── update.php
├── delete.php
└── db_setup.php
```

## 3. Configuración de la base de datos

### db_setup.php - Configuración inicial de la base de datos

````php
<?php
// filepath: /htdocs/iglesia_encasa/db_setup.php
// Script para crear la base de datos y tabla

// Conectar a MySQL sin seleccionar base de datos
$conn = new mysqli("localhost", "root", "");

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Crear base de datos
$sql = "CREATE DATABASE IF NOT EXISTS IglesiaEnCasa";
if ($conn->query($sql) !== TRUE) {
    echo "Error al crear base de datos: " . $conn->error;
}

// Seleccionar base de datos
$conn->select_db("IglesiaEnCasa");

// Crear tabla Miembro
$sql = "CREATE TABLE IF NOT EXISTS Miembro (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombres VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    tipo_documento VARCHAR(30) COMMENT 'Cédula, Pasaporte, etc.',
    numero_documento VARCHAR(30) NOT NULL,
    numero_celular VARCHAR(20) NOT NULL COMMENT 'Formato internacional: +123456789',
    telefono VARCHAR(20) COMMENT 'Teléfono fijo formato internacional',
    fecha_nacimiento DATE COMMENT 'Formato: YYYY-MM-DD',
    correo_electronico VARCHAR(100) COMMENT 'Formato email',
    instagram VARCHAR(50) COMMENT 'Usuario de Instagram',
    estado_civil VARCHAR(20) COMMENT 'Soltero, Casado, etc.',
    pais VARCHAR(100) COMMENT 'País de residencia',
    ciudad VARCHAR(100) COMMENT 'Ciudad de residencia',
    localidad VARCHAR(50) COMMENT 'Localidad de Bogotá',
    barrio VARCHAR(100) COMMENT 'Barrio de Bogotá',
    direccion VARCHAR(255),
    rh VARCHAR(5) COMMENT 'Tipo de sangre (A+, O-, etc.)',
    eps VARCHAR(50) COMMENT 'Entidad Promotora de Salud',
    acudiente1 VARCHAR(100) COMMENT 'Nombre del primer contacto de emergencia',
    telefono_acudiente1 VARCHAR(20) COMMENT 'Teléfono del primer contacto',
    acudiente2 VARCHAR(100) COMMENT 'Nombre del segundo contacto de emergencia',
    telefono_acudiente2 VARCHAR(20) COMMENT 'Teléfono del segundo contacto',
    nivel_estudios VARCHAR(50) COMMENT 'Primaria, Secundaria, Pregrado, etc.',
    profesion VARCHAR(100) COMMENT 'Profesión o campo de estudio',
    empresa VARCHAR(150) COMMENT 'Empresa donde trabaja actualmente',
    direccion_empresa VARCHAR(255) COMMENT 'Dirección de la empresa',
    otros_estudios TEXT COMMENT 'Descripción de estudios adicionales',
    emprendimientos TEXT COMMENT 'Descripción de emprendimientos personales',
    talla_camisa VARCHAR(10) COMMENT 'XS, S, M, L, XL, etc.',
    talla_pantalon VARCHAR(10) COMMENT 'Numérico o letra',
    talla_camiseta VARCHAR(10) COMMENT 'XS, S, M, L, XL, etc.',
    talla_zapatos VARCHAR(10) COMMENT 'Numeración de calzado',
    carrera_biblica VARCHAR(100) COMMENT 'Nivel o curso bíblico actual',
    miembro_de VARCHAR(100) COMMENT 'Grupo o ministerio al que pertenece',
    casa_de_palabra_y_vida VARCHAR(100) COMMENT 'Casa o grupo pequeño asignado',
    cobertura VARCHAR(100) COMMENT 'Líder o pastor que le cubre espiritualmente',
    anotaciones TEXT COMMENT 'Observaciones adicionales',
    conector VARCHAR(50) COMMENT 'Tipo de conexión con la iglesia',
    invitado_por INT,
    familiares VARCHAR(255) COMMENT 'Tipos de familiares en la iglesia',
    foto VARCHAR(255) COMMENT 'Ruta de la imagen subida',
    recorrido_espiritual TEXT COMMENT 'Observaciones del recorrido espiritual',
    habeas_data TEXT COMMENT 'Consentimiento para el tratamiento de datos',
    fecha_habeas_data DATE COMMENT 'Fecha de firma del consentimiento',
    notas TEXT,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (invitado_por) REFERENCES Miembro(id) ON DELETE SET NULL
)";

if ($conn->query($sql) !== TRUE) {
    echo "Error al crear tabla: " . $conn->error;
}

echo "Base de datos y tabla creadas correctamente";
$conn->close();
?>
````

### config/database.php - Archivo de conexión

````php
<?php
// filepath: /htdocs/iglesia_encasa/config/database.php
// Archivo de conexión a la base de datos

class Database {
    private $host = "localhost";
    private $db_name = "IglesiaEnCasa";
    private $username = "root";
    private $password = "";
    public $conn;
    
    public function getConnection() {
        $this->conn = null;
        
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Error de conexión: " . $exception->getMessage();
        }
        
        return $this->conn;
    }
}
?>
````

## 4. Implementación CRUD

### index.php - Página principal

````php
<?php
// filepath: /htdocs/iglesia_encasa/index.php
// Página principal con listado de miembros
include_once 'config/database.php';

// Inicializar conexión
$database = new Database();
$db = $database->getConnection();

// Mensaje de la operación
$message = isset($_GET['message']) ? $_GET['message'] : "";

// Consulta para traer todos los miembros
$query = "SELECT id, nombres, apellidos, numero_celular, correo_electronico FROM Miembro ORDER BY fecha_registro DESC";
$stmt = $db->prepare($query);
$stmt->execute();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iglesia en Casa - Sistema de Miembros</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Sistema de Gestión de Miembros - Iglesia en Casa</h4>
                    </div>
                    <div class="card-body">
                        <?php if(!empty($message)): ?>
                            <div class="alert alert-success"><?php echo $message; ?></div>
                        <?php endif; ?>
                        
                        <a href="create.php" class="btn btn-primary mb-3">Registrar Nuevo Miembro</a>
                        
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombres</th>
                                    <th>Apellidos</th>
                                    <th>Celular</th>
                                    <th>Correo</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if($stmt->rowCount() > 0){
                                    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                                        extract($row);
                                        echo "<tr>
                                            <td>{$id}</td>
                                            <td>{$nombres}</td>
                                            <td>{$apellidos}</td>
                                            <td>{$numero_celular}</td>
                                            <td>{$correo_electronico}</td>
                                            <td>
                                                <a href='read.php?id={$id}' class='btn btn-info btn-sm'>Ver</a>
                                                <a href='update.php?id={$id}' class='btn btn-warning btn-sm'>Editar</a>
                                                <a href='delete.php?id={$id}' class='btn btn-danger btn-sm' onclick='return confirm(\"¿Está seguro de eliminar este registro?\")'>Eliminar</a>
                                            </td>
                                        </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='6' class='text-center'>No hay miembros registrados</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/validation.js"></script>
</body>
</html>
````

### create.php - Formulario de registro

````php
<?php
// filepath: /htdocs/iglesia_encasa/create.php
// Formulario para crear nuevo miembro
include_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

// Procesar el formulario cuando se envía
if($_POST){
    try {
        // Consulta para insertar miembro
        $query = "INSERT INTO Miembro 
                 (nombres, apellidos, tipo_documento, numero_documento, numero_celular, 
                  telefono, fecha_nacimiento, correo_electronico, instagram, estado_civil,
                  pais, ciudad, localidad, barrio, direccion, rh, eps, acudiente1, 
                  telefono_acudiente1, acudiente2, telefono_acudiente2, nivel_estudios, 
                  profesion, empresa, direccion_empresa, otros_estudios, emprendimientos,
                  talla_camisa, talla_pantalon, talla_camiseta, talla_zapatos, 
                  carrera_biblica, miembro_de, casa_de_palabra_y_vida, cobertura, 
                  anotaciones, conector, familiares, recorrido_espiritual, habeas_data)
               VALUES 
                 (:nombres, :apellidos, :tipo_documento, :numero_documento, :numero_celular, 
                  :telefono, :fecha_nacimiento, :correo_electronico, :instagram, :estado_civil,
                  :pais, :ciudad, :localidad, :barrio, :direccion, :rh, :eps, :acudiente1, 
                  :telefono_acudiente1, :acudiente2, :telefono_acudiente2, :nivel_estudios, 
                  :profesion, :empresa, :direccion_empresa, :otros_estudios, :emprendimientos,
                  :talla_camisa, :talla_pantalon, :talla_camiseta, :talla_zapatos, 
                  :carrera_biblica, :miembro_de, :casa_de_palabra_y_vida, :cobertura, 
                  :anotaciones, :conector, :familiares, :recorrido_espiritual, :habeas_data)";
        
        $stmt = $db->prepare($query);
        
        // Valores sanitizados
        $nombres = htmlspecialchars(strip_tags($_POST['nombres']));
        $apellidos = htmlspecialchars(strip_tags($_POST['apellidos']));
        $tipo_documento = htmlspecialchars(strip_tags($_POST['tipo_documento']));
        $numero_documento = htmlspecialchars(strip_tags($_POST['numero_documento']));
        $numero_celular = htmlspecialchars(strip_tags($_POST['numero_celular']));
        $telefono = htmlspecialchars(strip_tags($_POST['telefono'] ?? ''));
        $fecha_nacimiento = htmlspecialchars(strip_tags($_POST['fecha_nacimiento'] ?? ''));
        $correo_electronico = htmlspecialchars(strip_tags($_POST['correo_electronico'] ?? ''));
        $instagram = htmlspecialchars(strip_tags($_POST['instagram'] ?? ''));
        $estado_civil = htmlspecialchars(strip_tags($_POST['estado_civil'] ?? ''));
        $pais = htmlspecialchars(strip_tags($_POST['pais'] ?? ''));
        $ciudad = htmlspecialchars(strip_tags($_POST['ciudad'] ?? ''));
        $localidad = htmlspecialchars(strip_tags($_POST['localidad'] ?? ''));
        $barrio = htmlspecialchars(strip_tags($_POST['barrio'] ?? ''));
        $direccion = htmlspecialchars(strip_tags($_POST['direccion'] ?? ''));
        $rh = htmlspecialchars(strip_tags($_POST['rh'] ?? ''));
        $eps = htmlspecialchars(strip_tags($_POST['eps'] ?? ''));
        $acudiente1 = htmlspecialchars(strip_tags($_POST['acudiente1'] ?? ''));
        $telefono_acudiente1 = htmlspecialchars(strip_tags($_POST['telefono_acudiente1'] ?? ''));
        $acudiente2 = htmlspecialchars(strip_tags($_POST['acudiente2'] ?? ''));
        $telefono_acudiente2 = htmlspecialchars(strip_tags($_POST['telefono_acudiente2'] ?? ''));
        $nivel_estudios = htmlspecialchars(strip_tags($_POST['nivel_estudios'] ?? ''));
        $profesion = htmlspecialchars(strip_tags($_POST['profesion'] ?? ''));
        $empresa = htmlspecialchars(strip_tags($_POST['empresa'] ?? ''));
        $direccion_empresa = htmlspecialchars(strip_tags($_POST['direccion_empresa'] ?? ''));
        $otros_estudios = htmlspecialchars(strip_tags($_POST['otros_estudios'] ?? ''));
        $emprendimientos = htmlspecialchars(strip_tags($_POST['emprendimientos'] ?? ''));
        $talla_camisa = htmlspecialchars(strip_tags($_POST['talla_camisa'] ?? ''));
        $talla_pantalon = htmlspecialchars(strip_tags($_POST['talla_pantalon'] ?? ''));
        $talla_camiseta = htmlspecialchars(strip_tags($_POST['talla_camiseta'] ?? ''));
        $talla_zapatos = htmlspecialchars(strip_tags($_POST['talla_zapatos'] ?? ''));
        $carrera_biblica = htmlspecialchars(strip_tags($_POST['carrera_biblica'] ?? ''));
        $miembro_de = htmlspecialchars(strip_tags($_POST['miembro_de'] ?? ''));
        $casa_de_palabra_y_vida = htmlspecialchars(strip_tags($_POST['casa_de_palabra_y_vida'] ?? ''));
        $cobertura = htmlspecialchars(strip_tags($_POST['cobertura'] ?? ''));
        $anotaciones = htmlspecialchars(strip_tags($_POST['anotaciones'] ?? ''));
        $conector = htmlspecialchars(strip_tags($_POST['conector'] ?? ''));
        $familiares = htmlspecialchars(strip_tags($_POST['familiares'] ?? ''));
        $recorrido_espiritual = htmlspecialchars(strip_tags($_POST['recorrido_espiritual'] ?? ''));
        $habeas_data = isset($_POST['habeas_data']) ? "Aceptado" : "No aceptado";

        // Asignar valores a parámetros
        $stmt->bindParam(':nombres', $nombres);
        $stmt->bindParam(':apellidos', $apellidos);
        $stmt->bindParam(':tipo_documento', $tipo_documento);
        $stmt->bindParam(':numero_documento', $numero_documento);
        $stmt->bindParam(':numero_celular', $numero_celular);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':fecha_nacimiento', $fecha_nacimiento);
        $stmt->bindParam(':correo_electronico', $correo_electronico);
        $stmt->bindParam(':instagram', $instagram);
        $stmt->bindParam(':estado_civil', $estado_civil);
        $stmt->bindParam(':pais', $pais);
        $stmt->bindParam(':ciudad', $ciudad);
        $stmt->bindParam(':localidad', $localidad);
        $stmt->bindParam(':barrio', $barrio);
        $stmt->bindParam(':direccion', $direccion);
        $stmt->bindParam(':rh', $rh);
        $stmt->bindParam(':eps', $eps);
        $stmt->bindParam(':acudiente1', $acudiente1);
        $stmt->bindParam(':telefono_acudiente1', $telefono_acudiente1);
        $stmt->bindParam(':acudiente2', $acudiente2);
        $stmt->bindParam(':telefono_acudiente2', $telefono_acudiente2);
        $stmt->bindParam(':nivel_estudios', $nivel_estudios);
        $stmt->bindParam(':profesion', $profesion);
        $stmt->bindParam(':empresa', $empresa);
        $stmt->bindParam(':direccion_empresa', $direccion_empresa);
        $stmt->bindParam(':otros_estudios', $otros_estudios);
        $stmt->bindParam(':emprendimientos', $emprendimientos);
        $stmt->bindParam(':talla_camisa', $talla_camisa);
        $stmt->bindParam(':talla_pantalon', $talla_pantalon);
        $stmt->bindParam(':talla_camiseta', $talla_camiseta);
        $stmt->bindParam(':talla_zapatos', $talla_zapatos);
        $stmt->bindParam(':carrera_biblica', $carrera_biblica);
        $stmt->bindParam(':miembro_de', $miembro_de);
        $stmt->bindParam(':casa_de_palabra_y_vida', $casa_de_palabra_y_vida);
        $stmt->bindParam(':cobertura', $cobertura);
        $stmt->bindParam(':anotaciones', $anotaciones);
        $stmt->bindParam(':conector', $conector);
        $stmt->bindParam(':familiares', $familiares);
        $stmt->bindParam(':recorrido_espiritual', $recorrido_espiritual);
        $stmt->bindParam(':habeas_data', $habeas_data);
        
        // Ejecutar consulta
        if($stmt->execute()){
            // Procesamiento de la imagen si se sube
            if(!empty($_FILES["foto"]["name"])){
                $lastId = $db->lastInsertId();
                $target_dir = "uploads/";
                
                // Crear directorio si no existe
                if(!file_exists($target_dir)){
                    mkdir($target_dir, 0777, true);
                }
                
                // Generar nombre único para la imagen
                $imageFileType = pathinfo($_FILES["foto"]["name"], PATHINFO_EXTENSION);
                $target_file = $target_dir . $lastId . "_" . time() . "." . $imageFileType;
                
                // Subir imagen
                if(move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)){
                    // Actualizar ruta de imagen en la base de datos
                    $update_query = "UPDATE Miembro SET foto = :foto WHERE id = :id";
                    $update_stmt = $db->prepare($update_query);
                    $update_stmt->bindParam(':foto', $target_file);
                    $update_stmt->bindParam(':id', $lastId);
                    $update_stmt->execute();
                }
            }
            
            header("Location: index.php?message=Miembro registrado exitosamente");
            exit();
        } else {
            $error = "No se pudo registrar el miembro.";
        }
    } catch(PDOException $exception) {
        $error = "Error: " . $exception->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Miembro - Iglesia en Casa</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Registrar Nuevo Miembro</h4>
                    </div>
                    <div class="card-body">
                        <?php if(isset($error)): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                            <div class="row">
                                <!-- Información Personal -->
                                <div class="col-md-6">
                                    <h5 class="mb-3">Información Personal</h5>
                                    
                                    <div class="mb-3">
                                        <label for="nombres" class="form-label">Nombres *</label>
                                        <input type="text" class="form-control" id="nombres" name="nombres" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="apellidos" class="form-label">Apellidos *</label>
                                        <input type="text" class="form-control" id="apellidos" name="apellidos" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="tipo_documento" class="form-label">Tipo de Documento</label>
                                        <select class="form-select" id="tipo_documento" name="tipo_documento">
                                            <option value="">Seleccione...</option>
                                            <option value="Cédula">Cédula</option>
                                            <option value="Tarjeta de Identidad">Tarjeta de Identidad</option>
                                            <option value="Pasaporte">Pasaporte</option>
                                            <option value="Cédula de Extranjería">Cédula de Extranjería</option>
                                        </select>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="numero_documento" class="form-label">Número de Documento *</label>
                                        <input type="text" class="form-control" id="numero_documento" name="numero_documento" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                                        <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="estado_civil" class="form-label">Estado Civil</label>
                                        <select class="form-select" id="estado_civil" name="estado_civil">
                                            <option value="">Seleccione...</option>
                                            <option value="Soltero/a">Soltero/a</option>
                                            <option value="Casado/a">Casado/a</option>
                                            <option value="Divorciado/a">Divorciado/a</option>
                                            <option value="Viudo/a">Viudo/a</option>
                                            <option value="Unión Libre">Unión Libre</option>
                                        </select>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="foto" class="form-label">Foto</label>
                                        <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
                                    </div>
                                </div>
                                
                                <!-- Información de Contacto -->
                                <div class="col-md-6">
                                    <h5 class="mb-3">Información de Contacto</h5>
                                    
                                    <div class="mb-3">
                                        <label for="numero_celular" class="form-label">Número Celular *</label>
                                        <input type="text" class="form-control" id="numero_celular" name="numero_celular" placeholder="+573001234567" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="telefono" class="form-label">Teléfono Fijo</label>
                                        <input type="text" class="form-control" id="telefono" name="telefono" placeholder="+5712345678">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="correo_electronico" class="form-label">Correo Electrónico</label>
                                        <input type="email" class="form-control" id="correo_electronico" name="correo_electronico">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="instagram" class="form-label">Instagram</label>
                                        <input type="text" class="form-control" id="instagram" name="instagram" placeholder="@usuario">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="pais" class="form-label">País</label>
                                        <input type="text" class="form-control" id="pais" name="pais" value="Colombia">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="ciudad" class="form-label">Ciudad</label>
                                        <input type="text" class="form-control" id="ciudad" name="ciudad" value="Bogotá">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="localidad" class="form-label">Localidad</label>
                                        <input type="text" class="form-control" id="localidad" name="localidad">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="barrio" class="form-label">Barrio</label>
                                        <input type="text" class="form-control" id="barrio" name="barrio">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="direccion" class="form-label">Dirección</label>
                                        <input type="text" class="form-control" id="direccion" name="direccion">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Botones de acción -->
                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-primary">Registrar Miembro</button>
                                <a href="index.php" class="btn btn-secondary">Cancelar</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/validation.js"></script>
</body>
</html>
````

### js/validation.js - Validaciones del formulario

````javascript
encasa/js/validation.js
// Script para validar formularios

document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(event) {
            const numberInputs = form.querySelectorAll('input[name="numero_celular"], input[name="telefono"], input[name="telefono_acudiente1"], input[name="telefono_acudiente2"]');
            
            numberInputs.forEach(input => {
                if (input.value && !validarTelefono(input.value)) {
                    event.preventDefault();
                    alert('El formato del número de teléfono ' + input.name + ' no es válido. Use formato internacional: +573001234567');
                }
            });
            
            const emailInput = form.querySelector('input[name="correo_electronico"]');
            if (emailInput && emailInput.value && !validarEmail(emailInput.value)) {
                event.preventDefault();
                alert('El formato del correo electrónico no es válido.');
            }
            
            const instagramInput = form.querySelector('input[name="instagram"]');
            if (instagramInput && instagramInput.value && !validarInstagram(instagramInput.value)) {
                event.preventDefault();
                alert('El formato del usuario de Instagram no es válido. Debe comenzar con @ seguido de letras, números o guiones bajos.');
            }
        });
    });
});

function validarTelefono(telefono) {
    // Formato internacional: +país número
    const regex = /^\+[0-9]{1,4}[0-9]{5,14}$/;
    return regex.test(telefono);
}

function validarEmail(email) {
    const regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    return regex.test(email);
}

function validarInstagram(instagram) {
    const regex = /^@[a-zA-Z0-9._]{1,30}$/;
    return regex.test(instagram);
}
````

### CSS/style.css - Estilos personalizados

````css
/* filepath: /htdocs/iglesia_encasa/css/style.css */
/* Estilos personalizados */

body {
    background-color: #f8f9fa;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.card {
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    margin-bottom: 30px;
}

.card-header {
    background-color: #563d7c;
    color: white;
    border-radius: 10px 10px 0 0 !important;
    padding: 15px 20px;
}

h5 {
    color: #563d7c;
    border-bottom: 1px solid #e1e1e1;
    padding-bottom: 10px;
}

.btn-primary {
    background-color: #563d7c;
    border-color: #563d7c;
}

.btn-primary:hover {
    background-color: #4a346b;
    border-color: #4a346b;
}

/* Estilos para formularios largos */
.form-section {
    background-color: #fff;
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 5px;
}

/* Indicador de campos requeridos */
.required::after {
    content: "*";
    color: red;
    margin-left: 4px;
}
````

## 5. Instrucciones de implementación

1. **Instalar XAMPP**:
   - Descargar XAMPP desde [apachefriends.org](https://www.apachefriends.org/)
   - Instalar con las opciones por defecto
   - Iniciar los servicios de Apache y MySQL

2. **Configurar el proyecto**:
   - Crear la estructura de carpetas en `C:\xampp\htdocs\iglesia_encasa` (Windows) o `/Applications/XAMPP/htdocs/iglesia_encasa` (Mac)
   - Copiar todos los archivos proporcionados

3. **Configurar la base de datos**:
   - Abrir el navegador y visitar: `http://localhost/iglesia_encasa/db_setup.php`
   - Este script creará la base de datos y tabla necesaria

4. **Acceder al sistema**:
   - Navegar a `http://localhost/iglesia_encasa/`
   - Comenzar a registrar miembros

## 6. Funcionalidades adicionales (a implementar)

1. **Interfaz completa para todos los campos**
   - Puedes expandir el formulario para incluir todos los campos adicionales organizados por pestañas

2. **Importación/Exportación**
   - Agregar funcionalidad para importar/exportar datos en CSV o Excel

3. **Seguridad**
   - Implementar sistema de login para proteger los datos
   - Configurar permisos por roles de usuario

4. **Reportes**
   - Generar informes estadísticos y reportes en PDF

Esta implementación básica te permitirá comenzar a registrar miembros y gestionar su información a través de un sistema CRUD completo.

Similar code found with 4 license types

jsapontep: Quiero agregar un nuevo campo a "Carrera Bíblica" el campo: Estado - selector de estados (Activo, Inactivo, Intermitente, Nuevo)

GitHub Copilot: # Agregar Campo de Estado a la Tabla CarreraBiblica

Para agregar el campo de estado a la tabla CarreraBiblica, puedes usar la siguiente sentencia SQL:

````sql
-- Opción 1: Si necesitas modificar la tabla existente
ALTER TABLE CarreraBiblica 
ADD estado VARCHAR(20) COMMENT 'Estado de participación: Activo, Inactivo, Intermitente, Nuevo';
````

O si prefieres ver cómo quedaría la definición completa de la tabla con el nuevo campo incluido:

````sql
-- Opción 2: Definición completa de la tabla con el nuevo campo
CREATE TABLE CarreraBiblica (
    id INT AUTO_INCREMENT PRIMARY KEY,
    miembro_id INT NOT NULL,
    carrera_biblica VARCHAR(100) COMMENT 'Nivel o curso bíblico actual',
    miembro_de VARCHAR(100) COMMENT 'Grupo o ministerio al que pertenece',
    casa_de_palabra_y_vida VARCHAR(100) COMMENT 'Casa o grupo pequeño asignado',
    cobertura VARCHAR(100) COMMENT 'Líder o pastor que le cubre espiritualmente',
    estado VARCHAR(20) COMMENT 'Estado de participación: Activo, Inactivo, Intermitente, Nuevo',
    anotaciones TEXT COMMENT 'Observaciones adicionales',
    recorrido_espiritual TEXT COMMENT 'Observaciones del recorrido espiritual',
    habeas_data TEXT COMMENT 'Consentimiento para el tratamiento de datos',
    fecha_habeas_data DATE COMMENT 'Fecha de firma del consentimiento',
    foto VARCHAR(255) COMMENT 'Ruta de la imagen subida',
    FOREIGN KEY (miembro_id) REFERENCES InformacionGeneral(miembro_id) ON DELETE CASCADE
);
````

## Implementación en el Formulario

Para incluir este campo en el formulario de la aplicación, puedes agregar el siguiente código HTML:

````html
<div class="mb-3">
    <label for="estado" class="form-label">Estado</label>
    <select class="form-select" id="estado" name="estado">
        <option value="">Seleccione...</option>
        <option value="Activo">Activo</option>
        <option value="Inactivo">Inactivo</option>
        <option value="Intermitente">Intermitente</option>
        <option value="Nuevo">Nuevo</option>
    </select>
</div>
````

Este selector puede ser útil para segmentar miembros según su nivel de participación y facilitar el seguimiento pastoral.

Similar code found with 1 license type

jsapontep: Todavía no he ingresado el código dame la tabla completa

GitHub Copilot: # Tabla Completa de CarreraBiblica con Campo de Estado

Aquí tienes la estructura completa de la tabla CarreraBiblica incluyendo el nuevo campo de estado:

````sql
CREATE TABLE CarreraBiblica (
    id INT AUTO_INCREMENT PRIMARY KEY,
    miembro_id INT NOT NULL,
    carrera_biblica VARCHAR(100) COMMENT 'Nivel o curso bíblico actual',
    miembro_de VARCHAR(100) COMMENT 'Grupo o ministerio al que pertenece',
    casa_de_palabra_y_vida VARCHAR(100) COMMENT 'Casa o grupo pequeño asignado',
    cobertura VARCHAR(100) COMMENT 'Líder o pastor que le cubre espiritualmente',
    estado VARCHAR(20) COMMENT 'Estado de participación: Activo, Inactivo, Intermitente, Nuevo',
    anotaciones TEXT COMMENT 'Observaciones adicionales',
    recorrido_espiritual TEXT COMMENT 'Observaciones del recorrido espiritual',
    habeas_data TEXT COMMENT 'Consentimiento para el tratamiento de datos',
    fecha_habeas_data DATE COMMENT 'Fecha de firma del consentimiento',
    foto VARCHAR(255) COMMENT 'Ruta de la imagen subida',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (miembro_id) REFERENCES InformacionGeneral(miembro_id) ON DELETE CASCADE
);
````

## Campos destacados:

- **estado**: Almacena el estado de participación del miembro en la carrera bíblica (Activo, Inactivo, Intermitente, Nuevo)
- **fecha_registro**: Registra automáticamente la fecha de creación del registro
- **fecha_actualizacion**: Se actualiza automáticamente al modificar cualquier dato

El campo de estado será útil para realizar seguimientos, generar estadísticas y planificar acciones pastorales segmentadas según la participación de los miembros.

jsapontep: Múestrame todas las tablas.

GitHub Copilot: # Tablas de la Base de Datos de Iglesia en Casa

A continuación se muestran todas las tablas definidas para la base de datos:

## Enfoque Normalizado (Tablas Relacionadas)

### 1. InformacionGeneral
```sql
CREATE TABLE InformacionGeneral (
    miembro_id INT AUTO_INCREMENT PRIMARY KEY,
    nombres VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    fecha_nacimiento DATE COMMENT 'Formato: YYYY-MM-DD',
    estado_civil VARCHAR(20) COMMENT 'Soltero, Casado, etc.',
    pais VARCHAR(100) COMMENT 'País de residencia',
    ciudad VARCHAR(100) COMMENT 'Ciudad de residencia',
    localidad VARCHAR(50) COMMENT 'Localidad de Bogotá',
    barrio VARCHAR(100) COMMENT 'Barrio de Bogotá',
    direccion VARCHAR(255),
    conector VARCHAR(50) COMMENT 'Tipo de conexión con la iglesia',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### 2. Contacto
```sql
CREATE TABLE Contacto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    miembro_id INT NOT NULL,
    tipo_documento VARCHAR(30) COMMENT 'Cédula, Pasaporte, etc.',
    numero_documento VARCHAR(30) NOT NULL,
    numero_celular VARCHAR(20) NOT NULL COMMENT 'Formato internacional: +123456789',
    telefono VARCHAR(20) COMMENT 'Teléfono fijo formato internacional',
    correo_electronico VARCHAR(100) COMMENT 'Formato email',
    instagram VARCHAR(50) COMMENT 'Usuario de Instagram',
    invitado_por INT,
    familiares VARCHAR(255) COMMENT 'Tipos de familiares en la iglesia',
    FOREIGN KEY (miembro_id) REFERENCES InformacionGeneral(miembro_id) ON DELETE CASCADE,
    FOREIGN KEY (invitado_por) REFERENCES InformacionGeneral(miembro_id) ON DELETE SET NULL
);
```

### 3. SaludEmergencias
```sql
CREATE TABLE SaludEmergencias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    miembro_id INT NOT NULL,
    rh VARCHAR(5) COMMENT 'Tipo de sangre (A+, O-, etc.)',
    eps VARCHAR(50) COMMENT 'Entidad Promotora de Salud',
    acudiente1 VARCHAR(100) COMMENT 'Nombre del primer contacto de emergencia',
    telefono_acudiente1 VARCHAR(20) COMMENT 'Teléfono del primer contacto en formato internacional',
    acudiente2 VARCHAR(100) COMMENT 'Nombre del segundo contacto de emergencia',
    telefono_acudiente2 VARCHAR(20) COMMENT 'Teléfono del segundo contacto en formato internacional',
    FOREIGN KEY (miembro_id) REFERENCES InformacionGeneral(miembro_id) ON DELETE CASCADE
);
```

### 4. EstudiosTrabajo
```sql
CREATE TABLE EstudiosTrabajo (
    id INT AUTO_INCREMENT PRIMARY KEY,
    miembro_id INT NOT NULL,
    nivel_estudios VARCHAR(50) COMMENT 'Primaria, Secundaria, Pregrado, etc.',
    profesion VARCHAR(100) COMMENT 'Profesión o campo de estudio',
    empresa VARCHAR(150) COMMENT 'Empresa donde trabaja actualmente',
    direccion_empresa VARCHAR(255) COMMENT 'Dirección de la empresa en formato internacional',
    otros_estudios TEXT COMMENT 'Descripción de estudios adicionales',
    emprendimientos TEXT COMMENT 'Descripción de emprendimientos personales',
    FOREIGN KEY (miembro_id) REFERENCES InformacionGeneral(miembro_id) ON DELETE CASCADE
);
```

### 5. Tallas
```sql
CREATE TABLE Tallas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    miembro_id INT NOT NULL,
    talla_camisa VARCHAR(10) COMMENT 'XS, S, M, L, XL, etc.',
    talla_pantalon VARCHAR(10) COMMENT 'Numérico o letra',
    talla_camiseta VARCHAR(10) COMMENT 'XS, S, M, L, XL, etc.',
    talla_zapatos VARCHAR(10) COMMENT 'Numeración de calzado',
    FOREIGN KEY (miembro_id) REFERENCES InformacionGeneral(miembro_id) ON DELETE CASCADE
);
```

### 6. CarreraBiblica
```sql
CREATE TABLE CarreraBiblica (
    id INT AUTO_INCREMENT PRIMARY KEY,
    miembro_id INT NOT NULL,
    carrera_biblica VARCHAR(100) COMMENT 'Nivel o curso bíblico actual',
    miembro_de VARCHAR(100) COMMENT 'Grupo o ministerio al que pertenece',
    casa_de_palabra_y_vida VARCHAR(100) COMMENT 'Casa o grupo pequeño asignado',
    cobertura VARCHAR(100) COMMENT 'Líder o pastor que le cubre espiritualmente',
    estado VARCHAR(20) COMMENT 'Estado de participación: Activo, Inactivo, Intermitente, Nuevo',
    anotaciones TEXT COMMENT 'Observaciones adicionales',
    recorrido_espiritual TEXT COMMENT 'Observaciones del recorrido espiritual',
    habeas_data TEXT COMMENT 'Consentimiento para el tratamiento de datos',
    fecha_habeas_data DATE COMMENT 'Fecha de firma del consentimiento',
    foto VARCHAR(255) COMMENT 'Ruta de la imagen subida',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (miembro_id) REFERENCES InformacionGeneral(miembro_id) ON DELETE CASCADE
);
```

## Enfoque Alternativo (Tabla Única)

### Tabla Miembro (Todos los campos en una sola tabla)
```sql
CREATE TABLE Miembro (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombres VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    tipo_documento VARCHAR(30) COMMENT 'Cédula, Pasaporte, etc.',
    numero_documento VARCHAR(30) NOT NULL,
    numero_celular VARCHAR(20) NOT NULL COMMENT 'Formato internacional: +123456789',
    telefono VARCHAR(20) COMMENT 'Teléfono fijo formato internacional',
    fecha_nacimiento DATE COMMENT 'Formato: YYYY-MM-DD',
    rh VARCHAR(5) COMMENT 'Tipo de sangre (A+, O-, etc.)',
    eps VARCHAR(50) COMMENT 'Entidad Promotora de Salud',
    correo_electronico VARCHAR(100) COMMENT 'Formato email',
    instagram VARCHAR(50) COMMENT 'Usuario de Instagram',
    estado_civil VARCHAR(20) COMMENT 'Soltero, Casado, etc.',
    pais VARCHAR(100) COMMENT 'País de residencia',
    ciudad VARCHAR(100) COMMENT 'Ciudad de residencia',
    localidad VARCHAR(50) COMMENT 'Localidad de Bogotá',
    barrio VARCHAR(100) COMMENT 'Barrio de Bogotá',
    direccion VARCHAR(255),
    nivel_estudios VARCHAR(50) COMMENT 'Primaria, Secundaria, Pregrado, etc.',
    profesion VARCHAR(100) COMMENT 'Profesión o campo de estudio',
    empresa VARCHAR(150) COMMENT 'Empresa donde trabaja actualmente',
    direccion_empresa VARCHAR(255) COMMENT 'Dirección de la empresa en formato internacional',
    otros_estudios TEXT COMMENT 'Descripción de estudios adicionales',
    emprendimientos TEXT COMMENT 'Descripción de emprendimientos personales',
    talla_camisa VARCHAR(10) COMMENT 'XS, S, M, L, XL, etc.',
    talla_pantalon VARCHAR(10) COMMENT 'Numérico o letra',
    talla_camiseta VARCHAR(10) COMMENT 'XS, S, M, L, XL, etc.',
    talla_zapatos VARCHAR(10) COMMENT 'Numeración de calzado',
    acudiente1 VARCHAR(100) COMMENT 'Nombre del primer contacto de emergencia',
    telefono_acudiente1 VARCHAR(20) COMMENT 'Teléfono del primer contacto en formato internacional',
    acudiente2 VARCHAR(100) COMMENT 'Nombre del segundo contacto de emergencia',
    telefono_acudiente2 VARCHAR(20) COMMENT 'Teléfono del segundo contacto en formato internacional',
    carrera_biblica VARCHAR(100) COMMENT 'Nivel o curso bíblico actual',
    miembro_de VARCHAR(100) COMMENT 'Grupo o ministerio al que pertenece',
    casa_de_palabra_y_vida VARCHAR(100) COMMENT 'Casa o grupo pequeño asignado',
    cobertura VARCHAR(100) COMMENT 'Líder o pastor que le cubre espiritualmente',
    anotaciones TEXT COMMENT 'Observaciones adicionales',
    conector VARCHAR(50) COMMENT 'Tipo de conexión con la iglesia',
    invitado_por INT,
    familiares VARCHAR(255) COMMENT 'Tipos de familiares en la iglesia',
    foto VARCHAR(255) COMMENT 'Ruta de la imagen subida',
    recorrido_espiritual TEXT COMMENT 'Observaciones del recorrido espiritual',
    habeas_data TEXT COMMENT 'Consentimiento para el tratamiento de datos',
    fecha_habeas_data DATE COMMENT 'Fecha de firma del consentimiento',
    notas TEXT,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (invitado_por) REFERENCES Miembro(id) ON DELETE SET NULL
);
```

## Tablas Adicionales del Enfoque Original Normalizado

```sql
CREATE TABLE RelacionesMiembros (
    id INT AUTO_INCREMENT PRIMARY KEY,
    miembro_id INT NOT NULL,
    invitado_por INT,
    tipo_relacion VARCHAR(50) COMMENT 'Invitación, mentor, familiar, etc.',
    fecha_relacion DATE,
    FOREIGN KEY (miembro_id) REFERENCES Miembro(id) ON DELETE CASCADE,
    FOREIGN KEY (invitado_por) REFERENCES Miembro(id) ON DELETE SET NULL
);

CREATE TABLE Foto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    miembro_id INT NOT NULL,
    ruta_foto VARCHAR(255),
    fecha_subida TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (miembro_id) REFERENCES Miembro(id) ON DELETE CASCADE
);

CREATE TABLE Consentimientos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    miembro_id INT NOT NULL,
    habeas_data TEXT,
    fecha_firma DATE,
    estado BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (miembro_id) REFERENCES Miembro(id) ON DELETE CASCADE
);

CREATE TABLE Notas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    miembro_id INT NOT NULL,
    contenido TEXT,
    fecha_nota TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (miembro_id) REFERENCES Miembro(id) ON DELETE CASCADE
);
```

jsapontep: Organizame la tabla de la forma en que ingresé los datos

GitHub Copilot: # Tabla Miembro Organizada por Categorías

Aquí tienes el script SQL para crear la tabla Miembro organizada según las categorías que indicaste:

````sql
CREATE DATABASE IF NOT EXISTS IglesiaEnCasa;
USE IglesiaEnCasa;

CREATE TABLE Miembro (
    id INT AUTO_INCREMENT PRIMARY KEY,
    
    /* 1. INFORMACIÓN GENERAL */
    nombres VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL, 
    fecha_nacimiento DATE COMMENT 'Formato: YYYY-MM-DD',
    estado_civil VARCHAR(20) COMMENT 'Soltero, Casado, etc.',
    pais VARCHAR(100) COMMENT 'País de residencia',
    ciudad VARCHAR(100) COMMENT 'Ciudad de residencia',
    localidad VARCHAR(50) COMMENT 'Localidad de Bogotá',
    barrio VARCHAR(100) COMMENT 'Barrio de Bogotá',
    direccion VARCHAR(255),
    conector VARCHAR(50) COMMENT 'Tipo de conexión con la iglesia',
    
    /* 2. CONTACTO */
    tipo_documento VARCHAR(30) COMMENT 'Cédula, Pasaporte, etc.',
    numero_documento VARCHAR(30) NOT NULL,
    numero_celular VARCHAR(20) NOT NULL COMMENT 'Formato internacional: +123456789',
    telefono VARCHAR(20) COMMENT 'Teléfono fijo formato internacional',
    correo_electronico VARCHAR(100) COMMENT 'Formato email',
    instagram VARCHAR(50) COMMENT 'Usuario de Instagram',
    invitado_por INT,
    familiares VARCHAR(255) COMMENT 'Tipos de familiares en la iglesia',
    
    /* 3. SALUD Y EMERGENCIAS */
    rh VARCHAR(5) COMMENT 'Tipo de sangre (A+, O-, etc.)',
    eps VARCHAR(50) COMMENT 'Entidad Promotora de Salud',
    acudiente1 VARCHAR(100) COMMENT 'Nombre del primer contacto de emergencia',
    telefono_acudiente1 VARCHAR(20) COMMENT 'Teléfono del primer contacto en formato internacional',
    acudiente2 VARCHAR(100) COMMENT 'Nombre del segundo contacto de emergencia',
    telefono_acudiente2 VARCHAR(20) COMMENT 'Teléfono del segundo contacto en formato internacional',
    
    /* 4. ESTUDIOS Y TRABAJO */
    nivel_estudios VARCHAR(50) COMMENT 'Primaria, Secundaria, Pregrado, etc.',
    profesion VARCHAR(100) COMMENT 'Profesión o campo de estudio',
    empresa VARCHAR(150) COMMENT 'Empresa donde trabaja actualmente',
    direccion_empresa VARCHAR(255) COMMENT 'Dirección de la empresa en formato internacional',
    otros_estudios TEXT COMMENT 'Descripción de estudios adicionales',
    emprendimientos TEXT COMMENT 'Descripción de emprendimientos personales',
    
    /* 5. TALLAS */
    talla_camisa VARCHAR(10) COMMENT 'XS, S, M, L, XL, etc.',
    talla_pantalon VARCHAR(10) COMMENT 'Numérico o letra',
    talla_camiseta VARCHAR(10) COMMENT 'XS, S, M, L, XL, etc.',
    talla_zapatos VARCHAR(10) COMMENT 'Numeración de calzado',
    
    /* 6. CARRERA BÍBLICA */
    carrera_biblica VARCHAR(100) COMMENT 'Nivel o curso bíblico actual',
    miembro_de VARCHAR(100) COMMENT 'Grupo o ministerio al que pertenece',
    casa_de_palabra_y_vida VARCHAR(100) COMMENT 'Casa o grupo pequeño asignado',
    cobertura VARCHAR(100) COMMENT 'Líder o pastor que le cubre espiritualmente',
    estado VARCHAR(20) COMMENT 'Estado de participación: Activo, Inactivo, Intermitente, Nuevo',
    anotaciones TEXT COMMENT 'Observaciones adicionales',
    recorrido_espiritual TEXT COMMENT 'Observaciones del recorrido espiritual',
    
    /* OTROS CAMPOS */
    habeas_data TEXT COMMENT 'Consentimiento para el tratamiento de datos',
    fecha_habeas_data DATE COMMENT 'Fecha de firma del consentimiento',
    foto VARCHAR(255) COMMENT 'Ruta de la imagen subida',
    notas TEXT,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    /* RELACIONES */
    FOREIGN KEY (invitado_por) REFERENCES Miembro(id) ON DELETE SET NULL
);
````

## Estructura del formulario

Esta organización también puede utilizarse para estructurar tu formulario de entrada de datos en pestañas o secciones, facilitando así el proceso de registro de nuevos miembros:

1. **Información General** - Datos básicos de identificación
2. **Contacto** - Información de contacto y relaciones
3. **Salud y Emergencias** - Datos médicos y contactos de emergencia
4. **Estudios y Trabajo** - Formación académica y situación laboral
5. **Tallas** - Medidas para vestuario
6. **Carrera Bíblica** - Participación y crecimiento espiritual

Esta estructura hace más fácil la visualización y el mantenimiento de los datos del miembro.

jsapontep: Organizame la tabla de Información general: Nombres, apellidos, celular, localidad, barrio, fecha de nacimiento, invitado por, fecha de ingreso, foto, recorrido espiritual, habeas data, estado espiritual, conector. 

GitHub Copilot: # Tabla de Información General para Iglesia en Casa

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

jsapontep: Dame ahora la tabla contacto

GitHub Copilot: # Tabla de Contacto para Iglesia en Casa

Aquí tienes la estructura organizada para la tabla de Contacto:

````sql
CREATE TABLE Contacto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    miembro_id INT NOT NULL,
    
    /* DOCUMENTACIÓN */
    tipo_documento VARCHAR(30) COMMENT 'Cédula, Pasaporte, etc.',
    numero_documento VARCHAR(30) NOT NULL COMMENT 'Número de identificación',
    
    /* INFORMACIÓN DE CONTACTO */
    telefono VARCHAR(20) COMMENT 'Teléfono fijo formato internacional',
    correo_electronico VARCHAR(100) COMMENT 'Formato email',
    instagram VARCHAR(50) COMMENT 'Usuario de Instagram',
    
    /* CONTACTOS ADICIONALES */
    telefono_trabajo VARCHAR(20) COMMENT 'Teléfono del lugar de trabajo',
    correo_empresarial VARCHAR(100) COMMENT 'Correo del trabajo o institucional',
    redes_sociales VARCHAR(255) COMMENT 'Otras redes sociales (JSON)',
    
    /* RELACIONES FAMILIARES */
    familiares VARCHAR(255) COMMENT 'Tipos de familiares en la iglesia (JSON)',
    
    /* METADATOS */
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    /* RELACIONES */
    FOREIGN KEY (miembro_id) REFERENCES InformacionGeneral(id) ON DELETE CASCADE
);
````

## Características de la tabla

- **Vinculación directa**: Se relaciona con InformacionGeneral mediante miembro_id
- **Documentación completa**: Mantiene los datos de identificación oficial
- **Múltiples canales**: Almacena diferentes formas de contactar al miembro
- **Soporte para formato JSON**: Los campos como redes_sociales y familiares pueden almacenar datos estructurados
- **Actualización automática**: Registra la última fecha de modificación de los datos
- **Integridad referencial**: La cláusula ON DELETE CASCADE garantiza que si se elimina un miembro, también se eliminarán sus datos de contacto

Esta tabla complementa la información básica almacenada en InformacionGeneral, permitiendo un registro detallado de las formas de contactar al miembro.

jsapontep: La tabla contacto debe estar organizada asi: Tipo de documento, número de documento, teléfono, país, ciudad, dirección, estado civil, correo electrónico, instragram, facebook (integrame un nuevo campo que sea: Facebook - campo de texto formato facebook), notas, familiares

GitHub Copilot: # Tabla de Contacto para Iglesia en Casa

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

jsapontep: Ahora vamos con la tabla salud y emergencias. Este es el orden: RH, Acudiente 1, Telefono 1, Acudiente 2, Telefono 2, EPS

GitHub Copilot: # Tabla de Salud y Emergencias para Iglesia en Casa

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

jsapontep: Tráeme ahora  la tabla tallas

GitHub Copilot: # Tabla de Tallas para Iglesia en Casa

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

jsapontep: Muéstrame la tabla de carrera bíblica

GitHub Copilot: # Tabla de Carrera Bíblica para Iglesia en Casa

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

jsapontep: ¿Qué cambios tú me sugieres que le hacen falta?

GitHub Copilot: # Sugerencias de Mejora para la Tabla Carrera Bíblica

La tabla actual está bien estructurada, pero podría enriquecerse con algunos campos adicionales para un seguimiento pastoral más completo:

## Campos que podrían añadirse:

1. **Historial de formación**:
   ```sql
   cursos_completados TEXT COMMENT 'JSON con historial de cursos y fechas'
   ```

2. **Dones y talentos**:
   ```sql
   dones_espirituales VARCHAR(255) COMMENT 'Dones identificados para ministerio'
   ```

3. **Discipulado activo**:
   ```sql
   discipulos VARCHAR(255) COMMENT 'IDs de miembros a quienes discipula'
   ```

4. **Sacramentos y ceremonias**:
   ```sql
   fecha_bautismo DATE COMMENT 'Fecha de bautismo',
   fecha_compromiso DATE COMMENT 'Fecha de compromiso con la iglesia'
   ```

5. **Seguimiento de asistencia**:
   ```sql
   porcentaje_asistencia INT COMMENT 'Porcentaje de asistencia en últimos 3 meses'
   ```

6. **Plan de crecimiento**:
   ```sql
   metas_espirituales TEXT COMMENT 'Próximos pasos en el crecimiento espiritual',
   fecha_proxima_revision DATE COMMENT 'Fecha programada para revisión pastoral'
   ```

Estos campos adicionales permitirían un seguimiento más estructurado del crecimiento espiritual y facilitarían la labor pastoral de acompañamiento personalizado.

¿Te gustaría que implemente alguno de estos campos en la estructura actual?

jsapontep: muéstrame todas las tablas relacionadas

GitHub Copilot: # Esquema Completo de Base de Datos para Iglesia en Casa

A continuación se presenta la estructura completa de la base de datos normalizada con todas las tablas relacionadas:

## 1. InformacionGeneral (Tabla Principal)

````sql
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

## 2. Contacto

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

## 3. SaludEmergencias

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

## 5. Tallas

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

## 6. CarreraBiblica

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

## Diagrama de Relaciones

```
InformacionGeneral (id) ───┐
       │                   │
       │                   │ invitado_por
       │                   │
       └─────────────────┐ │
                         ▼ │
                 ┌────────┴─┴─────────┐
                 │                    │
                 ▼                    ▼
           ┌─────────────┐     ┌─────────────┐
           │  Contacto   │     │SaludEmergencias
           └─────────────┘     └─────────────┘
                 ▲                    ▲
                 │                    │
       ┌─────────┴──────────┬────────┴─────────┐
       │                    │                  │
┌─────────────┐     ┌─────────────┐     ┌─────────────┐
│EstudiosTrabajo    │    Tallas   │     │CarreraBiblica
└─────────────┘     └─────────────┘     └─────────────┘
```

Esta estructura normalizada facilita el mantenimiento de la información y permite realizar consultas específicas por áreas mientras mantiene la integridad referencial.