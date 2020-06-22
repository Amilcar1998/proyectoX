-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-06-2020 a las 19:58:55
-- Versión del servidor: 10.4.11-MariaDB
-- Versión de PHP: 7.4.5
-- drop database concentrados;
create database concentrados;
use concentrados;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `concentrados`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `idCliente` int(11) NOT NULL,
  `NombreCliente` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `apellidosCliente` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `telefono` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `edad` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `genero` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `idUsuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`idCliente`, `NombreCliente`, `apellidosCliente`, `telefono`, `edad`, `genero`, `idUsuario`) VALUES
(206, 'sindy', 'lopez', '70580440', '12', 'Hombre', 391266),
(210, 'amanda', 'anzora', '0000000', '12', 'Hombre', 391292);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detallecompra`
--

CREATE TABLE `detallecompra` (
  `idDetalleCompra` int(11) NOT NULL,
  `idMateriaPrima` int(11) NOT NULL,
  `cantidadMP` int(45) DEFAULT NULL,
  `precioMP` double DEFAULT NULL,
  `idFacturaMP` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `detallecompra`
--

INSERT INTO `detallecompra` (`idDetalleCompra`, `idMateriaPrima`, `cantidadMP`, `precioMP`, `idFacturaMP`) VALUES
(1, 4, 200, 50, 23232);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detallepedido`
--

CREATE TABLE `detallepedido` (
  `idDetallePedido` int(11) NOT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `idReceta` int(11) NOT NULL,
  `IdPedido` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `detallepedido`
--

INSERT INTO `detallepedido` (`idDetallePedido`, `cantidad`, `idReceta`, `IdPedido`) VALUES
(1, 20, 2, 1),
(2, 20, 1, 4),
(3, 2, 2, 4),
(4, 0, 1, 54),
(5, 2, 1, 55),
(6, 2, 1, 56),
(7, 2, 1, 57),
(8, 2, 1, 58),
(9, 2, 1, 59),
(10, 2, 1, 60),
(11, 2, 1, 61),
(12, 2, 1, 62),
(13, 5, 2, 1),
(14, 34, 2, 1),
(15, 2, 1, 1),
(16, 5, 1, 1),
(17, 1, 1, 66),
(18, 13, 2, 67),
(19, 12, 2, 68),
(20, 2000, 1, 69),
(21, 0, 1, 70),
(22, 0, 1, 71),
(23, 1, 1, 72),
(24, 1, 1, 73),
(25, 2, 1, 74),
(26, 2, 1, 75),
(27, 2, 1, 76),
(28, 2, 1, 77),
(29, 2, 1, 78),
(30, 2, 1, 79),
(31, 2, 1, 80),
(32, 343, 1, 81),
(33, 343, 1, 82),
(34, 343, 1, 83),
(35, 343, 1, 84),
(36, 3, 1, 85),
(37, 2, 1, 86),
(38, 2, 1, 87),
(39, 2, 1, 88),
(40, 2, 1, 89),
(41, 2, 1, 90),
(42, 23, 1, 91),
(43, 23, 1, 92),
(44, 23, 1, 93),
(45, 34, 1, 94),
(46, 34, 1, 95),
(47, 232, 1, 96),
(48, 2, 1, 97),
(49, 2, 1, 98),
(50, 2, 1, 99),
(51, 2, 1, 100),
(52, 3, 1, 101),
(53, 3, 1, 102),
(54, 3, 1, 103),
(55, 2, 1, 104),
(56, 2, 1, 105),
(57, 2, 1, 106),
(58, 2, 1, 107),
(59, 3, 1, 107),
(60, 34, 2, 107),
(61, 2, 1, 107),
(62, 25, 1, 107),
(63, 33, 1, 107),
(64, 34, 1, 107),
(65, 3, 1, 108),
(66, 33, 1, 109),
(67, 3, 1, 109),
(68, 3, 1, 109),
(69, 3, 1, 109),
(70, 3, 1, 110),
(71, 2, 1, 111),
(72, 2, 1, 111),
(73, 2, 1, 111),
(74, 2, 1, 111),
(75, 2, 1, 111),
(76, 2, 1, 111),
(77, 2, 1, 111),
(78, 2, 1, 111),
(79, 2, 1, 111),
(80, 2, 1, 111),
(81, 2, 1, 112),
(82, 232, 1, 113),
(92, 2, 1, 119),
(93, 2, 1, 120),
(94, 2, 1, 121),
(95, 2, 1, 122),
(96, 2, 1, 123),
(97, 2, 1, 124),
(98, 2, 1, 125),
(99, 2, 1, 126),
(100, 2, 1, 127),
(101, 2, 1, 128),
(102, 2, 1, 129),
(103, 2, 1, 130),
(104, 2, 1, 131),
(105, 2, 1, 132),
(106, 2, 1, 133),
(107, 2, 1, 134),
(108, 2, 1, 135),
(109, 2, 1, 136),
(110, 2, 1, 137),
(111, 2, 1, 138),
(112, 2, 1, 139),
(113, 2, 1, 140),
(114, 2, 1, 141),
(115, 2, 1, 142),
(116, 2, 1, 143),
(117, 2, 1, 144),
(118, 2, 1, 145),
(119, 2, 1, 146),
(120, 2, 1, 147),
(121, 2, 1, 148),
(122, 2, 1, 149),
(123, 12, 1, 150),
(124, 1, 1, 150),
(125, 1, 1, 150),
(126, 1, 1, 150),
(127, 1, 2, 150),
(128, 1, 2, 150),
(129, 1, 2, 150),
(130, 1, 2, 150),
(131, 1, 2, 150),
(132, 1, 2, 150),
(133, 1, 2, 150),
(134, 1, 2, 150),
(135, 1, 2, 150),
(136, 2, 1, 150),
(137, 2, 2, 151),
(138, 2, 2, 151),
(139, 2, 1, 151),
(140, 2, 1, 152),
(141, 2, 1, 153),
(142, 2, 1, 154),
(143, 2, 1, 155),
(144, 2, 1, 156),
(145, 2, 1, 156),
(146, 2, 1, 156),
(147, 2, 1, 156),
(148, 2, 1, 156),
(149, 2, 1, 156),
(150, 2, 1, 156),
(151, 2, 1, 156),
(152, 2, 2, 156),
(153, 2, 2, 156),
(154, 1, 2, 156),
(155, 2, 2, 157),
(156, 2, 1, 157),
(157, 2, 1, 158),
(158, 2, 2, 158),
(159, 2, 2, 158),
(160, 1, 2, 159),
(161, 23, 2, 160),
(162, 23, 2, 160),
(163, 23, 2, 160),
(164, 23, 2, 160),
(165, 23, 2, 160),
(166, 2, 1, 160),
(167, 2, 1, 161),
(168, 2, 2, 162),
(169, 2, 1, 162),
(170, 2, 2, 163),
(171, 2, 2, 163),
(172, 2, 1, 163),
(173, 2, 2, 164),
(174, 2, 1, 165),
(175, 0, 1, 166),
(176, 3, 1, 166),
(177, 2, 2, 166),
(178, 2, 2, 166),
(179, 1, 1, 167),
(180, 2, 1, 168),
(181, 2, 1, 169),
(183, 2, 1, 170),
(184, 3, 1, 171),
(185, 5, 2, 171),
(186, 2, 1, 171),
(188, 2, 1, 171),
(198, 2, 1, 172),
(199, 1, 1, 172),
(200, 2, 1, 173),
(201, 1, 1, 173),
(202, 1, 1, 174),
(203, 1, 1, 174),
(204, 1, 1, 174),
(205, 1, 1, 174),
(206, 1, 1, 174),
(207, 1, 1, 175),
(208, 1, 2, 175),
(209, 1, 1, 175),
(210, 1, 1, 176),
(213, 1, 1, 177);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detallereceta`
--

CREATE TABLE `detallereceta` (
  `idDetalleReceta` int(11) NOT NULL,
  `idMateriaPrima` int(11) NOT NULL,
  `cantidaSa` int(11) DEFAULT NULL,
  `fechaSa` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `idInventario` int(11) NOT NULL,
  `IdReceta` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `detallereceta`
--

INSERT INTO `detallereceta` (`idDetalleReceta`, `idMateriaPrima`, `cantidaSa`, `fechaSa`, `idInventario`, `IdReceta`) VALUES
(2, 4, 25, '12/20/2020', 4, 2),
(3, 6, 25, '14/06/2020', 6, 2),
(4, 1, 25, '14/2020/2020', 1, 2),
(5, 3, 25, '14/06/2020', 3, 2),
(6, 1, 25, '14/06/2020', 1, 1),
(7, 2, 25, '14/2020/2020', 2, 1),
(8, 5, 25, '14/06/2020', 5, 1),
(9, 6, 25, '14/2020/2020', 6, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleado`
--

CREATE TABLE `empleado` (
  `idEmpleado` int(11) NOT NULL,
  `nombreEmp` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `apellido` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `genero` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `idPuesto` int(11) NOT NULL,
  `idUsuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `empleado`
--

INSERT INTO `empleado` (`idEmpleado`, `nombreEmp`, `apellido`, `genero`, `idPuesto`, `idUsuario`) VALUES
(301, 'Eliseo', 'Amaya', 'Hombre', 1, 391272),
(303, 'Rene Alexis', 'Amaya', 'Hombre', 1, 391275),
(304, 'Alexis', 'Lopez Portillo', 'Hombre', 3, 391284),
(305, 'Guillermo ', 'Castillo Lara', 'Hombre', 3, 391285),
(306, 'Eliseo Amilcar', 'Lopez Portillo', 'Hombre', 1, 391286),
(307, 'charly', 'apellido', 'Hombre', 3, 391287),
(308, 'charly', 'Arrazola Portillo', 'Hombre', 1, 391289);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estadopedido`
--

CREATE TABLE `estadopedido` (
  `idEstadoPedido` int(11) NOT NULL,
  `nombreEstado` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `estadopedido`
--

INSERT INTO `estadopedido` (`idEstadoPedido`, `nombreEstado`) VALUES
(1, 'no trabajado'),
(2, 'en proceso'),
(3, 'terminado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura`
--

CREATE TABLE `factura` (
  `idFacturaMP` int(11) NOT NULL,
  `numeroFac` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `Monto` int(11) DEFAULT NULL,
  `Fecha` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `idProveedor` int(11) NOT NULL,
  `idEmpleado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `factura`
--

INSERT INTO `factura` (`idFacturaMP`, `numeroFac`, `Monto`, `Fecha`, `idProveedor`, `idEmpleado`) VALUES
(23232, '12121223', 5000, '25', 391232, 301);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario`
--

CREATE TABLE `inventario` (
  `idInventario` int(11) NOT NULL,
  `idMateriaPrima` int(11) NOT NULL,
  `Existencias` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `inventario`
--

INSERT INTO `inventario` (`idInventario`, `idMateriaPrima`, `Existencias`) VALUES
(1, 1, 400),
(2, 2, 30),
(3, 3, 50),
(4, 4, 97),
(5, 5, 34),
(6, 6, 32);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `materiaprima`
--

CREATE TABLE `materiaprima` (
  `idMateriaPrima` int(11) NOT NULL,
  `NombreMP` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `materiaprima`
--

INSERT INTO `materiaprima` (`idMateriaPrima`, `NombreMP`) VALUES
(1, 'Maiz amarillo'),
(2, 'Maiz Blanco'),
(3, 'Soya'),
(4, 'Harina '),
(5, 'Maicillo'),
(6, 'Trigo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido`
--

CREATE TABLE `pedido` (
  `idPedido` int(11) NOT NULL,
  `fechaPedido` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `idCliente` int(11) NOT NULL,
  `idEstadoPedido` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `pedido`
--

INSERT INTO `pedido` (`idPedido`, `fechaPedido`, `idCliente`, `idEstadoPedido`) VALUES
(1, '20/20/2020', 206, 2),
(2, '08/06/2020', 206, 2),
(3, '08/06/2020', 206, 2),
(4, '08/06/2020', 206, 1),
(5, '08/06/2020', 206, 1),
(6, '08/06/2020', 206, 1),
(7, '08/06/2020', 206, 1),
(8, '08/06/2020', 206, 1),
(9, '08/06/2020', 206, 1),
(10, '08/06/2020', 206, 1),
(11, '08/06/2020', 206, 1),
(12, '08/06/2020', 206, 1),
(13, '08/06/2020', 206, 1),
(14, '08/06/2020', 206, 1),
(15, '08/06/2020', 206, 1),
(16, '08/06/2020', 206, 1),
(17, '09/06/2020', 206, 1),
(18, '09/06/2020', 206, 1),
(19, '09/06/2020', 206, 1),
(20, '09/06/2020', 206, 1),
(21, '09/06/2020', 206, 1),
(22, '09/06/2020', 206, 1),
(23, '09/06/2020', 206, 1),
(24, '09/06/2020', 206, 1),
(25, '09/06/2020', 206, 1),
(26, '09/06/2020', 206, 1),
(27, '09/06/2020', 206, 1),
(28, '09/06/2020', 206, 1),
(29, '09/06/2020', 206, 1),
(30, '09/06/2020', 206, 1),
(31, '09/06/2020', 206, 1),
(32, '09/06/2020', 206, 1),
(33, '09/06/2020', 206, 1),
(34, '09/06/2020', 206, 1),
(35, '09/06/2020', 206, 1),
(36, '09/06/2020', 206, 1),
(37, '09/06/2020', 206, 1),
(38, '09/06/2020', 206, 1),
(39, '09/06/2020', 206, 1),
(40, '09/06/2020', 206, 1),
(41, '09/06/2020', 206, 1),
(42, '09/06/2020', 206, 1),
(43, '09/06/2020', 206, 1),
(44, '09/06/2020', 206, 1),
(45, '09/06/2020', 206, 1),
(46, '09/06/2020', 206, 1),
(47, '09/06/2020', 206, 1),
(48, '09/06/2020', 206, 1),
(49, '09/06/2020', 206, 1),
(50, '09/06/2020', 206, 1),
(51, '09/06/2020', 206, 1),
(52, '09/06/2020', 206, 1),
(53, '09/06/2020', 206, 1),
(54, '09/06/2020', 206, 1),
(55, '09/06/2020', 206, 1),
(56, '09/06/2020', 206, 1),
(57, '09/06/2020', 206, 1),
(58, '09/06/2020', 206, 1),
(59, '09/06/2020', 206, 1),
(60, '09/06/2020', 206, 1),
(61, '09/06/2020', 206, 1),
(62, '09/06/2020', 206, 1),
(63, '09/06/2020', 206, 1),
(64, '09/06/2020', 206, 1),
(65, '09/06/2020', 206, 1),
(66, '09/06/2020', 206, 1),
(67, '09/06/2020', 206, 1),
(68, '09/06/2020', 206, 1),
(69, '09/06/2020', 206, 1),
(70, '09/06/2020', 206, 1),
(71, '09/06/2020', 206, 1),
(72, '09/06/2020', 206, 1),
(73, '11/06/2020', 206, 1),
(74, '11/06/2020', 206, 1),
(75, '11/06/2020', 206, 1),
(76, '11/06/2020', 206, 1),
(77, '11/06/2020', 206, 1),
(78, '11/06/2020', 206, 1),
(79, '11/06/2020', 206, 1),
(80, '11/06/2020', 206, 1),
(81, '11/06/2020', 206, 1),
(82, '11/06/2020', 206, 1),
(83, '11/06/2020', 206, 1),
(84, '11/06/2020', 206, 1),
(85, '11/06/2020', 206, 1),
(86, '11/06/2020', 206, 1),
(87, '11/06/2020', 206, 1),
(88, '11/06/2020', 206, 1),
(89, '12/06/2020', 206, 1),
(90, '12/06/2020', 206, 1),
(91, '12/06/2020', 206, 1),
(92, '12/06/2020', 206, 1),
(93, '12/06/2020', 206, 1),
(94, '12/06/2020', 206, 1),
(95, '12/06/2020', 206, 1),
(96, '12/06/2020', 206, 1),
(97, '12/06/2020', 206, 1),
(98, '12/06/2020', 206, 1),
(99, '12/06/2020', 206, 1),
(100, '12/06/2020', 206, 1),
(101, '12/06/2020', 206, 1),
(102, '12/06/2020', 206, 1),
(103, '12/06/2020', 206, 1),
(104, '12/06/2020', 206, 1),
(105, '12/06/2020', 206, 1),
(106, '12/06/2020', 206, 1),
(107, '12/06/2020', 206, 1),
(108, '12/06/2020', 206, 1),
(109, '12/06/2020', 206, 1),
(110, '12/06/2020', 206, 1),
(111, '12/06/2020', 206, 1),
(112, '12/06/2020', 206, 1),
(113, '12/06/2020', 206, 1),
(114, '12/06/2020', 206, 1),
(115, '12/06/2020', 206, 1),
(116, '12/06/2020', 206, 1),
(117, '12/06/2020', 206, 1),
(118, '12/06/2020', 206, 1),
(119, '12/06/2020', 206, 1),
(120, '12/06/2020', 206, 1),
(121, '12/06/2020', 206, 1),
(122, '12/06/2020', 206, 1),
(123, '12/06/2020', 206, 1),
(124, '12/06/2020', 206, 1),
(125, '12/06/2020', 206, 1),
(126, '12/06/2020', 206, 1),
(127, '12/06/2020', 206, 1),
(128, '12/06/2020', 206, 1),
(129, '12/06/2020', 206, 1),
(130, '12/06/2020', 206, 1),
(131, '12/06/2020', 206, 1),
(132, '12/06/2020', 206, 1),
(133, '12/06/2020', 206, 1),
(134, '12/06/2020', 206, 1),
(135, '12/06/2020', 206, 1),
(136, '12/06/2020', 206, 1),
(137, '12/06/2020', 206, 1),
(138, '12/06/2020', 206, 1),
(139, '12/06/2020', 206, 1),
(140, '12/06/2020', 206, 1),
(141, '12/06/2020', 206, 1),
(142, '12/06/2020', 206, 1),
(143, '12/06/2020', 206, 1),
(144, '12/06/2020', 206, 1),
(145, '12/06/2020', 206, 1),
(146, '12/06/2020', 206, 1),
(147, '12/06/2020', 206, 1),
(148, '12/06/2020', 206, 1),
(149, '12/06/2020', 206, 1),
(150, '12/06/2020', 206, 1),
(151, '12/06/2020', 206, 1),
(152, '13/06/2020', 206, 1),
(153, '13/06/2020', 206, 1),
(154, '13/06/2020', 206, 1),
(155, '13/06/2020', 206, 1),
(156, '13/06/2020', 206, 1),
(157, '13/06/2020', 206, 1),
(158, '13/06/2020', 206, 1),
(159, '13/06/2020', 206, 1),
(160, '13/06/2020', 206, 1),
(161, '13/06/2020', 206, 1),
(162, '13/06/2020', 206, 1),
(163, '13/06/2020', 206, 1),
(164, '13/06/2020', 206, 1),
(165, '13/06/2020', 206, 1),
(166, '13/06/2020', 206, 1),
(167, '13/06/2020', 206, 1),
(168, '13/06/2020', 206, 1),
(169, '14/06/2020', 206, 1),
(170, '14/06/2020', 206, 1),
(171, '14/06/2020', 206, 1),
(172, '14/06/2020', 206, 1),
(173, '14/06/2020', 206, 1),
(174, '14/06/2020', 206, 1),
(175, '14/06/2020', 206, 1),
(176, '14/06/2020', 206, 1),
(177, '14/06/2020', 206, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `produccion`
--

CREATE TABLE `produccion` (
  `idProduccion` int(11) NOT NULL,
  `fechaP` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `estadoP` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `idPedido` int(11) NOT NULL,
  `idEmpleado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `produccion`
--

INSERT INTO `produccion` (`idProduccion`, `fechaP`, `estadoP`, `idPedido`, `idEmpleado`) VALUES
(39, '09/06/2020', 'activo', 4, 301);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedor`
--

CREATE TABLE `proveedor` (
  `idProveedor` int(11) NOT NULL,
  `nombreProveedor` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `contacto` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `NIT` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `correoP` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `telefono` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `proveedor`
--

INSERT INTO `proveedor` (`idProveedor`, `nombreProveedor`, `contacto`, `NIT`, `correoP`, `telefono`) VALUES
(391232, 'Eliseo Amilcar Lopez Portillo', '2', 'erer', 'tordito15lopez@gmail.com', '7011556'),
(391234, 'Eliseo Amilcar Lopez Portillo', '2', 'aa', 'tordito15lopez@gmail.com', '7011556'),
(391235, 'Eliseo Amilcar Lopez Portillo', '2', 'aa', 'tordito15lopez@gmail.com', '7011556');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `puesto`
--

CREATE TABLE `puesto` (
  `idPuesto` int(11) NOT NULL,
  `nombrePuesto` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `puesto`
--

INSERT INTO `puesto` (`idPuesto`, `nombrePuesto`) VALUES
(1, 'Productor'),
(3, 'Gerente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `receta`
--

CREATE TABLE `receta` (
  `idReceta` int(11) NOT NULL,
  `nombreReceta` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `PrecioUnitario` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `receta`
--

INSERT INTO `receta` (`idReceta`, `nombreReceta`, `PrecioUnitario`) VALUES
(1, 'concentrado Pollo', 40),
(2, 'Concentrado Adulto', 50);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `id_Rol` int(11) NOT NULL,
  `nombreRol` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`id_Rol`, `nombreRol`) VALUES
(1, 'Empleado'),
(2, 'Cliente'),
(3, 'Gerente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `salidas`
--

CREATE TABLE `salidas` (
  `idSalida` int(11) NOT NULL,
  `idProduccion` int(11) NOT NULL,
  `NombreCliente` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `fecha` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `montoTotal` double DEFAULT NULL,
  `estadoSalida` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `idUsuario` int(11) NOT NULL,
  `username` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `pass` varchar(1000) COLLATE utf8_spanish_ci DEFAULT NULL,
  `id_Rol` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`idUsuario`, `username`, `pass`, `id_Rol`) VALUES
(391266, 'sindy@gmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', 2),
(391272, 'admin@gmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', 3),
(391275, '2020@gmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', 1),
(391284, 'lara1@gmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', 3),
(391285, 'lara1@gmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', 3),
(391286, 'Rene1@gmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', 1),
(391287, 'chmod777@gmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', 3),
(391288, 'jesy@gmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', 2),
(391289, 'charly@gmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', 1),
(391290, 'j1@gmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', 2),
(391291, 'amanda@gmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', 2),
(391292, 'amanda@gmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', 2);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`idCliente`),
  ADD KEY `fk_cliente_Usuarios1` (`idUsuario`);

--
-- Indices de la tabla `detallecompra`
--
ALTER TABLE `detallecompra`
  ADD PRIMARY KEY (`idDetalleCompra`),
  ADD KEY `fk_detalleCompra_comprasPrima1` (`idFacturaMP`),
  ADD KEY `fk_detalleCompra_materiaPrima1` (`idMateriaPrima`);

--
-- Indices de la tabla `detallepedido`
--
ALTER TABLE `detallepedido`
  ADD PRIMARY KEY (`idDetallePedido`),
  ADD KEY `fk_detallePedido_Pedido1` (`IdPedido`),
  ADD KEY `fk_detallePedido_Receta1` (`idReceta`);

--
-- Indices de la tabla `detallereceta`
--
ALTER TABLE `detallereceta`
  ADD PRIMARY KEY (`idDetalleReceta`),
  ADD KEY `fk_Kardex_Inventario1` (`idInventario`),
  ADD KEY `fk_Kardex_materiaPrima1` (`idMateriaPrima`),
  ADD KEY `fk1` (`IdReceta`);

--
-- Indices de la tabla `empleado`
--
ALTER TABLE `empleado`
  ADD PRIMARY KEY (`idEmpleado`),
  ADD KEY `fk_Empleado_puesto1` (`idPuesto`),
  ADD KEY `fk_empleado_Usuarios1` (`idUsuario`);

--
-- Indices de la tabla `estadopedido`
--
ALTER TABLE `estadopedido`
  ADD PRIMARY KEY (`idEstadoPedido`);

--
-- Indices de la tabla `factura`
--
ALTER TABLE `factura`
  ADD PRIMARY KEY (`idFacturaMP`),
  ADD KEY `fk_Factura_Empleado1` (`idEmpleado`),
  ADD KEY `fk_materiaPrima_Proveedor1` (`idProveedor`);

--
-- Indices de la tabla `inventario`
--
ALTER TABLE `inventario`
  ADD PRIMARY KEY (`idInventario`),
  ADD KEY `fk_Inventario_materiaPrima1` (`idMateriaPrima`);

--
-- Indices de la tabla `materiaprima`
--
ALTER TABLE `materiaprima`
  ADD PRIMARY KEY (`idMateriaPrima`);

--
-- Indices de la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD PRIMARY KEY (`idPedido`),
  ADD KEY `fk_Pedido_estadoPedido1` (`idEstadoPedido`),
  ADD KEY `fk_Pedidos_Cliente1` (`idCliente`);

--
-- Indices de la tabla `produccion`
--
ALTER TABLE `produccion`
  ADD PRIMARY KEY (`idProduccion`),
  ADD KEY `fk_Produccion_Empleado1` (`idEmpleado`),
  ADD KEY `fk_Produccion_Pedido1` (`idPedido`);

--
-- Indices de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  ADD PRIMARY KEY (`idProveedor`);

--
-- Indices de la tabla `puesto`
--
ALTER TABLE `puesto`
  ADD PRIMARY KEY (`idPuesto`);

--
-- Indices de la tabla `receta`
--
ALTER TABLE `receta`
  ADD PRIMARY KEY (`idReceta`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`id_Rol`);

--
-- Indices de la tabla `salidas`
--
ALTER TABLE `salidas`
  ADD PRIMARY KEY (`idSalida`),
  ADD KEY `fk_ventaF_Produccion1` (`idProduccion`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`idUsuario`),
  ADD KEY `fk_Usuarios_rol1` (`id_Rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `idCliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=211;

--
-- AUTO_INCREMENT de la tabla `detallecompra`
--
ALTER TABLE `detallecompra`
  MODIFY `idDetalleCompra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `detallepedido`
--
ALTER TABLE `detallepedido`
  MODIFY `idDetallePedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=214;

--
-- AUTO_INCREMENT de la tabla `detallereceta`
--
ALTER TABLE `detallereceta`
  MODIFY `idDetalleReceta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `empleado`
--
ALTER TABLE `empleado`
  MODIFY `idEmpleado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=309;

--
-- AUTO_INCREMENT de la tabla `estadopedido`
--
ALTER TABLE `estadopedido`
  MODIFY `idEstadoPedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `factura`
--
ALTER TABLE `factura`
  MODIFY `idFacturaMP` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23233;

--
-- AUTO_INCREMENT de la tabla `inventario`
--
ALTER TABLE `inventario`
  MODIFY `idInventario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `materiaprima`
--
ALTER TABLE `materiaprima`
  MODIFY `idMateriaPrima` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `pedido`
--
ALTER TABLE `pedido`
  MODIFY `idPedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=178;

--
-- AUTO_INCREMENT de la tabla `produccion`
--
ALTER TABLE `produccion`
  MODIFY `idProduccion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  MODIFY `idProveedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=391237;

--
-- AUTO_INCREMENT de la tabla `puesto`
--
ALTER TABLE `puesto`
  MODIFY `idPuesto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `receta`
--
ALTER TABLE `receta`
  MODIFY `idReceta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `id_Rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `salidas`
--
ALTER TABLE `salidas`
  MODIFY `idSalida` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `idUsuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=391293;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD CONSTRAINT `fk_cliente_Usuarios1` FOREIGN KEY (`idUsuario`) REFERENCES `usuarios` (`idUsuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `detallecompra`
--
ALTER TABLE `detallecompra`
  ADD CONSTRAINT `fk_detalleCompra_comprasPrima1` FOREIGN KEY (`idFacturaMP`) REFERENCES `factura` (`idFacturaMP`),
  ADD CONSTRAINT `fk_detalleCompra_materiaPrima1` FOREIGN KEY (`idMateriaPrima`) REFERENCES `materiaprima` (`idMateriaPrima`);

--
-- Filtros para la tabla `detallepedido`
--
ALTER TABLE `detallepedido`
  ADD CONSTRAINT `fk_detallePedido_Pedido1` FOREIGN KEY (`IdPedido`) REFERENCES `pedido` (`idPedido`),
  ADD CONSTRAINT `fk_detallePedido_Receta1` FOREIGN KEY (`idReceta`) REFERENCES `receta` (`idReceta`);

--
-- Filtros para la tabla `detallereceta`
--
ALTER TABLE `detallereceta`
  ADD CONSTRAINT `fk1` FOREIGN KEY (`IdReceta`) REFERENCES `receta` (`idReceta`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Kardex_Inventario1` FOREIGN KEY (`idInventario`) REFERENCES `inventario` (`idInventario`),
  ADD CONSTRAINT `fk_Kardex_materiaPrima1` FOREIGN KEY (`idMateriaPrima`) REFERENCES `materiaprima` (`idMateriaPrima`);

--
-- Filtros para la tabla `empleado`
--
ALTER TABLE `empleado`
  ADD CONSTRAINT `fk_Empleado_puesto1` FOREIGN KEY (`idPuesto`) REFERENCES `puesto` (`idPuesto`),
  ADD CONSTRAINT `fk_empleado_Usuarios1` FOREIGN KEY (`idUsuario`) REFERENCES `usuarios` (`idUsuario`);

--
-- Filtros para la tabla `factura`
--
ALTER TABLE `factura`
  ADD CONSTRAINT `fk_Factura_Empleado1` FOREIGN KEY (`idEmpleado`) REFERENCES `empleado` (`idEmpleado`),
  ADD CONSTRAINT `fk_materiaPrima_Proveedor1` FOREIGN KEY (`idProveedor`) REFERENCES `proveedor` (`idProveedor`);

--
-- Filtros para la tabla `inventario`
--
ALTER TABLE `inventario`
  ADD CONSTRAINT `fk_Inventario_materiaPrima1` FOREIGN KEY (`idMateriaPrima`) REFERENCES `materiaprima` (`idMateriaPrima`);

--
-- Filtros para la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD CONSTRAINT `fk_Pedido_estadoPedido1` FOREIGN KEY (`idEstadoPedido`) REFERENCES `estadopedido` (`idEstadoPedido`),
  ADD CONSTRAINT `fk_Pedidos_Cliente1` FOREIGN KEY (`idCliente`) REFERENCES `cliente` (`idCliente`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `produccion`
--
ALTER TABLE `produccion`
  ADD CONSTRAINT `fk_Produccion_Empleado1` FOREIGN KEY (`idEmpleado`) REFERENCES `empleado` (`idEmpleado`),
  ADD CONSTRAINT `fk_Produccion_Pedido1` FOREIGN KEY (`idPedido`) REFERENCES `pedido` (`idPedido`);

--
-- Filtros para la tabla `salidas`
--
ALTER TABLE `salidas`
  ADD CONSTRAINT `fk_ventaF_Produccion1` FOREIGN KEY (`idProduccion`) REFERENCES `produccion` (`idProduccion`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_Usuarios_rol1` FOREIGN KEY (`id_Rol`) REFERENCES `rol` (`id_Rol`) ON DELETE NO ACTION ON UPDATE NO ACTION;
  
  create table pedidoproveedor(
idPedido int primary key auto_increment,
idProveedor int not null,
idEmpleado int not null,
idMateriaPrima int not null,
fecha varchar(20) not null,
cantidadMP int not null,
monto float not null,
precioMP float not null
);
alter table pedidoproveedor add foreign key (idProveedor) references proveedor (idProveedor);
alter table pedidoproveedor add foreign key (idEmpleado) references empleado (idEmpleado);
alter table pedidoproveedor add foreign key (idMateriaPrima) references materiaprima(idMateriaPrima);
insert into pedidoproveedor values (1,391232,301,1,"21/06/2020",5,20,40);
insert into pedidoproveedor values (2,391232,301,2,"21/06/2020",5,20,40);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
select * from detallecompra;
