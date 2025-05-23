# Descripción del Sistema de Gestión de Miembros - Iglesia En Casa

## Visión General

El sistema está diseñado para centralizar la gestión de miembros de la iglesia como unidad fundamental, permitiendo su organización en ministerios, asignación de tareas específicas, y proporcionando vistas personalizadas según el rol del usuario dentro de la estructura de la iglesia.

## Estructura de Datos

### Enfoque Central: El Miembro

El sistema se organiza alrededor del **miembro** como entidad principal, quien puede pertenecer a uno o varios ministerios y desempeñar diferentes roles dentro de la iglesia. La información detallada (contacto, carrera bíblica, información médica, etc.) ya está estructurada en la base de datos existente.

### Nuevas Entidades Necesarias

Para implementar los requisitos adicionales, necesitamos añadir:

```
┌─────────────────────────────────────────────────────────┐
│ Roles                                                   │
├─────────────────┬─────────────────┬───────────────────┐ │
│ id (PK)         │ INT             │ AUTO_INCREMENT    │ │
│ nombre          │ VARCHAR(50)     │ Pastor, Líder, etc│ │
│ descripcion     │ TEXT            │ Detalles del rol  │ │
│ nivel_acceso    │ INT             │ 1-5 (jerarquía)   │ │
└─────────────────┴─────────────────┴───────────────────┘ │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│ Ministerios                                             │
├─────────────────┬─────────────────┬───────────────────┐ │
│ id (PK)         │ INT             │ AUTO_INCREMENT    │ │
│ nombre          │ VARCHAR(100)    │ Nombre ministerio │ │
│ descripcion     │ TEXT            │ Descripción       │ │
│ lider_id (FK)   │ INT             │ ID del líder      │ │
└─────────────────┴─────────────────┴───────────────────┘ │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│ MiembrosMinisterios                                     │
├─────────────────┬─────────────────┬───────────────────┐ │
│ id (PK)         │ INT             │ AUTO_INCREMENT    │ │
│ miembro_id (FK) │ INT             │ ID del miembro    │ │
│ ministerio_id   │ INT             │ ID del ministerio │ │
│ rol_id          │ INT             │ ID del rol        │ │
│ fecha_inicio    │ DATE            │ Fecha de ingreso  │ │
└─────────────────┴─────────────────┴───────────────────┘ │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│ Tareas                                                  │
├─────────────────┬─────────────────┬───────────────────┐ │
│ id (PK)         │ INT             │ AUTO_INCREMENT    │ │
│ ministerio_id   │ INT             │ ID del ministerio │ │
│ nombre          │ VARCHAR(100)    │ Nombre tarea      │ │
│ descripcion     │ TEXT            │ Descripción       │ │
│ fecha_creacion  │ TIMESTAMP       │ Fecha creación    │ │
│ estado          │ VARCHAR(20)     │ Pendiente/Completada│ │
└─────────────────┴─────────────────┴───────────────────┘ │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│ AsignacionTareas                                        │
├─────────────────┬─────────────────┬───────────────────┐ │
│ id (PK)         │ INT             │ AUTO_INCREMENT    │ │
│ tarea_id (FK)   │ INT             │ ID de la tarea    │ │
│ miembro_id (FK) │ INT             │ ID del miembro    │ │
│ fecha_asignacion│ TIMESTAMP       │ Fecha asignación  │ │
│ fecha_completada│ TIMESTAMP       │ Fecha finalización│ │
│ comentarios     │ TEXT            │ Observaciones     │ │
└─────────────────┴─────────────────┴───────────────────┘ │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│ Usuarios                                                │
├─────────────────┬─────────────────┬───────────────────┐ │
│ id (PK)         │ INT             │ AUTO_INCREMENT    │ │
│ miembro_id (FK) │ INT             │ ID del miembro    │ │
│ username        │ VARCHAR(50)     │ Nombre de usuario │ │
│ password        │ VARCHAR(255)    │ Contraseña hash   │ │
│ rol_id          │ INT             │ Rol de acceso     │ │
│ ultimo_acceso   │ TIMESTAMP       │ Último login      │ │
└─────────────────┴─────────────────┴───────────────────┘ │
└─────────────────────────────────────────────────────────┘
```

## Arquitectura del Sistema

### 1. Estructura de carpetas mejorada

```
/ENCASA_DATABASE/
  /app/
    /config/
      db.php                # Conexión a BD
      config.php            # Configuraciones generales
      routes.php            # Definición de rutas
    /core/
      Auth.php              # Sistema de autenticación
      Router.php            # Sistema de enrutamiento
      Controller.php        # Controlador base
      View.php              # Sistema de vistas
      Permissions.php       # Control de permisos
    /controllers/
      MiembrosController.php
      MinisteriosController.php
      TareasController.php
      ReportesController.php
      AuthController.php
    /models/
      Miembro.php
      Contacto.php
      CarreraBiblica.php
      Ministerio.php
      Rol.php
      Tarea.php
      Usuario.php
    /views/
      /layouts/
        main.php            # Layout principal con panel fijo
        auth.php            # Layout para login/registro
      /miembros/
        index.php
        view.php
        form.php
      /ministerios/
        # Vistas de ministerios
      /tareas/
        # Vistas de tareas
      /auth/
        login.php
        reset-password.php
  /public/
    index.php               # Punto de entrada único
    .htaccess               # Configuración para reescritura de URLs
    /assets/
      /css/
      /js/
        app.js              # Lógica para carga dinámica
        auth.js             # Autenticación cliente
      /img/
    /uploads/
      /photos/
```

