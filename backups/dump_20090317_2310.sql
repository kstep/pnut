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
  `owner_id` int(10) unsigned NOT NULL COMMENT 'владелец',
  `group_id` int(10) unsigned NOT NULL COMMENT 'группа',
  `title` varchar(255) NOT NULL,
  `abstract` text NOT NULL,
  `content` text NOT NULL,
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
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `articles`
--

LOCK TABLES `articles` WRITE;
/*!40000 ALTER TABLE `articles` DISABLE KEYS */;
INSERT INTO `articles` VALUES (9,9,1,1,1,'Тестовая статья','<p>Был ноябрь, холодный и дождливый месяц на западе Арканзаса. Вслед за отвратительной ночью наступал не менее отвратительный рассвет. Мокрый снег с дождем свистел между соснами, скапливаясь на верхушках торчащих из земли камней; прямо над головой проносились сердитые облака. Время от времени ветер порывами налетал на каньоны и, проносясь между деревьями, рассеивал мокрый снег, как пушечный дым. До наступления охотничьего сезона оставался один день.</p>','<p>Боб Ли Суэггер расположился сразу за последним подъемом, ведущим в долину Большой Сделки, которая находилась высоко в горах Уошито и была ровной, как крышка стола. В полном молчании и абсолютной тишине он сидел напротив старой сосны, поставив между коленей винтовку. Это был главный дар Боба умение хранить тишину. Он нигде этому не учился, просто черпал силы из какого-то собственного потайного внутреннего источника, никогда не реагирующего на внешние раздражители. Тогда, во Вьетнаме, о нем ходили легенды из-за того, что он, как зверь, мог полностью замереть и продолжительное время сохранять абсолютную, можно сказать, мертвую неподвижность.</p>\r\n<p>Холод забрался к нему под гамаши и, дойдя до короткой куртки, стал проникать под нее, поднимаясь по позвоночнику как маленькая пронырливая мышка. Стиснув зубы, он поборол настойчивое желание застучать ими от холода. Время от времени от полученной давным-давно раны начинало ныть бедро. Но он приказал мозгу не обращать внимания на эту боль. Сейчас он был выше собственных неудобств и желаний. Его мысли были совсем в другом месте. Он поджидал Тима.</p>\r\n<p>Понимаете, если бы вы были одним из тех немногочисленных &mdash; может быть, двух-трех &mdash; мужчин, с которыми он вообще разговаривает в этом мире &mdash; старым Сэмом Винсентом, бывшим прокурором графства Полк, или, может быть, доктором Ле Мьексом, дантистом, или Верноном Теллом, шерифом, &mdash; то тогда он сказал бы вам, что нельзя взять и просто так выстрелить в животное. Выстрелить &mdash; это слишком просто. Любой городской фраер может сидеть в засаде и, попивая горячий кофе, ждать, пока самка оленя гордо пройдет рядом с ним, причем настолько близко, что ее можно коснуться рукой. Только тогда он выставит ствол своей винтовки и судорожно нажмет на курок. Выпустив ей таким образом кишки, он найдет ее, истекающую кровью, на расстоянии трех графств отсюда и увидит в ее глазах застывшую тупую боль.</p>\r\n<p>Если бы вы были одним из тех мужчин, Боб сказал бы вам, что вы можете заслужить свое право на выстрел лишь тем, что сами когда-то побывали в шкуре зверя и с вами происходило все то же самое, что может произойти со зверем на охоте, и совсем неважно, сколько это длилось. В конце концов, игра велась по правилам.</p>','2009-03-05 04:14:13','2009-03-14 04:25:23','2009-03-01 14:00:00','2019-03-03 10:00:00','comments',0,500,0,'article',10),(10,11,1,1,1,'Галерея','<p>Статья-галерея</p>','<p>Пример простейшей галереи изображений.</p>','2009-03-05 05:15:59','2009-03-16 22:32:06','2009-03-05 09:00:00','2009-03-07 12:00:00','comments',0,500,0,'gallery',5),(11,11,1,1,1,'Обратная связь','<p>Можете здесь оставлять свои комментарии.</p>','<p>А можете и не оставлять :)</p>','2009-03-14 00:25:11','2009-03-15 01:13:21','2009-03-14 00:25:00','2009-03-14 00:25:00','comments',0,500,0,'article',5);
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
-- Dumping data for table `attachments`
--

