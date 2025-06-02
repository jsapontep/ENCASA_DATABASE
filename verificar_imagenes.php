<?php
// Verificar directorio e imágenes
require_once 'app/config/database.php';

// Conectar a la base de datos
$db = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Obtener todos los nombres de archivos
$stmt = $db->query("SELECT id, nombres, apellidos, foto FROM InformacionGeneral WHERE foto IS NOT NULL AND foto != ''");
$miembros = $stmt->fetchAll(PDO::FETCH_ASSOC);

$directorio = __DIR__ . '/uploads/miembros/';
echo "<h1>Verificación de imágenes</h1>";

// Verificar si el directorio existe
echo "<h2>Verificando directorio</h2>";
if (is_dir($directorio)) {
    echo "<p style='color:green'>✓ Directorio existe: {$directorio}</p>";
    echo "<p>Permisos: " . substr(sprintf('%o', fileperms($directorio)), -4) . "</p>";
} else {
    echo "<p style='color:red'>✗ Directorio NO existe: {$directorio}</p>";
    echo "<p>Intente crear el directorio manualmente.</p>";
    exit;
}

// Verificar cada imagen
echo "<h2>Verificando imágenes</h2>";
echo "<table border='1' cellpadding='5'>
      <tr><th>ID</th><th>Nombre</th><th>Archivo</th><th>Estado</th></tr>";

foreach ($miembros as $miembro) {
    $archivo = $directorio . $miembro['foto'];
    $estado = file_exists($archivo) ? 
        "<span style='color:green'>✓ Existe</span>" : 
        "<span style='color:red'>✗ No existe</span>";
    
    echo "<tr>
            <td>{$miembro['id']}</td>
            <td>{$miembro['nombres']} {$miembro['apellidos']}</td>
            <td>{$miembro['foto']}</td>
            <td>{$estado}</td>
          </tr>";
}
echo "</table>";
?>