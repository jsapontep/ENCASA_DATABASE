# Diagrama de Tablas - Base de Datos IglesiaEnCasa

## Tabla: InformacionGeneral

```
┌─────────────────────────────────────────────────────────────┐
│ InformacionGeneral                                          │
├───────────────────────┬─────────────┬─────────────────────┐ │
│ id (PK)               │ INT         │ AUTO_INCREMENT      │ │
│ nombres               │ VARCHAR(100)│ NOT NULL            │ │
│ apellidos             │ VARCHAR(100)│ NOT NULL            │ │
│ celular               │ VARCHAR(20) │ NOT NULL            │ │
│ localidad             │ VARCHAR(50) │ Localidad de Bogotá │ │
│ barrio                │ VARCHAR(100)│ Barrio de Bogotá    │ │
│ fecha_nacimiento      │ DATE        │ YYYY-MM-DD          │ │
│ fecha_ingreso         │ TIMESTAMP   │ DEFAULT CURRENT     │ │
│ invitado_por (FK)     │ INT         │ ID del invitador    │ │
│ conector              │ VARCHAR(50) │ Tipo de conexión    │ │
│ recorrido_espiritual  │ TEXT        │ Observaciones       │ │
│ estado_espiritual     │ VARCHAR(50) │ Estado de actividad │ │
│ foto                  │ VARCHAR(255)│ Ruta de la imagen   │ │
│ habeas_data           │ TEXT        │ Consentimiento      │ │
└───────────────────────┴─────────────┴─────────────────────┘ │
└─────────────────────────────────────────────────────────────┘
```

## Tabla: Contacto

```
┌─────────────────────────────────────────────────────────────┐
│ Contacto                                                    │
├───────────────────────┬─────────────┬─────────────────────┐ │
│ id (PK)               │ INT         │ AUTO_INCREMENT      │ │
│ miembro_id (FK)       │ INT         │ NOT NULL            │ │
│ tipo_documento        │ VARCHAR(30) │ Cédula, Pasaporte   │ │
│ numero_documento      │ VARCHAR(30) │ NOT NULL            │ │
│ telefono              │ VARCHAR(20) │ Formato internacional│ │
│ pais                  │ VARCHAR(100)│ País de residencia  │ │
│ ciudad                │ VARCHAR(100)│ Ciudad de residencia│ │
│ direccion             │ VARCHAR(255)│ Dirección completa  │ │
│ estado_civil          │ VARCHAR(20) │ Soltero, Casado     │ │
│ correo_electronico    │ VARCHAR(100)│ Formato email       │ │
│ instagram             │ VARCHAR(50) │ Usuario de Instagram│ │
│ facebook              │ VARCHAR(100)│ Perfil de Facebook  │ │
│ notas                 │ TEXT        │ Observaciones       │ │
│ familiares            │ VARCHAR(255)│ Tipos de familiares │ │
│ fecha_actualizacion   │ TIMESTAMP   │ AUTO UPDATE         │ │
└───────────────────────┴─────────────┴─────────────────────┘ │
└─────────────────────────────────────────────────────────────┘
```

## Tabla: CarreraBiblica

```
┌─────────────────────────────────────────────────────────────┐
│ CarreraBiblica                                              │
├───────────────────────┬─────────────┬─────────────────────┐ │
│ id (PK)               │ INT         │ AUTO_INCREMENT      │ │
│ miembro_id (FK)       │ INT         │ NOT NULL            │ │
│ carrera_biblica       │ VARCHAR(100)│ Nivel o curso actual│ │
│ miembro_de            │ VARCHAR(100)│ Grupo o ministerio  │ │
│ casa_de_palabra_y_vida│ VARCHAR(100)│ Grupo pequeño       │ │
│ cobertura             │ VARCHAR(100)│ Líder o pastor      │ │
│ estado                │ VARCHAR(20) │ Estado participación│ │
│ anotaciones           │ TEXT        │ Observaciones       │ │
│ recorrido_espiritual  │ TEXT        │ Registro crecimiento│ │
│ fecha_registro        │ TIMESTAMP   │ DEFAULT CURRENT     │ │
│ fecha_actualizacion   │ TIMESTAMP   │ AUTO UPDATE         │ │
└───────────────────────┴─────────────┴─────────────────────┘ │
└─────────────────────────────────────────────────────────────┘
```

