# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.7.22)
# Database: wave
# Generation Time: 2018-09-11 20:44:27 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table announcement_user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `announcement_user`;

CREATE TABLE `announcement_user` (
  `announcement_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  KEY `announcement_user_announcement_id_index` (`announcement_id`),
  KEY `announcement_user_user_id_index` (`user_id`),
  CONSTRAINT `announcement_user_announcement_id_foreign` FOREIGN KEY (`announcement_id`) REFERENCES `announcements` (`id`) ON DELETE CASCADE,
  CONSTRAINT `announcement_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `announcement_user` WRITE;
/*!40000 ALTER TABLE `announcement_user` DISABLE KEYS */;

INSERT INTO `announcement_user` (`announcement_id`, `user_id`)
VALUES
	(1,1),
	(6,1),
	(1,9),
	(6,9),
	(7,1),
	(1,12),
	(6,12),
	(7,12),
	(1,14),
	(6,14),
	(7,14),
	(1,15),
	(6,15),
	(7,15),
	(1,16),
	(6,16),
	(7,16),
	(1,38),
	(6,38),
	(7,38),
	(1,44),
	(6,44),
	(7,44),
	(1,45),
	(6,45),
	(7,45),
	(1,47),
	(6,47),
	(7,47),
	(1,49),
	(6,49),
	(7,49),
	(1,50),
	(6,50),
	(7,50),
	(1,51),
	(6,51),
	(7,51),
	(1,53),
	(6,53),
	(7,53),
	(1,55),
	(6,55),
	(7,55),
	(1,59),
	(6,59),
	(7,59),
	(1,60),
	(6,60),
	(7,60),
	(1,61),
	(6,61),
	(7,61),
	(1,62),
	(6,62),
	(7,62),
	(1,63),
	(6,63),
	(7,63),
	(1,64),
	(6,64),
	(7,64),
	(1,65),
	(6,65),
	(7,65),
	(1,66),
	(6,66),
	(7,66),
	(1,67),
	(6,67),
	(7,67),
	(1,68),
	(6,68),
	(7,68),
	(1,69),
	(6,69),
	(7,69),
	(1,70),
	(6,70),
	(7,70),
	(1,71),
	(6,71),
	(7,71),
	(1,72),
	(6,72),
	(7,72),
	(1,73),
	(6,73),
	(7,73),
	(1,74),
	(6,74),
	(7,74);

/*!40000 ALTER TABLE `announcement_user` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table announcements
# ------------------------------------------------------------

DROP TABLE IF EXISTS `announcements`;

CREATE TABLE `announcements` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `announcements` WRITE;
/*!40000 ALTER TABLE `announcements` DISABLE KEYS */;

INSERT INTO `announcements` (`id`, `title`, `description`, `body`, `created_at`, `updated_at`)
VALUES
	(1,'Wave 1.0 Released','We have just released the first official version of Wave. Click here to learn more!','<p>It\'s been a fun Journey creating this awesome SAAS starter kit and we are super excited to use it in many of our future projects. There are just so many features that Wave has that will make building the SAAS of your dreams easier than ever before.</p>\r\n<p>Make sure to stay up-to-date on our latest releases as we will be releasing many more features down the road :)</p>\r\n<p>Thanks! Talk to you soon.</p>','2018-05-20 23:19:00','2018-05-21 00:38:02'),
	(6,'Test Notification','This announcement will tell you about a new feature. yada yada yada  yada yada yada  yada yada yada  yada yada yada','<p>This announcement will tell you about a new feature. yada yada yada&nbsp; yada yada yada&nbsp; yada yada yada&nbsp; yada yada yada&nbsp;This announcement will tell you about a new feature. yada yada yada&nbsp; yada yada yada&nbsp; yada yada yada&nbsp; yada yada yada&nbsp;This announcement will tell you about a new feature. yada yada yada&nbsp; yada yada yada&nbsp; yada yada yada&nbsp; yada yada yada&nbsp;This announcement will tell you about a new feature. yada yada yada&nbsp; yada yada yada&nbsp; yada yada yada&nbsp; yada yada yada&nbsp;This announcement will tell you about a new feature. yada yada yada&nbsp; yada yada yada&nbsp; yada yada yada&nbsp; yada yada yada&nbsp;</p>','2018-05-25 19:44:58','2018-05-25 19:44:58'),
	(7,'Testing new announcement','Hey, we just released a new feature. You gotta check it out.','<p>In this latest release we are bringing you a full API out of the box.</p>\r\n<p>This is going to be pretty rad!</p>\r\n<p><img src=\"https://media1.giphy.com/media/sT60kiGyVA94Q/200w.gif?cid=540216295b3533be343462796781e027\" /></p>\r\n<p>&nbsp;</p>','2018-06-28 19:15:20','2018-06-28 19:15:20');

/*!40000 ALTER TABLE `announcements` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table api_keys
# ------------------------------------------------------------

DROP TABLE IF EXISTS `api_keys`;

CREATE TABLE `api_keys` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `key` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `api_tokens_token_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `api_keys` WRITE;
/*!40000 ALTER TABLE `api_keys` DISABLE KEYS */;

INSERT INTO `api_keys` (`id`, `user_id`, `name`, `key`, `last_used_at`, `created_at`, `updated_at`)
VALUES
	(4,9,'awesome','vPE44wFaVJzTwzqg0jahd4gyveW1y4KeeJ2Ok0ci4CmOZ5tnZxQ4twnQYTUm',NULL,'2018-06-26 00:55:26','2018-06-26 00:55:26'),
	(6,1,'radsauce','p08UnPxJcnMSPENkEyzVGu5r1eLAEHbqik8SkFzQfYmbBc7mTMX4mqEMQFUY',NULL,'2018-06-28 19:30:26','2018-06-28 19:30:26'),
	(7,1,'toocool','k09AnE5c7WUVeAH3CjoAL0wiL3TqXy8esnDHsJVzh3zwl1wLl3tETejnyv5O',NULL,'2018-06-28 20:55:24','2018-06-28 20:55:24'),
	(8,12,'rad','dHNc2PRQU6M10A3VrYoTdBd3hAWGoLKBgM2p2CY1E9RbjtgvtpWiHaKjZGrM',NULL,'2018-07-03 21:50:50','2018-07-03 21:50:50'),
	(9,14,'awesome','Sad5EL9uDWYbUspi3ZqQlEqsZVPuHzbsIa5u7tKPLuA05QLHoELL7i2oZv5D',NULL,'2018-07-03 22:53:23','2018-07-03 22:53:23'),
	(11,1,'awesome','qMNrz86PYIItOmV0heEed47ySQ7qGvnbtFi8FphE3IiahBY3nHsU3ithqdkL',NULL,'2018-07-03 23:08:09','2018-07-03 23:08:09');

/*!40000 ALTER TABLE `api_keys` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table categories
# ------------------------------------------------------------

DROP TABLE IF EXISTS `categories`;

CREATE TABLE `categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT '1',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`),
  KEY `categories_parent_id_foreign` (`parent_id`),
  CONSTRAINT `categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;

INSERT INTO `categories` (`id`, `parent_id`, `order`, `name`, `slug`, `created_at`, `updated_at`)
VALUES
	(1,NULL,1,'Category 1','category-1','2017-11-21 16:23:22','2017-11-21 16:23:22'),
	(2,NULL,1,'Category 2','category-2','2017-11-21 16:23:22','2017-11-21 16:23:22');

/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table data_rows
# ------------------------------------------------------------

DROP TABLE IF EXISTS `data_rows`;

CREATE TABLE `data_rows` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `data_type_id` int(10) unsigned NOT NULL,
  `field` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `required` tinyint(1) NOT NULL DEFAULT '0',
  `browse` tinyint(1) NOT NULL DEFAULT '1',
  `read` tinyint(1) NOT NULL DEFAULT '1',
  `edit` tinyint(1) NOT NULL DEFAULT '1',
  `add` tinyint(1) NOT NULL DEFAULT '1',
  `delete` tinyint(1) NOT NULL DEFAULT '1',
  `details` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `order` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `data_rows_data_type_id_foreign` (`data_type_id`),
  CONSTRAINT `data_rows_data_type_id_foreign` FOREIGN KEY (`data_type_id`) REFERENCES `data_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `data_rows` WRITE;
/*!40000 ALTER TABLE `data_rows` DISABLE KEYS */;

INSERT INTO `data_rows` (`id`, `data_type_id`, `field`, `type`, `display_name`, `required`, `browse`, `read`, `edit`, `add`, `delete`, `details`, `order`)
VALUES
	(1,1,'id','number','ID',1,0,0,0,0,0,'',1),
	(2,1,'author_id','text','Author',1,0,1,1,0,1,'',2),
	(3,1,'category_id','text','Category',1,0,1,1,1,0,'',3),
	(4,1,'title','text','Title',1,1,1,1,1,1,'',4),
	(5,1,'excerpt','text_area','excerpt',1,0,1,1,1,1,'',5),
	(6,1,'body','rich_text_box','Body',1,0,1,1,1,1,'',6),
	(7,1,'image','image','Post Image',0,1,1,1,1,1,'{\"resize\":{\"width\":\"1000\",\"height\":\"null\"},\"quality\":\"70%\",\"upsize\":true,\"thumbnails\":[{\"name\":\"medium\",\"scale\":\"50%\"},{\"name\":\"small\",\"scale\":\"25%\"},{\"name\":\"cropped\",\"crop\":{\"width\":\"300\",\"height\":\"250\"}}]}',7),
	(8,1,'slug','text','slug',1,0,1,1,1,1,'{\"slugify\":{\"origin\":\"title\",\"forceUpdate\":true}}',8),
	(9,1,'meta_description','text_area','meta_description',1,0,1,1,1,1,'',9),
	(10,1,'meta_keywords','text_area','meta_keywords',1,0,1,1,1,1,'',10),
	(11,1,'status','select_dropdown','status',1,1,1,1,1,1,'{\"default\":\"DRAFT\",\"options\":{\"PUBLISHED\":\"published\",\"DRAFT\":\"draft\",\"PENDING\":\"pending\"}}',11),
	(12,1,'created_at','timestamp','created_at',0,1,1,0,0,0,'',12),
	(13,1,'updated_at','timestamp','updated_at',0,0,0,0,0,0,'',13),
	(14,2,'id','number','id',1,0,0,0,0,0,'',1),
	(15,2,'author_id','text','author_id',1,0,0,0,0,0,'',2),
	(16,2,'title','text','title',1,1,1,1,1,1,'',3),
	(17,2,'excerpt','text_area','excerpt',1,0,1,1,1,1,'',4),
	(18,2,'body','rich_text_box','body',1,0,1,1,1,1,'',5),
	(19,2,'slug','text','slug',1,0,1,1,1,1,'{\"slugify\":{\"origin\":\"title\"}}',6),
	(20,2,'meta_description','text','meta_description',1,0,1,1,1,1,'',7),
	(21,2,'meta_keywords','text','meta_keywords',1,0,1,1,1,1,'',8),
	(22,2,'status','select_dropdown','status',1,1,1,1,1,1,'{\"default\":\"INACTIVE\",\"options\":{\"INACTIVE\":\"INACTIVE\",\"ACTIVE\":\"ACTIVE\"}}',9),
	(23,2,'created_at','timestamp','created_at',1,1,1,0,0,0,'',10),
	(24,2,'updated_at','timestamp','updated_at',1,0,0,0,0,0,'',11),
	(25,2,'image','image','image',0,1,1,1,1,1,'',12),
	(26,3,'id','number','id',1,0,0,0,0,0,NULL,1),
	(27,3,'name','text','name',1,1,1,1,1,1,NULL,2),
	(28,3,'email','text','email',1,1,1,1,1,1,NULL,3),
	(29,3,'password','password','password',1,0,0,1,1,0,NULL,5),
	(30,3,'user_belongsto_role_relationship','relationship','Role',0,1,1,1,1,0,'{\"model\":\"TCG\\\\Voyager\\\\Models\\\\Role\",\"table\":\"roles\",\"type\":\"belongsToMany\",\"column\":\"role_id\",\"key\":\"id\",\"label\":\"display_name\",\"pivot_table\":\"user_roles\",\"pivot\":\"1\",\"taggable\":\"on\"}',11),
	(31,3,'remember_token','text','remember_token',0,0,0,0,0,0,NULL,6),
	(32,3,'created_at','timestamp','created_at',0,1,1,0,0,0,NULL,7),
	(33,3,'updated_at','timestamp','updated_at',0,0,0,0,0,0,NULL,8),
	(34,3,'avatar','image','avatar',0,1,1,1,1,1,NULL,9),
	(35,5,'id','number','id',1,0,0,0,0,0,'',1),
	(36,5,'name','text','name',1,1,1,1,1,1,'',2),
	(37,5,'created_at','timestamp','created_at',0,0,0,0,0,0,'',3),
	(38,5,'updated_at','timestamp','updated_at',0,0,0,0,0,0,'',4),
	(39,4,'id','number','id',1,0,0,0,0,0,'',1),
	(40,4,'parent_id','select_dropdown','parent_id',0,0,1,1,1,1,'{\"default\":\"\",\"null\":\"\",\"options\":{\"\":\"-- None --\"},\"relationship\":{\"key\":\"id\",\"label\":\"name\"}}',2),
	(41,4,'order','text','order',1,1,1,1,1,1,'{\"default\":1}',3),
	(42,4,'name','text','name',1,1,1,1,1,1,'',4),
	(43,4,'slug','text','slug',1,1,1,1,1,1,'{\"slugify\":{\"origin\":\"name\"}}',5),
	(44,4,'created_at','timestamp','created_at',0,0,1,0,0,0,'',6),
	(45,4,'updated_at','timestamp','updated_at',0,0,0,0,0,0,'',7),
	(46,6,'id','number','id',1,0,0,0,0,0,'',1),
	(47,6,'name','text','Name',1,1,1,1,1,1,'',2),
	(48,6,'created_at','timestamp','created_at',0,0,0,0,0,0,'',3),
	(49,6,'updated_at','timestamp','updated_at',0,0,0,0,0,0,'',4),
	(50,6,'display_name','text','Display Name',1,1,1,1,1,1,'',5),
	(51,1,'seo_title','text','seo_title',0,1,1,1,1,1,'',14),
	(52,1,'featured','checkbox','featured',1,1,1,1,1,1,'',15),
	(53,3,'role_id','text','role_id',0,1,1,1,1,1,NULL,10),
	(54,3,'username','text','Username',1,1,1,1,1,1,NULL,4),
	(55,7,'id','hidden','Id',1,0,0,0,0,0,NULL,1),
	(56,7,'title','text','Title',1,1,1,1,1,1,NULL,2),
	(57,7,'description','text_area','Description (max 250 characters)',1,1,1,1,1,1,NULL,3),
	(58,7,'body','rich_text_box','Body',1,0,1,1,1,1,NULL,4),
	(59,7,'created_at','timestamp','Created At',0,1,1,1,0,1,NULL,5),
	(60,7,'updated_at','timestamp','Updated At',0,0,0,0,0,0,NULL,6),
	(61,3,'settings','hidden','Settings',0,1,1,1,1,1,NULL,9),
	(62,3,'user_belongstomany_role_relationship','relationship','Roles',0,1,1,1,1,0,'{\"model\":\"TCG\\\\Voyager\\\\Models\\\\Role\",\"table\":\"roles\",\"type\":\"belongsToMany\",\"column\":\"id\",\"key\":\"id\",\"label\":\"name\",\"pivot_table\":\"user_roles\",\"pivot\":\"1\"}',11),
	(63,3,'locale','text','Locale',0,1,1,1,1,0,'',12),
	(64,8,'id','hidden','Id',1,0,0,0,0,0,NULL,1),
	(65,8,'name','text','Name (Basic, Standard, Premium, etc)',1,1,1,1,1,1,NULL,3),
	(66,8,'description','text_area','Description (optional)',0,0,1,1,1,1,NULL,6),
	(67,8,'features','text_area','Features (comma separated)',1,0,1,1,1,1,NULL,4),
	(69,8,'role_id','text','Role Id',1,1,1,1,1,1,NULL,2),
	(70,8,'created_at','timestamp','Created At',0,1,0,0,0,1,NULL,8),
	(71,8,'updated_at','timestamp','Updated At',0,0,0,0,0,0,NULL,9),
	(72,8,'plan_belongsto_role_relationship','relationship','Role (role permissions for this plan)',0,1,1,1,1,1,'{\"model\":\"TCG\\\\Voyager\\\\Models\\\\Role\",\"table\":\"roles\",\"type\":\"belongsTo\",\"column\":\"role_id\",\"key\":\"id\",\"label\":\"name\",\"pivot_table\":\"announcement_user\",\"pivot\":\"0\",\"taggable\":\"0\"}',5),
	(73,8,'default','checkbox','Default (Make this the default plan)',1,0,1,1,1,1,NULL,7),
	(74,8,'price','text','Price (for display purposes only)',1,1,1,1,1,1,NULL,8),
	(75,8,'plan_id','text','Plan Id',1,1,1,1,1,1,NULL,6),
	(76,8,'trial_days','number','Trial Days (If none, set to 0)',1,0,1,1,1,1,NULL,9);

/*!40000 ALTER TABLE `data_rows` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table data_types
# ------------------------------------------------------------

DROP TABLE IF EXISTS `data_types`;

CREATE TABLE `data_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name_singular` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name_plural` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `policy_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `controller` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `generate_permissions` tinyint(1) NOT NULL DEFAULT '0',
  `server_side` tinyint(4) NOT NULL DEFAULT '0',
  `details` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `data_types_name_unique` (`name`),
  UNIQUE KEY `data_types_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `data_types` WRITE;
/*!40000 ALTER TABLE `data_types` DISABLE KEYS */;

INSERT INTO `data_types` (`id`, `name`, `slug`, `display_name_singular`, `display_name_plural`, `icon`, `model_name`, `policy_name`, `controller`, `description`, `generate_permissions`, `server_side`, `details`, `created_at`, `updated_at`)
VALUES
	(1,'posts','posts','Post','Posts','voyager-news','TCG\\Voyager\\Models\\Post','TCG\\Voyager\\Policies\\PostPolicy','','',1,0,NULL,'2017-11-21 16:23:22','2017-11-21 16:23:22'),
	(2,'pages','pages','Page','Pages','voyager-file-text','TCG\\Voyager\\Models\\Page',NULL,'','',1,0,NULL,'2017-11-21 16:23:22','2017-11-21 16:23:22'),
	(3,'users','users','User','Users','voyager-person','TCG\\Voyager\\Models\\User','TCG\\Voyager\\Policies\\UserPolicy',NULL,NULL,1,0,'{\"order_column\":null,\"order_display_column\":null}','2017-11-21 16:23:22','2018-06-22 20:29:47'),
	(4,'categories','categories','Category','Categories','voyager-categories','TCG\\Voyager\\Models\\Category',NULL,'','',1,0,NULL,'2017-11-21 16:23:22','2017-11-21 16:23:22'),
	(5,'menus','menus','Menu','Menus','voyager-list','TCG\\Voyager\\Models\\Menu',NULL,'','',1,0,NULL,'2017-11-21 16:23:22','2017-11-21 16:23:22'),
	(6,'roles','roles','Role','Roles','voyager-lock','TCG\\Voyager\\Models\\Role',NULL,'','',1,0,NULL,'2017-11-21 16:23:22','2017-11-21 16:23:22'),
	(7,'announcements','announcements','Announcement','Announcements','voyager-megaphone','Wave\\Announcement',NULL,NULL,NULL,1,0,NULL,'2018-05-20 21:08:14','2018-05-20 21:08:14'),
	(8,'plans','plans','Plan','Plans','voyager-logbook','Wave\\Plan',NULL,NULL,NULL,1,0,'{\"order_column\":null,\"order_display_column\":null}','2018-07-03 04:50:28','2018-07-03 04:50:28');

/*!40000 ALTER TABLE `data_types` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table menu_items
# ------------------------------------------------------------

DROP TABLE IF EXISTS `menu_items`;

CREATE TABLE `menu_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `menu_id` int(10) unsigned DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `target` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '_self',
  `icon_class` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `order` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `route` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parameters` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `menu_items_menu_id_foreign` (`menu_id`),
  CONSTRAINT `menu_items_menu_id_foreign` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `menu_items` WRITE;
/*!40000 ALTER TABLE `menu_items` DISABLE KEYS */;

INSERT INTO `menu_items` (`id`, `menu_id`, `title`, `url`, `target`, `icon_class`, `color`, `parent_id`, `order`, `created_at`, `updated_at`, `route`, `parameters`)
VALUES
	(1,1,'Dashboard','','_self','voyager-boat',NULL,NULL,1,'2017-11-21 16:23:22','2017-11-21 16:23:22','voyager.dashboard',NULL),
	(2,1,'Media','','_self','voyager-images',NULL,NULL,5,'2017-11-21 16:23:22','2018-07-03 04:51:09','voyager.media.index',NULL),
	(3,1,'Posts','','_self','voyager-news',NULL,NULL,6,'2017-11-21 16:23:22','2018-07-03 04:51:09','voyager.posts.index',NULL),
	(4,1,'Users','','_self','voyager-person',NULL,NULL,4,'2017-11-21 16:23:22','2018-07-03 04:51:09','voyager.users.index',NULL),
	(5,1,'Categories','','_self','voyager-categories',NULL,NULL,8,'2017-11-21 16:23:22','2018-07-03 04:51:09','voyager.categories.index',NULL),
	(6,1,'Pages','','_self','voyager-file-text',NULL,NULL,7,'2017-11-21 16:23:22','2018-07-03 04:51:09','voyager.pages.index',NULL),
	(7,1,'Roles','','_self','voyager-lock',NULL,NULL,3,'2017-11-21 16:23:22','2018-07-03 04:51:09','voyager.roles.index',NULL),
	(8,1,'Tools','','_self','voyager-tools',NULL,NULL,10,'2017-11-21 16:23:22','2018-07-03 04:51:03',NULL,NULL),
	(9,1,'Menu Builder','','_self','voyager-list',NULL,8,1,'2017-11-21 16:23:22','2018-05-20 21:08:37','voyager.menus.index',NULL),
	(10,1,'Database','','_self','voyager-data',NULL,8,2,'2017-11-21 16:23:22','2018-05-20 21:08:37','voyager.database.index',NULL),
	(11,1,'Compass','/admin/compass','_self','voyager-compass',NULL,8,3,'2017-11-21 16:23:22','2018-05-20 21:08:37',NULL,NULL),
	(12,1,'Hooks','/admin/hooks','_self','voyager-hook','#000000',8,5,'2017-11-21 16:23:22','2018-06-22 20:55:55',NULL,''),
	(13,1,'Settings','','_self','voyager-settings',NULL,NULL,11,'2017-11-21 16:23:22','2018-07-03 04:51:04','voyager.settings.index',NULL),
	(14,1,'Themes','/admin/themes','_self','voyager-paint-bucket',NULL,NULL,12,'2017-11-21 16:31:00','2018-07-03 04:51:04',NULL,NULL),
	(15,2,'Dashboard','','_self','home','#000000',NULL,1,'2017-11-28 14:48:21','2018-03-23 16:25:44','wave.dashboard','null'),
	(16,2,'Resources','#_','_self','info','#000000',NULL,2,'2017-11-28 14:49:36','2017-11-28 15:11:13',NULL,''),
	(19,2,'Next Child','/next','_self',NULL,'#000000',18,1,'2017-11-28 14:56:58','2017-11-28 14:57:10',NULL,''),
	(20,2,'Next Child 2','/next','_self',NULL,'#000000',18,2,'2017-11-28 14:57:07','2017-11-28 14:57:12',NULL,''),
	(21,2,'Documentation','','_self',NULL,'#000000',16,1,'2017-11-28 15:08:56','2017-11-28 15:09:14',NULL,''),
	(22,2,'Videos','','_self',NULL,'#000000',16,2,'2017-11-28 15:09:22','2017-11-28 15:09:25',NULL,''),
	(23,2,'Support','','_self','lifesaver','#000000',NULL,3,'2017-11-28 15:09:56','2018-03-31 18:22:05',NULL,''),
	(25,2,'Blog','/blog','_self',NULL,'#000000',16,3,'2018-03-31 18:22:02','2018-03-31 18:22:08',NULL,''),
	(26,3,'Home','/#','_self',NULL,'#000000',NULL,99,'2018-04-13 22:29:33','2018-08-28 18:39:05',NULL,''),
	(27,3,'Features','/#features','_self',NULL,'#000000',NULL,100,'2018-04-13 22:30:26','2018-08-28 00:24:49',NULL,''),
	(28,3,'Testimonials','/#testimonials','_self',NULL,'#000000',NULL,101,'2018-04-13 22:31:03','2018-08-28 00:24:57',NULL,''),
	(29,3,'Pricing','/#pricing','_self',NULL,'#000000',NULL,102,'2018-04-13 22:31:52','2018-08-28 00:25:04',NULL,''),
	(30,1,'Announcements','/admin/announcements','_self','voyager-megaphone',NULL,NULL,9,'2018-05-20 21:08:14','2018-07-03 04:51:03',NULL,NULL),
	(31,1,'BREAD','','_self','voyager-bread','#000000',8,4,'2018-06-22 20:53:25','2018-06-22 20:54:13','voyager.bread.index',NULL),
	(32,1,'Plans','','_self','voyager-logbook',NULL,NULL,2,'2018-07-03 04:50:28','2018-07-03 04:51:09','voyager.plans.index',NULL),
	(33,3,'Blog','','_self',NULL,'#000000',NULL,103,'2018-08-24 19:41:14','2018-08-24 19:41:14','wave.blog',NULL);

/*!40000 ALTER TABLE `menu_items` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table menus
# ------------------------------------------------------------

DROP TABLE IF EXISTS `menus`;

CREATE TABLE `menus` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `menus_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `menus` WRITE;
/*!40000 ALTER TABLE `menus` DISABLE KEYS */;

INSERT INTO `menus` (`id`, `name`, `created_at`, `updated_at`)
VALUES
	(1,'admin','2017-11-21 16:23:22','2017-11-21 16:23:22'),
	(2,'authenticated-menu','2017-11-28 14:47:49','2018-04-13 22:25:28'),
	(3,'guest-menu','2018-04-13 22:25:37','2018-04-13 22:25:37');

/*!40000 ALTER TABLE `menus` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table migrations
# ------------------------------------------------------------

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;

INSERT INTO `migrations` (`id`, `migration`, `batch`)
VALUES
	(1,'2014_10_12_000000_create_users_table',1),
	(2,'2014_10_12_100000_create_password_resets_table',1),
	(3,'2016_01_01_000000_add_voyager_user_fields',2),
	(4,'2016_01_01_000000_create_data_types_table',2),
	(5,'2016_01_01_000000_create_pages_table',2),
	(6,'2016_01_01_000000_create_posts_table',2),
	(7,'2016_02_15_204651_create_categories_table',2),
	(8,'2016_05_19_173453_create_menu_table',2),
	(9,'2016_10_21_190000_create_roles_table',2),
	(10,'2016_10_21_190000_create_settings_table',2),
	(11,'2016_11_30_135954_create_permission_table',2),
	(12,'2016_11_30_141208_create_permission_role_table',2),
	(13,'2016_12_26_201236_data_types__add__server_side',2),
	(14,'2017_01_13_000000_add_route_to_menu_items_table',2),
	(15,'2017_01_14_005015_create_translations_table',2),
	(16,'2017_01_15_000000_add_permission_group_id_to_permissions_table',2),
	(17,'2017_01_15_000000_create_permission_groups_table',2),
	(18,'2017_01_15_000000_make_table_name_nullable_in_permissions_table',2),
	(19,'2017_03_06_000000_add_controller_to_data_types_table',2),
	(20,'2017_04_11_000000_alter_post_nullable_fields_table',2),
	(21,'2017_04_21_000000_add_order_to_data_rows_table',2),
	(22,'2017_07_05_210000_add_policyname_to_data_types_table',2),
	(23,'2017_08_05_000000_add_group_to_settings_table',2),
	(24,'2018_04_15_143034_add_username_to_users_table',3),
	(27,'2018_04_22_020900_create_wave_key_values_table',4),
	(28,'2018_05_20_204156_create_announcements_table',5),
	(29,'2018_05_20_205346_create_announcement_user_table',5),
	(30,'2018_05_23_234956_create_notifications_table',6);

/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table notifications
# ------------------------------------------------------------

DROP TABLE IF EXISTS `notifications`;

CREATE TABLE `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` int(10) unsigned NOT NULL,
  `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_id_notifiable_type_index` (`notifiable_id`,`notifiable_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table pages
# ------------------------------------------------------------

DROP TABLE IF EXISTS `pages`;

CREATE TABLE `pages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `author_id` int(11) NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `excerpt` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `body` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `meta_keywords` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` enum('ACTIVE','INACTIVE') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'INACTIVE',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pages_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `pages` WRITE;
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;

INSERT INTO `pages` (`id`, `author_id`, `title`, `excerpt`, `body`, `image`, `slug`, `meta_description`, `meta_keywords`, `status`, `created_at`, `updated_at`)
VALUES
	(1,0,'Hello World','Hang the jib grog grog blossom grapple dance the hempen jig gangway pressgang bilge rat to go on account lugger. Nelsons folly gabion line draught scallywag fire ship gaff fluke fathom case shot. Sea Legs bilge rat sloop matey gabion long clothes run a shot across the bow Gold Road cog league.','<p>Hello World. Scallywag grog swab Cat o\'nine tails scuttle rigging hardtack cable nipper Yellow Jack. Handsomely spirits knave lad killick landlubber or just lubber deadlights chantey pinnace crack Jennys tea cup. Provost long clothes black spot Yellow Jack bilged on her anchor league lateen sail case shot lee tackle.</p>\n<p>Ballast spirits fluke topmast me quarterdeck schooner landlubber or just lubber gabion belaying pin. Pinnace stern galleon starboard warp carouser to go on account dance the hempen jig jolly boat measured fer yer chains. Man-of-war fire in the hole nipperkin handsomely doubloon barkadeer Brethren of the Coast gibbet driver squiffy.</p>','pages/page1.jpg','hello-world','Yar Meta Description','Keyword1, Keyword2','ACTIVE','2017-11-21 16:23:23','2017-11-21 16:23:23'),
	(2,1,'About','This is the about page.','<p>Wave is the ultimate&nbsp;Software as a Service Starter kit. If you\'ve ever wanted to create your own SAAS application, Wave can help save you hundreds of hours. Wave is one of a kind and it is built on top of Laravel and Voyager. Building your application is going to be funner&nbsp;than ever before... Funner may not be a real word, but you get where I\'m trying to go.</p>\r\n<p>Wave has a bunch of functionality built-in that will save you a bunch of time. Your users will be able to update their settings, billing information, profile information and so much more. You will also be able to accept&nbsp;payments from your user with multiple vendors.</p>\r\n<p>We want to help you build the SAAS of your dreams by making it easier and less time-consuming. Let\'s start creating some \"Waves\" and build out the SAAS in your particular niche... Sorry about that Wave pun...</p>',NULL,'about','About Wave','about, wave','ACTIVE','2018-03-30 03:04:51','2018-03-30 03:04:51');

/*!40000 ALTER TABLE `pages` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table password_resets
# ------------------------------------------------------------

DROP TABLE IF EXISTS `password_resets`;

CREATE TABLE `password_resets` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table permission_groups
# ------------------------------------------------------------

DROP TABLE IF EXISTS `permission_groups`;

CREATE TABLE `permission_groups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permission_groups_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table permission_role
# ------------------------------------------------------------

DROP TABLE IF EXISTS `permission_role`;

CREATE TABLE `permission_role` (
  `permission_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `permission_role_permission_id_index` (`permission_id`),
  KEY `permission_role_role_id_index` (`role_id`),
  CONSTRAINT `permission_role_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `permission_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `permission_role` WRITE;
/*!40000 ALTER TABLE `permission_role` DISABLE KEYS */;

INSERT INTO `permission_role` (`permission_id`, `role_id`)
VALUES
	(1,1),
	(2,1),
	(3,1),
	(4,1),
	(5,1),
	(6,1),
	(6,2),
	(6,3),
	(6,4),
	(6,5),
	(7,1),
	(7,2),
	(7,3),
	(7,4),
	(7,5),
	(8,1),
	(9,1),
	(10,1),
	(11,1),
	(12,1),
	(13,1),
	(14,1),
	(15,1),
	(16,1),
	(16,3),
	(16,4),
	(16,5),
	(17,1),
	(17,3),
	(17,4),
	(17,5),
	(18,1),
	(19,1),
	(20,1),
	(21,1),
	(22,1),
	(23,1),
	(24,1),
	(25,1),
	(26,1),
	(26,2),
	(26,3),
	(26,4),
	(26,5),
	(27,1),
	(27,2),
	(27,3),
	(27,4),
	(27,5),
	(28,1),
	(29,1),
	(30,1),
	(31,1),
	(31,2),
	(31,3),
	(31,4),
	(31,5),
	(32,1),
	(32,2),
	(32,3),
	(32,4),
	(32,5),
	(33,1),
	(34,1),
	(35,1),
	(36,1),
	(36,2),
	(36,3),
	(36,4),
	(36,5),
	(37,1),
	(37,2),
	(37,3),
	(37,4),
	(37,5),
	(38,1),
	(39,1),
	(40,1),
	(41,1),
	(42,1),
	(42,2),
	(42,3),
	(42,4),
	(42,5),
	(43,1),
	(43,2),
	(43,3),
	(43,4),
	(43,5),
	(44,1),
	(45,1),
	(46,1),
	(47,1),
	(48,1),
	(49,1),
	(50,1),
	(51,1),
	(52,1),
	(53,1),
	(54,1),
	(55,1),
	(56,1),
	(57,1);

/*!40000 ALTER TABLE `permission_role` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table permissions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `permissions`;

CREATE TABLE `permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `table_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `permission_group_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `permissions_key_index` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;

INSERT INTO `permissions` (`id`, `key`, `table_name`, `created_at`, `updated_at`, `permission_group_id`)
VALUES
	(1,'browse_admin',NULL,'2018-06-22 20:15:45','2018-06-22 20:15:45',NULL),
	(2,'browse_bread',NULL,'2018-06-22 20:15:45','2018-06-22 20:15:45',NULL),
	(3,'browse_database',NULL,'2018-06-22 20:15:45','2018-06-22 20:15:45',NULL),
	(4,'browse_media',NULL,'2018-06-22 20:15:45','2018-06-22 20:15:45',NULL),
	(5,'browse_compass',NULL,'2018-06-22 20:15:45','2018-06-22 20:15:45',NULL),
	(6,'browse_menus','menus','2018-06-22 20:15:45','2018-06-22 20:15:45',NULL),
	(7,'read_menus','menus','2018-06-22 20:15:45','2018-06-22 20:15:45',NULL),
	(8,'edit_menus','menus','2018-06-22 20:15:45','2018-06-22 20:15:45',NULL),
	(9,'add_menus','menus','2018-06-22 20:15:45','2018-06-22 20:15:45',NULL),
	(10,'delete_menus','menus','2018-06-22 20:15:45','2018-06-22 20:15:45',NULL),
	(11,'browse_roles','roles','2018-06-22 20:15:45','2018-06-22 20:15:45',NULL),
	(12,'read_roles','roles','2018-06-22 20:15:45','2018-06-22 20:15:45',NULL),
	(13,'edit_roles','roles','2018-06-22 20:15:45','2018-06-22 20:15:45',NULL),
	(14,'add_roles','roles','2018-06-22 20:15:45','2018-06-22 20:15:45',NULL),
	(15,'delete_roles','roles','2018-06-22 20:15:45','2018-06-22 20:15:45',NULL),
	(16,'browse_users','users','2018-06-22 20:15:45','2018-06-22 20:15:45',NULL),
	(17,'read_users','users','2018-06-22 20:15:45','2018-06-22 20:15:45',NULL),
	(18,'edit_users','users','2018-06-22 20:15:45','2018-06-22 20:15:45',NULL),
	(19,'add_users','users','2018-06-22 20:15:45','2018-06-22 20:15:45',NULL),
	(20,'delete_users','users','2018-06-22 20:15:45','2018-06-22 20:15:45',NULL),
	(21,'browse_settings','settings','2018-06-22 20:15:45','2018-06-22 20:15:45',NULL),
	(22,'read_settings','settings','2018-06-22 20:15:45','2018-06-22 20:15:45',NULL),
	(23,'edit_settings','settings','2018-06-22 20:15:45','2018-06-22 20:15:45',NULL),
	(24,'add_settings','settings','2018-06-22 20:15:45','2018-06-22 20:15:45',NULL),
	(25,'delete_settings','settings','2018-06-22 20:15:45','2018-06-22 20:15:45',NULL),
	(26,'browse_categories','categories','2018-06-22 20:15:46','2018-06-22 20:15:46',NULL),
	(27,'read_categories','categories','2018-06-22 20:15:46','2018-06-22 20:15:46',NULL),
	(28,'edit_categories','categories','2018-06-22 20:15:46','2018-06-22 20:15:46',NULL),
	(29,'add_categories','categories','2018-06-22 20:15:46','2018-06-22 20:15:46',NULL),
	(30,'delete_categories','categories','2018-06-22 20:15:46','2018-06-22 20:15:46',NULL),
	(31,'browse_posts','posts','2018-06-22 20:15:46','2018-06-22 20:15:46',NULL),
	(32,'read_posts','posts','2018-06-22 20:15:46','2018-06-22 20:15:46',NULL),
	(33,'edit_posts','posts','2018-06-22 20:15:46','2018-06-22 20:15:46',NULL),
	(34,'add_posts','posts','2018-06-22 20:15:46','2018-06-22 20:15:46',NULL),
	(35,'delete_posts','posts','2018-06-22 20:15:46','2018-06-22 20:15:46',NULL),
	(36,'browse_pages','pages','2018-06-22 20:15:46','2018-06-22 20:15:46',NULL),
	(37,'read_pages','pages','2018-06-22 20:15:46','2018-06-22 20:15:46',NULL),
	(38,'edit_pages','pages','2018-06-22 20:15:46','2018-06-22 20:15:46',NULL),
	(39,'add_pages','pages','2018-06-22 20:15:46','2018-06-22 20:15:46',NULL),
	(40,'delete_pages','pages','2018-06-22 20:15:46','2018-06-22 20:15:46',NULL),
	(41,'browse_hooks',NULL,'2018-06-22 20:15:46','2018-06-22 20:15:46',NULL),
	(42,'browse_announcements','announcements','2018-05-20 21:08:14','2018-05-20 21:08:14',NULL),
	(43,'read_announcements','announcements','2018-05-20 21:08:14','2018-05-20 21:08:14',NULL),
	(44,'edit_announcements','announcements','2018-05-20 21:08:14','2018-05-20 21:08:14',NULL),
	(45,'add_announcements','announcements','2018-05-20 21:08:14','2018-05-20 21:08:14',NULL),
	(46,'delete_announcements','announcements','2018-05-20 21:08:14','2018-05-20 21:08:14',NULL),
	(47,'browse_themes','admin','2017-11-21 16:31:00','2017-11-21 16:31:00',NULL),
	(48,'browse_hooks','hooks','2018-06-22 13:55:03','2018-06-22 13:55:03',NULL),
	(49,'read_hooks','hooks','2018-06-22 13:55:03','2018-06-22 13:55:03',NULL),
	(50,'edit_hooks','hooks','2018-06-22 13:55:03','2018-06-22 13:55:03',NULL),
	(51,'add_hooks','hooks','2018-06-22 13:55:03','2018-06-22 13:55:03',NULL),
	(52,'delete_hooks','hooks','2018-06-22 13:55:03','2018-06-22 13:55:03',NULL),
	(53,'browse_plans','plans','2018-07-03 04:50:28','2018-07-03 04:50:28',NULL),
	(54,'read_plans','plans','2018-07-03 04:50:28','2018-07-03 04:50:28',NULL),
	(55,'edit_plans','plans','2018-07-03 04:50:28','2018-07-03 04:50:28',NULL),
	(56,'add_plans','plans','2018-07-03 04:50:28','2018-07-03 04:50:28',NULL),
	(57,'delete_plans','plans','2018-07-03 04:50:28','2018-07-03 04:50:28',NULL);

/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table plans
# ------------------------------------------------------------

DROP TABLE IF EXISTS `plans`;

CREATE TABLE `plans` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `features` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `plan_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `role_id` int(10) unsigned NOT NULL,
  `default` tinyint(4) NOT NULL DEFAULT '0',
  `price` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `trial_days` int(5) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `plans_role_id_foreign` (`role_id`),
  CONSTRAINT `plans_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `plans` WRITE;
/*!40000 ALTER TABLE `plans` DISABLE KEYS */;

INSERT INTO `plans` (`id`, `name`, `description`, `features`, `plan_id`, `role_id`, `default`, `price`, `trial_days`, `created_at`, `updated_at`)
VALUES
	(1,'Basic','Signup for the Basic User Plan','Basic Feature Example 1, Basic Feature Example 2, Basic Feature Example 3, Basic Feature Example 4','basic',3,0,'$5/month',0,'2018-07-03 05:03:56','2018-07-03 17:17:24'),
	(2,'Premium','Signup for our premium plan to access all our Premium Features.','Premium Feature Example 1, Premium Feature Example 2, Premium Feature Example 3, Premium Feature Example 4','premium',5,1,'$8/month Paid Annually',0,'2018-07-03 16:29:46','2018-07-03 17:17:08'),
	(3,'Standard','Gain access to our standard features with the standard plan.','Standard Feature Example 1, Standard Feature Example 2, Standard Feature Example 3, Standard Feature Example 4','standard',4,0,'$12/month',14,'2018-07-03 16:30:43','2018-08-22 22:26:19');

/*!40000 ALTER TABLE `plans` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table posts
# ------------------------------------------------------------

DROP TABLE IF EXISTS `posts`;

CREATE TABLE `posts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `author_id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `seo_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `excerpt` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `body` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `meta_keywords` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` enum('PUBLISHED','DRAFT','PENDING') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'DRAFT',
  `featured` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `posts_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `posts` WRITE;
/*!40000 ALTER TABLE `posts` DISABLE KEYS */;

INSERT INTO `posts` (`id`, `author_id`, `category_id`, `title`, `seo_title`, `excerpt`, `body`, `image`, `slug`, `meta_description`, `meta_keywords`, `status`, `featured`, `created_at`, `updated_at`)
VALUES
	(5,1,1,'Best ways to market your application','Best ways to market your application',NULL,'<p>There are many different ways to market your application. First, let\'s start off at the beginning and then we will get more in-depth. You\'ll want to discover your target audience and after that, you\'ll want to run some ads.</p>\r\n<p>Let\'s not complicate things here, if you build a good product, you are going to have users. But you will need to let your users know where to find you. This is where social media and ads come in to play. You\'ll need to boast about your product and your app. If it\'s something that you really believe in, the odds are others will too.</p>\r\n<blockquote>\r\n<p>You may have a need to only want to make money from your application, but if your application can help others achieve a goal and you can make money from it too, you have a gold-mine.</p>\r\n</blockquote>\r\n<p>Some more info on your awesome post here. After this sentence, it\'s just going to be a little bit of jibberish. But you get a general idea. You\'ll want to blog about stuff to get your customers interested in your application. With leverage existing reliable initiatives before leveraged ideas. Rapidiously develops equity invested expertise rather than enabled channels. Monotonectally intermediate distinctive networks before highly efficient core competencies.</p>\r\n<h2>Seamlessly promote flexible growth strategies.</h2>\r\n<p><img src=\"/storage/posts/March2018/blog-1.jpg\" alt=\"blog\" /></p><p> Dramatically harness extensive value through the fully researched human capital. Seamlessly transition premium schemas vis-a-vis efficient convergence. Intrinsically build competitive e-commerce with cross-unit information. Collaboratively e-enable real-time processes before extensive technology. Authoritatively fabricate efficient metrics through intuitive quality vectors.</p>\r\n<p>Collaboratively deliver optimal vortals whereas backward-compatible models. Globally syndicate diverse leadership rather than high-payoff experiences. Uniquely pontificate unique metrics for cross-media human capital. Completely procrastinate professional collaboration and idea-sharing rather than 24/365 paradigms. Phosfluorescently initiates multimedia based outsourcing for interoperable benefits.</p>\r\n<h3>Seamlessly promote flexible growth strategies.</h3>\r\n<p>Progressively leverage other\'s e-business functionalities through corporate e-markets. Holistic repurpose timely systems via seamless total linkage. Appropriately maximize impactful \"outside the box\" thinking vis-a-vis visionary value. Authoritatively deploy interdependent technology through process-centric \"outside the box\" thinking. Interactively negotiate pandemic internal or \"organic\" sources whereas competitive relationships.</p>\r\n<figure><img src=\"/storage/posts/March2018/blog-2.jpg\" alt=\"wide\" />\r\n<figcaption>Keep working until you find success.</figcaption>\r\n</figure>\r\n<p>Enthusiastically deliver viral potentialities through multidisciplinary products. Synergistically plagiarize client-focused partnerships for adaptive applications. Seamlessly morph process-centric synergy whereas bricks-and-clicks deliverables. Continually disintermediate holistic action items without distinctive customer service. Enthusiastically seize enterprise web-readiness without effective schemas.</p>\r\n<h4>Seamlessly promote flexible growth strategies.</h4>\r\n<p>Assertively restore installed base data before sustainable platforms. Globally recapitalize orthogonal systems via clicks-and-mortar web services. Efficiently grow visionary action items through collaborative e-commerce. Efficiently architect highly efficient \"outside the box\" thinking before customer directed infomediaries. Proactively mesh holistic human capital rather than exceptional niches.</p>\r\n<p>Intrinsically create innovative value and pandemic resources. Progressively productize turnkey e-markets and economically sound synergy. Objectively supply turnkey imperatives vis-a-vis high standards in outsourcing. Dynamically exploit unique imperatives with dynamic systems. Appropriately formulate technically sound users and excellent expertise.</p>\r\n<p>Competently redefine long-term high-impact relationships rather than effective metrics. Distinctively maintain impactful platforms after strategic imperatives. Intrinsically evolve mission-critical deliverables after multimedia based e-business. Interactively mesh cooperative benefits whereas distributed process improvements. Progressively monetize an expanded array of e-services whereas.</p>','posts/March2018/h86hSqPMkT9oU8pjcrSu.jpg','best-ways-to-market-your-application','Find out the best ways to market your application in this article.','market, saas, market your app','PUBLISHED',0,'2018-03-26 02:55:01','2018-03-26 02:13:05'),
	(6,1,1,'Achieving your Dreams','Achieving your Dreams',NULL,'<p>What can be said about achieving your dreams?&nbsp;Well... It\'s a good thing, and it\'s probably something you\'re dreaming of. Oh yeah, when you create an app and a product that you enjoy working on... You\'ll be pretty happy and your dreams will probably come true. Cool, right?</p>\r\n<p>I hope that you are ready for some cool stuff because there is some cool stuff right around the corner. By the time you\'ve reached the sky, you\'ll realize your true limits. That last sentence there... That was a little bit of jibberish, but I\'m trying to write about something cool. Bottom line is that Wave is going to help save you so much time.</p>\r\n<blockquote>\r\n<p>You may have a need to only want to make money from your application, but if your application can help others achieve a goal and you can make money from it too, you have a gold-mine.</p>\r\n</blockquote>\r\n<p>Some more info on your awesome post here. After this sentence, it\'s just going to be a little bit of jibberish. But you get a general idea. You\'ll want to blog about stuff to get your customers interested in your application. With leverage existing reliable initiatives before leveraged ideas. Rapidiously develops equity invested expertise rather than enabled channels. Monotonectally intermediate distinctive networks before highly efficient core competencies.</p>\r\n<h2>Seamlessly promote flexible growth strategies.</h2>\r\n<p><img src=\"/storage/posts/March2018/blog-1.jpg\" alt=\"blog\" /></p><p>Dramatically harness extensive value through the fully researched human capital. Seamlessly transition premium schemas vis-a-vis efficient convergence. Intrinsically build competitive e-commerce with cross-unit information. Collaboratively e-enable real-time processes before extensive technology. Authoritatively fabricate efficient metrics through intuitive quality vectors.</p>\r\n<p>Collaboratively deliver optimal vortals whereas backward-compatible models. Globally syndicate diverse leadership rather than high-payoff experiences. Uniquely pontificate unique metrics for cross-media human capital. Completely procrastinate professional collaboration and idea-sharing rather than 24/365 paradigms. Phosfluorescently initiates multimedia based outsourcing for interoperable benefits.</p>\r\n<h3>Seamlessly promote flexible growth strategies.</h3>\r\n<p>Progressively leverage other\'s e-business functionalities through corporate e-markets. Holistic repurpose timely systems via seamless total linkage. Appropriately maximize impactful \"outside the box\" thinking vis-a-vis visionary value. Authoritatively deploy interdependent technology through process-centric \"outside the box\" thinking. Interactively negotiate pandemic internal or \"organic\" sources whereas competitive relationships.</p>\r\n<figure><img src=\"/storage/posts/March2018/blog-2.jpg\" alt=\"wide\" />\r\n<figcaption>Keep working until you find success.</figcaption>\r\n</figure>\r\n<p>Enthusiastically deliver viral potentialities through multidisciplinary products. Synergistically plagiarize client-focused partnerships for adaptive applications. Seamlessly morph process-centric synergy whereas bricks-and-clicks deliverables. Continually disintermediate holistic action items without distinctive customer service. Enthusiastically seize enterprise web-readiness without effective schemas.</p>\r\n<h4>Seamlessly promote flexible growth strategies.</h4>\r\n<p>Assertively restore installed base data before sustainable platforms. Globally recapitalize orthogonal systems via clicks-and-mortar web services. Efficiently grow visionary action items through collaborative e-commerce. Efficiently architect highly efficient \"outside the box\" thinking before customer directed infomediaries. Proactively mesh holistic human capital rather than exceptional niches.</p>\r\n<p>Intrinsically create innovative value and pandemic resources. Progressively productize turnkey e-markets and economically sound synergy. Objectively supply turnkey imperatives vis-a-vis high standards in outsourcing. Dynamically exploit unique imperatives with dynamic systems. Appropriately formulate technically sound users and excellent expertise.</p>\r\n<p>Competently redefine long-term high-impact relationships rather than effective metrics. Distinctively maintain impactful platforms after strategic imperatives. Intrinsically evolve mission-critical deliverables after multimedia based e-business. Interactively mesh cooperative benefits whereas distributed process improvements. Progressively monetize an expanded array of e-services whereas.</p>','posts/March2018/rU26aWVsZ2zocWGSTE7J.jpg','achieving-your-dreams','In this post, you\'ll learn about achieving your dreams by building the SAAS app of your dreams','saas app, dreams','PUBLISHED',0,'2018-03-26 02:50:18','2018-03-26 02:15:18'),
	(7,1,1,'Building a solid foundation','Building a solid foundation',NULL,'<p>The foundation is one of the most important aspects. You\'ll want to make sure that you build your application on a solid foundation because this is where every other feature will grow on top of.</p>\r\n<p>If the foundation is unstable the rest of the application will be so as well. But a solid foundation will make mediocre features seem amazing. So, if you want to save yourself some time you will build your application on a solid foundation of cool features, awesome jumps, and killer waves... I don\'t know what this paragraph is about anymore.</p>\r\n<blockquote>\r\n<p>You may have a need to only want to make money from your application, but if your application can help others achieve a goal and you can make money from it too, you have a gold-mine.</p>\r\n</blockquote>\r\n<p>Some more info on your awesome post here. After this sentence, it\'s just going to be a little bit of jibberish. But you get a general idea. You\'ll want to blog about stuff to get your customers interested in your application. With leverage existing reliable initiatives before leveraged ideas. Rapidiously develops equity invested expertise rather than enabled channels. Monotonectally intermediate distinctive networks before highly efficient core competencies.</p>\r\n<h2>Seamlessly promote flexible growth strategies.</h2>\r\n<p><img src=\"/storage/posts/March2018/blog-1.jpg\" alt=\"blog\" /></p><p>Dramatically harness extensive value through the fully researched human capital. Seamlessly transition premium schemas vis-a-vis efficient convergence. Intrinsically build competitive e-commerce with cross-unit information. Collaboratively e-enable real-time processes before extensive technology. Authoritatively fabricate efficient metrics through intuitive quality vectors.</p>\r\n<p>Collaboratively deliver optimal vortals whereas backward-compatible models. Globally syndicate diverse leadership rather than high-payoff experiences. Uniquely pontificate unique metrics for cross-media human capital. Completely procrastinate professional collaboration and idea-sharing rather than 24/365 paradigms. Phosfluorescently initiates multimedia based outsourcing for interoperable benefits.</p>\r\n<h3>Seamlessly promote flexible growth strategies.</h3>\r\n<p>Progressively leverage other\'s e-business functionalities through corporate e-markets. Holistic repurpose timely systems via seamless total linkage. Appropriately maximize impactful \"outside the box\" thinking vis-a-vis visionary value. Authoritatively deploy interdependent technology through process-centric \"outside the box\" thinking. Interactively negotiate pandemic internal or \"organic\" sources whereas competitive relationships.</p>\r\n<figure><img src=\"/storage/posts/March2018/blog-2.jpg\" alt=\"wide\" />\r\n<figcaption>Keep working until you find success.</figcaption>\r\n</figure>\r\n<p>Enthusiastically deliver viral potentialities through multidisciplinary products. Synergistically plagiarize client-focused partnerships for adaptive applications. Seamlessly morph process-centric synergy whereas bricks-and-clicks deliverables. Continually disintermediate holistic action items without distinctive customer service. Enthusiastically seize enterprise web-readiness without effective schemas.</p>\r\n<h4>Seamlessly promote flexible growth strategies.</h4>\r\n<p>Assertively restore installed base data before sustainable platforms. Globally recapitalize orthogonal systems via clicks-and-mortar web services. Efficiently grow visionary action items through collaborative e-commerce. Efficiently architect highly efficient \"outside the box\" thinking before customer directed infomediaries. Proactively mesh holistic human capital rather than exceptional niches.</p>\r\n<p>Intrinsically create innovative value and pandemic resources. Progressively productize turnkey e-markets and economically sound synergy. Objectively supply turnkey imperatives vis-a-vis high standards in outsourcing. Dynamically exploit unique imperatives with dynamic systems. Appropriately formulate technically sound users and excellent expertise.</p>\r\n<p>Competently redefine long-term high-impact relationships rather than effective metrics. Distinctively maintain impactful platforms after strategic imperatives. Intrinsically evolve mission-critical deliverables after multimedia based e-business. Interactively mesh cooperative benefits whereas distributed process improvements. Progressively monetize an expanded array of e-services whereas.&nbsp;</p>','posts/March2018/4vI1gzsAvMZ30yfDIe67.jpg','building-a-solid-foundation','Building a solid foundation for your application is super important. Read on below.','foundation, app foundation','PUBLISHED',0,'2018-03-26 02:24:43','2018-03-26 02:24:43'),
	(8,1,2,'Finding the solution that fits for you','Finding the solution that fits for you',NULL,'<p>There is a fit for each person. Depending on the service you may want to focus on what each person needs. When you find this you\'ll be able to segregate your application to fit each person\'s needs.</p>\r\n<p>This is really just an example post. I could write some stuff about how this and that, but it would probably only be information about this and that. Who am I kidding? This really isn\'t going to make some sense, but thanks for still reading. Are you still reading this article? That\'s awesome. Thanks for being interested.</p>\r\n<blockquote>\r\n<p>You may have a need to only want to make money from your application, but if your application can help others achieve a goal and you can make money from it too, you have a gold-mine.</p>\r\n</blockquote>\r\n<p>Some more info on your awesome post here. After this sentence, it\'s just going to be a little bit of jibberish. But you get a general idea. You\'ll want to blog about stuff to get your customers interested in your application. With leverage existing reliable initiatives before leveraged ideas. Rapidiously develops equity invested expertise rather than enabled channels. Monotonectally intermediate distinctive networks before highly efficient core competencies.</p>\r\n<h2>Seamlessly promote flexible growth strategies.</h2>\r\n<p><img src=\"/storage/posts/March2018/blog-1.jpg\" alt=\"blog\" /></p><p>Dramatically harness extensive value through the fully researched human capital. Seamlessly transition premium schemas vis-a-vis efficient convergence. Intrinsically build competitive e-commerce with cross-unit information. Collaboratively e-enable real-time processes before extensive technology. Authoritatively fabricate efficient metrics through intuitive quality vectors.</p>\r\n<p>Collaboratively deliver optimal vortals whereas backward-compatible models. Globally syndicate diverse leadership rather than high-payoff experiences. Uniquely pontificate unique metrics for cross-media human capital. Completely procrastinate professional collaboration and idea-sharing rather than 24/365 paradigms. Phosfluorescently initiates multimedia based outsourcing for interoperable benefits.</p>\r\n<h3>Seamlessly promote flexible growth strategies.</h3>\r\n<p>Progressively leverage other\'s e-business functionalities through corporate e-markets. Holistic repurpose timely systems via seamless total linkage. Appropriately maximize impactful \"outside the box\" thinking vis-a-vis visionary value. Authoritatively deploy interdependent technology through process-centric \"outside the box\" thinking. Interactively negotiate pandemic internal or \"organic\" sources whereas competitive relationships.</p>\r\n<figure><img src=\"/storage/posts/March2018/blog-2.jpg\" alt=\"wide\" />\r\n<figcaption>Keep working until you find success.</figcaption>\r\n</figure>\r\n<p>Enthusiastically deliver viral potentialities through multidisciplinary products. Synergistically plagiarize client-focused partnerships for adaptive applications. Seamlessly morph process-centric synergy whereas bricks-and-clicks deliverables. Continually disintermediate holistic action items without distinctive customer service. Enthusiastically seize enterprise web-readiness without effective schemas.</p>\r\n<h4>Seamlessly promote flexible growth strategies.</h4>\r\n<p>Assertively restore installed base data before sustainable platforms. Globally recapitalize orthogonal systems via clicks-and-mortar web services. Efficiently grow visionary action items through collaborative e-commerce. Efficiently architect highly efficient \"outside the box\" thinking before customer directed infomediaries. Proactively mesh holistic human capital rather than exceptional niches.</p>\r\n<p>Intrinsically create innovative value and pandemic resources. Progressively productize turnkey e-markets and economically sound synergy. Objectively supply turnkey imperatives vis-a-vis high standards in outsourcing. Dynamically exploit unique imperatives with dynamic systems. Appropriately formulate technically sound users and excellent expertise.</p>\r\n<p>Competently redefine long-term high-impact relationships rather than effective metrics. Distinctively maintain impactful platforms after strategic imperatives. Intrinsically evolve mission-critical deliverables after multimedia based e-business. Interactively mesh cooperative benefits whereas distributed process improvements. Progressively monetize an expanded array of e-services whereas.&nbsp;</p>','posts/March2018/hWOT5yqNmzCnLhVWXB2u.jpg','finding-the-solution-that-fits-for-you','How to build an app and find a solution that fits each users needs','solution, app solution','PUBLISHED',0,'2018-03-26 02:42:44','2018-03-26 02:42:44'),
	(9,1,2,'Creating something useful','Creating something useful',NULL,'<p>It\'s not enough nowadays to create something you want, instead you\'ll need to focus on what people need. If you find a need for something that isn\'t available... You should create it. Odds are someone will find it useful as well.</p>\r\n<p>When you focus your energy on building something that you are passionate about it\'s going to show. Your customers will buy because it\'s a great application, but also because they believe in what you are trying to achieve. So, continue to focus on making something that people need and find useful.</p>\r\n<blockquote>\r\n<p>You may have a need to only want to make money from your application, but if your application can help others achieve a goal and you can make money from it too, you have a gold-mine.</p>\r\n</blockquote>\r\n<p>Some more info on your awesome post here. After this sentence, it\'s just going to be a little bit of jibberish. But you get a general idea. You\'ll want to blog about stuff to get your customers interested in your application. With leverage existing reliable initiatives before leveraged ideas. Rapidiously develops equity invested expertise rather than enabled channels. Monotonectally intermediate distinctive networks before highly efficient core competencies.</p>\r\n<h2>Seamlessly promote flexible growth strategies.</h2>\r\n<p><img src=\"/storage/posts/March2018/blog-1.jpg\" alt=\"blog\" /></p><p>Dramatically harness extensive value through the fully researched human capital. Seamlessly transition premium schemas vis-a-vis efficient convergence. Intrinsically build competitive e-commerce with cross-unit information. Collaboratively e-enable real-time processes before extensive technology. Authoritatively fabricate efficient metrics through intuitive quality vectors.</p>\r\n<p>Collaboratively deliver optimal vortals whereas backward-compatible models. Globally syndicate diverse leadership rather than high-payoff experiences. Uniquely pontificate unique metrics for cross-media human capital. Completely procrastinate professional collaboration and idea-sharing rather than 24/365 paradigms. Phosfluorescently initiates multimedia based outsourcing for interoperable benefits.</p>\r\n<h3>Seamlessly promote flexible growth strategies.</h3>\r\n<p>Progressively leverage other\'s e-business functionalities through corporate e-markets. Holistic repurpose timely systems via seamless total linkage. Appropriately maximize impactful \"outside the box\" thinking vis-a-vis visionary value. Authoritatively deploy interdependent technology through process-centric \"outside the box\" thinking. Interactively negotiate pandemic internal or \"organic\" sources whereas competitive relationships.</p>\r\n<figure><img src=\"/storage/posts/March2018/blog-2.jpg\" alt=\"wide\" />\r\n<figcaption>Keep working until you find success.</figcaption>\r\n</figure>\r\n<p>Enthusiastically deliver viral potentialities through multidisciplinary products. Synergistically plagiarize client-focused partnerships for adaptive applications. Seamlessly morph process-centric synergy whereas bricks-and-clicks deliverables. Continually disintermediate holistic action items without distinctive customer service. Enthusiastically seize enterprise web-readiness without effective schemas.</p>\r\n<h4>Seamlessly promote flexible growth strategies.</h4>\r\n<p>Assertively restore installed base data before sustainable platforms. Globally recapitalize orthogonal systems via clicks-and-mortar web services. Efficiently grow visionary action items through collaborative e-commerce. Efficiently architect highly efficient \"outside the box\" thinking before customer directed infomediaries. Proactively mesh holistic human capital rather than exceptional niches.</p>\r\n<p>Intrinsically create innovative value and pandemic resources. Progressively productize turnkey e-markets and economically sound synergy. Objectively supply turnkey imperatives vis-a-vis high standards in outsourcing. Dynamically exploit unique imperatives with dynamic systems. Appropriately formulate technically sound users and excellent expertise.</p>\r\n<p>Competently redefine long-term high-impact relationships rather than effective metrics. Distinctively maintain impactful platforms after strategic imperatives. Intrinsically evolve mission-critical deliverables after multimedia based e-business. Interactively mesh cooperative benefits whereas distributed process improvements. Progressively monetize an expanded array of e-services whereas.</p>','posts/March2018/weZwLLpaXnxyTR989iDk.jpg','creating-something-useful','Find out how to Create something useful','useful, create something useful','PUBLISHED',0,'2018-03-26 02:49:37','2018-03-26 02:56:38'),
	(10,1,1,'Never Stop Creating','Never Stop Creating',NULL,'<p>The reason why we are the way we are is... Because we are designed for a purpose. Some people are created to help or service, and others are created to... Well... Create. Are you a creator.</p>\r\n<p>If you have a passion for creating new things and bringing ideas to life. You\'ll want to save yourself some time by using Wave to build the foundation. Wave has so many built-in features including Billing, User Profiles, User Settings, an API, and so much more.</p>\r\n<blockquote>\r\n<p>You may have a need to only want to make money from your application, but if your application can help others achieve a goal and you can make money from it too, you have a gold-mine.</p>\r\n</blockquote>\r\n<p>Some more info on your awesome post here. After this sentence, it\'s just going to be a little bit of jibberish. But you get a general idea. You\'ll want to blog about stuff to get your customers interested in your application. With leverage existing reliable initiatives before leveraged ideas. Rapidiously develops equity invested expertise rather than enabled channels. Monotonectally intermediate distinctive networks before highly efficient core competencies.</p>\r\n<h2>Seamlessly promote flexible growth strategies.</h2>\r\n<p><img src=\"/storage/posts/March2018/blog-1.jpg\" alt=\"blog\" /></p><p>Dramatically harness extensive value through the fully researched human capital. Seamlessly transition premium schemas vis-a-vis efficient convergence. Intrinsically build competitive e-commerce with cross-unit information. Collaboratively e-enable real-time processes before extensive technology. Authoritatively fabricate efficient metrics through intuitive quality vectors.</p>\r\n<p>Collaboratively deliver optimal vortals whereas backward-compatible models. Globally syndicate diverse leadership rather than high-payoff experiences. Uniquely pontificate unique metrics for cross-media human capital. Completely procrastinate professional collaboration and idea-sharing rather than 24/365 paradigms. Phosfluorescently initiates multimedia based outsourcing for interoperable benefits.</p>\r\n<h3>Seamlessly promote flexible growth strategies.</h3>\r\n<p>Progressively leverage other\'s e-business functionalities through corporate e-markets. Holistic repurpose timely systems via seamless total linkage. Appropriately maximize impactful \"outside the box\" thinking vis-a-vis visionary value. Authoritatively deploy interdependent technology through process-centric \"outside the box\" thinking. Interactively negotiate pandemic internal or \"organic\" sources whereas competitive relationships.</p>\r\n<figure><img src=\"/storage/posts/March2018/blog-2.jpg\" alt=\"wide\" />\r\n<figcaption>Keep working until you find success.</figcaption>\r\n</figure>\r\n<p>Enthusiastically deliver viral potentialities through multidisciplinary products. Synergistically plagiarize client-focused partnerships for adaptive applications. Seamlessly morph process-centric synergy whereas bricks-and-clicks deliverables. Continually disintermediate holistic action items without distinctive customer service. Enthusiastically seize enterprise web-readiness without effective schemas.</p>\r\n<h4>Seamlessly promote flexible growth strategies.</h4>\r\n<p>Assertively restore installed base data before sustainable platforms. Globally recapitalize orthogonal systems via clicks-and-mortar web services. Efficiently grow visionary action items through collaborative e-commerce. Efficiently architect highly efficient \"outside the box\" thinking before customer directed infomediaries. Proactively mesh holistic human capital rather than exceptional niches.</p>\r\n<p>Intrinsically create innovative value and pandemic resources. Progressively productize turnkey e-markets and economically sound synergy. Objectively supply turnkey imperatives vis-a-vis high standards in outsourcing. Dynamically exploit unique imperatives with dynamic systems. Appropriately formulate technically sound users and excellent expertise.</p>\r\n<p>Competently redefine long-term high-impact relationships rather than effective metrics. Distinctively maintain impactful platforms after strategic imperatives. Intrinsically evolve mission-critical deliverables after multimedia based e-business. Interactively mesh cooperative benefits whereas distributed process improvements. Progressively monetize an expanded array of e-services whereas.</p>','posts/March2018/K804BvnOehlLao0XmI08.jpg','never-stop-creating','In this article you\'ll learn how important it is to never stop creating','creating, never stop','PUBLISHED',0,'2018-03-26 02:08:02','2018-06-28 06:14:31');

/*!40000 ALTER TABLE `posts` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table roles
# ------------------------------------------------------------

DROP TABLE IF EXISTS `roles`;

CREATE TABLE `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;

INSERT INTO `roles` (`id`, `name`, `display_name`, `created_at`, `updated_at`)
VALUES
	(1,'admin','Admin User','2017-11-21 16:23:22','2017-11-21 16:23:22'),
	(2,'trial','Free Trial','2017-11-21 16:23:22','2017-11-21 16:23:22'),
	(3,'basic','Basic Plan','2018-07-03 05:03:21','2018-07-03 17:28:44'),
	(4,'standard','Standard Plan','2018-07-03 16:27:16','2018-07-03 17:28:38'),
	(5,'premium','Premium Plan','2018-07-03 16:28:42','2018-07-03 17:28:32'),
	(6,'inactive','Inactive User','2018-07-03 16:28:42','2018-07-03 17:28:32');

/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table settings
# ------------------------------------------------------------

DROP TABLE IF EXISTS `settings`;

CREATE TABLE `settings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `details` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `order` int(11) NOT NULL DEFAULT '1',
  `group` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;

INSERT INTO `settings` (`id`, `key`, `display_name`, `value`, `details`, `type`, `order`, `group`)
VALUES
	(1,'site.title','Site Title','Wave','','text',1,'Site'),
	(2,'site.description','Site Description','The Software as a Service Starter Kit built on Laravel & Voyager','','text',2,'Site'),
	(4,'site.google_analytics_tracking_id','Google Analytics Tracking ID',NULL,'','text',4,'Site'),
	(5,'admin.bg_image','Admin Background Image','','','image',5,'Admin'),
	(6,'admin.title','Admin Title','Wave','','text',1,'Admin'),
	(7,'admin.description','Admin Description','Create some waves and build your next great idea','','text',2,'Admin'),
	(8,'admin.loader','Admin Loader','settings/November2017/UYzuapBFBCrR4gKzwR9E.png','','image',3,'Admin'),
	(9,'admin.icon_image','Admin Icon Image','settings/March2018/a1benhFYD9IhEgrKEL4n.png','','image',4,'Admin'),
	(10,'admin.google_analytics_client_id','Google Analytics Client ID (used for admin dashboard)','854769825891-6pu07rot7151p7qtrph91joqmg0lo7ml.apps.googleusercontent.com','','text',1,'Admin'),
	(11,'site.favicon','Favicon','',NULL,'image',6,'Site'),
	(12,'auth.dashboard_redirect','Homepage Redirect to Dashboard if Logged in','0',NULL,'checkbox',7,'Auth'),
	(13,'auth.email_or_username','Users Login with Email or Username','email','{\r\n    \"default\" : \"email\",\r\n    \"options\" : {\r\n        \"email\": \"Email Address\",\r\n        \"username\": \"Username\"\r\n    }\r\n}','select_dropdown',8,'Auth'),
	(14,'auth.username_in_registration','Username when Registering','yes','{\r\n    \"default\" : \"yes\",\r\n    \"options\" : {\r\n        \"yes\": \"Yes, Include the Username Field when Registering\",\r\n        \"no\": \"No, Have it automatically generated\"\r\n    }\r\n}','select_dropdown',9,'Auth'),
	(15,'auth.verify_email','Verify Email during Sign Up','0',NULL,'checkbox',10,'Auth'),
	(16,'billing.card_upfront','Require Credit Card Up Front','0','{\n    \"on\" : \"Yes\",\n    \"off\" : \"No\",\n    \"checked\" : false\n}','checkbox',11,'Billing'),
	(17,'billing.trial_days','Trial Days when No Credit Card Up Front','14',NULL,'text',12,'Billing'),
	(18,'site.custom_javascript','Custom Javascript (inserted before the ending </body> tag)',NULL,NULL,'code_editor',13,'Site');

/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table subscriptions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `subscriptions`;

CREATE TABLE `subscriptions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stripe_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stripe_plan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int(11) NOT NULL,
  `trial_ends_at` timestamp NULL DEFAULT NULL,
  `ends_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `subscriptions` WRITE;
/*!40000 ALTER TABLE `subscriptions` DISABLE KEYS */;

INSERT INTO `subscriptions` (`id`, `user_id`, `name`, `stripe_id`, `stripe_plan`, `quantity`, `trial_ends_at`, `ends_at`, `created_at`, `updated_at`)
VALUES
	(2,43,'main','sub_DStnlxfTEFuIEh','premium',1,NULL,NULL,'2018-08-22 15:11:47','2018-08-22 15:11:47'),
	(3,44,'main','sub_DSuMI9w5MEi2eV','standard',1,NULL,NULL,'2018-08-22 15:47:13','2018-08-22 15:47:13'),
	(4,45,'main','sub_DSvnVqHAcuVCDu','premium',1,NULL,NULL,'2018-08-22 17:16:17','2018-08-22 17:16:17'),
	(5,46,'main','sub_DSyOaLdjz44Q38','basic',1,NULL,NULL,'2018-08-22 19:56:43','2018-08-22 19:56:43'),
	(6,52,'main','sub_DT0ZkwCCENBozr','standard',1,NULL,NULL,'2018-08-22 22:11:35','2018-08-22 22:11:35'),
	(7,53,'main','sub_DT0taMbbcFgywu','premium',1,NULL,NULL,'2018-08-22 22:32:13','2018-08-22 22:32:13'),
	(8,55,'main','sub_DT0wgGXXSv0XdW','standard',1,'2018-09-05 22:35:13',NULL,'2018-08-22 22:35:17','2018-08-22 22:35:17'),
	(9,60,'main','sub_DU1GIWvGFKsDIs','premium',1,NULL,NULL,'2018-08-25 14:59:11','2018-08-25 14:59:11'),
	(10,60,'main','sub_DU1HzogVL4Fc4o','premium',1,NULL,NULL,'2018-08-25 14:59:29','2018-08-25 14:59:29'),
	(11,61,'main','sub_DU1JJsLG2NEza3','premium',1,NULL,NULL,'2018-08-25 15:02:08','2018-08-25 15:02:08'),
	(12,62,'main','sub_DUSVZY5IOY67ja','premium',1,NULL,NULL,'2018-08-26 19:07:33','2018-08-26 19:07:33'),
	(13,62,'main','sub_DUT4Dv7ntGH1Hk','standard',1,NULL,NULL,'2018-08-26 19:42:55','2018-08-26 20:13:04'),
	(14,64,'main','sub_DUTZ0Ql6haJ9qG','basic',1,NULL,NULL,'2018-08-26 20:14:18','2018-08-26 20:17:57'),
	(15,65,'main','sub_DUTf7uo4uyiARd','basic',1,'2018-09-09 20:20:11',NULL,'2018-08-26 20:20:16','2018-08-26 20:25:07'),
	(16,66,'main','sub_DUUB6v2zKQY58d','premium',1,'2018-09-09 20:51:21',NULL,'2018-08-26 20:51:25','2018-08-26 20:51:40'),
	(17,67,'main','sub_DUUJMySrOcK53x','premium',1,NULL,NULL,'2018-08-26 20:59:48','2018-08-26 20:59:48'),
	(18,71,'main','sub_DVYVXvy5e2mOej','premium',1,NULL,NULL,'2018-08-29 17:24:09','2018-08-29 17:24:09'),
	(19,72,'main','sub_DVfHuam7rx6NuH','premium',1,NULL,NULL,'2018-08-30 00:24:12','2018-08-30 00:24:12'),
	(20,73,'main','sub_DVfeZeH3d1H2MU','basic',1,NULL,NULL,'2018-08-30 00:46:52','2018-08-30 00:47:14'),
	(21,74,'main','sub_DVfiHy1m4szj6W','premium',1,NULL,NULL,'2018-08-30 00:50:22','2018-08-30 00:52:40');

/*!40000 ALTER TABLE `subscriptions` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table translations
# ------------------------------------------------------------

DROP TABLE IF EXISTS `translations`;

CREATE TABLE `translations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `table_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `column_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `foreign_key` int(10) unsigned NOT NULL,
  `locale` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `translations_table_name_column_name_foreign_key_locale_unique` (`table_name`,`column_name`,`foreign_key`,`locale`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `translations` WRITE;
/*!40000 ALTER TABLE `translations` DISABLE KEYS */;

INSERT INTO `translations` (`id`, `table_name`, `column_name`, `foreign_key`, `locale`, `value`, `created_at`, `updated_at`)
VALUES
	(1,'data_types','display_name_singular',1,'pt','Post','2017-11-21 16:23:23','2017-11-21 16:23:23'),
	(2,'data_types','display_name_singular',2,'pt','Página','2017-11-21 16:23:23','2017-11-21 16:23:23'),
	(3,'data_types','display_name_singular',3,'pt','Utilizador','2017-11-21 16:23:23','2017-11-21 16:23:23'),
	(4,'data_types','display_name_singular',4,'pt','Categoria','2017-11-21 16:23:23','2017-11-21 16:23:23'),
	(5,'data_types','display_name_singular',5,'pt','Menu','2017-11-21 16:23:23','2017-11-21 16:23:23'),
	(6,'data_types','display_name_singular',6,'pt','Função','2017-11-21 16:23:23','2017-11-21 16:23:23'),
	(7,'data_types','display_name_plural',1,'pt','Posts','2017-11-21 16:23:23','2017-11-21 16:23:23'),
	(8,'data_types','display_name_plural',2,'pt','Páginas','2017-11-21 16:23:23','2017-11-21 16:23:23'),
	(9,'data_types','display_name_plural',3,'pt','Utilizadores','2017-11-21 16:23:23','2017-11-21 16:23:23'),
	(10,'data_types','display_name_plural',4,'pt','Categorias','2017-11-21 16:23:23','2017-11-21 16:23:23'),
	(11,'data_types','display_name_plural',5,'pt','Menus','2017-11-21 16:23:23','2017-11-21 16:23:23'),
	(12,'data_types','display_name_plural',6,'pt','Funções','2017-11-21 16:23:23','2017-11-21 16:23:23'),
	(13,'categories','slug',1,'pt','categoria-1','2017-11-21 16:23:23','2017-11-21 16:23:23'),
	(14,'categories','name',1,'pt','Categoria 1','2017-11-21 16:23:23','2017-11-21 16:23:23'),
	(15,'categories','slug',2,'pt','categoria-2','2017-11-21 16:23:23','2017-11-21 16:23:23'),
	(16,'categories','name',2,'pt','Categoria 2','2017-11-21 16:23:23','2017-11-21 16:23:23'),
	(17,'pages','title',1,'pt','Olá Mundo','2017-11-21 16:23:23','2017-11-21 16:23:23'),
	(18,'pages','slug',1,'pt','ola-mundo','2017-11-21 16:23:23','2017-11-21 16:23:23'),
	(19,'pages','body',1,'pt','<p>Olá Mundo. Scallywag grog swab Cat o\'nine tails scuttle rigging hardtack cable nipper Yellow Jack. Handsomely spirits knave lad killick landlubber or just lubber deadlights chantey pinnace crack Jennys tea cup. Provost long clothes black spot Yellow Jack bilged on her anchor league lateen sail case shot lee tackle.</p>\r\n<p>Ballast spirits fluke topmast me quarterdeck schooner landlubber or just lubber gabion belaying pin. Pinnace stern galleon starboard warp carouser to go on account dance the hempen jig jolly boat measured fer yer chains. Man-of-war fire in the hole nipperkin handsomely doubloon barkadeer Brethren of the Coast gibbet driver squiffy.</p>','2017-11-21 16:23:23','2017-11-21 16:23:23'),
	(20,'menu_items','title',1,'pt','Painel de Controle','2017-11-21 16:23:23','2017-11-21 16:23:23'),
	(21,'menu_items','title',2,'pt','Media','2017-11-21 16:23:23','2017-11-21 16:23:23'),
	(22,'menu_items','title',3,'pt','Publicações','2017-11-21 16:23:23','2017-11-21 16:23:23'),
	(23,'menu_items','title',4,'pt','Utilizadores','2017-11-21 16:23:23','2017-11-21 16:23:23'),
	(24,'menu_items','title',5,'pt','Categorias','2017-11-21 16:23:23','2017-11-21 16:23:23'),
	(25,'menu_items','title',6,'pt','Páginas','2017-11-21 16:23:23','2017-11-21 16:23:23'),
	(26,'menu_items','title',7,'pt','Funções','2017-11-21 16:23:23','2017-11-21 16:23:23'),
	(27,'menu_items','title',8,'pt','Ferramentas','2017-11-21 16:23:23','2017-11-21 16:23:23'),
	(28,'menu_items','title',9,'pt','Menus','2017-11-21 16:23:23','2017-11-21 16:23:23'),
	(29,'menu_items','title',10,'pt','Base de dados','2017-11-21 16:23:23','2017-11-21 16:23:23'),
	(30,'menu_items','title',13,'pt','Configurações','2017-11-21 16:23:23','2017-11-21 16:23:23');

/*!40000 ALTER TABLE `translations` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_roles
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_roles`;

CREATE TABLE `user_roles` (
  `user_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `user_roles_user_id_index` (`user_id`),
  KEY `user_roles_role_id_index` (`role_id`),
  CONSTRAINT `user_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_roles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(10) unsigned DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) NOT NULL,
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'users/default.png',
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `settings` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `stripe_id` varchar(255) DEFAULT NULL,
  `card_brand` varchar(255) DEFAULT NULL,
  `card_last_four` varchar(255) DEFAULT NULL,
  `trial_ends_at` timestamp NULL DEFAULT NULL,
  `verification_code` varchar(255) DEFAULT NULL,
  `verified` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_username_unique` (`username`),
  KEY `users_role_id_foreign` (`role_id`),
  CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;

INSERT INTO `users` (`id`, `role_id`, `name`, `email`, `username`, `avatar`, `password`, `remember_token`, `settings`, `created_at`, `updated_at`, `stripe_id`, `card_brand`, `card_last_four`, `trial_ends_at`, `verification_code`, `verified`)
VALUES
	(1,1,'Tony Lea','tnylea@gmail.com','tnylea','users/default.png','$2y$10$NTj4nKIuYQpkiGuyAP8Kl.3vsX2TiiF/b7I3ELoF4mxkINxpvAI/a','pBCFKLJ0KAuikvH3Jl4l7iRbnyItD7QLSwgpnku83ydezT9ATd0d9j7fwkq0',NULL,'2017-11-21 16:07:22','2018-08-26 20:18:24',NULL,NULL,NULL,NULL,NULL,1),
	(9,2,'John Doe','johndoe@gmail.com','johndoe','users/default.png','$2y$10$3VqIf6CSwroI2kz7aqMXx.2MXa.ERlRWZ/vJ1dKPaB5aF1lbhefGm','06HEVc3NqyMs0rpUra4XAh2SLf5id691aKdv8d2dYNEHVVdAZAZiydF6zSCx',NULL,'2018-06-22 21:32:49','2018-06-22 21:32:49',NULL,NULL,NULL,NULL,NULL,1),
	(10,2,'Bob Jones','bobjones@gmail.com','bobjones','users/default.png','$2y$10$2zBNFWjNMH3wx2HkAHkfv.cz39z1UG2PYHLGZr5csnn7NB.bSikLK',NULL,NULL,'2018-06-26 22:20:10','2018-06-26 22:20:10',NULL,NULL,NULL,NULL,NULL,1),
	(11,NULL,'Mike Jones','mike@gmail.com','mikejones','users/default.png','$2y$10$IxTwe2WeKGfp9wIrzF2ET.VQnk0rVBsFSog0QFu12LrCKyaHgFFWy',NULL,NULL,'2018-06-26 22:24:43','2018-06-26 22:24:43',NULL,NULL,NULL,NULL,NULL,1),
	(12,2,'Frank Jones','frankyjones@gmail.com','frankyjones','users/default.png','$2y$10$JBMv52E57yELxHA92HJwhugQX7CNd7ZWTmbuNpfG1wbc5.zw8jpWy','uV5mh85jEmT7uwerRm7kJRgaDYMkYGpc31ZjDTgbvDSiEkvS8vJqeX0WnUyb',NULL,'2018-07-03 21:50:34','2018-07-03 21:50:34',NULL,NULL,NULL,NULL,NULL,1),
	(13,NULL,'Franky Smith','frankysmith@gmail.com','frankysmith','users/default.png','$2y$10$RuGfAE0vXdHhMeWH7NEWA.ksksj7/Y5ZtWEyDkKE5mYLFy/RNAWa2',NULL,NULL,'2018-07-03 22:46:58','2018-07-03 22:46:58',NULL,NULL,NULL,NULL,NULL,1),
	(14,2,'Jim James','jimjames@gmail.com','jimjames','users/default.png','$2y$10$itN3ta4lrxuXmnfCBuvXyOylLuj7UUJLLiPDKnn/9N54Q6rNKIGh.','aHzJ6lcWIfjZDV5SUAJYh6Kff742aN5MCfYY3zaKRV01xoKOH9iHOrMETyXJ',NULL,'2018-07-03 22:51:05','2018-07-03 22:51:05',NULL,NULL,NULL,NULL,NULL,1),
	(15,2,'Bob Jones','bobjones223@gmail.com','bobjones223','users/default.png','$2y$10$21/WTGHMG5g.ShEQGDnvROdLd0enR2isiVI5a1BFsC6muvINN/H1O',NULL,NULL,'2018-07-25 04:54:09','2018-07-25 04:54:09',NULL,NULL,NULL,NULL,NULL,1),
	(16,2,'John Doe','johndoe123@gmail.com','john-doe','users/default.png','$2y$10$0WfKsmsz0Kw3id.oO..1Wug.Re7faPUQ6s.rCJvFRVfjedUZ6gP.6','Ns8QJpD2P7oPcjBnYEA3zkF7htErLxP0pvojFwjJeNUxgI9xW8KkjP5mb3fG',NULL,'2018-08-17 21:49:46','2018-08-17 21:49:46',NULL,NULL,NULL,NULL,NULL,1),
	(17,2,'Frank Ferter','frankferter@gmail.com','frank-ferter','users/default.png','$2y$10$TUGfXd75EnETRMQwSQPt8.1d6pWrGtK..eCLsv7B7tnWjyiCtr2a.','XSHfTBw6QP9vJSs3R2jY4XmqZD65raNA0Epy5qJxRH9xOdqaJyL3qRxr5ok4',NULL,'2018-08-17 22:34:42','2018-08-17 22:34:42',NULL,NULL,NULL,NULL,NULL,1),
	(18,2,'Jay Mkcray','jaymkcray@gmail.com','jay-mkcray','users/default.png','$2y$10$P6tk9LP0M/AgGrHFwgGvce8nOdlhqfQPV.4vtzX8qvgcHNPsg3nAu',NULL,NULL,'2018-08-17 22:36:24','2018-08-17 22:36:24',NULL,NULL,NULL,NULL,NULL,1),
	(19,2,'Jay Mkcray','jayzcrayz@gmail.com','jayzcrayz','users/default.png','$2y$10$/F5GSyw4xbOgwlMS1md6.eymg/IIpk7AS5ZOObt.8DVgkzgVqOg5S',NULL,NULL,'2018-08-17 22:42:52','2018-08-17 22:42:52',NULL,NULL,NULL,NULL,NULL,1),
	(20,2,'Jay Mcman','jaymcman@gmail.com','jaymcman','users/default.png','$2y$10$4.fKhNtVzFHaxvrNWdmgau4QHmQIjujfSqO5oLDhKJrGnA5F8DKpC',NULL,NULL,'2018-08-17 22:43:40','2018-08-17 22:43:40',NULL,NULL,NULL,NULL,NULL,1),
	(21,2,'Jay Mcman','jaymczman@gmail.com','jaymczman','users/default.png','$2y$10$uKbg4ks.G4goOJlktE61f.3oZR/mKAhEnhtAEXiDV4KLR5a./jvjq',NULL,NULL,'2018-08-17 22:48:36','2018-08-17 22:48:36',NULL,NULL,NULL,NULL,NULL,1),
	(22,2,'Jay Mcman','jaymczman2@gmail.com','jaymczman2','users/default.png','$2y$10$cfkpukJ5bDH6.iVZd7siHOLws.x47eS2SFDUco5jjeB1yI4gqEUR6',NULL,NULL,'2018-08-17 22:49:16','2018-08-17 22:49:16',NULL,NULL,NULL,NULL,NULL,1),
	(23,2,'Bo Mcjoe','bomcjoe@gmail.com','bomcjoe','users/default.png','$2y$10$tHN7JMyTd9WorfAoydVKq.kr1Z2AOzdQ11aeEkXAA9YxMSoshnelK',NULL,NULL,'2018-08-17 23:00:45','2018-08-17 23:00:45',NULL,NULL,NULL,NULL,NULL,1),
	(24,2,'Jay Mkcray','jaymkcrayz@gmail.com','jaymkcrayz','users/default.png','$2y$10$1./8tp8SxTxFPJVpM5V7KuuneX5GKq/MuKnhicYB/BHj88FHb1c16',NULL,NULL,'2018-08-17 23:02:59','2018-08-17 23:02:59',NULL,NULL,NULL,NULL,NULL,1),
	(25,2,'Joe Shmoe','joeshmoe123@gmail.com','joeshmoe','users/default.png','$2y$10$Q60cFBqj5icZsI4uQrEfbuIWkJFyzAIEcmqfKdJMYIqBQcB8VqR1.',NULL,NULL,'2018-08-17 23:19:48','2018-08-17 23:19:48',NULL,NULL,NULL,NULL,NULL,1),
	(26,2,'Bo Jo','bojo123@gmail.com','bojo','users/default.png','$2y$10$x5.b10YXogCfliNkxtc5LuxS8UE00wPmAkMcUMJSgPW4V3bPOXIO6',NULL,NULL,'2018-08-17 23:21:21','2018-08-17 23:21:21',NULL,NULL,NULL,NULL,NULL,1),
	(27,2,'Jay May','jaymay@gmail.com','jaymay','users/default.png','$2y$10$QFqXtJUBUM0M1BBnNXDnZu27sKsDi0cjB8kEwcdaYmHA17Sd.CF0a',NULL,NULL,'2018-08-17 23:22:11','2018-08-17 23:22:11',NULL,NULL,NULL,NULL,NULL,1),
	(28,2,'Bee Mcsee','beemcsee@gmail.com','beemcsee','users/default.png','$2y$10$KiG4SfzwdkGnxZPzWCYFle/ETPnebEoGID6Jr2ZwC8pTRKNNU8aUm','Tkyamt8wYQfNpqIOQMDyz9NZx7LMYI7nR9HZgon0rTEh0Jq55M1SkDBwcKKn',NULL,'2018-08-17 23:35:18','2018-08-17 23:35:18',NULL,NULL,NULL,NULL,'jknZhR4LqTV0Ol6F8VcEDy90cu03rN',0),
	(29,2,'Joe Mcfro','joemcfro@gmail.com','joemcfro','users/default.png','$2y$10$X/LNrQ0ePjnZvROW75gq6OTesdoojS.RFd.PkMeSsGFv5yQXFERKG',NULL,NULL,'2018-08-18 00:53:12','2018-08-18 00:53:12',NULL,NULL,NULL,NULL,'Vpo6cheb8Ba0spMEdAFlFXpMUNCqQJ',0),
	(31,2,'Fred Ed','freded@gmail.com','freded','users/default.png','$2y$10$MnsupGbWtI.57GLpGjFoj.JGKbwpusb4EQHoX9Au4I.8CCwN2yvYa',NULL,NULL,'2018-08-18 00:55:00','2018-08-18 00:55:00',NULL,NULL,NULL,NULL,'tpYNYzm4Dcb0hh7aRCUuJjQiQouusE',0),
	(32,2,'jay say','jaysay@gmail.com','jaysay','users/default.png','$2y$10$jtyP5w5ZMA12tbu9FHbrsOAbly6sKD1mg5IQcnCNY2vnvOkh95eRa',NULL,NULL,'2018-08-18 01:10:49','2018-08-18 01:10:49',NULL,NULL,NULL,NULL,'3wEjccHayIEOmbrkeeeaD49FIRZeID',0),
	(33,2,'jay gray','jaygray@gmail.com','jaygray','users/default.png','$2y$10$GCmiIANneGhCjfJVKTcTPu6q1cUV0w.F871AwXQ80njwAyM4qhfg.',NULL,NULL,'2018-08-18 01:19:40','2018-08-18 01:19:40',NULL,NULL,NULL,NULL,'bJnIHqCmPxotkPLdXhm9Kt8q7gW6mI',0),
	(34,2,'jay smay','jaysmay@gmail.com','jaysmay','users/default.png','$2y$10$wgENxZdm78WbHbWlZRJLOuekejxcL4qeDHKbHpuEIz4LfUuZGrQV6',NULL,NULL,'2018-08-18 01:20:58','2018-08-18 01:20:58',NULL,NULL,NULL,NULL,'DuxAajK44dMUIUxLOxZ1Ftowf1mefi',0),
	(35,2,'jay zsmay','jayzsmay@gmail.com','jayzsmay','users/default.png','$2y$10$Py87AC0.H0n8PF3fGZigL.j71q0aL6nObKRRQxyDpCGmcAsQNaVgu',NULL,NULL,'2018-08-18 01:21:27','2018-08-18 01:21:27',NULL,NULL,NULL,NULL,'wV7TnOkMhjnel4o0nvXPOvm99ghV9b',0),
	(36,2,'jay zsmay2','jayzsmay2@gmail.com','jayzsmay2','users/default.png','$2y$10$19lX7JUoOfJtAowmwWFf5.hhOPLUf7ZYMf5WhDWq4yNhW3c6vy74.',NULL,NULL,'2018-08-18 01:22:13','2018-08-18 01:22:13',NULL,NULL,NULL,NULL,'PgzdHmC28cWS01LkjeZVMXKPyEF5VA',0),
	(37,2,'jay zsmay23','jayzsmay23@gmail.com','jayzsmay23','users/default.png','$2y$10$.BRkhl1Zu33UtTUe989MH.vt0nZimwjh/Ki7omZF6HyQZqBvsYitG','vXACj97qMvjM2AYzRY3TWyZ7SPgiT9Z1XBWNKXBWtCIwUhLJ82aGI11HcpK9',NULL,'2018-08-18 01:25:16','2018-08-18 04:50:39',NULL,NULL,NULL,NULL,NULL,1),
	(38,2,'Frank Ferter','frnkfet@gmail.com','frnkfet','users/default.png','$2y$10$OHEMT99Zwzjs6vjPmXQLwOIJknHvN.GNlka9YqQc0cgCC3fvS3xZ2','dOxLCvWLqO2gVjisJRF6awFWLSNRIhKDzGNru163yZ3aNl5cAG1joDJKNe5S',NULL,'2018-08-21 23:07:53','2018-08-21 23:07:53',NULL,NULL,NULL,NULL,NULL,1),
	(43,5,'Jay Jay','jayjaymay@gmail.com','jayjaymay','users/default.png','$2y$10$tLNBgzx1NG3zZCb838SyW.y/nR7JdGya1FglhhZehuQ6DqtOyHGzu','kgqISHRUlDBkfMevkDhlKRRhlWlp4WZa1wx3rupeLquScgvDj0odIM4o54sj',NULL,'2018-08-22 15:11:42','2018-08-22 15:11:47','cus_DStnKGmIwr7IL0','Visa','4242',NULL,NULL,1),
	(44,4,'Bobby Frank','bobbyfrank@gmail.com','bobbyfrank','users/default.png','$2y$10$WymspZyE3vqA.tR8YlbPFOc1RgYK3Ch9QIE/El4yjsEcKe52NU28S','kyxOIDx6m2ePQVnSG94Q1aRHHxMkZdytWqAEUu0xWlEs9RX8cze79sglER4W',NULL,'2018-08-22 15:47:08','2018-08-22 15:47:13','cus_DSuMWbOX33KzzG','Visa','4242',NULL,NULL,1),
	(45,5,'Jay Mcray','jaymcray@gmail.com','jaymcray','users/default.png','$2y$10$PfaeKtcE2xwDrt.1lHpyn.tbyYNEG1RgDSVINGFyjloRh9sTLkPnK','bLNEftsHxWzervVYzJxF6XcpH6zlNuarj5xAAnLNlpJSWiuQXRmHrFygdyWR',NULL,'2018-08-22 17:16:11','2018-08-22 17:16:17','cus_DSvn9hAZJL5sKU','Visa','4242',NULL,NULL,1),
	(46,3,'Mike Smith','mikesmith@gmail.com','mikesmith','users/default.png','$2y$10$0BqarYhGWSbnWUob2BfcfeWC5ZVw/SbBuiyE.AxJGseFh4Cp6l/4a','GNy9sfoFNtwUKxyib7g0Y65Q0vGZT0oEPs7e3ZgFlvOATRLkJcxcDSyvYfkh',NULL,'2018-08-22 19:56:37','2018-08-22 19:56:43','cus_DSyOqUwIvIh9aZ','Visa','4242',NULL,NULL,1),
	(47,2,'Jimmy Jack','jimmyjack@gmail.com','jimmyjack','users/default.png','$2y$10$0ZL1JP2ig23oCP6HWCGiaOuJKhyuVkpK.6p6zLRyt1jaQujYAj4LS','2b01KgQcM2tBmUnaC9djVoj2I7njT3A2p1DwPvXKFMikKb0gnol79vD92KxA',NULL,'2018-08-22 21:36:14','2018-08-22 21:36:14',NULL,NULL,NULL,NULL,NULL,1),
	(48,2,'Bobby Jones','bobbyjones1@gmail.com','bobbyjones1','users/default.png','$2y$10$uNYgb306S6db8fcrtDZdF.12Y99hZ8IXmsf5/XKP3WMXDADYAGpS6','p6dzuvYArcyYOR5DxQBCxX0XDBofCBfCZyjQzn5JEMxnzbso2EJAK8V6VRp8',NULL,'2018-08-22 21:39:12','2018-08-22 21:39:12',NULL,NULL,NULL,NULL,NULL,1),
	(49,2,'Carl Watkins','carlwatkins@gmail.com','carlwatkins','users/default.png','$2y$10$/fLdmm83E0yt13ngcmeSluzIMpQD8SMplBXn75C.h/3OY335y2tSu','e9B4BNIT3XsteBuA5jkDFCRgPOIn9A6QGCNQZYlyrLzDuAxe0t2tZUFw3jx5',NULL,'2018-08-22 21:39:50','2018-08-22 21:39:50',NULL,NULL,NULL,NULL,NULL,1),
	(50,2,'Paul Blart','paulblart@gmail.com','paulblart','users/default.png','$2y$10$1wQMsNBSR7CedBJqZUJff.aClQDlF6xkMuN0sTycBVtyaq5.eW.xi','ZIDaIrw5PIzc5aBuyoUxVdj3zoiZgFU1Npt5w9qwfxKapkNpGBJdtEnSkB5M',NULL,'2018-08-22 21:42:16','2018-08-22 21:44:02',NULL,NULL,NULL,NULL,NULL,1),
	(51,2,'Mc George','mcgeorge@gmail.com','mcgeorge','users/default.png','$2y$10$HnGLpsSl/bYYEV5ALEno9ulk.qmTnYZwul9MBUbodXw6AySc3CtnO','IqHCCfrdfEnx5n5PucEUfQ207MA0SIdEq4pwzEyQi0HQnM12kAIJkwYkmAgI',NULL,'2018-08-22 21:45:51','2018-08-22 21:45:51',NULL,NULL,NULL,NULL,NULL,1),
	(52,4,'Otter Pop','otterpop@gmail.com','otterpop','users/default.png','$2y$10$8PZIwwFIcHDXQHcbP8sTEeXWyFv5I8CtzV19cLvoZBQUyf0Z1WKx2',NULL,NULL,'2018-08-22 22:11:29','2018-08-22 22:11:35','cus_DT0ZU6vMEsEodh','Visa','4242',NULL,NULL,1),
	(53,5,'May Day','mayday@gmail.com','mayday','users/default.png','$2y$10$KXzc0rFvvqAZ0iODsUrszu45IqGX878AbwIdZpZdWJtmy1xhJ5vCC','kov4uTGz3L0DHnYRpYBmIwzRuOxb9flDAEtkX4MlyVALB7yn2xdiPbkjW2bo',NULL,'2018-08-22 22:32:08','2018-08-22 22:32:13','cus_DT0t0ILy4jT1YW','Visa','4242',NULL,NULL,1),
	(55,4,'Bobo','bobo@gmail.com','bobo','users/default.png','$2y$10$5GzEUfHlnddMOvIgIT/9Wun0PfX3eTT4Y9YEbNtlwY/vnqgB89lZO','unNSQi0148giQDirJMmOybQWWOOLKCz53UblnysHCEMHdW0k8IXbe0qY9daQ',NULL,'2018-08-22 22:35:12','2018-08-22 22:35:17','cus_DT0wohOH9FAHKR','Visa','4242',NULL,NULL,1),
	(56,2,'Tim Tom','timtom@gmail.com','timtom','users/default.png','$2y$10$rwKd8.O5vOTX.PGGM5dh1OpK3D.1fxeKjj5LhKTBo978iDdvKOjYS','Thce6T2BHpx3Dm9l8u9uzi1utYvDk6AbcuIV51pu1zKOglAOKEblOyQzJNWG',NULL,'2018-08-23 04:46:57','2018-08-23 04:46:57',NULL,NULL,NULL,NULL,NULL,1),
	(57,2,'Bim Bam','bimbam@gmail.com','bimbam','users/default.png','$2y$10$ZzpdBFkQ3HH4R71MAp7EdeAmeQuKtxPiDBOy/jw6PEIMt7iDFn.72','wOslMMvCdfztcdxuoN51J8rw8XczDOaHjzK6jC7dwwnAiwj8iwpl8kTB7eic',NULL,'2018-08-23 04:48:15','2018-08-23 04:48:15',NULL,NULL,NULL,NULL,NULL,1),
	(58,2,'Sam Jam','samjam@gmail.com','samjam','users/default.png','$2y$10$cq82aobg08XFi3YSwDZq8uuEdFRF5dbd42QReyxtzv2lnenOAEr82','YlNvfGJaDckQwLxsMO0yr9wXwmfz3Rn1aDxAWwJVl0DLbV3SAL1GDQ617Js9',NULL,'2018-08-23 04:50:50','2018-08-23 04:50:50',NULL,NULL,NULL,'2018-08-28 04:50:50',NULL,1),
	(59,2,'Nolan Ryan','nolanryan@gmail.com','nolanryan','users/default.png','$2y$10$Y026GKgW.MpyEug21zPP7uWgPOMpf3DQIAMpQhzQ1rkSF/xKUc5aK','ZHfKYgS2YUa4Tt9wiKIAEjtPkLFYXLt6HKoySU4QfUbnjWdonc6rWYiF0ac8',NULL,'2018-08-23 04:59:21','2018-08-23 04:59:21',NULL,NULL,NULL,'2018-09-06 04:59:21',NULL,1),
	(60,5,'John Frankz','johnfrankz@gmail.com','johnfrankz','users/default.png','$2y$10$eenOFc41SRM2G5oj2aJVg.enuvpX1SIy.Cw8hT5GadSj6RI4Tetai','2xmXsdIFHd8S0Q6GPw3AiG2PnY5v39tuHZsNTgxqq9mpxJALteQgTtkzZ7Ms',NULL,'2018-08-24 19:42:12','2018-08-25 14:59:29','cus_DU1GIg5GiE9T7X','Visa','4242','2018-09-07 19:42:11',NULL,1),
	(61,5,'Sam Jenkins','samjenkins@gmail.com','samjenkins','users/default.png','$2y$10$A9J0VbHbXdmHKlimy3X8yOEelSV2jurTD5SbZxkrFPNTS0vGqzrBi','eQwTkWqvv2EUT6dYOTEFDgfWVETUwF9YDUMgaMue011vg632AGXJuzujvzp9',NULL,'2018-08-25 15:00:58','2018-08-25 15:02:08','cus_DU1JJxygGBNcI2','Visa','4242',NULL,NULL,1),
	(62,4,'John Jenkins','johnjenkins@gmail.com','johnjenkins','users/default.png','$2y$10$kFaLsbVk7E/xPOF9au07g.5DPO2qDePw/1PZ/z51DRDVr.V3Bj93W','5qd6IBFrtCbGRZuDRHpguQpC2m8O9TRhmAKs5NXtwAYi5XP1Dc3SnkS55A2F',NULL,'2018-08-25 15:08:06','2018-08-26 20:13:04','cus_DUSVpfFRPa6l6F','Visa','4242',NULL,NULL,1),
	(63,2,'Fred Flinstone','fredflinstone@gmail.com','fredflinstone','users/default.png','$2y$10$3iuQy8kwl7qklpRb1X8Ble1/lPFchTa//XXhZUzN8g.Y7gbVfCfbq','yJapyPObIt54VjBxIttnDZmlHotbCWMcCuaG9i8ZxcKZMC9V2wZHCjKyFVO7',NULL,'2018-08-26 19:16:50','2018-08-26 19:16:50',NULL,NULL,NULL,'2018-09-09 19:16:50',NULL,1),
	(64,3,'Jim Jim','jimjim@gmail.com','jimjim','users/default.png','$2y$10$UDZqhzIx6Os0nyokr.jEs.r7LbTXgC0fzvvqyAW.hctpDRsjawa1m','IRIVQweRJeyb34rEO5dbmGs9UeTyqDvRgre2FT2Rf3IeJlDuja2Hq72BFrpV',NULL,'2018-08-26 20:13:37','2018-08-26 20:17:57','cus_DUTZhmtERHUesA','Visa','4242',NULL,NULL,1),
	(65,3,'Barney Rubble','barneyrubble@gmail.com','barneyrubble','users/default.png','$2y$10$FLWPQ2shPR8ZqlBvN88vKef0FWflv5gAl3MrPrzVMm1cVxisgwJh2','oNXYPrfwk0m8gqeaePPvyarNLHjUPRLoF9I1huS7pYsLMiNDJbRmAiu7xPGV',NULL,'2018-08-26 20:20:10','2018-08-26 20:50:36','cus_DUTfE6Ue938Af9','Visa','4242','2018-09-09 20:20:10',NULL,1),
	(66,5,'Jake Smith','jakesmith@gmail.com','jakesmith','users/default.png','$2y$10$BxROw9oVhw0yzn2aw/gS9OeVwdv/nEOeBFQ7JKNjxkchSDkW9v7iO','2rHikASRRXuCDfG6Nc7XFV5CYYgH44p2PSKYLKkaeEkEpa7TPf3HjxtqVLRE',NULL,'2018-08-26 20:51:20','2018-08-26 20:51:58','cus_DUUBtlfMjpnVk2','Visa','4242','2018-09-09 20:51:20',NULL,1),
	(67,5,'Carl Jones','carljones@gmail.com','carljones','users/default.png','$2y$10$AvMjSF/aJ7UenJNm0qXTrOwQ9LYqmBsKWhE.thsyfkGIBwzGgZuMi',NULL,NULL,'2018-08-26 20:53:13','2018-08-26 21:00:20','cus_DUUJZOj2wUC8ob','MasterCard','4444',NULL,NULL,1),
	(68,2,'Blip Bloop','blipbloop@gmail.com','blipbloop','avatars/blipbloop.png','$2y$10$.lAmfTiVO1m6wzE2nz8otuGSXELK1.0nDkSbRLZXWas1NR.u2Y182','JoiMt5cKbong4xrdItrQtHRHQcHbPO87Uyrq3ieY0R3i3CmUksz2bouIPP6z',NULL,'2018-08-26 21:27:59','2018-08-26 21:40:45',NULL,NULL,NULL,'2018-09-09 21:27:59',NULL,1),
	(69,2,'Bob Michaels','bobmichaels2@gmail.com','bobmichaels2','users/default.png','$2y$10$UWEBn2TiYX8Kpa9om.1L/.ceSA7L20a1NhhfnfguGpWC9FfLTK1..','se2ftuEwdJvwnjLztP4jq3i5iE09c55Jx0LAEp4yrZ04oFgidjHavHc3DMzs',NULL,'2018-08-28 17:40:44','2018-08-28 17:40:44',NULL,NULL,NULL,'2018-09-11 17:40:44',NULL,1),
	(70,2,'Jimbo Jones','jimbo@gmail.com','jimbo','users/default.png','$2y$10$7Nrn3dXNDLyncXxPe7Rd9uEASkpr0gph3UlGi1jpt8rwy5eK6xiV2','OsINEfBz4XSgMQML9VZT9wDiELK7psLCZQCaAbc9ycBd2Nz1ojanwEX5msLF',NULL,'2018-08-28 20:13:53','2018-08-28 20:13:53',NULL,NULL,NULL,'2018-08-29 20:13:53',NULL,1),
	(71,5,'Bobby Uberman','bobbyuberman@gmail.com','bobbyuberman','users/default.png','$2y$10$2.a8Z9I3.4UUJC4uCZ6c6OXCD6p8xkiF2.N.oG4xLZQJqHV6rKZn2','EsVLr9ojmToOQRq8HBtQEeHV9Mm4Q90eUESPD2w6E4Y0YCnhzt0XKT1YwVRL',NULL,'2018-08-29 14:14:44','2018-08-29 17:24:09','cus_DVYVHw4W8zjT1d','Visa','4242',NULL,NULL,1),
	(72,5,'Bart Simpson','bartsimpson@gmail.com','bartsimpson','users/default.png','$2y$10$7b5NAWiiXGGxIHa9hW2J7.BjT.YySUV8jclVNfOAmwfFFFrSPJocC','sz7yuTgBcdo9evG9ulBm1ftmUhdjtCXvfJOq0ngsx2RHPxHbAqEKyayUDA5S',NULL,'2018-08-29 17:38:43','2018-08-30 00:24:12','cus_DVfHjEaO69Majf','Visa','4242',NULL,NULL,1),
	(73,3,'Homer Simpson','homersimpson@gmail.com','homersimpson','avatars/homersimpson.png','$2y$10$h2D78pNzJJg9qjS7qOl3bOE6F8vqdLxIhfSne5JAhjFdrXA3SyMEu','5NK7awDf7Rvc4lmWfeK3qcjxqNyXYxlNUOSt2vLitSbxjV5VgTplFsfByONO',NULL,'2018-08-30 00:45:25','2018-08-30 00:47:14','cus_DVfeSnLMgD6QXJ','Visa','4242',NULL,NULL,1),
	(74,5,'Fry','fry@gmail.com','fry','avatars/fry.png','$2y$10$FYoI6D8WQaF/CKtj1YlsdumK2d3tPKW.h/hxZmS.EGy29QiFxBA9K','5etruyAtFjtT8wayWAJ88JRkBepkNzypekz8yujY6hRMq5R1vDrfShul8MGu',NULL,'2018-08-30 00:49:17','2018-08-30 00:52:40','cus_DVfhfcKZ67ouBZ','MasterCard','4444',NULL,NULL,1);

/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table voyager_theme_options
# ------------------------------------------------------------

DROP TABLE IF EXISTS `voyager_theme_options`;

CREATE TABLE `voyager_theme_options` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `voyager_theme_id` int(10) unsigned NOT NULL,
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `voyager_theme_options_voyager_theme_id_index` (`voyager_theme_id`),
  CONSTRAINT `voyager_theme_options_voyager_theme_id_foreign` FOREIGN KEY (`voyager_theme_id`) REFERENCES `voyager_themes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `voyager_theme_options` WRITE;
/*!40000 ALTER TABLE `voyager_theme_options` DISABLE KEYS */;

INSERT INTO `voyager_theme_options` (`id`, `voyager_theme_id`, `key`, `value`, `created_at`, `updated_at`)
VALUES
	(2,2,'logo','themes/February2018/UUgOwPG08CnLLBOtgNWR.png','2017-11-22 16:54:46','2018-02-11 05:02:40'),
	(4,2,'home_headline','Create your next great idea','2017-11-25 17:31:45','2018-08-28 00:17:41'),
	(5,2,'home_subheadline','Wave is the perfect starter kit for building your next great idea','2017-11-25 17:31:45','2017-11-26 07:11:47'),
	(6,2,'home_description','Built using Laravel Voyager,  Wave will help you rapidly build your Software as a Service application. Out of the box Authentication, Subscriptions, Invoices, Announcements, User Profiles, API, and so much more!','2017-11-25 17:31:45','2017-11-26 07:09:50'),
	(7,2,'home_cta','Get It Now','2017-11-25 20:02:29','2017-11-26 16:12:28'),
	(8,2,'home_cta_url','/register','2017-11-25 20:09:33','2017-11-26 16:12:41'),
	(9,2,'home_promo_image','themes/February2018/mFajn4fwpGFXzI1UsNH6.png','2017-11-25 21:36:46','2017-11-29 01:17:00'),
	(10,2,'footer_logo','themes/August2018/TksmVWMqp5JXUQj8C6Ct.png','2018-08-28 23:12:11','2018-08-28 23:12:11');

/*!40000 ALTER TABLE `voyager_theme_options` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table voyager_themes
# ------------------------------------------------------------

DROP TABLE IF EXISTS `voyager_themes`;

CREATE TABLE `voyager_themes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `folder` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `version` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `voyager_themes_folder_unique` (`folder`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `voyager_themes` WRITE;
/*!40000 ALTER TABLE `voyager_themes` DISABLE KEYS */;

INSERT INTO `voyager_themes` (`id`, `name`, `folder`, `active`, `version`, `created_at`, `updated_at`)
VALUES
	(2,'UI Kit Theme','uikit',1,'1.0','2017-11-21 17:09:21','2017-11-21 17:11:57');

/*!40000 ALTER TABLE `voyager_themes` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table wave_key_values
# ------------------------------------------------------------

DROP TABLE IF EXISTS `wave_key_values`;

CREATE TABLE `wave_key_values` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `keyvalue_id` int(10) unsigned NOT NULL,
  `keyvalue_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `wave_key_values_keyvalue_id_keyvalue_type_key_unique` (`keyvalue_id`,`keyvalue_type`,`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `wave_key_values` WRITE;
/*!40000 ALTER TABLE `wave_key_values` DISABLE KEYS */;

INSERT INTO `wave_key_values` (`id`, `type`, `keyvalue_id`, `keyvalue_type`, `key`, `value`)
VALUES
	(10,'text_area',1,'users','about','Hello my name is Tony, I like to create cool web applications and I enjoy doing other stuff like going to the movies, hanging out at the beach, and spending time with my family.'),
	(11,'image',1,'users','cover_image','themes/April2018/uQPiLPWGTZp7JVjmB9oB.jpg'),
	(12,'text_area',73,'users','about','I live in SpringField and I like to drink duff beer. I have a cool family. I work at the power plant and go to Moes on a daily basis.');

/*!40000 ALTER TABLE `wave_key_values` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
