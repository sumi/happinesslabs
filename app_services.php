<?php 
error_reporting(0);
header('Content-Type: application/json');
include('include/app-db-connect.php');
include('include/app_functions.php');
$type=$_REQUEST['type'];
$tbl=$_REQUEST['tbl'];
$fb_id=trim($_REQUEST['fb_id']);
$user_id=(int)getUserId_by_FBid($fb_id);
$tblData=array();
//Vijay FB ID : 100002349398425


//if($user_id==0){
	
	//START SHARE ON EMAIL WEB SERVICES CODE
	if($type=='share_on_email'){
	   /*https://www.happinesslabs.com/app_services.php?type=share_on_email&fb_id=[user fb id]
&cherryboard_id=[cherryboard_id]&user_id=[user_id]&email_id=[email_id]&subject=[subject]&message=[message]*/
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
				   $tblData[]='Email Sent Successfully.';
				}
			}
		  }
	   }else{
	   	  $tblData[]='Invalid Data';
	   }
	}
	//START SEND STORY REQUEST WEB SERVICES CODE
	/*https://www.happinesslabs.com/app_services.php?type=send_story_request&fb_id=[user fb id]
&cherryboard_id=[cherryboard_id]&uid=[user fb id]&request_ids=[requestid and request user fb id]*/
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
					//REQUEST USER DETAILS
					$requestUserId=(int)getFieldValue('user_id','tbl_app_users','facebook_id='.$req_user_fb_id[1]);
					$requestUserDetails=getUserDetail($requestUserId);
					$RequestUserName=$requestUserDetails['first_name'].' '.$requestUserDetails['last_name'];
					$requestEmailId=$requestUserDetails['email_id'];
					//SENDER DETAILS
					$senderUserDetails=getUserDetail($UserId);
					$SenderName=$senderUserDetails['first_name'].' '.$senderUserDetails['last_name'];					
					//EXPERT STORY BOARD DETAIL
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
					   $tblData[]='Request Send Successfully.';
					}
				}	
			}
			$cnt++;
		   }		
		 }else{
	   		$tblData[]='Request Ids Not Set';
	   	 }
	   }else{
	   		$tblData[]='Invalid Data';
	   }		
	}
	//START DELETE PHOTO WEB SERVICES CODE
	/*https://www.happinesslabs.com/app_services.php?type=delete_photo&fb_id=[user fb id]
&del_photo_id=[photo_id]*/
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
				$tblData[]='Photo Deleted Successfully.';
			 }
		  }
	   }else{
	   		$tblData[]='Invalid Data';
	   }
	 }
	 //START DELETE STORY BOARD WEB SERVICES CODE
	/*https://www.happinesslabs.com/app_services.php?type=delete_storyboard&fb_id=[user fb id]
&delExpId=[expertboard_id]*/
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
				$tblData[]='StoryBoard Deleted Successfully.';
			 }
		  }else{
	   		$tblData[]='Invalid Data';
	   	  }	
	    }	
	 }	
	 //START UPDATE PHOTO TITLE WEB SERVICES CODE
	 /*https://www.happinesslabs.com/app_services.php?type=update_photo_title&fb_id=[user fb id]
&photo_id=[photo_id]&photo_title=[photo title]*/
	 if($type=='update_photo_title'){
	    $photo_id=(int)$_REQUEST['photo_id'];
	    $photoTitle=parseString($_REQUEST['photo_title']);
		if($photo_id>0&&$photoTitle!=''){
			$updtTitle=mysql_query("UPDATE tbl_app_expert_cherry_photo SET photo_title='".$photoTitle."' WHERE photo_id=".$photo_id);
			if($updtTitle){
			  $tblData[]='Photo Title Updated Successfully.';
			}
		}else{
			$tblData[]='Invalid Data';
		}
	 }
	 //START UPDATE PHOTO THEME WBE SERVICES CODE
	 /*https://www.happinesslabs.com/app_services.php?type=update_photo_theme&fb_id=[user fb id]
&photo_day=[photo day]&sub_day=[sub day]&expertboard_id=[expertboard_id]&photo_theme=[photo day title]*/
	 if($type=='update_photo_theme'){
	   $photo_day=$_REQUEST['photo_day'];
	   $sub_day=$_REQUEST['sub_day'];
	   $expertboard_id=$_REQUEST['expertboard_id'];
	   $photoTheme=parseString($_REQUEST['photo_theme']);
	   if($photo_day>0&&$sub_day>0&&$expertboard_id>0&&$photoTheme!=''){
		   $updtTheme=mysql_query("UPDATE tbl_app_expertboard_days SET day_title='".$photoTheme."' WHERE day_no=".$photo_day." AND sub_day=".$sub_day." AND expertboard_id=".$expertboard_id);
		   if($updtTheme){
			  $tblData[]='Photo Theme Updated Successfully.';
		   }
	   }else{
	   	  $tblData[]='Invalid Data';
	   }
	 }
	 //START DELETE REWARD WEB SERVICE CODE
	 /*https://www.happinesslabs.com/app_services.php?type=delete_reward&fb_id=[user fb id]
&expRewardId=[reward id]&user_id=[user id]*/
	if($type=='delete_reward'){
	   $expRewardId=(int)$_REQUEST['expRewardId'];
	   $UserId=(int)$_REQUEST['user_id'];
	   if($expRewardId>0&&$user_id==$UserId){
		  $photo_name=trim(getFieldValue('photo_name','tbl_app_expert_reward_photo','exp_reward_id='.$expRewardId));	
		  $photo_path='images/expertboard/reward/'.$photo_name;
		  $delReward=mysql_query("DELETE FROM tbl_app_expert_reward_photo WHERE exp_reward_id=".$expRewardId);
		  if($delReward){
			 unlink($photo_path);
			 $tblData[]='Reward Deleted Successfully.';
		  }	
	   }else{
	   	  $tblData[]='Invalid Data';
	   }
	}
	//START ADD REWARD WEB SERVICES CODE
	/*https://www.happinesslabs.com/app_services.php?type=add_reward&fb_id=[user fb id]
&cherryboard_id=[cherryboard id]&user_id=[user id]&comment=[photo comment]&image_name=[photo name]*/
	if($type=='add_reward'){
	   $rnd=rand();
	   $cherryboard_id=(int)$_REQUEST['cherryboard_id'];
	   $user_id=(int)$_REQUEST['user_id'];
	   $comment=$_REQUEST['comment'];
	   $image_name=trim($_FILES['file_name']['name']);
	   $photo_name=$rnd.'_'.$image_name;
	   $uploaddir='images/expertboard/reward/'.$photo_name;
	   $old_uploaddir='images/expertboard/temp/'.$image_name;
	   if(move_uploaded_file($_FILES['file_name']['tmp_name'],$old_uploaddir)){
		  $thumb_command=$ImageMagic_Path."convert ".$old_uploaddir." -thumbnail 180 x 180 ".$uploaddir;
		  $last_line=system($thumb_command,$retval);	
	   }
	   //$retval=copy($old_uploaddir,$uploaddir);
	   if($retval){
		  if($comment=="Write your comment here..."){
			 $comment='';
		  }
		  if($image_name!=''&&$cherryboard_id>0&&$user_id>0){			   
			$insReward=mysql_query("INSERT INTO tbl_app_expert_reward_photo
			(exp_reward_id,user_id,cherryboard_id,photo_title,photo_name,record_date)
			VALUES (NULL,'".$user_id."','".$cherryboard_id."','".$comment."','".$photo_name."',CURRENT_TIMESTAMP)");
			if($insReward){
			   unlink($old_uploaddir);
			   $tblData[]='Reward Added Successfully.';
			}	
		  }else{
		  	 $tblData[]='Invalid Data';
		  }	
	   } 
	}
	//START ADD AND DELETE TO-DO LIST WEB SERVICES CODE
	/*https://www.happinesslabs.com/app_services.php?type=add_todo_list&fb_id=[user fb id]
&cherryboard_id=[cherryboard id]&user_id=[user id]&txt_todolist=[todolist text]

 	  https://www.happinesslabs.com/app_services.php?type=delete_todo_list&fb_id=[user fb id]
&checklist_id=[checklist id]&user_id=[user id]*/
	if($type=='add_todo_list'||$type=='delete_todo_list'){	
		$cherryboard_id=(int)$_REQUEST['cherryboard_id'];
		$UserId=(int)$_REQUEST['user_id'];
		$txt_todolist=parseString($_REQUEST['txt_todolist']);
		if($type=='add_todo_list'){
			if($cherryboard_id>0&&$UserId>0&&$txt_todolist!=''){
			   $insTodoList=mysql_query("INSERT INTO tbl_app_expert_checklist(checklist_id,user_id,cherryboard_id, checklist) VALUES (NULL,'".$UserId."','".$cherryboard_id."','".$txt_todolist."')");
			   if($insTodoList){
				  $tblData[]='ToDo List Added Successfully.';
			   }
			}else{
				$tblData[]='Invalid Data';
			}
		}else if($type=='delete_todo_list'){
			$checklist_id=(int)$_REQUEST['checklist_id'];
			if($checklist_id>0&&$user_id==$UserId){
			  $delTodoList=mysql_query("DELETE FROM tbl_app_expert_checklist WHERE checklist_id=".$checklist_id);
			   if($delTodoList){
				  $tblData[]='ToDo List Deleted Successfully.';
			   }
			}else{
				$tblData[]='Invalid Data';
			}
		}
	}
	//START ADD AND DELETE PHOTO COMMENT WEB SERVICES CODE
	/*https://www.happinesslabs.com/app_services.php?type=add_photo_comment&fb_id=[user fb id]
&cherryboard_id=[cherryboard id]&user_id=[user id]&photo_id=[photo id]&cherry_comment=[comment text]

	https://www.happinesslabs.com/app_services.php?type=delete_photo_comment&fb_id=[user fb id]
&comment_id=[comment id]&user_id=[user id]*/
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
				  $tblData[]='Comment Added Successfully.';
			   }
			}else{
				$tblData[]='Invalid Data';
			}
		}else if($type=='delete_photo_comment'){
			$comment_id=(int)$_REQUEST['comment_id'];
			if($comment_id>0&&$user_id==$UserId){
			 $delComments=mysql_query("DELETE FROM tbl_app_expert_cherry_comment WHERE comment_id=".$comment_id);
			   if($delComments){
				  $tblData[]='Comment Deleted Successfully.';
			   }
			}else{
				$tblData[]='Invalid Data';
			}
		}
	}
	//START ADD AND DELETE PHOTO NOTES WEB SERVICES CODE
	/*https://www.happinesslabs.com/app_services.php?type=add_photo_notes&fb_id=[user fb id]
&cherryboard_id=[cherryboard id]&user_id=[user id]&photo_id=[photo id]&photo_day=[photo day]&cherry_notes=[notes text]
	
	https://www.happinesslabs.com/app_services.php?type=delete_photo_notes&fb_id=[user fb id]
&notes_id=[notes id]&user_id=[user id]*/
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
				  $tblData[]='Note Added Successfully.';
			   }
			}else{
				$tblData[]='Invalid Data';
			}
		}else if($type=='delete_photo_notes'){
			$notes_id=(int)$_REQUEST['notes_id'];
			if($notes_id>0&&$user_id==$UserId){
			   $delNotes=mysql_query("DELETE FROM tbl_app_expert_notes WHERE notes_id=".$notes_id);
			   if($delNotes){
				  $tblData[]='Note Deleted Successfully.';
			   }
			}else{
				$tblData[]='Invalid Data';
			}
		}
	}
	//START ADD AND DELETE PHOTO QUESTION AND ANSWER WEB SERVICES CODE
	/*https://www.happinesslabs.com/app_services.php?type=add_photo_question&fb_id=[user fb id]
&cherryboard_id=[cherryboard id]&user_id=[user id]&photo_id=[photo id]&photo_day=[photo day]&question=[question text]

	https://www.happinesslabs.com/app_services.php?type=delete_photo_question&fb_id=[user fb id]
&question_id=[question id]&user_id=[user id]

	https://www.happinesslabs.com/app_services.php?type=add_photo_answer&fb_id=[user fb id]
&question_id=[question id]&answer=[answer text]

	https://www.happinesslabs.com/app_services.php?type=delete_photo_answer&fb_id=[user fb id]
&question_id=[question id]&user_id=[user id]	
	*/
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
				  $tblData[]='Question Added Successfully.';
			   }
			}else{
				$tblData[]='Invalid Data';
			}
		}else if($type=='add_photo_answer'){
			$cherry_answer=parseString($_REQUEST['answer']);
			$question_id=(int)$_REQUEST['question_id'];
			if($cherry_answer!=''&&$question_id>0){
			   $insAnswer=mysql_query("UPDATE tbl_app_expert_question_answer SET cherry_answer='".$cherry_answer."' WHERE question_id=".$question_id);
			   if($insAnswer){
				  $tblData[]='Answer Added Successfully.';
			   }
			}else{
				$tblData[]='Invalid Data';
			}
		}else if($type=='delete_photo_answer'){
			$question_id=(int)$_REQUEST['question_id'];
			if($question_id>0&&$user_id==$UserId){
			   $delAnswer=mysql_query("UPDATE tbl_app_expert_question_answer SET cherry_answer='' WHERE question_id=".$question_id);
			   if($delAnswer){
				  $tblData[]='Answer Deleted Successfully.';
			   }
			}else{
				$tblData[]='Invalid Data';
			}
		}else if($type=='delete_photo_question'){
			$question_id=(int)$_REQUEST['question_id'];
			if($question_id>0&&$user_id==$UserId){
			  $delQuestion=mysql_query("DELETE FROM tbl_app_expert_question_answer WHERE question_id=".$question_id);
			  if($delQuestion){
				 $tblData[]='Question Deleted Successfully.';
			  }
			}else{
				$tblData[]='Invalid Data';
			}
		}
	}
	//START UPDATE STORY WEB SERVICES CODE
	/*https://www.happinesslabs.com/app_services.php?type=updatestory&fb_id=[user fb id]
&expid=[expertboard id]*/
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
				$tblData[]="StoryBoard Update Successfully";
			}	
		}else{
			$tblData[]='Invalid Data';
		}
	}
	//==========================================
		if($type=="add_story_photo"){
		//ADD CHERRYBOARD PHOTO
	   //URL : http://happinesslabs.com/app_services.php?type= add_story_photo&fb_id=&story_id=&photo_title=&photo_day&image_attach=
		$fb_id=$_REQUEST['fb_id'];	
		$cherryboard_id=$_REQUEST['story_id'];	
		$photo_title=$_REQUEST['photo_title'];
		$photo_day=$_REQUEST['photo_day'];
		$photo_name=$_FILES['image_attach']['name'];
		$user_id=getUserId_by_FBid($fb_id);	
		echo "1==>".$cherryboard_id."===".$user_id."===".$photo_name;
		if($cherryboard_id>0&&$user_id>0&&$photo_name!=""){
			$Photo_Source = $_FILES['image_attach']['tmp_name'];
			$FileName = rand().'_'.$photo_name;
			$ImagePath = "images/expertboard/".$FileName;
			
			$uploaddir='images/expertboard/'.$FileName;
			$uploaddirThumb='images/expertboard/thumb/'.$FileName;
			$old_uploaddir='images/expertboard/temp/'.$FileName;
			
			$CopyImage=copy($Photo_Source,$old_uploaddir);
			if($CopyImage){
				$thumb_command="convert ".$old_uploaddir." -thumbnail 195 x 195 ".$uploaddir;
				$last_line=system($thumb_command, $retval);
				$thumb_command_thumb="convert ".$old_uploaddir." -thumbnail 60 x 60 ".$uploaddirThumb;
				$last_line=system($thumb_command_thumb, $retval);
				
				//update day
				echo "2==>".$TotalPhoto=(int)getFieldValue('count(photo_id)','tbl_app_expert_cherry_photo','photo_day='.$photo_day.' and cherryboard_id='.$cherryboard_id);
				$sub_day=0;
				if($TotalPhoto>0){
					$expertboard_id=getFieldValue('expertboard_id','tbl_app_expert_cherryboard','cherryboard_id='.$cherryboard_id);
					$Day_Type=getDayType($expertboard_id);
					$sub_day=($TotalPhoto+1);
					echo "3==>".$insDay="INSERT INTO tbl_app_expertboard_days (expertboard_day_id,expertboard_id,day_no, day_title,record_date,sub_day) VALUES (NULL,'".$expertboard_id."','".$photo_day."','".$Day_Type." ".$photo_day.".".$sub_day."','".date('Y-m-d')."','".$sub_day."')";
					$insSql=mysql_query($insDay);
				}
				//update photo
				echo "4==>".$insMeb="INSERT INTO tbl_app_expert_cherry_photo(photo_id,user_id,cherryboard_id,photo_title, photo_name,photo_day,sub_day) VALUES (NULL,'".$user_id."','".$cherryboard_id."','".$photo_title."','".$FileName."','".$photo_day."','".$sub_day."')";
				$insMebSql=mysql_query($insMeb);
				if($insMebSql){
					$tblData[]='Photo Inserted Successfully';
				}
			}else{
				$tblData[]='Photo Upload Error';		
			}
		}else{
			$tblData[]='Invalid Data';
		}
	}	
	
	//START CREATE EXPERT BOARD
	//URL : http://happinesslabs.com/app_services.php?type=add_exp_board&fb_id=[fb_id]&title=[title]&detail=[detail]&category_id=[category_id]&day_type=[day_type]&number_of_days=[number_of_days]&is_board_price=[is_board_price]&board_price=[Board-Price]&board_type=[Board-type]
	//SUBSTORY BOARD URL
