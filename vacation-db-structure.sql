# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.7.13-0ubuntu0.16.04.2)
# Database: vacation
# Generation Time: 2016-10-19 22:10:08 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table absence
# ------------------------------------------------------------

DROP TABLE IF EXISTS `absence`;

CREATE TABLE `absence` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `absence_type_id` int(2) NOT NULL DEFAULT '1',
  `absence_status_id` int(2) NOT NULL,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


# Dump of table absence_status
# ------------------------------------------------------------

DROP TABLE IF EXISTS `absence_status`;

CREATE TABLE `absence_status` (
  `id` int(2) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `absence_status` WRITE;
/*!40000 ALTER TABLE `absence_status` DISABLE KEYS */;

INSERT INTO `absence_status` (`id`, `name`)
VALUES
	(1,'pending'),
	(2,'approved'),
	(3,'rejected');

/*!40000 ALTER TABLE `absence_status` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table absence_type
# ------------------------------------------------------------

DROP TABLE IF EXISTS `absence_type`;

CREATE TABLE `absence_type` (
  `id` int(2) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `absence_type` WRITE;
/*!40000 ALTER TABLE `absence_type` DISABLE KEYS */;

INSERT INTO `absence_type` (`id`, `name`)
VALUES
	(1,'Vacation'),
	(2,'Sick leave'),
	(3,'Unpaid leave');

/*!40000 ALTER TABLE `absence_type` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `last_name` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


# Dump of table user_absence
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_absence`;

CREATE TABLE `user_absence` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL COMMENT 'Id of user from table `user`.',
  `year` year(4) NOT NULL,
  `absence_type_id` int(10) NOT NULL COMMENT 'Id from table `absence_type`',
  `days_left` int(3) NOT NULL DEFAULT '0' COMMENT 'Id from table `absence_status`',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
