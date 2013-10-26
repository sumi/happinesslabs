<?php
include_once "fbmain.php";
include('include/app-common-config.php');
//include('include/local-app-common-config.php');

$msg='';
$strVar='';
if(isset($_SESSION['redirect'])){//&&SCRIPT_NAME!="index_detail.php"
	if(strpos($_SESSION['redirect'],'?') !== false){
		$strVar='&rs=1';
	}else{
		$strVar='?rs=1';
	}
	?>
	<script language="javascript">document.location='<?=$_SESSION['redirect'].$strVar?>';</script>
	<?php
}

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
<?php include('site_header.php');?>	
<!--Body Start-->
<div id="body_container">
	<div class="wrapper">
<?php
$type=$_GET['type'];
$checkGoal=(int)getFieldValue('cherryboard_id','tbl_app_cherryboard','user_id='.USER_ID);
$checkMission=(int)getFieldValue('user_mission_id','tbl_app_user_happy_mission','user_id='.USER_ID);
$checkExpert=(int)getFieldValue('cherryboard_id','tbl_app_expert_cherryboard','user_id='.USER_ID);
$checkRequest=(int)getFieldValue('cherryboard_id','tbl_app_expert_cherryboard_meb','req_user_fb_id='.FB_ID.' and is_accept=1');
//GET USER DETAILS
$userDetail=getUserDetail(USER_ID,'uid');
$photo_url=$userDetail['photo_url'];
$user_name=$userDetail['name'];

