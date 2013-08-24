<?php 
error_reporting(0);
header('Content-Type: application/json');
include('include/app-db-connect.php');
include('include/app_functions.php');
$type=$_REQUEST['type'];
$tbl=$_REQUEST['tbl'];
$fb_id=$_REQUEST['fb_id'];
$user_id=getUserId_by_FBid($fb_id);
$tblData=array();

//Vijay FB ID : 100002349398425


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
			$tblData['status']='User Registered';
		}else{
			$tblData['status']="User Exist";
		}
		
		if($user_id>0){
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


if($user_id>0){
		if($type=="add_story_photo"){
		//ADD CHERRYBOARD PHOTO
	   //URL : http://happinesslabs.com/app_services.php?type= add_story_photo&fb_id=&story_id=&photo_title=&photo_day&image_attach=
		$fb_id=$_REQUEST['fb_id'];	
		$cherryboard_id=$_REQUEST['story_id'];	
		$photo_title=$_REQUEST['photo_title'];
		$photo_day=$_REQUEST['photo_day'];
		$photo_name=trim($_FILES['image_attach']['name']);
		$user_id=getUserId_by_FBid($fb_id);	
		
		if($cherryboard_id>0&&$user_id>0&&$photo_name!=""){
			$Photo_Source = $_FILES['image_attach']['tmp_name'];
			$FileName = rand().'_'.$photo_name;
			$ImagePath = "images/cherryboard/".$FileName;
			
			$uploaddir='images/cherryboard/'.$FileName;
			$uploaddirThumb='images/cherryboard/thumb/'.$FileName;
			$old_uploaddir='images/cherryboard/temp/'.$FileName;
			
			$CopyImage=copy($Photo_Source,$old_uploaddir);
			if($CopyImage){
				$thumb_command=$ImageMagic_Path."convert ".$old_uploaddir." -thumbnail 195 x 195 ".$uploaddir;
				$last_line=system($thumb_command, $retval);
				$thumb_command_thumb=$ImageMagic_Path."convert ".$old_uploaddir." -thumbnail 60 x 60 ".$uploaddirThumb;
				$last_line=system($thumb_command_thumb, $retval);
				
				//update day
				$TotalPhoto=(int)getFieldValue('count(photo_id)','tbl_app_expert_cherry_photo','photo_day='.$photo_day.' and cherryboard_id='.$cherryboard_id);
				$sub_day=0;
				if($TotalPhoto>0){
					$expertboard_id=getFieldValue('expertboard_id','tbl_app_expert_cherryboard','cherryboard_id='.$cherryboard_id);
					$sub_day=($TotalPhoto+1);
					$insDay="INSERT INTO `tbl_app_expertboard_days` (`expertboard_day_id`, `expertboard_id`, `day_no`, `day_title`, `record_date`, `sub_day`) VALUES (NULL, '".$expertboard_id."', '".$photo_day."', 'Day ".$photo_day.".".$sub_day."','".date('Y-m-d')."', '".$sub_day."')";
					$insSql=mysql_query($insDay);
				}
				//update photo
				$insMeb="INSERT INTO `tbl_app_cherry_photo` (`photo_id`, `user_id`, `cherryboard_id`, `photo_name`, photo_title) VALUES (NULL, '".$user_id."', '".$cherryboard_id."', '".$FileName."', '".$photo_title."')";
				$insMebSql=mysql_query($insMeb);
				if($insMebSql){
					$tblData[]='Photo Inserted';
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
					$insExpBoard=mysql_query("INSERT INTO tbl_app_expert_cherryboard (cherryboard_id,user_id,expertboard_id, category_id,cherryboard_title,record_date,makeover,qualified,help_people,start_date,price,fb_album_id,main_board)
					VALUES (NULL, '".(int)$user_id."', '".$NewexpertBoardId."','0','', CURRENT_TIMESTAMP,'','','','','0','','1')");
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
//https://happinesslabs.com/app_services.php?type=copy_story&fb_id=100002349398425&story_id=47

	$cherryboard_id=$_GET['story_id'];
	$expertBoardId=(int)getFieldValue('expertboard_id','tbl_app_expert_cherryboard','cherryboard_id='.$cherryboard_id);
   if($cherryboard_id>0&&$expertBoardId>0&&$user_id>0){
	//CHECK USER HAVE COPY BOARD OR NOT	
    $expTitle=trim(getFieldValue('expertboard_title','tbl_app_expertboard','expertboard_id='.$expertBoardId));
	$recordDate=getFieldValue('date_format(record_date,"%Y-%m-%d") as recordDate','tbl_app_expertboard','expertboard_title="'.$expTitle.'" AND user_id="'.$user_id.'" ORDER BY expertboard_id DESC LIMIT 1');
	$curDate=date('Y-m-d');
	if($recordDate!=$curDate){
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
			$insExpBoard="INSERT INTO tbl_app_expertboard (expertboard_id,user_id,category_id,expertboard_title, expertboard_detail,goal_days,price,record_date,day_type,is_board_price,board_type,customers,ip_address,living_narrative,copyboard_id) VALUES (NULL,'".(int)$user_id."','".$category_id."','".$expertboard_title."','".addslashes($expertboard_detail)."','".$goal_days."','".$price."',CURRENT_TIMESTAMP,'".$day_type."','".$is_board_price."','".$board_type."','".$customers."','".$ip_address."','".$living_narrative."','".$expertBoardId."')";
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
			 VALUES (NULL,'".(int)$user_id."','".$NewExpBoardId."',CURRENT_TIMESTAMP,'1','".$cherryboard_id."')");
				$newCherryBoardId=mysql_insert_id();
				//ADD TO-DO LIST ITEM IN NEW EXPERT CHERRYBOARD				
				$selTodoList=mysql_query("SELECT * FROM tbl_app_expert_checklist WHERE cherryboard_id=".$cherryboard_id);
				while($selTodoListRow=mysql_fetch_array($selTodoList)){
					$insTodoList=mysql_query("INSERT INTO tbl_app_expert_checklist (checklist_id,user_id, cherryboard_id,checklist,record_date,is_checked,is_system) VALUES (NULL,'".(int)$user_id."','".$newCherryBoardId."','".addslashes($selTodoListRow['checklist'])."',CURRENT_TIMESTAMP,'".$selTodoListRow['is_checked']."','".$selTodoListRow['is_system']."')");
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
						$insExpReward=mysql_query("INSERT INTO tbl_app_expert_reward_photo (exp_reward_id,user_id,cherryboard_id,photo_title,photo_name,record_date) VALUES (NULL,'".(int)$user_id."','".$newCherryBoardId."','".$reward_title."','".$new_reward_photo."',CURRENT_TIMESTAMP)");
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
					 	$insExpPic=mysql_query("INSERT INTO tbl_app_expert_cherry_photo (photo_id,user_id, cherryboard_id,photo_title,photo_name,photo_day,record_date) VALUES (NULL,'".(int)$user_id."','".$newCherryBoardId."','".$photo_title."','".$new_photo_name."','".$photo_day."',CURRENT_TIMESTAMP)");
					}
				}
				//SEND MAIL OF THE EXPERTBOARD OWNER
				if($newCherryBoardId>0){
					$UserDetail=getUserDetail($user_id,'uid');
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
					$tblData['status']='Story Created Successfully';
				}
			}else { $tblData['status']='Story Main Board Error';}
		}
	}else { $tblData['status']='Same Date Validation';}
  }else{
  	$tblData['status']='Invalid Data';
  }	

}
	//END EXPERTBOARD COPY CODE

	//START DOIT CODE
	if($type=='doit'){
		//https://happinesslabs.com/app_services.php?type=doit&fb_id=100002349398425&story_id=346
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
	
	
}else{
	$tblData[]='Invalid User';
}

//print_r($tblData)."<br>";
$jsonData=array(array("data"=>$tblData));
$jsonData=json_encode($jsonData);
$jsonData=substr($jsonData,1);
$jsonData=substr($jsonData,0,(strlen($jsonData)-1));

echo $jsonData;
?>
