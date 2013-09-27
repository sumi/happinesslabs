<?php
include_once "fbmain.php";
include('include/app-common-config.php');

$query=array();
$query[]="CREATE TABLE IF NOT EXISTS `tbl_app_tag_type` (
  `tag_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `tag_type_name` varchar(255) NOT NULL,
  `record_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`tag_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";
$query[]="ALTER TABLE tbl_app_expert_tag_photo RENAME tbl_app_tag_photo;";
$query[]="ALTER TABLE `tbl_app_tag_photo` ADD `tag_type` INT( 11 ) NOT NULL AFTER `user_id` ";

foreach($query as $value){
	$h=mysql_query($value) or die('Error<Br/>'.$value);
	if($h){
		echo "<br>==>".substr($value,0,50)."...";
	}
}
/*ALTER TABLE `tbl_app_happybank_points` CHANGE `user_id` `user_id` VARCHAR( 100 ) NOT NULL DEFAULT '0',
CHANGE `from_user_id` `from_user_id` VARCHAR( 100 ) NOT NULL DEFAULT '0'*/
?>
