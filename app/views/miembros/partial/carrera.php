<div class="tab-pane fade" id="carrera" role="tabpanel">
    <div class="card shadow-sm">
        <div class="card-body">
            <!-- Formación espiritual -->
            <h5 class="card-title">Carrera Bíblica y Formación</h5>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="carrera_biblica" class="form-label">Carrera Bíblica</label>
                    <select class="form-select" id="carrera_biblica" name="carrera[carrera_biblica]">
                        <option value="">-- Seleccione --</option>
                        <option value="Nuevo Creyente" <?= isset($carrera['carrera_biblica']) && $carrera['carrera_biblica'] == 'Nuevo Creyente' ? 'selected' : '' ?>>Nuevo Creyente</option>
                        <option value="Consolidación" <?= isset($carrera['carrera_biblica']) && $carrera['carrera_biblica'] == 'Consolidación' ? 'selected' : '' ?>>Consolidación</option>
                        <option value="Discipulado Básico" <?= isset($carrera['carrera_biblica']) && $carrera['carrera_biblica'] == 'Discipulado Básico' ? 'selected' : '' ?>>Discipulado Básico</option>
                        <option value="Discipulado Avanzado" <?= isset($carrera['carrera_biblica']) && $carrera['carrera_biblica'] == 'Discipulado Avanzado' ? 'selected' : '' ?>>Discipulado Avanzado</option>
                        <option value="Formación Ministerial" <?= isset($carrera['carrera_biblica']) && $carrera['carrera_biblica'] == 'Formación Ministerial' ? 'selected' : '' ?>>Formación Ministerial</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="miembro_de" class="form-label">Miembro de</label>
                    <select class="form-select" id="miembro_de" name="carrera[miembro_de]">
                        <option value="">-- Seleccione --</option>
                        <option value="Congregación" <?= isset($carrera['miembro_de']) && $carrera['miembro_de'] == 'Congregación' ? 'selected' : '' ?>>Congregación</option>
                        <option value="Grupo de Crecimiento" <?= isset($carrera['miembro_de']) && $carrera['miembro_de'] == 'Grupo de Crecimiento' ? 'selected' : '' ?>>Grupo de Crecimiento</option>
                        <option value="Equipo de Servicio" <?= isset($carrera['miembro_de']) && $carrera['miembro_de'] == 'Equipo de Servicio' ? 'selected' : '' ?>>Equipo de Servicio</option>
                        <option value="Liderazgo" <?= isset($carrera['miembro_de']) && $carrera['miembro_de'] == 'Liderazgo' ? 'selected' : '' ?>>Liderazgo</option>
                    </select>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="casa_de_palabra_y_vida" class="form-label">Casa de Palabra y Vida</label>
                    <input type="text" class="form-control" id="casa_de_palabra_y_vida" name="carrera[casa_de_palabra_y_vida]" value="<?= isset($carrera['casa_de_palabra_y_vida']) ? $carrera['casa_de_palabra_y_vida'] : '' ?>">
                    <div class="form-text">Nombre de la casa o grupo al que pertenece</div>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="cobertura" class="form-label">Cobertura</label>
                    <input type="text" class="form-control" id="cobertura" name="carrera[cobertura]" value="<?= isset($carrera['cobertura']) ? $carrera['cobertura'] : '' ?>">
                    <div class="form-text">Líder espiritual que brinda cobertura</div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="estado" class="form-label">Estado</label>
                    <select class="form-select" id="estado" name="carrera[estado]">
                        <option value="">-- Seleccione --</option>
                        <option value="Activo" <?= isset($carrera['estado']) && $carrera['estado'] == 'Activo' ? 'selected' : '' ?>>Activo</option>
                        <option value="Inactivo" <?= isset($carrera['estado']) && $carrera['estado'] == 'Inactivo' ? 'selected' : '' ?>>Inactivo</option>
                        <option value="En Proceso" <?= isset($carrera['estado']) && $carrera['estado'] == 'En Proceso' ? 'selected' : '' ?>>En Proceso</option>
                        <option value="Visitante" <?= isset($carrera['estado']) && $carrera['estado'] == 'Visitante' ? 'selected' : '' ?>>Visitante</option>
                    </select>
                </div>
            </div>
            
            <!-- Anotaciones y recorrido espiritual -->
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="anotaciones" class="form-label">Anotaciones</label>
                    <textarea class="form-control" id="anotaciones" name="carrera[anotaciones]" rows="2"><?= isset($carrera['anotaciones']) ? $carrera['anotaciones'] : '' ?></textarea>
                    <div class="form-text">Notas breves sobre su participación y desarrollo</div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="recorrido_espiritual" class="form-label">Recorrido Espiritual</label>
                    <textarea class="form-control" id="recorrido_espiritual" name="carrera[recorrido_espiritual]" rows="3"><?= isset($carrera['recorrido_espiritual']) ? $carrera['recorrido_espiritual'] : '' ?></textarea>
                    <div class="form-text">Historia de fe y crecimiento espiritual del miembro</div>
                </div>
            </div>
        </div>
    </div>
</div>

