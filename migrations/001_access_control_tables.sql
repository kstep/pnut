--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL default '0000-00-00 00:00:00',
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `groups`;
CREATE TABLE `groups` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `parent_id` int(10) unsigned NOT NULL,
  `role` enum('superuser','admin','manager','user') NOT NULL default 'user',
  `name` varchar(255) NOT NULL default '',
  `title` varchar(255) NOT NULL default '',
  `lside` int(10) unsigned NOT NULL default '0',
  `rside` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Table structure for table `rights`
--

DROP TABLE IF EXISTS `rights`;
CREATE TABLE `rights` (
  `object_type` enum('article','attachment','topic','poll') NOT NULL,
  `object_id` int(10) unsigned NOT NULL,
  `owner_id` int(10) unsigned NOT NULL,
  `group_id` int(10) unsigned NOT NULL,
  `rights` int(11) NOT NULL,
  PRIMARY KEY  (`object_type`,`object_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `config`
--

DROP TABLE IF EXISTS `config`;
CREATE TABLE `config` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `section_id` int(10) unsigned NOT NULL default '0',
  `name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

