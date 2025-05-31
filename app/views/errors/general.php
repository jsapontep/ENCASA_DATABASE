<?php ?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-danger">
                <div class="card-header bg-danger text-white">
                    <h3>Error <?= $code ?></h3>
                </div>
                <div class="card-body">
                    <p class="card-text"><?= $message ?: 'Ha ocurrido un error al procesar tu solicitud.' ?></p>
                    <a href="<?= url('') ?>" class="btn btn-primary">Volver al inicio</a>
                </div>
            </div>
        </div>
    </div>
</div>