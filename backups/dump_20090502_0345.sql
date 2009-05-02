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
-- Dumping data for table `answers`
--

LOCK TABLES `answers` WRITE;
/*!40000 ALTER TABLE `answers` DISABLE KEYS */;
/*!40000 ALTER TABLE `answers` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `articles`
--

LOCK TABLES `articles` WRITE;
/*!40000 ALTER TABLE `articles` DISABLE KEYS */;
INSERT INTO `articles` VALUES (13,11,1,'Ещё одна статья','esche_odna_statya','','<p>Статья</p>','2009-03-27 22:55:35','2009-04-24 16:58:19','2009-03-27 22:55:00','2019-03-27 22:55:00','removed',3,0,'article',20,'ru_RU',0,''),(10,11,1,'Галерея','galereya','<p>Статья-галерея</p>','<p>Пример простейшей галереи изображений.</p>','2009-03-05 05:15:59','2009-05-02 00:10:34','2009-03-05 09:00:00','2019-03-07 12:00:00','comments',1,0,'gallery',5,'ru_RU',0,''),(11,11,1,'Обратная связь','obratnaya_svyaz','<p>Можете здесь оставлять свои комментарии.</p>','<p>А можете и не оставлять :)</p>','2009-03-14 00:25:11','2009-04-14 00:10:48','2009-03-14 00:25:00','2029-03-14 00:25:00','comments',2,0,'article',1,'ru_RU',0,''),(12,9,1,'Добро пожаловать на мой сайт!','dobro_pozhalovat_na_moj_sajt','','<p><!-- 		@page { size: 21cm 29.7cm; margin: 2cm } 		P { margin-bottom: 0.21cm } 	--></p>\r\n<p>Добро пожаловать на мой сайт! Будьте уверены, здесь всегда рады гостям. После того, как вы ознакомитесь с информацией, здесь предоставленной, не забудьте вернуться сюда еще раз через некоторое время. Уверяю, на этом сайте вы всегда сможете найти для себя что-то новенькое.</p>\r\n<p>Немного слов обо мне: меня зовут Анастасия Валицкая. Я мастер макияжа и стиля международной квалификации, и, если вы посетили меня, то, скорее всего, мы знакомы.</p>\r\n<p style=\\\"\\\\\\\">Макияж, мода, стиль, имидж &mdash; это моя жизнь. Поэтому, если у нас схожие интересы, мы обязательно подружимся.</p>\r\n<p style=\\\"\\\\\\\">В области создания образа и макияжа я работаю уже 5 лет, до этого еще пять лет изучала гармонию цвета, макияж, стиль и, конечно, получила педагогическое образование.</p>\r\n<p style=\\\"\\\\\\\">За моими плечами колоссальный опыт работы с клиентами в качестве визажиста в торжественных случаях и в роли консультанта для создания изысканного образа на каждый день. Участвовала в телевизионных проектах, занималась постановкой бодиарт-шоу и перформансшоу. В последнее время основной акцент моей деятельности направлен на проведение обучающих мастер-классов как для профессионалов, так и для любителей.</p>\r\n<p style=\\\"\\\\\\\">Идея создать данный проект возникла в рамках школы по макияжу и стилю, открытием которой я в данный момент занимаюсь. Главной задачей школы будет дать любой женщине необходимые знания и помочь ей в создании ее собственного неповторимого образа.</p>\r\n<p style=\\\"\\\\\\\">Информация на сайте будет постоянно обновляться, таким образом, вы всегда сможете быть в курсе последних новостей в области макияжа, моды и стиля.</p>\r\n<p style=\\\"\\\\\\\">Буду очень благодарна, если вы оставите для меня послание в гостевой книге. Мне очень важны ваши отзывы!</p>','2009-03-18 13:11:18','2009-04-04 15:13:27','2009-03-18 13:11:00','2019-03-18 13:11:00','',0,0,'article',20,'ru_RU',0,'');
/*!40000 ALTER TABLE `articles` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `attachments`
--

LOCK TABLES `attachments` WRITE;
/*!40000 ALTER TABLE `attachments` DISABLE KEYS */;
INSERT INTO `attachments` VALUES (7,'sshot.png','sshot.png','0000-00-00 00:00:00','2009-03-03 01:44:52',74937,'image/png','065598544782d0e6cc0858d74bb44f63',0,9,0),(11,'21461970.jpg','21461970.jpg','2009-04-29 23:36:30','0000-00-00 00:00:00',29907,'image/jpeg','adbf0370d23e706c562b4c5b73efbf64',0,10,0),(16,'21639246.jpg','21639246.jpg','2009-04-29 23:56:27','0000-00-00 00:00:00',24464,'image/jpeg','cf1b0b9816f21943dd5543bacfe8130b',0,10,0),(17,'05022007-6.jpg','05022007-6.jpg','2009-04-29 23:56:45','0000-00-00 00:00:00',59104,'image/jpeg','9d729a71bc022ea4da4f11b36150ee32',0,10,0),(18,'goto.jpg','goto.jpg','2009-04-29 23:57:01','0000-00-00 00:00:00',45348,'image/jpeg','6c3b45d37a39a48944addf2dfc0bb004',0,10,0),(19,'94.cbd6440046231a86fd598dcde15071bb.jpg','94.cbd6440046231a86fd598dcde15071bb.jpg','2009-04-29 23:57:17','0000-00-00 00:00:00',94688,'image/jpeg','059a35e7fbe1a1574639334d2cfff6cc',0,10,0),(20,'pic0010dxmv5.jpg','pic0010dxmv5.jpg','2009-04-29 23:57:32','0000-00-00 00:00:00',99179,'image/jpeg','7477ca789e08bb9a77608a252e1f84de',0,10,0),(21,'19022007-43.jpg','19022007-43.jpg','2009-04-29 23:57:51','0000-00-00 00:00:00',71907,'image/jpeg','22ec07143f3c5fb45516cbf8334e1da4',0,10,0),(22,'LMnmGdk1ODs.flv','LMnmGdk1ODs.flv','2009-04-29 23:58:07','0000-00-00 00:00:00',1643344,'video/x-flv','469de23fd1dc77932161350460d3e9d0',0,10,0),(25,'nasest.jpg','nasest.jpg','2009-04-29 23:58:28','0000-00-00 00:00:00',25131,'image/jpeg','67b5a3d7f1bac0d2a3ceb4cc92f4a1ef',0,10,0),(27,'rat_wallsRemy_1280.jpg','rat_wallsRemy_1280.jpg','2009-04-29 23:59:24','2009-04-05 21:33:09',186147,'image/jpeg','870202a776f4a582dc09b070ab0e0158',0,10,0),(32,'colors.html','colors.html','2009-05-01 08:50:08','2009-05-01 08:50:08',98129,'text/html','24680449dc0a7399c37cdaef8c54507b',0,10,0);
/*!40000 ALTER TABLE `attachments` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `bugnotes`
--

LOCK TABLES `bugnotes` WRITE;
/*!40000 ALTER TABLE `bugnotes` DISABLE KEYS */;
/*!40000 ALTER TABLE `bugnotes` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `bugs`
--

LOCK TABLES `bugs` WRITE;
/*!40000 ALTER TABLE `bugs` DISABLE KEYS */;
/*!40000 ALTER TABLE `bugs` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `comments`
--

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
INSERT INTO `comments` VALUES (38,11,0,'2009-03-27 23:39:19','2009-03-27 23:39:19','','Без темы','ljlkjlkj','lkjlkjklj',''),(29,9,0,'2009-03-17 10:13:58','2009-03-17 10:13:58','','Без темы','we32','230ew',''),(31,10,0,'2009-03-18 13:02:24','2009-03-18 13:02:24','','Без темы','kjhkjhkjhkj','kjhkjhkjh','jkjkhkjhj@kkk.ru'),(19,9,0,'2009-03-17 07:02:41','2009-03-17 07:02:41','','Без темы','kjhkjhkhk','kkjk','hkjhjkhkj@dd.net'),(20,9,0,'2009-03-17 07:04:18','2009-03-17 07:04:18','','Без темы','kjhkjhkhk','kkjk','hkjhjkhkj@dd.net'),(21,9,0,'2009-03-17 07:04:47','2009-03-17 07:04:47','','Без темы','kjhkjhkhk','kkjk','hkjhjkhkj@dd.net'),(22,9,0,'2009-03-17 07:06:21','2009-03-17 07:06:21','','Без темы','kjhkjhkhk','kkjk','hkjhjkhkj@dd.net'),(23,9,0,'2009-03-17 07:06:57','2009-03-17 07:06:57','','Без темы','kjhkjhkhk','kkjk','hkjhjkhkj@dd.net'),(24,9,0,'2009-03-17 07:07:10','2009-03-17 07:07:10','','Без темы','jhkjhkj','lkjhjhjk',''),(25,9,0,'2009-03-17 07:09:45','2009-03-17 07:09:45','','Без темы','lkklkjlkklj','lkjklkjl','milezv@yandex.ru'),(26,9,0,'2009-03-17 07:13:01','2009-03-17 07:13:01','','Без темы','kljlkj','123klkljl','kjljk@kkkk.ru'),(27,9,0,'2009-03-17 10:12:57','2009-03-17 10:12:57','','Без темы','kljlkj','kjklkj','hello@mail.ru'),(28,9,0,'2009-03-17 10:13:40','2009-03-17 10:13:40','','Без темы','opoipo','kslkk','klllk@mail.ru'),(37,11,0,'2009-03-27 23:39:11','2009-03-27 23:39:11','','Без темы','lkjkljkljl','kjkllkjklj','');
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `config`
--

LOCK TABLES `config` WRITE;
/*!40000 ALTER TABLE `config` DISABLE KEYS */;
INSERT INTO `config` VALUES (1,4,'0',''),(3,4,'prefix','/engine'),(4,0,'routes',''),(5,1,'match','#^(admin/[^/]+)(?:(?:/([a-z][a-z0-9]+))?(?:/([0-9]+))?)?$#'),(6,1,'cache','0'),(7,1,'controller','1');
/*!40000 ALTER TABLE `config` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `groups`
--

LOCK TABLES `groups` WRITE;
/*!40000 ALTER TABLE `groups` DISABLE KEYS */;
INSERT INTO `groups` VALUES (1,18,'user','users','Пользователи',2,3),(3,0,'admin','administartors','Администраторы',0,5),(18,3,'user','editors','Редакторы',1,4);
/*!40000 ALTER TABLE `groups` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `polls`
--

LOCK TABLES `polls` WRITE;
/*!40000 ALTER TABLE `polls` DISABLE KEYS */;
/*!40000 ALTER TABLE `polls` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `questions`
--

LOCK TABLES `questions` WRITE;
/*!40000 ALTER TABLE `questions` DISABLE KEYS */;
/*!40000 ALTER TABLE `questions` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `rights`
--

LOCK TABLES `rights` WRITE;
/*!40000 ALTER TABLE `rights` DISABLE KEYS */;
INSERT INTO `rights` VALUES ('article',12,2,18,500),('article',10,2,18,500),('topic',11,1,3,500),('article',11,1,3,500),('topic',9,1,3,500),('topic',47,1,3,500),('article',13,0,0,500),('topic',22,0,0,500),('topic',48,0,0,500),('attachment',11,0,0,500),('attachment',16,0,0,500),('attachment',17,0,0,500),('attachment',18,0,0,500),('attachment',19,0,0,500),('attachment',20,0,0,500),('attachment',21,0,0,500),('attachment',22,0,0,500),('attachment',25,0,0,500),('attachment',27,0,0,500),('article',14,0,0,500);
/*!40000 ALTER TABLE `rights` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `sites`
--

LOCK TABLES `sites` WRITE;
/*!40000 ALTER TABLE `sites` DISABLE KEYS */;
/*!40000 ALTER TABLE `sites` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `tag_relations`
--

LOCK TABLES `tag_relations` WRITE;
/*!40000 ALTER TABLE `tag_relations` DISABLE KEYS */;
/*!40000 ALTER TABLE `tag_relations` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `tags`
--

LOCK TABLES `tags` WRITE;
/*!40000 ALTER TABLE `tags` DISABLE KEYS */;
/*!40000 ALTER TABLE `tags` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `topics`
--

LOCK TABLES `topics` WRITE;
/*!40000 ALTER TABLE `topics` DISABLE KEYS */;
INSERT INTO `topics` VALUES (9,0,0,9,'aboutme','Обо мне','',47,12,'','article',10,'sortorder',0),(7,9,1,8,'portfolio','Портфолио','',0,0,'','article',20,'sortorder',0),(11,7,4,5,'photos','Фотографии работ','',0,0,'','gallery',20,'sortorder',0),(13,7,2,3,'masterclasses','Мастер-классы','',0,0,'','article',20,'sortorder',0),(23,7,6,7,'articles','Статьи','',0,0,'','article',20,'sortorder',0),(22,0,10,11,'news','Новости','<p>Новости на сайте</p>',0,0,'recursive','article',20,'published_at',1);
/*!40000 ALTER TABLE `topics` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Администратор','milezv@yandex.ru','2009-02-20 10:16:43','root','827ccb0eea8a706c4c34a16891f84e7b','superuser',3,'','0000-00-00 00:00:00',0,'male'),(2,'Настя','vostorg@tut.by','2009-03-31 00:16:38','nastassya','5f4dcc3b5aa765d61d8327deb882cf99','admin',18,'','0000-00-00 00:00:00',0,'female');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `variants`
--

LOCK TABLES `variants` WRITE;
/*!40000 ALTER TABLE `variants` DISABLE KEYS */;
/*!40000 ALTER TABLE `variants` ENABLE KEYS */;
UNLOCK TABLES;

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
