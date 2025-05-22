<?php

require_once __DIR__ . '/../app/config/database.php';

class DatabaseSeeder {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function seed() {
        try {
            $this->db->beginTransaction();
            
            $this->seedRoles();
            $this->seedInformacionGeneral();
            $this->seedContacto();
            $this->seedMinisterios();
            $this->seedMiembrosMinisterios();
            $this->seedUsuarios();
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            $this->logError($e->getMessage());
            return false;
        }
    }
    
    private function seedRoles() {
        $roles = [
            ['Admin', 'Administrador del sistema', 5],
            ['Líder', 'Líder de ministerio', 4],
            ['Coordinador', 'Coordinador de área', 3],
            ['Servidor', 'Servidor en ministerio', 2],
            ['Miembro', 'Miembro regular', 1]
        ];
        
        $stmt = $this->db->prepare("INSERT INTO Roles (nombre, descripcion, nivel_permiso) VALUES (?, ?, ?)");
        
        foreach ($roles as $role) {
            $stmt->execute($role);
        }
    }
    
    private function seedInformacionGeneral() {
        $miembros = [
            ['Juan', 'Pérez', '+573101234567', 'Kennedy', 'Patio Bonito', '1985-06-15', NULL, 'Invitación directa'],
            ['María', 'López', '+573119876543', 'Suba', 'Rincón', '1990-03-22', NULL, 'Familiar'],
            ['Carlos', 'Rodríguez', '+573157894561', 'Chapinero', 'La Soledad', '1982-11-07', NULL, 'Redes sociales'],
            ['Ana', 'Martínez', '+573203216547', 'Usaquén', 'Santa Bárbara', '1988-09-30', NULL, 'Amigo'],
            ['Pedro', 'González', '+573174563210', 'Fontibón', 'Modelia', '1979-04-18', NULL, 'Familiar']
        ];
        
        $stmt = $this->db->prepare("
            INSERT INTO InformacionGeneral 
            (nombres, apellidos, celular, localidad, barrio, fecha_nacimiento, invitado_por, conector) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        foreach ($miembros as $index => $miembro) {
            // Para el primer registro no tendrá invitado_por
            if ($index === 0) {
                $miembro[6] = NULL;
            } else {
                // Los demás fueron invitados por el primero
                $miembro[6] = 1;
            }
            $stmt->execute($miembro);
        }
    }
    
    private function seedContacto() {
        $contactos = [
            [1, 'CC', '1023456789', '6013456789', 'Colombia', 'Bogotá', 'Cra 15 #45-67', 'Casado', 'juan.perez@email.com'],
            [2, 'CC', '1034567890', '6014567890', 'Colombia', 'Bogotá', 'Calle 80 #23-45', 'Soltera', 'maria.lopez@email.com'],
            [3, 'CC', '1045678901', '6015678901', 'Colombia', 'Bogotá', 'Av 68 #34-56', 'Casado', 'carlos.rodriguez@email.com'],
            [4, 'CE', '1056789012', '6016789012', 'Colombia', 'Bogotá', 'Cra 7 #56-78', 'Casada', 'ana.martinez@email.com'],
            [5, 'CC', '1067890123', '6017890123', 'Colombia', 'Bogotá', 'Calle 100 #67-89', 'Soltero', 'pedro.gonzalez@email.com']
        ];
        
        $stmt = $this->db->prepare("
            INSERT INTO Contacto 
            (miembro_id, tipo_documento, numero_documento, telefono, pais, ciudad, direccion, estado_civil, correo_electronico) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        foreach ($contactos as $contacto) {
            $stmt->execute($contacto);
        }
    }
    
    private function seedMinisterios() {
        $ministerios = [
            ['Alabanza', 'Ministerio de música y adoración', 1],
            ['Niños', 'Ministerio de atención a niños', 2],
            ['Jóvenes', 'Ministerio juvenil', 3],
            ['Matrimonios', 'Ministerio para parejas casadas', 4],
            ['Servicio', 'Ministerio de apoyo logístico', 5]
        ];
        
        $stmt = $this->db->prepare("
            INSERT INTO Ministerios 
            (nombre, descripcion, lider_id) 
            VALUES (?, ?, ?)
        ");
        
        foreach ($ministerios as $ministerio) {
            $stmt->execute($ministerio);
        }
    }
    
    private function seedMiembrosMinisterios() {
        $miembrosMinisterios = [
            [1, 1, 1, '2022-01-15', NULL],
            [2, 2, 2, '2022-02-20', NULL],
            [3, 3, 2, '2022-03-10', NULL],
            [4, 4, 2, '2022-04-05', NULL],
            [5, 5, 2, '2022-05-12', NULL],
            [1, 3, 3, '2022-06-18', NULL],
            [2, 4, 3, '2022-07-22', NULL],
            [3, 5, 3, '2022-08-30', NULL]
        ];
        
        $stmt = $this->db->prepare("
            INSERT INTO MiembrosMinisterios 
            (miembro_id, ministerio_id, rol_id, fecha_inicio, fecha_fin) 
            VALUES (?, ?, ?, ?, ?)
        ");
        
        foreach ($miembrosMinisterios as $mm) {
            $stmt->execute($mm);
        }
    }
    
    private function seedUsuarios() {
        $usuarios = [
            [1, 'admin', password_hash('admin123', PASSWORD_DEFAULT), 1],
            [2, 'maria', password_hash('maria123', PASSWORD_DEFAULT), 2],
            [3, 'carlos', password_hash('carlos123', PASSWORD_DEFAULT), 3],
            [4, 'ana', password_hash('ana123', PASSWORD_DEFAULT), 4],
            [5, 'pedro', password_hash('pedro123', PASSWORD_DEFAULT), 5]
        ];
        
        $stmt = $this->db->prepare("
            INSERT INTO Usuarios 
            (miembro_id, nombre_usuario, password_hash, rol_id) 
            VALUES (?, ?, ?, ?)
        ");
        
        foreach ($usuarios as $usuario) {
            $stmt->execute($usuario);
        }
    }
    
    private function logError($message) {
        $logFile = __DIR__ . '/../app/logs/seed_errors.log';
        $directory = dirname($logFile);
        
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
        
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[$timestamp] Error de seeding: $message" . PHP_EOL;
        file_put_contents($logFile, $logMessage, FILE_APPEND);
    }
}

// Ejecutar el seeder
$seeder = new DatabaseSeeder();
if ($seeder->seed()) {
    echo "Base de datos poblada correctamente." . PHP_EOL;
} else {
    echo "Error al poblar la base de datos. Revisa los logs." . PHP_EOL;
}