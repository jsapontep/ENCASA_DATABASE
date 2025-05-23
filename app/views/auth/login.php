<?php
// filepath: /Applications/XAMPP/xamppfiles/htdocs/Encasa_Database/app/views/auth/login.php
?>
<div class="row justify-content-center">
  <div class="col-md-8 col-lg-6">
    <div class="card shadow">
      <div class="card-header bg-primary text-white">
        <h2 class="my-2">Iniciar Sesión</h2>
      </div>
      <div class="card-body">
        <form action="<?= APP_URL ?>/auth/login" method="post">
          <div class="mb-3">
            <label for="username" class="form-label">Usuario o Email</label>
            <input type="text" class="form-control" id="username" name="username" required autofocus>
          </div>
          
          <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" class="form-control" id="password" name="password" required>
          </div>
          
          <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
          </div>
        </form>
        
        <div class="text-center mt-3">
          <a href="<?= APP_URL ?>/registro">¿No tienes cuenta? Regístrate</a>
        </div>
      </div>
    </div>
  </div>
</div>