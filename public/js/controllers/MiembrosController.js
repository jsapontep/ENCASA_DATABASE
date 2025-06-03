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
        // Registrar todos los valores que se están enviando para diagnóstico
        const formData = new FormData(this.form);
        console.log("Enviando datos del formulario:");
        for (let [key, value] of formData.entries()) {
            console.log(`${key}: ${value}`);
        }
        
        // Validación básica
        let isValid = true;
        
        // Validar campos obligatorios
        const requiredFields = ['nombres', 'apellidos'];
        for (let field of requiredFields) {
            const inputField = this.form.querySelector(`[name="${field}"]`);
            if (!inputField || !inputField.value.trim()) {
                this.highlightError(inputField, `El campo ${field} es obligatorio`);
                isValid = false;
            }
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
    }
    
    // Añadir función para depurar actualizaciones
    debugSubmit() {
        const formData = new FormData(this.form);
        console.table(Array.from(formData.entries()));
    }
    
    onSuccess(data) {
        // Mostrar mensaje de éxito
        this.showMessage(data.message || 'Miembro actualizado correctamente', 'success');
        
        // Redirigir después de un breve retraso
        setTimeout(() => {
            window.location.href = data.redirect || `/ENCASA_DATABASE/miembros/${data.id || ''}`;
        }, 1500);
   }
    
    confirmSubmit() {
        const formData = new FormData(this.formElement);
        let message = "¿Confirmar los siguientes cambios?\n\n";
        message += `Nombre: ${formData.get('nombres')}\n`;
        message += `Apellidos: ${formData.get('apellidos')}\n`;
        // etc.
        
        return confirm(message);
    }
    
    // Añadir este método a la clase MiembrosController
    onSubmitForm(e) {
        e.preventDefault();
        
        // Evitar envíos múltiples
        if (this.isSubmitting) return;
        this.isSubmitting = true;
        
        // Validación
        if (!this.beforeSubmit()) {
            this.isSubmitting = false;
            return false;
        }
        
        // Mostrar indicador de carga
        const submitBtn = this.form.querySelector('[type="submit"]');
        const originalBtnText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';
        
        // Crear FormData con todos los campos
        const formData = new FormData(this.form);
        
        // Agregar encabezados AJAX
        const fetchOptions = {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: formData
        };
        
        console.log('Enviando formulario con datos:', this.debugFormData(formData));
        
        // Enviar solicitud
        fetch(this.form.action, fetchOptions)
            .then(response => {
                return response.text().then(text => {
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        console.error('Respuesta no es JSON válido:', text);
                        throw new Error('La respuesta del servidor no es JSON válido');
                    }
                });
            })
            .then(data => {
                this.isSubmitting = false;
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
                
                if (data.success) {
                    this.onSuccess(data);
                } else {
                    this.onError(data);
                }
            })
            .catch(error => {
                console.error('Error en envío:', error);
                this.isSubmitting = false;
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
                this.showMessage('Error: ' + error.message, 'danger');
            });
    }

    // Método para depurar FormData
    debugFormData(formData) {
        const object = {};
        formData.forEach((value, key) => {
            if (object[key]) {
                if (!Array.isArray(object[key])) {
                    object[key] = [object[key]];
                }
                object[key].push(value);
            } else {
                object[key] = value;
            }
        });
        return object;
    }
}