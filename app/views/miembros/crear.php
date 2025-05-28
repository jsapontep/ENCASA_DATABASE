<?php
// filepath: c:\xampp\htdocs\ENCASA_DATABASE\app\views\miembros\crear.php

// Verificar si es modo edición
$esEdicion = isset($miembro) && !empty($miembro);
$titulo = $esEdicion ? 'Editar Miembro' : 'Registrar Nuevo Miembro';
?>

<div class="container mt-4">
    <h1><?= $titulo ?></h1>
    
    <form action="<?= APP_URL ?>/miembros/<?= $esEdicion ? 'actualizar/'.$miembro['id'] : 'guardar' ?>" method="POST" enctype="multipart/form-data">
        <!-- Si es edición, añadir campo oculto con ID -->
        <?php if($esEdicion): ?>
            <input type="hidden" name="id" value="<?= $miembro['id'] ?>">
        <?php endif; ?>

        <!-- Navegación por pestañas -->
        <ul class="nav nav-tabs mb-4" id="miembroTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="datos-tab" data-bs-toggle="tab" href="#datos" role="tab">Información General</a>
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
                <a class="nav-link" id="espiritual-tab" data-bs-toggle="tab" href="#espiritual" role="tab">Carrera Bíblica</a>
            </li>
        </ul>

        <div class="tab-content" id="miembroTabContent">
            <!-- Pestaña de Información General -->
            <div class="tab-pane fade show active" id="datos" role="tabpanel">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nombres" class="form-label">Nombres *</label>
                        <input type="text" class="form-control" id="nombres" name="nombres" required 
                               value="<?= $esEdicion ? htmlspecialchars($miembro['nombres']) : '' ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="apellidos" class="form-label">Apellidos *</label>
                        <input type="text" class="form-control" id="apellidos" name="apellidos" required 
                               value="<?= $esEdicion ? htmlspecialchars($miembro['apellidos']) : '' ?>">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="celular" class="form-label">Celular *</label>
                        <input type="tel" class="form-control" id="celular" name="celular" required 
                               value="<?= $esEdicion ? htmlspecialchars($miembro['celular']) : '' ?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                        <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" 
                               value="<?= $esEdicion && $miembro['fecha_nacimiento'] ? $miembro['fecha_nacimiento'] : '' ?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="foto" class="form-label">Foto</label>
                        <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
                        <?php if($esEdicion && !empty($miembro['foto'])): ?>
                            <div class="mt-2">
                                <img src="<?= APP_URL ?>/uploads/miembros/<?= $miembro['foto'] ?>" alt="Foto actual" class="img-thumbnail" style="max-height: 100px;">
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="localidad" class="form-label">Localidad</label>
                        <input type="text" class="form-control" id="localidad" name="localidad" 
                               value="<?= $esEdicion ? htmlspecialchars($miembro['localidad'] ?? '') : '' ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="barrio" class="form-label">Barrio</label>
                        <input type="text" class="form-control" id="barrio" name="barrio" 
                               value="<?= $esEdicion ? htmlspecialchars($miembro['barrio'] ?? '') : '' ?>">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="invitado_por" class="form-label">Invitado por</label>
                        <select class="form-select" id="invitado_por" name="invitado_por">
                            <option value="">-- Seleccione --</option>
                            <!-- Aquí cargar dinámicamente los miembros disponibles -->
                            <?php if(isset($miembros) && is_array($miembros)): ?>
                                <?php foreach($miembros as $m): ?>
                                    <option value="<?= $m['id'] ?>" <?= $esEdicion && $miembro['invitado_por'] == $m['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($m['nombres'] . ' ' . $m['apellidos']) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="conector" class="form-label">Conector</label>
                        <input type="text" class="form-control" id="conector" name="conector" 
                               placeholder="Familiar, Amigo, Redes sociales, etc." 
                               value="<?= $esEdicion ? htmlspecialchars($miembro['conector'] ?? '') : '' ?>">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="estado_espiritual" class="form-label">Estado Espiritual</label>
                        <select class="form-select" id="estado_espiritual" name="estado_espiritual">
                            <option value="">-- Seleccione --</option>
                            <option value="Visitante" <?= $esEdicion && ($miembro['estado_espiritual'] ?? '') == 'Visitante' ? 'selected' : '' ?>>Visitante</option>
                            <option value="Nuevo Creyente" <?= $esEdicion && ($miembro['estado_espiritual'] ?? '') == 'Nuevo Creyente' ? 'selected' : '' ?>>Nuevo Creyente</option>
                            <option value="Discípulo" <?= $esEdicion && ($miembro['estado_espiritual'] ?? '') == 'Discípulo' ? 'selected' : '' ?>>Discípulo</option>
                            <option value="Líder" <?= $esEdicion && ($miembro['estado_espiritual'] ?? '') == 'Líder' ? 'selected' : '' ?>>Líder</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-check mt-4">
                            <input class="form-check-input" type="checkbox" id="habeas_data" name="habeas_data" value="1" 
                                  <?= $esEdicion && ($miembro['habeas_data'] ?? 0) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="habeas_data">
                                Autoriza tratamiento de datos personales
                            </label>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="recorrido_espiritual" class="form-label">Recorrido Espiritual</label>
                    <textarea class="form-control" id="recorrido_espiritual" name="recorrido_espiritual" rows="3"><?= $esEdicion ? htmlspecialchars($miembro['recorrido_espiritual'] ?? '') : '' ?></textarea>
                </div>
            </div>

            <!-- Pestaña de Contacto -->
            <div class="tab-pane fade" id="contacto" role="tabpanel">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="tipo_documento" class="form-label">Tipo de Documento</label>
                        <select class="form-select" id="tipo_documento" name="contacto[tipo_documento]">
                            <option value="">-- Seleccione --</option>
                            <option value="CC" <?= $esEdicion && isset($miembro['contacto']['tipo_documento']) && $miembro['contacto']['tipo_documento'] == 'CC' ? 'selected' : '' ?>>Cédula de Ciudadanía</option>
                            <option value="CE" <?= $esEdicion && isset($miembro['contacto']['tipo_documento']) && $miembro['contacto']['tipo_documento'] == 'CE' ? 'selected' : '' ?>>Cédula de Extranjería</option>
                            <option value="TI" <?= $esEdicion && isset($miembro['contacto']['tipo_documento']) && $miembro['contacto']['tipo_documento'] == 'TI' ? 'selected' : '' ?>>Tarjeta de Identidad</option>
                            <option value="Pasaporte" <?= $esEdicion && isset($miembro['contacto']['tipo_documento']) && $miembro['contacto']['tipo_documento'] == 'Pasaporte' ? 'selected' : '' ?>>Pasaporte</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="numero_documento" class="form-label">Número de Documento</label>
                        <input type="text" class="form-control" id="numero_documento" name="contacto[numero_documento]" 
                               value="<?= $esEdicion && isset($miembro['contacto']['numero_documento']) ? htmlspecialchars($miembro['contacto']['numero_documento']) : '' ?>">
                    </div>
                </div>

                <!-- Continuar con el resto de campos de contacto: teléfono, país, ciudad, dirección, etc. -->
            </div>

            <!-- Pestaña de Estudios/Trabajo -->
            <div class="tab-pane fade" id="estudios" role="tabpanel">
                <!-- Campos para Estudios y Trabajo -->
            </div>

            <!-- Pestaña de Tallas -->
            <div class="tab-pane fade" id="tallas" role="tabpanel">
                <!-- Campos para Tallas -->
            </div>

            <!-- Pestaña de Carrera Bíblica -->
            <div class="tab-pane fade" id="espiritual" role="tabpanel">
                <!-- Campos para Carrera Bíblica -->
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">
                <?= $esEdicion ? 'Actualizar' : 'Registrar' ?> Miembro
            </button>
            <a href="<?= APP_URL ?>/miembros" class="btn btn-secondary ms-2">Cancelar</a>
        </div>
    </form>
</div>