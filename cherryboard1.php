<?php
include_once "fbmain.php";
include('include/app-common-config.php');
$cherryboard_id=$_GET['cbid'];
$msg='';
?>
<?php 
include('site_header1.php');
?>
<?php
$giftTitle=getFieldValue('gift_title','tbl_app_gift','gift_id='.$gift_id);

	//Cheryboard Detail
	$cherrySel=mysql_query("select category_id,cherryboard_title from tbl_app_cherryboard where cherryboard_id=".$cherryboard_id);
	while($cherryRow=mysql_fetch_array($cherrySel)){
		$cherrry_title=ucwords($cherryRow['cherryboard_title']);
		$category_id=ucwords($cherryRow['category_id']);
		$category_name=getFieldValue('category_name','tbl_app_category','category_id='.$category_id);
	}

?>
<div id="wrapper">
	<div class="right">
<br>
<br>
<div id="my_cherryleaders">Monthly Specials<br>
    <div id="div_goal_monthly_specials">
<?php
	$cherrySel=mysql_query("select a.cherry_gift_id,a.gift_id,b.gift_photo,b.gift_title from tbl_app_cherry_gift a,tbl_app_gift b where a.gift_id=b.gift_id and a.cherryboard_id=".$cherryboard_id." group by a.gift_id");
	$MonthSpeCnt='';
	if(mysql_num_rows($cherrySel)>0){
		while($cherryRow=mysql_fetch_array($cherrySel)){
			$cherry_gift_id=$cherryRow['cherry_gift_id'];
			$gift_photo=$cherryRow['gift_photo'];
			$gift_title=$cherryRow['gift_title'];
			
			$MonthSpeCnt.='<div class="img_big_container">
		    	  <div class="feedbox_holder">
                </div>
              <img src="images/gift/'.$gift_photo.'" class="profile_img_big"><br>
<a href="#" class="get_another_gift right">Get another gift!</a> </div>';
		}
	}else{
		$MonthSpeCnt.='<strong>No Monthly Specials</strong>';
	}	
	echo $MonthSpeCnt;
?>
	</div>
</div>
<?php
	$TodayDay=getGoalboardRemainDays($cherryboard_id);
	if($TodayDay<30){
		echo '<br/><font style="color:#DACB25;font-weight:bold">'.$TodayDay.' more days to win</font>';
	}else{
		echo '<br/><font style="color:#DACB25;font-weight:bold">'.$TodayDay.' days</font>';
	}	
