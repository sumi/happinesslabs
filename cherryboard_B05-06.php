<?php
include_once "fbmain.php";
include('include/app-common-config.php');
$cherryboard_id=$_GET['cbid'];
$sort=$_GET['sort'];
$msg='';
//START SHARE PHOTO IN ALBUM
$goal_days=(int)getGoalDays($cherryboard_id);
if($goal_days==0){
	$goal_days=30;
}

if(isset($_SESSION['insert_photo_id'])){
	$AlbumDetail=getFieldsValueArray('fb_album_id,cherryboard_title','tbl_app_cherryboard','cherryboard_id='.$cherryboard_id);
	$AlbumId=trim($AlbumDetail[0]);
	$cherryboard_title=$AlbumDetail[1];
	$PhotoDetail=getFieldsValueArray('photo_name,photo_title','tbl_app_cherry_photo','photo_id='.$_SESSION['insert_photo_id']);
	$photo_name=$PhotoDetail[0];
	$photo_title=$PhotoDetail[1];
	$photoFile="images/cherryboard/temp/".$photo_name;
	$TodayDay=getGoalboardToday($cherryboard_id);
	
	//START FB image generator
	  $image_magick = "convert"; 
	  $font_selection = "bebas.ttf"; 
	
	  $source_image = $photoFile; 
	  $target_image = 'images/cherryboard/fb/'.rand().'_'.$photo_name; 
	  $text = "Day ".$TodayDay; 
	
	  $ImgSize = getimagesize($source_image);
	  $ImgWidth=$ImgSize[0];
	  $ImgHeight=$ImgSize[1];
	  
	  $TopFontSize=round($ImgWidth/8);
	  $TopLeftPed=round($ImgWidth/3);
	  $TopTopPed=round($ImgHeight/5);
	  
	  $command = $image_magick.' -resize '.$ImgWidth.' "'.$source_image.'" '.' -font "'.$font_selection.'" -pointsize '.$TopFontSize.' -fill white -gravity North -annotate +0+5 \''.$text.'\' "'.$target_image.'"';
	  passthru($command);
	
	  $source_image1 = $target_image; 
	  $target_image1 = 'images/cherryboard/fb/'.$photo_name;
	  $text = $photo_title; 
	
	  $BotFontSize=round($ImgWidth/14);
	  $BotLeftPed=round($ImgWidth/6);
	  $BotTopPed=round(($ImgHeight/5)*4);	
	  
	  $command = $image_magick.' -resize '.$ImgWidth.' "'.$source_image1.'" '.' -font "'.$font_selection.'"  -pointsize '.$BotFontSize.' -fill white -gravity South -annotate +0+5 \''.$text.'\'  "'.$target_image1.'"'; 
	
	  passthru($command);
	//END FB image generator
	 if($AlbumId!="0"&&$AlbumId!=""&&is_file($target_image1)){
	 	
		$gift_id=getFieldValue('gift_id','tbl_app_cherry_gift','cherryboard_id='.$cherryboard_id);
		$CompainDetail=getFieldsValueArray('campaign_title,gift_title,goal_days,miss_days','tbl_app_gift','gift_id='.$gift_id);
		$cntFillDay=0;
		$countFillDays="SELECT count(`photo_id`),date_format(`record_date`,'%Y-%m-%d') as postdate FROM `tbl_app_cherry_photo` WHERE `user_id`=".USER_ID." and `cherryboard_id`=".$cherryboard_id." group by postdate";
		$countFillSql=mysql_query($countFillDays);
		while($countFillRow=mysql_fetch_row($countFillSql)){
			$cntFillDay++;
		}
		$CampaignTitle=$CompainDetail[0];
		$GiftTitle=$CompainDetail[1];
		$NumberDays=$CompainDetail[2];
		$NumberStriks=$CompainDetail[3];
		$DaysMissed=(int)($TodayDay-$cntFillDay);
		$DaysRemaining=(int)($NumberDays-$TodayDay);
		
		unset($_SESSION['insert_photo_id']);
		$facebook->setFileUploadSupport(true);  
		# File is relative to the PHP doc  
		$photoMessage=$photo_title.' - 30DaysNew
		Campaign Title:'.$CampaignTitle.'
		Gift Title:'.$GiftTitle.'
		Number Days:'.$NumberDays.'
		Number Striks:'.$NumberStriks.'
		Days Missed:'.$DaysMissed.'
		Days Remaining:'.$DaysRemaining;
		
		$file = "@".realpath($target_image1);  
		$args = array(  
			'message' => $photoMessage, 
			"access_token" => $_SESSION['fb_access_token'],  
			"image" => $file  
		);  
		$data = $facebook->api('/'.$AlbumId.'/photos', 'post', $args);
		
		//echo "Photo Id :".$upload_photo['id']."==".$_SESSION['fb_access_token']; // The id of your newly uploaded pic.
		$upload_photoID=$data['id'];
		
		unlink($target_image); //delete tem target photo
	}

}
//END SHARE PHOTO IN ALBUM
include('site_header.php');
?>
<?php
//Cheryboard Detail
$cherrySel=mysql_query("select category_id,cherryboard_title from tbl_app_cherryboard where cherryboard_id=".$cherryboard_id);
while($cherryRow=mysql_fetch_array($cherrySel)){
	$cherrry_title=ucwords($cherryRow['cherryboard_title']);
	$category_id=ucwords($cherryRow['category_id']);
	$category_name=getFieldValue('category_name','tbl_app_category','category_id='.$category_id);
	$gift_id=getFieldValue('gift_id','tbl_app_cherry_gift','cherryboard_id='.$cherryboard_id);
	$campaign_title=getFieldValue('campaign_title','tbl_app_cherry_gift','gift_id='.$gift_id);
	if(trim($campaign_title)!=""){
		$cherrry_title=$campaign_title;
	}
	
}
?>
<div style="background:#FFFFFF; margin:0px auto;">
<div id="wrapper">
<div class="right">

