<?php
namespace App\Helpers;

class JWT {
    private static $secret;
    
    /**
     * Inicializa la clave secreta para JWT
     */
    public static function init($secret = null) {
        if ($secret === null) {
            self::$secret = getenv('JWT_SECRET') ?: 'encasa_secret_key_change_in_production';
        } else {
            self::$secret = $secret;
        }
    }
    
    /**
     * Genera un JWT token
     */
    public static function encode($payload, $expiry = 86400) {
        if (self::$secret === null) {
            self::init();
        }
        
        // Añadir timestamps
        $payload['iat'] = time();
        $payload['exp'] = time() + $expiry;
        
        // Crear las partes del JWT
        $header = self::base64UrlEncode(json_encode([
            'alg' => 'HS256',
            'typ' => 'JWT'
        ]));
        
        $payload = self::base64UrlEncode(json_encode($payload));
        
        // Crear firma
        $signature = self::base64UrlEncode(hash_hmac(
            'sha256',
            "$header.$payload",
            self::$secret,
            true
        ));
        
        return "$header.$payload.$signature";
    }
    
    /**
     * Decodifica y verifica un JWT token
     */
    public static function decode($token) {
        if (self::$secret === null) {
            self::init();
        }
        
        // Dividir el token
        $parts = explode('.', $token);
        
        if (count($parts) !== 3) {
            return false;
        }
        
        list($header, $payload, $signature) = $parts;
        
        // Recrear la firma para verificación
        $recalculatedSignature = self::base64UrlEncode(hash_hmac(
            'sha256',
            "$header.$payload",
            self::$secret,
            true
        ));
        
        // Verificar firma
        if ($signature !== $recalculatedSignature) {
            return false;
        }
        
        // Decodificar el payload
        $payload = json_decode(self::base64UrlDecode($payload), true);
        
        // Verificar expiración
        if (isset($payload['exp']) && $payload['exp'] < time()) {
            return false;
        }
        
        return $payload;
    }
    
    private static function base64UrlEncode($data) {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    }
    
    private static function base64UrlDecode($data) {
        $data = str_replace(['-', '_'], ['+', '/'], $data);
        $remainder = strlen($data) % 4;
        
        if ($remainder) {
            $data .= str_repeat('=', 4 - $remainder);
        }
        
        return base64_decode($data);
    }
}