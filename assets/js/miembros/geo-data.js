/**
 * Datos y funciones para manejar países, estados/departamentos y ciudades
 */

// Datos de países, departamentos y ciudades
const paisesData = {
    "Colombia": {
        "Amazonas": ["Leticia", "Puerto Nariño", "El Encanto", "La Chorrera", "La Pedrera", "La Victoria", "Mirití-Paraná", "Puerto Alegría", "Puerto Arica", "Santander", "Tarapacá"],
        "Antioquia": ["Medellín", "Bello", "Envigado", "Itagüí", "Rionegro", "Sabaneta", "Apartadó", "Turbo", "Caucasia", "Copacabana", "La Estrella", "Santa Fe de Antioquia"],
        "Arauca": ["Arauca", "Arauquita", "Cravo Norte", "Fortul", "Puerto Rondón", "Saravena", "Tame"],
        "Atlántico": ["Barranquilla", "Soledad", "Malambo", "Sabanalarga", "Baranoa", "Puerto Colombia", "Galapa", "Santo Tomás"],
        "Bogotá D.C.": ["Bogotá"],
        "Bolívar": ["Cartagena", "Magangué", "El Carmen de Bolívar", "Turbaco", "Arjona", "San Juan Nepomuceno", "María La Baja", "Mompós"],
        "Boyacá": ["Tunja", "Duitama", "Sogamoso", "Chiquinquirá", "Paipa", "Moniquirá", "Puerto Boyacá", "Samacá"],
        "Caldas": ["Manizales", "La Dorada", "Chinchiná", "Villamaría", "Anserma", "Riosucio", "Manzanares", "Pensilvania"],
        "Caquetá": ["Florencia", "San Vicente del Caguán", "Puerto Rico", "El Doncello", "Belén de Los Andaquíes", "Albania", "Solano", "Solita"],
        "Casanare": ["Yopal", "Aguazul", "Villanueva", "Tauramena", "Paz de Ariporo", "Monterrey", "Maní", "Hato Corozal"],
        "Cauca": ["Popayán", "Santander de Quilichao", "Puerto Tejada", "Patía", "Bolívar", "Cajibío", "El Tambo", "Miranda"],
        "Cesar": ["Valledupar", "Aguachica", "Agustín Codazzi", "Bosconia", "El Copey", "El Paso", "La Jagua de Ibirico", "San Alberto"],
        "Chocó": ["Quibdó", "Istmina", "Tadó", "Bahía Solano", "Acandí", "Condoto", "El Carmen de Atrato", "Riosucio"],
        "Córdoba": ["Montería", "Cereté", "Lorica", "Montelíbano", "Planeta Rica", "Sahagún", "Tierralta", "Valencia"],
        "Cundinamarca": ["Soacha", "Facatativá", "Zipaquirá", "Fusagasugá", "Chía", "Mosquera", "Madrid", "Funza", "Cajicá", "Girardot"],
        "Guainía": ["Inírida"],
        "Guaviare": ["San José del Guaviare", "Calamar", "El Retorno", "Miraflores"],
        "Huila": ["Neiva", "Pitalito", "Garzón", "La Plata", "Campoalegre", "Palermo", "San Agustín", "Gigante"],
        "La Guajira": ["Riohacha", "Maicao", "Uribia", "San Juan del Cesar", "Villanueva", "Barrancas", "Fonseca", "Dibulla"],
        "Magdalena": ["Santa Marta", "Ciénaga", "Fundación", "Plato", "Pivijay", "El Banco", "Aracataca", "Zona Bananera"],
        "Meta": ["Villavicencio", "Acacías", "Granada", "Puerto López", "La Macarena", "Cumaral", "Puerto Gaitán", "San Martín"],
        "Nariño": ["Pasto", "Tumaco", "Ipiales", "La Unión", "Samaniego", "Túquerres", "Barbacoas", "Sandoná"],
        "Norte de Santander": ["Cúcuta", "Ocaña", "Pamplona", "Villa del Rosario", "Los Patios", "El Zulia", "Tibú", "Abrego"],
        "Putumayo": ["Mocoa", "Puerto Asís", "Puerto Guzmán", "Orito", "Valle del Guamuez", "San Francisco", "Villagarzón", "Santiago"],
        "Quindío": ["Armenia", "Calarcá", "Montenegro", "Quimbaya", "La Tebaida", "Circasia", "Filandia", "Salento"],
        "Risaralda": ["Pereira", "Dosquebradas", "Santa Rosa de Cabal", "La Virginia", "Quinchía", "Belén de Umbría", "Apía", "Santuario"],
        "San Andrés y Providencia": ["San Andrés", "Providencia"],
        "Santander": ["Bucaramanga", "Floridablanca", "Girón", "Piedecuesta", "Barrancabermeja", "San Gil", "Barbosa", "Socorro"],
        "Sucre": ["Sincelejo", "Corozal", "San Marcos", "Majagual", "San Onofre", "San Benito Abad", "Sampués", "Tolú"],
        "Tolima": ["Ibagué", "Espinal", "Mariquita", "Chaparral", "Líbano", "Honda", "Flandes", "Melgar"],
        "Valle del Cauca": ["Cali", "Buenaventura", "Palmira", "Tuluá", "Cartago", "Buga", "Jamundí", "Yumbo"],
        "Vaupés": ["Mitú", "Carurú", "Taraira"],
        "Vichada": ["Puerto Carreño", "La Primavera", "Santa Rosalía", "Cumaribo"]
    },
    "México": {
        "Aguascalientes": ["Aguascalientes", "Calvillo", "Jesús María", "Pabellón de Arteaga", "Rincón de Romos"],
        "Baja California": ["Tijuana", "Mexicali", "Ensenada", "Tecate", "Rosarito"],
        "Baja California Sur": ["La Paz", "Los Cabos", "Comondú", "Loreto", "Mulegé"],
        "Ciudad de México": ["Ciudad de México"],
        "Jalisco": ["Guadalajara", "Zapopan", "Tlaquepaque", "Tonalá", "Puerto Vallarta"],
        "Nuevo León": ["Monterrey", "Guadalupe", "San Nicolás", "Apodaca", "Santa Catarina"]
    },
    "Estados Unidos": {
        "California": ["Los Ángeles", "San Francisco", "San Diego", "Sacramento", "Oakland"],
        "Florida": ["Miami", "Orlando", "Tampa", "Jacksonville", "Fort Lauderdale"],
        "Nueva York": ["Nueva York", "Buffalo", "Rochester", "Syracuse", "Albany"],
        "Texas": ["Houston", "Dallas", "Austin", "San Antonio", "El Paso"]
    },
    "España": {
        "Andalucía": ["Sevilla", "Málaga", "Granada", "Córdoba", "Cádiz"],
        "Cataluña": ["Barcelona", "Girona", "Lleida", "Tarragona"],
        "Madrid": ["Madrid", "Alcalá de Henares", "Aranjuez", "Móstoles", "Alcorcón"]
    },
    "Argentina": {
        "Buenos Aires": ["Buenos Aires", "La Plata", "Mar del Plata", "Bahía Blanca"],
        "Córdoba": ["Córdoba", "Villa María", "Río Cuarto", "Carlos Paz"],
        "Santa Fe": ["Rosario", "Santa Fe", "Venado Tuerto", "Rafaela"]
    }
};

