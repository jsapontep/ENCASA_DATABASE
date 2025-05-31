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
<?php 
// Verificar que tenemos datos del miembro
if (!isset($miembro) || !is_array($miembro)) {
    echo '<div class="alert alert-danger">Error: No se encontraron datos del miembro</div>';
    return;
}
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><?= htmlspecialchars($miembro['nombres'] . ' ' . $miembro['apellidos']) ?></h1>
        <div>
            <a href="<?= url('miembros/editar/'.$miembro['id']) ?>" class="btn btn-primary me-2">
                <i class="fas fa-edit"></i> Editar
            </a>
            <a href="<?= url('miembros') ?>" class="btn btn-secondary">
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
            <a class="nav-link" id="salud-tab" data-bs-toggle="tab" href="#salud" role="tab">Salud</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="carrera-tab" data-bs-toggle="tab" href="#carrera" role="tab">Carrera Bíblica</a>
        </li>
    </ul>

    <div class="tab-content mt-4">
        <!-- Pestaña de Información General -->
        <div class="tab-pane fade show active" id="general" role="tabpanel">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <!-- Foto del miembro -->
                    <div class="card">
                        <div class="card-body text-center">
                            <?php if(!empty($miembro['foto'])): ?>
                                <img src="<?= url('uploads/miembros/'.$miembro['foto']) ?>" class="img-fluid rounded" alt="Foto de perfil">
                            <?php else: ?>
                                <div class="p-5 bg-light rounded text-center">
                                    <i class="fas fa-user fa-4x text-secondary"></i>
                                    <p class="mt-2">Sin foto</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5>Información Personal</h5>
                        </div>
                        <div class="card-body">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th width="35%">ID:</th>
                                        <td><?= htmlspecialchars($miembro['id']) ?></td>
                                    </tr>
                                    <tr>
                                        <th>Nombres:</th>
                                        <td><?= htmlspecialchars($miembro['nombres']) ?></td>
                                    </tr>
                                    <tr>
                                        <th>Apellidos:</th>
                                        <td><?= htmlspecialchars($miembro['apellidos']) ?></td>
                                    </tr>
                                    <tr>
                                        <th>Celular:</th>
                                        <td><?= htmlspecialchars($miembro['celular'] ?? 'No registrado') ?></td>
                                    </tr>
                                    <tr>
                                        <th>Localidad:</th>
                                        <td><?= htmlspecialchars($miembro['localidad'] ?? 'No registrada') ?></td>
                                    </tr>
                                    <tr>
                                        <th>Barrio:</th>
                                        <td><?= htmlspecialchars($miembro['barrio'] ?? 'No registrado') ?></td>
                                    </tr>
                                    <tr>
                                        <th>Fecha de Nacimiento:</th>
                                        <td><?= $miembro['fecha_nacimiento'] ? date('d/m/Y', strtotime($miembro['fecha_nacimiento'])) : 'No registrada' ?></td>
                                    </tr>
                                    <tr>
                                        <th>Fecha de Ingreso:</th>
                                        <td><?= date('d/m/Y H:i', strtotime($miembro['fecha_ingreso'])) ?></td>
                                    </tr>
                                    <tr>
                                        <th>Estado Espiritual:</th>
                                        <td><?= htmlspecialchars($miembro['estado_espiritual'] ?? 'No registrado') ?></td>
                                    </tr>
                                    <tr>
                                        <th>Conector:</th>
                                        <td><?= htmlspecialchars($miembro['conector'] ?? 'No registrado') ?></td>
                                    </tr>
                                    <tr>
                                        <th>Invitado por:</th>
                                        <td>
                                            <?php if(!empty($miembro['invitado_por'])): ?>
                                                <?php 
                                                // Recuperar datos del invitador
                                                $db = \Database::getInstance()->getConnection();
                                                $stmt = $db->prepare("SELECT nombres, apellidos FROM InformacionGeneral WHERE id = ?");
                                                $stmt->execute([$miembro['invitado_por']]);
                                                $invitador = $stmt->fetch(PDO::FETCH_ASSOC);
                                                
                                                if($invitador): ?>
                                                <a href="<?= url('miembros/'.$miembro['invitado_por']) ?>">
                                                    <?= htmlspecialchars($invitador['nombres'] . ' ' . $invitador['apellidos']) ?>
                                                </a>
                                                <?php else: ?>
                                                    Miembro #<?= $miembro['invitado_por'] ?> (No encontrado)
                                                <?php endif; ?>
                                            <?php else: ?>
                                                No registrado
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Habeas Data:</th>
                                        <td><?= !empty($miembro['habeas_data']) ? '<i class="fas fa-check-circle text-success"></i> Aceptado' : '<i class="fas fa-times-circle text-danger"></i> No aceptado' ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <?php if(isset($miembro['recorrido_espiritual']) && !empty($miembro['recorrido_espiritual'])): ?>
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5>Recorrido Espiritual</h5>
                        </div>
                        <div class="card-body">
                            <p class="card-text"><?= nl2br(htmlspecialchars($miembro['recorrido_espiritual'])) ?></p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
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
                                    <th width="35%">Tipo Documento:</th>
                                    <td><?= htmlspecialchars($miembro['contacto']['tipo_documento'] ?? 'No registrado') ?></td>
                                </tr>
                                <tr>
                                    <th>Número Documento:</th>
                                    <td><?= htmlspecialchars($miembro['contacto']['numero_documento'] ?? 'No registrado') ?></td>
                                </tr>
                                <tr>
                                    <th>Teléfono:</th>
                                    <td><?= htmlspecialchars($miembro['contacto']['telefono'] ?? 'No registrado') ?></td>
                                </tr>
                                <tr>
                                    <th>Correo Electrónico:</th>
                                    <td><?= htmlspecialchars($miembro['contacto']['correo_electronico'] ?? 'No registrado') ?></td>
                                </tr>
                                <tr>
                                    <th>País:</th>
                                    <td><?= htmlspecialchars($miembro['contacto']['pais'] ?? 'No registrado') ?></td>
                                </tr>
                                <tr>
                                    <th>Ciudad:</th>
                                    <td><?= htmlspecialchars($miembro['contacto']['ciudad'] ?? 'No registrada') ?></td>
                                </tr>
                                <tr>
                                    <th>Dirección:</th>
                                    <td><?= htmlspecialchars($miembro['contacto']['direccion'] ?? 'No registrada') ?></td>
                                </tr>
                                <tr>
                                    <th>Estado Civil:</th>
                                    <td><?= htmlspecialchars($miembro['contacto']['estado_civil'] ?? 'No registrado') ?></td>
                                </tr>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="alert alert-info">No hay información de contacto disponible.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Pestaña de Estudios/Trabajo -->
        <div class="tab-pane fade" id="estudios" role="tabpanel">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Información Académica y Laboral</h5>
                    <?php if(isset($miembro['estudiostrabajo'])): ?>
                        <table class="table table-striped">
                            <tbody>
                                <tr>
                                    <th width="35%">Nivel de Estudios:</th>
                                    <td><?= htmlspecialchars($miembro['estudiostrabajo']['nivel_estudios'] ?? 'No registrado') ?></td>
                                </tr>
                                <tr>
                                    <th>Profesión:</th>
                                    <td><?= htmlspecialchars($miembro['estudiostrabajo']['profesion'] ?? 'No registrada') ?></td>
                                </tr>
                                <tr>
                                    <th>Otros Estudios:</th>
                                    <td><?= htmlspecialchars($miembro['estudiostrabajo']['otros_estudios'] ?? 'No registrados') ?></td>
                                </tr>
                                <tr>
                                    <th>Empresa:</th>
                                    <td><?= htmlspecialchars($miembro['estudiostrabajo']['empresa'] ?? 'No registrada') ?></td>
                                </tr>
                                <tr>
                                    <th>Dirección Empresa:</th>
                                    <td><?= htmlspecialchars($miembro['estudiostrabajo']['direccion_empresa'] ?? 'No registrada') ?></td>
                                </tr>
                                <tr>
                                    <th>Emprendimientos:</th>
                                    <td><?= htmlspecialchars($miembro['estudiostrabajo']['emprendimientos'] ?? 'No registrados') ?></td>
                                </tr>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="alert alert-info">No hay información de estudios y trabajo disponible.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Pestaña de Tallas -->
        <div class="tab-pane fade" id="tallas" role="tabpanel">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Información de Tallas</h5>
                    <?php if(isset($miembro['tallas'])): ?>
                        <table class="table table-striped">
                            <tbody>
                                <tr>
                                    <th width="35%">Talla Camisa:</th>
                                    <td><?= htmlspecialchars($miembro['tallas']['talla_camisa'] ?? 'No registrada') ?></td>
                                </tr>
                                <tr>
                                    <th>Talla Camiseta:</th>
                                    <td><?= htmlspecialchars($miembro['tallas']['talla_camiseta'] ?? 'No registrada') ?></td>
                                </tr>
                                <tr>
                                    <th>Talla Pantalón:</th>
                                    <td><?= htmlspecialchars($miembro['tallas']['talla_pantalon'] ?? 'No registrada') ?></td>
                                </tr>
                                <tr>
                                    <th>Talla Zapatos:</th>
                                    <td><?= htmlspecialchars($miembro['tallas']['talla_zapatos'] ?? 'No registrada') ?></td>
                                </tr>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="alert alert-info">No hay información de tallas disponible.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Pestaña de Salud y Emergencias -->
        <div class="tab-pane fade" id="salud" role="tabpanel">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Información de Salud y Emergencias</h5>
                    <?php if(isset($miembro['saludemergencias'])): ?>
                        <h6 class="mb-3">Información Médica</h6>
                        <table class="table table-striped mb-4">
                            <tbody>
                                <tr>
                                    <th width="35%">Tipo de Sangre (RH):</th>
                                    <td><?= htmlspecialchars($miembro['saludemergencias']['rh'] ?? 'No registrado') ?></td>
                                </tr>
                                <tr>
                                    <th>EPS:</th>
                                    <td><?= htmlspecialchars($miembro['saludemergencias']['eps'] ?? 'No registrada') ?></td>
                                </tr>
                            </tbody>
                        </table>

                        <h6 class="mb-3">Contactos de Emergencia</h6>
                        <table class="table table-striped">
                            <tbody>
                                <tr>
                                    <th width="35%">Contacto Principal:</th>
                                    <td><?= htmlspecialchars($miembro['saludemergencias']['acudiente1'] ?? 'No registrado') ?></td>
                                </tr>
                                <tr>
                                    <th>Teléfono Contacto Principal:</th>
                                    <td><?= htmlspecialchars($miembro['saludemergencias']['telefono1'] ?? 'No registrado') ?></td>
                                </tr>
                                <tr>
                                    <th>Contacto Secundario:</th>
                                    <td><?= htmlspecialchars($miembro['saludemergencias']['acudiente2'] ?? 'No registrado') ?></td>
                                </tr>
                                <tr>
                                    <th>Teléfono Contacto Secundario:</th>
                                    <td><?= htmlspecialchars($miembro['saludemergencias']['telefono2'] ?? 'No registrado') ?></td>
                                </tr>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="alert alert-info">No hay información de salud y emergencias disponible.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Pestaña de Carrera Bíblica -->
        <div class="tab-pane fade" id="carrera" role="tabpanel">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Información de Carrera Bíblica</h5>
                    <?php if(isset($miembro['carrerabiblica'])): ?>
                        <table class="table table-striped">
                            <tbody>
                                <tr>
                                    <th width="35%">Estado:</th>
                                    <td><?= htmlspecialchars($miembro['carrerabiblica']['estado'] ?? 'No registrado') ?></td>
                                </tr>
                                <tr>
                                    <th>Carrera Bíblica:</th>
                                    <td><?= htmlspecialchars($miembro['carrerabiblica']['carrera_biblica'] ?? 'No registrada') ?></td>
                                </tr>
                                <tr>
                                    <th>Miembro de:</th>
                                    <td><?= htmlspecialchars($miembro['carrerabiblica']['miembro_de'] ?? 'No registrado') ?></td>
                                </tr>
                                <tr>
                                    <th>Casa de Palabra y Vida:</th>
                                    <td><?= htmlspecialchars($miembro['carrerabiblica']['casa_de_palabra_y_vida'] ?? 'No registrada') ?></td>
                                </tr>
                                <tr>
                                    <th>Cobertura:</th>
                                    <td><?= htmlspecialchars($miembro['carrerabiblica']['cobertura'] ?? 'No registrada') ?></td>
                                </tr>
                                <?php if(!empty($miembro['carrerabiblica']['anotaciones'])): ?>
                                <tr>
                                    <th>Anotaciones:</th>
                                    <td><?= nl2br(htmlspecialchars($miembro['carrerabiblica']['anotaciones'])) ?></td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="alert alert-info">No hay información de carrera bíblica disponible.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Activar las pestañas de Bootstrap
document.addEventListener('DOMContentLoaded', function() {
    // Obtener todas las pestañas
    var tabTriggerList = [].slice.call(document.querySelectorAll('#perfilTabs a'))
    
    // Inicializar cada una
    tabTriggerList.forEach(function(tabTriggerEl) {
        new bootstrap.Tab(tabTriggerEl)
    });
});
</script>