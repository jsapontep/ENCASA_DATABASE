<?php
// Habilitar visualización de errores temporalmente
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Configurar cabecera JSON
header('Content-Type: application/json');

// Función básica para registrar cada paso
function debug_log($mensaje) {
    $logfile = __DIR__ . '/debug.log';
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($logfile, "[$timestamp] $mensaje\n", FILE_APPEND);
}

// Registrar inicio
debug_log("Inicio de la solicitud");

try {
    // Paso 1: Verificar configuración
    debug_log("Verificando archivo de configuración");
    $configFile = __DIR__ . '/app/config/database.php';
    if (!file_exists($configFile)) {
        debug_log("Archivo de configuración no encontrado");
        throw new Exception("Archivo de configuración no encontrado: $configFile");
    }
    
    // Paso 2: Cargar configuración
    debug_log("Cargando archivo de configuración");
    require_once $configFile;
    
    // Paso 3: Verificar clase Database
    debug_log("Verificando clase Database");
    if (!class_exists('Database')) {
        debug_log("La clase Database no existe");
        throw new Exception("La clase Database no está definida correctamente");
    }
    
    // Paso 4: Crear una conexión manual para probar
    debug_log("Intentando conexión manual PDO");
    try {
        $pdo = new PDO("mysql:host=localhost;dbname=IglesiaEnCasa;charset=utf8", "root", "");
        debug_log("Conexión manual PDO exitosa");
    } catch (PDOException $e) {
        debug_log("Error conexión manual PDO: " . $e->getMessage());
        throw new Exception("Error en conexión manual: " . $e->getMessage());
    }
    
    // Paso 5: Intentar usar la clase Database
    debug_log("Obteniendo conexión a través de Database::getInstance()");
    $db = \Database::getInstance()->getConnection();
    debug_log("Conexión exitosa a través de Database::getInstance()");
    
    // Si llegamos hasta aquí, la conexión funciona correctamente
    // Podemos proceder con una operación simple para verificar
    debug_log("Ejecutando consulta de prueba");
    $stmt = $db->query("SELECT COUNT(*) FROM InformacionGeneral");
    $count = $stmt->fetchColumn();
    debug_log("Registros en InformacionGeneral: $count");
    
    // Responder con éxito
    echo json_encode([
        'success' => true,
        'message' => 'Prueba de conexión exitosa',
        'count' => $count
    ]);
    
} catch (Exception $e) {
    // Registrar el error
    debug_log("ERROR: " . $e->getMessage());
    
    // Responder con error
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
?>