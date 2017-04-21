CREATE DATABASE IF NOT EXISTS `thesis_tool` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `thesis_tool`;

CREATE TABLE `ci_sessions` (
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
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

INSERT INTO `user_login` (`id`, `user_name`, `user_email`, `user_password`, `user_created_date`, `user_status`, `user_role`) VALUES
(1, 'admin', 'admin@qam.com', 'd033e22ae348aeb5660fc2140aec35850c4da997', '2017-01-01 00:00:00', 1, 'Admin');