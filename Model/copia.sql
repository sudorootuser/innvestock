/*
SQLyog Ultimate v11.11 (64 bit)
MySQL - 5.5.5-10.4.24-MariaDB : Database - innvestock
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`innvestock` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `innvestock`;

/*Table structure for table `alistado` */

DROP TABLE IF EXISTS `alistado`;

CREATE TABLE `alistado` (
  `idAlistado` int(11) NOT NULL AUTO_INCREMENT,
  `alistado_tipo` varchar(10) DEFAULT NULL,
  `alistado_fechaDespacho` date DEFAULT NULL,
  `alistado_fechaEntrada` date DEFAULT NULL,
  `alistado_idCliente` int(11) DEFAULT NULL,
  `alistado_estado` varchar(10) DEFAULT 'activo',
  `alistado_consecutivo` varchar(20) DEFAULT NULL,
  `alistado_idBodega` int(11) DEFAULT NULL,
  `alistado_nombrePersona` varchar(50) DEFAULT NULL,
  `alistado_cedulaPersona` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`idAlistado`),
  KEY `alistado_idBodega` (`alistado_idBodega`),
  CONSTRAINT `alistado_ibfk_1` FOREIGN KEY (`alistado_idBodega`) REFERENCES `bodega` (`idBodega`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

/*Data for the table `alistado` */

insert  into `alistado`(`idAlistado`,`alistado_tipo`,`alistado_fechaDespacho`,`alistado_fechaEntrada`,`alistado_idCliente`,`alistado_estado`,`alistado_consecutivo`,`alistado_idBodega`,`alistado_nombrePersona`,`alistado_cedulaPersona`) values (6,'Entrada','0000-00-00','2022-07-28',1,'inactivo','PRI-6',NULL,'fsfsdd','23112312'),(7,'Entrada','0000-00-00','2022-07-28',1,'Recepción','PRI-7',NULL,'nombre','34234234234'),(8,'Entrada','0000-00-00','2022-07-28',1,'inactivo','PRI-8',NULL,'nombre','12432432'),(9,'Entrada','0000-00-00','2022-07-28',1,'Recepción','PRI-9',NULL,'gfdsg','3454354'),(10,'Entrada','0000-00-00','2022-07-28',1,'activo','PRI-10',NULL,'hdfhgfdhgfd','43543'),(11,'Entrada','0000-00-00','2022-07-28',1,'Recepción','PRI-11',NULL,'gfdhgfd','234324'),(12,'Entrada','0000-00-00','2022-07-28',1,'Recepción','PRI-12',NULL,'fgdsgfds','3443534'),(13,'Entrada','0000-00-00','2022-07-28',1,'Recepción','PRI-13',NULL,'gfdsgfds','54353464'),(14,'Entrada','0000-00-00','2022-07-28',1,'Recepción','PRI-14',NULL,'hgfhgf','6554654');

/*Table structure for table `bodega` */

DROP TABLE IF EXISTS `bodega`;

CREATE TABLE `bodega` (
  `idBodega` int(11) NOT NULL AUTO_INCREMENT,
  `bodega_nombre` varchar(60) NOT NULL,
  `bodega_ciudad` int(11) NOT NULL,
  `bodega_municipio` int(11) NOT NULL,
  PRIMARY KEY (`idBodega`),
  KEY `bodega_ciudad` (`bodega_ciudad`),
  CONSTRAINT `bodega_ibfk_1` FOREIGN KEY (`bodega_ciudad`) REFERENCES `ciudad` (`idCiudad`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `bodega` */

insert  into `bodega`(`idBodega`,`bodega_nombre`,`bodega_ciudad`,`bodega_municipio`) values (1,'Mosquera',8,0),(2,'Bogota',8,0);

/*Table structure for table `ciudad` */

DROP TABLE IF EXISTS `ciudad`;

CREATE TABLE `ciudad` (
  `idCiudad` int(11) NOT NULL AUTO_INCREMENT,
  `ciudad_nombre` varchar(30) NOT NULL,
  PRIMARY KEY (`idCiudad`)
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8;

/*Data for the table `ciudad` */

insert  into `ciudad`(`idCiudad`,`ciudad_nombre`) values (1,'Medellín\r'),(2,'Rionegro\r'),(3,'Apartadó\r'),(4,'Turbo\r'),(5,'Caucasia\r'),(6,'Ciudad de Arauca\r'),(7,'Barranquilla\r'),(8,'Bogotá D.C\r'),(9,'Girardot\r'),(10,'Fusagasugá\r'),(11,'Cartagena\r'),(12,'Tunja\r'),(13,'Duitama\r'),(14,'Sogamoso\r'),(15,'Manizales\r'),(16,'Florencia\r'),(17,'Yopal\r'),(18,'Popayán\r'),(19,'Valledupar\r'),(20,'Quibdó\r'),(21,'Montería\r'),(22,'Inírida\r'),(23,'San José\r'),(24,'Neiva\r'),(25,'Riohacha\r'),(26,'Maicao\r'),(27,'Santa Marta\r'),(28,'Ciénaga\r'),(29,'Villavicencio\r'),(30,'Pasto\r'),(31,'Ipiales\r'),(32,'Tumaco\r'),(33,'Cúcuta\r'),(34,'Ocaña\r'),(35,'Pamplona\r'),(36,'Mocoa\r'),(37,'Puerto Asís\r'),(38,'Armenia\r'),(39,'Pereira\r'),(40,'Ciudad de San Andrés\r'),(41,'Bucaramanga\r'),(42,'Barrancabermeja\r'),(43,'San Gil\r'),(44,'Málaga\r'),(45,'Sincelejo\r'),(46,'Ibagué\r'),(47,'Honda\r'),(48,'Cali\r'),(49,'Tuluá\r'),(50,'Palmira\r'),(51,'Buenaventura\r'),(52,'Cartago\r'),(53,'Buga\r'),(54,'Mitú\r'),(55,'Puerto Carreño\r');

/*Table structure for table `cliente` */

DROP TABLE IF EXISTS `cliente`;

CREATE TABLE `cliente` (
  `idCliente` int(11) NOT NULL AUTO_INCREMENT,
  `cliente_tpId` varchar(25) NOT NULL,
  `cliente_nDocument` int(13) NOT NULL,
  `cliente_dv` varchar(11) NOT NULL,
  `cliente_estado` varchar(9) NOT NULL DEFAULT 'activo',
  `cliente_nombre` varchar(30) NOT NULL,
  `cliente_apellido` varchar(30) DEFAULT NULL,
  `cliente_actEco` varchar(30) NOT NULL,
  `cliente_direccion` varchar(30) NOT NULL,
  `cliente_telefono` varchar(11) NOT NULL,
  `cliente_ciudad` int(11) NOT NULL,
  `cliente_tpCliente` varchar(13) NOT NULL,
  `cliente_consecutivo` varchar(20) DEFAULT NULL,
  `cliente_bodega` int(11) DEFAULT NULL,
  PRIMARY KEY (`idCliente`),
  KEY `cliente_ciudad` (`cliente_ciudad`),
  KEY `cliente_bodega` (`cliente_bodega`),
  CONSTRAINT `cliente_ibfk_1` FOREIGN KEY (`cliente_ciudad`) REFERENCES `ciudad` (`idCiudad`),
  CONSTRAINT `cliente_ibfk_2` FOREIGN KEY (`cliente_bodega`) REFERENCES `bodega` (`idBodega`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `cliente` */

insert  into `cliente`(`idCliente`,`cliente_tpId`,`cliente_nDocument`,`cliente_dv`,`cliente_estado`,`cliente_nombre`,`cliente_apellido`,`cliente_actEco`,`cliente_direccion`,`cliente_telefono`,`cliente_ciudad`,`cliente_tpCliente`,`cliente_consecutivo`,`cliente_bodega`) values (1,'NIT',104577,'15456486','activo','nombre 3','apellido 1','Actividad 1','calle 1 #1-1','310789451',3,'Exportador','CL-1',NULL),(2,'Cédula de ciudadanía',2147483647,'123456','inactivo','nombre2','apellido 2','actividad 2','calle 2#2-2','3104567891',7,'Exportador','CL-2',NULL),(3,'Cédula de extrangería',12312453,'231321','activo','nombre3','apellido3','actividad3','direccion 3','3154897589',3,'0','CL-3',NULL),(4,'NIT',2132132,'213','activo','fsdffdsa','dsafds','afdsafd','fdsaf','35435453',4,'0','CL-4',NULL);

/*Table structure for table `cliente_bodega` */

DROP TABLE IF EXISTS `cliente_bodega`;

CREATE TABLE `cliente_bodega` (
  `idCliente_bodega` int(11) NOT NULL AUTO_INCREMENT,
  `cliente_bodega_bodega` int(11) DEFAULT NULL,
  `cliente_bodega_cliente` int(11) DEFAULT NULL,
  `cliente_bodega_fechaIngreso` date DEFAULT NULL,
  PRIMARY KEY (`idCliente_bodega`),
  KEY `clienteproducto_cliente` (`cliente_bodega_cliente`),
  KEY `cliente_bodega_bodega` (`cliente_bodega_bodega`),
  CONSTRAINT `cliente_bodega_ibfk_2` FOREIGN KEY (`cliente_bodega_cliente`) REFERENCES `cliente` (`idCliente`),
  CONSTRAINT `cliente_bodega_ibfk_3` FOREIGN KEY (`cliente_bodega_bodega`) REFERENCES `bodega` (`idBodega`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Data for the table `cliente_bodega` */

insert  into `cliente_bodega`(`idCliente_bodega`,`cliente_bodega_bodega`,`cliente_bodega_cliente`,`cliente_bodega_fechaIngreso`) values (5,1,1,'2022-07-28');

/*Table structure for table `despacho` */

DROP TABLE IF EXISTS `despacho`;

CREATE TABLE `despacho` (
  `idDespacho` int(11) NOT NULL AUTO_INCREMENT,
  `despacho_estado` varchar(10) NOT NULL DEFAULT 'activo',
  `despacho_fechaDs` date NOT NULL,
  `despacho_observaciones` varchar(100) NOT NULL,
  `despacho_idCliente` int(11) NOT NULL,
  `despacho_nombrePersona` varchar(30) DEFAULT NULL,
  `despacho_cedulaPersona` varchar(15) DEFAULT NULL,
  `despacho_placaPersona` varchar(7) DEFAULT NULL,
  `despacho_clienteF` varchar(30) DEFAULT NULL,
  `despacho_codigo` varchar(11) DEFAULT NULL,
  `despacho_consecutivo` varchar(20) DEFAULT NULL,
  `despacho_idBodega` int(11) DEFAULT NULL,
  PRIMARY KEY (`idDespacho`),
  KEY `despacho_idBodega` (`despacho_idBodega`),
  CONSTRAINT `despacho_ibfk_1` FOREIGN KEY (`despacho_idBodega`) REFERENCES `bodega` (`idBodega`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `despacho` */

insert  into `despacho`(`idDespacho`,`despacho_estado`,`despacho_fechaDs`,`despacho_observaciones`,`despacho_idCliente`,`despacho_nombrePersona`,`despacho_cedulaPersona`,`despacho_placaPersona`,`despacho_clienteF`,`despacho_codigo`,`despacho_consecutivo`,`despacho_idBodega`) values (1,'activo','2022-07-27','descripcion',0,'nombre','12321','dsad231','cliente final','32134','DS-1',NULL);

/*Table structure for table `entrada` */

DROP TABLE IF EXISTS `entrada`;

CREATE TABLE `entrada` (
  `idEntrada` int(11) NOT NULL AUTO_INCREMENT,
  `entrada_estado` varchar(10) NOT NULL DEFAULT 'activo',
  `entrada_fecha` datetime NOT NULL,
  `entrada_observaciones` varchar(100) NOT NULL,
  `entrada_idCliente` int(11) NOT NULL,
  `entrada_nombrePersona` varchar(30) DEFAULT NULL,
  `entrada_cedulaPersona` varchar(15) DEFAULT NULL,
  `entrada_placaPersona` varchar(7) DEFAULT NULL,
  `entrada_clienteF` varchar(30) DEFAULT NULL,
  `entrada_codigo` varchar(11) DEFAULT NULL,
  `entrada_consecutivo` varchar(20) DEFAULT NULL,
  `entrada_idBodega` int(11) DEFAULT NULL,
  PRIMARY KEY (`idEntrada`),
  KEY `entrada_idBodega` (`entrada_idBodega`),
  CONSTRAINT `entrada_ibfk_1` FOREIGN KEY (`entrada_idBodega`) REFERENCES `bodega` (`idBodega`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

/*Data for the table `entrada` */

insert  into `entrada`(`idEntrada`,`entrada_estado`,`entrada_fecha`,`entrada_observaciones`,`entrada_idCliente`,`entrada_nombrePersona`,`entrada_cedulaPersona`,`entrada_placaPersona`,`entrada_clienteF`,`entrada_codigo`,`entrada_consecutivo`,`entrada_idBodega`) values (1,'activo','2022-07-27 22:46:09','descripcion',0,'nombre','12313','ADB-231','Cliente Final','codigo ingr','RE-1',NULL),(2,'activo','2022-07-27 22:56:05','descripcion',0,'nombre','2321321','dsa432','cliente final','34423','RE-2',NULL),(3,'activo','2022-07-27 23:24:14','descricpcion',0,'nombre','1233','asd123','cliente final','1233','RE-3',NULL),(4,'activo','2022-07-27 23:30:33','dsadsa',0,'nombre','123','123','cliente','21312','RE-4',NULL),(5,'activo','2022-07-28 18:06:17','erwarefdsafs',0,'dfdsa','342343','543543f','3454534','5434543','RE-5',NULL),(6,'activo','2022-07-28 18:32:04','gfdsgfd',0,'gfds','55464','fcbfsdb','fbsbfd','bsdfbs','RE-6',NULL),(7,'activo','2022-07-28 18:39:58','fgdsgfdsgfd',0,'sgfdsgfds','35435','4gst54','r32r32','y54y54','RE-7',NULL),(8,'activo','2022-07-28 18:42:38','bvfchgf',0,'fgdhgf','546546','fdhgfd','fdhgf','hgfdhg','RE-8',NULL),(9,'activo','2022-07-28 18:45:54','fgdgfdgfds',0,'gfdgfd','43543543','6554655','6554654','54654','RE-9',NULL),(10,'activo','2022-07-28 18:50:33','dfsfdsa',0,'safdsaf','5733783','5t4esg','654765','dy5765','RE-10',NULL),(11,'activo','2022-07-28 18:54:30','hgfdhgfdhgdfh',0,'fdhgfd','546546','ytyy6','dhtfdht','yththt','RE-11',NULL),(12,'activo','2022-07-28 18:55:23','gfdgfd',0,'gfdsg','454665','ghj657','jt6565765','yjtjfjy','RE-12',NULL),(13,'activo','2022-07-28 18:56:10','mjhfgjhgf',0,'htht','756756','jy7','jhgfjy','jfuyty','RE-13',NULL);

/*Table structure for table `historial` */

DROP TABLE IF EXISTS `historial`;

CREATE TABLE `historial` (
  `id_historial` int(11) NOT NULL AUTO_INCREMENT,
  `historial_tipoAccion` varchar(50) NOT NULL,
  `historial_tablaAccion` varchar(15) NOT NULL,
  `historial_idAccion` varchar(20) NOT NULL,
  `historial_userAccion` varchar(20) NOT NULL,
  `historial_fechaAccion` datetime NOT NULL,
  PRIMARY KEY (`id_historial`)
) ENGINE=InnoDB AUTO_INCREMENT=104 DEFAULT CHARSET=utf8;

/*Data for the table `historial` */

insert  into `historial`(`id_historial`,`historial_tipoAccion`,`historial_tablaAccion`,`historial_idAccion`,`historial_userAccion`,`historial_fechaAccion`) values (1,'Crear','cliente','1','1','2022-07-27 21:42:12'),(2,'Crear','cliente','2','1','2022-07-27 21:45:45'),(3,'Actualizar','cliente','1','1','2022-07-27 21:53:05'),(4,'Actualizar','cliente','1','1','2022-07-27 21:53:13'),(5,'Actualizar','cliente','2','1','2022-07-27 21:53:21'),(6,'Actualizar','cliente','1','1','2022-07-27 22:00:36'),(7,'Eliminar','cliente','2','1','2022-07-27 22:01:05'),(8,'Crear','alistado','1','1','2022-07-27 22:02:45'),(9,'Crear','alistado','2','1','2022-07-27 22:03:56'),(10,'Crear','alistado','3','1','2022-07-27 22:05:08'),(11,'Crear','producto','114','1','2022-07-27 22:09:29'),(12,'Actualizar','producto','114','1','2022-07-27 22:10:04'),(13,'Crear','producto','115','1','2022-07-27 22:11:25'),(14,'Crear','cliente','1','1','2022-07-27 22:13:10'),(15,'Crear','cliente','2','1','2022-07-27 22:13:10'),(16,'10 unidades Bloqueadas ','producto','115','1','2022-07-27 22:13:10'),(17,'10 unidades Desbloqueadas ','producto','114','1','2022-07-27 22:32:25'),(18,'5 unidades Desbloqueadas ','producto','115','1','2022-07-27 22:32:45'),(19,'5 unidades Desbloqueadas ','producto','115','1','2022-07-27 22:34:10'),(20,'Crear','cliente','3','1','2022-07-27 22:34:40'),(21,'10 unidades Bloqueadas ','producto','114','1','2022-07-27 22:34:40'),(22,'10 unidades Desbloqueadas ','producto','114','1','2022-07-27 22:35:57'),(23,'Crear','alistado','1','1','2022-07-27 22:39:26'),(24,'Crear','alistado','2','1','2022-07-27 22:40:01'),(25,'Crear','alistado','0','1','2022-07-27 22:40:31'),(26,'Eliminar','producto','0','1','2022-07-27 22:43:07'),(27,'Crear','cliente','4','1','2022-07-27 22:46:09'),(28,'Crear','cliente','5','1','2022-07-27 22:46:10'),(29,'Crear','alistado','1','1','2022-07-27 22:46:09'),(30,'Crear','cliente','6','1','2022-07-27 22:56:05'),(31,'Crear','cliente','7','1','2022-07-27 22:56:05'),(32,'Crear','alistado','3','1','2022-07-27 22:59:49'),(33,'Crear','cliente','8','1','2022-07-27 23:06:39'),(34,'Crear','alistado','1','1','2022-07-27 23:06:39'),(35,'Crear','producto','116','1','2022-07-27 23:22:05'),(36,'Crear','cliente','9','1','2022-07-27 23:24:14'),(37,'Crear','cliente','10','1','2022-07-27 23:24:14'),(38,'Crear','cliente','11','1','2022-07-27 23:24:14'),(39,'Crear','alistado','4','1','2022-07-27 23:28:19'),(40,'Crear','cliente','12','1','2022-07-27 23:30:33'),(41,'Crear','cliente','13','1','2022-07-27 23:30:33'),(42,'Crear','cliente','14','1','2022-07-27 23:30:33'),(43,'Crear','cliente','15','1','2022-07-27 23:31:49'),(44,'3 unidades Bloqueadas ','producto','114','1','2022-07-27 23:31:49'),(45,'3 unidades Desbloqueadas ','producto','114','1','2022-07-27 23:34:03'),(46,'Crear','alistado','5','1','2022-07-27 23:36:41'),(47,'1 unidades Desbloqueadas ','producto','114','1','2022-07-28 15:32:32'),(48,'Actualizar','cliente','1','1','2022-07-28 15:38:22'),(49,'Eliminar','cliente','2','1','2022-07-28 15:39:11'),(50,'Crear','cliente','3','1','2022-07-28 15:45:35'),(51,'Crear','cliente','4','1','2022-07-28 15:48:14'),(52,'Crear','alistado','4','1','2022-07-28 16:01:44'),(53,'Actualizar','producto','114','1','2022-07-28 16:07:36'),(54,'Actualizar','producto','114','1','2022-07-28 16:07:43'),(55,'Crear','producto','117','1','2022-07-28 16:18:10'),(56,'Actualizar','producto','117','1','2022-07-28 16:18:29'),(57,'Crear','producto','118','1','2022-07-28 16:19:45'),(58,'Crear','alistado','5','1','2022-07-28 16:41:50'),(59,'Eliminar','producto','0','1','2022-07-28 16:45:16'),(60,'Crear','cliente','16','1','2022-07-28 16:52:42'),(61,'5 unidades Bloqueadas ','producto','114','1','2022-07-28 16:52:42'),(62,'Crear','cliente','17','1','2022-07-28 16:56:24'),(63,'Crear','cliente','18','1','2022-07-28 16:56:24'),(64,'10 unidades Bloqueadas ','producto','115','1','2022-07-28 16:56:24'),(65,'Crear','cliente','19','1','2022-07-28 16:58:22'),(66,'10 unidades Bloqueadas ','producto','114','1','2022-07-28 16:58:22'),(67,'10 unidades Desbloqueadas ','producto','114','1','2022-07-28 16:59:07'),(68,'Crear','alistado','6','1','2022-07-28 17:37:06'),(69,'Crear','alistado','7','1','2022-07-28 17:37:38'),(70,'Eliminar','producto','0','1','2022-07-28 17:40:44'),(71,'Crear','alistado','8','1','2022-07-28 17:50:21'),(72,'Eliminar','producto','0','1','2022-07-28 17:53:08'),(73,'Crear','tarea','20','1','2022-07-28 18:06:17'),(74,'Crear','tarea','21','1','2022-07-28 18:06:17'),(75,'Crear','alistado','5','1','2022-07-28 18:06:17'),(76,'Crear','alistado','9','1','2022-07-28 18:08:59'),(77,'Crear','alistado','10','1','2022-07-28 18:09:16'),(78,'Crear','tarea','1','1','2022-07-28 18:32:04'),(79,'Crear','tarea','2','1','2022-07-28 18:32:04'),(80,'Crear','alistado','6','1','2022-07-28 18:32:04'),(81,'Crear','alistado','11','1','2022-07-28 18:38:08'),(82,'Crear','tarea','3','1','2022-07-28 18:39:58'),(83,'Crear','tarea','4','1','2022-07-28 18:39:58'),(84,'Crear','Ingreso','7','1','2022-07-28 18:39:58'),(85,'Crear','alistado','12','1','2022-07-28 18:42:15'),(86,'Crear','tarea','5','1','2022-07-28 18:42:38'),(87,'Crear','tarea','6','1','2022-07-28 18:42:38'),(88,'Crear','Ingreso','8','1','2022-07-28 18:42:38'),(89,'Crear','alistado','13','1','2022-07-28 18:44:01'),(90,'Crear','tarea','7','1','2022-07-28 18:45:54'),(91,'Crear','tarea','8','1','2022-07-28 18:45:54'),(92,'Crear','Ingreso','9','1','2022-07-28 18:45:54'),(93,'Crear','alistado','14','1','2022-07-28 18:49:40'),(94,'Crear','tarea','1','1','2022-07-28 18:50:33'),(95,'Crear','tarea','2','1','2022-07-28 18:50:33'),(96,'Crear','Ingreso','10','1','2022-07-28 18:50:33'),(97,'Crear','tarea','3','1','2022-07-28 18:54:30'),(98,'Crear','tarea','4','1','2022-07-28 18:54:30'),(99,'1 unidades Desbloqueadas ','producto','114','1','2022-07-28 18:54:53'),(100,'Crear','tarea','5','1','2022-07-28 18:55:23'),(101,'Crear','tarea','6','1','2022-07-28 18:55:23'),(102,'Crear','tarea','7','1','2022-07-28 18:56:10'),(103,'Crear','tarea','8','1','2022-07-28 18:56:10');

/*Table structure for table `imagen` */

DROP TABLE IF EXISTS `imagen`;

CREATE TABLE `imagen` (
  `id_imagen` int(11) NOT NULL AUTO_INCREMENT,
  `imagen_nombre` varchar(30) NOT NULL,
  `imagen_tipo` varchar(20) NOT NULL,
  `imagen_size` varchar(15) NOT NULL,
  `imagen_fecha` date NOT NULL,
  `producto_id` int(11) NOT NULL,
  PRIMARY KEY (`id_imagen`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `imagen` */

insert  into `imagen`(`id_imagen`,`imagen_nombre`,`imagen_tipo`,`imagen_size`,`imagen_fecha`,`producto_id`) values (1,'a.png','image/png','4535924','2022-07-28',114),(2,'images.jpg','image/jpeg','9861','2022-07-27',114),(3,'miedos.gif','image/gif','110353','2022-07-28',118),(4,'lineas-para-emprendedores.jpg','image/jpeg','232058','2022-07-28',118);

/*Table structure for table `producto` */

DROP TABLE IF EXISTS `producto`;

CREATE TABLE `producto` (
  `idProducto` int(11) NOT NULL AUTO_INCREMENT,
  `producto_codigo` varchar(11) NOT NULL,
  `producto_nombre` varchar(300) NOT NULL,
  `producto_rotacion` varchar(1) DEFAULT NULL,
  `producto_diasAviso` int(11) DEFAULT NULL,
  `producto_minimo` int(11) NOT NULL,
  `producto_maximo` int(11) NOT NULL,
  `producto_alerta` int(11) NOT NULL,
  `producto_precio` int(11) DEFAULT NULL,
  `producto_descripcion` varchar(200) DEFAULT NULL,
  `producto_uniCant` varchar(9) NOT NULL,
  `producto_cantidad` int(11) NOT NULL DEFAULT 0,
  `producto_peso` float(4,1) NOT NULL,
  `producto_uniPeso` varchar(9) NOT NULL,
  `producto_modelo` varchar(11) DEFAULT NULL,
  `producto_serial` varchar(11) DEFAULT NULL,
  `producto_lote` varchar(11) DEFAULT NULL,
  `producto_marca` varchar(11) DEFAULT NULL,
  `producto_fechaVenc` date DEFAULT NULL,
  `producto_nContenedor` varchar(11) DEFAULT NULL,
  `producto_ancho` float(11,1) DEFAULT NULL,
  `producto_alto` float(11,1) DEFAULT NULL,
  `producto_largo` float(11,1) DEFAULT NULL,
  `producto_uniDimen` varchar(11) DEFAULT NULL,
  `producto_idCliente` int(11) NOT NULL,
  `producto_estado` varchar(9) NOT NULL DEFAULT 'activo',
  `producto_cantidadAlis` int(11) DEFAULT 0,
  `producto_RFID` varchar(10) DEFAULT NULL,
  `producto_cantidadBlock` int(11) DEFAULT 0,
  `producto_consecutivo` varchar(20) DEFAULT NULL,
  `producto_idBodega` int(11) DEFAULT NULL,
  `producto_subInventario` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`idProducto`),
  KEY `cliente_idCliente` (`producto_idCliente`),
  KEY `producto_idBodega` (`producto_idBodega`)
) ENGINE=InnoDB AUTO_INCREMENT=119 DEFAULT CHARSET=utf8;

/*Data for the table `producto` */

insert  into `producto`(`idProducto`,`producto_codigo`,`producto_nombre`,`producto_rotacion`,`producto_diasAviso`,`producto_minimo`,`producto_maximo`,`producto_alerta`,`producto_precio`,`producto_descripcion`,`producto_uniCant`,`producto_cantidad`,`producto_peso`,`producto_uniPeso`,`producto_modelo`,`producto_serial`,`producto_lote`,`producto_marca`,`producto_fechaVenc`,`producto_nContenedor`,`producto_ancho`,`producto_alto`,`producto_largo`,`producto_uniDimen`,`producto_idCliente`,`producto_estado`,`producto_cantidadAlis`,`producto_RFID`,`producto_cantidadBlock`,`producto_consecutivo`,`producto_idBodega`,`producto_subInventario`) values (114,'120102','SAL SOMEX 4% X 40 KILOS CON SELENIO AAA','A',10,10,10,10,100000,'Descripción','Pallet',139,40.0,'KiloGramo','modelo1','serial1','lote1','marca1','2022-07-29','10',100.0,100.0,100.0,'CentiMetros',1,'activo',0,'23123123',1,'PR-114',NULL,'Subinventario1'),(115,'120703','LECHE SAL SOMEX 5% X 10 KILOS MOCHILA','_',0,10,10,10,0,'','Unitaria',100,10.0,'Gramo','','','','','0000-00-00','',0.0,0.0,0.0,'_',2,'activo',0,'LFDS34',0,'PR-115',NULL,'Subinventario 2'),(116,'123123','nombre','_',0,123,12,12,0,'','Caja',100,10.0,'Gramo','','','','','0000-00-00','',0.0,0.0,0.0,'_',1,'activo',0,'234324',0,'PR-116',NULL,'dfssdfdfs'),(117,'134223','nombre de producto','_',0,123,123,123,0,'','Unitaria',100,40.0,'Gramo','','','','','0000-00-00','',0.0,0.0,0.0,'_',2,'activo',0,'4512312',0,'PR-117',NULL,'40'),(118,'10123456','nombre','_',0,2,2,1,0,'','Unitaria',100,2.0,'Miligramo','','','','','0000-00-00','',0.0,0.0,0.0,'_',2,'inactivo',0,'',0,'PR-118',NULL,'');

/*Table structure for table `producto_alistado` */

DROP TABLE IF EXISTS `producto_alistado`;

CREATE TABLE `producto_alistado` (
  `producto_alistado_id` int(11) NOT NULL AUTO_INCREMENT,
  `producto_alistado_idProducto` int(11) NOT NULL,
  `producto_alistado_idAlistado` int(11) NOT NULL,
  `producto_alistado_cantidad` int(11) NOT NULL,
  PRIMARY KEY (`producto_alistado_id`),
  KEY `producto_alistado_idAlistado` (`producto_alistado_idAlistado`),
  KEY `producto_alistado_idProducto` (`producto_alistado_idProducto`),
  CONSTRAINT `producto_alistado_ibfk_1` FOREIGN KEY (`producto_alistado_idAlistado`) REFERENCES `alistado` (`idAlistado`),
  CONSTRAINT `producto_alistado_ibfk_2` FOREIGN KEY (`producto_alistado_idProducto`) REFERENCES `producto` (`idProducto`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

/*Data for the table `producto_alistado` */

insert  into `producto_alistado`(`producto_alistado_id`,`producto_alistado_idProducto`,`producto_alistado_idAlistado`,`producto_alistado_cantidad`) values (1,114,9,10),(2,114,10,10),(3,116,10,10),(4,114,11,10),(5,114,12,10),(6,114,13,10),(7,114,14,10);

/*Table structure for table `producto_bodega` */

DROP TABLE IF EXISTS `producto_bodega`;

CREATE TABLE `producto_bodega` (
  `idProducto_bodega` int(11) NOT NULL AUTO_INCREMENT,
  `producto_bodega_idProducto` int(11) NOT NULL,
  `producto_bodega_idBodega` int(11) NOT NULL DEFAULT 1,
  `producto_bodega_cantidad` int(11) NOT NULL DEFAULT 0,
  `producto_bodega_cantidadAlis` int(11) DEFAULT 0,
  `producto_bodega_cantidadBlock` int(11) DEFAULT 0,
  PRIMARY KEY (`idProducto_bodega`),
  KEY `producto_bodega_idProducto` (`producto_bodega_idProducto`),
  KEY `producto_bodega_idBodega` (`producto_bodega_idBodega`),
  CONSTRAINT `producto_bodega_ibfk_1` FOREIGN KEY (`producto_bodega_idProducto`) REFERENCES `producto` (`idProducto`),
  CONSTRAINT `producto_bodega_ibfk_2` FOREIGN KEY (`producto_bodega_idBodega`) REFERENCES `bodega` (`idBodega`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `producto_bodega` */

/*Table structure for table `producto_despacho` */

DROP TABLE IF EXISTS `producto_despacho`;

CREATE TABLE `producto_despacho` (
  `producto_despacho_id` int(11) NOT NULL AUTO_INCREMENT,
  `producto_despacho_idProducto` int(11) DEFAULT NULL,
  `producto_despacho_idDespacho` int(11) DEFAULT NULL,
  `producto_despacho_cantidad` int(11) DEFAULT NULL,
  PRIMARY KEY (`producto_despacho_id`),
  KEY `producto_despacho_idProducto` (`producto_despacho_idProducto`),
  KEY `producto_despacho_idDespacho` (`producto_despacho_idDespacho`),
  CONSTRAINT `producto_despacho_ibfk_1` FOREIGN KEY (`producto_despacho_idProducto`) REFERENCES `producto` (`idProducto`),
  CONSTRAINT `producto_despacho_ibfk_2` FOREIGN KEY (`producto_despacho_idDespacho`) REFERENCES `despacho` (`idDespacho`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `producto_despacho` */

/*Table structure for table `producto_ingresado` */

DROP TABLE IF EXISTS `producto_ingresado`;

CREATE TABLE `producto_ingresado` (
  `producto_ingresado_id` int(11) NOT NULL AUTO_INCREMENT,
  `producto_ingresado_idProducto` int(11) DEFAULT NULL,
  `producto_ingresado_idEntrada` int(11) DEFAULT NULL,
  `producto_ingresado_cantidad` int(11) DEFAULT NULL,
  PRIMARY KEY (`producto_ingresado_id`),
  KEY `producto_ingresado_idEntrada` (`producto_ingresado_idEntrada`),
  KEY `producto_ingresado_idProducto` (`producto_ingresado_idProducto`),
  CONSTRAINT `producto_ingresado_ibfk_1` FOREIGN KEY (`producto_ingresado_idEntrada`) REFERENCES `entrada` (`idEntrada`),
  CONSTRAINT `producto_ingresado_ibfk_2` FOREIGN KEY (`producto_ingresado_idProducto`) REFERENCES `producto` (`idProducto`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `producto_ingresado` */

insert  into `producto_ingresado`(`producto_ingresado_id`,`producto_ingresado_idProducto`,`producto_ingresado_idEntrada`,`producto_ingresado_cantidad`) values (1,114,10,10),(2,114,11,10),(3,114,12,10),(4,114,13,10);

/*Table structure for table `tarea` */

DROP TABLE IF EXISTS `tarea`;

CREATE TABLE `tarea` (
  `idTarea` int(11) NOT NULL AUTO_INCREMENT,
  `tarea_descripCorta` varchar(100) DEFAULT NULL,
  `tarea_usuario` int(11) DEFAULT NULL,
  `tarea_idEntrada` int(11) DEFAULT NULL,
  `tarea_idDespacho` int(11) DEFAULT NULL,
  `tarea_prioridad` varchar(10) DEFAULT NULL,
  `tarea_estado` varchar(20) DEFAULT 'activo',
  `tarea_consecutivo` varchar(15) DEFAULT NULL,
  `tarea_novedad` int(3) DEFAULT NULL,
  `tarea_idProducto` int(11) DEFAULT NULL,
  PRIMARY KEY (`idTarea`),
  KEY `tarea_usuario` (`tarea_usuario`),
  KEY `tarea_idDespacho` (`tarea_idDespacho`),
  KEY `tarea_idEntrada` (`tarea_idEntrada`),
  KEY `tarea_idProducto` (`tarea_idProducto`),
  CONSTRAINT `tarea_ibfk_1` FOREIGN KEY (`tarea_usuario`) REFERENCES `usuario` (`idUsuario`),
  CONSTRAINT `tarea_ibfk_2` FOREIGN KEY (`tarea_idDespacho`) REFERENCES `despacho` (`idDespacho`),
  CONSTRAINT `tarea_ibfk_3` FOREIGN KEY (`tarea_idEntrada`) REFERENCES `entrada` (`idEntrada`),
  CONSTRAINT `tarea_ibfk_4` FOREIGN KEY (`tarea_idProducto`) REFERENCES `producto` (`idProducto`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

/*Data for the table `tarea` */

insert  into `tarea`(`idTarea`,`tarea_descripCorta`,`tarea_usuario`,`tarea_idEntrada`,`tarea_idDespacho`,`tarea_prioridad`,`tarea_estado`,`tarea_consecutivo`,`tarea_novedad`,`tarea_idProducto`) values (1,'dfsfdsa',1,10,NULL,'Control','activo','TR-1',NULL,NULL),(2,'',1,10,NULL,'Control','activo','TR-2',0,114),(3,'hgfdhgfdhgdfh',1,11,NULL,'Control','activo','TR-3',NULL,NULL),(4,'',1,11,NULL,'Alto','activo','TR-4',0,114),(5,'gfdgfd',1,12,NULL,'Control','activo','TR-5',NULL,NULL),(6,'',1,12,NULL,'','activo','TR-6',0,114),(7,'mjhfgjhgf',1,13,NULL,'Alto','activo','TR-7',NULL,NULL),(8,'',1,13,NULL,'Alto','activo','TR-8',1,114);

/*Table structure for table `usuario` */

DROP TABLE IF EXISTS `usuario`;

CREATE TABLE `usuario` (
  `idUsuario` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_idRol` varchar(25) NOT NULL,
  `usuario_nombre` varchar(41) DEFAULT NULL,
  `usuario_apellido` varchar(70) DEFAULT NULL,
  `usuario_fecha` date DEFAULT NULL,
  `usuario_tDocument` varchar(25) DEFAULT NULL,
  `usuario_documento` varchar(15) DEFAULT NULL,
  `usuario_correo` varchar(45) DEFAULT NULL,
  `usuario_direccion` varchar(70) DEFAULT NULL,
  `usuario_password` varchar(80) NOT NULL,
  `last_session` datetime NOT NULL,
  PRIMARY KEY (`idUsuario`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `usuario` */

insert  into `usuario`(`idUsuario`,`usuario_idRol`,`usuario_nombre`,`usuario_apellido`,`usuario_fecha`,`usuario_tDocument`,`usuario_documento`,`usuario_correo`,`usuario_direccion`,`usuario_password`,`last_session`) values (1,'Administrador','Ana Milena','Cadena',NULL,NULL,'123456789','ana@gmail.com',NULL,'D4584547C7F6A01A40BB8D863AB2C134E0C51CE353C0CA2FD93857961D750658','2022-07-28 18:54:57');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
