<?php
if($_SERVER['SERVER_NAME']=="localhost"){
	$serverIp="localhost";
	$userName="root";
	$password="";
	$dbname="cherryfull";
 }else{
	$serverIp="localhost";
	$userName="cherry";
	$password="cherry";
	$dbname="cherryfull";
}

$cn=mysql_connect($serverIp,$userName,$password) OR Die("Couldn't Connect - ".mysql_error());
$link=mysql_select_db($dbname,$cn)or Die("Couldn't SELCECT - ".mysql_error()); 
function getFieldValue($FieldName,$TableName,$WhereCondition) // table field name | table name | where condition
{
	$sel_query=mysql_query("SELECT ".$FieldName." FROM ".$TableName." WHERE ".$WhereCondition);
	$sel_row=mysql_fetch_array($sel_query) or die(mysql_error());
	return $sel_row[0];
}

function getFieldsValueArray($FieldNames,$TableName,$WhereCondition) // table field name | table name | where condition
{
	$sel_query=mysql_query("SELECT ".$FieldNames." FROM ".$TableName." WHERE ".$WhereCondition);
	$sel_row=mysql_fetch_array($sel_query)or die(mysql_error());
	return $sel_row;
}
//Update employee sick days at end of the month
$selClient=mysql_query("select user_id from tbl_app_happybank_points group by user_id");
while($selClientRow=mysql_fetch_array($selClient)){
	$user_id=$selClientRow['user_id'];
	$checkUser=(int)getFieldValue('user_id','tbl_app_users','facebook_id="'.$user_id.'"');
	if($checkUser>0){
		$userDetail=getFieldsValueArray('user_id,total_point','tbl_app_happybank_user','user_id="'.$user_id.'"');
		$point_user_id=(int)$userDetail[0];
		$total_point=(int)$userDetail[1];
		if($point_user_id>0){
			if($total_point>0){
				$updatePoint=mysql_query("update tbl_app_happybank_user set total_point=(total_point-20) where user_id=".$user_id);
			}	
		}else{
			$insPoint=mysql_query("INSERT INTO `tbl_app_happybank_user` (`happybank_user_id`, `user_id`, `total_point`, `record_date`) VALUES (NULL, '".$user_id."', '0', '".date('Y-m-d')."')");
		}
	}	
	/*
	$to = $requestEmailId;
				$subject = $SenderName.' Invited You.';
				$message = '<table>
							<tr><td>&nbsp;</td></tr>
							<tr><td>Dear '.$RequestUserName.',</td></tr>
							<tr><td>&nbsp;</td></tr>
							<tr><td>'.$SenderName.'&nbsp;invited you to the story&nbsp;"'.$expertboard_title.'"&nbsp;<a href="'.SITE_URL.'/expert_cherryboard.php?cbid='.$cherryboard_id.'"><strong>Click here</strong></a> to accept his/her invitation.</td></tr>
							<tr><td>&nbsp;</td></tr>
							<tr><td>Love,</td></tr>
							<tr><td>'.REGARDS.'</td></tr>
							</table>';
				SendMail($to,$subject,$message);
	*/
	
}
?>