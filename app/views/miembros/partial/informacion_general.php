<div class="tab-pane fade show active" id="datos" role="tabpanel">
    <div class="card shadow-sm">
        <div class="card-body">
            <!-- Foto del miembro (ahora al principio) -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <label for="foto" class="form-label">Foto</label>
                    <div class="input-group">
                        <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
                        <label class="input-group-text" for="foto">Seleccionar</label>
                    </div>
                    <?php if($esEdicion && !empty($miembro['foto'])): ?>
                    <div class="mt-2">
                        <div class="d-flex align-items-center">
                            <img src="<?= url('uploads/miembros/'.$miembro['foto']) ?>" alt="Foto de <?= htmlspecialchars($miembro['nombres']) ?>" class="img-thumbnail me-2" style="max-height: 100px;">
                            <div>
                                <p class="mb-1">Foto actual</p>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="eliminar_foto" name="eliminar_foto" value="1">
                                    <label class="form-check-label text-danger" for="eliminar_foto">
                                        Eliminar foto actual
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Nombres y apellidos -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nombres" class="form-label">Nombres <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="nombres" name="nombres" 
                           value="<?= $esEdicion ? $miembro['nombres'] : '' ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="apellidos" class="form-label">Apellidos <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="apellidos" name="apellidos" 
                           value="<?= $esEdicion ? $miembro['apellidos'] : '' ?>" required>
                </div>
            </div>

            <!-- Celular y fecha nacimiento -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="celular" class="form-label">Celular <span class="text-danger">*</span></label>
                    <input type="tel" class="form-control" id="celular" name="celular" 
                           value="<?= $esEdicion ? $miembro['celular'] : '' ?>" placeholder="+57..." required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                    <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" 
                           value="<?= $esEdicion ? $miembro['fecha_nacimiento'] : '' ?>">
                </div>
            </div>

            <!-- Localidad y barrio -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="localidad" class="form-label">Localidad</label>
                    <select class="form-select" id="localidad" name="localidad" data-valor="<?= $esEdicion ? htmlspecialchars($miembro['localidad'] ?? '', ENT_QUOTES) : '' ?>">
                        <option value="">-- Seleccione una localidad --</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="barrio" class="form-label">Barrio</label>
                    <select class="form-select" id="barrio" name="barrio" data-valor="<?= $esEdicion ? htmlspecialchars($miembro['barrio'] ?? '', ENT_QUOTES) : '' ?>">
                        <option value="">-- Seleccione primero una localidad --</option>
                    </select>
                    
                    <div id="barrio_personalizado_container" style="display:none; margin-top:10px;">
                        <input type="text" class="form-control" id="barrio_personalizado" 
                               placeholder="Escriba el nombre del barrio">
                        <div class="form-text">Este barrio se guardará para futura referencia</div>
                    </div>
                </div>
            </div>

            <!-- Invitado por y conector/canal -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="invitado_por" class="form-label">Invitado Por</label>
                    <select class="form-select" id="invitado_por" name="invitado_por">
                        <option value="">-- Seleccione --</option>
                        <?php if (isset($miembros) && is_array($miembros)): ?>
                            <?php foreach ($miembros as $invitador): ?>
                                <option value="<?= $invitador['id'] ?>" <?= $esEdicion && isset($miembro['invitado_por']) && $miembro['invitado_por'] == $invitador['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($invitador['nombres'].' '.$invitador['apellidos']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="conector" class="form-label">Conector/Canal</label>
                    <select class="form-select" id="conector" name="conector">
                        <option value="">-- Seleccione --</option>
                        <option value="Redes Sociales" <?= $esEdicion && isset($miembro['conector']) && $miembro['conector'] == 'Redes Sociales' ? 'selected' : '' ?>>Redes Sociales</option>
                        <option value="Familiar" <?= $esEdicion && isset($miembro['conector']) && $miembro['conector'] == 'Familiar' ? 'selected' : '' ?>>Familiar</option>
                        <option value="Amigo" <?= $esEdicion && isset($miembro['conector']) && $miembro['conector'] == 'Amigo' ? 'selected' : '' ?>>Amigo</option>
                        <option value="Compañero de Trabajo" <?= $esEdicion && isset($miembro['conector']) && $miembro['conector'] == 'Compañero de Trabajo' ? 'selected' : '' ?>>Compañero de Trabajo</option>
                        <option value="Visita Espontánea" <?= $esEdicion && isset($miembro['conector']) && $miembro['conector'] == 'Visita Espontánea' ? 'selected' : '' ?>>Visita Espontánea</option>
                        <option value="Evento" <?= $esEdicion && isset($miembro['conector']) && $miembro['conector'] == 'Evento' ? 'selected' : '' ?>>Evento</option>
                        <option value="Volanteo" <?= $esEdicion && isset($miembro['conector']) && $miembro['conector'] == 'Volanteo' ? 'selected' : '' ?>>Volanteo</option>
                        <option value="Otro" <?= $esEdicion && isset($miembro['conector']) && !in_array($miembro['conector'], ['Redes Sociales', 'Familiar', 'Amigo', 'Compañero de Trabajo', 'Visita Espontánea', 'Evento', 'Volanteo']) ? 'selected' : '' ?>>Otro</option>
                    </select>
                </div>
            </div>

            <!-- Estado espiritual y fecha ingreso iglesia -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="estado_espiritual" class="form-label">Estado Espiritual</label>
                    <select class="form-select" id="estado_espiritual" name="estado_espiritual">
                        <option value="Nuevo" <?= (!$esEdicion || ($esEdicion && isset($miembro['estado_espiritual']) && $miembro['estado_espiritual'] == 'Nuevo')) ? 'selected' : '' ?>>Nuevo</option>
                        <option value="Visitante" <?= $esEdicion && isset($miembro['estado_espiritual']) && $miembro['estado_espiritual'] == 'Visitante' ? 'selected' : '' ?>>Visitante</option>
                        <option value="Simpatizante" <?= $esEdicion && isset($miembro['estado_espiritual']) && $miembro['estado_espiritual'] == 'Simpatizante' ? 'selected' : '' ?>>Simpatizante</option>
                        <option value="Discípulo" <?= $esEdicion && isset($miembro['estado_espiritual']) && $miembro['estado_espiritual'] == 'Discípulo' ? 'selected' : '' ?>>Discípulo</option>
                        <option value="Servidor" <?= $esEdicion && isset($miembro['estado_espiritual']) && $miembro['estado_espiritual'] == 'Servidor' ? 'selected' : '' ?>>Servidor</option>
                        <option value="Líder" <?= $esEdicion && isset($miembro['estado_espiritual']) && $miembro['estado_espiritual'] == 'Líder' ? 'selected' : '' ?>>Líder</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="fecha_ingreso_iglesia" class="form-label">Fecha de Ingreso a la Iglesia</label>
                    <input type="date" class="form-control" id="fecha_ingreso_iglesia" name="fecha_ingreso_iglesia" value="<?= $esEdicion && isset($miembro['fecha_ingreso_iglesia']) ? $miembro['fecha_ingreso_iglesia'] : '' ?>">
                </div>
            </div>

            <!-- Estado del miembro -->
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="estado_miembro" class="form-label">Estado del Miembro</label>
                    <select class="form-select" id="estado_miembro" name="estado_miembro">
                        <option value="Por Validar Estado" <?= (!$esEdicion || ($esEdicion && isset($miembro['estado_miembro']) && $miembro['estado_miembro'] == 'Por Validar Estado')) ? 'selected' : '' ?>>Por Validar Estado</option>
                        
                        <optgroup label="Contacto Inicial">
                            <option value="Primer contacto" <?= $esEdicion && isset($miembro['estado_miembro']) && $miembro['estado_miembro'] == 'Primer contacto' ? 'selected' : '' ?>>Primer contacto</option>
                            <option value="Conectado" <?= $esEdicion && isset($miembro['estado_miembro']) && $miembro['estado_miembro'] == 'Conectado' ? 'selected' : '' ?>>Conectado</option>
                            <option value="Primer intento" <?= $esEdicion && isset($miembro['estado_miembro']) && $miembro['estado_miembro'] == 'Primer intento' ? 'selected' : '' ?>>Primer intento</option>
                            <option value="Segundo intento" <?= $esEdicion && isset($miembro['estado_miembro']) && $miembro['estado_miembro'] == 'Segundo intento' ? 'selected' : '' ?>>Segundo intento</option>
                            <option value="Tercero intento" <?= $esEdicion && isset($miembro['estado_miembro']) && $miembro['estado_miembro'] == 'Tercero intento' ? 'selected' : '' ?>>Tercero intento</option>
                            <option value="Intento llamada telefónica" <?= $esEdicion && isset($miembro['estado_miembro']) && $miembro['estado_miembro'] == 'Intento llamada telefónica' ? 'selected' : '' ?>>Intento llamada telefónica</option>
                            <option value="Intento 2 llamada telefónica" <?= $esEdicion && isset($miembro['estado_miembro']) && $miembro['estado_miembro'] == 'Intento 2 llamada telefónica' ? 'selected' : '' ?>>Intento 2 llamada telefónica</option>
                            <option value="Intento 3 llamada telefónica" <?= $esEdicion && isset($miembro['estado_miembro']) && $miembro['estado_miembro'] == 'Intento 3 llamada telefónica' ? 'selected' : '' ?>>Intento 3 llamada telefónica</option>
                            <option value="No interesado" <?= $esEdicion && isset($miembro['estado_miembro']) && $miembro['estado_miembro'] == 'No interesado' ? 'selected' : '' ?>>No interesado</option>
                        </optgroup>
                        
                        <optgroup label="Desayunos">
                            <option value="No confirma desayuno" <?= $esEdicion && isset($miembro['estado_miembro']) && $miembro['estado_miembro'] == 'No confirma desayuno' ? 'selected' : '' ?>>No confirma desayuno</option>
                            <option value="Confirmado a Desayuno" <?= $esEdicion && isset($miembro['estado_miembro']) && $miembro['estado_miembro'] == 'Confirmado a Desayuno' ? 'selected' : '' ?>>Confirmado a Desayuno</option>
                            <option value="Desayuno Asistido" <?= $esEdicion && isset($miembro['estado_miembro']) && $miembro['estado_miembro'] == 'Desayuno Asistido' ? 'selected' : '' ?>>Desayuno Asistido</option>
                        </optgroup>
                        
                        <optgroup label="Miembros">
                            <option value="Miembro activo" <?= $esEdicion && isset($miembro['estado_miembro']) && $miembro['estado_miembro'] == 'Miembro activo' ? 'selected' : '' ?>>Miembro activo</option>
                            <option value="Miembro inactivo" <?= $esEdicion && isset($miembro['estado_miembro']) && $miembro['estado_miembro'] == 'Miembro inactivo' ? 'selected' : '' ?>>Miembro inactivo</option>
                            <option value="Miembro ausente" <?= $esEdicion && isset($miembro['estado_miembro']) && $miembro['estado_miembro'] == 'Miembro ausente' ? 'selected' : '' ?>>Miembro ausente</option>
                            <option value="Congregado sin desayuno" <?= $esEdicion && isset($miembro['estado_miembro']) && $miembro['estado_miembro'] == 'Congregado sin desayuno' ? 'selected' : '' ?>>Congregado sin desayuno</option>
                            <option value="Visitante" <?= $esEdicion && isset($miembro['estado_miembro']) && $miembro['estado_miembro'] == 'Visitante' ? 'selected' : '' ?>>Visitante</option>
                        </optgroup>
                        
                        <optgroup label="Líderes">
                            <option value="Líder Activo" <?= $esEdicion && isset($miembro['estado_miembro']) && $miembro['estado_miembro'] == 'Líder Activo' ? 'selected' : '' ?>>Líder Activo</option>
                            <option value="Líder inactivo" <?= $esEdicion && isset($miembro['estado_miembro']) && $miembro['estado_miembro'] == 'Líder inactivo' ? 'selected' : '' ?>>Líder inactivo</option>
                            <option value="Líder ausente" <?= $esEdicion && isset($miembro['estado_miembro']) && $miembro['estado_miembro'] == 'Líder ausente' ? 'selected' : '' ?>>Líder ausente</option>
                        </optgroup>
                        
                        <optgroup label="Reconexión">
                            <option value="Reconectado" <?= $esEdicion && isset($miembro['estado_miembro']) && $miembro['estado_miembro'] == 'Reconectado' ? 'selected' : '' ?>>Reconectado</option>
                            <option value="Intento de reconexión" <?= $esEdicion && isset($miembro['estado_miembro']) && $miembro['estado_miembro'] == 'Intento de reconexión' ? 'selected' : '' ?>>Intento de reconexión</option>
                            <option value="Etapa 1 reconexión (1 mes)" <?= $esEdicion && isset($miembro['estado_miembro']) && $miembro['estado_miembro'] == 'Etapa 1 reconexión (1 mes)' ? 'selected' : '' ?>>Etapa 1 reconexión (1 mes)</option>
                            <option value="Etapa 2 reconexión (3 mes)" <?= $esEdicion && isset($miembro['estado_miembro']) && $miembro['estado_miembro'] == 'Etapa 2 reconexión (3 mes)' ? 'selected' : '' ?>>Etapa 2 reconexión (3 mes)</option>
                            <option value="Etapa 3 reconexión final (6 mes)" <?= $esEdicion && isset($miembro['estado_miembro']) && $miembro['estado_miembro'] == 'Etapa 3 reconexión final (6 mes)' ? 'selected' : '' ?>>Etapa 3 reconexión final (6 mes)</option>
                        </optgroup>
                        
                        <optgroup label="Ministerios">
                            <option value="Vencedores Kids" <?= $esEdicion && isset($miembro['estado_miembro']) && $miembro['estado_miembro'] == 'Vencedores Kids' ? 'selected' : '' ?>>Vencedores Kids</option>
                            <option value="Legado" <?= $esEdicion && isset($miembro['estado_miembro']) && $miembro['estado_miembro'] == 'Legado' ? 'selected' : '' ?>>Legado</option>
                            <option value="Micro Legado" <?= $esEdicion && isset($miembro['estado_miembro']) && $miembro['estado_miembro'] == 'Micro Legado' ? 'selected' : '' ?>>Micro Legado</option>
                        </optgroup>
                        
                        <optgroup label="Otras">
                            <option value="Nulo" <?= $esEdicion && isset($miembro['estado_miembro']) && $miembro['estado_miembro'] == 'Nulo' ? 'selected' : '' ?>>Nulo</option>
                            <option value="Delegado a acompañante" <?= $esEdicion && isset($miembro['estado_miembro']) && $miembro['estado_miembro'] == 'Delegado a acompañante' ? 'selected' : '' ?>>Delegado a acompañante</option>
                            <option value="Datos no autorizados" <?= $esEdicion && isset($miembro['estado_miembro']) && $miembro['estado_miembro'] == 'Datos no autorizados' ? 'selected' : '' ?>>Datos no autorizados</option>
                            <option value="Datos incorrectos" <?= $esEdicion && isset($miembro['estado_miembro']) && $miembro['estado_miembro'] == 'Datos incorrectos' ? 'selected' : '' ?>>Datos incorrectos</option>
                        </optgroup>
                    </select>
                </div>
            </div>

            <!-- Recorrido espiritual -->
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="recorrido_espiritual" class="form-label">Recorrido Espiritual</label>
                    <textarea class="form-control" id="recorrido_espiritual" name="recorrido_espiritual" rows="4"><?= $esEdicion && isset($miembro['recorrido_espiritual']) ? $miembro['recorrido_espiritual'] : '' ?></textarea>
                    <div class="form-text">Describe el recorrido y experiencia espiritual de la persona</div>
                </div>
            </div>
            
            <?php if($esEdicion): ?>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="fecha_registro_sistema" class="form-label">Fecha de Registro en Sistema</label>
                    <input type="text" class="form-control" id="fecha_registro_sistema" 
                           value="<?= date('d/m/Y H:i', strtotime($miembro['fecha_registro_sistema'])) ?>" readonly>
                    <div class="form-text">Este campo se genera automáticamente</div>
                </div>
            </div>
            <?php endif; ?>

            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="habeas_data" name="habeas_data" 
                       value="1" <?= $esEdicion && isset($miembro['habeas_data']) && $miembro['habeas_data'] ? 'checked' : '' ?>>
                <label class="form-check-label" for="habeas_data">
                    Acepto tratamiento de datos personales según la política de privacidad
                </label>
            </div>
        </div>
    </div>
</div>

