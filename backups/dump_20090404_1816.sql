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
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `articles`
--

LOCK TABLES `articles` WRITE;
/*!40000 ALTER TABLE `articles` DISABLE KEYS */;
INSERT INTO `articles` VALUES (13,11,1,'Ещё одна статья','esche_odna_statya','','<p>Статья</p>','2009-03-27 22:55:35','2009-04-02 21:31:47','2009-03-27 22:55:00','2019-03-27 22:55:00','',3,0,'article',20,'ru_RU',0,''),(10,11,1,'Галерея','galereya','<p>Статья-галерея</p>','<p>Пример простейшей галереи изображений.</p>','2009-03-05 05:15:59','2009-04-04 15:14:01','2009-03-05 09:00:00','2019-03-07 12:00:00','comments',1,0,'gallery',5,'ru_RU',0,''),(11,11,1,'Обратная связь','obratnaya_svyaz','<p>Можете здесь оставлять свои комментарии.</p>','<p>А можете и не оставлять :)</p>','2009-03-14 00:25:11','2009-04-02 03:05:28','2009-03-14 00:25:00','2029-03-14 00:25:00','comments',2,0,'article',1,'ru_RU',0,''),(12,9,1,'Добро пожаловать на мой сайт!','dobro_pozhalovat_na_moj_sajt','','<p><!-- 		@page { size: 21cm 29.7cm; margin: 2cm } 		P { margin-bottom: 0.21cm } 	--></p>\r\n<p>Добро пожаловать на мой сайт! Будьте уверены, здесь всегда рады гостям. После того, как вы ознакомитесь с информацией, здесь предоставленной, не забудьте вернуться сюда еще раз через некоторое время. Уверяю, на этом сайте вы всегда сможете найти для себя что-то новенькое.</p>\r\n<p>Немного слов обо мне: меня зовут Анастасия Валицкая. Я мастер макияжа и стиля международной квалификации, и, если вы посетили меня, то, скорее всего, мы знакомы.</p>\r\n<p style=\\\"\\\\\\\">Макияж, мода, стиль, имидж &mdash; это моя жизнь. Поэтому, если у нас схожие интересы, мы обязательно подружимся.</p>\r\n<p style=\\\"\\\\\\\">В области создания образа и макияжа я работаю уже 5 лет, до этого еще пять лет изучала гармонию цвета, макияж, стиль и, конечно, получила педагогическое образование.</p>\r\n<p style=\\\"\\\\\\\">За моими плечами колоссальный опыт работы с клиентами в качестве визажиста в торжественных случаях и в роли консультанта для создания изысканного образа на каждый день. Участвовала в телевизионных проектах, занималась постановкой бодиарт-шоу и перформансшоу. В последнее время основной акцент моей деятельности направлен на проведение обучающих мастер-классов как для профессионалов, так и для любителей.</p>\r\n<p style=\\\"\\\\\\\">Идея создать данный проект возникла в рамках школы по макияжу и стилю, открытием которой я в данный момент занимаюсь. Главной задачей школы будет дать любой женщине необходимые знания и помочь ей в создании ее собственного неповторимого образа.</p>\r\n<p style=\\\"\\\\\\\">Информация на сайте будет постоянно обновляться, таким образом, вы всегда сможете быть в курсе последних новостей в области макияжа, моды и стиля.</p>\r\n<p style=\\\"\\\\\\\">Буду очень благодарна, если вы оставите для меня послание в гостевой книге. Мне очень важны ваши отзывы!</p>','2009-03-18 13:11:18','2009-04-04 15:13:27','2009-03-18 13:11:00','2019-03-18 13:11:00','',0,0,'article',20,'ru_RU',0,'');
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
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `attachments`
--

