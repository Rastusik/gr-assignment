-- Adminer 4.2.4 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `account`;
CREATE TABLE `account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `owner_id` int(11) NOT NULL,
  `balance` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_7D3656A47E3C61F9` (`owner_id`),
  CONSTRAINT `FK_7D3656A47E3C61F9` FOREIGN KEY (`owner_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `account` (`id`, `owner_id`, `balance`) VALUES
(3,	5,	200),
(4,	2,	878),
(6,	2,	1990);

DROP TABLE IF EXISTS `account_disponent__account__user`;
CREATE TABLE `account_disponent__account__user` (
  `account_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`account_id`,`user_id`),
  KEY `IDX_135873289B6B5FBA` (`account_id`),
  KEY `IDX_13587328A76ED395` (`user_id`),
  CONSTRAINT `FK_135873289B6B5FBA` FOREIGN KEY (`account_id`) REFERENCES `account` (`id`),
  CONSTRAINT `FK_13587328A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `account_disponent__account__user` (`account_id`, `user_id`) VALUES
(3,	6),
(4,	2),
(4,	4),
(6,	2);

DROP TABLE IF EXISTS `migration_versions`;
CREATE TABLE `migration_versions` (
  `version` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `migration_versions` (`version`) VALUES
('20160621214353'),
('20160621215154'),
('20160622190803'),
('20160622201517'),
('20160622220640'),
('20160623011735'),
('20160623232423');

DROP TABLE IF EXISTS `role`;
CREATE TABLE `role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_57698A6A5E237E06` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `role` (`id`, `name`) VALUES
(1,	'ROLE_ADMIN'),
(2,	'ROLE_USER');

DROP TABLE IF EXISTS `transaction`;
CREATE TABLE `transaction` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `disponent_id` int(11) NOT NULL,
  `account_from_id` int(11) NOT NULL,
  `account_to_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `IDX_723705D147E13D68` (`disponent_id`),
  KEY `IDX_723705D1B1E5CD43` (`account_from_id`),
  KEY `IDX_723705D16BA9314` (`account_to_id`),
  CONSTRAINT `FK_723705D147E13D68` FOREIGN KEY (`disponent_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_723705D16BA9314` FOREIGN KEY (`account_to_id`) REFERENCES `account` (`id`),
  CONSTRAINT `FK_723705D1B1E5CD43` FOREIGN KEY (`account_from_id`) REFERENCES `account` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `transaction` (`id`, `disponent_id`, `account_from_id`, `account_to_id`, `amount`, `createdAt`) VALUES
(1,	2,	4,	6,	10,	'2016-06-23 21:43:35'),
(2,	2,	4,	3,	10,	'2016-06-23 21:49:38'),
(3,	2,	4,	6,	10,	'2016-06-23 21:58:25'),
(4,	2,	4,	6,	1,	'2016-06-23 22:00:17'),
(5,	2,	4,	3,	20,	'2016-06-23 22:11:21'),
(6,	2,	4,	3,	1,	'2016-06-23 22:16:31'),
(7,	2,	6,	4,	10,	'2016-06-23 23:00:21'),
(8,	4,	4,	3,	100,	'2016-06-23 23:11:15');

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `role_id` int(11) NOT NULL,
  `name` varchar(75) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649F85E0677` (`username`),
  UNIQUE KEY `UNIQ_8D93D649444F97DD` (`phone`),
  KEY `IDX_8D93D649D60322AC` (`role_id`),
  CONSTRAINT `FK_8D93D649D60322AC` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `user` (`id`, `username`, `password`, `phone`, `address`, `role_id`, `name`) VALUES
(1,	'admin',	'$2y$13$g6MpnnnIZ8U.wsRbQRDT0.CB9cIV9u3zmvTPymV.TdZE2zm7PQGzu',	'123456',	'Address 1',	1,	'Fero Admin'),
(2,	'owner_disponent',	'$2y$13$5DN0yWZuMS8jqhZJumDnLekcJeQ76YhvEcBKc8HnA14/2z4LmjFRq',	'test',	'test',	2,	'Owner disponent'),
(4,	'owner_nedisponent',	'$2y$13$pCqkJT8rYhh0z.79PoOE9eN4PoL31/1ed7jIdYoyoA2zykTlXpIE.',	'1234',	'Pekna krasna 2',	2,	'Owner Nedisponent'),
(5,	'owner',	'$2y$13$fQ2.dO/p92.P3f2.amsaZuy5SQKG/F93ok4klidEYkS0t7DJCgRby',	'080',	'Ownerova 4',	2,	'Owner'),
(6,	'disponent',	'',	'768768',	'Oranzova 56',	2,	'Disponent');

-- 2016-06-23 23:42:58
