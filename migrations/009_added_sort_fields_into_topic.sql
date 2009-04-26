ALTER TABLE `topics` ADD COLUMN `articles_sort` ENUM('sortorder', 'title', 'name', 'published_at', 'archived_at', 'created_at', 'modified_at') NOT NULL DEFAULT 'sortorder';
ALTER TABLE `topics` ADD COLUMN `articles_sort_desc` TINYINT(1) NOT NULL DEFAULT 0;
