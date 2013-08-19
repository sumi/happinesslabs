<?php 
//include_once "fbmain.php";
//include('app-common-config.php');
$sel=mysql_query("select cherryboard_id,fb_post_id from tbl_app_cherryboard order by cherryboard_id");
while($selRow=mysql_fetch_array($sel)){
	$cherryboard_id=$selRow['cherryboard_id'];
	$fb_post_id=trim($selRow['fb_post_id']);
	if($fb_post_id!=""&&$fb_post_id!=0){
		$checkDate=(int)getFieldValue('like_id','tbl_app_cherryboard_likes','like_date="'.date('Y-m-d').'" and cherryboard_id='.$cherryboard_id);
		if($checkDate==0){
			$msgData = $facebook->api("/".$fb_post_id, 'GET');
			$total_likes=(int)$msgData['likes']['count'];
			if($total_likes>0){
				$insQuery=mysql_query("INSERT INTO `tbl_app_cherryboard_likes` (`like_id`, `cherryboard_id`, `total_likes`, `like_date`) VALUES (NULL, '".$cherryboard_id."', '".$total_likes."', '".date('Y-m-d')."')") or mysql_error();
			}
		
		}
	}	
}
?>