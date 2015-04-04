ALTER TABLE `#__code_tracker_issue_tag_map` ADD `jc_issue_id` INT(10) UNSIGNED DEFAULT NULL;
UPDATE `#__code_tracker_issue_tag_map` AS a LEFT JOIN `#__code_tracker_issues` AS b ON b.issue_id = a.issue_id SET a.jc_issue_id = b.jc_issue_id;

ALTER TABLE `#__code_trackers` CHANGE `tracker_id` `tracker_id` int(10) unsigned NOT NULL;
UPDATE `#__code_trackers` SET `tracker_id` = `jc_tracker_id`;
ALTER TABLE `#__code_trackers` CHANGE `tracker_id` `tracker_id` int(10) unsigned NOT NULL AUTO_INCREMENT;
ALTER TABLE `#__code_trackers` DROP `jc_tracker_id`;

UPDATE `#__code_tracker_issues` SET `jc_close_by` = 0 WHERE `jc_close_by` IS NULL;

CREATE TABLE IF NOT EXISTS `#__code_tracker_issues_temp` (
  `issue_id` int(10) unsigned NOT NULL,
  `tracker_id` int(10) unsigned NOT NULL,
  `state` int(11) NOT NULL,
  `status` int(10) unsigned NOT NULL,
  `status_name` varchar(255) NOT NULL,
  `priority` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `close_date` datetime NOT NULL,
  `close_by` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` mediumtext,
  `jc_issue_id` int(10) unsigned NOT NULL,
  `jc_created_by` int(11) NOT NULL,
  `jc_modified_by` int(11) NOT NULL,
  `jc_close_by` int(11) NOT NULL,
  PRIMARY KEY (`issue_id`),
  UNIQUE KEY `idx_tracker_issues_legacy` (`jc_issue_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO `#__code_tracker_issues_temp` (`issue_id`, `tracker_id`, `state`, `status`, `status_name`, `priority`, `created_date`, `created_by`, `modified_date`, `modified_by`, `close_date`, `close_by`, `title`, `description`, `jc_issue_id`, `jc_created_by`, `jc_modified_by`, `jc_close_by`)
  SELECT `jc_issue_id`, `tracker_id`, `state`, `status`, `status_name`, `priority`, `created_date`, `jc_created_by`, `modified_date`, `jc_modified_by`, `close_date`, `jc_close_by`, `title`, `description`, `jc_issue_id`, `jc_created_by`, `jc_modified_by`, `jc_close_by` FROM `#__code_tracker_issues`;

DROP TABLE `#__code_tracker_issues`;
RENAME TABLE `#__code_tracker_issues_temp` TO `#__code_tracker_issues`;
ALTER TABLE `#__code_tracker_issues` CHANGE `issue_id` `issue_id` int(10) unsigned NOT NULL AUTO_INCREMENT;

UPDATE `#__code_tracker_issue_assignments` SET `issue_id`=`jc_issue_id`, `user_id`=`jc_user_id`;

CREATE TABLE IF NOT EXISTS `#__code_tracker_issue_changes_temp` (
  `change_id` int(10) unsigned NOT NULL,
  `issue_id` int(10) unsigned NOT NULL,
  `tracker_id` int(10) unsigned NOT NULL,
  `change_date` datetime NOT NULL,
  `change_by` int(11) NOT NULL,
  `data` text NOT NULL,
  `jc_change_id` int(10) DEFAULT NULL,
  `jc_issue_id` int(10) DEFAULT NULL,
  `jc_change_by` int(10) DEFAULT NULL,
  PRIMARY KEY (`change_id`),
  UNIQUE KEY `jc_change_id` (`jc_change_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO `#__code_tracker_issue_changes_temp` (`change_id`, `issue_id`, `tracker_id`, `change_date`, `change_by`, `data`, `jc_change_id`, `jc_issue_id`, `jc_change_by`)
  SELECT `jc_change_id`, `jc_issue_id`, `tracker_id`, `change_date`, `jc_change_by`, `data`, `jc_change_id`, `jc_issue_id`, `jc_change_by` FROM `#__code_tracker_issue_changes`;

DROP TABLE `#__code_tracker_issue_changes`;
RENAME TABLE `#__code_tracker_issue_changes_temp` TO `#__code_tracker_issue_changes`;
ALTER TABLE `#__code_tracker_issue_changes` CHANGE `change_id` `change_id` int(10) unsigned NOT NULL AUTO_INCREMENT;

CREATE TABLE IF NOT EXISTS `#__code_tracker_issue_commits_temp` (
  `commit_id` int(10) unsigned NOT NULL,
  `issue_id` int(10) unsigned NOT NULL,
  `tracker_id` int(10) unsigned NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `message` text NOT NULL,
  `jc_commit_id` int(10) DEFAULT NULL,
  `jc_issue_id` int(10) DEFAULT NULL,
  `jc_created_by` int(10) DEFAULT NULL,
  PRIMARY KEY (`commit_id`),
  UNIQUE KEY `jc_commit_id` (`jc_commit_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO `#__code_tracker_issue_commits_temp` (`commit_id`, `issue_id`, `tracker_id`, `created_date`, `created_by`, `message`, `jc_commit_id`, `jc_issue_id`, `jc_created_by`)
  SELECT `jc_commit_id`, `jc_issue_id`, `tracker_id`, `created_date`, `jc_created_by`, `message`, `jc_commit_id`, `jc_issue_id`, `jc_created_by` FROM `#__code_tracker_issue_commits`;

DROP TABLE `#__code_tracker_issue_commits`;
RENAME TABLE `#__code_tracker_issue_commits_temp` TO `#__code_tracker_issue_commits`;
ALTER TABLE `#__code_tracker_issue_commits` CHANGE `commit_id` `commit_id` int(10) unsigned NOT NULL AUTO_INCREMENT;

CREATE TABLE IF NOT EXISTS `#__code_tracker_issue_responses_temp` (
  `response_id` int(10) unsigned NOT NULL,
  `issue_id` int(10) unsigned NOT NULL,
  `tracker_id` int(10) unsigned NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `body` text NOT NULL,
  `jc_response_id` int(10) DEFAULT NULL,
  `jc_issue_id` int(10) DEFAULT NULL,
  `jc_created_by` int(10) DEFAULT NULL,
  PRIMARY KEY (`response_id`),
  UNIQUE KEY `idx_tracker_responses_legacy` (`jc_response_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO `#__code_tracker_issue_responses_temp` (`response_id`, `issue_id`, `tracker_id`, `created_date`, `created_by`, `body`, `jc_response_id`, `jc_issue_id`, `jc_created_by`)
  SELECT `response_id`, `jc_issue_id`, `tracker_id`, `created_date`, `jc_created_by`, `body`, `jc_response_id`, `jc_issue_id`, `jc_created_by` FROM `#__code_tracker_issue_responses`;

DROP TABLE `#__code_tracker_issue_responses`;
RENAME TABLE `#__code_tracker_issue_responses_temp` TO `#__code_tracker_issue_responses`;
ALTER TABLE `#__code_tracker_issue_responses` CHANGE `response_id` `response_id` int(10) unsigned NOT NULL AUTO_INCREMENT;

CREATE TABLE IF NOT EXISTS `#__code_tracker_issue_tag_map_temp` (
  `issue_id` int(10) unsigned DEFAULT NULL,
  `tag_id` int(10) unsigned DEFAULT NULL,
  `tag` varchar(255) DEFAULT NULL,
  KEY `issue_id` (`issue_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `#__code_tracker_issue_tag_map_temp` (`issue_id`, `tag_id`, `tag`)
  SELECT `jc_issue_id`, `tag_id`, `tag` FROM `#__code_tracker_issue_tag_map`;

DROP TABLE `#__code_tracker_issue_tag_map`;
RENAME TABLE `#__code_tracker_issue_tag_map_temp` TO  `#__code_tracker_issue_tag_map`;

CREATE TABLE IF NOT EXISTS `#__code_tracker_status_temp` (
  `status_id` int(10) unsigned NOT NULL,
  `tracker_id` int(10) unsigned NOT NULL,
  `state_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `jc_status_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`status_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO `#__code_tracker_status_temp`(`status_id`, `tracker_id`, `state_id`, `title`, `jc_status_id`)
  SELECT `jc_status_id`, `tracker_id`, `state_id`, `title`, `jc_status_id` FROM `#__code_tracker_status`;

DROP TABLE `#__code_tracker_status`;
RENAME TABLE `#__code_tracker_status_temp` TO `#__code_tracker_status` ;
ALTER TABLE `#__code_tracker_status` CHANGE `status_id` `status_id` int(10) unsigned NOT NULL AUTO_INCREMENT;

CREATE TABLE IF NOT EXISTS `#__code_users_temp` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `address` varchar(512) NOT NULL,
  `address2` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `region` varchar(255) NOT NULL,
  `country` varchar(5) NOT NULL,
  `postal_code` varchar(25) NOT NULL,
  `longitude` float NOT NULL,
  `latitude` float NOT NULL,
  `phone` varchar(255) NOT NULL,
  `agreed_tos` int(1) unsigned NOT NULL,
  `jca_document_id` varchar(255) NOT NULL,
  `signed_jca` int(1) unsigned NOT NULL,
  `jc_user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `idx_legacy_user_id` (`jc_user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO `#__code_users_temp`(`user_id`, `first_name`, `last_name`, `address`, `address2`, `city`, `region`, `country`, `postal_code`, `longitude`, `latitude`, `phone`, `agreed_tos`, `jca_document_id`, `signed_jca`, `jc_user_id`)
  SELECT `jc_user_id`, `first_name`, `last_name`, `address`, `address2`, `city`, `region`, `country`, `postal_code`, `longitude`, `latitude`, `phone`, `agreed_tos`, `jca_document_id`, `signed_jca`, `jc_user_id` FROM `#__code_users`;

DROP TABLE `#__code_users`;
RENAME TABLE `#__code_users_temp` TO `#__code_users`;
ALTER TABLE `#__code_users` CHANGE `user_id` `user_id` int(11) NOT NULL AUTO_INCREMENT;
