--
-- Table structure for table `articles`
--

DROP TABLE IF EXISTS `articles`;
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- Table structure for table `attachments`
--

DROP TABLE IF EXISTS `attachments`;
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Table structure for table `topics`
--

DROP TABLE IF EXISTS `topics`;
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
