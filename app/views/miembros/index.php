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
                                    <td>
                                        <a href="<?= APP_URL ?>/miembros/<?= $miembro['id'] ?>" class="btn btn-sm btn-info" title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>
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