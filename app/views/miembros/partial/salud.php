<div class="tab-pane fade" id="salud" role="tabpanel">
    <div class="card shadow-sm">
        <div class="card-body">
            <!-- Información médica básica -->
            <h5 class="card-title">Información Médica Básica</h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="rh" class="form-label">Grupo Sanguíneo</label>
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
                <div class="col-md-6 mb-3">
                    <label for="eps" class="form-label">EPS / Seguro Médico</label>
                    <input type="text" class="form-control" id="eps" name="salud[eps]" value="<?= isset($salud['eps']) ? $salud['eps'] : '' ?>">
                </div>
            </div>
            
            <!-- Contactos de emergencia -->
            <h5 class="card-title mt-3">Contactos de Emergencia</h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="acudiente1" class="form-label">Acudiente Principal</label>
                    <input type="text" class="form-control" id="acudiente1" name="salud[acudiente1]" value="<?= isset($salud['acudiente1']) ? $salud['acudiente1'] : '' ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="telefono1" class="form-label">Teléfono Principal</label>
                    <input type="tel" class="form-control" id="telefono1" name="salud[telefono1]" value="<?= isset($salud['telefono1']) ? $salud['telefono1'] : '' ?>">
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="acudiente2" class="form-label">Acudiente Secundario</label>
                    <input type="text" class="form-control" id="acudiente2" name="salud[acudiente2]" value="<?= isset($salud['acudiente2']) ? $salud['acudiente2'] : '' ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="telefono2" class="form-label">Teléfono Secundario</label>
                    <input type="tel" class="form-control" id="telefono2" name="salud[telefono2]" value="<?= isset($salud['telefono2']) ? $salud['telefono2'] : '' ?>">
                </div>
            </div>
        </div>
    </div>
</div>

