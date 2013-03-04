# ************************************************************
# Sequel Pro SQL dump
# Version 3408
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 127.0.0.1 (MySQL 5.5.18)
# Datenbank: chiron
# Erstellungsdauer: 2012-12-28 21:31:02 +0100
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Export von Tabelle feed
# ------------------------------------------------------------

DROP TABLE IF EXISTS `feed`;

CREATE TABLE `feed` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `url` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Export von Tabelle feed_meta
# ------------------------------------------------------------

DROP TABLE IF EXISTS `feed_meta`;

CREATE TABLE `feed_meta` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_feed` int(11) DEFAULT NULL,
  `id_user` int(11) DEFAULT '1',
  `meta_key` text CHARACTER SET latin1,
  `meta_value` text CHARACTER SET latin1,
  PRIMARY KEY (`id`),
  KEY `fk_feedmeta_to_uid` (`id_user`),
  KEY `fk_feedmeta_to_fid` (`id_feed`),
  CONSTRAINT `fk_feedmeta_to_fid` FOREIGN KEY (`id_feed`) REFERENCES `feed` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_feedmeta_to_uid` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Export von Tabelle item
# ------------------------------------------------------------

DROP TABLE IF EXISTS `item`;

CREATE TABLE `item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `source` text NOT NULL,
  `date` datetime NOT NULL,
  `title` text NOT NULL,
  `text` longtext NOT NULL,
  `url` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Export von Tabelle item_meta
# ------------------------------------------------------------

DROP TABLE IF EXISTS `item_meta`;

CREATE TABLE `item_meta` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_item` int(11) DEFAULT NULL,
  `id_user` int(11) DEFAULT '1',
  `meta_key` text CHARACTER SET latin1,
  `meta_value` text CHARACTER SET latin1,
  PRIMARY KEY (`id`),
  KEY `fk_itemmeta_to_uid` (`id_user`),
  KEY `fk_itemmeta_to_iid` (`id_item`),
  CONSTRAINT `fk_itemmeta_to_iid` FOREIGN KEY (`id_item`) REFERENCES `item` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_itemmeta_to_uid` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Export von Tabelle user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(128) NOT NULL DEFAULT '',
  `email` varchar(128) NOT NULL DEFAULT '',
  `firstname` varchar(256) NOT NULL DEFAULT '',
  `lastname` varchar(256) NOT NULL DEFAULT '',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_blocked` tinyint(1) NOT NULL DEFAULT '0',
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `date_firstlogin` timestamp NULL DEFAULT NULL,
  `date_lastlogin` timestamp NULL DEFAULT NULL,
  `password` text,
  `onetimecode` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `onetimecode` (`onetimecode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;

INSERT INTO `user` (`id`, `slug`, `email`, `firstname`, `lastname`, `is_active`, `is_blocked`, `is_admin`, `date_firstlogin`, `date_lastlogin`, `password`, `onetimecode`)
VALUES
	(1,'webmaster','webmaster@exmachina.ws','webmaster','ex machina',1,0,0,NULL,NULL,NULL,NULL);

/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
