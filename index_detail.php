<?php
include_once "fbmain.php";
include('include/app-common-config.php');
$msg='';
	if(isset($_SESSION['redirect'])){//&&SCRIPT_NAME!="index_detail.php"
		if(strpos($_SESSION['redirect'], '?') !== false){
			$strVar='&rs=1';
		}else{
		    $strVar='?rs=1';
		}
		?>
		<script language="javascript">document.location='<?=$_SESSION['redirect'].$strVar?>';</script>
		<?php
	}	
//$data['album'] = array('name'=>"Today Album",'description'=>"Vijay Album Description");
//$new_album = $facebook->api("/me/albums", 'POST', $data['album']);
/*
//At the time of writing it is necessary to enable upload support in the Facebook SDK, you do this with the line:
$facebook->setFileUploadSupport(true);
  
//Create an album
$album_details = array(
        'description'=> 'Vijay Alum '.rand(),
        'name'=> 'New Album '.rand());
$create_album = $facebook->api('/me/albums', 'POST', $album_details);
  
//Get album ID of the album you've just created
$album_uid = $create_album['id'];
  
//Upload a photo to album of ID...
$photo_details = array(
    'description'=> 'Test Photo 1'
);
$file='http://30daysnew.com/images/cherryboard/2132099203_img5.jpg'; //Example image file
$photo_details['image'] = '@' . realpath($file);
  
$upload_photo = $facebook->api('/'.$album_uid.'/photos', 'POST', $photo_details);
*/

//FRIENDS REQUEST CONFIRM CODE
$meb_id=(int)$_GET['meb_id'];
$subType=trim($_GET['subtype']);
if($subType=='confirm'&&$meb_id>0&&USER_ID>0){
	$updtRequest=mysql_query("UPDATE tbl_app_expert_cherryboard_meb SET is_accept='1' WHERE meb_id=".$meb_id);
	if($updtRequest){
		$selFriendsReq=mysql_query("SELECT * FROM tbl_app_expert_cherryboard_meb WHERE meb_id=".$meb_id);
		while($selFriendsReqRow=mysql_fetch_array($selFriendsReq)){
			$cherryboard_id=(int)$selFriendsReqRow['cherryboard_id'];
			$sender_user_id=(int)$selFriendsReqRow['user_id'];
			$req_user_fb_id=trim($selFriendsReqRow['req_user_fb_id']);
			//GET REQUEST USER DETAILS
			$requestUserId=(int)getFieldValue('user_id','tbl_app_users','facebook_id='.$req_user_fb_id);
			$requestUserDetails=getUserDetail($requestUserId);
			$RequestUserName=$requestUserDetails['first_name'].' '.$requestUserDetails['last_name'];
			//GET SENDER DETAILS
			$senderUserDetails=getUserDetail($sender_user_id);
			$SenderName=$senderUserDetails['first_name'].' '.$senderUserDetails['last_name'];
			$senderEmailId=$senderUserDetails['email_id'];
			//GET EXPERT STORY BOARD DETAIL
			$expertboard_id=(int)getFieldValue('expertboard_id','tbl_app_expert_cherryboard','cherryboard_id='.$cherryboard_id);
			$expertboard_title=ucwords(trim(getFieldValue('expertboard_title','tbl_app_expertboard','expertboard_id='.$expertboard_id)));
			//SEND EMAIL CODE
			$to = $senderEmailId;
			$subject = $RequestUserName.' Accepted Your Request.';
			$message = '<table>
						<tr><td>&nbsp;</td></tr>
						<tr><td>Dear '.$SenderName.',</td></tr>
						<tr><td>&nbsp;</td></tr>
						<tr><td>Your Friend&nbsp;'.$RequestUserName.'&nbsp;accepted your invitation to your story&nbsp;"'.$expertboard_title.'"&nbsp;<a href="'.SITE_URL.'/expert_cherryboard.php?cbid='.$cherryboard_id.'"><strong>Click here</strong></a> to see your storyboard.</td></tr>
						<tr><td>&nbsp;</td></tr>
						<tr><td>Love,</td></tr>
						<tr><td>'.REGARDS.'</td></tr>
						</table>';
			SendMail($to,$subject,$message);				
		}
		echo '<script>document.location.href=\'index_detail.php\';</script>';
	}
}
//FRIENDS REQUEST NOTNOW CODE
if($subType=='notnow'&&$meb_id>0&&USER_ID>0){
  $delRequest=mysql_query("DELETE FROM tbl_app_expert_cherryboard_meb WHERE meb_id=".$meb_id);
  if($delRequest){
		echo '<script>document.location.href=\'index_detail.php\';</script>';
  }
}
//DELETE EXPERT BOARD
$debid=(int)$_GET['debid'];
if($debid>0){	 
	$checkUser=getFieldValue('user_id','tbl_app_expert_cherryboard','cherryboard_id='.$debid); 
	if($checkUser==USER_ID){
	  deleteExpertBoard($debid);
	}		 
}

//DELETE EXPERT BOARD
$defid=$_GET['defid'];
if($defid>0){	 
	$frndBoardId=getFieldValue('cherryboard_id','tbl_app_expert_cherryboard_meb','req_user_fb_id="'.FB_ID.'" && cherryboard_id='.$defid); 
	if($frndBoardId==$defid){
	  $delFrndBoard=mysql_query("delete from tbl_app_expert_cherryboard_meb where req_user_fb_id='".FB_ID."' and cherryboard_id=".$defid);
	}		 			
}

