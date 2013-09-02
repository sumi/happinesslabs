<?php
include_once "fbmain.php";
include('include/app-common-config.php');

$query=array();
$query[]="ALTER TABLE `tbl_app_expertboard_likes` CHANGE `is_like` `is_like` ENUM( '1', '2', '0' ) NOT NULL DEFAULT '0'";
//$query[]="ALTER TABLE `tbl_app_expert_cherry_photo` ADD `sub_day` INT( 11 ) NOT NULL DEFAULT '0'";
foreach($query as $value){
	$h=mysql_query($value) or die('Error<Br/>'.$value);
	if($h){
		echo "<br>==>".substr($value,0,50)."...";
	}
}
?>
