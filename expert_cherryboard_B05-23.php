<?php
include_once "fbmain.php";
include('include/app-common-config.php');
$cherryboard_id=$_GET['cbid'];
$expertboard_id=getFieldValue('expertboard_id','tbl_app_expert_cherryboard','cherryboard_id='.$cherryboard_id);
$expOwner_id=getFieldValue('user_id','tbl_app_expertboard','expertboard_id='.$expertboard_id);
$msg='';

//UPLOAD EXPERT PHOTO
$msg="";
if(isset($_POST['photo_day'])&&$_POST['photo_day']>0){
   $rnd=rand();
   $file_name=$_FILES['exp_photo_name']['name'];
   $photo_day=$_POST['photo_day'];
   $user_id=USER_ID;
   $comment=$_POST['txtcomment'];
   
   $photo_name=$rnd.'_'.$file_name;//photo_path set in db
   $old_uploaddir='images/expertboard/temp/'.$photo_name;
   $uploaddir='images/expertboard/'.$photo_name;
   $uploaddirThumb='images/expertboard/thumb/'.$photo_name;
  
  $checkPhoto=getFieldValue('photo_name','tbl_app_expert_cherry_photo','cherryboard_id='.$cherryboard_id.' order by photo_id desc limit 1');
  $checkPhotoArray=explode('_',$checkPhoto);
  $checkPhotoName=str_replace($checkPhotoArray[0].'_','',$checkPhoto);
  if($checkPhotoName!=$file_name){
   if(copy($_FILES['exp_photo_name']['tmp_name'], $old_uploaddir)){
   	   if($_SERVER['SERVER_NAME']=="localhost"){
			//for local due to ImageMagic not working in local
			$retval=copy($old_uploaddir,$uploaddir);
			$retval=copy($old_uploaddir,$uploaddirThumb);
	   }else{
			$thumb_command=$ImageMagic_Path."convert ".$old_uploaddir." -thumbnail 195 x 195 ".$uploaddir;
			$last_line=system($thumb_command, $retval);
			$thumb_command_thumb=$ImageMagic_Path."convert ".$old_uploaddir." -thumbnail 60 x 60 ".$uploaddirThumb;
			$last_line=system($thumb_command_thumb, $retval);
	   }
	   if(is_file($uploaddir)){
			if($comment=="Write your comment here..."){
				$comment='';
			}
			
				$insert_qry="INSERT INTO `tbl_app_expert_cherry_photo`(`photo_id`, `user_id`, `cherryboard_id`, `photo_title`, `photo_name`,photo_day) VALUES ('',".$user_id.",".$cherryboard_id.",'".$comment."','".$photo_name."','".$photo_day."')";
		   
				$insert_qry_res=mysql_query($insert_qry);
	
				$AlbumDetail=getFieldsValueArray('fb_album_id,cherryboard_title','tbl_app_expert_cherryboard','cherryboard_id='.$cherryboard_id);
				$AlbumId=$AlbumDetail[0];
				$cherryboard_title=$AlbumDetail[1];
		
				//START SHARE PHOTO IN ALBUM
				if($AlbumId!="0"&&$AlbumId!=""){
					$facebook->setFileUploadSupport(true);  
					# File is relative to the PHP doc  
					$file = "@".realpath("images/expertboard/temp/".$photo_name);  
					$args = array(  
						'message' => $comment.' - 30DaysNew', 
						"access_token" => $_SESSION['fb_access_token'],  
						"image" => $file  
					);  
					$data = $facebook->api('/'.$AlbumId.'/photos', 'post', $args);
					//echo "Photo Id :".$upload_photo['id']; // The id of your newly uploaded pic.
					$upload_photoID=$data['id'];
				}
				//END SHARE PHOTO IN ALBUM
				$msg="<span class=\"fgreen\">Photo added successfully.</span>";
				
	   }
	}
  }	

}
//START DELETE EXPERT CODE
$delExpId=(int)$_GET['delExpId'];
if($delExpId>0){
	$delExpertboard=mysql_query("DELETE FROM tbl_app_expertboard WHERE expertboard_id=".$delExpId." and user_id=".USER_ID);
	if($delExpertboard){
		$delGoalExpertboard=mysql_query("DELETE FROM tbl_app_expert_cherryboard WHERE expertboard_id=".$delExpId);
		echo '<script language="javascript">document.location=\'expertboard.php\'</script>';
	}

}
//END DELETE EXPERT CODE
//START EDIT EXPERT CODE
if(isset($_POST['btnEditExpert'])){
	$expertboard_title=trim($_POST['title']);
	$expertboard_detail=trim(addslashes($_POST['detail']));
	$expertId=(int)$_POST['expertId'];
	$category_id=(int)$_POST['category_id1'];
	$number_days=(int)$_POST['number_days'];
	$price=$_POST['price'];
	
	if($expertboard_title!=''&&$expertboard_detail!=''&&$category_id>0&&$number_days>0&&trim($price)!=""){
		$editExpBoard="UPDATE tbl_app_expertboard SET 
						category_id= '".$category_id."',
						expertboard_title='".$expertboard_title."',
						expertboard_detail='".$expertboard_detail."',
						goal_days='".$number_days."',
						price='".$price."' WHERE expertboard_id='".$expertId."'";
		$editQry=mysql_query($editExpBoard);
		
		//update goal days with title
		 $totalConfigDays=getFieldValue('count(expertboard_day_id)','tbl_app_expertboard_days','expertboard_id='.$expertId);
		 if($totalConfigDays!=$number_days){
			//when increase days in goal days
			if($number_days>$totalConfigDays){
				for($i=($totalConfigDays+1);$i<=$number_days;$i++){
					$addDays="INSERT INTO `tbl_app_expertboard_days` (`expertboard_day_id`, `expertboard_id`, `day_no`, `day_title`, `record_date`) VALUES (NULL, '".$expertboard_id."', '".$i."', 'Day ".$i."', CURRENT_TIMESTAMP)";
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
		
	}
}
//END EDIT EXPERT CODE
//START UPDATE DAY CONFIG CODE
if(isset($_POST['btnAddDays'])){
	$selDays=mysql_query("SELECT * FROM tbl_app_expertboard_days where expertboard_id=".$expertboard_id." ORDER BY expertboard_day_id");
	while($row=mysql_fetch_array($selDays)){
		 $expertboardDayId=$row['expertboard_day_id'];
		 $day_title='day_'.$expertboardDayId;
		 $dayTitle=trim($_POST[$day_title]);
		 $updateDay=mysql_query("UPDATE tbl_app_expertboard_days SET day_title='".$dayTitle."' WHERE expertboard_day_id=".$expertboardDayId);		
	}
}
//END UPDATE DAY CONFIG CODE
?>
<?php
include('site_header.php');

//Expert Cheryboard Detail
$cherrySel=mysql_query("select * from tbl_app_expertboard where expertboard_id=".$expertboard_id);
	while($cherryRow=mysql_fetch_array($cherrySel)){
		$category_id=ucwords($cherryRow['category_id']);
		$expertboard_title=ucwords($cherryRow['expertboard_title']);
		$category_name=getFieldValue('category_name','tbl_app_category','category_id='.$category_id);
		$expertboard_detail=$cherryRow['expertboard_detail'];
		$price=$cherryRow['price'];
	}
	
?>
<div style="background:#FFFFFF; margin:0px auto;">
<div id="wrapper" style="padding-top: 97px;margin: 0 auto 0;">
<?php
 $expertCnt='';
	  $sel_expert=mysql_query("SELECT * FROM tbl_app_expertboard WHERE expertboard_id=".$expertboard_id);
	  while($fetchExpertRow=mysql_fetch_array($sel_expert)){
	  		//$expertboard_id=(int)$row['expertboard_id'];
			$expertboard_title=ucwords(trim($fetchExpertRow['expertboard_title']));
			$expertboard_detail=trim(stripslashes($fetchExpertRow['expertboard_detail']));
			$user_id=(int)$fetchExpertRow['user_id'];
			$userDetail=getUserDetail($user_id);
			$userOwnerFbId=$userDetail['fb_id'];
			$userName=$userDetail['name'];
			
			$goal_days=(int)$fetchExpertRow['goal_days'];
			$price=$fetchExpertRow['price'];
			//$expertPicPath='images/expert.jpg';
			$expertPicPath='https://graph.facebook.com/'.$userOwnerFbId.'/picture?type=large';
			$expert_detail='';
			if(strlen($expertboard_detail)>100){
				$expert_detail=''.substr($expertboard_detail,0,100).'...<a href="javascript:void(0);" style="text-decoration:none;color:#990000" onclick="ajax_action(\'get_more_expert\',\'div_more_expert\',\'expertboard_id='.$expertboard_id.'\')">More</a>';
			}else{
				$expert_detail=$expertboard_detail;
			}
			$created_cherryboard_id=(int)getFieldValue('cherryboard_id','tbl_app_expert_cherryboard','expertboard_id="'.$expertboard_id.'" and main_board="0" AND user_id='.USER_ID);
			
			if($expertPicPath!=""){
				$expertCnt.='<table align="center" border="0" width="50%">';
							
							if($expertboard_id>0){
							  $totalExpert=(int)getFieldValue('count(cherryboard_id)','tbl_app_expert_cherryboard','expertboard_id='.$expertboard_id.' and main_board="0"');
							  if($expOwner_id==USER_ID){
									$expertCnt.='<tr>
										<td colspan="3" align="right">
										<div style="padding-bottom:8px">
										<a id="go" rel="leanModal" href="#daysconfig" name="Days Config" class="btn_small" title="Days Config">Days Config</a>
										<a href="#" name="test" class="btn_small" title="Analytics">Analytics</a>
										<a id="go" rel="leanModal" href="#edit_expert" name="test" class="btn_small">Edit</a>&nbsp;
										<a onclick="return delExpert('.$totalExpert.')" href="expert_cherryboard.php?delExpId='.$expertboard_id.'"><img title="Delete" src="images/delete.png"></a>
										</div>
										<hr/>
										</td>
									</tr>';
								}
							}	
							$expertCnt.='<tr>
							    <td>
									 <div id="div_expert_picture'.$expertboard_id.'"> 
									 <div class="img_big_container" style="text-align: center;">
									 <div class="send_message">
										<!-- <div class="actions1">'.($user_id==USER_ID?'<a href="javascript:void(0);" onclick="ajax_action(\'edit_expert_picture\',\'div_expert_picture'.$expertboard_id.'\',\'eid='.$expertboard_id.'\')" class="msg">Change Image</a>':'').'</div> -->
									 </div>
									  <img src="'.$expertPicPath.'" height="180" width="180" title="'.$userName.'">
									  <br/>
									  '.$userName.'
									</div>
									<font size="+1"><strong>Share</strong></font><br/>
									<a rel="leanModal" href="#sendThankYou" title="Send Thank You" class="msg"><img src="images/send-email-button.jpg" title="Sent Email"></a>
									</div>
								</td>
								<td width="50px">&nbsp;</td>
								<td valign="top">
								   <div id="div_more">
								   <font size="+1"><strong>'.$expertboard_title.'</strong></font><br>
						          '.(trim($expert_detail)!=''?''.trim($expert_detail).'':'No expert details').'
								   </div>
								   <br/><br/><br/>
								   <br><font size="+1"><strong>Total :
								   '.$goal_days.' Days <br> Price : '.$price.'</strong></font><br/><br/>';
								   $check_main_id=(int)getFieldValue('cherryboard_id','tbl_app_expert_cherryboard','cherryboard_id="'.$cherryboard_id.'" and main_board="1"');
								   if($check_main_id>0){
										if($created_cherryboard_id>0){
										   $expertCnt.='<a href="expert_cherryboard.php?cbid='.$created_cherryboard_id.'" name="View Goal" class="btn_small" title="View Goal">View Goal</a>';
										}else{
										   $expertCnt.='<a href="expertboard.php?eid='.$expertboard_id.'" name="Buy" class="btn_small" title="Buy">Buy</a>';
										} 
									}
								
								
								$expertCnt.='<br/>';
								$expertCnt.='</td>  
							 </tr>';
							
						 $expertCnt.='</table>';
			}
	  }
	  echo $expertCnt;	  
?>
	<!-- <div class="right">
	 <table>
	 <tr><td><span class="desciption">Start Date</span></td></tr>
	 <tr><td><span class="desciption">Price</span></td></tr>
	 <tr><Td><?=($price!="0"?'$'.$price:'')?></Td></tr>
	 <tr><Td style="padding-top:20px;">
	 <a href="#" class="btn_red" id="buy_board_'<?=$cherryboard_id?>">Buy</a>
	 </Td></tr>
    </table>
	</div>
	<div id="left_container">
      
        <div id="my_cherryleaders"><a href="#" id="invite_frnd" class="gray_link_15 right">+</a>Expertboard Followers<input type="hidden" name="cherryboard_id" id="cherryboard_id" value="<?php echo $cherryboard_id;?>" /><input type="hidden" name="cherryboard_key" id="cherryboard_key" value="0" /><br>
	 <div id="div_goal_followers">
	 <?php
	//FRIENDS BLOCK
	    $FriendsCnt='';
		$selQuery="select a.meb_id,b.user_id,b.fb_photo_url from tbl_app_expert_cherryboard_meb a,tbl_app_users b where a.req_user_fb_id=b.facebook_id and a.is_accept='1' and a.cherryboard_id=".$cherryboard_id." group by b.user_id limit 10";
		$selSqlQ=mysql_query($selQuery);
		$FriendsArray=array();
		if(mysql_num_rows($selSqlQ)>0){
			$cnt=0;
			while($rowTbl=mysql_fetch_array($selSqlQ)){
				$FriendsArray[]=$rowTbl['user_id'];
				$meb_id=$rowTbl['meb_id'];
				if($cnt==5){$FriendsCnt.='<br/>';}
				$FriendsCnt.='<div class="small_thumb_container">
				<div class="img_big_container">
					<div class="feedbox_holder">
						<div class="actions"><a class="delete" href="#" onclick="ajax_action(\'delete_goal_followers\',\'div_goal_followers\',\'cherryboard_id='.$cherryboard_id.'&meb_id='.$meb_id.'\')" ><img src="images/delete.png"></a></div>
					</div>
					<img src="'.$rowTbl['fb_photo_url'].'" class="thumb">
				</div>
				</div>';
				$cnt++;
			}
			
		}else{
			$FriendsCnt.='<strong>No Followers</strong>';
		}
		//echo $FriendsCnt;
	?>
	</div>
	<div id="div_goal_recent_followers">
	<?php
	
		$FriendsCnt='';
		$selQuery="select meb_id,req_user_fb_id from tbl_app_expert_cherryboard_meb where is_accept='0' and cherryboard_id=".$cherryboard_id." order by meb_id desc limit 10";
		$selSqlQ=mysql_query($selQuery);
		if(mysql_num_rows($selSqlQ)>0){
			$FriendsCnt.='<br/><br/><p>Follower Request</p>';
			$cnt=0;
			while($rowTbl=mysql_fetch_array($selSqlQ)){
				if($cnt==5){$FriendsCnt.='<br/>';}
				$meb_id=$rowTbl['meb_id'];
				$fb_photo_url=getFriendPhoto($rowTbl['req_user_fb_id']);
				$FriendsCnt.='<div class="small_thumb_container">
				<div class="img_big_container">
					<div class="feedbox_holder">
						<div class="actions"><a class="delete" href="#" onclick="ajax_action(\'delete_goal_recent_followers\',\'div_goal_recent_followers\',\'cherryboard_id='.$cherryboard_id.'&meb_id='.$meb_id.'\')" ><img src="images/delete.png"></a></div>
					</div>
					<img src="'.$fb_photo_url.'" class="thumb">
				</div>
				</div>';
				$cnt++;
			}
			
		}
		//echo $FriendsCnt;
	?>
	</div>
	 </div>
       
     <div id="my_cherryleaders"><a href="experts.php?cbid=<?php echo $cherryboard_id;?>" class="gray_link_15 right">+</a>Inspirational Experts<br>
	 <div id="div_goal_experts">
   <?php
	//Experts BLOCK
		$selQuery="select a.expert_id,b.user_id,b.fb_photo_url from tbl_app_cherryboard_expert a,tbl_app_users b where a.user_id=b.user_id and a.cherryboard_id=".$cherryboard_id." and is_accept='1' group by b.user_id order by a.expert_id desc limit 10";
		$selSqlQ=mysql_query($selQuery);
		$ExpertsCnt='';
		if(mysql_num_rows($selSqlQ)>0){
			$cnt=0;
			while($rowTbl=mysql_fetch_array($selSqlQ)){
				$expert_id=$rowTbl['expert_id'];
				if($cnt==5){$ExpertsCnt.='<br/>';}
				$ExpertsCnt.='<div class="small_thumb_container">
				<div class="img_big_container">
					<div class="feedbox_holder">
						<div class="actions"><a class="delete" href="#" onclick="ajax_action(\'delete_goal_experts\',\'div_goal_experts\',\'cherryboard_id='.$cherryboard_id.'&expert_id='.$expert_id.'\')" ><img src="images/delete.png"></a></div>
					</div>
					<img src="'.$rowTbl['fb_photo_url'].'" class="thumb">
				</div>
				</div>';
				$cnt++;
			}
			
		}else{
			$ExpertsCnt.='<strong>No Experts</strong>';
		}
		//echo $ExpertsCnt;
	?>
	</div>
	 </div>
	
	
	 <table>
	 <tr><td><span class="desciption">Desciption</span></td></tr>
	 <tr><td><?=substr($expertboard_title,0,300)?></td></tr>
	 <tr><td><span class="desciption">My Expertise</span></td></tr>
	 <tr><td><span class="desciption">This is good for</span></td></tr>
	</table>
	 
    </div>
	<div id="middle_wrapper" style="margin-left:250px; width:470px;" >
    	<table width="100%">
		<?php if($msg!=""){ ?>
		<tr><td><font color="#009900;font-size:12px"><?php echo $msg;?></font></td></tr>
		<?php }	?>
    	<tr><td align="center"><h1><?php echo $expertboard_title;?></h1></td></tr>
	 	<tr><td align="center">Goal Type: <?=ucwords($category_name)?></td></tr>
		<tr><td align="center">&nbsp;</td></tr>
		<tr><td align="center">&nbsp;</td></tr>
		<tr><td align="center">&nbsp;</td></tr>
		</table>
  </div> -->
   <div class="clear"></div>
</div>
</div>
<div id="body_container">
	<div class="wrapper">
        <div id="checklist"><h2>Checklist</h2>
		<?php if($expOwner_id==USER_ID){ ?>
          <input name="txt_checklist" id="txt_checklist" type="text" onfocus="if(this.value=='add something to your checklist') this.value='';" onblur="if(this.value=='') this.value='add something to your checklist';" class="input_200" value="add something to your checklist">
          <input name="Submit" type="button" onclick="ajax_action('add_expert_checklist','div_checklist','cherryboard_id=<?=$cherryboard_id;?>&txt_checklist='+document.getElementById('txt_checklist').value+'&user_id=<?=USER_ID?>');" value="Post" title="Post" class="btn_small" style="margin:0px;">
          <br>
          <br>
		  <?php } ?>
		  <div id="div_checklist">
		  <?php
		  //CHECKLIST BLOCK
			 $selchk=mysql_query("select *,date_format(record_date,'%m-%d-%Y') as record_date from tbl_app_expert_checklist where cherryboard_id=".$cherryboard_id." order by checklist_id desc limit 10");
			$checkCnt='';
			while($selchkRow=mysql_fetch_array($selchk)){
				$checklist_id=$selchkRow['checklist_id'];
				$checklist=$selchkRow['checklist'];
				$record_date=$selchkRow['record_date'];
				$is_checked=$selchkRow['is_checked'];
				$chk_user_id=$selchkRow['user_id'];
				$checkCnt.='<div class="box_container" style="width: 230px;"><label><input type="checkbox" id="chkfield_'.$checklist_id.'"  name="chkfield_'.$checklist_id.'" '.($is_checked==1?'checked="checked"':'').' value="1"  onclick="checked_checklist(\'checked_expert_checklist\',\'div_checklist\',\'cherryboard_id='.$cherryboard_id.'&checklist_id='.$checklist_id.'&cuid='.USER_ID.'\',\'chkfield_'.$checklist_id.'\')" class="checkbox"></label>&nbsp;'.$checklist.'<br/><span class="smalltext">added '.$record_date.'&nbsp;';
				if($chk_user_id==USER_ID){
					$checkCnt.='<img src="images/close_small1.png" onclick="ajax_action(\'remove_expert_checklist\',\'div_checklist\',\'cherryboard_id='.$cherryboard_id.'&checklist_id='.$checklist_id.'&cuid='.USER_ID.'\')" style="cursor:pointer"/>';
				}
				$checkCnt.='</span></div>';
			}
			echo $checkCnt;
		 ?>
		</div>
		<br/><Br/>
		<div id="inspir_feed1">
		<h2>Bought Users</h2>
		<Table>
		<?php
		//BOUGHT SECTION
		$RemainDay=getExpertboardRemainDays($cherryboard_id);
		$sel_buy=mysql_query('select a.user_id,b.first_name,b.last_name,b.fb_photo_url from tbl_app_expert_buy a,tbl_app_users b where a.user_id=b.user_id and a.cherryboard_id='.$cherryboard_id);
		if(mysql_num_rows($sel_buy)>0){
			$cnt=1;
			while($row_buy=mysql_fetch_array($sel_buy)){
				$userid=$row_buy['user_id'];
				$name=ucwords($row_buy['first_name'].'&nbsp;'.$row_buy['last_name']);
				$fb_photo_url=$row_buy['fb_photo_url'];
				$checkIn='';
				if($RemainDay<=30){
					$checkIn='<a href="javascript:void(0);" id="exp_checkin_mail_'.$userid.'" onclick="ajax_action(\'exp_checkin_mail\',\'exp_checkin_mail_'.$userid.'\',\'cherryboard_id='.$cherryboard_id.'&user_id='.$userid.'\')" id="exp_checkin_msg" class="gray_link">Day&nbsp;'.(int)(30-$RemainDay).'&nbsp;Check&nbsp;In!</a>';
				}
		
				echo '<tr><td>'.$cnt.'.</td><td>'.$name.'</td><td><img class="img_small" title="'.$name.'" src="'.$fb_photo_url.'"></td><td>'.$checkIn.'</td></tr>';
				$cnt++;
			}
		}else{
			echo '<tr><td colspan="4"><div class="feed"><strong>No User<strong></div></td></tr>';
		}
		?>		
		</Table>
		</div>
		<br/><Br/>
		<div id="inspir_feed1">
          <?php
		 echo UserExpertFeedSection('expertboard',$cherryboard_id);
		?>
      </div>
      </div>
	  <div id="right_container" style="position: absolute;margin-left:275px;width:720px;">
		<div><table><tr><td><a title="Sort" class="btn_small" name="Sort" href="javascript:void(0);" onclick="javascript:ajax_action('exp_photo_refresh','right_container','cherryboard_id=<?=$cherryboard_id?>&sort=asc')">Ascending</a></td><td><img id="rotate_asc" src="images/transparent.png" height="35" width="35"/></td></div>
	  	  <?php
		   
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
 		   $photoCnt='';
		   $swap_id=0;
		   if(in_array($i,$photoDayArr)){
			$selphoto=mysql_query("select * from tbl_app_expert_cherry_photo where cherryboard_id=".$cherryboard_id." and photo_day='".$i."' order by photo_id desc");
			
			while($selphotoRow=mysql_fetch_array($selphoto)){
				$photo_id=$selphotoRow['photo_id'];
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
				   $photoCnt.='<div class="field_container2">
				   
 					<div class="day_container">Day '.$photo_day.'</div>
						  <div class="tag_container">
							<div class="comment_box1" id="photo_title'.$photo_id.'"><a href="javascript:void(0);" ondblclick="ajax_action(\'upd_photo_title\',\'photo_title'.$photo_id.'\',\'stype=eadd&photo_id='.$photo_id.'\')" title="Edit Comment" class="cleanLink">'.$photo_title.'</a></div><div class="clear"></div>
								<div class="info_box">
									<div class="score">'.$DaysTitleArr[$photo_day].'</div>
									<div class="date">'.$record_date.'</div>
								 </div>
								 <div class="b_arrow"></div>
							 <div class="clear"></div>
						 </div>';
						$photoCnt.='<div class="top1">
									<div class="img_big_container3" id="div'.$i.'_'.$swap_id.'" ondrop="drop(event,\''.$i.'_'.$swap_id.'\')" ondragover="allowDrop(event,\''.$i.'_'.$swap_id.'\')"> 
									<div class="feedbox_holder">
								    <div class="actions"><a class="delete" href="#" onclick="photo_action(\'del_expert_photo\','.$cherryboard_id.','.$photo_id.')"><img src="images/delete.png"></a></div>
							        </div> 
									<img src="'.$photoPath.'" id="drag'.$i.'_'.$swap_id.'" draggable="true" ondragstart="drag(event,\''.$i.'_'.$swap_id.'\')">
									</div>'; 
					
					    $photoCnt.='<div id="div_cherry_comment_'.$photo_id.'">
						<div class="bottom1">';
							$TotalCmt=getFieldValue('count(photo_id)','tbl_app_expert_cherry_comment','photo_id='.$photo_id);
							$TotalCheers=getFieldValue('count(cheers_id)','tbl_app_expert_cherryboard_cheers','photo_id='.$photo_id);
							$checkCheers=(int)getFieldValue('user_id','tbl_app_expert_cherryboard_cheers','photo_id='.$photo_id.' and user_id='.USER_ID);
							if($checkCheers==0){
								$cheersLink='<div class="likes"><a href="javascript:void(0);" onclick="add_cherry_cheers(\'add_expert_cheers\',\''.$photo_id.'\',\''.$cherryboard_id.'\',\''.USER_ID.'\')" class="likes">+give cheers!</a></div>';
							}else{$cheersLink='';}
							$photoCnt.=$cheersLink.'<div class="coment" id="div_photo_cheers_'.$photo_id.'">&nbsp;'.(int)$TotalCheers.'&nbsp;Cheers&nbsp;&nbsp;'.(int)$TotalCmt.'&nbsp;Comments</div><br>';
		$photoCnt.='</div>';					
							if($TotalCmt>0){
							  $selCmt=mysql_query("select * from tbl_app_expert_cherry_comment where photo_id=".$photo_id." order by comment_id desc limit 2");
							  while($cmtRow=mysql_fetch_array($selCmt)){
								   $userPhotoArray=getFieldsValueArray('first_name,last_name,fb_photo_url','tbl_app_users','user_id='.$cmtRow['user_id']);
								   $UserName=$userPhotoArray[0].' '.$userPhotoArray[1];
								   $UserPhoto=$userPhotoArray[2];
								   $comment_id=$cmtRow['comment_id'];
								   $PhotoComment=stripslashes($cmtRow['cherry_comment']);
								    $photoCnt.='<div class="leandro">
					 <div class="maryfeed"><div class="action"><a class="delete" href="javascript:void(0);" onclick="add_cherry_comment(event,\'del_cherry_expert_comment\','.$cherryboard_id.','.$photo_id.','.$cmtRow['user_id'].','.$comment_id.')"><img src="images/delete.png" title="Delete"></a></div></div>
					 <div class="leandro_1"><img src="'.$UserPhoto.'" class="img_small" /></div>
					 <div class="leandro_2"><strong>'.$UserName.'</strong>&nbsp;'.$PhotoComment.'</div>
					 </div>';
							  }
							}
					$current_userPic=getFieldValue('fb_photo_url','tbl_app_users','user_id='.USER_ID);	     
		$photoCnt.='</div><div class="add1">
			 <div class="add_img"><img src="'.$current_userPic.'" class="img_small" /></div>
			 <div class="add_txt">
			 <textarea name="cherry_comment_'.$photo_id.'" class="input_comments" id="cherry_comment_'.$photo_id.'" onfocus="if(this.value==\'Add a comment...\') this.value=\'\';" onblur="if(this.value==\'\') this.value=\'Add a comment...\';" style="height: 29px;width:130px;">Add a comment...</textarea>
			 
			 </div>
			 <div class="add_btn"><img style="cursor:pointer" src="images/btn_comment.png" onclick="return add_cherry_comment(event,\'add_cherry_expert_comment\',\''.$cherryboard_id.'\',\''.$photo_id.'\',\''.USER_ID.'\',document.getElementById(\'cherry_comment_'.$photo_id.'\').value)"></div>
    </div>';
						if($i==1){
							$photoCnt.='<div style="padding-bottom:35px;"></div>';	  
						}
						$photoCnt.='</div>';
					    $photoCntArray[$i]=$photoCnt;
						
					}
				}	
				
			}else{
			  	 $photoPath='images/cherryboard/no_image.png'; 
				 $photoCnt.='<div class="field_container2">
					   
						<div class="day_container">Day '.$i.'</div>
				  <div class="tag_container">
					<div class="comment_box1" id="photo_title'.$i.'">No Photo</div><div class="clear"></div>
						<div class="info_box">
							<div class="score">'.$DaysTitleArr[$i].'</div>
							<div class="date">&nbsp;</div>
						 </div>
						 <div class="b_arrow"></div>
					 <div class="clear"></div>
				 </div>
					   
							<div id="div'.$i.'_'.$swap_id.'" class="img_big_container" style="background-image:url('.$photoPath.');cursor:pointer;height:192px;width:192px;padding: 7px;" '.($expUser_id==USER_ID?'ondrop="drop(event,\''.$i.'_'.$swap_id.'\')" ondragover="allowDrop(event,\''.$i.'_'.$swap_id.'\')" onclick="javascript:document.getElementById(\'photo_day\').value='.$i.';document.getElementById(\'photo_upload\').style.display=\'inline\';"':'').' src="'.$photoPath.'">

							 </div>
						   <div id="div_cherry_comment">';
								
						$photoCnt.='</div>';
						$photoCnt.='</div>';
						$photoCntArray[$i]=$photoCnt;		
			  }
			   
			}
		$NewphotoCnt='';
		$NewphotoCnt='<table border="0"><tr>';
		if($sort=="asc"){
			for($i=1;$i<=$GoalDays;$i++){
				$NewphotoCnt.='<td valign="top">'.$photoCntArray[$i].'</td>';
				if($i%3==0){$NewphotoCnt.='</tr><tr>';}
			}
		}else{
			$cnt=1;
			for($i=$GoalDays;$i>=1;$i--){
				$NewphotoCnt.='<td valign="top">'.$photoCntArray[$i].'</td>';
				if($cnt%3==0){$NewphotoCnt.='</tr><tr>';}
				$cnt++;
			}
		}		
		$NewphotoCnt.='</tr>
		<tr><td colspan="3" style="height:50px">&nbsp;</td></tr>
		</table>';
  $NewphotoCnt.='<!-- START ADD PHOTO--- -->
		<div style="display: none; position: fixed; opacity: 1; z-index: 11000; left: 50%; margin-left: -165px; top: 200px;width:390px;" id="photo_upload" align="center" class="popup_div">
                <a class="modal_close" href="javascript:void(0);" title="close" onclick="javascript:document.getElementById(\'photo_upload\').style.display=\'none\';"></a>
                <span class="head_20">Upload Photo</span><br>
				<div id="setup_add_category" class="msg_red">&nbsp;</div>
				<form action="" method="post" name="frmphoto" enctype="multipart/form-data">
				<input type="hidden" name="photo_day" id="photo_day" value="1" />
				<table>
				<tr>
				<td><textarea onblur="if(this.value==\'\') this.value=\'Write your comment here...\';" onfocus="if(this.value==\'Write your comment here...\') this.value=\'\';" id="txtcomment" class="textfield" rows="3" name="txtcomment" style="width:290px">Write your comment here...</textarea>
				</td>
				</tr>
				<tr>
				<td>
				<input type="file" name="exp_photo_name" id="exp_photo_name" />
				</td>
				</tr>
				<tr>
				<td align="center">
				<input type="button" onclick="javascript:document.frmphoto.submit();" value="Upload Photo" name="btnExpUploadPhoto" id="btnExpUploadPhoto" class="btn_small" title="Upload Photo" />
				</td>
				</tr>
				</table>
				</form>
	 </div>
	  <!-- END ADD PHOTO--- -->';
	  		echo $NewphotoCnt;

		 ?>
	
       </div>
<input type="hidden" name="img_sort" id="img_sort" value="<?=$_GET['sort']?>">
<input type="hidden" name="imgswap_from" id="imgswap_from" value="">
<input type="hidden" name="imgswap_to" id="imgswap_to" value="">
<script>
function allowDrop(ev,id)
{
	ev.preventDefault();
}

function drag(ev,id)
{
	ev.dataTransfer.setData("Text",ev.target.id);
	document.getElementById('imgswap_from').value=id;
}

function drop(ev,id)
{
	document.getElementById('imgswap_to').value=id;
	
	var img_from=document.getElementById('imgswap_from').value;
	var img_to=document.getElementById('imgswap_to').value;
	var img_sort =document.getElementById('img_sort').value;
	
	ev.preventDefault();
	var data=ev.dataTransfer.getData("Text");
	ev.target.appendChild(document.getElementById(data));
	ajax_action('swap_image','imgswap_to','imgswap_from='+img_from+'&imgswap_to='+img_to+'&img_sort='+img_sort);
}
</script>

	   <div class="clear"></div>        
  </div>
</div>
<!-- START EDIT EXPERT BOARD CODE AND DIV -->	
<form action="" method="post" name="frmeditexpert" enctype="multipart/form-data">
<?php
$sel_expert=mysql_query("SELECT * FROM tbl_app_expertboard WHERE expertboard_id=".$expertboard_id);
while($fetchExpertRow=mysql_fetch_array($sel_expert)){
	$expertboard_title=trim($fetchExpertRow['expertboard_title']);
	$expertboard_detail=trim(stripslashes($fetchExpertRow['expertboard_detail']));
	$category_id=(int)$fetchExpertRow['category_id'];
	$expertboard_id=(int)$fetchExpertRow['expertboard_id'];
	$goal_days=(int)$fetchExpertRow['goal_days'];
	$price=(int)$fetchExpertRow['price']; 	
}
?>
	<input type="hidden" name="expertId" id="expertId" value="<?=$expertboard_id?>">
	<div style="display: none; position: fixed; opacity: 1; z-index: 11000; left: 50%; margin-left: -330px; top: 100px;" id="edit_expert" class="popup_div">
		        <a class="modal_close" href="#" title="close"></a>
                <div class="msg_red" id="div_frm_expmsg"></div>
				<div align="center" class="head_20">Edit Expert Board</div><br>
                <div class="red_circle">1</div><strong>Title</strong>:
	<input type="text" name="title" id="title" value="<?=$expertboard_title?>" style="margin-bottom:5px;margin-left:81px;"><br>
	<div class="red_circle">2</div><strong>Detail</strong>:&nbsp;
    <textarea id="detail"  name="detail" class="search_1" style="height:35px;width:300px;margin-bottom:5px;vertical-align:top;margin-left:67px;" onFocus="if(this.value=='Enter detail') this.value='';" onBlur="if(this.value=='') this.value='Enter detail';"><?=$expertboard_detail?></textarea><br>
				<div class="red_circle">3</div><strong>Category</strong>:&nbsp;<span style="margin-left:49px;"><?=getCategoryList($category_id,'','category_id1')?></span><br><br>
				 <div class="red_circle">4</div><strong>Number of days</strong>:
               <input type="text" name="number_days" id="number_days" value="<?=$goal_days?>" style="margin-bottom:5px;width:50px;margin-left:3px;"><br>
				 <div class="red_circle">5</div><strong>Price</strong>:
               <input type="text" name="price" id="price" value="<?=$price?>" style="width:50px;margin-bottom:5px;margin-left:76px;">	
			   <br>
				<input type="submit" class="btn_small right" id="btnEditExpert" value="Save Board" name="btnEditExpert" />
  </div>
  </form>
<!-- END EDIT EXPERT BOARD CODE AND DIV  -->
<!-- START DAYS CONFIG -->
<div style="display: none; position: fixed; opacity: 1; z-index: 11000; left: 50%; margin-left: -330px; top: 200px;height:400px;overflow:auto;" id="daysconfig" class="popup_div" align="center">
                <a class="modal_close" href="#" title="close"></a>
                <span class="head_20">Days Config</span><br>
				<form action="" method="post" name="frmdaysconfig" enctype="multipart/form-data">
				<table>
				<tr>
				<td>
					<table>
					<?php
					$selDays=mysql_query("SELECT * FROM tbl_app_expertboard_days where expertboard_id=".$expertboard_id." ORDER BY expertboard_day_id");
					$cnt=1;
					while($row=mysql_fetch_array($selDays)){
						 $expertboardDayId=$row['expertboard_day_id'];
						 $dayTitle=$row['day_title'];	
						
					?>
					<tr>
						<td>Day <?=$cnt?> : </td>
						<td><input type="text" name="day_<?=$expertboardDayId?>" value="<?=$dayTitle?>" /></td>
					</tr>
					<?php 
						 $cnt++;
					} ?>
					</table>
				</td>				  
			   <tr>
					<td><input type="submit" class="btn_small" value="Save Days" name="btnAddDays" style="margin-left:140px" /></td>
			   </tr>
			   </table>
				</form>
	 </div>
<!-- END DAYS CONFIG -->
<!-- START SEND THANK YOU CODE -->
<form action="" method="post" name="frmsndthank" enctype="multipart/form-data">
<div style="display: none; position: fixed; opacity: 1; z-index: 11000; left: 50%; margin-left: -330px; top: 100px; width:280px;" id="sendThankYou" class="popup_div">
		<a class="modal_close" href="#" title="close"></a>
		<div class="msg_red" id="div_frm_sndmsg"></div>
		<div id="div_send_thankYou">
			<div align="center" class="head_20">Send Thank You</div><br>
			<span style="padding-left:20px;"><strong>Email</strong>:
				<input type="text" style="width:200px;" name="send_email" id="send_email" onblur="if(this.value=='') this.value='Enter Email';" onfocus="if(this.value=='Enter Email') this.value='';" value="Enter Email" /></span><br>	
		   <br>
			<input type="button" style="margin-left:110px;" class="btn_small" id="btnsend" onClick="ajax_action('sendThankYou_Expert','div_send_thankYou','cherryboard_id=<?=$cherryboard_id;?>&send_email='+document.getElementById('send_email').value+'&user_id=<?=USER_ID?>');" value="Send" name="btnsend" />
		</div>
</div>
</form>
<!-- END SEND THANK YOU CODE -->  
<?php 
//include('fb_expert_invite.php');?>
<?php include('site_footer.php');?>
