<?php

/**
 * Dashboard de Estado del Proyecto ENCASA_DATABASE
 * Muestra el progreso de la implementación
 */

// Configuración de etapas y fases
$stages = [
    1 => [
        'name' => 'Configuración del Entorno y Base de Datos',
        'progress' => 100,
        'status' => 'completed',
        'start_date' => '2025-05-10',
        'end_date' => '2025-05-12',
        'phases' => [
            [
                'name' => 'Entorno de desarrollo',
                'progress' => 100,
                'status' => 'completed',
                'tasks' => [
                    ['name' => 'Configurar XAMPP y PHP', 'status' => 'completed'],
                    ['name' => 'Crear estructura de directorios', 'status' => 'completed'],
                    ['name' => 'Configurar permisos', 'status' => 'completed'],
                ]
            ],
            [
                'name' => 'Base de datos',
                'progress' => 100,
                'status' => 'completed',
                'tasks' => [
                    ['name' => 'Diseño de esquema de base de datos', 'status' => 'completed'],
                    ['name' => 'Configuración de conexión', 'status' => 'completed'],
                    ['name' => 'Implementar migraciones iniciales', 'status' => 'completed'],
                ]
            ]
        ]
    ],
    2 => [
        'name' => 'Núcleo MVC y Enrutamiento',
        'progress' => 100,
        'status' => 'completed',
        'start_date' => '2025-05-13',
        'end_date' => '2025-05-16',
        'phases' => [
            [
                'name' => 'Estructura MVC',
                'progress' => 100,
                'status' => 'completed',
                'tasks' => [
                    ['name' => 'Implementar clases base modelo', 'status' => 'completed'],
                    ['name' => 'Configurar controladores', 'status' => 'completed'],
                    ['name' => 'Establecer sistema de vistas', 'status' => 'completed'],
                ]
            ],
            [
                'name' => 'Sistema de Rutas',
                'progress' => 100,
                'status' => 'completed',
                'tasks' => [
                    ['name' => 'Crear router principal', 'status' => 'completed'],
                    ['name' => 'Configurar manejo de parámetros', 'status' => 'completed'],
                    ['name' => 'Implementar middlewares', 'status' => 'completed'],
                ]
            ]
        ]
    ],
    3 => [
        'name' => 'Sistema de Autenticación y Autorización',
        'progress' => 100,
        'status' => 'completed',
        'start_date' => '2025-05-17',
        'end_date' => '2025-05-28',
        'phases' => [
            [
                'name' => 'Autenticación Base',
                'progress' => 100,
                'status' => 'completed',
                'tasks' => [
                    ['name' => 'Implementar clase Auth con métodos base', 'status' => 'completed'],
                    ['name' => 'Implementar sistema de sesiones seguras', 'status' => 'completed'],
                    ['name' => 'Crear vistas login/registro', 'status' => 'completed'],
                ]
            ],
            [
                'name' => 'Verificación en Dos Pasos',
                'progress' => 100,
                'status' => 'completed',
                'tasks' => [
                    ['name' => 'Implementar verificación para registro', 'status' => 'completed'],
                    ['name' => 'Desarrollar verificación para login', 'status' => 'completed'],
                    ['name' => 'Integrar sistema de JWT', 'status' => 'completed'],
                ]
            ],
            [
                'name' => 'Sistema de Roles y Permisos',
                'progress' => 100,
                'status' => 'completed',
                'tasks' => [
                    ['name' => 'Crear sistema de roles y permisos', 'status' => 'completed'],
                    ['name' => 'Implementar middlewares de autorización', 'status' => 'completed'],
                    ['name' => 'Solucionar problemas de seguridad en formularios', 'status' => 'completed'],
                ]
            ]
        ]
    ],
    4 => [
        'name' => 'Modelos Base y CRUD de Miembros',
        'progress' => 75, // Aumentado de 60 a 75
        'status' => 'in-progress',
        'start_date' => '2025-05-22',
        'end_date' => '2025-05-31',
        'phases' => [
            [
                'name' => 'Modelos y Relaciones',
                'progress' => 100,
                'status' => 'completed',
                'tasks' => [
                    ['name' => 'Crear Modelo abstracto base', 'status' => 'completed'],
                    ['name' => 'Implementar modelo Miembro con relaciones', 'status' => 'completed'],
                    ['name' => 'Implementar modelos relacionados', 'status' => 'completed'],
                ]
            ],
            [
                'name' => 'Controladores CRUD',
                'progress' => 80, // Aumentado de 65 a 80
                'status' => 'in-progress',
                'tasks' => [
                    ['name' => 'Implementar visualización de perfil de miembro', 'status' => 'completed'],
                    ['name' => 'Implementar creación/edición de miembros', 'status' => 'in-progress'],
                    ['name' => 'Implementar listado y filtrado de miembros', 'status' => 'in-progress'],
                    ['name' => 'Sistema dinámico de datos educativos por país', 'status' => 'completed'], // Nueva tarea completada
                ]
            ],
            [
                'name' => 'Vistas y Formularios',
                'progress' => 60, // Aumentado de 40 a 60
                'status' => 'in-progress',
                'tasks' => [
                    ['name' => 'Implementar vistas para gestión de miembros', 'status' => 'in-progress'],
                    ['name' => 'Crear formularios de edición', 'status' => 'in-progress'],
                    ['name' => 'Formulario dinámico de datos educativos', 'status' => 'completed'], // Nueva tarea completada
                    ['name' => 'Implementar procesamiento de imágenes/fotos', 'status' => 'pending'],
                ]
            ]
        ]
    ],
    5 => [
        'name' => 'Ministerios, Roles y Tareas',
        'progress' => 0,
        'status' => 'pending',
        'start_date' => '2025-06-01',
        'end_date' => '2025-06-07',
        'phases' => [
            [
                'name' => 'Modelos y Relaciones',
                'progress' => 0,
                'status' => 'pending',
                'tasks' => [
                    ['name' => 'Implementar modelo Ministerio', 'status' => 'pending'],
                    ['name' => 'Implementar modelo Rol', 'status' => 'pending'],
                    ['name' => 'Implementar modelo Tarea', 'status' => 'pending'],
                ]
            ],
            [
                'name' => 'Controladores y Lógica',
                'progress' => 0,
                'status' => 'pending',
                'tasks' => [
                    ['name' => 'Desarrollar controlador de Ministerios', 'status' => 'pending'],
                    ['name' => 'Desarrollar controlador de Roles', 'status' => 'pending'],
                    ['name' => 'Implementar asignación de roles', 'status' => 'pending'],
                ]
            ]
        ]
    ],
    6 => [
        'name' => 'Vistas y UI/UX',
        'progress' => 35,
        'status' => 'in-progress',
        'start_date' => '2025-05-24',
        'end_date' => '2025-06-01',
        'phases' => [
            [
                'name' => 'Plantillas Base',
                'progress' => 100,
                'status' => 'completed',
                'tasks' => [
                    ['name' => 'Crear layouts principal y de autenticación', 'status' => 'completed'],
                    ['name' => 'Mejorar interfaz de formularios de autenticación', 'status' => 'completed'],
                ]
            ],
            [
                'name' => 'Mejoras de Interfaz',
                'progress' => 15,
                'status' => 'in-progress',
                'tasks' => [
                    ['name' => 'Implementación de Tailwind CSS', 'status' => 'in-progress'],
                    ['name' => 'Implementar vistas de miembros', 'status' => 'in-progress'],
                    ['name' => 'Crear vistas de ministerios y tareas', 'status' => 'pending'],
                ]
            ]
        ]
    ],
    7 => [
        'name' => 'Integración, Pruebas y Optimización',
        'progress' => 40,
        'status' => 'in-progress',
        'start_date' => '2025-05-24',
        'end_date' => '2025-06-03',
        'phases' => [
            [
                'name' => 'URLs y Entornos',
                'progress' => 100,
                'status' => 'completed',
                'tasks' => [
                    ['name' => 'Solucionar problemas de URLs con ngrok y túneles', 'status' => 'completed'],
                    ['name' => 'Implementar detección de entorno', 'status' => 'completed'],
                    ['name' => 'Configurar generación segura de URLs', 'status' => 'completed'],
                ]
            ],
            [
                'name' => 'Monitoreo y Optimización',
                'progress' => 30,
                'status' => 'in-progress',
                'tasks' => [
                    ['name' => 'Sistema de logs para monitoreo', 'status' => 'in-progress'],
                    ['name' => 'Optimizar consultas SQL', 'status' => 'in-progress'],
                    ['name' => 'Implementar caché donde sea necesario', 'status' => 'pending'],
                ]
            ],
            [
                'name' => 'Seguridad',
                'progress' => 40,
                'status' => 'in-progress',
                'tasks' => [
                    ['name' => 'Revisar y mejorar seguridad', 'status' => 'in-progress'],
                    ['name' => 'Implementar validaciones del lado del cliente', 'status' => 'pending'],
                ]
            ]
        ]
    ],
    8 => [
        'name' => 'Despliegue y Documentación',
        'progress' => 5,
        'status' => 'pending',
        'start_date' => '2025-05-30',
        'end_date' => '2025-06-05',
        'phases' => [
            [
                'name' => 'Documentación',
                'progress' => 10,
                'status' => 'in-progress',
                'tasks' => [
                    ['name' => 'Crear manual de usuario', 'status' => 'pending'],
                    ['name' => 'Documentar API y endpoints', 'status' => 'pending'],
                    ['name' => 'Crear documentación técnica', 'status' => 'in-progress'],
                ]
            ],
            [
                'name' => 'Despliegue',
                'progress' => 0,
                'status' => 'pending',
                'tasks' => [
                    ['name' => 'Configurar servidor de producción', 'status' => 'pending'],
                    ['name' => 'Automatizar proceso de despliegue', 'status' => 'pending'],
                    ['name' => 'Configurar backups y alta disponibilidad', 'status' => 'pending'],
                ]
            ]
        ]
    ],
];

