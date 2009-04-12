--
-- Table structure for table `bugs`
--

DROP TABLE IF EXISTS `bugs`;
CREATE TABLE `bugs` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `severity` enum('trivial','minor','normal','major','blocker') NOT NULL default 'normal',
  `priority` enum('low','normal','high','urgent','immediate') NOT NULL default 'normal',
  `state` enum('unconfirmed','new','assigned','progress','fixed','rejected','feedback','closed') NOT NULL default 'unconfirmed',
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
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
