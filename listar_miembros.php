<?php

// Mostrar todos los errores
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Incluir archivos necesarios
require_once __DIR__ . '/app/config/config.php';
require_once __DIR__ . '/app/config/database.php';

// Estilos básicos
echo '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Miembros Disponibles</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 1200px; margin: 0 auto; padding: 20px; }
        h1, h2 { color: #333; }
        table { border-collapse: collapse; width: 100%; margin: 15px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
        .warning { color: orange; font-weight: bold; }
        .btn { display: inline-block; padding: 8px 15px; background: #0066cc; color: white; 
               text-decoration: none; border-radius: 4px; margin: 5px; }
    </style>
</head>
<body>';

echo '<h1>Miembros Disponibles en la Base de Datos</h1>';

try {
    // Obtener conexión a la BD
    $db = Database::getInstance()->getConnection();
    
    // Obtener todos los miembros
    $stmt = $db->query("SELECT id, nombres, apellidos, fecha_registro_sistema FROM informaciongeneral ORDER BY id");
    $miembros = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($miembros) === 0) {
        echo "<p class='error'>No hay miembros en la base de datos.</p>";
    } else {
        echo "<p>Se encontraron <strong>" . count($miembros) . "</strong> miembros.</p>";
        
        // Mostrar tabla de miembros
        echo "<table>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Fecha Registro</th>
                    <th>Acciones</th>
                </tr>";
                
        foreach ($miembros as $miembro) {
            echo "<tr>
                    <td>{$miembro['id']}</td>
                    <td>" . (empty($miembro['nombres']) ? "<span class='warning'>Vacío</span>" : htmlspecialchars($miembro['nombres'])) . "</td>
                    <td>" . (empty($miembro['apellidos']) ? "<span class='warning'>Vacío</span>" : htmlspecialchars($miembro['apellidos'])) . "</td>
                    <td>{$miembro['fecha_registro_sistema']}</td>
                    <td>
                        <a href='debug_editar.php?id={$miembro['id']}' class='btn'>Diagnosticar</a>
                        <a href='".APP_URL."/miembros/editar/{$miembro['id']}' class='btn'>Editar</a>
                    </td>
                  </tr>";
        }
        
        echo "</table>";
    }
    
    echo "<h2>Solución del Problema</h2>";
    echo "<ol>
            <li><strong>Problema:</strong> Los IDs de miembros que se intentan editar no existen en la base de datos.</li>
            <li><strong>Causa:</strong> Se han eliminado miembros antiguos y creado nuevos, generando una discontinuidad en los IDs.</li>
            <li><strong>Solución:</strong> Usar los IDs correctos que realmente existen en la tabla (mostrados arriba).</li>
          </ol>";
    
    // Información sobre modificaciones necesarias en el código
    echo "<h2>Modificaciones Recomendadas</h2>";
    echo "<ul>
            <li>Actualizar enlaces en menús para usar IDs existentes</li>
            <li>Modificar el controlador MiembrosController para manejar mejor IDs inexistentes</li>
            <li>Implementar validación de IDs antes de intentar actualizar</li>
          </ul>";
    
} catch (Exception $e) {
    echo "<p class='error'>Error: " . $e->getMessage() . "</p>";
}

echo '</body></html>';