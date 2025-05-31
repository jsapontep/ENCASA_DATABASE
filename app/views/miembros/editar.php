<?php
// Corregido
$contacto = $miembro['contacto'] ?? [];
$estudios = $miembro['estudiostrabajo'] ?? []; 
$tallas = $miembro['tallas'] ?? [];
$salud = $miembro['saludemergencias'] ?? [];
$carrera = $miembro['carrerabiblica'] ?? [];
?>

<div class="container mt-4">
    <!-- Formulario con id para manipulación con JS -->
    <form id="editForm" action="#" method="POST" enctype="multipart/form-data">
        <!-- Pestañas de navegación -->
        <ul class="nav nav-tabs mb-4" id="editorTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="general-tab" data-bs-toggle="tab" href="#general" role="tab">
                    <i class="fas fa-user me-1"></i> Información General
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="contacto-tab" data-bs-toggle="tab" href="#contacto" role="tab">
                    <i class="fas fa-address-card me-1"></i> Contacto
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="estudios-tab" data-bs-toggle="tab" href="#estudios" role="tab">
                    <i class="fas fa-graduation-cap me-1"></i> Estudios/Trabajo
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tallas-tab" data-bs-toggle="tab" href="#tallas" role="tab">
                    <i class="fas fa-tshirt me-1"></i> Tallas
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="salud-tab" data-bs-toggle="tab" href="#salud" role="tab">
                    <i class="fas fa-first-aid me-1"></i> Salud y Emergencias
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="carrera-tab" data-bs-toggle="tab" href="#carrera" role="tab">
                    <i class="fas fa-bible me-1"></i> Carrera Bíblica
                </a>
            </li>
        </ul>
        
        <!-- Contenido de las pestañas -->
        <div class="tab-content" id="editorTabContent">
            <!-- Pestaña de Información General -->
            <div class="tab-pane fade show active" id="general" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h5>Información Personal</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nombres" class="form-label">Nombres *</label>
                                <input type="text" class="form-control" id="nombres" name="nombres" value="<?= htmlspecialchars($miembro['nombres']) ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="apellidos" class="form-label">Apellidos *</label>
                                <input type="text" class="form-control" id="apellidos" name="apellidos" value="<?= htmlspecialchars($miembro['apellidos']) ?>" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="celular" class="form-label">Celular *</label>
                                <input type="text" class="form-control" id="celular" name="celular" value="<?= htmlspecialchars($miembro['celular']) ?>" required>
                                <div class="form-text">Formato internacional: +573123456789</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                                <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" value="<?= $miembro['fecha_nacimiento'] ?? '' ?>">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="localidad" class="form-label">Localidad</label>
                                <input type="text" class="form-control" id="localidad" name="localidad" value="<?= htmlspecialchars($miembro['localidad'] ?? '') ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="barrio" class="form-label">Barrio</label>
                                <input type="text" class="form-control" id="barrio" name="barrio" value="<?= htmlspecialchars($miembro['barrio'] ?? '') ?>">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="conector" class="form-label">Conector</label>
                                <input type="text" class="form-control" id="conector" name="conector" value="<?= htmlspecialchars($miembro['conector'] ?? '') ?>">
                                <div class="form-text">Tipo de conexión con la iglesia</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="invitado_por" class="form-label">Invitado por</label>
                                <select class="form-select" id="invitado_por" name="invitado_por">
                                    <option value="">Seleccione una opción</option>
                                    <?php 
                                    // Obtener lista de miembros para el select
                                    $db = \Database::getInstance()->getConnection();
                                    $stmt = $db->prepare("SELECT id, nombres, apellidos FROM InformacionGeneral WHERE id != ? ORDER BY nombres ASC");
                                    $stmt->execute([$miembro['id']]);
                                    $miembros = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                    
                                    foreach ($miembros as $m): 
                                        $selected = ($miembro['invitado_por'] == $m['id']) ? 'selected' : '';
                                    ?>
                                    <option value="<?= $m['id'] ?>" <?= $selected ?>>
                                        <?= htmlspecialchars($m['nombres'] . ' ' . $m['apellidos']) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="estado_espiritual" class="form-label">Estado Espiritual</label>
                                <select class="form-select" id="estado_espiritual" name="estado_espiritual">
                                    <option value="">Seleccione una opción</option>
                                    <?php 
                                    $estados = ['Activo', 'Inactivo', 'Intermitente', 'Nuevo', 'En formación'];
                                    foreach ($estados as $estado): 
                                        $selected = ($miembro['estado_espiritual'] == $estado) ? 'selected' : '';
                                    ?>
                                    <option value="<?= $estado ?>" <?= $selected ?>><?= $estado ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="fecha_ingreso" class="form-label">Fecha de Ingreso</label>
                                <input type="text" class="form-control" value="<?= date('d/m/Y H:i', strtotime($miembro['fecha_ingreso'])) ?>" readonly>
                                <div class="form-text">Este campo no se puede editar</div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="foto" class="form-label">Foto de Perfil</label>
                            <input type="file" class="form-control" id="foto" name="foto">
                            <?php if(!empty($miembro['foto'])): ?>
                            <div class="mt-2">
                                <img src="<?= url('uploads/miembros/'.$miembro['foto']) ?>" alt="Foto actual" class="img-thumbnail" style="max-height: 100px;">
                                <div class="form-text">Foto actual. Suba una nueva para cambiarla.</div>
                            </div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="recorrido_espiritual" class="form-label">Recorrido Espiritual</label>
                            <textarea class="form-control" id="recorrido_espiritual" name="recorrido_espiritual" rows="4"><?= htmlspecialchars($miembro['recorrido_espiritual'] ?? '') ?></textarea>
                            <div class="form-text">Observaciones del recorrido espiritual del miembro</div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="habeas_data" name="habeas_data" value="1" <?= !empty($miembro['habeas_data']) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="habeas_data">
                                    Acepta tratamiento de datos personales (Habeas Data)
                                </label>
                            </div>
                            <div class="form-text">Al marcar esta casilla, el miembro autoriza el tratamiento de sus datos personales.</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Pestaña de Contacto -->
            <div class="tab-pane fade" id="contacto" role="tabpanel">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Información de Contacto</h5>
                        <input type="hidden" name="contacto_id" value="<?= $contacto['id'] ?? '' ?>">
                        
                        <!-- Ejemplo para cada sección -->
                        <!-- PESTAÑA CONTACTO -->
                        <div class="mb-3">
                            <label for="tipo_documento" class="form-label">Tipo de Documento</label>
                            <select class="form-select" id="tipo_documento" name="contacto[tipo_documento]">
                                <option value="">Seleccione</option>
                                <option value="CC" <?= ($contacto['tipo_documento'] ?? '') == 'CC' ? 'selected' : '' ?>>Cédula de Ciudadanía</option>
                                <option value="TI" <?= ($contacto['tipo_documento'] ?? '') == 'TI' ? 'selected' : '' ?>>Tarjeta de Identidad</option>
                                <option value="CE" <?= ($contacto['tipo_documento'] ?? '') == 'CE' ? 'selected' : '' ?>>Cédula de Extranjería</option>
                                <option value="PAS" <?= ($contacto['tipo_documento'] ?? '') == 'PAS' ? 'selected' : '' ?>>Pasaporte</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="numero_documento" class="form-label">Número de Documento</label>
                            <input type="text" class="form-control" id="numero_documento" name="contacto[numero_documento]" value="<?= htmlspecialchars($contacto['numero_documento'] ?? '') ?>">
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="text" class="form-control" id="telefono" name="contacto[telefono]" value="<?= htmlspecialchars($contacto['telefono'] ?? '') ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="correo_electronico" class="form-label">Correo Electrónico</label>
                                <input type="email" class="form-control" id="correo_electronico" name="contacto[correo_electronico]" value="<?= htmlspecialchars($contacto['correo_electronico'] ?? '') ?>">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="pais" class="form-label">País</label>
                                <input type="text" class="form-control" id="pais" name="contacto[pais]" value="<?= htmlspecialchars($contacto['pais'] ?? 'Colombia') ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="ciudad" class="form-label">Ciudad</label>
                                <input type="text" class="form-control" id="ciudad" name="contacto[ciudad]" value="<?= htmlspecialchars($contacto['ciudad'] ?? '') ?>">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="direccion" class="form-label">Dirección</label>
                                <input type="text" class="form-control" id="direccion" name="contacto[direccion]" value="<?= htmlspecialchars($contacto['direccion'] ?? '') ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="estado_civil" class="form-label">Estado Civil</label>
                                <select class="form-select" id="estado_civil" name="contacto[estado_civil]">
                                    <option value="">Seleccione</option>
                                    <option value="Soltero/a" <?= ($contacto['estado_civil'] ?? '') == 'Soltero/a' ? 'selected' : '' ?>>Soltero/a</option>
                                    <option value="Casado/a" <?= ($contacto['estado_civil'] ?? '') == 'Casado/a' ? 'selected' : '' ?>>Casado/a</option>
                                    <option value="Viudo/a" <?= ($contacto['estado_civil'] ?? '') == 'Viudo/a' ? 'selected' : '' ?>>Viudo/a</option>
                                    <option value="Divorciado/a" <?= ($contacto['estado_civil'] ?? '') == 'Divorciado/a' ? 'selected' : '' ?>>Divorciado/a</option>
                                    <option value="Unión libre" <?= ($contacto['estado_civil'] ?? '') == 'Unión libre' ? 'selected' : '' ?>>Unión libre</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pestaña de Estudios/Trabajo -->
            <div class="tab-pane fade" id="estudios" role="tabpanel">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Información Académica y Laboral</h5>
                        <input type="hidden" name="estudios_id" value="<?= $estudios['id'] ?? '' ?>">
                        
                        <!-- Ejemplo para cada sección -->
                        <!-- PESTAÑA ESTUDIOS/TRABAJO -->
                        <div class="mb-3">
                            <label for="nivel_estudios" class="form-label">Nivel de Estudios</label>
                            <select class="form-select" id="nivel_estudios" name="estudios[nivel_estudios]">
                                <option value="">Seleccione</option>
                                <option value="Primaria" <?= ($estudios['nivel_estudios'] ?? '') == 'Primaria' ? 'selected' : '' ?>>Primaria</option>
                                <option value="Secundaria" <?= ($estudios['nivel_estudios'] ?? '') == 'Secundaria' ? 'selected' : '' ?>>Secundaria</option>
                                <option value="Técnico" <?= ($estudios['nivel_estudios'] ?? '') == 'Técnico' ? 'selected' : '' ?>>Técnico</option>
                                <option value="Tecnólogo" <?= ($estudios['nivel_estudios'] ?? '') == 'Tecnólogo' ? 'selected' : '' ?>>Tecnólogo</option>
                                <option value="Universitario" <?= ($estudios['nivel_estudios'] ?? '') == 'Universitario' ? 'selected' : '' ?>>Universitario</option>
                                <option value="Especialización" <?= ($estudios['nivel_estudios'] ?? '') == 'Especialización' ? 'selected' : '' ?>>Especialización</option>
                                <option value="Maestría" <?= ($estudios['nivel_estudios'] ?? '') == 'Maestría' ? 'selected' : '' ?>>Maestría</option>
                                <option value="Doctorado" <?= ($estudios['nivel_estudios'] ?? '') == 'Doctorado' ? 'selected' : '' ?>>Doctorado</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="profesion" class="form-label">Profesión</label>
                            <input type="text" class="form-control" id="profesion" name="estudios[profesion]" value="<?= htmlspecialchars($estudios['profesion'] ?? '') ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="otros_estudios" class="form-label">Otros Estudios</label>
                            <textarea class="form-control" id="otros_estudios" name="estudios[otros_estudios]" rows="2"><?= htmlspecialchars($estudios['otros_estudios'] ?? '') ?></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="empresa" class="form-label">Empresa</label>
                                <input type="text" class="form-control" id="empresa" name="estudios[empresa]" value="<?= htmlspecialchars($estudios['empresa'] ?? '') ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="direccion_empresa" class="form-label">Dirección de Empresa</label>
                                <input type="text" class="form-control" id="direccion_empresa" name="estudios[direccion_empresa]" value="<?= htmlspecialchars($estudios['direccion_empresa'] ?? '') ?>">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="emprendimientos" class="form-label">Emprendimientos</label>
                            <textarea class="form-control" id="emprendimientos" name="estudios[emprendimientos]" rows="2"><?= htmlspecialchars($estudios['emprendimientos'] ?? '') ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pestaña de Tallas -->
            <div class="tab-pane fade" id="tallas" role="tabpanel">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Información de Tallas</h5>
                        <input type="hidden" name="tallas_id" value="<?= $tallas['id'] ?? '' ?>">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="talla_camisa" class="form-label">Talla de Camisa</label>
                                <select class="form-select" id="talla_camisa" name="tallas[talla_camisa]">
                                    <option value="">Seleccione</option>
                                    <option value="XS" <?= ($tallas['talla_camisa'] ?? '') == 'XS' ? 'selected' : '' ?>>XS</option>
                                    <option value="S" <?= ($tallas['talla_camisa'] ?? '') == 'S' ? 'selected' : '' ?>>S</option>
                                    <option value="M" <?= ($tallas['talla_camisa'] ?? '') == 'M' ? 'selected' : '' ?>>M</option>
                                    <option value="L" <?= ($tallas['talla_camisa'] ?? '') == 'L' ? 'selected' : '' ?>>L</option>
                                    <option value="XL" <?= ($tallas['talla_camisa'] ?? '') == 'XL' ? 'selected' : '' ?>>XL</option>
                                    <option value="XXL" <?= ($tallas['talla_camisa'] ?? '') == 'XXL' ? 'selected' : '' ?>>XXL</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="talla_camiseta" class="form-label">Talla de Camiseta</label>
                                <select class="form-select" id="talla_camiseta" name="tallas[talla_camiseta]">
                                    <option value="">Seleccione</option>
                                    <option value="XS" <?= ($tallas['talla_camiseta'] ?? '') == 'XS' ? 'selected' : '' ?>>XS</option>
                                    <option value="S" <?= ($tallas['talla_camiseta'] ?? '') == 'S' ? 'selected' : '' ?>>S</option>
                                    <option value="M" <?= ($tallas['talla_camiseta'] ?? '') == 'M' ? 'selected' : '' ?>>M</option>
                                    <option value="L" <?= ($tallas['talla_camiseta'] ?? '') == 'L' ? 'selected' : '' ?>>L</option>
                                    <option value="XL" <?= ($tallas['talla_camiseta'] ?? '') == 'XL' ? 'selected' : '' ?>>XL</option>
                                    <option value="XXL" <?= ($tallas['talla_camiseta'] ?? '') == 'XXL' ? 'selected' : '' ?>>XXL</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="talla_pantalon" class="form-label">Talla de Pantalón</label>
                                <input type="text" class="form-control" id="talla_pantalon" name="tallas[talla_pantalon]" value="<?= htmlspecialchars($tallas['talla_pantalon'] ?? '') ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="talla_zapatos" class="form-label">Talla de Zapatos</label>
                                <input type="text" class="form-control" id="talla_zapatos" name="tallas[talla_zapatos]" value="<?= htmlspecialchars($tallas['talla_zapatos'] ?? '') ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pestaña de Salud y Emergencias -->
            <div class="tab-pane fade" id="salud" role="tabpanel">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Información de Salud y Contactos de Emergencia</h5>
                        <input type="hidden" name="salud_id" value="<?= $salud['id'] ?? '' ?>">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="rh" class="form-label">Tipo de Sangre (RH)</label>
                                <select class="form-select" id="rh" name="salud[rh]">
                                    <option value="">Seleccione</option>
                                    <option value="A+" <?= ($salud['rh'] ?? '') == 'A+' ? 'selected' : '' ?>>A+</option>
                                    <option value="A-" <?= ($salud['rh'] ?? '') == 'A-' ? 'selected' : '' ?>>A-</option>
                                    <option value="B+" <?= ($salud['rh'] ?? '') == 'B+' ? 'selected' : '' ?>>B+</option>
                                    <option value="B-" <?= ($salud['rh'] ?? '') == 'B-' ? 'selected' : '' ?>>B-</option>
                                    <option value="AB+" <?= ($salud['rh'] ?? '') == 'AB+' ? 'selected' : '' ?>>AB+</option>
                                    <option value="AB-" <?= ($salud['rh'] ?? '') == 'AB-' ? 'selected' : '' ?>>AB-</option>
                                    <option value="O+" <?= ($salud['rh'] ?? '') == 'O+' ? 'selected' : '' ?>>O+</option>
                                    <option value="O-" <?= ($salud['rh'] ?? '') == 'O-' ? 'selected' : '' ?>>O-</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="eps" class="form-label">EPS</label>
                                <input type="text" class="form-control" id="eps" name="salud[eps]" value="<?= htmlspecialchars($salud['eps'] ?? '') ?>">
                            </div>
                        </div>
                        
                        <hr class="my-4">
                        <h5 class="mb-3">Contactos de Emergencia</h5>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="acudiente1" class="form-label">Primer Contacto</label>
                                <input type="text" class="form-control" id="acudiente1" name="salud[acudiente1]" value="<?= htmlspecialchars($salud['acudiente1'] ?? '') ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="telefono1" class="form-label">Teléfono Primer Contacto</label>
                                <input type="text" class="form-control" id="telefono1" name="salud[telefono1]" value="<?= htmlspecialchars($salud['telefono1'] ?? '') ?>">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="acudiente2" class="form-label">Segundo Contacto</label>
                                <input type="text" class="form-control" id="acudiente2" name="salud[acudiente2]" value="<?= htmlspecialchars($salud['acudiente2'] ?? '') ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="telefono2" class="form-label">Teléfono Segundo Contacto</label>
                                <input type="text" class="form-control" id="telefono2" name="salud[telefono2]" value="<?= htmlspecialchars($salud['telefono2'] ?? '') ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pestaña de Carrera Bíblica -->
            <div class="tab-pane fade" id="carrera" role="tabpanel">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Información de Carrera Bíblica</h5>
                        <input type="hidden" name="carrera_id" value="<?= $carrera['id'] ?? '' ?>">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="estado" class="form-label">Estado</label>
                                <select class="form-select" id="estado" name="carrera[estado]">
                                    <option value="">Seleccione</option>
                                    <option value="Activo" <?= ($carrera['estado'] ?? '') == 'Activo' ? 'selected' : '' ?>>Activo</option>
                                    <option value="Inactivo" <?= ($carrera['estado'] ?? '') == 'Inactivo' ? 'selected' : '' ?>>Inactivo</option>
                                    <option value="Estudiante" <?= ($carrera['estado'] ?? '') == 'Estudiante' ? 'selected' : '' ?>>Estudiante</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="carrera_biblica" class="form-label">Carrera Bíblica</label>
                                <select class="form-select" id="carrera_biblica" name="carrera[carrera_biblica]">
                                    <option value="">Seleccione</option>
                                    <option value="Catecumenado" <?= ($carrera['carrera_biblica'] ?? '') == 'Catecumenado' ? 'selected' : '' ?>>Catecumenado</option>
                                    <option value="Discipulado" <?= ($carrera['carrera_biblica'] ?? '') == 'Discipulado' ? 'selected' : '' ?>>Discipulado</option>
                                    <option value="Escuela de Ministerios" <?= ($carrera['carrera_biblica'] ?? '') == 'Escuela de Ministerios' ? 'selected' : '' ?>>Escuela de Ministerios</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="miembro_de" class="form-label">Miembro de</label>
                                <input type="text" class="form-control" id="miembro_de" name="carrera[miembro_de]" value="<?= htmlspecialchars($carrera['miembro_de'] ?? 'Iglesia En Casa') ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="casa_de_palabra_y_vida" class="form-label">Casa de Palabra y Vida</label>
                                <input type="text" class="form-control" id="casa_de_palabra_y_vida" name="carrera[casa_de_palabra_y_vida]" value="<?= htmlspecialchars($carrera['casa_de_palabra_y_vida'] ?? '') ?>">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="cobertura" class="form-label">Cobertura</label>
                            <input type="text" class="form-control" id="cobertura" name="carrera[cobertura]" value="<?= htmlspecialchars($carrera['cobertura'] ?? '') ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="anotaciones" class="form-label">Anotaciones</label>
                            <textarea class="form-control" id="anotaciones" name="carrera[anotaciones]" rows="3"><?= htmlspecialchars($carrera['anotaciones'] ?? '') ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Botón de guardar -->
        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
            <button type="button" id="btnGuardar" class="btn btn-primary btn-lg">
                <i class="fas fa-save me-1"></i> Guardar Cambios
            </button>
        </div>
    </form>
