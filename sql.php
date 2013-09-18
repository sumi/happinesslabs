<?php
include_once "fbmain.php";
include('include/app-common-config.php');

$query=array();
$query[]="CREATE TABLE IF NOT EXISTS `tbl_app_expert_tag_photo` (
  `tag_id` int(11) NOT NULL AUTO_INCREMENT,
  `cherryboard_id` int(11) NOT NULL,
  `photo_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `tag_title` text NOT NULL,
  `tag_x` int(11) NOT NULL,
  `tag_y` int(11) NOT NULL,
  `record_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";
//$query[]="INSERT INTO `tbl_app_happybank` (`happybank_id`, `happybank_type`, `point`, `record_date`) VALUES (NULL, 'Invite Friends', '10', '2013-09-09')";

foreach($query as $value){
	$h=mysql_query($value) or die('Error<Br/>'.$value);
	if($h){
		echo "<br>==>".substr($value,0,50)."...";
	}
}
/*ALTER TABLE `tbl_app_happybank_points` CHANGE `user_id` `user_id` VARCHAR( 100 ) NOT NULL DEFAULT '0',
CHANGE `from_user_id` `from_user_id` VARCHAR( 100 ) NOT NULL DEFAULT '0'*/
?>
