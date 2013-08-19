<?php
include_once "fbmain.php";
include('include/app-common-config.php');
error_reporting(0);
$type=$_REQUEST['type'];
$cherryboard_id=$_REQUEST['cherryboard_id'];
$ImageMagic_Path='';
$uploaddir = 'images/cherryboard/temp/';
$fname=$_FILES['uploadfile']['name'];
$fname=str_replace(' ','_',$fname);
$fname=str_replace('-','_',$fname);
$fname=rand()."-".$fname;
$uploadPath = $uploaddir.$fname; 
//photo cancel
if($type=="cancel"){
	unlink($uploaddir.$_REQUEST['file_name']);
	exit(0);
}else if (move_uploaded_file($_FILES['uploadfile']['tmp_name'], $uploadPath)) { 
	echo "<div class=\"comment_box\">
	<Table>
	<tr>
	  <td colspan=\"2\">
		  <div id=\"files\"><img src=\"".$uploadPath."\" alt=\"\" height=\"100\" width=\"100\" class=\"image\" /></div><br/><span class=\"comment_txt1\" style=\"font-size:10px;margin-left: 2px;\">Max allowed 3MB</span>
      </td>
	  <td>
		<textarea name=\"txtcomment\" rows=\"5\" class=\"textfield\" id=\"txtcomment\" onfocus=\"if(this.value=='Write your comment here...') this.value='';\" onblur=\"if(this.value=='') this.value='Write your comment here...';\">Write your comment here...</textarea> 
	  </td>
	</tr>
	<tr>
	 <td><img src=\"images/round_arrow_90.jpg\" style=\"cursor:pointer\" onclick=\"rotate_photo('goal','".$fname."','90')\" alt=\"\" width=\"35\" height=\"35\" id=\"rotate_img\" />&nbsp;</td>
	 <td>
	  <div class=\"styleall\"><a href=\"#\" onclick=\"photo_cancel('goal','".$fname."')\" class=\"right gray_link\">
		  <img src=\"images/close_small1.png\"> Cancel</a>
		  </div>
     </td>
	 <td><input name=\"button\" type=\"button\" onclick=\"add_photo('goal','".$fname."')\" value=\"Post\" title=\"Post\" class=\"btn_small right\"></td>
   </tr>
	</table>
		  <div class=\"clear\"></div></div>";
	exit(0);	  
}else if($type=="rotate") {
	$file_name=$_GET['file_name'];
	$txtcomment=$_GET['txtcomment'];
	$rotate_degree=$_GET['rotate_degree'];
	$rotate_img='round_arrow_90.jpg';
	$new_rotate_degree=90;
    $newFileName=rand().'_'.$file_name;
	$uploadPath = $uploaddir.$file_name;
	$uploadNewPath = $uploaddir.$newFileName;
	if($rotate_degree==90){
	    $new_rotate_degree=180;
		$rotate_img='round_arrow_180.jpg';
	}else if($rotate_degree==180){
		$new_rotate_degree=270;
		$rotate_img='round_arrow_270.jpg';
	}else if($rotate_degree==270){
	    $new_rotate_degree=360;
		$rotate_img='round_arrow_0.jpg';
	}
	//Rotate Image
    $command='convert -rotate 90 '.$uploadPath.' '.$uploadNewPath;
	passthru($command);
	
	echo "<div class=\"comment_box\">
	<Table>
	<tr>
	  <td colspan=\"2\">
		  <div id=\"files\"><img src=\"".$uploadNewPath."\" alt=\"\" height=\"100\" width=\"100\" class=\"image\" /></div><span class=\"comment_txt1\" style=\"font-size:10px;margin-left: 2px;\">Max allowed 3MB</span>
      </td>
	  <td>
		<textarea name=\"txtcomment\" rows=\"5\" class=\"textfield\" id=\"txtcomment\" onfocus=\"if(this.value=='Write your comment here...') this.value='';\" onblur=\"if(this.value=='') this.value='Write your comment here...';\">".$txtcomment."</textarea> 
	  </td>
	</tr>
	<tr>
	 <td><img src=\"images/".$rotate_img."\" style=\"cursor:pointer\" onclick=\"rotate_photo('goal','".$newFileName."','".($new_rotate_degree)."')\" alt=\"\" height=\"35\" id=\"rotate_img\" /></td>
	 <td>
	  <div class=\"styleall\"><a href=\"#\" onclick=\"photo_cancel('goal','".$newFileName."')\" class=\"right gray_link\">
		  <img src=\"images/close_small1.png\"> Cancel</a>
		  </div>
     </td>
	 <td><input name=\"button\" type=\"button\" onclick=\"add_photo('goal','".$newFileName."')\" value=\"Post\" title=\"Post\" class=\"btn_small right\"></td>
   </tr>
	</table>
		  <div class=\"clear\"></div></div>";
	exit(0);
}//End of if