### 2. Sistema de autenticación y autorización

#### Niveles de acceso:
1. **Pastor y copastores (Nivel 5)**: Acceso completo a todo el sistema.
2. **Líderes de ministerio (Nivel 4)**: Acceso a su ministerio y sus miembros.
3. **Servidores con responsabilidades (Nivel 3)**: Acceso a sus tareas asignadas y datos limitados.
4. **Miembros regulares (Nivel 2)**: Acceso sólo a su información personal.
5. **Visitante (Nivel 1)**: Acceso público (registro, información general).

#### Sistema de login seguro:
- Autenticación con JWT (JSON Web Tokens)
- Sesiones con tiempo limitado
- Contraseñas encriptadas (bcrypt)
- Protección contra CSRF
- Bloqueo tras intentos fallidos

### 3. Carga dinámica de vistas y panel fijo

```javascript
// Ejemplo de código para carga dinámica de contenido
document.addEventListener('DOMContentLoaded', function() {
    const mainContent = document.getElementById('main-content');
    
    // Función para cargar contenido dinámicamente
    function loadContent(url) {
        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.text();
            })
            .then(html => {
                mainContent.innerHTML = html;
                bindEventListeners(); // Revincula eventos en el nuevo contenido
                updateActiveMenu(url);
            })
            .catch(error => {
                console.error('Error cargando contenido:', error);
                mainContent.innerHTML = '<div class="alert alert-danger">Error al cargar el contenido</div>';
            });
    }
    
    // Intercepta clics en enlaces internos para carga dinámica
    document.body.addEventListener('click', function(e) {
        if (e.target.tagName === 'A' && e.target.getAttribute('data-dynamic') === 'true') {
            e.preventDefault();
            const url = e.target.getAttribute('href');
            loadContent(url);
            // Actualiza URL sin recargar página
            window.history.pushState({url: url}, '', url);
        }
    });
    
    // Maneja navegación con botones adelante/atrás
    window.addEventListener('popstate', function(e) {
        if (e.state && e.state.url) {
            loadContent(e.state.url);
        }
    });
});
```

### 4. Ejemplo de Controller con permisos

```php
<?php
// filepath: c:\xampp\htdocs\ENCASA_DATABASE\app\controllers\MinisteriosController.php

class MinisteriosController extends Controller {
    
    public function __construct() {
        // Verificar autenticación para todas las acciones
        Auth::requireLogin();
    }
    
    // Ver todos los ministerios (solo para pastores y copastores)
    public function index() {
        // Verificar nivel de acceso
        if (!Auth::hasPermission('ver_todos_ministerios')) {
            $this->redirect('dashboard', ['error' => 'permisos_insuficientes']);
        }
        
        $ministerios = Ministerio::getAll();
        $this->render('ministerios/index', ['ministerios' => $ministerios]);
    }
    
    // Ver un ministerio específico
    public function view($id) {
        $ministerio = Ministerio::getById($id);
        
        // Verificar si existe
        if (!$ministerio) {
            $this->redirect('ministerios', ['error' => 'ministerio_no_encontrado']);
        }
        
        // Verificar permisos - debe ser pastor/copastor O líder de este ministerio
        $usuario = Auth::getCurrentUser();
        if (!Auth::hasPermission('ver_todos_ministerios') && 
            !Ministerio::esLider($ministerio['id'], $usuario['miembro_id'])) {
            $this->redirect('dashboard', ['error' => 'permisos_insuficientes']);
        }
        
        $miembros = Ministerio::getMiembros($id);
        $tareas = Ministerio::getTareas($id);
        
        $this->render('ministerios/view', [
            'ministerio' => $ministerio,
            'miembros' => $miembros,
            'tareas' => $tareas
        ]);
    }
    
    // Más métodos: create, edit, delete, etc.
}
```

## Funcionalidades del Sistema

1. **Gestión de miembros**
   - Registro completo de información personal
   - Seguimiento de carrera bíblica y participación
   - Agrupación por ministerios y roles

2. **Gestión de ministerios**
   - Creación y configuración de ministerios
   - Asignación de líderes y miembros
   - Estadísticas y reportes por ministerio

3. **Gestión de tareas**
   - Asignación de responsabilidades
   - Seguimiento de cumplimiento
   - Notificaciones de tareas pendientes

4. **Control de acceso**
   - Login seguro para usuarios autorizados
   - Vistas personalizadas según nivel de acceso
   - Protección de información sensible

5. **Reportes y estadísticas**
   - Crecimiento de ministerios
   - Participación de miembros
   - Asistencia a eventos y actividades

Esta arquitectura proporciona un sistema completo, seguro y adaptado a las necesidades de la Iglesia En Casa, permitiendo una gestión eficiente de los miembros, su participación en ministerios y la asignación de tareas específicas según sus funciones.

Código similar encontrado con 4 tipos de licencias