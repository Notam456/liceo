-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 10-06-2025 a las 17:46:13
-- Versión del servidor: 10.4.32-MariaDB-log
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `liceo`
--
-- Crea la base de datos 'liceo' si no existe. Esto resuelve el error "Base de datos desconocida".
CREATE DATABASE IF NOT EXISTS asisssss;
-- Selecciona la base de datos 'liceo' para todas las operaciones subsiguientes.
USE asisssss;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistencia`
--

CREATE TABLE `asistencia` (
  `id` int(11) NOT NULL,
  `estudiante_id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `estado` enum('Presente','Ausente','Retardo','Justificada') NOT NULL,
  `comentarios` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `coordinadores`
--

CREATE TABLE `coordinadores` (
  `id_coordinadores` int(11) NOT NULL,
  `nombre_coordinadores` varchar(20) NOT NULL,
  `apellido_coordinadores` varchar(20) NOT NULL,
  `cedula_coordinadores` varchar(7) NOT NULL,
  `contacto_coordinadores` varchar(15) NOT NULL,
  `area_coordinacion` varchar(15) NOT NULL,
  `seccion_coordinadores` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `coordinadores`
--

INSERT INTO `coordinadores` (`id_coordinadores`, `nombre_coordinadores`, `apellido_coordinadores`, `cedula_coordinadores`, `contacto_coordinadores`, `area_coordinacion`, `seccion_coordinadores`) VALUES
(0, 'Roberto', 'Vielma', '3136115', '04126739379', 'Media general', 'que es esto');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiante`
--

CREATE TABLE `estudiante` (
  `id_estudiante` int(25) NOT NULL,
  `nombre_estudiante` varchar(50) NOT NULL,
  `apellido_estudiante` varchar(50) NOT NULL,
  `cedula_estudiante` int(8) NOT NULL,
  `contacto_estudiante` bigint(11) NOT NULL,
  `año_academico` varchar(10) NOT NULL,
  `seccion_estudiante` varchar(10) NOT NULL,
  `Municipio` text NOT NULL,
  `Parroquia` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estudiante`
--

INSERT INTO `estudiante` (`id_estudiante`, `nombre_estudiante`, `apellido_estudiante`, `cedula_estudiante`, `contacto_estudiante`, `año_academico`, `seccion_estudiante`, `Municipio`, `Parroquia`) VALUES
(1, 'José Felix', 'Yajure Arrieche', 30426270, 4143737810, '04-2025', 'B', '', ''),
(2, 'Roberto Carlos', 'Vielma Quevedo', 31361157, 4126739379, '05-2025', 'A', '', ''),
(3, 'Yhoenyer Alexander', 'Alvarado Fernández', 30795287, 4264251473, '04-2025', 'B', '', ''),
(4, 'Jose Luis', 'Peralta', 10101, 10101, '02-2025', 'C', '', ''),
(5, 'Jose Antonio', 'González', 10101, 10101, '02-2025', 'C', '', ''),
(8, '', '', 0, 0, '', '', '', ''),
(9, '', '', 0, 0, '', '', '', ''),
(10, '', '', 0, 0, '', '', '', ''),
(11, '', '', 0, 0, '', '', '', ''),
(12, 'Pepito Alberto', 'Camaron Rodriguez', 0, 0, 'asdasdsa', 'asdsadasd', 'Mi casa', 'La tuya');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `materia`
--

CREATE TABLE `materia` (
  `id` int(10) NOT NULL,
  `nombre_materia` varchar(30) NOT NULL,
  `info_materia` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `materia`
--

INSERT INTO `materia` (`id`, `nombre_materia`, `info_materia`) VALUES
(1, 'Matematicas', 'La matemática es una ciencia formal y fundamental que estudia las propiedades y relaciones entre entidades abstractas como números, figuras geométricas, símbolos y conceptos.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profesores`
--

CREATE TABLE `profesores` (
  `id_profesores` int(11) NOT NULL,
  `nombre_profesores` varchar(20) NOT NULL,
  `apellido_profesores` varchar(20) NOT NULL,
  `cedula_profesores` varchar(7) NOT NULL,
  `contacto_profesores` varchar(15) NOT NULL,
  `materia_impartida` varchar(15) NOT NULL,
  `seccion_profesores` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `profesores`
--

INSERT INTO `profesores` (`id_profesores`, `nombre_profesores`, `apellido_profesores`, `cedula_profesores`, `contacto_profesores`, `materia_impartida`, `seccion_profesores`) VALUES
(0, 'asdas', 'asdasd', 'asdasd', 'asdasd', 'asdasd', 'sadasd');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `seccion`
--

CREATE TABLE `seccion` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `año` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `seccion`
--

INSERT INTO `seccion` (`id`, `nombre`, `año`) VALUES
(1, '1°A', 1),
(2, '3°A', 3),
(3, '4°A', 4),
(4, '5°D', 5),
(5, '2°D', 2),
(12, '2°B', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id` int(255) NOT NULL,
  `usuario` varchar(255) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `rol` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `usuario`, `contrasena`, `rol`) VALUES
(1, 'Roberto', 'Hola1234!', 'admin');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `asistencia`
--
ALTER TABLE `asistencia`
  ADD PRIMARY KEY (`id`),
  ADD KEY `estudiante_id` (`estudiante_id`) USING BTREE;

--
-- Indices de la tabla `coordinadores`
--
ALTER TABLE `coordinadores`
  ADD PRIMARY KEY (`id_coordinadores`);

--
-- Indices de la tabla `estudiante`
--
ALTER TABLE `estudiante`
  ADD PRIMARY KEY (`id_estudiante`);

--
-- Indices de la tabla `materia`
--
ALTER TABLE `materia`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `profesores`
--
ALTER TABLE `profesores`
  ADD PRIMARY KEY (`id_profesores`);

--
-- Indices de la tabla `seccion`
--
ALTER TABLE `seccion`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `asistencia`
--
ALTER TABLE `asistencia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `estudiante`
--
ALTER TABLE `estudiante`
  MODIFY `id_estudiante` int(25) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `seccion`
--
ALTER TABLE `seccion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `asistencia`
--
ALTER TABLE `asistencia`
  ADD CONSTRAINT `asistencia_ibfk_1` FOREIGN KEY (`estudiante_id`) REFERENCES `estudiante` (`id_estudiante`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;