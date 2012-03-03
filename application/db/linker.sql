CREATE TABLE `linker` (
  `link_id` int(128) unsigned NOT NULL AUTO_INCREMENT,
  `link_original` text NOT NULL,
  `link_generated` text NOT NULL,
  `link_hash` varchar(128) DEFAULT NULL,
  `link_created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `link_updated_at` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP
  PRIMARY KEY (`link_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;