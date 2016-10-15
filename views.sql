-- phpMyAdmin SQL Dump
-- version 4.4.9
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 15-10-2015 a las 12:19:26
-- Versión del servidor: 5.5.27
-- Versión de PHP: 5.4.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `orveinca`
--

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `factura_comp_prod`
--
CREATE TABLE IF NOT EXISTS `factura_comp_prod` (
`id_prod` mediumint(9)
,`desc_prod` varchar(90)
,`codi_clpr` varchar(4)
,`id_mode` mediumint(9)
,`id_imag` mediumint(9)
,`desc_clpr` varchar(40)
,`desc_mode` varchar(30)
,`id_marc` mediumint(9)
,`desc_marc` varchar(30)
,`id_faco` mediumint(9)
,`exad_colo` varchar(17)
,`cost_comp` double(20,3)
,`cant_faco` int(11)
,`cant_reci` int(11)
,`nume_orde` mediumint(9)
,`exad` varchar(17)
,`desc_colo` varchar(30)
,`totalbs` double(20,3)
,`id_tama` mediumint(9)
,`medi_tama` varchar(7)
,`codi_umed` varchar(4)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `info_clientes`
--
CREATE TABLE IF NOT EXISTS `info_clientes` (
`idet_clie` varchar(12)
,`codi_tide` varchar(4)
,`nomb_clie` varchar(90)
,`emai_clie` varchar(90)
,`dire_clie` varchar(90)
,`id_parr` mediumint(9)
,`ci_cont` mediumint(9)
,`ci_empl` varchar(10)
,`desc_parr` varchar(100)
,`id_muni` mediumint(9)
,`desc_muni` varchar(100)
,`id_esta` mediumint(9)
,`desc_esta` varchar(100)
,`nom1_empl` varchar(30)
,`nom2_empl` varchar(30)
,`ape1_empl` varchar(30)
,`ape2_empl` varchar(30)
,`emai_empl` varchar(90)
,`nom1_cont` varchar(30)
,`nom2_cont` varchar(30)
,`ape1_cont` varchar(30)
,`ape2_cont` varchar(30)
,`emai_cont` varchar(90)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `info_facturas_c`
--
CREATE TABLE IF NOT EXISTS `info_facturas_c` (
`nume_orde` mediumint(9)
,`nume_fact` mediumint(9)
,`fech_fact` date
,`idet_prov` varchar(12)
,`codi_tide` varchar(4)
,`nomb_prov` varchar(90)
,`emai_prov` varchar(90)
,`dire_prov` varchar(90)
,`id_parr` mediumint(9)
,`ci_cont` mediumint(9)
,`total_bs` double(20,3)
,`desc_esta_reci` varchar(9)
,`esta_reci` decimal(23,0)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `info_facturas_c_plus_pagado`
--
CREATE TABLE IF NOT EXISTS `info_facturas_c_plus_pagado` (
`nume_orde` mediumint(9)
,`nume_fact` mediumint(9)
,`fech_fact` date
,`idet_prov` varchar(12)
,`codi_tide` varchar(4)
,`nomb_prov` varchar(90)
,`emai_prov` varchar(90)
,`dire_prov` varchar(90)
,`id_parr` mediumint(9)
,`ci_cont` mediumint(9)
,`total_bs` double(20,3)
,`esta_reci` decimal(23,0)
,`bsf_pago` double(20,3)
,`fect_pago` date
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `info_nota_entrega`
--
CREATE TABLE IF NOT EXISTS `info_nota_entrega` (
`idet_clie` varchar(12)
,`codi_tide` varchar(4)
,`nomb_clie` varchar(90)
,`emai_clie` varchar(90)
,`dire_clie` varchar(90)
,`id_parr` mediumint(9)
,`ci_cont` mediumint(9)
,`desc_parr` varchar(100)
,`id_muni` mediumint(9)
,`desc_muni` varchar(100)
,`id_esta` mediumint(9)
,`desc_esta` varchar(100)
,`nom1_cont` varchar(30)
,`nom2_cont` varchar(30)
,`ape1_cont` varchar(30)
,`ape2_cont` varchar(30)
,`emai_cont` varchar(90)
,`nume_nent` mediumint(9)
,`nume_fact` mediumint(9)
,`fech_nent` date
,`nom1_empl` varchar(30)
,`nom2_empl` varchar(30)
,`ape1_empl` varchar(30)
,`ape2_empl` varchar(30)
,`emai_empl` varchar(90)
,`total_bs` double(20,3)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `info_orden_compra`
--
CREATE TABLE IF NOT EXISTS `info_orden_compra` (
`nume_orde` mediumint(9)
,`fech_orde` date
,`esta_orde` varchar(1)
,`idet_prov` varchar(12)
,`codi_tide` varchar(4)
,`nomb_prov` varchar(90)
,`emai_prov` varchar(90)
,`dire_prov` varchar(90)
,`id_parr` mediumint(9)
,`ci_cont` mediumint(9)
,`desc_parr` varchar(100)
,`id_muni` mediumint(9)
,`desc_muni` varchar(100)
,`id_esta` mediumint(9)
,`desc_esta` varchar(100)
,`nom1_cont` varchar(30)
,`nom2_cont` varchar(30)
,`ape1_cont` varchar(30)
,`ape2_cont` varchar(30)
,`emai_cont` varchar(90)
,`total_bs` double(20,3)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `info_proveedores`
--
CREATE TABLE IF NOT EXISTS `info_proveedores` (
`idet_prov` varchar(12)
,`codi_tide` varchar(4)
,`nomb_prov` varchar(90)
,`emai_prov` varchar(90)
,`dire_prov` varchar(90)
,`id_parr` mediumint(9)
,`ci_cont` mediumint(9)
,`desc_parr` varchar(100)
,`id_muni` mediumint(9)
,`desc_muni` varchar(100)
,`id_esta` mediumint(9)
,`desc_esta` varchar(100)
,`nom1_cont` varchar(30)
,`nom2_cont` varchar(30)
,`ape1_cont` varchar(30)
,`ape2_cont` varchar(30)
,`emai_cont` varchar(90)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `inventario`
--
CREATE TABLE IF NOT EXISTS `inventario` (
`id_prod` mediumint(9)
,`desc_prod` varchar(90)
,`codi_clpr` varchar(4)
,`id_mode` mediumint(9)
,`id_imag` mediumint(9)
,`desc_clpr` varchar(40)
,`desc_mode` varchar(30)
,`id_marc` mediumint(9)
,`desc_marc` varchar(30)
,`exad_colo` varchar(17)
,`exad` varchar(17)
,`desc_colo` varchar(30)
,`id_tama` mediumint(9)
,`medi_tama` varchar(7)
,`codi_umed` varchar(4)
,`existencia` decimal(33,0)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `inventario_p1`
--
CREATE TABLE IF NOT EXISTS `inventario_p1` (
`id_prod` mediumint(9)
,`desc_prod` varchar(90)
,`codi_clpr` varchar(4)
,`id_mode` mediumint(9)
,`id_imag` mediumint(9)
,`desc_clpr` varchar(40)
,`desc_mode` varchar(30)
,`id_marc` mediumint(9)
,`desc_marc` varchar(30)
,`id_faco` mediumint(9)
,`exad_colo` varchar(17)
,`cost_comp` double(20,3)
,`cant_faco` int(11)
,`cant_reci` int(11)
,`nume_orde` mediumint(9)
,`exad` varchar(17)
,`desc_colo` varchar(30)
,`totalbs` double(20,3)
,`id_tama` mediumint(9)
,`medi_tama` varchar(7)
,`codi_umed` varchar(4)
,`sum_cant_reci` decimal(32,0)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `inventario_p2`
--
CREATE TABLE IF NOT EXISTS `inventario_p2` (
`id_prod` mediumint(9)
,`id_tama` mediumint(9)
,`exad_colo` varchar(17)
,`prec_vent` double(20,3)
,`cant_nent` int(11)
,`nume_nent` mediumint(9)
,`cant_vend` decimal(32,0)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `nota_entrega_prod`
--
CREATE TABLE IF NOT EXISTS `nota_entrega_prod` (
`exad_colo` varchar(17)
,`prec_vent` double(20,3)
,`cant_nent` int(11)
,`nume_nent` mediumint(9)
,`id_prod` mediumint(9)
,`desc_prod` varchar(90)
,`codi_clpr` varchar(4)
,`id_mode` mediumint(9)
,`id_imag` mediumint(9)
,`desc_clpr` varchar(40)
,`desc_mode` varchar(30)
,`id_marc` mediumint(9)
,`desc_marc` varchar(30)
,`id_tama` mediumint(9)
,`medi_tama` varchar(7)
,`codi_umed` varchar(4)
,`exad` varchar(17)
,`desc_colo` varchar(30)
,`totalbs` double(20,3)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `orden_compra_prod`
--
CREATE TABLE IF NOT EXISTS `orden_compra_prod` (
`id_prod` mediumint(9)
,`desc_prod` varchar(90)
,`codi_clpr` varchar(4)
,`id_mode` mediumint(9)
,`id_imag` mediumint(9)
,`desc_clpr` varchar(40)
,`desc_mode` varchar(30)
,`id_marc` mediumint(9)
,`desc_marc` varchar(30)
,`id_tama` mediumint(9)
,`medi_tama` varchar(7)
,`codi_umed` varchar(4)
,`id_orpr` mediumint(9)
,`cost_orde` double(20,3)
,`cant_orde` int(11)
,`nume_orde` mediumint(9)
,`exad_colo` varchar(17)
,`exad` varchar(17)
,`desc_colo` varchar(30)
,`totalbs` double(20,3)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `productos`
--
CREATE TABLE IF NOT EXISTS `productos` (
`id_prod` mediumint(9)
,`desc_prod` varchar(90)
,`codi_clpr` varchar(4)
,`id_mode` mediumint(9)
,`id_imag` mediumint(9)
,`desc_clpr` varchar(40)
,`desc_mode` varchar(30)
,`id_marc` mediumint(9)
,`desc_marc` varchar(30)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `productos_talla_precio`
--
CREATE TABLE IF NOT EXISTS `productos_talla_precio` (
`id_prod` mediumint(9)
,`id_tama1` mediumint(9)
,`id_tama2` mediumint(9)
,`cost_tama` double(20,3)
,`fech_tama` datetime
,`id_tmpd` mediumint(9)
,`codi_umed` varchar(4)
,`desc_umed` varchar(20)
,`medi_tama1` varchar(7)
,`medi_tama2` varchar(7)
);

-- --------------------------------------------------------

--
-- Estructura para la vista `factura_comp_prod`
--
DROP TABLE IF EXISTS `factura_comp_prod`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `factura_comp_prod` AS select `productos`.`id_prod` AS `id_prod`,`productos`.`desc_prod` AS `desc_prod`,`productos`.`codi_clpr` AS `codi_clpr`,`productos`.`id_mode` AS `id_mode`,`productos`.`id_imag` AS `id_imag`,`productos`.`desc_clpr` AS `desc_clpr`,`productos`.`desc_mode` AS `desc_mode`,`productos`.`id_marc` AS `id_marc`,`productos`.`desc_marc` AS `desc_marc`,`faco_prod`.`id_faco` AS `id_faco`,`faco_prod`.`exad_colo` AS `exad_colo`,`faco_prod`.`cost_comp` AS `cost_comp`,`faco_prod`.`cant_faco` AS `cant_faco`,`faco_prod`.`cant_reci` AS `cant_reci`,`faco_prod`.`nume_orde` AS `nume_orde`,`colores`.`exad` AS `exad`,`colores`.`desc_colo` AS `desc_colo`,(`faco_prod`.`cost_comp` * `faco_prod`.`cant_faco`) AS `totalbs`,`tamanos`.`id_tama` AS `id_tama`,`tamanos`.`medi_tama` AS `medi_tama`,`tamanos`.`codi_umed` AS `codi_umed` from (((`faco_prod` left join `productos` on((`faco_prod`.`id_prod` = `productos`.`id_prod`))) left join `tamanos` on((`faco_prod`.`id_tama` = `tamanos`.`id_tama`))) left join `colores` on((`colores`.`exad` = `faco_prod`.`exad_colo`)));

-- --------------------------------------------------------

--
-- Estructura para la vista `info_clientes`
--
DROP TABLE IF EXISTS `info_clientes`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `info_clientes` AS select `clientes`.`idet_clie` AS `idet_clie`,`clientes`.`codi_tide` AS `codi_tide`,`clientes`.`nomb_clie` AS `nomb_clie`,`clientes`.`emai_clie` AS `emai_clie`,`clientes`.`dire_clie` AS `dire_clie`,`clientes`.`id_parr` AS `id_parr`,`clientes`.`ci_cont` AS `ci_cont`,`clientes`.`ci_empl` AS `ci_empl`,`parroquias`.`desc_parr` AS `desc_parr`,`municipios`.`id_muni` AS `id_muni`,`municipios`.`desc_muni` AS `desc_muni`,`municipios`.`id_esta` AS `id_esta`,`estados`.`desc_esta` AS `desc_esta`,`empleados`.`nom1_empl` AS `nom1_empl`,`empleados`.`nom2_empl` AS `nom2_empl`,`empleados`.`ape1_empl` AS `ape1_empl`,`empleados`.`ape2_empl` AS `ape2_empl`,`empleados`.`emai_empl` AS `emai_empl`,`contactos`.`nom1_cont` AS `nom1_cont`,`contactos`.`nom2_cont` AS `nom2_cont`,`contactos`.`ape1_cont` AS `ape1_cont`,`contactos`.`ape2_cont` AS `ape2_cont`,`contactos`.`emai_cont` AS `emai_cont` from (((((`clientes` left join `parroquias` on((`clientes`.`id_parr` = `parroquias`.`id_parr`))) left join `municipios` on((`parroquias`.`id_muni` = `municipios`.`id_muni`))) left join `estados` on((`municipios`.`id_esta` = `estados`.`id_esta`))) left join `empleados` on((`clientes`.`ci_empl` = `empleados`.`ci_empl`))) left join `contactos` on((`clientes`.`ci_cont` = `contactos`.`ci_cont`)));

-- --------------------------------------------------------

--
-- Estructura para la vista `info_facturas_c`
--
DROP TABLE IF EXISTS `info_facturas_c`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `info_facturas_c` AS select `fact_comp`.`nume_orde` AS `nume_orde`,`fact_comp`.`nume_fact` AS `nume_fact`,`fact_comp`.`fech_fact` AS `fech_fact`,`provedores`.`idet_prov` AS `idet_prov`,`provedores`.`codi_tide` AS `codi_tide`,`provedores`.`nomb_prov` AS `nomb_prov`,`provedores`.`emai_prov` AS `emai_prov`,`provedores`.`dire_prov` AS `dire_prov`,`provedores`.`id_parr` AS `id_parr`,`provedores`.`ci_cont` AS `ci_cont`,sum((`faco_prod`.`cost_comp` * `faco_prod`.`cant_faco`)) AS `total_bs`,if((sum((`faco_prod`.`cant_faco` <> `faco_prod`.`cant_reci`)) = 0),'RECIVIDO','PENDIENTE') AS `desc_esta_reci`,sum((`faco_prod`.`cant_faco` <> `faco_prod`.`cant_reci`)) AS `esta_reci` from (((`fact_comp` left join `orden_comp` on((`fact_comp`.`nume_orde` = `orden_comp`.`nume_orde`))) left join `provedores` on((`orden_comp`.`idet_prov` = `provedores`.`idet_prov`))) left join `faco_prod` on((`fact_comp`.`nume_orde` = `faco_prod`.`nume_orde`))) group by `fact_comp`.`nume_orde`;

-- --------------------------------------------------------

--
-- Estructura para la vista `info_facturas_c_plus_pagado`
--
DROP TABLE IF EXISTS `info_facturas_c_plus_pagado`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `info_facturas_c_plus_pagado` AS select `info_facturas_c`.`nume_orde` AS `nume_orde`,`info_facturas_c`.`nume_fact` AS `nume_fact`,`info_facturas_c`.`fech_fact` AS `fech_fact`,`info_facturas_c`.`idet_prov` AS `idet_prov`,`info_facturas_c`.`codi_tide` AS `codi_tide`,`info_facturas_c`.`nomb_prov` AS `nomb_prov`,`info_facturas_c`.`emai_prov` AS `emai_prov`,`info_facturas_c`.`dire_prov` AS `dire_prov`,`info_facturas_c`.`id_parr` AS `id_parr`,`info_facturas_c`.`ci_cont` AS `ci_cont`,`info_facturas_c`.`total_bs` AS `total_bs`,`info_facturas_c`.`esta_reci` AS `esta_reci`,sum(`pagos_fact`.`bsf_pago`) AS `bsf_pago`,max(`pagos_fact`.`fech_pago`) AS `fect_pago` from (`pagos_fact` left join `info_facturas_c` on(((`pagos_fact`.`id_fact` = `info_facturas_c`.`nume_orde`) and (`pagos_fact`.`tipo_fact` = 'C')))) group by `info_facturas_c`.`nume_orde`;

-- --------------------------------------------------------

--
-- Estructura para la vista `info_nota_entrega`
--
DROP TABLE IF EXISTS `info_nota_entrega`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `info_nota_entrega` AS select `clientes`.`idet_clie` AS `idet_clie`,`clientes`.`codi_tide` AS `codi_tide`,`clientes`.`nomb_clie` AS `nomb_clie`,`clientes`.`emai_clie` AS `emai_clie`,`clientes`.`dire_clie` AS `dire_clie`,`clientes`.`id_parr` AS `id_parr`,`clientes`.`ci_cont` AS `ci_cont`,`parroquias`.`desc_parr` AS `desc_parr`,`municipios`.`id_muni` AS `id_muni`,`municipios`.`desc_muni` AS `desc_muni`,`municipios`.`id_esta` AS `id_esta`,`estados`.`desc_esta` AS `desc_esta`,`contactos`.`nom1_cont` AS `nom1_cont`,`contactos`.`nom2_cont` AS `nom2_cont`,`contactos`.`ape1_cont` AS `ape1_cont`,`contactos`.`ape2_cont` AS `ape2_cont`,`contactos`.`emai_cont` AS `emai_cont`,`nota_entrg`.`nume_nent` AS `nume_nent`,`nota_entrg`.`nume_fact` AS `nume_fact`,`nota_entrg`.`fech_nent` AS `fech_nent`,`empleados`.`nom1_empl` AS `nom1_empl`,`empleados`.`nom2_empl` AS `nom2_empl`,`empleados`.`ape1_empl` AS `ape1_empl`,`empleados`.`ape2_empl` AS `ape2_empl`,`empleados`.`emai_empl` AS `emai_empl`,sum((`nent_prod`.`prec_vent` * `nent_prod`.`cant_nent`)) AS `total_bs` from (((((((`nota_entrg` left join `nent_prod` on((`nota_entrg`.`nume_nent` = `nent_prod`.`nume_nent`))) left join `clientes` on((`nota_entrg`.`idet_clie` = `clientes`.`idet_clie`))) left join `parroquias` on((`clientes`.`id_parr` = `parroquias`.`id_parr`))) left join `municipios` on((`parroquias`.`id_muni` = `municipios`.`id_muni`))) left join `estados` on((`municipios`.`id_esta` = `estados`.`id_esta`))) left join `contactos` on((`clientes`.`ci_cont` = `contactos`.`ci_cont`))) left join `empleados` on((`empleados`.`ci_empl` = `nota_entrg`.`ci_empl`))) group by `nota_entrg`.`nume_nent`;

-- --------------------------------------------------------

--
-- Estructura para la vista `info_orden_compra`
--
DROP TABLE IF EXISTS `info_orden_compra`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `info_orden_compra` AS select `orden_comp`.`nume_orde` AS `nume_orde`,`orden_comp`.`fech_orde` AS `fech_orde`,`orden_comp`.`esta_orde` AS `esta_orde`,`info_proveedores`.`idet_prov` AS `idet_prov`,`info_proveedores`.`codi_tide` AS `codi_tide`,`info_proveedores`.`nomb_prov` AS `nomb_prov`,`info_proveedores`.`emai_prov` AS `emai_prov`,`info_proveedores`.`dire_prov` AS `dire_prov`,`info_proveedores`.`id_parr` AS `id_parr`,`info_proveedores`.`ci_cont` AS `ci_cont`,`info_proveedores`.`desc_parr` AS `desc_parr`,`info_proveedores`.`id_muni` AS `id_muni`,`info_proveedores`.`desc_muni` AS `desc_muni`,`info_proveedores`.`id_esta` AS `id_esta`,`info_proveedores`.`desc_esta` AS `desc_esta`,`info_proveedores`.`nom1_cont` AS `nom1_cont`,`info_proveedores`.`nom2_cont` AS `nom2_cont`,`info_proveedores`.`ape1_cont` AS `ape1_cont`,`info_proveedores`.`ape2_cont` AS `ape2_cont`,`info_proveedores`.`emai_cont` AS `emai_cont`,sum((`orde_prod`.`cost_orde` * `orde_prod`.`cant_orde`)) AS `total_bs` from ((`orden_comp` left join `info_proveedores` on((`orden_comp`.`idet_prov` = `info_proveedores`.`idet_prov`))) left join `orde_prod` on((`orden_comp`.`nume_orde` = `orde_prod`.`nume_orde`)));

-- --------------------------------------------------------

--
-- Estructura para la vista `info_proveedores`
--
DROP TABLE IF EXISTS `info_proveedores`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `info_proveedores` AS select `provedores`.`idet_prov` AS `idet_prov`,`provedores`.`codi_tide` AS `codi_tide`,`provedores`.`nomb_prov` AS `nomb_prov`,`provedores`.`emai_prov` AS `emai_prov`,`provedores`.`dire_prov` AS `dire_prov`,`provedores`.`id_parr` AS `id_parr`,`provedores`.`ci_cont` AS `ci_cont`,`parroquias`.`desc_parr` AS `desc_parr`,`municipios`.`id_muni` AS `id_muni`,`municipios`.`desc_muni` AS `desc_muni`,`municipios`.`id_esta` AS `id_esta`,`estados`.`desc_esta` AS `desc_esta`,`contactos`.`nom1_cont` AS `nom1_cont`,`contactos`.`nom2_cont` AS `nom2_cont`,`contactos`.`ape1_cont` AS `ape1_cont`,`contactos`.`ape2_cont` AS `ape2_cont`,`contactos`.`emai_cont` AS `emai_cont` from ((((`provedores` left join `parroquias` on((`provedores`.`id_parr` = `parroquias`.`id_parr`))) left join `municipios` on((`parroquias`.`id_muni` = `municipios`.`id_muni`))) left join `estados` on((`municipios`.`id_esta` = `estados`.`id_esta`))) left join `contactos` on((`provedores`.`ci_cont` = `contactos`.`ci_cont`)));

-- --------------------------------------------------------

--
-- Estructura para la vista `inventario`
--
DROP TABLE IF EXISTS `inventario`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `inventario` AS select `inventario_p1`.`id_prod` AS `id_prod`,`inventario_p1`.`desc_prod` AS `desc_prod`,`inventario_p1`.`codi_clpr` AS `codi_clpr`,`inventario_p1`.`id_mode` AS `id_mode`,`inventario_p1`.`id_imag` AS `id_imag`,`inventario_p1`.`desc_clpr` AS `desc_clpr`,`inventario_p1`.`desc_mode` AS `desc_mode`,`inventario_p1`.`id_marc` AS `id_marc`,`inventario_p1`.`desc_marc` AS `desc_marc`,`inventario_p1`.`exad_colo` AS `exad_colo`,`inventario_p1`.`exad` AS `exad`,`inventario_p1`.`desc_colo` AS `desc_colo`,`inventario_p1`.`id_tama` AS `id_tama`,`inventario_p1`.`medi_tama` AS `medi_tama`,`inventario_p1`.`codi_umed` AS `codi_umed`,(if(isnull(`inventario_p1`.`sum_cant_reci`),0,`inventario_p1`.`sum_cant_reci`) - if(isnull(`inventario_p2`.`cant_vend`),0,`inventario_p2`.`cant_vend`)) AS `existencia` from (`inventario_p1` left join `inventario_p2` on(((if(isnull(`inventario_p1`.`id_prod`),'NULL',`inventario_p1`.`id_prod`) = if(isnull(`inventario_p2`.`id_prod`),'NULL',`inventario_p2`.`id_prod`)) and (if(isnull(`inventario_p1`.`id_tama`),'NULL',`inventario_p1`.`id_tama`) = if(isnull(`inventario_p2`.`id_tama`),'NULL',`inventario_p2`.`id_tama`)) and (if(isnull(`inventario_p1`.`exad_colo`),'NULL',`inventario_p1`.`exad_colo`) = if(isnull(`inventario_p2`.`exad_colo`),'NULL',`inventario_p2`.`exad_colo`))))) having (`existencia` > 0);

-- --------------------------------------------------------

--
-- Estructura para la vista `inventario_p1`
--
DROP TABLE IF EXISTS `inventario_p1`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `inventario_p1` AS select `factura_comp_prod`.`id_prod` AS `id_prod`,`factura_comp_prod`.`desc_prod` AS `desc_prod`,`factura_comp_prod`.`codi_clpr` AS `codi_clpr`,`factura_comp_prod`.`id_mode` AS `id_mode`,`factura_comp_prod`.`id_imag` AS `id_imag`,`factura_comp_prod`.`desc_clpr` AS `desc_clpr`,`factura_comp_prod`.`desc_mode` AS `desc_mode`,`factura_comp_prod`.`id_marc` AS `id_marc`,`factura_comp_prod`.`desc_marc` AS `desc_marc`,`factura_comp_prod`.`id_faco` AS `id_faco`,`factura_comp_prod`.`exad_colo` AS `exad_colo`,`factura_comp_prod`.`cost_comp` AS `cost_comp`,`factura_comp_prod`.`cant_faco` AS `cant_faco`,`factura_comp_prod`.`cant_reci` AS `cant_reci`,`factura_comp_prod`.`nume_orde` AS `nume_orde`,`factura_comp_prod`.`exad` AS `exad`,`factura_comp_prod`.`desc_colo` AS `desc_colo`,`factura_comp_prod`.`totalbs` AS `totalbs`,`factura_comp_prod`.`id_tama` AS `id_tama`,`factura_comp_prod`.`medi_tama` AS `medi_tama`,`factura_comp_prod`.`codi_umed` AS `codi_umed`,sum(`factura_comp_prod`.`cant_reci`) AS `sum_cant_reci` from `factura_comp_prod` group by `factura_comp_prod`.`id_prod`,`factura_comp_prod`.`exad_colo`,`factura_comp_prod`.`id_tama`;

-- --------------------------------------------------------

--
-- Estructura para la vista `inventario_p2`
--
DROP TABLE IF EXISTS `inventario_p2`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `inventario_p2` AS select `nent_prod`.`id_prod` AS `id_prod`,`nent_prod`.`id_tama` AS `id_tama`,`nent_prod`.`exad_colo` AS `exad_colo`,`nent_prod`.`prec_vent` AS `prec_vent`,`nent_prod`.`cant_nent` AS `cant_nent`,`nent_prod`.`nume_nent` AS `nume_nent`,sum(`nent_prod`.`cant_nent`) AS `cant_vend` from `nent_prod` group by `nent_prod`.`id_prod`,`nent_prod`.`exad_colo`,`nent_prod`.`id_tama`;

-- --------------------------------------------------------

--
-- Estructura para la vista `nota_entrega_prod`
--
DROP TABLE IF EXISTS `nota_entrega_prod`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `nota_entrega_prod` AS select `nent_prod`.`exad_colo` AS `exad_colo`,`nent_prod`.`prec_vent` AS `prec_vent`,`nent_prod`.`cant_nent` AS `cant_nent`,`nent_prod`.`nume_nent` AS `nume_nent`,`productos`.`id_prod` AS `id_prod`,`productos`.`desc_prod` AS `desc_prod`,`productos`.`codi_clpr` AS `codi_clpr`,`productos`.`id_mode` AS `id_mode`,`productos`.`id_imag` AS `id_imag`,`productos`.`desc_clpr` AS `desc_clpr`,`productos`.`desc_mode` AS `desc_mode`,`productos`.`id_marc` AS `id_marc`,`productos`.`desc_marc` AS `desc_marc`,`tamanos`.`id_tama` AS `id_tama`,`tamanos`.`medi_tama` AS `medi_tama`,`tamanos`.`codi_umed` AS `codi_umed`,`colores`.`exad` AS `exad`,`colores`.`desc_colo` AS `desc_colo`,(`nent_prod`.`prec_vent` * `nent_prod`.`cant_nent`) AS `totalbs` from (((`nent_prod` join `productos` on((`nent_prod`.`id_prod` = `productos`.`id_prod`))) left join `tamanos` on((`nent_prod`.`id_tama` = `tamanos`.`id_tama`))) left join `colores` on((`colores`.`exad` = `nent_prod`.`exad_colo`)));

-- --------------------------------------------------------

--
-- Estructura para la vista `orden_compra_prod`
--
DROP TABLE IF EXISTS `orden_compra_prod`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `orden_compra_prod` AS select `productos`.`id_prod` AS `id_prod`,`productos`.`desc_prod` AS `desc_prod`,`productos`.`codi_clpr` AS `codi_clpr`,`productos`.`id_mode` AS `id_mode`,`productos`.`id_imag` AS `id_imag`,`productos`.`desc_clpr` AS `desc_clpr`,`productos`.`desc_mode` AS `desc_mode`,`productos`.`id_marc` AS `id_marc`,`productos`.`desc_marc` AS `desc_marc`,`tamanos`.`id_tama` AS `id_tama`,`tamanos`.`medi_tama` AS `medi_tama`,`tamanos`.`codi_umed` AS `codi_umed`,`orde_prod`.`id_orpr` AS `id_orpr`,`orde_prod`.`cost_orde` AS `cost_orde`,`orde_prod`.`cant_orde` AS `cant_orde`,`orde_prod`.`nume_orde` AS `nume_orde`,`orde_prod`.`exad_colo` AS `exad_colo`,`colores`.`exad` AS `exad`,`colores`.`desc_colo` AS `desc_colo`,(`orde_prod`.`cost_orde` * `orde_prod`.`cant_orde`) AS `totalbs` from (((`orde_prod` left join `productos` on((`orde_prod`.`id_prod` = `productos`.`id_prod`))) join `tamanos` on((`orde_prod`.`id_tama` = `tamanos`.`id_tama`))) left join `colores` on((`colores`.`exad` = `orde_prod`.`exad_colo`)));

-- --------------------------------------------------------

--
-- Estructura para la vista `productos`
--
DROP TABLE IF EXISTS `productos`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `productos` AS select `producto`.`id_prod` AS `id_prod`,`producto`.`desc_prod` AS `desc_prod`,`producto`.`codi_clpr` AS `codi_clpr`,`producto`.`id_mode` AS `id_mode`,`producto`.`id_imag` AS `id_imag`,`clas_prod`.`desc_clpr` AS `desc_clpr`,`modelos`.`desc_mode` AS `desc_mode`,`marcas`.`id_marc` AS `id_marc`,`marcas`.`desc_marc` AS `desc_marc` from (((`producto` left join `clas_prod` on((`producto`.`codi_clpr` = `clas_prod`.`codi_clpr`))) left join `modelos` on((`producto`.`id_mode` = `modelos`.`id_mode`))) left join `marcas` on((`modelos`.`id_marc` = `marcas`.`id_marc`)));

-- --------------------------------------------------------

--
-- Estructura para la vista `productos_talla_precio`
--
DROP TABLE IF EXISTS `productos_talla_precio`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `productos_talla_precio` AS select `tama_prod`.`id_prod` AS `id_prod`,`tama_prod`.`id_tama1` AS `id_tama1`,`tama_prod`.`id_tama2` AS `id_tama2`,`tama_prod`.`cost_tama` AS `cost_tama`,`tama_prod`.`fech_tama` AS `fech_tama`,`tama_prod`.`id_tmpd` AS `id_tmpd`,`u_medida`.`codi_umed` AS `codi_umed`,`u_medida`.`desc_umed` AS `desc_umed`,`t1`.`medi_tama` AS `medi_tama1`,`t2`.`medi_tama` AS `medi_tama2` from (((`tama_prod` join `tamanos` `t1` on((`tama_prod`.`id_tama1` = `t1`.`id_tama`))) join `tamanos` `t2` on((`tama_prod`.`id_tama2` = `t2`.`id_tama`))) join `u_medida` on((`u_medida`.`codi_umed` = `t2`.`codi_umed`)));

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
