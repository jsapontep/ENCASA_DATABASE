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
                <a class="nav-link" id="salud-tab" data-bs-toggle="tab" href="#salud" role="tab">Salud y Emergencias</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="espiritual-tab" data-bs-toggle="tab" href="#espiritual" role="tab">Carrera Bíblica</a>
            </li>
        </ul>

        <div class="tab-content" id="miembroTabContent">
            <!-- Pestaña de Información General - ya implementada -->
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
                                    <option value="<?= $m['id'] ?>" <?= $esEdicion && isset($miembro['invitado_por']) && $miembro['invitado_por'] == $m['id'] ? 'selected' : '' ?>>
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

            <!-- Pestaña de Contacto - completar -->
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

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="telefono" class="form-label">Teléfono Fijo</label>
                        <input type="tel" class="form-control" id="telefono" name="contacto[telefono]"
                               value="<?= $esEdicion && isset($miembro['contacto']['telefono']) ? htmlspecialchars($miembro['contacto']['telefono']) : '' ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="correo_electronico" class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control" id="correo_electronico" name="contacto[correo_electronico]"
                               value="<?= $esEdicion && isset($miembro['contacto']['correo_electronico']) ? htmlspecialchars($miembro['contacto']['correo_electronico']) : '' ?>">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="pais" class="form-label">País</label>
                        <input type="text" class="form-control" id="pais" name="contacto[pais]"
                               value="<?= $esEdicion && isset($miembro['contacto']['pais']) ? htmlspecialchars($miembro['contacto']['pais']) : '' ?>"
                               placeholder="Ejemplo: Colombia">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="ciudad" class="form-label">Ciudad</label>
                        <input type="text" class="form-control" id="ciudad" name="contacto[ciudad]"
                               value="<?= $esEdicion && isset($miembro['contacto']['ciudad']) ? htmlspecialchars($miembro['contacto']['ciudad']) : '' ?>"
                               placeholder="Ejemplo: Bogotá">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="direccion" class="form-label">Dirección</label>
                    <input type="text" class="form-control" id="direccion" name="contacto[direccion]"
                           value="<?= $esEdicion && isset($miembro['contacto']['direccion']) ? htmlspecialchars($miembro['contacto']['direccion']) : '' ?>">
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="estado_civil" class="form-label">Estado Civil</label>
                        <select class="form-select" id="estado_civil" name="contacto[estado_civil]">
                            <option value="">-- Seleccione --</option>
                            <option value="Soltero/a" <?= $esEdicion && isset($miembro['contacto']['estado_civil']) && $miembro['contacto']['estado_civil'] == 'Soltero/a' ? 'selected' : '' ?>>Soltero/a</option>
                            <option value="Casado/a" <?= $esEdicion && isset($miembro['contacto']['estado_civil']) && $miembro['contacto']['estado_civil'] == 'Casado/a' ? 'selected' : '' ?>>Casado/a</option>
                            <option value="Viudo/a" <?= $esEdicion && isset($miembro['contacto']['estado_civil']) && $miembro['contacto']['estado_civil'] == 'Viudo/a' ? 'selected' : '' ?>>Viudo/a</option>
                            <option value="Divorciado/a" <?= $esEdicion && isset($miembro['contacto']['estado_civil']) && $miembro['contacto']['estado_civil'] == 'Divorciado/a' ? 'selected' : '' ?>>Divorciado/a</option>
                            <option value="Unión Libre" <?= $esEdicion && isset($miembro['contacto']['estado_civil']) && $miembro['contacto']['estado_civil'] == 'Unión Libre' ? 'selected' : '' ?>>Unión Libre</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="instagram" class="form-label">Instagram</label>
                        <input type="text" class="form-control" id="instagram" name="contacto[instagram]"
                               value="<?= $esEdicion && isset($miembro['contacto']['instagram']) ? htmlspecialchars($miembro['contacto']['instagram']) : '' ?>"
                               placeholder="@usuario">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="facebook" class="form-label">Facebook</label>
                        <input type="text" class="form-control" id="facebook" name="contacto[facebook]"
                               value="<?= $esEdicion && isset($miembro['contacto']['facebook']) ? htmlspecialchars($miembro['contacto']['facebook']) : '' ?>"
                               placeholder="URL o nombre de usuario">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="familiares" class="form-label">Familiares en la iglesia</label>
                    <textarea class="form-control" id="familiares" name="contacto[familiares]" rows="2"
                             placeholder="Nombres de familiares que asisten a la iglesia"><?= $esEdicion && isset($miembro['contacto']['familiares']) ? htmlspecialchars($miembro['contacto']['familiares']) : '' ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="notas" class="form-label">Notas adicionales de contacto</label>
                    <textarea class="form-control" id="notas" name="contacto[notas]" rows="2"><?= $esEdicion && isset($miembro['contacto']['notas']) ? htmlspecialchars($miembro['contacto']['notas']) : '' ?></textarea>
                </div>
            </div>

            <!-- Pestaña de Estudios/Trabajo -->
            <div class="tab-pane fade" id="estudios" role="tabpanel">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nivel_estudios" class="form-label">Nivel de Estudios</label>
                        <select class="form-select" id="nivel_estudios" name="estudios[nivel_estudios]">
                            <option value="">-- Seleccione --</option>
                            <option value="Primaria" <?= $esEdicion && isset($miembro['estudios']['nivel_estudios']) && $miembro['estudios']['nivel_estudios'] == 'Primaria' ? 'selected' : '' ?>>Primaria</option>
                            <option value="Secundaria" <?= $esEdicion && isset($miembro['estudios']['nivel_estudios']) && $miembro['estudios']['nivel_estudios'] == 'Secundaria' ? 'selected' : '' ?>>Secundaria</option>
                            <option value="Técnico" <?= $esEdicion && isset($miembro['estudios']['nivel_estudios']) && $miembro['estudios']['nivel_estudios'] == 'Técnico' ? 'selected' : '' ?>>Técnico</option>
                            <option value="Tecnólogo" <?= $esEdicion && isset($miembro['estudios']['nivel_estudios']) && $miembro['estudios']['nivel_estudios'] == 'Tecnólogo' ? 'selected' : '' ?>>Tecnólogo</option>
                            <option value="Universitario" <?= $esEdicion && isset($miembro['estudios']['nivel_estudios']) && $miembro['estudios']['nivel_estudios'] == 'Universitario' ? 'selected' : '' ?>>Universitario</option>
                            <option value="Posgrado" <?= $esEdicion && isset($miembro['estudios']['nivel_estudios']) && $miembro['estudios']['nivel_estudios'] == 'Posgrado' ? 'selected' : '' ?>>Posgrado</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="profesion" class="form-label">Profesión/Ocupación</label>
                        <input type="text" class="form-control" id="profesion" name="estudios[profesion]"
                               value="<?= $esEdicion && isset($miembro['estudios']['profesion']) ? htmlspecialchars($miembro['estudios']['profesion']) : '' ?>">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="otros_estudios" class="form-label">Otros Estudios/Certificaciones</label>
                    <textarea class="form-control" id="otros_estudios" name="estudios[otros_estudios]" rows="2"><?= $esEdicion && isset($miembro['estudios']['otros_estudios']) ? htmlspecialchars($miembro['estudios']['otros_estudios']) : '' ?></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="empresa" class="form-label">Empresa donde trabaja</label>
                        <input type="text" class="form-control" id="empresa" name="estudios[empresa]"
                               value="<?= $esEdicion && isset($miembro['estudios']['empresa']) ? htmlspecialchars($miembro['estudios']['empresa']) : '' ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="direccion_empresa" class="form-label">Dirección de la empresa</label>
                        <input type="text" class="form-control" id="direccion_empresa" name="estudios[direccion_empresa]"
                               value="<?= $esEdicion && isset($miembro['estudios']['direccion_empresa']) ? htmlspecialchars($miembro['estudios']['direccion_empresa']) : '' ?>">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="emprendimientos" class="form-label">Emprendimientos/Negocios propios</label>
                    <textarea class="form-control" id="emprendimientos" name="estudios[emprendimientos]" rows="2"><?= $esEdicion && isset($miembro['estudios']['emprendimientos']) ? htmlspecialchars($miembro['estudios']['emprendimientos']) : '' ?></textarea>
                </div>
            </div>

            <!-- Pestaña de Tallas -->
            <div class="tab-pane fade" id="tallas" role="tabpanel">
                <div class="alert alert-info mb-4">
                    Esta información es útil para la entrega de uniformes y vestimenta en eventos especiales.
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="talla_camisa" class="form-label">Talla de Camisa</label>
                        <select class="form-select" id="talla_camisa" name="tallas[talla_camisa]">
                            <option value="">-- Seleccione --</option>
                            <option value="XS" <?= $esEdicion && isset($miembro['tallas']['talla_camisa']) && $miembro['tallas']['talla_camisa'] == 'XS' ? 'selected' : '' ?>>XS</option>
                            <option value="S" <?= $esEdicion && isset($miembro['tallas']['talla_camisa']) && $miembro['tallas']['talla_camisa'] == 'S' ? 'selected' : '' ?>>S</option>
                            <option value="M" <?= $esEdicion && isset($miembro['tallas']['talla_camisa']) && $miembro['tallas']['talla_camisa'] == 'M' ? 'selected' : '' ?>>M</option>
                            <option value="L" <?= $esEdicion && isset($miembro['tallas']['talla_camisa']) && $miembro['tallas']['talla_camisa'] == 'L' ? 'selected' : '' ?>>L</option>
                            <option value="XL" <?= $esEdicion && isset($miembro['tallas']['talla_camisa']) && $miembro['tallas']['talla_camisa'] == 'XL' ? 'selected' : '' ?>>XL</option>
                            <option value="XXL" <?= $esEdicion && isset($miembro['tallas']['talla_camisa']) && $miembro['tallas']['talla_camisa'] == 'XXL' ? 'selected' : '' ?>>XXL</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="talla_camiseta" class="form-label">Talla de Camiseta</label>
                        <select class="form-select" id="talla_camiseta" name="tallas[talla_camiseta]">
                            <option value="">-- Seleccione --</option>
                            <option value="XS" <?= $esEdicion && isset($miembro['tallas']['talla_camiseta']) && $miembro['tallas']['talla_camiseta'] == 'XS' ? 'selected' : '' ?>>XS</option>
                            <option value="S" <?= $esEdicion && isset($miembro['tallas']['talla_camiseta']) && $miembro['tallas']['talla_camiseta'] == 'S' ? 'selected' : '' ?>>S</option>
                            <option value="M" <?= $esEdicion && isset($miembro['tallas']['talla_camiseta']) && $miembro['tallas']['talla_camiseta'] == 'M' ? 'selected' : '' ?>>M</option>
                            <option value="L" <?= $esEdicion && isset($miembro['tallas']['talla_camiseta']) && $miembro['tallas']['talla_camiseta'] == 'L' ? 'selected' : '' ?>>L</option>
                            <option value="XL" <?= $esEdicion && isset($miembro['tallas']['talla_camiseta']) && $miembro['tallas']['talla_camiseta'] == 'XL' ? 'selected' : '' ?>>XL</option>
                            <option value="XXL" <?= $esEdicion && isset($miembro['tallas']['talla_camiseta']) && $miembro['tallas']['talla_camiseta'] == 'XXL' ? 'selected' : '' ?>>XXL</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="talla_pantalon" class="form-label">Talla de Pantalón</label>
                        <input type="text" class="form-control" id="talla_pantalon" name="tallas[talla_pantalon]"
                               placeholder="Ejemplo: 32, 34, S, M, etc."
                               value="<?= $esEdicion && isset($miembro['tallas']['talla_pantalon']) ? htmlspecialchars($miembro['tallas']['talla_pantalon']) : '' ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="talla_zapatos" class="form-label">Talla de Zapatos</label>
                        <input type="text" class="form-control" id="talla_zapatos" name="tallas[talla_zapatos]"
                               placeholder="Ejemplo: 39, 40, 41, etc."
                               value="<?= $esEdicion && isset($miembro['tallas']['talla_zapatos']) ? htmlspecialchars($miembro['tallas']['talla_zapatos']) : '' ?>">
                    </div>
                </div>
            </div>

            <!-- Pestaña de Salud y Emergencias -->
            <div class="tab-pane fade" id="salud" role="tabpanel">
                <div class="alert alert-info mb-4">
                    <i class="fas fa-info-circle"></i> Esta información es vital en caso de emergencia. Por favor completa todos los campos posibles.
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="rh" class="form-label">Tipo de Sangre (RH)</label>
                        <select class="form-select" id="rh" name="salud[rh]">
                            <option value="">-- Seleccione --</option>
                            <option value="A+" <?= $esEdicion && isset($miembro['salud']['rh']) && $miembro['salud']['rh'] == 'A+' ? 'selected' : '' ?>>A+</option>
                            <option value="A-" <?= $esEdicion && isset($miembro['salud']['rh']) && $miembro['salud']['rh'] == 'A-' ? 'selected' : '' ?>>A-</option>
                            <option value="B+" <?= $esEdicion && isset($miembro['salud']['rh']) && $miembro['salud']['rh'] == 'B+' ? 'selected' : '' ?>>B+</option>
                            <option value="B-" <?= $esEdicion && isset($miembro['salud']['rh']) && $miembro['salud']['rh'] == 'B-' ? 'selected' : '' ?>>B-</option>
                            <option value="AB+" <?= $esEdicion && isset($miembro['salud']['rh']) && $miembro['salud']['rh'] == 'AB+' ? 'selected' : '' ?>>AB+</option>
                            <option value="AB-" <?= $esEdicion && isset($miembro['salud']['rh']) && $miembro['salud']['rh'] == 'AB-' ? 'selected' : '' ?>>AB-</option>
                            <option value="O+" <?= $esEdicion && isset($miembro['salud']['rh']) && $miembro['salud']['rh'] == 'O+' ? 'selected' : '' ?>>O+</option>
                            <option value="O-" <?= $esEdicion && isset($miembro['salud']['rh']) && $miembro['salud']['rh'] == 'O-' ? 'selected' : '' ?>>O-</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="eps" class="form-label">EPS</label>
                        <input type="text" class="form-control" id="eps" name="salud[eps]"
                               value="<?= $esEdicion && isset($miembro['salud']['eps']) ? htmlspecialchars($miembro['salud']['eps']) : '' ?>"
                               placeholder="Ejemplo: Sura, Sanitas, Nueva EPS, etc.">
                    </div>
                </div>

                <h5 class="mt-4">Contacto de Emergencia Principal</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="acudiente1" class="form-label">Nombre Completo</label>
                        <input type="text" class="form-control" id="acudiente1" name="salud[acudiente1]"
                               value="<?= $esEdicion && isset($miembro['salud']['acudiente1']) ? htmlspecialchars($miembro['salud']['acudiente1']) : '' ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="telefono1" class="form-label">Teléfono de Contacto</label>
                        <input type="tel" class="form-control" id="telefono1" name="salud[telefono1]"
                               value="<?= $esEdicion && isset($miembro['salud']['telefono1']) ? htmlspecialchars($miembro['salud']['telefono1']) : '' ?>"
                               placeholder="Formato: +57 3001234567">
                    </div>
                </div>

                <h5 class="mt-4">Contacto de Emergencia Alternativo</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="acudiente2" class="form-label">Nombre Completo</label>
                        <input type="text" class="form-control" id="acudiente2" name="salud[acudiente2]"
                               value="<?= $esEdicion && isset($miembro['salud']['acudiente2']) ? htmlspecialchars($miembro['salud']['acudiente2']) : '' ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="telefono2" class="form-label">Teléfono de Contacto</label>
                        <input type="tel" class="form-control" id="telefono2" name="salud[telefono2]"
                               value="<?= $esEdicion && isset($miembro['salud']['telefono2']) ? htmlspecialchars($miembro['salud']['telefono2']) : '' ?>"
                               placeholder="Formato: +57 3001234567">
                    </div>
                </div>

                <div class="mb-3">
                    <div class="form-check mt-3">
                        <input class="form-check-input" type="checkbox" id="alergias_check">
                        <label class="form-check-label" for="alergias_check">
                            ¿Tiene alergias o condiciones médicas importantes?
                        </label>
                    </div>
                    <div id="alergias_container" class="mt-2" style="display: none;">
                        <textarea class="form-control" id="alergias" name="salud[alergias]" rows="3" 
                                  placeholder="Describa alergias o condiciones médicas relevantes"><?= $esEdicion && isset($miembro['salud']['alergias']) ? htmlspecialchars($miembro['salud']['alergias']) : '' ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Pestaña de Carrera Bíblica -->
            <div class="tab-pane fade" id="espiritual" role="tabpanel">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="carrera_biblica" class="form-label">Nivel en Carrera Bíblica</label>
                        <select class="form-select" id="carrera_biblica" name="carrera[carrera_biblica]">
                            <option value="">-- Seleccione --</option>
                            <option value="No ha iniciado" <?= $esEdicion && isset($miembro['carrera']['carrera_biblica']) && $miembro['carrera']['carrera_biblica'] == 'No ha iniciado' ? 'selected' : '' ?>>No ha iniciado</option>
                            <option value="Nivel 1" <?= $esEdicion && isset($miembro['carrera']['carrera_biblica']) && $miembro['carrera']['carrera_biblica'] == 'Nivel 1' ? 'selected' : '' ?>>Nivel 1</option>
                            <option value="Nivel 2" <?= $esEdicion && isset($miembro['carrera']['carrera_biblica']) && $miembro['carrera']['carrera_biblica'] == 'Nivel 2' ? 'selected' : '' ?>>Nivel 2</option>
                            <option value="Nivel 3" <?= $esEdicion && isset($miembro['carrera']['carrera_biblica']) && $miembro['carrera']['carrera_biblica'] == 'Nivel 3' ? 'selected' : '' ?>>Nivel 3</option>
                            <option value="Nivel 4" <?= $esEdicion && isset($miembro['carrera']['carrera_biblica']) && $miembro['carrera']['carrera_biblica'] == 'Nivel 4' ? 'selected' : '' ?>>Nivel 4</option>
                            <option value="Nivel 5" <?= $esEdicion && isset($miembro['carrera']['carrera_biblica']) && $miembro['carrera']['carrera_biblica'] == 'Nivel 5' ? 'selected' : '' ?>>Nivel 5</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="estado" class="form-label">Estado Espiritual</label>
                        <select class="form-select" id="estado" name="carrera[estado]">
                            <option value="">-- Seleccione --</option>
                            <option value="Visitante" <?= $esEdicion && isset($miembro['carrera']['estado']) && $miembro['carrera']['estado'] == 'Visitante' ? 'selected' : '' ?>>Visitante</option>
                            <option value="Nuevo Creyente" <?= $esEdicion && isset($miembro['carrera']['estado']) && $miembro['carrera']['estado'] == 'Nuevo Creyente' ? 'selected' : '' ?>>Nuevo Creyente</option>
                            <option value="Discípulo" <?= $esEdicion && isset($miembro['carrera']['estado']) && $miembro['carrera']['estado'] == 'Discípulo' ? 'selected' : '' ?>>Discípulo</option>
                            <option value="Líder" <?= $esEdicion && isset($miembro['carrera']['estado']) && $miembro['carrera']['estado'] == 'Líder' ? 'selected' : '' ?>>Líder</option>
                            <option value="Pastor" <?= $esEdicion && isset($miembro['carrera']['estado']) && $miembro['carrera']['estado'] == 'Pastor' ? 'selected' : '' ?>>Pastor</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="miembro_de" class="form-label">Miembro de Grupo</label>
                        <input type="text" class="form-control" id="miembro_de" name="carrera[miembro_de]"
                               value="<?= $esEdicion && isset($miembro['carrera']['miembro_de']) ? htmlspecialchars($miembro['carrera']['miembro_de']) : '' ?>"
                               placeholder="Ejemplo: Grupo de Jóvenes, Grupo de Matrimonios, etc.">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="casa_de_palabra_y_vida" class="form-label">Casa de Palabra y Vida</label>
                        <input type="text" class="form-control" id="casa_de_palabra_y_vida" name="carrera[casa_de_palabra_y_vida]"
                               value="<?= $esEdicion && isset($miembro['carrera']['casa_de_palabra_y_vida']) ? htmlspecialchars($miembro['carrera']['casa_de_palabra_y_vida']) : '' ?>"
                               placeholder="Dirección o nombre de la Casa">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="cobertura" class="form-label">Cobertura/Mentor</label>
                    <input type="text" class="form-control" id="cobertura" name="carrera[cobertura]"
                           value="<?= $esEdicion && isset($miembro['carrera']['cobertura']) ? htmlspecialchars($miembro['carrera']['cobertura']) : '' ?>"
                           placeholder="Nombre del líder que le da cobertura">
                </div>

                <div class="mb-3">
                    <label for="recorrido_espiritual" class="form-label">Recorrido Espiritual</label>
                    <textarea class="form-control" id="recorrido_espiritual_carrera" name="carrera[recorrido_espiritual]" rows="3"><?= $esEdicion && isset($miembro['carrera']['recorrido_espiritual']) ? htmlspecialchars($miembro['carrera']['recorrido_espiritual']) : '' ?></textarea>
                    <small class="form-text text-muted">Experiencias significativas en su camino de fe, testimonios, etc.</small>
                </div>

                <div class="mb-3">
                    <label for="anotaciones" class="form-label">Anotaciones Pastorales</label>
                    <textarea class="form-control" id="anotaciones" name="carrera[anotaciones]" rows="3"><?= $esEdicion && isset($miembro['carrera']['anotaciones']) ? htmlspecialchars($miembro['carrera']['anotaciones']) : '' ?></textarea>
                    <small class="form-text text-muted">Observaciones pastorales, necesidades de consejería, etc.</small>
                </div>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Activar las pestañas de Bootstrap
    var triggerTabList = [].slice.call(document.querySelectorAll('#miembroTabs a'))
    triggerTabList.forEach(function (triggerEl) {
        var tabTrigger = new bootstrap.Tab(triggerEl)
        triggerEl.addEventListener('click', function (event) {
            event.preventDefault()
            tabTrigger.show()
        })
    });
    
    // Validación básica del formulario
    const form = document.querySelector('form');
    form.addEventListener('submit', function(event) {
        const requiredFields = form.querySelectorAll('[required]');
        let hasErrors = false;
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                hasErrors = true;
            } else {
                field.classList.remove('is-invalid');
            }
        });
        
        if (hasErrors) {
            event.preventDefault();
            alert('Por favor complete todos los campos obligatorios.');
            
            // Activar la primera pestaña con error
            const firstInvalidField = form.querySelector('.is-invalid');
            if (firstInvalidField) {
                const tabPane = firstInvalidField.closest('.tab-pane');
                const tabId = tabPane.id;
                document.querySelector(`a[href="#${tabId}"]`).click();
            }
        }
    });
    
    // Manejar visibilidad del campo de alergias
    const alergiasCheck = document.getElementById('alergias_check');
    const alergiasContainer = document.getElementById('alergias_container');
    
    if (alergiasCheck && alergiasContainer) {
        // Si hay datos en el campo de alergias, marcar el checkbox
        const alergiasText = document.getElementById('alergias');
        if (alergiasText && alergiasText.value.trim() !== '') {
            alergiasCheck.checked = true;
            alergiasContainer.style.display = 'block';
        }
        
        alergiasCheck.addEventListener('change', function() {
            alergiasContainer.style.display = this.checked ? 'block' : 'none';
        });
    }
});
</script>