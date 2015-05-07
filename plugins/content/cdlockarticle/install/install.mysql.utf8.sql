CREATE TABLE IF NOT EXISTS `#__cdlockarticle` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `sourceid` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `context` varchar(255) NOT NULL DEFAULT '',
  `password` varchar(255) NOT NULL DEFAULT '',
  `headertext` varchar(255) NOT NULL DEFAULT '',
  `lockedby` int(11) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;