## Tabla: EstudiosTrabajo

```
┌─────────────────────────────────────────────────────────────┐
│ EstudiosTrabajo                                             │
├───────────────────────┬─────────────┬─────────────────────┐ │
│ id (PK)               │ INT         │ AUTO_INCREMENT      │ │
│ miembro_id (FK)       │ INT         │ NOT NULL            │ │
│ nivel_estudios        │ VARCHAR(50) │ Primaria, Secundaria│ │
│ profesion             │ VARCHAR(100)│ Campo de estudio    │ │
│ otros_estudios        │ TEXT        │ Estudios adicionales│ │
│ empresa               │ VARCHAR(150)│ Empresa actual      │ │
│ direccion_empresa     │ VARCHAR(255)│ Dirección laboral   │ │
│ emprendimientos       │ TEXT        │ Emprendimientos     │ │
│ fecha_actualizacion   │ TIMESTAMP   │ AUTO UPDATE         │ │
└───────────────────────┴─────────────┴─────────────────────┘ │
└─────────────────────────────────────────────────────────────┘
```

## Tabla: SaludEmergencias

```
┌─────────────────────────────────────────────────────────────┐
│ SaludEmergencias                                            │
├───────────────────────┬─────────────┬─────────────────────┐ │
│ id (PK)               │ INT         │ AUTO_INCREMENT      │ │
│ miembro_id (FK)       │ INT         │ NOT NULL            │ │
│ rh                    │ VARCHAR(5)  │ Tipo de sangre      │ │
│ acudiente1            │ VARCHAR(100)│ Contacto emergencia1│ │
│ telefono1             │ VARCHAR(20) │ Teléfono contacto1  │ │
│ acudiente2            │ VARCHAR(100)│ Contacto emergencia2│ │
│ telefono2             │ VARCHAR(20) │ Teléfono contacto2  │ │
│ eps                   │ VARCHAR(50) │ Entidad de Salud    │ │
│ fecha_actualizacion   │ TIMESTAMP   │ AUTO UPDATE         │ │
└───────────────────────┴─────────────┴─────────────────────┘ │
└─────────────────────────────────────────────────────────────┘
```

## Tabla: Tallas

```
┌─────────────────────────────────────────────────────────────┐
│ Tallas                                                      │
├───────────────────────┬─────────────┬─────────────────────┐ │
│ id (PK)               │ INT         │ AUTO_INCREMENT      │ │
│ miembro_id (FK)       │ INT         │ NOT NULL            │ │
│ talla_camisa          │ VARCHAR(10) │ XS, S, M, L, XL     │ │
│ talla_camiseta        │ VARCHAR(10) │ XS, S, M, L, XL     │ │
│ talla_pantalon        │ VARCHAR(10) │ Numérico o letra    │ │
│ talla_zapatos         │ VARCHAR(10) │ Numeración calzado  │ │
│ fecha_actualizacion   │ TIMESTAMP   │ AUTO UPDATE         │ │
└───────────────────────┴─────────────┴─────────────────────┘ │
└─────────────────────────────────────────────────────────────┘
```

## Relaciones entre tablas

- **InformacionGeneral** es la tabla principal.
- Las tablas **Contacto**, **CarreraBiblica**, **EstudiosTrabajo**, **SaludEmergencias** y **Tallas** se relacionan con **InformacionGeneral** mediante la clave foránea `miembro_id`.
- **InformacionGeneral** tiene una auto-relación a través de `invitado_por`.
- Todas las relaciones tienen `ON DELETE CASCADE` excepto `invitado_por` que usa `ON DELETE SET NULL`.

Código similar encontrado con 4 tipos de licencias