-- MySQL dump 10.11
--
-- Host: localhost    Database: nastya
-- ------------------------------------------------------
-- Server version	5.0.67-0ubuntu6

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
-- Table structure for table `answers`
--

DROP TABLE IF EXISTS `answers`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `answers` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `variant_id` int(10) unsigned NOT NULL,
  `question_id` int(10) unsigned NOT NULL,
  `answered_at` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `user_id` int(10) unsigned NOT NULL,
  `custom_text` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `articles`
--

DROP TABLE IF EXISTS `articles`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `articles` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `topic_id` int(10) unsigned NOT NULL COMMENT 'раздел',
  `author_id` int(10) unsigned NOT NULL COMMENT 'автор/создатель',
  `owner_id` int(10) unsigned NOT NULL COMMENT 'владелец',
  `group_id` int(10) unsigned NOT NULL COMMENT 'группа',
  `title` varchar(255) NOT NULL COMMENT 'заголовок статьи',
  `name` varchar(255) NOT NULL COMMENT 'внутреннее название статьи (например для URL-ов)',
  `abstract` text NOT NULL COMMENT 'краткое содержание статьи, резюме',
  `content` text NOT NULL COMMENT 'основное содержание статьи',
  `created_at` timestamp NOT NULL default '0000-00-00 00:00:00' COMMENT 'время создания',
  `modified_at` timestamp NOT NULL default CURRENT_TIMESTAMP COMMENT 'время последней правки',
  `published_at` timestamp NOT NULL default '0000-00-00 00:00:00' COMMENT 'время публикации, появления на сайте',
  `archived_at` timestamp NOT NULL default '0000-00-00 00:00:00' COMMENT 'время архивирования, скрытия в архив',
  `flags` set('comments','hidden','removed','archived') NOT NULL,
  `sortorder` int(11) NOT NULL default '0',
  `rights` smallint(6) NOT NULL default '496' COMMENT 'права на просмотр, редактирование, удаление для владельца, группы, остальных',
  `views` int(10) unsigned NOT NULL default '0',
  `type` enum('article','gallery') NOT NULL default 'article',
  `items_per_page` int(10) unsigned NOT NULL default '20',
  `language` varchar(5) NOT NULL default 'ru_RU',
  `original_id` int(10) unsigned NOT NULL default '0' COMMENT 'Основная статья, переводом которой является данная. Ноль означает оригинал.',
  `link` varchar(255) NOT NULL default '' COMMENT 'Ссылка на произвольный источник.',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `attachments`
--

