-- MySQL dump 10.13  Distrib 8.0.41, for Win64 (x86_64)
--
-- Host: localhost    Database: iii_inventory
-- ------------------------------------------------------
-- Server version	8.0.41

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `client`
--

DROP TABLE IF EXISTS `client`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `client` (
  `client_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `company` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone` bigint DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`client_id`)
) ENGINE=InnoDB AUTO_INCREMENT=286 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client`
--

LOCK TABLES `client` WRITE;
/*!40000 ALTER TABLE `client` DISABLE KEYS */;
INSERT INTO `client` VALUES (258,'Jamesen De la paz','Youtube','12345',123,'jmsndlpz@gmail.com'),(259,'Richell','Youtube','12345',1242,'obligado.richell26@gmail.com'),(260,'Joseph Domingo','ACLC Manila','NU Laguna',1234,'123@gmail.com'),(263,'Ejay Cabasag','CNX','San Lazaro',1234,'Ejay@gmail.com'),(264,'Leo Valderama','Northbay','Pritil',1234,'angelotrias95@gmail.com'),(265,'Angelo Jeric B. Trias','Tubig ng Langit','429 Coral St. Tondo  Manila',9663879940,'angelotrias95@gmail.com'),(266,'Test','Test','test',123,'Louie@gmail.com'),(267,'da','ad','da',123,'dada@gmail.com'),(275,'dada','dada','123',1221,'Louie@gmail.com'),(278,'Patrick','ACLC Manila','Legarda',9162002,'patrick.toledo@ama.edu.ph'),(279,'geo','geo','123',123,'geojomoc@gmail.com'),(280,'Joseph','NU Laguna','Cavite',123456,'angelotrias95@gmail.com'),(281,'Joseph Domingo','NU Laguna','1234',123,'angelotrias95@gmail.com'),(282,'Emel John Cabasag','CNX','San Lazaro',12345,'johncabasag10@gmail.com'),(283,'Emel John Cabasag','CNX','San Lazaro',12345,'johncabasag10@gmail.com'),(284,'Emel John Cabasag','CNX','San Lazaro',12345,'johncabasag10@gmail.com'),(285,'test','test','429 Coral St. Tondo  Manila',123,'Northbay@gmail.com');
/*!40000 ALTER TABLE `client` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-05-05 11:07:22
