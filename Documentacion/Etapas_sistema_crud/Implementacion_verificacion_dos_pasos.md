# Implementación de Verificación en Dos Pasos para Inicio de Sesión

Aquí tienes un plan por fases para implementar la verificación en dos pasos para el inicio de sesión, diseñado para ser incremental y evitar conflictos:

## Fase 1: Preparación

1. **Verifica los requisitos previos**:
   - Confirma que el modelo `VerificationCode` esté funcionando
   - Asegúrate de que el envío de correos electrónicos funcione
   - Verifica que la sesión se inicie correctamente

2. **Añade la configuración**:
   ```php
   // En app/config/config.php añade:
   define('REQUIRE_2FA_LOGIN', true);
   ```

## Fase 2: Crear la vista de verificación

1. **Crea la vista para verificar el código**:
   - Crea el archivo `app/views/auth/verify-login.php`
   - Implementa un formulario simple con un campo para el código

## Fase 3: Modificar el flujo de autenticación

1. **Modifica el método `authenticate()`**:
   - Después de verificar credenciales pero antes de iniciar sesión
   - Guarda en sesión los datos del usuario pendiente de verificación
   - Genera y envía un código
   - Redirecciona a la página de verificación

## Fase 4: Implementar la verificación

1. **Crea el método `verifyLogin()` en AuthController**:
   - Verifica que exista una sesión de verificación pendiente
   - Procesa el código ingresado
   - Inicia sesión si es correcto

2. **Añade un método para reenviar el código si es necesario**

## Fase 5: Agregar rutas nuevas

1. **Actualiza el archivo routes.php**:
   - Añade rutas para la verificación
   - Añade ruta para reenvío de código

## Fase 6: Pruebas y depuración

1. **Prueba incremental**:
   - Prueba el flujo completo de inicio de sesión
   - Verifica que el código se envíe correctamente
   - Prueba el reenvío del código
   - Verifica redirecciones y mensajes

¿Quieres comenzar por alguna fase específica o prefieres que te proporcione instrucciones detalladas para la Fase 1?