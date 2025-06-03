/**
 * Manejador para enviar los datos de cada tabla individual
 */
const TablaProcessor = {
    
    /**
     * Inicializa los manejadores de eventos para los botones de prueba
     */
    init: function() {
        console.log('TablaProcessor inicializando...');
        
        // Registrar listeners para cada botón de prueba
        document.querySelectorAll('.btn-probar-tabla').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const tabla = this.getAttribute('data-tabla');
                const formulario = document.getElementById('formMiembro');
                const miembroId = document.querySelector('input[name="id"]')?.value || null;
                
                TablaProcessor.enviarTabla(tabla, miembroId, formulario);
            });
        });
        
        console.log('TablaProcessor inicializado');
    },
    
    /**
     * Envía los datos de una tabla específica al servidor
     */
    enviarTabla: function(tabla, miembroId, formulario) {
        // Mostrar indicador de carga
        const boton = document.querySelector(`button[data-tabla="${tabla}"]`);
        if (!boton) {
            console.error('No se encontró el botón para la tabla:', tabla);
            return;
        }
        
        const textoOriginal = boton.innerHTML;
        boton.disabled = true;
        boton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Procesando...';
        
        // Crear FormData con solo los datos relevantes para esta tabla
        const formData = new FormData(formulario);
        const tablaData = new FormData();
        
        // Si es edición, incluir el ID
        if (miembroId) {
            tablaData.append('id', miembroId);
        }
        
        // Añadir solo los campos correspondientes a esta tabla
        for (let pair of formData.entries()) {
            // Filtrar campos según la tabla seleccionada
            if (this.perteneceATabla(pair[0], tabla)) {
                tablaData.append(pair[0], pair[1]);
            }
        }
        
        // Incluir la foto si estamos en la tabla principal y hay una foto seleccionada
        if (tabla === 'miembro' && formData.has('foto')) {
            const fotoInput = formulario.querySelector('input[name="foto"]');
            if (fotoInput && fotoInput.files && fotoInput.files.length > 0) {
                tablaData.append('foto', fotoInput.files[0]);
            }
        }
        
        console.log(`Enviando datos de tabla: ${tabla}`);
        
        // Determinar la URL según si es creación o edición
        const baseUrl = miembroId ? 
            `/ENCASA_DATABASE/miembros/actualizar/${miembroId}/tabla/${tabla}` : 
            `/ENCASA_DATABASE/miembros/guardar/tabla/${tabla}`;
        
        // Enviar la solicitud AJAX
        fetch(baseUrl, {
            method: 'POST',
            body: tablaData
        })
        .then(response => {
            // Verificar primero si la respuesta es de tipo JSON
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                return response.json().then(data => {
                    if (!response.ok) {
                        throw new Error(data.message || `Error HTTP: ${response.status}`);
                    }
                    return data;
                });
            } else {
                // Si no es JSON, obtenemos el texto para depuración
                return response.text().then(text => {
                    console.error('Respuesta no JSON:', text);
                    throw new Error('La respuesta del servidor no es JSON válido');
                });
            }
        })
        .then(data => {
            if (data.success) {
                // Mostrar mensaje de éxito
                this.mostrarMensaje(`Tabla "${tabla}" guardada correctamente`, 'success');
                
                // Si es una operación de creación y recibimos un ID, actualizar el formulario
                if (!miembroId && data.miembro_id) {
                    // Crear campo oculto de ID si no existe
                    if (!document.querySelector('input[name="id"]')) {
                        const idInput = document.createElement('input');
                        idInput.type = 'hidden';
                        idInput.name = 'id';
                        idInput.value = data.miembro_id;
                        formulario.appendChild(idInput);
                    } else {
                        document.querySelector('input[name="id"]').value = data.miembro_id;
                    }
                }
            } else {
                // Mostrar mensaje de error
                this.mostrarMensaje(`Error: ${data.message || 'No se pudo guardar la tabla'}`, 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            this.mostrarMensaje(`Error al procesar la solicitud: ${error.message}`, 'danger');
        })
        .finally(() => {
            // Restaurar el botón
            boton.disabled = false;
            boton.innerHTML = textoOriginal;
        });
    },
    
    /**
     * Determina si un campo del formulario pertenece a la tabla especificada
     */
    perteneceATabla: function(fieldName, tabla) {
        // Mapeo de campos por tabla
        const mapeoTablas = {
            'miembro': ['nombres', 'apellidos', 'celular', 'localidad', 'barrio', 'fecha_nacimiento', 
                      'fecha_ingreso_iglesia', 'estado_espiritual', 'conector', 'invitado_por', 
                      'habeas_data', 'estado_miembro', 'foto'],
            'contacto': fieldName.startsWith('contacto['),
            'estudiostrabajo': fieldName.startsWith('estudios['),
            'tallas': fieldName.startsWith('tallas['),
            'saludemergencias': fieldName.startsWith('salud['),
            'carrerabiblica': fieldName.startsWith('carrera[')
        };
        
        if (mapeoTablas[tabla] === true) {
            return true;
        } else if (Array.isArray(mapeoTablas[tabla])) {
            return mapeoTablas[tabla].includes(fieldName);
        }
        
        return false;
    },
    
    /**
     * Muestra un mensaje al usuario
     */
    mostrarMensaje: function(mensaje, tipo = 'success') {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${tipo} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            ${mensaje}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        
        // Insertar al principio del formulario o en un área designada para mensajes
        const mensajesArea = document.getElementById('mensajes-area');
        if (mensajesArea) {
            mensajesArea.appendChild(alertDiv);
        } else {
            const formulario = document.getElementById('formMiembro');
            formulario.insertBefore(alertDiv, formulario.firstChild);
        }
        
        // Desaparecer automáticamente después de 5 segundos
        setTimeout(() => {
            alertDiv.classList.remove('show');
            setTimeout(() => alertDiv.remove(), 300);
        }, 5000);
    }
};

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    TablaProcessor.init();
    
    // Mapeo de tablas a sus campos específicos
    const tablasCampos = {
        'informaciongeneral': ['nombres', 'apellidos', 'celular', 'localidad', 'barrio', 'fecha_nacimiento', 'estado_espiritual', 'recorrido_espiritual', 'tipo_miembro'],
        'contacto': ['tipo_documento', 'numero_documento', 'telefono', 'correo_electronico', 'pais', 'ciudad', 'direccion', 'estado_civil'],
        'estudiostrabajo': ['nivel_educativo', 'profesion', 'ocupacion', 'empresa', 'cargo'],
        'tallas': ['camisa', 'pantalon', 'calzado'],
        'saludemergencias': ['tipo_sangre', 'alergias', 'medicamentos', 'contacto_emergencia', 'telefono_emergencia'],
        'carrerabiblica': ['nivel_actual', 'cursos_completados', 'fecha_ultimo_curso']
    };

    // Manejador para botones de guardar por sección
    document.querySelectorAll('.btn-probar-tabla').forEach(button => {
        button.addEventListener('click', function() {
            // Obtener el nombre de la tabla de data-tabla
            const tabla = this.getAttribute('data-tabla');
            
            // Verificar si es una tabla válida
            if (!tabla || !tablasCampos[tabla]) {
                console.error(`Error: Tabla "${tabla}" no reconocida`);
                mostrarMensaje(`Error: Tabla "${tabla}" no definida en el sistema`, 'danger');
                return;
            }
            
            // Obtener el ID del miembro del formulario principal
            const idMiembro = document.querySelector('input[name="id"]').value;
            if (!idMiembro) {
                mostrarMensaje('Error: No se pudo identificar el ID del miembro', 'danger');
                return;
            }
            
            // Mostrar indicador de carga
            this.disabled = true;
            const textoOriginal = this.innerHTML;
            this.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Guardando...';
            
            // Recopilar datos según la tabla
            const formData = new FormData();
            formData.append('id', idMiembro);
            formData.append('tabla', tabla);
            
            // Agregar campos específicos de la tabla
            const campos = tablasCampos[tabla];
            campos.forEach(campo => {
                const input = document.querySelector(`[name="${campo}"], [name="${tabla}[${campo}]"]`);
                if (input) {
                    if (input.type === 'checkbox') {
                        formData.append(`datos[${campo}]`, input.checked ? '1' : '0');
                    } else {
                        formData.append(`datos[${campo}]`, input.value);
                    }
                }
            });
            
            // Enviar petición
            fetch(`${APP_URL}/api/actualizar-seccion`, {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Error HTTP: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                this.disabled = false;
                this.innerHTML = textoOriginal;
                
                if (data.success) {
                    mostrarMensaje(`Sección "${tabla}" actualizada correctamente`, 'success');
                } else {
                    mostrarMensaje(data.message || `Error al guardar la sección "${tabla}"`, 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                this.disabled = false;
                this.innerHTML = textoOriginal;
                mostrarMensaje(`Error al procesar la solicitud: ${error.message}`, 'danger');
            });
        });
    });
    
    // Función para mostrar mensajes
    function mostrarMensaje(mensaje, tipo = 'success') {
        const contenedor = document.createElement('div');
        contenedor.className = `alert alert-${tipo} alert-dismissible fade show`;
        contenedor.style.position = 'fixed';
        contenedor.style.top = '20px';
        contenedor.style.right = '20px';
        contenedor.style.zIndex = '9999';
        contenedor.innerHTML = `
            ${mensaje}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        document.body.appendChild(contenedor);
        
        setTimeout(() => {
            contenedor.classList.remove('show');
            setTimeout(() => contenedor.remove(), 300);
        }, 5000);
    }
});

function guardarTablaIndividual(tabla) {
    if (typeof baseUrl === 'undefined' || typeof miembroId === 'undefined') {
        console.error("Error: Variables baseUrl o miembroId no definidas");
        mostrarMensaje('danger', "Error de configuración: No se puede determinar la URL o ID del miembro");
        return;
    }

    // Asegúrate de que el nombre de la tabla sea el correcto
    const nombreTablaCorrecta = tabla === 'miembro' ? 'informaciongeneral' : tabla;
    
    // URL para guardar (CORREGIDA)
    const url = `${baseUrl}/miembros/actualizar/${miembroId}/tabla/${nombreTablaCorrecta}`;
    
    // Verificar si el formulario existe
    const formulario = document.querySelector(`#form-${tabla}`);
    if (!formulario) {
        console.error(`No se encontró el formulario #form-${tabla}`);
        mostrarMensaje('danger', `Error: No se encontró el formulario para ${tabla}`);
        return;
    }

    // Recopilar datos y enviar
    const formData = new FormData(formulario);
    
    // Mapear nombres de campos si es necesario
    if (tabla === 'tallas') {
        if (formData.has('camisa')) {
            formData.set('talla_camisa', formData.get('camisa'));
            formData.delete('camisa');
        }
        // Otros mapeos de nombres
    }
    
    // Realizar petición AJAX
    fetch(url, {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            return response.text().then(text => {
                throw new Error(`Error del servidor: ${text.substring(0, 200)}...`);
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            mostrarMensaje('success', data.message || `Datos de ${tabla} actualizados correctamente`);
        } else {
            mostrarMensaje('danger', data.message || `Error al guardar ${tabla}`);
        }
    })
    .catch(error => {
        console.error("Error al guardar:", error);
        mostrarMensaje('danger', `Error: ${error.message}`);
    });
}