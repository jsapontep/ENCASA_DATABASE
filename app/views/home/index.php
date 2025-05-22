<?php
// filepath: /Applications/XAMPP/xamppfiles/htdocs/Encasa_Database/app/views/home/index.php
?>
<div class="jumbotron bg-light p-5 rounded">
    <h1 class="display-4">Bienvenido a Iglesia En Casa</h1>
    <p class="lead">Sistema de gestión de información para la comunidad</p>
    <hr class="my-4">
    
    <?php if (isset($user)): ?>
        <p>¿Qué te gustaría hacer hoy?</p>
        <div class="row">
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Miembros</h5>
                        <p class="card-text">Gestionar la información de los miembros de la iglesia.</p>
                        <a href="<?= APP_URL ?>/miembros" class="btn btn-primary">Ver miembros</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Ministerios</h5>
                        <p class="card-text">Administrar los ministerios y sus miembros.</p>
                        <a href="<?= APP_URL ?>/ministerios" class="btn btn-primary">Ver ministerios</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Mi Perfil</h5>
                        <p class="card-text">Ver y editar tu información personal.</p>
                        <a href="<?= APP_URL ?>/perfil" class="btn btn-primary">Ver perfil</a>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <p>Por favor, inicia sesión para acceder al sistema.</p>
        <a class="btn btn-primary btn-lg" href="<?= APP_URL ?>/login" role="button">Iniciar sesión</a>
    <?php endif; ?>
</div>