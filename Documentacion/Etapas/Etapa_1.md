# Implementación Etapa 1: Configuración del Entorno y Base de Datos

## 1. Configuración del Servidor y Estructura MVC

### Paso 1: Configurar XAMPP
1. **Verifica que XAMPP está funcionando correctamente**:
   ```bash
   # Inicia los servicios de Apache y MySQL
   sudo /Applications/XAMPP/xamppfiles/xampp start
   ```

2. **Verifica acceso a phpMyAdmin**:
   - Abre [http://localhost/phpmyadmin](http://localhost/phpmyadmin) en tu navegador

### Paso 2: Crear estructura de carpetas MVC
```bash
cd /Applications/XAMPP/xamppfiles/htdocs/Encasa_Database

# Crear estructura principal de carpetas
mkdir -p app/{controllers,models,views,config,helpers,public,tests}
mkdir -p app/public/{css,js,images,uploads}
mkdir -p app/views/{layouts,miembros,ministerios,auth,errors}
```

### Paso 3: Configurar archivos base

1. **Crear index.php (punto de entrada)**:
```php
<?php
// Configurar visualización de errores para desarrollo
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Definir constantes del sistema
define('BASE_PATH', __DIR__);
define('APP_PATH', BASE_PATH . '/app');
define('CONTROLLER_PATH', APP_PATH . '/controllers');
define('MODEL_PATH', APP_PATH . '/models');
define('VIEW_PATH', APP_PATH . '/views');
define('CONFIG_PATH', APP_PATH . '/config');

// Cargar configuración
require_once CONFIG_PATH . '/config.php';
require_once CONFIG_PATH . '/database.php';
require_once CONFIG_PATH . '/autoload.php';
require_once CONFIG_PATH . '/routes.php';

// Iniciar Router
$router = new App\Helpers\Router();
$router->dispatch();
```

2. **Crear archivo .htaccess**:
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /Encasa_Database/

    # Si el archivo/directorio existe, úsalo directamente
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d

    # Redirige todo lo demás a index.php
    RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]
</IfModule>

# Prevenir acceso a los directorios
Options -Indexes

# Proteger archivos sensibles
<FilesMatch "(\.env|config\.php|database\.php)$">
    Order Allow,Deny
    Deny from all
</FilesMatch>
```

3. **Crear archivo de configuración**:
```php
<?php
// Configuración general de la aplicación
define('APP_NAME', 'Iglesia En Casa');
define('APP_URL', 'http://localhost/Encasa_Database');
define('APP_ENV', 'development'); // 'development' o 'production'

// Configuración de sesiones
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.use_strict_mode', 1);
if (APP_ENV === 'production') {
    ini_set('session.cookie_secure', 1);
}

// Configuración de zona horaria
date_default_timezone_set('America/Bogota');
```

4. **Crear configuración de base de datos**:
```php
<?php
// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_NAME', 'IglesiaEnCasa');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_PORT', '3306');
define('DB_CHARSET', 'utf8mb4');

// Clase Singleton para conexión a la base de datos
class Database {
    private static $instance = null;
    private $connection;
    
