/**
 * Manejo de formularios y eventos de la página de miembros
 */

document.addEventListener('DOMContentLoaded', function() {
    // Inicializar todas las funcionalidades del formulario
    inicializarEventos();
    inicializarSelectores();
    configurarSubmit();
});

/**
 * Inicializa los eventos para los elementos del formulario
 */
function inicializarEventos() {
    // === Eventos para localidad y barrio ===
    const selectLocalidad = document.getElementById('localidad');
    const selectBarrio = document.getElementById('barrio');

    if (selectLocalidad) {
        selectLocalidad.addEventListener('change', function() {
            cargarBarrios(this.value);
            // Resetear campo personalizado
            const barrioPersonalizado = document.getElementById('barrio_personalizado');
            if (barrioPersonalizado) barrioPersonalizado.value = '';
        });
    }

    if (selectBarrio) {
        selectBarrio.addEventListener('change', manejarBarrioPersonalizado);
    }

    // === Eventos para país, estado/departamento y ciudad ===
    const selectPais = document.getElementById('pais');
    const selectEstadoDepto = document.getElementById('estado_departamento');
    const selectCiudad = document.getElementById('ciudad');

    if (selectPais) {
        selectPais.addEventListener('change', function() {
            if (this.value === "otro") {
                manejarPaisPersonalizado();
            } else {
                cargarEstadosDepartamentos(this.value);
            }
        });
    }

    if (selectEstadoDepto) {
        selectEstadoDepto.addEventListener('change', function() {
            if (this.value === "otro") {
                manejarEstadoDeptoPersonalizado();
            } else {
                cargarCiudades(selectPais.value, this.value);
            }
        });
    }

    if (selectCiudad) {
        selectCiudad.addEventListener('change', function() {
            if (this.value === "otro") {
                manejarCiudadPersonalizada();
            }
        });
    }

    // === Configurar las pestañas Bootstrap para preservar el estado ===
    const tabEl = document.querySelectorAll('a[data-bs-toggle="tab"]');
    if (tabEl.length > 0) {
        tabEl.forEach(tab => {
            tab.addEventListener('shown.bs.tab', function (event) {
                localStorage.setItem('activeTab', event.target.getAttribute('href'));
            });
        });

        // Restaurar la pestaña activa si existe
        const activeTab = localStorage.getItem('activeTab');
        if (activeTab) {
            const tab = document.querySelector(`a[href="${activeTab}"]`);
            if (tab) {
                new bootstrap.Tab(tab).show();
            }
        }
    }
}

/**
 * Inicializa los selectores de ubicación y geografía
 */
function inicializarSelectores() {
    // Inicializar selectores geográficos
    if (typeof cargarPaises === 'function') {
        cargarPaises();
    }

    // Inicializar selectores de localidad/barrio
    if (typeof cargarLocalidades === 'function') {
        cargarLocalidades();
    }
}

/**
 * Configura la validación y envío del formulario
 */
function configurarSubmit() {
    const formMiembro = document.getElementById('formMiembro');
    if (!formMiembro) return;

    formMiembro.addEventListener('submit', function(e) {
        // Manejar barrio personalizado
        const selectBarrio = document.getElementById('barrio');
        if (selectBarrio && selectBarrio.value === "otro") {
            const barrioPersonalizado = document.getElementById('barrio_personalizado');
            if (barrioPersonalizado && barrioPersonalizado.value.trim()) {
                // Crear un campo oculto con el valor real del barrio
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'barrio';
                hiddenInput.value = barrioPersonalizado.value.trim();
                this.appendChild(hiddenInput);
            }
        }

        // Validación personalizada puede agregarse aquí
        // if (!validarFormulario()) {
        //     e.preventDefault();
        //     return false;
        // }
    });
}

/**
 * Función para validación personalizada del formulario
 * @returns {boolean} True si el formulario es válido
 */
function validarFormulario() {
    // Implementar validaciones personalizadas aquí
    // Ejemplo:
    const nombres = document.getElementById('nombres');
    const apellidos = document.getElementById('apellidos');
    const celular = document.getElementById('celular');
    
    let isValid = true;
    
    // Validar campos obligatorios
    if (!nombres || nombres.value.trim() === '') {
        mostrarError(nombres, 'El nombre es obligatorio');
        isValid = false;
    }
    
    if (!apellidos || apellidos.value.trim() === '') {
        mostrarError(apellidos, 'Los apellidos son obligatorios');
        isValid = false;
    }
    
    if (!celular || celular.value.trim() === '') {
        mostrarError(celular, 'El celular es obligatorio');
        isValid = false;
    }
    
    return isValid;
}

/**
 * Muestra un mensaje de error para un campo
 * @param {HTMLElement} elemento - El elemento con error
 * @param {string} mensaje - El mensaje de error
 */
function mostrarError(elemento, mensaje) {
    // Remover mensaje de error previo si existe
    const errorPrevio = elemento.parentElement.querySelector('.invalid-feedback');
    if (errorPrevio) {
        errorPrevio.remove();
    }
    
    // Agregar clase de error
    elemento.classList.add('is-invalid');
    
    // Crear y agregar mensaje de error
    const errorDiv = document.createElement('div');
    errorDiv.className = 'invalid-feedback';
    errorDiv.textContent = mensaje;
    elemento.parentElement.appendChild(errorDiv);
}