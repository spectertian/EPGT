/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE */;
/*!40101 SET SQL_MODE='' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES */;
/*!40103 SET SQL_NOTES='ON' */;

CREATE TABLE `theme_item` (
  `id` bigint(20) NOT NULL auto_increment,
  `theme_id` bigint(20) NOT NULL default '0',
  `wiki_id` varchar(32) NOT NULL default '0',
  `remark` varchar(250) NOT NULL COMMENT '推荐理由',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `img` varchar(255) default NULL,
  PRIMARY KEY  (`id`),
  KEY `theme_id` (`theme_id`,`wiki_id`)
) ENGINE=MyISAM AUTO_INCREMENT=81 DEFAULT CHARSET=utf8;

/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
