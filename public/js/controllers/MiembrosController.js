/**
 * Controlador específico para formularios de miembros
 */
class MiembrosController extends FormController {
    constructor(formId, options = {}) {
        super(formId, options);
        
        // Pestañas del formulario
        this.tabs = document.querySelectorAll('a[data-bs-toggle="tab"]');
        this.initializeTabs();
    }
    
    initializeTabs() {
        // Implementar navegación entre pestañas
        this.tabs.forEach(tab => {
            tab.addEventListener('shown.bs.tab', (e) => {
                const id = e.target.id.replace('-tab', '');
                const url = new URL(window.location.href);
                url.searchParams.set('tab', id);
                history.replaceState({}, '', url);
            });
        });
    }
    
    beforeSubmit() {
        // Validar campos críticos antes de enviar
        const nombres = this.formElement.querySelector('[name="nombres"]');
        const apellidos = this.formElement.querySelector('[name="apellidos"]');
        
        let isValid = true;
        
        if (!nombres || !nombres.value.trim()) {
            this.highlightError(nombres, 'El campo nombres es obligatorio');
            isValid = false;
        }
        
        if (!apellidos || !apellidos.value.trim()) {
            this.highlightError(apellidos, 'El campo apellidos es obligatorio');
            isValid = false;
        }
        
        return isValid;
    }
    
    highlightError(field, message) {
        if (!field) return;
        
        field.classList.add('is-invalid');
        
        // Agregar mensaje de error si no existe
        let feedback = field.parentNode.querySelector('.invalid-feedback');
        if (!feedback) {
            feedback = document.createElement('div');
            feedback.className = 'invalid-feedback';
            field.parentNode.appendChild(feedback);
        }
        
        feedback.textContent = message;
        
        // Ir a la pestaña correspondiente
        const tabPane = field.closest('.tab-pane');
        if (tabPane) {
            const tabId = tabPane.id;
            const tab = document.querySelector(`[data-bs-toggle="tab"][href="#${tabId}"]`);
            if (tab) {
                const tabInstance = new bootstrap.Tab(tab);
                tabInstance.show();
            }
        }
    }
    
    onSuccess(data) {
        // Mostrar mensaje de éxito
        this.showMessage(data.message || 'Miembro actualizado correctamente', 'success');
        
        // Redirigir después de un breve retraso
        setTimeout(() => {
            window.location.href = data.redirect || `/ENCASA_DATABASE/miembros/${data.id || ''}`;
        }, 1500);
    }
}