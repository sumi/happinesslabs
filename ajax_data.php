<?php 
error_reporting(0);
//include_once "fbmain.php";
//include('include/app-common-config.php');
include('include/app-db-connect.php');
include('include/app_functions.php');

$type=$_GET['type'];
$div_name=$_GET['div_name'];
$ajax_data='';

//STORY SHOW CATEGORY FIELDS VALUE
if($type=="show_storycat"){
   $stype=trim($_GET['stype']);   
   if($stype=='storycat'){
   	  $missionId=(int)$_GET['txt_storycat'];
	  $pillar_no=(int)getFieldValue('pillar_no','tbl_app_happy_mission','happy_mission_id='.$missionId);
   	  $category_id=(int)getFieldValue('category_id','tbl_app_happiness_pillar','pillar_no='.$pillar_no);
	  $category_name=ucwords(trim(getFieldValue('category_name','tbl_app_category','category_id='.$category_id)));
	  $iconPath=getCategoryIcon($category_name);	  //$catName=trim(ucwords(getFieldValue('happy_mission_title','tbl_app_happy_mission','happy_mission_id='.$storyCat)));
   	  $ajax_data.='<img src="images/mission/mission_'.$missionId.'.png" height="150" width="150"/>';
	  $catIcon='<img src="'.$iconPath.'" height="40" width="40" />';
   }     	 
   $ajax_data=$type."##===##".$div_name."##===##".$ajax_data."##===##".$catIcon;
   echo $ajax_data;
}
//STORY BOOK DO-IT CODE
if($type=="doit_story"){
 	$cherryboard_id=(int)$_GET['cherryboard_id'];
	$expertboard_id=(int)$_GET['expertboard_id'];
	$user_id=(int)$_GET['user_id'];	
	if($cherryboard_id>0&&$expertboard_id>0&&$user_id>0){
	   $title=trim(ucwords(getFieldValue('title','tbl_app_life_story_book_template','cherryboard_id='.$cherryboard_id)));	
	   $new_cherryboard_id=createExpertboard($expertboard_id,$cherryboard_id);
	   $ajax_data.='&nbsp;&nbsp;&nbsp;'.$title.'&nbsp;<a href="expert_cherryboard.php?cbid='.$new_cherryboard_id.'" style="text-decoration:none;">View story</a>&nbsp;<span style="color:#009933;font-weight:bold;">Story Board Created Successfully</span><br />'; 
	}
	$ajax_data=$type."##===##".$div_name."##===##".$ajax_data;
	echo $ajax_data;
}
//START LIKE AND UNLIKE CODE
if($type=="like_story"||$type=="unlike_story"){
   $cherryboard_id=(int)$_GET['cherryboard_id'];
   $user_id=(int)$_GET['user_id'];
   $like_id=(int)$_GET['like_id'];
   if($type=="like_story"){
	  if($cherryboard_id>0&&$user_id>0&&$like_id==0){
	  	 $isCheck=(int)getFieldValue('like_id','tbl_app_expertboard_likes','cherryboard_id='.$cherryboard_id.' AND user_id='.$user_id);
		 if($isCheck==0){
	   	 	$insLike=mysql_query("INSERT INTO tbl_app_expertboard_likes (like_id,user_id,cherryboard_id,is_like, record_date) VALUES (NULL,'".$user_id."','".$cherryboard_id."','1',CURRENT_TIMESTAMP);");
			$like_id=mysql_insert_id();
		 }
		 if($insLike&&$like_id>0){
		    $ajax_data.='<img src="images/set_like.png" height="35px" width="35px" title="Like" />&nbsp;<a href="javascript:void(0);" onclick="ajax_action(\'unlike_story\',\'div_like_'.$cherryboard_id.'\',\'like_id='.$like_id.'&user_id='.$user_id.'&cherryboard_id='.$cherryboard_id.'\');" title="Unlike"><img src="images/unlike.png" height="35px" width="35px" title="Unlike" /></a>';
		 }
	  }else if($cherryboard_id>0&&$user_id>0&&$like_id>0){
	  	 $updtLike=mysql_query("UPDATE tbl_app_expertboard_likes SET is_like='1' WHERE like_id=".$like_id); 
		 if($updtLike){
		 	$ajax_data.='<img src="images/set_like.png" height="35px" width="35px" title="Like" />&nbsp;<a href="javascript:void(0);" onclick="ajax_action(\'unlike_story\',\'div_like_'.$cherryboard_id.'\',\'like_id='.$like_id.'&user_id='.$user_id.'&cherryboard_id='.$cherryboard_id.'\');" title="Unlike"><img src="images/unlike.png" height="35px" width="35px" title="Unlike" /></a>';
		 }
	  }
   }else{//START UNLIKE STORY CODE   	   	
   	  if($cherryboard_id>0&&$user_id>0&&$like_id==0){
	   	 $isCheck=(int)getFieldValue('like_id','tbl_app_expertboard_likes','cherryboard_id='.$cherryboard_id.' AND user_id='.$user_id);
		 if($isCheck==0){
	   	 	$insUnlike=mysql_query("INSERT INTO tbl_app_expertboard_likes (like_id,user_id,cherryboard_id,is_like, record_date) VALUES (NULL,'".$user_id."','".$cherryboard_id."','2',CURRENT_TIMESTAMP);");
			$like_id=mysql_insert_id();
		 }
		 if($insUnlike&&$like_id>0){
		 	$ajax_data.='<a href="javascript:void(0);" onclick="ajax_action(\'like_story\',\'div_like_'.$cherryboard_id.'\',\'cherryboard_id='.$cherryboard_id.'&user_id='.$user_id.'&like_id='.$like_id.'\');" title="Like"><img src="images/like.png" height="35px" width="35px" title="Like" /></a>&nbsp;<img src="images/set_unlike.png" height="35px" width="35px" title="Unlike" />';
		 }
	  }else if($cherryboard_id>0&&$user_id>0&&$like_id>0){
	  	 $updtUnlike=mysql_query("UPDATE tbl_app_expertboard_likes SET is_like='2' WHERE like_id=".$like_id); 
		 if($updtUnlike){
		 	$ajax_data.='<a href="javascript:void(0);" onclick="ajax_action(\'like_story\',\'div_like_'.$cherryboard_id.'\',\'cherryboard_id='.$cherryboard_id.'&user_id='.$user_id.'&like_id='.$like_id.'\');" title="Like"><img src="images/like.png" height="35px" width="35px" title="Like" /></a>&nbsp;<img src="images/set_unlike.png" height="35px" width="35px" title="Unlike" />';
		 }
	  }	
   }
   $ajax_data=$type."##===##".$div_name."##===##".$ajax_data;
   echo $ajax_data;
}
//START PUBLISH AND UNPUBLISH HAPPY STORY CODE
if($type=="publish_story"||$type=="unpublish_story"){
   $cherryboard_id=(int)$_GET['cherryboard_id'];
   $publishUserId=(int)$_GET['user_id'];
   $stype=trim($_GET['stype']);
   
   if($publishUserId>0&&$publishUserId==$_SESSION['USER_ID']){
	   if($stype=="publish"&&$cherryboard_id>0){
	   	  $UpdtPublish=mysql_query("UPDATE tbl_app_expert_cherryboard SET is_publish='1' WHERE cherryboard_id=".$cherryboard_id);
		  if($UpdtPublish){
		  	 $ajax_data='<div class="banner_day_5_left" style="width:245px;">
					    <div class="banner_day_5_bg">
						<a href="javascript:void(0);" onclick="ajax_action(\'unpublish_story\',\'div_story_publish\',\'stype=unpublish&cherryboard_id='.$cherryboard_id.'&user_id='.$publishUserId.'\')" title="UnPublish">Unpublish</a>
						</div>
						<img src="images/ban.png" alt="" />
						</div>
						<img src="images/im.png" />';
		  }
	   }else{
	      $UpdtUnPublish=mysql_query("UPDATE tbl_app_expert_cherryboard SET is_publish='0' WHERE cherryboard_id=".$cherryboard_id);
		  if($UpdtUnPublish){
	   	  	 $ajax_data='<div class="banner_day_5_left" style="width:197px;">
					    <div class="banner_day_5_bg">
						<a href="javascript:void(0);" onclick="ajax_action(\'publish_story\',\'div_story_publish\',\'stype=publish&cherryboard_id='.$cherryboard_id.'&user_id='.$publishUserId.'\')" title="Publish">Publish</a>
						</div>
						<img src="images/ban.png" alt="" />
						</div>
						<img src="images/im.png" />';
		  }
	   }
   }
   $ajax_data=$type."##===##".$div_name."##===##".$ajax_data;
   echo $ajax_data;
}
//START CREATE STORY NUMBER IMAGES
if($type=="focus_story_title"||$type=="focus_story_category"||$type=="focus_story_about"||$type=="focus_story_daytype"||$type=="focus_story_price"||$type=="focus_story_type"){
   $stype=trim($_GET['stype']);
   $cnt=0;
   if($stype=='title'){ $cnt=1;
   }else if($stype=='category'){ $cnt=2;
   }else if($stype=='about'){ $cnt=3;
   }else if($stype=='daytype'){ $cnt=4;
   }else if($stype=='boardprice'){ $cnt=5;
   }else if($stype=='boardtype'){ $cnt=6;}
   if($stype!=''){
   	  $ajax_data='<div class="project_left_1">'.$cnt.'</div>
           		  <div class="project_left_one"><img src="images/one_2.png" alt="" /></div>';
   }
   $ajax_data=$type."##===##".$div_name."##===##".$ajax_data;
   echo $ajax_data;
}
//START TELL A HAPPY STORY CONFIRM AND NOTNOW CODE
if($type=="tell_story_confirm"||$type=="tell_story_notnow"){
   $invite_user_id=(int)$_GET['invite_user_id'];
   $stype=trim($_GET['stype']);
   if($invite_user_id>0&&$_SESSION['USER_ID']>0&&$stype!=''){
      if($stype=='confirm'){ 
	  	//CONFIRM QUERY
   	  	$updtRequest=mysql_query("UPDATE tbl_app_user_invite SET is_accept='1' WHERE invite_user_id=".$invite_user_id);
	  }else if($stype=='notnow'){
	    //NOTNOW QUERY
	  	$delRequest=mysql_query("DELETE FROM tbl_app_user_invite WHERE invite_user_id=".$invite_user_id);
	  }
	  if($updtRequest||$delRequest){
	  	$selInviteFrnds=mysql_query("SELECT * FROM tbl_app_user_invite WHERE is_accept='0' ORDER BY invite_user_id");
		while($selInviteFrndsRow=mysql_fetch_array($selInviteFrnds)){
			$invite_user_id=(int)$selInviteFrndsRow['invite_user_id'];
			$userId=(int)$selInviteFrndsRow['user_id'];
			$inviteUserFbId=trim($selInviteFrndsRow['invite_user_fb_id']);
			$inviteUserId=(int)getFieldValue('user_id','tbl_app_users','facebook_id='.$inviteUserFbId);
			//GET USER DETAILS
			$senderUserDetails=getUserDetail($userId);
			$senderFbId=$senderUserDetails['fb_id'];
			$SenderName=$senderUserDetails['first_name'].' '.$senderUserDetails['last_name'];
			$userPicPath='https://graph.facebook.com/'.$senderFbId.'/picture?type=large';
			if($inviteUserId==$_SESSION['USER_ID']){
			$ajax_data.='<span style="padding-left:20px;font-size:16px;font-weight:bold;">Your Friend '.$SenderName.'&nbsp;<img src="'.$userPicPath.'" height="20" width="20"/>&nbsp;Invited You To Tell A Happy Story.</span><br/>';
			$ajax_data.='<div style="padding-left:185px;">
						  <a rel="leanModal" href="#create_expert_board" title="Tell a Happy Story" />
				  <img src="images/happy_story.png" onclick="ajax_action(\'tell_story_confirm\',\'div_happy_story\',\'invite_user_id='.$invite_user_id.'&stype=confirm\');"/></a>&nbsp;&nbsp;
				  <a href="javascript:void(0);" title="NotNow" />
				  <img src="images/notnow.png" onclick="ajax_action(\'tell_story_notnow\',\'div_happy_story\',\'invite_user_id='.$invite_user_id.'&stype=notnow\');"/></a>
						  </div><br/>';
			 //$delete_success=$facebook->api('/'.$request_ids,'DELETE');			  
			}
		}
	  }
   }
   $ajax_data=$type."##===##".$div_name."##===##".$ajax_data;
   echo $ajax_data;
}
//START MORE CLASSMATES SECTION CODE
if($type=="more_classmates"){
   $expertboard_id=(int)$_GET['expertboard_id'];
   $selClassMates=mysql_query("SELECT DISTINCT user_id FROM tbl_app_expert_cherryboard WHERE expertboard_id=".$expertboard_id);
   $pageUserPhotosArray=array();
   while($selClassMatesRows=mysql_fetch_array($selClassMates)){
		$customerUserId=(int)$selClassMatesRows['user_id'];
		$expBoardId=(int)getFieldValue('cherryboard_id','tbl_app_expert_cherryboard','expertboard_id='.$expertboard_id.' and user_id='.$customerUserId);
		$customerDetail=getFieldsValueArray('first_name,last_name,fb_photo_url','tbl_app_users','user_id='.$customerUserId);
		$customer_name=$customerDetail[0].''.$customerDetail[1];
		$customer_photo=$customerDetail[2];
		$ajax_data.=''.($customerUserId>0?'<div class="classmates_img"><a href="expert_cherryboard.php?cbid='.$expBoardId.'"><img src="'.$customer_photo.'" title='.$customer_name.' style="margin-bottom:0px;width:50px;height:50px;" data-tooltip="stickyCustomer'.$customerUserId.'"/></a></div>':'').'';
		$pageUserPhotosArray[$customerUserId]=$customer_photo;						
   }			
   $ajax_data=$type."##===##".$div_name."##===##".$ajax_data;
   echo $ajax_data;
}
//START SWAP TO-DO LIST CODE
if($type=="swap_todolist"){
	$from_checklist_id=(int)$_GET['imgswap_from'];
	$to_checklist_id=(int)$_GET['imgswap_to'];
	$tbl_name='tbl_app_expert_checklist';
	
	if($from_checklist_id>0&&$to_checklist_id>0){
		$fromToDoList=stripslashes(getFieldValue('checklist',$tbl_name,'checklist_id='.$from_checklist_id));
		$fromIsChecked=(int)getFieldValue('is_checked',$tbl_name,'checklist_id='.$from_checklist_id);
	    $toToDoList=stripslashes(getFieldValue('checklist',$tbl_name,'checklist_id='.$to_checklist_id));
		$toIsChecked=(int)getFieldValue('is_checked',$tbl_name,'checklist_id='.$to_checklist_id);
		//FROM TODO LIST CODE
		if($from_checklist_id>0){			
			$updtToTodoList=mysql_query("UPDATE tbl_app_expert_checklist SET checklist='".addslashes($toToDoList)."',is_checked='".$toIsChecked."' WHERE checklist_id=".$from_checklist_id);
		}
		//TO TODO LIST CODE
		if($to_checklist_id>0){
			$updtFromToDoList=mysql_query("UPDATE tbl_app_expert_checklist SET checklist='".addslashes($fromToDoList)."',is_checked='".$fromIsChecked."' WHERE checklist_id=".$to_checklist_id);
		}
		if($updtToTodoList&&$updtFromToDoList){
			$cherryboard_id=(int)getFieldValue('cherryboard_id',$tbl_name,'checklist_id='.$from_checklist_id);
			//CALL FUNCTION GET TODOLIST ITEMS
			$ajax_data.=getToDoListItem($cherryboard_id);
		}			
	}	
	$ajax_data=$type."##===##".$div_name."##===##".$ajax_data;
	echo $ajax_data;
}
//START INCREASE EXPERT GOAL DAYS OR ITEMS
if($type=="increase_expdays_items"){
	$cherryboard_id=(int)$_GET['cherryboard_id'];
	$user_id=(int)$_GET['user_id'];
	if($cherryboard_id>0&&$user_id>0){
		$expertboard_id=getFieldValue('expertboard_id','tbl_app_expert_cherryboard','cherryboard_id='.$cherryboard_id);
		if($expertboard_id>0){
		    $daysDetail=getFieldsValueArray('goal_days,user_id','tbl_app_expertboard','expertboard_id='.$expertboard_id);
		    $goal_days=(int)$daysDetail[0];
		    $ownerUserId=(int)$daysDetail[1];
		   $numberDays=$goal_days+1;
		   $editExpGoalDays=mysql_query("UPDATE tbl_app_expertboard SET goal_days='".$numberDays."' WHERE expertboard_id=".$expertboard_id);
		   $dayType=getDayType($expertboard_id);
		   //INSERT DAYS
		   $insDays="INSERT INTO tbl_app_expertboard_days (expertboard_day_id,expertboard_id,day_no,day_title, record_date,cherryboard_id,sub_day) VALUES (NULL,'".$expertboard_id."','".$numberDays."','".$dayType." ".$numberDays."', CURRENT_TIMESTAMP,'".$cherryboard_id."','1')";
			$insDaysSql=mysql_query($insDays);
			//DISPLAY GOAL DAYS
			$ajax_data.='Total :<a href="javascript:void(0);" '.($user_id==$ownerUserId?'ondblclick="ajax_action(\'edt_exp_goal_day\',\''.$div_name.'\',\'stype=add&expertboard_id='.$expertboard_id.'&user_id='.$user_id.'\')"':'').' title="Edit '.$dayType.'" class="cleanLink"> <span class="style3"> '.$numberDays.' '.$dayType.'s</span></a>';
		}
	}
	$ajax_data=$type."##===##".$div_name."##===##".$ajax_data."##===##".$cherryboard_id;
	echo $ajax_data;
}

