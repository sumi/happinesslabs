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

08-30-2013
CREATE TABLE IF NOT EXISTS `tbl_app_expertboard_likes` (
  `like_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `expertboard_id` int(11) NOT NULL,
  `is_like` enum('1','0') NOT NULL DEFAULT '0',
  `record_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`like_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

08-31-2013
ALTER TABLE `tbl_app_expertboard_likes` CHANGE `expertboard_id` `cherryboard_id` INT( 11 ) NOT NULL 

09-02-2013
ALTER TABLE `tbl_app_expertboard_likes` CHANGE `is_like` `is_like` ENUM( '1', '2', '0' ) NOT NULL DEFAULT '0'

09-17-2013
CREATE TABLE IF NOT EXISTS `tbl_app_expert_tag_photo` (
  `tag_id` int(11) NOT NULL AUTO_INCREMENT,
  `cherryboard_id` int(11) NOT NULL,
  `photo_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `tag_title` text NOT NULL,
  `tag_x` int(11) NOT NULL,
  `tag_y` int(11) NOT NULL,
  `record_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

09-21-2013
ALTER TABLE `tbl_app_expert_tag_photo` ADD `tag_photo` VARCHAR( 255 ) NOT NULL AFTER `tag_title`

09-27-2013
ALTER TABLE tbl_app_expert_tag_photo RENAME tbl_app_tag_photo; 
ALTER TABLE `tbl_app_tag_photo` ADD `tag_type` INT( 11 ) NOT NULL AFTER `user_id` 
CREATE TABLE IF NOT EXISTS `tbl_app_tag_type` (
  `tag_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `tag_type_name` varchar(255) NOT NULL,
  `record_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`tag_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

09-30-2013
Date,Time,Why,How,Happiness Score Type Add On tbl_app_tag_type Table

10-02-2013
ALTER TABLE `tbl_app_life_story_book_template` ADD `cherryboard_id` INT( 11 ) NOT NULL AFTER `pillar_no` 
UPDATE `cherryfull`.`tbl_app_life_story_book_template` SET `cherryboard_id` = '819' WHERE `tbl_app_life_story_book_template`.`template_id`=1;

10-09-2013
ALTER TABLE `tbl_app_happiness_pillar` ADD `category_id` INT( 11 ) NOT NULL AFTER `parent_id` 

CREATE TABLE IF NOT EXISTS `tbl_app_happy_mission` (
  `happy_mission_id` int(11) NOT NULL AUTO_INCREMENT,
  `pillar_no` int(11) NOT NULL,
  `happy_mission_title` varchar(255) NOT NULL,
  PRIMARY KEY (`happy_mission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

INSERT INTO `tbl_app_happy_mission` (`happy_mission_id`, `pillar_no`, `happy_mission_title`) VALUES
(1, 1, 'Happy Family Mission'),
(2, 1, 'Happy Husband Mission'),
(3, 1, 'Happy Wife Mission'),
(4, 1, 'Happy Kids Mission'),
(5, 1, 'Happy Father Mission'),
(6, 1, 'Happy Mother Mission'),
(7, 1, 'Happy Brother Mission'),
(8, 1, 'Happy Sister Mission'),
(9, 1, 'Happy Friend Mission'),
(10, 1, 'Happy Grand Mother Mission'),
(11, 1, 'Happy Grand Father Mission'),
(12, 2, 'Happy Boss Mission'),
(13, 2, 'Happy Peer Mission'),
(14, 2, 'Happy Subordinate Mission'),
(15, 2, 'Happy Team Mission'),
(16, 2, 'Happy Company Mission'),
(17, 2, 'Happy Business Mission'),
(18, 2, 'Happy Salary Mission'),
(19, 2, 'Happy Accomplishments Mission'),
(20, 2, 'Happy Goals Mission'),
(21, 3, 'Happy Investment Mission'),
(22, 3, 'Happy Car Mission'),
(23, 3, 'Happy Home Mission'),
(24, 3, 'Happy Savings Mission'),
(25, 3, 'Happy Income Mission'),
(26, 3, 'Happy Kids Fund Mission'),
(27, 4, 'Happy Breakfast Mission'),
(28, 4, 'Happy Lunch Mission'),
(29, 4, 'Happy Dinner Mission'),
(30, 4, 'Happy Snacks Mission'),
(31, 4, 'Happy Workout Mission'),
(32, 4, 'Happy Yoga Mission'),
(33, 4, 'Happy Dance Mission'),
(34, 4, 'Happy Walking Mission'),
(35, 4, 'Happy Running Mission'),
(36, 4, 'Happy Cardio Mission'),
(37, 5, 'Happy Travel Mission'),
(38, 5, 'Happy Music Mission'),
(39, 5, 'Happy Sports Mission'),
(40, 5, 'Happy Books Mission'),
(41, 5, 'Happy Movies Mission'),
(42, 5, 'Happy TV Shows Mission'),
(43, 5, 'Happy Coocking Mission'),
(44, 5, 'Happy Singing Mission'),
(45, 5, 'Happy Golf Mission'),
(46, 5, 'Happy Tennis Mission'),
(47, 5, 'Happy Shopping Mission'),
(48, 5, 'Happy Baking Mission'),
(49, 5, 'Happy Sewing Mission'),
(50, 5, 'Happy Writing Mission'),
(51, 5, 'Happy Painting Mission'),
(52, 6, 'Happy Hair Mission'),
(53, 6, 'Happy Skin Mission'),
(54, 6, 'Happy Eyes Mission'),
(55, 6, 'Happy Teeth Mission'),
(56, 6, 'Happy Neck Mission'),
(57, 6, 'Happy Arms Mission'),
(58, 6, 'Happy Hands Mission'),
(59, 6, 'Happy Abbs Mission'),
(60, 6, 'Happy Butt Mission'),
(61, 6, 'Happy Thighs Mission'),
(62, 6, 'Happy Legs Mission'),
(63, 6, 'Happy Feet Mission'),
(64, 6, 'Happy Style Mission'),
(65, 6, 'Happy Shoes Mission'),
(66, 6, 'Happy Tops Mission'),
(67, 6, 'Happy Dress Mission'),
(68, 6, 'Happy Pants Mission'),
(69, 6, 'Happy Hair Style Mission'),
(70, 7, 'Happy City School Mission'),
(71, 7, 'Happy School Mission'),
(72, 7, 'Happy Library Mission'),
(73, 7, 'Happy Park Mission'),
(74, 7, 'Happy Church Mission'),
(75, 7, 'Happy Earth Mission'),
(76, 7, 'Happy Sea Mission'),
(77, 7, 'Happy Pets Mission'),
(78, 7, 'Happy Dogs Mission'),
(79, 7, 'Happy Cats Mission'),
(80, 7, 'Happy Rivers Mission'),
(81, 7, 'Happy Forest Mission'),
(82, 7, 'Happy Gardens Mission');

10/10/2013
ALTER TABLE `tbl_app_expertboard` ADD `happy_mission_id` INT( 11 ) NOT NULL

10/16/2013
ALTER TABLE  `tbl_app_expertboard_days` ADD  `cherryboard_id` INT( 11 ) NOT NULL AFTER  `expertboard_id`

ALTER TABLE `tbl_app_expert_checklist` ADD `day_no` INT( 11 ) NOT NULL ,
ADD `sub_day` INT( 11 ) NOT NULL DEFAULT '1'