<?php
// filepath: c:\xampp\htdocs\ENCASA_DATABASE\app\views\auth\register.php
?>
<div class="row justify-content-center">
  <div class="col-md-8 col-lg-6">
    <div class="card shadow">
      <div class="card-body">
        <h3 class="card-title text-center mb-4">Registro de Usuario</h3>
        
        <?php if(isset($errors)): ?>
        <div class="alert alert-danger">
          <ul class="mb-0">
            <?php foreach($errors as $error): ?>
            <li><?= $error ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
        <?php endif; ?>
        
        <form action="<?= APP_URL ?>/auth/registro" method="post">
          <!-- Nombre completo -->
          <div class="mb-3">
            <label for="nombre_completo" class="form-label">Nombre completo</label>
            <input type="text" class="form-control" id="nombre_completo" name="nombre_completo" required>
          </div>
          
          <!-- Nombre de usuario -->
          <div class="mb-3">
            <label for="username" class="form-label">Nombre de usuario</label>
            <input type="text" class="form-control" id="username" name="username" required>
          </div>
          
          <!-- Correo electrónico -->
          <div class="mb-3">
            <label for="email" class="form-label">Correo electrónico</label>
            <input type="email" class="form-control" id="email" name="email" required>
          </div>
          
          <!-- Contraseña -->
          <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" class="form-control" id="password" name="password" required>
            <div class="form-text">Mínimo 6 caracteres</div>
          </div>
          
          <!-- Confirmación de contraseña -->
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