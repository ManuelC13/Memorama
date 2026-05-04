-- MySQL dump 10.13  Distrib 5.6.24, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: memorama
-- ------------------------------------------------------
-- Server version	5.6.26-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `materias`
--

DROP TABLE IF EXISTS `materias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `materias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `materias`
--

LOCK TABLES `materias` WRITE;
/*!40000 ALTER TABLE `materias` DISABLE KEYS */;
INSERT INTO `materias` VALUES (1,'filosofia cuantica'),(2,'Semat');
/*!40000 ALTER TABLE `materias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `parejas`
--

DROP TABLE IF EXISTS `parejas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `parejas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idmateria` int(11) NOT NULL,
  `concepto` varchar(100) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idmateria_idx` (`idmateria`),
  CONSTRAINT `idmateria` FOREIGN KEY (`idmateria`) REFERENCES `materias` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `parejas`
--

LOCK TABLES `parejas` WRITE;
/*!40000 ALTER TABLE `parejas` DISABLE KEYS */;
INSERT INTO `parejas` VALUES (1,1,'concepto1','descripcion1'),(2,1,'concepto2','descripcion2'),(3,1,'pesiria','cantollanes'),(4,1,'concepto1','descripcion1'),(5,1,'concepto2','descripcion2'),(6,1,'pesiria','cantollanes'),(7,1,'concepto1','descripcion1'),(8,1,'concepto2','descripcion2'),(9,1,'pesiria','cantollanes'),(10,2,'Semat','Software Engineering Method and Theory.'),(11,2,'Alphas','Representations of the essential things to work with.'),(12,2,'Activity Spaces','Representations of the essential things to do.'),(13,2,'Customer','Area of concern the team needs to understand the stakeholders and the opportunity to be addressed'),(14,2,'Solution','Area of concern the team needs to establish a share understanding of the requirements, and implement, build, test, deploy and support a software system.'),(15,2,'Endeavor','Area of concern the team and its way-of-working have to be formed, and the work has to be done.'),(16,2,'Opportunity','The set of circumstances that makes it appropriate to develop or change a software system.'),(17,2,'Stakeholders','The people, groups, or organizations who affect or are affected by a software system.'),(18,2,'Requirements','What the software system must do to address the opportunity and satisfy the stakeholders.'),(19,2,'Software System','A system made up of software, hardware, and data that provides its primary value by the execution of the software.'),(20,2,'Work','Activity involving mental or physical effort done in order to achieve a result.'),(21,2,'Team','The group of people actively engaged in the development, maintenance, delivery and support of a specific software system.'),(22,2,'Way of work','The tailored set of practices and tools used by a team to guide and support their work.'),(23,2,'Stakeholder Representation','This competency encapsulates the ability to gather, communicate, and balance the needs of other stakeholders, and accurately represent their views.'),(24,2,'Analysis','This competency encapsulates the ability to understand opportunities and their related stakeholder needs, and transform them into an agreed upon and consistent set of  requirements.'),(25,2,'Development','This competency encapsulates the ability to design and program effective software systems following the standards and norms agreed upon by the team.'),(26,2,'Testing','This competency encapsulates the ability to test a system, verifying that it is usable and that it meets the requirements.'),(27,2,'Leadership','This competency enable a person to inspire and motivate a group of people to achieve a successful conclusion to their work and to meet their objectives.'),(28,2,'Management','This competency encapsulates the ability to coordinate, plan, and track the work done by a team.');
/*!40000 ALTER TABLE `parejas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL DEFAULT 'usuario',
  `tipo` int(1) NOT NULL DEFAULT '0',
  `clave` varchar(100) NOT NULL DEFAULT 'memopass',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES (1,'pepe',0,'camello');
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-04-23 17:45:32
