# Estrategia de Control de Versiones para el Plan de Etapas

## Estructura de Branches por Etapas

- **main**: Código estable de producción
- **develop**: Rama de desarrollo integrado
- **etapa/1-configuracion**: Configuración del entorno y BD
- **etapa/2-mvc**: Núcleo MVC y enrutamiento
- **etapa/3-auth**: Sistema de autenticación
- **etapa/4-miembros**: CRUD de miembros
- **etapa/5-ministerios**: Ministerios, roles y tareas
- **etapa/6-ui**: Vistas y UI/UX
- **etapa/7-optimizacion**: Integración y optimización
- **etapa/8-deploy**: Despliegue y documentación

### Flujo de trabajo con branches de características

Para cada etapa, usar branches de características:
- **feature/etapa3/login**
- **feature/etapa4/miembro-form**
- **fix/etapa3/password-reset**

## Convenciones de Commits

Formato: `etapa(componente): descripción`

Ejemplos:
- `etapa3(auth): implementar login con remember me`
- `etapa4(miembros): crear formulario de registro con validación`
- `fix(auth): corregir validación de contraseñas`