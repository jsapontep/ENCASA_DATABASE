<?php
// filepath: c:\xampp\htdocs\ENCASA_DATABASE\app\views\miembros\crear.php

// Verificar si es modo edición
$esEdicion = isset($miembro) && !empty($miembro);
$titulo = $esEdicion ? 'Editar Miembro' : 'Registrar Nuevo Miembro';

// Variables para acceder fácilmente a los datos relacionados
$contacto = $esEdicion ? ($miembro['contacto'] ?? []) : [];
$estudios = $esEdicion ? ($miembro['estudiostrabajo'] ?? []) : [];
$tallas = $esEdicion ? ($miembro['tallas'] ?? []) : [];
$salud = $esEdicion ? ($miembro['saludemergencias'] ?? []) : [];
$carrera = $esEdicion ? ($miembro['carrerabiblica'] ?? []) : [];
?>

<div class="container mt-4">
    <h1><?= $titulo ?></h1>
    
    <!-- Área para mensajes -->
    <div id="mensajes-area"></div>
    
    <form action="<?= url($esEdicion ? 'miembros/actualizar/'.$miembro['id'] : 'miembros/guardar') ?>" method="POST" enctype="multipart/form-data" id="formMiembro">
        <!-- ID oculto para edición -->
        <?php if($esEdicion): ?>
            <input type="hidden" name="id" value="<?= $miembro['id'] ?>">
        <?php endif; ?>

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
                <a class="nav-link" id="espiritual-tab" data-bs-toggle="tab" href="#espiritual" role="tab">
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
        <!-- Cambiar nombre del campo para coincidir con la columna de la BD -->
        <input type="hidden" name="fecha_registro_sistema" value="<?php echo date('Y-m-d H:i:s'); ?>">

        <!-- Botones de acción generales -->
        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-save me-1"></i> <?= $esEdicion ? 'Actualizar' : 'Registrar' ?> Miembro Completo
            </button>
            <a href="<?= url('miembros') ?>" class="btn btn-secondary btn-lg">
                <i class="fas fa-times me-1"></i> Cancelar
            </a>
        </div>
        
        <!-- Botones de prueba para cada tabla -->
        <div class="mt-4 mb-3">
            <h5>Herramientas de prueba (guardar tabla por tabla):</h5>
            <div class="d-flex flex-wrap gap-2">
                <button type="button" class="btn btn-outline-primary btn-probar-tabla" data-tabla="miembro">
                    <i class="fas fa-user me-1"></i> Probar Información General
                </button>
                <button type="button" class="btn btn-outline-primary btn-probar-tabla" data-tabla="contacto">
                    <i class="fas fa-address-book me-1"></i> Probar Contacto
                </button>
                <button type="button" class="btn btn-outline-primary btn-probar-tabla" data-tabla="estudiostrabajo">
                    <i class="fas fa-graduation-cap me-1"></i> Probar Estudios/Trabajo
                </button>
                <button type="button" class="btn btn-outline-primary btn-probar-tabla" data-tabla="tallas">
                    <i class="fas fa-tshirt me-1"></i> Probar Tallas
                </button>
                <button type="button" class="btn btn-outline-primary btn-probar-tabla" data-tabla="saludemergencias">
                    <i class="fas fa-heartbeat me-1"></i> Probar Salud
                </button>
                <button type="button" class="btn btn-outline-primary btn-probar-tabla" data-tabla="carrerabiblica">
                    <i class="fas fa-pray me-1"></i> Probar Carrera Bíblica
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Cargar archivos JavaScript -->
<script src="<?= url('assets/js/miembros/location-data.js') ?>"></script>
<script src="<?= url('assets/js/miembros/geo-data.js') ?>"></script>
<script src="<?= url('assets/js/miembros/form-handlers.js') ?>"></script>
<script src="<?= url('assets/js/formulario-estudios.js') ?>"></script>
<script src="<?= url('assets/js/tabla-por-tabla.js') ?>"></script>