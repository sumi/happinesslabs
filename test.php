<?php
error_reporting(0);
include_once "fbmain.php";
include('include/app-common-config.php');
$message='';
?>
<?php
$type=trim($_REQUEST['type']);
$fb_id=(int)$_REQUEST['fb_id'];
$user_id=0;
if($fb_id!=""){
   $user_id=getUserId_by_FBid($fb_id);
}
/*https://www.happinesslabs.com/app_services.php?type=add_user&fb_id=[user fb id]
&first_name=[first name]&last_name=[last name]&email=[email]&fb_photo_url=[user photo irl]&location=[location city]*/
//http://localhost/cherryfull/test.php?type=share_on_email&cherryboard_id=115&user_id=96&email_id=suresh.uniquewebinfo@gmail.com&subject=jsk&message=Jay shree Krishna

//START SHARE ON EMAIL
if($type=='share_on_email'){
   $cherryboard_id=(int)$_REQUEST['cherryboard_id'];
   $UserId=(int)$_REQUEST['user_id'];
   $send_email=trim($_REQUEST['email_id']);
   $subject=trim($_REQUEST['subject']);
   $message=trim($_REQUEST['message']);
   if($cherryboard_id>0&&$UserId>0&&$send_email!=''&&$subject!=''&&$message!=''){
   	  $send_emailArr=explode(',',$send_email);
	  foreach($send_emailArr as $email_id){	  	
		if($email_id!=''){
		   //mail to user
		   $ExpBoardDetail=getExpGoalDetail($cherryboard_id);
		   $goal_title=ucwords($ExpBoardDetail['expertboard_title']);			
		   $userDetail=getUserDetail($UserId);
		   $sender_name=$userDetail['name'];
		   $to=$email_id;				
		   $subject=$subject;
		   $message='<table>
					 <tr><td>Hi,</td></tr>
					 <tr><td>&nbsp;</td></tr>
					 <tr><td>'.$message.'</td></tr>
					 <tr><td>I would appreciate if you can join by <a href="'.SITE_URL.'/expert_cherryboard.php?cbid='.$cherryboard_id.'">Clicking Here</a>.</td></tr>
					 <tr><td>&nbsp;</td></tr>
					 <tr><td>Thanks</td></tr>
					 <tr><td>'.$sender_name.'</td></tr>
					 </table>';
			SendMail($to,$subject,$message,$sender_name);
			if(SendMail){
			   $message='Share On Email Successfully.';
			}
		}
	  }
   }
}
//START SEND STORY REQUEST
if($type=='send_story_request'){
   $cherryboard_id=(int)$_REQUEST['cherryboard_id'];
   if($cherryboard_id>0){
   	 if(isset($_REQUEST['request_ids'])){
	   $UserId=getUserId_by_FBid($_REQUEST['uid']);
	   $Arrayids=explode(',',$_REQUEST['request_ids']);
	   $cnt=1;
	   foreach($Arrayids as $req_user_fbArr){
		if($cnt<=10){
		   $req_user_fb_id=explode('_',$req_user_fbArr);
		   $chkUser=(int)getFieldValue('meb_id','tbl_app_expert_cherryboard_meb','req_user_fb_id='.$req_user_fb_id[1].' AND cherryboard_id='.$cherryboard_id);
			if($chkUser==0){
			   $insMeb="INSERT INTO tbl_app_expert_cherryboard_meb (meb_id,cherryboard_id,user_id,req_user_fb_id,request_ids,is_accept) VALUES (NULL,'".$cherryboard_id."','".$UserId."','".$req_user_fb_id[1]."','".$req_user_fb_id[0]."','0')";
			   $ins_sql=mysql_query($insMeb);
			    //=========> START SEND EMAIL CODE <============
				//GET REQUEST USER DETAILS
			    $requestUserId=(int)getFieldValue('user_id','tbl_app_users','facebook_id='.$req_user_fb_id[1]);
				$requestUserDetails=getUserDetail($requestUserId);
				$RequestUserName=$requestUserDetails['first_name'].' '.$requestUserDetails['last_name'];
				$requestEmailId=$requestUserDetails['email_id'];
				//GET SENDER DETAILS
				$senderUserDetails=getUserDetail($UserId);
				$SenderName=$senderUserDetails['first_name'].' '.$senderUserDetails['last_name'];					
				//GET EXPERT STORY BOARD DETAIL
				$expertboard_id=(int)getFieldValue('expertboard_id','tbl_app_expert_cherryboard','cherryboard_id='.$cherryboard_id);
				$expertboard_title=ucwords(trim(getFieldValue('expertboard_title','tbl_app_expertboard','expertboard_id='.$expertboard_id)));
				//SEND EMAIL CODE
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
				if(SendMail){
				   $message='Request Send Successfully.';
			    }
			}	
		}
		$cnt++;
	   }		
	 }
   }		
}
//START DELETE PHOTO 
if($type=='delete_photo'){
   $del_photo_id=(int)$_REQUEST['del_photo_id'];
   if($del_photo_id>0&&$user_id>0){
   	  $photo_name=getFieldValue('photo_name','tbl_app_expert_cherry_photo','photo_id='.$del_photo_id);
	  $UserId=getFieldValue('user_id','tbl_app_expert_cherry_photo','photo_id='.$del_photo_id);
	  $photo_path='images/expertboard/'.$photo_name;
	  $thumb_path='images/expertboard/thumb/'.$photo_name;
	  $profileSlidePath='images/expertboard/profile_slide/'.$photo_name;
      $sliderPath='images/expertboard/slider/'.$photo_name;
	  if(is_file($photo_path)&&$user_id==$UserId){
	  	 $delPhoto=mysql_query('DELETE FROM tbl_app_expert_cherry_photo WHERE photo_id='.$del_photo_id);
		 $delComment=mysql_query('DELETE FROM tbl_app_expert_cherry_comment WHERE photo_id='.$del_photo_id);
		 $delQuestion=mysql_query('DELETE FROM tbl_app_expert_question_answer WHERE photo_id='.$del_photo_id);
		 $delNotes=mysql_query('DELETE FROM tbl_app_expert_notes WHERE photo_id='.$del_photo_id);
		 $delCheers=mysql_query('DELETE FROM tbl_app_expert_cherryboard_cheers WHERE photo_id='.$del_photo_id);
		 if($delPhoto){
		 	unlink($photo_path);
			unlink($thumb_path); 
			unlink($profileSlidePath);
			unlink($sliderPath);
	  		$message='Photo Deleted Successfully.';
		 }
	  }
   }
}
//START DELETE STORY BOARD
if($type=='delete_storyboard'){
   $delStoryBordId=(int)$_REQUEST['delExpId'];
   if($delStoryBordId>0){
	  $UserId=(int)getFieldValue('user_id','tbl_app_expertboard','expertboard_id='.$delStoryBordId);
	  $cherryboard_id=(int)getFieldValue('cherryboard_id','tbl_app_expert_cherryboard','expertboard_id="'.$delStoryBordId.'" AND user_id='.$UserId);
	  if($user_id==$UserId&&$cherryboard_id>0){
	  	 $delStoryBoard=mysql_query("DELETE FROM tbl_app_expertboard WHERE expertboard_id=".$delStoryBordId);
		 if($delStoryBoard){
		 	$delStoryDays=mysql_query("DELETE FROM tbl_app_expertboard_days WHERE expertboard_id=".$delStoryBordId);
			deleteExpertBoard($cherryboard_id);//call function deleteExpertBoard
	  		$message='StoryBoard Deleted Successfully.';
		 }
	  }	
   }	
}
//START UPDATE PHOTO TITLE
if($type=='update_photo_title'){
   $photo_id=(int)$_REQUEST['photo_id'];
   $photoTitle=parseString($_REQUEST['photo_title']);
   $updtTitle=mysql_query("UPDATE tbl_app_expert_cherry_photo SET photo_title='".$photoTitle."' WHERE photo_id=".$photo_id);
   if($updtTitle){
	  $message='Title Updated Successfully.';
   }
}
//START UPDATE PHOTO THEME
if($type=='update_photo_theme'){
   $photo_day=$_REQUEST['photo_day'];
   $sub_day=$_REQUEST['sub_day'];
   $expertboard_id=$_REQUEST['expertboard_id'];
   $photoTheme=parseString($_REQUEST['photo_theme']);
   $updtTheme=mysql_query("UPDATE tbl_app_expertboard_days SET day_title='".$photoTheme."' WHERE day_no=".$photo_day." AND sub_day=".$sub_day." AND expertboard_id=".$expertboard_id);
   if($updtTheme){
	  $message='Theme Updated Successfully.';
   }
}
//START DELETE REWARD
if($type=='delete_reward'){
   $expRewardId=(int)$_REQUEST['expRewardId'];
   $UserId=(int)$_REQUEST['user_id'];
   if($expRewardId>0&&$user_id==$UserId){
	  $photo_name=trim(getFieldValue('photo_name','tbl_app_expert_reward_photo','exp_reward_id='.$expRewardId));	
	  $photo_path='images/expertboard/reward/'.$photo_name;
	  $delReward=mysql_query("DELETE FROM tbl_app_expert_reward_photo WHERE exp_reward_id=".$expRewardId);
	  if($delReward){
		 unlink($photo_path);
		 $message='Reward Deleted Successfully.';
	  }	
   }
}
//START ADD REWARD 
//http://localhost/cherryfull/test.php?type=delete_reward&expRewardId=31&user_id=96
//http://localhost/cherryfull/test.php?type=add_reward&cherryboard_id=115&user_id=96&comment=jsk&file_name=1777785590_Budgetrevised.jpeg
if($type=='add_reward'){
   $rnd=rand();
   $file_name=$_REQUEST['file_name'];
   $cherryboard_id=(int)$_REQUEST['cherryboard_id'];
   $user_id=(int)$_REQUEST['user_id'];
   $comment=(int)$_REQUEST['comment'];
   $photo_name=$rnd.'_'.$file_name;
   $uploaddir='images/expertboard/reward/'.$photo_name;
   $old_uploaddir='images/expertboard/temp/'.$file_name;
   $thumb_command=$ImageMagic_Path."convert ".$old_uploaddir." -thumbnail 180 x 180 ".$uploaddir;
   $last_line=system($thumb_command,$retval);	
   if($retval){
	  if($comment=="Write your comment here..."){
		 $comment='';
	  }
	  if($file_name!=''&&$cherryboard_id>0&&$user_id>0){			   
		$insReward=mysql_query("INSERT INTO tbl_app_expert_reward_photo
		(exp_reward_id,user_id,cherryboard_id,photo_title,photo_name,record_date)
		VALUES (NULL,'".$user_id."','".$cherryboard_id."','".$comment."','".$photo_name."',CURRENT_TIMESTAMP)");
		if($insReward){
		   unlink($old_uploaddir);
		   $message='Reward Added Successfully.';
		}	
	  }	
   }
}
//START ADD TO-DOLIST 
if($type=='add_todo_list'||$type=='delete_todo_list'){	
	$cherryboard_id=(int)$_REQUEST['cherryboard_id'];
	$UserId=(int)$_REQUEST['user_id'];
	$txt_todolist=parseString($_REQUEST['txt_todolist']);
	if($type=='add_todo_list'){
		if($cherryboard_id>0&&$UserId>0&&$txt_todolist!=''){
		   $insTodoList=mysql_query("INSERT INTO tbl_app_expert_checklist(checklist_id,user_id,cherryboard_id, checklist) VALUES (NULL,'".$UserId."','".$cherryboard_id."','".$txt_todolist."')");
		   if($insTodoList){
		  	  $message='ToDo List Added Successfully.';
		   }
		}
	}else if($type=='delete_todo_list'){
		$checklist_id=(int)$_REQUEST['checklist_id'];
		if($checklist_id>0&&$user_id==$UserId){
		   $delTodoList=mysql_query("DELETE FROM tbl_app_expert_checklist WHERE checklist_id=".$checklist_id);
		   if($delTodoList){
		  	  $message='ToDo List Deleted Successfully.';
		   }
		}
	}
}
//START ADD AND DELETE PHOTO COMMENT 
if($type=='add_photo_comment'||$type=='delete_photo_comment'){
	$cherryboard_id=(int)$_REQUEST['cherryboard_id'];
	$photo_id=(int)$_REQUEST['photo_id'];
	$UserId=(int)$_REQUEST['user_id'];
	$cherry_comment=parseString($_REQUEST['cherry_comment']);
	
	if($type=='add_photo_comment'){
		if($cherryboard_id>0&&$photo_id>0&&$UserId>0&&$cherry_comment!=''){
		   $insComment=mysql_query("INSERT INTO tbl_app_expert_cherry_comment(comment_id,cherryboard_id,photo_id, user_id,cherry_comment) 
		   VALUES (NULL,'".$cherryboard_id."','".$photo_id."','".$UserId."','".$cherry_comment."')");
		   if($insComment){
		  	  $message='Comment Added Successfully.';
		   }
		}
	}else if($type=='delete_photo_comment'){
		$comment_id=(int)$_REQUEST['comment_id'];
		if($comment_id>0&&$user_id==$UserId){
		   $delComments=mysql_query("DELETE FROM tbl_app_expert_cherry_comment WHERE comment_id=".$comment_id);
		   if($delComments){
		  	  $message='Comment Deleted Successfully.';
		   }
		}
	}
}
//START ADD AND DELETE PHOTO NOTES 
if($type=='add_photo_notes'||$type=='delete_photo_notes'){
	$cherryboard_id=(int)$_REQUEST['cherryboard_id'];
	$photo_id=(int)$_REQUEST['photo_id'];
	$UserId=(int)$_REQUEST['user_id'];
	$cherry_note=parseString($_REQUEST['cherry_notes']);
	$photo_day=(int)$_REQUEST['photo_day'];
	
	if($type=='add_photo_notes'){
		if($cherryboard_id>0&&$photo_id>0&&$UserId>0&&$cherry_note!=''&&$photo_day>0){
		   $insNotes=mysql_query("INSERT INTO tbl_app_expert_notes(notes_id,cherryboard_id,photo_id, user_id,photo_day,cherry_notes) VALUES (NULL,'".$cherryboard_id."','".$photo_id."','".$UserId."','".$photo_day."','".$cherry_note."')");
		   if($insNotes){
		  	  $message='Note Added Successfully.';
		   }
		}
	}else if($type=='delete_photo_notes'){
		$notes_id=(int)$_REQUEST['notes_id'];
		if($notes_id>0&&$user_id==$UserId){
		   $delNotes=mysql_query("DELETE FROM tbl_app_expert_notes WHERE notes_id=".$notes_id);
		   if($delNotes){
		  	  $message='Note Deleted Successfully.';
		   }
		}
	}
}
//START ADD AND DELETE PHOTO QUESTION AND ANSWER 
if($type=='add_photo_question'||$type=='add_photo_answer'||$type=='delete_photo_answer'||$type=='delete_photo_question'){
	$cherryboard_id=(int)$_REQUEST['cherryboard_id'];
	$photo_id=(int)$_REQUEST['photo_id'];
	$UserId=(int)$_REQUEST['user_id'];	
	$photo_day=(int)$_REQUEST['photo_day'];
	
	if($type=='add_photo_question'){
		$cherry_question=parseString($_REQUEST['question']);
		if($cherryboard_id>0&&$photo_id>0&&$UserId>0&&$cherry_question!=''&&$photo_day>0){
		   $insQuestion=mysql_query("INSERT INTO tbl_app_expert_question_answer(question_id,cherryboard_id,photo_id,user_id,cherry_question,photo_day) VALUES (NULL,'".$cherryboard_id."','".$photo_id."','".$UserId."','".$cherry_question."','".$photo_day."')");
		   if($insQuestion){
		  	  $message='Question Added Successfully.';
		   }
		}
	}else if($type=='add_photo_answer'){
		$cherry_answer=parseString($_REQUEST['answer']);
		$question_id=(int)$_REQUEST['question_id'];
		if($cherry_answer!=''&&$question_id>0){
		   $insAnswer=mysql_query("UPDATE tbl_app_expert_question_answer SET cherry_answer='".$cherry_answer."' WHERE question_id=".$question_id);
		   if($insAnswer){
		  	  $message='Answer Added Successfully.';
		   }
		}
	}else if($type=='delete_photo_answer'){
		$question_id=(int)$_REQUEST['question_id'];
		if($question_id>0&&$user_id==$UserId){
		   $delAnswer=mysql_query("UPDATE tbl_app_expert_question_answer SET cherry_answer='' WHERE question_id=".$question_id);
		   if($delAnswer){
		  	  $message='Answer Deleted Successfully.';
		   }
		}
	}else if($type=='delete_photo_question'){
		$question_id=(int)$_REQUEST['question_id'];
		if($question_id>0&&$user_id==$UserId){
		  $delQuestion=mysql_query("DELETE FROM tbl_app_expert_question_answer WHERE question_id=".$question_id);
		  if($delQuestion){
		  	 $message='Question Deleted Successfully.';
		  }
		}
	}
}
//START UPDATE STORY CODE
$expertboard_id=(int)$_REQUEST['expid'];
if($type=='updatestory'){
	if($expertboard_id>0){
		$expertboard_title=trim($_REQUEST['title']);
		$expertboard_detail=trim(addslashes($_REQUEST['detail']));
		$category_id=(int)$_REQUEST['category_id1'];
		$day_type=(int)$_REQUEST['day_type'];
		$number_days=(int)$_REQUEST['number_days'];
		if($number_days==0){ $number_days=1; }
		$livingNarrative=0;
		if($day_type==4){
		 $livingNarrative=1;
		 $day_type=1;
		 $number_days=1;
		}	
		$chk_is_board_price=(int)$_REQUEST['chk_is_board_price'];
		if($chk_is_board_price==1){
			$price=$_REQUEST['price'];
		}else{
			$price=0;
		}	
		$board_type=(int)$_REQUEST['board_type'];
		
		$updtBoard=mysql_query("UPDATE tbl_app_expertboard SET category_id='".$category_id."',expertboard_title='".$expertboard_title."',expertboard_detail='".$expertboard_detail."',goal_days='".$number_days."',price='".$price."',day_type='".$day_type."',is_board_price='".$chk_is_board_price."',board_type='".$board_type."',living_narrative='".$livingNarrative."' WHERE expertboard_id=".$expertboard_id);
		if($updtBoard){
			echo "Board Updated Successfully...";
		}	
	}
	//START SELECT STORY CODE
	$selBoard=mysql_query("SELECT * FROM tbl_app_expertboard WHERE expertboard_id=".$expertboard_id);
	while($selBoardRow=mysql_fetch_array($selBoard)){
		$expertboard_id=(int)$selBoardRow['expertboard_id'];
		$category_id=(int)$selBoardRow['category_id'];
		$expertboard_title=trim($selBoardRow['expertboard_title']);
		$expertboard_detail=trim(stripslashes($selBoardRow['expertboard_detail']));
		$goal_days=(int)$selBoardRow['goal_days'];
		$price=(int)$selBoardRow['price'];
		$day_type=$selBoardRow['day_type'];
		$is_board_price=(int)$selBoardRow['is_board_price'];
		$board_type=(int)$selBoardRow['board_type'];
		$living_narrative=(int)$selBoardRow['living_narrative'];
	}
?>
<form name="editstory" id="editstory" action="test.php?type=updatestory&expid=<?=$expertboard_id?>" method="post" enctype="multipart/form-data">
Project title: <input name="title" id="title" type="text" value="<?=$expertboard_title?>"/><br/><br/>
Story catagory: <?=getCategoryList($category_id,'','category_id1')?><br/><br/>
What is your happy story about? : <textarea name="detail" id="detail"><?=$expertboard_detail?></textarea><br/><br/>
<?php if($day_type=='D'){ ?>
<input name="day_type" id="day_type" type="radio" checked="checked" value="1" /> Day-by-Day
<input name="day_type" id="day_type" type="radio" value="2" /> Item-By-Item
<input name="day_type" id="day_type" type="radio" value="3" /> Step-By-Step <br/><br/>
Approximate number of days/items/steps:<input name="number_days" id="number_days" type="text" value="<?=$goal_days?>"/><br/>
<input name="day_type" id="day_type" type="radio" value="4" /> living narrative (By dates entered)<br/><br/>
<?php }else if($day_type=='I'){ ?>
<input name="day_type" id="day_type" type="radio" value="1" /> Day-by-Day
<input name="day_type" id="day_type" type="radio" checked="checked" value="2" /> Item-By-Item
<input name="day_type" id="day_type" type="radio" value="3" /> Step-By-Step <br/><br/>
Approximate number of days/items/steps:<input name="number_days" id="number_days" type="text" value="<?=$goal_days?>"/><br/>
<input name="day_type" id="day_type" type="radio" value="4" /> living narrative (By dates entered)<br/><br/>
<?php }else if($day_type=='S'){ ?>
<input name="day_type" id="day_type" type="radio" value="1" /> Day-by-Day
<input name="day_type" id="day_type" type="radio" value="2" /> Item-By-Item
<input name="day_type" id="day_type" type="radio" checked="checked" value="3" /> Step-By-Step <br/><br/>
Approximate number of days/items/steps:<input name="number_days" id="number_days" type="text" value="<?=$goal_days?>"/><br/>
<input name="day_type" id="day_type" type="radio" value="4" /> living narrative (By dates entered)<br/><br/>
<?php }else{ ?>
<input name="day_type" id="day_type" type="radio" value="1" /> Day-by-Day
<input name="day_type" id="day_type" type="radio" value="2" /> Item-By-Item
<input name="day_type" id="day_type" type="radio" value="3" /> Step-By-Step <br/><br/>
Approximate number of days/items/steps:<input name="number_days" id="number_days" type="text" value="<?=$goal_days?>"/><br/>
<input name="day_type" id="day_type" type="radio" checked="checked" value="4" /> living narrative (By dates entered)<br/><br/>
<?php } ?>
<?php if($is_board_price>0){ ?>
Storyboard price? :<input name="chk_is_board_price" id="chk_is_board_price" type="radio" checked="checked" value="1" />
Price board <input type="text" name="price" id="price" value="<?=$price?>" >
<input name="chk_is_board_price" id="chk_is_board_price" type="radio" value="0"/> Non-price board<br/>
<?php }else{ ?>
Storyboard price? :<input name="chk_is_board_price" id="chk_is_board_price" type="radio" value="1"/>Price board
<input name="chk_is_board_price" id="chk_is_board_price" type="radio" value="0" checked="checked"/> Non-price board<br/>
<?php } ?>
<?php if($board_type==0){ ?>
Storyboard type? : <input name="board_type" id="board_type" type="radio" checked="checked" value="0" /> Public
<input name="board_type" id="board_type" type="radio" value="1" /> Private <br/><br/>
<?php }else{ ?>
Storyboard type? : <input name="board_type" id="board_type" type="radio" value="0" /> Public
<input name="board_type" id="board_type" type="radio" checked="checked" value="1" /> Private <br/><br/>
<?php } ?>
<input type="submit" name="updatestory" id="updatestory" value="Create">
</form>
<?php }//End of updatestory
if($message!=''){
   echo "<font color='#006600'><strong>".$message."</strong></font>";
}
?>