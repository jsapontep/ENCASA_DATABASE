<?php
// filepath: c:\xampp\htdocs\ENCASA_DATABASE\app\views\auth\login.php
?>
<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card shadow">
        <div class="card-header bg-primary text-white">
          <h2 class="my-2">Iniciar Sesión</h2>
        </div>
        <div class="card-body">
          <?php if (isset($_SESSION['flash_message'])): ?>
            <div class="alert alert-<?= $_SESSION['flash_type'] ?? 'info' ?>">
              <?= $_SESSION['flash_message'] ?>
              <?php if (isset($_SESSION['flash_action'])): ?>
                <div class="mt-2">
                  <?= $_SESSION['flash_action'] ?>
                </div>
              <?php endif; ?>
            </div>
            <?php 
              // Limpiar mensajes flash después de mostrarlos
              unset($_SESSION['flash_message']); 
              unset($_SESSION['flash_type']);
              unset($_SESSION['flash_action']);
            ?>
          <?php endif; ?>

          <?php if (isset($error)): ?>
            <div class="alert alert-danger">
              <?= $error ?>
              <?php if (isset($_SESSION['pending_verification'])): ?>
                <div class="mt-2">
                  <a href="<?= url('auth/resendCode') ?>" class="btn btn-sm btn-primary">Reenviar código</a>
                </div>
              <?php endif; ?>
            </div>
          <?php endif; ?>
          
          <form action="<?= url('auth/login', true) ?>" method="post" autocomplete="on">
            <!-- Asegurarnos de usar HTTPS -->
            <input type="hidden" name="secure_form" value="1">
            
            <div class="mb-3">
              <label for="email_or_username" class="form-label">Email o nombre de usuario</label>
              <input type="text" class="form-control" id="email_or_username" name="email_or_username" 
                     autocomplete="username" required autofocus>
            </div>
            
            <div class="mb-3">
              <label for="password" class="form-label">Contraseña</label>
              <input type="password" class="form-control" id="password" name="password" 
                     autocomplete="current-password" required>
            </div>
            
            <div class="d-grid gap-2">
              <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
            </div>
          </form>
          
          <div class="text-center mt-3">
            <a href="<?= url('registro') ?>">¿No tienes cuenta? Regístrate</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>