/**
 * Función para cargar los países en el dropdown
 */
function cargarPaises() {
    const selectPais = document.getElementById('pais');
    if (!selectPais) return;
    
    // Limpiar opciones existentes
    selectPais.innerHTML = '<option value="">-- Seleccione un país --</option>';
    
    // Agregar cada país como una opción
    Object.keys(paisesData).forEach(pais => {
        const option = document.createElement('option');
        option.value = pais;
        option.textContent = pais;
        selectPais.appendChild(option);
    });
    
    // Agregar opción para país personalizado
    const otroOption = document.createElement('option');
    otroOption.value = "otro";
    otroOption.textContent = "-- Otro país --";
    selectPais.appendChild(otroOption);
    
    // Obtener el país guardado (si existe)
    const paisGuardado = selectPais.dataset.valor || '';
    
    if (paisGuardado) {
        // Si el país guardado existe en la lista, seleccionarlo
        if (Object.keys(paisesData).includes(paisGuardado)) {
            selectPais.value = paisGuardado;
        } else if (paisGuardado.trim() !== '') {
            // Si es un valor personalizado, crear la opción y seleccionarla
            const option = document.createElement('option');
            option.value = paisGuardado;
            option.textContent = paisGuardado + " (personalizado)";
            selectPais.insertBefore(option, otroOption);
            selectPais.value = paisGuardado;
        }
        // Cargar los estados/departamentos correspondientes
        cargarEstadosDepartamentos(paisGuardado);
    } else {
        // Si no hay país guardado, seleccionar Colombia por defecto
        selectPais.value = "Colombia";
        cargarEstadosDepartamentos("Colombia");
    }
}

