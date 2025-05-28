<?php
// filepath: c:\xampp\htdocs\ENCASA_DATABASE\app\views\auth\verify.php
?>
<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
      <div class="card shadow">
        <div class="card-header bg-primary text-white">
          <h2 class="my-2">Verificación de tu cuenta</h2>
        </div>
        <div class="card-body">
          <?php if (isset($error)): ?>
            <div class="alert alert-danger">
              <?= $error ?>
            </div>
          <?php endif; ?>
          
          <p>Hemos enviado un código de verificación al correo <strong><?= $email ?></strong></p>
          <p>Ingresa el código de 6 dígitos para verificar tu cuenta:</p>
          
          <form action="<?= APP_URL ?>/auth/verify" method="post">
            <div class="mb-4">
              <div class="verification-code-container">
                <input type="text" class="form-control text-center fw-bold" 
                       name="code" id="code" maxlength="6" 
                       style="font-size: 24px; letter-spacing: 8px;"
                       placeholder="------" required autofocus>
              </div>
            </div>
            
            <div class="d-grid mb-3">
              <button type="submit" class="btn btn-primary btn-lg">Verificar Cuenta</button>
            </div>
          </form>
          
          <div class="text-center mt-3">
            <p>¿No recibiste el código? <a href="<?= APP_URL ?>/auth/resendCode">Reenviar código</a></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  document.getElementById('code').addEventListener('input', function(e) {
    // Permitir solo dígitos
    this.value = this.value.replace(/[^0-9]/g, '');
  });
</script>