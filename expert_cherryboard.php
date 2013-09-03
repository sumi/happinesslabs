<?php
include_once "fbmain.php";
include('include/app-common-config.php');
$cherryboard_id=$_GET['cbid'];
$mainExpCherryId=(int)getFieldValue('cherryboard_id','tbl_app_expert_cherryboard','cherryboard_id="'.$cherryboard_id.'" and main_board="1"');
$expertboard_id=getFieldValue('expertboard_id','tbl_app_expert_cherryboard','cherryboard_id='.$cherryboard_id);
$expOwner_id=getFieldValue('user_id','tbl_app_expertboard','expertboard_id='.$expertboard_id);
$expCreator_id=getFieldValue('user_id','tbl_app_expert_cherryboard','cherryboard_id='.$cherryboard_id);
$DayType=getDayType($expertboard_id);
$checkIsExpertBoard=(int)getFieldValue('cherryboard_id','tbl_app_expert_cherryboard','expertboard_id='.$expertboard_id.' and user_id='.USER_ID);
$expertboard_cehrry_id=getFieldValue('cherryboard_id','tbl_app_expert_cherryboard','expertboard_id='.$expertboard_id.' and main_board="1"');
$msg='';
$sort='asc';

//UPLOAD EXPERT PHOTO
$msg="";
if(isset($_SESSION['insert_photo_id'])){
   //START SHARE PHOTO IN ALBUM
	$AlbumDetail=getFieldsValueArray('fb_album_id,cherryboard_title','tbl_app_expert_cherryboard','cherryboard_id='.$cherryboard_id);
	$AlbumId=$AlbumDetail[0];
	$cherryboard_title=$AlbumDetail[1];
	
	$photoDetail=getFieldsValueArray('photo_title,photo_name','tbl_app_expert_cherry_photo','photo_id='.$_SESSION['insert_photo_id']);
	$comment=stripslashes($photoDetail[0]);
	$photo_name=stripslashes($photoDetail[1]);
	//echo "==>".$_SESSION['insert_photo_id']."==".$comment."==".$photo_name."==".$AlbumId;	
	if($AlbumId!="0"&&$AlbumId!=""){
		unset($_SESSION['insert_photo_id']);
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

//START	ADD REWARD CODE
if(isset($_POST['btnAddReward'])){
	$totalReward=(int)$_POST['totalDyndiv'];
	
	for($i=1;$i<=$totalReward;$i++){
		$rewardTitle='reward_title'.$i;
		$reward_title=addslashes(trim($_POST[$rewardTitle]));
		
		$rewardPhoto='reward_photo'.$i;
		$reward_photo= getPhotoName($_FILES[$rewardPhoto]['name']);
		$uploadTempdir = 'images/expertboard/reward/'.$reward_photo; 
		$uploaddir = 'images/expertboard/reward/'.$reward_photo;
		$checkPHoto=(int)getFieldValue($reward_photo,'tbl_app_expert_reward_photo','cherryboard_id='.$cherryboard_id);
		if($reward_title!=''&&$reward_photo!=''&&$checkPHoto==0){
			if(move_uploaded_file($_FILES[$rewardPhoto]['tmp_name'],$uploadTempdir)){					
				$ins_sel="INSERT INTO `tbl_app_expert_reward_photo` (`exp_reward_id`, `user_id`, `cherryboard_id`, `photo_title`, `photo_name`, `record_date`) VALUES (NULL, '".USER_ID."', '".$cherryboard_id."', '".$reward_title."', '".$reward_photo."', CURRENT_TIMESTAMP)";
				$ins_sql=mysql_query($ins_sel);
			}	
		}
	}
}
//END ADD REWARD CODE

//START DELETE EXPERT STORY BOARDS CODE
$delExpId=(int)$_GET['delExpId'];
if($delExpId>0){
	$delExpertboard=mysql_query("DELETE FROM tbl_app_expertboard WHERE expertboard_id=".$delExpId." and user_id=".USER_ID);
	if($delExpertboard){
	   $delGoalExpertDays=mysql_query("DELETE FROM tbl_app_expertboard_days WHERE expertboard_id=".$delExpId);
	   $cherryboard_id=getFieldValue('cherryboard_id','tbl_app_expert_cherryboard','expertboard_id='.$delExpId);
	   if($cherryboard_id>0){
	   		deleteExpertBoard($cherryboard_id);
	   		echo '<script language="javascript">document.location=\'index_detail.php\'</script>';
	   }
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
	$cherrySel=mysql_query("select * from tbl_app_expert_cherryboard where cherryboard_id=".$cherryboard_id);
	while($cherryRow=mysql_fetch_array($cherrySel)){
		$BuyerDetail=getUserDetail($cherryRow['user_id']);
		$BuyerName=$BuyerDetail['name'];
		$BuyerPic=$BuyerDetail['photo_url'];
	}
	
?>
<div style="background:#FFFFFF; margin:0px auto;">
<div id="wrapper" style="padding-top: 0px;margin: 0 auto 0;width:100%">
<input type="hidden" name="cherryboard_id" id="cherryboard_id" value="<?=$cherryboard_id?>" />
<?php
 $expertCnt='';
	  $sel_expert=mysql_query("SELECT * FROM tbl_app_expertboard WHERE expertboard_id=".$expertboard_id);
	  while($fetchExpertRow=mysql_fetch_array($sel_expert)){
	  		//$expertboard_id=(int)$row['expertboard_id'];
			$expertboard_title=ucwords(trim($fetchExpertRow['expertboard_title']));
			$expertboard_detail=trim(stripslashes($fetchExpertRow['expertboard_detail']));
			$customers=trim($fetchExpertRow['customers']);
			$category_id=(int)$fetchExpertRow['category_id'];
			$category_name=getFieldValue('category_name','tbl_app_category','category_id='.$category_id);
			//START PARENT STORY CODE
			$parent_id=(int)$fetchExpertRow['parent_id'];
			if($parent_id>0){
			  $parentBoardTitle=ucwords(trim(getFieldValue('expertboard_title','tbl_app_expertboard','expertboard_id='.$parent_id)));
			  $parentBoardId=(int)getFieldValue('cherryboard_id','tbl_app_expert_cherryboard','expertboard_id="'.$parent_id.'" and main_board="1"');
			  $expertCnt.='<span style="padding-left:450px;font-size:20px;">Parent Story : <a href="expert_cherryboard.php?cbid='.$parentBoardId.'" style="text-decoration:none;color:#000000;">'.$parentBoardTitle.'</a></span>';
			}
			$user_id=(int)$fetchExpertRow['user_id'];			
			$userDetail=getUserDetail($user_id);
			$userOwnerFbId=$userDetail['fb_id'];
			$userName=$userDetail['name'];
			$is_board_price=(int)$fetchExpertRow['is_board_price'];
			$board_type=(int)$fetchExpertRow['board_type'];
			$main_BoardId=(int)getFieldValue('cherryboard_id','tbl_app_expert_cherryboard','expertboard_id="'.$expertboard_id.'" and main_board="1"');
			
			$goal_days=(int)$fetchExpertRow['goal_days'];
			$price=$fetchExpertRow['price'];
			//$expertPicPath='images/expert.jpg';
			$profile_picture=trim($fetchExpertRow['profile_picture']);
			if($profile_picture!=''){
				$expertPicPath='images/expertboard/profile/'.$profile_picture;
			}else{
				$expertPicPath='https://graph.facebook.com/'.$userOwnerFbId.'/picture?type=large';
			}									
			$expert_detail='';
			if(strlen($expertboard_detail)>100){
				$expert_detail=''.substr($expertboard_detail,0,100).'...<a href="javascript:void(0);" style="text-decoration:none;color:#990000" onclick="ajax_action(\'get_more_expert\',\'div_more_expert_'.$expertboard_id.'\',\'expertboard_id='.$expertboard_id.'\')">More</a>';
			}else{
				$expert_detail=$expertboard_detail;
			}
			$created_cherryboard_id=(int)getFieldValue('cherryboard_id','tbl_app_expert_cherryboard','expertboard_id="'.$expertboard_id.'" and main_board="0" AND user_id='.USER_ID);
			
			
			//START INVITE SECTION
			$FriendsCnt='
			<table>
			<tr><td>
			<div id="my_cherryleaders" style="text-align: left; width: 150px;margin-bottom: 1px;">Your Companions&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.(getExpCreator('Expert',$cherryboard_id,USER_ID)==1?'<a href="#" id="invite_frnd" class="gray_link_15">+</a>':'').'<input type="hidden" name="cherryboard_key" id="cherryboard_key" value="0" /><br>
			 <div id="div_goal_followers">';
				$selQuery="select a.meb_id,b.user_id,b.fb_photo_url from tbl_app_expert_cherryboard_meb a,tbl_app_users b where a.req_user_fb_id=b.facebook_id and a.is_accept='1' and a.cherryboard_id=".$cherryboard_id." group by b.user_id limit 10";
				$selSqlQ=mysql_query($selQuery);
				$FriendsArray=array();
				$pageFriendsPhotoArray=array();
				if(mysql_num_rows($selSqlQ)>0){
					$cnt=0;
					while($rowTbl=mysql_fetch_array($selSqlQ)){
						$FriendsArray[]=$rowTbl['user_id'];
						$meb_id=$rowTbl['meb_id'];
						if($cnt==5){$FriendsCnt.='<br/>';}
						$FriendsCnt.='<div class="small_thumb_container">
						<div class="img_big_container1">
							<div class="feedbox_holder">
								<div class="actions">'.(getExpCreator('Expert',$cherryboard_id,USER_ID)==1?'<a class="delete" href="#" onclick="ajax_action(\'delete_goal_followers\',\'div_goal_followers\',\'cherryboard_id='.$cherryboard_id.'&meb_id='.$meb_id.'\')" ><img src="images/delete.png"></a>':'').'</div>
							</div>
							<img src="'.$rowTbl['fb_photo_url'].'" class="thumb" data-tooltip="stickyFriends'.$meb_id.'">
						</div>
						</div>';
						$pageFriendsPhotoArray[$meb_id]=$rowTbl['fb_photo_url'];
						$cnt++;
					}
					
				}else{
					$FriendsCnt.='<strong>No Companions</strong>';
				}
				//echo $FriendsCnt;
			$FriendsCnt.='</div>';
			$FriendsCnt.='</td></tr><tr><td>';
			//Follower request
			$FriendsCnt.='<div id="my_cherryleaders"><div id="div_goal_recent_followers">';
				$selQuery="select meb_id,req_user_fb_id from tbl_app_expert_cherryboard_meb where is_accept='0' and cherryboard_id=".$cherryboard_id." order by meb_id desc limit 10";
				$selSqlQ=mysql_query($selQuery);
				if(mysql_num_rows($selSqlQ)>0){
					$FriendsCnt.='<p>Companions Request</p>';
					$cnt=0;
					while($rowTbl=mysql_fetch_array($selSqlQ)){
						if($cnt==5){$FriendsCnt.='<br/>';}
						$meb_id=$rowTbl['meb_id'];
						$fb_photo_url=getFriendPhoto($rowTbl['req_user_fb_id']);
						$FriendsCnt.='<div class="small_thumb_container">
						<div class="img_big_container1">
							<div class="feedbox_holder">
								<div class="actions">'.(getExpCreator('Expert',$cherryboard_id,USER_ID)==1?'<a class="delete" href="#" onclick="ajax_action(\'delete_goal_recent_followers\',\'div_goal_recent_followers\',\'cherryboard_id='.$cherryboard_id.'&meb_id='.$meb_id.'\')" ><img src="images/delete.png"></a>':'').'</div>
							</div>
							<img src="'.$fb_photo_url.'" class="thumb">
						</div>
						</div>';
						$cnt++;
					}
				}
				//echo $FriendsCnt;
			 $FriendsCnt.='</div></div>';
			 $FriendsCnt.='</div>';
			 $FriendsCnt.='</td></tr></table>';
			//END INVITE SECTION
			
			if($expertPicPath!=""){
				$countShare=(int)getFieldValue('count(link_id)','tbl_app_expert_link','cherryboard_id='.$cherryboard_id);				
				$expertCnt.='
				<div class="banner_bg">
     			<div class="banner_main" style="width:1465px">       
       			<div class="banner">';
				//PHOTO BANNER SECTION CODE
				$exportPhoto=mysql_query("select cherryboard_id,photo_title,photo_name,photo_day from tbl_app_expert_cherry_photo where cherryboard_id=".$cherryboard_id);
				$totalExpPhotos=(int)mysql_num_rows($exportPhoto);
				if($totalExpPhotos==0){
					$exportPhoto=mysql_query("select cherryboard_id,photo_title,photo_name,photo_day from tbl_app_expert_cherry_photo where cherryboard_id=".$expertboard_cehrry_id);
					$totalExpPhotos=(int)mysql_num_rows($exportPhoto);
				}
				if($totalExpPhotos>0){
					$MainSlide='';
					$IconSlide='';
					$cnt=0;
					$MainSlidePhotoArr=array();
					while($exportPhotoRow=mysql_fetch_array($exportPhoto)){
						$photo_title=trim(ucwords($exportPhotoRow['photo_title']));
						if($photo_title!=""){$photo_title=' - '.$photo_title;}
						$photo_name=$exportPhotoRow['photo_name'];
						$photo_day=$exportPhotoRow['photo_day'];
						$photoTitle=getDayType($expertboard_id).' '.$photo_day.$photo_title;
						
						$photoPath='images/expertboard/slider/'.$photo_name;
						if(!is_file($photoPath)){
							$photoPath='images/expertboard/'.$photo_name;
						}
						if(is_file($photoPath)){
							$MainSlide.='<li><img src="'.$photoPath.'" alt="'.$photoTitle.'" title="'.$photoTitle.'" id="wows1_'.$cnt.'"/></li>';
							$IconSlide.='<a href="#" title="'.$photoTitle.'"><img src="'.$photoPath.'" alt="'.$photoTitle.'"/>'.$cnt.'</a>';
							$MainSlidePhotoArr[$cnt]=$photoPath;
							$cnt++;
						}	
					}
					$expertCnt.='<div style="height:75px">
						<div style="float:left;"><a href="#" onclick="javascript:document.getElementById(\'cimemagraph\').style.display=\'inline\';" style="text-decoration:none;color:#000000;padding-right:25px;">Cimemagraph</a></div>
						<div class="img_box_container" id="div_expert_picture'.$expertboard_id.'" style="float:right;">
				<div class="feedbox">';
					$expertCnt.='<div class="message" style="position: absolute; z-index: 3; top: 0px; left: 0px; ">
					'.($user_id==USER_ID?'<a href="javascript:void(0);" onclick="javascript:document.getElementById(\'subtype\').value=\'change_profile_pic\';document.getElementById(\'photo_upload\').style.display=\'inline\';" class="change" style="clear: both; float: left; margin: 20px 0 0 0; background:#FFFFFF; text-decoration:none; font-size:10px; color:#333333; border-radius:0px; border-radius:0px;">Change Photo</a>':'').'
					</div>';
				$expertCnt.='</div>
				<img src="'.$expertPicPath.'" title="'.$userName.'" alt="" height="75px" width="75px"/>
				</div>';					
						
				$expertCnt.='</div><br/>
					<div id="wowslider-container1">
						<div class="ws_images">
							<ul>'.$MainSlide.'</ul>
						</div>
						<div class="ws_bullets" style="display:none">
							<div>'.$IconSlide.'</div>
						</div>
					</div>';
				}else{
					$expertCnt.='<div style="height:100px;">
						<div style="float:right;">
						<img src="'.$expertPicPath.'" height="75" width="75" title="'.$userName.'">
						</div>
						<br/>
						<div style="float:left;">
						<img src="images/expertboard/no_photo_slide.jpg" width="900" height="500" title="No Photo">
						</div>
					</div>';
				}
				//COPY USER CODE 
				$copyboard_id=(int)getFieldValue('copyboard_id','tbl_app_expert_cherryboard','cherryboard_id='.$cherryboard_id);
				$strCopy='';
				$strOriginal='';
				if($copyboard_id>0){
				  $strCopy='Copy of';
				  $OriginalUserId=(int)getFieldValue('user_id','tbl_app_expert_cherryboard','cherryboard_id='.$copyboard_id);
				  $UserDetail=getUserDetail($OriginalUserId);
				  $OriginalName=$UserDetail['name'];
				  $strOriginal='<em>Original by <a href="expert_cherryboard.php?cbid='.$copyboard_id.'" style="text-decoration:none;color:#404041;">'.$OriginalName.'</a></em><br/>';
				}
				$expertCnt.='</div>
       			<div class="banner_day" style="width:315px"><span style="font-size:16px;">'.$strCopy.'</span>
        		<div class="banner_day_1" id="div_exp_title_'.$expertboard_id.'"><a '.($expOwner_id==USER_ID?'href="javascript:void(0);"  ondblclick="ajax_action(\'edt_exp_title\',\'div_exp_title_'.$expertboard_id.'\',\'stype=add&fieldname=expertboard_title&expertboard_id='.$expertboard_id.'&user_id='.USER_ID.'\')" title="Edit Title"':' href="expert_cherryboard.php?cbid='.$main_BoardId.'"').' class="cleanLink">'.$expertboard_title.'</a>
				</div>
        		<div class="banner_day_2"><em>by '.$userName.'</em><br/>'.$strOriginal.'';
				//CREATE PUBLISH BUTTON CODE
				$publishDetail=getFieldsValueArray('user_id,is_publish','tbl_app_expert_cherryboard','cherryboard_id='.$cherryboard_id);
				$UserId=$publishDetail[0];
				$IsPublish=$publishDetail[1];
				if($UserId==USER_ID){
					$expertCnt.='<div id="div_story_publish">';					
					if($IsPublish==0){
						$expertCnt.='<div class="banner_day_5_left" style="width:197px;">
					    <div class="banner_day_5_bg" id="div_story_publish">
						<a href="javascript:void(0);" onclick="ajax_action(\'publish_story\',\'div_story_publish\',\'stype=publish&cherryboard_id='.$cherryboard_id.'&user_id='.(int)USER_ID.'\')" title="Publish">Publish</a>
						</div>
						<img src="images/ban.png" alt="" />
						</div>
						<img src="images/im.png" />';
					}else{
						$expertCnt.='<div class="banner_day_5_left" style="width:245px;">
					    <div class="banner_day_5_bg" id="div_story_publish">
						<a href="javascript:void(0);" onclick="ajax_action(\'unpublish_story\',\'div_story_publish\',\'stype=unpublish&cherryboard_id='.$cherryboard_id.'&user_id='.(int)USER_ID.'\')" title="UnPublish">Unpublish</a>
						</div>
						<img src="images/ban.png" alt="" />
						</div>
						<img src="images/im.png" />';
					}
					$expertCnt.='</div>';
					
				}
				$expertCnt.='</div>
        		<div class="banner_day_3" id="div_more_expert_'.$expertboard_id.'">
				'.($expOwner_id==USER_ID?'<a href="javascript:void(0);" ondblclick="ajax_action(\'edt_exp_detail\',\'div_more_expert_'.$expertboard_id.'\',\'stype=add&fieldname=expertboard_detail&expertboard_id='.$expertboard_id.'&user_id='.USER_ID.'\')" title="Edit Detail" class="cleanLink">':'').' '.(trim($expert_detail)!=''?''.trim($expert_detail).'':'No expert details').'</a>
        		</div>
       			<div class="banner_day_4" id="div_exp_day_'.$expertboard_id.'">Total : '.($expOwner_id==USER_ID?'<a href="javascript:void(0);" ondblclick="ajax_action(\'edt_exp_goal_day\',\'div_exp_day_'.$expertboard_id.'\',\'stype=add&expertboard_id='.$expertboard_id.'&user_id='.USER_ID.'\')" title="Edit Day" class="cleanLink"> ':'').' <span class="style3">'.$goal_days.' '.$DayType.'s</span></a>
				</div>';
				if($is_board_price==1){
				$expertCnt.='<div class="banner_day_4" id="div_exp_price_'.$expertboard_id.'">Price : '.($expOwner_id==USER_ID?'<a href="javascript:void(0);" ondblclick="ajax_action(\'edt_exp_price\',\'div_exp_price_'.$expertboard_id.'\',\'stype=add&fieldname=price&expertboard_id='.$expertboard_id.'&user_id='.USER_ID.'\')" title="Edit Price" class="cleanLink">':'').'<span class="style4">$'.$price.'</span></a></div>';
				}
				
				if($created_cherryboard_id>0&&$expOwner_id!=USER_ID){
				  $expertCnt.='<div class="banner_day_5_left">
          		  <div class="banner_day_5_bg"><a href="expert_cherryboard.php?cbid='.$created_cherryboard_id.'" title="View Goal">View</a></div>
          		  <img src="images/ban.png" alt="" />
         		  </div>
				  <img src="images/im.png" /><br/>';	
				  $expertCnt.='<div class="banner_day_5_left">
					<div class="banner_day_5_bg"><a href="expertboard.php?cbid='.$cherryboard_id.'&type=copy" title="Copy">Copy</a></div>
					<img src="images/ban.png" alt="" />
					</div>
					<img src="images/im.png" /><br/>';			
				}else{
					if($expOwner_id!=USER_ID){
					$expertCnt.='<div class="banner_day_5_left">
					<div class="banner_day_5_bg"><a href="expertboard.php?cbid='.$cherryboard_id.'&type=doit" title="Do it!">Do it!</a></div>
					<div class="banner_day_5_im"><img src="images/ban.png" alt="" /></div>
					</div>
					<div class="banner_day_5_right"><img src="images/im.png" /></div>';
					}
					$expertCnt.='<div class="banner_day_5_left">
					<div class="banner_day_5_bg"><a href="expertboard.php?cbid='.$cherryboard_id.'&type=copy" title="Copy">Copy</a></div>
					<img src="images/ban.png" alt="" />
					</div>
					<img src="images/im.png" /><br/>';
				}
				//CREATE SUB STORY CODE
				if($expCreator_id==USER_ID){
					$expertCnt.='<div class="banner_day_5_left" style="width:250px;">
					<div class="banner_day_5_bg">
					<a rel="leanModal" href="#create_expert_board" onclick="javascript:document.getElementById(\'create_from\').value=\'sub_story\';" title="Sub Story">Sub Story</a>
					</div>
					<img src="images/ban.png" alt="" />
					</div>
					<img src="images/im.png" />';
				}
				//CREATE SUB STORY LIST
				if($parent_id==0&&$expertboard_id>0&&$expOwner_id==USER_ID){
				   $selChild=mysql_query("SELECT expertboard_id,expertboard_title FROM tbl_app_expertboard WHERE parent_id=".$expertboard_id);
				   if(mysql_num_rows($selChild)>0){
					while($selChildRow=mysql_fetch_array($selChild)){
						$childId=(int)$selChildRow['expertboard_id'];
					 	$childBoardTitle=ucwords(trim($selChildRow['expertboard_title']));
						$childBoardId=(int)getFieldValue('cherryboard_id','tbl_app_expert_cherryboard','expertboard_id="'.$childId.'" and main_board="1"');
				  $expertCnt.='<br/><a href="expert_cherryboard.php?cbid='.$childBoardId.'" style="text-decoration:none;color:#000000;font-size:16px;">'.$childBoardTitle.'</a>';
					}
				   }			  
				}
         		//User like and post section
				$expertCnt.='<table><tr><td><font size="+1">&nbsp;</font><br/>
									<a rel="leanModal" href="#sendThankYou" title="Send Thank You" class="msg"
									 style="text-decoration:none;color:#000000">Email</a><br/>
									<!-- <div id="div_fb_postbtn">
									<strong>'.$countShare.'</strong><br/>
									 <img style="cursor:pointer" src="images/fb_share_btn.png" height="27px"  width="101px" onclick="postToFeedExp(); return false;"/>
									</div> -->
									</td><td>
									<!-- FB Code -->
<a name="fb_share" type="box_count" expr:share_url="data:post.canonicalUrl" href="http://www.facebook.com/sharer.php">Share</a><script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" type="text/javascript"></script><br/><br/>
</td><td>
									<!-- Twitter Code -->
									<a href="https://twitter.com/share" class="twitter-share-button" data-url="http://30daysnew.com/expert_cherryboard.php?cbid='.$cherryboard_id.'" data-via="'.$expertboard_title.'" data-lang="en" data-related="anywhereTheJavascriptAPI" data-count="vertical">Tweet</a>
									<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
									</td></tr></table>';
				//COPY A BOARD USER LIST 				
				$selCopyUser=mysql_query("SELECT cherryboard_id,user_id FROM tbl_app_expert_cherryboard WHERE copyboard_id=".$cherryboard_id." AND user_id!=0");
				if(mysql_num_rows($selCopyUser)>0){
				   $expertCnt.='<br/><strong>Copy User List :</strong><br/>';	
				   while($selCopyUserRow=mysql_fetch_array($selCopyUser)){
						$CherryBoardId=(int)$selCopyUserRow['cherryboard_id'];
						$UserId=(int)$selCopyUserRow['user_id'];
						$UserDetail=getUserDetail($UserId);
						$CopyUsrName=$UserDetail['name'];
						$expertCnt.=$CopyUsrName.'<br/>';
				   }
				}
							
				$expertCnt.='</div>';
				
       			$expertCnt.='<div class="classmates" style="float: left;">
				'.$FriendsCnt.'
       			<div class="classmates_text" '.($expOwner_id!=USER_ID?'style="float:none;"':'').' id="div_exp_customer_'.$expertboard_id.'">
				<a href="expert_customer.php?cbid='.$cherryboard_id.'"	
				style="text-decoration:none;color:#000000;" />'.ucwords($customers).'</a>
				'.($expOwner_id==USER_ID?'<img src="images/edit.png" height="10px" 
				style="cursor:pointer" ondblclick="ajax_action(\'edit_exp_customer\',\'div_exp_customer_'
				.$expertboard_id.'\',\'stype=eadd&fieldname=customers&expertboard_id='.$expertboard_id.					'&user_id='.USER_ID.'&cbid='.$cherryboard_id.'\')" width="10px" title="Edit '.ucwords($customers).'" />':'').'
				</div>';
				//START DELETE EXPERTBOARD CODE
				if($expertboard_id>0&&$expOwner_id==USER_ID){
					$totalExpert=(int)getFieldValue('count(cherryboard_id)','tbl_app_expert_cherryboard','expertboard_id='.$expertboard_id.' and main_board="0"');
					$expertCnt.='<a onclick="return delExpert('.$totalExpert.')" href="expert_cherryboard.php?delExpId='.$expertboard_id.'"><img title="Delete Story Board" src="images/new_close.png" height="10px" width="10px" style="padding-left:5px;"></a>';
				    $expertCnt.='<a href="expert_graph.php?ebid='.$cherryboard_id.'" name="test" title="Analytics"><img title="Analytics" src="images/analytic.png" height="10px" width="10px" style="padding-left:5px;"></a>';	
				}
				//START CLASSMATES SECTION
				$expertCnt.='<div id="div_more_classmates">';
				$selCustomer="SELECT DISTINCT user_id FROM tbl_app_expert_cherryboard WHERE expertboard_id=".$expertboard_id." LIMIT 6";		
				$selExpCustomer=mysql_query($selCustomer);
				$TotalRows=mysql_num_rows($selExpCustomer);
				if($TotalRows>0){
					$pageUserPhotosArray=array();
					while($fetchExpCustomer=mysql_fetch_array($selExpCustomer)){
						$customerUserId=(int)$fetchExpCustomer['user_id'];
						$expBoardId=(int)getFieldValue('cherryboard_id','tbl_app_expert_cherryboard','expertboard_id='.$expertboard_id.' and user_id='.$customerUserId);
						$customerDetail=getFieldsValueArray('first_name,last_name,fb_photo_url','tbl_app_users','user_id='.$customerUserId);
						$customer_name=$customerDetail[0].''.$customerDetail[1];
						$customer_photo=$customerDetail[2];
						$expertCnt.=''.($customerUserId>0?'<div class="classmates_img"><a href="expert_cherryboard.php?cbid='.$expBoardId.'"><img src="'.$customer_photo.'" title='.$customer_name.' style="margin-bottom:0px;width:50px;height:50px;" data-tooltip="stickyCustomer'.$customerUserId.'"/></a></div>':'').'';
						$pageUserPhotosArray[$customerUserId]=$customer_photo;						
					}							
				}else{
					$expertCnt.='<strong>No Customers</strong>';
				}
				$TotalRows+=1;
				if($TotalRows>6){
        		  $expertCnt.='<div class="classmates_more"><em><a href="javascript:void(0);" onclick="ajax_action(\'more_classmates\',\'div_more_classmates\',\'expertboard_id='.$expertboard_id.					'\')">More....</a></em></div>';
				}
       			$expertCnt.='</div></div>
        		<div style="clear:both"></div>
     			</div>
   				</div>';
?>
<script>
function postToFeedExp() {			
	// calling the API ...
	var objExp = {
	  method: 'feed',
	  redirect_uri: 'http://30daysnew.com/expert_cherryboard.php?cbid=<?=$cherryboard_id?>',
	  link: 'http://30daysnew.com/expert_cherryboard.php?cbid=<?=$cherryboard_id?>',
	  picture: 'http://30daysnew.com/images/expert.jpg',
	  name: '<?=ucwords($expertboard_title)?>',
	  caption: '<?=ucwords($userName)?>',
	  description: '<?=$expert_detail?>'
	};
	function callbackExp(response) {
	  var post_id=response['post_id'];
	  ajax_action('fb_link_post_exp','div_fb_postbtn','cherryboard_id=<?=$cherryboard_id?>&post_id='+post_id);
	}
	FB.ui(objExp, callbackExp);
  }
</script>
<?php
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

<!-- START BOTTOM TODO LIST AND PICTURE SECTION -->
<div class="bottom_top"></div>
  <div class="bottom_bg">
	<div class="bottom_main" style="width:965px;">   
	  <!-- START REWARD SECTION --> 
	  <div class="bottom_right_container" style="margin:0;">
       <div class="bottom_day_box">rewards
	    <div style="float:right" id="asc_desc_arrow"><table><tr><td><a title="Sort" name="Sort" href="javascript:void(0);" onclick="javascript:ajax_action('exp_photo_refresh','right_container','cherryboard_id=<?=$cherryboard_id?>&sort=<?=($sort=="asc"?'desc':'asc')?>')"><?=($sort=="asc"?'<img id="des" src="images/des.jpg" height="35" width="35"/>':'<img id="asc" src="images/asc.jpg" height="35" width="35"/>')?></a></td><td><img id="rotate_asc" src="images/transparent.png" height="25" width="25"/></td></tr></table></div>
	   </div>
       <div class="bottom_day_bg">
	   <?php
	   $expRewardCnt='';
	   if($expOwner_id==USER_ID){
	   	 $expRewardCnt='<a href="javascript:void(0);" onclick="javascript:document.getElementById(\'subtype\').value=\'add_expert_reward_pic\';document.getElementById(\'photo_upload\').style.display=\'inline\';" style="text-decoration:none; font-weight:bold; color:#000000;padding-left:225px;" title="Add Reward"><font size="2+">(+)</font></a>';	
	   }
	   $expRewardCnt.='<div id="div_del_exp_reward">';
	   //Call Expert Reward Function
	   $expRewardCnt.=getExpertReward($cherryboard_id);
	   $expRewardCnt.='</div>';
	   echo $expRewardCnt;
	  ?>	  
      <!--<div class="applemenu">
			<div class="silverheader">-->
			<div class="todo" style="width:232px;">
				<div class="todolist_left_1"><a href="#">to do list</a></div>
				<div class="img_comments"><img src="images/box2.png" alt="" /></div>
				<div style="clear:both"></div> 
			</div>
			<!--</div>
			<div class="submenu">-->
			<div class="todolist_bt"><img src="images/banet_4.png" alt="" /></div>
			<div style="clear:both"></div>
			<!-- START ADD TO-DO LIST SECTION -->
		    <?php if($expOwner_id==USER_ID){ ?>
		    <input name="txt_todolist" id="txt_todolist" type="text" onfocus="if(this.value=='add something to To-Do List') this.value='';" onblur="if(this.value=='') this.value='add something to To-Do List';" value="add something to To-Do List" style="padding:8px;margin-bottom:10px;margin-left:8px;width:211px;">
			<div class="banner_day_5_left" style="margin-top:11px;width:150px;margin-left:10px;">
          	  <div class="banner_day_5_bg">
				 <a href="javascript:void(0);" onclick="ajax_action('add_expert_checklist','div_todo_list','cherryboard_id=<?=$cherryboard_id;?>&txt_checklist='+document.getElementById('txt_todolist').value+'&user_id=<?=USER_ID?>');" title="Post">Post</a>
			  </div>
			<img src="images/ban.png" alt="" />
			</div>
			<img src="images/im.png" />
		    <br/>
			<!-- END ADD TO-DO LIST SECTION -->
		    <?php } ?>
			<div id="div_todo_list">
			<?php
			//TO-DO LIST BLOCK
			$checkCnt='';
			//CALL FUNCTION GET TODOLIST ITEMS
			$checkCnt.=getToDoListItem($cherryboard_id);
			echo $checkCnt;
			?>
			</div>
		   <div style="clear:both"></div>
		   <!--</div>
			<div class="silverheader">
				<div class="todo" style="width:232px;">
				to do list 2
				</div>
			<div style="clear:both"></div>
			</div>
			<div class="submenu">
			Some random content here<br />
			</div>
	   </div>-->
       </div>
       <div style="clear:both"></div>
   </div>	   
  <!-- START DAY PICTURE SECTION -->
  <div id="right_container" class="bottom_left_container" style="width:718px;float:none;">  
  <?php
  //DAYS TITLE
  $selDays=mysql_query("select * from tbl_app_expertboard_days where expertboard_id=".$expertboard_id." order by day_no");
  $DaysTitleArr=array();
  if(mysql_num_rows($selDays)>0){
	  while($selDaysRow=mysql_fetch_array($selDays)){
	  	$DaysTitleArr[$selDaysRow['day_no'].'_'.$selDaysRow['sub_day']]=$selDaysRow['day_title'];
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
  $pagePhotosArray=array();
  for($i=1;$i<=$GoalDays;$i++){  
	   $swap_id=0;
	   if(in_array($i,$photoDayArr)){
			$selphoto=mysql_query("select * from tbl_app_expert_cherry_photo where cherryboard_id=".$cherryboard_id." and photo_day='".$i."' order by photo_id");
			$sub_day=1;
			$sub_photoCntArray=array();
			$page_photoArray=array();
			$totalPhoto=mysql_num_rows($selphoto);
			while($selphotoRow=mysql_fetch_array($selphoto)){
				$photo_id=$selphotoRow['photo_id'];
				$user_id=$selphotoRow['user_id'];
				$swap_id=$photo_id;
				$photo_title=ucwords(stripslashes($selphotoRow['photo_title']));
				$photo_name=$selphotoRow['photo_name'];
				$record_date=$selphotoRow['new_record_date'];
				$photoPath='images/expertboard/'.$photo_name;
				$photoProfileSlide='images/expertboard/profile_slide/'.$photo_name;
				$photo_day=(int)$selphotoRow['photo_day'];
				if($photo_title==""){
					$photo_title='<div style="width:180px;height:18px">&nbsp;</div>';
				}
				if(is_file($photoPath)){
				   $photoCnt='';
				   if($totalPhoto>1){
					 $printDay=$photo_day.'_'.$sub_day;
				   }else{ $printDay=$photo_day; }
				$TotalCheers=getFieldValue('count(cheers_id)','tbl_app_expert_cherryboard_cheers','photo_id='.$photo_id);
				   $photoCnt.='<div class="bottom_box_main">';				   
				   if($i==3){
				   	  $photoCnt.='<div class="bottom_daya">'.$DayType.' '.str_replace('_','.',$printDay).' '.($user_id==USER_ID?'<img src="images/upload.png" onclick="javascript:document.getElementById(\'photo_day\').value='.$i.';document.getElementById(\'photo_upload\').style.display=\'inline\';" height="15" width="15" style="vertical-align:middle;cursor:pointer;" title="Add Your Picture" />':'').'</div>
					  <img src="images/banet_2.png" alt="" />';
					  $varClass='day_got_1';
					  $varClass1='bottom_healthy_box_1';
				   }else{
				   	  $photoCnt.='<div class="bottom_day_box">'.$DayType.' '.str_replace('_','.',$printDay).' '.($user_id==USER_ID?'<img src="images/upload.png" onclick="javascript:document.getElementById(\'photo_day\').value='.$i.';document.getElementById(\'photo_upload\').style.display=\'inline\';" height="15" width="15" style="vertical-align:middle;cursor:pointer;" title="Add Your Picture" />':'').'</div>';
					  $varClass='bottom_box_text';
					  $varClass1='bottom_healthy_box';
				   }
				   $photoCnt.='<div class="bottom_box_bg">
				   <div class="'.$varClass.'" id="photo_title'.$photo_id.'">'.($user_id==USER_ID?'<a href="javascript:void(0);" ondblclick="ajax_action(\'upd_photo_title\',\'photo_title'.$photo_id.'\',\'stype=eadd&photo_id='.$photo_id.'&user_id='.USER_ID.'\')" title="Edit Comment" class="cleanLink">':'').''.$photo_title.'</a></div>
				   </div>';
				   $photoCnt.='<div class="bottom_healthy">
						   <div class="bottom_healthy_im"><img src="images/box.png" alt="" /></div>
						   <div class="bottom_healthy_12" id="div_expert_cheers_'.$photo_id.'">
						   '.$TotalCheers.' cheers!</div>
						   <div class="'.$varClass1.'" id="div_photo_day'.$photo_day.'_'.$sub_day.'">
						   '.($expOwner_id==USER_ID?'<a href="javascript:void(0);"  ondblclick="ajax_action(\'edt_exp_photo_day\',\'div_photo_day'.$photo_day.'_'.$sub_day.'\',\'stype=add&photo_day='.$photo_day.'&sub_day='.$sub_day.'&expertboard_id='.$expertboard_id.'&user_id='.USER_ID.'\')" title="Edit Day Title">':'').' &nbsp;'.$DaysTitleArr[$photo_day.'_'.$sub_day].'&nbsp;</a>            	   </div>
					  <div style="clear:both"></div>
					  </div>';
				   $photoCnt.='<div class="img_box_container" align="center" id="div'.$i.'_'.$swap_id.'" '.($user_id==USER_ID?'ondrop="drop(event,\''.$i.'_'.$swap_id.'\')" ondragover="allowDrop(event,\''.$i.'_'.$swap_id.'\')"':'').'>
				   <div class="feedbox">';
				   if($user_id==USER_ID){
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
				   if($expUser_id==USER_ID){
					   $photoCnt.='<div id="div_expert_notes_'.$photo_id.'">';
					   $photoCnt.=expert_notes_section($cherryboard_id,$photo_id,$photo_day);
					   $photoCnt.='</div>';
				   }   						   
		 
				   $photoCnt.='</div>
				   <div style="clear:both"></div>
				   </div>';
				   				   
						$sub_photoCntArray[$photo_id]=$photoCnt;
						$pagePhotosArray[$photo_id]=$photoProfileSlide;
						$sub_day++;	
					}
				}
				$photoCntArray[$i]=$sub_photoCntArray;
		}else{
			 $photoCnt='';
			 $sub_photoCntArray=array();
			 $photoPath='images/cherryboard/no_image.png'; 
			 $photoCnt.='<div class="bottom_box_main">
			 			 <div class="bottom_day_box">'.$DayType.' '.$i.''.($user_id==USER_ID?'&nbsp;<img src="images/upload.png" onclick="javascript:document.getElementById(\'photo_day\').value='.$i.';document.getElementById(\'photo_upload\').style.display=\'inline\';" height="15" width="15" style="vertical-align:middle;cursor:pointer;" title="Add Your Picture" />':'').'</div>
						 <div class="bottom_box_bg">
						 	<div class="bottom_box_text" id="photo_title'.$i.'">No Photo</div>
						 </div>
						 <div class="bottom_healthy">
							 <div class="bottom_healthy_im"><img src="images/box.png" alt="" /></div>
							 <div class="bottom_healthy_box"><a href="#">'.$DaysTitleArr[$i.'_1'].'</a></div>
							 <div style="clear:both"></div>
         				 </div>
						 <div class="day_img" style="padding:12px;">
						 <div id="div'.$i.'_'.$swap_id.'" style="background-image:url('.$photoPath.');cursor:pointer;height:192px;width:192px;" '.($expUser_id==USER_ID?'ondrop="drop(event,\''.$i.'_'.$swap_id.'\')" ondragover="allowDrop(event,\''.$i.'_'.$swap_id.'\')" onclick="javascript:document.getElementById(\'photo_day\').value='.$i.';document.getElementById(\'photo_upload\').style.display=\'inline\';"':'').' src="'.$photoPath.'">
						 </div>
						 </div>';
			 $photoCnt.='</div>';
			 		
			 $sub_photoCntArray[1]=$photoCnt;
			 $photoCntArray[$i]=$sub_photoCntArray;
		  }
	}
	$NewphotoCnt='';
	$NewphotoCnt='<table border="0"><tr>';
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
	<tr><td colspan="3" style="height:50px;padding-left:450px;">'.($expOwner_id==USER_ID?'<a href="javascript:void(0);" onclick="ajax_action(\'increase_expdays_items\',\'div_exp_day_'.$expertboard_id.'\',\'cherryboard_id='.$cherryboard_id.'&user_id='.USER_ID.'\')" title="Add '.$DayType.'" class="gray_link_15">+</a>':'&nbsp;').'</td></tr>';
	
	$NewphotoCnt.='</table>';	
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

function dragTodoList(ev,id)
{	
	ev.dataTransfer.setData("Text",ev.target.id);
	document.getElementById('imgswap_from').value=id;
}

function dropTodoList(ev,id)
{	
	document.getElementById('imgswap_to').value=id;
	
	var img_from=document.getElementById('imgswap_from').value;
	var img_to=document.getElementById('imgswap_to').value;
	
	ev.preventDefault();
	var data=ev.dataTransfer.getData("Text");
	ev.target.appendChild(document.getElementById(data));
	ajax_action('swap_todolist','div_checklist','imgswap_from='+img_from+'&imgswap_to='+img_to);
}
</script>
	   <div class="clear"></div>
</div>
<div id="mystickytooltip" class="stickytooltip">
<?php
$pagePhotoEffect='';
//Cherryboard photos
foreach($pagePhotosArray as $photoId=>$photoUrl){
	if(!is_file($photoUrl)){
		$photoUrl=str_replace('profile_slide/','',$photoUrl);
		$imgInfo=getimagesize($photoUrl);
		$imgWidth=(int)($imgInfo[0]*1.5);
		$imgHeight=(int)($imgInfo[1]*1.5);
	}else{
		$imgInfo=getimagesize($photoUrl);
		$imgWidth=(int)($imgInfo[0]);
		$imgHeight=(int)($imgInfo[1]);
	}
	
	
	$pagePhotoEffect.='<div id="stickyCherry'.$photoId.'" class="atip">
		<img src="'.$photoUrl.'" width="'.$imgWidth.'px" height="'.$imgHeight.'px" />
		</div>';
}
//Rewards photos
foreach($pageRewardPhotosArray as $photoId=>$photoUrl){
	$imgInfo=getimagesize($photoUrl);
	$imgWidth=(int)($imgInfo[0]*2);
	$imgHeight=(int)($imgInfo[1]*2);
	$pagePhotoEffect.='<div id="stickyReward'.$photoId.'" class="atip">
		<img src="'.$photoUrl.'" width="'.$imgWidth.'px" height="'.$imgHeight.'px" />
		</div>';
}
//Customers photos
foreach($pageUserPhotosArray as $photoId=>$photoUrl){
	$pagePhotoEffect.='<div id="stickyCustomer'.$photoId.'" class="atip">
		<img src="'.$photoUrl.'" width="100px" height="100px" />
		</div>';
}
//Friends photos
foreach($pageFriendsPhotoArray as $photoId=>$photoUrl){
	$pagePhotoEffect.='<div id="stickyFriends'.$photoId.'" class="atip">
		<img src="'.$photoUrl.'" width="100px" height="100px" />
		</div>';
}
echo $pagePhotoEffect;
?>
</div>
<!-- START ADD REWARD PHOTO -->
		<div style="display: none; position: fixed; opacity: 1; z-index: 11000; left: 50%; margin-left: -165px; top: 150px;width:450px;" id="photo_upload" align="center" class="popup_div">
                <a class="modal_close" href="javascript:void(0);" title="close" onclick="javascript:document.getElementById('photo_upload').style.display='none';"></a>
                <span class="head_20">Upload Photo</span><br>
				<div id="setup_add_category" class="msg_red">&nbsp;</div>
				<form action="" method="post" name="frmphoto<?=$cherryboard_id?>" enctype="multipart/form-data">
				<input type="hidden" name="photo_day" id="photo_day" value="1" />
				<input type="hidden" name="exp_reward_id" id="exp_reward_id" value="1" />
				<input type="hidden" name="story_photo_id" id="story_photo_id" value="1" />
				<input type="hidden" name="subtype" id="subtype" value="exp" />
				<input type="hidden" name="user_id" id="user_id" value="<?=USER_ID?>" />
				<div id="div_up_photo"></div>
				<div id="me" class="red_link_14">+ Add a Photo (3MB)</div>				
				</form>
	 </div>
<!-- END ADD REWARD PHOTO--- -->
<!-- START SEND THANK YOU CODE -->
<form action="" method="post" name="frmsndthank" enctype="multipart/form-data">
<div style="display: none; position: fixed; opacity: 1; z-index: 11000; left: 50%; margin-left: -330px; top: 100px; width:500px;border:5px solid #000000;" id="sendThankYou" class="popup_div">
		<a class="modal_close" href="#" title="close"></a>	
		<div class="msg_red" id="div_frm_sndmsg"></div>
		<div id="div_send_thankYou">
			<?php
			$ExpBoardDetail=getExpGoalDetail($cherryboard_id);
			$goal_title=ucwords($ExpBoardDetail['expertboard_title']);
			$MailMsg='I created "'.$goal_title.'" expert board, to help my friends with my experience.';
			$subject=$goal_title.' expert board';
			?>	
			<div align="center" class="email_header">Send Email</div><br>
			<span style="padding-left:20px;"><strong>Email</strong>:
			<input type="text" style="width:380px;margin-left:25px;" name="email_id" id="email_id" onblur="if(this.value=='') this.value='Enter Email';" onfocus="if(this.value=='Enter Email') this.value='';" value="Enter Email" /></span><br><br>
			<span style="padding-left:20px;"><strong>Subject</strong>:
			<input type="text" style="width:380px;margin-left:10px;" name="subject" id="subject" value="<?=$subject?>" /></span><br><br>
			<table><tr>
			<td valign="top" style="padding-left:15px;"><strong>Message</strong>:</td>
			<td>
			<textarea style="width:380px;" rows="8" name="message" id="message"><?=$MailMsg?></textarea>
			</td></tr></table>
			<br>
			<input type="button" style="margin-left:210px;" class="btn_small" id="btnsend" onClick="ajax_action('sendThankYou_Expert','div_send_thankYou','cherryboard_id=<?=$cherryboard_id;?>&email_id='+document.getElementById('email_id').value+'&subject='+document.getElementById('subject').value+'&message='+document.getElementById('message').value+'&user_id=<?=USER_ID?>');" value="Send" name="btnsend" />
		</div>
</div>
</form>
<!-- END SEND THANK YOU CODE --> 
<!-- Start Cimemagraph Slider -->
<?php
	$photoString='';
	$photoHeight='';
	$photoWidth='';
	foreach($MainSlidePhotoArr as $key=>$photoPath){
		$imgInfo=getImgSizeRatio($photoPath,890,490);
		$origWidth  = $imgInfo['width'];
		$origHeight = $imgInfo['height'];
		
		if($photoString!=""){$photoString.=',';$photoWidth.=',';$photoHeight.=',';}
		$photoString.='"'.$photoPath.'"';
		$newWidth=((int)$origWidth*3);
		$newHeight=((int)$origHeight*3);
		//$photoWidth.='"'.$origWidth.'"';
		//$photoHeight.='"'.$origHeight.'"';
		if($newWidth>900){
			$photoWidth.='"900"';
		}else{
			$photoWidth.='"'.$newWidth.'"';
		}
		if($newHeight>500){
			$photoHeight.='"500"';
		}else{
			$photoHeight.='"'.$newHeight.'"';
		}

	}
	?>
<div style="display: none; position: fixed; opacity: 1; z-index: 11000; left: 40%; margin-left: -330px; top: 30px; width:900px;height:500px;border:5px solid #000000;" id="cimemagraph" class="popup_div">
	<table>
	<tr>
	<td>
	<div id="home-photo"><img src="<?=$MainSlidePhotoArr[0]?>" id="photo" width="900" height="500" alt=""></div>
	<script language="JavaScript">
		var ImageArr1 = new Array(<?=$photoString?>);
		var ImageWidth1 = new Array(<?=$photoWidth?>);
		var ImageHeight1 = new Array(<?=$photoHeight?>);
		var ImageHolder1 = document.getElementById('photo');
		
		function RotateImages(whichHolder,Start)
		{
			var a = eval("ImageArr"+whichHolder);
			var b = eval("ImageHolder"+whichHolder);
			
			var w = eval("ImageWidth"+whichHolder);
			var h = eval("ImageHeight"+whichHolder);
			
			if(Start>=a.length){
			Start=0;
			}
			b.src = a[Start];
			b.width = w[Start];
			b.height = h[Start];
			window.setTimeout("RotateImages("+whichHolder+","+(Start+1)+")",1500);
		}
		
		RotateImages(1,0);
	</script>
	</td>
	<td align="center" valign="top">
	<a href="javascript:void(0);" title="close" onclick="javascript:document.getElementById('cimemagraph').style.display='none';"><img src="images/close.png" /></a>
	</td>
	
	</tr>
	</table>
</div>
<!-- End Cimemagraph Slider -->
<script type="text/javascript" src="board_slider/wowslider.js"></script>
<script type="text/javascript" src="board_slider/script.js"></script>
<?php include('fb_expert_invite.php');?>
<?php include('site_footer.php');?>