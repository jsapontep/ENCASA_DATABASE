<?php
// filepath: c:\xampp\htdocs\ENCASA_DATABASE\app\views\miembros\ver.php

// IMPORTANTE: Primero detectar el ID de la URL 
$uri = $_SERVER['REQUEST_URI'];
$debug_id = null;
if (preg_match('#/miembros/(\d+)#', $uri, $matches)) {
    $debug_id = (int)$matches[1];
    error_log("ID detectado en URL: {$debug_id}");
}

// Verificar si los datos del miembro corresponden al ID de la URL
if ($debug_id && (!isset($miembro['id']) || $miembro['id'] != $debug_id)) {
    error_log("ID del miembro en datos ({$miembro['id']}) no coincide con ID en URL ({$debug_id})");
    
    // Forzar la obtención de datos correctos (solución de emergencia)
    require_once __DIR__ . '/../../models/Miembro.php';
    $model = new \App\Models\Miembro();
    $miembro = $model->getFullProfile($debug_id);
    
    error_log("Datos forzados obtenidos: " . json_encode(array_keys($miembro)));
}

// Añadir comentario de depuración
echo "<!-- DEBUG: ID URL: {$debug_id} | ID miembro: {$miembro['id']} -->";
// Añadir esta línea de depuración temporal
echo "<!-- DEBUG: " . htmlspecialchars(json_encode($miembro)) . " -->";
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><?= htmlspecialchars($miembro['nombres'] . ' ' . $miembro['apellidos']) ?></h1>
        <div>
            <a href="<?= APP_URL ?>/miembros/editar/<?= $miembro['id'] ?>" class="btn btn-primary me-2">
                <i class="fas fa-edit"></i> Editar
            </a>
            <a href="<?= APP_URL ?>/miembros" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <!-- Información de perfil en pestañas -->
    <ul class="nav nav-tabs" id="perfilTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="general-tab" data-bs-toggle="tab" href="#general" role="tab">Información General</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="contacto-tab" data-bs-toggle="tab" href="#contacto" role="tab">Contacto</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="estudios-tab" data-bs-toggle="tab" href="#estudios" role="tab">Estudios/Trabajo</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="tallas-tab" data-bs-toggle="tab" href="#tallas" role="tab">Tallas</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="carrera-tab" data-bs-toggle="tab" href="#carrera" role="tab">Carrera Bíblica</a>
        </li>
    </ul>

    <div class="tab-content mt-4" id="perfilTabsContent">
        <!-- Pestaña de Información General -->
        <div class="tab-pane fade show active" id="general" role="tabpanel">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <!-- Foto del miembro -->
                    <div class="text-center">
                        <?php if(!empty($miembro['foto'])): ?>
                            <img src="<?= APP_URL ?>/uploads/miembros/<?= $miembro['foto'] ?>" alt="Foto de <?= htmlspecialchars($miembro['nombres']) ?>" 
                                 class="img-fluid rounded-circle" style="width: 200px; height: 200px; object-fit: cover;">
                        <?php else: ?>
                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" 
                                 style="width: 200px; height: 200px; margin: 0 auto;">
                                <i class="fas fa-user fa-5x text-secondary"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Datos Personales</h5>
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <th scope="row" style="width: 30%">Celular:</th>
                                        <td><?= htmlspecialchars($miembro['celular'] ?? 'No disponible') ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Localidad:</th>
                                        <td><?= htmlspecialchars($miembro['localidad'] ?? 'No disponible') ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Barrio:</th>
                                        <td><?= htmlspecialchars($miembro['barrio'] ?? 'No disponible') ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Fecha de Nacimiento:</th>
                                        <td><?= $miembro['fecha_nacimiento'] ? date('d/m/Y', strtotime($miembro['fecha_nacimiento'])) : 'No disponible' ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Estado Espiritual:</th>
                                        <td><?= htmlspecialchars($miembro['estado_espiritual'] ?? 'No disponible') ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Fecha de Ingreso:</th>
                                        <td><?= $miembro['fecha_ingreso'] ? date('d/m/Y', strtotime($miembro['fecha_ingreso'])) : 'No disponible' ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <?php if(isset($miembro['recorrido_espiritual']) && !empty($miembro['recorrido_espiritual'])): ?>
            <div class="card mt-3">
                <div class="card-body">
                    <h5 class="card-title">Recorrido Espiritual</h5>
                    <p class="card-text"><?= nl2br(htmlspecialchars($miembro['recorrido_espiritual'])) ?></p>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Pestaña de Contacto -->
        <div class="tab-pane fade" id="contacto" role="tabpanel">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Información de Contacto</h5>
                    <?php if(isset($miembro['contacto'])): ?>
                        <table class="table table-striped">
                            <tbody>
                                <tr>
                                    <th scope="row" style="width: 30%">Tipo de Documento:</th>
                                    <td><?= htmlspecialchars($miembro['contacto']['tipo_documento'] ?? 'No disponible') ?></td>
                                </tr>
                                <tr>
                                    <th scope="row">Número de Documento:</th>
                                    <td><?= htmlspecialchars($miembro['contacto']['numero_documento'] ?? 'No disponible') ?></td>
                                </tr>
                                <tr>
                                    <th scope="row">Teléfono:</th>
                                    <td><?= htmlspecialchars($miembro['contacto']['telefono'] ?? 'No disponible') ?></td>
                                </tr>
                                <tr>
                                    <th scope="row">País:</th>
                                    <td><?= htmlspecialchars($miembro['contacto']['pais'] ?? 'No disponible') ?></td>
                                </tr>
                                <tr>
                                    <th scope="row">Ciudad:</th>
                                    <td><?= htmlspecialchars($miembro['contacto']['ciudad'] ?? 'No disponible') ?></td>
                                </tr>
                                <tr>
                                    <th scope="row">Dirección:</th>
                                    <td><?= htmlspecialchars($miembro['contacto']['direccion'] ?? 'No disponible') ?></td>
                                </tr>
                                <tr>
                                    <th scope="row">Estado Civil:</th>
                                    <td><?= htmlspecialchars($miembro['contacto']['estado_civil'] ?? 'No disponible') ?></td>
                                </tr>
                                <tr>
                                    <th scope="row">Correo Electrónico:</th>
                                    <td><?= htmlspecialchars($miembro['contacto']['correo_electronico'] ?? 'No disponible') ?></td>
                                </tr>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="alert alert-info">No hay información de contacto disponible.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Incluir otras pestañas: estudios, tallas, carrera bíblica -->

    </div>
</div>