# ************************************************************
# Sequel Pro SQL dump
# Version 3408
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 127.0.0.1 (MySQL 5.5.18)
# Datenbank: chiron
# Erstellungsdauer: 2013-03-06 14:00:05 +0100
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
  `type` text,
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
  `image` text,
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



# Export von Tabelle user_meta
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_meta`;

CREATE TABLE `user_meta` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(11) DEFAULT NULL,
  `meta_key` text,
  `meta_value` text,
  PRIMARY KEY (`id`),
  KEY `fk_usermeta_to_uid` (`id_user`),
  CONSTRAINT `fk_usermeta_to_uid` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;