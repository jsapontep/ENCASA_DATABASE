<?php

// Archivo de diagnóstico para verificar todas las tablas relacionadas con un miembro

// Configuración básica
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h1>Diagnóstico de Miembro</h1>";

// Obtener ID de la URL o usar 1 por defecto
$id = isset($_GET['id']) ? (int)$_GET['id'] : 1;

echo "<h2>Verificando Miembro ID: $id</h2>";

try {
    // Conexión directa a la base de datos
    $pdo = new PDO("mysql:host=localhost;dbname=IglesiaEnCasa;charset=utf8", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // 1. Verificar si el miembro existe
    $stmt = $pdo->prepare("SELECT * FROM InformacionGeneral WHERE id = ?");
    $stmt->execute([$id]);
    $miembro = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$miembro) {
        echo "<div style='color:red; padding:10px; background:#ffeeee; border:1px solid red;'>";
        echo "<strong>ERROR:</strong> No existe ningún miembro con ID $id";
        echo "</div>";
        
        // Mostrar los miembros disponibles
        echo "<h3>Miembros disponibles en la base de datos:</h3>";
        $stmt = $pdo->query("SELECT id, nombres, apellidos FROM InformacionGeneral ORDER BY id");
        echo "<ul>";
        while ($row = $stmt->fetch()) {
            echo "<li><a href='?id={$row['id']}'>{$row['id']}: {$row['nombres']} {$row['apellidos']}</a></li>";
        }
        echo "</ul>";
        
        exit;
    }
    
    echo "<div style='color:green; padding:10px; background:#eeffee; border:1px solid green;'>";
    echo "<strong>✓</strong> Miembro encontrado: {$miembro['nombres']} {$miembro['apellidos']}";
    echo "</div>";
    
    // 2. Verificar tablas relacionadas
    $tablas = ['Contacto', 'EstudiosTrabajo', 'Tallas', 'SaludEmergencias', 'CarreraBiblica'];
    
    echo "<h3>Verificación de tablas relacionadas:</h3>";
    echo "<table border='1' cellpadding='5' style='border-collapse:collapse;'>";
    echo "<tr><th>Tabla</th><th>Estado</th><th>Acción</th></tr>";
    
    foreach ($tablas as $tabla) {
        try {
            // Verificar si la tabla existe
            $stmt = $pdo->prepare("SHOW TABLES LIKE ?");
            $stmt->execute([$tabla]);
            
            if ($stmt->rowCount() === 0) {
                echo "<tr style='background:#fff0f0;'>";
                echo "<td>$tabla</td><td><span style='color:red;'>La tabla no existe</span></td>";
                echo "<td><a href='?id=$id&crear_tabla=$tabla'>Crear tabla</a></td>";
                echo "</tr>";
                continue;
            }
            
            // Verificar si hay un registro para este miembro
            $stmt = $pdo->prepare("SELECT id FROM $tabla WHERE miembro_id = ?");
            $stmt->execute([$id]);
            
            if ($stmt->rowCount() === 0) {
                echo "<tr style='background:#ffffd0;'>";
                echo "<td>$tabla</td><td><span style='color:orange;'>Sin registro para este miembro</span></td>";
                echo "<td><a href='?id=$id&crear_registro=$tabla'>Crear registro vacío</a></td>";
                echo "</tr>";
            } else {
                echo "<tr style='background:#f0fff0;'>";
                echo "<td>$tabla</td><td><span style='color:green;'>OK</span></td>";
                echo "<td>-</td>";
                echo "</tr>";
            }
            
        } catch (PDOException $e) {
            echo "<tr style='background:#fff0f0;'>";
            echo "<td>$tabla</td><td><span style='color:red;'>Error: " . $e->getMessage() . "</span></td>";
            echo "<td>-</td>";
            echo "</tr>";
        }
    }
    
    echo "</table>";
    
    // 3. Procesar acciones
    if (isset($_GET['crear_tabla'])) {
        $tabla = $_GET['crear_tabla'];
        
        // Definiciones SQL para cada tabla
        $sql = [];
        $sql['Contacto'] = "CREATE TABLE Contacto (
            id INT AUTO_INCREMENT PRIMARY KEY,
            miembro_id INT NOT NULL,
            tipo_documento VARCHAR(10),
            numero_documento VARCHAR(20),
            telefono VARCHAR(20),
            pais VARCHAR(50),
            ciudad VARCHAR(50),
            direccion VARCHAR(200),
            estado_civil VARCHAR(20),
            correo_electronico VARCHAR(100),
            instagram VARCHAR(100),
            facebook VARCHAR(100),
            notas TEXT,
            familiares TEXT
        )";
        
        $sql['EstudiosTrabajo'] = "CREATE TABLE EstudiosTrabajo (
            id INT AUTO_INCREMENT PRIMARY KEY,
            miembro_id INT NOT NULL,
            nivel_estudios VARCHAR(50),
            profesion VARCHAR(100),
            otros_estudios TEXT,
            empresa VARCHAR(100),
            direccion_empresa VARCHAR(200),
            emprendimientos TEXT
        )";
        
        $sql['Tallas'] = "CREATE TABLE Tallas (
            id INT AUTO_INCREMENT PRIMARY KEY,
            miembro_id INT NOT NULL,
            talla_camisa VARCHAR(10),
            talla_camiseta VARCHAR(10),
            talla_pantalon VARCHAR(10),
            talla_zapatos VARCHAR(10)
        )";
        
        $sql['SaludEmergencias'] = "CREATE TABLE SaludEmergencias (
            id INT AUTO_INCREMENT PRIMARY KEY,
            miembro_id INT NOT NULL,
            rh VARCHAR(10),
            eps VARCHAR(100),
            acudiente1 VARCHAR(100),
            telefono1 VARCHAR(20),
            acudiente2 VARCHAR(100),
            telefono2 VARCHAR(20),
            fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";
        
        $sql['CarreraBiblica'] = "CREATE TABLE CarreraBiblica (
            id INT AUTO_INCREMENT PRIMARY KEY,
            miembro_id INT NOT NULL,
            carrera_biblica VARCHAR(100),
            miembro_de VARCHAR(100),
            casa_de_palabra_y_vida VARCHAR(100),
            cobertura VARCHAR(100),
            estado VARCHAR(50),
            anotaciones TEXT,
            recorrido_espiritual TEXT
        )";
        
        if (isset($sql[$tabla])) {
            $pdo->exec($sql[$tabla]);
            echo "<div style='color:green; padding:10px; background:#eeffee; border:1px solid green; margin-top:10px;'>";
            echo "✓ Tabla $tabla creada correctamente";
            echo "</div>";
        }
    }
    
    if (isset($_GET['crear_registro'])) {
        $tabla = $_GET['crear_registro'];
        
        $stmt = $pdo->prepare("INSERT INTO $tabla (miembro_id) VALUES (?)");
        $stmt->execute([$id]);
        
        echo "<div style='color:green; padding:10px; background:#eeffee; border:1px solid green; margin-top:10px;'>";
        echo "✓ Registro creado en $tabla para el miembro $id";
        echo "</div>";
    }
    
    echo "<p><a href='diagnostico_miembro.php?id=$id'>Actualizar página</a> | <a href='/ENCASA_DATABASE/miembros/editar/$id'>Intentar editar miembro</a></p>";
    
} catch (PDOException $e) {
    echo "<div style='color:red; padding:10px; background:#ffeeee; border:1px solid red;'>";
    echo "<strong>ERROR DE BASE DE DATOS:</strong> " . $e->getMessage();
    echo "</div>";
}
?>