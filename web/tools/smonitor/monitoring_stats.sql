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
-- Table structure for table `monitoring_stats`
--

DROP TABLE IF EXISTS `monitoring_stats`;
CREATE TABLE `monitoring_stats` (
  `name` varchar(64) NOT NULL,
  `time` int(11) NOT NULL,
  `value` varchar(64) NOT NULL default '0',
  `box_id` mediumint(8) unsigned NOT NULL default '0'
#  PRIMARY KEY  (`name`,`time`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `monitoring_stats`
--

LOCK TABLES `monitoring_stats` WRITE;
/*!40000 ALTER TABLE `monitoring_stats` DISABLE KEYS */;
INSERT INTO `monitoring_stats` VALUES ('dialog:activedialogs',1173477601,'0',0),('msilo',1173477601,'0',0),('shmem:max_used_size',1173477601,'0',0),('shmem:real_used_size',1173477601,'0',0),('sl',1173477601,'0',0),('tm:inuse_transactions',1173477601,'0',0),('usrloc:location-contacts',1173477601,'0',0),('usrloc:location-users',1173477601,'0',0),('dialog:activedialogs',1173477901,'0',0),('msilo',1173477901,'0',0),('shmem:max_used_size',1173477901,'0',0),('shmem:real_used_size',1173477901,'0',0),('sl',1173477901,'0',0),('tm:inuse_transactions',1173477901,'0',0),('usrloc:location-contacts',1173477901,'0',0),('usrloc:location-users',1173477901,'0',0),('dialog:activedialogs',1173478201,'0',0),('msilo',1173478201,'0',0),('shmem:max_used_size',1173478201,'0',0),('shmem:real_used_size',1173478201,'0',0),('sl',1173478201,'0',0),('tm:inuse_transactions',1173478201,'0',0),('usrloc:location-contacts',1173478201,'0',0),('usrloc:location-users',1173478201,'0',0),('dialog:activedialogs',1173478502,'0',0),('msilo',1173478502,'0',0),('shmem:max_used_size',1173478502,'0',0),('shmem:real_used_size',1173478502,'0',0),('sl',1173478502,'0',0),('tm:inuse_transactions',1173478502,'0',0),('usrloc:location-contacts',1173478502,'0',0),('usrloc:location-users',1173478502,'0',0);
/*!40000 ALTER TABLE `monitoring_stats` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2007-03-09 22:16:08