<?php
	$TodayDay=getGoalboardRemainDays($cherryboard_id);
	$giftMsg='';
	if($TodayDay<$goal_days){
		$giftMsg.=$TodayDay.' more days to win';
	}else{
		$giftMsg.=$TodayDay.' days';
	}	
	
	$cherrySel=mysql_query("select a.cherry_gift_id,a.gift_id,b.gift_photo,b.gift_title,b.goal_days from tbl_app_cherry_gift a,tbl_app_gift b where a.gift_id=b.gift_id and a.cherryboard_id=".$cherryboard_id." group by a.gift_id");
	$MonthSpeCnt='';
	if(mysql_num_rows($cherrySel)>0){
		while($cherryRow=mysql_fetch_array($cherrySel)){
			$cherry_gift_id=$cherryRow['cherry_gift_id'];
			$gift_photo=$cherryRow['gift_photo'];
			$gift_title=$cherryRow['gift_title'];
			$MonthSpeCnt.='<div class="img_big_container">
		    	  <div class="feedbox_holder">
                </div>
              <img src="images/gift/'.$gift_photo.'" class="profile_img_big"></div>';
		}
	}else{
		$MonthSpeCnt.='<strong>No Monthly Specials</strong>';
	}
?>
<div id="my_cherryleaders"><a href="#" class="get_another_gift left">&nbsp;</a>&nbsp;<?=$giftMsg?><br>
    <div id="div_goal_monthly_specials" style="padding-top:5px;">
	<?php echo $MonthSpeCnt;?>	
	<?php echo '<br><br><br><br>'.$gift_title;?>
	</div>	
