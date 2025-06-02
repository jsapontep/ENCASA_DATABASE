<?php
// Script para crear un usuario administrador inicial

// Incluir archivo de configuración de la base de datos
require_once 'app/config/database.php';

try {
    // Conectar a la base de datos
    $db = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Datos del usuario administrador
    $username = "Administrador";
    $email = "rafa.gzfr@gmail.com";
    $password = "Amor2025+"; 
    $nombre_completo = "Administrador del Sistema";
    
    // Encriptar la contraseña
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    // Fecha actual
    $fecha_actual = date('Y-m-d H:i:s');
    
    // PASO 0: Verificar si el usuario ya existe
    $checkStmt = $db->prepare("SELECT id FROM Usuarios WHERE email = ? OR username = ?");
    $checkStmt->execute([$email, $username]);
    
    if ($checkStmt->rowCount() > 0) {
        $user = $checkStmt->fetch(PDO::FETCH_ASSOC);
        echo "<h3>El usuario administrador ya existe con ID: " . $user['id'] . "</h3>";
        echo "<p>Puede iniciar sesión con:</p>";
        echo "<p><strong>Username:</strong> " . htmlspecialchars($username) . "</p>";
        echo "<p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>";
        echo "<p><strong>Contraseña:</strong> " . htmlspecialchars($password) . "</p>";
        echo "<p><a href='index.php'>Ir a la página de inicio de sesión</a></p>";
    } else {
        // PASO 1: Crear registro en la tabla InformacionGeneral
        $sql_info = "INSERT INTO InformacionGeneral (nombres, apellidos, celular) 
                     VALUES (?, ?, ?)";
        $stmt_info = $db->prepare($sql_info);
        $stmt_info->execute(["Administrador", "Sistema", "+123456789"]);
        
        $miembro_id = $db->lastInsertId();
        
        // PASO 2: Crear el usuario en la tabla Usuarios
        // Cambiado: "activo" por "estado"
        $sql = "INSERT INTO Usuarios (miembro_id, rol_id, email, username, password, nombre_completo, estado, fecha_creacion) 
                VALUES (?, ?, ?, ?, ?, ?, 'Activo', ?)";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([$miembro_id, 1, $email, $username, $hashedPassword, $nombre_completo, $fecha_actual]);
        
        $usuario_id = $db->lastInsertId();
        
        echo "<h2>Usuario administrador creado exitosamente!</h2>";
        echo "<p><strong>Username:</strong> " . htmlspecialchars($username) . "</p>";
        echo "<p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>";
        echo "<p><strong>Contraseña:</strong> " . htmlspecialchars($password) . "</p>";
        echo "<p>Por favor, cambia esta contraseña después de iniciar sesión.</p>";
        echo "<p><a href='index.php'>Ir a la página de inicio de sesión</a></p>";
    }
} catch (PDOException $e) {
    echo "<h2>Error al crear el usuario administrador</h2>";
    echo "<p>Detalles del error: " . htmlspecialchars($e->getMessage()) . "</p>";
    
    // Sugerencias para solucionar problemas específicos
    echo "<h3>Posibles soluciones:</h3>";
    echo "<ul>";
    echo "<li>Verifique que las tablas existen y tienen la estructura correcta</li>";
    echo "<li>Verifique que los nombres de las columnas son correctos</li>";
    echo "<li>Si el error persiste, consulte los detalles específicos del error</li>";
    echo "</ul>";
}
?>