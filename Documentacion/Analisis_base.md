# Análisis de la Base de Datos IglesiaEnCasa

El script SQL presentado corresponde a una base de datos diseñada para gestionar la información de los miembros de una iglesia llamada "Iglesia En Casa". A continuación, un análisis de su estructura:

## Estructura General

La base de datos contiene 6 tablas principales:

1. **InformacionGeneral**: Tabla central que almacena los datos básicos de cada miembro.

2. **Contacto**: Información detallada de contacto y documentación.

3. **CarreraBiblica**: Seguimiento del desarrollo espiritual y participación.

4. **EstudiosTrabajo**: Información académica y laboral.

5. **SaludEmergencias**: Datos médicos y contactos de emergencia.

6. **Tallas**: Registro de tallas de ropa y calzado (posiblemente para eventos).

## Relaciones

- La tabla `InformacionGeneral` es la tabla principal.
- Las demás tablas se relacionan con ella mediante un campo `miembro_id`.
- Existe una relación reflexiva en `InformacionGeneral` a través del campo `invitado_por`.
- Todas las relaciones están configuradas con `ON DELETE CASCADE`, excepto `invitado_por` que usa `ON DELETE SET NULL`.

## Características Técnicas

- Motor de almacenamiento: InnoDB (soporta integridad referencial)
- Codificación: utf8mb4_general_ci
- Incluye comentarios detallados en cada campo
- Uso de timestamps automáticos para seguimiento de cambios
- AUTO_INCREMENT configurado para los IDs

Este sistema permite gestionar de manera integral la información de los miembros de la iglesia, desde sus datos personales hasta su recorrido espiritual, facilitando el seguimiento pastoral y administrativo.