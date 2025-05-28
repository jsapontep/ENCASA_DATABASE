<?php

// Script para verificar el progreso del proyecto

echo "<h1>Verificación de Progreso del Sistema</h1>";

// Verificar componentes críticos
$components = [
    // Etapa 1: Configuración del Entorno y Base de Datos
    'Conexión BD' => function() {
        return class_exists('PDO');
    },
    'Estructura BD' => function() {
        // Verificar si podemos conectar y si existe al menos una tabla clave
        try {
            if (!class_exists('PDO')) return false;
            $db = new PDO('mysql:host=localhost;dbname=IglesiaEnCasa', 'root', '');
            $stmt = $db->query("SHOW TABLES LIKE 'InformacionGeneral'");
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            return false;
        }
    },
    'Entorno Configurado' => function() {
        return file_exists('app/config/config.php');
    },
    
    // Etapa 2: Núcleo MVC y Enrutamiento
    'Router' => function() {
        return file_exists('app/helpers/Router.php');
    },
    'Modelo Base' => function() {
        return file_exists('app/models/Model.php') || file_exists('app/models/BaseModel.php');
    },
    'Controlador Base' => function() {
        return file_exists('app/controllers/Controller.php') || file_exists('app/core/Controller.php');
    },
    'Sistema de Vistas' => function() {
        return is_dir('app/views') && file_exists('app/views/layouts/default.php');
    },
    
    // Etapa 3: Sistema de Autenticación y Autorización
    'Autenticación' => function() {
        return file_exists('app/controllers/AuthController.php');
    },
    'Modelo Usuario' => function() {
        return file_exists('app/models/Usuario.php');
    },
    'Login Funcional' => function() {
        return file_exists('app/views/auth/login.php');
    },
    'Middleware Auth' => function() {
        return file_exists('app/middleware/Auth.php') || file_exists('app/middleware/AuthMiddleware.php');
    },
    'Sistema de Roles' => function() {
        return file_exists('app/models/Rol.php') || file_exists('app/models/Role.php');
    },
    
    // Etapa 4: Modelos Base y CRUD de Miembros
    'Modelo Miembro' => function() {
        return file_exists('app/models/Miembro.php') || file_exists('app/models/InformacionGeneral.php');
    },
    'Controlador Miembros' => function() {
        return file_exists('app/controllers/MiembroController.php') || file_exists('app/controllers/MiembrosController.php');
    },
    'Vista Listado Miembros' => function() {
        return file_exists('app/views/miembros/index.php');
    },
    'Vista Formulario Miembro' => function() {
        return file_exists('app/views/miembros/create.php') || file_exists('app/views/miembros/form.php');
    },
    
    // Etapa 5: Ministerios, Roles y Tareas
    'Modelo Ministerio' => function() {
        return file_exists('app/models/Ministerio.php');
    },
    'Modelo Tarea' => function() {
        return file_exists('app/models/Tarea.php');
    },
    'Controlador Ministerios' => function() {
        return file_exists('app/controllers/MinisterioController.php') || file_exists('app/controllers/MinisteriosController.php');
    },
    
    // Etapa 6: Vistas y UI/UX
    'Layout Principal' => function() {
        return file_exists('app/views/layouts/default.php');
    },
    'Layout Auth' => function() {
        return file_exists('app/views/layouts/auth.php');
    },
    'Config Tailwind' => function() {
        return file_exists('tailwind.config.js');
    },
    'CSS Compilado' => function() {
        return file_exists('public/css/main.css');
    },
    'Dashboard' => function() {
        return file_exists('app/views/dashboard/index.php');
    },
    
    // Etapa 7: Integración, Pruebas y Optimización
    'Sistema de Logs' => function() {
        return file_exists('app/helpers/Logger.php');
    },
    'Visualizador de Logs' => function() {
        return file_exists('app/controllers/LogController.php') && file_exists('app/views/admin/logs.php');
    },
    'Test Unitarios' => function() {
        return is_dir('tests') && file_exists('phpunit.xml');
    },
    'Sistema Cache' => function() {
        return file_exists('app/helpers/Cache.php') || file_exists('app/services/CacheService.php');
    },
    
    // Etapa 8: Despliegue y Documentación
    'Manual Usuario' => function() {
        return file_exists('Documentacion/manual_usuario.md') || file_exists('Documentacion/manual_usuario.pdf');
    },
    'Documentación API' => function() {
        return file_exists('Documentacion/api.md') || file_exists('api/index.html');
    },
    'Script Despliegue' => function() {
        return file_exists('deploy.sh') || file_exists('deploy.php');
    },
    
    // Añadir más verificaciones para cada etapa según sea necesario
];

