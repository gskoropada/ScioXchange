
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
-- Table structure for table `answer`
--

DROP TABLE IF EXISTS `answer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `answer` (
  `answer_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `author` int(10) unsigned NOT NULL,
  `content` longtext NOT NULL,
  `positive_votes` int(11) NOT NULL DEFAULT '0',
  `negative_votes` int(11) NOT NULL DEFAULT '0',
  `question` int(10) unsigned NOT NULL,
  `timestamp` datetime DEFAULT NULL,
  PRIMARY KEY (`answer_id`),
  KEY `author` (`author`),
  KEY `answer_question_idx` (`question`),
  CONSTRAINT `answer_ibfk_1` FOREIGN KEY (`author`) REFERENCES `user` (`UserID`),
  CONSTRAINT `answer_question` FOREIGN KEY (`question`) REFERENCES `question` (`question_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `answer`
--

LOCK TABLES `answer` WRITE;
/*!40000 ALTER TABLE `answer` DISABLE KEYS */;
INSERT INTO `answer` VALUES (21,54,'It seems to work',1,0,19,'2014-10-03 10:03:02'),(22,4,'Testing',1,0,21,'2014-10-03 11:18:47'),(23,4,'dafdf',1,0,21,'2014-10-03 11:20:10'),(24,4,'dafdf',1,0,21,'2014-10-03 11:26:53'),(25,4,'dafdf',1,0,21,'2014-10-03 11:27:16'),(26,4,'I don\'t know, you tell me...',1,0,22,'2014-10-03 11:32:47'),(27,4,'I don\'t know, you tell me...',0,1,22,'2014-10-03 11:32:53'),(28,4,'kjhaslkdfj\nasdfkjhalksdf\nasdkjhaskdfj\n',1,0,22,'2014-10-06 05:19:25'),(29,4,'I\'m just testing a new functionality',0,0,21,'2014-10-07 06:04:09'),(30,41,'I\'ll test it for you',2,0,25,'2014-10-14 02:32:11'),(31,54,'It hasn\'t worked so far...',2,0,25,'2014-10-14 02:37:57'),(32,41,'Just testing some functionalities...',0,0,22,'2014-10-14 02:45:41'),(33,41,'Testing a little more...',0,0,19,'2014-10-14 02:46:25'),(34,41,'And a little bit more testing...',1,0,20,'2014-10-14 02:52:45');
/*!40000 ALTER TABLE `answer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comment`
--

DROP TABLE IF EXISTS `comment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comment` (
  `comment_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `comment_author` int(10) unsigned NOT NULL,
  `comment` longtext NOT NULL,
  `link` int(10) unsigned NOT NULL,
  `link_to` tinyint(4) DEFAULT NULL,
  `timestamp` datetime DEFAULT NULL,
  PRIMARY KEY (`comment_id`),
  KEY `author` (`comment_author`),
  KEY `link` (`link`),
  CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`comment_author`) REFERENCES `user` (`UserID`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comment`
--

LOCK TABLES `comment` WRITE;
/*!40000 ALTER TABLE `comment` DISABLE KEYS */;
INSERT INTO `comment` VALUES (16,4,'I\'m working on that.\nI don\'t know why it stopped working.',21,0,'2014-10-03 10:10:17'),(17,54,'Testing notifications again.',20,0,'2014-10-03 10:11:54'),(18,4,'Does this work?',23,1,'2014-10-04 02:21:56'),(19,4,'Testing',22,0,'2014-10-06 04:48:20'),(20,4,'It looks like 255 characters is a long text. I have to try that again.',24,0,'2014-10-07 08:15:32'),(21,4,'Let\'s see if the system notifies me this time.',23,0,'2014-10-07 08:22:10'),(22,4,'And let\'s see if the system notifies Sarah this time.',22,0,'2014-10-07 08:22:41');
/*!40000 ALTER TABLE `comment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `message`
--

DROP TABLE IF EXISTS `message`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `message` (
  `message_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `from` int(10) unsigned NOT NULL,
  `subject` tinytext NOT NULL,
  `content` longtext NOT NULL,
  `reply_to` int(10) unsigned NOT NULL,
  PRIMARY KEY (`message_id`),
  KEY `from` (`from`,`reply_to`),
  KEY `reply_to` (`reply_to`),
  CONSTRAINT `message_ibfk_1` FOREIGN KEY (`from`) REFERENCES `user` (`UserID`),
  CONSTRAINT `message_ibfk_2` FOREIGN KEY (`reply_to`) REFERENCES `message` (`message_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `message`
--

LOCK TABLES `message` WRITE;
/*!40000 ALTER TABLE `message` DISABLE KEYS */;
/*!40000 ALTER TABLE `message` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `message_recipient`
--

DROP TABLE IF EXISTS `message_recipient`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `message_recipient` (
  `mesage_id` int(10) unsigned NOT NULL,
  `recipient` int(10) unsigned NOT NULL,
  `read` bit(2) NOT NULL,
  PRIMARY KEY (`mesage_id`,`recipient`),
  KEY `recipient` (`recipient`),
  CONSTRAINT `message_recipient_ibfk_1` FOREIGN KEY (`mesage_id`) REFERENCES `message` (`message_id`),
  CONSTRAINT `message_recipient_ibfk_2` FOREIGN KEY (`recipient`) REFERENCES `user` (`UserID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `message_recipient`
--

LOCK TABLES `message_recipient` WRITE;
/*!40000 ALTER TABLE `message_recipient` DISABLE KEYS */;
/*!40000 ALTER TABLE `message_recipient` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `moderator_scope`
--

DROP TABLE IF EXISTS `moderator_scope`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `moderator_scope` (
  `moderator_id` int(10) unsigned NOT NULL,
  `tag_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`moderator_id`,`tag_id`),
  KEY `moderator_id` (`moderator_id`),
  KEY `tag_id` (`tag_id`),
  CONSTRAINT `moderator_scope_ibfk_1` FOREIGN KEY (`moderator_id`) REFERENCES `user` (`UserID`),
  CONSTRAINT `moderator_scope_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `tag` (`tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `moderator_scope`
--

LOCK TABLES `moderator_scope` WRITE;
/*!40000 ALTER TABLE `moderator_scope` DISABLE KEYS */;
/*!40000 ALTER TABLE `moderator_scope` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notification`
--

DROP TABLE IF EXISTS `notification`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notification` (
  `not_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user` int(10) unsigned NOT NULL,
  `not_type` tinyint(4) NOT NULL,
  `origin` int(10) unsigned NOT NULL,
  `origin_type` tinyint(4) NOT NULL,
  `timestamp` datetime NOT NULL,
  `status` bit(1) DEFAULT b'0',
  `parent` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`not_id`),
  KEY `fk_not_user_idx` (`user`),
  CONSTRAINT `fk_not_user` FOREIGN KEY (`user`) REFERENCES `user` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notification`
--

LOCK TABLES `notification` WRITE;
/*!40000 ALTER TABLE `notification` DISABLE KEYS */;
INSERT INTO `notification` VALUES (15,54,2,21,1,'2014-10-03 10:04:42','',19),(16,54,1,21,0,'2014-10-03 10:10:17','',21),(17,4,1,20,0,'2014-10-03 10:11:54','',20),(18,54,0,21,0,'2014-10-03 11:27:16','',25),(19,4,2,25,1,'2014-10-03 11:28:54','',21),(20,4,2,23,1,'2014-10-03 11:28:58','',21),(21,4,2,22,1,'2014-10-03 11:29:00','',21),(22,4,2,24,1,'2014-10-03 11:29:03','',21),(23,54,0,22,0,'2014-10-03 11:32:48','',26),(24,54,0,22,0,'2014-10-03 11:32:53','',27),(25,4,2,26,1,'2014-10-03 11:33:59','',22),(26,4,2,27,1,'2014-10-03 11:34:06','',22),(27,4,1,23,1,'2014-10-04 02:21:56','',21),(28,54,1,22,0,'2014-10-06 04:48:20','',22),(29,54,0,22,0,'2014-10-06 05:19:25','',28),(30,4,2,28,1,'2014-10-06 05:19:46','',22),(31,54,0,21,0,'2014-10-07 06:04:09','',29),(32,4,1,24,0,'2014-10-07 08:15:32','',24),(33,54,1,22,0,'2014-10-07 08:22:41','',22),(34,4,0,25,0,'2014-10-14 02:32:11','',30),(35,41,2,30,1,'2014-10-14 02:34:00','',25),(36,41,2,30,1,'2014-10-14 02:36:43','',25),(37,4,0,25,0,'2014-10-14 02:37:57','',31),(38,54,2,31,1,'2014-10-14 02:39:23','\0',25),(39,54,2,31,1,'2014-10-14 02:44:59','\0',25),(40,54,0,22,0,'2014-10-14 02:45:41','\0',32),(41,4,0,19,0,'2014-10-14 02:46:25','',33),(42,4,0,20,0,'2014-10-14 02:52:45','',34),(43,41,2,34,1,'2014-10-14 02:53:32','\0',20);
/*!40000 ALTER TABLE `notification` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `question`
--

DROP TABLE IF EXISTS `question`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `question` (
  `question_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `author` int(10) unsigned NOT NULL COMMENT 'Foreign Key',
  `question_title` text,
  `content` longtext,
  `tags` text,
  `timestamp` datetime NOT NULL,
  PRIMARY KEY (`question_id`),
  KEY `author` (`author`),
  CONSTRAINT `question_ibfk_1` FOREIGN KEY (`author`) REFERENCES `user` (`UserID`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `question`
--

LOCK TABLES `question` WRITE;
/*!40000 ALTER TABLE `question` DISABLE KEYS */;
INSERT INTO `question` VALUES (19,4,'Testing this site','1, 2, 3, 4... Testing...','test, php, jquery','2014-10-03 10:01:07'),(20,4,'More testing','More testing. Need more tags.','jquery, php, java, web','2014-10-03 10:03:41'),(21,54,'Notifications','Notifications for new answers don\'t work','questions, answers, web','2014-10-03 10:09:42'),(22,54,'Answer notifications','It seems to have worked','questions, answers, web, php, jquery','2014-10-03 11:32:01'),(23,4,'Adding questions','How do you ask a question on this site?','questions, answers, web','2014-10-04 02:05:12'),(24,4,'A new question.','I\'ve just added a new question.\nI should check if questions over 255 characters get truncated when later displayed.\nI believe that 255 characters are really too long. I wonder how many have I already typed.','questions, answers, web','2014-10-07 05:56:39'),(25,4,'Testing the reputation functions','I wonder if this will work...','questions, answers, php','2014-10-14 02:31:12');
/*!40000 ALTER TABLE `question` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tag`
--

DROP TABLE IF EXISTS `tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tag` (
  `tag_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tag` varchar(30) NOT NULL,
  `tag_count` int(10) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`tag_id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tag`
--

LOCK TABLES `tag` WRITE;
/*!40000 ALTER TABLE `tag` DISABLE KEYS */;
INSERT INTO `tag` VALUES (17,'test',1),(18,'php',4),(19,'jquery',3),(20,'java',1),(21,'web',5),(22,'questions',5),(23,'answers',5);
/*!40000 ALTER TABLE `tag` ENABLE KEYS */;
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
  `reputation` float DEFAULT '0',
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
INSERT INTO `user` VALUES (4,'gskoropada@gmail.com','gskoropada',9,2,'abcde','$2y$10$VnKFUp8KpFViocdhAjoO4eci21n60SzE0HheAZjGxKA9xQ9NBVl6i','','\0','\0'),(41,'user1@email.com','User 1',5,0,'f4c6703674540','$2y$10$ba.9LVnaEd2Sv2KrSD330.5Ns9W7FBwB0Y6WvF8oyD8MY42WwsFdy','\0','','\0'),(43,'user3@email.com','User 3',0,0,'5d41746f2e9f0','$2y$10$be3DO40Dt9AFTCMg7ZBWDOzKGo46UHOHkM8WPEzYGF6kcX8rfl5eG','','','\0'),(44,'user4@email.com','User 4',0,0,'74afe5b9290b2','$2y$10$cj4QMSXIy.gZGx9letFNwOo60P8cbqz7gw5SeznlOKBh9eD0aIP2O','\0','','\0'),(47,'user7@email.com','User 7',0,0,'65443fdedb209','$2y$10$g2WHT6dgCudQ3jYkmOPimeqY8cSqQivehvgklw.wy9LK4RIR1CH0e','\0','','\0'),(48,'user8@email.com','User 8',0,0,'0257c54ba8e90','$2y$10$rPeAFaBC9X0MszgzJxO3luKJ6tQdoppkgcecqtD/DW0YrLzWIqCx2','\0','','\0'),(49,'user9@email.com','User 9',0,0,'9ef0f42619d05','$2y$10$LumWKf16RriG2MBjHXBxBecfP011RUYIv28rq0Z450.43ndWtId4u','\0','','\0'),(50,'user10@email.com','User 10',0,0,'6c01542a1e207','$2y$10$hT4XUxWr8IwMD13d8vN41eGui2wRVa5rBzQydWMgb6RH/kqo545ey','\0','','\0'),(51,'user11@email.com','User 11',0,0,'09a2a5e229405','$2y$10$HPg1FhjPAfK.XzpkM/jomO2m.Ax3ly0Xi1gTT9rgHvejR41vlwNbS','\0','','\0'),(52,'user12@email.com','User 12',0,0,'ea0025b202433','$2y$10$3GOpBNguAyRIWVZzOVgR0Oq6g/t2Uxkkje9Ae8n9kRUFCJ9.i4f0G','\0','','\0'),(53,'user13@email.com','User 13',0,0,'43a04f85382ea','$2y$10$8sA/uA7iZQ1bJEG519G2eOHx//U00.nn1YQ/f67rpD6pe8Lyp98Ky','\0','','\0'),(54,'sarah@email.com','Sarah',4,1,'a3a2a4e0de475','$2y$10$1jtI.LKVGNwuMnfuHTD40OgQZ5pbaDezoFZv83xQssfVwBWlAofre','','\0','\0');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_ban`
--

DROP TABLE IF EXISTS `user_ban`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_ban` (
  `user_id` int(10) unsigned NOT NULL,
  `moderator_id` int(10) unsigned NOT NULL,
  `expiry_date` date NOT NULL,
  `active` bit(2) NOT NULL,
  PRIMARY KEY (`user_id`),
  KEY `moderator_id` (`moderator_id`),
  CONSTRAINT `user_ban_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`UserID`),
  CONSTRAINT `user_ban_ibfk_2` FOREIGN KEY (`moderator_id`) REFERENCES `user` (`UserID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_ban`
--

LOCK TABLES `user_ban` WRITE;
/*!40000 ALTER TABLE `user_ban` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_ban` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Table structure for table `vote_tracking`
--

DROP TABLE IF EXISTS `vote_tracking`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vote_tracking` (
  `author` int(10) unsigned NOT NULL,
  `answer` int(10) unsigned NOT NULL,
  `timestamp` datetime DEFAULT NULL,
  PRIMARY KEY (`author`,`answer`),
  KEY `vote_answer_idx` (`answer`),
  CONSTRAINT `vote_answer` FOREIGN KEY (`answer`) REFERENCES `answer` (`answer_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `vote_author` FOREIGN KEY (`author`) REFERENCES `user` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vote_tracking`
--

LOCK TABLES `vote_tracking` WRITE;
/*!40000 ALTER TABLE `vote_tracking` DISABLE KEYS */;
INSERT INTO `vote_tracking` VALUES (4,21,'2014-10-03 10:04:42'),(4,28,'2014-10-06 05:19:46'),(4,30,'2014-10-14 02:34:00'),(4,31,'2014-10-14 02:39:23'),(4,34,'2014-10-14 02:53:32'),(41,31,'2014-10-14 02:44:59'),(54,22,'2014-10-03 11:29:00'),(54,23,'2014-10-03 11:28:57'),(54,24,'2014-10-03 11:29:02'),(54,25,'2014-10-03 11:28:54'),(54,26,'2014-10-03 11:33:58'),(54,27,'2014-10-03 11:34:05'),(54,30,'2014-10-14 02:36:43');
/*!40000 ALTER TABLE `vote_tracking` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-10-14 12:00:42
