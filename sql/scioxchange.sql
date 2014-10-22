CREATE DATABASE  IF NOT EXISTS `scioxchange` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `scioxchange`;
-- MySQL dump 10.13  Distrib 5.6.13, for Win32 (x86)
--
-- Host: localhost    Database: scioxchange
-- ------------------------------------------------------
-- Server version	5.6.17

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
-- Table structure for table `validation_key`
--

DROP TABLE IF EXISTS `validation_key`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `validation_key` (
  `UserId` int(10) unsigned NOT NULL,
  `ValidationKey` char(36) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `TimeStamp` datetime DEFAULT NULL,
  PRIMARY KEY (`UserId`,`ValidationKey`),
  CONSTRAINT `validation_key_ibfk_1` FOREIGN KEY (`UserId`) REFERENCES `user` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `validation_key`
--

LOCK TABLES `validation_key` WRITE;
/*!40000 ALTER TABLE `validation_key` DISABLE KEYS */;
INSERT INTO `validation_key` VALUES (44,'54029abbc312a0.54468005','2014-08-31 05:47:07'),(47,'54029d6fe457b0.07579498','2014-08-31 05:58:39'),(48,'54029ecbee9008.01226131','2014-08-31 06:04:27'),(49,'54029fde440677.39749854','2014-08-31 06:09:02'),(50,'5402a012415fa5.70212078','2014-08-31 06:09:54'),(51,'5402a0a954a2d1.07633349','2014-08-31 06:12:25'),(52,'5402a20375e8f8.69374223','2014-08-31 06:18:11'),(53,'5402a33b2c2550.96194890','2014-08-31 06:23:23');
/*!40000 ALTER TABLE `validation_key` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `UserID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(30) NOT NULL,
  `screenName` varchar(25) NOT NULL,
  `reputation` float DEFAULT NULL,
  `role` int(11) DEFAULT '0',
  `avatar` varchar(20) DEFAULT NULL,
  `pwd` varchar(255) NOT NULL,
  `active` bit(1) NOT NULL DEFAULT b'0',
  `moderated` bit(1) NOT NULL DEFAULT b'1',
  `pwdChange` bit(1) DEFAULT b'0',
  PRIMARY KEY (`UserID`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (4,'gskoropada@gmail.com','gskoropada',NULL,2,'abcde','$2y$10$VnKFUp8KpFViocdhAjoO4eci21n60SzE0HheAZjGxKA9xQ9NBVl6i','','\0','\0'),(41,'user1@email.com','User 1',NULL,0,'f4c6703674540','$2y$10$ba.9LVnaEd2Sv2KrSD330.5Ns9W7FBwB0Y6WvF8oyD8MY42WwsFdy','','','\0'),(43,'user3@email.com','User 3',NULL,0,'5d41746f2e9f0','$2y$10$be3DO40Dt9AFTCMg7ZBWDOzKGo46UHOHkM8WPEzYGF6kcX8rfl5eG','','','\0'),(44,'user4@email.com','User 4',NULL,0,'74afe5b9290b2','$2y$10$cj4QMSXIy.gZGx9letFNwOo60P8cbqz7gw5SeznlOKBh9eD0aIP2O','\0','','\0'),(47,'user7@email.com','User 7',NULL,0,'65443fdedb209','$2y$10$g2WHT6dgCudQ3jYkmOPimeqY8cSqQivehvgklw.wy9LK4RIR1CH0e','\0','','\0'),(48,'user8@email.com','User 8',NULL,0,'0257c54ba8e90','$2y$10$rPeAFaBC9X0MszgzJxO3luKJ6tQdoppkgcecqtD/DW0YrLzWIqCx2','\0','','\0'),(49,'user9@email.com','User 9',NULL,0,'9ef0f42619d05','$2y$10$LumWKf16RriG2MBjHXBxBecfP011RUYIv28rq0Z450.43ndWtId4u','\0','','\0'),(50,'user10@email.com','User 10',NULL,0,'6c01542a1e207','$2y$10$hT4XUxWr8IwMD13d8vN41eGui2wRVa5rBzQydWMgb6RH/kqo545ey','\0','','\0'),(51,'user11@email.com','User 11',NULL,0,'09a2a5e229405','$2y$10$HPg1FhjPAfK.XzpkM/jomO2m.Ax3ly0Xi1gTT9rgHvejR41vlwNbS','\0','','\0'),(52,'user12@email.com','User 12',NULL,0,'ea0025b202433','$2y$10$3GOpBNguAyRIWVZzOVgR0Oq6g/t2Uxkkje9Ae8n9kRUFCJ9.i4f0G','\0','','\0'),(53,'user13@email.com','User 13',NULL,0,'43a04f85382ea','$2y$10$8sA/uA7iZQ1bJEG519G2eOHx//U00.nn1YQ/f67rpD6pe8Lyp98Ky','\0','','\0'),(54,'sarah@email.com','Sarah',NULL,1,'a3a2a4e0de475','$2y$10$1jtI.LKVGNwuMnfuHTD40OgQZ5pbaDezoFZv83xQssfVwBWlAofre','','\0','\0');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-09-30 15:46:39
