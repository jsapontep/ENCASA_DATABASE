<?php ?>
<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card shadow">
        <div class="card-header bg-primary text-white">
          <h3 class="mb-0">Verificación de Inicio de Sesión</h3>
        </div>
        <div class="card-body">
          <?php if (isset($error)): ?>
            <div class="alert alert-danger">
              <?= $error ?>
            </div>
          <?php endif; ?>
          
          <p>Para proteger tu cuenta, hemos enviado un código de verificación al correo:</p>
          <p class="font-weight-bold"><?= htmlspecialchars($email) ?></p>
          
          <form action="<?= APP_URL ?>/auth/verify-login" method="post">
            <div class="mb-4">
              <label for="code" class="form-label">Código de verificación</label>
              <input type="text" class="form-control form-control-lg text-center" 
                     name="code" id="code" maxlength="6" 
                     style="font-size: 24px; letter-spacing: 8px;"
                     placeholder="------" required autofocus>
              <div class="form-text">Ingresa el código de 6 dígitos enviado a tu correo.</div>
            </div>
            
            <div class="d-grid gap-2">
              <button type="submit" class="btn btn-primary btn-lg">Verificar y Continuar</button>
            </div>
          </form>
          
          <div class="text-center mt-3">
            <p>¿No recibiste el código? <a href="<?= APP_URL ?>/auth/resend-login-code">Reenviar código</a></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  // Mejorar la experiencia de usuario para el campo de código
  document.getElementById('code').addEventListener('input', function(e) {
    // Permitir solo dígitos
    this.value = this.value.replace(/[^0-9]/g, '');
  });
</script>