<?php
// Añadir a tu barra de navegación

 if (isset($_SESSION['user_id'])): ?>
<div class="ml-auto">
    <a href="<?= APP_URL ?>/logout" class="btn btn-outline-light btn-sm">
        <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
    </a>
</div>
<?php endif; ?>