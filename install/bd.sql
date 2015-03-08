-- phpMyAdmin SQL Dump
-- version 4.0.6
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 08-03-2015 a las 21:55:48
-- Versión del servidor: 5.5.40-0ubuntu0.14.04.1
-- Versión de PHP: 5.5.9-1ubuntu4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `2daw1415_sara01`
--
CREATE DATABASE IF NOT EXISTS `2daw1415_sara01` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `2daw1415_sara01`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

DROP TABLE IF EXISTS `categoria`;
CREATE TABLE IF NOT EXISTS `categoria` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(45) NOT NULL,
  `nombre` varchar(45) DEFAULT NULL,
  `descripcion` text,
  `anuncio` text,
  `oculto` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=54 ;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`id`, `codigo`, `nombre`, `descripcion`, `anuncio`, `oculto`) VALUES
(1, '012DD', 'Disco Duro', 'Todo tipo de disco duro: tanto interno como externo, SSD y mecánico, diferentes velocidades...', '', 0),
(2, '123UO', 'Redes', 'Todo lo necesario para montar una red: cables, dispositivos, infraestructura...', '', 0),
(3, '234T', 'Teclados', 'Periféricos de entrada de diversos materiales y formas.', '', 0),
(4, '345S', 'Software', 'Aplicaciones informáticas de todo tipo para cualquier plataforma.', '', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `destacado`
--

DROP TABLE IF EXISTS `destacado`;
CREATE TABLE IF NOT EXISTS `destacado` (
  `producto` int(11) NOT NULL,
  `fecha_inicio` datetime DEFAULT NULL,
  `fecha_fin` datetime DEFAULT NULL,
  PRIMARY KEY (`producto`),
  KEY `fk_destacado_productos1_idx` (`producto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `destacado`
--

INSERT INTO `destacado` (`producto`, `fecha_inicio`, `fecha_fin`) VALUES
(33, '2015-03-01 00:00:00', '2015-03-31 00:00:00'),
(34, '2015-03-01 00:00:00', '2015-03-31 00:00:00'),
(45, '2015-03-01 00:00:00', '2015-03-31 00:00:00'),
(46, '2015-03-01 00:00:00', '2015-03-31 00:00:00'),
(55, '2015-03-01 00:00:00', '2015-03-31 00:00:00'),
(56, '2015-03-01 00:00:00', '2015-03-31 00:00:00'),
(85, '2015-03-01 00:00:00', '2015-03-31 00:00:00'),
(86, '2015-03-01 00:00:00', '2015-03-31 00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `linea_pedido`
--

DROP TABLE IF EXISTS `linea_pedido`;
CREATE TABLE IF NOT EXISTS `linea_pedido` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `producto` int(11) NOT NULL,
  `pedido` int(11) NOT NULL,
  `cantidad` varchar(45) DEFAULT NULL,
  `precio` decimal(6,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_productos_has_pedido_pedido1_idx` (`pedido`),
  KEY `fk_productos_has_pedido_productos1_idx` (`producto`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Volcado de datos para la tabla `linea_pedido`
--

INSERT INTO `linea_pedido` (`id`, `producto`, `pedido`, `cantidad`, `precio`) VALUES
(2, 33, 2, '3', 59.95),
(3, 34, 2, '3', 28.95);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido`
--

DROP TABLE IF EXISTS `pedido`;
CREATE TABLE IF NOT EXISTS `pedido` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `estado` enum('Procesado','Pendiente','Recibido','Devuelto','Cancelado') DEFAULT 'Pendiente',
  `cantidad` int(11) DEFAULT NULL,
  `fecha_pedido` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_entrega` timestamp NULL DEFAULT NULL,
  `usuario` int(11) NOT NULL,
  `nombre` varchar(80) DEFAULT NULL,
  `apellidos` varchar(80) DEFAULT NULL,
  `dni` char(9) DEFAULT NULL,
  `direccion` varchar(100) DEFAULT NULL,
  `cp` char(5) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_pedido_usuario1_idx` (`usuario`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `pedido`
--

INSERT INTO `pedido` (`id`, `estado`, `cantidad`, `fecha_pedido`, `fecha_entrega`, `usuario`, `nombre`, `apellidos`, `dni`, `direccion`, `cp`) VALUES
(2, 'Pendiente', NULL, '2015-03-08 07:34:00', NULL, 4, 'sarita', 'alamillo', '49113766A', 'San Sebastian ', '21600');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

DROP TABLE IF EXISTS `producto`;
CREATE TABLE IF NOT EXISTS `producto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(15) NOT NULL,
  `categoria` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `precio` decimal(6,2) DEFAULT NULL,
  `descuento` decimal(5,2) DEFAULT NULL,
  `imagen` varchar(256) DEFAULT NULL,
  `iva` decimal(5,2) DEFAULT NULL,
  `descripcion` text,
  `anuncio` text,
  `stock` int(11) DEFAULT NULL,
  `oculto` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_productos_categoria_idx` (`categoria`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=294 ;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`id`, `codigo`, `categoria`, `nombre`, `precio`, `descuento`, `imagen`, `iva`, `descripcion`, `anuncio`, `stock`, `oculto`) VALUES
(33, '202ADSL', 2, 'Asus DSL-N16U Router Wireless 300Mpbs', 59.95, 10.00, '202ADSL.jpg', 21.00, 'Especificaciones: \nEstándar de red: IEEE 802.11b, IEEE 802.11g, IEEE 802.11n, IEEE 802.3, IEEE 802.3u, IEEE 802.11i, IEEE 802.11e. \nSegmento de producto: N300 trabajo en red completo; 300Mbps. \nVelocidad de datos Wi-Fi: \n802.11b : 1, 2, 5.5, 11Mbps. \n802.11g : 6,9,12,18,24,36,48,54Mbps. \n802.11n: hasta 300Mbps. \nvelocidad de datos xDSL: \nANSI T1.413i2. \nITU G.922.1(G.dmt).ITU G.922.2 (G.lite). \nITU G.922.3 ADSL2(G.dmt.bis). \nITU G.922.4(G.lite.bis). \nITU G.922.5(G.dmt.bisplus). \nCompatible con ADSL/ADSL2/ADSL2+ multimode AnnexA,AnnexB. \nADSL 2/2+ 24Mbps downstream. \nSupport Annex A/B/I/J/L/M. \nAntena:3 x antena Externa dBi. \nLED Indicador: Power, ADSL, Internet, LAN x 4, 2.4 GHz Wi-Fi. \nBotón: Botón WPS, Botón reset, Interruptor de encendido. \nGarantía: 2 años.', NULL, 24, 0),
(34, '228TPL', 2, 'TP-LINK TL-WA850RE Repetidor 11n eXtended Range', 28.95, 50.00, '204TPL.jpg', 21.00, 'Especificaciones: \nEstándar de red: IEEE 802.11b, IEEE 802.11g, IEEE 802.11n, IEEE 802.3, IEEE 802.3u, IEEE 802.11i, IEEE 802.11e. \nSegmento de producto: N300 trabajo en red completo; 300Mbps. \nVelocidad de datos Wi-Fi: \n802.11b : 1, 2, 5.5, 11Mbps. \n802.11g : 6,9,12,18,24,36,48,54Mbps. \n802.11n: hasta 300Mbps. \nvelocidad de datos xDSL: \nANSI T1.413i2. \nITU G.922.1(G.dmt).ITU G.922.2 (G.lite). \nITU G.922.3 ADSL2(G.dmt.bis). \nITU G.922.4(G.lite.bis). \nITU G.922.5(G.dmt.bisplus). \nCompatible con ADSL/ADSL2/ADSL2+ multimode AnnexA,AnnexB. \nADSL 2/2+ 24Mbps downstream. \nSupport Annex A/B/I/J/L/M. \nAntena:3 x antena Externa dBi. \nLED Indicador: Power, ADSL, Internet, LAN x 4, 2.4 GHz Wi-Fi. \nBotón: Botón WPS, Botón reset, Interruptor de encendido. \nGarantía: 2 años.', NULL, 44, 0),
(35, '222TPL', 2, 'TP-LINK TL-PA411KIT AV500 Nano Powerline Starter Kit', 28.95, 70.00, '222TPL.jpg', 21.00, 'Kit de Inicio con Mini Adaptador Powerline AV500 \n \nConforme con el estándar HomePlug AV, tasas de transferencia de datos a alta velocidad de hasta 500Mbps \nSin necesidad de nuevos cables, Instalación Plug and Play sencilla, no requiere configuración \nDiseño super-reducido con nuevo formato, se integra perfectamente con los enchufes de la pared. \nModo patentado de ahorro de energía que reduce automáticamente el consumo hasta en un 85% \nGarantía: 2 años.', NULL, 5, 0),
(36, '014TPL', 2, 'TP-LINK TL-SF1005D Switch 5 puertos 10/100', 10.75, 0.00, '014TPL.jpg', 21.00, 'Especificaciones: \nSwitch de 5 puertos para red local 10/100. \nSupports IEEE 802.3x flow control for Full Duplex mode and backpressure for half-duplex mode \nSupports MAC address auto-learning and auto-aging \nLED indicators for monitoring power, link, activity \nPlastic case, desktop or wall-mounting design \nExternal Power Adapter supply \nGarantía: 2 años.', NULL, 1, 0),
(37, '203TPL', 2, 'TP-LINK TL-WN851ND 300Mbps 11n Wireless PCI', 17.75, 0.00, '203TPL.jpg', 21.00, 'Especificaciones: \nVelocidad inalámbrica N de hasta 300 Mbps es ideal para la difusión de vídeo, juegos en línea y las llamadas por Internet \n2T2R MIMO ™ ofrece mayor rendimiento en la gama convencional versus 1T1R \nEs compatible con la función de QSS, cumpliendo con WPS para la seguridad se preocupe inalámbrica \nEs compatible con 64/128 WEP, WPA / WPA2/WPA-PSK/WPA2-PSK (TKIP / AES), Es compatible con IEEE 802.1X \nCompatible con Windows 2000, Windows XP 32/64, 32/64 bits Windows Vista, Windows 7 32/64 bits \nEs compatible con ad-hoc y modo infraestructura \nCompatible con Sony PSP X-Link para el juego alegre en línea para Windows XP \nPaquete de utilidades que permite una instalación rápida y sin complicaciones \nPerfectamente compatible con los productos 802.11n/b/g \nGarantía: 2 años.', NULL, 3, 0),
(38, '205TPL', 2, 'Cable de red UTP Categoría 6', 12.75, 0.00, '205TPL.jpg', 21.00, 'Especificaciones: \nCategoría del cable de red cat. 6e \nLongitud del cable en metros 2 m \nTipo de Cable UTP \nGarantía: 2 años.', NULL, 5, 0),
(39, '202LO', 2, 'LevelOne GNC-0112 PCI-E Gigabit Ethernet 10/100/1000', 14.75, 0.00, '202LO.jpg', 21.00, 'Especificaciones: \nTarjeta de red Gigabit de alta velocidad compatible con PCI Express v1.1 \nAdmite PCI Express de un carril que proporciona una velocidad de transmisión de enlace de hasta 2,5 Gbps \nAdmite colas de prioridad IEEE 802.1P Nivel 2 para mejorar el flujo del tráfico \nLa VLAN IEEE 802.1Q permite segmentación de la red para mejorar el rendimiento y la seguridad \nEl control de flujo IEEE 802.3x evita la pérdida de paquetes para mejorar la fiabilidad de la transmisión de datos \nDetección automática de velocidad de red \nIndicador LED para supervisar el enlace y la actividad \nSoporta Windows 2000/XP/2003/Vista/7/8 \nGarantía: 2 años.', NULL, 2, 0),
(40, '208TPL', 2, 'TP-Link RE200 Repetidor Extensor de Cobertura WiFi AC750', 47.00, 0.00, '208TPL.jpg', 21.00, 'Especificaciones: \nEl modo Extensor de Cobertura amplifica la señal inalámbrica perfectamente hasta zonas donde antes no llegaba o que son difíciles de cablear \nSólo necesita pulsar el botón Range Extender para ampliar fácilmente la cobertura inalámbrica \nCompatible con dispositivos inalámbricos 802.11 b/g/n y 802.11ac \nVelocidad en Banda Dual de hasta 750 Mbps \nSu diseño de tamaño en miniatura para montaje en pared facilita la instalación y permite un traslado flexible \nEl puerto Ethernet permite funcionar al Extensor como adaptador inalámbrico para conectar dispositivos cableados \nGarantía: 2 años.', NULL, 8, 0),
(41, '201TPL', 2, 'TP-LINK TL-ANT2408CL Antena Interior Omnidireccional 8dBi', 9.95, 0.00, '201TPL.jpg', 21.00, 'Especificaciones: \nCompatible con los estándares 802.11n/b/g (2,4 GHz) \nGanancia de señal de 8 dBi \nConector hembra RP-SMA \nGarantía: 2 años.', NULL, 1, 0),
(42, '224TPL', 2, 'TP-Link AC750 Router Gigabit de Banda Dual Inalámbrico', 56.00, 0.00, '224TPL.jpg', 21.00, 'Especificaciones: \nConexiones de banda dual a 733 Mbps para un entorno sin latencia \nCon velocidades inalámbricas de 433Mbps sobre la cristalina banda de 5GHz y 300Mbps sobre la banda de 2,4 GHz, el Archer C2 le ofrece la flexibilidad de dos redes dedicadas y asegura un rendimiento inalámbrico increíble. Las tareas simples como enviar correos electrónicos o navegar por la web pueden ser manejadas por la banda de 2,4 GHz, mientras que las tareas intensivas de ancho de banda como los juegos en línea o Streaming de vídeo en HD pueden ser procesadas por la banda de 5GHz - todo al mismo tiempo. \nSeñal omnidireccional estable y cobertura superior \nGarantía: 2 años.', NULL, 4, 0),
(43, '202AR', 2, 'Asus DSL-N16U Router Wireless 300Mpbs', 59.95, 0.00, '202AR.jpg', 21.00, 'Especificaciones: \nEstándar de red: IEEE 802.11b, IEEE 802.11g, IEEE 802.11n, IEEE 802.3, IEEE 802.3u, IEEE 802.11i, IEEE 802.11e. \nSegmento de producto: N300 trabajo en red completo; 300Mbps. \nVelocidad de datos Wi-Fi: \n802.11b : 1, 2, 5.5, 11Mbps. \n802.11g : 6,9,12,18,24,36,48,54Mbps. \n802.11n: hasta 300Mbps. \nvelocidad de datos xDSL: \nANSI T1.413i2. \nITU G.922.1(G.dmt).ITU G.922.2 (G.lite). \nITU G.922.3 ADSL2(G.dmt.bis). \nITU G.922.4(G.lite.bis). \nITU G.922.5(G.dmt.bisplus). \nCompatible con ADSL/ADSL2/ADSL2+ multimode AnnexA,AnnexB. \nADSL 2/2+ 24Mbps downstream. \nSupport Annex A/B/I/J/L/M. \nAntena:3 x antena Externa dBi. \nLED Indicador: Power, ADSL, Internet, LAN x 4, 2.4 GHz Wi-Fi. \nBotón: Botón WPS, Botón reset, Interruptor de encendido. \nGarantía: 2 años.', NULL, 2, 0),
(44, '204TPL', 2, 'TP-LINK TL-WA850RE Repetidor 11n eXtended Range', 28.95, 0.00, '204TPL.jpg', 21.00, 'El modo de Extensor de Rango mejora la señal inalámbrica a áreas anteriormente inalcanzables o difíciles de cablear a la perfección \nEl Diseño en Miniatura y montado en la pared lo hace fácil de usar y de mover de una manera más flexible \nExpande fácilmente la cobertura inalámbrica con sólo presionar el botón de Extensor de Rango \nEl puerto Ethernet permite que el Extensor funcione como un adaptador inalámbrico para conectar los dispositivos cableados \nGarantía: 2 años.', NULL, 4, 0),
(45, '04S', 1, 'Seagate Barracuda 7200.14 1TB SATA3', 54.95, 0.00, '04S.jpg', 21.00, 'Especificaciones\nNº de modelo: ST1000DM003\nInterfaz SATA 6Gb/s\nCaché 64MB\nCapacidad 1TB\nDensidad de área (promedio) 625Gb/in2\nSectores garantizados 1,953,525,168\nVelocidad de giro (rpm) 7200 rpm\nLatencia promedio 4.16ms\nTiempo de búsqueda de lectura aleatoria <8.5ms\nTiempo de búsqueda de escritura aleatoria <9.5ms\nPorcentaje de errores anuales <1%\nCorriente inicial máxima, CC 2.0\nDimensiones Altura 20.17mm x Anchura 101.6mm x Largo 146.99mm\nPeso (típico) 400g\nGarantía: 2 años.', NULL, 0, 0),
(46, '02WD', 1, 'WD Caviar Green 1TB SATA3 64MB', 59.95, 0.00, '02WD.jpg', 21.00, 'Especificaciones físicas\nCapacidad formateada 1,000,204 MB\nCapacidad 1 TB\nInterfaz SATA 6 Gb/s\nSectores de usuario por disco 1,953,525,168\n5400 rpm\nGarantía: 2 años.', NULL, 1, 0),
(47, '03WD', 1, 'WD NAS Red 2TB SATA3', 99.95, 0.00, '03WD.jpg', 21.00, 'Especificaciones:\nInterface SATA 6 Gb/s\nRendimiento\nRotational Speed IntelliPower *\nBuffer Size 64 MB\nVelocidad de transferencia de datos\nBuffer To Host (Serial ATA) 6 Gb/s (Max)\nEspecificaciones Físicas\nCapacidad formateado 2.000.398 MB\nCapacidad 2 TB\nNumero de sectores 3.907.029.168\nDimensiones147x101.6x26.1mm\nPeso 0.6 kg\nGarantía: 2 años.', NULL, 3, 0),
(48, '01WD', 1, 'WD Caviar Blue 500GB SATA3', 54.95, 0.00, '01WD.jpg', 21.00, 'Especificaciones:\nBuffer Size: 16 MB\nCapacidad formateada 500.107 MB \nCapacidad 500GB \nInterfaz SATA 6Gb/s \nVelocidad de rotación: 7.200 RPM \nDimensiones físicas \nAltura 25.4 mm (Máx) \nLongitud 147 mm (Max) \nAncho 101.6 mm \nPeso 0.60 kg \nCompatible con placas con SATA2 \nGarantía: 2 años.', NULL, 1, 0),
(49, '02S', 1, 'Seagate Barracuda 7200.14 1TB SATA3', 55.95, 0.00, '02S.jpg', 21.00, 'Especificaciones: \nNº de modelo: ST1000DM003 \nInterfaz SATA 6Gb/s \nCaché 64MB \nCapacidad 1TB \nDensidad de área (promedio) 625Gb/in2 \nSectores garantizados 1,953,525,168 \nVelocidad de giro (rpm) 7200 rpm \nLatencia promedio 4.16ms \nTiempo de búsqueda de lectura aleatoria <8.5ms \nTiempo de búsqueda de escritura aleatoria <9.5ms \nPorcentaje de errores anuales <1% \nCorriente inicial máxima, CC 2.0 \nDimensiones Altura 20.17mm x Anchura 101.6mm x Largo 146.99mm \nPeso (típico) 400g \nGarantía: 2 años.', NULL, 2, 0),
(50, '03T', 1, 'Toshiba DT01ACA300 3TB SATA3', 104.00, 0.00, '03T.jpg', 21.00, 'Especificaciones: \nCapacidad con formateo: 3 .000 GByte. \nFactor de forma: 3,5 Pulgadas. \nTipo de interfaz: Serial ATA. \nInterfaces estándar admitidas: ATA-8, Serial ATA 3,0. \nS.M.A.R.T.: es compatible con el conjunto de comandos SMART. \nBytes/sector (Host): 512. \nBytes/sector (disco): 4096 kByte. \nTiempo de búsqueda de una sola pista (lectura): 0,5 ms. \nTiempo de búsqueda de una sola pista (escritura): 0,6 ms. \nVelocidades de transferencia SATA (host): máx. 6,0 Gbit/s. \nVelocidad de giro: 7.200 rpm. \nTamaño de búfer: 64 MByte. \nGarantía: 2 años.', NULL, 3, 0),
(51, '11WD', 1, 'WD Purple 2TB SATA3', 92.00, 0.00, '11WD.jpg', 21.00, 'Especificaciones: \nInterfaz SATA 6 Gb / s \nEspecificaciones de rendimiento \nVelocidad de rotación IntelliPower * \nTamaño de búfer 64 MB \nCarga / descarga de ciclos 300000 mínima \nTasas de transferencia \nBuffer To Host ( Serial ATA ) 6 Gb / s (Max ) \nEspecificaciones físicas \nCapacidad de 2 TB \nFactor de forma de 3,5 pulgadas \nGarantía: 2 años.', NULL, 1, 0),
(52, '06WD', 1, 'WD Caviar Green 3.5" 500GB SATA3 64MB', 53.00, 0.00, '06WD.jpg', 21.00, 'Especificaciones: \nTipo Disco duro - interno \nFactor de forma 3.5" x 1/3H \nDimensiones (Ancho x Profundidad x Altura) 10.2 cm x 14.7 cm x 2.6 cm \nPeso 0.73 kg \nCapacidad 500GB \nTipo de interfaz SATA 6 Gb/s with 22-pin SATA connector \nVelocidad de transferencia de datos 300 MBps \nVelocidad del eje 5400rpm \nTamaño de búfer 64 MB \nGarantía: 2 años.', NULL, 6, 0),
(53, '22WD', 1, 'WD Caviar Black 500GB SATA3 7200rpm', 71.00, 0.00, '22WD.jpg', 21.00, 'Especificaciones: \nCapacidad de disco duro 500 GB \nTamaño de disco duro* 89 mm (3.5 ") \nVelocidad de rotación de disco duro 7200 RPM \nInterfaz del disco duro* Serial ATA III \nUnidad, tamaño de búfer* 64 MB \nCiclo comenzar/detener 300000 \nTecnología inteligente \nVelocidad de transferencia de datos 6 Gbit/s \nGarantía: 2 años.', NULL, 2, 0),
(54, '11S', 1, 'Seagate Constellation ES.3 4TB', 283.00, 0.00, '11S.jpg', 21.00, 'Especificaciones: \nInterfaz del disco duro* Serial Attached SCSI (SAS) \nCapacidad de disco duro 4000 GB \nTamaño de disco duro* 89 mm (3.5 ") \nVelocidad de rotación de disco duro 7200 RPM \nUnidad, tamaño de búfer* 128 MB \nPromedio de latencia 4.16 ms \nBytes por sector 512 \nVelocidad de transferencia de datos 6 Gbit/s \nTiempo medio entre fallos 1400000 h \nGarantía: 2 años.', NULL, 1, 0),
(55, '302LWT', 3, 'Logitech Wireless Touch Keyboard K400', 26.95, 0.00, '302LWT.jpg', 21.00, 'Especificaciones: \nLogitech Wireless Touch Keyboard K400 \nPara un cómodo control inalámbrico del portátil, incluso al conectarlo al televisor. \nMando a distancia \nPuede controlar el portátil cómodamente desde el sofá gracias al alcance inalámbrico de 10 metros.* \nPotencia táctil \nUn gran touchpad integrado con navegación multitoque permite señalar y desplazarse para abrirse paso por Internet. \nPlug and Play \nOlvídese de cables, software y complicaciones. Sólo tiene que conectar el minúsculo receptor inalámbrico Logitech Unifying a un puerto USB para empezar a disfrutar. \nGarantía: 2 años.', NULL, 1, 0),
(56, '301LWC', 3, 'Logitech Wireless Combo MK330', 29.95, 0.00, '301LWC.jpg', 21.00, 'Especificaciones: \nBotones de acceso rápido a multimedia \nSáltese una canción. Haga una pausa en un vídeo. Suba el volumen. Once botones de acceso rápido a multimedia para controlar al instante reproducción, pausa, retroceso, avance, volumen y silencio. \nDiseño cómodo y atractivo \nEl diseño atractivo y plano del teclado, junto con las teclas silenciosas, ofrece una escritura más cómoda que los teclados de portátiles y netbooks. \nRatón inalámbrico portátil \nEl atractivo ratón se lleva fácilmente con el ordenador. Además, el compartimento para almacenamiento incluido permite guardar fácilmente el nano receptor. \nTecnología inalámbrica avanzada de 2,4 GHz \nPara trabajar o jugar desde más lugares (como un cómodo sofá): la conexión inalámbrica de largo alcance elimina casi por completo retrasos, interrupciones e interferencias y ofrece un alcance de hasta 10 metros \nGarantía: 2 años.', NULL, 9, 0),
(57, '302LKL', 3, 'Logitech K310 Lavable', 24.95, 0.00, '302LKL.jpg', 21.00, 'Especificaciones: \nFácil de limpiar \nDiseñado para estar siempre como nuevo. Se le puede quitar el polvo o darle un remojón*: este teclado lavable con orificios de drenaje se limpia y se seca fácilmente \nLo resiste todo \nLos caracteres, impresos en las teclas a láser y con recubrimiento ultravioleta, no se desgastan con el lavado. \nFuncional y agradable a la vista \nGarantía: 2 años.', NULL, 2, 0),
(58, '301LWCM', 3, 'Logitech Wireless Combo MK520', 39.95, 0.00, '301LWCM.jpg', 21.00, 'Especificaciones: \nFácil de limpiar \nDiseñado para estar siempre como nuevo. Se le puede quitar el polvo o darle un remojón*: este teclado lavable con orificios de drenaje se limpia y se seca fácilmente \nLo resiste todo \nLos caracteres, impresos en las teclas a láser y con recubrimiento ultravioleta, no se desgastan con el lavado. \nFuncional y agradable a la vista \nGarantía: 2 años.', NULL, 1, 0),
(59, '301LWD', 3, 'Logitech Wireless Desktop MK710', 85.00, 0.00, '301LWD.jpg', 21.00, 'Especificaciones: \nWireless Desktop MK710 ... donde comodidad y productividad van de la mano gracias a teclas cóncavas, un reposamanos blando y un ratón con desplazamiento hiperrápido. \nBien equipado \nCon tres años de duración de las pilas del teclado y ratón en esta combinación, hasta puede que olvide que las usa.* \nSumérjase en la comodidad total \nLogitech® Incurve keys™ y el reposamanos blando harán que este teclado ultraplano parezca diseñado especialmente para usted. \nPequeño, pero muy útil \nLogitech® Unifying es un minúsculo receptor inalámbrico que puede permanecer conectado al ordenador portátil y permite conectar dispositivos cuando se necesitan. \nGarantía: 2 años.', NULL, 1, 0),
(60, '302LKK', 3, 'Logitech K290 Keyboard USB', 28.00, 0.00, '302LKK.jpg', 21.00, 'Especificaciones: \nReposamanos integrado \nTeclas planas \nTeclas F de acceso fácil \nDiseño resistente \nConexión USB Plug and Play \nGarantía: 2 años.', NULL, 2, 0),
(61, '223LKK', 3, 'Logitech K290 Keyboard USB', 16.95, 0.00, '223LKK.jpg', 21.00, 'Especificaciones: \nTe presentamos el Set Free de B-Move, un maravilloso kit de teclado y ratón wireless. Teclado inalámbrico compacto ultrafino y multimedia fabricado con los mejores materiales junto al nuevo ratón inalámbrico. La más alta tecnología en el mínimo espacio y con un estilo elegante y ultra-fino. \nGarantía: 2 años.', NULL, 2, 0),
(62, '302TMG', 3, 'Tacens Mars Gaming MK2', 23.00, 0.00, '302TMG.jpg', 21.00, 'Especificaciones: \nMáxima personalización Con el nuevo MK2 de Mars Gaming podrás jugar al límite configurando el teclado según tus necesidades. Gracias a su impresionante software de interfaz de usuario, que te permite una personalización avanzada, perfiles de usuario y la programación completa de todo el teclado, conseguirás un rendimiento extremo en tus sesiones gaming. \nTecnología profesional Gaming con 1000 Hz frecuencia de sondeo y la capacidad anti-ghosting. \nImpresionante Software de Interfaz de Usuario para Teclas Macro (con grabación Macro). \nPersonalización avanzada, perfiles de usuario y sistema de programación completa de todo el teclado. \nPulsación de teclas perfecta optimizada para Gaming: altura de teclas optimizada para gaming, pulsadores especialmente seleccionados por su presión y respuesta para un rendimiento extremo. \n20 teclas multimedia para el control total del sistema. \nDiseñado para durar y rendir gracias a su cable trenzado de doble nylon y el conector USB bañado en oro de 18 quilates. \nCompatible con: (Win XP / VISTA / WIN 7 32/64Bit / WIN 8 32Bit/64Bit). \nGarantía: 2 años.', NULL, 2, 0),
(63, '301GRG', 3, 'Genesis R11 Gaming Keyboard', 9.95, 0.00, '301GRG.jpg', 21.00, 'Especificaciones: \nGenesis R11, especialmente diseñado para gamers por su diseño ergonómico y una estética agresiva urbana: pura adrenalina gaming. \nFabricado con materiales ABS para una mayor durabilidad de sus componentes. \n8 teclas con tacto de goma intercambiables especiales para gaming \nCapa protectora que garantiza la durabilidad de la grabación de las teclas \nGarantía: 2 años.', NULL, 1, 0),
(64, '302LKU', 3, 'Logitech K290 Keyboard USB', 149.00, 0.00, '302LKU.jpg', 21.00, 'Especificaciones: \nContactos Razer™ con fuerza de accionamiento de 50 g. \nVida útil: 60 millones de pulsaciones. \nRetroiluminación personalizable Chroma con 16,8 millones de opciones de color. \nPreparado para Razer Synapse. \nCombinaciones de 10 teclas con protección contra interferencias ("anti-ghosting"). \nTeclas totalmente programables con grabación simultánea de macros. \n5 teclas macro adicionales dedicadas. \nOpción de modo juego. \nConectores de salida de audio/entrada de micro. \nCon puertos USB adicionales. \nUltrapolling de 1000 Hz. \nCable de fibra trenzada. \nTamaño aproximado: 475 mm (Ancho) x 171 mm (Alto) x 39 mm (Profundidad). \nPeso aproximado: 1500 g. \nGarantía: 2 años.', NULL, 2, 0),
(65, '031', 1, 'Seagate 1TB SATA HDD', 52.90, 0.00, '031.png', 21.00, 'Capacidad: 1 TB \nms/caché/rpm: 8,5 ms/64 MB/7.200 rpm \nPrecio por GB: € 0,05 \nGarantía: 2 años.', NULL, 2, 0),
(66, '032', 1, 'Toshiba 3.5" 3TB SATA', 96.90, 0.00, '032.png', 21.00, 'Capacidad: 3 TB \nms/caché/rpm: -/32 MB/5.940 rpm \nPrecio por GB: € 0,03 \nGarantía: 2 años.', NULL, 1, 0),
(67, '033', 1, 'Seagate NAS 2TB', 152.90, 0.00, '033.png', 21.00, 'Capacidad: 2 TB \nms/caché/rpm: 4,16 ms/128 MB/7.200 rpm \nPrecio por GB: € 0,08 \nGarantía: 2 años.', NULL, 1, 0),
(68, '034', 1, 'Seagate 4TB HDD SATA', 225.90, 0.00, '034.png', 21.00, 'Capacidad: 4 TB \nms/caché/rpm: 8,5 ms/64 MB/5.900 rpm \nPrecio por GB: € 0,04 \nGarantía: 2 años.', NULL, 1, 0),
(69, '035', 1, 'Seagate Enterprise Capacity 3.5 HDD, 6TB', 250.90, 0.00, '035.png', 21.00, 'Capacidad: 6 TB \nms/caché/rpm: 4,16 ms/128 MB/7.200 rpm \nPrecio por GB: € 0,08 \nGarantía: 2 años.', NULL, 2, 0),
(70, '036', 1, 'Toshiba 3.5" 2TB SATA, 6TB', 76.90, 0.00, '036.png', 21.00, 'Capacidad: 2 TB \nms/caché/rpm: -/32 MB/5.700 rpm \nPrecio por GB: € 0,04 \nGarantía: 2 años.', NULL, 3, 0),
(71, '037', 1, 'Toshiba MG03ACA 4TB, 6TB', 399.00, 0.00, '037.png', 21.00, 'Capacidad: 4 TB \nms/caché/rpm: -/64 MB/7.200 rpm \nPrecio por GB: € 0,08 \nGarantía: 2 años.', NULL, 1, 0),
(72, '038', 1, 'Western Digital 1TB Se, 6TB', 81.90, 0.00, '038.png', 21.00, 'Capacidad: 1 TB \nms/caché/rpm: -/128 MB/7.200 rpm \nPrecio por GB: € 0,08 \nGarantía: 2 años.', NULL, 4, 0),
(73, '039', 1, 'Western Digital 1TB VelociRaptor, 6TB', 227.90, 0.00, '039.png', 21.00, 'Capacidad: 1 TB \nms/caché/rpm: 4,2 ms/64 MB/10.000 rpm \nPrecio por GB: € 0,23 \nGarantía: 2 años.', NULL, 2, 0),
(74, '040', 1, 'Western Digital 2TB Se, 6TB', 137.90, 0.00, '040.png', 21.00, 'Capacidad: 2 TB \nms/caché/rpm: -/64 MB/7.200 rpm \nPrecio por GB: € 0,07 \nGarantía: 2 años.', NULL, 1, 0),
(75, '041', 2, 'Apple AirPort Express Base Station', 84.90, 0.00, '041.png', 21.00, 'Tipo de dispositivo: Router \nLAN: 10/100 MBit/s, SNMP \nGarantía: 2 años.', NULL, 2, 0),
(76, '042', 2, 'Apple AirPort Express Base Station', 172.90, 0.00, '042.png', 21.00, 'Tipo de dispositivo: Router \nLAN: 10/100/1000 MBit/s \nGarantía: 2 años.', NULL, 1, 0),
(77, '043', 2, 'Linksys EA6700', 159.90, 0.00, '043.png', 21.00, 'Tipo de dispositivo: Router \nLAN: 10/100/1000 MBit/s \nGarantía: 2 años.', NULL, 2, 0),
(78, '044', 2, 'Linksys E900', 24.99, 0.00, '044.png', 21.00, 'Tipo de dispositivo: Router \nLAN: 10/100 MBit/s \nGarantía: 2 años.', NULL, 1, 0),
(79, '045', 2, 'Linksys EA6100', 74.90, 0.00, '045.png', 21.00, 'Tipo de dispositivo: Router \nLAN: 10/100 MBit/s \nGarantía: 2 años.', NULL, 1, 0),
(80, '046', 2, 'ASUS EA-N66', 69.90, 0.00, '046.png', 21.00, 'Tipo de dispositivo: Router \nLAN: 10/100 MBit/s \nGarantía: 2 años.', NULL, 1, 0),
(81, '047', 2, 'TP-LINK TL-WR710N', 25.90, 0.00, '047.png', 21.00, 'Tipo de dispositivo: Router \nLAN: 10/100 MBit/s \nGarantía: 2 años.', NULL, 5, 0),
(82, '048', 2, 'ASUS RT-N12 C1', 24.90, 0.00, '048.png', 21.00, 'Tipo de dispositivo: Router \nLAN: 10/100 MBit/s \nGarantía: 2 años.', NULL, 2, 0),
(83, '049', 2, 'ASUS RT-N56U', 74.90, 0.00, '049.png', 21.00, 'Tipo de dispositivo: Router \nLAN: 10/100/1000 MBit/s, Auto-MDI/MDIX \nGarantía: 2 años.', NULL, 1, 0),
(84, '050', 2, 'Buffalo Technology WZR-1750DHP-EU', 119.90, 0.00, '050.png', 21.00, 'Tipo de dispositivo: Router \nLAN: 10/100/1000 MBit/s, Auto-MDI/MDIX \nGarantía: 2 años.', NULL, 2, 0),
(85, '061', 4, 'Microsoft Office 365 Home Premium', 94.40, 0.00, '061.jpg', 21.00, '\r\n64-bit: Si\r\nCantidad de licencia: 5 usuario(s)\r\nLocalización: Eurozone\r\nNúmero de años: 1 Año(s)\r\nSistema operativo MAC soportado: Si\r\nSistema operativo Windows soportado: Si\r\nTipo de software: Electronic Software Download (ESD)\r\nVersión de idioma: Plurilingüe.', NULL, 2, 0),
(86, '062', 4, 'Microsoft Office 365 - Paquete Hogar, Para Windows, Para 5 PCs, 1 Año', 79.99, 0.00, '062.jpg', 21.00, '64-bit: Si\r\nCantidad de licencia: 1 usuario(s)\r\nCompatible con Mac: Si\r\nEspacio mínimo del disco duro: 3000 MB\r\nNúmero de años: 1 Año(s)\r\nPlataforma: PC, Mac\r\nRAM mínima: 1024 MB\r\nSoftware incluido: Word, Excel, PowerPoint, OneNote, Outlook, Publisher y Access', NULL, 5, 0),
(87, '063', 4, 'Adobe Photoshop Elements 11 & Premiere Elements', 113.90, 0.00, '063.jpg', 21.00, 'Software de gráficos (1 usuario(s), Full, 7168 MB, 2048 MB, Intel Dual Core, ENG)', NULL, 2, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `provincia`
--

DROP TABLE IF EXISTS `provincia`;
CREATE TABLE IF NOT EXISTS `provincia` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=53 ;

--
-- Volcado de datos para la tabla `provincia`
--

INSERT INTO `provincia` (`id`, `nombre`) VALUES
(1, 'Alava'),
(2, 'Albacete'),
(3, 'Alicante'),
(4, 'Almeria'),
(5, 'Avila'),
(6, 'Badajoz'),
(7, 'Illes Balears'),
(8, 'Barcelona'),
(9, 'Burgos'),
(10, 'Cáceres'),
(11, 'Cádiz'),
(12, 'Castellón'),
(13, 'Ciudad Real'),
(14, 'Córdoba'),
(15, 'A Coruña'),
(16, 'Cuenca'),
(17, 'Girona'),
(18, 'Granada'),
(19, 'Guadalajara'),
(20, 'Guipzcoa'),
(21, 'Huelva'),
(22, 'Huesca'),
(23, 'Jaén'),
(24, 'León'),
(25, 'Lleida'),
(26, 'La Rioja'),
(27, 'Lugo'),
(28, 'Madrid'),
(29, 'Málaga'),
(30, 'Murcia'),
(31, 'Navarra'),
(32, 'Ourense'),
(33, 'Asturias'),
(34, 'Palencia'),
(35, 'Las Palmas'),
(36, 'Pontevedra'),
(37, 'Salamanca'),
(38, 'Santa Cruz de Tenerife'),
(39, 'Cantabria'),
(40, 'Segovia'),
(41, 'Sevilla'),
(42, 'Soria'),
(43, 'Tarragona'),
(44, 'Teruel'),
(45, 'Toledo'),
(46, 'Valencia'),
(47, 'Valladolid'),
(48, 'Vizcaya'),
(49, 'Zamora'),
(50, 'Zaragoza'),
(51, 'Ceuta'),
(52, 'Melilla');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

DROP TABLE IF EXISTS `usuario`;
CREATE TABLE IF NOT EXISTS `usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario` varchar(80) NOT NULL,
  `contrasenia` char(40) DEFAULT NULL,
  `email` varchar(128) DEFAULT NULL,
  `nombre` varchar(80) DEFAULT NULL,
  `apellidos` varchar(80) DEFAULT NULL,
  `dni` char(9) DEFAULT NULL,
  `direccion` varchar(100) DEFAULT NULL,
  `cp` char(5) DEFAULT NULL,
  `provincia` int(11) DEFAULT NULL,
  `rol` enum('Administrador','Usuario') DEFAULT NULL,
  `activo` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre_usuario_UNIQUE` (`usuario`),
  KEY `fk_usuario_provincias1_idx` (`provincia`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=23 ;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `usuario`, `contrasenia`, `email`, `nombre`, `apellidos`, `dni`, `direccion`, `cp`, `provincia`, `rol`, `activo`) VALUES
(4, 'sara', 'sara', 'saraalamillo93@gmail.com', 'Sara', 'Alamillo Arroyo', '49113766A', 'c\\ San Sebastian  nº 8', '21600', 21, 'Usuario', 1),
(5, 'admin', 'admin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Administrador', 1);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `destacado`
--
ALTER TABLE `destacado`
  ADD CONSTRAINT `fk_destacado_productos1` FOREIGN KEY (`producto`) REFERENCES `producto` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `linea_pedido`
--
ALTER TABLE `linea_pedido`
  ADD CONSTRAINT `fk_productos_has_pedido_pedido1` FOREIGN KEY (`pedido`) REFERENCES `pedido` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_productos_has_pedido_productos1` FOREIGN KEY (`producto`) REFERENCES `producto` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD CONSTRAINT `fk_pedido_usuario1` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `fk_productos_categoria` FOREIGN KEY (`categoria`) REFERENCES `categoria` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `fk_usuario_provincias1` FOREIGN KEY (`provincia`) REFERENCES `provincia` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