</div>
</div>
	<div id="left_container">
      <!--my cheeryleader Start-->
        <div id="my_cherryleaders"><a href="#" id="invite_frnd" class="gray_link_15 right">+</a>Encouraging Friends<input type="hidden" name="cherryboard_id" id="cherryboard_id" value="<?php echo $cherryboard_id;?>" /><input type="hidden" name="cherryboard_key" id="cherryboard_key" value="0" /><br>
	 <div id="div_goal_friends">
	 <?php
	//FRIENDS BLOCK
	    $FriendsCnt='';
		$selQuery="select a.meb_id,b.user_id,b.fb_photo_url from tbl_app_cherryboard_meb a,tbl_app_users b where a.req_user_fb_id=b.facebook_id and a.is_accept='1' and b.user_id!=".USER_ID." and a.cherryboard_id=".$cherryboard_id." group by b.user_id";
		$selSqlQ=mysql_query($selQuery);
		$FriendsArray=array();
		if(mysql_num_rows($selSqlQ)>0){
			$cnt=0;
			while($rowTbl=mysql_fetch_array($selSqlQ)){
				
					$FriendsArray[]=$rowTbl['user_id'];
					$meb_id=$rowTbl['meb_id'];
					$user_id=(int)getFieldValue('user_id','tbl_app_cherryboard_meb',
					'cherryboard_id='.$cherryboard_id.' AND is_accept=1');
					if($cnt==5){$FriendsCnt.='<br/>';}
					$FriendsCnt.='<div class="small_thumb_container">
					<div class="img_big_container">
						<div class="feedbox_holder">
							<div class="actions">'.($user_id==USER_ID?'<a class="delete" href="#" onclick="ajax_action(\'delete_goal_friends\',\'div_goal_friends\',\'cherryboard_id='.$cherryboard_id.'&meb_id='.$meb_id.'\')" ><img src="images/delete.png"></a>':'').'</div>
						</div>
						<img src="'.$rowTbl['fb_photo_url'].'" class="thumb">
					</div>
					</div>';
					$cnt++;
					
			}
			
		}else{
			$FriendsCnt.='<strong>No Friends</strong>';
		}
		echo $FriendsCnt;
	?>
	</div>
	<div id="div_goal_recent_friends">
	<?php
	
		$FriendsCnt='';
		$selQuery="select meb_id,user_id,req_user_fb_id from tbl_app_cherryboard_meb where is_accept='0' and cherryboard_id=".$cherryboard_id." order by meb_id desc";
		$selSqlQ=mysql_query($selQuery);
		if(mysql_num_rows($selSqlQ)>0){
			$FriendsCnt.='<p>Friends Request</p>';
			$cnt=0;
			while($rowTbl=mysql_fetch_array($selSqlQ)){
				if($cnt==5){$FriendsCnt.='<br/>';}
				$meb_id=$rowTbl['meb_id'];
				$user_id=$rowTbl['user_id'];
				$fb_photo_url=getFriendPhoto($rowTbl['req_user_fb_id']);
				$FriendsCnt.='<div class="small_thumb_container">
				<div class="img_big_container">
					<div class="feedbox_holder">
						<div class="actions">'.($user_id==USER_ID?'<a class="delete" href="#" onclick="ajax_action(\'delete_goal_recent_friends\',\'div_goal_recent_friends\',\'cherryboard_id='.$cherryboard_id.'&meb_id='.$meb_id.'\')" ><img src="images/delete.png"></a>':'').'</div>
					</div>
					<img src="'.$fb_photo_url.'" class="thumb">
				</div>
				</div>';
				$cnt++;
			}
			
		}
		echo $FriendsCnt;
	?>
	</div>
	 </div>
        <!--my cheeryleader End-->
     <div id="my_cherryleaders"><a href="experts.php" class="gray_link_15 right">+</a>Inspirational Experts<br>
	 <div id="div_goal_experts">
   <?php
	//Experts BLOCK
		$selQuery="select a.experts_id,b.user_id,b.fb_photo_url from tbl_app_cherryboard_experts a,tbl_app_users b where a.user_id=b.user_id and a.cherryboard_id=".$cherryboard_id." order by a.experts_id desc limit 10";
		$selSqlQ=mysql_query($selQuery);
		$ExpertsCnt='';
		if(mysql_num_rows($selSqlQ)>0){
			$cnt=0;
			while($rowTbl=mysql_fetch_array($selSqlQ)){
				$experts_id=$rowTbl['experts_id'];
				if($cnt==5){$ExpertsCnt.='<br/>';}
				$ExpertsCnt.='<div class="small_thumb_container">
				<div class="img_big_container">
					<div class="feedbox_holder">
						<div class="actions"><a class="delete" href="#" onclick="ajax_action(\'delete_goal_experts\',\'div_goal_experts\',\'cherryboard_id='.$cherryboard_id.'&experts_id='.$experts_id.'\')" ><img src="images/delete.png"></a></div>
					</div>
					<img src="'.$rowTbl['fb_photo_url'].'" class="thumb">
				</div>
				</div>';
				$cnt++;
			}
			
		}else{
			$ExpertsCnt.='<strong>No Experts</strong>';
		}
		echo $ExpertsCnt;
	?>
	</div>
	 </div>
	 
    </div>
	
    <div id="middle_wrapper" style="margin-left:250px; width:470px;" >
    <?php if($msg!=""){ ?>
		<h1 style="font-size:12px"><font color="#009900"><?php echo $msg;?></font></h1>
		<?php }	?>
		<?php $user_id=(int)getFieldValue('user_id','tbl_app_cherryboard','cherryboard_id='.$cherryboard_id);?>
    	<h1><div id="div_goal_title"><?php echo $cherrry_title;?>&nbsp;
		<?php if($user_id==USER_ID){ ?>
		<img src="images/edit.jpg" height="20" style="cursor:pointer" onclick="edit_goal('edit_goal_title',<?php echo $cherryboard_id;?>)" width="20" title="Edit" />
		<?php } ?>
		</div></h1><br>
	  <div>Goal type: <?=ucwords($category_name)?></div><br>
	   <form name="form1" method="post" action="" enctype="multipart/form-data">
		<input type="hidden" name="user_id" id="user_id" value="<?php echo USER_ID;?>" />
		<input type="hidden" name="cherryboard_id" id="cherryboard_id" value="<?php echo $cherryboard_id;?>" />
			<div id="div_up_photo"><!--<div id="me" class="red_link_14">+ Add a Photo</div>--></div>
		  <br>
		</form>
		<div id="me" class="red_link_14">+ Add a Photo</div>
	<br>
  </div>
   <div class="clear"></div>
