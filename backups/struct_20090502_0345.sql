-- MySQL dump 10.11
--
-- Host: localhost    Database: nastya
-- ------------------------------------------------------
-- Server version	5.0.75-0ubuntu10

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
  `views` int(10) unsigned NOT NULL default '0',
  `type` enum('article','gallery') NOT NULL default 'article',
  `items_per_page` int(10) unsigned NOT NULL default '20',
  `language` varchar(5) NOT NULL default 'ru_RU',
  `original_id` int(10) unsigned NOT NULL default '0' COMMENT 'Основная статья, переводом которой является данная. Ноль означает оригинал.',
  `link` varchar(255) NOT NULL default '' COMMENT 'Ссылка на произвольный источник.',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;
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
  `article_id` int(10) unsigned NOT NULL,
  `sortorder` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `article_id` (`article_id`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `bugnotes`
--

DROP TABLE IF EXISTS `bugnotes`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `bugnotes` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `bug_id` int(10) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `author_id` int(10) unsigned NOT NULL,
  `new_state` enum('unconfirmed','new','assigned','progress','feedback','deferred','done','closed') default NULL,
  `new_assignee_id` int(10) unsigned default NULL,
  `new_resolution` enum('fixed','wontfix','notabug','cantreproduce','duplicate') default NULL,
  `new_progress` tinyint(3) unsigned default NULL,
  `vote` float default NULL,
  `new_due_version` varchar(255) default NULL,
  `new_due_date` timestamp NULL default NULL,
  `new_severity` enum('trivial','minor','normal','major','critical','blocker') default NULL,
  `new_priority` enum('low','normal','high','urgent','immediate') default NULL,
  `new_duplicate_id` int(10) unsigned default NULL,
  `time_spent` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `bugs`
--

DROP TABLE IF EXISTS `bugs`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `bugs` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `severity` enum('trivial','minor','normal','major','critical','blocker') NOT NULL default 'normal',
  `priority` enum('low','normal','high','urgent','immediate') NOT NULL default 'normal',
  `resolution` enum('fixed','wontfix','notabug','cantreproduce','duplicate') NOT NULL,
  `state` enum('unconfirmed','new','assigned','progress','feedback','deferred','done','closed') NOT NULL default 'unconfirmed',
  `os_type` set('win','linux','bsd','macos','other') NOT NULL default 'win,linux,bsd,macos,other',
  `type` enum('bug','feature','task') NOT NULL default 'bug',
  `created_at` timestamp NOT NULL default '0000-00-00 00:00:00',
  `modified_at` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `modified_by` int(10) unsigned NOT NULL default '0',
  `created_by` int(10) unsigned NOT NULL default '0',
  `due_to` timestamp NOT NULL default '0000-00-00 00:00:00',
  `version` varchar(255) NOT NULL default '',
  `due_to_version` varchar(255) NOT NULL default '',
  `assigned_to` int(10) unsigned NOT NULL default '0',
  `progress` tinyint(3) unsigned NOT NULL default '0',
  `category_id` int(10) unsigned NOT NULL default '0',
  `rating` float NOT NULL default '0',
  `duplicate_of` int(10) unsigned NOT NULL default '0',
  `time_spent` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
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
  `lside` int(10) unsigned NOT NULL default '0',
  `rside` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;
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
  `flags` set('closed','archived','hidden','removed') NOT NULL default '',
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
-- Table structure for table `rights`
--

DROP TABLE IF EXISTS `rights`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `rights` (
  `object_type` enum('article','attachment','topic','poll') NOT NULL,
  `object_id` int(10) unsigned NOT NULL,
  `owner_id` int(10) unsigned NOT NULL,
  `group_id` int(10) unsigned NOT NULL,
  `rights` int(11) NOT NULL,
  PRIMARY KEY  (`object_type`,`object_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `sites`
--

DROP TABLE IF EXISTS `sites`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `sites` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `title` varchar(255) NOT NULL,
  `host` varchar(255) NOT NULL COMMENT 'Хост',
  `prefix` varchar(255) NOT NULL COMMENT 'Префикс',
  `realm` varchar(255) NOT NULL,
  `topic_id` int(10) unsigned NOT NULL COMMENT 'Корневой раздел сайта',
  `group_id` int(10) unsigned NOT NULL COMMENT 'Группа администраторов сайта',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `tag_relations`
--

DROP TABLE IF EXISTS `tag_relations`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tag_relations` (
  `obj_type` enum('article','topic') NOT NULL default 'article',
  `obj_id` int(10) unsigned NOT NULL,
  `tag_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`obj_type`,`obj_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `tags`
--

DROP TABLE IF EXISTS `tags`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tags` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `parent_id` int(10) unsigned NOT NULL default '0',
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
  `flags` set('hidden','removed','archived','recursive') NOT NULL,
  `type` enum('article','gallery') NOT NULL default 'article',
  `items_per_page` int(10) unsigned NOT NULL default '20',
  `articles_sort` enum('sortorder','title','name','published_at','archived_at','created_at','modified_at') NOT NULL default 'sortorder',
  `articles_sort_desc` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`parent_id`,`name`),
  KEY `parent_id` (`parent_id`),
  KEY `sortorder` USING BTREE (`parent_id`,`lside`,`id`)
) ENGINE=MyISAM AUTO_INCREMENT=49 DEFAULT CHARSET=utf8;
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
  `password` varchar(64) NOT NULL,
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
  `views` int(10) unsigned,
  `type` enum('article','gallery'),
  `items_per_page` int(10) unsigned,
  `language` varchar(5),
  `original_id` int(10) unsigned,
  `link` varchar(255)
) ENGINE=MyISAM */;

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
  `flags` set('hidden','removed','archived','recursive'),
  `type` enum('article','gallery'),
  `items_per_page` int(10) unsigned,
  `articles_sort` enum('sortorder','title','name','published_at','archived_at','created_at','modified_at'),
  `articles_sort_desc` tinyint(1)
) ENGINE=MyISAM */;

--
-- Final view structure for view `visible_articles`
--

/*!50001 DROP TABLE `visible_articles`*/;
/*!50001 DROP VIEW IF EXISTS `visible_articles`*/;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `visible_articles` AS select `articles`.`id` AS `id`,`articles`.`topic_id` AS `topic_id`,`articles`.`author_id` AS `author_id`,`articles`.`title` AS `title`,`articles`.`name` AS `name`,`articles`.`abstract` AS `abstract`,`articles`.`content` AS `content`,`articles`.`created_at` AS `created_at`,`articles`.`modified_at` AS `modified_at`,`articles`.`published_at` AS `published_at`,`articles`.`archived_at` AS `archived_at`,`articles`.`flags` AS `flags`,`articles`.`sortorder` AS `sortorder`,`articles`.`views` AS `views`,`articles`.`type` AS `type`,`articles`.`items_per_page` AS `items_per_page`,`articles`.`language` AS `language`,`articles`.`original_id` AS `original_id`,`articles`.`link` AS `link` from `articles` where ((not((`articles`.`flags` & 14))) and (now() between `articles`.`published_at` and `articles`.`archived_at`)) */;

--
-- Final view structure for view `visible_topics`
--

/*!50001 DROP TABLE `visible_topics`*/;
/*!50001 DROP VIEW IF EXISTS `visible_topics`*/;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `visible_topics` AS select `topics`.`id` AS `id`,`topics`.`parent_id` AS `parent_id`,`topics`.`lside` AS `lside`,`topics`.`rside` AS `rside`,`topics`.`name` AS `name`,`topics`.`title` AS `title`,`topics`.`description` AS `description`,`topics`.`subtopic_id` AS `subtopic_id`,`topics`.`article_id` AS `article_id`,`topics`.`flags` AS `flags`,`topics`.`type` AS `type`,`topics`.`items_per_page` AS `items_per_page`,`topics`.`articles_sort` AS `articles_sort`,`topics`.`articles_sort_desc` AS `articles_sort_desc` from `topics` where (not(`topics`.`flags`)) */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2009-05-02  0:45:33
