<?php

// Script de proxy directo para actualizar miembros sin problemas con túneles

// Configuración básica
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

// Permitir CORS para túneles
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// Si es una solicitud OPTIONS (preflight), responder inmediatamente
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Verificar método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Solo se aceptan solicitudes POST']);
    exit;
}

// Obtener ID de miembro
$miembroId = isset($_GET['id']) ? (int)$_GET['id'] : null;
if (!$miembroId) {
    echo json_encode(['success' => false, 'message' => 'ID de miembro no especificado']);
    exit;
}

// Conectar directamente a la base de datos
try {
    require_once __DIR__ . '/app/config/config.php';
    $db = new PDO("mysql:host=localhost;dbname=IglesiaEnCasa;charset=utf8", "root", "");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Comprobar que el miembro existe
    $stmt = $db->prepare("SELECT id FROM InformacionGeneral WHERE id = ?");
    $stmt->execute([$miembroId]);
    if ($stmt->rowCount() === 0) {
        echo json_encode(['success' => false, 'message' => 'Miembro no encontrado']);
        exit;
    }
    
    // Iniciar transacción para asegurar integridad
    $db->beginTransaction();
    
    // 1. Actualizar tabla principal si se enviaron datos
    $camposPrincipales = ['nombres', 'apellidos', 'celular', 'localidad', 'barrio', 
        'fecha_nacimiento', 'estado_espiritual', 'recorrido_espiritual'];
    
    $actualizados = [];
    $params = [];
    
    foreach ($camposPrincipales as $campo) {
        if (!empty($_POST[$campo])) {
            $actualizados[] = "$campo = ?";
            $params[] = $_POST[$campo];
        }
    }
    
    if (!empty($actualizados)) {
        $params[] = $miembroId;
        $sql = "UPDATE InformacionGeneral SET " . implode(", ", $actualizados) . " WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
    }
    
    // 2. Actualizar tallas
    $existe = $db->prepare("SELECT id FROM Tallas WHERE miembro_id = ?");
    $existe->execute([$miembroId]);
    
    $tallas = [
        'talla_camisa' => $_POST['talla_camisa'] ?? null,
        'talla_camiseta' => $_POST['talla_camiseta'] ?? null,
        'talla_pantalon' => $_POST['talla_pantalon'] ?? null,
        'talla_zapatos' => $_POST['talla_zapatos'] ?? null
    ];
    
    if ($existe->rowCount() > 0) {
        // Actualizar registro existente
        $actualizados = [];
        $params = [];
        
        foreach ($tallas as $campo => $valor) {
            if ($valor !== null) {
                $actualizados[] = "$campo = ?";
                $params[] = $valor;
            }
        }
        
        if (!empty($actualizados)) {
            $params[] = $miembroId;
            $sql = "UPDATE Tallas SET " . implode(", ", $actualizados) . " WHERE miembro_id = ?";
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
        }
    } else {
        // Crear nuevo registro
        $tallas['miembro_id'] = $miembroId;
        
        // Filtrar valores nulos
        $campos = [];
        $valores = [];
        $params = [];
        
        foreach ($tallas as $campo => $valor) {
            if ($valor !== null) {
                $campos[] = $campo;
                $valores[] = "?";
                $params[] = $valor;
            }
        }
        
        if (!empty($campos)) {
            $sql = "INSERT INTO Tallas (" . implode(", ", $campos) . ") VALUES (" . implode(", ", $valores) . ")";
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
        }
    }
    
    // Actualizar otras tablas relacionadas
    $tablasRelacionadas = [
        // Elimina o comenta las tablas que no existen en tu base de datos
        // 'DatosAdicionales' => ['telefono_adicional', 'direccion_adicional'],
        // 'Hijos' => ['cantidad_hijos'],
        // 'Ocupacion' => ['ocupacion'],
        // 'ReferidoPor' => ['referido_por']
    ];
    
    foreach ($tablasRelacionadas as $tabla => $campos) {
        actualizarTablaRelacionada($db, $miembroId, $tabla, $campos);
    }
    
    // 3. Actualizar contacto
    actualizarTablaRelacionada($db, $miembroId, 'Contacto', [
        'tipo_documento', 'numero_documento', 'telefono', 
        'correo_electronico', 'pais', 'ciudad', 'direccion', 'estado_civil',
        'instagram', 'facebook', 'notas', 'familiares'
    ]);
    
    // 4. Actualizar estudios/trabajo
    actualizarTablaRelacionada($db, $miembroId, 'EstudiosTrabajo', [
        'nivel_estudios', 'profesion', 'otros_estudios', 
        'empresa', 'direccion_empresa', 'emprendimientos'
    ]);
    
    // 5. Actualizar salud
    actualizarTablaRelacionada($db, $miembroId, 'SaludEmergencias', [
        'rh', 'eps', 'acudiente1', 'telefono1', 'acudiente2', 'telefono2'
    ]);
    
    // 6. Actualizar carrera
    actualizarTablaRelacionada($db, $miembroId, 'CarreraBiblica', [
        'estado', 'carrera_biblica', 'miembro_de', 
        'casa_de_palabra_y_vida', 'cobertura', 'anotaciones'
    ]);
    
    // Confirmar todas las operaciones
    $db->commit();
    
    echo json_encode([
        'success' => true,
        'message' => 'Datos actualizados correctamente',
        'id' => $miembroId,
        'redirect' => "/ENCASA_DATABASE/miembros/$miembroId"
    ]);
    
} catch (Exception $e) {
    // Revertir cambios en caso de error
    if (isset($db) && $db->inTransaction()) {
        $db->rollBack();
    }
    
    echo json_encode([
        'success' => false, 
        'message' => 'Error: ' . $e->getMessage()
    ]);
}

