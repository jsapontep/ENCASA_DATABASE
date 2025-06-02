/**
 * Sistema de gestión de información educativa
 * Este archivo maneja la carga dinámica de instituciones educativas y carreras
 * según el país seleccionado y nivel educativo
 */

const EducationManager = {
    // Almacena las instituciones y carreras cargadas por país
    educationData: {},
    
    // Inicializar el sistema
    init: function() {
        // Conectar con los selectores del formulario
        const nivelSelect = document.getElementById('nivel_estudios');
        const institucionSelect = document.getElementById('institucion_educativa_select');
        const profesionSelect = document.getElementById('profesion_select');
        const paisSelect = document.getElementById('pais'); // De la pestaña contacto
        
        // Eventos para los campos existentes
        if (nivelSelect) {
            nivelSelect.addEventListener('change', this.onNivelChange.bind(this));
        }
        
        if (institucionSelect) {
            institucionSelect.addEventListener('change', this.onInstitucionChange.bind(this));
        }
        
        if (paisSelect) {
            paisSelect.addEventListener('change', function() {
                // Cuando cambie el país en la pestaña contacto, actualizamos
                // las opciones relevantes en la pestaña estudios
                const paisSeleccionado = paisSelect.value;
                if (paisSeleccionado) {
                    this.loadCountryEducationData(paisSeleccionado);
                }
            }.bind(this));
        }
        
        // Configurar campos personalizados
        this.setupCustomFields();
        
        // Cargar datos del país seleccionado inicialmente (si existe)
        if (paisSelect && paisSelect.value) {
            this.loadCountryEducationData(paisSelect.value);
        }
    },
    
    // Cuando cambia el nivel educativo
    onNivelChange: function(event) {
        const nivelSeleccionado = event.target.value;
        const paisSeleccionado = document.getElementById('pais')?.value;
        
        if (!paisSeleccionado || !nivelSeleccionado) {
            this.resetInstituciones();
            return;
        }
        
        this.loadInstituciones(paisSeleccionado, nivelSeleccionado);
    },
    
    // Cuando cambia la institución
    onInstitucionChange: function(event) {
        const institucionSeleccionada = event.target.value;
        const nivelSeleccionado = document.getElementById('nivel_estudios')?.value;
        const paisSeleccionado = document.getElementById('pais')?.value;
        
        if (institucionSeleccionada === 'custom') {
            // Mostrar campo para agregar nueva institución
            document.getElementById('institucion_personalizada_container').style.display = 'block';
            this.resetProfesiones();
            return;
        } else {
            document.getElementById('institucion_personalizada_container').style.display = 'none';
        }
        
        if (!paisSeleccionado || !nivelSeleccionado || !institucionSeleccionada) {
            this.resetProfesiones();
            return;
        }
        
        this.loadProfesiones(paisSeleccionado, nivelSeleccionado, institucionSeleccionada);
    },
    
    // Configurar los campos personalizados para nuevas instituciones y carreras
    setupCustomFields: function() {
        const profesionSelect = document.getElementById('profesion_select');
        if (profesionSelect) {
            profesionSelect.addEventListener('change', function(event) {
                if (event.target.value === 'custom') {
                    document.getElementById('profesion_personalizada_container').style.display = 'block';
                } else {
                    document.getElementById('profesion_personalizada_container').style.display = 'none';
                }
            });
        }
    },
    
    // Carga los datos educativos específicos de un país
    loadCountryEducationData: function(pais) {
        if (this.educationData[pais]) {
            // Ya tenemos los datos cargados
            return;
        }
        
        // Realizar petición AJAX para obtener los datos del país
        fetch(`${APP_URL}/api/education-data/${pais}`)
            .then(response => response.json())
            .then(data => {
                this.educationData[pais] = data;
                
                // Si ya hay un nivel seleccionado, cargar las instituciones
                const nivelSeleccionado = document.getElementById('nivel_estudios')?.value;
                if (nivelSeleccionado) {
                    this.loadInstituciones(pais, nivelSeleccionado);
                }
            })
            .catch(error => {
                console.error('Error cargando datos educativos:', error);
            });
    },
    
    // Cargar instituciones según nivel educativo
    loadInstituciones: function(pais, nivel) {
        const institucionSelect = document.getElementById('institucion_educativa_select');
        if (!institucionSelect) return;
        
        // Resetear el selector
        institucionSelect.innerHTML = '<option value="">-- Seleccione una institución --</option>';
        
        // Si no tenemos datos para este país, no podemos continuar
        if (!this.educationData[pais] || !this.educationData[pais][nivel]) {
            institucionSelect.disabled = true;
            return;
        }
        
        // Agregar las instituciones disponibles
        const instituciones = this.educationData[pais][nivel].instituciones || [];
        instituciones.forEach(institucion => {
            const option = document.createElement('option');
            option.value = institucion.id;
            option.textContent = institucion.nombre;
            institucionSelect.appendChild(option);
        });
        
        // Agregar opción para personalizar
        const customOption = document.createElement('option');
        customOption.value = 'custom';
        customOption.textContent = '-- Agregar nueva institución --';
        institucionSelect.appendChild(customOption);
        
        institucionSelect.disabled = false;
        this.resetProfesiones();
    },
    
    // Cargar profesiones según institución
    loadProfesiones: function(pais, nivel, institucionId) {
        const profesionSelect = document.getElementById('profesion_select');
        if (!profesionSelect) return;
        
        // Resetear el selector
        profesionSelect.innerHTML = '<option value="">-- Seleccione una profesión --</option>';
        
        // Si no tenemos datos, no podemos continuar
        if (!this.educationData[pais] || !this.educationData[pais][nivel]) {
            profesionSelect.disabled = true;
            return;
        }
        
        // Buscar la institución seleccionada
        const institucion = this.educationData[pais][nivel].instituciones.find(i => i.id === institucionId);
        if (!institucion || !institucion.profesiones) {
            profesionSelect.disabled = true;
            return;
        }
        
        // Agregar las profesiones disponibles
        institucion.profesiones.forEach(profesion => {
            const option = document.createElement('option');
            option.value = profesion.id;
            option.textContent = profesion.nombre;
            profesionSelect.appendChild(option);
        });
        
        // Agregar opción para personalizar
        const customOption = document.createElement('option');
        customOption.value = 'custom';
        customOption.textContent = '-- Agregar nueva profesión --';
        profesionSelect.appendChild(customOption);
        
        profesionSelect.disabled = false;
    },
    
    // Resetear el selector de instituciones
    resetInstituciones: function() {
        const institucionSelect = document.getElementById('institucion_educativa_select');
        if (institucionSelect) {
            institucionSelect.innerHTML = '<option value="">-- Seleccione primero un nivel educativo --</option>';
            institucionSelect.disabled = true;
        }
        this.resetProfesiones();
    },
    
    // Resetear el selector de profesiones
    resetProfesiones: function() {
        const profesionSelect = document.getElementById('profesion_select');
        if (profesionSelect) {
            profesionSelect.innerHTML = '<option value="">-- Seleccione primero una institución --</option>';
            profesionSelect.disabled = true;
        }
        
        // Ocultar campos personalizados
        const profesionPersonalizada = document.getElementById('profesion_personalizada_container');
        if (profesionPersonalizada) {
            profesionPersonalizada.style.display = 'none';
        }
    },
    
    // Guardar una nueva institución
    saveNewInstitution: function(pais, nivel, nombreInstitucion) {
        // Esta función enviaría una solicitud AJAX para guardar la nueva institución
        // y devolvería un ID para la nueva institución
        return fetch(`${APP_URL}/api/education-data/save-institution`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                pais: pais,
                nivel: nivel,
                nombre: nombreInstitucion
            })
        })
        .then(response => response.json());
    },
    
    // Guardar una nueva profesión
    saveNewProfession: function(pais, nivel, institucionId, nombreProfesion) {
        // Esta función enviaría una solicitud AJAX para guardar la nueva profesión
        return fetch(`${APP_URL}/api/education-data/save-profession`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                pais: pais,
                nivel: nivel,
                institucionId: institucionId,
                nombre: nombreProfesion
            })
        })
        .then(response => response.json());
    }
};

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    EducationManager.init();
});