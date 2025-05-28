<?php
// filepath: c:\xampp\htdocs\ENCASA_DATABASE\tests\check_member.php
require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../app/config/database.php';

// ID a verificar (puedes cambiarlo por GET)
$id = isset($_GET['id']) ? (int)$_GET['id'] : 2;

echo "<h1>Verificación de miembro con ID: {$id}</h1>";

try {
    // Obtener conexión directa
    $database = Database::getInstance();
    $db = $database->getConnection();
    
    // Consulta directa para verificar información general
    $stmt = $db->prepare("SELECT * FROM InformacionGeneral WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $miembro = $stmt->fetch();
    
    if (!$miembro) {
        echo "<div style='color:red'>No se encontró ningún miembro con ID {$id} en la base de datos.</div>";
    } else {
        echo "<h2>Datos básicos del miembro:</h2>";
        echo "<pre>";
        print_r($miembro);
        echo "</pre>";
        
        // Verificar tablas relacionadas
        echo "<h2>Datos relacionados:</h2>";
        
        // Contacto
        $stmt = $db->prepare("SELECT * FROM Contacto WHERE miembro_id = :id");
        $stmt->execute(['id' => $id]);
        $contacto = $stmt->fetch();
        echo "<h3>Contacto:</h3>";
        echo "<pre>";
        print_r($contacto ?: "No hay datos de contacto");
        echo "</pre>";
        
        // EstudiosTrabajo
        $stmt = $db->prepare("SELECT * FROM EstudiosTrabajo WHERE miembro_id = :id");
        $stmt->execute(['id' => $id]);
        $estudios = $stmt->fetch();
        echo "<h3>Estudios y Trabajo:</h3>";
        echo "<pre>";
        print_r($estudios ?: "No hay datos de estudios");
        echo "</pre>";
        
        // Tallas
        $stmt = $db->prepare("SELECT * FROM Tallas WHERE miembro_id = :id");
        $stmt->execute(['id' => $id]);
        $tallas = $stmt->fetch();
        echo "<h3>Tallas:</h3>";
        echo "<pre>";
        print_r($tallas ?: "No hay datos de tallas");
        echo "</pre>";
        
        // CarreraBiblica
        $stmt = $db->prepare("SELECT * FROM CarreraBiblica WHERE miembro_id = :id");
        $stmt->execute(['id' => $id]);
        $carrera = $stmt->fetch();
        echo "<h3>Carrera Bíblica:</h3>";
        echo "<pre>";
        print_r($carrera ?: "No hay datos de carrera bíblica");
        echo "</pre>";
        
        echo "<p>Enlaces de prueba:</p>";
        echo "<ul>";
        echo "<li><a href='" . APP_URL . "/miembros/{$id}' target='_blank'>Ver en sistema (miembros/{$id})</a></li>";
        echo "<li><a href='check_member.php?id=" . ($id+1) . "'>Verificar miembro " . ($id+1) . "</a></li>";
        echo "<li><a href='check_member.php?id=" . ($id-1) . "'>Verificar miembro " . ($id-1) . "</a></li>";
        echo "</ul>";
    }
} catch (PDOException $e) {
    echo "<div style='color:red'>Error en la base de datos: " . $e->getMessage() . "</div>";
}