LOCK TABLES `attachments` WRITE;
/*!40000 ALTER TABLE `attachments` DISABLE KEYS */;
INSERT INTO `attachments` VALUES (7,'sshot.png','sshot.png','0000-00-00 00:00:00','2009-03-03 01:44:52',74937,'image/png','065598544782d0e6cc0858d74bb44f63',0,9,0),(11,'05022007-6.jpg','05022007-6.jpg','2009-03-05 04:50:34','0000-00-00 00:00:00',59104,'image/jpeg','9d729a71bc022ea4da4f11b36150ee32',0,10,0),(16,'fotoprikol_017.jpg','fotoprikol_017.jpg','2009-03-05 05:15:59','0000-00-00 00:00:00',25810,'image/jpeg','e6a0596e110556551723e9856811b14f',0,10,0),(17,'d42448eeada8af49de2f561004462e18.jpg','d42448eeada8af49de2f561004462e18.jpg','2009-03-05 05:15:59','0000-00-00 00:00:00',27958,'image/jpeg','20a713cbf670ded10fcabdc49ae50792',0,10,0),(18,'goto.jpg','goto.jpg','2009-03-05 05:15:59','0000-00-00 00:00:00',45348,'image/jpeg','6c3b45d37a39a48944addf2dfc0bb004',0,10,0),(19,'Лисички.jpg','Лисички.jpg','2009-03-05 05:15:59','0000-00-00 00:00:00',271376,'image/jpeg','818848eda933e7c1604917de48cb4560',0,10,0),(20,'nasest.jpg','nasest.jpg','2009-03-05 05:15:59','0000-00-00 00:00:00',25131,'image/jpeg','67b5a3d7f1bac0d2a3ceb4cc92f4a1ef',0,10,0),(21,'Восход.jpg','Восход.jpg','2009-03-05 05:15:59','0000-00-00 00:00:00',35577,'image/jpeg','c270595d083eca030b2a64ba622c482d',0,10,0),(22,'contractea7.jpg','contractea7.jpg','2009-03-05 05:15:59','0000-00-00 00:00:00',45559,'image/jpeg','5b89304ee548ac435cae0f161213fc7f',0,10,0),(25,'Оливье.jpg','Оливье.jpg','2009-03-05 05:15:59','0000-00-00 00:00:00',125568,'image/jpeg','58ad4bbe2c6dbf7463ff4074c7222161',0,10,0);
/*!40000 ALTER TABLE `attachments` ENABLE KEYS */;
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
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `groups`
--

LOCK TABLES `groups` WRITE;
/*!40000 ALTER TABLE `groups` DISABLE KEYS */;
INSERT INTO `groups` VALUES (1,0,'user','users','Пользователи'),(3,0,'admin','administartors','Администраторы'),(18,0,'user','editors','Редакторы');
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
  `flags` set('closed','archived','hidden') NOT NULL,
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
INSERT INTO `rights` VALUES ('article',12,2,18,500),('article',10,2,18,496);
/*!40000 ALTER TABLE `rights` ENABLE KEYS */;
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
-- Dumping data for table `topics`
--

LOCK TABLES `topics` WRITE;
/*!40000 ALTER TABLE `topics` DISABLE KEYS */;
INSERT INTO `topics` VALUES (9,0,0,11,'aboutme','Обо мне','',0,12,'','article',10),(7,9,3,10,'portfolio','Портфолио','',0,0,'','article',20),(11,7,8,9,'photos','Фотографии работ','',0,0,'','gallery',20),(12,7,6,7,'performances','Перформансы','',0,0,'','article',20),(13,7,4,5,'masterclasses','Мастер-классы','',0,0,'','article',20),(23,9,1,2,'articles','Статьи','',0,0,'','article',20),(22,0,12,13,'news','Новости','<p>Новости на сайте</p>',0,0,'','article',20);
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
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Администратор','milezv@yandex.ru','2009-02-20 10:16:43','root','827ccb0eea8a706c4c34a16891f84e7b','superuser',3,'inactive','0000-00-00 00:00:00',0,'male'),(2,'Настя','vostorg@tut.by','2009-03-31 00:16:38','nastassya','5f4dcc3b5aa765d61d8327deb882cf99','admin',18,'','0000-00-00 00:00:00',0,'female');
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
/*!50001 VIEW `visible_articles` AS select `articles`.`id` AS `id`,`articles`.`topic_id` AS `topic_id`,`articles`.`author_id` AS `author_id`,`articles`.`title` AS `title`,`articles`.`name` AS `name`,`articles`.`abstract` AS `abstract`,`articles`.`content` AS `content`,`articles`.`created_at` AS `created_at`,`articles`.`modified_at` AS `modified_at`,`articles`.`published_at` AS `published_at`,`articles`.`archived_at` AS `archived_at`,`articles`.`flags` AS `flags`,`articles`.`sortorder` AS `sortorder`,`articles`.`views` AS `views`,`articles`.`type` AS `type`,`articles`.`items_per_page` AS `items_per_page`,`articles`.`language` AS `language`,`articles`.`original_id` AS `original_id`,`articles`.`link` AS `link` from `articles` where ((not((`articles`.`flags` & 14))) and (now() between `articles`.`published_at` and `articles`.`archived_at`)) */;

--
-- Final view structure for view `visible_topics`
--

/*!50001 DROP TABLE `visible_topics`*/;
/*!50001 DROP VIEW IF EXISTS `visible_topics`*/;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `visible_topics` AS select `topics`.`id` AS `id`,`topics`.`parent_id` AS `parent_id`,`topics`.`lside` AS `lside`,`topics`.`rside` AS `rside`,`topics`.`name` AS `name`,`topics`.`title` AS `title`,`topics`.`description` AS `description`,`topics`.`subtopic_id` AS `subtopic_id`,`topics`.`article_id` AS `article_id`,`topics`.`flags` AS `flags`,`topics`.`type` AS `type`,`topics`.`items_per_page` AS `items_per_page` from `topics` where (not(`topics`.`flags`)) */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2009-04-04 15:16:14
