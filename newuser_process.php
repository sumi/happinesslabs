<?php
include_once "fbmain.php";
include('include/app-common-config.php');
?>
<?php include('site_header.php');?>
<!-- START BOTTOM SECTION -->
<?php
$categoryArray=array(1=>"Relationships",2=>"Wellness",3=>"Community",4=>"Career",5=>"Fun",6=>"Style",7=>"Money");
$categoryIdsArray=array(1=>"11",2=>"29",3=>"30",4=>"2",5=>"31",6=>"3",7=>"19");

if($_POST['LoginStep']=="3"){
	$cat_no=(int)$_POST['cat_no'];
	$selected_story=(int)$_POST['selected_story'];
	$selected_friend=$_POST['selected_friend'];
	
	if($selected_story>0&&count($selected_friend)>0){
			$expertBoardId=(int)getFieldValue('expertboard_id','tbl_app_expert_cherryboard','cherryboard_id='.$selected_story);
			if($expertBoardId>0&&$selected_story>0){
				$lastCreatedStoryId=$_POST['lastCreatedStoryId'];
				if($lastCreatedId>0){
						$cherryboard_id=$lastCreatedId;
						$expertDetail=getFieldsValueArray('user_id,expertboard_id','tbl_app_expert_cherryboard','cherryboard_id='.$cherryboard_id);
				
						$user_id=$expertDetail[0];
						$expertboard_id=$expertDetail[1];
						foreach($selected_friend as $req_user_fb_id){
							$chkUser=(int)getFieldValue('meb_id','tbl_app_expert_cherryboard_meb','req_user_fb_id='.$req_user_fb_id.' and cherryboard_id='.$cherryboard_id);
								if($chkUser>0){
									$delOldReq=mysql_query("delete from tbl_app_expert_cherryboard_meb where meb_id=".$chkUser);
								}
								$request_id=rand();		
								$insMeb="INSERT INTO tbl_app_expert_cherryboard_meb (`meb_id`, `cherryboard_id`, `user_id`, `req_user_fb_id`, request_ids, `is_accept`) VALUES (NULL, '".$cherryboard_id."', '".$user_id."', '".$req_user_fb_id."','".$request_id."', '0')";
								$ins_sql=mysql_query($insMeb);
								//=========> START SEND EMAIL CODE <============
								//GET EXPERT STORY BOARD DETAIL
								$expertboard_id=(int)getFieldValue('expertboard_id','tbl_app_expert_cherryboard','cherryboard_id='.$cherryboard_id);
								$expertboard_title=ucwords(trim(getFieldValue('expertboard_title','tbl_app_expertboard','expertboard_id='.$expertboard_id)));
								
								//GET SENDER DETAILS
								$senderUserDetails=getUserDetail($user_id);
								$SenderName=$senderUserDetails['first_name'].' '.$senderUserDetails['last_name'];					
								
								//GET REQUEST USER DETAILS
								$requestUserId=(int)getFieldValue('user_id','tbl_app_users','facebook_id='.$req_user_fb_id);
								if($requestUserId>0){
									$requestUserDetails=getUserDetail($requestUserId);
									$RequestUserName=$requestUserDetails['first_name'].' '.$requestUserDetails['last_name'];
									$requestEmailId=$requestUserDetails['email_id'];
									
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
									////SENT FB Notification
									$notificationMsg=$SenderName.' invited you to story '.$expertboard_title;
									$post = $facebook->api('/'.$req_user_fb_id.'/notifications/', 'post',  array(
		'access_token' => APPID.'|'.SECRET,
		'href' => 'https://www.happinesslabs.com/index.php?frid='.$request_id,  
		'template' => $notificationMsg));
								}
						  }	
						 if($cat_no==8){
							echo '<script language="javascript">document.location=\'index_profile.php\';</script>';
							header('Location:https://www.happinesslabs.com/index_profile.php');
						 }else{	
							echo '<script language="javascript">document.location=\'newuser_process.php?LoginStep=1&cat_no='.$cat_no.'\';</script>';
							header('Location:https://www.happinesslabs.com/newuser_process.php?LoginStep=1&cat_no='.$cat_no);
						}
				}
			}
	 }
	 
}

