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
-- Table structure for table `stocks`
--

DROP TABLE IF EXISTS `stocks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stocks` (
  `stock_id` int NOT NULL AUTO_INCREMENT,
  `item_name` varchar(255) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `quantity` int NOT NULL DEFAULT '0',
  `min_stocks` int DEFAULT NULL,
  `max_stocks` int DEFAULT NULL,
  `last_updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `barcode` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`stock_id`),
  UNIQUE KEY `barcode` (`barcode`)
) ENGINE=InnoDB AUTO_INCREMENT=10034 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stocks`
--

LOCK TABLES `stocks` WRITE;
/*!40000 ALTER TABLE `stocks` DISABLE KEYS */;
INSERT INTO `stocks` VALUES (10018,'Tarpaulin','Printing Materials',93,100,200,'2025-05-03 03:55:46','III010018'),(10019,'Sticker (Matte)','Printing Materials',100,50,100,'2025-05-04 16:33:43','III010019'),(10020,'Sticker (Lamination)','Printing Materials',62,50,100,'2025-05-03 15:37:09','III010020'),(10021,'Sticker (Gloss)','Printing Materials',67,50,100,'2025-05-03 15:37:09','III010021'),(10022,'Fabric Banner','Printing Materials',38,5,10,'2025-05-04 16:36:46','III010022'),(10023,'Duratrans ','Printing Materials',102,40,80,'2025-05-03 15:37:09','III010023'),(10024,'Sintra','Printing Materials',100,50,100,'2025-05-03 15:37:09','III010024'),(10025,'Ink (C - Cyan)','Printing Materials',31,8,16,'2025-05-03 15:37:09','III010025'),(10026,'Ink (M - Magenta)','Printing Materials',-5,8,16,'2025-05-03 15:38:00','III010026'),(10027,'Ink (Y - Yellow) ','Printing Materials',109,8,16,'2025-05-03 09:12:41','III010027'),(10028,'Ink (K - Black)','Printing Materials',111,8,16,'2025-05-03 09:12:41','III010028'),(10029,'Cleaning Solution','Printing Materials',307,3,6,'2025-05-03 09:12:41','III010029'),(10030,'Cyno','Printing Materials',209,3,6,'2025-05-03 09:12:41','III010030'),(10032,'Mouse','Tools & Accessories',0,5,10,'2025-04-24 04:58:44','III010032'),(10033,'Test','Printing Materials',0,10,20,'2025-05-03 09:24:46','III010033');
/*!40000 ALTER TABLE `stocks` ENABLE KEYS */;
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