//DELETE GOAL STORYBOARD
$dgbid=$_GET['dgbid'];
if($dgbid>0){
	 $checkUser=getFieldValue('user_id','tbl_app_cherryboard','cherryboard_id='.$dgbid); 
	 if($checkUser==USER_ID){
		 $upd_request=mysql_query('delete from tbl_app_cherryboard where cherryboard_id="'.$dgbid.'"');
		 if($upd_request){
			$checklist=mysql_query('delete from tbl_app_checklist where cherryboard_id="'.$dgbid.'"');
			$cherryboard_cheers=mysql_query('delete from tbl_app_cherryboard_cheers where cherryboard_id="'.$dgbid.'"');
			$cherryboard_experts=mysql_query('delete from tbl_app_cherryboard_experts where cherryboard_id="'.$dgbid.'"');
			$cherryboard_meb=mysql_query('delete from tbl_app_cherryboard_meb where cherryboard_id="'.$dgbid.'"');
			$cherry_comment=mysql_query('delete from tbl_app_cherry_comment where cherryboard_id="'.$dgbid.'"');
			$cherry_gift=mysql_query('delete from tbl_app_cherry_gift where cherryboard_id="'.$dgbid.'"');
			$cherry_photo=mysql_query('delete from tbl_app_cherry_photo where cherryboard_id="'.$dgbid.'"');
			$temp_cherryboard_meb=mysql_query('delete from tbl_app_temp_cherryboard_meb where cherryboard_id="'.$dgbid.'"');
		 }
	}	 
}
//delete cherryboard request
$arequest_ids=$_GET['arequest_ids'];
	if($arequest_ids>0){
		$upd_request=mysql_query('update tbl_app_cherryboard_meb set is_accept="1" where request_ids="'.$arequest_ids.'"');
	 $msg="Request accepted successfully.";
}
//accept cherryboard request
$drequest_ids=$_GET['drequest_ids'];
if($drequest_ids>0){
	 $upd_request=mysql_query('delete from tbl_app_cherryboard_meb where request_ids="'.$drequest_ids.'"');
	 //$delete_success = $facebook->api('/'.$drequest_ids,'DELETE');
	 $Errmsg="Request deleted successfully.";
}
//delete cherryboard request
$arequest_ids=$_GET['aexprequest_ids'];
	if($arequest_ids>0){
		$upd_request=mysql_query('update tbl_app_expert_cherryboard_meb set is_accept="1" where request_ids="'.$arequest_ids.'"');
	 $msg="Request accepted successfully.";
}
//accept cherryboard request
$drequest_ids=$_GET['dexprequest_ids'];
if($drequest_ids>0){
	$upd_request=mysql_query('delete from tbl_app_expert_cherryboard_meb where request_ids="'.$drequest_ids.'"');
	 //$delete_success = $facebook->api('/'.$drequest_ids,'DELETE');
	 $Errmsg="Request deleted successfully.";
}

?>
<?php include('site_header.php');
//check use visited system page or not
/*
if(USER_ID>0){
 $system_page=(int)getFieldValue('system_page','tbl_app_users','user_id="'.USER_ID.'"');
 if($system_page==0){
	echo '<script>document.location.href = "setup2.php";</script>';
 }
}
*/
if($_GET['msg']=="addche"){
	$msg="Cherryboard added successfully.";
}
//check for the system page
?>	
<!--Body Start-->
<div id="body_container">
	<div class="wrapper">