</div>

<!-- Incluir los scripts necesarios -->
<script src="<?= APP_URL ?>/public/js/controllers/FormController.js"></script>
<script src="<?= APP_URL ?>/public/js/controllers/MiembrosController.js"></script>
<script src="<?= APP_URL ?>/public/js/app.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Referencias
    const form = document.getElementById('editForm');
    const btnGuardar = document.getElementById('btnGuardar');
    
    // Función para mostrar mensajes
    function showMessage(message, type = 'success') {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        
        form.prepend(alertDiv);
        
        setTimeout(() => {
            alertDiv.classList.remove('show');
            setTimeout(() => alertDiv.remove(), 300);
        }, 5000);
    }
    
    // Manejador para el botón guardar con mejor manejo de errores
    btnGuardar.addEventListener('click', function() {
        // Indicador de carga
        btnGuardar.disabled = true;
        btnGuardar.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Guardando...';
        
        // FormData para envío AJAX
        const formData = new FormData(form);
        
        fetch('/ENCASA_DATABASE/miembros/actualizar/<?= $miembro['id'] ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`Error HTTP: ${response.status}`);
            }
            
            // Clonamos la respuesta para poder leerla como texto si falla el JSON
            const responseClone = response.clone();
            
            return response.json().catch(e => {
                console.error("Error al parsear JSON:", e);
                return responseClone.text().then(text => {
                    throw new Error(`Respuesta no válida: ${text.substring(0, 100)}...`);
                });
            });
        })
        .then(data => {
            btnGuardar.disabled = false;
            btnGuardar.innerHTML = '<i class="fas fa-save me-1"></i> Guardar Cambios';
            
            if (data.success) {
                // Mostrar solo un mensaje
                showMessage('Miembro actualizado correctamente');
                
                // Redirigir después de 1 segundo
                setTimeout(() => {
                    window.location.href = data.redirect || "/ENCASA_DATABASE/miembros/<?= $miembro['id'] ?>";
                }, 1000);
            } else {
                showMessage(data.message || 'Error al guardar los datos', 'danger');
            }
        })
        .catch(error => {
            console.error('Error completo:', error);
            btnGuardar.disabled = false;
            btnGuardar.innerHTML = '<i class="fas fa-save me-1"></i> Guardar Cambios';
            showMessage('Error al procesar la solicitud: ' + error.message, 'danger');
        });
    });
});
</script>