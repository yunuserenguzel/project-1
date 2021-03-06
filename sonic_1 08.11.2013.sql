-- phpMyAdmin SQL Dump
-- version 4.0.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 08, 2013 at 11:15 PM
-- Server version: 5.0.96-community-log
-- PHP Version: 5.3.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `sonic_1`
--

-- --------------------------------------------------------

--
-- Table structure for table `authentication`
--

CREATE TABLE IF NOT EXISTS `authentication` (
  `token` varchar(100) NOT NULL,
  `user_id` varchar(50) NOT NULL,
  `creation_date` datetime NOT NULL,
  `platform` varchar(50) NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  PRIMARY KEY  (`token`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `authentication`
--

INSERT INTO `authentication` (`token`, `user_id`, `creation_date`, `platform`, `is_active`) VALUES
('SNCKL001527bedc56798a527bedc568b28527bedc56ac69', 'SNCKL001527bedc565680', '2013-11-07 21:45:09', 'iOS', 1);

-- --------------------------------------------------------

--
-- Table structure for table `following`
--

CREATE TABLE IF NOT EXISTS `following` (
  `follower_id` varchar(50) NOT NULL,
  `followed_id` varchar(50) NOT NULL,
  PRIMARY KEY  (`follower_id`,`followed_id`),
  KEY `followed_id` (`followed_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE IF NOT EXISTS `likes` (
  `sonic_id` varchar(50) NOT NULL,
  `user_id` varchar(50) NOT NULL,
  PRIMARY KEY  (`sonic_id`,`user_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE IF NOT EXISTS `notification` (
  `id` varchar(50) NOT NULL,
  `type` varchar(50) NOT NULL,
  `actor_user_id` varchar(50) NOT NULL,
  `sonic_id` varchar(50) default NULL,
  `affected_user_id` varchar(50) default NULL,
  `is_read` tinyint(1) NOT NULL default '0',
  `action_date` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `actor_user_id` (`actor_user_id`,`sonic_id`,`affected_user_id`),
  KEY `actor_user_id_2` (`actor_user_id`),
  KEY `sonickle_id` (`sonic_id`),
  KEY `affected_user_id` (`affected_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sonic`
--

CREATE TABLE IF NOT EXISTS `sonic` (
  `id` varchar(50) NOT NULL,
  `creation_date` datetime NOT NULL,
  `latitude` float default NULL,
  `longitude` float default NULL,
  `isPrivate` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `creation_date` (`creation_date`,`latitude`,`longitude`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sonic`
--

INSERT INTO `sonic` (`id`, `creation_date`, `latitude`, `longitude`, `isPrivate`) VALUES
('SNCKL001527bf7fb04f69', '2013-11-07 22:28:43', 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `sonicowner`
--

CREATE TABLE IF NOT EXISTS `sonicowner` (
  `sonic_id` varchar(50) NOT NULL,
  `user_id` varchar(50) NOT NULL,
  PRIMARY KEY  (`sonic_id`,`user_id`),
  UNIQUE KEY `sonickle_id` (`sonic_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sonicowner`
--

INSERT INTO `sonicowner` (`sonic_id`, `user_id`) VALUES
('SNCKL001527bf7fb04f69', 'SNCKL001527bedc565680');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `passhash` varchar(250) NOT NULL,
  `profile_image` varchar(50) NOT NULL,
  `realname` varchar(100) NOT NULL,
  `email_validation` varchar(50) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `username` (`username`,`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `email`, `passhash`, `profile_image`, `realname`, `email_validation`) VALUES
('SNCKL001527bedc565680', 'testuser1383853509.4868350029', 'testuser1383853509.4869120121@sonicraph.com', 'e10adc3949ba59abbe56e057f20f883e', '', '', '');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `authentication`
--
ALTER TABLE `authentication`
  ADD CONSTRAINT `authentication_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `following`
--
ALTER TABLE `following`
  ADD CONSTRAINT `following_ibfk_1` FOREIGN KEY (`follower_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `following_ibfk_2` FOREIGN KEY (`followed_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_3` FOREIGN KEY (`sonic_id`) REFERENCES `sonic` (`id`),
  ADD CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `notification`
--
ALTER TABLE `notification`
  ADD CONSTRAINT `notification_ibfk_4` FOREIGN KEY (`sonic_id`) REFERENCES `sonic` (`id`),
  ADD CONSTRAINT `notification_ibfk_1` FOREIGN KEY (`actor_user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `notification_ibfk_3` FOREIGN KEY (`affected_user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `sonicowner`
--
ALTER TABLE `sonicowner`
  ADD CONSTRAINT `sonicowner_ibfk_3` FOREIGN KEY (`sonic_id`) REFERENCES `sonic` (`id`),
  ADD CONSTRAINT `sonicowner_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
         