/**
 * Función para cargar los estados/departamentos según el país seleccionado
 */
function cargarEstadosDepartamentos(pais) {
    const selectEstadoDepto = document.getElementById('estado_departamento');
    if (!selectEstadoDepto) return;
    
    // Limpiar opciones existentes
    selectEstadoDepto.innerHTML = '<option value="">-- Seleccione un estado/departamento --</option>';
    
    if (!pais || pais === "otro") {
        selectEstadoDepto.disabled = true;
        const ciudadSelect = document.getElementById('ciudad');
        if (ciudadSelect) {
            ciudadSelect.disabled = true;
            ciudadSelect.innerHTML = '<option value="">-- Seleccione primero un estado/departamento --</option>';
        }
        return;
    }
    
    selectEstadoDepto.disabled = false;
    
    // Si el país existe en nuestros datos, cargar sus estados/departamentos
    if (paisesData[pais]) {
        Object.keys(paisesData[pais]).forEach(estadoDepto => {
            const option = document.createElement('option');
            option.value = estadoDepto;
            option.textContent = estadoDepto;
            selectEstadoDepto.appendChild(option);
        });
    }
    
    // Agregar opción para estado/departamento personalizado
    const otroOption = document.createElement('option');
    otroOption.value = "otro";
    otroOption.textContent = "-- Otro (especificar) --";
    selectEstadoDepto.appendChild(otroOption);
    
    // Obtener el estado/departamento guardado (si existe)
    const estadoDeptoGuardado = selectEstadoDepto.dataset.valor || '';
    
    if (estadoDeptoGuardado) {
        // Si el estado/departamento guardado existe en la lista, seleccionarlo
        if (paisesData[pais] && Object.keys(paisesData[pais]).includes(estadoDeptoGuardado)) {
            selectEstadoDepto.value = estadoDeptoGuardado;
        } else if (estadoDeptoGuardado.trim() !== '') {
            // Si es un valor personalizado, crear la opción y seleccionarla
            const option = document.createElement('option');
            option.value = estadoDeptoGuardado;
            option.textContent = estadoDeptoGuardado + " (personalizado)";
            selectEstadoDepto.insertBefore(option, otroOption);
            selectEstadoDepto.value = estadoDeptoGuardado;
        }
        // Cargar las ciudades correspondientes
        cargarCiudades(pais, estadoDeptoGuardado);
    }
}

/**
 * Función para cargar ciudades según el estado/departamento seleccionado
 */
function cargarCiudades(pais, estadoDepto) {
    const selectCiudad = document.getElementById('ciudad');
    if (!selectCiudad) return;
    
    // Limpiar opciones existentes
    selectCiudad.innerHTML = '<option value="">-- Seleccione una ciudad --</option>';
    
    if (!estadoDepto || estadoDepto === "otro") {
        selectCiudad.disabled = true;
        return;
    }
    
    selectCiudad.disabled = false;
    
    // Si el país y estado/departamento existen en nuestros datos, cargar sus ciudades
    if (paisesData[pais] && paisesData[pais][estadoDepto]) {
        paisesData[pais][estadoDepto].forEach(ciudad => {
            const option = document.createElement('option');
            option.value = ciudad;
            option.textContent = ciudad;
            selectCiudad.appendChild(option);
        });
    }
    
    // Agregar opción para ciudad personalizada
    const otroOption = document.createElement('option');
    otroOption.value = "otro";
    otroOption.textContent = "-- Otra (especificar) --";
    selectCiudad.appendChild(otroOption);
    
    // Obtener la ciudad guardada (si existe)
    const ciudadGuardada = selectCiudad.dataset.valor || '';
    
    if (ciudadGuardada) {
        // Si la ciudad guardada existe en la lista, seleccionarla
        if (paisesData[pais] && paisesData[pais][estadoDepto] && 
            paisesData[pais][estadoDepto].includes(ciudadGuardada)) {
            selectCiudad.value = ciudadGuardada;
        } else if (ciudadGuardada.trim() !== '') {
            // Si es un valor personalizado, crear la opción y seleccionarla
            const option = document.createElement('option');
            option.value = ciudadGuardada;
            option.textContent = ciudadGuardada + " (personalizado)";
            selectCiudad.insertBefore(option, otroOption);
            selectCiudad.value = ciudadGuardada;
        }
    }
}

