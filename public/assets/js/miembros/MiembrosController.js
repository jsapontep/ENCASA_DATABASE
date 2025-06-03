/**
 * Controlador para el procesamiento de formularios de miembros
 */
class MiembrosController {
    /**
     * Constructor del controlador
     * @param {string} formId - ID del formulario a controlar
     * @param {Object} options - Opciones de configuración
     */
    constructor(formId, options = {}) {
        this.formId = formId;
        this.options = {
            successRedirect: '',
            errorRedirect: '',
            ...options
        };
        this.initialize();
    }
    
    /**
     * Inicializar el controlador
     */
    initialize() {
        console.log(`MiembrosController inicializado para el formulario #${this.formId}`);
        // Cualquier inicialización adicional
    }
    
    /**
     * Procesa el envío del formulario
     * @param {FormData} formData - Datos del formulario
     * @returns {Promise} Promesa con el resultado del envío
     */
    async submitForm(formData) {
        try {
            const response = await fetch(document.getElementById(this.formId).action, {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                if (this.options.successRedirect) {
                    window.location.href = this.options.successRedirect;
                }
                return data;
            } else {
                console.error("Error en el servidor:", data.message);
                return data;
            }
        } catch (error) {
            console.error("Error al enviar formulario:", error);
            throw error;
        }
    }
}