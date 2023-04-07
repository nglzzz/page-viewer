CREATE TABLE IF NOT EXISTS `page` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `mime` varchar(255) NOT NULL,
    `title` varchar(255) NOT NULL,
    `text` text NOT NULL,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
