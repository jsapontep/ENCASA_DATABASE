<div class="tab-pane fade" id="tallas" role="tabpanel">
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title">Tallas de Ropa</h5>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="talla_camisa" class="form-label">Talla Camisa</label>
                    <select class="form-select" id="talla_camisa" name="tallas[talla_camisa]">
                        <option value="">Seleccione...</option>
                        <option value="XS" <?= (isset($tallas['talla_camisa']) && $tallas['talla_camisa'] == 'XS') ? 'selected' : '' ?>>XS</option>
                        <option value="S" <?= (isset($tallas['talla_camisa']) && $tallas['talla_camisa'] == 'S') ? 'selected' : '' ?>>S</option>
                        <option value="M" <?= (isset($tallas['talla_camisa']) && $tallas['talla_camisa'] == 'M') ? 'selected' : '' ?>>M</option>
                        <option value="L" <?= (isset($tallas['talla_camisa']) && $tallas['talla_camisa'] == 'L') ? 'selected' : '' ?>>L</option>
                        <option value="XL" <?= (isset($tallas['talla_camisa']) && $tallas['talla_camisa'] == 'XL') ? 'selected' : '' ?>>XL</option>
                        <option value="XXL" <?= (isset($tallas['talla_camisa']) && $tallas['talla_camisa'] == 'XXL') ? 'selected' : '' ?>>XXL</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="talla_camiseta" class="form-label">Talla de Camiseta</label>
                    <select class="form-select" id="talla_camiseta" name="tallas[talla_camiseta]">
                        <option value="">-- Seleccione --</option>
                        <option value="XS" <?= isset($tallas['talla_camiseta']) && $tallas['talla_camiseta'] == 'XS' ? 'selected' : '' ?>>XS</option>
                        <option value="S" <?= isset($tallas['talla_camiseta']) && $tallas['talla_camiseta'] == 'S' ? 'selected' : '' ?>>S</option>
                        <option value="M" <?= isset($tallas['talla_camiseta']) && $tallas['talla_camiseta'] == 'M' ? 'selected' : '' ?>>M</option>
                        <option value="L" <?= isset($tallas['talla_camiseta']) && $tallas['talla_camiseta'] == 'L' ? 'selected' : '' ?>>L</option>
                        <option value="XL" <?= isset($tallas['talla_camiseta']) && $tallas['talla_camiseta'] == 'XL' ? 'selected' : '' ?>>XL</option>
                        <option value="XXL" <?= isset($tallas['talla_camiseta']) && $tallas['talla_camiseta'] == 'XXL' ? 'selected' : '' ?>>XXL</option>
                        <option value="XXXL" <?= isset($tallas['talla_camiseta']) && $tallas['talla_camiseta'] == 'XXXL' ? 'selected' : '' ?>>XXXL</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="talla_pantalon" class="form-label">Talla de Pantalón</label>
                    <select class="form-select" id="talla_pantalon" name="tallas[talla_pantalon]">
                        <option value="">-- Seleccione --</option>
                        <option value="28" <?= isset($tallas['talla_pantalon']) && $tallas['talla_pantalon'] == '28' ? 'selected' : '' ?>>28</option>
                        <option value="30" <?= isset($tallas['talla_pantalon']) && $tallas['talla_pantalon'] == '30' ? 'selected' : '' ?>>30</option>
                        <option value="32" <?= isset($tallas['talla_pantalon']) && $tallas['talla_pantalon'] == '32' ? 'selected' : '' ?>>32</option>
                        <option value="34" <?= isset($tallas['talla_pantalon']) && $tallas['talla_pantalon'] == '34' ? 'selected' : '' ?>>34</option>
                        <option value="36" <?= isset($tallas['talla_pantalon']) && $tallas['talla_pantalon'] == '36' ? 'selected' : '' ?>>36</option>
                        <option value="38" <?= isset($tallas['talla_pantalon']) && $tallas['talla_pantalon'] == '38' ? 'selected' : '' ?>>38</option>
                        <option value="40" <?= isset($tallas['talla_pantalon']) && $tallas['talla_pantalon'] == '40' ? 'selected' : '' ?>>40</option>
                        <option value="42" <?= isset($tallas['talla_pantalon']) && $tallas['talla_pantalon'] == '42' ? 'selected' : '' ?>>42</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="talla_zapatos" class="form-label">Talla de Zapatos</label>
                    <select class="form-select" id="talla_zapatos" name="tallas[talla_zapatos]">
                        <option value="">-- Seleccione --</option>
                        <?php for($i=34; $i<=46; $i++): ?>
                            <option value="<?= $i ?>" <?= isset($tallas['talla_zapatos']) && $tallas['talla_zapatos'] == $i ? 'selected' : '' ?>><?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>

