function guardarTablaIndividual(tabla) {
    if (typeof baseUrl === 'undefined' || typeof miembroId === 'undefined') {
        console.error("Error: Variables baseUrl o miembroId no definidas");
        mostrarMensaje('danger', "Error de configuración");
        return;
    }

    // Construir la URL correctamente
    const url = `${baseUrl}/miembros/actualizar/${miembroId}/tabla/${tabla}`;
    console.log("Enviando petición a:", url);
    
    const formId = `form-${tabla}`;
    const formulario = document.getElementById(formId);
    
    if (!formulario) {
        console.error(`Formulario #${formId} no encontrado`);
        mostrarMensaje('danger', `Error: Formulario no encontrado`);
        return;
    }
    
    const formData = new FormData(formulario);
    
    // Mostrar datos que se están enviando (para depuración)
    console.log("Datos enviados:", Object.fromEntries(formData));
    
    fetch(url, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            return response.text().then(text => {
                console.error("Error en la respuesta:", text);
                try {
                    // Intentar parsear como JSON por si acaso
                    return JSON.parse(text);
                } catch (e) {
                    // Si no es JSON, lanzar error con el texto
                    throw new Error(`Error del servidor: ${text.substring(0, 100)}...`);
                }
            });
        }
        return response.json();
    })
    .then(data => {
        console.log("Respuesta:", data);
        if (data.success) {
            mostrarMensaje('success', data.message || "Datos actualizados correctamente");
        } else {
            mostrarMensaje('danger', data.message || "Error al actualizar datos");
        }
    })
    .catch(error => {
        console.error("Error en la petición:", error);
        mostrarMensaje('danger', error.message || "Error de comunicación con el servidor");
    });
}

function mostrarMensaje(tipo, mensaje) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${tipo} alert-dismissible fade show`;
    alertDiv.role = 'alert';
    alertDiv.innerHTML = `
        ${mensaje}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    `;
    
    // Insertar al inicio del contenedor principal
    const container = document.querySelector('.container');
    container.insertBefore(alertDiv, container.firstChild);
    
    // Auto-ocultar después de 5 segundos
    setTimeout(() => {
        alertDiv.classList.remove('show');
        setTimeout(() => alertDiv.remove(), 500);
    }, 5000);
}