// Mostrar resultados
echo "<table border='1' style='border-collapse:collapse'>";
echo "<tr><th>Componente</th><th>Estado</th></tr>";

$completedCount = 0;
foreach ($components as $name => $checkFn) {
    $status = $checkFn() ? "✅ Implementado" : "❌ Pendiente";
    $style = $checkFn() ? "background-color: #d4edda;" : "background-color: #f8d7da;";
    
    echo "<tr style='$style'><td>$name</td><td>$status</td></tr>";
    
    if ($checkFn()) $completedCount++;
}

$percent = round(($completedCount / count($components)) * 100);
echo "</table>";
echo "<p>Progreso general: <b>$percent%</b></p>";

// Generar reporte de progreso por etapas
echo "<h2>Progreso por Etapas</h2>";
$etapas = [
    'Etapa 1: Config y BD' => ['Conexión BD', 'Estructura BD', 'Entorno Configurado'],
    'Etapa 2: Núcleo MVC' => ['Router', 'Modelo Base', 'Controlador Base', 'Sistema de Vistas'],
    'Etapa 3: Autenticación' => ['Autenticación', 'Modelo Usuario', 'Login Funcional', 'Middleware Auth', 'Sistema de Roles'],
    'Etapa 4: CRUD Miembros' => ['Modelo Miembro', 'Controlador Miembros', 'Vista Listado Miembros', 'Vista Formulario Miembro'],
    'Etapa 5: Ministerios/Tareas' => ['Modelo Ministerio', 'Modelo Tarea', 'Controlador Ministerios'],
    'Etapa 6: Vistas y UI/UX' => ['Layout Principal', 'Layout Auth', 'Config Tailwind', 'CSS Compilado', 'Dashboard'],
    'Etapa 7: Integración/Pruebas' => ['Sistema de Logs', 'Visualizador de Logs', 'Test Unitarios', 'Sistema Cache'],
    'Etapa 8: Despliegue/Docs' => ['Manual Usuario', 'Documentación API', 'Script Despliegue']
];

echo "<table border='1' style='border-collapse:collapse'>";
echo "<tr><th>Etapa</th><th>Componentes Completados</th><th>Progreso</th></tr>";

foreach ($etapas as $etapa => $componentesEtapa) {
    $completados = 0;
    $total = count($componentesEtapa);
    
    foreach ($componentesEtapa as $componente) {
        if (isset($components[$componente]) && $components[$componente]()) {
            $completados++;
        }
    }
    
    $porcentajeEtapa = $total > 0 ? round(($completados / $total) * 100) : 0;
    $styleEtapa = $porcentajeEtapa == 100 ? "background-color: #d4edda;" : 
                 ($porcentajeEtapa > 0 ? "background-color: #fff3cd;" : "background-color: #f8d7da;");
    
    echo "<tr style='$styleEtapa'><td>$etapa</td><td>$completados / $total</td><td>$porcentajeEtapa%</td></tr>";
}

echo "</table>";

// Mostrar gráfico visual de progreso (opcional)
echo "<h2>Gráfico de Progreso</h2>";
echo "<div style='width:100%; background-color:#eee; height:30px; border-radius:5px; overflow:hidden;'>";
echo "<div style='width:$percent%; background-color:#4CAF50; height:30px; text-align:center; line-height:30px; color:white;'>$percent%</div>";
echo "</div>";

// Mostrar próximos pasos
echo "<h2>Próximos Pasos Recomendados</h2>";
echo "<ul>";

// Analizar componentes faltantes prioritarios
$prioridadAlta = [];

// Revisar etapa 3 - Autenticación
if (!$components['Sistema de Roles']()) {
    $prioridadAlta[] = "Completar implementación del sistema de roles y permisos";
}

// Revisar etapa 4 - CRUD Miembros
if (!$components['Controlador Miembros']()) {
    $prioridadAlta[] = "Implementar controlador de miembros con operaciones CRUD básicas";
}

// Revisar nuevos componentes
if (!$components['Sistema de Logs']()) {
    $prioridadAlta[] = "Implementar sistema de logs para monitoreo de errores";
}

if (!$components['Config Tailwind']()) {
    $prioridadAlta[] = "Configurar Tailwind CSS para la interfaz de usuario";
}

foreach ($prioridadAlta as $paso) {
    echo "<li>$paso</li>";
}

echo "</ul>";
?>