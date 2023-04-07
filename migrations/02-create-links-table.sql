CREATE TABLE IF NOT EXISTS `link` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `link` varchar(255) NOT NULL,
    `page_id` int(11) NOT NULL,
    PRIMARY KEY (`id`),
    KEY `page_id` (`page_id`),
    CONSTRAINT link_ibfk_1 FOREIGN KEY (page_id) REFERENCES `page` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
