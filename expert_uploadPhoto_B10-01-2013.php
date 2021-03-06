<?php
include_once "fbmain.php";
include('include/app-common-config.php');
require('include/instagraph.php');
error_reporting(0);
$type=$_REQUEST['type'];
$cherryboard_id=$_REQUEST['cherryboard_id'];
$mainExpCherryId=(int)getFieldValue('cherryboard_id','tbl_app_expert_cherryboard','cherryboard_id="'.$cherryboard_id.'" and main_board="1"');
$expertboard_id=getFieldValue('expertboard_id','tbl_app_expert_cherryboard','cherryboard_id='.$cherryboard_id);
$expOwner_id=getFieldValue('user_id','tbl_app_expertboard','expertboard_id='.$expertboard_id);
$checkIsExpertBoard=(int)getFieldValue('cherryboard_id','tbl_app_expert_cherryboard','expertboard_id='.$expertboard_id.' and user_id='.$_SESSION['USER_ID']);
$DayType=getDayType($expertboard_id);
//Buyer Detail
$Buyer_id=getFieldValue('user_id','tbl_app_expert_cherryboard','cherryboard_id='.$cherryboard_id);
$BuyerDetail=getUserDetail($Buyer_id);
$BuyerName=$BuyerDetail['name'];
$BuyerPic=$BuyerDetail['photo_url'];

$new_rotate_degree=90;
$rotate_img='round_arrow_90.jpg';

