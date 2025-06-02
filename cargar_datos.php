<?php

// Script para cargar datos de muestra en todas las tablas
require_once 'app/config/database.php';

try {
    // Conectar a la base de datos
    $db = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Función para verificar si una tabla está vacía
    function tablaVacia($db, $tabla) {
        $stmt = $db->query("SELECT COUNT(*) FROM $tabla");
        return $stmt->fetchColumn() == 0;
    }
    
    echo "<h1>Cargando datos de muestra en la base de datos</h1>";
    
    // 1. ROLES (si no existen)
    if (tablaVacia($db, "Roles")) {
        $roles = [
            ['nombre' => 'Administrador', 'descripcion' => 'Acceso completo a todas las funcionalidades', 'nivel_permiso' => 3],
            ['nombre' => 'Pastor', 'descripcion' => 'Acceso a gestión de miembros y eventos', 'nivel_permiso' => 2],
            ['nombre' => 'Líder', 'descripcion' => 'Acceso limitado a gestión de miembros asignados', 'nivel_permiso' => 1]
        ];
        
        $stmt = $db->prepare("INSERT INTO Roles (nombre, descripcion, nivel_permiso) VALUES (:nombre, :descripcion, :nivel_permiso)");
        
        foreach ($roles as $rol) {
            $stmt->bindParam(':nombre', $rol['nombre']);
            $stmt->bindParam(':descripcion', $rol['descripcion']);
            $stmt->bindParam(':nivel_permiso', $rol['nivel_permiso']);
            $stmt->execute();
        }
        
        echo "<p>✅ Roles insertados correctamente</p>";
    } else {
        echo "<p>⏭️ La tabla Roles ya contiene datos</p>";
    }
    
    // 2. ASIGNAR ROLES A USUARIOS EXISTENTES (si no tienen)
    $stmt = $db->query("SELECT COUNT(*) FROM RolesUsuario");
    if ($stmt->fetchColumn() == 0) {
        // Obtener usuarios existentes
        $stmt = $db->query("SELECT id FROM Usuarios");
        $usuarios = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        // Obtener roles existentes
        $stmt = $db->query("SELECT id FROM Roles");
        $roles = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        if (count($roles) > 0) {
            $stmt = $db->prepare("INSERT INTO RolesUsuario (usuario_id, rol_id) VALUES (:usuario_id, :rol_id)");
            
            // Asignar rol de Administrador al primer usuario
            if (isset($usuarios[0]) && isset($roles[0])) {
                $stmt->bindParam(':usuario_id', $usuarios[0]);
                $stmt->bindParam(':rol_id', $roles[0]);
                $stmt->execute();
            }
            
            // Asignar rol de Pastor al segundo usuario si existe
            if (isset($usuarios[1]) && isset($roles[1])) {
                $stmt->bindParam(':usuario_id', $usuarios[1]);
                $stmt->bindParam(':rol_id', $roles[1]);
                $stmt->execute();
            }
            
            // Asignar roles adicionales si hay más usuarios
            for ($i = 2; $i < count($usuarios); $i++) {
                $rol_id = $roles[array_rand($roles)]; // Rol aleatorio
                $stmt->bindParam(':usuario_id', $usuarios[$i]);
                $stmt->bindParam(':rol_id', $rol_id);
                $stmt->execute();
            }
            
            echo "<p>✅ Roles de usuario asignados correctamente</p>";
        }
    } else {
        echo "<p>⏭️ Ya existen asignaciones de roles a usuarios</p>";
    }
    
    // 3. INFORMACIÓN GENERAL (MIEMBROS)
    if (tablaVacia($db, "InformacionGeneral")) {
        $miembros = [
            [
                'nombres' => 'Juan Carlos', 
                'apellidos' => 'Martínez Rodríguez', 
                'celular' => '+573111234567', 
                'localidad' => 'Kennedy', 
                'barrio' => 'Castilla', 
                'fecha_nacimiento' => '1985-06-15',
                'conector' => 'Invitación directa',
                'estado_espiritual' => 'Activo',
                'foto' => 'juan_martinez.jpg'
            ],
            [
                'nombres' => 'María Fernanda', 
                'apellidos' => 'López García', 
                'celular' => '+573129876543', 
                'localidad' => 'Chapinero', 
                'barrio' => 'La Soledad', 
                'fecha_nacimiento' => '1990-03-22',
                'conector' => 'Redes sociales',
                'estado_espiritual' => 'Nuevo',
                'foto' => 'maria_lopez.jpg'
            ],
            [
                'nombres' => 'Carlos Eduardo', 
                'apellidos' => 'González Pérez', 
                'celular' => '+573145678901', 
                'localidad' => 'Suba', 
                'barrio' => 'Niza', 
                'fecha_nacimiento' => '1978-11-10',
                'conector' => 'Familiar',
                'estado_espiritual' => 'Activo',
                'foto' => 'carlos_gonzalez.jpg'
            ],
            [
                'nombres' => 'Ana María', 
                'apellidos' => 'Sánchez Torres', 
                'celular' => '+573187654321', 
                'localidad' => 'Teusaquillo', 
                'barrio' => 'Galerías', 
                'fecha_nacimiento' => '1995-08-03',
                'conector' => 'Evento evangelístico',
                'estado_espiritual' => 'En formación',
                'foto' => 'ana_sanchez.jpg'
            ],
            [
                'nombres' => 'Pedro José', 
                'apellidos' => 'Ramírez López', 
                'celular' => '+573153456789', 
                'localidad' => 'Usaquén', 
                'barrio' => 'Santa Bárbara', 
                'fecha_nacimiento' => '1982-04-17',
                'conector' => 'Amigo',
                'estado_espiritual' => 'Intermitente',
                'foto' => 'pedro_ramirez.jpg'
            ],
        ];
        
        // Preparar la consulta SQL
        $stmt = $db->prepare("INSERT INTO InformacionGeneral 
                             (nombres, apellidos, celular, localidad, barrio, fecha_nacimiento, 
                              conector, estado_espiritual, foto) 
                             VALUES 
                             (:nombres, :apellidos, :celular, :localidad, :barrio, :fecha_nacimiento, 
                              :conector, :estado_espiritual, :foto)");
        
        // Insertar cada miembro
        foreach ($miembros as $miembro) {
            $stmt->bindParam(':nombres', $miembro['nombres']);
            $stmt->bindParam(':apellidos', $miembro['apellidos']);
            $stmt->bindParam(':celular', $miembro['celular']);
            $stmt->bindParam(':localidad', $miembro['localidad']);
            $stmt->bindParam(':barrio', $miembro['barrio']);
            $stmt->bindParam(':fecha_nacimiento', $miembro['fecha_nacimiento']);
            $stmt->bindParam(':conector', $miembro['conector']);
            $stmt->bindParam(':estado_espiritual', $miembro['estado_espiritual']);
            $stmt->bindParam(':foto', $miembro['foto']);
            $stmt->execute();
        }
        
        // Actualizar los campos invitado_por para algunos miembros
        // Primero obtenemos los IDs generados
        $stmt = $db->query("SELECT id FROM InformacionGeneral ORDER BY id");
        $ids = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        if (count($ids) >= 3) {
            // El miembro 3 fue invitado por el miembro 1
            $stmt = $db->prepare("UPDATE InformacionGeneral SET invitado_por = ? WHERE id = ?");
            $stmt->execute([$ids[0], $ids[2]]);
            
            // El miembro 4 fue invitado por el miembro 2
            $stmt->execute([$ids[1], $ids[3]]);
        }
        
        echo "<p>✅ Información general de miembros insertada correctamente</p>";
    } else {
        echo "<p>⏭️ La tabla InformacionGeneral ya contiene datos</p>";
    }
    
    // 4. CONTACTO
    if (tablaVacia($db, "Contacto")) {
        // Primero obtenemos los IDs de los miembros
        $stmt = $db->query("SELECT id FROM InformacionGeneral ORDER BY id");
        $miembro_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        if (count($miembro_ids) > 0) {
            $contactos = [
                [
                    'tipo_documento' => 'CC',
                    'numero_documento' => '1020304050',
                    'telefono' => '+5712345678',
                    'pais' => 'Colombia',
                    'ciudad' => 'Bogotá',
                    'direccion' => 'Calle 85 # 15-45, Apto 502',
                    'estado_civil' => 'Casado/a',
                    'correo_electronico' => 'juanmartinez@ejemplo.com',
                    'instagram' => '@juanmartinez',
                    'facebook' => 'juancarlosmartinez'
                ],
                [
                    'tipo_documento' => 'CC',
                    'numero_documento' => '1030405060',
                    'telefono' => '+5712345679',
                    'pais' => 'Colombia',
                    'ciudad' => 'Bogotá',
                    'direccion' => 'Carrera 11 # 95-20, Apto 305',
                    'estado_civil' => 'Soltero/a',
                    'correo_electronico' => 'marialopez@ejemplo.com',
                    'instagram' => '@mafelopez',
                    'facebook' => 'mariafernanda.lopez'
                ],
                [
                    'tipo_documento' => 'CE',
                    'numero_documento' => '40506070',
                    'telefono' => '+5712345680',
                    'pais' => 'Colombia',
                    'ciudad' => 'Bogotá',
                    'direccion' => 'Av. Suba # 120-15, Casa 8',
                    'estado_civil' => 'Casado/a',
                    'correo_electronico' => 'carlosgonzalez@ejemplo.com',
                    'instagram' => '@carlosegp',
                    'facebook' => 'carlos.gonzalez'
                ],
                [
                    'tipo_documento' => 'CC',
                    'numero_documento' => '50607080',
                    'telefono' => '+5712345681',
                    'pais' => 'Colombia',
                    'ciudad' => 'Bogotá',
                    'direccion' => 'Calle 53 # 25-30, Apto 901',
                    'estado_civil' => 'Soltero/a',
                    'correo_electronico' => 'anasanchez@ejemplo.com',
                    'instagram' => '@anamasanchez',
                    'facebook' => 'ana.sanchez'
                ],
                [
                    'tipo_documento' => 'CC',
                    'numero_documento' => '60708090',
                    'telefono' => '+5712345682',
                    'pais' => 'Colombia',
                    'ciudad' => 'Bogotá',
                    'direccion' => 'Carrera 7 # 110-45, Apto 1201',
                    'estado_civil' => 'Divorciado/a',
                    'correo_electronico' => 'pedroramirez@ejemplo.com',
                    'instagram' => '@pedrojramirez',
                    'facebook' => 'pedro.ramirez.lopez'
                ]
            ];
            
            $stmt = $db->prepare("INSERT INTO Contacto 
                                 (miembro_id, tipo_documento, numero_documento, telefono, pais, ciudad, 
                                  direccion, estado_civil, correo_electronico, instagram, facebook) 
                                 VALUES 
                                 (:miembro_id, :tipo_documento, :numero_documento, :telefono, :pais, :ciudad, 
                                  :direccion, :estado_civil, :correo_electronico, :instagram, :facebook)");
            
            for ($i = 0; $i < min(count($miembro_ids), count($contactos)); $i++) {
                $stmt->bindParam(':miembro_id', $miembro_ids[$i]);
                $stmt->bindParam(':tipo_documento', $contactos[$i]['tipo_documento']);
                $stmt->bindParam(':numero_documento', $contactos[$i]['numero_documento']);
                $stmt->bindParam(':telefono', $contactos[$i]['telefono']);
                $stmt->bindParam(':pais', $contactos[$i]['pais']);
                $stmt->bindParam(':ciudad', $contactos[$i]['ciudad']);
                $stmt->bindParam(':direccion', $contactos[$i]['direccion']);
                $stmt->bindParam(':estado_civil', $contactos[$i]['estado_civil']);
                $stmt->bindParam(':correo_electronico', $contactos[$i]['correo_electronico']);
                $stmt->bindParam(':instagram', $contactos[$i]['instagram']);
                $stmt->bindParam(':facebook', $contactos[$i]['facebook']);
                $stmt->execute();
            }
            
            echo "<p>✅ Información de contacto insertada correctamente</p>";
        } else {
            echo "<p>❌ No hay miembros para asignar información de contacto</p>";
        }
    } else {
        echo "<p>⏭️ La tabla Contacto ya contiene datos</p>";
    }
    
    // 5. ESTUDIOS/TRABAJO
    if (tablaVacia($db, "EstudiosTrabajo")) {
        // Obtener los IDs de los miembros
        $stmt = $db->query("SELECT id FROM InformacionGeneral ORDER BY id");
        $miembro_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        if (count($miembro_ids) > 0) {
            $estudios = [
                [
                    'nivel_estudios' => 'Universitario',
                    'profesion' => 'Ingeniero Civil',
                    'otros_estudios' => 'Especialización en Gestión de Proyectos',
                    'empresa' => 'Constructora ABC',
                    'direccion_empresa' => 'Av. El Dorado # 85-75',
                    'emprendimientos' => 'Consultora de proyectos de infraestructura'
                ],
                [
                    'nivel_estudios' => 'Maestría',
                    'profesion' => 'Psicóloga',
                    'otros_estudios' => 'Diplomado en Terapia Familiar',
                    'empresa' => 'Centro de Psicología Integral',
                    'direccion_empresa' => 'Calle 93 # 12-30',
                    'emprendimientos' => 'Talleres de inteligencia emocional'
                ],
                [
                    'nivel_estudios' => 'Técnico',
                    'profesion' => 'Técnico en Sistemas',
                    'otros_estudios' => 'Cursos de programación web',
                    'empresa' => 'Soluciones Informáticas XYZ',
                    'direccion_empresa' => 'Carrera 15 # 78-33',
                    'emprendimientos' => 'Desarrollo de aplicaciones móviles'
                ],
                [
                    'nivel_estudios' => 'Universitario',
                    'profesion' => 'Comunicadora Social',
                    'otros_estudios' => 'Fotografía profesional',
                    'empresa' => 'Revista Digital Impacto',
                    'direccion_empresa' => 'Calle 67 # 8-32',
                    'emprendimientos' => 'Agencia de contenidos digitales'
                ],
                [
                    'nivel_estudios' => 'Especialización',
                    'profesion' => 'Administrador de Empresas',
                    'otros_estudios' => 'MBA en Administración de Negocios',
                    'empresa' => 'Banco Nacional',
                    'direccion_empresa' => 'Carrera 7 # 72-64',
                    'emprendimientos' => 'Asesoría financiera para emprendedores'
                ]
            ];
            
            $stmt = $db->prepare("INSERT INTO EstudiosTrabajo 
                                 (miembro_id, nivel_estudios, profesion, otros_estudios, empresa,
                                  direccion_empresa, emprendimientos) 
                                 VALUES 
                                 (:miembro_id, :nivel_estudios, :profesion, :otros_estudios, :empresa,
                                  :direccion_empresa, :emprendimientos)");
            
            for ($i = 0; $i < min(count($miembro_ids), count($estudios)); $i++) {
                $stmt->bindParam(':miembro_id', $miembro_ids[$i]);
                $stmt->bindParam(':nivel_estudios', $estudios[$i]['nivel_estudios']);
                $stmt->bindParam(':profesion', $estudios[$i]['profesion']);
                $stmt->bindParam(':otros_estudios', $estudios[$i]['otros_estudios']);
                $stmt->bindParam(':empresa', $estudios[$i]['empresa']);
                $stmt->bindParam(':direccion_empresa', $estudios[$i]['direccion_empresa']);
                $stmt->bindParam(':emprendimientos', $estudios[$i]['emprendimientos']);
                $stmt->execute();
            }
            
            echo "<p>✅ Información de estudios y trabajo insertada correctamente</p>";
        } else {
            echo "<p>❌ No hay miembros para asignar información de estudios y trabajo</p>";
        }
    } else {
        echo "<p>⏭️ La tabla EstudiosTrabajo ya contiene datos</p>";
    }
    
    // 6. TALLAS
    if (tablaVacia($db, "Tallas")) {
        // Obtener los IDs de los miembros
        $stmt = $db->query("SELECT id FROM InformacionGeneral ORDER BY id");
        $miembro_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        if (count($miembro_ids) > 0) {
            $tallas = [
                [
                    'talla_camisa' => 'M',
                    'talla_camiseta' => 'L',
                    'talla_pantalon' => '32',
                    'talla_zapatos' => '40'
                ],
                [
                    'talla_camisa' => 'S',
                    'talla_camiseta' => 'M',
                    'talla_pantalon' => '28',
                    'talla_zapatos' => '37'
                ],
                [
                    'talla_camisa' => 'XL',
                    'talla_camiseta' => 'XL',
                    'talla_pantalon' => '36',
                    'talla_zapatos' => '42'
                ],
                [
                    'talla_camisa' => 'S',
                    'talla_camiseta' => 'S',
                    'talla_pantalon' => '26',
                    'talla_zapatos' => '36'
                ],
                [
                    'talla_camisa' => 'L',
                    'talla_camiseta' => 'L',
                    'talla_pantalon' => '34',
                    'talla_zapatos' => '41'
                ]
            ];
            
            $stmt = $db->prepare("INSERT INTO Tallas 
                                 (miembro_id, talla_camisa, talla_camiseta, talla_pantalon, talla_zapatos) 
                                 VALUES 
                                 (:miembro_id, :talla_camisa, :talla_camiseta, :talla_pantalon, :talla_zapatos)");
            
            for ($i = 0; $i < min(count($miembro_ids), count($tallas)); $i++) {
                $stmt->bindParam(':miembro_id', $miembro_ids[$i]);
                $stmt->bindParam(':talla_camisa', $tallas[$i]['talla_camisa']);
                $stmt->bindParam(':talla_camiseta', $tallas[$i]['talla_camiseta']);
                $stmt->bindParam(':talla_pantalon', $tallas[$i]['talla_pantalon']);
                $stmt->bindParam(':talla_zapatos', $tallas[$i]['talla_zapatos']);
                $stmt->execute();
            }
            
            echo "<p>✅ Información de tallas insertada correctamente</p>";
        } else {
            echo "<p>❌ No hay miembros para asignar tallas</p>";
        }
    } else {
        echo "<p>⏭️ La tabla Tallas ya contiene datos</p>";
    }
    
    // 7. SALUD Y EMERGENCIAS
    if (tablaVacia($db, "SaludEmergencias")) {
        // Obtener los IDs de los miembros
        $stmt = $db->query("SELECT id FROM InformacionGeneral ORDER BY id");
        $miembro_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        if (count($miembro_ids) > 0) {
            $salud = [
                [
                    'rh' => 'A+',
                    'acudiente1' => 'Laura Martínez',
                    'telefono1' => '+573101234567',
                    'acudiente2' => 'Roberto Rodríguez',
                    'telefono2' => '+573112345678',
                    'eps' => 'Compensar'
                ],
                [
                    'rh' => 'O+',
                    'acudiente1' => 'Patricia López',
                    'telefono1' => '+573123456789',
                    'acudiente2' => 'Juan García',
                    'telefono2' => '+573134567890',
                    'eps' => 'Sura'
                ],
                [
                    'rh' => 'B+',
                    'acudiente1' => 'Sandra González',
                    'telefono1' => '+573145678901',
                    'acudiente2' => 'Ricardo Pérez',
                    'telefono2' => '+573156789012',
                    'eps' => 'Famisanar'
                ],
                [
                    'rh' => 'AB-',
                    'acudiente1' => 'Miguel Torres',
                    'telefono1' => '+573167890123',
                    'acudiente2' => 'Claudia Sánchez',
                    'telefono2' => '+573178901234',
                    'eps' => 'Nueva EPS'
                ],
                [
                    'rh' => 'O-',
                    'acudiente1' => 'Martha Ramírez',
                    'telefono1' => '+573189012345',
                    'acudiente2' => 'Luis López',
                    'telefono2' => '+573190123456',
                    'eps' => 'Sanitas'
                ]
            ];
            
            $stmt = $db->prepare("INSERT INTO SaludEmergencias 
                                 (miembro_id, rh, acudiente1, telefono1, acudiente2, telefono2, eps) 
                                 VALUES 
                                 (:miembro_id, :rh, :acudiente1, :telefono1, :acudiente2, :telefono2, :eps)");
            
            for ($i = 0; $i < min(count($miembro_ids), count($salud)); $i++) {
                $stmt->bindParam(':miembro_id', $miembro_ids[$i]);
                $stmt->bindParam(':rh', $salud[$i]['rh']);
                $stmt->bindParam(':acudiente1', $salud[$i]['acudiente1']);
                $stmt->bindParam(':telefono1', $salud[$i]['telefono1']);
                $stmt->bindParam(':acudiente2', $salud[$i]['acudiente2']);
                $stmt->bindParam(':telefono2', $salud[$i]['telefono2']);
                $stmt->bindParam(':eps', $salud[$i]['eps']);
                $stmt->execute();
            }
            
            echo "<p>✅ Información de salud y emergencias insertada correctamente</p>";
        } else {
            echo "<p>❌ No hay miembros para asignar información de salud y emergencias</p>";
        }
    } else {
        echo "<p>⏭️ La tabla SaludEmergencias ya contiene datos</p>";
    }
    
    // 8. CARRERA BÍBLICA
    if (tablaVacia($db, "CarreraBiblica")) {
        // Obtener los IDs de los miembros
        $stmt = $db->query("SELECT id FROM InformacionGeneral ORDER BY id");
        $miembro_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        if (count($miembro_ids) > 0) {
            $carrera = [
                [
                    'carrera_biblica' => 'Escuela de Ministerios',
                    'miembro_de' => 'Iglesia En Casa',
                    'casa_de_palabra_y_vida' => 'Grupo Norte',
                    'cobertura' => 'Pastor Principal',
                    'estado' => 'Activo',
                    'anotaciones' => 'Excelente participación y compromiso',
                    'recorrido_espiritual' => 'Ha completado Catecumenado y Discipulado'
                ],
                [
                    'carrera_biblica' => 'Catecumenado',
                    'miembro_de' => 'Iglesia En Casa',
                    'casa_de_palabra_y_vida' => 'Grupo Centro',
                    'cobertura' => 'Líder Ana Gómez',
                    'estado' => 'Nuevo',
                    'anotaciones' => 'Comenzó el proceso hace un mes',
                    'recorrido_espiritual' => 'Primera experiencia en formación bíblica'
                ],
                [
                    'carrera_biblica' => 'Discipulado',
                    'miembro_de' => 'Iglesia En Casa',
                    'casa_de_palabra_y_vida' => 'Grupo Sur',
                    'cobertura' => 'Líder Carlos Díaz',
                    'estado' => 'Activo',
                    'anotaciones' => 'Participación constante',
                    'recorrido_espiritual' => 'Completó Catecumenado hace 6 meses'
                ],
                [
                    'carrera_biblica' => 'Catecumenado',
                    'miembro_de' => 'Iglesia En Casa',
                    'casa_de_palabra_y_vida' => 'Grupo Occidente',
                    'cobertura' => 'Líder María Ramos',
                    'estado' => 'Estudiante',
                    'anotaciones' => 'Muestra interés y compromiso',
                    'recorrido_espiritual' => 'Nuevo en la fe, proviene de otra denominación'
                ],
                [
                    'carrera_biblica' => 'Escuela de Ministerios',
                    'miembro_de' => 'Iglesia En Casa',
                    'casa_de_palabra_y_vida' => 'Grupo Oriente',
                    'cobertura' => 'Pastor Asistente',
                    'estado' => 'Inactivo',
                    'anotaciones' => 'Tomando un descanso por motivos laborales',
                    'recorrido_espiritual' => 'Ha completado todas las etapas de formación'
                ]
            ];
            
            $stmt = $db->prepare("INSERT INTO CarreraBiblica 
                                 (miembro_id, carrera_biblica, miembro_de, casa_de_palabra_y_vida, 
                                  cobertura, estado, anotaciones, recorrido_espiritual) 
                                 VALUES 
                                 (:miembro_id, :carrera_biblica, :miembro_de, :casa_de_palabra_y_vida, 
                                  :cobertura, :estado, :anotaciones, :recorrido_espiritual)");
            
            for ($i = 0; $i < min(count($miembro_ids), count($carrera)); $i++) {
                $stmt->bindParam(':miembro_id', $miembro_ids[$i]);
                $stmt->bindParam(':carrera_biblica', $carrera[$i]['carrera_biblica']);
                $stmt->bindParam(':miembro_de', $carrera[$i]['miembro_de']);
                $stmt->bindParam(':casa_de_palabra_y_vida', $carrera[$i]['casa_de_palabra_y_vida']);
                $stmt->bindParam(':cobertura', $carrera[$i]['cobertura']);
                $stmt->bindParam(':estado', $carrera[$i]['estado']);
                $stmt->bindParam(':anotaciones', $carrera[$i]['anotaciones']);
                $stmt->bindParam(':recorrido_espiritual', $carrera[$i]['recorrido_espiritual']);
                $stmt->execute();
            }
            
            echo "<p>✅ Información de carrera bíblica insertada correctamente</p>";
        } else {
            echo "<p>❌ No hay miembros para asignar información de carrera bíblica</p>";
        }
    } else {
        echo "<p>⏭️ La tabla CarreraBiblica ya contiene datos</p>";
    }
    
    // 9. CARPETA DE IMÁGENES
    // Crear la carpeta para imágenes si no existe
    $upload_dir = __DIR__ . '/uploads/miembros';
    if (!is_dir($upload_dir)) {
        if (mkdir($upload_dir, 0777, true)) {
            echo "<p>✅ Carpeta de imágenes creada: {$upload_dir}</p>";
        } else {
            echo "<p>❌ Error al crear la carpeta de imágenes</p>";
        }
    } else {
        echo "<p>⏭️ La carpeta de imágenes ya existe</p>";
    }
    
    echo "<h2>¡Datos cargados exitosamente!</h2>";
    echo "<p>Ahora puede <a href='index.php'>ir a la aplicación</a> para ver los datos cargados.</p>";
    
} catch (PDOException $e) {
    echo "<h2>Error al cargar datos</h2>";
    echo "<p>Detalle del error: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>