<?php
$type=$_GET['type'];
$checkGoal=(int)getFieldValue('cherryboard_id','tbl_app_cherryboard','user_id='.USER_ID);
$checkExpert=(int)getFieldValue('cherryboard_id','tbl_app_expert_cherryboard','user_id='.USER_ID);
$checkRequest=(int)getFieldValue('cherryboard_id','tbl_app_expert_cherryboard_meb','req_user_fb_id='.FB_ID.' and is_accept=1');
$firstUserDetail=getUserDetail(USER_ID);
$firstUserName=$firstUserDetail['name'];
//START NO EXPERTBOARD CODE
if($checkGoal==0&&$checkExpert==0&&$checkRequest==0){
?>
<script language="javascript">document.location='newuser_process.php'</script>
<?php

	if($type=="stepone"){
?>
<div class="main_div">
	<div class="div_left">
		<div class="img_div_1">
		<div class="div_left1"><font size="+1"><strong>Build your happiness.</strong></font><br />
		  <span>Do How-to guides.</span></div>
		  <div class="div_left_bottom" id="div_great">
			<div class="div_left_bottom_in">Start by do-it 1</div>	
			<div class="img_div_2"><img src="images/down.png" alt="" /></div>		
		  </div>	  
		</div>		
		<div class="div_expert">
			<div id="searchwrapper"><form action="">
			<input type="text" class="searchbox" name="search" value="search for..." onFocus="if(this.value=='search for...') this.value='';" onBlur="if(this.value=='') this.value='search for...';" />
			<input type="image" src="images/search.png" class="searchbox_submit" value="" />
			</form>
			</div>	
			<div class="div_doit">
			<?php
			//SELECT * FROM tbl_app_expertboard ORDER BY expertboard_id
			$selExpert=mysql_query("SELECT a.*,b.cherryboard_id FROM tbl_app_expertboard a,tbl_app_expert_cherryboard b WHERE a.expertboard_id=b.expertboard_id AND a.board_type='0' AND b.is_publish='1' ORDER BY expertboard_id");
				while($selExpertRow=mysql_fetch_array($selExpert)){
					$user_id=(int)$selExpertRow['user_id'];
					$expertboard_id=(int)$selExpertRow['expertboard_id'];
					$cherryboard_id=(int)$selExpertRow['cherryboard_id'];
					$expertboard_title=trim($selExpertRow['expertboard_title']);
					$userDetail=getUserDetail($user_id);
					$userOwnerFbId=$userDetail['fb_id'];
					$userName=$userDetail['name'];
					$expertPicPath='https://graph.facebook.com/'.$userOwnerFbId.'/picture?type=large';
					echo '<div class="div_img">
					<img src="'.$expertPicPath.'" class="div_img_small" height="50px" width="50px" /></div>
				<p style="vertical-align:top;"><font color="#FF8000"><strong>'.$userName.'</strong></font><br/>
				<strong>'.$expertboard_title.'</strong></p>
				<div id="div_doit_'.$expertboard_id.'" style="padding-top:2px;padding-bottom:2px;">
				'.($checkExpert>0?'<img src="images/doingit.png" height="25px" width="70px" style="padding-left:160px;" />':'<img src="images/doit.png" onclick="javascript:document.getElementById(\'cherryboard_id\').value='.$cherryboard_id.';ajax_action(\'expert_doit\',\'div_doit_'.$expertboard_id.'\',\'expertboard_id='.$expertboard_id.'&cherryboard_id='.$cherryboard_id.'\');" height="25px" width="70px" style="padding-left:160px; cursor:pointer;" />').'
				</div>';
				}				
		?>
		<input type="hidden" name="cherryboard_key" id="cherryboard_key" value="0" />
		<input type="hidden" name="cherryboard_id" id="cherryboard_id" value="0" />
			</div> 
		</div>
	</div>	
	<div class="div_right">
		<p style="padding-left:10px;"><font size="+1"><strong>Preview</strong></font><br/>
		Steps from How-to guides you do appear here.</p>
		<div id="div_expowner_picture" class="div_content">
		<div class="div_img"><img src="images/no_photo.jpg" height="50px" width="50px" /></div>
		<div style="color:#CCCCCC;background-color:#CCCCCC;height:3px;width:580px;margin-left:60px;margin-top:10px;"></div>
		<div style="color:#CCCCCC;background-color:#CCCCCC;height:3px;width:400px;margin-left:60px;margin-top:15px;"></div>
		<div style="color:#CCCCCC;background-color:#CCCCCC;height:3px;width:200px;margin-left:60px;margin-top:17px;"></div>
		</div>
		<div class="div_content">
		<div class="div_img"><img src="images/no_photo.jpg" height="50px" width="50px" /></div>
		<div style="color:#CCCCCC;background-color:#CCCCCC;height:3px;width:580px;margin-left:60px;margin-top:10px;"></div>
		<div style="color:#CCCCCC;background-color:#CCCCCC;height:3px;width:400px;margin-left:60px;margin-top:15px;"></div>
		<div style="color:#CCCCCC;background-color:#CCCCCC;height:3px;width:200px;margin-left:60px;margin-top:17px;"></div>
		</div>
		<div class="div_content">
		<div class="div_img"><img src="images/no_photo.jpg" height="50px" width="50px" /></div>
		<div style="color:#CCCCCC;background-color:#CCCCCC;height:3px;width:580px;margin-left:60px;margin-top:10px;"></div>
		<div style="color:#CCCCCC;background-color:#CCCCCC;height:3px;width:400px;margin-left:60px;margin-top:15px;"></div>
		<div style="color:#CCCCCC;background-color:#CCCCCC;height:3px;width:200px;margin-left:60px;margin-top:17px;"></div>
		</div>
		<div class="div_content">
		<div class="div_img"><img src="images/no_photo.jpg" height="50px" width="50px" /></div>
		<div style="color:#CCCCCC;background-color:#CCCCCC;height:3px;width:580px;margin-left:60px;margin-top:10px;"></div>
		<div style="color:#CCCCCC;background-color:#CCCCCC;height:3px;width:400px;margin-left:60px;margin-top:15px;"></div>
		<div style="color:#CCCCCC;background-color:#CCCCCC;height:3px;width:200px;margin-left:60px;margin-top:17px;"></div>
		</div>
		<div class="div_content">
		<div class="div_img"><img src="images/no_photo.jpg" height="50px" width="50px" /></div>
		<div style="color:#CCCCCC;background-color:#CCCCCC;height:3px;width:580px;margin-left:60px;margin-top:10px;"></div>
		<div style="color:#CCCCCC;background-color:#CCCCCC;height:3px;width:400px;margin-left:60px;margin-top:15px;"></div>
		<div style="color:#CCCCCC;background-color:#CCCCCC;height:3px;width:200px;margin-left:60px;margin-top:17px;"></div>
		</div>
		<div class="div_content">
		<div class="div_img"><img src="images/no_photo.jpg" height="50px" width="50px" /></div>
		<div style="color:#CCCCCC;background-color:#CCCCCC;height:3px;width:580px;margin-left:60px;margin-top:10px;"></div>
		<div style="color:#CCCCCC;background-color:#CCCCCC;height:3px;width:400px;margin-left:60px;margin-top:15px;"></div>
		<div style="color:#CCCCCC;background-color:#CCCCCC;height:3px;width:200px;margin-left:60px;margin-top:17px;"></div>
		</div>
		<div class="div_content">
		<div class="div_img"><img src="images/no_photo.jpg" height="50px" width="50px" /></div>
		<div style="color:#CCCCCC;background-color:#CCCCCC;height:3px;width:580px;margin-left:60px;margin-top:10px;"></div>
		<div style="color:#CCCCCC;background-color:#CCCCCC;height:3px;width:400px;margin-left:60px;margin-top:15px;"></div>
		<div style="color:#CCCCCC;background-color:#CCCCCC;height:3px;width:200px;margin-left:60px;margin-top:17px;"></div>
		</div>
	</div>
<div style="clear:both;"></div>	
</div><br/><br/>
<?php }else{ ?>  
<div class="main_div">
	<div class="div_left">
		<div class="img_div_1">
		<div class="div_left1"><font size="+1"><strong>Welcome, <?=$firstUserName?></strong></font><br />
		  <span>Get Started in less then 60 second.</span></div>
		<div class="div_left_bottom"><a href="index_detail.php?type=stepone" title="Next"><img src="images/next.png" alt="" style="padding-left:190px;" /></a></div>
		</div>
		<div class="img_div"><img src="images/img.png" alt="" /></div>	
	</div>	
	<div class="div_right">
		<?php //START FRIENDS REQUEST NO GOAL USER CODE
		$RequestCnt='';
		$selFriendsReq=mysql_query("SELECT * FROM tbl_app_expert_cherryboard_meb WHERE is_accept='0' ORDER BY meb_id ");
			while($selFriendsReqRow=mysql_fetch_array($selFriendsReq)){
				$meb_id=(int)$selFriendsReqRow['meb_id'];
				$cherryboard_id=(int)$selFriendsReqRow['cherryboard_id'];
				$sender_user_id=(int)$selFriendsReqRow['user_id'];
				$req_user_fb_id=trim($selFriendsReqRow['req_user_fb_id']);
				//GET USER DETAILS
				$senderUserDetails=getUserDetail($sender_user_id);
				$senderFbId=$senderUserDetails['fb_id'];
				$SenderName=$senderUserDetails['first_name'].' '.$senderUserDetails['last_name'];
				$userPicPath='https://graph.facebook.com/'.$senderFbId.'/picture?type=large';
				$requestUserId=(int)getFieldValue('user_id','tbl_app_users','facebook_id='.$req_user_fb_id);
				//USER REQUEST CODE
				if($requestUserId==USER_ID&&$senderFbId!=''&&(int)USER_ID>0){
					$RequestCnt.='<div style="height:20px;"></div>';
					$expertboard_id=(int)getFieldValue('expertboard_id','tbl_app_expert_cherryboard','cherryboard_id='.$cherryboard_id);			
				//SELECT EXPERTBOARD CODE
				$selBoard=mysql_query("SELECT expertboard_id,expertboard_title,profile_picture FROM tbl_app_expertboard WHERE expertboard_id=".$expertboard_id);
					while($selBoardRow=mysql_fetch_array($selBoard)){
						 $expertBoardId=(int)$selBoardRow['expertboard_id'];
						 $expertboard_title=ucwords(trim($selBoardRow['expertboard_title']));
						 $profile_picture=trim($selBoardRow['profile_picture']);
						 $profilePicPath='images/expertboard/profile/'.$profile_picture;
						 $TotalCheers=countCheers($expertBoardId,'expertboard');
						 $RequestCnt.='<span style="padding-left:20px;font-size:16px;font-weight:bold;">Your Friend '.$SenderName.'&nbsp;<img src="'.$userPicPath.'" height="20" width="20"/>&nbsp;Invited You To His/Her Story '.$expertboard_title.'. </span>';
						 //CHECK USER HAVE PROFILE PICTURE
						 if($profile_picture!=''&&is_file($profilePicPath)){
						 	$userPicPath=$profilePicPath;
						 }
						 $RequestCnt.='<br/><br/>';
						 $RequestCnt.='<div style="margin-left:180px;border:solid 5px #CCCCCC;width:209px;
						 				float:left;">
										   <a href="expert_cherryboard.php?cbid='.$cherryboard_id.'">
										   <img src="'.$userPicPath.'" height="150px" width="209px"
										   title="'.$SenderName.'"/></a><br/>
										   <span style="padding-left:10px;font-size:12px;font-weight:bold;">
										   '.$expertboard_title.'<br/><br/></span>
										   <img src="images/box.png" style="padding-left:10px;" />
										   '.$TotalCheers.' cheers!<br/>
									   </div><br/>';
						 $RequestCnt.='<div style="margin:0px;height:125px;padding-top:90px;">
						 			   &nbsp;&nbsp;<a href="index_detail.php?subtype=confirm&meb_id='.$meb_id.'" title="Confirm" /><img src="images/confirm.png" /></a>&nbsp;&nbsp;
								   <a href="index_detail.php?subtype=notnow&meb_id='.$meb_id.'" title="NotNow" />
								   <img src="images/notnow.png" /></a>
									   </div>';
					}	
					$RequestCnt.='<div style="height:10px;"></div>';
				}	
			}
			//START REQUEST TO TELL A HAPPY STORY CODE
			$RequestCnt.='<div id="div_happy_story">';
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
				if($inviteUserId==USER_ID&&(int)USER_ID>0){
					$RequestCnt.='<span style="padding-left:20px;font-size:16px;font-weight:bold;">Your Friend '.$SenderName.'&nbsp;<img src="'.$userPicPath.'" height="20" width="20"/>&nbsp;Invited You To Tell A Happy Story.</span><br/>';
					$RequestCnt.='<div style="padding-left:185px;">
								  <a rel="leanModal" href="#create_expert_board" title="Tell a Happy Story" />
						  <img src="images/happy_story.png" onclick="ajax_action(\'tell_story_confirm\',\'div_happy_story\',\'invite_user_id='.$invite_user_id.'&stype=confirm\');"/></a>&nbsp;&nbsp;
						  <a href="javascript:void(0);" title="NotNow" />
						  <img src="images/notnow.png" onclick="ajax_action(\'tell_story_notnow\',\'div_happy_story\',\'invite_user_id='.$invite_user_id.'&stype=notnow\');"/></a>
								  </div><br/>';
				}
			}
			$RequestCnt.='</div>';
			echo $RequestCnt;
		?>
		<p style="padding-left:10px;"><font size="+1"><strong>Preview</strong></font></p>
		<div class="div_content">
		<div class="div_img"><img src="images/img_big1.jpg" height="50px" width="50px" /></div>
		<p><strong>The HappinessLabs Teacher</strong><br/>
		This is a Happiness How-To Guide. Happiness How-To Guide Is Picture Base Boards.
		</p>
		</div>
		<div class="div_content">
		<div class="div_img"><img src="images/no_photo.jpg" height="50px" width="50px" /></div>
		<div style="color:#CCCCCC;background-color:#CCCCCC;height:3px;width:580px;margin-left:60px;margin-top:10px;"></div>
		<div style="color:#CCCCCC;background-color:#CCCCCC;height:3px;width:400px;margin-left:60px;margin-top:15px;"></div>
		<div style="color:#CCCCCC;background-color:#CCCCCC;height:3px;width:200px;margin-left:60px;margin-top:17px;"></div>
		</div>
		<div class="div_content">
		<div class="div_img"><img src="images/no_photo.jpg" height="50px" width="50px" /></div>
		<div style="color:#CCCCCC;background-color:#CCCCCC;height:3px;width:580px;margin-left:60px;margin-top:10px;"></div>
		<div style="color:#CCCCCC;background-color:#CCCCCC;height:3px;width:400px;margin-left:60px;margin-top:15px;"></div>
		<div style="color:#CCCCCC;background-color:#CCCCCC;height:3px;width:200px;margin-left:60px;margin-top:17px;"></div>
		</div>
		<div class="div_content">
		<div class="div_img"><img src="images/no_photo.jpg" height="50px" width="50px" /></div>
		<div style="color:#CCCCCC;background-color:#CCCCCC;height:3px;width:580px;margin-left:60px;margin-top:10px;"></div>
		<div style="color:#CCCCCC;background-color:#CCCCCC;height:3px;width:400px;margin-left:60px;margin-top:15px;"></div>
		<div style="color:#CCCCCC;background-color:#CCCCCC;height:3px;width:200px;margin-left:60px;margin-top:17px;"></div>
		</div>
		<div class="div_content">
		<div class="div_img"><img src="images/no_photo.jpg" height="50px" width="50px" /></div>
		<div style="color:#CCCCCC;background-color:#CCCCCC;height:3px;width:580px;margin-left:60px;margin-top:10px;"></div>
		<div style="color:#CCCCCC;background-color:#CCCCCC;height:3px;width:400px;margin-left:60px;margin-top:15px;"></div>
		<div style="color:#CCCCCC;background-color:#CCCCCC;height:3px;width:200px;margin-left:60px;margin-top:17px;"></div>
		</div>
		<div class="div_content">
		<div class="div_img"><img src="images/no_photo.jpg" height="50px" width="50px" /></div>
		<div style="color:#CCCCCC;background-color:#CCCCCC;height:3px;width:580px;margin-left:60px;margin-top:10px;"></div>
		<div style="color:#CCCCCC;background-color:#CCCCCC;height:3px;width:400px;margin-left:60px;margin-top:15px;"></div>
		<div style="color:#CCCCCC;background-color:#CCCCCC;height:3px;width:200px;margin-left:60px;margin-top:17px;"></div>
		</div>
	</div>
<div style="clear:both;"></div>	
</div>
<?php
	  }	
}else{
$category_id=(int)$_GET['category_id'];
?>
<script language="javascript" type="text/javascript" src="js/niceforms.js"></script>
<style type="text/css" media="screen">@import url(niceforms-default.css);</style>
<div class="banner-listof_bg" style="padding:0 7px">
        <form action="vars.php" method="post" class="niceform" target="_blank" style="padding-left:480px;">
        <span class="niceform">
        <?php echo getCategoryList($category_id,'onchange="javascript:document.location=\'index_detail.php?category_id=\'+this.value;"','category_id'); ?>
        </span>
      </form>  
       <div class="banner_listof"><img src="images/banner_listof.png" alt=""  width="470"/></div>
      <div class="banner_day" style="padding-left: 3px;">
        <div class="banner_listof_text">10 easy ways to say "I love you" every day.<br /><br /> 
        <em style="font-size:22px;">by Olivia Janisch</em></div>
        <div class="banner_day_5">
         <div class="banner_day_5_left">
          <div class="banner_day_5_bg" style="border:#3c3c3d;"><a href="#">Do it!</a></div>
          <div class="banner_day_5_im"><img src="images/ban.png" alt=""  height="43"/></div>
         </div>
         <div class="banner_day_5_right"><img src="images/im.png" width="102" height="98" /></div>
        </div>
       </div>
       <div style="clear:both"></div>
   </div>
<!-- START FRIENDS REQUEST GOAL USER SECTION -->   
<div id="div_ExpertFriend_Request" style="width:959px;background-color:#FFFFFF;">
<?php 
$RequestCnt='';
$selFriendsReq=mysql_query("SELECT * FROM tbl_app_expert_cherryboard_meb WHERE is_accept='0' ORDER BY meb_id ");
	while($selFriendsReqRow=mysql_fetch_array($selFriendsReq)){
		$meb_id=(int)$selFriendsReqRow['meb_id'];
		$cherryboard_id=(int)$selFriendsReqRow['cherryboard_id'];
		$sender_user_id=(int)$selFriendsReqRow['user_id'];
		$req_user_fb_id=trim($selFriendsReqRow['req_user_fb_id']);
		//GET USER DETAILS
		$senderUserDetails=getUserDetail($sender_user_id);
		$senderFbId=$senderUserDetails['fb_id'];
		$SenderName=$senderUserDetails['first_name'].' '.$senderUserDetails['last_name'];
		$userPicPath='https://graph.facebook.com/'.$senderFbId.'/picture?type=large';
		$requestUserId=(int)getFieldValue('user_id','tbl_app_users','facebook_id='.$req_user_fb_id);
		//USER REQUEST CODE
		if($requestUserId==USER_ID&&$senderFbId!=''&&USER_ID>0){
			$RequestCnt.='<div style="height:20px;"></div>';
			$expertboard_id=(int)getFieldValue('expertboard_id','tbl_app_expert_cherryboard','cherryboard_id='.$cherryboard_id);			
			//SELECT EXPERTBOARD CODE
			$selBoard=mysql_query("SELECT expertboard_id,expertboard_title,profile_picture FROM tbl_app_expertboard WHERE expertboard_id=".$expertboard_id);
			while($selBoardRow=mysql_fetch_array($selBoard)){
				 $expertBoardId=(int)$selBoardRow['expertboard_id'];
				 $expertboard_title=ucwords(trim($selBoardRow['expertboard_title']));
				 $profile_picture=trim($selBoardRow['profile_picture']);
				 $profilePicPath='images/expertboard/profile/'.$profile_picture;
				 $TotalCheers=countCheers($expertBoardId,'expertboard');
				 $RequestCnt.='<span style="padding-left:150px;font-size:16px;font-weight:bold;">Your Friend '.$SenderName.'&nbsp;<img src="'.$userPicPath.'" height="20" width="20"/>&nbsp;Invited You To His/Her Story '.$expertboard_title.'. </span>';
				 //CHECK USER HAVE PROFILE PICTURE
				 if($profile_picture!=''&&is_file($profilePicPath)){
					$userPicPath=$profilePicPath;
				 }
				 $RequestCnt.='<br/><br/>';
				 $RequestCnt.='<div style="margin-left:350px;float:left;border:solid 5px #CCCCCC;width:209px;">
								   <a href="expert_cherryboard.php?cbid='.$cherryboard_id.'">
								   <img src="'.$userPicPath.'" height="150px" width="209px"
								   title="'.$SenderName.'"/></a><br/>
								   <span style="padding-left:10px;font-size:12px;font-weight:bold;">
								   '.$expertboard_title.'<br/><br/></span>
								   <img src="images/box.png" style="padding-left:10px;" />
								   '.$TotalCheers.' cheers!<br/><br/>
							   </div>';
				 $RequestCnt.='<div style="margin:0px;height:145px;padding-top:105px;">
							   &nbsp;&nbsp;<a href="index_detail.php?subtype=confirm&meb_id='.$meb_id.'" title="Confirm" /><img src="images/confirm.png" /></a>&nbsp;&nbsp;
							   <a href="index_detail.php?subtype=notnow&meb_id='.$meb_id.'" title="NotNow" />
							   <img src="images/notnow.png" /></a>
							   </div>';
			}	
			$RequestCnt.='<div style="height:10px;"></div>';
		}	
	}
	//START REQUEST TO TELL A HAPPY STORY CODE
	$RequestCnt.='<div id="div_tell_happy_story">';
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
		if($inviteUserId==USER_ID&&(int)USER_ID>0){
			$RequestCnt.='<span style="padding-left:150px;font-size:16px;font-weight:bold;">Your Friend '.$SenderName.'&nbsp;<img src="'.$userPicPath.'" height="20" width="20"/>&nbsp;Invited You To Tell A Happy Story.</span><br/>';
			$RequestCnt.='<div style="padding-left:315px;">
						  <a rel="leanModal" href="#create_expert_board" title="Tell a Happy Story" />
						  <img src="images/happy_story.png" onclick="ajax_action(\'tell_story_confirm\',\'div_tell_happy_story\',\'invite_user_id='.$invite_user_id.'&stype=confirm\');"/></a>&nbsp;&nbsp;
						  <a href="javascript:void(0);" title="NotNow" />
						  <img src="images/notnow.png" onclick="ajax_action(\'tell_story_notnow\',\'div_tell_happy_story\',\'invite_user_id='.$invite_user_id.'&stype=notnow\');"/></a>
						  </div><br/><div style="padding-bottom:10px;"></div>';
		}
	}
	$RequestCnt.='</div>';
	echo $RequestCnt;
