<?php 
error_reporting(0);
header('Content-Type: application/json');
include('include/app-db-connect.php');
include('include/app_functions.php');
$type=$_REQUEST['type'];
$tbl=$_REQUEST['tbl'];
$tblData=array();
//ADD USER
//URL : http://happinesslabs.com/app_services.php?type=add_user&fb_id=&first_name=&last_name=&email=&fb_photo_url=&location=
if($type=="add_user"){	
	$fb_id=$_REQUEST['fb_id'];
	$first_name=$_REQUEST['first_name'];
	$last_name=$_REQUEST['last_name'];
	$email=$_REQUEST['email'];
	$fb_photo_url=$_REQUEST['fb_photo_url'];
	$location=$_REQUEST['location'];
	if($fb_id!=""&&$first_name!=""&&$last_name!=""&&$email!=""){
		$check_user=mysql_query("select user_id from tbl_app_users where facebook_id='".$fb_id."'");
		$exist_user=(int)mysql_num_rows($check_user);
		if($exist_user==0){
			$ins_query="INSERT INTO `tbl_app_users` (`user_id`, `first_name`, `last_name`, `email_id`, `facebook_id`, `join_date`, fb_photo_url, location) VALUES (NULL, '".$first_name."', '".$last_name."', '".$email."', '".$fb_id."', '".date('Y-m-d')."', '".$fb_photo_url."', '".$location."')";
			
			$ins_sql=mysql_query($ins_query) or die(mysql_error());
			if($ins_query){
				$tblData[]="Inserted";
			}
		}else{
			$tblData[]="Exist";
		}	
	}else{
		$tblData[]="Invalid Data";
	}
}
//ADD CHERRYBOARD 1 Way
//URL : http://happinesslabs.com/app_services.php?type=add_cherryboard&tp=ad&resolution_title=&fb_id=&category_id=
//&tp=ad
if($type=="add_cherryboard"){	
	$resolution_title=$_REQUEST['resolution_title'];
	$fb_id=$_REQUEST['fb_id'];
	$category_id=$_REQUEST['category_id'];
	
	if($resolution_title!=""&&$fb_id!=""){
		$cherryboard_id=(int)getFieldValue('cherryboard_id','tbl_app_cherryboard','cherryboard_title="'.$resolution_title.'"');
		if($cherryboard_id==0){
			$user_id=getUserId_by_FBid($fb_id);	
			$insRes="INSERT INTO `tbl_app_cherryboard` (`cherryboard_id`, `user_id`, `cherryboard_title`,category_id) VALUES (NULL, '".$user_id."', '".addslashes($resolution_title)."', '".$category_id."')";
			$insSql=mysql_query($insRes);
			if($insSql){
				$tblData[]='Inserted';
				$tblData[]=mysql_insert_id();
			}
		}else{
			$tblData[]='Exist';
		}	
	}else{
		$tblData[]='Invalid Data';
	}	
}
//ADD CHERRYBOARD 2 Way [With Gift]
//URL : http://happinesslabs.com/app_services.php?type=add_setup_cherryboard&&fb_id=&cherryboard_id=&cat_id=&gift_id=
if($type=="add_setup_cherryboard"){	
	$cherryboard_id=$_REQUEST['cherryboard_id'];
	$fb_id=$_REQUEST['fb_id'];
	$user_id=getUserId_by_FBid($fb_id);
	$cat_id=$_REQUEST['cat_id'];
	$gift_id=$_REQUEST['gift_id'];
	
	
	if($cherryboard_id>0&&$cat_id>0&&$fb_id!=""&&$gift_id>0){
		  		//CREATE CHERRYBOARD
				$CherryboardArr=getFieldsValueArray('cherryboard_title','tbl_app_system_cherryboard','cherryboard_id='.$cherryboard_id);
				$resolution_title=$CherryboardArr[0];
				$insRes="INSERT INTO `tbl_app_cherryboard` (`cherryboard_id`, `user_id`, `cherryboard_title`,category_id) VALUES (NULL, '".$user_id."', '".$resolution_title."','".$cat_id."')";
				$insSql=mysql_query($insRes);
				$new_cherryboard_id=mysql_insert_id();
				
				//CREATE GIFT
				$insGift="INSERT INTO `tbl_app_cherry_gift` (`cherry_gift_id`, `gift_id`, `cherryboard_id`, `user_id`, `record_date`) VALUES (NULL, '".$gift_id."', '".$new_cherryboard_id."', '".$user_id."', CURRENT_TIMESTAMP)";					mysql_query($insGift);
				//CREATE CHECKLIST
				$selChk=mysql_query("select checklist from tbl_app_system_checklist where cherryboard_id=".$cherryboard_id);
				while($selChkRow=mysql_fetch_array($selChk)){
						$insChecklist="INSERT INTO `tbl_app_checklist` (`checklist_id`,user_id, `cherryboard_id`, `checklist`, `record_date`, `is_checked`) VALUES (NULL, '".$user_id."', '".$new_cherryboard_id."', '".$selChkRow['checklist']."', CURRENT_TIMESTAMP, '0')";
						mysql_query($insChecklist);
				}
				//ADD PHOTO INTO CHERRYBOARD
				$photo_name=trim($_FILES['image_attach']['name']);
				if($photo_name!=""){
					$Photo_Source = $_FILES['image_attach']['tmp_name'];
					$FileName = rand().'_'.$photo_name;
					$old_uploaddir = "images/cherryboard/".$FileName;
					$CopyImage=copy($Photo_Source,$old_uploaddir);
					
					if($CopyImage){
						$uploaddir='images/cherryboard/'.$FileName;
						$uploaddirThumb='images/cherryboard/thumb/'.$FileName;
						//Image magic
						$thumb_command=$ImageMagic_Path."convert ".$old_uploaddir." -thumbnail 195 x 195 ".$uploaddir;
						$last_line=system($thumb_command, $retval);
						$thumb_command_thumb=$ImageMagic_Path."convert ".$old_uploaddir." -thumbnail 60 x 60 ".$uploaddirThumb;
						$last_line=system($thumb_command_thumb, $retval);
					}
					
					if($CopyImage){
						$insMeb="INSERT INTO `tbl_app_cherry_photo` (`photo_id`, `user_id`, `cherryboard_id`, `photo_name`) VALUES (NULL, '".$user_id."', '".$new_cherryboard_id."', '".$FileName."')";
						$insMebSql=mysql_query($insMeb);
						if($insMebSql){
							$tblData['id']=$new_cherryboard_id;
							$tblData['title']=$resolution_title;
							$tblData[]='Inserted';
						}
					}else{
						$tblData[]='Photo Error';		
					}
				}
					
	  }else{
		$tblData[]='Invalid Data';
	}	
}

