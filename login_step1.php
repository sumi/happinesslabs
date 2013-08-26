<?php
include_once "fbmain.php";
include('include/app-common-config.php');
?>
<?php include('site_header.php');?>
<!-- START BOTTOM SECTION -->
<?php
if($_POST['LoginStep']=="2"){
?>
<div class="listoftop_bg" style="padding-top:100px">
    <div class="bottom_main">
		<div class="bottom_box_main">
			<div class="todo" style="border:none;width:600px">
			  <div class="todolist_left_1"><a href="#" style="cursor:default;">Relationship Stories</a></div>
			  <div style="clear:both"></div> 
			</div>
			<div class="todolist_bt"><img src="images/banet_4.png" alt="" /></div>		
		</div>
	</div> <!-- End of Bottom Main --> 	
	<div class="mini masonry" id="mini-container" style="padding-top:5px;width:965px;margin:auto;">
	<?php	
	/*
	$whereCnd="";
	if($category_id>0){
		$whereCnd="a.expertboard_id=b.expertboard_id AND a.board_type='0' AND b.is_publish='1' AND a.category_id=".$category_id;
	}else{
		$whereCnd="a.expertboard_id=b.expertboard_id AND a.board_type='0' AND b.is_publish='1'"; 
	}
	$giftCnt='';
	//SELECT * FROM tbl_app_expertboard WHERE ".$whereCnd." ORDER BY expertboard_id
	$sel=mysql_query("SELECT a.* FROM tbl_app_expertboard a,tbl_app_expert_cherryboard b WHERE ".$whereCnd." ORDER BY expertboard_id");					
	$pagePhotosArray=array();
	if(mysql_num_rows($sel)>0){
		$newCnt=1;
		while($row=mysql_fetch_array($sel)){
				
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
			//Expert
			$reward='';
			$selQuery=mysql_query("select a.* from tbl_app_expert_reward_photo a,tbl_app_expert_cherryboard b where a.cherryboard_id=b.cherryboard_id and b.expertboard_id=".$expertboard_id.' group by a.exp_reward_id');
			if(mysql_num_rows($selQuery)>0){
				$cnt=1;
				while($selQueryRow=mysql_fetch_array($selQuery)){
					$reward_title=$selQueryRow['photo_title'];
					$reward_photo='images/expertboard/reward/'.$selQueryRow['photo_name'];
					if($cnt==2){echo "<br>";$cnt=1;}
					$reward.='<div class="img_big_container" style="text-align:center">
					  <img width="180px" src="'.$reward_photo.'" alt="'.$reward_title.'"><br/>
					  '.ucwords($reward_title).'
					</div>';
					$cnt++;
				}
			}
			if($reward!=""){$reward='<br>'.$reward;}
			$expertPicPath='https://graph.facebook.com/'.$userOwnerFbId.'/picture?type=large';
				
			$cherryboard_id=(int)getFieldValue('cherryboard_id','tbl_app_expert_cherryboard','expertboard_id="'.$expertboard_id.'" and main_board="0" AND user_id='.USER_ID);
			if($userOwnerFbId!=""){								
				$Owner_cherryboard_id=(int)getFieldValue('cherryboard_id','tbl_app_expert_cherryboard','expertboard_id="'.$expertboard_id.'" and main_board="1" limit 1');
				if($Owner_cherryboard_id>0){
					$TotalCheers=countCheers($expertboard_id,'expertboard');
					$giftCnt.='
					<div class="w2 h1 masonry-brick">
					<div class="bottom_box_main" style="width:85px;height:80px">
					<div class="main_box">
					 <div style="text-align:center"><input type="checkbox" value="0" name=""></div>
						<div class="day_img">
						<a href="expert_cherryboard.php?cbid='.$Owner_cherryboard_id.'">
						<img src="'.$expertPicPath.'" height="75px" width="75px" title="'.$userName.'" data-tooltip="sticky'.$newCnt.'" />
						</a></div>
				   </div>
				   <div class="padding"></div>
				   </div></div>';
				   $pagePhotosArray[$newCnt]=$expertPicPath;
				   $newCnt++;
				 }
			}
		}
	}else{
		$giftCnt.='No Story';
	}
	*/
	  	$friends = $facebook->api('/me/friends');
		//print_r($friends);
		$newCnt=1;
		foreach ($friends as $key=>$value) {
		//echo count($value) . ' Friends';
			foreach ($value as $fkey=>$fvalue) {
				$friend_id=$fvalue['id'];
				$friend_name=$fvalue['name'];
				$frind_Photo="http://graph.facebook.com/".$friend_id."/picture";
				$giftCnt.='
					<div class="w2 h1 masonry-brick">
					<div class="bottom_box_main" style="width:95px;height:95px">
					<div class="main_box">
					 <div style="text-align:center"><input type="checkbox" value="0" name=""></div>
						<div class="day_img">
						<img src="'.$frind_Photo.'" height="75px" width="75px" title="'.$friend_name.'" data-tooltip="sticky'.$newCnt.'" />
						</div>
				   </div>
				   <div class="padding"></div>
				   </div></div>';
				   $pagePhotosArray[$newCnt]=$frind_Photo;
				   $newCnt++;
			}
		}

	
	echo $giftCnt;
	?>
	</div><!-- End of Mini-Container -->
	 <div style="clear:both"></div>
   </div>
   
   <div id="mystickytooltip" class="stickytooltip">


   <?php
   $pagePhotoEffect='';
   foreach($pagePhotosArray as $photoCnt=>$photoUrl){
   		$pagePhotoEffect.='<div id="sticky'.$photoCnt.'" class="atip">
			<img src="'.$photoUrl.'" width="100px" />
			</div>';
   }
   echo $pagePhotoEffect;
   ?>
   <form action="" name="frmLoginStep" method="post">
   <input type="hidden" value="2" name="LoginStep" id="LoginStep" />
   </form>
	</div>
	<div style="text-align:center"><a href="javascript:void(0);" onclick="javascript:document.frmLoginStep.submit();"><img style="padding-left:190px;" alt="" src="images/next.png"></a></div>
<?php 
}else{
?>
<div class="listoftop_bg" style="padding-top:100px">
    <div class="bottom_main">
		<div class="bottom_box_main">
			<div class="todo" style="border:none;width:600px">
			  <div class="todolist_left_1"><a href="#" style="cursor:default;">Relationship Stories</a></div>
			  <div style="clear:both"></div> 
			</div>
			<div class="todolist_bt"><img src="images/banet_4.png" alt="" /></div>		
		</div>
	</div> <!-- End of Bottom Main --> 	
	<div class="mini masonry" id="mini-container" style="padding-top:5px;width:965px;margin:auto;">
	<?php	
	$whereCnd="";
	if($category_id>0){
		$whereCnd="a.expertboard_id=b.expertboard_id AND a.board_type='0' AND b.is_publish='1' AND a.category_id=".$category_id;
	}else{
		$whereCnd="a.expertboard_id=b.expertboard_id AND a.board_type='0' AND b.is_publish='1'"; 
	}
	$giftCnt='';
	//SELECT * FROM tbl_app_expertboard WHERE ".$whereCnd." ORDER BY expertboard_id
	$sel=mysql_query("SELECT a.* FROM tbl_app_expertboard a,tbl_app_expert_cherryboard b WHERE ".$whereCnd." ORDER BY expertboard_id");					
	$pagePhotosArray=array();
	if(mysql_num_rows($sel)>0){
		$newCnt=1;
		while($row=mysql_fetch_array($sel)){
				
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
			//Expert
			$reward='';
			$selQuery=mysql_query("select a.* from tbl_app_expert_reward_photo a,tbl_app_expert_cherryboard b where a.cherryboard_id=b.cherryboard_id and b.expertboard_id=".$expertboard_id.' group by a.exp_reward_id');
			if(mysql_num_rows($selQuery)>0){
				$cnt=1;
				while($selQueryRow=mysql_fetch_array($selQuery)){
					$reward_title=$selQueryRow['photo_title'];
					$reward_photo='images/expertboard/reward/'.$selQueryRow['photo_name'];
					if($cnt==2){echo "<br>";$cnt=1;}
					$reward.='<div class="img_big_container" style="text-align:center">
					  <img width="180px" src="'.$reward_photo.'" alt="'.$reward_title.'"><br/>
					  '.ucwords($reward_title).'
					</div>';
					$cnt++;
				}
			}
			if($reward!=""){$reward='<br>'.$reward;}
			$expertPicPath='https://graph.facebook.com/'.$userOwnerFbId.'/picture?type=large';
				
			$cherryboard_id=(int)getFieldValue('cherryboard_id','tbl_app_expert_cherryboard','expertboard_id="'.$expertboard_id.'" and main_board="0" AND user_id='.USER_ID);
			if($userOwnerFbId!=""){								
				$Owner_cherryboard_id=(int)getFieldValue('cherryboard_id','tbl_app_expert_cherryboard','expertboard_id="'.$expertboard_id.'" and main_board="1" limit 1');
				if($Owner_cherryboard_id>0){
					$TotalCheers=countCheers($expertboard_id,'expertboard');
					$giftCnt.='
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
				   </div></div>';
				   $pagePhotosArray[$newCnt]=$expertPicPath;
				   $newCnt++;
				 }
			}
		}
	}else{
		$giftCnt.='No Story';
	}
	echo $giftCnt;
	?>
	</div><!-- End of Mini-Container -->
	 <div style="clear:both"></div>
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
   <form action="" name="frmLoginStep" method="post">
   <input type="hidden" value="2" name="LoginStep" id="LoginStep" />
   </form>
	</div>
	<div style="text-align:center"><a href="javascript:void(0);" onclick="javascript:document.frmLoginStep.submit();"><img style="padding-left:190px;" alt="" src="images/next.png"></a></div>
<?php 
} 
?>
	
   <div style="padding-bottom:50px;"></div>
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