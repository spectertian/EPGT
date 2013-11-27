/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE */;
/*!40101 SET SQL_MODE='' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES */;
/*!40103 SET SQL_NOTES='ON' */;

CREATE TABLE `channel_recommend` (
  `id` bigint(20) NOT NULL auto_increment,
  `channel_code` varchar(32) NOT NULL,
  `wiki_id` varchar(32) NOT NULL,
  `title` varchar(128) NOT NULL,
  `pic` varchar(128) NOT NULL,
  `playtime` varchar(32) NOT NULL,
  `remark` varchar(255) NOT NULL,
  `publish` tinyint(4) NOT NULL default '0',
  `sort` int(11) NOT NULL default '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `channel_code_idx` (`channel_code`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