//=================> Start Insert Code <===================
if($type=="add"){
   $rnd=rand();
   $photo_name=$_REQUEST['file_name'];
   $user_id=$_REQUEST['user_id'];
   $comment=$_REQUEST['comment'];
   $uploaddir='images/cherryboard/'.$photo_name;
   $uploaddirThumb='images/cherryboard/thumb/'.$photo_name;
   $old_uploaddir='images/cherryboard/temp/'.$photo_name;
   
   //for local due to ImageMagic not working in local
   if($_SERVER['SERVER_NAME']=="localhost"){
   		$retval=copy($old_uploaddir,$uploaddir);
		$retval=copy($old_uploaddir,$uploaddirThumb);
   }else{
	    $thumb_command=$ImageMagic_Path."convert ".$old_uploaddir." -thumbnail 195 x 195 ".$uploaddir;
		$last_line=system($thumb_command, $retval);
   		$thumb_command_thumb=$ImageMagic_Path."convert ".$old_uploaddir." -thumbnail 60 x 60 ".$uploaddirThumb;
		$last_line=system($thumb_command_thumb, $retval);
   }
   if($retval){
   		if($comment=="Write your comment here..."){
			$comment='';
		}
    	$insert_qry="INSERT INTO `tbl_app_cherry_photo`(`photo_id`, `user_id`, `cherryboard_id`, `photo_title`, `photo_name`) VALUES ('',".$user_id.",".$cherryboard_id.",'".$comment."','".$photo_name."')";
   		$insert_qry_res=mysql_query($insert_qry);
		$insert_photo_id=mysql_insert_id();
		$_SESSION['insert_photo_id']=$insert_photo_id;
		if($insert_qry_res){
			//update photo status date
			$updateStatus=mysql_query("update tbl_app_cherry_photo_status set photo_date='".date('Y-m-d')."' where cherryboard_id=".$cherryboard_id);
			//send mail to user of thanks
			$userArray=getFieldsValueArray('email_id,first_name','tbl_app_users','user_id='.$user_id);
			$cherryboard_title=getFieldValue('cherryboard_title','tbl_app_cherryboard','cherryboard_id='.$cherryboard_id);
			$email_id=$userArray[0];
			$first_name=$userArray[1];
			$TodayDay=getGoalboardToday($cherryboard_id);
			$GiftName=getFieldValue('b.gift_title','tbl_app_cherry_gift a,tbl_app_gift b','a.gift_id=b.gift_id and a.cherryboard_id='.$cherryboard_id);
			$RemainDays=getGoalboardRemainDays($cherryboard_id);
			
			//START share into facebook wall
			$post_wall_array=array('access_token' =>$_SESSION['fb_access_token'],'message' => 'Day '.$TodayDay.' of changing my life by sticking to my goal '.$cherryboard_title,'name' => $cherryboard_title,'description' => 'I am very happy that I am able to stick to my goal the past '.$TodayDay.' days by simply uploading 1 picture a day.','caption' => 'Achieve goals and win gifts change to '.$RemainDays.' more days to win my gift '.$GiftName,'picture' => 'http://30daysnew.com/'.$uploaddir,'link' => 'http://30daysnew.com','properties' => array(array('text' => 'View Goal Storyboard', 'href' => 'http://30daysnew.com/cherryboard.php?cbid='.$cherryboard_id),),);
			include('post_fb_wall.php');
			//END share into facebook wall
			//START Mail content code
			$gift_id=getFieldValue('gift_id','tbl_app_cherry_gift','cherryboard_id='.$cherryboard_id);
			$CompainDetail=getFieldsValueArray('campaign_title,gift_title,goal_days,miss_days,sponsor_name','tbl_app_gift','gift_id='.$gift_id);
			$cntFillDay=0;
			$countFillDays="SELECT count(`photo_id`),date_format(`record_date`,'%Y-%m-%d') as postdate FROM `tbl_app_cherry_photo` WHERE `user_id`=".USER_ID." and `cherryboard_id`=".$cherryboard_id." group by postdate";
			$countFillSql=mysql_query($countFillDays);
			while($countFillRow=mysql_fetch_row($countFillSql)){
				$cntFillDay++;
			}
			$CampaignTitle=$CompainDetail[0];
			$RewardTitle=$CompainDetail[1];
			$NumberDays=$CompainDetail[2];
			$NumberStriks=$CompainDetail[3];
			$SponsoredBy=$CompainDetail[4];
			$DaysMissed=(int)($TodayDay-$cntFillDay);
			$DaysRemaining=(int)($NumberDays-$TodayDay);
		
			if($email_id!=""){
				//mail to user
				$to      = $email_id;
				$subject = 'Day '.$TodayDay.' picture for "'.$CampaignTitle.'"';
				$message = '<table>
							<tr><td colspan="2">Hi '.$first_name.',</td></tr>
							<tr><td colspan="2">&nbsp;</td></tr>
							<tr><td><strong>Message</strong><span style="padding-left:127px;">:</span></td><td>'.$CampaignTitle.'</td></tr>
							<tr><td><strong>Reward</strong><span style="padding-left:133px;">:</span></td><td>'.$RewardTitle.'</td></tr>
							<tr><td><strong>Number of days</strong><span style="padding-left:84px;">:</span></td><td>'.$NumberDays.'</td></tr>
							<tr><td><strong>Number of strikes</strong><span style="padding-left:72px;">:</span></td><td>'.$NumberStriks.'</td></tr>
							<tr><td><strong>Number of pictures uploaded</strong>:</td><td>'.$cntFillDay.'</td></tr>
							<tr><td><strong>Number of day missed</strong><span style="padding-left:42px;">:</span></td><td>'.$DaysMissed.'</td></tr>
							<tr><td><strong>Sponsored by</strong><span style="padding-left:95px;">:</span></td><td>'.$SponsoredBy.'</td></tr>
							<tr><td>&nbsp;</td></tr>
							<tr><td colspan="2">Thank you,</td></tr>
							<tr><td colspan="2">30daysnew Team,</td></tr>
							<tr><td colspan="2">www.30daysnew.com</td></tr>
							</table>';
				SendMail($email_id,$subject,$message);
			}
		}
		//unlink($old_uploaddir);
		echo "<span class=\"fgreen\">Photo added successfully.</span>";
		echo "##===##";
   }else{
    echo "Photo Inserting Error...";
	unlink($_REQUEST['file_name']);
   } 
}//end of Submit  