DROP TABLE IF EXISTS `attachments`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `attachments` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `filename` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `modified_at` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL default '0000-00-00 00:00:00',
  `filesize` int(10) unsigned NOT NULL default '0',
  `mimetype` varchar(100) NOT NULL,
  `md5hash` varchar(32) NOT NULL default '',
  `author_id` int(10) unsigned NOT NULL,
  `owner_id` int(10) unsigned NOT NULL,
  `group_id` int(10) unsigned NOT NULL,
  `rights` smallint(6) NOT NULL,
  `article_id` int(10) unsigned NOT NULL,
  `sortorder` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `article_id` (`article_id`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `comments` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `article_id` int(10) unsigned NOT NULL,
  `author_id` int(10) unsigned NOT NULL default '0',
  `modified_at` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL default '0000-00-00 00:00:00',
  `flags` set('hidden','removed') NOT NULL,
  `title` varchar(255) NOT NULL default '',
  `content` text NOT NULL,
  `username` varchar(255) default NULL,
  `email` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=39 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `config`
--

DROP TABLE IF EXISTS `config`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `config` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `section_id` int(10) unsigned NOT NULL default '0',
  `name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `groups`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `groups` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `parent_id` int(10) unsigned NOT NULL,
  `role` enum('superuser','admin','manager','user') NOT NULL default 'user',
  `name` varchar(255) NOT NULL default '',
  `title` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `polls`
--

DROP TABLE IF EXISTS `polls`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `polls` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `author_id` int(10) unsigned NOT NULL,
  `owner_id` int(10) unsigned NOT NULL,
  `group_id` int(10) unsigned NOT NULL,
  `rights` int(10) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NOT NULL default '0000-00-00 00:00:00',
  `modified_at` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `answers` int(10) unsigned NOT NULL default '0',
  `closed_at` timestamp NOT NULL default '0000-00-00 00:00:00',
  `max_answers` int(10) unsigned NOT NULL default '0',
  `flags` set('closed','archived','hidden') NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `questions`
--

DROP TABLE IF EXISTS `questions`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `questions` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `poll_id` int(10) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `max_variants` int(10) unsigned NOT NULL default '1',
  `min_variants` int(10) unsigned NOT NULL default '1',
  `sortorder` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `topics`
--

DROP TABLE IF EXISTS `topics`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `topics` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `parent_id` int(10) unsigned NOT NULL default '0',
  `lside` int(10) unsigned NOT NULL default '0',
  `rside` int(10) unsigned NOT NULL default '0',
  `name` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `subtopic_id` int(10) unsigned NOT NULL default '0',
  `article_id` int(10) unsigned NOT NULL default '0',
  `owner_id` int(10) unsigned NOT NULL,
  `group_id` int(10) unsigned NOT NULL,
  `rights` smallint(6) NOT NULL default '496',
  `flags` set('hidden','removed','archived') NOT NULL,
  `type` enum('article','gallery') NOT NULL default 'article',
  `items_per_page` int(10) unsigned NOT NULL default '20',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`parent_id`,`name`),
  KEY `parent_id` (`parent_id`),
  KEY `sortorder` USING BTREE (`parent_id`,`lside`,`id`)
) ENGINE=MyISAM AUTO_INCREMENT=42 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `login` varchar(255) NOT NULL,
  `password` varchar(32) NOT NULL,
  `role` enum('superuser','admin','manager','user','guest') NOT NULL default 'user',
  `group_id` int(11) unsigned NOT NULL,
  `flags` set('banned','inactive') NOT NULL default 'inactive',
  `online_at` timestamp NOT NULL default '0000-00-00 00:00:00',
  `rating` float NOT NULL default '0',
  `gender` enum('male','female') NOT NULL default 'male',
  PRIMARY KEY  (`id`),
  KEY `login` (`login`(32),`password`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `variants`
--

DROP TABLE IF EXISTS `variants`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `variants` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `question_id` int(10) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `sortorder` int(11) NOT NULL default '0',
  `flags` set('custom') NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `visible_articles`
--

DROP TABLE IF EXISTS `visible_articles`;
/*!50001 DROP VIEW IF EXISTS `visible_articles`*/;
/*!50001 CREATE TABLE `visible_articles` (
  `id` int(10) unsigned,
  `topic_id` int(10) unsigned,
  `author_id` int(10) unsigned,
  `owner_id` int(10) unsigned,
  `group_id` int(10) unsigned,
  `title` varchar(255),
  `name` varchar(255),
  `abstract` text,
  `content` text,
  `created_at` timestamp,
  `modified_at` timestamp,
  `published_at` timestamp,
  `archived_at` timestamp,
  `flags` set('comments','hidden','removed','archived'),
  `sortorder` int(11),
  `rights` smallint(6),
  `views` int(10) unsigned,
  `type` enum('article','gallery'),
  `items_per_page` int(10) unsigned,
  `language` varchar(5),
  `original_id` int(10) unsigned,
  `link` varchar(255)
) */;

--
-- Temporary table structure for view `visible_topics`
--

DROP TABLE IF EXISTS `visible_topics`;
/*!50001 DROP VIEW IF EXISTS `visible_topics`*/;
/*!50001 CREATE TABLE `visible_topics` (
  `id` int(10) unsigned,
  `parent_id` int(10) unsigned,
  `lside` int(10) unsigned,
  `rside` int(10) unsigned,
  `name` varchar(255),
  `title` varchar(255),
  `description` text,
  `subtopic_id` int(10) unsigned,
  `article_id` int(10) unsigned,
  `owner_id` int(10) unsigned,
  `group_id` int(10) unsigned,
  `rights` smallint(6),
  `flags` set('hidden','removed','archived'),
  `type` enum('article','gallery'),
  `items_per_page` int(10) unsigned
) */;

--
-- Final view structure for view `visible_articles`
--

/*!50001 DROP TABLE `visible_articles`*/;
/*!50001 DROP VIEW IF EXISTS `visible_articles`*/;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `visible_articles` AS select `articles`.`id` AS `id`,`articles`.`topic_id` AS `topic_id`,`articles`.`author_id` AS `author_id`,`articles`.`owner_id` AS `owner_id`,`articles`.`group_id` AS `group_id`,`articles`.`title` AS `title`,`articles`.`name` AS `name`,`articles`.`abstract` AS `abstract`,`articles`.`content` AS `content`,`articles`.`created_at` AS `created_at`,`articles`.`modified_at` AS `modified_at`,`articles`.`published_at` AS `published_at`,`articles`.`archived_at` AS `archived_at`,`articles`.`flags` AS `flags`,`articles`.`sortorder` AS `sortorder`,`articles`.`rights` AS `rights`,`articles`.`views` AS `views`,`articles`.`type` AS `type`,`articles`.`items_per_page` AS `items_per_page`,`articles`.`language` AS `language`,`articles`.`original_id` AS `original_id`,`articles`.`link` AS `link` from `articles` where ((not((`articles`.`flags` & 14))) and (now() between `articles`.`published_at` and `articles`.`archived_at`)) */;

--
-- Final view structure for view `visible_topics`
--

/*!50001 DROP TABLE `visible_topics`*/;
/*!50001 DROP VIEW IF EXISTS `visible_topics`*/;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `visible_topics` AS select `topics`.`id` AS `id`,`topics`.`parent_id` AS `parent_id`,`topics`.`lside` AS `lside`,`topics`.`rside` AS `rside`,`topics`.`name` AS `name`,`topics`.`title` AS `title`,`topics`.`description` AS `description`,`topics`.`subtopic_id` AS `subtopic_id`,`topics`.`article_id` AS `article_id`,`topics`.`owner_id` AS `owner_id`,`topics`.`group_id` AS `group_id`,`topics`.`rights` AS `rights`,`topics`.`flags` AS `flags`,`topics`.`type` AS `type`,`topics`.`items_per_page` AS `items_per_page` from `topics` where (not(`topics`.`flags`)) */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2009-04-02  4:44:25
