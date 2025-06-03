<?php
// Verificar si es modo edición
$esEdicion = true;
$titulo = 'Editar Miembro';

// Variables para acceder fácilmente a los datos relacionados
$contacto = $miembro['contacto'] ?? [];
$estudios = $miembro['estudiostrabajo'] ?? [];
$tallas = $miembro['tallas'] ?? [];
$salud = $miembro['saludemergencias'] ?? [];
$carrera = $miembro['carrerabiblica'] ?? [];
?>

<div class="container mt-4">
    <h1><?= $titulo ?></h1>
    
    <!-- Área para mensajes -->
    <div id="mensajes-area"></div>
    
    <!-- Asegúrate de que el formulario tenga el enctype correcto -->
    <form id="formMiembro" action="<?= url('miembros/actualizar/'.$miembro['id']) ?>" method="POST" enctype="multipart/form-data">
        <!-- ID oculto para edición -->
        <input type="hidden" name="id" value="<?= htmlspecialchars($miembro['id']) ?>">

        <!-- Navegación por pestañas -->
        <ul class="nav nav-tabs mb-4" id="miembroTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="datos-tab" data-bs-toggle="tab" href="#datos" role="tab">
                    <i class="fas fa-user me-1"></i> Información General
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="contacto-tab" data-bs-toggle="tab" href="#contacto" role="tab">
                    <i class="fas fa-address-book me-1"></i> Contacto
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="estudios-tab" data-bs-toggle="tab" href="#estudios" role="tab">
                    <i class="fas fa-graduation-cap me-1"></i> Estudios/Trabajo
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tallas-tab" data-bs-toggle="tab" href="#tallas" role="tab">
                    <i class="fas fa-tshirt me-1"></i> Tallas
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="salud-tab" data-bs-toggle="tab" href="#salud" role="tab">
                    <i class="fas fa-heartbeat me-1"></i> Salud y Emergencias
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="carrera-tab" data-bs-toggle="tab" href="#carrera" role="tab">
                    <i class="fas fa-pray me-1"></i> Carrera Bíblica
                </a>
            </li>
        </ul>

        <div class="tab-content" id="miembroTabContent">
            <!-- Incluir contenido de pestañas desde archivos parciales -->
            <?php include 'partial/informacion_general.php'; ?>
            <?php include 'partial/contacto.php'; ?>
            <?php include 'partial/estudios.php'; ?>
            <?php include 'partial/tallas.php'; ?>
            <?php include 'partial/salud.php'; ?>
            <?php include 'partial/carrera.php'; ?>
        </div>

        <!-- Campos ocultos para fechas de modificación -->
        <input type="hidden" name="fecha_modificacion" value="<?php echo date('Y-m-d H:i:s'); ?>">

        <!-- Botones de acción generales -->
        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
            <button type="submit" class="btn btn-primary btn-lg" id="btnGuardar">
                <i class="fas fa-save me-1"></i> Actualizar Miembro
            </button>
            <a href="<?= url('miembros') ?>" class="btn btn-secondary btn-lg">
                <i class="fas fa-times me-1"></i> Cancelar
            </a>
        </div>
    </form>
</div>

<!-- Añade este script ANTES de los demás scripts -->
<script src="<?= url('assets/js/miembros/MiembrosController.js') ?>"></script>

<!-- Scripts existentes -->
<script src="<?= url('assets/js/miembros/location-data.js') ?>"></script>
<script src="<?= url('assets/js/miembros/geo-data.js') ?>"></script>
<script src="<?= url('assets/js/miembros/form-handlers.js') ?>"></script>
<script src="<?= url('assets/js/formulario-estudios.js') ?>"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar el controlador de miembros con ID correcto
    const miembrosController = new MiembrosController('formMiembro', {
        successRedirect: '<?= url("miembros/{$miembro['id']}") ?>',
        errorRedirect: '<?= url("miembros/editar/{$miembro['id']}") ?>'
    });
    
    // Verificar si el formulario existe antes de agregar el event listener
    const form = document.getElementById('formMiembro');
    
    if (form) {
        // En el evento submit del formulario
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Verificar si hay un archivo para subir
            const fileInput = form.querySelector('input[type="file"]');
            if (fileInput && fileInput.files.length > 0) {
                console.log("Archivo seleccionado:", fileInput.files[0].name);
            } else {
                console.log("No se ha seleccionado ningún archivo");
            }
            
            const formData = new FormData(form);
            
            // Usar el controlador si está definido, o hacer el fetch directamente
            if (typeof miembrosController !== 'undefined') {
                miembrosController.submitForm(formData)
                    .then(data => {
                        if (!data.success) {
                            alert("Error: " + data.message);
                        }
                    })
                    .catch(error => {
                        console.error("Error:", error);
                    });
            } else {
                console.warn("MiembrosController no disponible, usando fetch directo");
                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = data.redirect || '<?= url("miembros/{$miembro['id']}") ?>';
                    } else {
                        alert("Error: " + data.message);
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                });
            }
        });
    } else {
        console.error("Formulario #formMiembro no encontrado en la página");
    }
});
</script>