// Función para actualizar tablas relacionadas
function actualizarTablaRelacionada($db, $miembroId, $tabla, $camposPermitidos) {
    // Verificar si hay datos para actualizar
    $hayDatos = false;
    $datos = [];
    
    foreach ($camposPermitidos as $campo) {
        if (isset($_POST[$campo])) {
            $datos[$campo] = $_POST[$campo];
            $hayDatos = true;
        }
    }
    
    if (!$hayDatos) return;
    
    // Verificar si ya existe registro
    $verificar = $db->prepare("SELECT id FROM $tabla WHERE miembro_id = ?");
    $verificar->execute([$miembroId]);
    $existe = $verificar->fetch();
    
    if ($existe) {
        // Actualizar
        $sets = [];
        $params = [];
        
        foreach ($datos as $campo => $valor) {
            $sets[] = "$campo = ?";
            $params[] = $valor;
        }
        
        if (empty($sets)) return;
        
        $params[] = $miembroId;
        $sql = "UPDATE $tabla SET " . implode(', ', $sets) . " WHERE miembro_id = ?";
        
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
    } else {
        // Insertar
        if (empty($datos)) return;
        
        $datos['miembro_id'] = $miembroId;
        $campos = array_keys($datos);
        $placeholders = array_fill(0, count($campos), '?');
        
        $sql = "INSERT INTO $tabla (" . implode(', ', $campos) . ") 
                VALUES (" . implode(', ', $placeholders) . ")";
        
        $stmt = $db->prepare($sql);
        $stmt->execute(array_values($datos));
    }
}

// Solo para depuración - Eliminar en producción
file_put_contents(__DIR__ . '/debug_proxy.log', "Solicitud recibida: " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
file_put_contents(__DIR__ . '/debug_proxy.log', "POST: " . print_r($_POST, true) . "\n", FILE_APPEND);
// Solo para depuración temporal
file_put_contents(__DIR__ . '/campos_form.log', "Campos del formulario: " . print_r($_POST, true), FILE_APPEND);
?>