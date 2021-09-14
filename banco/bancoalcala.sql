-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 17-05-2020 a las 13:01:24
-- Versión del servidor: 10.4.11-MariaDB
-- Versión de PHP: 7.4.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `bancoalcala`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuenta`
--

CREATE TABLE `cuenta` (
  `Cuenta` int(4) UNSIGNED ZEROFILL NOT NULL,
  `Saldo` float NOT NULL,
  `Pin` int(4) UNSIGNED ZEROFILL NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `cuenta`
--

INSERT INTO `cuenta` (`Cuenta`, `Saldo`, `Pin`) VALUES
(0001, 1799.5, 8217),
(0002, 4050.5, 0276),
(0003, 1250, 6234),
(0004, 1250, 3308),
(0005, 1250, 2492),
(0006, 1250, 9900),
(0007, 1250, 6264),
(0008, 1250, 9466);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimientos`
--

CREATE TABLE `movimientos` (
  `Id` bigint(20) UNSIGNED NOT NULL,
  `Cuenta` int(4) UNSIGNED ZEROFILL NOT NULL,
  `Descripcion` varchar(200) CHARACTER SET utf8mb4 NOT NULL,
  `Fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `Localidad` varchar(100) CHARACTER SET utf8mb4 NOT NULL,
  `Pais` varchar(100) CHARACTER SET utf8mb4 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `movimientos`
--

INSERT INTO `movimientos` (`Id`, `Cuenta`, `Descripcion`, `Fecha`, `Localidad`, `Pais`) VALUES
(1, 0001, 'Ingreso de 150.5 en la cuenta 0001', '2020-05-13 15:33:55', '', ''),
(2, 0001, 'Ingreso de 150.5 en la cuenta 0001', '2020-05-13 15:34:18', '', ''),
(3, 0002, 'Ingreso de 1000 en la cuenta 0002', '2020-05-13 15:44:44', '', ''),
(4, 0002, 'Ingreso de 1000 en la cuenta 0002', '2020-05-13 15:45:55', '', ''),
(5, 0001, 'Retirada de 100 en la cuenta 0001', '2020-05-13 16:15:35', '', ''),
(6, 0001, 'Retirada de 451 en la cuenta 0001', '2020-05-13 16:19:26', '', ''),
(9, 0001, 'Una descripcion super larga para ver como actua la listview, si pone el texto abqajo, se lo come o uqe', '2020-05-14 10:46:10', '', ''),
(10, 0001, 'Transferencia de 500 de la cuenta 0001 a la cuenta 0002', '2020-05-14 11:19:40', '', ''),
(11, 0001, 'Transferencia de 500.50 de la cuenta 0001 a la cuenta 0002', '2020-05-14 11:37:51', '', ''),
(12, 0001, 'Recarga de 50 de la cuenta 0001 al telefono 628350908', '2020-05-14 15:57:23', '', ''),
(13, 0001, 'Recarga de 50 de la cuenta 0001 al telefono 628350908', '2020-05-14 15:57:35', '', ''),
(14, 0001, 'Ingreso de 50 en la cuenta 0001', '2020-05-15 15:46:53', '', ''),
(15, 0001, 'Ingreso de 50 en la cuenta 0001', '2020-05-15 16:38:48', 'Guadalajara', 'España'),
(16, 0001, 'Transferencia de 100 de la cuenta 0001 a la cuenta 0002', '2020-05-15 17:24:33', 'Guadalajara', 'España'),
(17, 0001, 'Transferencia de 100 de la cuenta 0001 a la cuenta 0002', '2020-05-15 17:27:39', 'Guadalajara', 'España');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cuenta`
--
ALTER TABLE `cuenta`
  ADD PRIMARY KEY (`Cuenta`);

--
-- Indices de la tabla `movimientos`
--
ALTER TABLE `movimientos`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `Id` (`Id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cuenta`
--
ALTER TABLE `cuenta`
  MODIFY `Cuenta` int(4) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `movimientos`
--
ALTER TABLE `movimientos`
  MODIFY `Id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
