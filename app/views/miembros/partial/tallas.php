
<div class="tab-pane fade" id="tallas" role="tabpanel">
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title">Tallas de Ropa</h5>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="talla_camisa" class="form-label">Talla de Camisa</label>
                    <select class="form-select" id="talla_camisa" name="tallas[camisa]">
                        <option value="">-- Seleccione --</option>
                        <option value="XS" <?= isset($tallas['camisa']) && $tallas['camisa'] == 'XS' ? 'selected' : '' ?>>XS</option>
                        <option value="S" <?= isset($tallas['camisa']) && $tallas['camisa'] == 'S' ? 'selected' : '' ?>>S</option>
                        <option value="M" <?= isset($tallas['camisa']) && $tallas['camisa'] == 'M' ? 'selected' : '' ?>>M</option>
                        <option value="L" <?= isset($tallas['camisa']) && $tallas['camisa'] == 'L' ? 'selected' : '' ?>>L</option>
                        <option value="XL" <?= isset($tallas['camisa']) && $tallas['camisa'] == 'XL' ? 'selected' : '' ?>>XL</option>
                        <option value="XXL" <?= isset($tallas['camisa']) && $tallas['camisa'] == 'XXL' ? 'selected' : '' ?>>XXL</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="talla_pantalon" class="form-label">Talla de Pantalón</label>
                    <select class="form-select" id="talla_pantalon" name="tallas[pantalon]">
                        <option value="">-- Seleccione --</option>
                        <option value="28" <?= isset($tallas['pantalon']) && $tallas['pantalon'] == '28' ? 'selected' : '' ?>>28</option>
                        <option value="30" <?= isset($tallas['pantalon']) && $tallas['pantalon'] == '30' ? 'selected' : '' ?>>30</option>
                        <option value="32" <?= isset($tallas['pantalon']) && $tallas['pantalon'] == '32' ? 'selected' : '' ?>>32</option>
                        <option value="34" <?= isset($tallas['pantalon']) && $tallas['pantalon'] == '34' ? 'selected' : '' ?>>34</option>
                        <option value="36" <?= isset($tallas['pantalon']) && $tallas['pantalon'] == '36' ? 'selected' : '' ?>>36</option>
                        <option value="38" <?= isset($tallas['pantalon']) && $tallas['pantalon'] == '38' ? 'selected' : '' ?>>38</option>
                        <option value="40" <?= isset($tallas['pantalon']) && $tallas['pantalon'] == '40' ? 'selected' : '' ?>>40</option>
                        <option value="42" <?= isset($tallas['pantalon']) && $tallas['pantalon'] == '42' ? 'selected' : '' ?>>42</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="talla_calzado" class="form-label">Talla de Calzado</label>
                    <select class="form-select" id="talla_calzado" name="tallas[calzado]">
                        <option value="">-- Seleccione --</option>
                        <?php for($i=34; $i<=46; $i++): ?>
                            <option value="<?= $i ?>" <?= isset($tallas['calzado']) && $tallas['calzado'] == $i ? 'selected' : '' ?>><?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="talla_chaqueta" class="form-label">Talla de Chaqueta/Abrigo</label>
                    <select class="form-select" id="talla_chaqueta" name="tallas[chaqueta]">
                        <option value="">-- Seleccione --</option>
                        <option value="XS" <?= isset($tallas['chaqueta']) && $tallas['chaqueta'] == 'XS' ? 'selected' : '' ?>>XS</option>
                        <option value="S" <?= isset($tallas['chaqueta']) && $tallas['chaqueta'] == 'S' ? 'selected' : '' ?>>S</option>
                        <option value="M" <?= isset($tallas['chaqueta']) && $tallas['chaqueta'] == 'M' ? 'selected' : '' ?>>M</option>
                        <option value="L" <?= isset($tallas['chaqueta']) && $tallas['chaqueta'] == 'L' ? 'selected' : '' ?>>L</option>
                        <option value="XL" <?= isset($tallas['chaqueta']) && $tallas['chaqueta'] == 'XL' ? 'selected' : '' ?>>XL</option>
                        <option value="XXL" <?= isset($tallas['chaqueta']) && $tallas['chaqueta'] == 'XXL' ? 'selected' : '' ?>>XXL</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="talla_guantes" class="form-label">Talla de Guantes</label>
                    <select class="form-select" id="talla_guantes" name="tallas[guantes]">
                        <option value="">-- Seleccione --</option>
                        <option value="S" <?= isset($tallas['guantes']) && $tallas['guantes'] == 'S' ? 'selected' : '' ?>>S</option>
                        <option value="M" <?= isset($tallas['guantes']) && $tallas['guantes'] == 'M' ? 'selected' : '' ?>>M</option>
                        <option value="L" <?= isset($tallas['guantes']) && $tallas['guantes'] == 'L' ? 'selected' : '' ?>>L</option>
                        <option value="XL" <?= isset($tallas['guantes']) && $tallas['guantes'] == 'XL' ? 'selected' : '' ?>>XL</option>
                    </select>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="notas_tallas" class="form-label">Notas Adicionales</label>
                    <textarea class="form-control" id="notas_tallas" name="tallas[notas]" rows="2"><?= isset($tallas['notas']) ? $tallas['notas'] : '' ?></textarea>
                    <div class="form-text">Información adicional sobre preferencias de vestimenta o medidas específicas</div>
                </div>
            </div>
        </div>
    </div>
</div>