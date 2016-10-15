-- phpMyAdmin SQL Dump
-- version 4.4.9
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 25-06-2015 a las 22:30:52
-- Versión del servidor: 5.5.27
-- Versión de PHP: 5.4.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `orveinca_2_1_2`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `config`
--

CREATE TABLE IF NOT EXISTS `config` (
  `desc_conf` varchar(10) NOT NULL,
  `valo_conf` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `config`
--

INSERT INTO `config` (`desc_conf`, `valo_conf`) VALUES
('cest_tike', '75'),
('iva', '0.12'),
('l_p_h', '56.22'),
('precio1', '0.5'),
('precio2', '0.7'),
('precio3', '0.8'),
('sueldo_min', '224.89'),
('s_o_s', '203.09'),
('s_p_f', '0');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nomina`
--

CREATE TABLE IF NOT EXISTS `nomina` (
  `codi_nomi` mediumint(9) NOT NULL,
  `ci_empl` varchar(10) NOT NULL,
  `suel_diar` float(10,3) NOT NULL,
  `comicion` float(10,3) NOT NULL,
  `dias_labo` int(11) NOT NULL,
  `s_p_f` float(10,3) NOT NULL,
  `l_p_h` float(10,3) NOT NULL,
  `s_o_s` float(10,3) NOT NULL,
  `cest_tike` float(10,3) NOT NULL,
  `fech_nomi` date NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `nomina`
--

INSERT INTO `nomina` (`codi_nomi`, `ci_empl`, `suel_diar`, `comicion`, `dias_labo`, `s_p_f`, `l_p_h`, `s_o_s`, `cest_tike`, `fech_nomi`) VALUES
(10, '17.864.456', 224.899, 0.000, 20, 0.000, 56.220, 203.090, 75.000, '2015-05-25'),
(11, '20.039.168', 224.899, 0.000, 20, 0.000, 56.220, 203.090, 75.000, '2015-05-25'),
(12, '20.705.108', 224.899, 0.000, 20, 0.000, 56.220, 203.090, 75.000, '2015-05-25');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `config`
--
ALTER TABLE `config`
  ADD PRIMARY KEY (`desc_conf`);

--
-- Indices de la tabla `nomina`
--
ALTER TABLE `nomina`
  ADD PRIMARY KEY (`codi_nomi`),
  ADD KEY `ci_empl` (`ci_empl`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `nomina`
--
ALTER TABLE `nomina`
  MODIFY `codi_nomi` mediumint(9) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `nomina`
--
ALTER TABLE `nomina`
  ADD CONSTRAINT `nomina_ibfk_1` FOREIGN KEY (`ci_empl`) REFERENCES `empleados` (`ci_empl`) ON DELETE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
