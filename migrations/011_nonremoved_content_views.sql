
DROP VIEW IF EXISTS `nonremoved_articles`;
CREATE VIEW `nonremoved_articles` AS select `articles`.`id` AS `id`,`articles`.`topic_id` AS `topic_id`,`articles`.`author_id` AS `author_id`,`articles`.`title` AS `title`,`articles`.`name` AS `name`,`articles`.`abstract` AS `abstract`,`articles`.`content` AS `content`,`articles`.`created_at` AS `created_at`,`articles`.`modified_at` AS `modified_at`,`articles`.`published_at` AS `published_at`,`articles`.`archived_at` AS `archived_at`,`articles`.`flags` AS `flags`,`articles`.`sortorder` AS `sortorder`,`articles`.`views` AS `views`,`articles`.`type` AS `type`,`articles`.`items_per_page` AS `items_per_page`,`articles`.`language` AS `language`,`articles`.`original_id` AS `original_id`,`articles`.`link` AS `link` from `articles` where not(`articles`.`flags` & 4);

DROP VIEW IF EXISTS `nonremoved_topics`;
CREATE VIEW `nonremoved_topics` AS select `topics`.`id` AS `id`,`topics`.`parent_id` AS `parent_id`,`topics`.`lside` AS `lside`,`topics`.`rside` AS `rside`,`topics`.`name` AS `name`,`topics`.`title` AS `title`,`topics`.`description` AS `description`,`topics`.`subtopic_id` AS `subtopic_id`,`topics`.`article_id` AS `article_id`,`topics`.`flags` AS `flags`,`topics`.`type` AS `type`,`topics`.`items_per_page` AS `items_per_page`, `topics`.`articles_sort` AS `articles_sort`, `topics`.`articles_sort_desc` AS `articles_sort_desc` from `topics` where not(`topics`.`flags` & 1);

DROP VIEW IF EXISTS `nonremoved_comments`;
CREATE VIEW `nonremoved_comments` AS select `comments`.`id` AS `id`,`comments`.`article_id` AS `article_id`,`comments`.`author_id` AS `author_id`,`comments`.`modified_at` AS `modified_at`,`comments`.`created_at` AS `created_at`,`comments`.`flags` AS `flags`,`comments`.`title` AS `title`,`comments`.`content` AS `content`,`comments`.`username` AS `username`,`comments`.`email` AS `email` from `comments` where not(`comments`.`flags` & 1)