$uploaddir='images/expertboard/temp/';
$fname=$_FILES['uploadfile']['name'];
$fsize=$_FILES['uploadfile']['size'];
$fname=str_replace(' ','_',$fname);
$fname=str_replace('-','_',$fname);
$fname=str_replace('(','_',$fname);
$fname=str_replace(')','_',$fname);
$fname=rand().'_'.$fname;
$file = $uploaddir.$fname; 
$MAX_FILE_SIZE=3145728;//3MB Size
//photo cancel
if($fsize>$MAX_FILE_SIZE){
	$message = 'File too large. File allowed must be less than 3 megabytes.'; 
    echo '<script type="text/javascript">alert("'.$message.'");</script>';
	exit(0);
}else{
	if (move_uploaded_file($_FILES['uploadfile']['tmp_name'],$file)) {
	$_SESSION['fname']=$fname; 
    echo "<div class=\"comment_box\">
		<Table>
		<tr>
		  <td colspan=\"2\">
			  <div id=\"files\"><img src=\"".$file."\" alt=\"\" height=\"100\" width=\"100\" class=\"image\" /></div><br/><span class=\"comment_txt1\" style=\"font-size:10px;margin-left: 2px;\">Max allowed 3MB </span>
		  </td>
		  <td>
			<textarea name=\"txtcomment\" rows=\"5\" class=\"textfield\" id=\"txtcomment\" onfocus=\"if(this.value=='Write your comment here...') this.value='';\" onblur=\"if(this.value=='') this.value='Write your comment here...';\">Write your comment here...</textarea> 
		  </td>
		  <td valign=\"top\" rowspan=\"2\">
			 ".displayFiltersImgs('expert')."
		  </td>
		</tr>
		<tr>
		<td><img src=\"images/round_arrow_90.jpg\" style=\"cursor:pointer\" onclick=\"rotate_photo('expert','".$fname."','90')\" alt=\"\" width=\"35\" height=\"35\" id=\"rotate_img\" />&nbsp;</td>
		 <td>
		  <div class=\"styleall\"><a href=\"javascript:void(0);\" onclick=\"photo_cancel('expert','".$fname."')\" class=\"right gray_link\">
			  <img src=\"images/close_small1.png\"> Cancel</a>
			  </div>
		 </td>
		 <td><input name=\"button\" type=\"button\" onclick=\"add_photo('expert','".$fname."')\" value=\"Post\" title=\"Post\" class=\"btn_small right\"></td>
	   </tr>
		</table>
			  <div class=\"clear\"></div></div>";
		exit(0);
	}
}
if($type=="cancel"){
	unlink($_REQUEST['file_name']);
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
	  <td valign=\"top\" rowspan=\"2\">
			".displayFiltersImgs('expert')."
	   </td>
	</tr>
	<tr>
	 <td><img src=\"images/".$rotate_img."\" style=\"cursor:pointer\" onclick=\"rotate_photo('expert','".$newFileName."','".($new_rotate_degree)."')\" alt=\"\" height=\"35\" id=\"rotate_img\" /></td>
	 <td>
	  <div class=\"styleall\"><a href=\"javascript:void(0);\" onclick=\"photo_cancel('expert','".$newFileName."')\" class=\"right gray_link\">
		  <img src=\"images/close_small1.png\"> Cancel</a>
		  </div>
     </td>
	 <td><input name=\"button\" type=\"button\" onclick=\"add_photo('expert','".$newFileName."')\" value=\"Post\" title=\"Post\" class=\"btn_small right\"></td>
   </tr>
	</table>
		  <div class=\"clear\"></div></div>";
	exit(0);
}else if($type=="filter") {
	$file_name=$_GET['file_name'];
	$txtcomment=$_GET['txtcomment'];
	$filter_type=$_GET['filter_type'];
	$rotate_img='round_arrow_90.jpg';
	$new_rotate_degree=90;
    
	$uploadPath = $uploaddir.$file_name;
	$newFileName=rand().'_'.$file_name;
	$uploadNewPath = $uploaddir.$newFileName;
	
	//Filter Image
    if($filter_type!=""){
		try
		{
			$instagraph = Instagraph::factory($uploadPath, $uploadNewPath);
		}
		catch (Exception $e) 
		{
			echo $e->getMessage();
			die;
		}
		 
		if($filter_type=="effect1"){ 
			$instagraph->effect1();
		}else if($filter_type=="effect2"){ 
			$instagraph->effect2();
		}else if($filter_type=="effect3"){ 
			$instagraph->effect3();
		}else if($filter_type=="effect4"){ 
			$instagraph->effect4();
		}else if($filter_type=="effect5"){ 
			$instagraph->effect5();
		}else{
			//LIKE AS ORIGNAL
			$instagraph->effect0();
		}
	}
	
	echo "<div class=\"comment_box\">
	<Table>
	<tr>
	  <td colspan=\"2\">
		  <div id=\"files\"><img src=\"".$uploadNewPath."\" alt=\"\" height=\"100\" width=\"100\" class=\"image\" /></div><span class=\"comment_txt1\" style=\"font-size:10px;margin-left: 2px;\">Max allowed 3MB</span>
      </td>
	  <td>
		<textarea name=\"txtcomment\" rows=\"5\" class=\"textfield\" id=\"txtcomment\" onfocus=\"if(this.value=='Write your comment here...') this.value='';\" onblur=\"if(this.value=='') this.value='Write your comment here...';\">".$txtcomment."</textarea> 
	  </td>
	   <td valign=\"top\" rowspan=\"2\">
			  ".displayFiltersImgs('expert')."
	   </td>
	</tr>
	<tr>
	 <td><img src=\"images/".$rotate_img."\" style=\"cursor:pointer\" onclick=\"rotate_photo('expert','".$newFileName."','".($new_rotate_degree)."')\" alt=\"\" height=\"35\" id=\"rotate_img\" /></td>
	 <td>
	  <div class=\"styleall\"><a href=\"javascript:void(0);\" onclick=\"photo_cancel('expert','".$newFileName."')\" class=\"right gray_link\">
		  <img src=\"images/close_small1.png\"> Cancel</a>
		  </div>
     </td>
	 <td><input name=\"button\" type=\"button\" onclick=\"add_photo('expert','".$newFileName."')\" value=\"Post\" title=\"Post\" class=\"btn_small right\"></td>
   </tr>
	</table>
		  <div class=\"clear\"></div></div>";
	exit(0);
}//End of if
//=================> Start Insert Code <===================
if($type=="expert_add"||$type=="edit_exp_story_pic"){
   $rnd=rand();
   $file_name=$_REQUEST['file_name'];
   $user_id=$_REQUEST['user_id'];
   $comment=$_REQUEST['comment'];
   $photo_day=$_REQUEST['photo_day'];
   $story_photo_id=$_REQUEST['story_photo_id'];
   $photo_name=$rnd.'_'.$file_name;//photo_path set in db
   $uploaddir='images/expertboard/'.$photo_name;
   $uploadProfileSlide='images/expertboard/profile_slide/'.$photo_name;
   $uploaddirThumb='images/expertboard/thumb/'.$photo_name;
   $uploaddirSliderThumb='images/expertboard/slider/'.$photo_name;
   $old_uploaddir='images/expertboard/temp/'.$file_name;
   
   //for local due to ImageMagic not working in local
   if($_SERVER['SERVER_NAME']=="localhost"){
   		$retval=copy($old_uploaddir,$uploaddir);
		$retval=copy($old_uploaddir,$uploadProfileSlide);
		$retval=copy($old_uploaddir,$uploaddirThumb);
		$retval=copy($old_uploaddir,$uploaddirSliderThumb);
   }else{
   		//profile page part
	    $thumb_command=$ImageMagic_Path."convert ".$old_uploaddir." -thumbnail 219 x 219 ".$uploaddir;
		$last_line=system($thumb_command, $retval);
		//profile multiple 2 time
	    $thumb_command=$ImageMagic_Path."convert ".$old_uploaddir." -thumbnail 438 x 438 ".$uploadProfileSlide;
		$last_line=system($thumb_command, $retval);
   		//thumb part
		$thumb_command_thumb=$ImageMagic_Path."convert ".$old_uploaddir." -thumbnail 60 x 60 ".$uploaddirThumb;
		$last_line=system($thumb_command_thumb, $retval);
		//Slider Part
		$imgInfo1=getImageRatio($old_uploaddir,900,500);
		$NewImgW1=$imgInfo1['width'];
		$NewImgH1=$imgInfo1['height'];
		$thumb_command_slide_thumb=$ImageMagic_Path."convert ".$old_uploaddir." -thumbnail ".$NewImgW1." x ".$NewImgH1." ".$uploaddirSliderThumb;
		$last_line=system($thumb_command_slide_thumb, $retval1);
		
		//Adding Empty BG with photo
		if($NewImgW1<(900-80)||$NewImgW1<(500-60)){
			$bg_command_slide_thumb="convert images/expertboard/slider_emptyBG.jpg -gravity Center ".$uploaddirSliderThumb." -compose Over -composite ".$uploaddirSliderThumb;
			$last_line=system($bg_command_slide_thumb, $retval1);
		}
   }
   if($retval){
	  if($comment=="Write your comment here..."){
		 $comment='';
	  }
	  if($type=="expert_add"){
	    //START ADD STORY PICTURE
		$expertboard_id=getFieldValue('expertboard_id','tbl_app_expert_cherryboard','cherryboard_id='.$cherryboard_id);
		$TotalPhoto=(int)getFieldValue('count(photo_id)','tbl_app_expert_cherry_photo','photo_day='.$photo_day.' and cherryboard_id='.$cherryboard_id);
		$sub_day=0;
		if($TotalPhoto>0){
			$sub_day=($TotalPhoto+1);
			$insDay="INSERT INTO `tbl_app_expertboard_days` (`expertboard_day_id`, `expertboard_id`, `day_no`, `day_title`, `record_date`, `sub_day`) VALUES (NULL, '".$expertboard_id."', '".$photo_day."', 'Day ".$photo_day.".".$sub_day."','".date('Y-m-d')."', '".$sub_day."')";
			$insSql=mysql_query($insDay);
		}
		
    	$insert_qry="INSERT INTO `tbl_app_expert_cherry_photo`(`photo_id`, `user_id`, `cherryboard_id`, `photo_title`, `photo_name`,photo_day,sub_day) VALUES ('',".$user_id.",".$cherryboard_id.",'".$comment."','".$photo_name."','".$photo_day."','".$sub_day."')";			   
		$insert_qry_res=mysql_query($insert_qry);
		//START UPLOAD PHOTO NOTIFICATION SEND TO STORYBOARD FRIENDS
		if($insert_qry_res){
		   $sel_meb=mysql_query("SELECT req_user_fb_id FROM tbl_app_expert_cherryboard_meb WHERE cherryboard_id=".$cherryboard_id." AND is_accept='1' AND user_id=".$user_id);
		   if(mysql_num_rows($sel_meb)>0){
		   	  while($sel_meb_rows=mysql_fetch_array($sel_meb)){
				$req_user_fb_id=trim($sel_meb_rows['req_user_fb_id']);
				$requestUserId=(int)getFieldValue('user_id','tbl_app_users','facebook_id="'.$req_user_fb_id.'"');
				$UserDetail=getUserDetail($requestUserId);
				$requestUserName=$UserDetail['name'];
				$requestUserEmailId=$UserDetail['email_id'];
				$storyTitle=ucwords(trim(getFieldValue('expertboard_title','tbl_app_expertboard','expertboard_id='.$expertboard_id)));
				//Owner Details
				$OwnerDetail=getUserDetail($expOwner_id);
				$OwnerName=$OwnerDetail['name'];
				$OwnerPic=$OwnerDetail['photo_url'];
				$pictureCnt='<html><head></head><body><img src="'.$OwnerPic.'" height="20" width="20" />
				</body></html>';
				//SEND EMAIL CODE
				$to = $requestUserEmailId;
				$subject = 'Picture Uploded To "'.$storyTitle.'"';
				$message = '<table>
							<tr><td>&nbsp;</td></tr>
							<tr><td>Dear '.$requestUserName.',</td></tr>
							<tr><td>&nbsp;</td></tr>
							<tr><td>&nbsp;'.$OwnerName.'&nbsp;&nbsp;'.$pictureCnt.'&nbsp;uploded picture<a href="'.SITE_URL.'/expert_cherryboard.php?cbid='.$cherryboard_id.'"><strong>Click here</strong></a> to see the picture.</td></tr>
							<tr><td>&nbsp;</td></tr>
							<tr><td>Love,</td></tr>
							<tr><td>'.REGARDS.'</td></tr>
							</table>';
				SendMail($to,$subject,$message);	
			  }
		   }
		}
		$insert_last_id=mysql_insert_id();
		$_SESSION['insert_photo_id']=$insert_last_id;
	 }else{	
	 	//START CHANGE STORY PICTURE
	 	$expStoryPic=trim(getFieldValue('photo_name','tbl_app_expert_cherry_photo','photo_id='.$story_photo_id));
		$expStoryPicPath='images/expertboard/'.$expStoryPic;
		$expStoryPicProfile='images/expertboard/profile_slide/'.$expStoryPic;
   		$expStoryPicThumb='images/expertboard/thumb/'.$expStoryPic;
   		$expStoryPicSlider='images/expertboard/slider/'.$expStoryPic;
		$updtQry="UPDATE tbl_app_expert_cherry_photo SET photo_title='".$comment."',photo_name='".$photo_name."' WHERE photo_id=".$story_photo_id;
			   
		$updtQryRes=mysql_query($updtQry);
		if($updtQryRes){
		  	unlink($expStoryPicPath);
			unlink($expStoryPicProfile);
			unlink($expStoryPicThumb);
			unlink($expStoryPicSlider);
		}				
	 }
		unlink($old_uploaddir);
		echo $type.'##===##'.$cherryboard_id;
		exit(0);
   }else{
    echo "Photo Inserting Error...";
	unlink($_REQUEST['file_name']);
   }
}
//START CHANGE EXPERT PROFILE PICTURE 
if($type=="add_exp_profile_pic"){	
   $rnd=rand();
   $file_name=$_REQUEST['file_name'];
   $user_id=$_REQUEST['user_id'];
   $comment=$_REQUEST['comment'];
   $photo_name=$rnd.'_'.$file_name;//photo_path set in db
   $uploaddir='images/expertboard/profile/'.$photo_name;
   $old_uploaddir='images/expertboard/temp/'.$file_name;
   
   //for local due to ImageMagic not working in local
   if($_SERVER['SERVER_NAME']=="localhost"){
   		$retval=copy($old_uploaddir,$uploaddir);
   }else{
	    $thumb_command=$ImageMagic_Path."convert ".$old_uploaddir." -thumbnail 180 x 180 ".$uploaddir;
		$last_line=system($thumb_command, $retval);
   }
   if($retval){
  	 $profilePic=trim(getFieldValue('profile_picture','tbl_app_expertboard','expertboard_id='.$expertboard_id));
	 	$profilePicPath='images/expertboard/profile/'.$profilePic;	
	 
   		if($comment=="Write your comment here..."){
			$comment='';
		}
		
    	$updtQry="UPDATE tbl_app_expertboard SET profile_picture='".$photo_name."',picture_title='".$comment."' WHERE expertboard_id=".$expertboard_id;
			   
		$updtQryRes=mysql_query($updtQry);
		unlink($profilePicPath);
		unlink($old_uploaddir);
		echo $type.'##===##'.$cherryboard_id;
		exit(0);
   }else{
    echo "Photo Inserting Error...";
	unlink($_REQUEST['file_name']);
   }
}
//START ADD EXPERT REWARD PICTURE
if($type=="add_exp_reward_pic"){
   $rnd=rand();
   $file_name=$_REQUEST['file_name'];
   $user_id=$_REQUEST['user_id'];
   $comment=$_REQUEST['comment'];
   $photo_name=$rnd.'_'.$file_name;//photo_path set in db
   $uploaddir='images/expertboard/reward/'.$photo_name;
   $old_uploaddir='images/expertboard/temp/'.$file_name;
   
   //for local due to ImageMagic not working in local
   if($_SERVER['SERVER_NAME']=="localhost"){
   		$retval=copy($old_uploaddir,$uploaddir);
   }else{
	    $thumb_command=$ImageMagic_Path."convert ".$old_uploaddir." -thumbnail 180 x 180 ".$uploaddir;
		$last_line=system($thumb_command, $retval);
   }
   if($retval){
   		if($comment=="Write your comment here..."){
			$comment='';
		}
		
		$insExpReward="INSERT INTO tbl_app_expert_reward_photo
		(exp_reward_id,user_id,cherryboard_id,photo_title, photo_name,record_date)
		VALUES (NULL,'".$user_id."','".$cherryboard_id."','".$comment."','".$photo_name."',CURRENT_TIMESTAMP)";			   
		$insExpRewardRes=mysql_query($insExpReward);
		unlink($old_uploaddir);
		echo $type.'##===##'.$cherryboard_id;
		exit(0);
   }else{
    echo "Photo Inserting Error...";
	unlink($old_uploaddir);
   }
}
//START CHANGE EXPERT REWARD PICTURE 
if($type=="edit_exp_reward_pic"){	
   $rnd=rand();
   $file_name=$_REQUEST['file_name'];
   $user_id=$_REQUEST['user_id'];
   $comment=$_REQUEST['comment'];
   $exp_reward_id=$_REQUEST['exp_reward_id'];
   $photo_name=$rnd.'_'.$file_name;//photo_path set in db
   $uploaddir='images/expertboard/reward/'.$photo_name;
   $old_uploaddir='images/expertboard/temp/'.$file_name;
   
   //for local due to ImageMagic not working in local
   if($_SERVER['SERVER_NAME']=="localhost"){
   		$retval=copy($old_uploaddir,$uploaddir);
   }else{
	    $thumb_command=$ImageMagic_Path."convert ".$old_uploaddir." -thumbnail 180 x 180 ".$uploaddir;
		$last_line=system($thumb_command, $retval);
   }
   if($retval){
		$expRewardPic=trim(getFieldValue('photo_name','tbl_app_expert_reward_photo','exp_reward_id='.$exp_reward_id));
	 	$exprewardPicPath='images/expertboard/reward/'.$expRewardPic;	
	 
   		if($comment=="Write your comment here..."){
			$comment='';
		}
		
    	$updtQry="UPDATE tbl_app_expert_reward_photo SET photo_title='".$comment."',photo_name='".$photo_name."' WHERE exp_reward_id=".$exp_reward_id;
			   
		$updtQryRes=mysql_query($updtQry);
		unlink($exprewardPicPath);
		unlink($old_uploaddir);
		echo $type.'##===##'.$cherryboard_id;
		exit(0);
   }else{
    echo "Photo Inserting Error...";
	unlink($_REQUEST['file_name']);
   }
}
//DELETE EXPERT PHOTO
if($type=="del_expert_photo"&&$_GET['del_photo_id']>0){
	$del_photo_id=$_GET['del_photo_id'];
	$photo_name=getFieldValue('photo_name','tbl_app_expert_cherry_photo','photo_id='.$del_photo_id);
	$photo_path='images/expertboard/'.$photo_name;
	$thumb_path='images/expertboard/thumb/'.$photo_name;
	$profileSlidePath='images/expertboard/profile_slide/'.$photo_name;
    $sliderPath='images/expertboard/slider/'.$photo_name;
	if(is_file($photo_path)){		
		$del_photo=mysql_query('DELETE FROM tbl_app_expert_cherry_photo WHERE photo_id='.$del_photo_id);
		$delComment=mysql_query('DELETE FROM tbl_app_expert_cherry_comment WHERE photo_id='.$del_photo_id);
		$delQuestion=mysql_query('DELETE FROM tbl_app_expert_question_answer WHERE photo_id='.$del_photo_id);
		$delNotes=mysql_query('DELETE FROM tbl_app_expert_notes WHERE photo_id='.$del_photo_id);
		$delCheers=mysql_query('DELETE FROM tbl_app_expert_cherryboard_cheers WHERE photo_id='.$del_photo_id);
		if($del_photo){
			unlink($photo_path);
			unlink($thumb_path); 
			unlink($profileSlidePath);
			unlink($sliderPath);
		}
	}
}
//START ADD EXPERT PICTURE SECTION
if($type=="expert_add"||$type=="del_expert_photo"||$type=="exp_photo_refresh"||$type=="rotate"){
 $sort=$_GET['sort'];
 if(trim($sort)==""){$sort="asc";}
  //DAYS TITLE
  $selDays=mysql_query("select day_title from tbl_app_expertboard_days where expertboard_id=".$expertboard_id." order by day_no");
  $DaysTitleArr=array();
  if(mysql_num_rows($selDays)>0){
	  $cntDay=1;
	  while($selDaysRow=mysql_fetch_array($selDays)){
		$DaysTitleArr[$cntDay]=$selDaysRow['day_title'];
		$cntDay++;
	  }
  }
  //EXPERT BOARD PHOTOS
  $qryphoto="select * from tbl_app_expert_cherry_photo where cherryboard_id=".$cherryboard_id." order by photo_id desc";
  $selphoto=mysql_query($qryphoto);
  $cntPhoto=mysql_num_rows($selphoto);
  $photoDayArr=array();
  if($cntPhoto>0){
	while($selphotoRow1=mysql_fetch_array($selphoto)){
		$photo_id=$selphotoRow1['photo_id'];
		$photo_day=((int)$selphotoRow1['photo_day']);
		$photoDayArr[$photo_id]=$photo_day;
	}
  }
  $photoDayArr = array_unique($photoDayArr);
  $GoalDays=getExpertGoalDays($cherryboard_id);
  $expUser_id=(int)getFieldValue('user_id','tbl_app_expert_cherryboard','cherryboard_id='.$cherryboard_id);	
  $photoCntArray=array();
  
  for($i=1;$i<=$GoalDays;$i++){  
	   $swap_id=0;
	   if(in_array($i,$photoDayArr)){
			$selphoto=mysql_query("select * from tbl_app_expert_cherry_photo where cherryboard_id=".$cherryboard_id." and photo_day='".$i."' order by photo_id");
			$sub_day=1;
			$sub_photoCntArray=array();
			$totalPhoto=mysql_num_rows($selphoto);
			while($selphotoRow=mysql_fetch_array($selphoto)){
				$photo_id=$selphotoRow['photo_id'];
				$user_id=$selphotoRow['user_id'];
				$swap_id=$photo_id;
				$photo_title=ucwords(stripslashes($selphotoRow['photo_title']));
				$photo_name=$selphotoRow['photo_name'];
				$record_date=$selphotoRow['new_record_date'];
				$photoPath='images/expertboard/'.$photo_name;
				$photo_day=(int)$selphotoRow['photo_day'];
				if($photo_title==""){
					$photo_title='<div style="width:180px;height:18px">&nbsp;</div>';
				}
				if(is_file($photoPath)){
				   $photoCnt='';
				   if($totalPhoto>1){
					 $printDay=$photo_day.'.'.$sub_day;
				   }else{ $printDay=$photo_day; }
				   	 $TotalCheers=getFieldValue('count(cheers_id)','tbl_app_expert_cherryboard_cheers','photo_id='.$photo_id);
				   $photoCnt.='<div class="bottom_box_main">';				   
				   if($i==3){
				   	  $photoCnt.='<div class="bottom_daya">'.$DayType.' '.$printDay.' '.($user_id==$_SESSION['USER_ID']?'<img src="images/upload.png" onclick="javascript:document.getElementById(\'photo_day\').value='.$i.';document.getElementById(\'photo_upload\').style.display=\'inline\';" height="15" width="15" style="vertical-align:middle;cursor:pointer;" title="Add Your Picture" />':'').'</div>
					  <img src="images/banet_2.png" alt="" />';
					  $varClass='day_got_1';
					  $varClass1='bottom_healthy_box_1';
				   }else{
				   	  $photoCnt.='<div class="bottom_day_box">'.$DayType.' '.$printDay.' '.($user_id==$_SESSION['USER_ID']?'<img src="images/upload.png" onclick="javascript:document.getElementById(\'photo_day\').value='.$i.';document.getElementById(\'photo_upload\').style.display=\'inline\';" height="15" width="15" style="vertical-align:middle;cursor:pointer;" title="Add Your Picture" />':'').'</div>';
					  $varClass='bottom_box_text';
					  $varClass1='bottom_healthy_box';
				   }
				   $photoCnt.='<div class="bottom_box_bg">
				   <div class="'.$varClass.'" id="photo_title'.$photo_id.'">'.($user_id==$_SESSION['USER_ID']?'<a href="javascript:void(0);" ondblclick="ajax_action(\'upd_photo_title\',\'photo_title'.$photo_id.'\',\'stype=eadd&photo_id='.$photo_id.'&user_id='.$_SESSION['USER_ID'].'\')" title="Edit Comment" class="cleanLink">':'').''.$photo_title.'</a></div>
				   </div>';
				   $photoCnt.='<div class="bottom_healthy">
						   <div class="bottom_healthy_im"><img src="images/box.png" alt="" /></div>
						   <div class="bottom_healthy_12" id="div_expert_cheers_'.$photo_id.'">
						   '.$TotalCheers.' cheers!</div>
						   <div class="'.$varClass1.'" id="div_photo_day'.$photo_day.'">
						   '.($expOwner_id==$_SESSION['USER_ID']?'<a href="javascript:void(0);"  ondblclick="ajax_action(\'edt_exp_photo_day\',\'div_photo_day'.$photo_day.'\',\'stype=add&photo_day='.$photo_day.'&expertboard_id='.$expertboard_id.'&user_id='.USER_ID.'\')" title="Edit Day Title">':'').' &nbsp;'.$DaysTitleArr[$photo_day].'&nbsp;</a>            	   </div>
					  <div style="clear:both"></div>
					  </div>';
				   $photoCnt.='<div class="img_box_container" align="center" id="div'.$i.'_'.$swap_id.'" '.($user_id==$_SESSION['USER_ID']?'ondrop="drop(event,\''.$i.'_'.$swap_id.'\')" ondragover="allowDrop(event,\''.$i.'_'.$swap_id.'\')"':'').'>
				   <div class="feedbox">';
				   if($user_id==$_SESSION['USER_ID']){
						$photoCnt.='<div class="actions"><a class="delete" href="#" onclick="photo_action(\'del_expert_photo\','.$cherryboard_id.','.$photo_id.')"><img src="images/delete.png" title="Delete"></a></div>';
						//Change Photo Hover Code
						$photoCnt.='<div class="message">
						<a href="javascript:void(0);" onclick="javascript:document.getElementById(\'story_photo_id\').value='.$photo_id.';javascript:document.getElementById(\'subtype\').value=\'change_story_pic\';document.getElementById(\'photo_upload\').style.display=\'inline\';" class="change">Change Photo</a>
						</div>';		
				   }	
				   $photoCnt.='</div>';			   
				   $photoCnt.='<img src="'.$photoPath.'" id="drag'.$i.'_'.$swap_id.'" draggable="true" ondragstart="drag(event,\''.$i.'_'.$swap_id.'\')" data-tooltip="stickyCherry'.$photo_id.'" style="width:219px">
				   </div>';
				   $photoCnt.='<div class="applemenu">';
				   //COMMENT SECTION
				   $photoCnt.='<div id="div_cherry_comment_'.$photo_id.'">';
				   $photoCnt.=expert_comment_section($cherryboard_id,$photo_id,$photo_day);
				   $photoCnt.='</div>';	
				   //QUESTION SECTION
				   $photoCnt.='<div id="div_cherry_question_'.$photo_id.'">';
				   $photoCnt.=expert_question_section($cherryboard_id,$photo_id,$photo_day);
				   $photoCnt.='</div>';				   
				   //NOTES SECTION
				   if($expUser_id==$_SESSION['USER_ID']){
					   $photoCnt.='<div id="div_expert_notes_'.$photo_id.'">';
					   $photoCnt.=expert_notes_section($cherryboard_id,$photo_id,$photo_day);
					   $photoCnt.='</div>';
				   }   						   
		 
				   $photoCnt.='</div>
				   <div style="clear:both"></div>
				   </div>';
				   				   
						$sub_photoCntArray[$sub_day]=$photoCnt;
						$sub_day++;	
					}
				}
				$photoCntArray[$i]=$sub_photoCntArray;
		}else{
			 $photoCnt='';
			 $sub_photoCntArray=array();
			 $photoPath='images/cherryboard/no_image.png'; 
			 $photoCnt.='<div class="bottom_box_main">
			 			 <div class="bottom_day_box">'.$DayType.' '.$i.''.($user_id==$_SESSION['USER_ID']?'&nbsp;<img src="images/upload.png" onclick="javascript:document.getElementById(\'photo_day\').value='.$i.';document.getElementById(\'photo_upload\').style.display=\'inline\';" height="15" width="15" style="vertical-align:middle;cursor:pointer;" title="Add Your Picture" />':'').'</div>
						 <div class="bottom_box_bg">
						 	<div class="bottom_box_text" id="photo_title'.$i.'">No Photo</div>
						 </div>
						 <div class="bottom_healthy">
							 <div class="bottom_healthy_im"><img src="images/box.png" alt="" /></div>
							 <div class="bottom_healthy_box"><a href="#">'.$DaysTitleArr[$i].'</a></div>
							 <div style="clear:both"></div>
         				 </div>
						 <div class="day_img" style="padding:12px;">
						 <div id="div'.$i.'_'.$swap_id.'" style="background-image:url('.$photoPath.');cursor:pointer;height:192px;width:192px;" '.($expUser_id==$_SESSION['USER_ID']?'ondrop="drop(event,\''.$i.'_'.$swap_id.'\')" ondragover="allowDrop(event,\''.$i.'_'.$swap_id.'\')" onclick="javascript:document.getElementById(\'photo_day\').value='.$i.';document.getElementById(\'photo_upload\').style.display=\'inline\';"':'').' src="'.$photoPath.'">
						 </div>
						 </div>';
			 $photoCnt.='</div>';
			 		
			 $sub_photoCntArray[1]=$photoCnt;
			 $photoCntArray[$i]=$sub_photoCntArray;
		  }
	}
	$NewphotoCnt='';
	$AscDescArrow='<table><tr><td><a title="Sort" name="Sort" href="javascript:void(0);" onclick="javascript:ajax_action(\'exp_photo_refresh\',\'right_container\',\'cherryboard_id='.$cherryboard_id.'&sort='.($sort=="asc"?'desc':'asc').'\')">'.($sort=="asc"?'<img id="des" src="images/des.jpg" height="35" width="35"/>':'<img id="asc" src="images/asc.jpg" height="35" width="35"/>').'</a></td><td><img id="rotate_asc" src="images/transparent.png" height="25" width="25"/></td></tr></table>';
		
	$NewphotoCnt.='<table border="0"><tr>';
	if($sort=="asc"){
		$cnt=1;
		for($i=1;$i<=$GoalDays;$i++){
			foreach($photoCntArray[$i] as $photosection){
				$NewphotoCnt.='<td valign="top" style="height:100%">'.$photosection.'</td>';
				if($cnt%3==0){$NewphotoCnt.='</tr><tr>';}
				$cnt++;
			}				
		}
	}else{
		$cnt=1;
		for($i=$GoalDays;$i>=1;$i--){
			foreach($photoCntArray[$i] as $photosection){
				$NewphotoCnt.='<td valign="top" style="height:100%">'.$photosection.'</td>';
				if($cnt%3==0){$NewphotoCnt.='</tr><tr>';}
				$cnt++;
			}
		}
	}		
	$NewphotoCnt.='</tr>
	<tr><td colspan="3" style="height:50px;padding-left:450px;">'.($expOwner_id==$_SESSION['USER_ID']?'<a href="javascript:void(0);" onclick="ajax_action(\'increase_expdays_items\',\'div_exp_day_'.$expertboard_id.'\',\'cherryboard_id='.$cherryboard_id.'&user_id='.$_SESSION['USER_ID'].'\')" title="Add '.$DayType.'" class="gray_link_15">+</a>':'&nbsp;').'</td></tr>';
	
	$NewphotoCnt.='</table>';
}			
//START DELETE EXPERT PICTURE
if($type=="del_expert_photo"||$type=="exp_photo_refresh"||$type=="rotate"){
	$sort=$_GET['sort'];
    if(trim($sort)==""){$sort="asc";}
	$photoCnt=$type.'##===##right_container##===##'.$NewphotoCnt.'##===##'.$cherryboard_id.'##===##'.$sort.'##===##'.$AscDescArrow;
}	
echo $photoCnt;
?>