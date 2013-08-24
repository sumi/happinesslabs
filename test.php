<?php
error_reporting(0);
include_once "fbmain.php";
include('include/app-common-config.php');
?>
<?php
$type=trim($_GET['type']);

//START DELETE PHOTO 
if($type=='delete_photo'){
   $del_photo_id=(int)$_GET['del_photo_id'];
   $user_id=(int)$_SESSION['USER_ID'];
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
		 }
	  }
   }
}
//START DELETE STORY BOARD
if($type=='delete_storyboard'){
   $delStoryBordId=(int)$_GET['delExpId'];
   if($delStoryBordId>0){
   	  $user_id=(int)$_SESSION['USER_ID'];
	  $UserId=(int)getFieldValue('user_id','tbl_app_expertboard','expertboard_id='.$delStoryBordId);
	  $cherryboard_id=(int)getFieldValue('cherryboard_id','tbl_app_expert_cherryboard','expertboard_id="'.$delStoryBordId.'" AND user_id='.$user_id);
	  if($user_id==$UserId&&$cherryboard_id>0){
	  	 $delStoryBoard=mysql_query("DELETE FROM tbl_app_expertboard WHERE expertboard_id=".$delStoryBordId);
		 if($delStoryBoard){
		 	$delStoryDays=mysql_query("DELETE FROM tbl_app_expertboard_days WHERE expertboard_id=".$delStoryBordId);
			deleteExpertBoard($cherryboard_id);//call function deleteExpertBoard
		 }
	  }	
   }	
}
//START UPDATE PHOTO TITLE
if($type=='update_photo_title'){
   $photo_day=$_GET['photo_day'];
   $sub_day=$_GET['sub_day'];
   $expertboard_id=$_GET['expertboard_id'];
   $editTitle=parseString($_GET['edt_day']);
   $updtTitle=mysql_query("UPDATE tbl_app_expertboard_days SET day_title='".$editTitle."' WHERE day_no=".$photo_day." AND sub_day=".$sub_day." AND expertboard_id=".$expertboard_id);
}
//START DELETE REWARD
if($type=='delete_reward'){
   $expRewardId=(int)$_GET['expRewardId'];	
   $cherryboard_id=(int)$_GET['cherryboard_id'];
   $user_id=(int)$_GET['user_id'];
   if($expRewardId>0&&$cherryboard_id>0&&$user_id>0){
   	  $expertboard_id=getFieldValue('expertboard_id','tbl_app_expert_cherryboard','cherryboard_id='.$cherryboard_id);
      $expOwner_id=getFieldValue('user_id','tbl_app_expertboard','expertboard_id='.$expertboard_id);	
	  $photo_name=trim(getFieldValue('photo_name','tbl_app_expert_reward_photo','exp_reward_id='.$expRewardId));	
	  $photo_path='images/expertboard/reward/'.$photo_name;
	  $delReward=mysql_query("DELETE FROM tbl_app_expert_reward_photo WHERE exp_reward_id=".$expRewardId);
	  if($delReward){
		 unlink($photo_path); 
	  }	
   }
}
//START ADD REWARD 
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
	  $insReward=mysql_query("INSERT INTO tbl_app_expert_reward_photo
	  (exp_reward_id,user_id,cherryboard_id,photo_title,photo_name,record_date)
	  VALUES (NULL,'".$user_id."','".$cherryboard_id."','".$comment."','".$photo_name."',CURRENT_TIMESTAMP)");
	  if($insReward){
		 unlink($old_uploaddir);
	  }		
   }
}
//START ADD TO-DOLIST 
if($type=='add_todo_list'||$type=='delete_todo_list'){	
	$cherryboard_id=(int)$_GET['cherryboard_id'];
	$user_id=(int)$_GET['user_id'];
	$txt_checklist=parseString($_GET['txt_checklist']);
	if($type=='add_todo_list'){
		if($cherryboard_id>0&&$user_id>0&&$txt_checklist!=''){
			$insTodoList=mysql_query("INSERT INTO tbl_app_expert_checklist(checklist_id,user_id,cherryboard_id, checklist) VALUES (NULL,'".$user_id."','".$cherryboard_id."','".$txt_checklist."')");
		}
	}else if($type=='delete_todo_list'){
		$checklist_id=(int)$_GET['checklist_id'];
		if($checklist_id>0&&$user_id>0){
			$delTodoList=mysql_query("DELETE FROM tbl_app_expert_checklist WHERE checklist_id=".$checklist_id);
		}
	}
}
//START ADD AND DELETE PHOTO COMMENT 
if($type=='add_photo_comment'||$type=='delete_photo_comment'){
	$cherryboard_id=(int)$_GET['cherryboard_id'];
	$photo_id=(int)$_GET['photo_id'];
	$user_id=(int)$_GET['user_id'];
	$cherry_comment=parseString($_GET['cherry_comment']);
	
	if($type=='add_photo_comment'){
		if($cherryboard_id>0&&$photo_id>0&&$user_id>0&&$cherry_comment!=''){
			$insComment=mysql_query("INSERT INTO tbl_app_expert_cherry_comment(comment_id,cherryboard_id,photo_id, user_id,cherry_comment) VALUES (NULL,'".$cherryboard_id."','".$photo_id."','".$user_id."','".$cherry_comment."')");
		}
	}else if($type=='delete_photo_comment'){
		$comment_id=(int)$_GET['comment_id'];
		if($comment_id>0&&$user_id>0){
			$delComments=mysql_query("DELETE FROM tbl_app_expert_cherry_comment WHERE comment_id=".$comment_id);
		}
	}
}
//START ADD AND DELETE PHOTO NOTES 
if($type=='add_photo_notes'||$type=='delete_photo_notes'){
	$cherryboard_id=(int)$_GET['cherryboard_id'];
	$photo_id=(int)$_GET['photo_id'];
	$user_id=(int)$_GET['user_id'];
	$cherry_note=parseString($_GET['cherry_notes']);
	$photo_day=(int)$_GET['photo_day'];
	
	if($type=='add_photo_notes'){
		if($cherryboard_id>0&&$photo_id>0&&$user_id>0&&$cherry_note!=''&&$photo_day>0){
			$insNotes=mysql_query("INSERT INTO tbl_app_expert_notes(notes_id,cherryboard_id,photo_id, user_id,photo_day,cherry_notes) VALUES (NULL,'".$cherryboard_id."','".$photo_id."','".$user_id."','".$photo_day."','".$cherry_note."')");
		}
	}else if($type=='delete_photo_notes'){
		$notes_id=(int)$_GET['notes_id'];
		if($notes_id>0&&$user_id>0){
			$delNotes=mysql_query("DELETE FROM tbl_app_expert_notes WHERE notes_id=".$notes_id);
		}
	}
}
//START ADD AND DELETE PHOTO QUESTION AND ANSWER 
if($type=='add_photo_question'||$type=='add_photo_answer'||$type=='delete_photo_answer'||$type=='delete_photo_question'){
	$cherryboard_id=(int)$_GET['cherryboard_id'];
	$photo_id=(int)$_GET['photo_id'];
	$user_id=(int)$_GET['user_id'];	
	$photo_day=(int)$_GET['photo_day'];
	
	if($type=='add_photo_question'){
		$cherry_question=parseString($_GET['question']);
		if($cherryboard_id>0&&$photo_id>0&&$user_id>0&&$cherry_question!=''&&$photo_day>0){
			$insQuestion=mysql_query("INSERT INTO tbl_app_expert_question_answer(question_id,cherryboard_id,photo_id,user_id,cherry_question,photo_day) VALUES (NULL,'".$cherryboard_id."','".$photo_id."','".$user_id."','".$cherry_question."','".$photo_day."')");
		}
	}else if($type=='add_photo_answer'){
		$cherry_answer=parseString($_GET['answer']);
		$question_id=(int)$_GET['question_id'];
		if($cherry_answer!=''&&$question_id>0){
			$insAnswer=mysql_query("UPDATE tbl_app_expert_question_answer SET cherry_answer='".$cherry_answer."' WHERE question_id=".$question_id);
		}
	}else if($type=='delete_photo_answer'){
		$question_id=(int)$_GET['question_id'];
		if($question_id>0&&$user_id>0){
			$delAnswer=mysql_query("UPDATE tbl_app_expert_question_answer SET cherry_answer='' WHERE question_id=".$question_id);
		}
	}else if($type=='delete_photo_question'){
		$question_id=(int)$_GET['question_id'];
		if($question_id>0&&$user_id>0){
		  $delQuestion=mysql_query("DELETE FROM tbl_app_expert_question_answer WHERE question_id=".$question_id);
		}
	}
}
//START UPDATE STORY CODE
$expertboard_id=(int)$_GET['expid'];
if(isset($_POST['updatestory'])){
	if($expertboard_id>0){
		$expertboard_title=trim($_POST['title']);
		$expertboard_detail=trim(addslashes($_POST['detail']));
		$category_id=(int)$_POST['category_id1'];
		$day_type=(int)$_POST['day_type'];
		$number_days=(int)$_POST['number_days'];
		if($number_days==0){ $number_days=1; }
		$livingNarrative=0;
		if($day_type==4){
		 $livingNarrative=1;
		 $day_type=1;
		 $number_days=1;
		}	
		$chk_is_board_price=(int)$_POST['chk_is_board_price'];
		if($chk_is_board_price==1){
			$price=$_POST['price'];
		}else{
			$price=0;
		}	
		$board_type=(int)$_POST['board_type'];
		
		$updtBoard=mysql_query("UPDATE tbl_app_expertboard SET category_id='".$category_id."',expertboard_title='".$expertboard_title."',expertboard_detail='".$expertboard_detail."',goal_days='".$number_days."',price='".$price."',day_type='".$day_type."',is_board_price='".$chk_is_board_price."',board_type='".$board_type."',living_narrative='".$livingNarrative."' WHERE expertboard_id=".$expertboard_id);
		if($updtBoard){
			echo "Board Updated Successfully...";
		}	
	}
}
//START SELECT STORY CODE
$expertboard_id=18;
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
<form name="editstory" id="editstory" action="test.php?expid=<?=$expertboard_id?>" method="post" enctype="multipart/form-data">
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

