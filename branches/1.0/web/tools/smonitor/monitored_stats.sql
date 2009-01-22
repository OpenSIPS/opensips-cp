-- MySQL dump 10.11
--
-- Host: localhost    Database: opensips
-- ------------------------------------------------------
-- Server version	5.0.32-Debian_7-log

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
-- Table structure for table `monitored_stats`
--

DROP TABLE IF EXISTS `monitored_stats`;
CREATE TABLE `monitored_stats` (
  `name` varchar(64) NOT NULL,
  `extra` varchar(64) NOT NULL,
  `box_id` mediumint(8) unsigned NOT NULL default '0'
#  PRIMARY KEY  (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `monitored_stats`
--

LOCK TABLES `monitored_stats` WRITE;
/*!40000 ALTER TABLE `monitored_stats` DISABLE KEYS */;
INSERT INTO `monitored_stats` VALUES ('sampling_time','5',0),('chart_size','700',0),('chart_history','auto',0),('dialog:activedialogs','',0),('shmem:real_used_size','',0),('sl','',0),('tm:inuse_transactions','',0),('msilo','',0),('usrloc:location-users','',0),('usrloc:location-contacts','',0),('shmem:max_used_size','',0);
/*!40000 ALTER TABLE `monitored_stats` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2007-03-09 22:15:57