</div>
</div>
<div id="body_container">
	<div class="wrapper">
        <div id="checklist"><h2>Checklist</h2>
          <input name="txt_checklist" id="txt_checklist" type="text" onfocus="if(this.value=='add something to your checklist') this.value='';" onblur="if(this.value=='') this.value='add something to your checklist';" class="input_200" value="add something to your checklist">
          </label><input name="Submit" type="button" onclick="ajax_action('add_checklist','div_checklist','cherryboard_id=<?=$cherryboard_id;?>&txt_checklist='+document.getElementById('txt_checklist').value+'&user_id=<?=USER_ID?>');" value="Post" title="Post" class="btn_small" style="margin:0px;">
          <br>
          <br>
		  <div id="div_checklist">
		  <?php
		  //CHECKLIST BLOCK
			 $selchk=mysql_query("select *,date_format(record_date,'%m-%d-%Y') as record_date from tbl_app_checklist where cherryboard_id=".$cherryboard_id." order by checklist_id desc limit 10");
			$checkCnt='';
			while($selchkRow=mysql_fetch_array($selchk)){
				$checklist_id=$selchkRow['checklist_id'];
				$checklist=$selchkRow['checklist'];
				$record_date=$selchkRow['record_date'];
				$is_checked=$selchkRow['is_checked'];
				$checkCnt.='<div class="box_container"><label><input type="checkbox" id="chkfield_'.$checklist_id.'"  name="chkfield_'.$checklist_id.'" '.($is_checked==1?'checked="checked"':'').' value="1" onclick="checked_checklist(\'checked_checklist\',\'div_checklist\',\'cherryboard_id='.$cherryboard_id.'&checklist_id='.$checklist_id.'\',\'chkfield_'.$checklist_id.'\')" class="checkbox"></label>&nbsp;'.$checklist.'<br/><span class="smalltext">added '.$record_date.'&nbsp;<img src="images/close_small1.png" onclick="ajax_action(\'remove_checklist\',\'div_checklist\',\'cherryboard_id='.$cherryboard_id.'&checklist_id='.$checklist_id.'\')" style="cursor:pointer"></span></div>';
			}
			echo $checkCnt;
		 ?>
		</div>
		<br/><Br/>
		<?php echo UserFeedSection('cherryboard',$cherryboard_id);?>
      </div>
	  <div id="right_container" style="position: absolute;margin-left:275px;">
	  
	  <div style="padding-left:30px"><a title="Sort" class="btn_small" name="Sort" href="javascript:void(0);" onclick="javascript:ajax_action('photo_refresh','right_container','cherryboard_id=<?=$cherryboard_id?>&sort=asc')">Ascending</a></div>
	  
	  	  <?php
		  //CHERRYBOARD PHOTOS
		  $Board_record_date=getFieldValue('record_date','tbl_app_cherryboard','cherryboard_id='.$cherryboard_id);
		  $qryphoto="select *,date_format(record_date,'%m/%d/%Y') as new_record_date,DATEDIFF(record_date,'".$Board_record_date."') as photo_day  from tbl_app_cherry_photo where cherryboard_id=".$cherryboard_id." order by photo_id desc";
		  $selphoto=mysql_query($qryphoto);
		  $cntPhoto=mysql_num_rows($selphoto);
		  $photoDayArr=array();
		  if($cntPhoto>0){
			while($selphotoRow1=mysql_fetch_array($selphoto)){
				$photo_id=$selphotoRow1['photo_id'];
				$photo_day=((int)$selphotoRow1['photo_day']+1);
				$photoDayArr[$photo_id]=$photo_day;
			}	
		  
		  }
		 $photoDayArr = array_unique($photoDayArr);
		 $photoCntArray=array();
		 for($i=$goal_days;$i>=1;$i--){	
		  	$photoCnt='';	
			if(in_array($i,$photoDayArr)){
			  $qryphoto="select *,date_format(record_date,'%m/%d/%Y') as new_record_date,DATEDIFF(record_date,'".$Board_record_date."') as photo_day  from tbl_app_cherry_photo where cherryboard_id=".$cherryboard_id." and (DATEDIFF(record_date,'".$Board_record_date."')+1)='".$i."' order by photo_id desc";
			  $selphoto=mysql_query($qryphoto);
			  while($selphotoRow=mysql_fetch_array($selphoto)){
					$photo_id=$selphotoRow['photo_id'];
					$user_id=$selphotoRow['user_id'];
					$photo_title=ucwords(stripslashes($selphotoRow['photo_title']));
					$photo_name=$selphotoRow['photo_name'];
					$record_date=$selphotoRow['new_record_date'];
					$photo_day=((int)$selphotoRow['photo_day']+1);
					$photoPath='images/cherryboard/'.$photo_name;
					if(is_file($photoPath)){
					   $photoCnt.='<div class="field_container2" style="width: 190px; position: absolute;	padding-right: 20px; font-size:12px;border-radius:5px">
					   
						<div class="day_container">Day '.$photo_day.'</div>
				  <div class="tag_container">
					<div class="comment_box1" style="cursor:pointer;height:30px;" ondblclick="ajax_action(\'upd_photo_title\',\'photo_title'.$photo_id.'\',\'stype=add&photo_id='.$photo_id.'\')" id="photo_title'.$photo_id.'">&nbsp;'.getLimitString($photo_title,55).'</div><div class="clear"></div>
						<div class="info_box">
							<div class="score">Day '.$photo_day.'</div>
							<div class="date">'.$record_date.'</div>
						 </div>
						 <div class="b_arrow"></div>
					 <div class="clear"></div>
				 </div>
					   
							<div class="img_big_container">
								<div class="feedbox_holder">
									<div class="actions">';
									if($user_id==USER_ID){
									$photoCnt.='<a class="delete" href="#" onclick="photo_action(\'del_photo\','.$cherryboard_id.','.$photo_id.')"><img src="images/delete.png"></a>';
									}
									$photoCnt.='</div>
								 </div>
								 <div class="send_message">
									<div class="actions1"><a href="#" onclick="javascript:window.open(\'add_data.php?type=thankyou&cherryboard_id='.$cherryboard_id.'\',\'add_data\',\'height=150,width=500\')" class="msg">Send Thank You</a></div>
								 </div>
								  <img src="'.$photoPath.'">
							 </div>
						   <div id="div_cherry_comment_'.$photo_id.'">';
								$TotalCmt=getFieldValue('count(photo_id)','tbl_app_cherry_comment','photo_id='.$photo_id);
								$TotalCheers=getFieldValue('count(cheers_id)','tbl_app_cherryboard_cheers','photo_id='.$photo_id);
								$checkCheers=(int)getFieldValue('user_id','tbl_app_cherryboard_cheers','photo_id='.$photo_id.' and user_id='.USER_ID);
								if($checkCheers==0){
									$cheersLink='<a href="javascript:void(0);" onclick="add_cherry_cheers(\'add_cheers\',\''.$photo_id.'\',\''.$cherryboard_id.'\',\''.USER_ID.'\')" class="red_link_14" style="font-size:12px;">+give cheers!</a>';
								}else{$cheersLink='';}
								$photoCnt.=$cheersLink.'<div class="right smalltext1" id="div_photo_cheers_'.$photo_id.'">&nbsp;'.(int)$TotalCheers.'&nbsp;Cheers&nbsp;&nbsp;'.(int)$TotalCmt.'&nbsp;Comments</div><br><br>';
								if($TotalCmt>0){
								  $selCmt=mysql_query("select * from tbl_app_cherry_comment where photo_id=".$photo_id." order by comment_id desc limit 2");
								  while($cmtRow=mysql_fetch_array($selCmt)){
									   $userPhotoArray=getFieldsValueArray('first_name,last_name,fb_photo_url','tbl_app_users','user_id='.$cmtRow['user_id']);
									   $UserName=$userPhotoArray[0].' '.$userPhotoArray[1];
									   $UserPhoto=$userPhotoArray[2];
									   $comment_id=$cmtRow['comment_id'];
									   $PhotoComment=stripslashes($cmtRow['cherry_comment']);
									   
									   $photoCnt.='<div class="comment2">
										  <div class="feedbox_holder">
											<div class="actions"><a class="delete" href="javascript:void(0);" onclick="add_cherry_comment(event,\'del_cherry_comment\','.$cherryboard_id.','.$photo_id.','.$cmtRow['user_id'].','.$comment_id.')"><img src="images/delete.png"></a></div>
										  </div>
										  <img src="'.$UserPhoto.'" height="30" width="30" class="img_thumb1">
										  <div class="comment_txt"><strong>'.$UserName.'</strong>&nbsp;&nbsp;'.$PhotoComment.'</div> <div class="clear"></div></div>';
								  }
								}
						$photoCnt.='</div>
							  <textarea name="cherry_comment_'.$photo_id.'" class="input_comments" id="cherry_comment_'.$photo_id.'" onfocus="if(this.value==\'Leave your comment here\') this.value=\'\';" onblur="if(this.value==\'\') this.value=\'Leave your comment here\';" onkeypress="return add_cherry_comment(event,\'add_cherry_comment\',\''.$cherryboard_id.'\',\''.$photo_id.'\',\''.USER_ID.'\',document.getElementById(\'cherry_comment_'.$photo_id.'\').value)">Leave your comment here</textarea>
							  ';
						if(($i==$goal_days&&$sort=="asc")||($i==1&&$sort!="asc")){
							$photoCnt.='<div style="padding-bottom:35px;"></div>';	  
						}
						$photoCnt.='</div>';
						$photoCntArray[$i]=$photoCnt;
					}
				}
			  }else{
			  	 $photoPath='images/cherryboard/no_image.jpg'; 
				 $photoCnt.='<div class="field_container2" style="width: 190px; position: absolute;	padding-right: 20px; font-size:12px;border-radius:5px">
					   
						<div class="day_container">Day '.$i.'</div>
				  <div class="tag_container">
					<div class="comment_box1" style="height:30px;">No Photo</div><div class="clear"></div>
						<div class="info_box">
							<div class="score">Day '.$i.'</div>
							<div class="date">&nbsp;</div>
						 </div>
						 <div class="b_arrow"></div>
					 <div class="clear"></div>
				 </div>
					   
							<div class="img_big_container">
								 <img src="'.$photoPath.'" height="195" width="195">
							 </div>
						   <div id="div_cherry_comment">';
								
						$photoCnt.='</div>';
						if(($i==$goal_days&&$sort=="asc")||($i==1&&$sort!="asc")){
							$photoCnt.='<div style="padding-bottom:35px;"></div>';	  
						}
						$photoCnt.='</div>';
						$photoCntArray[$i]=$photoCnt;
						
			  }
			   
			}
		$NewphotoCnt='';
		if($sort=="asc"){
			for($i=1;$i<=$goal_days;$i++){
				$NewphotoCnt.=$photoCntArray[$i];
			}
		}else{
			for($i=$goal_days;$i>=1;$i--){
				$NewphotoCnt.=$photoCntArray[$i];
			}
		}		
		echo $NewphotoCnt;
		 ?>
            
       </div>
	   <div class="clear"></div>        
  </div>
</div>
<?php include('fb_invite.php');?>
<?php include('site_footer.php');?>
