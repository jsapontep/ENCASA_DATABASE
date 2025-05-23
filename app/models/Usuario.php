<?php
// filepath: /Applications/XAMPP/xamppfiles/htdocs/Encasa_Database/app/models/Usuario.php
namespace App\Models;

class Usuario extends Model {
    protected $table = 'Usuarios';
    protected $fillable = ['username', 'email', 'password', 'nombre_completo', 'ultimo_login', 'activo'];
    
    /**
     * Encuentra un usuario por su nombre de usuario
     */
    public function findByUsername($username) {
        return $this->findOneWhere('username', $username);
    }
    
    /**
     * Encuentra un usuario por su correo electrónico
     */
    public function findByEmail($email) {
        return $this->findOneWhere('email', $email);
    }
    
    /**
     * Encuentra un registro por una condición específica
     */
    public function findOneWhere($field, $value) {
        $query = "SELECT * FROM {$this->table} WHERE {$field} = :value LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':value', $value);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    /**
     * Autentica un usuario por su nombre de usuario/email y contraseña
     */
    public function authenticate($username, $password) {
        echo "<div style='background:#f8f9fa;padding:10px;margin:10px 0;border-radius:5px;'>";
        echo "<h4>Depuración de autenticación:</h4>";
        
        $user = $this->findByUsername($username);
        echo "<p>Buscando por username: " . ($user ? "Encontrado" : "No encontrado") . "</p>";
        
        if (!$user) {
            // Intentar con email
            echo "<p>Buscando por email...</p>";
            $user = $this->findByEmail($username);
            echo "<p>Resultado búsqueda por email: " . ($user ? "Encontrado" : "No encontrado") . "</p>";
        }
        
        if (!$user) {
            echo "<p>Usuario no encontrado</p>";
            echo "</div>";
            return false;
        }
        
        // Verificamos si la contraseña está hasheada correctamente
        echo "<p>Usuario encontrado, ID: {$user['id']}, Username: {$user['username']}</p>";
        echo "<p>Hash almacenado: {$user['password']}</p>";
        
        $passwordMatch = password_verify($password, $user['password']);
        echo "<p>Contraseña introducida: $password</p>";
        echo "<p>¿Contraseña coincide?: " . ($passwordMatch ? "SÍ" : "NO") . "</p>";
        
        echo "</div>";
        
        if (!$passwordMatch) {
            return false;
        }
        
        // Actualizar último login
        $this->update($user['id'], [
            'ultimo_login' => date('Y-m-d H:i:s')
        ]);
        
        return $user;
    }
    
    /**
     * Hashea una contraseña
     */
    public function hashPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }
    
    /**
     * Verifica si una contraseña coincide con su hash
     */
    public function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }
    
    /**
     * Crea un nuevo usuario con contraseña hasheada
     */
    public function register($data) {
        // Verificar si el usuario o email ya existe
        if ($this->findByUsername($data['username']) || $this->findByEmail($data['email'])) {
            return false;
        }
        
        // Hashear la contraseña antes de guardarla
        $data['password'] = $this->hashPassword($data['password']);
        
        // Crear el usuario
        return $this->create($data);
    }
    
    /**
     * Obtiene los roles del usuario
     */
    public function getRoles($userId) {
        $sql = "SELECT r.* FROM RolesUsuario ru
                JOIN Roles r ON ru.rol_id = r.id
                WHERE ru.usuario_id = :user_id";
                
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $userId);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    /**
     * Verifica si un usuario tiene un rol específico
     */
    public function hasRole($userId, $roleName) {
        $sql = "SELECT COUNT(*) as count FROM RolesUsuario ru
                JOIN Roles r ON ru.rol_id = r.id
                WHERE ru.usuario_id = :user_id AND r.nombre = :role_name";
                
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $userId);
        $stmt->bindValue(':role_name', $roleName);
        $stmt->execute();
        
        $result = $stmt->fetch();
        return (int) $result['count'] > 0;
    }

    /**
     * Obtiene todos los usuarios
     */
    public function getAll() {
        $query = "SELECT * FROM {$this->table}";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}