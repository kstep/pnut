--
-- Table structure for table `sites`
--

DROP TABLE IF EXISTS `sites`;
CREATE TABLE `sites` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `title` varchar(255) NOT NULL,
  `host` varchar(255) NOT NULL COMMENT 'Хост',
  `prefix` varchar(255) NOT NULL COMMENT 'Префикс',
  `realm` varchar(255) NOT NULL,
  `topic_id` int(10) unsigned NOT NULL COMMENT 'Корневой раздел сайта',
  `group_id` int(10) unsigned NOT NULL COMMENT 'Группа администраторов сайта',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
