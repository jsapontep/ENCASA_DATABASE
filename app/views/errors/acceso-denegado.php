<?php
 require_once APP_PATH . '/views/partials/header.php';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-danger shadow">
                <div class="card-header bg-danger text-white">
                    <h3 class="mb-0">Acceso Denegado</h3>
                </div>
                <div class="card-body text-center">
                    <i class="fas fa-exclamation-triangle text-danger fa-5x mb-4"></i>
                    <h4>No tienes permisos suficientes para acceder a esta página</h4>
                    <p class="text-muted">Por favor, contacta con el administrador si consideras que deberías tener acceso.</p>
                    
                    <div class="mt-4">
                        <a href="<?= APP_URL ?>/" class="btn btn-primary">
                            <i class="fas fa-home"></i> Volver al Inicio
                        </a>
                        <a href="<?= APP_URL ?>/logout" class="btn btn-outline-secondary ml-2">
                            <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_PATH . '/views/partials/footer.php'; ?>