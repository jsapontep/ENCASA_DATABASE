<?php
// filepath: c:\xampp\htdocs\ENCASA_DATABASE\tests\ModelsTest.php
require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../app/config/database.php'; // Agregar esta línea
require_once __DIR__ . '/../app/models/Model.php';
require_once __DIR__ . '/../app/models/Miembro.php';
require_once __DIR__ . '/../app/models/Contacto.php';
require_once __DIR__ . '/../app/models/EstudiosTrabajo.php';
require_once __DIR__ . '/../app/models/Tallas.php';
require_once __DIR__ . '/../app/models/CarreraBiblica.php';

use App\Models\Miembro;
use App\Models\Contacto;
use App\Models\EstudiosTrabajo;
use App\Models\Tallas;
use App\Models\CarreraBiblica;

// Prueba de modelos
echo "<h1>Prueba de modelos para Miembros</h1>";

// Instanciar modelos
$miembroModel = new Miembro();
$contactoModel = new Contacto();
$estudiosModel = new EstudiosTrabajo();
$tallasModel = new Tallas();
$carreraModel = new CarreraBiblica();

echo "<pre>";
echo "Modelos cargados correctamente:";
echo "\nMiembro: " . (class_exists('App\Models\Miembro') ? 'OK' : 'ERROR');
echo "\nContacto: " . (class_exists('App\Models\Contacto') ? 'OK' : 'ERROR');
echo "\nEstudiosTrabajo: " . (class_exists('App\Models\EstudiosTrabajo') ? 'OK' : 'ERROR');
echo "\nTallas: " . (class_exists('App\Models\Tallas') ? 'OK' : 'ERROR');
echo "\nCarreraBiblica: " . (class_exists('App\Models\CarreraBiblica') ? 'OK' : 'ERROR');
echo "</pre>";

// Prueba: obtener todos los miembros
echo "<h2>Listado de miembros existentes:</h2>";
$miembros = $miembroModel->getAll();
if (empty($miembros)) {
    echo "<p>No hay miembros registrados aún.</p>";
} else {
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>ID</th><th>Nombre</th><th>Apellido</th><th>Celular</th><th>Localidad</th></tr>";
    foreach ($miembros as $miembro) {
        echo "<tr>";
        echo "<td>{$miembro['id']}</td>";
        echo "<td>{$miembro['nombres']}</td>";
        echo "<td>{$miembro['apellidos']}</td>";
        echo "<td>{$miembro['celular']}</td>";
        echo "<td>{$miembro['localidad']}</td>";
        echo "</tr>";
    }
    echo "</table>";
}

echo "<h2>Estado de las tablas relacionadas:</h2>";
echo "<pre>";
echo "Contacto: " . ($contactoModel ? 'Modelo cargado' : 'Error');
echo "\nEstudiosTrabajo: " . ($estudiosModel ? 'Modelo cargado' : 'Error');
echo "\nTallas: " . ($tallasModel ? 'Modelo cargado' : 'Error');
echo "\nCarreraBiblica: " . ($carreraModel ? 'Modelo cargado' : 'Error');
echo "</pre>";

echo "<p>Fase 1 completada: Modelos básicos implementados.</p>";