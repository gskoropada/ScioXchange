-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Sep 02, 2014 at 12:30 AM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `php_assignment`
--

-- --------------------------------------------------------

--
-- Table structure for table `answer`
--

CREATE TABLE IF NOT EXISTS `answer` (
  `answer_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `author` int(10) unsigned NOT NULL,
  `content` longtext NOT NULL,
  `positive_votes` int(11) NOT NULL,
  `negative_votes` int(11) NOT NULL,
  PRIMARY KEY (`answer_id`),
  KEY `author` (`author`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE IF NOT EXISTS `comment` (
  `comment_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `comment_author` int(10) unsigned NOT NULL,
  `comment` longtext NOT NULL,
  `link` int(10) unsigned NOT NULL,
  `link_to` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`comment_id`),
  KEY `author` (`comment_author`),
  KEY `link` (`link`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`comment_id`, `comment_author`, `comment`, `link`, `link_to`) VALUES
(3, 2, 'How am I supposed to help you with your php if you can''t even create the message board for me to post the reply in?', 1, 1),
(4, 2, 'Come on Robin, get your shit together.', 2, 1),
(5, 1, 'Always pretend to have a king.', 1, NULL),
(8, 2, 'that was gibberish, and not very helpful', 4, NULL),
(13, 2, 'It worked!', 3, NULL),
(20, 1, 'sfds', 4, NULL),
(22, 1, 'Always pretend to have a king', 4, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE IF NOT EXISTS `message` (
  `message_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `from` int(10) unsigned NOT NULL,
  `subject` tinytext NOT NULL,
  `content` longtext NOT NULL,
  `reply_to` int(10) unsigned NOT NULL,
  PRIMARY KEY (`message_id`),
  KEY `from` (`from`,`reply_to`),
  KEY `reply_to` (`reply_to`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `message_recipient`
--

CREATE TABLE IF NOT EXISTS `message_recipient` (
  `mesage_id` int(10) unsigned NOT NULL,
  `recipient` int(10) unsigned NOT NULL,
  `read` bit(2) NOT NULL,
  PRIMARY KEY (`mesage_id`,`recipient`),
  KEY `recipient` (`recipient`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `moderator_scope`
--

CREATE TABLE IF NOT EXISTS `moderator_scope` (
  `moderator_id` int(10) unsigned NOT NULL,
  `tag_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`moderator_id`,`tag_id`),
  KEY `moderator_id` (`moderator_id`),
  KEY `tag_id` (`tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `question`
--

CREATE TABLE IF NOT EXISTS `question` (
  `question_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `author` int(10) unsigned NOT NULL COMMENT 'Foreign Key',
  `question_title` text,
  `content` longtext,
  `tags` text,
  PRIMARY KEY (`question_id`),
  KEY `author` (`author`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `question`
--

INSERT INTO `question` (`question_id`, `author`, `question_title`, `content`, `tags`) VALUES
(1, 1, 'php assignment', 'Hey Gabe, I really don''t understand what I''m supposed to be doing with this assignment. How does php work?', 'php, assignment2'),
(2, 1, 'time management', 'I spent all my time playing boardgames and have no time left to do my java assignment. Help!', 'java'),
(3, 1, 'This is a test', 'Maybe it will work this time.', 'test, doubts'),
(4, 2, 'Coup rules query', 'What are the rules for Coup?', 'non-work related, cardgames, coup');

-- --------------------------------------------------------

--
-- Table structure for table `question_tag`
--

CREATE TABLE IF NOT EXISTS `question_tag` (
  `question_id` int(10) unsigned NOT NULL,
  `tag_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`question_id`,`tag_id`),
  KEY `tag_id` (`tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tag`
--

CREATE TABLE IF NOT EXISTS `tag` (
  `tag_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tag` varchar(30) NOT NULL,
  PRIMARY KEY (`tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tag_heirarchy`
--

CREATE TABLE IF NOT EXISTS `tag_heirarchy` (
  `parent_tag_id` int(10) unsigned NOT NULL,
  `child_tag_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`parent_tag_id`,`child_tag_id`),
  KEY `child_tag_id` (`child_tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(30) NOT NULL,
  `screen_name` varchar(20) NOT NULL,
  `reputation` smallint(5) unsigned DEFAULT NULL,
  `role` tinyint(3) unsigned DEFAULT NULL,
  `avatar` text,
  `password` varchar(20) DEFAULT NULL,
  `active` bit(2) DEFAULT NULL,
  `moderated` bit(2) DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `email`, `screen_name`, `reputation`, `role`, `avatar`, `password`, `active`, `moderated`) VALUES
(1, 's3477769@student.rmit.edu.au', 'Robin', 5, 0, '', 'robin', b'00', b'00'),
(2, 's3479112@student.rmit.edu.au', 'Gabe', 6, 0, '', 'gabe', b'00', b'00'),
(3, 'sarah.mackinnon@rmit.edu.au', 'Sarah', 0, NULL, NULL, 'sarah', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_ban`
--

CREATE TABLE IF NOT EXISTS `user_ban` (
  `user_id` int(10) unsigned NOT NULL,
  `moderator_id` int(10) unsigned NOT NULL,
  `expiry_date` date NOT NULL,
  `active` bit(2) NOT NULL,
  PRIMARY KEY (`user_id`),
  KEY `moderator_id` (`moderator_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `answer`
--
ALTER TABLE `answer`
  ADD CONSTRAINT `answer_ibfk_1` FOREIGN KEY (`author`) REFERENCES `user` (`user_id`);

--
-- Constraints for table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`link`) REFERENCES `question` (`question_id`),
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`comment_author`) REFERENCES `user` (`user_id`);

--
-- Constraints for table `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `message_ibfk_1` FOREIGN KEY (`from`) REFERENCES `user` (`user_id`),
  ADD CONSTRAINT `message_ibfk_2` FOREIGN KEY (`reply_to`) REFERENCES `message` (`message_id`);

--
-- Constraints for table `message_recipient`
--
ALTER TABLE `message_recipient`
  ADD CONSTRAINT `message_recipient_ibfk_1` FOREIGN KEY (`mesage_id`) REFERENCES `message` (`message_id`),
  ADD CONSTRAINT `message_recipient_ibfk_2` FOREIGN KEY (`recipient`) REFERENCES `user` (`user_id`);

--
-- Constraints for table `moderator_scope`
--
ALTER TABLE `moderator_scope`
  ADD CONSTRAINT `moderator_scope_ibfk_1` FOREIGN KEY (`moderator_id`) REFERENCES `user` (`user_id`),
  ADD CONSTRAINT `moderator_scope_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `tag` (`tag_id`);

--
-- Constraints for table `question`
--
ALTER TABLE `question`
  ADD CONSTRAINT `question_ibfk_1` FOREIGN KEY (`author`) REFERENCES `user` (`user_id`);

--
-- Constraints for table `question_tag`
--
ALTER TABLE `question_tag`
  ADD CONSTRAINT `question_tag_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `question` (`question_id`),
  ADD CONSTRAINT `question_tag_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `tag` (`tag_id`);

--
-- Constraints for table `tag_heirarchy`
--
ALTER TABLE `tag_heirarchy`
  ADD CONSTRAINT `tag_heirarchy_ibfk_1` FOREIGN KEY (`parent_tag_id`) REFERENCES `tag` (`tag_id`),
  ADD CONSTRAINT `tag_heirarchy_ibfk_2` FOREIGN KEY (`child_tag_id`) REFERENCES `tag` (`tag_id`);

--
-- Constraints for table `user_ban`
--
ALTER TABLE `user_ban`
  ADD CONSTRAINT `user_ban_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`),
  ADD CONSTRAINT `user_ban_ibfk_2` FOREIGN KEY (`moderator_id`) REFERENCES `user` (`user_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
