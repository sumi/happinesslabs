<?php
include_once "fbmain.php";
include('include/app-common-config.php');
$gift_id=(int)$_GET['gid'];
$expertboard_id=(int)$_GET['eid'];
$compain_owner_id=(int)getFieldValue('user_id','tbl_app_gift','gift_id='.$gift_id);
$super_admin=getSuperAdmin(USER_ID);
?>
<?php include('site_header.php');?>
<?php 
if($_GET['dpid']>0){
	$delUserId=(int)getFieldValue('user_id','tbl_app_gift','gift_id='.$_GET['dpid']);
	if($delUserId==USER_ID||$super_admin==1){
		$delReward=mysql_query('delete from tbl_app_gift where gift_id='.$_GET['dpid']);
	}
}
if($_GET['dcmpid']>0){
	$delUserId=(int)getFieldValue('user_id','tbl_app_gift','gift_id='.$_GET['dcmpid']);
	if($delUserId==USER_ID||$super_admin==1){
			$sel_data=mysql_query("select gift_id from tbl_app_gift where campaign_id=".$_GET['dcmpid']."");
			while($sel_dataRow=mysql_fetch_array($sel_data)){
				$sub_gift_id=$sel_dataRow['gift_id'];
				$delReward=mysql_query('delete from tbl_app_gift where gift_id='.$sub_gift_id);		
			}
			$delCmp=mysql_query('delete from tbl_app_gift where gift_id='.$_GET['dcmpid']);		
			if($delCmp){
				echo '<script language="javascript">document.location="gifts.php?type=campaign";</script>';
			}
	}
}
//START UPDATE DAY CONFIG CODE
if(isset($_POST['btnAddDays'])){
	$selDays=mysql_query("SELECT * FROM tbl_app_campaign_days where campaign_id=".$gift_id." ORDER BY campaign_day_id");
	while($row=mysql_fetch_array($selDays)){
		 $campaignDayId=$row['campaign_day_id'];
		 $day_title='day_'.$campaignDayId;
		 $dayTitle=trim($_POST[$day_title]);
		 $updateDay=mysql_query("UPDATE tbl_app_campaign_days SET day_title='".$dayTitle."' WHERE campaign_day_id=".$campaignDayId);		
	}
}
//END UPDATE DAY CONFIG CODE
//START SAVE REWARD PICTURE CODE
if(isset($_POST['saveEditPic'])){
	$save_reward_id=(int)$_POST['save_reward_id'];
	$save_reward_photo= rand().'_'.trim($_FILES['edit_reward_picture']['name']);;
	$save_reward_photo=str_replace(' ','_',$save_reward_photo);
	$save_reward_photo=str_replace('-','_',$save_reward_photo);
	$reward_uploadTempdir = 'images/gift/temp/'.$save_reward_photo; 
	$reward_uploaddir = 'images/gift/'.$save_reward_photo;
	if($save_reward_photo!=''&&$save_reward_id>0){
		$gift_photo=trim(getFieldValue('gift_photo','tbl_app_gift','gift_id='.$save_reward_id));
		$path='images/gift/'.$gift_photo;
		$temp_path='images/gift/temp/'.$gift_photo;
		if(unlink($path)&&unlink($temp_path)){
		   if(move_uploaded_file($_FILES['edit_reward_picture']['tmp_name'],$reward_uploadTempdir)){		
				if($_SERVER['SERVER_NAME']=="localhost"){
					$RetVal=copy($reward_uploadTempdir,$reward_uploaddir);
				}else{
					$Thumb_Command=$ImageMagic_Path."convert ".$reward_uploadTempdir." -thumbnail 150 x 150 ".$reward_uploaddir;
					$last_line=system($Thumb_Command, $RetVal);
				}
				$upd_qry="update tbl_app_gift set gift_photo='".$save_reward_photo."' where gift_id=".$save_reward_id;
				$updateQry=mysql_query($upd_qry);
		  }
	   } 
	}
}
//END SAVE REWARD PICTURE CODE
//START ADD COMPAIGN EXPERT CODE
if(isset($_POST['btnAddExpert'])){
	$expert_name=trim($_POST['expert_name']);
	$expert_picture=trim($_FILES['expert_picture']['name']);
	$expert_photo= rand().'_'.$expert_picture;
	$expert_photo=str_replace(' ','_',$expert_photo);
	$expert_photo=str_replace('-','_',$expert_photo);
	$expert_uploadTempdir = 'images/gift/temp/'.$expert_photo; 
	$expert_uploaddir = 'images/gift/'.$expert_photo;	
	
	if($expert_name!=''&&$expert_picture!=''&&$gift_id>0){
	
	   $ischeck=(int)getFieldValue('campaign_expert_id','tbl_app_campaign_experts','campaign_id='.$gift_id.' and expert_name="'.$expert_name.'"');
	   if($ischeck==0){
	   	  if(move_uploaded_file($_FILES['expert_picture']['tmp_name'],$expert_uploadTempdir)){					
			if($_SERVER['SERVER_NAME']=="localhost"){
				$expert_retval=copy($expert_uploadTempdir,$expert_uploaddir);
			}else{
				$expert_thumb_command=$ImageMagic_Path."convert ".$expert_uploadTempdir." -thumbnail 150 x 150 ".$expert_uploaddir;
				$expert_last_line=system($expert_thumb_command,$expert_retval);						
			}
			echo $ins_sel="INSERT INTO tbl_app_campaign_experts    
			         (campaign_expert_id,campaign_id,expert_name,expert_photo,record_date)
					 VALUES (NULL,'".$gift_id."','".$expert_name."','".$expert_photo."',CURRENT_TIMESTAMP)";
			$ins_sql=mysql_query($ins_sel);
		 }
	   }			
	}else{
			echo "<br /><br /><br /><br /><br /><br /><br />
			<font color='#FF0000' style='text-align:center'><strong>Please enter all fields</strong></font>";	
	}
}
//START	ADD REWARD CODE
if(isset($_POST['btnAddReward'])){
	$campaign_id=$gift_id;	
	$totalReward=(int)$_POST['totalDyndiv'];
	
	$compainDetail=getFieldsValueArray('category_id,campaign_title,is_system,sponsor,goal_days,miss_days,	sponsor_name,sponsor_url,campaign_detail,sponsor_logo','tbl_app_gift','gift_id='.$gift_id);
	$category_id=$compainDetail[0]; 
	$campaign_title=$compainDetail[1]; 
	$is_system=$compainDetail[2]; 
	$sponsor=$compainDetail[3]; 
	$goal_days=$compainDetail[4]; 
	$miss_days=$compainDetail[5]; 
	$sponsor_name=$compainDetail[6]; 
	$sponsor_url=$compainDetail[7]; 
	$campaign_detail=$compainDetail[8]; 
	$sponsor_logo=$compainDetail[9]; 
	
	
	
	for($i=1;$i<=$totalReward;$i++){
		$rewardTitle='reward_title'.$i;
		$reward_title=trim($_POST[$rewardTitle]);
		
		$rewardPhoto='reward_photo'.$i;
		$reward_photo= rand().'_'.trim($_FILES[$rewardPhoto]['name']);
		$reward_photo=str_replace(' ','_',$reward_photo);
		$reward_photo=str_replace('-','_',$reward_photo);
		$uploadTempdir = 'images/gift/temp/'.$reward_photo; 
		$uploaddir = 'images/gift/'.$reward_photo;
		
		if($reward_title!=''&&$reward_photo!=''){
			if(move_uploaded_file($_FILES[$rewardPhoto]['tmp_name'],$uploadTempdir)){					
				if($_SERVER['SERVER_NAME']=="localhost"){
				$retval=copy($uploadTempdir,$uploaddir);
				}else{
				$thumb_command=$ImageMagic_Path."convert ".$uploadTempdir." -thumbnail 150 x 150 ".$uploaddir;
				$last_line=system($thumb_command, $retval);						
				}
				$ins_sel="INSERT INTO tbl_app_gift (gift_id,category_id,gift_title,gift_photo,is_system, sponsor,record_date,goal_days,miss_days,sponsor_url,user_id,campaign_title,campaign_detail,sponsor_logo,sponsor_name,campaign_id)
						VALUES (NULL,'".$category_id."','".$reward_title."','".$reward_photo."','".$is_system."','".$sponsor."', CURRENT_TIMESTAMP,'".$goal_days."','".$miss_days."','".$sponsor_url."','".USER_ID."','".$campaign_title."','".$campaign_detail."','".$sponsor_logo."','".$sponsor_name."','".$campaign_id."')";
				$ins_sql=mysql_query($ins_sel);
			}	
		}else{
			echo "<br /><br /><br /><br /><br /><br /><br />
			<font color='#FF0000' style='text-align:center'><strong>Please enter all fields</strong></font>";	
		}
	}
}
//END ADD REWARD CODE
//START CREATE GOAL BOARD CODE
if((int)$_GET['jid']>0){
	$jid=$_GET['jid'];
	$sel_gift=mysql_query("select * from tbl_app_gift where gift_id=".$jid."");
	while($fetchGiftRow=mysql_fetch_array($sel_gift)){
		$campaign_title=str_replace("'","",$fetchGiftRow['campaign_title']);
		$category_id=(int)$fetchGiftRow['category_id'];
		$gift_id=(int)$fetchGiftRow['gift_id'];
		$goal_days=(int)$fetchGiftRow['goal_days'];
		$miss_days=(int)$fetchGiftRow['miss_days'];
		$sponsor_name=ucwords($fetchGiftRow['sponsor_name']);
		$gift_title=ucwords($fetchGiftRow['gift_title']);
		$gift_photo=$fetchGiftRow['gift_photo'];
		$userArray=getFieldsValueArray('first_name,last_name','tbl_app_users','user_id='.USER_ID);
		$first_name=$userArray[0];
		$last_name=$userArray[1];
		$full_name=$first_name.' '.$last_name;
		if((int)$_GET['cmp_id']>0){
			$gift_id=(int)$_GET['cmp_id'];
		}
		$check_cherryboard_id=(int)getFieldValue('cherryboard_id','tbl_app_cherry_gift','user_id='.USER_ID.' and gift_id='.$gift_id);
	if($check_cherryboard_id==0){	
		//CREATE CHERRYBOARD
		$insRes="INSERT INTO `tbl_app_cherryboard` (`cherryboard_id`, `user_id`, `cherryboard_title`,category_id,record_date) VALUES (NULL, '".USER_ID."', '".addslashes($campaign_title)."','".$category_id."','".date('Y-m-d')."')";
				$insSql=mysql_query($insRes);
				$cherryboard_id=mysql_insert_id();
		//CREATE GIFT
		$insGift="INSERT INTO `tbl_app_cherry_gift` (`cherry_gift_id`, `gift_id`, `cherryboard_id`, `user_id`, `record_date`) VALUES (NULL, '".$gift_id."', '".$cherryboard_id."', '".USER_ID."', CURRENT_TIMESTAMP)";
		mysql_query($insGift);
		
		//CREATE CHECKLIST
		$cmp_user_id=(int)getFieldValue('user_id','tbl_app_gift','gift_id='.$jid);
		$selChk=mysql_query("select checklist from tbl_app_campaign_checklist where campaign_id=".$jid);
		while($selChkRow=mysql_fetch_array($selChk)){
				$insChecklist="INSERT INTO `tbl_app_checklist` (`checklist_id`,user_id, `cherryboard_id`, `checklist`, `record_date`, `is_checked`) VALUES (NULL, '".$cmp_user_id."', '".$cherryboard_id."', '".$selChkRow['checklist']."', CURRENT_TIMESTAMP, '0')";
				mysql_query($insChecklist);
		}		
		//ADD AS MEMBER
		/*$insMeb="INSERT INTO tbl_app_cherryboard_meb (`meb_id`, `cherryboard_id`, `user_id`, `req_user_fb_id`, request_ids, `is_accept`) VALUES (NULL, '".$cherryboard_id."', '".USER_ID."', '".FB_ID."','00000', '1')";
		mysql_query($insMeb);*/		
		//START share into facebook wall
		$category_name=getFieldValue('category_name','tbl_app_category','category_id='.$category_id);
		
		$post_wall_array=array('message' => ''.$full_name.' joined the campaign "'.$campaign_title.'" Reward : '.$gift_title.' Number of days : '.$goal_days.' Number of strikes : '.$miss_days.' Sponsored by : '.$sponsor_name.'','name' => $campaign_title,'description' => 'Lifestyle  change and  habit formation made simple and easy with rewards and picture storyboard. Start your journey by joining a campaign today.','caption' => '','picture' => 'http://30daysnew.com/images/gift/'.$gift_photo,'link' => 'http://30daysnew.com/cherryboard.php?cbid='.$cherryboard_id,'properties' => array(array('text' => 'View Goal Storyboard', 'href' => 'http://30daysnew.com/cherryboard.php?cbid='.$cherryboard_id),),);
		include('post_fb_wall.php');
		$fb_post_id=0;
		if(isset($_SESSION['fb_post_id'])&&$_SESSION['fb_post_id']!=0){
			$fb_post_id=$_SESSION['fb_post_id'];
		}
		//END share into facebook wall 			
		//START FB Created Album
			$facebook->setFileUploadSupport(true);
			//Create an album
			$album_details = array(
				'message'=> 'New album '.ucwords($campaign_title).' added into 30daysnew',
				'name'=> ucwords($campaign_title)
			);
			$create_album = $facebook->api('/me/albums', 'post', $album_details); 
			$fbAlbumId=$create_album['id'];
			$update_albumid=mysql_query("update tbl_app_cherryboard set fb_album_id='".$fbAlbumId."',fb_post_id='".$fb_post_id."' where cherryboard_id=".$cherryboard_id);
			// Upload a pictures
		//END FB Created Album
		echo "<script>document.location='cherryboard.php?cbid=".$cherryboard_id."';</script>";					
	  }
	}	
}
//CODE FOR THE EDIT GIFT
if(isset($_POST['btnSaveCampaign'])&&$_POST['campaign_id']>0){
		$upd_campaign_id=$_POST['campaign_id'];
		$campaign_title=trim(addslashes($_POST['campaign_title']));
		$campaign_detail=$_POST['campaign_detail'];
		$category_id=$_POST['category_id'];
		$sponsor=(int)$_POST['sponsor'];
		$campaign_type=(int)$_POST['campaign_type'];
		$sponsor_name=trim($_POST['sponsor_name']);
		$sponsor_url=trim($_POST['sponsor_url']);
		$sponsor_logo=trim($_FILES['sponsor_logo']['name']);
		$sponsor_file_name=rand().'_'.trim($_FILES['sponsor_logo']['name']);
		$sponsor_file_name=str_replace(' ','_',$sponsor_file_name);
		$sponsor_file_name=str_replace('-','_',$sponsor_file_name);		
		$goal_days=(int)$_POST['goal_days'];
		$miss_days=(int)$_POST['miss_days']; 
		
		$sponsor_uploadTempdir = 'images/gift/temp/'.$sponsor_file_name; 
		$sponsor_uploaddir = 'images/gift/'.$sponsor_file_name;
		
		if($campaign_title!=""&&$category_id>0&&$goal_days>0&&$sponsor_name!=''&&$sponsor_url!=''){
				 $upd_sel="update tbl_app_gift set campaign_title='".$campaign_title."',campaign_detail='".$campaign_detail."',category_id='".$category_id."',sponsor='".$sponsor."',sponsor_name='".$sponsor_name."', sponsor_url='".$sponsor_url."',miss_days='".$miss_days."',goal_days='".$goal_days."',campaign_type='".$campaign_type."' where gift_id=".$upd_campaign_id;
				 $upd_selSql=mysql_query($upd_sel);
				 //update goal days with title
				 $totalConfigDays=getFieldValue('count(campaign_day_id)','tbl_app_campaign_days','campaign_id='.$upd_campaign_id);
				 if($totalConfigDays!=$goal_days){
				 	//when increase days in goal days
				 	if($goal_days>$totalConfigDays){
						for($i=($totalConfigDays+1);$i<=$goal_days;$i++){
							$addDays="INSERT INTO `tbl_app_campaign_days` (`campaign_day_id`, `campaign_id`, `day_no`, `day_title`, `record_date`) VALUES (NULL, '".$upd_campaign_id."', '".$i."', 'Day ".$i."', CURRENT_TIMESTAMP)";
							$addDaysSql=mysql_query($addDays);
						}
					}
					//when decrease days in goal days
				 	if($goal_days<$totalConfigDays){
						for($i=($goal_days+1);$i<=$totalConfigDays;$i++){
							
							$delDays="delete from `tbl_app_campaign_days` where day_no=".$i." and campaign_id=".$upd_campaign_id;
							$delDaysSql=mysql_query($delDays);
						}
					}
					
				 }
				 
				 //sponsor photo upload	
				if($sponsor_logo!=''){ 
				  $sponsor_photo=trim(getFieldValue('sponsor_logo','tbl_app_gift','gift_id='.$upd_campaign_id));
				  $sponsor_photo_path='images/gift/'.$sponsor_photo;
				  $sponsor_photo_Temppath='images/gift/temp/'.$sponsor_photo;
				  if(unlink($sponsor_photo_path)&&unlink($sponsor_photo_Temppath)){
					  if(move_uploaded_file($_FILES['sponsor_logo']['tmp_name'],$sponsor_uploadTempdir)){		
							if($_SERVER['SERVER_NAME']=="localhost"){
								$RetVal=copy($sponsor_uploadTempdir,$sponsor_uploaddir);
							}else{
								$Thumb_Command=$ImageMagic_Path."convert ".$sponsor_uploadTempdir." -thumbnail 150 x 150 ".$sponsor_uploaddir;
								$last_line=system($Thumb_Command, $RetVal);
							}
							$upd_qry="update tbl_app_gift set sponsor_logo='".$sponsor_file_name."' where gift_id=".$upd_campaign_id;
							$updateQry=mysql_query($upd_qry);
					  }
				  }
			    }
		}
}
//END CREATE GOAL BOARD CODE
//START ADD CHECKLIST
if(isset($_POST['btnAddChk'])){
	$totalDyndivChk=(int)$_POST['totalDyndivChk'];
	$cnt=1;
	for($i=1;$i<=$totalDyndivChk;$i++){
		$chklistName='newchklist'.$i;
		$chklist=trim($_POST[$chklistName]);
		if($chklist!="Enter checklist"&&$gift_id>0){
			$chkList=(int)getFieldValue('campaign_chk_id','tbl_app_campaign_checklist','campaign_id='.$gift_id.' and checklist="'.$chklist.'"');
			if($chkList==0){
				$insChklist="INSERT INTO `tbl_app_campaign_checklist` (`campaign_chk_id`, `campaign_id`, `checklist`, `record_date`, `is_checked`) VALUES (NULL, '".$gift_id."', '".$chklist."', CURRENT_TIMESTAMP, '0')";
				$insChklistSql=mysql_query($insChklist);
				$cnt++;
			}
		}
	}	
}
//END ADD CHECKLIST

