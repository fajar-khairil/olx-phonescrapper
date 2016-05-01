-- Adminer 4.2.0 MySQL dump

SET NAMES utf8mb4;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `olx_cities`;
CREATE TABLE `olx_cities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `src_logs`;
CREATE TABLE `src_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `source` char(16) NOT NULL,
  `city` varchar(255) DEFAULT NULL,
  `keyword` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `limit` tinyint(4) NOT NULL,
  `records` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '0 = PENDING | 1 = PROCCESSING | 2 = DONE | 3 = FAILED',
  `failure_reason` text,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `source` (`source`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `src_olx_categories`;
CREATE TABLE `src_olx_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `lft` int(11) DEFAULT NULL,
  `rgt` int(11) DEFAULT NULL,
  `depth` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `src_providers`;
CREATE TABLE `src_providers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `source` varchar(25) NOT NULL COMMENT 'source (olx|jualo)',
  `url` varchar(255) NOT NULL COMMENT 'url of ad',
  `phone` varchar(255) DEFAULT NULL COMMENT 'phone number',
  `email` varchar(255) DEFAULT NULL COMMENT 'email if available(showed)',
  `city` varchar(255) DEFAULT NULL COMMENT 'city of ad',
  `content` text COMMENT 'ad content',
  `verified` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `url` (`url`),
  KEY `source` (`source`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- 2016-05-01 12:57:05
