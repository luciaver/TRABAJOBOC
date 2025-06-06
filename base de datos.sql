/*
SQLyog Community v13.3.0 (64 bit)
MySQL - 5.7.24 : Database - practicalmgsi
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`practicalmgsi` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;

USE `practicalmgsi`;

/*Table structure for table `alergenos` */

DROP TABLE IF EXISTS `alergenos`;

CREATE TABLE `alergenos` (
  `id` int(11) NOT NULL,
  `icono` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `descripcion` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Data for the table `alergenos` */

insert  into `alergenos`(`id`,`icono`,`nombre`,`descripcion`) values 
(1,'?','Gluten','Presente en el pan'),
(2,'?','Lactosa','Presente en queso y mayonesa'),
(3,'?','Frutos secos','Puede contener trazas');

/*Table structure for table `alumno` */

DROP TABLE IF EXISTS `alumno`;

CREATE TABLE `alumno` (
  `email` varchar(100) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `alta` enum('true','false') DEFAULT NULL,
  `alergenos` varchar(350) DEFAULT NULL,
  `curso` enum('1ºESO','2ºESO','3ºESO','4ºESO','Grado Medio 1º año','Grado Medio 2º año') DEFAULT NULL,
  PRIMARY KEY (`email`),
  CONSTRAINT `alumno_ibfk_1` FOREIGN KEY (`email`) REFERENCES `usuario` (`email`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Data for the table `alumno` */

insert  into `alumno`(`email`,`nombre`,`alta`,`alergenos`,`curso`) values 
('juan@alumno.com','Juan López','false','Frutos secos','Grado Medio 1º año'),
('maria@alumno.com','María Gómez','true','Gluten,Lactosa','4ºESO');

/*Table structure for table `bocadillos` */

DROP TABLE IF EXISTS `bocadillos`;

CREATE TABLE `bocadillos` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(250) DEFAULT NULL,
  `coste` decimal(5,2) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `estado` enum('CALIENTE','FRIO') DEFAULT NULL,
  'alergenos' varchar (50),
  'ingredientes' varchar (50),
  
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Data for the table `bocadillos` */

insert  into `bocadillos`(`id`,`descripcion`,`coste`,`nombre`,`estado`) values 
(1,'Bocadillo de jamón y queso con pan integral',3.50,'Jamón y Queso','FRIO'),
(2,'Bocadillo vegetal con huevo y lechuga',4.00,'Vegetal','CALIENTE'),
(3,'Bocadillo de atún con mayonesa',3.80,'Atún Mayo','FRIO');

/*Table structure for table `bocadillos_alergenos` */

DROP TABLE IF EXISTS `bocadillos_alergenos`;

CREATE TABLE `bocadillos_alergenos` (
  `id_bocadillos` int(11) NOT NULL,
  `id_alergenos` int(11) NOT NULL,
  PRIMARY KEY (`id_bocadillos`,`id_alergenos`),
  KEY `id_alergenos` (`id_alergenos`),
  CONSTRAINT `bocadillos_alergenos_ibfk_1` FOREIGN KEY (`id_bocadillos`) REFERENCES `bocadillos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `bocadillos_alergenos_ibfk_2` FOREIGN KEY (`id_alergenos`) REFERENCES `alergenos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Data for the table `bocadillos_alergenos` */

insert  into `bocadillos_alergenos`(`id_bocadillos`,`id_alergenos`) values 
(1,1),
(3,1),
(1,2),
(3,2),
(2,3);

/*Table structure for table `pedidos` */

DROP TABLE IF EXISTS `pedidos`;

CREATE TABLE `pedidos` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nombre_bocadillo` VARCHAR(100) NOT NULL,
  `precio` DECIMAL(5,2) NOT NULL,
  `estado` ENUM('activo', 'cancelado') DEFAULT 'activo',
  `fecha_pedido` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `fecha_cancelado` DATETIME NULL,
  `email_alumno` VARCHAR(100),
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_pedidos_email` FOREIGN KEY (`email_alumno`) REFERENCES `alumno`(`email`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Data for the table `pedidos` */

insert  into `pedidos`(`id`,`estado`) values 
(1,'NO RETIRADO'),
(2,'RETIRADO'),
(3,'NO RETIRADO');

/*Table structure for table `usuario` */

DROP TABLE IF EXISTS `usuario`;

CREATE TABLE `usuario` (
  `email` varchar(100) NOT NULL,
  `PASSWORD` varchar(255) DEFAULT NULL,
  `rol` enum('Alumno','Cocina','Admin') DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Data for the table `usuario` */

insert  into `usuario`(`email`,`PASSWORD`,`rol`) values 
('admin@sistema.com','admin000','Admin'),
('chef@cocina.com','cocina789','Cocina'),
('juan@alumno.com','clave456','Alumno'),
('maria@alumno.com','clave123','Alumno');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
