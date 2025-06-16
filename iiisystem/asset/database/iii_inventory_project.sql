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
-- Table structure for table `project`
--

DROP TABLE IF EXISTS `project`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `project` (
  `project_id` int NOT NULL AUTO_INCREMENT,
  `client_id` int DEFAULT NULL,
  `date_requested` date NOT NULL,
  `date_needed` date NOT NULL,
  `services` varchar(255) NOT NULL,
  `tarp_type` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `quantity` bigint NOT NULL,
  `price` float(10,2) DEFAULT NULL,
  `total` float(10,2) DEFAULT NULL,
  `status` varchar(255) DEFAULT 'Pending',
  `delivery_receipt` longblob,
  PRIMARY KEY (`project_id`),
  KEY `client_id` (`client_id`),
  CONSTRAINT `project_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `client` (`client_id`)
) ENGINE=InnoDB AUTO_INCREMENT=86 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project`
--

LOCK TABLES `project` WRITE;
/*!40000 ALTER TABLE `project` DISABLE KEYS */;
INSERT INTO `project` VALUES (51,258,'2025-04-22','2025-04-24','Vehicle Sign','Gloss','',12,12.00,144.00,'Completed',_binary '../../asset/uploads/delivery_receipts/1745391100_Screenshot 2025-01-20 204300.png'),(52,259,'2025-04-22','2025-04-30','Display','Gloss','',12,12.00,144.00,'Completed',_binary '../../asset/uploads/delivery_receipts/1745331893_Screenshot 2025-01-13 101532.png'),(53,260,'2025-04-22','2025-04-26','Banner','Matte','12',12,12.00,144.00,'Completed',_binary '../../asset/uploads/delivery_receipts/1745335206_Screenshot (1).png'),(54,260,'2025-04-22','2025-04-26','Sign','Reflectorized','12',12,12.00,144.00,'Completed',_binary '../../asset/uploads/delivery_receipts/1745335206_Screenshot (1).png'),(55,260,'2025-04-22','2025-04-26','Lettering','Matte','12',12,12.00,144.00,'Completed',_binary '../../asset/uploads/delivery_receipts/1745335206_Screenshot (1).png'),(60,263,'2022-04-22','2022-05-04','Banner','Matte','12',20,100.00,2000.00,'Completed',_binary '../../asset/uploads/delivery_receipts/1745338136_Screenshot 2025-01-13 095257.png'),(61,263,'2022-04-22','2022-05-04','Banner','Matte','12',12,12.00,144.00,'Completed',_binary '../../asset/uploads/delivery_receipts/1745338136_Screenshot 2025-01-13 095257.png'),(62,263,'2022-04-22','2022-05-04','Banner','Matte','12',12,200.00,2400.00,'Completed',_binary '../../asset/uploads/delivery_receipts/1745338136_Screenshot 2025-01-13 095257.png'),(63,264,'2022-04-22','2025-04-23','Display','Gloss','',12,12.00,144.00,'Completed',_binary '../../asset/uploads/delivery_receipts/1745339632_Screenshot 2025-01-10 125546.png'),(64,265,'2025-04-23','2025-04-30','Lettering','Matte','12',12,12.00,144.00,'Completed',_binary '../../asset/uploads/delivery_receipts/1745386296_Screenshot 2025-02-01 090142.png'),(65,265,'2025-04-23','2025-04-30','Display','Gloss','12',12,12.00,144.00,'Completed',_binary '../../asset/uploads/delivery_receipts/1745386296_Screenshot 2025-02-01 090142.png'),(66,266,'2025-04-23','2025-04-30','Banner','Matte','21',12,12.00,144.00,'Completed',_binary '../../asset/uploads/delivery_receipts/1745391353_Screenshot 2025-01-10 125546.png'),(67,267,'2025-03-18','2025-04-23','Lettering','Matte','',12,12.00,144.00,'Completed',_binary '../../asset/uploads/delivery_receipts/1745393705_Screenshot (1).png'),(75,275,'2025-04-23','2025-04-30','Banner','Matte','12',12,12.00,151.68,'Completed',_binary '../../asset/uploads/delivery_receipts/1745395240_Screenshot 2025-02-01 092807.png'),(78,278,'2025-04-24','2025-04-30','Decals','Reflectorized','Para sa Highway',2,500.00,1000.00,'Completed',_binary '../../asset/uploads/delivery_receipts/1745470394_Screenshot 2025-02-01 094412.png'),(79,279,'2025-04-26','2025-04-30','Banner','Matte','12',12,12.00,144.00,'Completed',_binary '../../asset/uploads/delivery_receipts/1745654056_Screenshot 2025-01-13 095257.png'),(80,280,'2025-04-26','2025-04-30','Decals','Vinyl','',1,2000.00,2000.00,'For Delivery',NULL),(81,280,'2025-04-26','2025-04-30','Banner','Gloss','',1,1000.00,1000.00,'For Delivery',NULL),(82,280,'2025-04-26','2025-04-30','Sign','Reflectorized','',1,3000.00,3000.00,'For Delivery',NULL),(83,281,'2025-04-26','2025-04-27','Decals','Vinyl','',1,3000.00,3000.00,'Pending',NULL),(84,284,'2025-04-28','2025-05-10','Banner','Matte','1 - 2x2 for entrance',1,1000.00,1000.00,'Completed',_binary '../../asset/uploads/delivery_receipts/1745851650_492800423_1760228354907805_3916454413934548941_n.jpg'),(85,285,'2025-05-04','2025-05-05','Display','Gloss','12',12,12.00,144.00,'For Delivery',NULL);
/*!40000 ALTER TABLE `project` ENABLE KEYS */;
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
