<?php
require_once APP_PATH . '/views/partials/header.php';
require_once APP_PATH . '/views/partials/navbar.php';
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Permisos del Rol: <?= htmlspecialchars($rol['nombre']) ?></h1>
        <a href="<?= APP_URL ?>/admin/roles" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver a Roles
        </a>
    </div>
    
    <?php if(isset($_SESSION['flash_message'])): ?>
    <div class="alert alert-<?= $_SESSION['flash_type'] ?? 'info' ?> alert-dismissible fade show">
        <?= $_SESSION['flash_message'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['flash_message']); unset($_SESSION['flash_type']); endif; ?>
    
    <div class="card shadow">
        <div class="card-body">
            <form action="<?= APP_URL ?>/admin/roles/permisos/<?= $rol['id'] ?>" method="post">
                <div class="row">
                    <?php foreach($permisos as $permiso): ?>
                    <div class="col-md-4 mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="permisos[]" 
                                value="<?= $permiso['id'] ?>" id="permiso_<?= $permiso['id'] ?>"
                                <?= in_array($permiso['id'], $permisosRol) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="permiso_<?= $permiso['id'] ?>">
                                <?= htmlspecialchars($permiso['nombre']) ?>
                                <small class="d-block text-muted"><?= htmlspecialchars($permiso['descripcion']) ?></small>
                            </label>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="text-end mt-3">
                    <button type="submit" class="btn btn-primary">Guardar Permisos</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once APP_PATH . '/views/partials/footer.php'; ?>