if($_POST['LoginStep']=="2"){
$cat_no=$_POST['cat_no'];
$selected_story=$_POST['selected_story'];
$LoginStep=$_POST['LoginStep'];
$expertboard_id=(int)getFieldValue('expertboard_id','tbl_app_expert_cherryboard','cherryboard_id="'.$selected_story.'"');

$expertboardDetail=getFieldsValueArray('expertboard_title,expertboard_detail,user_id','tbl_app_expertboard','expertboard_id='.$expertboard_id);
$expertboard_title=$expertboardDetail[0];
$expertboard_detail=$expertboardDetail[1];
$user_id=$expertboardDetail[2];

$userDetail=getUserDetail($user_id,'uid');
$owner_photo=$userDetail['photo_url'];
$owner_name=$userDetail['name'];

//do-it selected story for new user
if($expertboard_id>0&&$selected_story>0){
	$lastCreatedStoryId=(int)createExpertboard($expertboard_id,$selected_story);
}
if($lastCreatedStoryId==0){
	$lastCreatedStoryId=$_POST['lastCreatedStoryId'];
}

//Photo Slider
$cnt=0;
$MainSlide='';
$IconSlide='';
$storyPhoto=mysql_query("select cherryboard_id,expertboard_id from tbl_app_expert_cherryboard where cherryboard_id 
in (".$selected_story.") order by cherryboard_id desc limit 10");
while($storyPhotoRow=mysql_fetch_array($storyPhoto)){
		$cherryboard_id=$storyPhotoRow['cherryboard_id'];
		$expertboard_id=$storyPhotoRow['expertboard_id'];
		$exportPhoto=mysql_query("select cherryboard_id,photo_title,photo_name,photo_day from tbl_app_expert_cherry_photo where cherryboard_id=".$cherryboard_id." order by photo_day");
		$totalExpPhotos=(int)mysql_num_rows($exportPhoto);
		if($totalExpPhotos>0){
			$MainSlidePhotoArr=array();
			while($exportPhotoRow=mysql_fetch_array($exportPhoto)){
				$photo_title=trim(ucwords($exportPhotoRow['photo_title']));
				if($photo_title!=""){$photo_title=' - '.$photo_title;}
				$photo_name=$exportPhotoRow['photo_name'];
				$photo_day=$exportPhotoRow['photo_day'];
				$expertboardTitle=ucwords(getFieldValue('expertboard_title','tbl_app_expertboard','expertboard_id='.$expertboard_id));
				$photoTitle=$expertboardTitle.' : '.getDayType($expertboard_id).' '.$photo_day.$photo_title;
				
				$photoPath='images/expertboard/slider/'.$photo_name;
				if(!is_file($photoPath)){
					$photoPath='images/expertboard/'.$photo_name;
				}
				if(is_file($photoPath)){
					$MainSlide.='<li><img src="'.$photoPath.'" style="width:192px;" alt="'.$photoTitle.'" title="'.$photoTitle.'" id="wows1_'.$cnt.'"/></li>';
					$IconSlide.='<a style="width:192px;" href="#" title="'.$photoTitle.'"><img src="'.$photoPath.'" alt="'.$photoTitle.'"/>'.$cnt.'</a>';
					$MainSlidePhotoArr[$cnt]=$photoPath;
					$cnt++;
				}	
			}
		}	
}

?>
<link rel="stylesheet" type="text/css" href="board_slider/slider3/style.css" />
<form action="" name="frmLoginStep" method="post">
 <div class="relationship_main">
   <div class="Slides_box">
      <?php  if($MainSlide!=""){ ?>
	<!-- 
		
		<div id="wowslider-container1" style="width:192px;height:192px;">
				<div class="ws_images" style="width:192px;height:192px;">
					<ul><?=$MainSlide?></ul>
				</div>
				<div class="ws_bullets" style="display:none">
					<div><?=$IconSlide?></div>
				</div>
		</div> -->
		<img src="images/new_img.png" alt="" /> 
	<?php }else{ ?>
		<img src="images/new_img.png" alt="" /> 
	<?php }?>
     <div class="text_detail"><strong><?=$expertboard_title?></strong></div><?=$expertboard_detail?>
   </div>
   <div class="text_spread">Spread happiness to your friends by sharing your story now</div>
   
    <div class="box_friends">
    <div class="friends_box_main">
   Owner : <?=$owner_name?><br/>
    <div class="friends_box_img"><img src="<?=$owner_photo?>" height="152px" width="182px" alt="" /></div>
   </div>
  <!--  <input name="input_text" type="text"  class="input_text"/> -->
  </div>
  
   <?php	
	 	
		$friends = $facebook->api('/me/friends');
		//print_r($friends);
		$newCnt=1;
		$friendsCnt='';
		foreach ($friends as $key=>$value) {
		//echo count($value) . ' Friends';
			foreach ($value as $fkey=>$fvalue) {
				$friend_id=$fvalue['id'];
				$friend_name=$fvalue['name'];
				$frind_Photo="http://graph.facebook.com/".$friend_id."/picture";
				$friendsCnt.='<div class="friends_box_main">
					<div class="check_box"> <input type="checkbox" name="selected_friend[]" id="checkbox-2-'.$newCnt.'" class="regular-checkbox big-checkbox" value="'.$friend_id.'" /><label for="checkbox-2-'.$newCnt.'"></label></div>
					<div class="friends_box_img"><img src="'.$frind_Photo.'" height="152px" width="182px" alt="" title="'.$friend_name.'" /></div>
				   </div>';
				   $pagePhotosArray[$newCnt]=$frind_Photo;
				   $newCnt++;
			}
		}
		
	echo $friendsCnt;
	?>
  	<div class="wellness_button_images"></div>
    <div class="wellness_button">
      <a href="javascript:void(0);" onclick="javascript:document.frmLoginStep.submit();"><span class="text_relptionship"><?=$categoryArray[$cat_no+1]?></span><br /> STORIER</a>
	</div>
  </div>
	<input type="hidden" value="<?=($cat_no+1)?>" name="cat_no" id="cat_no" />
	<input type="hidden" value="3" name="LoginStep" id="LoginStep" />
	<input type="hidden" value="<?=$lastCreatedStoryId?>" name="lastCreatedStoryId" id="lastCreatedStoryId" />
	<input type="hidden" value="<?=$selected_story?>" name="selected_story" id="selected_story" />
	
	</form>
<?php 
}else if($_REQUEST['LoginStep']=="1"){
$cat_no=$_REQUEST['cat_no'];
?>
<script type="text/javascript">  
function newUserProgressStep1(newCnt,cherryboard_id)  
{  
	var total=document.getElementById('totalChk').value;
	if(document.getElementById('selected_story_'+newCnt).checked){
		for (var j = 1; j <= total; j++) 
		{
			if(j!=newCnt){
				box = eval("document.frmLoginStep.selected_story_" + j);
				if (box.checked == true) box.checked = false;
			}else{
				document.getElementById('selected_story').value=cherryboard_id;
			}
		 }
	}else{
		document.getElementById('selected_story').value=0;
	}
} 
</script> 
 <div class="relationship_main">
   <div class="relationship_text" style="height:257px"><?=$categoryArray[$cat_no]?> Stories</div>
   <img src="images/progress_circle.png" alt="" />
   <form action="" name="frmLoginStep" method="post">
	<div class="mini masonry" id="mini-container" style="padding-top:5px;width:965px;margin:auto;">
  <?php	
	$category_id=$categoryIdsArray[$cat_no];
	
	$giftCnt='';
	$sqlStory="SELECT a.*,b.cherryboard_id FROM tbl_app_expertboard a,tbl_app_expert_cherryboard b WHERE a.expertboard_id=b.expertboard_id AND a.category_id='".$category_id."' AND b.main_board='1' ORDER BY a.expertboard_id";
	$sel=mysql_query($sqlStory);					
	$pagePhotosArray=array();
	if(mysql_num_rows($sel)>0){
		$newCnt=1;
		while($row=mysql_fetch_array($sel)){
			$cherryboard_id=(int)$row['cherryboard_id'];
			$user_id=(int)$row['user_id'];
			$expertboard_id=(int)$row['expertboard_id'];
			$expertboard_title=ucwords(trim($row['expertboard_title']));
			$userDetail=getUserDetail($user_id);
			$userOwnerFbId=$userDetail['fb_id'];
			$userName=$userDetail['name'];
			$price=$row['price'];
			$goal_days=$row['goal_days'];
			$expertboard_detail=$row['expertboard_detail'];
			$DayType=getDayType($expertboard_id);

			$expertPicPath='https://graph.facebook.com/'.$userOwnerFbId.'/picture?type=large';
				
			$Owner_cherryboard_id=(int)getFieldValue('cherryboard_id','tbl_app_expert_cherryboard','expertboard_id="'.$expertboard_id.'" and main_board="1" limit 1');
			
			if($Owner_cherryboard_id>0){
					$TotalCheers=countCheers($expertboard_id,'expertboard');
					/*$giftCnt.='
					<div class="w2 h1 masonry-brick">
					<div class="bottom_box_main">
					<div class="main_box"><br/>
					 <div style="text-align:center"><input type="checkbox" value="0" name=""></div>
						<div class="day_img">
						<a href="expert_cherryboard.php?cbid='.$Owner_cherryboard_id.'">
						<img src="'.$expertPicPath.'" height="150px" width="209px" title="'.$userName.'" data-tooltip="sticky'.$newCnt.'" />
						</a></div>
						<div class="bottom_box_text"><strong>'.$expertboard_title.'</strong><br/></div>
					   <div class="bottom_healthy">
						 <div class="bottom_healthy_im"><img src="images/box.png" alt="" /></div>
						 <div class="bottom_healthy_12">'.$TotalCheers.' cheers!</div>
					   <div style="clear:both"></div>
					   </div>
				   </div>
				   <div class="padding"></div>
				   </div></div>';*/
				 /*  $giftCnt.='<div class="box_main">
					 <div class="check"> <input type="radio" class="regular-checkbox big-checkbox" name="selected_story" value="'.$cherryboard_id.'" id="checkbox-2-'.$newCnt.'" />
					 <label for="checkbox-2-'.$newCnt.'"></label>
					 </div>
					  <div class="box_bg_img" /><img src="'.$expertPicPath.'" height="161px" width="191px" title="'.$userName.'" data-tooltip="sticky'.$newCnt.'" /></div>
					  '.$expertboard_title.'
				   </div>';*/
				 
				   $giftCnt.='<div class="friends_box_main">
					 <div class="check_box"> 
					 <input type="checkbox" onclick="newUserProgressStep1('.$newCnt.','.$cherryboard_id.')" class="regular-checkbox big-checkbox" name="selected_story_'.$newCnt.'" value="'.$cherryboard_id.'" id="selected_story_'.$newCnt.'" />
					 <label for="selected_story_'.$newCnt.'"></label>
					 </div>
					  <div class="friends_box_img" /><img src="'.$expertPicPath.'" width="182px" height="152px" title="'.$userName.'" data-tooltip="sticky'.$newCnt.'"/></div>
					   '.$expertboard_title.'
				   </div>';
				   $pagePhotosArray[$newCnt]=$expertPicPath;
				   $newCnt++;
				 }
			
		}
	}else{
		$giftCnt.='No Story';
	}
	echo $giftCnt;
	?>
   </div>
   <input type="hidden" value="<?=(int)$cat_no?>" name="cat_no" id="cat_no" />
   <input type="hidden" value="2" name="LoginStep" id="LoginStep" />
   <input type="hidden" value="<?=($newCnt-1)?>" name="totalChk" id="totalChk" />
   <input type="hidden" value="0" name="selected_story" id="selected_story" />
   </form>
   <div class="wellness_button_images"></div>
   <div class="wellness_button">
     <a href="javascript:void(0);" onclick="document.frmLoginStep.submit()";>SPREAD<br />
                HAPPINESS<br />
                BY SHARING<br />
                <span class="text_relptionship"><?=$categoryArray[$cat_no]?></span>.</a>
    </div>
			
<div id="mystickytooltip" class="stickytooltip">

   <?php
   $pagePhotoEffect='';
   foreach($pagePhotosArray as $photoCnt=>$photoUrl){
   		$pagePhotoEffect.='<div id="sticky'.$photoCnt.'" class="atip">
			<img src="'.$photoUrl.'" height="200px" width="259px" />
			</div>';
   }
   echo $pagePhotoEffect;
   ?>
  
	</div>
	</div>
<?php 
}else{
?>
<form action="" name="frmLoginStep" method="post">
<div class="relationship_main">
   <div class="welcome_text">Happinesslabs is your Home for your Happiness. Share Happines and spread happiness. Create a Happy Story. Share a Happy Story. Follow a Happy Story.</div>
   
     <div class="wheel_main">
        <div class="wheel_icon_left"><img alt="" src="images/icon_hover.png"></div>
        <div class="wheel_icon"><img src="images/icon_bg-2.png" alt="" /></div>
        <div class="wheel_icon"><img src="images/icon_bg-3.png" alt="" /></div>
        <div class="wheel_icon"><img src="images/icon_bg-4.png" alt="" /></div>
        <div class="wheel_icon"><img src="images/icon_bg-5.png" alt="" /></div>
        <div class="wheel_icon"><img src="images/icon_bg-6.png" alt="" /></div>
        <div class="wheel_icon_right"><img src="images/icon_bg-7.png" alt="" /></div>
     </div>
   <div style="clear:both"></div>
   
   <div class="wheel_main">
    <div class="Wheel_images"><img src="images/icon_wheel-welcome-1.png" alt="" /></div>
    <div class="welcome_text">This is your stories of categories. Select happy stories from 7 categories. Relarionships, Wellness, Community, Career, Fun, Style, Money.</div>
   </div>
   <div style="clear:both"></div>

        <div class="wellness_button_images"></div>
        <div class="wellness_button">
             <a href="javascript:void(0);" onclick="javascript:document.frmLoginStep.submit();">CATEGORY 1:<br />
   <span class="text_relptionship">RELATIONSHIP</span><br />
                        STORIES.</a>
        </div>
        
    </div>
	<input type="hidden" value="1" name="LoginStep" id="LoginStep" />
	<input type="hidden" value="1" name="cat_no" id="cat_no" />
	</form>
<?php
} 
?>
<script src="js/masonry.js"></script>
<script>
  window.onload = function() {    
    var miniWall = new Masonry(document.getElementById('mini-container'), {
      columnWidth: 20,
      foo: 'bar'
    });        
  };
</script>
<?php include('fb_invite.php');?>
<?php include('site_footer.php');?>