// Calcular progreso general
$totalStages = count($stages);
$completedProgress = 0;

foreach ($stages as $key => $stage) {
    $completedProgress += $stage['progress'];
}

$overallProgress = $totalStages > 0 ? round($completedProgress / $totalStages) : 0;

// Calcular siguiente tarea pendiente
$nextTask = null;
foreach ($stages as $stageKey => $stage) {
    if ($stage['status'] !== 'completed') {
        foreach ($stage['phases'] as $phaseKey => $phase) {
            if ($phase['status'] !== 'completed') {
                foreach ($phase['tasks'] as $taskKey => $task) {
                    if ($task['status'] === 'pending') {
                        $nextTask = [
                            'stage' => $stageKey,
                            'phase' => $phaseKey,
                            'name' => $task['name'],
                            'stage_name' => $stage['name'],
                            'phase_name' => $phase['name']
                        ];
                        break 3;
                    }
                }
            }
        }
    }
}

function getStatusClass($status) {
    switch($status) {
        case 'completed': return 'bg-success text-white';
        case 'in-progress': return 'bg-primary text-white';
        case 'pending': return 'bg-neutral-200 text-neutral-700';
        default: return 'bg-neutral-200 text-neutral-700';
    }
}

function getStatusIcon($status) {
    switch($status) {
        case 'completed': return '✓';
        case 'in-progress': return '→';
        case 'pending': return '○';
        default: return '○';
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estado del Proyecto - Iglesia En Casa</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #FF5C28;
            --primary-light: #FF8A5C;
            --primary-dark: #E04B1F;
            --neutral-900: #121212;
            --neutral-800: #333333;
            --neutral-700: #555555;
            --neutral-500: #888888;
            --neutral-300: #CCCCCC;
            --neutral-200: #E5E5E5;
            --neutral-100: #F5F5F5;
            --neutral-50: #FAFAFA;
            --white: #FFFFFF;
            --success: #34D399;
            --error: #F87171;
            --warning: #FBBF24;
            --info: #60A5FA;
            --shadow: rgba(0, 0, 0, 0.1);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--neutral-50);
            color: var(--neutral-800);
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }
        
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 2rem;
        }
        
        .logo-container {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }
        
        .logo {
            max-width: 150px;
            height: auto;
        }
        
        .logo-tagline {
            font-size: 0.75rem;
            color: var(--neutral-700);
            margin-top: 0.25rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .progress-bar-container {
            height: 8px;
            background-color: var(--neutral-200);
            border-radius: 4px;
            margin: 1.5rem 0;
            overflow: hidden;
        }
        
        .progress-bar {
            height: 100%;
            background-color: var(--primary);
            border-radius: 4px;
            transition: width 0.3s ease;
        }
        
        .stage {
            background: var(--white);
            border-radius: 8px;
            box-shadow: 0 2px 4px var(--shadow);
            margin-bottom: 1.5rem;
            overflow: hidden;
        }
        
        .stage-header {
            padding: 1rem 1.5rem;
            background-color: var(--neutral-100);
            border-bottom: 1px solid var(--neutral-200);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .stage-title {
            font-size: 1.25rem;
            display: flex;
            align-items: center;
        }
        
        .stage-title .status-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            margin-right: 0.75rem;
            font-weight: bold;
        }
        
        .stage-content {
            padding: 1.5rem;
        }
        
        .phase {
            margin-bottom: 1.5rem;
            background: var(--neutral-50);
            border-radius: 6px;
            padding: 1rem;
            border-left: 3px solid var(--neutral-300);
        }
        
        .phase.completed {
            border-left-color: var(--success);
        }
        
        .phase.in-progress {
            border-left-color: var(--primary);
        }
        
        .phase-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0.75rem;
        }
        
        .phase-title {
            font-size: 1.1rem;
            font-weight: 500;
            display: flex;
            align-items: center;
        }
        
        .phase-title .status-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            margin-right: 0.5rem;
            font-size: 0.8rem;
            font-weight: bold;
        }
        
        .tasks {
            list-style: none;
        }
        
        .task {
            display: flex;
            align-items: center;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }
        
        .task-status {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            margin-right: 0.5rem;
            font-size: 0.7rem;
        }
        
        .next-task {
            background: var(--primary-light);
            color: var(--white);
            padding: 1.5rem;
            border-radius: 8px;
            margin: 2rem 0;
        }
        
        .next-task h3 {
            margin-bottom: 0.5rem;
            font-size: 1.25rem;
        }
        
        .next-task p {
            margin-bottom: 0.25rem;
            opacity: 0.9;
        }
        
        .highlight-box {
            background: var(--info);
            color: var(--white);
            padding: 1.5rem;
            border-radius: 8px;
            margin: 1rem 0 2rem;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .header > div:last-child {
                margin-top: 1rem;
            }
            
            .stage-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .stage-header .stage-dates {
                margin-top: 0.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header class="header">
            <div class="logo-container">
             <img src="data:image/png;base64,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" alt="Iglesia En Casa" class="logo">
            <span class="logo-tagline">donde dios es todo</span>
            </div>
            <div>
                            <h1>Estado del Proyecto</h1>
                            <p>Sistema de Gestión de Base de Datos</p>
                        </div>
                    </header>
            
                    <!-- Progreso general -->
                    <div>
                        <div class="d-flex justify-content-between align-items-center">
                            <h2>Progreso General: <?= $overallProgress ?>%</h2>
                        </div>
                        <div class="progress-bar-container">
                            <div class="progress-bar" style="width: <?= $overallProgress ?>%"></div>
                        </div>
                    </div>
            
                    <!-- Próxima tarea -->
                    <?php if ($nextTask): ?>
                    <div class="next-task">
                        <h3>Próxima tarea pendiente</h3>
                        <p><strong>Etapa <?= $nextTask['stage'] ?>:</strong> <?= $nextTask['stage_name'] ?></p>
                        <p><strong>Fase:</strong> <?= $nextTask['phase_name'] ?></p>
                        <p><strong>Tarea:</strong> <?= $nextTask['name'] ?></p>
                    </div>
                    <?php endif; ?>
            
                    <!-- Logros destacados -->
                    <div class="highlight-box">
                        <h3>Logros destacados (02/06/2025)</h3>
                        <ul style="margin-top: 10px; opacity: 0.9; padding-left: 20px;">
                            <li>✅ Implementado sistema dinámico de datos educativos por país</li>
                            <li>✅ Creadas tablas para instituciones educativas y profesiones</li>
                            <li>✅ Formularios interactivos para selección de instituciones y carreras</li>
                            <li>✅ API para gestión de datos educativos implementada</li>
                            <li>✅ Solución implementada para problemas con ngrok y túneles</li>
                        </ul>
                    </div>
            
                    <!-- Etapas -->
                    <?php foreach ($stages as $stageKey => $stage): ?>
                    <div class="stage">
                        <div class="stage-header">
                            <div class="stage-title">
                                <span class="status-icon <?= getStatusClass($stage['status']) ?>"><?= getStatusIcon($stage['status']) ?></span>
                                <span>Etapa <?= $stageKey ?>: <?= $stage['name'] ?></span>
                            </div>
                            <div class="stage-dates">
                                <?= $stage['start_date'] ?> - <?= $stage['status'] === 'completed' ? $stage['end_date'] : ('Estimado: ' . $stage['end_date']) ?>
                                <span class="ms-2"><?= $stage['progress'] ?>%</span>
                            </div>
                        </div>
                        <div class="stage-content">
                            <!-- Fases -->
                            <?php foreach ($stage['phases'] as $phaseKey => $phase): ?>
                            <div class="phase <?= $phase['status'] ?>">
                                <div class="phase-header">
                                    <div class="phase-title">
                                        <span class="status-icon <?= getStatusClass($phase['status']) ?>"><?= getStatusIcon($phase['status']) ?></span>
                                        <?= $phase['name'] ?>
                                    </div>
                                    <div><?= $phase['progress'] ?>%</div>
                                </div>
                                <!-- Tareas -->
                                <ul class="tasks">
                                    <?php foreach ($phase['tasks'] as $taskKey => $task): ?>
                                    <li class="task">
                                        <span class="task-status <?= getStatusClass($task['status']) ?>"><?= getStatusIcon($task['status']) ?></span>
                                        <?= $task['name'] ?>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
            
                    <!-- Footer -->
                    <footer style="margin-top: 3rem; text-align: center; color: var(--neutral-700); font-size: 0.85rem;">
                        <p>ENCASA_DATABASE v1.0 &copy; <?= date('Y') ?> Iglesia En Casa</p>
                        <p>Última actualización: <?= date('d/m/Y') ?></p>
                    </footer>
                </div>
            </body>
            </html>
