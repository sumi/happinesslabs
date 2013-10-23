<?php
include_once "fbmain.php";
include('include/app-common-config.php');

$query=array();
$query[]="CREATE TABLE IF NOT EXISTS `tbl_app_user_happy_mission` (
  `user_mission_id` int(11) NOT NULL AUTO_INCREMENT,
  `pillar_no` int(11) NOT NULL,
  `happy_mission_id` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `record_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_mission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";
/*$query[]="CREATE TABLE IF NOT EXISTS `tbl_app_happy_mission` (
  `happy_mission_id` int(11) NOT NULL AUTO_INCREMENT,
  `pillar_no` int(11) NOT NULL,
  `happy_mission_title` varchar(255) NOT NULL,
  PRIMARY KEY (`happy_mission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";*/

foreach($query as $value){
	$h=mysql_query($value) or die('Error<Br/>'.$value);
	if($h){
		echo "<br>==>".substr($value,0,50)."...";
	}
}
?>
