<?php
include_once "fbmain.php";
include('include/app-common-config.php');

$query=array();
$query[]="ALTER TABLE `tbl_app_life_story_book_template` ADD `cherryboard_id` INT( 11 ) NOT NULL AFTER `pillar_no`";
$query[]="UPDATE `cherryfull`.`tbl_app_life_story_book_template` SET `cherryboard_id` = '819' WHERE `tbl_app_life_story_book_template`.`template_id`=1;";

foreach($query as $value){
	$h=mysql_query($value) or die('Error<Br/>'.$value);
	if($h){
		echo "<br>==>".substr($value,0,50)."...";
	}
}
?>
