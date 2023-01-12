-- Adminer 4.8.1 MySQL 5.7.40 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;

SET SQL_MODE = STRICT_ALL_TABLES;

ALTER DATABASE `mydb` COLLATE utf8_general_ci;

USE `mydb`;

DROP TABLE IF EXISTS `articles`;
CREATE TABLE `articles` (
                            `id` int(11) NOT NULL AUTO_INCREMENT,
                            `title` varchar(255) NOT NULL,
                            `perex` varchar(1000) NOT NULL,
                            `text` text NOT NULL,
                            `post_on` datetime NOT NULL,
                            `need_login` bit(1) NOT NULL,
                            PRIMARY KEY (`id`)
) ENGINE=InnoDB;

DROP TABLE IF EXISTS `article_rank`;
CREATE TABLE `article_rank` (
                                `id` int(11) NOT NULL AUTO_INCREMENT,
                                `article_id` int(11) NOT NULL,
                                `user_id` int(11) DEFAULT NULL,
                                `value` tinyint(4) NOT NULL,
                                `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                PRIMARY KEY (`id`),
                                KEY `article_id` (`article_id`),
                                KEY `user_id` (`user_id`),
                                CONSTRAINT `article_rank_article` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`),
                                CONSTRAINT `article_rank_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB;

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
                         `id` int(11) NOT NULL AUTO_INCREMENT,
                         `name` varchar(255) NOT NULL,
                         `password` varchar(255) NOT NULL,
                         `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                         PRIMARY KEY (`id`),
                         UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB;

DROP VIEW IF EXISTS `v_articles`;

CREATE VIEW v_articles AS
SELECT a.*,  AVG(a_r.value) AS 'rank'
FROM articles AS a
         LEFT JOIN article_rank AS a_r ON a_r.article_id = a.id
GROUP BY a.id;
