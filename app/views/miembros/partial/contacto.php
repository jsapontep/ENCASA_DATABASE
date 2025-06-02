
<div class="tab-pane fade" id="contacto" role="tabpanel">
    <div class="card shadow-sm">
        <div class="card-body">
            <!-- Documentos de Identidad -->
            <h5 class="card-title">Documentos de Identidad</h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="tipo_documento" class="form-label">Tipo de Documento</label>
                    <select class="form-select" id="tipo_documento" name="contacto[tipo_documento]">
                        <option value="">-- Seleccione --</option>
                        <option value="CC" <?= isset($contacto['tipo_documento']) && $contacto['tipo_documento'] == 'CC' ? 'selected' : '' ?>>Cédula de Ciudadanía</option>
                        <option value="TI" <?= isset($contacto['tipo_documento']) && $contacto['tipo_documento'] == 'TI' ? 'selected' : '' ?>>Tarjeta de Identidad</option>
                        <option value="CE" <?= isset($contacto['tipo_documento']) && $contacto['tipo_documento'] == 'CE' ? 'selected' : '' ?>>Cédula de Extranjería</option>
                        <option value="PP" <?= isset($contacto['tipo_documento']) && $contacto['tipo_documento'] == 'PP' ? 'selected' : '' ?>>Pasaporte</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="numero_documento" class="form-label">Número de Documento</label>
                    <input type="text" class="form-control" id="numero_documento" name="contacto[numero_documento]" value="<?= isset($contacto['numero_documento']) ? $contacto['numero_documento'] : '' ?>">
                </div>
            </div>
            
            <!-- Teléfono -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="telefono" class="form-label">Teléfono</label>
                    <input type="tel" class="form-control" id="telefono" name="contacto[telefono]" value="<?= isset($contacto['telefono']) ? $contacto['telefono'] : '' ?>">
                </div>
            </div>
            
            <!-- Ubicación geográfica -->
            <h5 class="card-title mt-3">Dirección y Ubicación</h5>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="pais" class="form-label">País</label>
                    <select class="form-select" id="pais" name="contacto[pais]" data-valor="<?= isset($contacto['pais']) ? htmlspecialchars($contacto['pais'], ENT_QUOTES) : '' ?>">
                        <option value="">-- Seleccione un país --</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="estado_departamento" class="form-label">Estado/Departamento</label>
                    <select class="form-select" id="estado_departamento" name="contacto[estado_departamento]" data-valor="<?= isset($contacto['estado_departamento']) ? htmlspecialchars($contacto['estado_departamento'], ENT_QUOTES) : '' ?>" disabled>
                        <option value="">-- Seleccione primero un país --</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="ciudad" class="form-label">Ciudad</label>
                    <select class="form-select" id="ciudad" name="contacto[ciudad]" data-valor="<?= isset($contacto['ciudad']) ? htmlspecialchars($contacto['ciudad'], ENT_QUOTES) : '' ?>" disabled>
                        <option value="">-- Seleccione primero un estado/departamento --</option>
                    </select>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="direccion" class="form-label">Dirección Completa</label>
                    <input type="text" class="form-control" id="direccion" name="contacto[direccion]" value="<?= isset($contacto['direccion']) ? $contacto['direccion'] : '' ?>">
                </div>
            </div>
            
            <!-- Correo electrónico -->
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="correo_electronico" class="form-label">Correo Electrónico</label>
                    <input type="email" class="form-control" id="correo_electronico" name="contacto[correo_electronico]" value="<?= isset($contacto['correo_electronico']) ? $contacto['correo_electronico'] : '' ?>">
                </div>
            </div>
            
            <!-- Redes sociales -->
            <h5 class="card-title mt-3">Redes Sociales</h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="instagram" class="form-label"><i class="fab fa-instagram text-danger"></i> Instagram</label>
                    <input type="text" class="form-control" id="instagram" name="contacto[instagram]" value="<?= isset($contacto['instagram']) ? $contacto['instagram'] : '' ?>" placeholder="@usuario">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="facebook" class="form-label"><i class="fab fa-facebook text-primary"></i> Facebook</label>
                    <input type="text" class="form-control" id="facebook" name="contacto[facebook]" value="<?= isset($contacto['facebook']) ? $contacto['facebook'] : '' ?>" placeholder="Usuario o URL">
                </div>
            </div>
            
            <!-- Notas -->
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="notas" class="form-label">Notas</label>
                    <textarea class="form-control" id="notas" name="contacto[notas]" rows="2"><?= isset($contacto['notas']) ? $contacto['notas'] : '' ?></textarea>
                    <div class="form-text">Información adicional de contacto</div>
                </div>
            </div>
            
            <!-- Familiares -->
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="familiares" class="form-label">Familiares</label>
                    <textarea class="form-control" id="familiares" name="contacto[familiares]" rows="3"><?= isset($contacto['familiares']) ? $contacto['familiares'] : '' ?></textarea>
                    <div class="form-text">Información sobre familiares y relaciones</div>
                </div>
            </div>
        </div>
    </div>
</div>