//DELETE PHOTO
if($type=="del_photo"&&$_GET['del_photo_id']>0){
	$del_photo_id=$_GET['del_photo_id'];
	$photo_name=getFieldValue('photo_name','tbl_app_cherry_photo','photo_id='.$del_photo_id);
	$photo_path='images/cherryboard/'.$photo_name;
	if(is_file($photo_path)){
		unlink($photo_path);
		$del_photo=mysql_query('delete from tbl_app_cherry_photo where photo_id='.$del_photo_id);
	}
}
if($type=="del_photo"||$type=="photo_refresh"){
	  $sort=$_GET['sort'];
	  
	  $NewphotoCnt='<div><table><tr><td><a title="Sort" name="Sort" href="javascript:void(0);" onclick="javascript:ajax_action(\'photo_refresh\',\'right_container\',\'cherryboard_id='.$cherryboard_id.'&sort='.($sort=="asc"?'desc':'asc').'\')">'.($sort=="asc"?'<img id="des" src="images/des.jpg" height="35" width="35"/>':'<img id="des" src="images/asc.jpg" height="35" width="35"/>').'</a></td><td><img id="rotate_asc" src="images/transparent.png" height="35" width="35"/></td></tr></table></div>';
	  
	  //Days Title
	  $campaign_id=getCampaignId($cherryboard_id);
	  $selDays=mysql_query("select day_title from tbl_app_campaign_days where campaign_id=".$campaign_id." order by day_no");
	  $DaysTitleArr=array();
	  if(mysql_num_rows($selDays)>0){
		  while($selDaysRow=mysql_fetch_array($selDays)){
			$DaysTitleArr[]=$selDaysRow['day_title'];
		  }
		  
	  }
	  
	  //CHERRYBOARD PHOTOS
	  $Board_record_date=getFieldValue('record_date','tbl_app_cherryboard','cherryboard_id='.$cherryboard_id);
	  $qryphoto="select *,date_format(record_date,'%m/%d/%Y') as new_record_date,DATEDIFF(record_date,'".$Board_record_date."') as photo_day  from tbl_app_cherry_photo where cherryboard_id=".$cherryboard_id." order by photo_id desc";
	  $selphoto=mysql_query($qryphoto);
	  $cntPhoto=mysql_num_rows($selphoto);
	  $photoDayArr=array();
	  if($cntPhoto>0){
		while($selphotoRow1=mysql_fetch_array($selphoto)){
			$photo_id=$selphotoRow1['photo_id'];
			$photo_day=((int)$selphotoRow1['photo_day']+1);
			$photoDayArr[$photo_id]=$photo_day;
		}	
	  
	  }
	 $goal_days=getGoalDays($cherryboard_id);
	 $photoDayArr = array_unique($photoDayArr);
	 $photoCntArray=array();
	 for($i=$goal_days;$i>=1;$i--){	
		$photoCnt='';	
		if(in_array($i,$photoDayArr)){
		  $qryphoto="select *,date_format(record_date,'%m/%d/%Y') as new_record_date,DATEDIFF(record_date,'".$Board_record_date."') as photo_day  from tbl_app_cherry_photo where cherryboard_id=".$cherryboard_id." and (DATEDIFF(record_date,'".$Board_record_date."')+1)='".$i."' order by photo_id desc";
		  $selphoto=mysql_query($qryphoto);
		  while($selphotoRow=mysql_fetch_array($selphoto)){
				$photo_id=$selphotoRow['photo_id'];
				$photo_title=ucwords(stripslashes($selphotoRow['photo_title']));
				$photo_name=$selphotoRow['photo_name'];
				$record_date=$selphotoRow['new_record_date'];
				$photo_day=((int)$selphotoRow['photo_day']+1);
				$photoPath='images/cherryboard/'.$photo_name;
				if(is_file($photoPath)){
				   $photoCnt.='<div class="field_container2">
				   
					<div class="day_container">Day '.$photo_day.'</div>
			  <div class="tag_container">
				<div class="comment_box1" style="cursor:pointer;height:30px;" ondblclick="ajax_action(\'upd_photo_title\',\'photo_title'.$photo_id.'\',\'stype=add&photo_id='.$photo_id.'\')" id="photo_title'.$photo_id.'">&nbsp;'.getLimitString($photo_title,55).'</div><div class="clear"></div>
					<div class="info_box">
						<div class="score">'.$DaysTitleArr[(int)($photo_day-1)].'</div>
						<div class="date">'.$record_date.'</div>
					 </div>
					 <div class="b_arrow"></div>
				 <div class="clear"></div>
			 </div>
				   
						<div class="img_big_container">
							<div class="feedbox_holder">
								<div class="actions">';
									if($user_id==USER_ID){
									$photoCnt.='<a class="delete" href="#" onclick="photo_action(\'del_photo\','.$cherryboard_id.','.$photo_id.')"><img src="images/delete.png"></a>';
									}
									$photoCnt.='</div>
							 </div>
							 <div class="send_message">
								<div class="actions1"><a href="#" onclick="javascript:window.open(\'add_data.php?type=thankyou&cherryboard_id='.$cherryboard_id.'\',\'add_data\',\'height=150,width=500\')" class="msg">Send Thank You</a></div>
							 </div>
							  <img src="'.$photoPath.'">
						 </div>
					   <div id="div_cherry_comment_'.$photo_id.'">';
							$TotalCmt=getFieldValue('count(photo_id)','tbl_app_cherry_comment','photo_id='.$photo_id);
							$TotalCheers=getFieldValue('count(cheers_id)','tbl_app_cherryboard_cheers','photo_id='.$photo_id);
							$checkCheers=(int)getFieldValue('user_id','tbl_app_cherryboard_cheers','photo_id='.$photo_id.' and user_id='.USER_ID);
							if($checkCheers==0){
								$cheersLink='<a href="javascript:void(0);" onclick="add_cherry_cheers(\'add_cheers\',\''.$photo_id.'\',\''.$cherryboard_id.'\',\''.USER_ID.'\')" class="red_link_14" style="font-size:12px;">+give cheers!</a>';
							}else{$cheersLink='';}
							$photoCnt.=$cheersLink.'<div class="right smalltext1" id="div_photo_cheers_'.$photo_id.'">'.(int)$TotalCheers.' Cheers &nbsp;&nbsp;'.(int)$TotalCmt.' Comments</div><br><br>';
							if($TotalCmt>0){
							  $selCmt=mysql_query("select * from tbl_app_cherry_comment where photo_id=".$photo_id." order by comment_id desc limit 2");
							  while($cmtRow=mysql_fetch_array($selCmt)){
								   $userPhotoArray=getFieldsValueArray('first_name,last_name,fb_photo_url','tbl_app_users','user_id='.$cmtRow['user_id']);
								   $UserName=$userPhotoArray[0].' '.$userPhotoArray[1];
								   $UserPhoto=$userPhotoArray[2];
								   $comment_id=$cmtRow['comment_id'];
								   $PhotoComment=stripslashes($cmtRow['cherry_comment']);
								   
								   $photoCnt.='<div class="comment2">
									  <div class="feedbox_holder">
										<div class="actions" style="right: -5px;"><a class="delete" href="javascript:void(0);" onclick="add_cherry_comment(event,\'del_cherry_comment\','.$cherryboard_id.','.$photo_id.','.$cmtRow['user_id'].','.$comment_id.')"><img src="images/delete.png"></a></div>
									  </div>
									  <img src="'.$UserPhoto.'" height="30" width="30" class="img_thumb1">
									  <div class="comment_txt"><strong>'.$UserName.'</strong>&nbsp;&nbsp;'.getLimitString($PhotoComment,30).'</div> <div class="clear"></div></div>';
							  }
							}
					$photoCnt.='</div>
						  <textarea name="cherry_comment_'.$photo_id.'" class="input_comments" id="cherry_comment_'.$photo_id.'" onfocus="if(this.value==\'Leave your comment here\') this.value=\'\';" onblur="if(this.value==\'\') this.value=\'Leave your comment here\';" onkeypress="return add_cherry_comment(event,\'add_cherry_comment\',\''.$cherryboard_id.'\',\''.$photo_id.'\',\''.USER_ID.'\',document.getElementById(\'cherry_comment_'.$photo_id.'\').value)" style="height: 20px;">Leave your comment here</textarea>
						  ';
					if(($i==$goal_days&&$sort=="asc")||($i==1&&$sort!="asc")){
							//$photoCnt.='<div style="padding-bottom:35px;"></div>';	  
					}
					$photoCnt.='</div>';
					$photoCntArray[$i]=$photoCnt;
				}
			}
		  }else{
			 $photoPath='images/cherryboard/no_image.jpg'; 
			 $photoCnt.='<div class="field_container2">
				   
					<div class="day_container">Day '.$i.'</div>
			  <div class="tag_container">
				<div class="comment_box1" style="height:30px;">No Photo</div><div class="clear"></div>
					<div class="info_box">
						<div class="score">'.$DaysTitleArr[(int)($i-1)].'</div>
						<div class="date">&nbsp;</div>
					 </div>
					 <div class="b_arrow"></div>
				 <div class="clear"></div>
			 </div>
				   
						<div class="img_big_container">
							 <img src="'.$photoPath.'" height="195" width="195">
						 </div>
					   <div id="div_cherry_comment">';
							
					$photoCnt.='</div>';
					if(($i==$goal_days&&$sort=="asc")||($i==1&&$sort!="asc")){
							//$photoCnt.='<div style="padding-bottom:35px;"></div>';	  
					}
					$photoCnt.='</div>';
					$photoCntArray[$i]=$photoCnt;
					
		  }
		   
		}
	$NewphotoCnt.='<table border="0"><tr>';
	if($sort=="asc"||$sort==""){
		for($i=1;$i<=$goal_days;$i++){
			$NewphotoCnt.='<td valign="top">'.$photoCntArray[$i].'</td>';
			if($i%3==0){$NewphotoCnt.='</tr><tr>';}
		}
	}else{
	    $cnt=1;
		for($i=$goal_days;$i>=1;$i--){
			$NewphotoCnt.='<td valign="top">'.$photoCntArray[$i].'</td>';
			if($cnt%3==0){$NewphotoCnt.='</tr><tr>';}
			$cnt++;
		}
	}
	$NewphotoCnt.='</tr>
	<tr><td colspan="3" style="height:50px">&nbsp;</td></tr>
	</table>';
}		
//END CHERRYBOARD PHOTOS
if($type=="del_photo"||$type=="photo_refresh"){
	echo $NewphotoCnt=$type.'##===##right_container##===##'.$NewphotoCnt;
}else{
	echo '##===##'.$cherryboard_id;
}

?>