//save all gift ids
$totalCmpGoals=0;
$view_cherryboard_id=(int)getFieldValue('cherryboard_id','tbl_app_cherry_gift','gift_id='.$gift_id.' and user_id='.USER_ID);
if($view_cherryboard_id==0){
	$sel_data=mysql_query("select gift_id from tbl_app_gift where campaign_id=".$gift_id."");
	while($sel_dataRow=mysql_fetch_array($sel_data)){
		$sub_gift_id=$sel_dataRow['gift_id'];
		$cherryboard_id=(int)getFieldValue('cherryboard_id','tbl_app_cherry_gift','gift_id='.$sub_gift_id.' and user_id='.USER_ID);
		if($cherryboard_id>0){
			$view_cherryboard_id=$cherryboard_id;
			$totalCmpGoals++;
		}
		
	}
}
?>
<!--Body Start-->
<div id="wrapper">
<?php if($gift_id>0){ ?>
	 <!-- START ADD Reward--- -->
		<?php if($compain_owner_id==USER_ID||$super_admin==1){?>
		<a id="go" rel="leanModal" href="#daysconfig" name="test" style="margin-left:271px;" class="btn_small" title="Days Config">Days Config</a>
		<a id="go" rel="leanModal" href="#add_expert1" name="test" class="btn_small" title="+ Add Expert">+ Add Expert</a>
		<a id="go" rel="leanModal" href="#add_checklist" name="test" class="btn_small" title="+ Add Checklist">+ Add Checklist</a>
		<a href="gift_graph.php?type=compain&gift_id=<?=$gift_id?>" name="test" class="btn_small" title="Analytics">Analytics</a>
		<a id="go" rel="leanModal" href="#add_gift1" name="test" class="btn_small" title="+ Add Reward">+ Add Reward</a>
		<a id="go" rel="leanModal" href="#upddiv_reward" name="test" class="btn_small">Edit</a>
		<a href="gift_profile.php?dcmpid=<?=$gift_id?>" onclick="return delCompain(<?=$totalCmpGoals?>)" ><img src="images/delete.png" title="Delete" /></a>
		<?php } ?>
		<!-- Add Checklist -->
		<div style="display: none; position: fixed; opacity: 1; z-index: 11000; left: 50%; margin-left: -330px; top: 200px;" id="add_checklist" class="popup_div" align="center">
                <a class="modal_close" href="#" title="close"></a>
                <span class="head_20">Add Checklist</span><br>
				<form action="" method="post" name="chkdata" id="chkdata" enctype="multipart/form-data">
				<input type="hidden" name="totalDyndivChk" id="totalDyndivChk" value="1" />
				<div id="setup_add_category" class="msg_red">&nbsp;</div>
				<table>
				<tr><td valign="top">Checklist : </td><td>
				<table>
					<tr id="DynDiv1">
						<td>1.</td><td><input type="text" name="newchklist1" onblur="if(this.value=='') this.value='Enter checklist';" onfocus="if(this.value=='Enter checklist') this.value='';" value="Enter checklist" /></td>
						<td><a href="javascript:void(0);" onclick="showDynamicDiv('NewDynDiv','totalDyndivChk');" style="text-decoration:none">+ Add</a>
					</td>
					</tr>
					<?php
					for($p=2;$p<=10;$p++){
					?>
					<tr id="NewDynDiv<?=$p?>" style="display:none">
						<td><?=$p?>.</td><td colspan="2"><input type="text" name="newchklist<?=$p?>" onblur="if(this.value=='') this.value='Enter checklist';" onfocus="if(this.value=='Enter checklist') this.value='';" value="Enter checklist"  /></td>
					</tr>
				<?php }?>
				</table>
				</td></tr>
				<tr><td>&nbsp;</td><td><input type="submit" class="btn_small" value="Add Checklist" name="btnAddChk" /></td></tr>
				</table>
				</form>
	 </div>
		<!-- Add Reward -->
		<div style="display: none; position: fixed; opacity: 1; z-index: 11000; left: 50%; margin-left: -330px; top: 200px;" id="add_gift1" class="popup_div" align="center">
                <a class="modal_close" href="#" title="close"></a>
                <span class="head_20">Add Reward</span><br>
				<div id="setup_add_category" class="msg_red">&nbsp;</div>
				<form action="" method="post" name="data" id="data" enctype="multipart/form-data">
				<input type="hidden" name="totalDyndiv" id="totalDyndiv" value="1" />
				<table>
				<tr id="DynDiv1">
				<td>
					<table>
					<tr>
						<td>1. Reward Title : </td><td><input type="text" name="reward_title1" /></td>
					</tr>
					<tr>	
						<td>Reward Photo : </td>
						<td><input type="file" name="reward_photo1"/>&nbsp;
						<a href="javascript:void(0);" onclick="showDynamicDiv('DynDiv','totalDyndiv');" style="text-decoration:none">+ Add</a></td>
					</tr>
					</table>
				  </td>
				  </tr>	
					<?php
					for($p=2;$p<=5;$p++){
					?>
						<tr id="DynDiv<?=$p?>" style="display:none">
						<td>
							<table>
							<tr>
					<td><?=$p?>. Reward Title : </td><td><input type="text" name="reward_title<?=$p?>" /></td>
							</tr>
							<tr>	
								<td>Reward Photo : </td>
								<td><input type="file" name="reward_photo<?=$p?>"/></td>
							</tr>
							</table>
						  </td>
						  </tr>		
					<?php }?>
			<tr><td><input type="submit" class="btn_small" value="Add Reward" name="btnAddReward" style="margin-left:150px" /></td></tr>
				</table>
				</form>
	 </div>
<!-- END ADD Reward -->
<!-- START ADD EXPERT -->
<div style="display: none; position: fixed; opacity: 1; z-index: 11000; left: 50%; margin-left: -330px; top: 200px;" id="add_expert1" class="popup_div" align="center">
                <a class="modal_close" href="#" title="close"></a>
                <span class="head_20">Add Expert</span><br>
				<div id="setup_add_category" class="msg_red">&nbsp;</div>
				<form action="" method="post" name="addexpert" id="addexpert" enctype="multipart/form-data">
				<table>
				<tr>
				<td>
					<table>
					<tr>
						<td>Expert Name : </td>
						<td><input type="text" name="expert_name" /></td>
					</tr>
					<tr>	
						<td>Expert Picture : </td>
						<td><input type="file" name="expert_picture"/></td>
					</tr>
					</table>
				  </td>				  
				  <tr>
						<td><input type="submit" class="btn_small" value="Add Expert" name="btnAddExpert" style="margin-left:130px" /></td>
				  </tr>
				</table>
				</form>
	 </div>
<!-- END ADD EXPERT -->
<!-- START DAYS CONFIG -->
<div style="display: none; position: fixed; opacity: 1; z-index: 11000; left: 50%; margin-left: -330px; top: 200px;height:400px;overflow:auto;" id="daysconfig" class="popup_div" align="center">
                <a class="modal_close" href="#" title="close"></a>
                <span class="head_20">Days Config</span><br>
				<form action="" method="post" name="daysconfig" id="daysconfig" enctype="multipart/form-data">
				<table>
				<tr>
				<td>
					<table>
					<?php
					$selDays=mysql_query("SELECT * FROM tbl_app_campaign_days where campaign_id=".$gift_id." ORDER BY campaign_day_id");
					$cnt=1;
					while($row=mysql_fetch_array($selDays)){
						 $campaignDayId=$row['campaign_day_id'];
						 $dayTitle=$row['day_title'];	
						
					?>
					<tr>
						<td>Day <?=$cnt?> : </td>
						<td><input type="text" name="day_<?=$campaignDayId?>" value="<?=$dayTitle?>" /></td>
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
	<div id="div_goal_">	
		<div class="field_container1" style="width:960px;">
		<?php	
			$giftCnt='';
			if($gift_id>0){	
				//Start Reward code		
				$Div=1;			
				$giftsIdsArr=array($gift_id);
				$sel_gift=mysql_query("select * from tbl_app_gift where campaign_id=".$gift_id."");
				while($fetchGiftRow=mysql_fetch_array($sel_gift)){
					$reward_id=$fetchGiftRow['gift_id'];
					$campaign_id=(int)$fetchGiftRow['campaign_id'];
					$giftsIdsArr[]=$reward_id;
					$gift_title=ucwords($fetchGiftRow['gift_title']);
					$giftPhoto=$fetchGiftRow['gift_photo'];
					$gift_path='images/gift/'.$giftPhoto;
					//Start Campaign Detail
					$campaignDetail=getFieldsValueArray('campaign_detail,campaign_title,sponsor_name,sponsor_url,sponsor_logo,goal_days,miss_days','tbl_app_gift','gift_id='.$campaign_id);
					$campaign_detail=trim($campaignDetail['campaign_detail']);
					$campaign_title=str_replace("'","",$campaignDetail['campaign_title']);
					$sponsor_name=ucwords($campaignDetail['sponsor_name']);
					$sponsor_url=trim($campaignDetail['sponsor_url']);
					$sponsor_logo=$campaignDetail['sponsor_logo'];
					$num_days=$campaignDetail['goal_days'];
					$strikes_days=$campaignDetail['miss_days'];
					$logoPath='images/gift/'.$sponsor_logo;
								
					$totalReGoals=(int)getFieldValue('count(cherry_gift_id)','tbl_app_cherry_gift','gift_id='.$reward_id);					
					//END campaign detail
					$fb_post=(int)getFieldValue('fb_post','tbl_app_gift_link','gift_id="'.$reward_id.'" AND user_id='.USER_ID);
					$cherryboard_id=(int)getFieldValue('cherryboard_id','tbl_app_cherry_gift','gift_id='.$reward_id.' and user_id='.USER_ID);								
					$str_detail='';
					if(strlen($campaign_detail)>100){
						$str_detail=''.substr($campaign_detail,0,100).'...<a href="javascript:void(0);" style="text-decoration:none;color:#990000" onclick="ajax_action(\'get_more\',\'div_more'.$Div.'\',\'gift_id='.$gift_id.'\')">More</a>';
					}else{
						$str_detail=$campaign_detail;
					}
					//find reward joined users
					$selQuery="SELECT b.first_name,b.last_name,b.fb_photo_url FROM tbl_app_cherry_gift a,tbl_app_users b where a.user_id=b.user_id and a.gift_id=".$reward_id." order by a.record_date";
					$selGift=mysql_query($selQuery);
					$UserPhotos='';
					if(mysql_num_rows($selGift)>0){
						while($rowGift=mysql_fetch_array($selGift)){
							$user_name=ucwords($rowGift['first_name'].' '.$rowGift['last_name']);
							$fb_photo_url=$rowGift['fb_photo_url'];
							$UserPhotos.='<div class="gift" style="vertical-align:bottom;padding:0px 3px 0px 0px;"><img src="'.$fb_photo_url.'"  class="imgsmall" title='.$user_name.' style="margin-bottom:0px;height: 30px;width: 30px;" /></div>';
						}	
					}	
					if(is_file($gift_path)){
					  $giftCnt.='<table width="950px" align="center" border="0" id="tblgift'.$reward_id.'">
					  			 <tr>
								 <td width="239px">'.($Div==1?'<strong>Checklist Items</strong>':'').'</td>
								 <Td width="300px"><div id="div_reward'.$reward_id.'"  style="font-size:19px;color:#000000;font-weight:bold">'.ucwords($gift_title).'&nbsp;&nbsp;'.($compain_owner_id==USER_ID||$super_admin==1?'<img src="images/edit.png" height="16" style="cursor:pointer" onclick="ajax_action(\'edit_reward_title\',\'div_reward'.$reward_id.'\',\'rid='.$reward_id.'\')" width="16" title="Edit Title" />&nbsp;<a href="gift_profile.php?gid='.$campaign_id.'&dpid='.$reward_id.'" onclick="return delReward('.(int)$totalReGoals.');"><img src="images/delete.png" title="Delete" /></a>':'').'</div></td>
								 <td width="10px">&nbsp;</td>
								 <td align="right">&nbsp;';
								  if($compain_owner_id==USER_ID||$super_admin==1){
										$giftCnt.='<br/><a href="gift_graph.php?type=gift&gift_id='.$reward_id.'" name="test" class="btn_small" title="Analytics">Analytics</a>';
							  		}	
					   $giftCnt.='</td>
								 </tr>
								 <tr>
								     <td valign="top">';
									 if($Div==1){
									  	$selchk=mysql_query("select *,date_format(record_date,'%m-%d-%Y') as record_date from tbl_app_campaign_checklist where campaign_id =".$campaign_id." order by campaign_chk_id");
										$checkListCnt='';
										$cnt=1;
										while($selchkRow=mysql_fetch_array($selchk)){
											$campaign_chk_id=$selchkRow['campaign_chk_id'];
											$checklist=$selchkRow['checklist'];
											$record_date=$selchkRow['record_date'];
											$is_checked=$selchkRow['is_checked'];
											$checkCnt.='<div class="box_container"><span>'.$cnt.'.&nbsp;'.$checklist.'</span>&nbsp;&nbsp;';
											if($compain_owner_id==USER_ID||$super_admin==1){
												$checkCnt.='<a class="delete" onclick="ajax_action(\'delete_chk\',\'chk_div\',\'campaign_chk_id='.$campaign_chk_id.'&campaign_id='.$gift_id.'\')" href="#"><img src="images/delete.png"></a></div>';
											}
											$cnt++;
										}
								 		$giftCnt.='<div id="chk_div">'.$checkCnt.'</div>';
									 }
					  $giftCnt.='</td>
								     <td><div id="div_reward_picture'.$reward_id.'"> 
									 <div class="img_big_container">
									 <div class="send_message">
										<div class="actions1">'.($compain_owner_id==USER_ID||$super_admin==1?'<a href="javascript:void(0);" onclick="ajax_action(\'edit_reward_picture\',\'div_reward_picture'.$reward_id.'\',\'rid='.$reward_id.'\')" class="msg">Change Image</a>':'').'</div>
									 </div>
									  <img src="'.$gift_path.'" height="200px" width="200px">
									</div></div></td>';
					 $giftCnt.='<td>&nbsp;</td>
									 <td valign="top">
									 <table width="100%">
									 <tr><td>
									 <div id="div_more'.$Div.'">
									 <font size="+1"><strong>'.ucwords($campaign_title).'</strong></font><br>
							'.(trim($str_detail)!=''?''.trim($str_detail).'':'No campaign details').'</div><br/><div style="vertical-align:bottom;padding:81px 0px 0px 0px;">Experts:<br/><div id="div_delete_exp">'.getCompainExperts('cmp',$campaign_id).'</div></div>
							</td><td align="right" valign="top" style="padding-right:40px;">&nbsp;';							
							$giftCnt.='</td></tr></table>
							</td>
								 </tr>
								 <tr>
								     <td>&nbsp;</td>
									 <td>
									  <table>
									  <tr>
										 <td>Sponsored By :</td>
										 <td>'.(is_file($logoPath)?'<a href="'.$sponsor_url.'" style="text-decoration:none;color:#990000"><div class="gift" style="vertical-align:bottom;padding:0px 0px 0px 0px;"><img src="'.$logoPath.'"  class="imgsmall" style="margin-bottom:0px;" title="'.$sponsor_name.'" /></div></a>':'').'
										 </td>
										 <td><a href="'.$sponsor_url.'" style="text-decoration:none;color:#990000">'.$sponsor_name.'</a>
	</td>
										 </tr>
										  <tr>
										  <td align="right">Joined By :</td>
										  <td colspan="2">
										  &nbsp;'.$UserPhotos.'
										 </td>
										 </tr>
										 </table>
									 </td>
									 <td>&nbsp;</td>
									 <td>Time to win reward:<br><font size="+1"><strong>Total
									  '.$num_days.' Days <br> Strikes '.$strikes_days.' Days</strong></font></td>
								 </tr>
								 <tr>
								 	 <td>&nbsp;</td>
								 	 <td><div id="fb_post'.$reward_id.'">
									  <img style="cursor:pointer" src="images/'.($fb_post==1?'fb_thanks.png':'fb.jpg').'" width="101px" '.($fb_post==1?'':'onclick="postToFeed'.$reward_id.'(); return false;"').'/>&nbsp;
								    <!-- <img src="images/twitter.jpg" width="101px"/>&nbsp;
								     <img src="images/pinterest.jpg" width="101px"/>--></div></td>
									 <td>&nbsp;</td>				   
									 <td><br>';
									 if($cherryboard_id==$view_cherryboard_id&&$cherryboard_id>0){
									 		$giftCnt.='<a id="join" href="cherryboard.php?cbid='.$cherryboard_id.'" name="join" class="btn_small" title="Your Goal">Your Goal</a>';
									 }else{
										if($view_cherryboard_id==0){
											$giftCnt.='<a id="join" href="gift_profile.php?jid='.$campaign_id.'&cmp_id='.$reward_id.'" name="join" class="btn_small" title="Join">JOIN</a>';
										}	
									 }
									$giftCnt.='<br/><br/></td>
								 </tr>
								 </tbody>
								 </table><hr/>';
								 
								$campaign_description='Number Days:'.$num_days.'<center></center>'.'Number Striks:'.$strikes_days.'<center></center>'.$campaign_detail; 
								?>
								<script>
								function postToFeed<?=$reward_id?>() {
			
										// calling the API ...
										var obj<?=$reward_id?> = {
										  method: 'feed',
										  redirect_uri: 'http://30daysnew.com/gift_profile.php?gid=<?=$gift_id?>',
										  link: 'http://30daysnew.com/gift_profile.php?gid=<?=$gift_id?>',
										  picture: 'http://30daysnew.com/images/gift/<?=$giftPhoto?>',
										  name: '<?=ucwords($campaign_title)?>',
										  caption: '<?=ucwords($gift_title)?>',
										  description: '<?=$campaign_description?>'
										};
								
										function callback<?=$reward_id?>(response) {
										  //document.location='http://30daysnew.com/gift_profile.php?gid=<=$gift_id?>&gp=yes';
										  var post_id=response['post_id'];
										  ajax_action('fb_link_post','fb_post<?=$reward_id?>','gift_id=<?=$reward_id?>&post_id='+post_id+'&user_id=<?=USER_ID?>');	  
										}
								
										FB.ui(obj<?=$reward_id?>, callback<?=$reward_id?>);
									  }
									  </script>
								<?php
					}
					$Div++;
				}			
				//End Reward code
			}
			echo $giftCnt;
		?>
		</div>
<form action="" method="post" name="frmgift" enctype="multipart/form-data">		
<?php
if($compain_owner_id==USER_ID||$super_admin==1){
	$QueryGift=mysql_query("select * from tbl_app_gift where gift_id=".$gift_id);
	while($QueryGiftRow=mysql_fetch_array($QueryGift)){
		$category_id=$QueryGiftRow['category_id'];
		$campaign_title=$QueryGiftRow['campaign_title'];
		$campaign_detail=$QueryGiftRow['campaign_detail'];
		$sponsor=$QueryGiftRow['sponsor'];
		$campaign_type=$QueryGiftRow['campaign_type'];
		$sponsor_name=$QueryGiftRow['sponsor_name'];
		$sponsor_url=$QueryGiftRow['sponsor_url'];
		$sponsor_logo=$QueryGiftRow['sponsor_logo'];
		$goal_days=$QueryGiftRow['goal_days'];
		$miss_days=$QueryGiftRow['miss_days'];
		$sponsor_logoPath='';
		if(is_file('images/gift/'.$sponsor_logo)){
			$sponsor_logoPath='<div style="vertical-align:bottom;padding:0px 3px 0px 0px; float: right;" class="gift"><img style="margin-bottom:0px;" christopherson="" title="Cody" class="imgsmall" src="images/gift/'.$sponsor_logo.'"></div>';
		}
	}
 ?>		
   <input type="hidden" name="campaign_id" id="campaign_id" value="<?=$gift_id?>">
	<div style="display: none; position: fixed; opacity: 1; z-index: 11000; left: 50%; margin-left: -330px; top: 200px;" id="upddiv_reward" class="popup_div">
		        <a class="modal_close" href="#" title="close"></a>
                <div class="msg_red" id="div_frm_msg"></div>
				<div align="center" class="head_20">Edit Reward</div><br>
                <div class="red_circle">1</div><strong>Campaign Title</strong>:
	<input type="text" name="campaign_title" id="campaign_title" value="<?=$campaign_title?>" style="margin-bottom:5px;margin-left:14px;"><br>
	<div class="red_circle">2</div><strong>Campaign Detail</strong>:&nbsp;
    <textarea id="campaign_detail"  name="campaign_detail" class="search_1" style="height:35px;width:300px;    margin-bottom:5px;vertical-align:top" onFocus="if(this.value=='Enter campaign detail') this.value='';" onBlur="if(this.value=='') this.value='Enter campaign detail';"><?=$campaign_detail?></textarea><br>
				<div class="red_circle">3</div><strong>Category</strong>:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=getCategoryList($category_id)?>
				<br><br>
				<div class="red_circle">4</div><strong>Campaign Type</strong>:&nbsp;&nbsp;&nbsp;<input type="radio" name="campaign_type" id="campaign_type" <?=($campaign_type==1?'checked="checked"':'')?> value="1">&nbsp;Private&nbsp;&nbsp;<input type="radio" name="campaign_type" id="campaign_type" <?=($campaign_type==0?'checked="checked"':'')?> value="0">&nbsp;Public
				<br><br>
	            <div class="red_circle">5</div><strong>Sponsor</strong><br>
                 <input type="radio" name="sponsor" id="sponsor" <?=($sponsor==1?'checked="checked"':'')?> value="1">&nbsp;I want to find sponsor for this reward.&nbsp;&nbsp;<input type="radio" name="sponsor" <?=($sponsor==2?'checked="checked"':'')?> id="sponsor" value="2">&nbsp;I want to sponsor this reward&nbsp;<br/><input type="radio" name="sponsor" id="sponsor" <?=($sponsor==3?'checked="checked"':'')?> value="3" style="margin-left:32px;">&nbsp;I want to reward this to myself 
				 <br><br>
				<div class="red_circle">6</div><strong>Sponsored by</strong>:
         <input type="text" name="sponsor_name" id="sponsor_name" value="<?=$sponsor_name?>" style="margin-left:21px;margin-bottom:5px;width:170px;"><br>
				 <div class="red_circle">7</div><strong>Sponsor url</strong>:
              <input type="text" name="sponsor_url" id="sponsor_url" value="<?=$sponsor_url?>" style="margin-left:36px;margin-bottom:5px;"><br>
				 <div class="red_circle">8</div><?=$sponsor_logoPath?><strong>Sponsor logo</strong>&nbsp;:&nbsp;
                 <input name="sponsor_logo" id="sponsor_logo" type="file" style="margin-left:17px;margin-bottom:5px;"><br>
				 <div class="red_circle">9</div><strong>Number of days</strong>:
               <input type="text" name="goal_days" id="goal_days" value="<?=$goal_days?>" style="margin-bottom:5px;width:50px;margin-left:10px;"><br>
				 <div class="red_circle">10</div><strong>No. of strikes</strong>:
                <input type="text" name="miss_days" id="miss_days" value="<?=$miss_days?>" style="width:50px;margin-bottom:5px;margin-left:17px;"><br>
				<input type="submit" class="btn_small right" id="btnSaveCampaign" value="Save Campaign" name="btnSaveCampaign" />
  </div>
<?php
}
 ?>	 
  </form>
	</div>	
	<div class="clear"></div>
<?php } ?>
</div>
<!--Body End-->
<?php include('site_footer.php');?>