<?php

// Incluir el autoloader
require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../app/helpers/JWT.php';

use App\Helpers\JWT;

// Clase de pruebas simple
class JWTTest {
    
    public function testEncodeAndDecode() {
        // Configurar una clave secreta específica para las pruebas
        JWT::init('test_secret_key');
        
        // Crear un payload
        $payload = [
            'user_id' => 123,
            'username' => 'testuser'
        ];
        
        // Codificar el token
        $token = JWT::encode($payload, 60); // 60 segundos de expiración
        
        // Verificar que el token se creó y tiene el formato correcto
        echo "Token Creado: ";
        $tokenParts = explode('.', $token);
        echo (count($tokenParts) === 3) ? "PASÓ ✓" : "FALLÓ ✗";
        echo "\n";
        
        // Decodificar el token
        $decoded = JWT::decode($token);
        
        // Verificar que la decodificación fue exitosa
        echo "Decodificación: ";
        echo ($decoded !== false) ? "PASÓ ✓" : "FALLÓ ✗";
        echo "\n";
        
        // Verificar que el payload se mantiene intacto
        echo "Verificación de user_id: ";
        echo ($decoded['user_id'] === 123) ? "PASÓ ✓" : "FALLÓ ✗";
        echo "\n";
        
        echo "Verificación de username: ";
        echo ($decoded['username'] === 'testuser') ? "PASÓ ✓" : "FALLÓ ✗";
        echo "\n";
    }
    
    public function testExpiredToken() {
        // Configurar una clave secreta
        JWT::init('test_secret_key');
        
        // Crear un payload con expiración inmediata
        $payload = ['test' => 'data'];
        
        // Codificar el token con expiración de 1 segundo
        $token = JWT::encode($payload, 1);
        
        // Esperar 2 segundos para que expire
        sleep(2);
        
        // Intentar decodificar el token expirado
        $decoded = JWT::decode($token);
        
        // Verificar que la decodificación falla
        echo "Verificación de expiración: ";
        echo ($decoded === false) ? "PASÓ ✓" : "FALLÓ ✗";
        echo "\n";
    }
    
    public function testInvalidToken() {
        // Configurar una clave secreta
        JWT::init('test_secret_key');
        
        // Token malformado
        $token = "header.payload.invalid_signature";
        
        // Intentar decodificar
        $decoded = JWT::decode($token);
        
        // Verificar que la decodificación falla
        echo "Verificación de token inválido: ";
        echo ($decoded === false) ? "PASÓ ✓" : "FALLÓ ✗";
        echo "\n";
    }
    
    public function runTests() {
        $this->testEncodeAndDecode();
        $this->testExpiredToken();
        $this->testInvalidToken();
    }
}

// Ejecutar pruebas
$test = new JWTTest();
$test->runTests();