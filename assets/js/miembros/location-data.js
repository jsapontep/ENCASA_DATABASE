/**
 * Datos y funciones para manejar localidades y barrios de Bogotá
 */

// Datos de las localidades y barrios de Bogotá
const localidadesBarrios = {
    "Usaquén": ["Cedritos", "Santa Bárbara", "Unicentro", "Verbenal", "San Cristóbal Norte", "Toberín", "Usaquén", "Country Club", "Santa Ana"],
    "Chapinero": ["Chapinero Central", "Chicó", "La Cabrera", "El Nogal", "El Refugio", "Rosales", "Quinta Camacho", "Chapinero Alto", "Pardo Rubio"],
    "Santa Fe": ["La Candelaria", "Las Nieves", "Las Cruces", "Egipto", "Centro Administrativo", "La Macarena", "El Dorado", "Sagrado Corazón"],
    "San Cristóbal": ["San Blas", "20 de Julio", "La Victoria", "Sosiego", "Altamira", "Juan Rey", "La Gloria", "Los Libertadores"],
    "Usme": ["Comuneros", "Alfonso López", "Danubio Azul", "El Virrey", "Yomasa", "Santa Librada", "Gran Yomasa", "Chuniza"],
    "Tunjuelito": ["Tunjuelito", "Venecia", "San Carlos", "Abraham Lincoln", "San Benito", "El Carmen", "Samore"],
    "Bosa": ["Bosa Central", "El Recreo", "Porvenir", "Olarte", "La Estación", "El Apogeo", "San Pablo", "La Libertad"],
    "Kennedy": ["Kennedy Central", "Castilla", "Timiza", "Patio Bonito", "Américas", "Bavaria", "Carvajal", "Marsella", "Mandalay"],
    "Fontibón": ["Fontibón Centro", "Modelia", "Capellanía", "Hayuelos", "Aeropuerto El Dorado", "Salitre Occidental", "Granjas de Techo"],
    "Engativá": ["Engativá Centro", "Las Ferias", "Minuto de Dios", "Boyacá Real", "Santa Cecilia", "Bolivia", "Álamos", "Jardín Botánico"],
    "Suba": ["Niza", "La Alhambra", "Prado", "Spring", "Suba Centro", "El Rincón", "Tibabuyes", "Club Los Lagartos", "Ciudad Jardín Norte"],
    "Barrios Unidos": ["12 de Octubre", "Los Alcázares", "Park Way", "La Castellana", "Andes", "Rionegro", "Colombia", "San Fernando"],
    "Teusaquillo": ["Teusaquillo", "La Soledad", "Palermo", "Galerías", "Quinta Paredes", "Ciudad Universitaria", "Salitre Oriental", "Pablo VI"],
    "Los Mártires": ["Santa Isabel", "La Sabana", "El Listón", "Samper Mendoza", "Ricaurte", "Voto Nacional", "La Estanzuela", "Eduardo Santos"],
    "Antonio Nariño": ["Restrepo", "Ciudad Jardín", "Villa Mayor", "La Fragua", "Policarpa", "Santander", "Caracas"],
    "Puente Aranda": ["Ciudad Montes", "Muzu", "San Rafael", "Puente Aranda", "Salazar Gómez", "Zona Industrial", "Cundinamarca", "Primavera"],
    "La Candelaria": ["La Catedral", "Centro Administrativo", "Santa Bárbara", "La Concordia", "Las Aguas", "Egipto", "Belén", "Nueva Santa Fe"],
    "Rafael Uribe Uribe": ["Quiroga", "San José", "Marco Fidel Suárez", "Marruecos", "Diana Turbay", "Gustavo Restrepo", "Olaya", "Inglés"],
    "Ciudad Bolívar": ["Vista Hermosa", "Lucero", "Ismael Perdomo", "Jerusalem", "San Francisco", "Candelaria La Nueva", "Sierra Morena", "Arborizadora"],
    "Sumapaz": ["Nazareth", "San Juan de Sumapaz", "La Unión", "Tunal Alto", "Betania", "Nueva Granada", "San José"]
};

/**
 * Función para cargar las localidades en el dropdown
 */
function cargarLocalidades() {
    const selectLocalidad = document.getElementById('localidad');
    if (!selectLocalidad) return;
    
    // Limpiar opciones existentes
    selectLocalidad.innerHTML = '<option value="">-- Seleccione una localidad --</option>';
    
    // Agregar cada localidad como una opción
    Object.keys(localidadesBarrios).forEach(localidad => {
        const option = document.createElement('option');
        option.value = localidad;
        option.textContent = localidad;
        selectLocalidad.appendChild(option);
    });
    
    // Obtener la localidad guardada (si existe)
    const localidadGuardada = selectLocalidad.dataset.valor || '';
    
    if (localidadGuardada) {
        // Si la localidad guardada existe en la lista, seleccionarla
        if (Object.keys(localidadesBarrios).includes(localidadGuardada)) {
            selectLocalidad.value = localidadGuardada;
        } else if (localidadGuardada.trim() !== '') {
            // Si es un valor personalizado, crear la opción y seleccionarla
            const option = document.createElement('option');
            option.value = localidadGuardada;
            option.textContent = localidadGuardada + " (personalizado)";
            selectLocalidad.appendChild(option);
            selectLocalidad.value = localidadGuardada;
        }
        // Cargar los barrios correspondientes
        cargarBarrios(localidadGuardada);
    } else {
        // Si no hay localidad guardada, seleccionar "Engativá" por defecto
        const defaultLocalidad = "Engativá";
        selectLocalidad.value = defaultLocalidad;
        cargarBarrios(defaultLocalidad);
    }
}

/**
 * Función para cargar los barrios según la localidad seleccionada
 */
function cargarBarrios(localidad) {
    const selectBarrio = document.getElementById('barrio');
    if (!selectBarrio) return;
    
    // Limpiar opciones existentes
    selectBarrio.innerHTML = '<option value="">-- Seleccione un barrio --</option>';
    
    // Si no hay localidad seleccionada, no hacer nada más
    if (!localidad) return;
    
    // Agregar cada barrio de la localidad como una opción
    const barrios = localidadesBarrios[localidad] || [];
    barrios.forEach(barrio => {
        const option = document.createElement('option');
        option.value = barrio;
        option.textContent = barrio;
        selectBarrio.appendChild(option);
    });
    
    // Agregar opción para barrio personalizado
    const otroOption = document.createElement('option');
    otroOption.value = "otro";
    otroOption.textContent = "-- Otro barrio --";
    selectBarrio.appendChild(otroOption);
    
    // Obtener el barrio guardado (si existe)
    const barrioGuardado = selectBarrio.dataset.valor || '';
    
    if (barrioGuardado) {
        // Si el barrio guardado existe en la lista, seleccionarlo
        if (barrios.includes(barrioGuardado)) {
            selectBarrio.value = barrioGuardado;
        } else if (barrioGuardado.trim() !== '') {
            // Si es un barrio personalizado, crear la opción y seleccionarla
            selectBarrio.value = "otro";
            // Mostrar el campo de barrio personalizado
            document.getElementById('barrio_personalizado_container').style.display = 'block';
            document.getElementById('barrio_personalizado').value = barrioGuardado;
        }
    }
}

/**
 * Maneja la selección de "Otro barrio"
 */
function manejarBarrioPersonalizado() {
    const selectBarrio = document.getElementById('barrio');
    const container = document.getElementById('barrio_personalizado_container');
    
    if (selectBarrio.value === "otro") {
        container.style.display = 'block';
    } else {
        container.style.display = 'none';
    }
}