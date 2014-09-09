/*
SQLyog Community v10.3 
MySQL - 5.5.24-log : Database - prensa_v2
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `newster_newsletterlists` */

DROP TABLE IF EXISTS `newster_newsletterlists`;

CREATE TABLE `newster_newsletterlists` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Table structure for table `newster_newsletters` */

DROP TABLE IF EXISTS `newster_newsletters`;

CREATE TABLE `newster_newsletters` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `send_date` datetime DEFAULT NULL,
  `newsletter_title` varchar(255) DEFAULT NULL,
  `newsletter_intro` text,
  `newsletter_text` text,
  `newsletter_code` text,
  `lists` text,
  `status` tinyint(3) DEFAULT NULL COMMENT '1 => Sin enviar, 2 => Envio pendiente, 3 => enviado',
  `last_total` int(11) DEFAULT NULL,
  `last_errors` int(11) DEFAULT NULL,
  `last_sent` int(11) DEFAULT NULL,
  `last_sent_date` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

/*Table structure for table `newster_newsletters_items` */

DROP TABLE IF EXISTS `newster_newsletters_items`;

CREATE TABLE `newster_newsletters_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `newsletter_id` int(11) NOT NULL,
  `related_model` varchar(50) NOT NULL,
  `related_id` int(11) DEFAULT NULL,
  `order_by` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `content` text,
  `pic_file_name` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

/*Table structure for table `newster_queue` */

DROP TABLE IF EXISTS `newster_queue`;

CREATE TABLE `newster_queue` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `newsletter_id` int(11) DEFAULT NULL,
  `newsletterlist_id` int(11) DEFAULT NULL,
  `attempts` int(11) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `values` text,
  `sent` tinyint(1) DEFAULT '0',
  `error` tinyint(1) DEFAULT '0',
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

/*Table structure for table `newster_subscribers` */

DROP TABLE IF EXISTS `newster_subscribers`;

CREATE TABLE `newster_subscribers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `company` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` text,
  `city` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `optout` tinyint(1) DEFAULT NULL,
  `optout_date` datetime DEFAULT NULL,
  `optout_ip` varchar(50) DEFAULT NULL,
  `added_manually` tinyint(1) DEFAULT NULL,
  `spam_reported` tinyint(1) DEFAULT NULL,
  `spam_reported_date` datetime DEFAULT NULL,
  `spam_reported_ip` varchar(50) DEFAULT NULL,
  `test_account` tinyint(1) DEFAULT NULL,
  `notes` text,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

/*Table structure for table `newster_subscribers_lists` */

DROP TABLE IF EXISTS `newster_subscribers_lists`;

CREATE TABLE `newster_subscribers_lists` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `subscriber_id` int(11) DEFAULT NULL,
  `newsletterlist_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
