<?php
// filepath: /Applications/XAMPP/xamppfiles/htdocs/Encasa_Database/app/views/auth/register.php
?>
<div class="row justify-content-center">
  <div class="col-md-8 col-lg-6">
    <div class="card shadow">
      <div class="card-header bg-primary text-white">
        <h2 class="my-2">Registro de Usuario</h2>
      </div>
      <div class="card-body">
        <form action="<?= APP_URL ?>/auth/registro" method="post">
          <div class="mb-3">
            <label for="nombre_completo" class="form-label">Nombre Completo</label>
            <input type="text" class="form-control" id="nombre_completo" name="nombre_completo" required>
          </div>
          
          <div class="mb-3">
            <label for="username" class="form-label">Nombre de Usuario</label>
            <input type="text" class="form-control" id="username" name="username" required>
            <div class="form-text">Mínimo 4 caracteres, sin espacios</div>
          </div>
          
          <div class="mb-3">
            <label for="email" class="form-label">Correo Electrónico</label>
            <input type="email" class="form-control" id="email" name="email" required>
          </div>
          
          <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" class="form-control" id="password" name="password" required>
            <div class="form-text">Mínimo 6 caracteres</div>
          </div>
          
          <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
          </div>
          
          <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary">Registrarse</button>
          </div>
        </form>
        
        <div class="text-center mt-3">
          <a href="<?= APP_URL ?>/login">¿Ya tienes cuenta? Iniciar sesión</a>
        </div>
      </div>
    </div>
  </div>
</div>