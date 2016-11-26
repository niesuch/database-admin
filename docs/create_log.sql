CREATE DATABASE IF NOT EXISTS `logs`;
USE `logs`;

CREATE TABLE IF NOT EXISTS `log_query` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `base_name` varchar(255) COLLATE utf8_polish_ci NOT NULL,
  `query` text COLLATE utf8_polish_ci NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

CREATE TABLE `log_error` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`query` text COLLATE utf8_polish_ci NOT NULL,
	`error` text COLLATE utf8_polish_ci NOT NULL,
	`date` datetime NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

CREATE TABLE IF NOT EXISTS `log_history_query` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `base_name` varchar(255) COLLATE utf8_polish_ci NOT NULL,
  `query` text COLLATE utf8_polish_ci NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;