/**
 * Maneja la selección de "Otro país"
 */
function manejarPaisPersonalizado() {
    const selectPais = document.getElementById('pais');
    if (selectPais && selectPais.value === "otro") {
        const nuevoPais = prompt("Ingrese el nombre del país:");
        if (nuevoPais && nuevoPais.trim() !== "") {
            // Crear nueva opción y seleccionarla
            const option = document.createElement('option');
            option.value = nuevoPais;
            option.textContent = nuevoPais + " (personalizado)";
            
            // Insertar antes de la opción "Otro"
            selectPais.insertBefore(option, selectPais.lastChild);
            selectPais.value = nuevoPais;
            
            // Habilitar el selector de estados/departamentos para entrada manual
            const estadoDeptSelect = document.getElementById('estado_departamento');
            if (estadoDeptSelect) {
                estadoDeptSelect.disabled = false;
                estadoDeptSelect.innerHTML = '<option value="">-- Seleccione un estado/departamento --</option>' +
                                           '<option value="otro">-- Otro (especificar) --</option>';
            }
            
            // Resetear el selector de ciudades
            const ciudadSelect = document.getElementById('ciudad');
            if (ciudadSelect) {
                ciudadSelect.disabled = true;
                ciudadSelect.innerHTML = '<option value="">-- Seleccione primero un estado/departamento --</option>';
            }
        } else {
            selectPais.value = ""; // Restablecer si se canceló
        }
    }
}

/**
 * Maneja la selección de "Otro estado/departamento"
 */
function manejarEstadoDeptoPersonalizado() {
    const selectEstadoDepto = document.getElementById('estado_departamento');
    if (selectEstadoDepto && selectEstadoDepto.value === "otro") {
        const nuevoEstadoDepto = prompt("Ingrese el nombre del estado o departamento:");
        if (nuevoEstadoDepto && nuevoEstadoDepto.trim() !== "") {
            // Crear nueva opción y seleccionarla
            const option = document.createElement('option');
            option.value = nuevoEstadoDepto;
            option.textContent = nuevoEstadoDepto + " (personalizado)";
            
            // Insertar antes de la opción "Otro"
            selectEstadoDepto.insertBefore(option, selectEstadoDepto.lastChild);
            selectEstadoDepto.value = nuevoEstadoDepto;
            
            // Habilitar el selector de ciudades para entrada manual
            const ciudadSelect = document.getElementById('ciudad');
            if (ciudadSelect) {
                ciudadSelect.disabled = false;
                ciudadSelect.innerHTML = '<option value="">-- Seleccione una ciudad --</option>' +
                                       '<option value="otro">-- Otra (especificar) --</option>';
            }
        } else {
            selectEstadoDepto.value = ""; // Restablecer si se canceló
        }
    }
}

/**
 * Maneja la selección de "Otra ciudad"
 */
function manejarCiudadPersonalizada() {
    const selectCiudad = document.getElementById('ciudad');
    if (selectCiudad && selectCiudad.value === "otro") {
        const nuevaCiudad = prompt("Ingrese el nombre de la ciudad:");
        if (nuevaCiudad && nuevaCiudad.trim() !== "") {
            // Crear nueva opción y seleccionarla
            const option = document.createElement('option');
            option.value = nuevaCiudad;
            option.textContent = nuevaCiudad + " (personalizado)";
            
            // Insertar antes de la opción "Otro"
            selectCiudad.insertBefore(option, selectCiudad.lastChild);
            selectCiudad.value = nuevaCiudad;
        } else {
            selectCiudad.value = ""; // Restablecer si se canceló
        }
    }
}