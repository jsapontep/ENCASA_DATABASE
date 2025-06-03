<div class="tab-pane fade" id="estudios" role="tabpanel">
    <div class="card shadow-sm">
        <div class="card-body">
            <!-- Formación académica -->
            <h5 class="card-title">Formación Académica</h5>
            
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="estudios_nivel_estudios" class="form-label">Nivel Educativo</label>
                    <select class="form-select" id="estudios_nivel_estudios" name="estudios[nivel_estudios]">
                        <option value="">Seleccione...</option>
                        <option value="Primaria" <?= (isset($estudios['nivel_estudios']) && $estudios['nivel_estudios'] == 'Primaria') ? 'selected' : '' ?>>Primaria</option>
                        <option value="Secundaria" <?= (isset($estudios['nivel_estudios']) && $estudios['nivel_estudios'] == 'Secundaria') ? 'selected' : '' ?>>Secundaria</option>
                        <option value="Técnico" <?= (isset($estudios['nivel_estudios']) && $estudios['nivel_estudios'] == 'Técnico') ? 'selected' : '' ?>>Técnico</option>
                        <option value="Tecnólogo" <?= (isset($estudios['nivel_estudios']) && $estudios['nivel_estudios'] == 'Tecnólogo') ? 'selected' : '' ?>>Tecnólogo</option>
                        <option value="Universitario" <?= (isset($estudios['nivel_estudios']) && $estudios['nivel_estudios'] == 'Universitario') ? 'selected' : '' ?>>Universitario</option>
                        <option value="Postgrado" <?= (isset($estudios['nivel_estudios']) && $estudios['nivel_estudios'] == 'Postgrado') ? 'selected' : '' ?>>Postgrado</option>
                    </select>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="institucion_educativa_select" class="form-label">Institución Educativa</label>
                    <select class="form-select" id="institucion_educativa_select" name="estudios[institucion_educativa_select]" disabled>
                        <option value="">-- Seleccione primero un nivel educativo --</option>
                    </select>
                    
                    <div id="institucion_personalizada_container" style="display:none; margin-top:10px;">
                        <input type="text" class="form-control" id="institucion_personalizada" 
                               placeholder="Nombre de la nueva institución" name="estudios[institucion_personalizada]">
                        <div class="form-text">La nueva institución será guardada en la base de datos</div>
                    </div>
                    
                    <!-- Campo oculto para almacenar la institución final (sea seleccionada o personalizada) -->
                    <input type="hidden" id="institucion_educativa" name="estudios[institucion_educativa]" 
                           value="<?= isset($estudios['institucion_educativa']) ? $estudios['institucion_educativa'] : '' ?>">
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="profesion_select" class="form-label">Profesión/Carrera</label>
                    <select class="form-select" id="profesion_select" name="estudios[profesion_select]" disabled>
                        <option value="">-- Seleccione primero una institución --</option>
                    </select>
                    
                    <div id="profesion_personalizada_container" style="display:none; margin-top:10px;">
                        <input type="text" class="form-control" id="profesion_personalizada" 
                               placeholder="Nombre de la nueva profesión" name="estudios[profesion_personalizada]">
                        <div class="form-text">La nueva profesión será guardada en la base de datos</div>
                    </div>
                    
                    <!-- Campo oculto para almacenar la profesión final (sea seleccionada o personalizada) -->
                    <input type="hidden" id="profesion" name="estudios[profesion]" 
                           value="<?= isset($estudios['profesion']) ? $estudios['profesion'] : '' ?>">
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="otros_estudios" class="form-label">Otros Estudios</label>
                    <textarea class="form-control" id="otros_estudios" name="estudios[otros_estudios]" rows="3"><?= isset($estudios['otros_estudios']) ? $estudios['otros_estudios'] : '' ?></textarea>
                    <div class="form-text">Incluya cursos adicionales, certificaciones, idiomas u otro tipo de formación complementaria</div>
                </div>
            </div>
            
            <!-- Información laboral -->
            <h5 class="card-title mt-3">Información Laboral</h5>
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="empresa" class="form-label">Empresa o Lugar de Trabajo</label>
                    <input type="text" class="form-control" id="empresa" name="estudios[empresa]" value="<?= isset($estudios['empresa']) ? $estudios['empresa'] : '' ?>">
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="direccion_empresa" class="form-label">Dirección de la Empresa</label>
                    <input type="text" class="form-control" id="direccion_empresa" name="estudios[direccion_empresa]" value="<?= isset($estudios['direccion_empresa']) ? $estudios['direccion_empresa'] : '' ?>">
                    <div class="form-text">Indique la dirección completa del lugar donde trabaja actualmente</div>
                </div>
            </div>
            
            <!-- Emprendimientos -->
            <h5 class="card-title mt-3">Emprendimientos</h5>
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="emprendimientos" class="form-label">Proyectos o Emprendimientos</label>
                    <textarea class="form-control" id="emprendimientos" name="estudios[emprendimientos]" rows="3"><?= isset($estudios['emprendimientos']) ? $estudios['emprendimientos'] : '' ?></textarea>
                    <div class="form-text">Describa brevemente proyectos personales, emprendimientos o negocios propios</div>
                </div>
            </div>
        </div>
    </div>
</div>




<script src="<?= url('assets/js/education-data.js') ?>"></script>
<script src="<?= url('assets/js/formulario-estudios.js') ?>"></script>