?>
</div>
	<div id="left_container">
      <!--my cheeryleader Start-->
        <div id="my_cherryleaders"><a href="#" id="invite_frnd" class="gray_link_15 right">+</a>Encouraging Friends<input type="hidden" name="cherryboard_id" id="cherryboard_id" value="<?php echo $cherryboard_id;?>" /><input type="hidden" name="cherryboard_key" id="cherryboard_key" value="0" /><br>
	 <div id="div_goal_friends">
	 <?php
	//FRIENDS BLOCK
	    $FriendsCnt='';
		$selQuery="select a.meb_id,b.user_id,b.fb_photo_url from tbl_app_cherryboard_meb a,tbl_app_users b where a.req_user_fb_id=b.facebook_id and a.is_accept='1' and b.user_id!=".USER_ID." and a.cherryboard_id=".$cherryboard_id." group by b.user_id limit 10";
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
							<div class="actions"><a class="delete" href="#" onclick="ajax_action(\'delete_goal_friends\',\'div_goal_friends\',\'cherryboard_id='.$cherryboard_id.'&meb_id='.$meb_id.'\')" ><img src="images/delete.png"></a></div>
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
		$selQuery="select meb_id,req_user_fb_id from tbl_app_cherryboard_meb where is_accept='0' and cherryboard_id=".$cherryboard_id." order by meb_id desc limit 10";
		$selSqlQ=mysql_query($selQuery);
		if(mysql_num_rows($selSqlQ)>0){
			$FriendsCnt.='<br/><br/><p>Friends Request</p>';
			$cnt=0;
			while($rowTbl=mysql_fetch_array($selSqlQ)){
				if($cnt==5){$FriendsCnt.='<br/>';}
				$meb_id=$rowTbl['meb_id'];
				$fb_photo_url=getFriendPhoto($rowTbl['req_user_fb_id']);
				$FriendsCnt.='<div class="small_thumb_container">
				<div class="img_big_container">
					<div class="feedbox_holder">
						<div class="actions"><a class="delete" href="#" onclick="ajax_action(\'delete_goal_recent_friends\',\'div_goal_recent_friends\',\'cherryboard_id='.$cherryboard_id.'&meb_id='.$meb_id.'\')" ><img src="images/delete.png"></a></div>
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
    	<h1><div id="div_goal_title"><?php echo $cherrry_title;?>&nbsp;<img src="images/edit.jpg" height="20" style="cursor:pointer" onclick="edit_goal('edit_goal_title',<?php echo $cherryboard_id;?>)" width="20" title="Edit" /></div></h1><br>
	  <div align="center">Goal type: <?=ucwords($category_name)?></div><br>
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
	  	  <?php
		  //CHERRYBOARD PHOTOS
		  $Board_record_date=getFieldValue('record_date','tbl_app_cherryboard','cherryboard_id='.$cherryboard_id);
			
			 $selphoto=mysql_query("select *,date_format(record_date,'%m/%d/%Y') as new_record_date,DATEDIFF(record_date,'".$Board_record_date."') as photo_day  from tbl_app_cherry_photo where cherryboard_id=".$cherryboard_id." order by photo_id desc");
			$photoCnt='';
			$cntPhoto=mysql_num_rows($selphoto);
			while($selphotoRow=mysql_fetch_array($selphoto)){
				$photo_id=$selphotoRow['photo_id'];
				$photo_title=ucwords(stripslashes($selphotoRow['photo_title']));
				$photo_name=$selphotoRow['photo_name'];
				$record_date=$selphotoRow['new_record_date'];
				$photo_day=((int)$selphotoRow['photo_day']+1);
				$photoPath='images/cherryboard/'.$photo_name;
				if(is_file($photoPath)){
				   $photoCnt.='<div class="field_container2" style="width: 190px; position: absolute;	padding: 20px; font-size:12px;border-radius:5px">
				   
				    <div class="day_container">Day '.$photo_day.'</div>
           	  <div class="tag_container">
              	<div class="comment_box1">'.stripslashes($photo_title).'</div><div class="clear"></div>
                	<div class="info_box">
                		<div class="score">Day '.$photo_day.'</div>
		           	    <div class="date">'.$record_date.'</div>
                     </div>
                     <div class="b_arrow"></div>
                 <div class="clear"></div>
             </div>
				   
						<div class="img_big_container">
							<div class="feedbox_holder">
								<div class="actions"><a class="delete" href="#" onclick="photo_action(\'del_photo\','.$cherryboard_id.','.$photo_id.')"><img src="images/delete.png"></a></div>
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
							$photoCnt.=$cheersLink.'<div class="right smalltext1" id="div_photo_cheers_'.$photo_id.'">'.(int)$TotalCheers.' Cheers &nbsp;&nbsp;'.(int)$TotalCmt.' Comments</div><br><br>';
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
									  <img src="'.$UserPhoto.'" height="30" width="30" class="img_thumb1"><br/><strong>'.$UserName.'</strong>&nbsp;&nbsp;'.$PhotoComment.'</div>';
							  }
							}
					$photoCnt.='</div>
						  <textarea name="cherry_comment_'.$photo_id.'" class="input_comments" id="cherry_comment_'.$photo_id.'" onfocus="if(this.value==\'Leave your comment here\') this.value=\'\';" onblur="if(this.value==\'\') this.value=\'Leave your comment here\';" onkeypress="return add_cherry_comment(event,\'add_cherry_comment\',\''.$cherryboard_id.'\',\''.$photo_id.'\',\''.USER_ID.'\',document.getElementById(\'cherry_comment_'.$photo_id.'\').value)">Leave your comment here</textarea>
						  </div>';
					$cntPhoto--;
				}
			}
			echo $photoCnt;
		 ?>
		<div style="height:50px">&nbsp;</div>            
       </div>
	   <div class="clear"></div>
  </div>
</div>
<?php include('fb_invite.php');?>
<?php include('site_footer.php');?>
