<?php 
require_once APP_PATH . '/views/partials/header.php';
require_once APP_PATH . '/views/partials/navbar.php';
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Gestión de Roles</h1>
        <a href="<?= APP_URL ?>/admin/roles/crear" class="btn btn-primary">
            <i class="fas fa-plus-circle"></i> Nuevo Rol
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
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Nivel de Acceso</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($roles as $rol): ?>
                        <tr>
                            <td><?= $rol['id'] ?></td>
                            <td><?= htmlspecialchars($rol['nombre']) ?></td>
                            <td><?= htmlspecialchars($rol['descripcion']) ?></td>
                            <td><?= $rol['nivel_acceso'] ?></td>
                            <td>
                                <a href="<?= APP_URL ?>/admin/roles/editar/<?= $rol['id'] ?>" class="btn btn-sm btn-info">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="<?= APP_URL ?>/admin/roles/permisos/<?= $rol['id'] ?>" class="btn btn-sm btn-warning">
                                    <i class="fas fa-key"></i>
                                </a>
                                <button class="btn btn-sm btn-danger" onclick="confirmarEliminar(<?= $rol['id'] ?>)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function confirmarEliminar(id) {
    if (confirm('¿Estás seguro de que deseas eliminar este rol?')) {
        window.location.href = '<?= APP_URL ?>/admin/roles/eliminar/' + id;
    }
}
</script>

<?php require_once APP_PATH . '/views/partials/footer.php'; ?>