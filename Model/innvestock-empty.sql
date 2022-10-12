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
  `alistado_placaPersona` varchar(7) DEFAULT NULL,
  `alistado_clienteF` varchar(30) DEFAULT NULL,
  `alistado_codigo` varchar(11) DEFAULT NULL,
  `alistado_observacion` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`idAlistado`),
  KEY `alistado_idBodega` (`alistado_idBodega`),
  CONSTRAINT `alistado_ibfk_1` FOREIGN KEY (`alistado_idBodega`) REFERENCES `bodega` (`idBodega`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `alistado` */

/*Table structure for table `bodega` */

DROP TABLE IF EXISTS `bodega`;

CREATE TABLE `bodega` (
  `idBodega` int(11) NOT NULL AUTO_INCREMENT,
  `bodega_nombre` varchar(60) NOT NULL,
  `bodega_ciudad` int(11) NOT NULL,
  `bodega_municipio` int(11) NOT NULL,
  `bodega_estado` varchar(8) DEFAULT 'activo',
  `bodega_observacion` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`idBodega`),
  KEY `bodega_ciudad` (`bodega_ciudad`),
  CONSTRAINT `bodega_ibfk_1` FOREIGN KEY (`bodega_ciudad`) REFERENCES `ciudad` (`idCiudad`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

/*Data for the table `bodega` */

insert  into `bodega`(`idBodega`,`bodega_nombre`,`bodega_ciudad`,`bodega_municipio`,`bodega_estado`,`bodega_observacion`) values (1,'Mosquera',15,0,'activo',NULL),(2,'Soacha',8,0,'activo',NULL),(3,'Bogotá',55,0,'activo','observacionn');

/*Table structure for table `ciudad` */

DROP TABLE IF EXISTS `ciudad`;

CREATE TABLE `ciudad` (
  `idCiudad` int(11) NOT NULL AUTO_INCREMENT,
  `ciudad_codigo` varchar(10) NOT NULL,
  `ciudad_nombre` varchar(30) NOT NULL,
  `ciudad_estado` varchar(7) DEFAULT 'Activo',
  PRIMARY KEY (`idCiudad`)
) ENGINE=InnoDB AUTO_INCREMENT=1122 DEFAULT CHARSET=utf8;

/*Data for the table `ciudad` */

insert  into `ciudad`(`idCiudad`,`ciudad_codigo`,`ciudad_nombre`,`ciudad_estado`) values (8,'05036','ANGELOPOLIS','Activo'),(11,'05042','SANTAFE DE ANTIOQUIA','Activo'),(13,'05045','APARTADO','Activo'),(14,'05051','ARBOLETES','Activo'),(15,'05055','ARGELIA','Activo'),(16,'05059','ARMENIA','Activo'),(17,'05079','BARBOSA','Activo'),(18,'05086','BELMIRA','Activo'),(19,'05088','BELLO','Activo'),(20,'05091','BETANIA','Activo'),(21,'05093','BETULIA','Activo'),(22,'05101','CIUDAD BOLIVAR','Activo'),(23,'05107','BRICEÑO','Activo'),(24,'05113','BURITICA','Activo'),(25,'05120','CACERES','Activo'),(26,'05125','CAICEDO','Activo'),(27,'05129','CALDAS','Activo'),(28,'05134','CAMPAMENTO','Activo'),(29,'05138','CAÑASGORDAS','Activo'),(30,'05142','CARACOLI','Activo'),(31,'05145','CARAMANTA','Activo'),(32,'05147','CAREPA','Activo'),(33,'05148','EL CARMEN DE VIBORAL','Activo'),(34,'05150','CAROLINA','Activo'),(35,'05154','CAUCASIA','Activo'),(36,'05172','CHIGORODO','Activo'),(37,'05190','CISNEROS','Activo'),(38,'05197','COCORNA','Activo'),(39,'05206','CONCEPCION','Activo'),(40,'05209','CONCORDIA','Activo'),(41,'05212','COPACABANA','Activo'),(42,'05234','DABEIBA','Activo'),(43,'05237','DON MATIAS','Activo'),(44,'05240','EBEJICO','Activo'),(45,'05250','EL BAGRE','Activo'),(46,'05264','ENTRERRIOS','Activo'),(47,'05266','ENVIGADO','Activo'),(48,'05282','FREDONIA','Activo'),(49,'05284','FRONTINO','Activo'),(50,'05306','GIRALDO','Activo'),(51,'05308','GIRARDOTA','Activo'),(52,'05310','GOMEZ PLATA','Activo'),(53,'05313','GRANADA','Activo'),(54,'05315','GUADALUPE','Activo'),(55,'05318','GUARNE','Activo'),(56,'05321','GUATAPE','Activo'),(57,'05347','HELICONIA','Activo'),(58,'05353','HISPANIA','Activo'),(59,'05360','ITAGUI','Activo'),(60,'05361','ITUANGO','Activo'),(61,'05364','JARDIN','Activo'),(62,'05368','JERICO','Activo'),(63,'05376','LA CEJA','Activo'),(64,'05380','LA ESTRELLA','Activo'),(65,'05390','LA PINTADA','Activo'),(66,'05400','LA UNION','Activo'),(67,'05411','LIBORINA','Activo'),(68,'05425','MACEO','Activo'),(69,'05440','MARINILLA','Activo'),(70,'05467','MONTEBELLO','Activo'),(71,'05475','MURINDO','Activo'),(72,'05480','MUTATA','Activo'),(73,'05483','NARIÑO','Activo'),(74,'05490','NECOCLI','Activo'),(75,'05495','NECHI','Activo'),(76,'05501','OLAYA','Activo'),(77,'05541','PEÐOL','Activo'),(78,'05543','PEQUE','Activo'),(79,'05576','PUEBLORRICO','Activo'),(80,'05579','PUERTO BERRIO','Activo'),(81,'05585','PUERTO NARE','Activo'),(82,'05591','PUERTO TRIUNFO','Activo'),(83,'05604','REMEDIOS','Activo'),(84,'05607','RETIRO','Activo'),(85,'05615','RIONEGRO','Activo'),(86,'05628','SABANALARGA','Activo'),(87,'05631','SABANETA','Activo'),(88,'05642','SALGAR','Activo'),(89,'05647','SAN ANDRES DE CUERQUIA','Activo'),(90,'05649','SAN CARLOS','Activo'),(91,'05652','SAN FRANCISCO','Activo'),(92,'05656','SAN JERONIMO','Activo'),(93,'05658','SAN JOSE DE LA MONTAÑA','Activo'),(94,'05659','SAN JUAN DE URABA','Activo'),(95,'05660','SAN LUIS','Activo'),(96,'05664','SAN PEDRO','Activo'),(97,'05665','SAN PEDRO DE URABA','Activo'),(98,'05667','SAN RAFAEL','Activo'),(99,'05670','SAN ROQUE','Activo'),(100,'05674','SAN VICENTE','Activo'),(101,'05679','SANTA BARBARA','Activo'),(102,'05686','SANTA ROSA DE OSOS','Activo'),(103,'05690','SANTO DOMINGO','Activo'),(104,'05697','EL SANTUARIO','Activo'),(105,'05736','SEGOVIA','Activo'),(106,'05756','SONSON','Activo'),(107,'05761','SOPETRAN','Activo'),(108,'05789','TAMESIS','Activo'),(109,'05790','TARAZA','Activo'),(110,'05792','TARSO','Activo'),(111,'05809','TITIRIBI','Activo'),(112,'05819','TOLEDO','Activo'),(113,'05837','TURBO','Activo'),(114,'05842','URAMITA','Activo'),(115,'05847','URRAO','Activo'),(116,'05854','VALDIVIA','Activo'),(117,'05856','VALPARAISO','Activo'),(118,'05858','VEGACHI','Activo'),(119,'05861','VENECIA','Activo'),(120,'05873','VIGIA DEL FUERTE','Activo'),(121,'05885','YALI','Activo'),(122,'05887','YARUMAL','Activo'),(123,'05890','YOLOMBO','Activo'),(124,'05893','YONDO','Activo'),(125,'05895','ZARAGOZA','Activo'),(126,'08001','BARRANQUILLA','Activo'),(127,'08078','BARANOA','Activo'),(128,'08137','CAMPO DE LA CRUZ','Activo'),(129,'08141','CANDELARIA','Activo'),(130,'08296','GALAPA','Activo'),(131,'08372','JUAN DE ACOSTA','Activo'),(132,'08421','LURUACO','Activo'),(133,'08433','MALAMBO','Activo'),(134,'08436','MANATI','Activo'),(135,'08520','PALMAR DE VARELA','Activo'),(136,'08549','PIOJO','Activo'),(137,'08558','POLONUEVO','Activo'),(138,'08560','PONEDERA','Activo'),(139,'08573','PUERTO COLOMBIA','Activo'),(140,'08606','REPELON','Activo'),(141,'08634','SABANAGRANDE','Activo'),(142,'08638','SABANALARGA','Activo'),(143,'08675','SANTA LUCIA','Activo'),(144,'08685','SANTO TOMAS','Activo'),(145,'08758','SOLEDAD','Activo'),(146,'08770','SUAN','Activo'),(147,'08832','TUBARA','Activo'),(148,'08849','USIACURI','Activo'),(149,'11001','BOGOTA, D.C.','Activo'),(150,'13001','CARTAGENA','Activo'),(151,'13006','ACHI','Activo'),(152,'13030','ALTOS DEL ROSARIO','Activo'),(153,'13042','ARENAL','Activo'),(154,'13052','ARJONA','Activo'),(155,'13062','ARROYOHONDO','Activo'),(156,'13074','BARRANCO DE LOBA','Activo'),(157,'13140','CALAMAR','Activo'),(158,'13160','CANTAGALLO','Activo'),(159,'13188','CICUCO','Activo'),(160,'13212','CORDOBA','Activo'),(161,'13222','CLEMENCIA','Activo'),(162,'13244','EL CARMEN DE BOLIVAR','Activo'),(163,'13248','EL GUAMO','Activo'),(164,'13268','EL PEÑON','Activo'),(165,'13300','HATILLO DE LOBA','Activo'),(166,'13430','MAGANGUE','Activo'),(167,'13433','MAHATES','Activo'),(168,'13440','MARGARITA','Activo'),(169,'13442','MARIA LA BAJA','Activo'),(170,'13458','MONTECRISTO','Activo'),(171,'13468','MOMPOS','Activo'),(172,'13490','NOROSI','Activo'),(173,'13473','MORALES','Activo'),(174,'13549','PINILLOS','Activo'),(175,'13580','REGIDOR','Activo'),(176,'13600','RIO VIEJO','Activo'),(177,'13620','SAN CRISTOBAL','Activo'),(178,'13647','SAN ESTANISLAO','Activo'),(179,'13650','SAN FERNANDO','Activo'),(180,'13654','SAN JACINTO','Activo'),(181,'13655','SAN JACINTO DEL CAUCA','Activo'),(182,'13657','SAN JUAN NEPOMUCENO','Activo'),(183,'13667','SAN MARTIN DE LOBA','Activo'),(184,'13670','SAN PABLO','Activo'),(185,'13673','SANTA CATALINA','Activo'),(186,'13683','SANTA ROSA','Activo'),(187,'13688','SANTA ROSA DEL SUR','Activo'),(188,'13744','SIMITI','Activo'),(189,'13760','SOPLAVIENTO','Activo'),(190,'13780','TALAIGUA NUEVO','Activo'),(191,'13810','TIQUISIO','Activo'),(192,'13836','TURBACO','Activo'),(193,'13838','TURBANA','Activo'),(194,'13873','VILLANUEVA','Activo'),(195,'13894','ZAMBRANO','Activo'),(196,'15001','TUNJA','Activo'),(197,'15022','ALMEIDA','Activo'),(198,'15047','AQUITANIA','Activo'),(199,'15051','ARCABUCO','Activo'),(200,'15087','BELEN','Activo'),(201,'15090','BERBEO','Activo'),(202,'15092','BETEITIVA','Activo'),(203,'15097','BOAVITA','Activo'),(204,'15104','BOYACA','Activo'),(205,'15106','BRICEÑO','Activo'),(206,'15109','BUENAVISTA','Activo'),(207,'15114','BUSBANZA','Activo'),(208,'15131','CALDAS','Activo'),(209,'15135','CAMPOHERMOSO','Activo'),(210,'15162','CERINZA','Activo'),(211,'15172','CHINAVITA','Activo'),(212,'15176','CHIQUINQUIRA','Activo'),(213,'15180','CHISCAS','Activo'),(214,'15183','CHITA','Activo'),(215,'15185','CHITARAQUE','Activo'),(216,'15187','CHIVATA','Activo'),(217,'15189','CIENEGA','Activo'),(218,'15204','COMBITA','Activo'),(219,'15212','COPER','Activo'),(220,'15215','CORRALES','Activo'),(221,'15218','COVARACHIA','Activo'),(222,'15223','CUBARA','Activo'),(223,'15224','CUCAITA','Activo'),(224,'15226','CUITIVA','Activo'),(225,'15232','CHIQUIZA','Activo'),(226,'15236','CHIVOR','Activo'),(227,'15238','DUITAMA','Activo'),(228,'15244','EL COCUY','Activo'),(229,'15248','EL ESPINO','Activo'),(230,'15272','FIRAVITOBA','Activo'),(231,'15276','FLORESTA','Activo'),(232,'15293','GACHANTIVA','Activo'),(233,'15296','GAMEZA','Activo'),(234,'15299','GARAGOA','Activo'),(235,'15317','GUACAMAYAS','Activo'),(236,'15322','GUATEQUE','Activo'),(237,'15325','GUAYATA','Activo'),(238,'15332','GsICAN','Activo'),(239,'15362','IZA','Activo'),(240,'15367','JENESANO','Activo'),(241,'15368','JERICO','Activo'),(242,'15377','LABRANZAGRANDE','Activo'),(243,'15380','LA CAPILLA','Activo'),(244,'15401','LA VICTORIA','Activo'),(245,'15403','LA UVITA','Activo'),(246,'15407','VILLA DE LEYVA','Activo'),(247,'15425','MACANAL','Activo'),(248,'15442','MARIPI','Activo'),(249,'15455','MIRAFLORES','Activo'),(250,'15464','MONGUA','Activo'),(251,'15466','MONGUI','Activo'),(252,'15469','MONIQUIRA','Activo'),(253,'15476','MOTAVITA','Activo'),(254,'15480','MUZO','Activo'),(255,'15491','NOBSA','Activo'),(256,'15494','NUEVO COLON','Activo'),(257,'15500','OICATA','Activo'),(258,'15507','OTANCHE','Activo'),(259,'15511','PACHAVITA','Activo'),(260,'15514','PAEZ','Activo'),(261,'15516','PAIPA','Activo'),(262,'15518','PAJARITO','Activo'),(263,'15522','PANQUEBA','Activo'),(264,'15531','PAUNA','Activo'),(265,'15533','PAYA','Activo'),(266,'15537','PAZ DE RIO','Activo'),(267,'15542','PESCA','Activo'),(268,'15550','PISBA','Activo'),(269,'15572','PUERTO BOYACA','Activo'),(270,'15580','QUIPAMA','Activo'),(271,'15599','RAMIRIQUI','Activo'),(272,'15600','RAQUIRA','Activo'),(273,'15621','RONDON','Activo'),(274,'15632','SABOYA','Activo'),(275,'15638','SACHICA','Activo'),(276,'15646','SAMACA','Activo'),(277,'15660','SAN EDUARDO','Activo'),(278,'15664','SAN JOSE DE PARE','Activo'),(279,'15667','SAN LUIS DE GACENO','Activo'),(280,'15673','SAN MATEO','Activo'),(281,'15676','SAN MIGUEL DE SEMA','Activo'),(282,'15681','SAN PABLO DE BORBUR','Activo'),(283,'15686','SANTANA','Activo'),(284,'15690','SANTA MARIA','Activo'),(285,'15693','SANTA ROSA DE VITERBO','Activo'),(286,'15696','SANTA SOFIA','Activo'),(287,'15720','SATIVANORTE','Activo'),(288,'15723','SATIVASUR','Activo'),(289,'15740','SIACHOQUE','Activo'),(290,'15753','SOATA','Activo'),(291,'15755','SOCOTA','Activo'),(292,'15757','SOCHA','Activo'),(293,'15759','SOGAMOSO','Activo'),(294,'15761','SOMONDOCO','Activo'),(295,'15762','SORA','Activo'),(296,'15763','SOTAQUIRA','Activo'),(297,'15764','SORACA','Activo'),(298,'15774','SUSACON','Activo'),(299,'15776','SUTAMARCHAN','Activo'),(300,'15778','SUTATENZA','Activo'),(301,'15790','TASCO','Activo'),(302,'15798','TENZA','Activo'),(303,'15804','TIBANA','Activo'),(304,'15806','TIBASOSA','Activo'),(305,'15808','TINJACA','Activo'),(306,'15810','TIPACOQUE','Activo'),(307,'15814','TOCA','Activo'),(308,'15816','TOGsI','Activo'),(309,'15820','TOPAGA','Activo'),(310,'15822','TOTA','Activo'),(311,'15832','TUNUNGUA','Activo'),(312,'15835','TURMEQUE','Activo'),(313,'15837','TUTA','Activo'),(314,'15839','TUTAZA','Activo'),(315,'15842','UMBITA','Activo'),(316,'15861','VENTAQUEMADA','Activo'),(317,'15879','VIRACACHA','Activo'),(318,'15897','ZETAQUIRA','Activo'),(319,'17001','MANIZALES','Activo'),(320,'17013','AGUADAS','Activo'),(321,'17042','ANSERMA','Activo'),(322,'17050','ARANZAZU','Activo'),(323,'17088','BELALCAZAR','Activo'),(324,'17174','CHINCHINA','Activo'),(325,'17272','FILADELFIA','Activo'),(326,'17380','LA DORADA','Activo'),(327,'17388','LA MERCED','Activo'),(328,'17433','MANZANARES','Activo'),(329,'17442','MARMATO','Activo'),(330,'17444','MARQUETALIA','Activo'),(331,'17446','MARULANDA','Activo'),(332,'17486','NEIRA','Activo'),(333,'17495','NORCASIA','Activo'),(334,'17513','PACORA','Activo'),(335,'17524','PALESTINA','Activo'),(336,'17541','PENSILVANIA','Activo'),(337,'17614','RIOSUCIO','Activo'),(338,'17616','RISARALDA','Activo'),(339,'17653','SALAMINA','Activo'),(340,'17662','SAMANA','Activo'),(341,'17665','SAN JOSE','Activo'),(342,'17777','SUPIA','Activo'),(343,'17867','VICTORIA','Activo'),(344,'17873','VILLAMARIA','Activo'),(345,'17877','VITERBO','Activo'),(346,'18001','FLORENCIA','Activo'),(347,'18029','ALBANIA','Activo'),(348,'18094','BELEN DE LOS ANDAQUIES','Activo'),(349,'18150','CARTAGENA DEL CHAIRA','Activo'),(350,'18205','CURILLO','Activo'),(351,'18247','EL DONCELLO','Activo'),(352,'18256','EL PAUJIL','Activo'),(353,'18410','LA MONTAÑITA','Activo'),(354,'18460','MILAN','Activo'),(355,'18479','MORELIA','Activo'),(356,'18592','PUERTO RICO','Activo'),(357,'18610','SAN JOSE DEL FRAGUA','Activo'),(358,'18753','SAN VICENTE DEL CAGUAN','Activo'),(359,'18756','SOLANO','Activo'),(360,'18785','SOLITA','Activo'),(361,'18860','VALPARAISO','Activo'),(362,'19001','POPAYAN','Activo'),(363,'19022','ALMAGUER','Activo'),(364,'19050','ARGELIA','Activo'),(365,'19075','BALBOA','Activo'),(366,'19100','BOLIVAR','Activo'),(367,'19110','BUENOS AIRES','Activo'),(368,'19130','CAJIBIO','Activo'),(369,'19137','CALDONO','Activo'),(370,'19142','CALOTO','Activo'),(371,'19212','CORINTO','Activo'),(372,'19256','EL TAMBO','Activo'),(373,'19290','FLORENCIA','Activo'),(374,'19300','GUACHENE','Activo'),(375,'19318','GUAPI','Activo'),(376,'19355','INZA','Activo'),(377,'19364','JAMBALO','Activo'),(378,'19392','LA SIERRA','Activo'),(379,'19397','LA VEGA','Activo'),(380,'19418','LOPEZ','Activo'),(381,'19450','MERCADERES','Activo'),(382,'19455','MIRANDA','Activo'),(383,'19473','MORALES','Activo'),(384,'19513','PADILLA','Activo'),(385,'19517','PAEZ','Activo'),(386,'19532','PATIA','Activo'),(387,'19533','PIAMONTE','Activo'),(388,'19548','PIENDAMO','Activo'),(389,'19573','PUERTO TEJADA','Activo'),(390,'19585','PURACE','Activo'),(391,'19622','ROSAS','Activo'),(392,'19693','SAN SEBASTIAN','Activo'),(393,'19698','SANTANDER DE QUILICHAO','Activo'),(394,'19701','SANTA ROSA','Activo'),(395,'19743','SILVIA','Activo'),(396,'19760','SOTARA','Activo'),(397,'19780','SUAREZ','Activo'),(398,'19785','SUCRE','Activo'),(399,'19807','TIMBIO','Activo'),(400,'19809','TIMBIQUI','Activo'),(401,'19821','TORIBIO','Activo'),(402,'19824','TOTORO','Activo'),(403,'19845','VILLA RICA','Activo'),(404,'20001','VALLEDUPAR','Activo'),(405,'20011','AGUACHICA','Activo'),(406,'20013','AGUSTIN CODAZZI','Activo'),(407,'20032','ASTREA','Activo'),(408,'20045','BECERRIL','Activo'),(409,'20060','BOSCONIA','Activo'),(410,'20175','CHIMICHAGUA','Activo'),(411,'20178','CHIRIGUANA','Activo'),(412,'20228','CURUMANI','Activo'),(413,'20238','EL COPEY','Activo'),(414,'20250','EL PASO','Activo'),(415,'20295','GAMARRA','Activo'),(416,'20310','GONZALEZ','Activo'),(417,'20383','LA GLORIA','Activo'),(418,'20400','LA JAGUA DE IBIRICO','Activo'),(419,'20443','MANAURE','Activo'),(420,'20517','PAILITAS','Activo'),(421,'20550','PELAYA','Activo'),(422,'20570','PUEBLO BELLO','Activo'),(423,'20614','RIO DE ORO','Activo'),(424,'20621','LA PAZ','Activo'),(425,'20710','SAN ALBERTO','Activo'),(426,'20750','SAN DIEGO','Activo'),(427,'20770','SAN MARTIN','Activo'),(428,'20787','TAMALAMEQUE','Activo'),(429,'23001','MONTERIA','Activo'),(430,'23068','AYAPEL','Activo'),(431,'23079','BUENAVISTA','Activo'),(432,'23090','CANALETE','Activo'),(433,'23162','CERETE','Activo'),(434,'23168','CHIMA','Activo'),(435,'23182','CHINU','Activo'),(436,'23189','CIENAGA DE ORO','Activo'),(437,'23300','COTORRA','Activo'),(438,'23350','LA APARTADA','Activo'),(439,'23417','LORICA','Activo'),(440,'23419','LOS CORDOBAS','Activo'),(441,'23464','MOMIL','Activo'),(442,'23466','MONTELIBANO','Activo'),(443,'23500','MOÑITOS','Activo'),(444,'23555','PLANETA RICA','Activo'),(445,'23570','PUEBLO NUEVO','Activo'),(446,'23574','PUERTO ESCONDIDO','Activo'),(447,'23580','PUERTO LIBERTADOR','Activo'),(448,'23586','PURISIMA','Activo'),(449,'23660','SAHAGUN','Activo'),(450,'23670','SAN ANDRES SOTAVENTO','Activo'),(451,'23672','SAN ANTERO','Activo'),(452,'23675','SAN BERNARDO DEL VIENTO','Activo'),(453,'23678','SAN CARLOS','Activo'),(454,'23686','SAN PELAYO','Activo'),(455,'23807','TIERRALTA','Activo'),(456,'23855','VALENCIA','Activo'),(457,'25001','AGUA DE DIOS','Activo'),(458,'25019','ALBAN','Activo'),(459,'25035','ANAPOIMA','Activo'),(460,'25040','ANOLAIMA','Activo'),(461,'25053','ARBELAEZ','Activo'),(462,'25086','BELTRAN','Activo'),(463,'25095','BITUIMA','Activo'),(464,'25099','BOJACA','Activo'),(465,'25120','CABRERA','Activo'),(466,'25123','CACHIPAY','Activo'),(467,'25126','CAJICA','Activo'),(468,'25148','CAPARRAPI','Activo'),(469,'25151','CAQUEZA','Activo'),(470,'25154','CARMEN DE CARUPA','Activo'),(471,'25168','CHAGUANI','Activo'),(472,'25175','CHIA','Activo'),(473,'25178','CHIPAQUE','Activo'),(474,'25181','CHOACHI','Activo'),(475,'25183','CHOCONTA','Activo'),(476,'25200','COGUA','Activo'),(477,'25214','COTA','Activo'),(478,'25224','CUCUNUBA','Activo'),(479,'25245','EL COLEGIO','Activo'),(480,'25258','EL PEÑON','Activo'),(481,'25260','EL ROSAL','Activo'),(482,'25269','FACATATIVA','Activo'),(483,'25279','FOMEQUE','Activo'),(484,'25281','FOSCA','Activo'),(485,'25286','FUNZA','Activo'),(486,'25288','FUQUENE','Activo'),(487,'25290','FUSAGASUGA','Activo'),(488,'25293','GACHALA','Activo'),(489,'25295','GACHANCIPA','Activo'),(490,'25297','GACHETA','Activo'),(491,'25299','GAMA','Activo'),(492,'25307','GIRARDOT','Activo'),(493,'25312','GRANADA','Activo'),(494,'25317','GUACHETA','Activo'),(495,'25320','GUADUAS','Activo'),(496,'25322','GUASCA','Activo'),(497,'25324','GUATAQUI','Activo'),(498,'25326','GUATAVITA','Activo'),(499,'25328','GUAYABAL DE SIQUIMA','Activo'),(500,'25335','GUAYABETAL','Activo'),(501,'25339','GUTIERREZ','Activo'),(502,'25368','JERUSALEN','Activo'),(503,'25372','JUNIN','Activo'),(504,'25377','LA CALERA','Activo'),(505,'25386','LA MESA','Activo'),(506,'25394','LA PALMA','Activo'),(507,'25398','LA PEÑA','Activo'),(508,'25402','LA VEGA','Activo'),(509,'25407','LENGUAZAQUE','Activo'),(510,'25426','MACHETA','Activo'),(511,'25430','MADRID','Activo'),(512,'25436','MANTA','Activo'),(513,'25438','MEDINA','Activo'),(514,'25473','MOSQUERA','Activo'),(515,'25483','NARIÑO','Activo'),(516,'25486','NEMOCON','Activo'),(517,'25488','NILO','Activo'),(518,'25489','NIMAIMA','Activo'),(519,'25491','NOCAIMA','Activo'),(520,'25506','VENECIA','Activo'),(521,'25513','PACHO','Activo'),(522,'25518','PAIME','Activo'),(523,'25524','PANDI','Activo'),(524,'25530','PARATEBUENO','Activo'),(525,'25535','PASCA','Activo'),(526,'25572','PUERTO SALGAR','Activo'),(527,'25580','PULI','Activo'),(528,'25592','QUEBRADANEGRA','Activo'),(529,'25594','QUETAME','Activo'),(530,'25596','QUIPILE','Activo'),(531,'25599','APULO','Activo'),(532,'25612','RICAURTE','Activo'),(533,'25645','SAN ANTONIO DEL TEQUENDAMA','Activo'),(534,'25649','SAN BERNARDO','Activo'),(535,'25653','SAN CAYETANO','Activo'),(536,'25658','SAN FRANCISCO','Activo'),(537,'25662','SAN JUAN DE RIO SECO','Activo'),(538,'25718','SASAIMA','Activo'),(539,'25736','SESQUILE','Activo'),(540,'25740','SIBATE','Activo'),(541,'25743','SILVANIA','Activo'),(542,'25745','SIMIJACA','Activo'),(543,'Activo','SOACHA','Activo'),(544,'25758','SOPO','Activo'),(545,'25769','SUBACHOQUE','Activo'),(546,'25772','SUESCA','Activo'),(547,'25777','SUPATA','Activo'),(548,'25779','SUSA','Activo'),(549,'25781','SUTATAUSA','Activo'),(550,'25785','TABIO','Activo'),(551,'25793','TAUSA','Activo'),(552,'25797','TENA','Activo'),(553,'25799','TENJO','Activo'),(554,'25805','TIBACUY','Activo'),(555,'25807','TIBIRITA','Activo'),(556,'25815','TOCAIMA','Activo'),(557,'25817','TOCANCIPA','Activo'),(558,'25823','TOPAIPI','Activo'),(559,'25839','UBALA','Activo'),(560,'25841','UBAQUE','Activo'),(561,'25843','VILLA DE SAN DIEGO DE UBATE','Activo'),(562,'25845','UNE','Activo'),(563,'25851','UTICA','Activo'),(564,'25862','VERGARA','Activo'),(565,'25867','VIANI','Activo'),(566,'25871','VILLAGOMEZ','Activo'),(567,'25873','VILLAPINZON','Activo'),(568,'25875','VILLETA','Activo'),(569,'25878','VIOTA','Activo'),(570,'25885','YACOPI','Activo'),(571,'25898','ZIPACON','Activo'),(572,'25899','ZIPAQUIRA','Activo'),(573,'27001','QUIBDO','Activo'),(574,'27006','ACANDI','Activo'),(575,'27025','ALTO BAUDO','Activo'),(576,'27050','ATRATO','Activo'),(577,'27073','BAGADO','Activo'),(578,'27075','BAHIA SOLANO','Activo'),(579,'27077','BAJO BAUDO','Activo'),(580,'27099','BOJAYA','Activo'),(581,'27135','EL CANTON DEL SAN PABLO','Activo'),(582,'27150','CARMEN DEL DARIEN','Activo'),(583,'27160','CERTEGUI','Activo'),(584,'27205','CONDOTO','Activo'),(585,'27245','EL CARMEN DE ATRATO','Activo'),(586,'27250','EL LITORAL DEL SAN JUAN','Activo'),(587,'27361','ISTMINA','Activo'),(588,'27372','JURADO','Activo'),(589,'27413','LLORO','Activo'),(590,'27425','MEDIO ATRATO','Activo'),(591,'27430','MEDIO BAUDO','Activo'),(592,'27450','MEDIO SAN JUAN','Activo'),(593,'27491','NOVITA','Activo'),(594,'27495','NUQUI','Activo'),(595,'27580','RIO IRO','Activo'),(596,'27600','RIO QUITO','Activo'),(597,'27615','RIOSUCIO','Activo'),(598,'27660','SAN JOSE DEL PALMAR','Activo'),(599,'27745','SIPI','Activo'),(600,'27787','TADO','Activo'),(601,'27800','UNGUIA','Activo'),(602,'27810','UNION PANAMERICANA','Activo'),(603,'41001','NEIVA','Activo'),(604,'41006','ACEVEDO','Activo'),(605,'41013','AGRADO','Activo'),(606,'41016','AIPE','Activo'),(607,'41020','ALGECIRAS','Activo'),(608,'41026','ALTAMIRA','Activo'),(609,'41078','BARAYA','Activo'),(610,'41132','CAMPOALEGRE','Activo'),(611,'41206','COLOMBIA','Activo'),(612,'41244','ELIAS','Activo'),(613,'41298','GARZON','Activo'),(614,'41306','GIGANTE','Activo'),(615,'41319','GUADALUPE','Activo'),(616,'41349','HOBO','Activo'),(617,'41357','IQUIRA','Activo'),(618,'41359','ISNOS','Activo'),(619,'41378','LA ARGENTINA','Activo'),(620,'41396','LA PLATA','Activo'),(621,'41483','NATAGA','Activo'),(622,'41503','OPORAPA','Activo'),(623,'41518','PAICOL','Activo'),(624,'41524','PALERMO','Activo'),(625,'41530','PALESTINA','Activo'),(626,'41548','PITAL','Activo'),(627,'41551','PITALITO','Activo'),(628,'41615','RIVERA','Activo'),(629,'41660','SALADOBLANCO','Activo'),(630,'41668','SAN AGUSTIN','Activo'),(631,'41676','SANTA MARIA','Activo'),(632,'41770','SUAZA','Activo'),(633,'41791','TARQUI','Activo'),(634,'41797','TESALIA','Activo'),(635,'41799','TELLO','Activo'),(636,'41801','TERUEL','Activo'),(637,'41807','TIMANA','Activo'),(638,'41872','VILLAVIEJA','Activo'),(639,'41885','YAGUARA','Activo'),(640,'44001','RIOHACHA','Activo'),(641,'44035','ALBANIA','Activo'),(642,'44078','BARRANCAS','Activo'),(643,'44090','DIBULLA','Activo'),(644,'44098','DISTRACCION','Activo'),(645,'44110','EL MOLINO','Activo'),(646,'44279','FONSECA','Activo'),(647,'44378','HATONUEVO','Activo'),(648,'44420','LA JAGUA DEL PILAR','Activo'),(649,'44430','MAICAO','Activo'),(650,'44560','MANAURE','Activo'),(651,'44650','SAN JUAN DEL CESAR','Activo'),(652,'44847','URIBIA','Activo'),(653,'44855','URUMITA','Activo'),(654,'44874','VILLANUEVA','Activo'),(655,'47001','SANTA MARTA','Activo'),(656,'47030','ALGARROBO','Activo'),(657,'47053','ARACATACA','Activo'),(658,'47058','ARIGUANI','Activo'),(659,'47161','CERRO SAN ANTONIO','Activo'),(660,'47170','CHIBOLO','Activo'),(661,'47189','CIENAGA','Activo'),(662,'47205','CONCORDIA','Activo'),(663,'47245','EL BANCO','Activo'),(664,'47258','EL PIÑON','Activo'),(665,'47268','EL RETEN','Activo'),(666,'47288','FUNDACION','Activo'),(667,'47318','GUAMAL','Activo'),(668,'47460','NUEVA GRANADA','Activo'),(669,'47541','PEDRAZA','Activo'),(670,'47545','PIJIÑO DEL CARMEN','Activo'),(671,'47551','PIVIJAY','Activo'),(672,'47555','PLATO','Activo'),(673,'47570','PUEBLOVIEJO','Activo'),(674,'47605','REMOLINO','Activo'),(675,'47660','SABANAS DE SAN ANGEL','Activo'),(676,'47675','SALAMINA','Activo'),(677,'47692','SAN SEBASTIAN DE BUENAVISTA','Activo'),(678,'47703','SAN ZENON','Activo'),(679,'47707','SANTA ANA','Activo'),(680,'47720','SANTA BARBARA DE PINTO','Activo'),(681,'47745','SITIONUEVO','Activo'),(682,'47798','TENERIFE','Activo'),(683,'47960','ZAPAYAN','Activo'),(684,'47980','ZONA BANANERA','Activo'),(685,'50001','VILLAVICENCIO','Activo'),(686,'50006','ACACIAS','Activo'),(687,'50110','BARRANCA DE UPIA','Activo'),(688,'50124','CABUYARO','Activo'),(689,'50150','CASTILLA LA NUEVA','Activo'),(690,'50223','CUBARRAL','Activo'),(691,'50226','CUMARAL','Activo'),(692,'50245','EL CALVARIO','Activo'),(693,'50251','EL CASTILLO','Activo'),(694,'50270','EL DORADO','Activo'),(695,'50287','FUENTE DE ORO','Activo'),(696,'50313','GRANADA','Activo'),(697,'50318','GUAMAL','Activo'),(698,'50325','MAPIRIPAN','Activo'),(699,'50330','MESETAS','Activo'),(700,'50350','LA MACARENA','Activo'),(701,'50370','URIBE','Activo'),(702,'50400','LEJANIAS','Activo'),(703,'50450','PUERTO CONCORDIA','Activo'),(704,'50568','PUERTO GAITAN','Activo'),(705,'50573','PUERTO LOPEZ','Activo'),(706,'50577','PUERTO LLERAS','Activo'),(707,'50590','PUERTO RICO','Activo'),(708,'50606','RESTREPO','Activo'),(709,'50680','SAN CARLOS DE GUAROA','Activo'),(710,'50683','SAN JUAN DE ARAMA','Activo'),(711,'50686','SAN JUANITO','Activo'),(712,'50689','SAN MARTIN','Activo'),(713,'50711','VISTAHERMOSA','Activo'),(714,'52001','PASTO','Activo'),(715,'52019','ALBAN','Activo'),(716,'52022','ALDANA','Activo'),(717,'52036','ANCUYA','Activo'),(718,'52051','ARBOLEDA','Activo'),(719,'52079','BARBACOAS','Activo'),(720,'52083','BELEN','Activo'),(721,'52110','BUESACO','Activo'),(722,'52203','COLON','Activo'),(723,'52207','CONSACA','Activo'),(724,'52210','CONTADERO','Activo'),(725,'52215','CORDOBA','Activo'),(726,'52224','CUASPUD','Activo'),(727,'52227','CUMBAL','Activo'),(728,'52233','CUMBITARA','Activo'),(729,'52240','CHACHAGsI','Activo'),(730,'52250','EL CHARCO','Activo'),(731,'52254','EL PEÑOL','Activo'),(732,'52256','EL ROSARIO','Activo'),(733,'52258','EL TABLON DE GOMEZ','Activo'),(734,'52260','EL TAMBO','Activo'),(735,'52287','FUNES','Activo'),(736,'52317','GUACHUCAL','Activo'),(737,'52320','GUAITARILLA','Activo'),(738,'52323','GUALMATAN','Activo'),(739,'52352','ILES','Activo'),(740,'52354','IMUES','Activo'),(741,'52356','IPIALES','Activo'),(742,'52378','LA CRUZ','Activo'),(743,'52381','LA FLORIDA','Activo'),(744,'52385','LA LLANADA','Activo'),(745,'52390','LA TOLA','Activo'),(746,'52399','LA UNION','Activo'),(747,'52405','LEIVA','Activo'),(748,'52411','LINARES','Activo'),(749,'52418','LOS ANDES','Activo'),(750,'52427','MAGsI','Activo'),(751,'52435','MALLAMA','Activo'),(752,'52473','MOSQUERA','Activo'),(753,'52480','NARIÑO','Activo'),(754,'52490','OLAYA HERRERA','Activo'),(755,'52506','OSPINA','Activo'),(756,'52520','FRANCISCO PIZARRO','Activo'),(757,'52540','POLICARPA','Activo'),(758,'52560','POTOSI','Activo'),(759,'52565','PROVIDENCIA','Activo'),(760,'52573','PUERRES','Activo'),(761,'52585','PUPIALES','Activo'),(762,'52612','RICAURTE','Activo'),(763,'52621','ROBERTO PAYAN','Activo'),(764,'52678','SAMANIEGO','Activo'),(765,'52683','SANDONA','Activo'),(766,'52685','SAN BERNARDO','Activo'),(767,'52687','SAN LORENZO','Activo'),(768,'52693','SAN PABLO','Activo'),(769,'52694','SAN PEDRO DE CARTAGO','Activo'),(770,'52696','SANTA BARBARA','Activo'),(771,'52699','SANTACRUZ','Activo'),(772,'52720','SAPUYES','Activo'),(773,'52786','TAMINANGO','Activo'),(774,'52788','TANGUA','Activo'),(775,'52835','SAN ANDRES DE TUMACO','Activo'),(776,'52838','TUQUERRES','Activo'),(777,'52885','YACUANQUER','Activo'),(778,'54001','CUCUTA','Activo'),(779,'54003','ABREGO','Activo'),(780,'54051','ARBOLEDAS','Activo'),(781,'54099','BOCHALEMA','Activo'),(782,'54109','BUCARASICA','Activo'),(783,'54125','CACOTA','Activo'),(784,'54128','CACHIRA','Activo'),(785,'54172','CHINACOTA','Activo'),(786,'54174','CHITAGA','Activo'),(787,'54206','CONVENCION','Activo'),(788,'54223','CUCUTILLA','Activo'),(789,'54239','DURANIA','Activo'),(790,'54245','EL CARMEN','Activo'),(791,'54250','EL TARRA','Activo'),(792,'54261','EL ZULIA','Activo'),(793,'54313','GRAMALOTE','Activo'),(794,'54344','HACARI','Activo'),(795,'54347','HERRAN','Activo'),(796,'54377','LABATECA','Activo'),(797,'54385','LA ESPERANZA','Activo'),(798,'54398','LA PLAYA','Activo'),(799,'54405','LOS PATIOS','Activo'),(800,'54418','LOURDES','Activo'),(801,'54480','MUTISCUA','Activo'),(802,'54498','OCAÑA','Activo'),(803,'54518','PAMPLONA','Activo'),(804,'54520','PAMPLONITA','Activo'),(805,'54553','PUERTO SANTANDER','Activo'),(806,'54599','RAGONVALIA','Activo'),(807,'54660','SALAZAR','Activo'),(808,'54670','SAN CALIXTO','Activo'),(809,'54673','SAN CAYETANO','Activo'),(810,'54680','SANTIAGO','Activo'),(811,'54720','SARDINATA','Activo'),(812,'54743','SILOS','Activo'),(813,'54800','TEORAMA','Activo'),(814,'54810','TIBU','Activo'),(815,'54820','TOLEDO','Activo'),(816,'54871','VILLA CARO','Activo'),(817,'54874','VILLA DEL ROSARIO','Activo'),(818,'63001','ARMENIA','Activo'),(819,'63111','BUENAVISTA','Activo'),(820,'63130','CALARCA','Activo'),(821,'63190','CIRCASIA','Activo'),(822,'63212','CORDOBA','Activo'),(823,'63272','FILANDIA','Activo'),(824,'63302','GENOVA','Activo'),(825,'63401','LA TEBAIDA','Activo'),(826,'63470','MONTENEGRO','Activo'),(827,'63548','PIJAO','Activo'),(828,'63594','QUIMBAYA','Activo'),(829,'63690','SALENTO','Activo'),(830,'66001','PEREIRA','Activo'),(831,'66045','APIA','Activo'),(832,'66075','BALBOA','Activo'),(833,'66088','BELEN DE UMBRIA','Activo'),(834,'66170','DOSQUEBRADAS','Activo'),(835,'66318','GUATICA','Activo'),(836,'66383','LA CELIA','Activo'),(837,'66400','LA VIRGINIA','Activo'),(838,'66440','MARSELLA','Activo'),(839,'66456','MISTRATO','Activo'),(840,'66572','PUEBLO RICO','Activo'),(841,'66594','QUINCHIA','Activo'),(842,'66682','SANTA ROSA DE CABAL','Activo'),(843,'66687','SANTUARIO','Activo'),(844,'68001','BUCARAMANGA','Activo'),(845,'68013','AGUADA','Activo'),(846,'68020','ALBANIA','Activo'),(847,'68051','ARATOCA','Activo'),(848,'68077','BARBOSA','Activo'),(849,'68079','BARICHARA','Activo'),(850,'68081','BARRANCABERMEJA','Activo'),(851,'68092','BETULIA','Activo'),(852,'68101','BOLIVAR','Activo'),(853,'68121','CABRERA','Activo'),(854,'68132','CALIFORNIA','Activo'),(855,'68147','CAPITANEJO','Activo'),(856,'68152','CARCASI','Activo'),(857,'68160','CEPITA','Activo'),(858,'68162','CERRITO','Activo'),(859,'68167','CHARALA','Activo'),(860,'68169','CHARTA','Activo'),(861,'68176','CHIMA','Activo'),(862,'68179','CHIPATA','Activo'),(863,'68190','CIMITARRA','Activo'),(864,'68207','CONCEPCION','Activo'),(865,'68209','CONFINES','Activo'),(866,'68211','CONTRATACION','Activo'),(867,'68217','COROMORO','Activo'),(868,'68229','CURITI','Activo'),(869,'68235','EL CARMEN DE CHUCURI','Activo'),(870,'68245','EL GUACAMAYO','Activo'),(871,'68250','EL PEÑON','Activo'),(872,'68255','EL PLAYON','Activo'),(873,'68264','ENCINO','Activo'),(874,'68266','ENCISO','Activo'),(875,'68271','FLORIAN','Activo'),(876,'68276','FLORIDABLANCA','Activo'),(877,'68296','GALAN','Activo'),(878,'68298','GAMBITA','Activo'),(879,'68307','GIRON','Activo'),(880,'68318','GUACA','Activo'),(881,'68320','GUADALUPE','Activo'),(882,'68322','GUAPOTA','Activo'),(883,'68324','GUAVATA','Activo'),(884,'68327','GsEPSA','Activo'),(885,'68344','HATO','Activo'),(886,'68368','JESUS MARIA','Activo'),(887,'68370','JORDAN','Activo'),(888,'68377','LA BELLEZA','Activo'),(889,'68385','LANDAZURI','Activo'),(890,'68397','LA PAZ','Activo'),(891,'68406','LEBRIJA','Activo'),(892,'68418','LOS SANTOS','Activo'),(893,'68425','MACARAVITA','Activo'),(894,'68432','MALAGA','Activo'),(895,'68444','MATANZA','Activo'),(896,'68464','MOGOTES','Activo'),(897,'68468','MOLAGAVITA','Activo'),(898,'68498','OCAMONTE','Activo'),(899,'68500','OIBA','Activo'),(900,'68502','ONZAGA','Activo'),(901,'68522','PALMAR','Activo'),(902,'68524','PALMAS DEL SOCORRO','Activo'),(903,'68533','PARAMO','Activo'),(904,'68547','PIEDECUESTA','Activo'),(905,'68549','PINCHOTE','Activo'),(906,'68572','PUENTE NACIONAL','Activo'),(907,'68573','PUERTO PARRA','Activo'),(908,'68575','PUERTO WILCHES','Activo'),(909,'68615','RIONEGRO','Activo'),(910,'68655','SABANA DE TORRES','Activo'),(911,'68669','SAN ANDRES','Activo'),(912,'68673','SAN BENITO','Activo'),(913,'68679','SAN GIL','Activo'),(914,'68682','SAN JOAQUIN','Activo'),(915,'68684','SAN JOSE DE MIRANDA','Activo'),(916,'68686','SAN MIGUEL','Activo'),(917,'68689','SAN VICENTE DE CHUCURI','Activo'),(918,'68705','SANTA BARBARA','Activo'),(919,'68720','SANTA HELENA DEL OPON','Activo'),(920,'68745','SIMACOTA','Activo'),(921,'68755','SOCORRO','Activo'),(922,'68770','SUAITA','Activo'),(923,'68773','SUCRE','Activo'),(924,'68780','SURATA','Activo'),(925,'68820','TONA','Activo'),(926,'68855','VALLE DE SAN JOSE','Activo'),(927,'68861','VELEZ','Activo'),(928,'68867','VETAS','Activo'),(929,'68872','VILLANUEVA','Activo'),(930,'68895','ZAPATOCA','Activo'),(931,'70001','SINCELEJO','Activo'),(932,'70110','BUENAVISTA','Activo'),(933,'70124','CAIMITO','Activo'),(934,'70204','COLOSO','Activo'),(935,'70215','COROZAL','Activo'),(936,'70221','COVEÑAS','Activo'),(937,'70230','CHALAN','Activo'),(938,'70233','EL ROBLE','Activo'),(939,'70235','GALERAS','Activo'),(940,'70265','GUARANDA','Activo'),(941,'70400','LA UNION','Activo'),(942,'70418','LOS PALMITOS','Activo'),(943,'70429','MAJAGUAL','Activo'),(944,'70473','MORROA','Activo'),(945,'70508','OVEJAS','Activo'),(946,'70523','PALMITO','Activo'),(947,'70670','SAMPUES','Activo'),(948,'70678','SAN BENITO ABAD','Activo'),(949,'70702','SAN JUAN DE BETULIA','Activo'),(950,'70708','SAN MARCOS','Activo'),(951,'70713','SAN ONOFRE','Activo'),(952,'70717','SAN PEDRO','Activo'),(953,'70742','SAN LUIS DE SINCE','Activo'),(954,'70771','SUCRE','Activo'),(955,'70820','SANTIAGO DE TOLU','Activo'),(956,'70823','TOLU VIEJO','Activo'),(957,'73001','IBAGUE','Activo'),(958,'73024','ALPUJARRA','Activo'),(959,'73026','ALVARADO','Activo'),(960,'73030','AMBALEMA','Activo'),(961,'73043','ANZOATEGUI','Activo'),(962,'73055','ARMERO','Activo'),(963,'73067','ATACO','Activo'),(964,'73124','CAJAMARCA','Activo'),(965,'73148','CARMEN DE APICALA','Activo'),(966,'73152','CASABIANCA','Activo'),(967,'73168','CHAPARRAL','Activo'),(968,'73200','COELLO','Activo'),(969,'73217','COYAIMA','Activo'),(970,'73226','CUNDAY','Activo'),(971,'73236','DOLORES','Activo'),(972,'73268','ESPINAL','Activo'),(973,'73270','FALAN','Activo'),(974,'73275','FLANDES','Activo'),(975,'73283','FRESNO','Activo'),(976,'73319','GUAMO','Activo'),(977,'73347','HERVEO','Activo'),(978,'73349','HONDA','Activo'),(979,'73352','ICONONZO','Activo'),(980,'73408','LERIDA','Activo'),(981,'73411','LIBANO','Activo'),(982,'73443','MARIQUITA','Activo'),(983,'73449','MELGAR','Activo'),(984,'73461','MURILLO','Activo'),(985,'73483','NATAGAIMA','Activo'),(986,'73504','ORTEGA','Activo'),(987,'73520','PALOCABILDO','Activo'),(988,'73547','PIEDRAS','Activo'),(989,'73555','PLANADAS','Activo'),(990,'73563','PRADO','Activo'),(991,'73585','PURIFICACION','Activo'),(992,'73616','RIOBLANCO','Activo'),(993,'73622','RONCESVALLES','Activo'),(994,'73624','ROVIRA','Activo'),(995,'73671','SALDAÑA','Activo'),(996,'73675','SAN ANTONIO','Activo'),(997,'73678','SAN LUIS','Activo'),(998,'73686','SANTA ISABEL','Activo'),(999,'73770','SUAREZ','Activo'),(1000,'73854','VALLE DE SAN JUAN','Activo'),(1001,'73861','VENADILLO','Activo'),(1002,'73870','VILLAHERMOSA','Activo'),(1003,'73873','VILLARRICA','Activo'),(1004,'76001','CALI','Activo'),(1005,'76020','ALCALA','Activo'),(1006,'76036','ANDALUCIA','Activo'),(1007,'76041','ANSERMANUEVO','Activo'),(1008,'76054','ARGELIA','Activo'),(1009,'76100','BOLIVAR','Activo'),(1010,'76109','BUENAVENTURA','Activo'),(1011,'76111','GUADALAJARA DE BUGA','Activo'),(1012,'76113','BUGALAGRANDE','Activo'),(1013,'76122','CAICEDONIA','Activo'),(1014,'76126','CALIMA','Activo'),(1015,'76130','CANDELARIA','Activo'),(1016,'76147','CARTAGO','Activo'),(1017,'76233','DAGUA','Activo'),(1018,'76243','EL AGUILA','Activo'),(1019,'76246','EL CAIRO','Activo'),(1020,'76248','EL CERRITO','Activo'),(1021,'76250','EL DOVIO','Activo'),(1022,'76275','FLORIDA','Activo'),(1023,'76306','GINEBRA','Activo'),(1024,'76318','GUACARI','Activo'),(1025,'76364','JAMUNDI','Activo'),(1026,'76377','LA CUMBRE','Activo'),(1027,'76400','LA UNION','Activo'),(1028,'76403','LA VICTORIA','Activo'),(1029,'76497','OBANDO','Activo'),(1030,'76520','PALMIRA','Activo'),(1031,'76563','PRADERA','Activo'),(1032,'76606','RESTREPO','Activo'),(1033,'76616','RIOFRIO','Activo'),(1034,'76622','ROLDANILLO','Activo'),(1035,'76670','SAN PEDRO','Activo'),(1036,'76736','SEVILLA','Activo'),(1037,'76823','TORO','Activo'),(1038,'76828','TRUJILLO','Activo'),(1039,'76834','TULUA','Activo'),(1040,'76845','ULLOA','Activo'),(1041,'76863','VERSALLES','Activo'),(1042,'76869','VIJES','Activo'),(1043,'76890','YOTOCO','Activo'),(1044,'76892','YUMBO','Activo'),(1045,'76895','ZARZAL','Activo'),(1046,'81001','ARAUCA','Activo'),(1047,'81065','ARAUQUITA','Activo'),(1048,'81220','CRAVO NORTE','Activo'),(1049,'81300','FORTUL','Activo'),(1050,'81591','PUERTO RONDON','Activo'),(1051,'81736','SARAVENA','Activo'),(1052,'81794','TAME','Activo'),(1053,'85001','YOPAL','Activo'),(1054,'85010','AGUAZUL','Activo'),(1055,'85015','CHAMEZA','Activo'),(1056,'85125','HATO COROZAL','Activo'),(1057,'85136','LA SALINA','Activo'),(1058,'85139','MANI','Activo'),(1059,'85162','MONTERREY','Activo'),(1060,'85225','NUNCHIA','Activo'),(1061,'85230','OROCUE','Activo'),(1062,'85250','PAZ DE ARIPORO','Activo'),(1063,'85263','PORE','Activo'),(1064,'85279','RECETOR','Activo'),(1065,'85300','SABANALARGA','Activo'),(1066,'85315','SACAMA','Activo'),(1067,'85325','SAN LUIS DE PALENQUE','Activo'),(1068,'85400','TAMARA','Activo'),(1069,'85410','TAURAMENA','Activo'),(1070,'85430','TRINIDAD','Activo'),(1071,'85440','VILLANUEVA','Activo'),(1072,'86001','MOCOA','Activo'),(1073,'86219','COLON','Activo'),(1074,'86320','ORITO','Activo'),(1075,'86568','PUERTO ASIS','Activo'),(1076,'86569','PUERTO CAICEDO','Activo'),(1077,'86571','PUERTO GUZMAN','Activo'),(1078,'86573','LEGUIZAMO','Activo'),(1079,'86749','SIBUNDOY','Activo'),(1080,'86755','SAN FRANCISCO','Activo'),(1081,'86757','SAN MIGUEL','Activo'),(1082,'86760','SANTIAGO','Activo'),(1083,'86865','VALLE DEL GUAMUEZ','Activo'),(1084,'86885','VILLAGARZON','Activo'),(1085,'88001','SAN ANDRES','Activo'),(1086,'88564','PROVIDENCIA','Activo'),(1087,'91001','LETICIA','Activo'),(1088,'91263','EL ENCANTO','Activo'),(1089,'91405','LA CHORRERA','Activo'),(1090,'91407','LA PEDRERA','Activo'),(1091,'91430','LA VICTORIA','Activo'),(1092,'91460','MIRITI - PARANA','Activo'),(1093,'91530','PUERTO ALEGRIA','Activo'),(1094,'91536','PUERTO ARICA','Activo'),(1095,'91540','PUERTO NARIÑO','Activo'),(1096,'91669','PUERTO SANTANDER','Activo'),(1097,'91798','TARAPACA','Activo'),(1098,'94001','INIRIDA','Activo'),(1099,'94343','BARRANCO MINAS','Activo'),(1100,'94663','MAPIRIPANA','Activo'),(1101,'94883','SAN FELIPE','Activo'),(1102,'94884','PUERTO COLOMBIA','Activo'),(1103,'94885','LA GUADALUPE','Activo'),(1104,'94886','CACAHUAL','Activo'),(1105,'94887','PANA PANA','Activo'),(1106,'94888','MORICHAL','Activo'),(1107,'95001','SAN JOSE DEL GUAVIARE','Activo'),(1108,'95015','CALAMAR','Activo'),(1109,'95025','EL RETORNO','Activo'),(1110,'95200','MIRAFLORES','Activo'),(1111,'97001','MITU','Activo'),(1112,'97161','CARURU','Activo'),(1113,'97511','PACOA','Activo'),(1114,'97666','TARAIRA','Activo'),(1115,'97777','PAPUNAUA','Activo'),(1116,'97889','YAVARATE','Activo'),(1117,'99001','PUERTO CARREÑO','Activo'),(1118,'99524','LA PRIMAVERA','Activo'),(1119,'99624','SANTA ROSALIA','Activo'),(1120,'99773','CUMARIBO','Activo'),(1121,'Activo','Ciudadad Edubin2020','Inactiv');

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
  PRIMARY KEY (`idCliente`),
  KEY `cliente_ciudad` (`cliente_ciudad`),
  CONSTRAINT `cliente_ibfk_1` FOREIGN KEY (`cliente_ciudad`) REFERENCES `ciudad` (`idCiudad`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

/*Data for the table `cliente` */

insert  into `cliente`(`idCliente`,`cliente_tpId`,`cliente_nDocument`,`cliente_dv`,`cliente_estado`,`cliente_nombre`,`cliente_apellido`,`cliente_actEco`,`cliente_direccion`,`cliente_telefono`,`cliente_ciudad`,`cliente_tpCliente`,`cliente_consecutivo`) values (9,'Cédula de ciudadanía',1003652439,'124566','activo','Juan Andres','Cadena Martin','No aplica','12546','100365248',149,'Importador','CL-9');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `cliente_bodega` */

/*Table structure for table `despacho` */

DROP TABLE IF EXISTS `despacho`;

CREATE TABLE `despacho` (
  `idDespacho` int(11) NOT NULL AUTO_INCREMENT,
  `despacho_estado` varchar(10) NOT NULL DEFAULT 'activo',
  `despacho_fechaDs` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
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
  KEY `despacho_idCliente` (`despacho_idCliente`),
  CONSTRAINT `despacho_ibfk_1` FOREIGN KEY (`despacho_idBodega`) REFERENCES `bodega` (`idBodega`),
  CONSTRAINT `despacho_ibfk_2` FOREIGN KEY (`despacho_idCliente`) REFERENCES `cliente` (`idCliente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `despacho` */

/*Table structure for table `entrada` */

DROP TABLE IF EXISTS `entrada`;

CREATE TABLE `entrada` (
  `idEntrada` int(11) NOT NULL AUTO_INCREMENT,
  `entrada_estado` varchar(10) NOT NULL DEFAULT 'activo',
  `entrada_fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `entrada_observaciones` varchar(100) NOT NULL,
  `entrada_idCliente` int(11) NOT NULL,
  `entrada_nombrePersona` varchar(30) DEFAULT NULL,
  `entrada_cedulaPersona` varchar(15) DEFAULT NULL,
  `entrada_placaPersona` varchar(7) DEFAULT NULL,
  `entrada_consecutivo` varchar(20) DEFAULT NULL,
  `entrada_idBodega` int(11) DEFAULT NULL,
  PRIMARY KEY (`idEntrada`),
  KEY `entrada_idBodega` (`entrada_idBodega`),
  KEY `entrada_idCliente` (`entrada_idCliente`),
  CONSTRAINT `entrada_ibfk_1` FOREIGN KEY (`entrada_idBodega`) REFERENCES `bodega` (`idBodega`),
  CONSTRAINT `entrada_ibfk_2` FOREIGN KEY (`entrada_idCliente`) REFERENCES `cliente` (`idCliente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `entrada` */

/*Table structure for table `historial` */

DROP TABLE IF EXISTS `historial`;

CREATE TABLE `historial` (
  `id_historial` int(11) NOT NULL AUTO_INCREMENT,
  `historial_tipoAccion` varchar(50) NOT NULL,
  `historial_tablaAccion` varchar(15) NOT NULL,
  `historial_idAccion` varchar(20) NOT NULL,
  `historial_userAccion` varchar(20) NOT NULL,
  `historial_fechaAccion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `historial_idBodega` int(11) NOT NULL,
  PRIMARY KEY (`id_historial`),
  KEY `historial_idBodega` (`historial_idBodega`),
  CONSTRAINT `historial_ibfk_1` FOREIGN KEY (`historial_idBodega`) REFERENCES `bodega` (`idBodega`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `historial` */

/*Table structure for table `imagen` */

DROP TABLE IF EXISTS `imagen`;

CREATE TABLE `imagen` (
  `id_imagen` int(11) NOT NULL AUTO_INCREMENT,
  `imagen_nombre` varchar(30) NOT NULL,
  `imagen_tipo` varchar(20) NOT NULL,
  `imagen_size` varchar(15) NOT NULL,
  `imagen_fecha` date NOT NULL,
  `producto_id` int(11) NOT NULL,
  `entrada_firmaId` int(11) DEFAULT NULL,
  `despacho_firmaId` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_imagen`),
  KEY `entrada_firmaId` (`entrada_firmaId`),
  KEY `despacho_firmaId` (`despacho_firmaId`),
  CONSTRAINT `imagen_ibfk_1` FOREIGN KEY (`entrada_firmaId`) REFERENCES `entrada` (`idEntrada`),
  CONSTRAINT `imagen_ibfk_2` FOREIGN KEY (`despacho_firmaId`) REFERENCES `despacho` (`idDespacho`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `imagen` */

/*Table structure for table `kit` */

DROP TABLE IF EXISTS `kit`;

CREATE TABLE `kit` (
  `idKit` int(11) NOT NULL AUTO_INCREMENT,
  `kit_idCliente` int(11) NOT NULL,
  `kit_idBodega` int(11) NOT NULL,
  `kit_estado` varchar(12) DEFAULT 'activo',
  `kit_consecutivo` varchar(13) DEFAULT NULL,
  `kit_nombre` varchar(30) DEFAULT NULL,
  `kit_peso` double(4,1) NOT NULL,
  PRIMARY KEY (`idKit`),
  KEY `kit_idCliente` (`kit_idCliente`),
  KEY `kit_idBodega` (`kit_idBodega`),
  CONSTRAINT `kit_ibfk_1` FOREIGN KEY (`kit_idCliente`) REFERENCES `cliente` (`idCliente`),
  CONSTRAINT `kit_ibfk_2` FOREIGN KEY (`kit_idBodega`) REFERENCES `bodega` (`idBodega`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `kit` */

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
  `producto_uniPeso` varchar(10) NOT NULL DEFAULT 'KiloGramo',
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Data for the table `producto` */

insert  into `producto`(`idProducto`,`producto_codigo`,`producto_nombre`,`producto_rotacion`,`producto_diasAviso`,`producto_minimo`,`producto_maximo`,`producto_alerta`,`producto_precio`,`producto_descripcion`,`producto_uniCant`,`producto_cantidad`,`producto_peso`,`producto_uniPeso`,`producto_modelo`,`producto_serial`,`producto_lote`,`producto_marca`,`producto_fechaVenc`,`producto_nContenedor`,`producto_ancho`,`producto_alto`,`producto_largo`,`producto_uniDimen`,`producto_idCliente`,`producto_estado`,`producto_cantidadAlis`,`producto_RFID`,`producto_cantidadBlock`,`producto_consecutivo`,`producto_idBodega`,`producto_subInventario`) values (1,'1585','Arróz para Moler','A',23,34,45,133,3465,'No aplica','Unitaria',0,22.5,'KiloGramo','No aplica','3434','4345','45','2022-08-26','2456343',34.0,123.0,468.0,'MiliMetros',9,'activo',0,'34674',0,'PR-1',NULL,'No alica'),(2,'1212','CALCILECHE SOMEX 12 ORGANICOS X 10 KILOS BOLSA','A',453453,1,854,455,453453,'5345345','Caja',0,44.5,'KiloGramo','24','5345','11255','452','2022-08-26','3453453',45.0,5345.0,4552.0,'MiliMetros',9,'activo',0,'4562',0,'PR-2',NULL,'452');

/*Table structure for table `producto_alistado` */

DROP TABLE IF EXISTS `producto_alistado`;

CREATE TABLE `producto_alistado` (
  `producto_alistado_id` int(11) NOT NULL AUTO_INCREMENT,
  `producto_alistado_idProducto` int(11) DEFAULT NULL,
  `producto_alistado_idKit` int(11) DEFAULT NULL,
  `producto_alistado_idAlistado` int(11) NOT NULL,
  `producto_alistado_cantidad` int(11) NOT NULL,
  `producto_alistado_CodeIngreso` int(11) DEFAULT 0,
  PRIMARY KEY (`producto_alistado_id`),
  KEY `producto_alistado_idAlistado` (`producto_alistado_idAlistado`),
  KEY `producto_alistado_idProducto` (`producto_alistado_idProducto`),
  KEY `producto_alistado_idKit` (`producto_alistado_idKit`),
  CONSTRAINT `producto_alistado_ibfk_1` FOREIGN KEY (`producto_alistado_idAlistado`) REFERENCES `alistado` (`idAlistado`),
  CONSTRAINT `producto_alistado_ibfk_2` FOREIGN KEY (`producto_alistado_idProducto`) REFERENCES `producto` (`idProducto`),
  CONSTRAINT `producto_alistado_ibfk_3` FOREIGN KEY (`producto_alistado_idKit`) REFERENCES `kit` (`idKit`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `producto_alistado` */

/*Table structure for table `producto_bodega` */

DROP TABLE IF EXISTS `producto_bodega`;

CREATE TABLE `producto_bodega` (
  `idProducto_bodega` int(11) NOT NULL AUTO_INCREMENT,
  `producto_bodega_idProducto` int(11) NOT NULL,
  `producto_bodega_idBodega` int(11) NOT NULL DEFAULT 1,
  `producto_bodega_cantidad` int(11) NOT NULL DEFAULT 0,
  `producto_bodega_cantidadAlis` int(11) DEFAULT 0,
  `producto_bodega_cantidadBlock` int(11) DEFAULT 0,
  `producto_bodega_fechaIngreso` date DEFAULT NULL,
  `producto_bodega_estado` varchar(10) DEFAULT 'activo',
  `producto_bodega_ubicacion` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`idProducto_bodega`),
  KEY `producto_bodega_idProducto` (`producto_bodega_idProducto`),
  KEY `producto_bodega_idBodega` (`producto_bodega_idBodega`),
  CONSTRAINT `producto_bodega_ibfk_1` FOREIGN KEY (`producto_bodega_idProducto`) REFERENCES `producto` (`idProducto`),
  CONSTRAINT `producto_bodega_ibfk_2` FOREIGN KEY (`producto_bodega_idBodega`) REFERENCES `bodega` (`idBodega`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `producto_bodega` */

insert  into `producto_bodega`(`idProducto_bodega`,`producto_bodega_idProducto`,`producto_bodega_idBodega`,`producto_bodega_cantidad`,`producto_bodega_cantidadAlis`,`producto_bodega_cantidadBlock`,`producto_bodega_fechaIngreso`,`producto_bodega_estado`,`producto_bodega_ubicacion`) values (1,1,1,0,0,0,NULL,'activo',NULL),(2,2,1,0,0,0,NULL,'activo',NULL);

/*Table structure for table `producto_despacho` */

DROP TABLE IF EXISTS `producto_despacho`;

CREATE TABLE `producto_despacho` (
  `producto_despacho_id` int(11) NOT NULL AUTO_INCREMENT,
  `producto_despacho_idProducto` int(11) DEFAULT NULL,
  `producto_despacho_idDespacho` int(11) DEFAULT NULL,
  `producto_despacho_cantidad` int(11) DEFAULT NULL,
  `producto_despacho_idKit` int(11) DEFAULT NULL,
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `producto_ingresado` */

/*Table structure for table `producto_kit` */

DROP TABLE IF EXISTS `producto_kit`;

CREATE TABLE `producto_kit` (
  `idProducto_kit` int(11) NOT NULL AUTO_INCREMENT,
  `producto_kit_idProducto` int(11) NOT NULL,
  `producto_kit_idKit` int(11) NOT NULL,
  `producto_kit_cantidad` int(11) DEFAULT NULL,
  PRIMARY KEY (`idProducto_kit`),
  KEY `producto_kit_idProducto` (`producto_kit_idProducto`),
  KEY `producto_kit_idKit` (`producto_kit_idKit`),
  CONSTRAINT `producto_kit_ibfk_1` FOREIGN KEY (`producto_kit_idProducto`) REFERENCES `producto` (`idProducto`),
  CONSTRAINT `producto_kit_ibfk_2` FOREIGN KEY (`producto_kit_idKit`) REFERENCES `kit` (`idKit`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `producto_kit` */

/*Table structure for table `rol` */

DROP TABLE IF EXISTS `rol`;

CREATE TABLE `rol` (
  `idRol` int(11) NOT NULL AUTO_INCREMENT,
  `rol_nombre` varchar(20) NOT NULL,
  `rol_estado` varchar(20) DEFAULT 'activo',
  PRIMARY KEY (`idRol`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `rol` */

insert  into `rol`(`idRol`,`rol_nombre`,`rol_estado`) values (1,'Administrador','activo'),(2,'Coordinador','activo'),(3,'Operativo','activo'),(4,'Invitado','activo');

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
  `tarea_idBodega` int(11) DEFAULT NULL,
  PRIMARY KEY (`idTarea`),
  KEY `tarea_usuario` (`tarea_usuario`),
  KEY `tarea_idDespacho` (`tarea_idDespacho`),
  KEY `tarea_idEntrada` (`tarea_idEntrada`),
  KEY `tarea_idProducto` (`tarea_idProducto`),
  KEY `tarea_idBodega` (`tarea_idBodega`),
  CONSTRAINT `tarea_ibfk_1` FOREIGN KEY (`tarea_usuario`) REFERENCES `usuario` (`idUsuario`),
  CONSTRAINT `tarea_ibfk_2` FOREIGN KEY (`tarea_idDespacho`) REFERENCES `despacho` (`idDespacho`),
  CONSTRAINT `tarea_ibfk_3` FOREIGN KEY (`tarea_idEntrada`) REFERENCES `entrada` (`idEntrada`),
  CONSTRAINT `tarea_ibfk_4` FOREIGN KEY (`tarea_idProducto`) REFERENCES `producto` (`idProducto`),
  CONSTRAINT `tarea_ibfk_5` FOREIGN KEY (`tarea_idBodega`) REFERENCES `bodega` (`idBodega`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `tarea` */

/*Table structure for table `usuario` */

DROP TABLE IF EXISTS `usuario`;

CREATE TABLE `usuario` (
  `idUsuario` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_tDocument` varchar(25) DEFAULT NULL,
  `usuario_documento` varchar(15) DEFAULT NULL,
  `usuario_fecha` date DEFAULT NULL,
  `usuario_telefono` varchar(15) DEFAULT NULL,
  `usuario_nombre` varchar(41) DEFAULT NULL,
  `usuario_apellido` varchar(70) DEFAULT NULL,
  `usuario_correo` varchar(45) DEFAULT NULL,
  `usuario_password` varchar(80) NOT NULL,
  `usuario_direccion` varchar(70) DEFAULT NULL,
  `last_session` datetime NOT NULL,
  `usuario_estado` varchar(8) DEFAULT 'activo',
  `usuario_idBodega` int(11) NOT NULL,
  `usuario_idRol` int(25) NOT NULL,
  `usuario_consecutivo` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`idUsuario`),
  KEY `usuario_idBodega` (`usuario_idBodega`),
  KEY `usuario_idRol` (`usuario_idRol`),
  CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`usuario_idBodega`) REFERENCES `bodega` (`idBodega`),
  CONSTRAINT `usuario_ibfk_2` FOREIGN KEY (`usuario_idRol`) REFERENCES `rol` (`idRol`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `usuario` */

insert  into `usuario`(`idUsuario`,`usuario_tDocument`,`usuario_documento`,`usuario_fecha`,`usuario_telefono`,`usuario_nombre`,`usuario_apellido`,`usuario_correo`,`usuario_password`,`usuario_direccion`,`last_session`,`usuario_estado`,`usuario_idBodega`,`usuario_idRol`,`usuario_consecutivo`) values (1,'0000000000','0000000000',NULL,NULL,'Administrador',NULL,'admin@gmail.com','cVRveXpwSEZmTEJsWGpoWGwzYmxhUT09',NULL,'0000-00-00 00:00:00','activo',1,1,'US-1');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
