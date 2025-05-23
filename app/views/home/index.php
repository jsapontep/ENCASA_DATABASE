<?php
// filepath: /Applications/XAMPP/xamppfiles/htdocs/Encasa_Database/app/views/home/index.php
?>
<div class="jumbotron bg-light p-5 rounded mb-4">
    <h1 class="display-4">¡Bienvenido a Iglesia En Casa!</h1>
    <p class="lead">Sistema de gestión de información para la comunidad</p>
    <hr class="my-4">
    
    <?php if (isset($user) && $user): ?>
        <p class="mb-4">Hola, <strong><?= htmlspecialchars($user['nombre_completo'] ?? $user['username']) ?></strong>. Bienvenido al sistema de gestión.</p>
        
        <div class="row">
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Miembros</h5>
                        <p class="card-text">Gestiona la información de los miembros de la iglesia.</p>
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <a href="<?= APP_URL ?>/miembros" class="btn btn-primary">Ver miembros</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Ministerios</h5>
                        <p class="card-text">Administra los ministerios y sus participantes.</p>
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <a href="<?= APP_URL ?>/ministerios" class="btn btn-primary">Ver ministerios</a>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <p class="mb-4">Este sistema te permite gestionar la información de la Iglesia En Casa.</p>
        <div class="d-grid gap-2 d-md-block">
            <a class="btn btn-primary btn-lg" href="<?= APP_URL ?>/login">Iniciar sesión</a>
            <a class="btn btn-outline-primary btn-lg" href="<?= APP_URL ?>/registro">Registrarse</a>
        </div>
    <?php endif; ?>
</div>

<?php if (!isset($user) || !$user): ?>
<div class="row mt-4">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">¿Qué es Iglesia En Casa?</h5>
            </div>
            <div class="card-body">
                <p class="card-text">Iglesia En Casa es una comunidad cristiana comprometida con compartir el amor de Dios y ayudar a otros a encontrar su propósito.</p>
                <p class="card-text">Este sistema nos permite administrar la información de nuestros miembros y ministerios de manera eficiente.</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Contacto</h5>
            </div>
            <div class="card-body">
                <p class="card-text">Si tienes alguna duda sobre el sistema o deseas más información, no dudes en contactarnos.</p>
                <p class="card-text">Email: contacto@iglesiaencasa.org</p>
                <p class="card-text">Teléfono: (123) 456-7890</p>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>