    private function __construct() {
        try {
            $dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=".DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ];
            $this->connection = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            $this->logError($e->getMessage());
            die("Error de conexión a la base de datos.");
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->connection;
    }
    
    private function logError($message) {
        $logFile = APP_PATH . '/logs/db_errors.log';
        $directory = dirname($logFile);
        
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
        
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[$timestamp] $message" . PHP_EOL;
        file_put_contents($logFile, $logMessage, FILE_APPEND);
    }
}
```

## 2. Implementación de la Base de Datos

### Paso 1: Crear script SQL completo
```sql
-- Crear base de datos si no existe
CREATE DATABASE IF NOT EXISTS IglesiaEnCasa CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE IglesiaEnCasa;

-- Tabla de información general
CREATE TABLE IF NOT EXISTS `InformacionGeneral` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `nombres` VARCHAR(100) NOT NULL,
    `apellidos` VARCHAR(100) NOT NULL,
    `celular` VARCHAR(20) NOT NULL COMMENT 'Formato internacional: +123456789',
    `localidad` VARCHAR(50) COMMENT 'Localidad de Bogotá',
    `barrio` VARCHAR(100) COMMENT 'Barrio de Bogotá',
    `fecha_nacimiento` DATE COMMENT 'Formato: YYYY-MM-DD',
    `fecha_ingreso` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha de registro en la iglesia',
    `invitado_por` INT(11) COMMENT 'ID de la persona que lo invitó',
    `conector` VARCHAR(50) COMMENT 'Tipo de conexión con la iglesia',
    `recorrido_espiritual` TEXT COMMENT 'Observaciones del recorrido espiritual',
    `estado_espiritual` VARCHAR(50) COMMENT 'Activo, Inactivo, Intermitente, Nuevo, etc.',
    `foto` VARCHAR(255) COMMENT 'Ruta de la imagen subida',
    `habeas_data` TEXT COMMENT 'Consentimiento para el tratamiento de datos',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabla de contacto
CREATE TABLE IF NOT EXISTS `Contacto` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `miembro_id` INT(11) NOT NULL,
    `tipo_documento` VARCHAR(20) COMMENT 'Cédula, Pasaporte, etc.',
    `numero_documento` VARCHAR(20) UNIQUE,
    `telefono` VARCHAR(20),
    `pais` VARCHAR(50) DEFAULT 'Colombia',
    `ciudad` VARCHAR(50) DEFAULT 'Bogotá',
    `direccion` VARCHAR(255),
    `estado_civil` VARCHAR(20),
    `correo_electronico` VARCHAR(100),
    `instagram` VARCHAR(100),
    `facebook` VARCHAR(100),
    `notas` TEXT,
    `familiares` TEXT COMMENT 'JSON con información de familiares',
    PRIMARY KEY (`id`),
    FOREIGN KEY (`miembro_id`) REFERENCES `InformacionGeneral`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabla de roles
CREATE TABLE IF NOT EXISTS `Roles` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `nombre` VARCHAR(50) NOT NULL UNIQUE,
    `descripcion` TEXT,
    `nivel_permiso` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '1-5, donde 5 es administrador',
    `fecha_creacion` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabla de ministerios
CREATE TABLE IF NOT EXISTS `Ministerios` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `nombre` VARCHAR(100) NOT NULL COMMENT 'Nombre del ministerio',
    `descripcion` TEXT DEFAULT NULL COMMENT 'Descripción del ministerio',
    `lider_id` INT(11) DEFAULT NULL COMMENT 'ID del miembro líder',
    `fecha_creacion` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `estado` VARCHAR(20) DEFAULT 'Activo' COMMENT 'Estado del ministerio',
    PRIMARY KEY (`id`),
    KEY `lider_id` (`lider_id`),
    CONSTRAINT `ministerios_ibfk_1` FOREIGN KEY (`lider_id`) REFERENCES `InformacionGeneral` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabla de relación miembros-ministerios
CREATE TABLE IF NOT EXISTS `MiembrosMinisterios` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `miembro_id` INT(11) NOT NULL,
    `ministerio_id` INT(11) NOT NULL,
    `rol_id` INT(11) NOT NULL,
    `fecha_inicio` DATE NOT NULL,
    `fecha_fin` DATE DEFAULT NULL COMMENT 'Si está vacío, sigue activo',
    PRIMARY KEY (`id`),
    CONSTRAINT `miembrosministerios_ibfk_1` FOREIGN KEY (`miembro_id`) REFERENCES `InformacionGeneral` (`id`) ON DELETE CASCADE,
    CONSTRAINT `miembrosministerios_ibfk_2` FOREIGN KEY (`ministerio_id`) REFERENCES `Ministerios` (`id`) ON DELETE CASCADE,
    CONSTRAINT `miembrosministerios_ibfk_3` FOREIGN KEY (`rol_id`) REFERENCES `Roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabla de usuarios del sistema
CREATE TABLE IF NOT EXISTS `Usuarios` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `miembro_id` INT(11) NOT NULL,
    `nombre_usuario` VARCHAR(50) NOT NULL UNIQUE,
    `password_hash` VARCHAR(255) NOT NULL,
    `rol_id` INT(11) NOT NULL,
    `token` VARCHAR(255) DEFAULT NULL COMMENT 'Token de sesión/recuperación',
    `ultimo_login` TIMESTAMP NULL,
    `status` ENUM('activo','inactivo','bloqueado') DEFAULT 'activo',
    `intentos_fallidos` TINYINT(1) DEFAULT 0,
    `fecha_creacion` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`miembro_id`) REFERENCES `InformacionGeneral` (`id`) ON DELETE CASCADE,
    CONSTRAINT `usuarios_ibfk_2` FOREIGN KEY (`rol_id`) REFERENCES `Roles` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Adding foreign key for invitado_por in InformacionGeneral
ALTER TABLE `InformacionGeneral`
ADD CONSTRAINT `informacion_general_ibfk_1` FOREIGN KEY (`invitado_por`) REFERENCES `InformacionGeneral`(`id`) ON DELETE SET NULL;
```

### Paso 2: Crear script de verificación de la base de datos
```php
<?php
require_once __DIR__ . '/database.php';

class DBVerifier {
    private $db;
    private $errors = [];
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function verifyDatabase() {
        try {
            // Verificar si la base de datos existe
            $this->db->query("USE IglesiaEnCasa");
            $this->verifyTables();
            $this->verifyConstraints();
            $this->verifyIndexes();
            
            if (empty($this->errors)) {
                return true;
            } else {
                $this->logErrors();
                return false;
            }
        } catch (PDOException $e) {
            $this->errors[] = "Error de base de datos: " . $e->getMessage();
            $this->logErrors();
            return false;
        }
    }
    
    private function verifyTables() {
        $tables = [
            'InformacionGeneral', 'Contacto', 'Roles', 'Ministerios', 
            'MiembrosMinisterios', 'Usuarios'
        ];
        
        foreach ($tables as $table) {
            try {
                $result = $this->db->query("SELECT 1 FROM `$table` LIMIT 1");
            } catch (PDOException $e) {
                $this->errors[] = "La tabla '$table' no existe o no es accesible.";
            }
        }
    }
    
    private function verifyConstraints() {
        $constraints = [
            ['InformacionGeneral', 'informacion_general_ibfk_1'],
            ['Contacto', 'contacto_ibfk_1'],
            ['Ministerios', 'ministerios_ibfk_1'],
            ['MiembrosMinisterios', 'miembrosministerios_ibfk_1'],
            ['MiembrosMinisterios', 'miembrosministerios_ibfk_2'],
            ['MiembrosMinisterios', 'miembrosministerios_ibfk_3'],
            ['Usuarios', 'usuarios_ibfk_1'],
            ['Usuarios', 'usuarios_ibfk_2']
        ];
        
        foreach ($constraints as $constraint) {
            try {
                $query = "
                    SELECT * FROM information_schema.TABLE_CONSTRAINTS 
                    WHERE CONSTRAINT_SCHEMA = 'IglesiaEnCasa' 
                    AND TABLE_NAME = ? 
                    AND CONSTRAINT_NAME = ?
                ";
                $stmt = $this->db->prepare($query);
                $stmt->execute([$constraint[0], $constraint[1]]);
                
                if ($stmt->rowCount() === 0) {
                    $this->errors[] = "La restricción '{$constraint[1]}' no existe en la tabla '{$constraint[0]}'.";
                }
            } catch (PDOException $e) {
                $this->errors[] = "Error verificando restricciones: " . $e->getMessage();
            }
        }
    }
    
    private function verifyIndexes() {
        $indexes = [
            ['InformacionGeneral', 'PRIMARY'],
            ['Contacto', 'PRIMARY'],
            ['Roles', 'PRIMARY'],
            ['Ministerios', 'PRIMARY'],
            ['Ministerios', 'lider_id'],
            ['MiembrosMinisterios', 'PRIMARY']
        ];
        
        foreach ($indexes as $index) {
            try {
                $query = "
                    SELECT * FROM information_schema.STATISTICS 
                    WHERE TABLE_SCHEMA = 'IglesiaEnCasa' 
                    AND TABLE_NAME = ? 
                    AND INDEX_NAME = ?
                ";
                $stmt = $this->db->prepare($query);
                $stmt->execute([$index[0], $index[1]]);
                
                if ($stmt->rowCount() === 0) {
                    $this->errors[] = "El índice '{$index[1]}' no existe en la tabla '{$index[0]}'.";
                }
            } catch (PDOException $e) {
                $this->errors[] = "Error verificando índices: " . $e->getMessage();
            }
        }
    }
    
    private function logErrors() {
        $logFile = APP_PATH . '/logs/db_verification.log';
        $directory = dirname($logFile);
        
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
        
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[$timestamp] Verificación de base de datos:" . PHP_EOL;
        
        foreach ($this->errors as $error) {
            $logMessage .= "- $error" . PHP_EOL;
        }
        
        file_put_contents($logFile, $logMessage, FILE_APPEND);
    }
    
    public function getErrors() {
        return $this->errors;
    }
}
```

### Paso 3: Crear script para datos de prueba
```php
<?php
require_once __DIR__ . '/../app/config/database.php';

class DatabaseSeeder {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function seed() {
        try {
            $this->db->beginTransaction();
            
            $this->seedRoles();
            $this->seedInformacionGeneral();
            $this->seedContacto();
            $this->seedMinisterios();
            $this->seedMiembrosMinisterios();
            $this->seedUsuarios();
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            $this->logError($e->getMessage());
            return false;
        }
    }
    
    private function seedRoles() {
        $roles = [
            ['Admin', 'Administrador del sistema', 5],
            ['Líder', 'Líder de ministerio', 4],
            ['Coordinador', 'Coordinador de área', 3],
            ['Servidor', 'Servidor en ministerio', 2],
            ['Miembro', 'Miembro regular', 1]
        ];
        
        $stmt = $this->db->prepare("INSERT INTO Roles (nombre, descripcion, nivel_permiso) VALUES (?, ?, ?)");
        
        foreach ($roles as $role) {
            $stmt->execute($role);
        }
    }
    
    private function seedInformacionGeneral() {
        $miembros = [
            ['Juan', 'Pérez', '+573101234567', 'Kennedy', 'Patio Bonito', '1985-06-15', NULL, 'Invitación directa'],
            ['María', 'López', '+573119876543', 'Suba', 'Rincón', '1990-03-22', NULL, 'Familiar'],
            ['Carlos', 'Rodríguez', '+573157894561', 'Chapinero', 'La Soledad', '1982-11-07', NULL, 'Redes sociales'],
            ['Ana', 'Martínez', '+573203216547', 'Usaquén', 'Santa Bárbara', '1988-09-30', NULL, 'Amigo'],
            ['Pedro', 'González', '+573174563210', 'Fontibón', 'Modelia', '1979-04-18', NULL, 'Familiar']
        ];
        
        $stmt = $this->db->prepare("
            INSERT INTO InformacionGeneral 
            (nombres, apellidos, celular, localidad, barrio, fecha_nacimiento, invitado_por, conector) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        foreach ($miembros as $index => $miembro) {
            // Para el primer registro no tendrá invitado_por
            if ($index === 0) {
                $miembro[6] = NULL;
            } else {
                // Los demás fueron invitados por el primero
                $miembro[6] = 1;
            }
            $stmt->execute($miembro);
        }
    }
    
    private function seedContacto() {
        $contactos = [
            [1, 'CC', '1023456789', '6013456789', 'Colombia', 'Bogotá', 'Cra 15 #45-67', 'Casado', 'juan.perez@email.com'],
            [2, 'CC', '1034567890', '6014567890', 'Colombia', 'Bogotá', 'Calle 80 #23-45', 'Soltera', 'maria.lopez@email.com'],
            [3, 'CC', '1045678901', '6015678901', 'Colombia', 'Bogotá', 'Av 68 #34-56', 'Casado', 'carlos.rodriguez@email.com'],
            [4, 'CE', '1056789012', '6016789012', 'Colombia', 'Bogotá', 'Cra 7 #56-78', 'Casada', 'ana.martinez@email.com'],
            [5, 'CC', '1067890123', '6017890123', 'Colombia', 'Bogotá', 'Calle 100 #67-89', 'Soltero', 'pedro.gonzalez@email.com']
        ];
        
        $stmt = $this->db->prepare("
            INSERT INTO Contacto 
            (miembro_id, tipo_documento, numero_documento, telefono, pais, ciudad, direccion, estado_civil, correo_electronico) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        foreach ($contactos as $contacto) {
            $stmt->execute($contacto);
        }
    }
    
    private function seedMinisterios() {
        $ministerios = [
            ['Alabanza', 'Ministerio de música y adoración', 1],
            ['Niños', 'Ministerio de atención a niños', 2],
            ['Jóvenes', 'Ministerio juvenil', 3],
            ['Matrimonios', 'Ministerio para parejas casadas', 4],
            ['Servicio', 'Ministerio de apoyo logístico', 5]
        ];
        
        $stmt = $this->db->prepare("
            INSERT INTO Ministerios 
            (nombre, descripcion, lider_id) 
            VALUES (?, ?, ?)
        ");
        
        foreach ($ministerios as $ministerio) {
            $stmt->execute($ministerio);
        }
    }
    
    private function seedMiembrosMinisterios() {
        $miembrosMinisterios = [
            [1, 1, 1, '2022-01-15', NULL],
            [2, 2, 2, '2022-02-20', NULL],
            [3, 3, 2, '2022-03-10', NULL],
            [4, 4, 2, '2022-04-05', NULL],
            [5, 5, 2, '2022-05-12', NULL],
            [1, 3, 3, '2022-06-18', NULL],
            [2, 4, 3, '2022-07-22', NULL],
            [3, 5, 3, '2022-08-30', NULL]
        ];
        
        $stmt = $this->db->prepare("
            INSERT INTO MiembrosMinisterios 
            (miembro_id, ministerio_id, rol_id, fecha_inicio, fecha_fin) 
            VALUES (?, ?, ?, ?, ?)
        ");
        
        foreach ($miembrosMinisterios as $mm) {
            $stmt->execute($mm);
        }
    }
    
    private function seedUsuarios() {
        $usuarios = [
            [1, 'admin', password_hash('admin123', PASSWORD_DEFAULT), 1],
            [2, 'maria', password_hash('maria123', PASSWORD_DEFAULT), 2],
            [3, 'carlos', password_hash('carlos123', PASSWORD_DEFAULT), 3],
            [4, 'ana', password_hash('ana123', PASSWORD_DEFAULT), 4],
            [5, 'pedro', password_hash('pedro123', PASSWORD_DEFAULT), 5]
        ];
        
        $stmt = $this->db->prepare("
            INSERT INTO Usuarios 
            (miembro_id, nombre_usuario, password_hash, rol_id) 
            VALUES (?, ?, ?, ?)
        ");
        
        foreach ($usuarios as $usuario) {
            $stmt->execute($usuario);
        }
    }
    
    private function logError($message) {
        $logFile = __DIR__ . '/../app/logs/seed_errors.log';
        $directory = dirname($logFile);
        
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
        
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[$timestamp] Error de seeding: $message" . PHP_EOL;
        file_put_contents($logFile, $logMessage, FILE_APPEND);
    }
}

// Ejecutar el seeder
$seeder = new DatabaseSeeder();
if ($seeder->seed()) {
    echo "Base de datos poblada correctamente." . PHP_EOL;
} else {
    echo "Error al poblar la base de datos. Revisa los logs." . PHP_EOL;
}
```

## 3. Script para ejecutar toda la configuración

```bash
#!/bin/bash

# Colores para los mensajes
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[0;33m'
NC='\033[0m' # No Color

echo -e "${YELLOW}Iniciando configuración del sistema Iglesia En Casa...${NC}"

# Verifica si XAMPP está corriendo
if ! pgrep httpd > /dev/null; then
    echo -e "${RED}Apache no está corriendo. Iniciando servicios de XAMPP...${NC}"
    sudo /Applications/XAMPP/xamppfiles/xampp start
    sleep 5
fi

# Crear directorios si no existen
echo -e "${YELLOW}Creando estructura de directorios...${NC}"
mkdir -p app/{controllers,models,views,config,helpers,logs,public,tests}
mkdir -p app/public/{css,js,images,uploads}
mkdir -p app/views/{layouts,miembros,ministerios,auth,errors}
mkdir -p database

echo -e "${GREEN}Directorios creados correctamente.${NC}"

# Ejecutar script SQL para crear la base de datos
echo -e "${YELLOW}Creando base de datos...${NC}"
/Applications/XAMPP/xamppfiles/bin/mysql -u root < database/schema.sql

if [ $? -eq 0 ]; then
    echo -e "${GREEN}Base de datos creada correctamente.${NC}"
else
    echo -e "${RED}Error al crear la base de datos.${NC}"
    exit 1
fi

# Ejecutar verificación de la base de datos
echo -e "${YELLOW}Verificando estructura de la base de datos...${NC}"
php -f app/config/db_verifier.php

# Insertar datos de prueba
echo -e "${YELLOW}Insertando datos de prueba...${NC}"
php -f database/seed.php

echo -e "${GREEN}¡Configuración completada correctamente!${NC}"
echo -e "${YELLOW}Puedes acceder al sistema en: http://localhost/Encasa_Database${NC}"
```

## 4. Pruebas y verificación

Para probar la configuración inicial:

1. **Ejecuta el script de configuración**:
   ```bash
   cd /Applications/XAMPP/xamppfiles/htdocs/Encasa_Database
   chmod +x setup.sh
   ./setup.sh
   ```

2. **Verifica la base de datos** accediendo a phpMyAdmin:
   - Revisa que todas las tablas existan con sus restricciones
   - Verifica que los datos de prueba se hayan insertado correctamente

3. **Prueba manual de relaciones**:
   - Intenta insertar un registro en Contacto sin un miembro_id válido
   - Intenta eliminar un miembro que tiene registros relacionados
   - Verifica que las restricciones CASCADE y SET NULL funcionan correctamente

4. **Respaldo inicial de la base de datos**:
   ```bash
   /Applications/XAMPP/xamppfiles/bin/mysqldump -u root IglesiaEnCasa > database/backup_inicial.sql
   ```

Con esto hemos completado la Etapa 1 del proyecto, estableciendo la estructura básica MVC y la base de datos. ¿Quieres continuar con algún aspecto específico o pasar a la Etapa 2?

Código similar encontrado con 1 tipo de licencia