CREATE DATABASE  IF NOT EXISTS `ddnmobile` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `ddnmobile`;
-- MySQL dump 10.13  Distrib 8.0.34, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: ddnmobile
-- ------------------------------------------------------
-- Server version	8.0.34

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
-- Table structure for table `brand`
--

DROP TABLE IF EXISTS `brand`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `brand` (
  `brand_id` int NOT NULL AUTO_INCREMENT,
  `brand_name` varchar(255) NOT NULL,
  `brand_logo_img_url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`brand_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `brand`
--

LOCK TABLES `brand` WRITE;
/*!40000 ALTER TABLE `brand` DISABLE KEYS */;
INSERT INTO `brand` VALUES (1,'Apple',NULL),(2,'Samsung',NULL),(3,'Nokia',NULL),(4,'Google',NULL),(5,'Redmi',NULL),(7,'Vivo',NULL),(8,'Oppo',NULL),(9,'Sony',NULL);
/*!40000 ALTER TABLE `brand` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `capacity`
--

DROP TABLE IF EXISTS `capacity`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `capacity` (
  `capacity_id` int NOT NULL AUTO_INCREMENT,
  `capacity_storage` varchar(45) NOT NULL,
  PRIMARY KEY (`capacity_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `capacity`
--

LOCK TABLES `capacity` WRITE;
/*!40000 ALTER TABLE `capacity` DISABLE KEYS */;
INSERT INTO `capacity` VALUES (1,'4GB'),(2,'8GB'),(3,'16GB'),(4,'32GB'),(5,'64GB'),(6,'128GB'),(7,'256GB'),(8,'512GB'),(9,'1TB'),(10,'2TB');
/*!40000 ALTER TABLE `capacity` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cart_item`
--

DROP TABLE IF EXISTS `cart_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cart_item` (
  `cart_item_id` int NOT NULL AUTO_INCREMENT,
  `cart_item_quntity` int NOT NULL,
  `item_id` int NOT NULL,
  `shopping_cart_id` int NOT NULL,
  `cart_item_color` varchar(45) NOT NULL,
  PRIMARY KEY (`cart_item_id`),
  KEY `item_id_fk_cart_item_idx` (`item_id`),
  KEY `shopping_cart_id_fk_idx` (`shopping_cart_id`),
  CONSTRAINT `item_id_fk_cart_item` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`),
  CONSTRAINT `shopping_cart_id_fk` FOREIGN KEY (`shopping_cart_id`) REFERENCES `shopping_cart` (`shopping_cart_id`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart_item`
--

LOCK TABLES `cart_item` WRITE;
/*!40000 ALTER TABLE `cart_item` DISABLE KEYS */;
INSERT INTO `cart_item` VALUES (29,3,5,8,'green'),(53,3,5,2,'blue'),(54,1,63,2,'white'),(55,1,66,7,'black');
/*!40000 ALTER TABLE `cart_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `categories_id` int NOT NULL AUTO_INCREMENT,
  `categories_name` varchar(255) NOT NULL,
  PRIMARY KEY (`categories_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Brand New Mobiles'),(2,'Iphones'),(3,'Watches'),(4,'Ipods'),(6,'Tabs'),(8,'speakers'),(9,'Ipads');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `colors`
--

DROP TABLE IF EXISTS `colors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `colors` (
  `color_id` int NOT NULL AUTO_INCREMENT,
  `color_code` varchar(45) NOT NULL,
  PRIMARY KEY (`color_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `colors`
--

LOCK TABLES `colors` WRITE;
/*!40000 ALTER TABLE `colors` DISABLE KEYS */;
INSERT INTO `colors` VALUES (1,'black'),(2,'white'),(3,'red'),(4,'blue'),(5,'green'),(9,'yellow'),(10,'pink'),(12,'rose'),(13,'purple');
/*!40000 ALTER TABLE `colors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customer`
--

DROP TABLE IF EXISTS `customer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customer` (
  `customer_id` int NOT NULL AUTO_INCREMENT,
  `customer_name` varchar(255) NOT NULL,
  `customer_tele` varchar(45) DEFAULT NULL,
  `customer_email` varchar(45) NOT NULL,
  `customer_password` varchar(255) DEFAULT NULL,
  `google_id` int unsigned DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`customer_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customer`
--

LOCK TABLES `customer` WRITE;
/*!40000 ALTER TABLE `customer` DISABLE KEYS */;
INSERT INTO `customer` VALUES (4,'Ameesha Sathsarani','0760597791','amee@gmail.com','$2y$10$x7to5bAWt9/lNlcP2T179uL/Bc7wWnjW3aDAlBcdHocaGvORqs4hq',NULL,NULL),(5,'Kaviska Dinajara','0114789562','k@gmail.com','$2y$10$Bl/ey6/zItqSg.FZE.fElehhWi02rr6U.RI/Ih2Lq4TGIq3jymo8m',NULL,NULL),(6,'Ruwan Kumara','0778654521','r@gmail.com','$2y$10$POdpNFOf07JMaApe1N9kwO9fRLu.PaawR/QOVm2qviNOrDA1Bzw..',NULL,NULL),(7,'Surath de Mel','0774587962','mel@gmail.com','$2y$10$8/UfURdMXICRVAm3ozOuAes5zQX6OYSjey7cNqe/6JS5qQa1rY1Yi',NULL,NULL),(8,'Kaviska Dinajara','09+1419695','asd@gmail.com','$2y$10$wPAjhE/eh57feE0dEjVc0uAjrWLv17qMzry5ySt9fxt/OM9MNYU3O',NULL,NULL),(9,'Ashini Upeksha','0984689147','zx@gmail.com','$2y$10$544xIlWXyu/VI/EN7H6sEexcbviwDUG.Ey50jXerdf3tuy0XXDvyS',NULL,NULL),(10,'Surath de Mel','0774587962','de@gmail.com','$2y$10$vSmnxqPB3x/EfV4Njn1kX.I/uYUwXeQPpWWhwp54ncnyJS5ejSbqm',NULL,NULL),(11,'ddn','011122655','ddn@gmail.com','$2y$10$4y2fylKtGE.fCCpmZnhmxOUNA/CzLtqv3oVy08dm6hINAnC9Eizda',NULL,NULL),(12,'no cart','045631524','no@gmail.com','$2y$10$o3wv2.Gh8TLRDfpW.GHb2uHSMLewVhdD2uo3kZhDfUuBvzYRwg426',NULL,NULL),(13,'Ashini Upeksha','0778852555','ashi@gmail.com','$2y$10$.Uiu2bIWv.rLZLRcqy1okOebUa79BCuu1GJ/9HnJZuoPJnhOovvza',NULL,NULL),(14,'ashi','0111111111','a@gmail.com','$2y$10$CpJL6BNoWs1/sBcn4FwHfeDjJ0qLGeuzqCda1dMF2YaEF01Huy8pS',NULL,NULL);
/*!40000 ALTER TABLE `customer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `item_capacity`
--

DROP TABLE IF EXISTS `item_capacity`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `item_capacity` (
  `item_id` int NOT NULL,
  `capacity_id` int NOT NULL,
  PRIMARY KEY (`item_id`,`capacity_id`),
  KEY `item_id_idx` (`item_id`),
  KEY `capacity_id_idx` (`capacity_id`),
  CONSTRAINT `capacity_id` FOREIGN KEY (`capacity_id`) REFERENCES `capacity` (`capacity_id`),
  CONSTRAINT `item_id_fk_capacity` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `item_capacity`
--

LOCK TABLES `item_capacity` WRITE;
/*!40000 ALTER TABLE `item_capacity` DISABLE KEYS */;
/*!40000 ALTER TABLE `item_capacity` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `item_color`
--

DROP TABLE IF EXISTS `item_color`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `item_color` (
  `color_id` int NOT NULL,
  `item_id` int NOT NULL,
  `item_stock_quantity` int DEFAULT NULL,
  PRIMARY KEY (`color_id`,`item_id`),
  KEY `item_id_idx` (`item_id`),
  CONSTRAINT `color_id` FOREIGN KEY (`color_id`) REFERENCES `colors` (`color_id`),
  CONSTRAINT `item_id` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `item_color`
--

LOCK TABLES `item_color` WRITE;
/*!40000 ALTER TABLE `item_color` DISABLE KEYS */;
INSERT INTO `item_color` VALUES (1,1,NULL),(1,3,NULL),(1,62,NULL),(1,63,NULL),(1,66,NULL),(2,3,NULL),(2,62,NULL),(2,63,NULL),(2,66,NULL),(3,1,NULL),(3,62,NULL),(3,63,NULL),(3,66,NULL),(4,1,NULL),(4,5,NULL),(5,62,NULL),(5,63,NULL);
/*!40000 ALTER TABLE `item_color` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `items`
--

DROP TABLE IF EXISTS `items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `items` (
  `item_id` int NOT NULL AUTO_INCREMENT,
  `item_name` varchar(45) NOT NULL,
  `item_discription` text NOT NULL,
  `item_price` double NOT NULL,
  `item_discount_rate` double DEFAULT NULL,
  `item_stock_quantity` int DEFAULT NULL,
  `item_img_url1` varchar(225) DEFAULT NULL,
  `item_keywords` text NOT NULL,
  `brand_id` int NOT NULL,
  `categories_id` int NOT NULL,
  `item_img_url2` varchar(255) DEFAULT NULL,
  `item_img_url3` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`item_id`),
  KEY `brand_id_idx` (`brand_id`),
  KEY `categoties_id_idx` (`categories_id`),
  CONSTRAINT `brand_id` FOREIGN KEY (`brand_id`) REFERENCES `brand` (`brand_id`),
  CONSTRAINT `categoties_id` FOREIGN KEY (`categories_id`) REFERENCES `categories` (`categories_id`)
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `items`
--

LOCK TABLES `items` WRITE;
/*!40000 ALTER TABLE `items` DISABLE KEYS */;
INSERT INTO `items` VALUES (1,'Iphone 14','  256 GB\r\nbattery health 100%  ',150000,2.3,49,'iphon14.jpg','  iphone, brand new, apple, ios 16  ',1,2,NULL,NULL),(3,'S22','256GB\r\n',300000,10,0,'s22.jpg','samsung, brand new, s22',2,1,NULL,NULL),(5,'Y93','* 155.11×75.09×8.28mm *\r\nWeight: 163.5g *\r\nProcessor - Qualcomm Snapdragon 439 Octa-core *\r\nRAM - 3GB *\r\nStorage - 32GB *\r\nBattery - 4030mAh (TYP) *\r\nCamera - Front 8MP / Rear 13MP+2MP *\r\n',75000,5,13,'vivo_y93.jpg','vivo \r\ny93\r\nbrand new\r\n',7,1,'vivo-y93-1814-original-imafc7bpqpbq2avv.webp','Vivo_Y93_black.jpg'),(62,'iphone XS',' \r\nThe iPhone XS was a flagship smartphone model released by Apple in September 2018. It featured a 5.8-inch Super Retina OLED display with a resolution of 1125 x 2436 pixels, providing sharp and vibrant visuals. The device was powered by Apple\'s A12 Bionic chip, which offered impressive performance and energy efficiency. It came with a dual-camera system on the rear, consisting of a 12-megapixel wide-angle and telephoto lens, capable of capturing high-quality photos and 4K video. The iPhone XS also supported features like Face ID for secure facial recognition, iOS 12, and had an elegant design with a stainless steel frame and glass back. It was available in various storage capacities and served as a predecessor to the iPhone 11 series.',100000,2,47,'61Q7UHuJvEL.jpg',' apple\r\niphone xs brand new',1,2,'Apple-iPhone-Xs-combo-gold-09122018_big.jpg.large.jpg','download (2).jpg'),(63,' v20',' The Vivo V20 was a mid-range smartphone released by Vivo in October 2020. It featured a 6.44-inch AMOLED display with a Full HD+ resolution, offering vibrant and sharp visuals. The device was powered by the Qualcomm Snapdragon 720G processor, which provided good overall performance for everyday tasks and gaming.\r\n\r\nOne of its notable features was its focus on camera capabilities. The Vivo V20 boasted a 44-megapixel front camera for high-resolution selfies and video calls. On the rear, it had a triple-camera setup, which included a 64-megapixel main camera, an 8-megapixel ultrawide lens, and a 2-megapixel depth sensor, allowing users to capture a variety of scenes and artistic shots.\r\n\r\nThe phone ran on Vivo\'s Funtouch OS, based on Android 11, and had a sleek design with a slim profile. It was available in various RAM and storage configurations to suit different user needs. The Vivo V20 aimed to provide a balanced package of design, camera performance, and mid-range specifications.',50000,2,26,'V20-SE-Banner-2000-x-2000px.jpg',' vivo v20 brand new ',7,1,'9a595110fce3cb1add138aff8a2ac9e2.png','1b37e4809bbcccd933b87b92bcf2c97f.png'),(66,'Pixel 8',' The Google Pixel 7 and the Pixel 7 Pro were so good that we\'re eager to see what Google cooks up for the Pixel 8, and thanks to leaks and rumors we have quite a good idea of what to expect.\r\n\r\nFor one thing, it will probably use a Tensor 3 chipset, support satellite communications, and might have a 6.2-inch screen, 12GB of RAM, and a dual-lens camera. Indeed, based on past form we\'d expect class-leading cameras and generally strong software.\r\n\r\nWe\'ve also seen what the Pixel 8 might look like and, well, it\'s not a big visual change from the Pixel 7, but that\'s already one of the more distinctive phones, so it makes sense that Google would stick with this design for a while',150000,2.3,46,'pixel83.jpg',' Brand new\r\n2023 new phone\r\ngoogle\r\npixel 8',4,1,'pixel82.jpg','pixel81.jpg');
/*!40000 ALTER TABLE `items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oder_item`
--

DROP TABLE IF EXISTS `oder_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `oder_item` (
  `oder_item_id` int NOT NULL AUTO_INCREMENT,
  `oder_item_quantity` int NOT NULL,
  `oder_item_price_at_purchase` double DEFAULT NULL,
  `item_id` int NOT NULL,
  `oders_id` int NOT NULL,
  `oder_item_color` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`oder_item_id`,`item_id`,`oders_id`),
  KEY `item_id_fk_oder_item_idx` (`item_id`),
  KEY `oders_id_fk_oder_item_idx` (`oders_id`),
  CONSTRAINT `oders_id_fk_oder_item` FOREIGN KEY (`oders_id`) REFERENCES `oders` (`oders_id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oder_item`
--

LOCK TABLES `oder_item` WRITE;
/*!40000 ALTER TABLE `oder_item` DISABLE KEYS */;
INSERT INTO `oder_item` VALUES (25,1,NULL,1,26,'red'),(26,1,NULL,63,26,'green'),(27,3,NULL,5,27,'blue'),(28,1,NULL,62,28,'black'),(29,1,NULL,5,28,'blue'),(30,1,NULL,63,28,'green'),(31,1,NULL,5,30,'blue'),(32,1,NULL,62,30,'white'),(33,2,NULL,66,31,'white'),(34,2,NULL,62,32,'red'),(35,1,NULL,5,33,'blue'),(36,1,NULL,66,33,'white'),(37,1,NULL,5,34,'blue'),(38,1,NULL,66,35,'red'),(39,1,NULL,63,36,'red'),(40,1,NULL,63,37,'black'),(41,1,NULL,62,38,'white');
/*!40000 ALTER TABLE `oder_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oders`
--

DROP TABLE IF EXISTS `oders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `oders` (
  `oders_id` int NOT NULL AUTO_INCREMENT,
  `payment_method` varchar(45) DEFAULT NULL,
  `oders_total_cost` double DEFAULT NULL,
  `oder_date` date DEFAULT NULL,
  `shipping_address_id` int DEFAULT NULL,
  `customer_id` int NOT NULL,
  `oders_code` varchar(225) DEFAULT NULL,
  `oders_confrim_admin` int DEFAULT NULL,
  `oders_deliver_cus` int DEFAULT NULL,
  PRIMARY KEY (`oders_id`),
  KEY `shipping_add_id_fk_idx` (`shipping_address_id`),
  KEY `customer_id_fk_idx` (`customer_id`),
  CONSTRAINT `customer_id_fk` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`),
  CONSTRAINT `shipping_add_id_fk` FOREIGN KEY (`shipping_address_id`) REFERENCES `shipping_address` (`shipping_address_id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oders`
--

LOCK TABLES `oders` WRITE;
/*!40000 ALTER TABLE `oders` DISABLE KEYS */;
INSERT INTO `oders` VALUES (26,NULL,200600,'2023-11-19',31,6,'4mXQmvoF',1,NULL),(27,NULL,225600,'2023-11-19',33,6,'W8m$6c3V',1,NULL),(28,NULL,225600,'2023-11-20',28,10,'2&rI$1Rr',1,NULL),(29,NULL,75600,'2023-11-22',28,10,'at6H0z0L',NULL,NULL),(30,NULL,175600,'2023-11-22',24,11,'#aZcaWh2',1,1),(31,NULL,300600,'2023-11-22',1,11,'joZnJ13C',1,NULL),(32,NULL,200600,'2023-11-24',1,11,'jdvAG9w6',1,NULL),(33,NULL,225600,'2023-11-30',11,11,'8iSzRRmZ',1,1),(34,NULL,75600,'2023-11-30',1,11,'Ttd6uSb1',1,NULL),(35,NULL,150600,'2023-11-30',24,11,'WE88h7R&',1,1),(36,NULL,50600,'2023-12-01',31,6,'U6POPfI7',1,NULL),(37,NULL,50600,'2023-12-03',31,6,'78HqoUFM',1,NULL),(38,NULL,100600,'2023-12-22',31,6,'pM278Lt6',1,NULL);
/*!40000 ALTER TABLE `oders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shipping_address`
--

DROP TABLE IF EXISTS `shipping_address`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `shipping_address` (
  `shipping_address_id` int NOT NULL AUTO_INCREMENT,
  `city` varchar(45) NOT NULL,
  `province` varchar(45) NOT NULL,
  `postal_code` varchar(45) NOT NULL,
  `recever_full_name` varchar(45) NOT NULL,
  `customer_id` int NOT NULL,
  `shipping_address_tele` varchar(45) NOT NULL,
  `address` varchar(225) DEFAULT NULL,
  PRIMARY KEY (`shipping_address_id`),
  KEY `customer_id_fk_shipping_add_idx` (`customer_id`),
  CONSTRAINT `customer_id_fk_shipping_add` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shipping_address`
--

LOCK TABLES `shipping_address` WRITE;
/*!40000 ALTER TABLE `shipping_address` DISABLE KEYS */;
INSERT INTO `shipping_address` VALUES (1,'Galle','Southern','80150','Kavishka Dinajara',11,'077894566','Hiriyamalkumbura,Yakkalamulla'),(11,'Galle','Southern','80150','kavishka Dinajara',11,'0778165268','Indurawththa,Hiriyamalkumbura,Yakkalamulla'),(12,'Galle','Southern','80150','Kavishka Dinajara',12,'0778165268','Indurawaththa,Hiriyamalkumbura,Yakkalamulla'),(24,'Galle','Central','80150','kavishka Dinajara',11,'0778165268','Indurawththa,Hiriyamalkumbura,Yakkalamulla'),(25,'Galle','North Western','80150','hjjxcgvhbkn',12,'0778165268','Indurawaththa,Hiriyamalkumbura,Yakkalamulla'),(26,'Galle','Sabaragamuwa','80150','gfnb',12,'0778165268','Indurawaththa,Hiriyamalkumbura,Yakkalamulla'),(28,'Galle','North Eastern','80150','kavishka Dinajara',10,'0778165268','Indurawththa,Hiriyamalkumbura,Yakkalamulla'),(31,'colombo','Sabaragamuwa','80150','aewairfgewr8giohwer',6,'0778165268','Indurawaththa,Hiriyamalkumbura,Yakkalamulla'),(32,'Galle','North Eastern','80150','Ashini Upeksha',10,'0778165268','Pinnaduwa, Galle'),(33,'Galle','North Eastern','80150','Kavishka Dinajara',6,'+94778165268','Indurawaththa,Hiriyamalkumbura,Yakkalamulla');
/*!40000 ALTER TABLE `shipping_address` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shopping_cart`
--

DROP TABLE IF EXISTS `shopping_cart`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `shopping_cart` (
  `shopping_cart_id` int NOT NULL AUTO_INCREMENT,
  `customer_id` int NOT NULL,
  PRIMARY KEY (`shopping_cart_id`),
  KEY `customer_id_fk_shopping_cart_idx` (`customer_id`),
  CONSTRAINT `customer_id_fk_shopping_cart` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shopping_cart`
--

LOCK TABLES `shopping_cart` WRITE;
/*!40000 ALTER TABLE `shopping_cart` DISABLE KEYS */;
INSERT INTO `shopping_cart` VALUES (2,6),(1,7),(3,8),(4,9),(6,10),(7,11),(8,12);
/*!40000 ALTER TABLE `shopping_cart` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `temp_order`
--

DROP TABLE IF EXISTS `temp_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `temp_order` (
  `customer_id` int NOT NULL,
  `shopping_cart_id` int DEFAULT NULL,
  `item_id` int DEFAULT NULL,
  `color` varchar(45) DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  PRIMARY KEY (`customer_id`),
  CONSTRAINT `cus_id` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `temp_order`
--

LOCK TABLES `temp_order` WRITE;
/*!40000 ALTER TABLE `temp_order` DISABLE KEYS */;
INSERT INTO `temp_order` VALUES (6,NULL,63,'white',1),(10,NULL,5,'blue',1),(11,7,NULL,NULL,NULL),(12,8,NULL,NULL,NULL),(14,NULL,63,'red',1);
/*!40000 ALTER TABLE `temp_order` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-12-30 12:06:49