LOCK TABLES `attachments` WRITE;
/*!40000 ALTER TABLE `attachments` DISABLE KEYS */;
INSERT INTO `attachments` VALUES (7,'sshot.png','sshot.png','0000-00-00 00:00:00','2009-03-03 01:44:52',74937,'image/png','065598544782d0e6cc0858d74bb44f63',0,0,0,0,9,0),(11,'05022007-6.jpg','05022007-6.jpg','2009-03-05 04:50:34','0000-00-00 00:00:00',59104,'image/jpeg','9d729a71bc022ea4da4f11b36150ee32',0,0,0,0,10,0),(16,'fotoprikol_017.jpg','fotoprikol_017.jpg','2009-03-05 05:15:59','0000-00-00 00:00:00',25810,'image/jpeg','e6a0596e110556551723e9856811b14f',0,0,0,0,10,0),(17,'d42448eeada8af49de2f561004462e18.jpg','d42448eeada8af49de2f561004462e18.jpg','2009-03-05 05:15:59','0000-00-00 00:00:00',27958,'image/jpeg','20a713cbf670ded10fcabdc49ae50792',0,0,0,0,10,0),(18,'goto.jpg','goto.jpg','2009-03-05 05:15:59','0000-00-00 00:00:00',45348,'image/jpeg','6c3b45d37a39a48944addf2dfc0bb004',0,0,0,0,10,0),(19,'Лисички.jpg','Лисички.jpg','2009-03-05 05:15:59','0000-00-00 00:00:00',271376,'image/jpeg','818848eda933e7c1604917de48cb4560',0,0,0,0,10,0),(20,'nasest.jpg','nasest.jpg','2009-03-05 05:15:59','0000-00-00 00:00:00',25131,'image/jpeg','67b5a3d7f1bac0d2a3ceb4cc92f4a1ef',0,0,0,0,10,0),(21,'Восход.jpg','Восход.jpg','2009-03-05 05:15:59','0000-00-00 00:00:00',35577,'image/jpeg','c270595d083eca030b2a64ba622c482d',0,0,0,0,10,0),(22,'contractea7.jpg','contractea7.jpg','2009-03-05 05:15:59','0000-00-00 00:00:00',45559,'image/jpeg','5b89304ee548ac435cae0f161213fc7f',0,0,0,0,10,0),(25,'Оливье.jpg','Оливье.jpg','2009-03-05 05:15:59','0000-00-00 00:00:00',125568,'image/jpeg','58ad4bbe2c6dbf7463ff4074c7222161',0,0,0,0,10,0);
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
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `comments`
--

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
INSERT INTO `comments` VALUES (29,9,0,'2009-03-17 10:13:58','2009-03-17 10:13:58','','Без темы','we32','230ew',''),(18,11,0,'2009-03-15 01:14:33','2009-03-15 01:14:33','','Без темы','додлодлод','',''),(19,9,0,'2009-03-17 07:02:41','2009-03-17 07:02:41','','Без темы','kjhkjhkhk','kkjk','hkjhjkhkj@dd.net'),(20,9,0,'2009-03-17 07:04:18','2009-03-17 07:04:18','','Без темы','kjhkjhkhk','kkjk','hkjhjkhkj@dd.net'),(21,9,0,'2009-03-17 07:04:47','2009-03-17 07:04:47','','Без темы','kjhkjhkhk','kkjk','hkjhjkhkj@dd.net'),(22,9,0,'2009-03-17 07:06:21','2009-03-17 07:06:21','','Без темы','kjhkjhkhk','kkjk','hkjhjkhkj@dd.net'),(23,9,0,'2009-03-17 07:06:57','2009-03-17 07:06:57','','Без темы','kjhkjhkhk','kkjk','hkjhjkhkj@dd.net'),(24,9,0,'2009-03-17 07:07:10','2009-03-17 07:07:10','','Без темы','jhkjhkj','lkjhjhjk',''),(25,9,0,'2009-03-17 07:09:45','2009-03-17 07:09:45','','Без темы','lkklkjlkklj','lkjklkjl','milezv@yandex.ru'),(26,9,0,'2009-03-17 07:13:01','2009-03-17 07:13:01','','Без темы','kljlkj','123klkljl','kjljk@kkkk.ru'),(27,9,0,'2009-03-17 10:12:57','2009-03-17 10:12:57','','Без темы','kljlkj','kjklkj','hello@mail.ru'),(28,9,0,'2009-03-17 10:13:40','2009-03-17 10:13:40','','Без темы','opoipo','kslkk','klllk@mail.ru');
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
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `groups`
--

LOCK TABLES `groups` WRITE;
/*!40000 ALTER TABLE `groups` DISABLE KEYS */;
INSERT INTO `groups` VALUES (1,0,'user','users','Пользователи');
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
  `owner_id` int(10) unsigned NOT NULL,
  `group_id` int(10) unsigned NOT NULL,
  `rights` smallint(6) NOT NULL default '496',
  `sortorder` int(11) NOT NULL default '0',
  `flags` set('hidden','removed','archived') NOT NULL,
  `type` enum('article','gallery') NOT NULL default 'article',
  `items_per_page` int(10) unsigned NOT NULL default '20',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`parent_id`,`name`),
  KEY `parent_id` (`parent_id`),
  KEY `sortorder` USING BTREE (`lside`,`sortorder`,`id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `topics`
--

LOCK TABLES `topics` WRITE;
/*!40000 ALTER TABLE `topics` DISABLE KEYS */;
INSERT INTO `topics` VALUES (9,7,1,2,'aboutme','Обо мне','',0,1,1,1,0,'','article',10),(10,7,3,10,'myworks','Мои работы','',0,1,1,1,0,'','article',20),(7,0,0,11,'portfolio','Портфолио','',9,0,0,496,0,'','article',20),(8,0,12,13,'school','Школа','',0,0,0,496,0,'','article',20),(11,10,4,5,'brides','Фото невесты','',0,1,1,1,0,'','gallery',20),(12,10,6,7,'performances','Перформансы','',0,1,1,1,0,'','article',20),(13,10,8,9,'masterclasses','Мастер-классы','',0,0,0,496,0,'','article',20),(14,0,14,15,'marykay','Mary Kay','',0,1,1,1,0,'','article',10);
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
  PRIMARY KEY  (`id`),
  KEY `login` (`login`(32),`password`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Администратор','','2009-02-20 10:16:43','root','202cb962ac59075b964b07152d234b70','superuser',1,'','0000-00-00 00:00:00',0);
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
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2009-03-17 21:10:07
