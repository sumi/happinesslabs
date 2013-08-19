<?php
include_once "fbmain.php";	
include('include/app-common-config.php');
$cnt=1;
while($cnt<31){	
	echo '<br>call'.$cnt;
	$day_title='day'.$cnt;
    echo "<br>".$updt="INSERT INTO `cherryfull`.`tbl_app_campaign_days` (
`campaign_day_id` ,`campaign_id` ,`day_no`,`day_title`,`record_date`)
VALUES (NULL , '55', '".$cnt."', '".$day_title."',CURRENT_TIMESTAMP)";
   $ins=mysql_query($updt);
	$cnt++;
}
?>