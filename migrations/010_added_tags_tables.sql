--
-- Table structure for table `tags`
--

DROP TABLE IF EXISTS `tags`;
CREATE TABLE `tags` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `syn_id` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;

--
-- Table structure for table `tag_relations`
--

DROP TABLE IF EXISTS `tag_relations`;
CREATE TABLE `tag_relations` (
  `obj_type` enum('articles','topics') NOT NULL default 'articles',
  `obj_id` int(10) unsigned NOT NULL,
  `tag_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`obj_type`,`obj_id`)
) ENGINE=MyISAM;
