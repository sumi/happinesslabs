<?php
include_once "fbmain.php";
include('include/app-common-config.php');

$query=array();
$query[]="CREATE TABLE IF NOT EXISTS `tbl_app_photo_title_size` (
  `photo_title_id` int(11) NOT NULL AUTO_INCREMENT,
  `photo_id` int(11) NOT NULL,
  `top` float(10,2) NOT NULL,
  `left` float(10,2) NOT NULL,
  `font_type` varchar(100) NOT NULL,
  `font_color` varchar(100) NOT NULL,
  `font_size` varchar(100) NOT NULL,
  `record_date` date NOT NULL,
  PRIMARY KEY (`photo_title_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4";

foreach($query as $value){
	$h=mysql_query($value) or die('Error<Br/>'.$value);
	if($h){
		echo "<br>==>".substr($value,0,50)."...";
	}
}
/*ALTER TABLE `tbl_app_happybank_points` CHANGE `user_id` `user_id` VARCHAR( 100 ) NOT NULL DEFAULT '0',
CHANGE `from_user_id` `from_user_id` VARCHAR( 100 ) NOT NULL DEFAULT '0'*/
?>