?>
</div> 
<!-- END FRIENDS REQUEST GOAL USER SECTION -->  
<div class="listoftop_bg">
    <div class="bottom_main">
		<div class="bottom_box_main">
			<div class="todo" style="border:none;">
			  <div class="todolist_left_1"><a href="#" style="cursor:default;">My Goals</a></div>
			  <div style="clear:both"></div> 
			</div>
			<div class="todolist_bt"><img src="images/banet_4.png" alt="" /></div>		
		</div>
		<div class="bottom_box_main" style="float:right;width:340px;margin-left:15px;">
			<div class="todo" style="border:none;width:340px;">
			  <div class="todolist_left_1">
			  <?php
			  $selDoit=mysql_query("SELECT expertboard_id FROM tbl_app_expertboard WHERE user_id='".USER_ID."' AND parent_id='0' AND copyboard_id='0'");
			  $totalDoIt=0;
			  while($selDoitRow=mysql_fetch_array($selDoit)){
			  	$expertBoardId=(int)$selDoitRow['expertboard_id'];
				$cherryBoardId=getExpGoalMainId($expertBoardId);
				$totalDoIt+=(int)getFieldValue('count(cherryboard_id)','tbl_app_expert_cherryboard','expertboard_id='.$expertBoardId.' AND doit_id='.$cherryBoardId);
			  }
			  ?>
			  <a href="#" style="cursor:default;"><?=$totalDoIt?> happy stories spread</a>
			  </div>
			  <div style="clear:both"></div> 
			</div>		
		</div>
		<div class="bottom_box_main" style="float:right;width:340px;">
			<div class="todo" style="border:none;width:340px;">
			  <div class="todolist_left_1">
			  <?php
			  $totalStories=(int)getFieldValue('count(expertboard_id)','tbl_app_expertboard','user_id='.USER_ID.' AND parent_id="0" AND copyboard_id="0"');
			  ?>
			  <a href="#" style="cursor:default;"><?=$totalStories?> happy stories created</a>
			  </div>
			  <div style="clear:both"></div> 
			</div>		
		</div>
	</div> <!-- End of Bottom Main --> 
	<div class="mini masonry" id="mini-container">
	<?php
	//Joined goal boards list
	$selJoinedBoards=mysql_query("select cherryboard_id from tbl_app_expert_cherryboard_meb where req_user_fb_id='".FB_ID."' and is_accept='1'");
	$JoinedBoardsArray=array();
	while($rowJoinedBoards=mysql_fetch_array($selJoinedBoards)){
		$JoinedBoardsArray[]=$rowJoinedBoards['cherryboard_id'];
	}
	$JoinedBoardsCnd='';
	if(count($JoinedBoardsArray)>0){
		$JoinedBoardsId=implode(',',$JoinedBoardsArray);
		if($category_id>0){
			$JoinedBoardsCnd=' OR a.cherryboard_id in ('.$JoinedBoardsId.')';
		}else{
			$JoinedBoardsCnd=' OR cherryboard_id in ('.$JoinedBoardsId.')';
		}
	}	
	 
	//EXPERT GOAL BOARD LIST
	 if($category_id>0){
	    $selCherryQuery="SELECT a.* FROM tbl_app_expert_cherryboard a,tbl_app_expertboard b WHERE a.expertboard_id=b.expertboard_id AND b.category_id=".$category_id." AND (a.user_id=".USER_ID." ".$JoinedBoardsCnd.")";
	 }else{
		$selCherryQuery="select * from tbl_app_expert_cherryboard where user_id=".USER_ID." ".$JoinedBoardsCnd;
	 }
	 
	 $selCherry=mysql_query($selCherryQuery);
	  $totalExpGoals=mysql_num_rows($selCherry);
	  if($totalExpGoals>0){
	  	$cnt=1;
		$delRedirectLink='';
	  	while($selCherryRow=mysql_fetch_array($selCherry)){
			$user_id=$selCherryRow['user_id'];
			$cherryboard_id=$selCherryRow['cherryboard_id'];
			$expertboard_id=$selCherryRow['expertboard_id'];
			$main_board=$selCherryRow['main_board'];
			//START COPYBOARD CODE
			$copyboard_id=(int)$selCherryRow['copyboard_id'];
			$strCopy='';
			$strOriginal='';
			if($copyboard_id>0){
			   $UserId=getFieldValue('user_id','tbl_app_expert_cherryboard','cherryboard_id='.$copyboard_id); 	
			   $strCopy='Copy of';
			   $UserDetail=getUserDetail($UserId);
			   $OriginalName=$UserDetail['name'];
			   $strOriginal='Original by : '.$OriginalName.'<br/>';
			}
			//START COPYBOARD USER LIST
			$copyUserCnt='';			
			$selCopyUser=mysql_query("SELECT cherryboard_id,user_id FROM tbl_app_expert_cherryboard WHERE copyboard_id=".$cherryboard_id." AND user_id!=0");
			if(mysql_num_rows($selCopyUser)>0){
			   $copyUserCnt.='<br/><strong>Copy User List :</strong><br/>';	
			   while($selCopyUserRow=mysql_fetch_array($selCopyUser)){
					$CherryBoardId=(int)$selCopyUserRow['cherryboard_id'];
					$UserId=(int)$selCopyUserRow['user_id'];
					$UserDetail=getUserDetail($UserId);
					$CopyUsrName=$UserDetail['name'];
					$copyUserCnt.=$CopyUsrName.'<br/>';
			   }
			}
				
			if($main_board==1){
				$delRedirectLink='expert_cherryboard.php?delExpId='.$expertboard_id.'';
			}else{
				$delRedirectLink='index_detail.php?debid='.$cherryboard_id.'';
			}			
			
			$expertBoardDetail=getFieldsValueArray('expertboard_title,category_id,goal_days','tbl_app_expertboard','expertboard_id='.$expertboard_id);
			$cherryboard_title=ucwords($expertBoardDetail[0]);
			$category_id=$expertBoardDetail[1];
			$category_name=getFieldValue('category_name','tbl_app_category','category_id='.$category_id);
			$goal_days=$expertBoardDetail[2];
			
			$DayCount=$goal_days.' Days';
			
			$selphoto=mysql_query("select * from tbl_app_expert_cherry_photo where cherryboard_id=".$cherryboard_id." order by photo_id desc");
			$phtotoArray=array();
			$element=0;
			if(mysql_num_rows($selphoto)>0){
				while($selphotoRow=mysql_fetch_array($selphoto)){
					$photo_name=$selphotoRow['photo_name'];
					if($element>0){
						$photoPath='images/expertboard/thumb/'.$photo_name;
					}else{
						$photoPath='images/expertboard/'.$photo_name;
					}
					if(is_file($photoPath)){
						$phtotoArray[]=$photoPath;
						$element++;
					}
					
				}	
			}else{
				$phtotoArray[]='images/cherryboard/no_image.jpg';
			}		
			
			//board delete link
			$delLink='';
			if($user_id==USER_ID){
				$delLink='<a href="'.$delRedirectLink.'" onclick="return confirm(\'Are you sure to delete this board?\')"><img src="images/delete.png"></a>';
			}else{
				$delLink='<a href="index_detail.php?defid='.$cherryboard_id.'" onclick="return confirm(\'Are you sure to delete friend board?\')"><img src="images/delete.png"></a>';
			}
			
			//join board photo & name
			$UserOwnerPhoto='';
			$UserOwnerName='';
			if(in_array($cherryboard_id,$JoinedBoardsArray)){
				$UserDetail=getUserDetail($user_id,'uid');
				$UserOwnerPhoto=$UserDetail['fb_photo_url'];
				$UserOwnerName='&nbsp;<strong>'.$UserDetail['name'].'</strong>';
			}
			$TotalCheers=countCheers($cherryboard_id,'cherryboard');
			?>
				<div class="w2 h1 masonry-brick">
				<div class="bottom_box_main">
					<div class="main_box">
						<table width="100%"><tr><td valign="middle"><?=$UserOwnerName?></td><td align="right"><?=$UserOwnerPhoto?></td></tr></table>
						<div class="day_img">
						<a href="expert_cherryboard.php?cbid=<?=$cherryboard_id?>">
						<img src="<?php echo $phtotoArray[0];?>" width="209px"/>
						</a></div>
						<div class="bottom_box_text">
						<?php echo $strOriginal; ?>
						<strong><?php echo $strCopy.' '.$cherryboard_title.' - '.$category_name;?></strong>&nbsp;&nbsp;<?=$delLink?><br/>
						<?php echo $copyUserCnt; ?>
						</div>
					   <div class="bottom_healthy">
						 <div class="bottom_healthy_im"><img src="images/box.png" alt="" /></div>
						 <div class="bottom_healthy_12"><?=$TotalCheers?> cheers!</div>
					   <div style="clear:both"></div>
					   </div>
				   </div>
				 <div class="padding"></div>
				 </div>
				<!--<div class="field_container">
					<table width="100%"><tr><td valign="middle"><?=$UserOwnerName?></td><td align="right"><?=$UserOwnerPhoto?></td></tr></table>
					<div align="center"><a href="expert_cherryboard.php?cbid=<?php echo $cherryboard_id;?>" style="text-decoration:none;color:#000000;"><strong><?php echo $cherryboard_title.' - '.$category_name;?></strong></a>&nbsp;<?=$delLink?></div><br>
					<img src="<?php echo $phtotoArray[0];?>" height="195" width="195"><br><br>
					<font style="color:#DACB25;font-weight:bold">&nbsp;<?=$DayCount?></font>
					<br><br>
					<?php
					for($i=1;$i<count($phtotoArray);$i++){
					?>
					<img src="<?php echo $phtotoArray[$i];?>" class="img_thumb" style="margin: 0 3px 0 0;">
					<?php } ?>
			   </div>-->
			   </div>
			 <?php 		   
	 		$cnt++;
	 	}
	 }else{
	 	echo "No Goals";
	 }
	?>   
	</div>
<div style="clear:both"></div>
   </div>
<?php
} ?>	  
</div></div>
<div style="height:30px">&nbsp;</div>
<script src="js/masonry.js"></script>
<script>
  window.onload = function() {
    
    var miniWall = new Masonry( document.getElementById('mini-container'), {
      columnWidth: 20,
      foo: 'bar'
    });
        
  };
</script>
<!--Gray body End-->
<!--Body End-->
<?php include('fb_invite.php');?>
<?php include('fb_expert_invite.php');?>
<?php include('site_footer.php');?>