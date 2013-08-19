28-05-2013
ALTER TABLE `tbl_app_expert_cherry_comment` ADD `cherry_question` TEXT NOT NULL AFTER `cherry_comment`;
ALTER TABLE `tbl_app_expert_cherry_comment` ADD `cherry_answer` TEXT NOT NULL AFTER `cherry_question`;

06-17-2013
ALTER TABLE `tbl_app_expertboard` ADD `customers` VARCHAR( 255 ) NOT NULL DEFAULT 'Customers' AFTER `price` 

06-27-2013
ALTER TABLE `tbl_app_expertboard` ADD `profile_picture` TEXT NOT NULL ,
ADD `picture_title` VARCHAR( 255 ) NOT NULL

07-01-2013
ALTER TABLE `tbl_app_expertboard` ADD `ip_address` VARCHAR( 255 ) NOT NULL 

07-02-2013
CREATE TABLE IF NOT EXISTS `tbl_app_expert_notes` (
  `notes_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `cherryboard_id` int(11) NOT NULL,
  `photo_id` int(11) NOT NULL,
  `photo_day` int(11) NOT NULL,
  `cherry_notes` text NOT NULL,
  `record_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`notes_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

07-04-2013
ALTER TABLE `tbl_app_expertboard` ADD `parent_id` INT( 11 ) NOT NULL 

07-09-2013
ALTER TABLE `tbl_app_expert_checklist` ADD `is_system` ENUM( '1', '0' ) NOT NULL DEFAULT '0'

08-08-2013
ALTER TABLE `tbl_app_expertboard` ADD `copyboard_id` INT( 11 ) NOT NULL

08-08-2013 
ALTER TABLE `tbl_app_expert_cherryboard` ADD `parent_id` INT( 11 ) NOT NULL ,
ADD `copyboard_id` INT( 11 ) NOT NULL ,
ADD `doit_id` INT( 11 ) NOT NULL

08-17-2013
ALTER TABLE `tbl_app_expert_cherryboard` ADD `is_publish` ENUM( '1', '0' ) NOT NULL DEFAULT '0'