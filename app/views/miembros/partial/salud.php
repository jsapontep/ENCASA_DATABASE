
<div class="tab-pane fade" id="salud" role="tabpanel">
    <div class="card shadow-sm">
        <div class="card-body">
            <!-- Información médica básica -->
            <h5 class="card-title">Información Médica Básica</h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="grupo_sanguineo" class="form-label">Grupo Sanguíneo</label>
                    <select class="form-select" id="grupo_sanguineo" name="salud[grupo_sanguineo]">
                        <option value="">-- Seleccione --</option>
                        <option value="A+" <?= isset($salud['grupo_sanguineo']) && $salud['grupo_sanguineo'] == 'A+' ? 'selected' : '' ?>>A+</option>
                        <option value="A-" <?= isset($salud['grupo_sanguineo']) && $salud['grupo_sanguineo'] == 'A-' ? 'selected' : '' ?>>A-</option>
                        <option value="B+" <?= isset($salud['grupo_sanguineo']) && $salud['grupo_sanguineo'] == 'B+' ? 'selected' : '' ?>>B+</option>
                        <option value="B-" <?= isset($salud['grupo_sanguineo']) && $salud['grupo_sanguineo'] == 'B-' ? 'selected' : '' ?>>B-</option>
                        <option value="AB+" <?= isset($salud['grupo_sanguineo']) && $salud['grupo_sanguineo'] == 'AB+' ? 'selected' : '' ?>>AB+</option>
                        <option value="AB-" <?= isset($salud['grupo_sanguineo']) && $salud['grupo_sanguineo'] == 'AB-' ? 'selected' : '' ?>>AB-</option>
                        <option value="O+" <?= isset($salud['grupo_sanguineo']) && $salud['grupo_sanguineo'] == 'O+' ? 'selected' : '' ?>>O+</option>
                        <option value="O-" <?= isset($salud['grupo_sanguineo']) && $salud['grupo_sanguineo'] == 'O-' ? 'selected' : '' ?>>O-</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="eps" class="form-label">EPS / Seguro Médico</label>
                    <input type="text" class="form-control" id="eps" name="salud[eps]" value="<?= isset($salud['eps']) ? $salud['eps'] : '' ?>">
                </div>
            </div>
            
            <!-- Condiciones médicas -->
            <h5 class="card-title mt-3">Condiciones Médicas</h5>
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="condiciones_medicas" class="form-label">Condiciones Médicas Relevantes</label>
                    <textarea class="form-control" id="condiciones_medicas" name="salud[condiciones_medicas]" rows="2"><?= isset($salud['condiciones_medicas']) ? $salud['condiciones_medicas'] : '' ?></textarea>
                    <div class="form-text">Enfermedades, alergias, condiciones crónicas, etc.</div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="medicamentos" class="form-label">Medicamentos Actuales</label>
                    <textarea class="form-control" id="medicamentos" name="salud[medicamentos]" rows="2"><?= isset($salud['medicamentos']) ? $salud['medicamentos'] : '' ?></textarea>
                </div>
            </div>
            
            <!-- Contactos de emergencia -->
            <h5 class="card-title mt-3">Contactos de Emergencia</h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="contacto_emergencia_nombre" class="form-label">Nombre Completo</label>
                    <input type="text" class="form-control" id="contacto_emergencia_nombre" name="salud[contacto_nombre]" value="<?= isset($salud['contacto_nombre']) ? $salud['contacto_nombre'] : '' ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="contacto_emergencia_telefono" class="form-label">Teléfono</label>
                    <input type="tel" class="form-control" id="contacto_emergencia_telefono" name="salud[contacto_telefono]" value="<?= isset($salud['contacto_telefono']) ? $salud['contacto_telefono'] : '' ?>">
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="contacto_emergencia_relacion" class="form-label">Relación/Parentesco</label>
                    <input type="text" class="form-control" id="contacto_emergencia_relacion" name="salud[contacto_relacion]" value="<?= isset($salud['contacto_relacion']) ? $salud['contacto_relacion'] : '' ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="contacto_emergencia_direccion" class="form-label">Dirección</label>
                    <input type="text" class="form-control" id="contacto_emergencia_direccion" name="salud[contacto_direccion]" value="<?= isset($salud['contacto_direccion']) ? $salud['contacto_direccion'] : '' ?>">
                </div>
            </div>
        </div>
    </div>
</div>