/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE */;
/*!40101 SET SQL_MODE='' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES */;
/*!40103 SET SQL_NOTES='ON' */;

CREATE TABLE `admin` (
  `id` bigint(20) NOT NULL auto_increment,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `name` varchar(30) default NULL,
  `phone` varchar(20) default NULL,
  `status` tinyint(1) NOT NULL default '0',
  `email` varchar(255) default NULL,
  `last_login_ip` varchar(20) NOT NULL,
  `last_login_at` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `admin` VALUES (1,'admin','71c7dac86479855501dbc5997016a5d8','超级用户','',1,'admin@126.com','192.168.80.102','2012-08-20 16:49:58','2012-03-31 11:04:27','2012-08-20 16:49:58');


/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
