<?php
// filepath: c:\xampp\htdocs\ENCASA_DATABASE\app\models\VerificationCode.php
namespace App\Models;

class VerificationCode extends Model {
    protected $table = 'Verification_Codes';
    
    /**
     * Genera un código aleatorio de verificación
     * @return string Código de 6 dígitos
     */
    public static function generateCode() {
        return str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT);
    }
    
    /**
     * Crea un nuevo código de verificación para un usuario
     * @param int $userId ID del usuario
     * @param string $type Tipo de verificación (email_verification por defecto)
     * @return string El código generado
     */
    public function createForUser($userId, $type = 'email_verification') {
        $code = self::generateCode();
        $expiresAt = date('Y-m-d H:i:s', time() + 3600); // Expira en 1 hora
        
        $query = "INSERT INTO {$this->table} (user_id, code, type, expires_at, used) 
                 VALUES (?, ?, ?, ?, 0)";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$userId, $code, $type, $expiresAt]);
        
        return $code;
    }
    
    /**
     * Verifica si un código es válido
     * @param int $userId ID del usuario
     * @param string $code Código a verificar
     * @param string $type Tipo de verificación
     * @return bool True si el código es válido, false en caso contrario
     */
    public function verifyCode($userId, $code, $type = 'email_verification') {
        $now = date('Y-m-d H:i:s');
        $query = "SELECT * FROM {$this->table} 
                 WHERE user_id = ? AND code = ? AND type = ? 
                 AND expires_at > ? AND used = 0
                 ORDER BY created_at DESC LIMIT 1";
                 
        $stmt = $this->db->prepare($query);
        $stmt->execute([$userId, $code, $type, $now]);
        $result = $stmt->fetch();
        
        if (!$result) {
            return false;
        }
        
        // Marcar el código como usado
        $updateQuery = "UPDATE {$this->table} SET used = 1 WHERE id = ?";
        $updateStmt = $this->db->prepare($updateQuery);
        $updateStmt->execute([$result['id']]);
        
        return true;
    }
}