//START ADD + EXPERT GOAL DAYS OR ITEMS
if($type=="add_expday"){
	$cherryboard_id=(int)$_GET['cherryboard_id'];
	$day_no=(int)$_GET['day_no'];
	if($cherryboard_id>0&&$day_no>0){
		$expertboard_id=getFieldValue('expertboard_id','tbl_app_expert_cherryboard','cherryboard_id='.$cherryboard_id);
		$dayType=getDayType($expertboard_id);
		
		//Add Day Total into Expertboard
		$dayArr=explode('.',$day_no);
		$newDay=$dayArr[0];
		$newSubDay=(int)$dayArr[1];
		if($newSubDay==0){$newSubDay=1;}
		//ADD Main Day
		if(count($dayArr)==1){
			$dayTitle=$dayType." ".$newDay;
			$insDays="INSERT INTO tbl_app_expertboard_days (expertboard_day_id,expertboard_id,day_no,day_title, record_date,cherryboard_id,sub_day) VALUES (NULL,'".$expertboard_id."','".$newDay."','".$dayTitle."', CURRENT_TIMESTAMP,'".$cherryboard_id."','1')";
			$insDaysSql=mysql_query($insDays);
			$lastDayId=mysql_insert_id();
			//update all past days
			if($lastDayId>0){
				$updDays=mysql_query("update `tbl_app_expertboard_days` set `day_no`=`day_no`+1 where `day_no`>=".$newDay." and cherryboard_id=".$cherryboard_id." and expertboard_day_id!=".$lastDayId);
				
				$updPhotos=mysql_query("update `tbl_app_expert_cherry_photo` set `photo_day`=`photo_day`+1 where `photo_day`>=".$newDay." and cherryboard_id=".$cherryboard_id);
			}
		
		}else{ //ADD subday/point day
		/*	$dayTitle=$dayType." ".$newDay.".".$newSubDay;
			$insDays="INSERT INTO tbl_app_expertboard_days (expertboard_day_id,expertboard_id,day_no,day_title, record_date,cherryboard_id,sub_day) VALUES (NULL,'".$expertboard_id."','".$newDay."','".$dayTitle."', CURRENT_TIMESTAMP,'".$cherryboard_id."','".$newSubDay."')";
			$insDaysSql=mysql_query($insDays);
			$lastDayId=mysql_insert_id();
			//update all past days
			if($lastDayId>0){
				$updDays=mysql_query("update `tbl_app_expertboard_days` set `sub_day`=`sub_day`+1 where `sub_day`>=".$newSubDay." and day_no=".$newDay." and cherryboard_id=".$cherryboard_id." and expertboard_day_id!=".$lastDayId);
				
				$updPhotos=mysql_query("update `tbl_app_expert_cherry_photo` set `sub_day`=`sub_day`+1 where `sub_day`>=".$newSubDay." and photo_day=".$newDay." and cherryboard_id=".$cherryboard_id);
			}
		*/
		}
	}
	$ajax_data=$type."##===##".$div_name."##===##".$ajax_data."##===##".$cherryboard_id;
	echo $ajax_data;
}
//START EXPERT SEND THANK YOU MAIL
if($type=="sendStoryRequest"){
	 $ajax_data='';
	 $user_id=(int)$_GET['user_id'];
	 $send_email=trim($_GET['email_id']);
	 $subject=trim($_GET['subject']);
	 $message=trim($_GET['message']);
	 
	 if($send_email!="Enter Email"&&$subject!="Enter Subject"&&$message!="Enter Message"&&$user_id>0){
	 	$send_emailArr=explode(',',$send_email);
		foreach($send_emailArr as $email_id){
			if($email_id!=""){
				//mail to user
				$userDetail=getUserDetail($user_id);
		    	$sender_name=ucwords($userDetail['name']);
				$to = $email_id;				
				$subject=$subject;
				$message='<table>
						  <tr><td>Hi,</td></tr>
						  <tr><td>&nbsp;</td></tr>
						  <tr><td>'.$message.'</td></tr>
						  <tr><td>&nbsp;</td></tr>
						  <tr><td>'.$sender_name.' sent you a request to share your story.
						  <a href="'.SITE_URL.'/ask_experts.php?type=request">Click here</a>
						   to add your story.
						  </td></tr>
						  <tr><td>&nbsp;</td></tr>
						  <tr><td>Thanks</td></tr>
						  <tr><td>'.$sender_name.'</td></tr>
						  </table>';
				//$ajax_data.=$to."========".$subject."========".$message;
				SendMail($to,$subject,$message,$sender_name);
				$sentMail=1;
				if($sentMail){
				 	$msg='<div class="msg_green" style="padding-left:74px;">Mail sent successfully</div>';
				}else{
					$msg='<div class="msg_red" style="padding-left:74px;">Mail sending error...</div>';
				}
			}		
		}
	 }else{
		$msg='<div class="msg_red" style="padding-left:74px;">Please enter valid email details</div>';
	 }
	 $ajax_data.='<div id="div_send_request">
						<div align="center" class="email_header">Send Request</div><br>
						'.$msg.'
			<span style="padding-left:20px;"><strong>Email</strong>:
			<input type="text" style="width:380px;margin-left:25px;" name="email_id" id="email_id" onblur="if(this.value==\'\') this.value=\'Enter Email\';" onfocus="if(this.value==\'Enter Email\') this.value=\'\';" value="Enter Email" /></span><br><br>
			<span style="padding-left:20px;"><strong>Subject</strong>:
			<input type="text" style="width:380px;margin-left:10px;" name="subject" id="subject" onblur="if(this.value==\'\') this.value=\'Enter Subject\';" onfocus="if(this.value==\'Enter Subject\') this.value=\'\';" value="Enter Subject" /></span><br><br>
			<table><tr>
			<td valign="top" style="padding-left:15px;"><strong>Message</strong>:</td>
			<td><textarea style="width:380px;height:200px;" name="message" id="message" onblur="if(this.value==\'\') this.value=\'Enter Message\';" onfocus="if(this.value==\'Enter Message\') this.value=\'\';">Enter Message</textarea></td></tr></table><br>
			<input type="button" style="margin-left:210px;" class="btn_small" id="btnsend" onClick="ajax_action(\'sendStoryRequest\',\'div_send_request\',\'user_id='.$_SESSION['USER_ID'].'&email_id=\'+document.getElementById(\'email_id\').value+\'&subject=\'+document.getElementById(\'subject\').value+\'&message=\'+document.getElementById(\'message\').value);" value="Send" name="btnsend" />			
			</div>';	
	 $ajax_data=$type."##===##".$div_name."##===##".$ajax_data;
	 echo $ajax_data;
}
//ADD EXPERT NOTES
if($type=="add_expert_notes"||$type=="del_expert_note"){
	$cherryboard_id=(int)$_GET['cherryboard_id'];
	$photo_id=(int)$_GET['photo_id'];
	$user_id=(int)$_GET['user_id'];
	$cherry_note=parseString($_GET['cherry_notes']);
	$tbl_name='tbl_app_expert_notes';
	$photo_day=(int)$_GET['photo_day'];
		
	$expertboard_id=getFieldValue('expertboard_id','tbl_app_expert_cherryboard','cherryboard_id='.$cherryboard_id);
	$expUserId=getFieldValue('user_id','tbl_app_expert_cherryboard','cherryboard_id='.$cherryboard_id);
	$main_board=getFieldValue('cherryboard_id','tbl_app_expert_cherryboard','expertboard_id='.$expertboard_id.' and main_board="1"');
	$expOwner_id=getFieldValue('user_id','tbl_app_expertboard','expertboard_id='.$expertboard_id);

	//ADD NOTES	
	if($type=="add_expert_notes"&&$cherry_note!="Add a notes..."&&$photo_id>0){
		$ins_query="INSERT INTO ".$tbl_name." (notes_id,user_id,cherryboard_id,photo_id,photo_day, cherry_notes,record_date) VALUES (NULL,'".$user_id."','".$cherryboard_id."','".$photo_id."','".$photo_day."','".$cherry_note."',CURRENT_TIMESTAMP)";
		$ins_sql=mysql_query($ins_query);
	}
	//DELETE EXPERT NOTE
	if($type=="del_expert_note"){
		$notes_id=(int)$_GET['notes_id'];
		$del_query="DELETE FROM ".$tbl_name." WHERE notes_id=".$notes_id;
		$del_sql=mysql_query($del_query);
	}
	
	//ADD EXPERT NOTES SECTION
	if($expUserId==$_SESSION['USER_ID']){
		$TotalNotes=(int)getFieldValue('count(photo_id)','tbl_app_expert_notes','photo_id='.$photo_id);
		$notesCnt=$TotalNotes.' notes';
		$photoCnt.=expert_notes_section($cherryboard_id,$photo_id,$photo_day);
	}
	
	$ajax_data.=$photoCnt;	
	$ajax_data=$type."##===##".$div_name."##===##".$ajax_data."##===##".$notesCnt;
	echo $ajax_data;
	exit(0);
}
//START ASK QUESTION CODE
if($type=="expert_notes"){
	 $photo_id=(int)$_GET['photo_id'];
	 $photo_day=(int)$_GET['photo_day'];
	 $cherryboard_id=(int)$_GET['cherryboard_id'];
	 $userId=(int)$_GET['user_id'];
	 $currentUserPic=getFieldValue('fb_photo_url','tbl_app_users','user_id='.$userId);	  
	 $ajax_data.='<div class="add1">
			 <div class="add_img"><img src="'.$currentUserPic.'" class="img_small" /></div>
			 <div class="add_txt">
			 <textarea name="expert_note_'.$photo_id.'" class="input_comments" id="expert_note_'.$photo_id.'" onfocus="if(this.value==\'Expert notes\') this.value=\'\';" onblur="if(this.value==\'\') this.value=\'Expert notes\';" style="height: 29px;width:125px;">Expert notes</textarea>			 
			 </div>
			 <div class="add_btn"><img src="images/addnote.png" style="cursor:pointer" onclick="ajax_action(\'add_expert_notes\',\'div_cherry_comment_'.$photo_id.'\',\'photo_id='.$photo_id.'&cherryboard_id='.$cherryboard_id.'&user_id='.$userId.'&photo_day='.$photo_day.'&note=\'+document.getElementById(\'expert_note_'.$photo_id.'\').value);"></div>
    </div>';
	 $ajax_data=$type."##===##".$div_name."##===##".$ajax_data;
	 echo $ajax_data;  
}
//DO-IT EXPERT HAPPINESS
/*if($type=="expertUsr_Doit_Pic"){
	$photo_id=(int)$_GET['photo_id'];
	$cherryboard_id=(int)$_GET['cherryboard_id'];
	if($photo_id>0&&$cherryboard_id>0){
		$selExpPhoto=mysql_query("SELECT * FROM tbl_app_expert_cherry_photo WHERE photo_id=".$photo_id);
		while($selExpPhotoRow=mysql_fetch_array($selExpPhoto)){
			$photo_title=$selExpPhotoRow['photo_title'];
			$photo_name=$selExpPhotoRow['photo_name'];
			$photo_day=$selExpPhotoRow['photo_day'];
			
			$old_uploaddir='images/expertboard/'.$photo_name;
		    $old_uploaddirThumb='images/expertboard/thumb/'.$photo_name;
		    $old_uploaddirTemp='images/expertboard/temp/'.$photo_name;
			$rnd=rand();
			$new_photo_name=$rnd.'_'.$photo_name;//photo_path set in db
		    $new_uploaddir='images/expertboard/'.$new_photo_name;
		    $new_uploaddirThumb='images/expertboard/thumb/'.$new_photo_name;
		    $new_uploaddirTemp='images/expertboard/temp/'.$new_photo_name;
			
			//for local due to ImageMagic not working in local
		   if($_SERVER['SERVER_NAME']=="localhost"){
		  		$retval=copy($old_uploaddir,$new_uploaddir);
				$retval=copy($old_uploaddirThumb,$new_uploaddirThumb);
				$retval=copy($old_uploaddirTemp,$new_uploaddirTemp);				
		   }else{
				$thumb_command=$ImageMagic_Path."convert ".$old_uploaddir." -thumbnail 195 x 195 ".$new_uploaddir;
				$last_line=system($thumb_command, $retval);
				$thumb_command_thumb=$ImageMagic_Path."convert ".$old_uploaddirThumb." -thumbnail 60 x 60 ".$new_uploaddirThumb;
				$last_line=system($thumb_command_thumb,$retval);
		   }
		   if($retval){
		   		$insQry="INSERT INTO tbl_app_expert_cherry_photo (photo_id,user_id,cherryboard_id,photo_title, photo_name,photo_day) VALUES (NULL,'".$_SESSION['USER_ID']."','".$cherryboard_id."','".$photo_title."','".$new_photo_name."','".$photo_day."')";
				
				$insQryRes=mysql_query($insQry);
		   }
	   }
	}
	$ajax_data=$type."##===##".$div_name."##===##".$ajax_data."##===##".$cherryboard_id;
	echo $ajax_data;
}*/
//DO-IT EXPERT HAPPINESS
if($type=="expert_doit"){
	$expertboard_id=$_GET['expertboard_id'];
	$cherryboard_id=$_GET['cherryboard_id'];
	$expertGreat='';
	$expertOwnerPic='';
	if($expertboard_id>0){
	   createExpertboard($expertboard_id,$cherryboard_id);
	   $ajax_data.='<img src="images/doingit.png" height="25px" width="70px" style="padding-left:160px;" />';
	   $expertGreat.='<img src="images/great.png" />	
                     <div class="img_div_2" style="padding-top:33px;"><img src="images/down.png" alt="" />';
	   $selUsrBoard=mysql_query("SELECT * FROM tbl_app_expert_cherryboard WHERE user_id=".$_SESSION['USER_ID']);
	   while($selUsrBoardRow=mysql_fetch_array($selUsrBoard)){
			$expertBoardId=$selUsrBoardRow['expertboard_id'];
			$DayType=getDayType($expertBoardId);
		    $expertDetails=getFieldsValueArray('cherryboard_id,user_id','tbl_app_expert_cherryboard','expertboard_id='.$expertBoardId.' and main_board="1"');		
		    $cherryboard_id=$expertDetails[0];	 
		    $user_id=$expertDetails[1];
		    $userDetail=getUserDetail($user_id);
		    $userOwnerFbId=$userDetail['fb_id'];
		    $userName=$userDetail['name'];
		    $expertPicPath='https://graph.facebook.com/'.$userOwnerFbId.'/picture?type=large';
		    $expertOwnerPic.='<div class="div_content"><div class="div_img">
			<a href="expert_cherryboard.php?cbid='.$cherryboard_id.'" class="cleanLink">
	 <img src="'.$expertPicPath.'" class="div_img_small" height="150px" width="150px" title="'.$userName.'"/>
		    </a></div>';
		    $selExpPic=mysql_query("select photo_name,photo_day from tbl_app_expert_cherry_photo where cherryboard_id=".$cherryboard_id." order by photo_id");
		    while($selExpPicRow=mysql_fetch_array($selExpPic)){
				$photo_name=$selExpPicRow['photo_name'];
				$photo_day=(int)$selExpPicRow['photo_day'];
				$photoPath='images/expertboard/'.$photo_name;
				$expertOwnerPic.='<div style="width:150px;float:left;padding-left:7px;">';
				$expertOwnerPic.='<span style="padding-left:60px;width:150px;font-size:14px;"><strong>'.$DayType.' '.$photo_day.'</strong></span>';
			    $expertOwnerPic.='<a href="expert_cherryboard.php?cbid='.$cherryboard_id.'" class="cleanLink">
				<img src="'.$photoPath.'" class="div_img_small" height="150px" width="150px" /></a>';
				$expertOwnerPic.='</div>';
		    }
	        $expertOwnerPic.='<div/>';
	   }
	}
	$ajax_data=$type."##===##".$div_name."##===##".$ajax_data."##===##".$expertGreat."##===##".$expertOwnerPic;
	echo $ajax_data;
}
//EDIT EXPERT CUSTOMERS
if($type=="edit_exp_customer"){
	$stype=$_GET['stype'];
	$expertboard_id=$_GET['expertboard_id'];
	$user_id=$_GET['user_id'];
	$cherryboard_id=$_GET['cbid'];
	$fieldname=$_GET['fieldname'];
	$tblName='tbl_app_expertboard';
	
	if($stype=="eadd"){
		$customers=getFieldValue($fieldname,$tblName,'expertboard_id='.$expertboard_id);
		$ajax_data.='<textarea onmouseout="ajax_action(\''.$type.'\',\''.$div_name.'\',\'stype=esave&fieldname='.$fieldname.'&expertboard_id='.$expertboard_id.'&user_id='.$user_id.'&cbid='.$cherryboard_id.'&customers=\'+this.value)" id="edit_customers'.$expertboard_id.'" class="input_comments" name="edit_customers'.$expertboard_id.'" style="height:20px;">'.$customers.'</textarea>';
	}
	if($stype=="esave"){
		$customers=$_GET['customers'];
		$ownerUserId=getFieldValue('user_id',$tblName,'expertboard_id='.$expertboard_id);
		$editExpGoalDays=mysql_query("UPDATE ".$tblName." SET ".$fieldname."='".$customers."' WHERE expertboard_id=".$expertboard_id);
		$ajax_data.='<a href="expert_customer.php?cbid='.$cherryboard_id.'" class="cleanLink">
		'.ucwords($customers).'</a>
		'.($user_id==$ownerUserId?'<img src="images/edit.png" height="10px" style="cursor:pointer" 
		ondblclick="ajax_action(\'edit_exp_customer\',\'div_exp_customer_'.$expertboard_id.'\',\'stype=eadd&fieldname=customers&expertboard_id='.$expertboard_id.'&user_id='.$user_id.'&cbid='.$cherryboard_id.'\')" width="10px" title="Edit '.ucwords($customers).'" />':'').'';
	}
	$ajax_data=$type."##===##".$div_name."##===##".$ajax_data;
	echo $ajax_data;
}
//EDIT EXPERT REWARD PHOTO
/*if($type=="edit_exp_reward_pic"){
	 $expRewardId=(int)$_GET['expRewardId'];
	 $photo_name=trim(getFieldValue('photo_name','tbl_app_expert_reward_photo','exp_reward_id='.$expRewardId));
	 $photo_path='images/expertboard/reward/'.$photo_name;
	  
	 $ajax_data.='<img src="'.$photo_path.'" height="50" width="50" />
	 			<form action="" method="post" name="frmexprwd" enctype="multipart/form-data">
	 			<input type="file" name="edit_exp_reward_pic" id="edit_exp_reward_pic" size="12px" />
				<input type="hidden" id="save_expRewardId" name="save_expRewardId" value="'.$expRewardId.'"/>
	    <input type="submit" class="btn_small right" id="saveExpEditPic" name="saveExpEditPic" value="Save"/>  				                </form>';
	 $ajax_data=$type."##===##".$div_name."##===##".$ajax_data;
	 echo $ajax_data;  
}*/
//EDIT EXPERT REWARD TITLE
if($type=="edit_exp_reward_title"){
	$expRewardId=$_GET['expRewardId'];
	$stype=$_GET['stype'];
	$user_id=$_GET['user_id'];
	$cherryboard_id=$_GET['cherryboard_id'];
	$tblName='tbl_app_expert_reward_photo';
	
	if($stype=="eadd"){
		$photo_title=getFieldValue('photo_title',$tblName,'exp_reward_id='.$expRewardId);
		$ajax_data.='<textarea onblur="ajax_action(\''.$type.'\',\''.$div_name.'\',\'stype=esave&expRewardId='.$expRewardId.'&user_id='.$user_id.'&cherryboard_id='.$cherryboard_id.'&photo_title=\'+this.value)" id="exprewardtitle'.$expRewardId.'" class="input_comments" name="exprewardtitle'.$expRewardId.'" style="height:20px;">'.$photo_title.'</textarea>';
	}
	if($stype=="esave"){
		$photo_title=$_GET['photo_title'];
		$expertboard_id=getFieldValue('expertboard_id','tbl_app_expert_cherryboard','cherryboard_id='.$cherryboard_id);
    	$expOwner_id=getFieldValue('user_id','tbl_app_expertboard','expertboard_id='.$expertboard_id);		
		$editExpRewardTitle=mysql_query("UPDATE ".$tblName." SET photo_title='".$photo_title."' WHERE exp_reward_id=".$expRewardId);
		$ajax_data.='<a href="javascript:void(0);" '.($user_id==$expOwner_id?'ondblclick="ajax_action(\''.$type.'\',\''.$div_name.'\',\'stype=eadd&cherryboard_id='.$cherryboard_id.'&expRewardId='.$expRewardId.'&user_id='.$user_id.'\')"':'').' title="Edit Reward Title" class="cleanLink">'.ucwords($photo_title).'</a>';
	}
	$ajax_data=$type."##===##".$div_name."##===##".$ajax_data;
	echo $ajax_data;
}
//DELETE EXPERT REWARD CODE
if($type=="del_exp_reward"){
	$expRewardId=(int)$_GET['expRewardId'];	
	$cherryboard_id=(int)$_GET['cherryboard_id'];
	$user_id=(int)$_GET['user_id'];
	
	if($expRewardId>0&&$cherryboard_id>0&&$user_id>0){
	$expertboard_id=getFieldValue('expertboard_id','tbl_app_expert_cherryboard','cherryboard_id='.$cherryboard_id);
    $expOwner_id=getFieldValue('user_id','tbl_app_expertboard','expertboard_id='.$expertboard_id);	
	$photo_name=trim(getFieldValue('photo_name','tbl_app_expert_reward_photo','exp_reward_id='.$expRewardId));	
	$photo_path='images/expertboard/reward/'.$photo_name;
	$del_reward=mysql_query("DELETE FROM tbl_app_expert_reward_photo WHERE exp_reward_id=".$expRewardId."");
		if($del_reward){
		  if(unlink($photo_path)){
			$ajax_data.=getExpertReward($cherryboard_id);	
		  }
		}	
	}
	$ajax_data=$type."##===##".$div_name."##===##".$ajax_data;
	echo $ajax_data;
}
//VIEW MORE EXPERT PART CODE
if($type=="get_more_expert"){
	$expertboard_id=(int)$_GET['expertboard_id'];		
	if($expertboard_id>0&&$_SESSION['USER_ID']>0){
		$tblName='tbl_app_expertboard';	
  		$expertboard_detail=stripslashes(trim(getFieldValue('expertboard_detail',$tblName,'expertboard_id='.$expertboard_id)));
		$ownerUserId=getFieldValue('user_id',$tblName,'expertboard_id='.$expertboard_id);
	    $ajax_data.=''.($expertboard_detail!=''?''.($ownerUserId==$_SESSION['USER_ID']?'<a href="javascript:void(0);" ondblclick="ajax_action(\'edt_exp_detail\',\''.$div_name.'\',\'stype=add&fieldname=expertboard_detail&expertboard_id='.$expertboard_id.'&user_id='.$_SESSION['USER_ID'].'\')" title="Edit Detail" class="cleanLink">'.$expertboard_detail.'</a>':''.$expertboard_detail.'').'':'No expert details').'';
	}
	$ajax_data=$type."##===##".$div_name."##===##".$ajax_data;
	echo $ajax_data;
}
//UPDATE EXPERT DETAILS
if($type=="edt_exp_detail"){
	$stype=$_GET['stype'];
	$expertboard_id=$_GET['expertboard_id'];
	$user_id=$_GET['user_id'];
	$fieldname=$_GET['fieldname'];
	$tblName='tbl_app_expertboard';
	
	if($stype=="add"){
		$detail=stripslashes(trim(getFieldValue($fieldname,$tblName,'expertboard_id='.$expertboard_id)));
		$ajax_data.='<textarea onmouseout="ajax_action(\''.$type.'\',\''.$div_name.'\',\'stype=save&fieldname='.$fieldname.'&expertboard_id='.$expertboard_id.'&user_id='.$user_id.'&detail=\'+this.value)" id="edit_detail_text'.$expertboard_id.'" class="input_comments" name="edit_detail_text'.$expertboard_id.'" style="height:35px;width:300px;">'.$detail.'</textarea>';
	}
	if($stype=="save"){
		$expertboard_id=$_GET['expertboard_id'];
		$detail=$_GET['detail'];
		$user_id=$_GET['user_id'];
		$fieldname=$_GET['fieldname'];
		$ownerUserId=getFieldValue('user_id',$tblName,'expertboard_id='.$expertboard_id);
		$editExpGoalDays=mysql_query("UPDATE ".$tblName." SET ".$fieldname."='".addslashes($detail)."' WHERE expertboard_id=".$expertboard_id);
		$expert_detail='';
		if(strlen($detail)>100){
			$expert_detail=''.substr($detail,0,100).'...<a href="javascript:void(0);" style="text-decoration:none;color:#990000" onclick="ajax_action(\'get_more_expert\',\'div_more_expert_'.$expertboard_id.'\',\'expertboard_id='.$expertboard_id.'\')">More</a>';
		}else{
			$expert_detail=$detail;
		}
		$ajax_data.=''.($user_id==$ownerUserId?'<a href="javascript:void(0);" ondblclick="ajax_action(\''.$type.'\',\''.$div_name.'\',\'stype=add&fieldname='.$fieldname.'&expertboard_id='.$expertboard_id.'&user_id='.$user_id.'\')" title="Edit Detail" class="cleanLink">'.$expert_detail.'</a>':''.$expert_detail.'').'';
	}
	$ajax_data=$type."##===##".$div_name."##===##".$ajax_data;
	echo $ajax_data;
}
//UPDATE EXPERT TITLE
if($type=="edt_exp_title"){
	$stype=$_GET['stype'];
	$expertboard_id=$_GET['expertboard_id'];
	$user_id=$_GET['user_id'];
	$fieldname=$_GET['fieldname'];
	$tblName='tbl_app_expertboard';
	
	if($stype=="add"){
		$title=getFieldValue($fieldname,$tblName,'expertboard_id='.$expertboard_id);
		$ajax_data.='<textarea onmouseout="ajax_action(\''.$type.'\',\''.$div_name.'\',\'stype=save&fieldname='.$fieldname.'&expertboard_id='.$expertboard_id.'&user_id='.$user_id.'&title=\'+this.value)" id="edit_title_text'.$expertboard_id.'" class="input_comments" name="edit_title_text'.$expertboard_id.'" style="height:20px;width:300px;">'.$title.'</textarea>';
	}
	if($stype=="save"){
		$expertboard_id=$_GET['expertboard_id'];
		$title=$_GET['title'];
		$user_id=$_GET['user_id'];
		$fieldname=$_GET['fieldname'];
		$ownerUserId=getFieldValue('user_id',$tblName,'expertboard_id='.$expertboard_id);
		$editExpGoalDays=mysql_query("UPDATE ".$tblName." SET ".$fieldname."='".$title."' WHERE expertboard_id=".$expertboard_id);
		$ajax_data.='<a href="javascript:void(0);" '.($user_id==$ownerUserId?'ondblclick="ajax_action(\''.$type.'\',\''.$div_name.'\',\'stype=add&fieldname='.$fieldname.'&expertboard_id='.$expertboard_id.'&user_id='.$user_id.'\')"':'').' title="Edit Title" class="cleanLink">'.ucwords($title).'</a>';
	}
	$ajax_data=$type."##===##".$div_name."##===##".$ajax_data;
	echo $ajax_data;
}
//UPDATE EXPERT PRICE
if($type=="edt_exp_price"){
	$stype=$_GET['stype'];
	$expertboard_id=$_GET['expertboard_id'];
	$user_id=$_GET['user_id'];
	$fieldname=$_GET['fieldname'];
	$tblName='tbl_app_expertboard';
	
	if($stype=="add"){
		$price=getFieldValue($fieldname,$tblName,'expertboard_id='.$expertboard_id);
		$ajax_data.='<textarea onmouseout="ajax_action(\''.$type.'\',\''.$div_name.'\',\'stype=save&fieldname='.$fieldname.'&expertboard_id='.$expertboard_id.'&user_id='.$user_id.'&price=\'+this.value)" id="edit_price_text'.$expertboard_id.'" class="input_comments" name="edit_price_text'.$expertboard_id.'" style="height:25px;width:100px;">'.$price.'</textarea>';
	}
	if($stype=="save"){
		$expertboard_id=$_GET['expertboard_id'];
		$price=$_GET['price'];
		$user_id=$_GET['user_id'];
		$fieldname=$_GET['fieldname'];
		$ownerUserId=getFieldValue('user_id',$tblName,'expertboard_id='.$expertboard_id);
		$editExpGoalDays=mysql_query("UPDATE ".$tblName." SET ".$fieldname."='".$price."' WHERE expertboard_id=".$expertboard_id);
		$ajax_data.='Price : <a href="javascript:void(0);" '.($user_id==$ownerUserId?'ondblclick="ajax_action(\''.$type.'\',\''.$div_name.'\',\'stype=add&fieldname='.$fieldname.'&expertboard_id='.$expertboard_id.'&user_id='.$user_id.'\')"':'').' title="Edit Price" class="cleanLink"><span class="style4">$'.$price.'</span></a>'; 
	}
	$ajax_data=$type."##===##".$div_name."##===##".$ajax_data;
	echo $ajax_data;
}
//UPDATE EXPERT GOAL DAY
if($type=="edt_exp_goal_day"){
	$stype=$_GET['stype'];
	$expertboard_id=$_GET['expertboard_id'];
	$user_id=$_GET['user_id'];
	$tblName='tbl_app_expertboard';
	$cherryboard_id=0;
	
	if($stype=="add"){
		$goal_days=getFieldValue('goal_days',$tblName,'expertboard_id='.$expertboard_id);
		$ajax_data.='<textarea onmouseout="ajax_action(\'edt_exp_goal_day\',\''.$div_name.'\',\'stype=esave&expertboard_id='.$expertboard_id.'&user_id='.$user_id.'&goal_text=\'+this.value)" id="edit_goal_text'.$expertboard_id.'" class="input_comments" name="edit_goal_text'.$expertboard_id.'" style="height:25px;width:50px;">'.$goal_days.'</textarea>';
	}
	if($stype=="esave"){
		$expertboard_id=(int)$_GET['expertboard_id'];
		$number_days=(int)$_GET['goal_text'];
		$user_id=(int)$_GET['user_id'];
		$ownerUserId=getFieldValue('user_id',$tblName,'expertboard_id='.$expertboard_id);
		$cherryboard_id=getFieldValue('cherryboard_id','tbl_app_expert_cherryboard','expertboard_id='.$expertboard_id.' and user_id='.$user_id);
		$editExpGoalDays=mysql_query("UPDATE ".$tblName." SET goal_days='".$number_days."' WHERE expertboard_id=".$expertboard_id);
		$dayType=getDayType($expertboard_id);
		
		//update goal days with title
		 $totalConfigDays=getFieldValue('count(expertboard_day_id)','tbl_app_expertboard_days','expertboard_id='.$expertboard_id);
		 if($totalConfigDays!=$number_days){
			//when increase days in goal days
			if($number_days>$totalConfigDays){
				for($i=($totalConfigDays+1);$i<=$number_days;$i++){
					$addDays="INSERT INTO `tbl_app_expertboard_days` (`expertboard_day_id`, `expertboard_id`, `day_no`, `day_title`, `record_date`) VALUES (NULL, '".$expertboard_id."', '".$i."', '".$dayType." ".$i."', CURRENT_TIMESTAMP)";
					$addDaysSql=mysql_query($addDays);
				}
			}
			//when decrease days in goal days
			if($number_days<$totalConfigDays){
				for($i=($number_days+1);$i<=$totalConfigDays;$i++){
					
					$delDays="delete from `tbl_app_expertboard_days` where day_no=".$i." and expertboard_id=".$expertboard_id;
					$delDaysSql=mysql_query($delDays);
				}
			}			
		 }	
		 	
		$ajax_data.='Total :<a href="javascript:void(0);" '.($user_id==$ownerUserId?'ondblclick="ajax_action(\'edt_exp_goal_day\',\''.$div_name.'\',\'stype=add&expertboard_id='.$expertboard_id.'&user_id='.$user_id.'\')"':'').' title="Edit Day" class="cleanLink"> <span class="style3"> '.$number_days.' '.$dayType.'s</span></a>';
	}
	$ajax_data=$type."##===##".$div_name."##===##".$ajax_data."##===##".$stype."##===##".$cherryboard_id;
	echo $ajax_data;
}
//UPDATE EXPERT TODO LIST ITEM
if($type=="edit_todo_list"){
	$stype=$_GET['stype'];
	$checklist_id=$_GET['checklist_id'];
	$user_id=$_GET['user_id'];
	$tblName='tbl_app_expert_checklist';
	
	if($stype=="add"){
		$checklistItem=getFieldValue('checklist',$tblName,'checklist_id='.$checklist_id);
		$toDoList=str_replace('w.','w.<br/>',$checklistItem);
		$toDoListItem=wordwrap($toDoList,30,"<br/>",TRUE);
		$ajax_data.='<textarea onmouseout="ajax_action(\'edit_todo_list\',\'div_todo_list_'.$checklist_id.'\',\'stype=save&checklist_id='.$checklist_id.'&user_id='.$user_id.'&todo_list=\'+this.value)" id="edit_todo_text'.$checklist_id.'" class="input_comments" name="edit_todo_text'.$checklist_id.'" style="height:70px;">'.$toDoListItem.'</textarea>';
	}
	if($stype=="save"){
		$checklist_id=$_GET['checklist_id'];
		$toDoList=parseString($_GET['todo_list']);
		$user_id=$_GET['user_id'];
		$checkListItem=str_replace('w.','w.<br/>',$toDoList);
		$checkList=wordwrap($checkListItem,30,"<br/>",TRUE);
		$ownerUserId=getFieldValue('user_id',$tblName,'checklist_id='.$checklist_id);
		$editExpTODoList=mysql_query("UPDATE ".$tblName." SET checklist='".$toDoList."' WHERE checklist_id=".$checklist_id);
		$ajax_data.='<a href="javascript:void(0);" '.($user_id==$ownerUserId?'ondblclick="ajax_action(\'edit_todo_list\',\'div_todo_list_'.$checklist_id.'\',\'stype=add&checklist_id='.$checklist_id.'&user_id='.$user_id.'\')"':'').' title="Edit To-Do List"  class="cleanLink">'.$checkList.'</a>'; 
	}
	$ajax_data=$type."##===##".$div_name."##===##".$ajax_data;
	echo $ajax_data;
}
//UPDATE EXPERT PHOTO DAY
if($type=="edt_exp_photo_day"){
	$stype=$_GET['stype'];
	$photo_day=$_GET['photo_day'];
	$user_id=$_GET['user_id'];
	$expertboard_id=$_GET['expertboard_id'];
	$tblName='tbl_app_expertboard_days';
	$sub_day=$_GET['sub_day'];
	
	if($stype=="add"){
	  $dayTitle=getFieldValue('day_title',$tblName,'expertboard_id='.$expertboard_id.' and day_no='.$photo_day.' and sub_day='.$sub_day);
	  $ajax_data.='<textarea onmouseout="ajax_action(\'edt_exp_photo_day\',\'div_photo_day'.$photo_day.'_'.$sub_day.'\',\'stype=save&photo_day='.$photo_day.'&sub_day='.$sub_day.'&expertboard_id='.$expertboard_id.'&user_id='.$user_id.'&edt_day=\'+this.value)" id="edt_exp_text'.$photo_day.'_'.$sub_day.'" class="input_comments" name="edt_exp_text'.$photo_day.'_'.$sub_day.'" style="height:25px;color:#FFFFFF;">'.$dayTitle.'</textarea>';		
	}
	if($stype=="save"){
		$photo_day=$_GET['photo_day'];
		$sub_day=$_GET['sub_day'];
		$expertboard_id=$_GET['expertboard_id'];
		$editTitle=parseString($_GET['edt_day']);
		$user_id=$_GET['user_id'];
		$ownerUserId=getFieldValue('user_id','tbl_app_expertboard','expertboard_id='.$expertboard_id);
		$editExpTitle=mysql_query("UPDATE ".$tblName." SET day_title='".$editTitle."' WHERE day_no=".$photo_day." and sub_day=".$sub_day." AND expertboard_id=".$expertboard_id);
		$ajax_data.='<div id="div_photo_day'.$photo_day.'_'.$sub_day.'"><div class="score">
					 <a href="javascript:void(0);" '.($user_id==$ownerUserId?'ondblclick="ajax_action(\'edt_exp_photo_day\',\'div_photo_day'.$photo_day.'_'.$sub_day.'\',\'stype=add&photo_day='.$photo_day.'&sub_day='.$sub_day.'&expertboard_id='.$expertboard_id.'&user_id='.$user_id.'\')"':'').' title="Edit Day Title" style="text-decoration:none;color:#FFFFFF;">&nbsp;'.$editTitle.'&nbsp;</a></div></div>';
	}
	$ajax_data=$type."##===##".$div_name."##===##".$ajax_data;
	echo $ajax_data;
}
//ADD EXPERT QUESTION/ANSWER
if($type=="ask_expert_question"||$type=="cherry_answer"||$type=="del_expert_question"||$type=="del_expert_answer"){
	$cherryboard_id=(int)$_GET['cherryboard_id'];
	$photo_id=(int)$_GET['photo_id'];
	$user_id=(int)$_GET['user_id'];
	$cherry_question=trim(addslashes($_GET['question']));
	$cherry_answer=trim(addslashes($_GET['answer']));
	$tbl_name='tbl_app_expert_question_answer';
	$photo_day=(int)$_GET['photo_day'];
		
		$expertboard_id=getFieldValue('expertboard_id','tbl_app_expert_cherryboard','cherryboard_id='.$cherryboard_id);
		$main_board=getFieldValue('cherryboard_id','tbl_app_expert_cherryboard','expertboard_id='.$expertboard_id.' and main_board="1"');
		$expUserId=getFieldValue('user_id','tbl_app_expert_cherryboard','cherryboard_id='.$cherryboard_id);
		$expOwner_id=getFieldValue('user_id','tbl_app_expertboard','expertboard_id='.$expertboard_id);
		$expOwnerDetail=getUserDetail($expOwner_id,'uid');
		$expOwner_Name=$expOwnerDetail['name'];
		$expOwner_EmailId=$expOwnerDetail['email_id'];
	
	
		//ADD ASK QUESTION	
		if($type=="ask_expert_question"&&$cherry_question!="Ask a question"&&$photo_id>0){
			$ins_query="INSERT INTO `tbl_app_expert_question_answer` (`question_id`, `cherryboard_id`, `photo_id`, `user_id`, `cherry_question`, `cherry_answer`, `photo_day`, `record_date`) VALUES (NULL, '".$cherryboard_id."', '".$photo_id."', '".$user_id."', '".$cherry_question."','', '".$photo_day."', CURRENT_TIMESTAMP)";
			$ins_sql=mysql_query($ins_query);
			//send mail to owner of the expertboard
			$ExpboardTitle=getFieldValue('expertboard_title','tbl_app_expertboard','expertboard_id='.$expertboard_id);
			$UserDetail=getUserDetail($user_id,'uid');
			$UserName=$UserDetail['name'];
			$to = $expOwner_EmailId;
			$subject = 'Question from '.$UserName;
			$message = '<table>
						<tr><td>&nbsp;</td></tr>
						<tr><td>Dear '.$expOwner_Name.', your '.$ExpboardTitle.' customer, '.$UserName.' asked you the following question.</td></tr>
						<tr><td><strong>Question:</strong>&nbsp;'.$cherry_question.'</td></tr>
						<tr><td>&nbsp;</td></tr>
						<tr><td><a href="'.SITE_URL.'/expert_cherryboard.php?cbid='.$main_board.'"><strong>Click here</strong></a> to answer his question.</td></tr>
						<tr><td>&nbsp;</td></tr>
						<tr><td>Thanks,</td></tr>
						<tr><td>'.REGARDS.'</td></tr>
						</table>';
			SendMail($to,$subject,$message);
		}
		//ADD QUESTION ANSWER 
		if($type=="cherry_answer"&&$cherry_answer!="Add a Answer"){
			$question_id=(int)$_GET['question_id'];
			$ins_query="update ".$tbl_name." set `cherry_answer`='".$cherry_answer."' where question_id=".$question_id;
		    $ins_sql=mysql_query($ins_query);
			//SEND MAIL TO OWNER OF THE ASK QUESTION
		   $questionOwner=getFieldValue('user_id','tbl_app_expert_question_answer','question_id='.$question_id);
		   $cherry_question=getFieldValue('cherry_question','tbl_app_expert_question_answer','question_id='.$question_id);
			$UserDetail=getUserDetail($questionOwner,'uid');
			$UserName=$UserDetail['name'];
			$emailId=$UserDetail['email_id'];
			$to = $emailId;
			$subject = $expOwner_Name.' answered your question';
			$message = '<table>
						<tr><td>&nbsp;</td></tr>
						<tr><td>Dear '.$UserName.',</td></tr>
					    <tr><td>Your question "'.$cherry_question.'" is answered by '.$expOwner_Name.'.
						</td></tr>
						<tr><td>&nbsp;</td></tr>
						<tr><td><a href="'.SITE_URL.'/expert_cherryboard.php?cbid='.$cherryboard_id.'"><strong>Click here</strong></a> to see the answer.</td></tr>
						<tr><td>&nbsp;</td></tr>
						<tr><td>Love,</td></tr>
						<tr><td>'.REGARDS.'</td></tr>
						</table>';
			SendMail($to,$subject,$message);
		}
		if($type=="del_expert_answer"){
			$question_id=(int)$_GET['question_id'];
			$del_query="update ".$tbl_name." set `cherry_answer`='' where question_id=".$question_id;
		    $del_sql=mysql_query($del_query);
		}
		if($type=="del_expert_question"){
			$question_id=(int)$_GET['question_id'];
			$del_query="DELETE FROM ".$tbl_name." WHERE question_id=".$question_id;
			$del_sql=mysql_query($del_query);
		}
	//QUESTION/ANSWER SECTION
	$TotalQue=(int)getFieldValue('count(photo_id)','tbl_app_expert_question_answer','photo_id='.$photo_id);
	$questionCnt=$TotalQue.' questions';
	$photoCnt.=expert_question_section($cherryboard_id,$photo_id,$photo_day);	
	$ajax_data.=$photoCnt;	
	$ajax_data=$type."##===##".$div_name."##===##".$ajax_data."##===##".$questionCnt;
	echo $ajax_data;
	exit(0);
}
//Swap images
if($type=="swap_image"){
	$img_sort=$_GET['img_sort'];
    $imgswap_from=explode('_',$_GET['imgswap_from']);
	$imgswap_to=explode('_',$_GET['imgswap_to']);
	
	$from_photo_day=$imgswap_from[0];
	$from_photo_id=$imgswap_from[1];
	$from_sub_day=$imgswap_from[2];
	
	
	$to_photo_day=$imgswap_to[0];
	$to_photo_id=$imgswap_to[1];	
	$to_sub_day=$imgswap_to[2];
	
	
	$swap_frm_div='photo_title';
	$swap_frm_title='';
	$swap_to_div='photo_title';
	$swap_to_title='';
	
	//FROM DETAIL
	$from_photoDetail=getFieldsValueArray('cherryboard_id,photo_name','tbl_app_expert_cherry_photo','photo_id='.$from_photo_id);
	$cherryboard_id=$from_photoDetail[0];
	$from_photo_name=$from_photoDetail[1];
	
	
	$expertboard_id=getFieldValue('expertboard_id','tbl_app_expert_cherryboard','cherryboard_id='.$cherryboard_id);
	$DayType=getDayType($expertboard_id);
	//GET FROM PHOTO DAY TITLE
	$fromDaysArray=getFieldsValueArray('expertboard_day_id,day_title','tbl_app_expertboard_days','expertboard_id='.$expertboard_id.' and day_no='.$from_photo_day.' and sub_day='.$from_sub_day);
	$from_expertboard_day_id=$fromDaysArray[0];
	$from_day_title=$fromDaysArray[1];
	//GET TO PHOTO DAY TITLE
	$toDaysArray=getFieldsValueArray('expertboard_day_id,day_title','tbl_app_expertboard_days','expertboard_id='.$expertboard_id.' and day_no='.$to_photo_day.' and sub_day='.$to_sub_day);
	$to_expertboard_day_id=$toDaysArray[0];
	$to_day_title=$toDaysArray[1];
	
	if($from_photo_id>0&&$to_photo_id==0){
		//UPDATE FRom PHOTO DAY
		$swap_type='new_swaped';
		$updtCherry=mysql_query("UPDATE tbl_app_expert_cherry_photo SET photo_day=".$to_photo_day." WHERE photo_id=".$from_photo_id);
		//UPDATE TO PHOTO DAY
		$insQry="INSERT INTO `tbl_app_expert_cherry_photo` (`photo_id`, `user_id`, `cherryboard_id`, `photo_title`, `photo_name`, `photo_day`, `record_date`, `sub_day`) VALUES (NULL, '".$_SESSION['USER_ID']."', '".$cherryboard_id."', '".$from_day_title."', '".$from_photo_name."', '".$to_photo_day."', CURRENT_TIMESTAMP, '".$to_sub_day."')";
		mysql_query($insQry);
		
		
		if($updtCherry){
			//UPDATE QUESTION AND ANSWER SECTION
			$checkDay=getFieldValue('question_id','tbl_app_expert_question_answer','photo_day='.$from_photo_day.' and photo_id='.$from_photo_id);
			if($checkDay>0){
				$updtQue=mysql_query("UPDATE tbl_app_expert_question_answer SET photo_day=".$to_photo_day." WHERE question_id=".$checkDay);
			}
			//UPDATE FROM DAY TITLE SECTION
			$toDayTitle=$DayType.' '.$to_photo_day;
			$fromDayTitle=$DayType.' '.$from_photo_day;
			$to_day_title=str_replace($toDayTitle,$fromDayTitle,$to_day_title);
			
			$updtFromDayTitle=mysql_query("UPDATE tbl_app_expertboard_days SET day_title='".$to_day_title."' WHERE expertboard_day_id=".$from_expertboard_day_id);
			//UPDATE TO DAY TITLE SECTION
			$toDayTitle=$DayType.' '.$to_photo_day;
			$fromDayTitle=$DayType.' '.$from_photo_day;
			$from_day_title=str_replace($fromDayTitle,$toDayTitle,$from_day_title);
			$updtToDayTitle=mysql_query("UPDATE tbl_app_expertboard_days SET day_title='".$from_day_title."' WHERE expertboard_day_id=".$to_expertboard_day_id);
			
			$ImgDetailArray=getFieldsValueArray('cherryboard_id,photo_title','tbl_app_expert_cherry_photo','photo_id='.$from_photo_id);
			$cherryboard_id=$ImgDetailArray[0];
			$swap_frm_div='photo_title'.$from_photo_id;
			$swap_frm_title='No Photo';
			$swap_to_div='photo_title'.$to_photo_day;
			$swap_to_title=$ImgDetailArray[1];
		}				
	}else if($from_photo_id>0&&$to_photo_id>0){
		$swap_type='new_swaped';
		if($from_photo_id>0){
			$upddelFromPhoto=mysql_query("UPDATE tbl_app_expert_cherry_photo SET photo_day=".$to_photo_day.",sub_day=".$to_sub_day." WHERE photo_id=".$from_photo_id);
			if($upddelFromPhoto){
				//UPDATE QUESTION AND ANSWER SECTION
				$checkDay=getFieldValue('question_id','tbl_app_expert_question_answer','photo_day='.$from_photo_day.' and photo_id='.$from_photo_id);
				if($checkDay>0){
					$updtQue=mysql_query("UPDATE tbl_app_expert_question_answer SET photo_day=".$to_photo_day." WHERE question_id=".$checkDay);
				}
				//UPDATE FROM DAY TITLE SECTION 
				$toDayTitle=$DayType.' '.$to_photo_day;
				$fromDayTitle=$DayType.' '.$from_photo_day;
				$to_day_title=str_replace($toDayTitle,$fromDayTitle,$to_day_title);
				$to_day_title=str_replace($toDayTitle.'.'.$to_sub_day,$fromDayTitle.'.'.$from_sub_day,$to_day_title);
				$updtFromDayTitle=mysql_query("UPDATE tbl_app_expertboard_days SET day_title='".$to_day_title."' WHERE expertboard_day_id=".$from_expertboard_day_id);	
							
				$fromImgDetailArray=getFieldsValueArray('cherryboard_id,photo_title','tbl_app_expert_cherry_photo','photo_id='.$from_photo_id);
				$cherryboard_id=$fromImgDetailArray[0];
				$swap_to_div='photo_title'.$from_photo_id;
				$swap_to_title=$fromImgDetailArray[1].'&nbsp;';								
			}	
		}	
		if($to_photo_id>0){
			$upddelFromToPhoto=mysql_query("UPDATE tbl_app_expert_cherry_photo SET photo_day=".$from_photo_day." ,sub_day=".$from_sub_day." WHERE photo_id=".$to_photo_id);
			if($upddelFromToPhoto){
				//UPDATE QUESTION AND ANSWER SECTION
				$checkDay=getFieldValue('question_id','tbl_app_expert_question_answer','photo_day='.$to_photo_day.' and photo_id='.$to_photo_id);
				if($checkDay>0){
					$updtQue=mysql_query("UPDATE tbl_app_expert_question_answer SET photo_day=".$from_photo_id." WHERE question_id=".$checkDay);
				}
				//UPDATE TO DAY TITLE SECTION
				$toDayTitle=$DayType.' '.$to_photo_day;
				$fromDayTitle=$DayType.' '.$from_photo_day;
				$from_day_title=str_replace($fromDayTitle,$toDayTitle,$from_day_title);
				$from_day_title=str_replace($fromDayTitle.'.'.$from_sub_day,$toDayTitle.'.'.$to_sub_day,$from_day_title);
				$updtToDayTitle=mysql_query("UPDATE tbl_app_expertboard_days SET day_title='".$from_day_title."' WHERE expertboard_day_id=".$to_expertboard_day_id);	
				
				$toImgDetailArray=getFieldsValueArray('cherryboard_id,photo_title','tbl_app_expert_cherry_photo','photo_id='.$to_photo_id);
				$cherryboard_id=$toImgDetailArray[0];
				$swap_frm_div='photo_title'.$to_photo_id;
				$swap_frm_title=$toImgDetailArray[1].'&nbsp;';
			}	
		}
	}
	$ajax_data=$type."##===##".$div_name."##===##0##===##".$swap_type."##===##".$swap_frm_div."##===##".$swap_frm_title."##===##".$swap_to_div."##===##".$swap_to_title."##===##".$cherryboard_id."##===##".$img_sort;
	echo $ajax_data;
}
//update expert photo title
if($type=="upd_photo_title"){
	$stype=$_GET['stype'];
	$photo_id=$_GET['photo_id'];
	$user_id=$_GET['user_id'];
	$tpadd='add';
	$tpsave='save';
	if($stype=="eadd"||$stype=="esave"){
		$tblName='tbl_app_expert_cherry_photo';
		$tpadd='eadd';
		$tpsave='esave';
	}else{
		$tblName='tbl_app_cherry_photo';
	}
	if($stype=="add"||$stype=="eadd"){
		$photoTitle=getFieldValue('photo_title',$tblName,'photo_id='.$photo_id);
		$ajax_data.='<textarea onmouseout="ajax_action(\'upd_photo_title\',\'photo_title'.$photo_id.'\',\'stype='.$tpsave.'&photo_id='.$photo_id.'&user_id='.$user_id.'&upd_title=\'+this.value)" id="cherry_comment'.$photo_id.'" class="input_comments" name="cherry_comment'.$photo_id.'" style="height:30px;color:#FFFFFF;">'.$photoTitle.'</textarea>';
		$ajax_data=$type."##===##".$div_name."##===##".$ajax_data;
	}
	if($stype=="save"||$stype=="esave"){
		$photo_id=$_GET['photo_id'];
		$user_id=$_GET['user_id'];
		$updateTitle=parseString($_GET['upd_title']);
		$ownerUserId=getFieldValue('user_id',$tblName,'photo_id='.$photo_id);
		$update_title=mysql_query("update ".$tblName." set photo_title='".$updateTitle."'  where photo_id=".$photo_id);
		$ajax_data.='<div class="comment_box1" id="photo_title'.$photo_id.'"><a href="javascript:void(0);" '.($user_id==$ownerUserId?'ondblclick="ajax_action(\'upd_photo_title\',\'photo_title'.$photo_id.'\',\'stype=eadd&photo_id='.$photo_id.'&user_id='.$user_id.'\')"':'').' title="Edit Comment" style="text-decoration:none;color:#FFFFFF;font-size:14px;">'.getLimitString($updateTitle,55).'</a></div><div class="clear"></div>';
		$ajax_data=$type."##===##".$div_name."##===##".$ajax_data;
	}
	echo $ajax_data;
}
//EXPERT CHECKIN MAIL
if($type=="exp_checkin_mail"){
	$cherryboard_id=$_GET['cherryboard_id'];
	$user_id=$_GET['user_id'];
	if($cherryboard_id>0&&$user_id>0){
			$UserDetail=getFieldsValueArray('email_id,first_name,last_name','tbl_app_users','user_id='.$user_id);
			$email_id=$UserDetail['email_id'];
			$first_name=$UserDetail['first_name'];
			$last_name=$UserDetail['last_name'];
			
			$cherryboard_title=getFieldValue('cherryboard_title','tbl_app_expert_cherryboard','cherryboard_id='.$cherryboard_id);
			$RemainDay=getExpertboardRemainDays($cherryboard_id);
			//mail to user
			$to      = $email_id;
			$subject = ucwords($cherryboard_title).' - Day '.(int)(30-$RemainDay).' Check In! ';
			$message = '<table>
						<tr><td>Hi '.ucwords($first_name.' '.$last_name).',</td></tr>
						<tr><td>&nbsp;</td></tr>
						<tr><td>Please check your expertboard <a href="'.SITE_URL.'/expert_cherryboard.php?cbid='.$cherryboard_id.'"><strong>'.ucwords($cherryboard_title).'</strong></a>.</td></tr>
						</table>';
			SendMail($email_id,$subject,$message);
			$msg_data='<span class="msg_green">Mail&nbsp;Sent.</span>';
	}
	$ajax_data=$type."##===##".$div_name."##===##".$msg_data;
	echo $ajax_data;
}
//BUY EXPERT BOARD
if($type=="buy_board"||$type=="buy_board_exp"){
	$msg_data='';
	$cherryboard_id=$_GET['cherryboard_id'];
	$user_id=$_GET['user_id'];
	if($cherryboard_id>0&&$user_id>0){
		$ins_sel="INSERT INTO `tbl_app_expert_buy` (`buy_id`, `user_id`, `cherryboard_id`, `buy_date`) VALUES (NULL, '".$user_id."', '".$cherryboard_id."', '".date('Y-m-d')."')";
		$ins_sql=mysql_query($ins_sel);
		$msg_data='<font class="msg_green">Thanks for buy.</font>';
	}else{
		$msg_data='<font class="msg_red">Invalid Request.</font>';
	}
		
	$ajax_data=$type."##===##".$div_name."##===##".$msg_data."##===##".$cherryboard_id;
	echo $ajax_data;
}

