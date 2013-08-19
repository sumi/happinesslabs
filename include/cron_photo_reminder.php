<?php 
//include('app-common-config.php');
$sel=mysql_query("select * from tbl_app_cherryboard order by cherryboard_id");
while($selRow=mysql_fetch_array($sel)){
	$cherryboard_id=$selRow['cherryboard_id'];
	$user_id=$selRow['user_id'];
	//reminder for the photo upload
	$photo_date=getFieldValue('photo_date','tbl_app_cherry_photo_status','cherryboard_id='.$cherryboard_id);
	if($photo_date==""){
		$insStatus="INSERT INTO `tbl_app_cherry_photo_status` (`status_id`, `user_id`, `cherryboard_id`, `photo_date`, `record_date`) VALUES (NULL, '".$user_id."', '".$cherryboard_id."', '".date('Y-m-d')."', CURRENT_TIMESTAMP)";
		$insStatusSql=mysql_query($insStatus);
	}
	
	$PhotoDay=getGoalboardToday($cherryboard_id);
	if($PhotoDay>2){	
		if($photo_date!=date('Y-m-d')){
			$userArray=getFieldsValueArray('email_id,first_name','tbl_app_users','user_id='.$user_id);
			$email_id=$userArray[0];
			$first_name=$userArray[1];
			
			$cherryboard_title=getFieldValue('cherryboard_title','tbl_app_cherryboard','cherryboard_id='.$cherryboard_id);
	
			$GiftName=getFieldValue('b.gift_title','tbl_app_cherry_gift a,tbl_app_gift b','a.gift_id=b.gift_id and a.cherryboard_id='.$cherryboard_id);
			
			
			
			if($email_id!=""){
				//mail to user
				$to      = $email_id;
				$subject = 'Reminder : Upload day '.$PhotoDay.' picture to win '.$GiftName;
				$message = '<table>
							<tr><td>Dear '.$first_name.',</td></tr>
							<tr><td>&nbsp;</td></tr>
							<tr><td>Congratulations, for sticking to your '.$cherryboard_title.' for '.$PhotoDay.' days. This is a reminder to update todays picture to keep you in a running for winning the gift '.$GiftName.'</strong>.</td></tr>
							<tr><td>&nbsp;</td></tr>
							<tr><td>Please <a href="http://www.30daysnew.com/cherryboard.php?cbid='.$cherryboard_id.'">Click Here</a> to upload today photo.</strong>.</td></tr>
							<tr><td>&nbsp;</td></tr>
							<tr><td>Love,</td></tr>
							<tr><td>30daysNEW Team</td></tr>
							</table>';
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headers .= 'From: info@30daysnew.com' . "\r\n" .
					'Reply-To: info@30daysnew.com' . "\r\n" .
					'X-Mailer: PHP/' . phpversion();
					//echo $to."========".$subject."========".$message."========".$headers;
				$sentMail=mail($to, $subject, $message, $headers);
				if($sentMail){
					$updateSel="update tbl_app_cherry_photo_status set photo_date='".date('Y-m-d')."' where cherryboard_id=".$cherryboard_id;
					$updateSql=mysql_query($updateSel);
				}
			 }
		}
	}	
}
?>