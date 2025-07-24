-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 10-07-2025 a las 03:04:18
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `anio_academico`
--

CREATE TABLE `anio_academico` (
  `id_anio` int(11) NOT NULL,
  `anio` int(4) NOT NULL,
  `anio_academico` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `anio_academico`
--

INSERT INTO `anio_academico` (`id_anio`, `anio`, `anio_academico`) VALUES
(1, 2025, '071-2025');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `anio_academico`
--
ALTER TABLE `anio_academico`
  ADD PRIMARY KEY (`id_anio`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `anio_academico`
--
ALTER TABLE `anio_academico`
  MODIFY `id_anio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
