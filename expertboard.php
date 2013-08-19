<?php
include_once "fbmain.php";
include('include/app-common-config.php');
//$expertBoardId=(int)$_GET['eid'];
$cherryboard_id=$_GET['cbid'];
$expertBoardId=(int)getFieldValue('expertboard_id','tbl_app_expert_cherryboard','cherryboard_id='.$cherryboard_id);
$type=trim($_GET['type']);

//START EXPERTBOARD COPY CODE
if($expertBoardId>0&&$type=='copy'&&USER_ID>0&&$cherryboard_id>0){	
	//CHECK USER REGISTER LOGIN OR NOT
	if(USER_ID>0){	
		$userId=(int)getFieldValue('user_id','tbl_app_users','user_id='.USER_ID);
	}
	//CHECK USER HAVE COPY BOARD OR NOT	
    $expTitle=trim(getFieldValue('expertboard_title','tbl_app_expertboard','expertboard_id='.$expertBoardId));
	$recordDate=getFieldValue('date_format(record_date,"%Y-%m-%d") as recordDate','tbl_app_expertboard','expertboard_title="'.$expTitle.'" AND user_id="'.USER_ID.'" ORDER BY expertboard_id DESC LIMIT 1');
	$curDate=date('Y-m-d');
	if($recordDate!=$curDate&&$userId>0){
		//START CREATE EXPORTBOARD
		$selExpBoard=mysql_query("SELECT * FROM tbl_app_expertboard WHERE expertboard_id=".$expertBoardId);
		while($selExpBoardRow=mysql_fetch_array($selExpBoard)){
			$category_id=$selExpBoardRow['category_id'];
			$expertboard_title=trim($selExpBoardRow['expertboard_title']);
			$expertboard_detail=trim(stripslashes($selExpBoardRow['expertboard_detail']));
			$goal_days=$selExpBoardRow['goal_days'];
			$price=$selExpBoardRow['price'];
			$customers=trim($selExpBoardRow['customers']);
			$day_type=$selExpBoardRow['day_type'];
			$is_board_price=$selExpBoardRow['is_board_price'];
			$board_type=$selExpBoardRow['board_type'];
			$living_narrative=$selExpBoardRow['living_narrative'];
			
			//CREATE NEW EXPERTBOARD
			$ip_address=$_SERVER['REMOTE_ADDR'];
			$insExpBoard="INSERT INTO tbl_app_expertboard (expertboard_id,user_id,category_id,expertboard_title, expertboard_detail,goal_days,price,record_date,day_type,is_board_price,board_type,customers,ip_address,living_narrative,copyboard_id) VALUES (NULL,'".(int)USER_ID."','".$category_id."','".$expertboard_title."','".addslashes($expertboard_detail)."','".$goal_days."','".$price."',CURRENT_TIMESTAMP,'".$day_type."','".$is_board_price."','".$board_type."','".$customers."','".$ip_address."','".$living_narrative."','".$expertBoardId."')";
			$insExpBoardRes=mysql_query($insExpBoard);
			$NewExpBoardId=mysql_insert_id();
			//CREATE NEW GOAL DAYS
			if($NewExpBoardId>0){
				$selDays=mysql_query("SELECT * FROM tbl_app_expertboard_days WHERE expertboard_id=".$expertBoardId);
				while($selDaysRow=mysql_fetch_array($selDays)){
					$day_no=$selDaysRow['day_no'];
					$day_title=$selDaysRow['day_title'];
					$insDays="INSERT INTO tbl_app_expertboard_days (expertboard_day_id,expertboard_id,day_no, day_title,record_date) VALUES (NULL,'".$NewExpBoardId."','".$day_no."','".$day_title."',CURRENT_TIMESTAMP)";
					$insDaysRes=mysql_query($insDays);
				}
				//CREATE NEW EXPERT CHERRYBOARD
				//$cherryBoardId=getExpGoalMainId($expertBoardId);
				$insNewExpBoard=mysql_query("INSERT INTO tbl_app_expert_cherryboard 
				(cherryboard_id,user_id,expertboard_id,record_date,main_board,copyboard_id)
			 VALUES (NULL,'".(int)USER_ID."','".$NewExpBoardId."',CURRENT_TIMESTAMP,'1','".$cherryboard_id."')");
				$newCherryBoardId=mysql_insert_id();
				//ADD TO-DO LIST ITEM IN NEW EXPERT CHERRYBOARD				
				$selTodoList=mysql_query("SELECT * FROM tbl_app_expert_checklist WHERE cherryboard_id=".$cherryboard_id);
				while($selTodoListRow=mysql_fetch_array($selTodoList)){
					$insTodoList=mysql_query("INSERT INTO tbl_app_expert_checklist (checklist_id,user_id, cherryboard_id,checklist,record_date,is_checked,is_system) VALUES (NULL,'".(int)USER_ID."','".$newCherryBoardId."','".addslashes($selTodoListRow['checklist'])."',CURRENT_TIMESTAMP,'".$selTodoListRow['is_checked']."','".$selTodoListRow['is_system']."')");
				}
				//ADD EXPERT REWARD PICTURE
				$selExpReward=mysql_query("SELECT * FROM tbl_app_expert_reward_photo WHERE cherryboard_id=".$cherryboard_id);
				while($selExpRewardRow=mysql_fetch_array($selExpReward)){
					$reward_title=$selExpRewardRow['photo_title'];
					$reward_photo=$selExpRewardRow['photo_name'];
					$oldDirPath='images/expertboard/reward/'.$reward_photo;
					$rnd=rand();
					$new_reward_photo=$rnd.'_'.$reward_photo;
					$newDirPath='images/expertboard/reward/'.$new_reward_photo;
					if($_SERVER['SERVER_NAME']=="localhost"){
						$retval=copy($oldDirPath,$newDirPath);				
				    }else{
						$thumb_command=$ImageMagic_Path."convert ".$oldDirPath." -thumbnail 195 x 195 ".$newDirPath;
						$last_line=system($thumb_command,$retval);
				    }
					if($retval){
						$insExpReward=mysql_query("INSERT INTO tbl_app_expert_reward_photo (exp_reward_id,user_id,cherryboard_id,photo_title,photo_name,record_date) VALUES (NULL,'".(int)USER_ID."','".$newCherryBoardId."','".$reward_title."','".$new_reward_photo."',CURRENT_TIMESTAMP)");
					}
				}
				//ADD EXPERT BOARD PICTURE
				$selExpPic=mysql_query("SELECT * FROM tbl_app_expert_cherry_photo WHERE cherryboard_id=".$cherryboard_id);
				while($selExpPicRow=mysql_fetch_array($selExpPic)){
					$photo_title=$selExpPicRow['photo_title'];
					$photo_name=$selExpPicRow['photo_name'];
					$photo_day=$selExpPicRow['photo_day'];
					
					$old_uploaddir='images/expertboard/'.$photo_name;
					$old_uploaddirThumb='images/expertboard/thumb/'.$photo_name;
					$rnd=rand();
					$new_photo_name=$rnd.'_'.$photo_name;//photo_path set in db
					$new_uploaddir='images/expertboard/'.$new_photo_name;
					$new_uploaddirThumb='images/expertboard/thumb/'.$new_photo_name;
					
					if($_SERVER['SERVER_NAME']=="localhost"){
						$retval=copy($old_uploaddir,$new_uploaddir);
						$retval=copy($old_uploaddirThumb,$new_uploaddirThumb);				
				    }else{
						$thumb_command=$ImageMagic_Path."convert ".$old_uploaddir." -thumbnail 195 x 195 ".$new_uploaddir;
						$last_line=system($thumb_command, $retval);
						$thumb_command_thumb=$ImageMagic_Path."convert ".$old_uploaddirThumb." -thumbnail 60 x 60 ".$new_uploaddirThumb;
						$last_line=system($thumb_command_thumb,$retval);
				    }
					if($retval){
					 	$insExpPic=mysql_query("INSERT INTO tbl_app_expert_cherry_photo (photo_id,user_id, cherryboard_id,photo_title,photo_name,photo_day,record_date) VALUES (NULL,'".(int)USER_ID."','".$newCherryBoardId."','".$photo_title."','".$new_photo_name."','".$photo_day."',CURRENT_TIMESTAMP)");
					}
				}
				//SEND MAIL OF THE EXPERTBOARD OWNER
				if($newCherryBoardId>0){
					$UserDetail=getUserDetail(USER_ID,'uid');
					$UserName=$UserDetail['name'];
					$expertDetail=getFieldsValueArray('user_id,expertboard_title','tbl_app_expertboard','expertboard_id='.$expertBoardId);					
					$expertUserId=$expertDetail[0];
					$expertTitle=$expertDetail[1];
					$OwnerDetail=getUserDetail($expertUserId,'uid');
					$OwnerName=$OwnerDetail['name'];
					$expOwner_EmailId=$OwnerDetail['email_id'];
					$to = $expOwner_EmailId;
					$subject = 'Your '.$expertTitle.' is copied.';
					$message = '<table>
								<tr><td>&nbsp;</td></tr>
								<tr><td>Dear '.$OwnerName.',</td></tr>
								<tr><td>&nbsp;</td></tr>
								<tr><td>Your '.$expertTitle.' is copied by '.$UserName.'. 
								<a href="'.SITE_URL.'/expert_cherryboard.php?cbid='.$newCherryBoardId.'"><strong>Click here</strong></a> to see how '.$UserName.' is using it.</td></tr>
								<tr><td>&nbsp;</td></tr>
								<tr><td>Love,</td></tr>
								<tr><td>'.REGARDS.'</td></tr>
								</table>';
					SendMail($to,$subject,$message);
				  echo "<script>document.location='expert_cherryboard.php?cbid=".$newCherryBoardId."'</script>";		
				}
			}
		}
	}
}
//START CREATE EXPERT CODE
if(isset($_POST['btnCreateExpert'])){
	$expertboard_title=trim($_POST['title']);
	$expertboard_detail=trim(addslashes($_POST['detail']));
	$category_id=(int)$_POST['category_id1'];
	$day_type=(int)$_POST['day_type'];
	$number_days=(int)$_POST['number_days'];
	$is_board_price=(int)$_POST['is_board_price'];
	$price=$_POST['price'];
	$board_type=(int)$_POST['board_type'];
	//GET SUB STORY FIELDS VALUE
	$create_from=trim($_POST['create_from']);
	$parent_id=(int)$_POST['parent_id'];
	$cherryboard_parent_id=(int)$_POST['cherryboard_parent_id'];
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
	if((int)USER_ID>0&&$expertboard_title!=''&&$expertboard_detail!=''&&$category_id>0&&$number_days>0){
		$checkExpBoard=(int)getFieldValue('expertboard_id','tbl_app_expertboard','expertboard_title="'.$expertboard_title.'" and user_id='.USER_ID);
		if($checkExpBoard==0){
			$ip_address=$_SERVER['REMOTE_ADDR'];
			$insExpBoard="INSERT INTO tbl_app_expertboard (expertboard_id,user_id,category_id,expertboard_title, expertboard_detail,goal_days,price,record_date,day_type,is_board_price,board_type,customers,ip_address,parent_id,living_narrative) VALUES (NULL,'".(int)USER_ID."','".$category_id."','".$expertboard_title."','".$expertboard_detail."','".$number_days."','".$price."',CURRENT_TIMESTAMP,'".$day_type."','".$is_board_price."','".$board_type."','".$Customers."','".$ip_address."','".$parent_id."','".$living_narrative."')";
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
				if($cherryboard_parent_id>0){	
					$cherryBoardId=$cherryboard_parent_id;
				}else{ $cherryBoardId=0; }						
				$insExpBoard=mysql_query("INSERT INTO tbl_app_expert_cherryboard (cherryboard_id,user_id,expertboard_id, category_id,cherryboard_title,record_date,makeover,qualified,help_people,start_date,price,fb_album_id,main_board,parent_id)
				VALUES (NULL, '".(int)USER_ID."','".$NewexpertBoardId."','0','', CURRENT_TIMESTAMP,'','','','','0','','1','".$cherryBoardId."')");
				$GoalBoardId=mysql_insert_id();
				if($GoalBoardId>0){
					//Create Goal To-Do List
					for($i=1;$i<=$number_days;$i++){
						$insTodo="INSERT INTO tbl_app_expert_checklist (checklist_id,user_id,cherryboard_id, checklist,record_date,is_checked,is_system) VALUES (NULL,'".(int)USER_ID."','".$GoalBoardId."','".$DayType." ".$i."',CURRENT_TIMESTAMP,'0','1')";
						$insTodoSql=mysql_query($insTodo);
					}
					echo "<script>document.location='expert_cherryboard.php?cbid=".$GoalBoardId."'</script>";		
				}
			}	
		}
	}else{
		echo "<script>document.location='index_detail.php'</script>";	
	}
}
//END CREATE EXPERT CODE
//START CREATE EXPERTBOARD BUY(Do-It) CODE
if($expertBoardId>0&&$type=='doit'&&$cherryboard_id>0){
	$lastCreatedId=createExpertboard($expertBoardId,$cherryboard_id);
	echo "<script type=\"text/javascript\">document.location.href='expert_cherryboard.php?cbid=".$lastCreatedId."';</script>";
}
//END CREATE EXPERTBOARD BUY(Do-It) CODE	
?>
<?php include('site_header.php'); ?>	
<!--Body Start-->
<div id="wrapper">
<?php
	$userExpertBoardIds=mysql_query("SELECT expertboard_id FROM tbl_app_expert_cherryboard WHERE user_id=".USER_ID." ORDER BY cherryboard_id");
	
	$userExpertBoardIdsArr=array();
	while($userExpertBoardRows=mysql_fetch_array($userExpertBoardIds)){
		$userExpertboardId=$userExpertBoardRows['expertboard_id'];
		if($userExpertboardId>0){
			$userExpertBoardIdsArr[]=$userExpertboardId;		
		}
	}
	//print_r($userExpertBoardIdsArr);
	if($type=='expert'){
		$sel=mysql_query("SELECT * FROM tbl_app_expertboard WHERE user_id=".USER_ID." OR expertboard_id IN(".implode(',',$userExpertBoardIdsArr).") ORDER BY expertboard_id");
	}else{
		$sel=mysql_query("SELECT * FROM tbl_app_expertboard ORDER BY expertboard_id");
	}	
	$giftCnt='<table border="0"><tr>';
	if(mysql_num_rows($sel)>0){
	    $cnt=1;
		while($row=mysql_fetch_array($sel)){
			if($cnt==7){$giftCnt.='</tr><tr>';$cnt=1;}			
			$user_id=(int)$row['user_id'];
			$expertboard_id=(int)$row['expertboard_id'];
			$expertboard_title=ucwords(trim($row['expertboard_title']));
			$userDetail=getUserDetail($user_id);
			$userOwnerFbId=$userDetail['fb_id'];
			$userName=$userDetail['name'];
			
			//$expertPicPath='images/expert.jpg';
			$expertPicPath='https://graph.facebook.com/'.$userOwnerFbId.'/picture?type=large';
			
				
			$cherryboard_id=(int)getFieldValue('cherryboard_id','tbl_app_expert_cherryboard','expertboard_id="'.$expertboard_id.'" and main_board="0" AND user_id='.USER_ID);
			//expert_profile.php?eid='.$expertboard_id.'
			if($expertPicPath!=""){
				$Owner_cherryboard_id=(int)getFieldValue('cherryboard_id','tbl_app_expert_cherryboard','expertboard_id="'.$expertboard_id.'" and main_board="1" limit 1');
				$giftCnt.='<td><div class="gift_center">
				<a href="expert_cherryboard.php?cbid='.$Owner_cherryboard_id.'">
				<img src="'.$expertPicPath.'" class="imgbig" title="'.$userName.'"></a><br>
				'.$userName.'<br/>
				'.($expertboard_title!=''?'<strong>'.getLimitString($expertboard_title,50).'</strong><br>':'').'<br>';
				/*if($cherryboard_id>0){
				   $giftCnt.='<a href="expert_cherryboard.php?cbid='.$cherryboard_id.'" name="View Goal" class="btn_small" title="View Goal">View Goal</a>';
				}else{*/
				   $giftCnt.='<a href="expert_cherryboard.php?cbid='.$Owner_cherryboard_id.'" name="View" class="btn_small" title="View">View</a>';
				//}
				$giftCnt.='</div></td>';
		    }
			$cnt++;
		}

		for($i=$cnt;$i<=3;$i++){
			$giftCnt.='<td>&nbsp;</td>';
		}
	}else{
		$giftCnt.='<td><strong>'.($type=='expert'?'No Challenge':'No Challenges').'</strong></td>';
	}
	echo $giftCnt.'</tr></table>';
//END EXPERT PART CODE	
  ?>  
<div class="clear"></div>
</div>
<!--Gray body End-->
<!--Body End-->
<?php include('site_footer.php');?> 