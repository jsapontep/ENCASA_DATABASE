<?php
// filepath: c:\xampp\htdocs\ENCASA_DATABASE\app\views\miembros\index.php
?>
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Directorio de Miembros</h1>
        <a href="<?= APP_URL ?>/miembros/crear" class="btn btn-primary">
            <i class="fas fa-plus-circle"></i> Nuevo Miembro
        </a>
    </div>
    
    <?php if (isset($_SESSION['flash_message'])): ?>
        <div class="alert alert-<?= $_SESSION['flash_type'] ?? 'info' ?> alert-dismissible fade show">
            <?= $_SESSION['flash_message'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php 
        unset($_SESSION['flash_message']);
        unset($_SESSION['flash_type']);
        ?>
    <?php endif; ?>
    
    <!-- Formulario de búsqueda -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="<?= APP_URL ?>/miembros" method="get" class="row g-3">
                <div class="col-md-8">
                    <input type="text" name="busqueda" class="form-control" 
                           placeholder="Buscar por nombre, apellido, celular, localidad..." 
                           value="<?= htmlspecialchars($busqueda ?? '') ?>">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Buscar
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <div class="card shadow">
        <div class="card-body">
            <?php if (empty($miembros)): ?>
                <div class="alert alert-info">
                    <?= !empty($busqueda) ? 'No se encontraron resultados para tu búsqueda.' : 'No hay miembros registrados todavía.' ?>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre completo</th>
                                <th>Celular</th>
                                <th>Localidad</th>
                                <th>Estado espiritual</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($miembros as $miembro): ?>
                                <tr>
                                    <td><?= $miembro['id'] ?></td>
                                    <td><?= htmlspecialchars($miembro['nombres'] . ' ' . $miembro['apellidos']) ?></td>
                                    <td><?= htmlspecialchars($miembro['celular'] ?? 'No registrado') ?></td>
                                    <td><?= htmlspecialchars($miembro['localidad'] ?? 'No registrada') ?></td>
                                    <td><?= htmlspecialchars($miembro['estado_espiritual'] ?? 'No registrado') ?></td>
                                    <td class="text-center">
                                        <a href="<?= APP_URL ?>/miembros/<?= $miembro['id'] ?>" class="btn btn-sm btn-info" title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?= APP_URL ?>/miembros/editar/<?= $miembro['id'] ?>" class="btn btn-sm btn-primary" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger eliminar-btn" 
                                                data-id="<?= $miembro['id'] ?>"
                                                data-nombre="<?= htmlspecialchars($miembro['nombres'] . ' ' . $miembro['apellidos']) ?>"
                                                title="Eliminar">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Paginación -->
                <?php if ($totalPaginas > 1): ?>
                    <nav aria-label="Navegación de páginas">
                        <ul class="pagination justify-content-center">
                            <?php if ($pagina > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?= APP_URL ?>/miembros?pagina=<?= $pagina - 1 ?><?= !empty($busqueda) ? '&busqueda=' . urlencode($busqueda) : '' ?>">
                                        &laquo; Anterior
                                    </a>
                                </li>
                            <?php endif; ?>
                            
                            <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                                <li class="page-item <?= $i === $pagina ? 'active' : '' ?>">
                                    <a class="page-link" href="<?= APP_URL ?>/miembros?pagina=<?= $i ?><?= !empty($busqueda) ? '&busqueda=' . urlencode($busqueda) : '' ?>">
                                        <?= $i ?>
                                    </a>
                                </li>
                            <?php endfor; ?>
                            
                            <?php if ($pagina < $totalPaginas): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?= APP_URL ?>/miembros?pagina=<?= $pagina + 1 ?><?= !empty($busqueda) ? '&busqueda=' . urlencode($busqueda) : '' ?>">
                                        Siguiente &raquo;
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal de Confirmación para eliminación desde listado -->
<div class="modal fade" id="eliminarListadoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i> 
                    <strong>¡Advertencia!</strong> Esta acción eliminará permanentemente todos los datos de:
                </div>
                <p class="text-center fs-5" id="nombreMiembroEliminar"></p>
                <p>Esta acción no se puede deshacer.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="formEliminarListado" action="" method="post">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash-alt"></i> Eliminar Definitivamente
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Configurar el modal de eliminación para el listado
    const botonesEliminar = document.querySelectorAll('.eliminar-btn');
    const modalEliminar = document.getElementById('eliminarListadoModal');
    const formEliminar = document.getElementById('formEliminarListado');
    const nombreMiembro = document.getElementById('nombreMiembroEliminar');
    
    botonesEliminar.forEach(boton => {
        boton.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const nombre = this.getAttribute('data-nombre');
            
            // Configurar el modal
            formEliminar.action = `<?= APP_URL ?>/miembros/eliminar/${id}`;
            nombreMiembro.textContent = nombre;
            
            // Mostrar el modal
            const modal = new bootstrap.Modal(modalEliminar);
            modal.show();
        });
    });
});
</script>