// Asegúrate de que estos eventos estén configurados en el archivo
document.addEventListener('DOMContentLoaded', function() {
    // Para institución educativa
    const institucionSelect = document.getElementById('institucion_educativa_select');
    const institucionPersonalizada = document.getElementById('institucion_personalizada');
    const institucionFinal = document.getElementById('institucion_educativa');
    
    if (institucionSelect) {
        institucionSelect.addEventListener('change', function() {
            if (this.value) {
                institucionFinal.value = this.value;
            }
        });
    }
    
    if (institucionPersonalizada) {
        institucionPersonalizada.addEventListener('input', function() {
            institucionFinal.value = this.value;
        });
    }
    
    // Para profesión
    const profesionSelect = document.getElementById('profesion_select');
    const profesionPersonalizada = document.getElementById('profesion_personalizada');
    const profesionFinal = document.getElementById('profesion');
    
    if (profesionSelect) {
        profesionSelect.addEventListener('change', function() {
            if (this.value) {
                profesionFinal.value = this.value;
            }
        });
    }
    
    if (profesionPersonalizada) {
        profesionPersonalizada.addEventListener('input', function() {
            profesionFinal.value = this.value;
        });
    }
    
    // Para el formulario completo
    const form = document.getElementById('formMiembro');
    if (form) {
        form.addEventListener('submit', function() {
            // Asegurarse de que los campos finales tengan los valores correctos antes de enviar
            if (institucionPersonalizada && institucionPersonalizada.style.display !== 'none' && institucionPersonalizada.value) {
                institucionFinal.value = institucionPersonalizada.value;
            } else if (institucionSelect && institucionSelect.value) {
                institucionFinal.value = institucionSelect.value;
            }
            
            if (profesionPersonalizada && profesionPersonalizada.style.display !== 'none' && profesionPersonalizada.value) {
                profesionFinal.value = profesionPersonalizada.value;
            } else if (profesionSelect && profesionSelect.value) {
                profesionFinal.value = profesionSelect.value;
            }
        });
    }
});