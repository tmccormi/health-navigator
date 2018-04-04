CREATE TABLE `form_gtc_referral` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `date` datetime DEFAULT NULL,
  `pid` bigint(20) DEFAULT NULL,
  `groupname` varchar(255) DEFAULT NULL,
  `activity` tinyint(4) DEFAULT NULL,
  `authorized` tinyint(4) DEFAULT NULL,
  `user` varchar(255) DEFAULT NULL,
  
  `provider_id` bigint(20) DEFAULT NULL,
  `use_pledge_slot` tinyint(4) DEFAULT 0,
  `note` longtext,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
