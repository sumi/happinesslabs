<?php
//accept user request from fb notification 
$FRequestId=$_REQUEST['frid'];
if($FRequestId!=""){
	$updStatus=mysql_query("update tbl_app_expert_cherryboard_meb set is_accept='1' where request_ids=".$FRequestId);
}
?>