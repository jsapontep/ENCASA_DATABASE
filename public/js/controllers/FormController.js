/**
 * Controlador base para manejar formularios
 */
class FormController {
    constructor(formId, options = {}) {
        this.formElement = document.getElementById(formId);
        this.submitButton = document.querySelector(`#${formId} [type="submit"]`) || 
                           document.getElementById('btnGuardar');
        this.options = Object.assign({
            method: 'POST',
            redirect: true,
            showMessages: true,
        }, options);
        
        this.initialize();
    }
    
    initialize() {
        if (this.formElement) {
            this.formElement.addEventListener('submit', this.handleSubmit.bind(this));
            
            // Si hay un botón específico (no submit) para el envío
            if (this.submitButton && this.submitButton.type !== 'submit') {
                this.submitButton.addEventListener('click', this.handleSubmit.bind(this));
            }
        }
    }
    
    handleSubmit(event) {
        event.preventDefault();
        
        if (this.beforeSubmit() === false) {
            return false;
        }
        
        this.showLoading();
        
        const formData = new FormData(this.formElement);
        const url = this.options.url || this.formElement.getAttribute('action');
        
        fetch(url, {
            method: this.options.method,
            body: formData,
            credentials: 'same-origin'
        })
        .then(response => {
            if (response.redirected) {
                // Si el servidor redirige, seguimos la redirección
                window.location.href = response.url;
                return null;
            } else {
                return response.json().catch(() => response.text());
            }
        })
        .then(data => {
            this.hideLoading();
            
            if (data === null) return; // Ya se redirigió
            
            if (typeof data === 'object' && data.success) {
                this.onSuccess(data);
            } else if (typeof data === 'object' && data.errors) {
                this.onErrors(data.errors);
            } else if (typeof data === 'object' && !data.success) {
                this.onError(data.message || 'Ha ocurrido un error');
            } else {
                // Intentar detectar si es HTML de la página de éxito
                if (typeof data === 'string' && data.includes('<title>')) {
                    this.onSuccess({message: 'Operación completada con éxito'});
                } else {
                    this.onError('Ocurrió un error inesperado');
                }
            }
        })
        .catch(error => {
            this.hideLoading();
            this.onError('Error de conexión: ' + error.message);
            console.error('Error:', error);
        });
    }
    
    // Métodos que pueden ser sobrescritos por controladores hijos
    beforeSubmit() {
        return true; // Permitir continuar por defecto
    }
    
    onSuccess(data) {
        if (this.options.showMessages) {
            this.showMessage(data.message || 'Operación completada con éxito', 'success');
        }
        
        if (this.options.redirect && data.redirect) {
            setTimeout(() => {
                window.location.href = data.redirect;
            }, 1500);
        }
    }
    
    onErrors(errors) {
        if (this.options.showMessages) {
            this.showMessage(
                Array.isArray(errors) ? errors.join('<br>') : errors,
                'danger'
            );
        }
        
        // Marcar campos con error
        if (typeof errors === 'object') {
            for (const field in errors) {
                const input = this.formElement.querySelector(`[name="${field}"]`);
                if (input) {
                    input.classList.add('is-invalid');
                    
                    // Agregar mensaje de error
                    const feedback = document.createElement('div');
                    feedback.className = 'invalid-feedback';
                    feedback.textContent = errors[field];
                    input.parentNode.appendChild(feedback);
                }
            }
        }
    }
    
    onError(message) {
        if (this.options.showMessages) {
            this.showMessage(message, 'danger');
        }
    }
    
    // Métodos de utilidad
    showMessage(message, type = 'info') {
        // Crear un elemento de alerta
        const alert = document.createElement('div');
        alert.className = `alert alert-${type} alert-dismissible fade show`;
        alert.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        
        // Insertar al inicio del formulario
        this.formElement.prepend(alert);
        
        // Auto-ocultar después de 5 segundos
        setTimeout(() => {
            alert.classList.remove('show');
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    }
    
    showLoading() {
        if (this.submitButton) {
            this.submitButton.disabled = true;
            this.submitButtonOriginalText = this.submitButton.innerHTML;
            this.submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Procesando...';
        }
    }
    
    hideLoading() {
        if (this.submitButton && this.submitButtonOriginalText) {
            this.submitButton.disabled = false;
            this.submitButton.innerHTML = this.submitButtonOriginalText;
        }
    }
}