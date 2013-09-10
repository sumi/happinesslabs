<?php
include_once "fbmain.php";
include('include/app-common-config.php');

$query=array();
$query[]="ALTER TABLE `tbl_app_happybank_points` CHANGE `user_id` `user_id` VARCHAR( 100 ) NOT NULL DEFAULT '0',
CHANGE `from_user_id` `from_user_id` VARCHAR( 100 ) NOT NULL DEFAULT '0'";
$query[]="INSERT INTO `tbl_app_happybank` (`happybank_id`, `happybank_type`, `point`, `record_date`) VALUES (NULL, 'Invite Friends', '10', '2013-09-09')";

foreach($query as $value){
	$h=mysql_query($value) or die('Error<Br/>'.$value);
	if($h){
		echo "<br>==>".substr($value,0,50)."...";
	}
}
?>
