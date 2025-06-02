/**
 * Script para manejar el envío del formulario con las opciones seleccionadas
 * o personalizadas de instituciones y profesiones
 */
document.addEventListener('DOMContentLoaded', function() {
    // Capturar el formulario
    const form = document.querySelector('form');
    
    if (form) {
        form.addEventListener('submit', function(event) {
            // Procesar la institución educativa (seleccionada o personalizada)
            const institucionSelect = document.getElementById('institucion_educativa_select');
            const institucionPersonalizada = document.getElementById('institucion_personalizada');
            const institucionFinal = document.getElementById('institucion_educativa');
            
            if (institucionSelect && institucionSelect.value === 'custom' && institucionPersonalizada) {
                // El usuario está agregando una nueva institución
                institucionFinal.value = institucionPersonalizada.value;
            } else if (institucionSelect && institucionSelect.options[institucionSelect.selectedIndex]) {
                // El usuario seleccionó una institución existente
                institucionFinal.value = institucionSelect.options[institucionSelect.selectedIndex].text;
            }
            
            // Procesar la profesión (seleccionada o personalizada)
            const profesionSelect = document.getElementById('profesion_select');
            const profesionPersonalizada = document.getElementById('profesion_personalizada');
            const profesionFinal = document.getElementById('profesion');
            
            if (profesionSelect && profesionSelect.value === 'custom' && profesionPersonalizada) {
                // El usuario está agregando una nueva profesión
                profesionFinal.value = profesionPersonalizada.value;
            } else if (profesionSelect && profesionSelect.options[profesionSelect.selectedIndex]) {
                // El usuario seleccionó una profesión existente
                profesionFinal.value = profesionSelect.options[profesionSelect.selectedIndex].text;
            }
            
            // Continuar con el envío normal del formulario
            return true;
        });
    }
});