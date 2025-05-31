/**
 * Inicialización de controladores
 */
document.addEventListener('DOMContentLoaded', function() {
    // Detectar formularios de miembros
    if (document.getElementById('editForm')) {
        // Obtener el ID del miembro de la URL
        const path = window.location.pathname;
        const matches = path.match(/\/miembros\/editar\/(\d+)/);
        const miembroId = matches ? matches[1] : '';
        
        if (miembroId) {
            new MiembrosController('editForm', {
                url: `/ENCASA_DATABASE/miembros/actualizar/${miembroId}`,
                method: 'POST',
                redirect: true
            });
        }
    }
    
    // Otros inicializadores aquí...
});