-- SQL Dump for the "testman" database

CREATE TABLE `permitted_users` (
  `user_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY  (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

CREATE TABLE `winetest_logs` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `log` longtext collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

CREATE TABLE `winetest_results` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `test_id` int(10) unsigned NOT NULL,
  `suite_id` int(10) unsigned NOT NULL,
  `status` enum('ok','crash','canceled') collate latin1_general_ci NOT NULL,
  `count` int(10) NOT NULL COMMENT 'Number of all executed tests',
  `failures` int(10) unsigned NOT NULL COMMENT 'Number of failed tests',
  `skipped` int(10) unsigned NOT NULL COMMENT 'Number of skipped tests',
  PRIMARY KEY  (`id`),
  KEY `suite_id` (`suite_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

CREATE TABLE `winetest_runs` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `finished` tinyint(1) NOT NULL default '0',
  `user_id` bigint(20) unsigned NOT NULL,
  `revision` int(9) unsigned NOT NULL,
  `platform` varchar(24) collate latin1_general_ci NOT NULL,
  `comment` varchar(255) collate latin1_general_ci default NULL,
  `count` int(10) unsigned NOT NULL COMMENT 'Sum of all executed tests',
  `failures` int(10) unsigned NOT NULL COMMENT 'Sum of all test failures',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

CREATE TABLE `winetest_suites` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `module` varchar(50) collate latin1_general_ci NOT NULL,
  `test` varchar(50) collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
