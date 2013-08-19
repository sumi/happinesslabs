<?php
include_once "fbmain.php";
include('include/app-common-config.php');
$msg='';

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

//DELETE EXPERT BOARD
$debid=$_GET['debid'];
if($debid>0){
	 $checkUser=getFieldValue('user_id','tbl_app_expert_cherryboard','cherryboard_id='.$debid); 
	 if($checkUser==USER_ID){
		 $upd_request=mysql_query('delete from tbl_app_expert_cherryboard where cherryboard_id="'.$debid.'"');
		 if($upd_request){
			$checklist=mysql_query('delete from tbl_app_expert_checklist where cherryboard_id="'.$debid.'"');
			$cherryboard_cheers=mysql_query('delete from tbl_app_expert_cherryboard_cheers where cherryboard_id="'.$debid.'"');
			$cherryboard_meb=mysql_query('delete from tbl_app_expert_cherryboard_meb where cherryboard_id="'.$debid.'"');
			$cherry_comment=mysql_query('delete from tbl_app_expert_cherry_comment where cherryboard_id="'.$debid.'"');
			$cherry_gift=mysql_query('delete from tbl_app_expert_cherry_gift where cherryboard_id="'.$debid.'"');
			$cherry_photo=mysql_query('delete from tbl_app_expert_cherry_photo where cherryboard_id="'.$debid.'"');
			$temp_cherryboard_meb=mysql_query('delete from tbl_app_temp_expert_cherryboard_meb where cherryboard_id="'.$debid.'"');
		 }
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
if(USER_ID>0){
 $system_page=(int)getFieldValue('system_page','tbl_app_users','user_id="'.USER_ID.'"');
 if($system_page==0){
	echo '<script>document.location.href = "setup2.php";</script>';
 }
}

if($_GET['msg']=="addche"){
	$msg="Cherryboard added successfully.";
}
//check for the system page

?>	
<!--Body Start-->
<div id="body_container" style="padding-top:100px;">
	<div class="wrapper">
      <div id="checklist">
      		<div class="font14px">
        	 <div class="feed_comment"><strong>Bought Boards</strong></div>   
				<?php
				$sel_buy=mysql_query('select a.cherryboard_id,b.cherryboard_title from tbl_app_expert_buy a,tbl_app_expert_cherryboard b where a.cherryboard_id=b.cherryboard_id and a.user_id='.USER_ID);
				if(mysql_num_rows($sel_buy)>0){
					$cnt=1;
					while($row_buy=mysql_fetch_array($sel_buy)){
						echo $cnt.'. <a href="expert_cherryboard.php?cbid='.$row_buy['cherryboard_id'].'" class="gray_link">'.ucwords($row_buy['cherryboard_title']).'</a><br/>';
						$cnt++;
					}
				}
				?>			 
			</div>
      <?php
		//followers
		$selExpert=mysql_query("select cherryboard_id from tbl_app_expert_cherryboard where user_id=".$user_id);
		$Followers_user_id=array();
		while($rowExpert=mysql_fetch_array($selExpert)){
			$cherryboard_id=$rowExpert['cherryboard_id'];
			$selExpert1=mysql_query("select req_user_fb_id from tbl_app_expert_cherryboard_meb where cherryboard_id=".$cherryboard_id);
			while($rowExpert1=mysql_fetch_array($selExpert1)){
				$Followers_fb_id[]=$rowExpert1['req_user_fb_id'];
			}	
		}
		$FollowersCnt='';
		if(count($Followers_fb_id)>0){
			$FollowersCnt.='';
			foreach($Followers_fb_id as $fbid){
				$UserDetail=getUserDetail($fbid,'fbid');
				$FollowersCnt.=$UserDetail['fb_photo_url'];
			}	
		}
		//following
		$selFollowing=mysql_query("select user_id from tbl_app_expert_cherryboard_meb where req_user_fb_id=".FB_ID);
		$Following_user_id=array();
		while($rowselFollowing=mysql_fetch_array($selFollowing)){
			$Following_user_id[]=$rowselFollowing['user_id'];
		}
		$FollowingCnt='';
		if(count($Following_user_id)>0){
			$FollowingCnt.='<br/><br/><br/><br/><br/><hr/>';
			foreach($Following_user_id as $fuser_id){
				$UserDetail=getUserDetail($fuser_id,'uid');
				$FollowingCnt.=$UserDetail['fb_photo_url'];
			}	

		}
	?>
	<hr/>
	<div class="right"><?=count($Following_user_id)?> following</div> <?=count($Followers_fb_id)?> followers
	  <hr/>
	  <?=$FollowersCnt?>
	  <?=$FollowingCnt?>
    <br><br><br>
	<?=($msg!=""?'<span class="fgreen"><br/>'.$msg.'</span>':'')?>
	<?=($Errmsg!=""?'<span class="fgreen"><br/>'.$Errmsg.'</span>':'')?>
	<?php
	$selFollowingReq=mysql_query("select a.request_ids,a.meb_id,a.cherryboard_id,b.cherryboard_title,
	a.req_user_fb_id,b.user_id from tbl_app_cherryboard_meb a,tbl_app_cherryboard b where a.cherryboard_id=b.cherryboard_id and a.req_user_fb_id='".FB_ID."' and a.is_accept='0' order by a.record_date");
	$cnt=1;
	$FollowedReqCnt='';
	if(mysql_num_rows($selFollowingReq)>0){
		$FollowedReqCnt.='<hr/>
						<strong>Requests</strong>';
		while($rowFollowingReq=mysql_fetch_array($selFollowingReq)){
		$mebNameArr=getFieldsValueArray('first_name,last_name','tbl_app_users','user_id='.$rowFollowingReq['user_id']);
			$mebName=$mebNameArr[0].' '.$mebNameArr[1];
			$FollowedReqCnt.='<br>'.$cnt.'. <a href="cherryboard.php?cbid='.$rowFollowingReq['cherryboard_id'].'" style="text-decoration:none;color:#000000;">'.$rowFollowingReq['cherryboard_title'].'</a>-'.$mebName.'&nbsp;&nbsp;&nbsp;<a href="index_detail.php?arequest_ids='.$rowFollowingReq['request_ids'].'" style="text-decoration:none;color:#006633;" onclick="return confirm(\'Are you sure to accept request?\')">Accept</a>,&nbsp;<a href="index_detail.php?drequest_ids='.$rowFollowingReq['request_ids'].'" style="text-decoration:none;color:#FF0000;" onclick="return confirm(\'Are you sure to decline request?\')">Decline</a>';
			$cnt++;
		}
	}
	echo $FollowedReqCnt;
	///CODE FOR THE FOLLOWERS REQUEST
	$selFollowingReq=mysql_query("select a.request_ids,a.meb_id,a.cherryboard_id,b.cherryboard_title,
	a.req_user_fb_id,b.user_id from tbl_app_expert_cherryboard_meb a,tbl_app_expert_cherryboard b where a.cherryboard_id=b.cherryboard_id and a.req_user_fb_id='".FB_ID."' and a.is_accept='0' order by a.record_date");
	$cnt=1;
	$FollowedReqCnt='';
	if(mysql_num_rows($selFollowingReq)>0){
		$FollowedReqCnt.='<hr/>
						<strong>Followers Requests</strong>';
		while($rowFollowingReq=mysql_fetch_array($selFollowingReq)){
		$mebNameArr=getFieldsValueArray('first_name,last_name','tbl_app_users','user_id='.$rowFollowingReq['user_id']);
			$mebName=$mebNameArr[0].' '.$mebNameArr[1];
			$FollowedReqCnt.='<br>'.$cnt.'. <a href="expert_cherryboard.php?cbid='.$rowFollowingReq['cherryboard_id'].'" style="text-decoration:none;color:#000000;">'.$rowFollowingReq['cherryboard_title'].'</a>-'.$mebName.'&nbsp;&nbsp;&nbsp;<a href="index_detail.php?aexprequest_ids='.$rowFollowingReq['request_ids'].'" style="text-decoration:none;color:#006633;" onclick="return confirm(\'Are you sure to accept request?\')">Accept</a>,&nbsp;<a href="index_detail.php?dexprequest_ids='.$rowFollowingReq['request_ids'].'" style="text-decoration:none;color:#FF0000;" onclick="return confirm(\'Are you sure to decline request?\')">Decline</a>';
			$cnt++;
		}
	}
	echo $FollowedReqCnt;
	?>
	</div>
<div id="right_container" style="width:440px;">
	  <div align="center" class="head_gray head_tittle">Expert</div>  
	  <div align="center" class="head_gray head_tittle">My Goals</div>
	  <table width="100%" border="0" cellpadding="0" cellspacing="0">
	  <tr><td width="50%" valign="top">
	  <?php
	  $selCherry=mysql_query("select * from tbl_app_expert_cherryboard where user_id=".USER_ID);
	  $totalExpGoals=mysql_num_rows($selCherry);
	  if($totalExpGoals>0){
	  	$cnt=1;
	  	while($selCherryRow=mysql_fetch_array($selCherry)){
			$cherryboard_id=$selCherryRow['cherryboard_id'];
			$cherryboard_title=ucwords($selCherryRow['cherryboard_title']);
			$category_id=$selCherryRow['category_id'];
			$category_name=getFieldValue('category_name','tbl_app_category','category_id='.$category_id);
			
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
	  ?>
		<div class="field_container"><img src="images/tag_expert.png" class="tag_expert">
        	<div style="padding-left:62px;"><strong><a href="expert_cherryboard.php?cbid=<?php echo $cherryboard_id;?>" style="text-decoration:none;color:#000000;"><?php echo $cherryboard_title.' - '.$category_name;?></a>&nbsp;<a href="index_detail.php?debid=<?php echo $cherryboard_id;?>" onclick="return confirm('Are you sure to delete this board?')"><img src="images/delete.png"></a></strong></div><br>
            <img src="<?php echo $phtotoArray[0];?>" height="195" width="195"><br><br>
			<?php
			for($i=1;$i<count($phtotoArray);$i++){
			?>
			<img src="<?php echo $phtotoArray[$i];?>" class="img_thumb" style="margin: 0 3px 0 0;">
			<?php } ?>
	   </div>
           <?php 
		    if($totalExpGoals==$cnt){
			 	echo '<div style="padding-bottom:35px;">&nbsp;</div>';	  
			 }
	 		$cnt++;
	 	}
	 }else{
	 	echo '<a href="add_cherryboard_expert.php"><img src="images/empty_expert_board.png" height="195" width="195" /></a>';
	 } ?>   
	  </td><td valign="top">
	  <?php
	  $selCherry=mysql_query("select * from tbl_app_cherryboard where user_id=".USER_ID);
	  $totalGoals=mysql_num_rows($selCherry);
	  if($totalGoals>0){
	  	$cnt=1;
	  	while($selCherryRow=mysql_fetch_array($selCherry)){
			$cherryboard_id=$selCherryRow['cherryboard_id'];
			$cherryboard_title=ucwords($selCherryRow['cherryboard_title']);
			$category_id=$selCherryRow['category_id'];
			$category_name=getFieldValue('category_name','tbl_app_category','category_id='.$category_id);
			
			$selphoto=mysql_query("select * from tbl_app_cherry_photo where cherryboard_id=".$cherryboard_id." order by photo_id desc");
			$phtotoArray=array();
			$element=0;
			if(mysql_num_rows($selphoto)>0){
				while($selphotoRow=mysql_fetch_array($selphoto)){
					$photo_name=$selphotoRow['photo_name'];
					if($element>0){
						$photoPath='images/cherryboard/thumb/'.$photo_name;
					}else{
						$photoPath='images/cherryboard/'.$photo_name;
					}
					
					if(is_file($photoPath)){
						$phtotoArray[]=$photoPath;
						$element++;
					}
				}	
			}else{
				$phtotoArray[]='images/cherryboard/no_image.jpg';
			}		
			  $TodayDay=getGoalboardRemainDays($cherryboard_id);
				if($TodayDay<30){
					$DayCount= '<font style="color:#B90000;font-weight:bold">'.$TodayDay.' more days to win</font>';
				}else{
					$DayCount=  '<font style="color:#B90000;font-weight:bold">'.$TodayDay.' days</font>';
				}
				$cherrySel=mysql_query("select a.cherry_gift_id,a.gift_id,b.gift_photo,b.gift_title from tbl_app_cherry_gift a,tbl_app_gift b where a.gift_id=b.gift_id and a.cherryboard_id=".$cherryboard_id." group by a.gift_id");
				$MonthSpeCnt='';
				if(mysql_num_rows($cherrySel)>0){
					while($cherryRow=mysql_fetch_array($cherrySel)){
						$cherry_gift_id=$cherryRow['cherry_gift_id'];
						$gift_photo=$cherryRow['gift_photo'];
						$gift_title=$cherryRow['gift_title'];
						
						$MonthSpeCnt.='<img src="images/gift/'.$gift_photo.'" style="width:20px;height:20px" class="profile_img_big" title="'.$gift_title.'">';
					}
				}
			  ?>
				<div class="field_container">
					<div align="center"><strong><a href="cherryboard.php?cbid=<?php echo $cherryboard_id;?>" style="text-decoration:none;color:#000000"><?php echo $cherryboard_title.' - '.$category_name;?></a>&nbsp;<a href="index_detail.php?dgbid=<?php echo $cherryboard_id;?>" onclick="return confirm('Are you sure to delete this board?')"><img src="images/delete.png"></a></strong></div><br>
					<img src="<?php echo $phtotoArray[0];?>" height="195" width="195"><br><br>
					<font style="color:#DACB25;font-weight:bold"><?=$MonthSpeCnt?>&nbsp;<?=$DayCount?></font>
					<br><br>
					<?php
					for($i=1;$i<count($phtotoArray);$i++){
					?>
					<img src="<?php echo $phtotoArray[$i];?>" class="img_thumb" style="margin: 0 3px 0 0;">
					<?php } ?>
				   <!--  <div align="center"><a href="#" class="blue_btn_small">Follow</a><img src="images/img_day_thumb2.jpg" width="30" height="30" class="img_small_1"></div><br> -->
			   </div>
			 <?php 
			 if($totalGoals==$cnt){
			 	echo '<div style="padding-bottom:35px;">&nbsp;</div>';	  
			 }
	 		$cnt++;
		}
	 }else{
	 	echo '<a href="setup2.php"><img src="images/empty_story_board.png" height="195" width="195" /></a>';
	 } ?>   
	  </td></tr>
	  </table>
      </div>
	  <div id="inspir_feed" style="margin-top:30px;">
          <?php
		  //FRIEND GOALS LIST
		  $selFollowingGoal=mysql_query("select b.user_id,b.cherryboard_id,b.cherryboard_title from tbl_app_cherryboard_meb a,tbl_app_cherryboard b where a.cherryboard_id=b.cherryboard_id and a.req_user_fb_id=".FB_ID." and a.user_id!=".USER_ID." and a.is_accept='1'");
	$cnt=1;
	$FollowedGoalCnt='';
	if(mysql_num_rows($selFollowingGoal)>0){
		$FollowedGoalCnt.='
			<strong>Friend\'s Goals</strong>';
		while($rowselFollowingGoal=mysql_fetch_array($selFollowingGoal)){
			$UserDetail=getUserDetail($rowselFollowingGoal['user_id'],'uid');
			$user_photo=$UserDetail['fb_photo_url'];
			
			$FollowedGoalCnt.='<br><div style="float:left;">'.$cnt.'.<a href="cherryboard.php?cbid='.$rowselFollowingGoal['cherryboard_id'].'" style="text-decoration:none;color:#000000;">'.$rowselFollowingGoal['cherryboard_title'].'</div> <div style="float:right">'.$user_photo.'</a></div>';
			$cnt++;
		}
		
	}	
	echo $FollowedGoalCnt;
	?>
		  <?php echo UserFeedSection('user',$cherryboard_id,USER_ID);?>
      </div>
	  <div class="clear"></div>        
  </div>
</div>

<!--Gray body End-->
<!--Body End-->
<?php include('site_footer.php');?>