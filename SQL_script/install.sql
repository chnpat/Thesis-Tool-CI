CREATE DATABASE IF NOT EXISTS `thesis_tool` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `thesis_tool`;

CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `data` blob NOT NULL,
  `user_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `user_login` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_created_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_status` tinyint(1) NOT NULL DEFAULT '0',
  `user_role` varchar(255) NOT NULL DEFAULT 'Regular',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

INSERT INTO `user_login` (`id`, `user_name`, `user_email`, `user_password`, `user_created_date`, `user_status`, `user_role`) VALUES
(1, 'admin', 'admin@qam.com', 'd033e22ae348aeb5660fc2140aec35850c4da997', '2017-01-01 00:00:00', 1, 'Admin');

CREATE TABLE IF NOT EXISTS `pattern` ( 
  `id` INT(11) NOT NULL AUTO_INCREMENT , 
  `pattern_id` VARCHAR(255) NOT NULL , 
  `pattern_name` VARCHAR(255) NOT NULL , 
  `pattern_creator_id` INT(11) NOT NULL ,
  `pattern_assess_version` decimal(10,1) NOT NULL,
  `pattern_assess_limit` INT(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `pattern_description` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pattern_id` varchar(255) NOT NULL,
  `desc_version` decimal(10,1) NOT NULL,
  `desc_is_assessed` tinyint(1) NOT NULL,
  `desc_classification` varchar(255) NOT NULL,
  `desc_aka` varchar(255) NOT NULL,
  `desc_intent` text NOT NULL,
  `desc_motivation` text NOT NULL,
  `desc_applicability` text NOT NULL,
  `desc_structure` text NOT NULL,
  `desc_participants` text NOT NULL,
  `desc_collaborations` text NOT NULL,
  `desc_implementation` text NOT NULL,
  `desc_consequences` text NOT NULL,
  `desc_known_uses` text NOT NULL,
  `desc_sample_code` text NOT NULL,
  `desc_related_pattern` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;