//ADD CHERRYBOARD MEMBER
//URL : http://happinesslabs.com/app_services.php?type=add_cherryboard_meb&cherryboard_id=&fb_id=&req_user_fb_id=
if($type=="add_cherryboard_meb"){	
	$cherryboard_id=$_REQUEST['cherryboard_id'];
	$fb_id=$_REQUEST['fb_id'];	
	$req_user_fb_id=$_REQUEST['req_user_fb_id'];
	
	if($cherryboard_id>0&&$fb_id!=""&&$req_user_fb_id!=""){
		$user_id=getUserId_by_FBid($fb_id);
		$insMeb="INSERT INTO `tbl_app_cherryboard_meb` (`meb_id`, `cherryboard_id`, `user_id`, `req_user_fb_id`, `is_accept`) VALUES (NULL, '".$cherryboard_id."', '".$user_id."', '".$req_user_fb_id."', '0')";
		$insMebSql=mysql_query($insMeb);
		$TotalMeb=(int)getFieldValue('count(meb_id)','tbl_app_cherryboard_meb','cherryboard_id='.$cherryboard_id);
		if($TotalMeb<=10){
			if($insMebSql){
				$tblData[]='Inserted';
			}
		}else{
			$tblData[]='OutOfLimit';
		}	
	}else{
		$tblData[]='Invalid Data';
	}
}
//ADD CHERRYBOARD PHOTO
//URL : http://happinesslabs.com/app_services.php?type=add_story_photo&fb_id=&story_id=&photo_title=&photo_day&image_attach=
if($type=="add_story_photo"){
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

//ADD PHOTO COMMENT
//URL : http://happinesslabs.com/app_services.php?type=photo_comment&fb_id=&cherryboard_id=&photo_id=&photo_cmt=
if($type=="photo_comment"){	
	$fb_id=$_REQUEST['fb_id'];	
	$cherryboard_id=$_REQUEST['cherryboard_id'];	
	$photo_id=$_REQUEST['photo_id'];
	$photo_cmt=addslashes($_REQUEST['photo_cmt']);
	
	if($cherryboard_id>0&&$photo_cmt!=""&&$photo_id>0&&$fb_id!=""){
			$user_id=getUserId_by_FBid($fb_id);	
			$ins_query="INSERT INTO `tbl_app_cherry_comment` (`comment_id`, `cherryboard_id`, `photo_id`, `user_id`, `cherry_comment`) VALUES (NULL, '".$cherryboard_id."', '".$photo_id."', '".$user_id."', '".$photo_cmt."')";
			$ins_sql=mysql_query($ins_query);
			if($insMebSql){
				$tblData[]='Inserted';
			}else{
				$tblData[]='Error';
			}
	}else{
		$tblData[]='Invalid Data';
	}
}
if($type=="upd_photo_title"){	
	$photo_id=$_REQUEST['photo_id'];
	$photo_title=addslashes($_REQUEST['photo_title']);
	
	if($photo_id>0&&$photo_title!=""){
			$upd_query="UPDATE `tbl_app_cherry_photo` set photo_title='".$photo_title."' where photo_id=".$photo_id;
			$upd_Sql=mysql_query($upd_query);
			if($upd_Sql){
				$tblData[]='Updated';
			}else{
				$tblData[]='Error';
			}
	}else{
		$tblData[]='Invalid Data';
	}
}

//START INSERT EXPERT BOARD
//URL : http://happinesslabs.com/app_services.php?type=add_exp_board&fb_id=[fb_id]&title=[title]&detail=[detail]&category_id=[category_id]&day_type=[day_type]&number_of_days=[number_of_days]&is_board_price=[is_board_price]&board_price=[Board-Price]&board_type=[Board-type]

if($type=="add_exp_board"){	
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
	$parent_id=0;
	if($day_type==4){
		$day_type=1;
		$number_days=1;
	}
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

//Insert all common type of fields
//URL : http://happinesslabs.com/app_services.php?type=insert&tbl=users&fields=field1|-|field2|-|field3&values=123|-|456|-|789
if($type=="insert"){
	$fields=$_REQUEST['fields'];
	$values=$_REQUEST['values'];
	$fields_Array=explode('|-|',$fields);
	$value_Array=explode('|-|',$values);
	if($tbl!=""&&count($fields_Array)==count($value_Array)){
		$fields_data=implode(',',$fields_Array);
		$value_data="";
		$cnt=0;
		foreach($value_Array as $fieldValue){
			if($fields_Array[$cnt]=="fb_id"){
				$fieldValue1=getUserId_by_FBid($fieldValue);	
			}
		
			if($value_data!=""){$value_data.=",";}
			$value_data.="'".mysql_real_escape_string($fieldValue1)."'";
			$cnt++;
		}
		$insQuery="INSERT INTO ".$tbl." (".$fields_data.") VALUES (".$value_data.")";
		$insQuerySql=mysql_query($insQuery);
		if($insQuerySql){
			$tblData[]='Inserted';
			$tblData[]=mysql_insert_id();
		}else{
			echo 'Error';
		}
	}else{
		echo 'Invalid Data';
	}

}

//update goal location
//URL : http://happinesslabs.com/app_services.php?type=checkin_location&fb_id=&cherryboard_id=&location=
if($type=="checkin_location"){	
	$cherryboard_id=(int)$_REQUEST['cherryboard_id'];
	$location=addslashes($_REQUEST['location']);
	$fb_id=$_REQUEST['fb_id'];
	$user_id=getUserId_by_FBid($fb_id);	
	
	if($cherryboard_id>0&&$location!=""&&$user_id>0){
			$upd_query="INSERT INTO `tbl_app_cherry_checkin` (`location_id`, `user_id`, `cherryboard_id`, `location`, `checkin_time`) VALUES (NULL, '".$user_id."', '".$cherryboard_id."', '".$location."', '".date('Y-m-d H:i:s')."')";
			$upd_sql=mysql_query($upd_query);
			if($upd_sql){
				$tblData[]='Updated';
			}else{
				$tblData[]='Error';
			}
	}else{
		$tblData[]='Invalid Data';
	}
}

//print_r($tblData)."<br>";
$jsonData=array(array("data"=>$tblData));
$jsonData=json_encode($jsonData);
$jsonData=substr($jsonData,1);
$jsonData=substr($jsonData,0,(strlen($jsonData)-1));

echo $jsonData;
?>