//ADD SETUP CATEGORY
if($type=="add_category"){
	$msg_data='';
	$category_name=$_GET['category_name'];
	if($category_name!="Enter category name"&&$category_name!=""){
		$ins_sel="INSERT INTO `tbl_app_category` (`category_id`, `category_name`, `record_date`) VALUES (NULL, '".$category_name."', '".date('Y-m-d')."')";
		$ins_sql=mysql_query($ins_sel);
		$msg_data='Success';
	}else{
		$msg_data='Invalid category name';
	}
		
	$ajax_data=$type."##===##".$div_name."##===##".$msg_data;
	echo $ajax_data;
}
//REFRESH INSPIR FEED
if($type=="refresh_inspir_feed"||$type=="refresh_expert_inspir_feed"){
	$cherryboard_id=$_GET['cherryboard_id'];
	$feed_data='';
	if($cherryboard_id>0){
		if($type=="refresh_inspir_feed"){
			$feed_data=UserFeedSection('cherryboard',$cherryboard_id);
		}else{
			$feed_data=UserFeedSection('expertboard',$cherryboard_id);
		}
		
	}	
	$ajax_data=$type."##===##".$div_name."##===##".$feed_data;
	echo $ajax_data;
}
//DELETE MONTHLY SPECIAL
if($type=="delete_goal_monthly_specials"){
	 $cherryboard_id=$_GET['cherryboard_id'];
	 $cherry_gift_id=$_GET['cherry_gift_id'];
	 if($cherry_gift_id>0){
	 	$mysql_delete=mysql_query("delete from tbl_app_cherry_gift where cherry_gift_id=".$cherry_gift_id);
	 }
	 	$cherrySel=mysql_query("select a.cherry_gift_id,b.gift_photo,b.gift_title from tbl_app_cherry_gift a,tbl_app_gift b where a.gift_id=b.gift_id and a.cherryboard_id=".$cherryboard_id." group by a.gift_id");
	$MonthSpeCnt='';
	if(mysql_num_rows($cherrySel)>0){
		while($cherryRow=mysql_fetch_array($cherrySel)){
			$cherry_gift_id=$cherryRow['cherry_gift_id'];
			$gift_photo=$cherryRow['gift_photo'];
			$gift_title=$cherryRow['gift_title'];
			$MonthSpeCnt.='<div class="small_thumb_container">
				<div class="img_big_container">
					<div class="feedbox_holder">
						<div class="actions">
						<a class="delete" href="#" onclick="ajax_action(\'delete_goal_monthly_specials\',\'div_goal_monthly_specials\',\'cherryboard_id='.$cherryboard_id.'&cherry_gift_id='.$cherry_gift_id.'\')" ><img src="images/delete.png"></a></div>
					</div>
					<img src="images/gift/'.$gift_photo.'" class="thumb" height="30" width="30" title="'.$gift_title.'">
				</div>
				</div>';
		}
	}else{
		$MonthSpeCnt.='<strong>No Monthly Specials</strong>';
	}
	$ajax_data=$type."##===##".$div_name."##===##".$MonthSpeCnt;
	echo $ajax_data;
}
//DELETE GOAL RECENT FRIENDS
if($type=="delete_goal_recent_friends"||$type=="sel_goal_recent_friends"||$type=="delete_goal_recent_followers"||$type=="sel_goal_recent_followers"){
	 
	 $FriendsCnt='';
	 $recentLbl='Request Friends';
	 $tblName='tbl_app_cherryboard_meb';
	 $delTypeName='delete_goal_recent_friends';
	 $divName='div_goal_recent_friends';
	 $subType='Goal';
	 if($type=="sel_goal_recent_followers"||$type=="delete_goal_recent_followers"){
	 	$tblName='tbl_app_expert_cherryboard_meb';
		$recentLbl='Companions Request';
		$delTypeName='delete_goal_recent_followers';
		$divName='div_goal_recent_followers';
		$subType='Expert';
	}
	 $cherryboard_id=$_GET['cherryboard_id'];
	 $meb_id=$_GET['meb_id'];
	 $get_user_id=$_GET['user_id'];
	 if($meb_id>0){
	 	$mysql_delete=mysql_query("delete from ".$tblName." where meb_id=".$meb_id);
	 }
	 	
		$selQuery="select meb_id,user_id,req_user_fb_id from ".$tblName." where is_accept='0' and cherryboard_id=".$cherryboard_id." order by meb_id desc limit 10";
		$selSqlQ=mysql_query($selQuery);
		if(mysql_num_rows($selSqlQ)>0){
			$FriendsCnt.='<p>'.$recentLbl.'</p>';
			$cnt=0;
			while($rowTbl=mysql_fetch_array($selSqlQ)){
				if($cnt==5){$FriendsCnt.='<br/>';}
				$meb_id=$rowTbl['meb_id'];
				$user_id=$rowTbl['user_id'];
				$fb_photo_url=getFriendPhoto($rowTbl['req_user_fb_id']);
				$FriendsCnt.='<div class="small_thumb_container">
				<div class="img_big_container1">
					<div class="feedbox_holder">
						<div class="actions">'.(getExpCreator($subType,$cherryboard_id,$_SESSION['USER_ID'])==1?'<a class="delete" href="#" onclick="ajax_action(\''.$delTypeName.'\',\''.$divName.'\',\'cherryboard_id='.$cherryboard_id.'&meb_id='.$meb_id.'\')" ><img src="images/delete.png"></a>':'').'</div>
					</div>
					<img src="'.$fb_photo_url.'" class="thumb">
				</div>
				</div>';
				$cnt++;
			}
			
		}
	 $ajax_data=$type."##===##".$div_name."##===##".$FriendsCnt;
	 echo $ajax_data;
}	 
//DELETE GOAL FRIENDS
if($type=="delete_goal_friends"||$type=="delete_goal_followers"){

	 $tblName='tbl_app_cherryboard_meb';
	 $divName='div_goal_friends';
	 $delType='delete_goal_friends';
	 $lblBlock='Friends';
	 $subType="Goal";
	 if($type=="delete_goal_followers"){
	 	$tblName='tbl_app_expert_cherryboard_meb';
		 $divName='div_goal_followers';
		 $delType='delete_goal_followers';
		 $lblBlock='Companions';
		 $subType="Expert";
	 }
	 	
	 $cherryboard_id=$_GET['cherryboard_id'];
	 $meb_id=$_GET['meb_id'];
	 if($meb_id>0){
	 	$mysql_delete=mysql_query("delete from ".$tblName." where meb_id=".$meb_id);
	 }
	 	$FriendsCnt='';
		$selQuery="select a.meb_id,b.user_id,b.fb_photo_url from ".$tblName." a,tbl_app_users b where a.req_user_fb_id=b.facebook_id and a.cherryboard_id=".$cherryboard_id." group by b.user_id limit 10";
		$selSqlQ=mysql_query($selQuery);
		$FriendsArray=array();
		if(mysql_num_rows($selSqlQ)>0){
			$cnt=0;
			while($rowTbl=mysql_fetch_array($selSqlQ)){
				$FriendsArray[]=$rowTbl['user_id'];
				$meb_id=$rowTbl['meb_id'];
				if($cnt==5){$FriendsCnt.='<br/>';}
				$FriendsCnt.='<div class="small_thumb_container">
				<div class="img_big_container1">
					<div class="feedbox_holder">
						<div class="actions">'.(getExpCreator($subType,$cherryboard_id,$_SESSION['USER_ID'])==1?'<a class="delete" href="#" onclick="ajax_action(\''.$delType.'\',\''.$divName.'\',\'cherryboard_id='.$cherryboard_id.'&meb_id='.$meb_id.'\')" ><img src="images/delete.png"></a>':'').'</div>
					</div>
					<img src="'.$rowTbl['fb_photo_url'].'" class="thumb">
				</div>
				</div>';
				$cnt++;
			}
			
		}else{
			$FriendsCnt.='<strong>No '.$lblBlock.'</strong>';
		}
	 $ajax_data=$type."##===##".$div_name."##===##".$FriendsCnt;
	 echo $ajax_data;
}	 
//DELETE GOAL EXPERT
if($type=="delete_goal_experts"){
	 $cherryboard_id=$_GET['cherryboard_id'];
	 $experts_id=$_GET['experts_id'];
	 if($experts_id>0){
	 	$mysql_delete=mysql_query("delete from tbl_app_cherryboard_experts where experts_id=".$experts_id);
	 }
	$selQuery="select a.experts_id,b.user_id,b.fb_photo_url from tbl_app_cherryboard_experts a,tbl_app_users b where a.user_id=b.user_id and a.cherryboard_id=".$cherryboard_id." order by a.experts_id desc limit 10";
		$selSqlQ=mysql_query($selQuery);
		$ExpertsCnt='';
		if(mysql_num_rows($selSqlQ)>0){
			$cnt=0;
			while($rowTbl=mysql_fetch_array($selSqlQ)){
				$experts_id=$rowTbl['experts_id'];
				if($cnt==5){$ExpertsCnt.='<br/>';}
				$ExpertsCnt.='<div class="small_thumb_container">
				<div class="img_big_container">
					<div class="feedbox_holder">
						<div class="actions"><a class="delete" href="#" onclick="ajax_action(\'delete_goal_experts\',\'div_goal_experts\',\'cherryboard_id='.$cherryboard_id.'&experts_id='.$experts_id.'\')" ><img src="images/delete.png"></a></div>
					</div>
					<img src="'.$rowTbl['fb_photo_url'].'" class="thumb">
				</div>
				</div>';
				$cnt++;
			}
			
		}else{
			$ExpertsCnt.='<strong>No Experts</strong>';
		}
	 
	 $ajax_data=$type."##===##".$div_name."##===##".$ExpertsCnt;
	 echo $ajax_data;
}
if($type=="add_goal_expert"){
	 $cherryboard_id=$_GET['cherryboard_id'];
	 $user_id=$_GET['user_id'];
	 if($user_id>0){
	 		$checkExp=(int)getFieldValue('experts_id','tbl_app_cherryboard_experts','cherryboard_id='.$cherryboard_id.' and user_id='.$user_id);
			if($checkExp==0){
				$upd_expert=mysql_query("INSERT INTO `tbl_app_cherryboard_experts` (`experts_id`, `cherryboard_id`, `user_id`, `record_date`) VALUES (NULL, '".$cherryboard_id."', '".$user_id."', CURRENT_TIMESTAMP)");
			}	
		$ajax_data="Expert added"; 
	 }
	$ajax_data=$type."##===##".$div_name."##===##".$ajax_data."##===##".$cherryboard_id;
	echo $ajax_data;
}

