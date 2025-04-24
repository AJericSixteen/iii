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
-- Dumping data for table `account`
--

LOCK TABLES `account` WRITE;
/*!40000 ALTER TABLE `account` DISABLE KEYS */;
INSERT INTO `account` VALUES (1003,1003,'admin','$2y$10$lvU.ZDUf7I/El1TDmrwy6O6MMCdac0WFax/CySW3y6ku9GSOWvNBe','managing director');
/*!40000 ALTER TABLE `account` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `client`
--

LOCK TABLES `client` WRITE;
/*!40000 ALTER TABLE `client` DISABLE KEYS */;
INSERT INTO `client` VALUES (258,'Jamesen De la paz','Youtube','12345',123,'jmsndlpz@gmail.com'),(259,'Richell','Youtube','12345',1242,'obligado.richell26@gmail.com'),(260,'Joseph Domingo','ACLC Manila','NU Laguna',1234,'123@gmail.com'),(263,'Ejay Cabasag','CNX','San Lazaro',1234,'Ejay@gmail.com'),(264,'Leo Valderama','Northbay','Pritil',1234,'angelotrias95@gmail.com'),(265,'Angelo Jeric B. Trias','Tubig ng Langit','429 Coral St. Tondo  Manila',9663879940,'angelotrias95@gmail.com'),(266,'Test','Test','test',123,'Louie@gmail.com'),(267,'da','ad','da',123,'dada@gmail.com'),(275,'dada','dada','123',1221,'Louie@gmail.com'),(277,'Angelo Jeric B. Trias','ACLC Manila','429 Coral St. Tondo  Manila',9663879940,'angelotrias95@gmail.com');
/*!40000 ALTER TABLE `client` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `project`
--

LOCK TABLES `project` WRITE;
/*!40000 ALTER TABLE `project` DISABLE KEYS */;
INSERT INTO `project` VALUES (51,258,'2025-04-22','2025-04-24','Vehicle Sign','Gloss','',12,21,12,12.00,144.00,'Completed',_binary '../../asset/uploads/delivery_receipts/1745391100_Screenshot 2025-01-20 204300.png'),(52,259,'2025-04-22','2025-04-30','Display','Gloss','',12,12,12,12.00,144.00,'Completed',_binary '../../asset/uploads/delivery_receipts/1745331893_Screenshot 2025-01-13 101532.png'),(53,260,'2025-04-22','2025-04-26','Banner','Matte','12',12,12,12,12.00,144.00,'Completed',_binary '../../asset/uploads/delivery_receipts/1745335206_Screenshot (1).png'),(54,260,'2025-04-22','2025-04-26','Sign','Reflectorized','12',12,12,12,12.00,144.00,'Completed',_binary '../../asset/uploads/delivery_receipts/1745335206_Screenshot (1).png'),(55,260,'2025-04-22','2025-04-26','Lettering','Matte','12',12,12,12,12.00,144.00,'Completed',_binary '../../asset/uploads/delivery_receipts/1745335206_Screenshot (1).png'),(60,263,'2022-04-22','2022-05-04','Banner','Matte','12',12.5,12,20,100.00,2000.00,'Completed',_binary '../../asset/uploads/delivery_receipts/1745338136_Screenshot 2025-01-13 095257.png'),(61,263,'2022-04-22','2022-05-04','Banner','Matte','12',12,12,12,12.00,144.00,'Completed',_binary '../../asset/uploads/delivery_receipts/1745338136_Screenshot 2025-01-13 095257.png'),(62,263,'2022-04-22','2022-05-04','Banner','Matte','12',12,12,12,200.00,2400.00,'Completed',_binary '../../asset/uploads/delivery_receipts/1745338136_Screenshot 2025-01-13 095257.png'),(63,264,'2022-04-22','2025-04-23','Display','Gloss','',12,12,12,12.00,144.00,'Completed',_binary '../../asset/uploads/delivery_receipts/1745339632_Screenshot 2025-01-10 125546.png'),(64,265,'2025-04-23','2025-04-30','Lettering','Matte','12',12,12,12,12.00,144.00,'Completed',_binary '../../asset/uploads/delivery_receipts/1745386296_Screenshot 2025-02-01 090142.png'),(65,265,'2025-04-23','2025-04-30','Display','Gloss','12',12,12,12,12.00,144.00,'Completed',_binary '../../asset/uploads/delivery_receipts/1745386296_Screenshot 2025-02-01 090142.png'),(66,266,'2025-04-23','2025-04-30','Banner','Matte','21',12,212,12,12.00,144.00,'Completed',_binary '../../asset/uploads/delivery_receipts/1745391353_Screenshot 2025-01-10 125546.png'),(67,267,'2025-03-18','2025-04-23','Lettering','Matte','',12,12,12,12.00,144.00,'Completed',_binary '../../asset/uploads/delivery_receipts/1745393705_Screenshot (1).png'),(75,275,'2025-04-23','2025-04-30','Banner','Matte','12',12,12,12,12.00,151.68,'Completed',_binary '../../asset/uploads/delivery_receipts/1745395240_Screenshot 2025-02-01 092807.png'),(77,277,'2025-04-23','2025-04-30','Banner','Matte','1 for wall\r\n1 for sides',12,12,10,105.00,1050.00,'Pending',NULL);
/*!40000 ALTER TABLE `project` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `stock_transaction`
--

LOCK TABLES `stock_transaction` WRITE;
/*!40000 ALTER TABLE `stock_transaction` DISABLE KEYS */;
/*!40000 ALTER TABLE `stock_transaction` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `stocks`
--

LOCK TABLES `stocks` WRITE;
/*!40000 ALTER TABLE `stocks` DISABLE KEYS */;
INSERT INTO `stocks` VALUES (10018,'Tarpaulin','Printing Materials',89,100,200,'2025-04-21 15:04:32','III010018'),(10019,'Sticker (Matte)','Printing Materials',50,50,100,'2025-04-21 14:48:09','III010019'),(10020,'Sticker (Lamination)','Printing Materials',50,50,100,'2025-04-21 14:48:09','III010020'),(10021,'Sticker (Gloss)','Printing Materials',50,50,100,'2025-04-21 14:48:09','III010021'),(10022,'Fabric Banner','Printing Materials',2,5,10,'2025-04-21 15:08:09','III010022'),(10023,'Duratrans ','Printing Materials',40,40,80,'2025-04-21 14:48:09','III010023'),(10024,'Sintra','Printing Materials',100,50,100,'2025-04-21 14:48:09','III010024'),(10025,'Ink (C - Cyan)','Printing Materials',8,8,16,'2025-04-21 14:48:09','III010025'),(10026,'Ink (M - Magenta)','Printing Materials',8,8,16,'2025-04-21 14:48:09','III010026'),(10027,'Ink (Y - Yellow) ','Printing Materials',8,8,16,'2025-04-21 14:48:09','III010027'),(10028,'Ink (K - Black)','Printing Materials',8,8,16,'2025-04-21 14:48:09','III010028'),(10029,'Cleaning Solution','Printing Materials',3,3,6,'2025-04-21 14:48:09','III010029'),(10030,'Cyno','Printing Materials',3,3,6,'2025-04-21 14:48:09','III010030');
/*!40000 ALTER TABLE `stocks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `user_info`
--

LOCK TABLES `user_info` WRITE;
/*!40000 ALTER TABLE `user_info` DISABLE KEYS */;
INSERT INTO `user_info` VALUES (1003,_binary '../../asset/uploads/1740739193_eat-sleep-code-repeat-black-background-programmer-quotes-3840x2160-5947.png','Isagani','Gamab III',12345,'angelotrias95@gmail.com');
/*!40000 ALTER TABLE `user_info` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-04-23 16:30:23