//URL: http://happinesslabs.com/app_services.php?type=sub_story&fb_id=[fb_id]&create_from=[create_from]&story_id=[story_id]&title=[title]&detail=[detail]&category_id=[category_id]&day_type=[day_type]&number_of_days=[number_of_days]&is_board_price=[is_board_price]&board_price=[Board-Price]&board_type=[Board-type]
	if($type=="add_exp_board"||$type=="sub_story"){	
		$expertboard_title=parseString($_REQUEST['title']);
		$expertboard_detail=parseString($_REQUEST['detail']);
		$category_id=(int)$_REQUEST['category_id'];
		$day_type=(int)$_REQUEST['day_type'];
		$number_days=(int)$_REQUEST['number_of_days'];
		$is_board_price=(int)$_REQUEST['is_board_price'];
		$price=$_REQUEST['board_price'];
		$board_type=(int)$_REQUEST['board_type'];
		$user_id=getUserId_by_FBid($_REQUEST['fb_id']);
		//GET SUB STORY FIELDS VALUE
		$create_from=trim($_POST['create_from']);
		$cherryboard_parent_id=(int)$_POST['story_id'];
		$parent_id=(int)getFieldValue('expertboard_id','tbl_app_expert_cherryboard','cherryboard_id='.$cherryboard_parent_id);
		
		if($day_type==4){
			$day_type=1;
			$number_days=1;
		}
					
		if($create_from=='header'){ $parent_id=0; }
		
		if($is_board_price==1){
			$Customers='Customers';
		}else{
			$Customers='People';
		}
		if($expertboard_title!=''&&$expertboard_detail!=''&&$category_id>0&&$number_days>0){
			$checkExpBoard=(int)getFieldValue('expertboard_id','tbl_app_expertboard','expertboard_title="'.$expertboard_title.'" and user_id='.$user_id);
			if($checkExpBoard==0){
				$ip_address=$_SERVER['REMOTE_ADDR'];
				$insExpBoard="INSERT INTO tbl_app_expertboard (expertboard_id,user_id,category_id,expertboard_title, expertboard_detail,goal_days,price,record_date,day_type,is_board_price,board_type,customers,ip_address,parent_id,living_narrative) VALUES (NULL,'".(int)$user_id."','".$category_id."','".$expertboard_title."','".$expertboard_detail."','".$number_days."','".$price."',CURRENT_TIMESTAMP,'".$day_type."','".$is_board_price."','".$board_type."','".$Customers."','".$ip_address."','".$parent_id."','".$living_narrative."')";
				$insQry=mysql_query($insExpBoard);
				$NewexpertBoardId=mysql_insert_id();
				if($NewexpertBoardId>0){
					//GET DAY TYPE
					$DayType=getDayType($NewexpertBoardId);
					//created goal days
					for($i=1;$i<=$number_days;$i++){
						$insDays="INSERT INTO `tbl_app_expertboard_days` (`expertboard_day_id`, `expertboard_id`, `day_no`, `day_title`, `record_date`) VALUES (NULL, '".$NewexpertBoardId."', '".$i."', '".$DayType." ".$i."', CURRENT_TIMESTAMP)";
						$insDaysSql=mysql_query($insDays);
					}
					//new main goal board							
					$insExpBoard=mysql_query("INSERT INTO tbl_app_expert_cherryboard (cherryboard_id,user_id,expertboard_id, category_id,cherryboard_title,record_date,makeover,qualified,help_people,start_date,price,fb_album_id,main_board,is_publish)
					VALUES (NULL, '".(int)$user_id."', '".$NewexpertBoardId."','0','', CURRENT_TIMESTAMP,'','','','','0','','1','1')");
					$GoalBoardId=mysql_insert_id();
					if($GoalBoardId>0){
						//Create Goal To-Do List
						for($i=1;$i<=$number_days;$i++){
							$insTodo="INSERT INTO tbl_app_expert_checklist (checklist_id,user_id,cherryboard_id, checklist,record_date,is_checked,is_system) VALUES (NULL,'".(int)$user_id."','".$GoalBoardId."','".$DayType." ".$i."',CURRENT_TIMESTAMP,'0','1')";
							$insTodoSql=mysql_query($insTodo);
						}
						$tblData['status']=(int)$GoalBoardId;	
					}
				}else{
					$tblData['status']='board error';
				}	
			}else{
				$tblData['status']='Same board already exist';
			}
		}else{
			$tblData['status']='Invalid Data';
		}
	}
	//END INSERT EXPERT BOARD
	
if($type=='copy_story'){
//START EXPERTBOARD COPY CODE
//https://happinesslabs.com/app_services.php?type=copy_story&fb_id=[fb_id]&story_id=[storyboard_id]
$cherryboard_id=$_GET['story_id'];
$expertBoardId=(int)getFieldValue('expertboard_id','tbl_app_expert_cherryboard','cherryboard_id='.$cherryboard_id);
	if($expertBoardId>0&&$cherryboard_id>0&&$user_id>0){
	   $lastCreatedId=CopyExpertBoard($expertBoardId,$cherryboard_id,$user_id);
	   if($lastCreatedId>0){
			$tblData['status']='Story Copy Successfully';
			$url = 'https://www.happinesslabs.com/app_services_data.php?type=story_detail&fb_id='.$fb_id.'&story_id='.$lastCreatedId;
			$storyDetail=getWSdata($url);
			$tblData['detail']=$storyDetail;
	   }else{$tblData['status']='Same Date Validation';}
	}else{$tblData['status']='Invalid Data';}			
}
//END EXPERTBOARD COPY CODE

//START DOIT CODE
if($type=='doit'){
	//https://happinesslabs.com/app_services.php?type=doit&fb_id=[fb_id]&story_id=[storyboard_id]
	$cherryboard_id=$_GET['story_id'];
	$expertBoardId=(int)getFieldValue('expertboard_id','tbl_app_expert_cherryboard','cherryboard_id='.$cherryboard_id);
	if($expertBoardId>0&&$cherryboard_id>0&&$user_id>0){
		$lastCreatedId=createExpertboard($expertBoardId,$cherryboard_id,$user_id);
		if($lastCreatedId>0){
			$tblData['status']='Do-It Successfully';
			$url = 'https://www.happinesslabs.com/app_services_data.php?type=story_detail&fb_id='.$fb_id.'&story_id='.$lastCreatedId;
			$storyDetail=getWSdata($url);
			$tblData['detail']=$storyDetail;
		}else{ $tblData['status']='Do-It Exist';}
	}else{	$tblData['status']='Invalid Data';	}
}
//END DOIT CODE	
/*}else{
	$tblData[]='Invalid User';
}*/

//ADD USER & RETURN STORY LIST
if($type=="add_user"){
//URL : http://happinesslabs.com/app_services.php?type=add_user&fb_id=&first_name=&last_name=&email=&fb_photo_url=&location=
	$fb_id=$_REQUEST['fb_id'];
	$first_name=$_REQUEST['first_name'];
	$last_name=$_REQUEST['last_name'];
	$email=$_REQUEST['email'];
	$fb_photo_url=$_REQUEST['fb_photo_url'];
	$location=$_REQUEST['location'];
	if($fb_id!=""&&$first_name!=""&&$last_name!=""&&$email!=""){
		$user_id=getFieldValue('user_id','tbl_app_users','facebook_id="'.$fb_id.'"');
		if($user_id==0){
			$ins_query="INSERT INTO `tbl_app_users` (`user_id`, `first_name`, `last_name`, `email_id`, `facebook_id`, `join_date`, fb_photo_url, location) VALUES (NULL, '".$first_name."', '".$last_name."', '".$email."', '".$fb_id."', '".date('Y-m-d')."', '".$fb_photo_url."', '".$location."')";
			
			$ins_sql=mysql_query($ins_query) or die(mysql_error());
			$user_id=mysql_insert_id();
			if($user_id>0){
				$tblData['status']='User Registered';
			}
		}else{
			$tblData['status']="User Exist";
		}
		
		if($user_id>0){
			$userDetail=getUserDetail($user_id,$type='uid');
			$tblData['user_detail']=$userDetail;
							
			$selStory=mysql_query("select expertboard_id from tbl_app_expert_cherryboard where user_id='".$user_id."'");
			if(mysql_num_rows($selStory)>0){
				$url = 'http://happinesslabs.com/app_services_data.php?type=user_profile&fb_id='.$fb_id;
			}else{
				$url = 'http://happinesslabs.com/app_services_data.php?type=all_stories&fb_id='.$fb_id;
			}	
			$storyDetail=getWSdata($url);
			$tblData['detail']=$storyDetail;
		}else{
			$tblData['status']="User Error";
		}
	}else{
		$tblData['status']="Invalid Data";
	}
}

//print_r($tblData)."<br>";
$jsonData=array(array("data"=>$tblData));
$jsonData=json_encode($jsonData);
$jsonData=substr($jsonData,1);
$jsonData=substr($jsonData,0,(strlen($jsonData)-1));
echo $jsonData;
?>