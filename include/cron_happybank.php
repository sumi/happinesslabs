<?php
error_reporting(0);
include('include/app-db-connect.php');
include('include/app_functions.php');
//Update employee sick days at end of the month
$selClient=mysql_query("select user_id from tbl_app_happybank_points group by user_id");
while($selClientRow=mysql_fetch_array($selClient)){
	$user_id=$selClientRow['user_id'];
	$checkUser=(int)getFieldValue('user_id','tbl_app_users','facebook_id="'.$user_id.'"');
	if($checkUser>0 && ($user_id=='100002349398425' || $user_id=='650516592')){
		//Deduct 20 points, item 7 from the user
		$insQry="INSERT INTO `tbl_app_happybank_points` (`point_id`, `user_id`, `from_user_id`, `cherryboard_id`, `happybank_id`, status, `record_date`,category_id) VALUES (NULL, '".$user_id."', '0', '0', '7', '1', '".date('Y-m-d')."','0')";
		$insSql=mysql_query($insQry);
		$UserDeductPoint=getHappyDeductPoint($user_id);
		if($UserPlushPoint>0){
			$UserBalance=(int)($UserPlushPoint-$UserDeductPoint);
		}else{
			$UserBalance=0;
		}	
		$UserDetail=getUserDetail($user_id,'fbid');
		$email_id=$UserDetail['email_id'];
		$UserName=$UserDetail['first_name'].' '.$UserDetail['last_name'];
		
		if($email_id!=""){
			//1. mail for the happybank report detail
			$to = $email_id;
			$subject = 'Happy Life bank account - activity report - '.date('dS F Y');
			$message = '<table>
						<tr><td>&nbsp;</td></tr>
						<tr><td>Dear '.$UserName.',</td></tr>
						<tr><td>&nbsp;</td></tr>
						<tr><td>Date :'.date('dS F Y').'</td></tr>
						<tr><td>&nbsp;</td></tr>';
						$selQuery=mysql_query("select category_id,category_name from tbl_app_category where is_happybank='1' order by sequence");
						while($selRow=mysql_fetch_array($selQuery)){
							$category_id=$selRow['category_id'];
							$UserBalance=getHappyPlushPoint($user_id,$category_id);
							$CreaetStory=getHappyPlushPoint($user_id,$category_id,1);
							$Doit=getHappyPlushPoint($user_id,$category_id,2);
							$Copy_Board=getHappyPlushPoint($user_id,$category_id,3);
							$Share_Friend=getHappyPlushPoint($user_id,$category_id,4);
							$Invite_Friends=getHappyPlushPoint($user_id,$category_id,5);
							$Happy_Experience=getHappyPlushPoint($user_id,$category_id,6);
							
							$category=ucwords($selRow['category_name']);
							$message .= '<tr><td><strong>'.$category.' Debit Card</strong></td></tr>
							<tr><td>end of previous day total : '.$UserBalance.'</td></tr>
							<tr><td>today do atleast 1 of these 6 activities to keep you love account happy</td></tr>
							<tr><td>1.Create Story '.$CreaetStory.'</td></tr>
							<tr><td>2.Do-it '.$Doit.'</td></tr>
							<tr><td>3.Copy Board '.$Copy_Board.'</td></tr>
							<tr><td>4.Share Friend '.$Share_Friend.'</td></tr>
							<tr><td>5.Invite Friends '.$Invite_Friends.'</td></tr>
							<tr><td>6.Add Happy Experience '.$Happy_Experience.'</td></tr>
							<tr><td>&nbsp;</td></tr>';
							
						}
			$message .= '<tr><td>&nbsp;</td></tr>
						<tr><td>&nbsp;</td></tr>
						<tr><td>Love,</td></tr>
						<tr><td>'.REGARDS.'</td></tr>
						</table>';
			SendMail($to,$subject,$message);
			
			//2. mail for the attract for happyness bank
			$to1 = $email_id;
			$subject1 = 'Your Bank of Happy Life report - '.date('dS F Y');
			$message1 = '<table>
						<tr><td>&nbsp;</td></tr>
						<tr><td>Dear '.$UserName.',</td></tr>
						<tr><td>&nbsp;</td></tr>
						<tr><td>Date :'.date('dS F Y').'</td></tr>
						<tr><td>&nbsp;</td></tr>';
						$selQuery=mysql_query("select category_id,category_name from tbl_app_category where is_happybank='1' order by sequence");
						while($selRow=mysql_fetch_array($selQuery)){
							$category_id=$selRow['category_id'];
							$UserBalance=getHappyPlushPoint($user_id,$category_id);
							$category=ucwords($selRow['category_name']);
							$message1 .= '<tr><td><strong>'.$category.' Debit Card</strong></td></tr>
							<tr><td>end of previous day total : '.$UserBalance.'</td></tr>
							<tr><td>today do atleast 1 of these 6 activities to keep you love account happy</td></tr>
							<tr><td>1.Create Story (+10 points)</td></tr>
							<tr><td>2.Do-it (+10 points)</td></tr>
							<tr><td>3.Copy Board (+10 points)</td></tr>
							<tr><td>4.Share Friend (+10 points)</td></tr>
							<tr><td>5.Invite Friends (+10 points)</td></tr>
							<tr><td>6.Add Happy Experience (+10 points)</td></tr>
							<tr><td>&nbsp;</td></tr>';
							
						}
			$message1 .= '<tr><td>&nbsp;</td></tr>
						<tr><td>&nbsp;</td></tr>
						<tr><td>Love,</td></tr>
						<tr><td>'.REGARDS.'</td></tr>
						</table>';
			SendMail($to1,$subject1,$message1);
		}
	}	
}
?>