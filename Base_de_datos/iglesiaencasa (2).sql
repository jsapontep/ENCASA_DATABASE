-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 02-06-2025 a las 13:20:55
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `iglesiaencasa`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignaciontareas`
--

CREATE TABLE `asignaciontareas` (
  `id` int(11) NOT NULL,
  `tarea_id` int(11) NOT NULL,
  `miembro_id` int(11) NOT NULL,
  `fecha_asignacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_completada` timestamp NULL DEFAULT NULL,
  `comentarios` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrerabiblica`
--

CREATE TABLE `carrerabiblica` (
  `id` int(11) NOT NULL,
  `miembro_id` int(11) NOT NULL,
  `carrera_biblica` varchar(50) DEFAULT NULL,
  `miembro_de` varchar(50) DEFAULT NULL,
  `casa_de_palabra_y_vida` varchar(100) DEFAULT NULL,
  `cobertura` varchar(100) DEFAULT NULL,
  `estado` varchar(50) DEFAULT NULL,
  `anotaciones` text DEFAULT NULL,
  `recorrido_espiritual` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `carrerabiblica`
--

INSERT INTO `carrerabiblica` (`id`, `miembro_id`, `carrera_biblica`, `miembro_de`, `casa_de_palabra_y_vida`, `cobertura`, `estado`, `anotaciones`, `recorrido_espiritual`) VALUES
(6, 13, '', '', '', '', '', '', ''),
(7, 11, '', 'Iglesia En Casa', '', '', '', '', NULL),
(8, 15, 'Discipulado', 'Mujeres', 'Fontibon - Versalles', 'Ps Javier y Paola', 'Activo', 'Comentario etc', ''),
(9, 14, '', 'Iglesia En Casa', '', '', '', '', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contacto`
--

CREATE TABLE `contacto` (
  `id` int(11) NOT NULL,
  `miembro_id` int(11) NOT NULL,
  `tipo_documento` varchar(50) DEFAULT NULL,
  `numero_documento` varchar(20) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `pais` varchar(50) DEFAULT NULL,
  `ciudad` varchar(50) DEFAULT NULL,
  `direccion` varchar(100) DEFAULT NULL,
  `estado_civil` varchar(20) DEFAULT NULL,
  `correo_electronico` varchar(100) DEFAULT NULL,
  `instagram` varchar(100) DEFAULT NULL,
  `facebook` varchar(100) DEFAULT NULL,
  `notas` text DEFAULT NULL,
  `familiares` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `contacto`
--

INSERT INTO `contacto` (`id`, `miembro_id`, `tipo_documento`, `numero_documento`, `telefono`, `pais`, `ciudad`, `direccion`, `estado_civil`, `correo_electronico`, `instagram`, `facebook`, `notas`, `familiares`) VALUES
(10, 13, 'CC', '1056370099', '', 'Colombia', 'Bogotá', '', '', '', '', '', '', ''),
(11, 11, '', '', '', 'Colombia', '', '', '', '', NULL, NULL, NULL, NULL),
(12, 15, 'CC', '55555555', '5555555', 'Colombia', 'Bogotá', 'Cr 100 22h 27', 'Casado/a', 'nat@.', '', '', '', ''),
(13, 14, 'CC', '79687830', '+573023228906', 'Colombia', 'Bogotá', 'cr 107 22h 27', 'Casado/a', '', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiostrabajo`
--

CREATE TABLE `estudiostrabajo` (
  `id` int(11) NOT NULL,
  `miembro_id` int(11) NOT NULL,
  `nivel_estudios` varchar(50) DEFAULT NULL,
  `profesion` varchar(100) DEFAULT NULL,
  `otros_estudios` text DEFAULT NULL,
  `empresa` varchar(100) DEFAULT NULL,
  `direccion_empresa` varchar(200) DEFAULT NULL,
  `emprendimientos` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estudiostrabajo`
--

INSERT INTO `estudiostrabajo` (`id`, `miembro_id`, `nivel_estudios`, `profesion`, `otros_estudios`, `empresa`, `direccion_empresa`, `emprendimientos`) VALUES
(8, 13, '', '', '', '', '', ''),
(9, 11, '', '', '', '', '', ''),
(10, 15, 'Universitario', 'Contadora', 'Crecer, Cebco', 'Idea', 'Cr 12 12 12', 'Esika'),
(11, 14, '', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `informaciongeneral`
--

CREATE TABLE `informaciongeneral` (
  `id` int(11) NOT NULL,
  `nombres` varchar(100) NOT NULL COMMENT 'Nombres del miembro',
  `apellidos` varchar(100) NOT NULL COMMENT 'Apellidos del miembro',
  `celular` varchar(20) NOT NULL COMMENT 'Número de celular principal',
  `localidad` varchar(100) DEFAULT NULL COMMENT 'Localidad o sector de residencia',
  `barrio` varchar(100) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `invitado_por` int(11) DEFAULT NULL,
  `conector` varchar(100) DEFAULT NULL,
  `recorrido_espiritual` text DEFAULT NULL,
  `estado_espiritual` varchar(50) DEFAULT 'Nuevo' COMMENT 'Estado espiritual: Nuevo, Activo, Discípulo, etc',
  `foto` varchar(255) DEFAULT NULL,
  `fecha_ingreso_iglesia` date DEFAULT NULL COMMENT 'Fecha en que el miembro ingresó a la iglesia',
  `fecha_registro_sistema` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Fecha de registro en la plataforma',
  `habeas_data` tinyint(1) DEFAULT 0 COMMENT 'Indica si el miembro aceptó la política de tratamiento de datos'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `informaciongeneral`
--

INSERT INTO `informaciongeneral` (`id`, `nombres`, `apellidos`, `celular`, `localidad`, `barrio`, `fecha_nacimiento`, `invitado_por`, `conector`, `recorrido_espiritual`, `estado_espiritual`, `foto`, `fecha_ingreso_iglesia`, `fecha_registro_sistema`, `habeas_data`) VALUES
(11, 'Administrador', 'Sistema', '+123456789', NULL, NULL, NULL, NULL, NULL, NULL, 'Nuevo', NULL, '2025-05-31', '2025-06-02 11:20:02', 0),
(12, 'Administrador', 'Sistema', '+123456789', NULL, NULL, NULL, NULL, NULL, NULL, 'Nuevo', NULL, '2025-05-31', '2025-06-02 11:20:02', 0),
(13, 'Yohana', 'Villa Ocampo', '+57 3042629153', 'Bogota', '', '0000-00-00', NULL, '', '', '', NULL, '2025-05-31', '2025-06-02 11:20:02', 0),
(14, 'Javier Eduardo', 'Aponte Rodriguez', '+123456789', NULL, NULL, NULL, NULL, NULL, NULL, 'Nuevo', NULL, '2025-05-31', '2025-06-02 11:20:02', 0),
(15, 'Natalie', 'Solano', '+573005555555', 'Fontibón', 'Versalles', '1975-06-10', 14, 'Rafa Gamez', 'Llega de Mision Colombia', 'Activo', '683bbe54da981.png', '2025-05-31', '2025-06-02 11:20:02', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `miembrosministerios`
--

CREATE TABLE `miembrosministerios` (
  `id` int(11) NOT NULL,
  `miembro_id` int(11) NOT NULL,
  `ministerio_id` int(11) NOT NULL,
  `rol_id` int(11) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date DEFAULT NULL COMMENT 'Si está vacío, sigue activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ministerios`
--

CREATE TABLE `ministerios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL COMMENT 'Nombre del ministerio',
  `descripcion` text DEFAULT NULL COMMENT 'Descripción del ministerio',
  `lider_id` int(11) DEFAULT NULL COMMENT 'ID del miembro líder',
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado` varchar(20) DEFAULT 'Activo' COMMENT 'Estado del ministerio'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos`
--

CREATE TABLE `permisos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `permisos`
--

INSERT INTO `permisos` (`id`, `nombre`, `descripcion`, `created_at`) VALUES
(1, 'ver_miembros', 'Ver listado de miembros', '2025-05-24 12:26:38'),
(2, 'crear_miembro', 'Crear nuevos miembros', '2025-05-24 12:26:38'),
(3, 'editar_miembro', 'Editar información de miembros', '2025-05-24 12:26:38'),
(4, 'eliminar_miembro', 'Eliminar miembros', '2025-05-24 12:26:38'),
(5, 'ver_ministerios', 'Ver listado de ministerios', '2025-05-24 12:26:38'),
(6, 'administrar_ministerios', 'Crear, editar y eliminar ministerios', '2025-05-24 12:26:38'),
(7, 'administrar_usuarios', 'Gestionar usuarios del sistema', '2025-05-24 12:26:38'),
(8, 'administrar_roles', 'Gestionar roles y permisos', '2025-05-24 12:26:38');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL COMMENT 'Pastor, Líder, etc',
  `descripcion` text DEFAULT NULL COMMENT 'Descripción detallada',
  `nivel_acceso` int(11) NOT NULL COMMENT 'Nivel jerárquico: 1-5',
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `nombre`, `descripcion`, `nivel_acceso`, `fecha_creacion`) VALUES
(1, 'Pastor', 'Acceso completo al sistema', 5, '2025-05-23 22:03:57'),
(2, 'Copastor', 'Acceso completo al sistema', 5, '2025-05-23 22:03:57'),
(3, 'Líder de Ministerio', 'Acceso a su ministerio y miembros', 4, '2025-05-23 22:03:57'),
(4, 'Servidor', 'Acceso limitado a sus tareas', 3, '2025-05-23 22:03:57'),
(5, 'Miembro', 'Acceso sólo a su información', 2, '2025-05-23 22:03:57'),
(6, 'Visitante', 'Acceso público', 1, '2025-05-23 22:03:57');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rolespermisos`
--

CREATE TABLE `rolespermisos` (
  `rol_id` int(11) NOT NULL,
  `permiso_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rolespermisos`
--

INSERT INTO `rolespermisos` (`rol_id`, `permiso_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(1, 6),
(1, 7),
(1, 8);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `saludemergencias`
--

CREATE TABLE `saludemergencias` (
  `id` int(11) NOT NULL,
  `miembro_id` int(11) NOT NULL,
  `rh` varchar(10) DEFAULT NULL,
  `eps` varchar(100) DEFAULT NULL,
  `acudiente1` varchar(100) DEFAULT NULL,
  `telefono1` varchar(20) DEFAULT NULL,
  `acudiente2` varchar(100) DEFAULT NULL,
  `telefono2` varchar(20) DEFAULT NULL,
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `saludemergencias`
--

INSERT INTO `saludemergencias` (`id`, `miembro_id`, `rh`, `eps`, `acudiente1`, `telefono1`, `acudiente2`, `telefono2`, `fecha_actualizacion`) VALUES
(5, 13, '', '', '', '', '', '', '2025-06-01 02:26:59'),
(6, 11, '', '', '', '', '', '', '2025-06-01 02:36:02'),
(7, 15, 'O+', 'NI-idea', 'CArlos', '55555555', 'Laura', '5555555', '2025-06-01 02:43:32'),
(8, 14, '', '', '', '', '', '', '2025-06-01 02:48:08');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tallas`
--

CREATE TABLE `tallas` (
  `id` int(11) NOT NULL,
  `miembro_id` int(11) NOT NULL,
  `talla_camisa` varchar(10) DEFAULT NULL,
  `talla_camiseta` varchar(10) DEFAULT NULL,
  `talla_pantalon` varchar(10) DEFAULT NULL,
  `talla_zapatos` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tallas`
--

INSERT INTO `tallas` (`id`, `miembro_id`, `talla_camisa`, `talla_camiseta`, `talla_pantalon`, `talla_zapatos`) VALUES
(6, 13, '', '', '', ''),
(7, 11, '', '', '', ''),
(8, 15, 'M', 'M', '10', '36'),
(9, 14, '', '', '', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tareas`
--

CREATE TABLE `tareas` (
  `id` int(11) NOT NULL,
  `ministerio_id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_limite` date DEFAULT NULL,
  `estado` varchar(20) DEFAULT 'Pendiente',
  `creador_id` int(11) DEFAULT NULL COMMENT 'Miembro que creó la tarea'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `miembro_id` int(11) NOT NULL,
  `rol_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `nombre_completo` varchar(100) DEFAULT NULL,
  `estado` varchar(20) DEFAULT 'Activo',
  `ultimo_acceso` timestamp NULL DEFAULT NULL,
  `intentos_fallidos` int(11) DEFAULT 0,
  `token_reset` varchar(100) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `miembro_id`, `rol_id`, `username`, `password`, `email`, `nombre_completo`, `estado`, `ultimo_acceso`, `intentos_fallidos`, `token_reset`, `fecha_creacion`) VALUES
(32, 12, 1, 'Administrador', '$2y$10$xuXT3pvmEprCsNSqBxQI/e.X8Ix4YdUharJDDkj.vp6Wi5TBvQAHW', 'rafa.gzfr@gmail.com', 'Administrador del Sistema', 'Activo', '2025-06-01 02:25:36', 0, NULL, '2025-06-01 09:20:05'),
(33, 14, 1, 'Pastor Javi Aponte', '$2y$10$jbd0iSThhw5g5dN/KacTE.h5CIlyyuDbQtGL3Td3DpN8qKJyZWbEi', 'Javaponte@gmail.com', 'Javier Eduardo Aponte Rodriguez', 'Activo', '2025-06-01 02:37:24', 0, NULL, '2025-06-01 09:34:10');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `verification_codes`
--

CREATE TABLE `verification_codes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `code` varchar(6) NOT NULL,
  `type` varchar(20) NOT NULL DEFAULT 'email_verification',
  `expires_at` datetime NOT NULL,
  `used` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `verification_codes`
--

INSERT INTO `verification_codes` (`id`, `user_id`, `code`, `type`, `expires_at`, `used`, `created_at`) VALUES
(37, 32, '840879', 'login_verification', '2025-05-31 22:20:32', 1, '2025-06-01 02:20:32'),
(38, 32, '475736', 'login_verification', '2025-05-31 22:25:00', 1, '2025-06-01 02:25:00'),
(39, 33, '968433', 'login_verification', '2025-05-31 22:36:52', 1, '2025-06-01 02:36:52');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `asignaciontareas`
--
ALTER TABLE `asignaciontareas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tarea_id` (`tarea_id`),
  ADD KEY `miembro_id` (`miembro_id`);

--
-- Indices de la tabla `carrerabiblica`
--
ALTER TABLE `carrerabiblica`
  ADD PRIMARY KEY (`id`),
  ADD KEY `miembro_id` (`miembro_id`);

--
-- Indices de la tabla `contacto`
--
ALTER TABLE `contacto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `miembro_id` (`miembro_id`);

--
-- Indices de la tabla `estudiostrabajo`
--
ALTER TABLE `estudiostrabajo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `miembro_id` (`miembro_id`);

--
-- Indices de la tabla `informaciongeneral`
--
ALTER TABLE `informaciongeneral`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invitado_por` (`invitado_por`);

--
-- Indices de la tabla `miembrosministerios`
--
ALTER TABLE `miembrosministerios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `miembro_id` (`miembro_id`),
  ADD KEY `ministerio_id` (`ministerio_id`),
  ADD KEY `rol_id` (`rol_id`);

--
-- Indices de la tabla `ministerios`
--
ALTER TABLE `ministerios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lider_id` (`lider_id`);

--
-- Indices de la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `rolespermisos`
--
ALTER TABLE `rolespermisos`
  ADD PRIMARY KEY (`rol_id`,`permiso_id`),
  ADD KEY `permiso_id` (`permiso_id`);

--
-- Indices de la tabla `saludemergencias`
--
ALTER TABLE `saludemergencias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `miembro_id` (`miembro_id`);

--
-- Indices de la tabla `tallas`
--
ALTER TABLE `tallas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `miembro_id` (`miembro_id`);

--
-- Indices de la tabla `tareas`
--
ALTER TABLE `tareas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ministerio_id` (`ministerio_id`),
  ADD KEY `creador_id` (`creador_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `miembro_id` (`miembro_id`),
  ADD KEY `rol_id` (`rol_id`);

--
-- Indices de la tabla `verification_codes`
--
ALTER TABLE `verification_codes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_verification_user_id` (`user_id`),
  ADD KEY `idx_verification_code` (`code`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `asignaciontareas`
--
ALTER TABLE `asignaciontareas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `carrerabiblica`
--
ALTER TABLE `carrerabiblica`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `contacto`
--
ALTER TABLE `contacto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `estudiostrabajo`
--
ALTER TABLE `estudiostrabajo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `informaciongeneral`
--
ALTER TABLE `informaciongeneral`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `miembrosministerios`
--
ALTER TABLE `miembrosministerios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ministerios`
--
ALTER TABLE `ministerios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `permisos`
--
ALTER TABLE `permisos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `saludemergencias`
--
ALTER TABLE `saludemergencias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `tallas`
--
ALTER TABLE `tallas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `tareas`
--
ALTER TABLE `tareas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT de la tabla `verification_codes`
--
ALTER TABLE `verification_codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `asignaciontareas`
--
ALTER TABLE `asignaciontareas`
  ADD CONSTRAINT `asignaciontareas_ibfk_1` FOREIGN KEY (`tarea_id`) REFERENCES `tareas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `asignaciontareas_ibfk_2` FOREIGN KEY (`miembro_id`) REFERENCES `informaciongeneral` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `carrerabiblica`
--
ALTER TABLE `carrerabiblica`
  ADD CONSTRAINT `carrerabiblica_ibfk_1` FOREIGN KEY (`miembro_id`) REFERENCES `informaciongeneral` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `contacto`
--
ALTER TABLE `contacto`
  ADD CONSTRAINT `contacto_ibfk_1` FOREIGN KEY (`miembro_id`) REFERENCES `informaciongeneral` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `estudiostrabajo`
--
ALTER TABLE `estudiostrabajo`
  ADD CONSTRAINT `estudiostrabajo_ibfk_1` FOREIGN KEY (`miembro_id`) REFERENCES `informaciongeneral` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `informaciongeneral`
--
ALTER TABLE `informaciongeneral`
  ADD CONSTRAINT `informaciongeneral_ibfk_1` FOREIGN KEY (`invitado_por`) REFERENCES `informaciongeneral` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `miembrosministerios`
--
ALTER TABLE `miembrosministerios`
  ADD CONSTRAINT `miembrosministerios_ibfk_1` FOREIGN KEY (`miembro_id`) REFERENCES `informaciongeneral` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `miembrosministerios_ibfk_2` FOREIGN KEY (`ministerio_id`) REFERENCES `ministerios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `miembrosministerios_ibfk_3` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `ministerios`
--
ALTER TABLE `ministerios`
  ADD CONSTRAINT `ministerios_ibfk_1` FOREIGN KEY (`lider_id`) REFERENCES `informaciongeneral` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `rolespermisos`
--
ALTER TABLE `rolespermisos`
  ADD CONSTRAINT `rolespermisos_ibfk_1` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rolespermisos_ibfk_2` FOREIGN KEY (`permiso_id`) REFERENCES `permisos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `saludemergencias`
--
ALTER TABLE `saludemergencias`
  ADD CONSTRAINT `saludemergencias_ibfk_1` FOREIGN KEY (`miembro_id`) REFERENCES `informaciongeneral` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `tallas`
--
ALTER TABLE `tallas`
  ADD CONSTRAINT `tallas_ibfk_1` FOREIGN KEY (`miembro_id`) REFERENCES `informaciongeneral` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `tareas`
--
ALTER TABLE `tareas`
  ADD CONSTRAINT `tareas_ibfk_1` FOREIGN KEY (`ministerio_id`) REFERENCES `ministerios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tareas_ibfk_2` FOREIGN KEY (`creador_id`) REFERENCES `informaciongeneral` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`miembro_id`) REFERENCES `informaciongeneral` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `usuarios_ibfk_2` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`);

--
-- Filtros para la tabla `verification_codes`
--
ALTER TABLE `verification_codes`
  ADD CONSTRAINT `verification_codes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
