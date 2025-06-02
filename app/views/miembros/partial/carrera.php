
<div class="tab-pane fade" id="espiritual" role="tabpanel">
    <div class="card shadow-sm">
        <div class="card-body">
            <!-- Formación espiritual -->
            <h5 class="card-title">Formación y Crecimiento Espiritual</h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="fecha_conversion" class="form-label">Fecha de Conversión</label>
                    <input type="date" class="form-control" id="fecha_conversion" name="carrera[fecha_conversion]" value="<?= isset($carrera['fecha_conversion']) ? $carrera['fecha_conversion'] : '' ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="fecha_bautismo" class="form-label">Fecha de Bautismo</label>
                    <input type="date" class="form-control" id="fecha_bautismo" name="carrera[fecha_bautismo]" value="<?= isset($carrera['fecha_bautismo']) ? $carrera['fecha_bautismo'] : '' ?>">
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="iglesia_anterior" class="form-label">Iglesia Anterior</label>
                    <input type="text" class="form-control" id="iglesia_anterior" name="carrera[iglesia_anterior]" value="<?= isset($carrera['iglesia_anterior']) ? $carrera['iglesia_anterior'] : '' ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="tiempo_congregando" class="form-label">Tiempo Congregando</label>
                    <select class="form-select" id="tiempo_congregando" name="carrera[tiempo_congregando]">
                        <option value="">-- Seleccione --</option>
                        <option value="Menos de 1 mes" <?= isset($carrera['tiempo_congregando']) && $carrera['tiempo_congregando'] == 'Menos de 1 mes' ? 'selected' : '' ?>>Menos de 1 mes</option>
                        <option value="1-3 meses" <?= isset($carrera['tiempo_congregando']) && $carrera['tiempo_congregando'] == '1-3 meses' ? 'selected' : '' ?>>1-3 meses</option>
                        <option value="4-6 meses" <?= isset($carrera['tiempo_congregando']) && $carrera['tiempo_congregando'] == '4-6 meses' ? 'selected' : '' ?>>4-6 meses</option>
                        <option value="7-12 meses" <?= isset($carrera['tiempo_congregando']) && $carrera['tiempo_congregando'] == '7-12 meses' ? 'selected' : '' ?>>7-12 meses</option>
                        <option value="1-2 años" <?= isset($carrera['tiempo_congregando']) && $carrera['tiempo_congregando'] == '1-2 años' ? 'selected' : '' ?>>1-2 años</option>
                        <option value="Más de 2 años" <?= isset($carrera['tiempo_congregando']) && $carrera['tiempo_congregando'] == 'Más de 2 años' ? 'selected' : '' ?>>Más de 2 años</option>
                    </select>
                </div>
            </div>
            
            <!-- Cursos y formación -->
            <h5 class="card-title mt-3">Cursos y Formación</h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label d-block">Encuentro</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="carrera[encuentro]" id="encuentro_si" value="1" <?= isset($carrera['encuentro']) && $carrera['encuentro'] == '1' ? 'checked' : '' ?>>
                        <label class="form-check-label" for="encuentro_si">Sí</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="carrera[encuentro]" id="encuentro_no" value="0" <?= isset($carrera['encuentro']) && $carrera['encuentro'] == '0' ? 'checked' : '' ?>>
                        <label class="form-check-label" for="encuentro_no">No</label>
                    </div>
                    <?php if(isset($carrera['fecha_encuentro']) && $carrera['fecha_encuentro']): ?>
                    <div class="form-text">Fecha: <?= date('d/m/Y', strtotime($carrera['fecha_encuentro'])) ?></div>
                    <?php endif; ?>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label d-block">Curso de Liderazgo</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="carrera[curso_liderazgo]" id="liderazgo_si" value="1" <?= isset($carrera['curso_liderazgo']) && $carrera['curso_liderazgo'] == '1' ? 'checked' : '' ?>>
                        <label class="form-check-label" for="liderazgo_si">Sí</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="carrera[curso_liderazgo]" id="liderazgo_no" value="0" <?= isset($carrera['curso_liderazgo']) && $carrera['curso_liderazgo'] == '0' ? 'checked' : '' ?>>
                        <label class="form-check-label" for="liderazgo_no">No</label>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label d-block">Escuela de Ministerio</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="carrera[escuela_ministerio]" id="ministerio_si" value="1" <?= isset($carrera['escuela_ministerio']) && $carrera['escuela_ministerio'] == '1' ? 'checked' : '' ?>>
                        <label class="form-check-label" for="ministerio_si">Sí</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="carrera[escuela_ministerio]" id="ministerio_no" value="0" <?= isset($carrera['escuela_ministerio']) && $carrera['escuela_ministerio'] == '0' ? 'checked' : '' ?>>
                        <label class="form-check-label" for="ministerio_no">No</label>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label d-block">Pre-Encuentro</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="carrera[pre_encuentro]" id="pre_encuentro_si" value="1" <?= isset($carrera['pre_encuentro']) && $carrera['pre_encuentro'] == '1' ? 'checked' : '' ?>>
                        <label class="form-check-label" for="pre_encuentro_si">Sí</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="carrera[pre_encuentro]" id="pre_encuentro_no" value="0" <?= isset($carrera['pre_encuentro']) && $carrera['pre_encuentro'] == '0' ? 'checked' : '' ?>>
                        <label class="form-check-label" for="pre_encuentro_no">No</label>
                    </div>
                </div>
            </div>
            
            <!-- Ministerios y servicio -->
            <h5 class="card-title mt-3">Ministerios y Servicio</h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="ministerio" class="form-label">Ministerio Actual</label>
                    <select class="form-select" id="ministerio" name="carrera[ministerio]">
                        <option value="">-- Seleccione --</option>
                        <option value="Alabanza" <?= isset($carrera['ministerio']) && $carrera['ministerio'] == 'Alabanza' ? 'selected' : '' ?>>Alabanza</option>
                        <option value="Ujieres" <?= isset($carrera['ministerio']) && $carrera['ministerio'] == 'Ujieres' ? 'selected' : '' ?>>Ujieres</option>
                        <option value="Niños" <?= isset($carrera['ministerio']) && $carrera['ministerio'] == 'Niños' ? 'selected' : '' ?>>Niños</option>
                        <option value="Jóvenes" <?= isset($carrera['ministerio']) && $carrera['ministerio'] == 'Jóvenes' ? 'selected' : '' ?>>Jóvenes</option>
                        <option value="Mujeres" <?= isset($carrera['ministerio']) && $carrera['ministerio'] == 'Mujeres' ? 'selected' : '' ?>>Mujeres</option>
                        <option value="Hombres" <?= isset($carrera['ministerio']) && $carrera['ministerio'] == 'Hombres' ? 'selected' : '' ?>>Hombres</option>
                        <option value="Matrimonios" <?= isset($carrera['ministerio']) && $carrera['ministerio'] == 'Matrimonios' ? 'selected' : '' ?>>Matrimonios</option>
                        <option value="Misiones" <?= isset($carrera['ministerio']) && $carrera['ministerio'] == 'Misiones' ? 'selected' : '' ?>>Misiones</option>
                        <option value="Otro" <?= isset($carrera['ministerio']) && $carrera['ministerio'] == 'Otro' ? 'selected' : '' ?>>Otro</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="lider_de" class="form-label">Líder de</label>
                    <input type="text" class="form-control" id="lider_de" name="carrera[lider_de]" value="<?= isset($carrera['lider_de']) ? $carrera['lider_de'] : '' ?>">
                    <div class="form-text">Grupo o área que lidera</div>
                </div>
            </div>
            
            <!-- Dones y llamado -->
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="dones_espirituales" class="form-label">Dones Espirituales</label>
                    <textarea class="form-control" id="dones_espirituales" name="carrera[dones_espirituales]" rows="2"><?= isset($carrera['dones_espirituales']) ? $carrera['dones_espirituales'] : '' ?></textarea>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="notas_espirituales" class="form-label">Notas Adicionales</label>
                    <textarea class="form-control" id="notas_espirituales" name="carrera[notas]" rows="3"><?= isset($carrera['notas']) ? $carrera['notas'] : '' ?></textarea>
                    <div class="form-text">Información relevante sobre su crecimiento y desarrollo espiritual</div>
                </div>
            </div>
        </div>
    </div>
</div>