//START NO EXPERTBOARD CODE
if($checkGoal==0&&$checkExpert==0&&$checkRequest==0&&$checkMission==0){
?>
<script language="javascript">document.location='newuser_process.php'</script>
<?php	
}else{
$category_id=(int)$_GET['category_id'];
?>
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
<!-- START HAPPY LIFE STORY BOOK SECTION -->
<link rel="stylesheet" type="text/css" href="css/happiness_book.css" />
<script type="text/javascript" src="book/turn-jquery.js"></script>
<script type="text/javascript" src="book/turn.js"></script>

<div class="relationship_bg" style="background-color:#FFFFFF;">
<div id="magazine" style="margin:auto;">
<?php
$pillarCnt=1;
$pillarArray=array();
$pillarTitleArray=array();
$selPillar=mysql_query("SELECT pillar_no,title FROM tbl_app_happiness_pillar WHERE parent_id=0 ORDER BY pillar_no");
while($selPillarRow=mysql_fetch_array($selPillar)){
	 $pillar_no=$selPillarRow['pillar_no'];
	 $title=ucwords($selPillarRow['title']);
	 $pillarArray[$pillarCnt]=$pillar_no;
	 $pillarTitleArray[$pillarCnt]=$title;
	 $pillarCnt++;
}

$cnt=1;
foreach($pillarArray as $pillar_no){	
	if($cnt==1){
?>
		<!-- FRONT PAGE -->
		<div class="welcome_main" style="background-color:#FFFFFF">
			 <div style="clear:both"></div>
			 <div class="activate_friends_main_top" style="width:580px;">
			   <div class="book_tabs_main_page">
			   <?php
			   $pillarCnt=1;
			   $selPillar=mysql_query("SELECT title FROM tbl_app_happiness_pillar WHERE parent_id=0 ORDER BY pillar_no");
			   while($selPillarRow=mysql_fetch_array($selPillar)){
					 $title=trim(ucwords($selPillarRow['title']));
					
						echo '<div class="book_tabs_left"></div>
							  <div class="book_tabs"><a href="javascript:void(0);" onclick="trun_next()">'.$title.'</a></div>
							  <div class="book_tabs_right"></div>';
					 
					 $pillarCnt++;
			   }
			   ?>
			  </div>
			   <div style="clear:both"></div>              	 
			  <div class="activate_friends_bg"> 
                <div class="Happy_Family_right">
                <?php
				//FRONT RIGHT SIDE MENU
				for($i=1;$i<5;$i++){
					echo '<div class="Happy_Family_Top"></div>
                  		  <div class="Happy_Family_bottom">
                  		  <a href="#" onclick="trun_next()">';
					if($i==1){
						echo '<img src="images/happy_text.png" alt="" />';
					}else if($i==2){
						echo '<img src="images/products_text.png" alt="" />';
					}else if($i==3){
						echo '<img src="images/people_text.png" alt="" />';
					}else if($i==4){
						echo '<img src="images/places_text.png" alt="" />';
					}else{
						echo '<img src="images/plans_text.png" alt="" />';
					}	  
                  	echo '</a></div>
					      <div class="Happy_Family_footer"></div>';
				}
				?>                 
                </div>                       	        	
				<div class="book_page_right1" style="width:554px;">
					<div class="book_profile_text"><img src="<?=$photo_url?>" height="100px" width="100px" /></div>
												<?=$user_name?>
					<div class="life_story_book_text">Life Story Book</div>
				</div>
			  </div>
			 </div>
		   </div>
		<?php
	}
	//LEFT SIDE
	?>
	<!-- PAGE 1 -->
<div class="welcome_main" id="div_refresh_left_mission" style="background-color:#FFFFFF">
     <div style="clear:both"></div>
     <div class="activate_friends_main_top">
       <div class="book_tabs_main_page_left">
       <?php
	   for($i=1;$i<=$cnt;$i++){
			echo '<div class="book_tabs_left'.($i==$cnt?'_love':'').'"></div>
				  <div class="book_tabs'.($i==$cnt?'_love':'').'"><a href="javascript:void(0);" onclick="trun_privious()">'.$pillarTitleArray[$i].'</a></div>
				  <div class="book_tabs_right'.($i==$cnt?'_love':'').'"></div>';
	   }
	   ?>
      </div>
       <div style="clear:both"></div>
      
      <div class="activate_friends_bg">
      	<div class="Happy_Family_left">
         <?php
		//LEFT PAGE LEFT SIDE MENU
		/*for($i=1;$i<5;$i++){
			echo '<div class="Happy_Family_Top"></div>
				  <div class="Happy_Family_bottom">
				  <a href="#" onclick="trun_next()">';
			if($i==1){
				echo '<img src="images/happy_text.png" alt="" />';
			}else if($i==2){
				echo '<img src="images/products_text.png" alt="" />';
			}else if($i==3){
				echo '<img src="images/people_text.png" alt="" />';
			}else if($i==4){
				echo '<img src="images/places_text.png" alt="" />';
			}else{
				echo '<img src="images/plans_text.png" alt="" />';
			}	  
			echo '</a></div>
				  <div class="Happy_Family_footer"></div>';
		}*/
		?>  
          <div class="book_Happy_left_love"></div>
          <div class="book_Happy_love"><a href="#" onclick="trun_privious()">
          <img src="images/happy_text_left.png" alt="" /></a></div>
          <div class="book_Happy_right_love"></div>
          
          <div class="Happy_Family_left_Top"></div>
          <div class="Happy_Family_left_bottom"><a href="#" onclick="trun_privious()">
          <img src="images/products_text_left.png" alt="" /></a></div>
          <div class="Happy_Family_left_footer"></div>
          
          <div class="Happy_Family_left_Top"></div>
          <div class="Happy_Family_left_bottom"><a href="#" onclick="trun_privious()">
          <img src="images/people_text_left.png" alt="" /></a></div>
          <div class="Happy_Family_left_footer"></div>
          
          <div class="Happy_Family_left_Top"></div>
          <div class="Happy_Family_left_bottom"><a href="#" onclick="trun_privious()">
          <img src="images/places_text_left.png" alt="" /></a></div>
          <div class="Happy_Family_left_footer"></div>
          
          <div class="Happy_Family_left_Top"></div>
          <div class="Happy_Family_left_bottom"><a href="#" onclick="trun_privious()">
          <img src="images/plans_text_left.png" alt="" /></a></div>
          <div class="Happy_Family_left_footer"></div>
      </div>   
      <div class="book_page_right" style="width:546;"><!--564-->
           <div class="story_book_Chapter_text">Chapter</div>
           <div class="story_book_love_text"><?=$pillarTitleArray[$cnt]?></div>
           <div class="book_profile_love">
           <img src="images/pillar_<?=$cnt?>.png" alt="" /></div>
      </div>           
      </div>
     </div>
   </div>
	<?php
	
	//RIGHT SIDE
	if($cnt<count($pillarArray)){
	?>
	<div class="welcome_main" style="background-color:#FFFFFF">
     <div style="clear:both"></div>
     <div class="activate_friends_main_top">
       <div class="book_tabs_main_page">         
	  <?php
          for($i=$cnt;$i<=count($pillarArray);$i++){
            echo '<div class="book_tabs_left'.($i==$cnt?'_love':'').'"></div>
              <div class="book_tabs'.($i==$cnt?'_love':'').'"><a href="javascript:void(0);" onclick="trun_next()">'.$pillarTitleArray[$i].'</a></div>
              <div class="book_tabs_right'.($i==$cnt?'_love':'').'"></div>';
       }
       ?>
      </div>
       <div style="clear:both"></div>
      
      <div class="activate_friends_bg" id="div_refresh_right_mission">
      	<div class="Happy_Family_right">
          <div class="Happy_Family_Top"></div>
          <div class="Happy_Family_bottom"><a href="#" onclick="trun_next()">
          <img src="images/happy_text.png" alt="" /></a></div>
          <div class="Happy_Family_footer"></div>
          
          <div class="Happy_Family_Top"></div>
          <div class="Happy_Family_bottom"><a href="#" onclick="trun_next()">
          <img src="images/products_text.png" alt="" /></a></div>
          <div class="Happy_Family_footer"></div>
          
          <div class="Happy_Family_Top"></div>
          <div class="Happy_Family_bottom"><a href="#" onclick="trun_next()">
          <img src="images/people_text.png" alt="" /></a></div>
          <div class="Happy_Family_footer"></div>
          
          <div class="Happy_Family_Top"></div>
          <div class="Happy_Family_bottom"><a href="#" onclick="trun_next()">
          <img src="images/places_text.png" alt="" /></a></div>
          <div class="Happy_Family_footer"></div>
          
          <div class="Happy_Family_Top"></div>
          <div class="Happy_Family_bottom"><a href="#" onclick="trun_next()">
          <img src="images/plans_text.png" alt="" /></a></div>
          <div class="Happy_Family_footer"></div>
       </div>
        <div class="book_page_right1">
        
         <div class="chapter_love">Chapter - <?=$pillarTitleArray[$cnt]?></div>
         <div class="book_right_text">
         <?php
		 $selSubPlr=mysql_query("SELECT * FROM tbl_app_happiness_pillar WHERE parent_id=".$pillarArray[$cnt]." ORDER BY pillar_no");
		   while($selSubPlrRow=mysql_fetch_array($selSubPlr)){
				 $pillar_no=(int)$selSubPlrRow['pillar_no'];
				 $title=trim(ucwords($selSubPlrRow['title']));
				 echo $title."<br />";
				 $selQry=mysql_query("SELECT * FROM tbl_app_life_story_book_template WHERE pillar_no=".$pillar_no);
				 while($selQryRow=mysql_fetch_array($selQry)){
					   $cherryboard_id=(int)$selQryRow['cherryboard_id'];	
					   $subTitle=trim(ucwords($selQryRow['title']));
					   if($cherryboard_id>0){
						  $checkBoard=(int)getFieldValue('cherryboard_id','tbl_app_expert_cherryboard','doit_id="'.$cherryboard_id.'" AND user_id='.USER_ID);
						  $expertboard_id=(int)getFieldValue('expertboard_id','tbl_app_expert_cherryboard','cherryboard_id='.$cherryboard_id);
					   }
					   if($checkBoard==0&&$cherryboard_id>0){
						  echo '<div id="div_doit_story">&nbsp;&nbsp;&nbsp;'.$subTitle.'&nbsp;<a href="javascript:void(0);" style="text-decoration:none;" onclick="ajax_action(\'doit_story\',\'div_doit_story\',\'cherryboard_id='.$cherryboard_id.'&expertboard_id='.$expertboard_id.'&user_id='.USER_ID.'\')">Share the good news with friends</a></div>';
					   }else if($checkBoard>0&&$cherryboard_id>0){
						  echo '&nbsp;&nbsp;&nbsp;'.$subTitle.'&nbsp;<a href="expert_cherryboard.php?cbid='.$checkBoard.'" style="text-decoration:none;">View story</a><br />';
					   }else{
						  echo '&nbsp;&nbsp;&nbsp;'.$subTitle.'<br/>';
					   }					 
				 }
		   }	  
		 ?> 
         </div>
        </div>
      </div>
     </div>
   </div>
	<?php
	}
	$cnt++;
}
?>
</div>
</div>
<!-- END OF HAPPY LIFE STORY BOOK SECTION -->
<div style="clear:both"></div>
   </div>
<?php
} ?>	  
</div></div>
<div style="height:30px">&nbsp;</div>
<!-- START TURN PAGE JAVASCRIPTS -->
<script type="text/javascript">
$(window).ready(function() {
	$('#magazine').turn({
		display: 'double',
		acceleration: true,
		gradients: !$.isTouch,
		elevation:50,
		when: {
			turned: function(e, page) {
				console.log('Current view: ', $(this).turn('view'));
			}
		}
	});
});

$(window).bind('keydown', function(e){	
	if (e.keyCode==37)
		$('#magazine').turn('previous');
	else if (e.keyCode==39)
		$('#magazine').turn('next');
});

function trun_next(){
	$('#magazine').turn('next');
}
function trun_privious(){
	$('#magazine').turn('previous');
}
</script>
<!-- END TURN PAGE JAVASCRIPT -->
<!--Body End-->
<?php include('fb_invite.php');?>
<?php include('fb_expert_invite.php');?>
<?php include('site_footer.php');?>