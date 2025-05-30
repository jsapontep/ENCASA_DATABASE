# Integración de XAMPP con Proyecto Iglesia en Casa

## Instalación y Configuración de XAMPP

1. **Descargar XAMPP para macOS**:
   - Visita [https://www.apachefriends.org/es/download.html](https://www.apachefriends.org/es/download.html)
   - Descarga la versión más reciente para macOS

2. **Instalación**:
   - Abre el archivo `.dmg` descargado
   - Arrastra XAMPP a la carpeta de Aplicaciones
   - Inicia XAMPP desde tus aplicaciones
   - Inicia los servicios Apache y MySQL desde el panel de control

3. **Configuración básica**:
   ```bash
   # Ubicación de la instalación
   /Applications/XAMPP
   
   # Directorio web principal (htdocs)
   /Applications/XAMPP/xamppfiles/htdocs
   ```

## Estructura de Proyecto en XAMPP

Coloca tu proyecto en la carpeta `htdocs`:

```bash
# Crear directorio del proyecto
mkdir -p /Applications/XAMPP/xamppfiles/htdocs/iglesia_encasa

# Copiar archivos del proyecto (desde tu carpeta actual)
cp -R /Users/javieraponte/Documents/Encasa_Database/* /Applications/XAMPP/xamppfiles/htdocs/iglesia_encasa/
```

## Dependencias Necesarias

### 1. Dependencias Frontend

```html
<!-- Bootstrap 5 (CSS) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Font Awesome (iconos) -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap 5 (JS) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables (para tablas avanzadas) -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

<!-- SweetAlert2 (alertas mejoradas) -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
```

### 2. Extensiones PHP Requeridas

Asegúrate de que estas extensiones estén habilitadas en tu archivo `php.ini`:

- `mysqli` o `pdo_mysql` (para conexiones a MySQL)
- `gd` (para manipulación de imágenes)
- `mbstring` (para manejo de caracteres multibyte)
- `json` (para procesamiento JSON)
- `session` (para manejo de sesiones)

Para verificar las extensiones activas:
```php
<?php phpinfo(); ?>
```

### 3. Herramientas Adicionales Recomendadas

1. **Composer** - Gestor de dependencias para PHP:
   ```bash
   # Instalar Composer
   curl -sS https://getcomposer.org/installer | php
   sudo mv composer.phar /usr/local/bin/composer
   ```

2. **PHPMailer** - Para envío de correos:
   ```bash
   # Instalar con Composer
   composer require phpmailer/phpmailer
   ```

3. **TCPDF** o **FPDF** - Para generar PDF:
   ```bash
   # Instalar con Composer
   composer require tecnickcom/tcpdf
   ```

## Estructura de Archivos para Composer

Crea un archivo `composer.json` en la raíz del proyecto:

```json
{
    "name": "iglesia-encasa/sistema-gestion",
    "description": "Sistema de Gestión para Iglesia en Casa",
    "type": "project",
    "require": {
        "php": ">=7.4",
        "phpmailer/phpmailer": "^6.6",
        "tecnickcom/tcpdf": "^6.4"
    },
    "autoload": {
        "psr-4": {
            "App\\": "app/"
        }
    }
}
```

## Configuración de Base de Datos

1. **Acceder a phpMyAdmin**:
   - Abre `http://localhost/phpmyadmin` en tu navegador
   - Usuario por defecto: `root` (sin contraseña)

2. **Crear la base de datos**:
   ```sql
   CREATE DATABASE IF NOT EXISTS IglesiaEnCasa CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

## Verificación de la Configuración

Crea un archivo `test_config.php` en la raíz del proyecto:

```php
<?php
// Verificar versión de PHP
echo "Versión de PHP: " . phpversion() . "<br>";

// Comprobar extensiones
$required_extensions = ['mysqli', 'gd', 'mbstring', 'json', 'session'];
foreach ($required_extensions as $ext) {
    echo "Extensión $ext: " . (extension_loaded($ext) ? "✓" : "✗") . "<br>";
}

// Comprobar conexión a MySQL
$conn = new mysqli("localhost", "root", "", "IglesiaEnCasa");
echo "Conexión a MySQL: " . ($conn->connect_error ? "Error: " . $conn->connect_error : "✓") . "<br>";
$conn->close();

// Comprobar permisos de escritura
$upload_dir = __DIR__ . '/uploads';
echo "Permisos de escritura en uploads: " . (is_writable($upload_dir) ? "✓" : "✗") . "<br>";
?>
```

Accede a este archivo desde el navegador para verificar que todo esté configurado correctamente.

¿Necesitas alguna aclaración adicional sobre alguna de estas dependencias o pasos de configuración?

Código similar encontrado con 3 tipos de licencias