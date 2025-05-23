-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 23-05-2025 a las 23:40:35
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `IglesiaEnCasa`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CarreraBiblica`
--

CREATE TABLE `CarreraBiblica` (
  `id` int(11) NOT NULL,
  `miembro_id` int(11) NOT NULL,
  `carrera_biblica` varchar(100) DEFAULT NULL COMMENT 'Nivel o curso bíblico actual',
  `miembro_de` varchar(100) DEFAULT NULL COMMENT 'Grupo o ministerio al que pertenece',
  `casa_de_palabra_y_vida` varchar(100) DEFAULT NULL COMMENT 'Casa o grupo pequeño asignado',
  `cobertura` varchar(100) DEFAULT NULL COMMENT 'Líder o pastor que le cubre espiritualmente',
  `estado` varchar(20) DEFAULT NULL COMMENT 'Estado de participación: Activo, Inactivo, Intermitente, Nuevo',
  `anotaciones` text DEFAULT NULL COMMENT 'Observaciones adicionales',
  `recorrido_espiritual` text DEFAULT NULL COMMENT 'Registro del crecimiento espiritual',
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Contacto`
--

CREATE TABLE `Contacto` (
  `id` int(11) NOT NULL,
  `miembro_id` int(11) NOT NULL,
  `tipo_documento` varchar(30) DEFAULT NULL COMMENT 'Cédula, Pasaporte, etc.',
  `numero_documento` varchar(30) NOT NULL COMMENT 'Número de identificación',
  `telefono` varchar(20) DEFAULT NULL COMMENT 'Teléfono fijo formato internacional',
  `pais` varchar(100) DEFAULT NULL COMMENT 'País de residencia',
  `ciudad` varchar(100) DEFAULT NULL COMMENT 'Ciudad de residencia',
  `direccion` varchar(255) DEFAULT NULL COMMENT 'Dirección completa de residencia',
  `estado_civil` varchar(20) DEFAULT NULL COMMENT 'Soltero, Casado, etc.',
  `correo_electronico` varchar(100) DEFAULT NULL COMMENT 'Formato email',
  `instagram` varchar(50) DEFAULT NULL COMMENT 'Usuario de Instagram',
  `facebook` varchar(100) DEFAULT NULL COMMENT 'Perfil de Facebook',
  `notas` text DEFAULT NULL COMMENT 'Observaciones adicionales',
  `familiares` varchar(255) DEFAULT NULL COMMENT 'Tipos de familiares en la iglesia',
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `EstudiosTrabajo`
--

CREATE TABLE `EstudiosTrabajo` (
  `id` int(11) NOT NULL,
  `miembro_id` int(11) NOT NULL,
  `nivel_estudios` varchar(50) DEFAULT NULL COMMENT 'Primaria, Secundaria, Pregrado, etc.',
  `profesion` varchar(100) DEFAULT NULL COMMENT 'Profesión o campo de estudio',
  `otros_estudios` text DEFAULT NULL COMMENT 'Descripción de estudios adicionales',
  `empresa` varchar(150) DEFAULT NULL COMMENT 'Empresa donde trabaja actualmente',
  `direccion_empresa` varchar(255) DEFAULT NULL COMMENT 'Dirección de la empresa en formato internacional',
  `emprendimientos` text DEFAULT NULL COMMENT 'Descripción de emprendimientos personales',
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `InformacionGeneral`
--

CREATE TABLE `InformacionGeneral` (
  `id` int(11) NOT NULL,
  `nombres` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `celular` varchar(20) NOT NULL COMMENT 'Formato internacional: +123456789',
  `localidad` varchar(50) DEFAULT NULL COMMENT 'Localidad de Bogotá',
  `barrio` varchar(100) DEFAULT NULL COMMENT 'Barrio de Bogotá',
  `fecha_nacimiento` date DEFAULT NULL COMMENT 'Formato: YYYY-MM-DD',
  `fecha_ingreso` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Fecha de registro en la iglesia',
  `invitado_por` int(11) DEFAULT NULL COMMENT 'ID de la persona que lo invitó',
  `conector` varchar(50) DEFAULT NULL COMMENT 'Tipo de conexión con la iglesia',
  `recorrido_espiritual` text DEFAULT NULL COMMENT 'Observaciones del recorrido espiritual',
  `estado_espiritual` varchar(50) DEFAULT NULL COMMENT 'Activo, Inactivo, Intermitente, Nuevo, etc.',
  `foto` varchar(255) DEFAULT NULL COMMENT 'Ruta de la imagen subida',
  `habeas_data` text DEFAULT NULL COMMENT 'Consentimiento para el tratamiento de datos'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Roles`
--

CREATE TABLE `Roles` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `nivel_permiso` tinyint(1) NOT NULL DEFAULT 1,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `RolesUsuario`
--

CREATE TABLE `RolesUsuario` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `rol_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `SaludEmergencias`
--

CREATE TABLE `SaludEmergencias` (
  `id` int(11) NOT NULL,
  `miembro_id` int(11) NOT NULL,
  `rh` varchar(5) DEFAULT NULL COMMENT 'Tipo de sangre (A+, O-, etc.)',
  `acudiente1` varchar(100) DEFAULT NULL COMMENT 'Nombre del primer contacto de emergencia',
  `telefono1` varchar(20) DEFAULT NULL COMMENT 'Teléfono del primer contacto en formato internacional',
  `acudiente2` varchar(100) DEFAULT NULL COMMENT 'Nombre del segundo contacto de emergencia',
  `telefono2` varchar(20) DEFAULT NULL COMMENT 'Teléfono del segundo contacto en formato internacional',
  `eps` varchar(50) DEFAULT NULL COMMENT 'Entidad Promotora de Salud',
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Tallas`
--

CREATE TABLE `Tallas` (
  `id` int(11) NOT NULL,
  `miembro_id` int(11) NOT NULL,
  `talla_camisa` varchar(10) DEFAULT NULL COMMENT 'XS, S, M, L, XL, etc.',
  `talla_camiseta` varchar(10) DEFAULT NULL COMMENT 'XS, S, M, L, XL, etc.',
  `talla_pantalon` varchar(10) DEFAULT NULL COMMENT 'Numérico (30, 32) o letra (S, M, L)',
  `talla_zapatos` varchar(10) DEFAULT NULL COMMENT 'Numeración de calzado',
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Usuarios`
--

CREATE TABLE `Usuarios` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nombre_completo` varchar(100) DEFAULT NULL,
  `ultimo_login` timestamp NULL DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `Usuarios`
--

INSERT INTO `Usuarios` (`id`, `email`, `username`, `password`, `nombre_completo`, `ultimo_login`, `activo`, `fecha_creacion`) VALUES
(1, 'rafa.gzfr@gmail.com', 'Administrador', '$2y$10$y9AALAB5Czt/uXmu3gmIK.83vwrLwjIpo5BM/rGtq/B0co1GvU1ke', 'Hernando Rafael Gamez Fragozo', NULL, 1, '2025-05-23 01:11:19'),
(2, 'javaponte@gmail.com', 'japonte', '$2y$10$HRZl/z46KjzhabBJsZQ7MuPWcseWVGilBeS..W0nt.X7KnTTOzwna', 'Javier Aponte Rodríguez', NULL, 1, '2025-05-23 01:16:33'),
(3, 'admin2@ejemplo.com', 'admin2', '$2y$10$U1AYKUTl8QwCx4jiSxpuzOUncFdNm6ulnjpIhE0ByRcZjaF6YrHxS', 'Administrador 2', NULL, 1, '2025-05-23 01:36:25');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `CarreraBiblica`
--
ALTER TABLE `CarreraBiblica`
  ADD PRIMARY KEY (`id`),
  ADD KEY `miembro_id` (`miembro_id`);

--
-- Indices de la tabla `Contacto`
--
ALTER TABLE `Contacto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `miembro_id` (`miembro_id`);

--
-- Indices de la tabla `EstudiosTrabajo`
--
ALTER TABLE `EstudiosTrabajo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `miembro_id` (`miembro_id`);

--
-- Indices de la tabla `InformacionGeneral`
--
ALTER TABLE `InformacionGeneral`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invitado_por` (`invitado_por`);

--
-- Indices de la tabla `Roles`
--
ALTER TABLE `Roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `RolesUsuario`
--
ALTER TABLE `RolesUsuario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario_rol` (`usuario_id`,`rol_id`),
  ADD KEY `rolesusuario_ibfk_2` (`rol_id`);

--
-- Indices de la tabla `SaludEmergencias`
--
ALTER TABLE `SaludEmergencias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `miembro_id` (`miembro_id`);

--
-- Indices de la tabla `Tallas`
--
ALTER TABLE `Tallas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `miembro_id` (`miembro_id`);

--
-- Indices de la tabla `Usuarios`
--
ALTER TABLE `Usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `CarreraBiblica`
--
ALTER TABLE `CarreraBiblica`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `Contacto`
--
ALTER TABLE `Contacto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `EstudiosTrabajo`
--
ALTER TABLE `EstudiosTrabajo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `InformacionGeneral`
--
ALTER TABLE `InformacionGeneral`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `Roles`
--
ALTER TABLE `Roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `RolesUsuario`
--
ALTER TABLE `RolesUsuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `SaludEmergencias`
--
ALTER TABLE `SaludEmergencias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `Tallas`
--
ALTER TABLE `Tallas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `Usuarios`
--
ALTER TABLE `Usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `CarreraBiblica`
--
ALTER TABLE `CarreraBiblica`
  ADD CONSTRAINT `carrerabiblica_ibfk_1` FOREIGN KEY (`miembro_id`) REFERENCES `InformacionGeneral` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `Contacto`
--
ALTER TABLE `Contacto`
  ADD CONSTRAINT `contacto_ibfk_1` FOREIGN KEY (`miembro_id`) REFERENCES `InformacionGeneral` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `EstudiosTrabajo`
--
ALTER TABLE `EstudiosTrabajo`
  ADD CONSTRAINT `estudiostrabajo_ibfk_1` FOREIGN KEY (`miembro_id`) REFERENCES `InformacionGeneral` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `InformacionGeneral`
--
ALTER TABLE `InformacionGeneral`
  ADD CONSTRAINT `informaciongeneral_ibfk_1` FOREIGN KEY (`invitado_por`) REFERENCES `InformacionGeneral` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `RolesUsuario`
--
ALTER TABLE `RolesUsuario`
  ADD CONSTRAINT `rolesusuario_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `Usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rolesusuario_ibfk_2` FOREIGN KEY (`rol_id`) REFERENCES `Roles` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `SaludEmergencias`
--
ALTER TABLE `SaludEmergencias`
  ADD CONSTRAINT `saludemergencias_ibfk_1` FOREIGN KEY (`miembro_id`) REFERENCES `InformacionGeneral` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `Tallas`
--
ALTER TABLE `Tallas`
  ADD CONSTRAINT `tallas_ibfk_1` FOREIGN KEY (`miembro_id`) REFERENCES `InformacionGeneral` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
