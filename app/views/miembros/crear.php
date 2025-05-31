<?php
// filepath: c:\xampp\htdocs\ENCASA_DATABASE\app\views\miembros\crear.php

// Verificar si es modo edición
$esEdicion = isset($miembro) && !empty($miembro);
$titulo = $esEdicion ? 'Editar Miembro' : 'Registrar Nuevo Miembro';

// Variables para acceder fácilmente a los datos relacionados
$contacto = $esEdicion ? ($miembro['contacto'] ?? []) : [];
$estudios = $esEdicion ? ($miembro['estudiostrabajo'] ?? []) : [];
$tallas = $esEdicion ? ($miembro['tallas'] ?? []) : [];
$salud = $esEdicion ? ($miembro['saludemergencias'] ?? []) : [];
$carrera = $esEdicion ? ($miembro['carrerabiblica'] ?? []) : [];
?>

<div class="container mt-4">
    <h1><?= $titulo ?></h1>
    
    <form action="<?= url($esEdicion ? 'miembros/actualizar/'.$miembro['id'] : 'miembros/guardar') ?>" method="POST" enctype="multipart/form-data" id="formMiembro">
        <!-- ID oculto para edición -->
        <?php if($esEdicion): ?>
            <input type="hidden" name="id" value="<?= $miembro['id'] ?>">
        <?php endif; ?>

        <!-- Navegación por pestañas -->
        <ul class="nav nav-tabs mb-4" id="miembroTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="datos-tab" data-bs-toggle="tab" href="#datos" role="tab">
                    <i class="fas fa-user me-1"></i> Información General
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="contacto-tab" data-bs-toggle="tab" href="#contacto" role="tab">
                    <i class="fas fa-address-book me-1"></i> Contacto
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
                    <i class="fas fa-heartbeat me-1"></i> Salud y Emergencias
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="espiritual-tab" data-bs-toggle="tab" href="#espiritual" role="tab">
                    <i class="fas fa-pray me-1"></i> Carrera Bíblica
                </a>
            </li>
        </ul>

        <div class="tab-content" id="miembroTabContent">
            <!-- Pestaña de Información General -->
            <div class="tab-pane fade show active" id="datos" role="tabpanel">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nombres" class="form-label">Nombres <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nombres" name="nombres" value="<?= $esEdicion ? $miembro['nombres'] : '' ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="apellidos" class="form-label">Apellidos <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="apellidos" name="apellidos" value="<?= $esEdicion ? $miembro['apellidos'] : '' ?>" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="celular" class="form-label">Celular <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control" id="celular" name="celular" value="<?= $esEdicion ? $miembro['celular'] : '' ?>" placeholder="+57..." required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                                <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" value="<?= $esEdicion ? $miembro['fecha_nacimiento'] : '' ?>">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="localidad" class="form-label">Localidad</label>
                                <input type="text" class="form-control" id="localidad" name="localidad" value="<?= $esEdicion ? $miembro['localidad'] : '' ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="barrio" class="form-label">Barrio</label>
                                <input type="text" class="form-control" id="barrio" name="barrio" value="<?= $esEdicion ? $miembro['barrio'] : '' ?>">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="invitado_por" class="form-label">Invitado Por</label>
                                <select class="form-select" id="invitado_por" name="invitado_por">
                                    <option value="">-- Seleccione --</option>
                                    <?php if (isset($miembros_lista) && is_array($miembros_lista)): ?>
                                        <?php foreach ($miembros_lista as $m): ?>
                                            <option value="<?= $m['id'] ?>" <?= $esEdicion && $miembro['invitado_por'] == $m['id'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($m['nombres'] . ' ' . $m['apellidos']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="conector" class="form-label">Conector/Canal</label>
                                <select class="form-select" id="conector" name="conector">
                                    <option value="">-- Seleccione --</option>
                                    <option value="Familiar" <?= $esEdicion && $miembro['conector'] == 'Familiar' ? 'selected' : '' ?>>Familiar</option>
                                    <option value="Amigo" <?= $esEdicion && $miembro['conector'] == 'Amigo' ? 'selected' : '' ?>>Amigo</option>
                                    <option value="Redes Sociales" <?= $esEdicion && $miembro['conector'] == 'Redes Sociales' ? 'selected' : '' ?>>Redes Sociales</option>
                                    <option value="Invitación directa" <?= $esEdicion && $miembro['conector'] == 'Invitación directa' ? 'selected' : '' ?>>Invitación directa</option>
                                    <option value="Otro" <?= $esEdicion && $miembro['conector'] == 'Otro' ? 'selected' : '' ?>>Otro</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="estado_espiritual" class="form-label">Estado Espiritual</label>
                                <select class="form-select" id="estado_espiritual" name="estado_espiritual">
                                    <option value="">-- Seleccione --</option>
                                    <option value="Activo" <?= $esEdicion && $miembro['estado_espiritual'] == 'Activo' ? 'selected' : '' ?>>Activo</option>
                                    <option value="Inactivo" <?= $esEdicion && $miembro['estado_espiritual'] == 'Inactivo' ? 'selected' : '' ?>>Inactivo</option>
                                    <option value="Intermitente" <?= $esEdicion && $miembro['estado_espiritual'] == 'Intermitente' ? 'selected' : '' ?>>Intermitente</option>
                                    <option value="Nuevo" <?= $esEdicion && $miembro['estado_espiritual'] == 'Nuevo' ? 'selected' : '' ?>>Nuevo</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="foto" class="form-label">Foto</label>
                                <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
                                <?php if ($esEdicion && !empty($miembro['foto'])): ?>
                                    <div class="mt-2">
                                        <img src="<?= url('uploads/miembros/' . $miembro['foto']) ?>" alt="Foto actual" class="img-thumbnail" style="max-height: 100px;">
                                        <input type="hidden" name="foto_actual" value="<?= $miembro['foto'] ?>">
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="recorrido_espiritual" class="form-label">Recorrido Espiritual</label>
                            <textarea class="form-control" id="recorrido_espiritual" name="recorrido_espiritual" rows="3"><?= $esEdicion ? $miembro['recorrido_espiritual'] : '' ?></textarea>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="habeas_data" name="habeas_data" value="1" <?= $esEdicion && $miembro['habeas_data'] ? 'checked' : '' ?>>
                            <label class="form-check-label" for="habeas_data">
                                Acepto tratamiento de datos personales según la política de privacidad
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pestaña de Contacto -->
            <div class="tab-pane fade" id="contacto" role="tabpanel">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="tipo_documento" class="form-label">Tipo de Documento</label>
                                <select class="form-select" id="tipo_documento" name="contacto[tipo_documento]">
                                    <option value="">-- Seleccione --</option>
                                    <option value="CC" <?= isset($contacto['tipo_documento']) && $contacto['tipo_documento'] == 'CC' ? 'selected' : '' ?>>Cédula de Ciudadanía</option>
                                    <option value="CE" <?= isset($contacto['tipo_documento']) && $contacto['tipo_documento'] == 'CE' ? 'selected' : '' ?>>Cédula de Extranjería</option>
                                    <option value="TI" <?= isset($contacto['tipo_documento']) && $contacto['tipo_documento'] == 'TI' ? 'selected' : '' ?>>Tarjeta de Identidad</option>
                                    <option value="Pasaporte" <?= isset($contacto['tipo_documento']) && $contacto['tipo_documento'] == 'Pasaporte' ? 'selected' : '' ?>>Pasaporte</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="numero_documento" class="form-label">Número de Documento</label>
                                <input type="text" class="form-control" id="numero_documento" name="contacto[numero_documento]" value="<?= isset($contacto['numero_documento']) ? $contacto['numero_documento'] : '' ?>">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="telefono" class="form-label">Teléfono Fijo</label>
                                <input type="tel" class="form-control" id="telefono" name="contacto[telefono]" value="<?= isset($contacto['telefono']) ? $contacto['telefono'] : '' ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="correo_electronico" class="form-label">Correo Electrónico</label>
                                <input type="email" class="form-control" id="correo_electronico" name="contacto[correo_electronico]" value="<?= isset($contacto['correo_electronico']) ? $contacto['correo_electronico'] : '' ?>">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="pais" class="form-label">País</label>
                                <input type="text" class="form-control" id="pais" name="contacto[pais]" value="<?= isset($contacto['pais']) ? $contacto['pais'] : 'Colombia' ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="ciudad" class="form-label">Ciudad</label>
                                <input type="text" class="form-control" id="ciudad" name="contacto[ciudad]" value="<?= isset($contacto['ciudad']) ? $contacto['ciudad'] : 'Bogotá' ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="direccion" class="form-label">Dirección</label>
                            <input type="text" class="form-control" id="direccion" name="contacto[direccion]" value="<?= isset($contacto['direccion']) ? $contacto['direccion'] : '' ?>">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="estado_civil" class="form-label">Estado Civil</label>
                                <select class="form-select" id="estado_civil" name="contacto[estado_civil]">
                                    <option value="">-- Seleccione --</option>
                                    <option value="Soltero" <?= isset($contacto['estado_civil']) && $contacto['estado_civil'] == 'Soltero' ? 'selected' : '' ?>>Soltero</option>
                                    <option value="Casado" <?= isset($contacto['estado_civil']) && $contacto['estado_civil'] == 'Casado' ? 'selected' : '' ?>>Casado</option>
                                    <option value="Unión libre" <?= isset($contacto['estado_civil']) && $contacto['estado_civil'] == 'Unión libre' ? 'selected' : '' ?>>Unión libre</option>
                                    <option value="Divorciado" <?= isset($contacto['estado_civil']) && $contacto['estado_civil'] == 'Divorciado' ? 'selected' : '' ?>>Divorciado</option>
                                    <option value="Viudo" <?= isset($contacto['estado_civil']) && $contacto['estado_civil'] == 'Viudo' ? 'selected' : '' ?>>Viudo</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="familiares" class="form-label">Familiares en la Iglesia</label>
                                <input type="text" class="form-control" id="familiares" name="contacto[familiares]" value="<?= isset($contacto['familiares']) ? $contacto['familiares'] : '' ?>" placeholder="Ej: Esposo, Hijos, Padres">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="instagram" class="form-label">Instagram</label>
                                <input type="text" class="form-control" id="instagram" name="contacto[instagram]" value="<?= isset($contacto['instagram']) ? $contacto['instagram'] : '' ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="facebook" class="form-label">Facebook</label>
                                <input type="text" class="form-control" id="facebook" name="contacto[facebook]" value="<?= isset($contacto['facebook']) ? $contacto['facebook'] : '' ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notas" class="form-label">Notas Adicionales</label>
                            <textarea class="form-control" id="notas" name="contacto[notas]" rows="3"><?= isset($contacto['notas']) ? $contacto['notas'] : '' ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pestaña de Estudios/Trabajo -->
            <div class="tab-pane fade" id="estudios" role="tabpanel">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nivel_estudios" class="form-label">Nivel de Estudios</label>
                                <select class="form-select" id="nivel_estudios" name="estudios[nivel_estudios]">
                                    <option value="">-- Seleccione --</option>
                                    <option value="Primaria" <?= isset($estudios['nivel_estudios']) && $estudios['nivel_estudios'] == 'Primaria' ? 'selected' : '' ?>>Primaria</option>
                                    <option value="Secundaria" <?= isset($estudios['nivel_estudios']) && $estudios['nivel_estudios'] == 'Secundaria' ? 'selected' : '' ?>>Secundaria</option>
                                    <option value="Técnico" <?= isset($estudios['nivel_estudios']) && $estudios['nivel_estudios'] == 'Técnico' ? 'selected' : '' ?>>Técnico</option>
                                    <option value="Tecnólogo" <?= isset($estudios['nivel_estudios']) && $estudios['nivel_estudios'] == 'Tecnólogo' ? 'selected' : '' ?>>Tecnólogo</option>
                                    <option value="Pregrado" <?= isset($estudios['nivel_estudios']) && $estudios['nivel_estudios'] == 'Pregrado' ? 'selected' : '' ?>>Pregrado</option>
                                    <option value="Especialización" <?= isset($estudios['nivel_estudios']) && $estudios['nivel_estudios'] == 'Especialización' ? 'selected' : '' ?>>Especialización</option>
                                    <option value="Maestría" <?= isset($estudios['nivel_estudios']) && $estudios['nivel_estudios'] == 'Maestría' ? 'selected' : '' ?>>Maestría</option>
                                    <option value="Doctorado" <?= isset($estudios['nivel_estudios']) && $estudios['nivel_estudios'] == 'Doctorado' ? 'selected' : '' ?>>Doctorado</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="profesion" class="form-label">Profesión/Área de Estudio</label>
                                <input type="text" class="form-control" id="profesion" name="estudios[profesion]" value="<?= isset($estudios['profesion']) ? $estudios['profesion'] : '' ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="otros_estudios" class="form-label">Otros Estudios/Certificaciones</label>
                            <textarea class="form-control" id="otros_estudios" name="estudios[otros_estudios]" rows="3"><?= isset($estudios['otros_estudios']) ? $estudios['otros_estudios'] : '' ?></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="empresa" class="form-label">Empresa Actual</label>
                                <input type="text" class="form-control" id="empresa" name="estudios[empresa]" value="<?= isset($estudios['empresa']) ? $estudios['empresa'] : '' ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="direccion_empresa" class="form-label">Dirección de la Empresa</label>
                                <input type="text" class="form-control" id="direccion_empresa" name="estudios[direccion_empresa]" value="<?= isset($estudios['direccion_empresa']) ? $estudios['direccion_empresa'] : '' ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="emprendimientos" class="form-label">Emprendimientos/Negocios Propios</label>
                            <textarea class="form-control" id="emprendimientos" name="estudios[emprendimientos]" rows="3"><?= isset($estudios['emprendimientos']) ? $estudios['emprendimientos'] : '' ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pestaña de Tallas -->
            <div class="tab-pane fade" id="tallas" role="tabpanel">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="talla_camisa" class="form-label">Talla de Camisa</label>
                                <select class="form-select" id="talla_camisa" name="tallas[talla_camisa]">
                                    <option value="">-- Seleccione --</option>
                                    <option value="XS" <?= isset($tallas['talla_camisa']) && $tallas['talla_camisa'] == 'XS' ? 'selected' : '' ?>>XS</option>
                                    <option value="S" <?= isset($tallas['talla_camisa']) && $tallas['talla_camisa'] == 'S' ? 'selected' : '' ?>>S</option>
                                    <option value="M" <?= isset($tallas['talla_camisa']) && $tallas['talla_camisa'] == 'M' ? 'selected' : '' ?>>M</option>
                                    <option value="L" <?= isset($tallas['talla_camisa']) && $tallas['talla_camisa'] == 'L' ? 'selected' : '' ?>>L</option>
                                    <option value="XL" <?= isset($tallas['talla_camisa']) && $tallas['talla_camisa'] == 'XL' ? 'selected' : '' ?>>XL</option>
                                    <option value="XXL" <?= isset($tallas['talla_camisa']) && $tallas['talla_camisa'] == 'XXL' ? 'selected' : '' ?>>XXL</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="talla_camiseta" class="form-label">Talla de Camiseta</label>
                                <select class="form-select" id="talla_camiseta" name="tallas[talla_camiseta]">
                                    <option value="">-- Seleccione --</option>
                                    <option value="XS" <?= isset($tallas['talla_camiseta']) && $tallas['talla_camiseta'] == 'XS' ? 'selected' : '' ?>>XS</option>
                                    <option value="S" <?= isset($tallas['talla_camiseta']) && $tallas['talla_camiseta'] == 'S' ? 'selected' : '' ?>>S</option>
                                    <option value="M" <?= isset($tallas['talla_camiseta']) && $tallas['talla_camiseta'] == 'M' ? 'selected' : '' ?>>M</option>
                                    <option value="L" <?= isset($tallas['talla_camiseta']) && $tallas['talla_camiseta'] == 'L' ? 'selected' : '' ?>>L</option>
                                    <option value="XL" <?= isset($tallas['talla_camiseta']) && $tallas['talla_camiseta'] == 'XL' ? 'selected' : '' ?>>XL</option>
                                    <option value="XXL" <?= isset($tallas['talla_camiseta']) && $tallas['talla_camiseta'] == 'XXL' ? 'selected' : '' ?>>XXL</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="talla_pantalon" class="form-label">Talla de Pantalón</label>
                                <input type="text" class="form-control" id="talla_pantalon" name="tallas[talla_pantalon]" value="<?= isset($tallas['talla_pantalon']) ? $tallas['talla_pantalon'] : '' ?>" placeholder="Ejemplo: 32, M, 12...">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="talla_zapatos" class="form-label">Talla de Zapatos</label>
                                <input type="text" class="form-control" id="talla_zapatos" name="tallas[talla_zapatos]" value="<?= isset($tallas['talla_zapatos']) ? $tallas['talla_zapatos'] : '' ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pestaña de Salud y Emergencias -->
            <div class="tab-pane fade" id="salud" role="tabpanel">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="rh" class="form-label">Tipo de Sangre/RH</label>
                            <select class="form-select" id="rh" name="salud[rh]">
                                <option value="">-- Seleccione --</option>
                                <option value="A+" <?= isset($salud['rh']) && $salud['rh'] == 'A+' ? 'selected' : '' ?>>A+</option>
                                <option value="A-" <?= isset($salud['rh']) && $salud['rh'] == 'A-' ? 'selected' : '' ?>>A-</option>
                                <option value="B+" <?= isset($salud['rh']) && $salud['rh'] == 'B+' ? 'selected' : '' ?>>B+</option>
                                <option value="B-" <?= isset($salud['rh']) && $salud['rh'] == 'B-' ? 'selected' : '' ?>>B-</option>
                                <option value="AB+" <?= isset($salud['rh']) && $salud['rh'] == 'AB+' ? 'selected' : '' ?>>AB+</option>
                                <option value="AB-" <?= isset($salud['rh']) && $salud['rh'] == 'AB-' ? 'selected' : '' ?>>AB-</option>
                                <option value="O+" <?= isset($salud['rh']) && $salud['rh'] == 'O+' ? 'selected' : '' ?>>O+</option>
                                <option value="O-" <?= isset($salud['rh']) && $salud['rh'] == 'O-' ? 'selected' : '' ?>>O-</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="eps" class="form-label">EPS</label>
                            <input type="text" class="form-control" id="eps" name="salud[eps]" value="<?= isset($salud['eps']) ? $salud['eps'] : '' ?>">
                        </div>

                        <h5 class="mt-4 mb-3">Contactos de Emergencia</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="acudiente1" class="form-label">Nombre del Primer Contacto</label>
                                <input type="text" class="form-control" id="acudiente1" name="salud[acudiente1]" value="<?= isset($salud['acudiente1']) ? $salud['acudiente1'] : '' ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="telefono1" class="form-label">Teléfono del Primer Contacto</label>
                                <input type="tel" class="form-control" id="telefono1" name="salud[telefono1]" value="<?= isset($salud['telefono1']) ? $salud['telefono1'] : '' ?>">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="acudiente2" class="form-label">Nombre del Segundo Contacto</label>
                                <input type="text" class="form-control" id="acudiente2" name="salud[acudiente2]" value="<?= isset($salud['acudiente2']) ? $salud['acudiente2'] : '' ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="telefono2" class="form-label">Teléfono del Segundo Contacto</label>
                                <input type="tel" class="form-control" id="telefono2" name="salud[telefono2]" value="<?= isset($salud['telefono2']) ? $salud['telefono2'] : '' ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pestaña de Carrera Bíblica -->
            <div class="tab-pane fade" id="espiritual" role="tabpanel">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="carrera_biblica" class="form-label">Nivel en Carrera Bíblica</label>
                                <select class="form-select" id="carrera_biblica" name="carrera[carrera_biblica]">
                                    <option value="">-- Seleccione --</option>
                                    <option value="Nivel 1" <?= isset($carrera['carrera_biblica']) && $carrera['carrera_biblica'] == 'Nivel 1' ? 'selected' : '' ?>>Nivel 1</option>
                                    <option value="Nivel 2" <?= isset($carrera['carrera_biblica']) && $carrera['carrera_biblica'] == 'Nivel 2' ? 'selected' : '' ?>>Nivel 2</option>
                                    <option value="Nivel 3" <?= isset($carrera['carrera_biblica']) && $carrera['carrera_biblica'] == 'Nivel 3' ? 'selected' : '' ?>>Nivel 3</option>
                                    <option value="Nivel 4" <?= isset($carrera['carrera_biblica']) && $carrera['carrera_biblica'] == 'Nivel 4' ? 'selected' : '' ?>>Nivel 4</option>
                                    <option value="Nivel 5" <?= isset($carrera['carrera_biblica']) && $carrera['carrera_biblica'] == 'Nivel 5' ? 'selected' : '' ?>>Nivel 5</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="miembro_de" class="form-label">Miembro de (Ministerio)</label>
                                <input type="text" class="form-control" id="miembro_de" name="carrera[miembro_de]" value="<?= isset($carrera['miembro_de']) ? $carrera['miembro_de'] : '' ?>">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="casa_de_palabra_y_vida" class="form-label">Casa de Palabra y Vida</label>
                                <input type="text" class="form-control" id="casa_de_palabra_y_vida" name="carrera[casa_de_palabra_y_vida]" value="<?= isset($carrera['casa_de_palabra_y_vida']) ? $carrera['casa_de_palabra_y_vida'] : '' ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="cobertura" class="form-label">Cobertura/Líder</label>
                                <input type="text" class="form-control" id="cobertura" name="carrera[cobertura]" value="<?= isset($carrera['cobertura']) ? $carrera['cobertura'] : '' ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="estado" class="form-label">Estado de Participación</label>
                            <select class="form-select" id="estado" name="carrera[estado]">
                                <option value="">-- Seleccione --</option>
                                <option value="Activo" <?= isset($carrera['estado']) && $carrera['estado'] == 'Activo' ? 'selected' : '' ?>>Activo</option>
                                <option value="Inactivo" <?= isset($carrera['estado']) && $carrera['estado'] == 'Inactivo' ? 'selected' : '' ?>>Inactivo</option>
                                <option value="Intermitente" <?= isset($carrera['estado']) && $carrera['estado'] == 'Intermitente' ? 'selected' : '' ?>>Intermitente</option>
                                <option value="Nuevo" <?= isset($carrera['estado']) && $carrera['estado'] == 'Nuevo' ? 'selected' : '' ?>>Nuevo</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="anotaciones" class="form-label">Anotaciones</label>
                            <textarea class="form-control" id="anotaciones" name="carrera[anotaciones]" rows="3"><?= isset($carrera['anotaciones']) ? $carrera['anotaciones'] : '' ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="recorrido_espiritual_carrera" class="form-label">Recorrido Espiritual (Detallado)</label>
                            <textarea class="form-control" id="recorrido_espiritual_carrera" name="carrera[recorrido_espiritual]" rows="3"><?= isset($carrera['recorrido_espiritual']) ? $carrera['recorrido_espiritual'] : '' ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-save me-1"></i> <?= $esEdicion ? 'Actualizar' : 'Registrar' ?> Miembro
            </button>
            <a href="<?= url('miembros') ?>" class="btn btn-secondary btn-lg">
                <i class="fas fa-times me-1"></i> Cancelar
            </a>
        </div>
    </form>
</div>