//EDIT/SAVE GOAL TITLE
if($type=="edit_goal_title"){
	 $cherryboard_id=$_GET['cherryboard_id'];
	 $getTitle=getFieldValue('cherryboard_title','tbl_app_cherryboard','cherryboard_id='. $cherryboard_id); 
	  
	 $ajax_data.='<input type="text" name="goal_edit_title" id="goal_edit_title" value="'.$getTitle.'"/>&nbsp;<img src="images/save.png" height="20" style="cursor:pointer" onclick="edit_goal(\'save_goal_title\','.$cherryboard_id.')" width="20" title="Save" />';
	  echo $ajax_data;
	  
}else if($type=="save_goal_title"){
	$edit_title=$_GET['edit_title'];
	$cherryboard_id=$_GET['cherryboard_id'];
	$updateTitle=mysql_query("update tbl_app_cherryboard set cherryboard_title='".$edit_title."' where cherryboard_id=".$cherryboard_id);
	 $ajax_data.=$edit_title.'&nbsp;<img src="images/edit.png" height="16" style="cursor:pointer" onclick="edit_goal(\'edit_goal_title\','.$cherryboard_id.')" width="16" title="Edit" />';
	 //share into facebook
	try {
		$ret_obj = $facebook->api('/me/feed', 'POST',
									array(
									  'link' => 'www.30daysnew.com',
									  'message' => 'Updated Goal Storyboard '.ucwords($edit_title),
								 ));
	  }catch(FacebookApiException $e) {
		
	  }
	  echo $ajax_data;
}
//EDIT/SAVE GOAL DAYS AND STRIKES DAY
if($type=="edit_goal_day"){
	 $cherryboard_id=$_GET['cherryboard_id'];
	 $gift_id=(int)getFieldValue('gift_id',' tbl_app_cherry_gift','cherryboard_id='.$cherryboard_id);
	 $campaignDetail=getFieldsValueArray('goal_days,miss_days','tbl_app_gift','gift_id='.$gift_id);
	 $goal_days=$campaignDetail[0];
	 $miss_days=$campaignDetail[1]; 
	  
	 $ajax_data.='Days : <input type="text" name="edit_goal_days" id="edit_goal_days" value="'.$goal_days.'" size="10" />&nbsp;&nbsp;Strikes : <input type="text" name="edit_miss_days" id="edit_miss_days" value="'.$miss_days.'" size="10" />&nbsp;<img src="images/save.png" height="20" style="cursor:pointer" onclick="ajax_action(\'save_goal_day\',\'div_edit_days\',\'cherryboard_id='.$cherryboard_id.'\')" width="20" title="Save" />';
	 $ajax_data=$type."##===##".$div_name."##===##".$ajax_data;
	 echo $ajax_data;
	 //;ajax_action(\'photo_refresh\',\'right_container\',\'cherryboard_id='.$cherryboard_id.'&sort=desc\')	  
}else if($type=="save_goal_day"){
	$edit_goal_days=$_GET['edit_goal_days'];
	$edit_miss_days=$_GET['edit_miss_days'];
	$cherryboard_id=$_GET['cherryboard_id'];
	$gift_id=(int)getFieldValue('gift_id',' tbl_app_cherry_gift','cherryboard_id='.$cherryboard_id);
	if($edit_goal_days>0&&$edit_miss_days>0&&$gift_id>0){
		$updateDay=mysql_query("update tbl_app_gift set goal_days='".$edit_goal_days."', miss_days='".$edit_miss_days."' where gift_id=".$gift_id);
	}
	 $ajax_data.='Days&nbsp;:&nbsp;'.$edit_goal_days.'&nbsp; Strikes &nbsp;:&nbsp;'.$edit_miss_days.'&nbsp;&nbsp;<img src="images/edit.png" height="16" style="cursor:pointer" onclick="ajax_action(\'edit_goal_day\',\'div_edit_days\',\'cherryboard_id='.$cherryboard_id.'\')" width="16" title="Edit" />'; 
	 $ajax_data=$type."##===##".$div_name."##===##".$ajax_data;
	 echo $ajax_data;
}
//EDIT/SAVE EXPERT TITLE
if($type=="edit_expert_title"){
	 $cherryboard_id=$_GET['cherryboard_id'];
	 $getTitle=getFieldValue('cherryboard_title','tbl_app_expert_cherryboard','cherryboard_id='. $cherryboard_id); 
	  
	 $ajax_data.='<input type="text" name="goal_edit_title" id="goal_edit_title" value="'.$getTitle.'"/>&nbsp;<img src="images/save.png" height="20" style="cursor:pointer" onclick="edit_goal(\'save_expert_title\','.$cherryboard_id.')" width="20" title="Save" />';
	  echo $ajax_data;
}else if($type=="save_expert_title"){
	$edit_title=$_GET['edit_title'];
	$cherryboard_id=$_GET['cherryboard_id'];
	$updateTitle=mysql_query("update tbl_app_expert_cherryboard set cherryboard_title='".$edit_title."' where cherryboard_id=".$cherryboard_id);
	 $ajax_data.=$edit_title.'&nbsp;<img src="images/edit.jpg" height="20" style="cursor:pointer" onclick="edit_goal(\'edit_expert_title\','.$cherryboard_id.')" width="20" title="Edit" />';
	  echo $ajax_data;

}
//SELECT CHECKLIST
if($type=="select_checklist"){
	  $checklist_id=$_GET['checklist_id'];
	  $uncheck=$_GET['uncheck'];
	  
	 if($uncheck=="true"){
	 	$_SESSION['select_checklist'] = array_diff($_SESSION['select_checklist'], array($checklist_id));
	 }else{
	    array_push($_SESSION['select_checklist'],$checklist_id);
	    $ajax_data="0##===##0";
	  }	  
	  echo $ajax_data;
}
//SELECT GOALS
if($type=="select_goal"){
	  $cherryboard_id=$_GET['cherryboard_id'];
	  $uncheck=$_GET['uncheck'];
	  
	 if($uncheck=="true"){
	 	$_SESSION['select_goals'] = array_diff($_SESSION['select_goals'], array($cherryboard_id));
	 }else{
	 
		  $select_goals=$_SESSION['select_goals'];
		  $existGoalId=0;
		  
		  $checkCatId=getFieldValue('category_id','tbl_app_system_cherryboard','cherryboard_id='.$cherryboard_id);
		  foreach($_SESSION['select_goals'] as $goal_id){
		  	  $checkGoalId=getFieldValue('category_id','tbl_app_system_cherryboard','cherryboard_id='.$goal_id);
			  if($checkCatId==$checkGoalId){
			  	 $existGoalId=$goal_id;
			  }	
		  }	  
		
		  if($existGoalId==0){
		      array_push($_SESSION['select_goals'],$cherryboard_id);
			  $ajax_data="0##===##0";
		  }else{
			  $cherryboard_title=GetFieldValue('cherryboard_title','tbl_app_system_cherryboard','cherryboard_id='.$existGoalId);
			  $ajax_data=$cherryboard_id."##===##".$cherryboard_title." goal already selected";
		  }
	  }	  
	  echo $ajax_data;
}
//GET GOALS
if($type=="get_goals"&&$_GET['goal_arr_key']!=""){
		$goal_arr_key=$_GET['goal_arr_key'];
		$goalCatArr=$_SESSION['select_gifts'][$goal_arr_key];
		$giftArr=explode('_',$goalCatArr);
		$category_id=$giftArr[0];
			 
		 $selGift=mysql_query("select * from tbl_app_system_cherryboard where category_id=".$category_id." order by cherryboard_id");
		  while($rowGift=mysql_fetch_array($selGift)){
			$cherryboard_id=$rowGift['cherryboard_id'];
			$cherryboard_title=$rowGift['cherryboard_title'];
			
			  $GoalCnt.='<div class="setup_achive"><input name="chk_goals_'.$cherryboard_id.'" id="chk_goals_'.$cherryboard_id.'" type="checkbox" value="'.$cherryboard_id.'" onclick="select_goal(\'select_goal\',this.value,this.name)" class="checkbox1" '.(in_array($cherryboard_id,$_SESSION['select_goals'])?'checked="checked"':'').'>
				<div class="box">
				  <div class="head"><strong>'.$cherryboard_title.'</strong></div>
				  <strong>Checklist</strong><br><br>';
				$selCheckList=mysql_query("select * from tbl_app_system_checklist where cherryboard_id=".$cherryboard_id." order by checklist_id");
				while($rowCheckList=mysql_fetch_array($selCheckList)){
					$checklist_id=$rowCheckList['checklist_id'];
					$checklist=$rowCheckList['checklist'];
					$chk_list_value=$cherryboard_id.'_'.$checklist_id;
					//$GoalCnt.='<div class="list"><label><input type="checkbox" '.(in_array($chk_list_value,$_SESSION['select_checklist'])?'checked="checked"':'').' name="chk_list_'.$checklist_id.'" id="chk_list_'.$checklist_id.'" value="'.$chk_list_value.'" onclick="select_checklist(\'select_checklist\',this.value,this.name)" class="checkboxes"></label>'.$checklist.'</div>';
					$GoalCnt.='<div class="list"><label><input type="checkbox" disabled="disabled" name="chk_list_'.$checklist_id.'" id="chk_list_'.$checklist_id.'" value="'.$cherryboard_id.'_'.$checklist_id.'"  class="checkboxes"></label>'.$checklist.'</div>';
				}
			  $GoalCnt.='<br>
				</div>
			  </div>';
		  }
		  
		 $GoalCnt.='<div class="clear"></div><div>'; 
		 if($goal_arr_key==count($_SESSION['select_gifts'])-1){
		 	$GoalCnt.='<input type="button" onclick="goto_step4()" value="Create Goalboard" class="btn_small">';
		 }else{
			$GoalCnt.='<input type="button" onclick="goto_step3_next('.($goal_arr_key+1).')" value="Next" class="btn_small">';
			
		}	
		if($goal_arr_key>0){
			$GoalCnt.='<input type="button" onclick="goto_step3_next('.($goal_arr_key-1).')"  value="Previous" class="btn_small">';		
		}
		$GoalCnt.='</div>';	
		echo $GoalCnt;  

}
//SELECT GIFTS
if($type=="select_gift"){
	  $gift_id=$_GET['gift_id'];
	  $uncheck=$_GET['uncheck'];
	  $getArr=explode('_',$gift_id);
	 
	 if($uncheck=="true"){
	 	$_SESSION['select_gifts'] = array_diff($_SESSION['select_gifts'], array($gift_id));
	 }else{
	 
		  $gifts_arr=$_SESSION['select_gifts'];
		  $existGiftId=0;
		  if(count($gifts_arr)>0){
			  foreach($gifts_arr as $giftValue){
				$catArr=explode('_',$giftValue);
				if($catArr[0]==$getArr[0]){
					$existGiftId=$catArr[1];
				}
				
			  }
		  }	  
		
		  if($existGiftId==0){
		      //array_push($_SESSION['select_gifts'],$gift_id);
			  $_SESSION['select_gifts'] = array($gift_id);
			  $ajax_data="0##===##0";
		  }else{
			  //$catName=GetFieldValue('category_name','tbl_app_category','category_id='.$getArr[0]);
			  //$GiftName=GetFieldValue('gift_title','tbl_app_gift','gift_id='.$existGiftId);
			  $ajax_data=$getArr[1]."##===##&nbsp;Gift is selected and if u want to select a different gift unselect the secleted gift.&nbsp;";
		  }
	  }	  
	  echo $ajax_data;
}
//GET GIFTS
if($type=="get_gifts"&& $_GET['category_id']>0){
		$user_id=$_GET['user_id'];
	    $get_category_id=$_GET['category_id'];
	  
	  /* Restriction for the show only created non category goal
	  	$sel_cat=mysql_query("select category_id from tbl_app_cherryboard where user_id=".(int)$user_id." group by category_id order by category_id");
		$cat_array=array('0');
		while($sel_catrow=mysql_fetch_array($sel_cat))
		{
			$cat_array[]=$sel_catrow['category_id'];
		}
		$cat_array=implode(',',$cat_array);
		$sel_query=mysql_query("select * from tbl_app_category where category_id not in(".$cat_array.") order by category_id");
	  */
	  		$ajax_data.='<div class="left" style="padding:20px 0px; display:block; width:100%;">';
			$sel_query=mysql_query("select * from tbl_app_category order by category_id");
			$categoryCnt='';
			while($sel_row=mysql_fetch_array($sel_query))
			{
				if($sel_row['category_id']==$get_category_id){
					$selectedColor=' style="background: none repeat scroll 0 0 #C02336;color: #FFFFFF;"';
				}else{
					$selectedColor='';
				}	
				
				$ajax_data.='<a href="#" onclick="ajax_action(\'get_gifts\',\'div_get_gifts\',\'category_id='.$sel_row['category_id'].'&user_id='.$_SESSION['USER_ID'].'\')" class="gray_tag" '.$selectedColor.'>'.ucwords($sel_row['category_name']).'</a>';
			}	
	 $ajax_data.='</div>';
	 
	 
	 
	  $selGift=mysql_query("select * from tbl_app_gift where is_system='1' and category_id='".$get_category_id."' order by gift_id");
	  if(mysql_num_rows($selGift)>0){
		  while($rowGift=mysql_fetch_array($selGift)){
			$gift_id=$rowGift['gift_id'];
			$gift_title=$rowGift['gift_title'];
			$gift_photo=$rowGift['gift_photo'];
			$category_id=$rowGift['category_id'];
			$campaign_title=ucwords($rowGift['campaign_title']);
			$sponsor_name=ucwords($rowGift['sponsor_name']);
			$sponsor_logo=$rowGift['sponsor_logo'];
			$sponsorPath='images/gift/'.$sponsor_logo;
			$cherryboard_id=(int)getFieldValue('cherryboard_id','tbl_app_cherry_gift','gift_id='.$gift_id);
			$ajax_data.='
			<div class="gift1"><a href="gift_profile.php?gid='.$gift_id.'"><img src="images/gift/'.$gift_photo.'" class="imgbig"></a><br><strong>'.$gift_title.'</strong>';		
			
			//winner photo
				$selWin="select a.user_id,b.fb_photo_url,b.first_name,b.last_name from tbl_app_cherry_gift a,tbl_app_users b where a.gift_id='".$gift_id."' and a.user_id=b.user_id group by a.user_id order by a.cherry_gift_id limit 5";
				 $sqlWin=mysql_query($selWin);
				 $winnersPhoto='';	
			     if(mysql_num_rows($sqlWin)>0){
				 	while($selWinRow=mysql_fetch_array($sqlWin)){
				 		$imgUrl=$selWinRow['fb_photo_url'];
						$winner_title=$selWinRow['first_name'].' '.$selWinRow['last_name'];
						if(trim($imgUrl!='')){						
				 		$winnersPhoto.='<img src="'.$imgUrl.'" alt="'.$winner_title.'" style="cursor:pointer" title="'.$winner_title.'" class="imgsmall">';
						}
					}
				 }
				if($winnersPhoto!=""){
					$ajax_data.='Recent Winners<br/><div class="thumb_container">'.$winnersPhoto;
				}else{
					$ajax_data.='<div class="thumb_container">';
				}
			
			$ajax_data.=''.(trim($campaign_title)!=''?'<a href="cherryboard.php?cbid='.$cherryboard_id.'"
			  style="text-decoration:none;color:#990000">'.$campaign_title.'</a><br>':'').'
				'.(trim($sponsor_name)!=''?'Sponsored by :'.$sponsor_name.'<br>':'').'
				'.(is_file($sponsorPath)?'<img src="'.$sponsorPath.'" class="imgsmall"><br>':'').'
			<div class="clear">
			  <label>';
			$ajax_data.='<input type="checkbox" '.(in_array($category_id.'_'.$gift_id,$_SESSION['select_gifts'])?'checked="checked"':'').' onclick="select_gift(\'select_gift\',this.value,this.name)" name="chk_gift_'.$gift_id.'" value="'.$category_id.'_'.$gift_id.'" id="chk_gift_'.$gift_id.'">
			  </label>
			</div></div>    
		  </div>';
		  $GiftCnt.='<div class="gift1"><img src="images/gift/'.$gift_photo.'" class="imgbig"><br><strong>'.$gift_title.'</strong>';
		  
				//winner photo
				$selWin="select a.user_id,b.fb_photo_url,b.first_name,b.last_name from tbl_app_cherry_gift a,tbl_app_users b where a.gift_id='".$gift_id."' and a.user_id=b.user_id group by a.user_id order by a.cherry_gift_id limit 5";
				 $sqlWin=mysql_query($selWin);
				 $winnersPhoto='';	
			     if(mysql_num_rows($sqlWin)>0){
				 	while($selWinRow=mysql_fetch_array($sqlWin)){
				 		$winnersPhoto.='<img src="'.$selWinRow['fb_photo_url'].'" alt="'.$selWinRow['first_name'].' '.$selWinRow['last_name'].'" style="cursor:pointer" title="'.$selWinRow['first_name'].' '.$selWinRow['last_name'].'" class="small_thumb1">';
					}
				 }
				if($winnersPhoto!=""){
					$GiftCnt.='<br/>Recent Winners<br/><div style="text-align:center">'.$winnersPhoto.'</div>';
				}
				$GiftCnt.='
				<div class="thumb_container">
				<div class="clear">
				  <label>';
				 $GiftCnt.='<input type="checkbox" '.(in_array($category_id.'_'.$gift_id,$_SESSION['select_gifts'])?'checked="checked"':'').' onclick="select_gift(\'select_gift\',this.value,this.name)" name="chk_gift_'.$gift_id.'" value="'.$category_id.'_'.$gift_id.'" id="chk_gift_'.$gift_id.'">
				  </label>
				</div></div>    
			  </div>';
		  
		  
		 } 
		 
		$ajax_data.='<div class="clear"></div><input type="button" onclick="goto_step3('.($_SESSION['select_gifts']==""?'0':'1').');" name="btnGift" value="Next" class="right btn_small">';
	 }else{
	 	$ajax_data.='No Gifts';
	 }
	$ajax_data=$type."##===##".$div_name."##===##".$ajax_data;
	echo $ajax_data;
}

//ADD CHECKLIST / TODO LIST
if($type=="add_checklist"||$type=="add_expert_checklist"||$type=="remove_checklist"||$type=="remove_expert_checklist"||$type=="checked_checklist"||$type=="checked_expert_checklist"||$type=="refresh_todo_list"){
	
	$tbl_name='tbl_app_checklist';
	$chk_type='checked_checklist';
	$chk_remove='remove_checklist';
	
	if($type=="add_expert_checklist"||$type=="remove_expert_checklist"||$type=="checked_expert_checklist"){
		$tbl_name='tbl_app_expert_checklist';
		$chk_type='checked_expert_checklist';
		$chk_remove='remove_expert_checklist';
	}

	$txt_checklist=addslashes($_GET['txt_checklist']);
	$cherryboard_id=$_GET['cherryboard_id'];
	$user_id=(int)$_GET['user_id'];
	//CHECKED CHECKLIST
	if($type=="checked_checklist"||$type=="checked_expert_checklist"){	
		$checklist_id=$_GET['checklist_id'];
		$checkVal=$_GET['checkVal'];
		if($checklist_id>0){
			$updCheck=mysql_query("UPDATE ".$tbl_name." SET is_checked='".$checkVal."' WHERE checklist_id=".$checklist_id);
		}
	}
	//REMOVE CHECKLIST
	if($type=="remove_checklist"||$type=="remove_expert_checklist"){
		$checklist_id=(int)$_GET['checklist_id'];
		$delChecklist=mysql_query("DELETE FROM ".$tbl_name." WHERE checklist_id=".$checklist_id);
	}
	//ADD CHECKLIST / TODO LIST
	if(($type=="add_checklist"||$type=="add_expert_checklist")&&$txt_checklist!=""&&$txt_checklist!="add something to To-Do List"){
		$ins_query="INSERT INTO ".$tbl_name." (checklist_id,user_id,cherryboard_id,checklist) VALUES (NULL,'".$user_id."','".$cherryboard_id."','".$txt_checklist."')";
		$ins_sql=mysql_query($ins_query);
		$currentUserId=$user_id;
	}
	//REFRESH TODO LIST
	$sort='';
	if($type=="refresh_todo_list"){
	   $sort=trim($_GET['sort']);
	   if(trim($sort)=='asc'){$sort='';}
	}
	//Call Expert Section
	if($type=="add_expert_checklist"||$type=="remove_expert_checklist"||$type=="checked_expert_checklist"||$type=="refresh_todo_list"){
		//Call To-Do List Items
		$ajax_data.=getToDoListItem($cherryboard_id,$sort);			
	}else{
		$selchk=mysql_query("select *,date_format(record_date,'%m-%d-%Y') as record_date from ".$tbl_name." where cherryboard_id=".$cherryboard_id." order by checklist_id") or die(mysql_error());
		$checkCnt='';
		while($selchkRow=mysql_fetch_array($selchk)){
			$checklist_id=$selchkRow['checklist_id'];
			$checklist=$selchkRow['checklist'];
			$record_date=$selchkRow['record_date'];
			$is_checked=$selchkRow['is_checked'];
			$user_id=$selchkRow['user_id'];
			$toDoList=wordwrap($checklist,30,"<br/>",TRUE);
			$ajax_data.='<div class="box_container" style="width: 230px;"><label><input type="checkbox" id="chkfield_'.$checklist_id.'"  name="chkfield_'.$checklist_id.'" '.($is_checked==1?'checked="checked"':'').' value="1" onclick="checked_checklist(\''.$chk_type.'\',\'div_checklist\',\'cherryboard_id='.$cherryboard_id.'&checklist_id='.$checklist_id.'&cuid='.$currentUserId.'\',\'chkfield_'.$checklist_id.'\')" class="checkbox"></label>&nbsp;'.$toDoList.'<br/><span class="smalltext">added '.$record_date.'&nbsp;'.($user_id==$currentUserId?'<img src="images/close_small1.png"  onclick="ajax_action(\''.$chk_remove.'\',\'div_checklist\',\'cherryboard_id='.$cherryboard_id.'&checklist_id='.$checklist_id.'&cuid='.$currentUserId.'\')" style="cursor:pointer">':'').'</span></div>';
		}
	}		
	$ajax_data=$type."##===##".$div_name."##===##".$ajax_data."##===##".$cherryboard_id;
	echo $ajax_data;
	exit(0);
}

//ADD CHERRYBOARD COMMENT
if($type=="add_cherry_comment"||$type=="del_cherry_comment"||$type=="add_cheers"||$type=="add_cherry_expert_comment"||$type=="del_cherry_expert_comment"||$type=="add_expert_cheers"){
	$cherryboard_id=(int)$_GET['cherryboard_id'];
	$photo_id=(int)$_GET['photo_id'];
	$user_id=(int)$_GET['user_id'];
	$cherry_comment=parseString($_GET['cherry_comment']);
	
	$tbl_name='tbl_app_cherry_comment';
	$tbl_cheers_name='tbl_app_cherryboard_cheers';
	$typeName='del_cherry_comment';
	$typeAddCheers='add_cheers';
	if($type=="add_cherry_expert_comment"||$type=="del_cherry_expert_comment"||$type=="add_expert_cheers"||$type=="ask_expert_question"||$type=="cherry_answer"){
		$tbl_name='tbl_app_expert_cherry_comment';
		$typeName='del_cherry_expert_comment';
		$tbl_cheers_name='tbl_app_expert_cherryboard_cheers';
		$typeAddCheers='add_expert_cheers';
		$day_no=(int)$_GET['day_no'];
		
		$expertboard_id=getFieldValue('expertboard_id','tbl_app_expert_cherryboard','cherryboard_id='.$cherryboard_id);
		$expUserId=getFieldValue('user_id','tbl_app_expert_cherryboard','cherryboard_id='.$cherryboard_id);
		$expOwner_id=getFieldValue('user_id','tbl_app_expertboard','expertboard_id='.$expertboard_id);
		$expOwnerDetail=getUserDetail($expOwner_id,'uid');
		$expOwner_Name=$expOwnerDetail['name'];
		$expOwner_EmailId=$expOwnerDetail['email_id'];
	}
	
		if($type=="add_cheers"||$type=="add_expert_cheers"){
			$checkCheers=(int)getFieldValue('user_id','tbl_app_expert_cherryboard_cheers','photo_id='.$photo_id.' and user_id='.$_SESSION['USER_ID']);
			if($checkCheers==0){
				$ins_query="INSERT INTO ".$tbl_cheers_name." (`cheers_id`, `photo_id`, `user_id`, `cherryboard_id`) VALUES (NULL, '".$photo_id."', '".$user_id."','".$cherryboard_id."')";
				$ins_sql=mysql_query($ins_query);
			}
		}	
		
		//ADD EXPERT COMMENT
		if(($type=="add_cherry_comment"||$type=="add_cherry_expert_comment")&&$cherry_comment!="Add a comment..."&&$photo_id>0){
			$ins_query="INSERT INTO ".$tbl_name." (`comment_id`, `cherryboard_id`, `photo_id`, `user_id`, `cherry_comment`) VALUES (NULL, '".$cherryboard_id."', '".$photo_id."', '".$user_id."', '".$cherry_comment."')";
			$ins_sql=mysql_query($ins_query);
		}
		if($type=="del_cherry_comment"||$type=="del_cherry_expert_comment"){
			$comment_id=(int)$_GET['comment_id'];
			$del_query="DELETE FROM ".$tbl_name." WHERE comment_id=".$comment_id;
			$del_sql=mysql_query($del_query);
		}	
		
	$photoCnt='';
	//START EXPERT CHERRYBOARD COMMENT AND CHEERS PATRS
	if($type=="add_cherry_expert_comment"||$type=="del_cherry_expert_comment"){
		//COMMENT SECTION
		$TotalCmt=(int)getFieldValue('count(photo_id)','tbl_app_expert_cherry_comment','photo_id='.$photo_id);
		$commentCnt=$TotalCmt.' comments';
		$photoCnt.=expert_comment_section($cherryboard_id,$photo_id,$photo_day);
		
	}else{
		//START CHERRYBOARD COMMENT AND CHEERS PATRS
		$TotalCheers=getFieldValue('count(cheers_id)',$tbl_cheers_name,'photo_id='.$photo_id);
		$photoCnt.=''.$TotalCheers.' <span style="color:#006600;font-weight:bold;">Cheered!!</span>';
	}
	$ajax_data.=$photoCnt;	
	if($type=="ask_expert_question"||$type=="cherry_answer"||$type=="add_expert_cheers"){
		$ajax_data=$type."##===##".$div_name."##===##".$ajax_data;
		echo $ajax_data;
	}else{
		echo $photo_id.'###'.$ajax_data.'###'.$cherryboard_id.'###'.$commentCnt;
	}
}
//Get More Link
if($type=="get_more"){
	$gift_id=(int)$_GET['gift_id'];		
	if($gift_id>0){
		//Add Gift Link On DB
	$sel_gift=mysql_query("select campaign_detail,campaign_title from tbl_app_gift where gift_id=".$gift_id."");
				while($fetchGiftRow=mysql_fetch_array($sel_gift)){
					$campaign_detail=trim($fetchGiftRow['campaign_detail']);
					$campaign_title=ucwords($fetchGiftRow['campaign_title']);
					$ajax_data.='<font size="+1"><strong>'.$campaign_title.'</strong></font><br>
								'.($campaign_detail!=''?''.$campaign_detail.'':'No campaign details').'';
				}
			}
	$ajax_data=$type."##===##".$div_name."##===##".$ajax_data;
	echo $ajax_data;
}
//Campaign Post Link On Facebook
if($type=="fb_link_post"){
	$gift_id=(int)$_GET['gift_id'];
	$post_id=trim($_GET['post_id']);
	$user_id=(int)$_GET['user_id'];	
	//$cherryboard_id=(int)getFieldValue('cherryboard_id','tbl_app_cherry_gift','gift_id='.$gift_id);
	if($gift_id>0&&$user_id>0&&$post_id!=''){
		$ins_query=mysql_query("INSERT INTO tbl_app_gift_link 
						(link_id,user_id,gift_id,fb_post,twitter_post,pintrest_post,record_date) 
			VALUES (NULL,".$user_id.",".$gift_id.",'1','0','0','".date('Y-m-d')."')");
	}
	$ajax_data.='<img style="cursor:pointer" src="images/fb_thanks.png" width="101px" />&nbsp;
				<!--  <img src="images/twitter.jpg" width="101px"/>&nbsp;
				 <img src="images/pinterest.jpg" width="101px"/> -->';
	$ajax_data=$type."##===##".$div_name."##===##".$ajax_data;
	echo $ajax_data;
}
//Expertboard Post Link On Facebook
if($type=="fb_link_post_exp"){
	$cherryboard_id=(int)$_GET['cherryboard_id'];
	$post_id=trim($_GET['post_id']);

	if($cherryboard_id>0&&$post_id!=''){
		$ins_query=mysql_query("INSERT INTO tbl_app_expert_link 
						(link_id,user_id,cherryboard_id,fb_post,twitter_post,pintrest_post,record_date) 
			VALUES (NULL,".$_SESSION['USER_ID'].",".$cherryboard_id.",'1','0','0','".date('Y-m-d')."')");
	}
	$countShare=(int)getFieldValue('count(link_id)','tbl_app_expert_link','cherryboard_id='.$cherryboard_id);
	$ajax_data.='<strong>'.$countShare.'</strong><br/><img style="cursor:pointer" src="images/fb_thanks.png" width="101px" />';
	$ajax_data=$type."##===##".$div_name."##===##".$ajax_data;
	echo $ajax_data;
}
//Delete Campaign Checklist
if($type=="delete_chk"){
	$campaign_chk_id=(int)$_GET['campaign_chk_id'];
	$campaign_id=(int)$_GET['campaign_id'];
	if($campaign_chk_id>0&&$campaign_id>0){
	$del_query=mysql_query("DELETE FROM tbl_app_campaign_checklist WHERE campaign_chk_id=".$campaign_chk_id."");
		if($del_query){
			$cnt=1;
			$selchk=mysql_query("select * from tbl_app_campaign_checklist where campaign_id=".$campaign_id." order by campaign_chk_id");
			while($selchkRow=mysql_fetch_array($selchk)){
				$checklist=$selchkRow['checklist'];
				$campaign_ChkId=$selchkRow['campaign_chk_id'];
				$ajax_data.='<div class="box_container"><span>'.$cnt.'.&nbsp;'.$checklist.'</span>&nbsp;&nbsp;<a class="delete" onclick="ajax_action(\'delete_chk\',\'chk_div\',\'campaign_chk_id='.$campaign_ChkId.'&campaign_id='.$campaign_id.'\')" href="#"><img src="images/delete.png"></a></div>';
				$cnt++;
		    }
	    }
	}
	$ajax_data=$type."##===##".$div_name."##===##".$ajax_data;
	echo $ajax_data;
}
if($type=="edit_reward_title"){
	 $reward_id=(int)$_GET['rid'];
	 $reward_title=trim(getFieldValue('gift_title','tbl_app_gift','gift_id='.$reward_id));
	  
	 $ajax_data.='<input type="text" name="edit_reward_title" id="edit_reward_title" value="'.$reward_title.'" />&nbsp;<img src="images/save.png" height="20" style="cursor:pointer" onclick="ajax_action(\'save_reward_title\',\'div_reward'.$reward_id.'\',\'rid='.$reward_id.'\')" width="20" title="Save" />';
	 $ajax_data=$type."##===##".$div_name."##===##".$ajax_data;
	 echo $ajax_data;  
}else if($type=="save_reward_title"){
	$edit_reward_title=trim($_GET['edit_reward_title']);
	$reward_id=(int)$_GET['rid'];
	if($edit_reward_title!=''&&$reward_id>0){
		$updateDay=mysql_query("update tbl_app_gift set gift_title='".$edit_reward_title."' where gift_id=".$reward_id);
	}
	 $ajax_data.=''.ucwords($edit_reward_title).'&nbsp;&nbsp;<img src="images/edit.png" height="16" style="cursor:pointer" onclick="ajax_action(\'edit_reward_title\',\'div_reward'.$reward_id.'\',\'rid='.$reward_id.'\')" width="16" title="Edit" />'; 
	 $ajax_data=$type."##===##".$div_name."##===##".$ajax_data;
	 echo $ajax_data;
}
if($type=="edit_reward_picture"){
	 $reward_id=(int)$_GET['rid'];
	 $gift_photo=trim(getFieldValue('gift_photo','tbl_app_gift','gift_id='.$reward_id));
	  
	 $ajax_data.='<img src="images/gift/'.$gift_photo.'" height="100" width="100" /><form action="" method="post" name="frmrwd" enctype="multipart/form-data"><input type="file" name="edit_reward_picture" id="edit_reward_picture" size="15px" /><input type="hidden" id="save_reward_id" name="save_reward_id" value="'.$reward_id.'"/><input type="submit" class="btn_small right" id="saveEditPic" name="saveEditPic" value="save"/></form>';
	 $ajax_data=$type."##===##".$div_name."##===##".$ajax_data;
	 echo $ajax_data;  
}
//Delete Campaign Experts
if($type=="delete_cmp_expert"){
	 $expertId=(int)$_GET['expertId'];
	 if($expertId>0){
	 	$del_query=mysql_query("DELETE FROM tbl_app_campaign_experts WHERE campaign_expert_id=".$expertId."");
	 }
	 if($del_query){
		 $selExp=mysql_query("select expert_photo,expert_name,campaign_id from tbl_app_campaign_experts");
		 while($selExpRow=mysql_fetch_array($selExp)){
			   $expertPic=$selExpRow[0];
			   $expertName=$selExpRow[1];
			   $campaign_id=$selExpRow[2];
			   $expPicPath='images/gift/'.$expertPic;
			   $user_id=(int)getFieldValue('user_id','tbl_app_gift','gift_id='.$campaign_id);
		 if(is_file($expPicPath)){	 
		 $ajax_data.='<div class="img_big_container1">
					 <div class="feedbox_holder">
					 <div class="actions">'.($user_id==$_SESSION['USER_ID']?'<a href="javascript:void(0);"
					 onclick="ajax_action(\'delete_cmp_expert\',\'div_delete_exp\',\'expertId='.$expertId.'\')" class="delete"><img src="images/delete.png" title="Delete"></a>':'').'</div></div>
					 <img src="'.$expPicPath.'"  class="imgsmall" style="margin-top:4px;width: 40px;height: 40px;" title="'.$expertName.'" />&nbsp;</div>';
		 }
		}			 
	 }
	 $ajax_data=$type."##===##".$div_name."##===##".$ajax_data;
	 echo $ajax_data;  
}
//START SEND THANK YOU MAIL CODE
if($type=="sendThankYou"){
	 $ajax_data='';
	 $cherryboard_id=(int)$_GET['cherryboard_id'];
	 $send_email=trim($_GET['send_email']);
	 if($send_email!="Enter Email"&&$cherryboard_id>0){
	 	$send_emailArr=explode(',',$send_email);
		foreach($send_emailArr as $email_id){
			if($email_id!=""){
				//mail to user
				$GoalDetail=getFieldsValueArray('user_id,cherryboard_title','tbl_app_cherryboard','cherryboard_id='.$cherryboard_id);
				$user_id=$GoalDetail[0];
				$goal_title=$GoalDetail[1];
				$GiftName=getFieldValue('b.gift_title','tbl_app_cherry_gift a,tbl_app_gift b','a.gift_id=b.gift_id and a.cherryboard_id='.$cherryboard_id);
				
			    $user_nameDetail=getFieldsValueArray('first_name,last_name','tbl_app_users','user_id='.$user_id);
				$user_name=ucwords($user_nameDetail[0].' '.$user_nameDetail[1]);
				$to = $email_id;				
				$GoalToday=getGoalboardToday($cherryboard_id);				
				$subject=$goal_title.' : '.$GoalToday.' days out of 30 days! ';					
				$message='<table>
							<tr><td>Dear '.$first_name.',</td></tr>
							<tr><td>&nbsp;</td></tr>
							<tr><td>You are able to stick to your goal <strong>'.$goal_title.'</strong> for '.$GoalToday.' days. You gave only '.(30-$GoalToday).' more days to do to win the gift <strong>'.$GiftName.'</strong>.</td></tr>
							<tr><td>&nbsp;</td></tr>
							<tr><td>Keep it up!</td></tr>
							<tr><td>&nbsp;</td></tr>
							</table>';
				//$ajax_data.=$to."========".$subject."========".$message."========".$headers;
				SendMail($to,$subject,$message);
				$sentMail=1;
				if($sentMail){
				 	$msg='<div class="msg_green" style="padding-left:74px;">Mail sent successfully</div>';
				}else{
					$msg='<div class="msg_red" style="padding-left:74px;">Mail sending error...</div>';
				}
			}
						
		}
	 }else{
				$msg='<div class="msg_red" style="padding-left:74px;">Please enter valid email</div>';
			}
	 $ajax_data.='
				  <div id="div_send_thankYou">
						<div align="center" class="head_20">Send Thank You</div><br>
						'.$msg.'
						<span style="padding-left:30px;"><strong>Email</strong>:
				<input type="text" name="send_email" id="send_email" onblur="if(this.value==\'\') this.value=\'Enter Email\';" onfocus="if(this.value==\'Enter Email\') this.value=\'\';" value="Enter Email" /></span><br>	
					   <br>
						<input type="button" style="margin-left:110px;" class="btn_small" id="btnsend" onClick="ajax_action(\'sendThankYou\',\'div_send_thankYou\',\'cherryboard_id='.$cherryboard_id.'&send_email=\'+document.getElementById(\'send_email\').value);" value="Send" name="btnsend" />
					</div>';	
	 $ajax_data=$type."##===##".$div_name."##===##".$ajax_data;
	 echo $ajax_data;  
}
//START EXPERT SEND THANK YOU MAIL
if($type=="sendThankYou_Expert"){
	 $ajax_data='';
	 $cherryboard_id=(int)$_GET['cherryboard_id'];
	 $user_id=(int)$_GET['user_id'];
	 $send_email=trim($_GET['email_id']);
	 $subject=trim($_GET['subject']);
	 $message=trim($_GET['message']);
	 
	 if($send_email!="Enter Email"&&$subject!="Enter Subject"&&$message!="Enter Message"&&$cherryboard_id>0){
	 	$send_emailArr=explode(',',$send_email);
		foreach($send_emailArr as $email_id){
			if($email_id!=""){
				//mail to user
				$ExpBoardDetail=getExpGoalDetail($cherryboard_id);
				$goal_title=ucwords($ExpBoardDetail['expertboard_title']);
				
				$userDetail=getUserDetail($_SESSION['USER_ID']);
		    	$sender_name=$userDetail['name'];
				
			   $user_nameDetail=getFieldsValueArray('first_name,last_name','tbl_app_users','user_id='.$user_id);
				$user_name=ucwords($user_nameDetail[0].' '.$user_nameDetail[1]);
				$to = $email_id;				
				$subject=$subject;
				$message='<table>
							<tr><td>Hi,</td></tr>
							<tr><td>&nbsp;</td></tr>
							<tr><td>'.$message.'</td></tr>
							<tr><td>I would appreciate if you can join by <a href="'.SITE_URL.'/expert_cherryboard.php?cbid='.$cherryboard_id.'">Clicking Here</a>.</td></tr>
							<tr><td>&nbsp;</td></tr>
							<tr><td>Thanks</td></tr>
							<tr><td>'.$user_name.'</td></tr>
							</table>';
				//$ajax_data.=$to."========".$subject."========".$message;
				SendMail($to,$subject,$message,$sender_name);
				$sentMail=1;
				if($sentMail){
				 	$msg='<div class="msg_green" style="padding-left:74px;">Mail sent successfully</div>';
				}else{
					$msg='<div class="msg_red" style="padding-left:74px;">Mail sending error...</div>';
				}
			}						
		}
	 }else{
				$msg='<div class="msg_red" style="padding-left:74px;">Please enter valid email details</div>';
			}
	 $ajax_data.='
				  <div id="div_send_thankYou">
						<div align="center" class="email_header">Send Email</div><br>
						'.$msg.'
			<span style="padding-left:20px;"><strong>Email</strong>:
			<input type="text" style="width:380px;margin-left:25px;" name="email_id" id="email_id" onblur="if(this.value==\'\') this.value=\'Enter Email\';" onfocus="if(this.value==\'Enter Email\') this.value=\'\';" value="Enter Email" /></span><br><br>
			<span style="padding-left:20px;"><strong>Subject</strong>:
			<input type="text" style="width:380px;margin-left:10px;" name="subject" id="subject" onblur="if(this.value==\'\') this.value=\'Enter Subject\';" onfocus="if(this.value==\'Enter Subject\') this.value=\'\';" value="Enter Subject" /></span><br><br>
			<table><tr>
			<td valign="top" style="padding-left:15px;"><strong>Message</strong>:</td>
			<td><textarea style="width:380px;height:200px;" name="message" id="message" onblur="if(this.value==\'\') this.value=\'Enter Message\';" onfocus="if(this.value==\'Enter Message\') this.value=\'\';">Enter Message</textarea></td></tr></table><br>
			<input type="button" style="margin-left:210px;" class="btn_small" id="btnsend" onClick="ajax_action(\'sendThankYou_Expert\',\'div_send_thankYou\',\'cherryboard_id='.$cherryboard_id.'&user_id='.$_SESSION['USER_ID'].'&email_id=\'+document.getElementById(\'email_id\').value+\'&subject=\'+document.getElementById(\'subject\').value+\'&message=\'+document.getElementById(\'message\').value);" value="Send" name="btnsend" />			
			</div>';	
	 $ajax_data=$type."##===##".$div_name."##===##".$ajax_data;
	 echo $ajax_data;
}
//START ASK QUESTION CODE
if($type=="ask_question"){
	 $photo_id=(int)$_GET['photo_id'];
	 $photo_day=(int)$_GET['photo_day'];
	 $cherryboard_id=(int)$_GET['cherryboard_id'];
	 $userId=(int)$_GET['user_id'];
	 $currentUserPic=getFieldValue('fb_photo_url','tbl_app_users','user_id='.$userId);	  
	 $ajax_data.='<div class="add1">
			 <div class="add_img"><img src="'.$currentUserPic.'" class="img_small" /></div>
			 <div class="add_txt">
			 <textarea name="ask_question_'.$photo_id.'" class="input_comments" id="ask_question_'.$photo_id.'" onfocus="if(this.value==\'Ask question\') this.value=\'\';" onblur="if(this.value==\'\') this.value=\'Ask question\';" style="height: 29px;width:125px;">Ask question</textarea>			 
			 </div>
			 <div class="add_btn"><img src="images/btn_ask.png" style="cursor:pointer" onclick="ajax_action(\'ask_expert_question\',\'div_cherry_comment_'.$photo_id.'\',\'photo_id='.$photo_id.'&cherryboard_id='.$cherryboard_id.'&user_id='.$userId.'&photo_day='.$photo_day.'&question=\'+document.getElementById(\'ask_question_'.$photo_id.'\').value);"></div>
    </div>';
	 $ajax_data=$type."##===##".$div_name."##===##".$ajax_data;
	 echo $ajax_data;  
}
?>