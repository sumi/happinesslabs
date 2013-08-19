<?php 
include('include/app-db-connect.php');
include('include/app_functions.php');
$sel=mysql_query("select * from tbl_app_cherryboard order by cherryboard_id");
while($selRow=mysql_fetch_array($sel)){
	$cherryboard_id=$selRow['cherryboard_id'];
	$user_id=$selRow['user_id'];
}	
?>