<?php require_once APP_PATH . '/views/partials/header.php'; ?>

<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow">
                <div class="card-body p-4">
                    <h2 class="text-center mb-4">Recuperar Contraseña</h2>
                    
                    <?php if(isset($_SESSION['flash_message'])): ?>
                    <div class="alert alert-<?= $_SESSION['flash_type'] ?? 'info' ?> alert-dismissible fade show">
                        <?= $_SESSION['flash_message'] ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['flash_message']); unset($_SESSION['flash_type']); endif; ?>
                    
                    <p class="text-muted mb-4">Ingresa tu dirección de correo electrónico y te enviaremos un enlace para restablecer tu contraseña.</p>
                    
                    <form action="<?= APP_URL ?>/auth/reset-password-request" method="post">
                        <div class="mb-3">
                            <label for="email" class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control" id="email" name="email" required autocomplete="email">
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Enviar Enlace de Recuperación</button>
                        </div>
                    </form>
                    
                    <div class="text-center mt-3">
                        <a href="<?= APP_URL ?>/login">Volver al Inicio de Sesión</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_PATH . '/views/partials/footer.php'; ?>