<?php
include_once "fbmain.php";
include('include/app-common-config.php');
?>
<?php include('site_header.php');?>
<script language="javascript" type="text/javascript" src="js/niceforms.js"></script>
<style type="text/css" media="screen">@import url(niceforms-default.css);</style>
<!--  START TOP SECTION -->
<div class="banner-listof_bg" style="padding: 0 200px;">
	<form action="vars.php" method="post" class="niceform" target="_blank" style="padding-left:480px;">
	<span class="niceform">
	<select size="1" id="mySelect1" name="mySelect1" class="width_330">
	  <option>Choose Story Catagory:</option>
	  <option>Choose Story Catagory:1</option>
	  <option>Choose Story Catagory:2</option>
	  <option>Choose Story Catagory:3</option>
	  <option>Choose Story Catagory:4</option>
	</select>
	</span>
  </form>  
	<div class="banner_listof"><img src="images/banner_listof.png" alt=""  width="470"/></div>
	<div class="banner_day">
	<div class="banner_listof_text">10 easy ways to say &rdquo;I love you&rdquo; every day.<br /><br /> 
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
<!-- START BOTTOM SECTION -->
<div class="listoftop_bg">
   <div class="bottom_main">
		<div class="top_text">
		  <div class="todo" style="border:none;">
			  <div class="todolist_left_1" style="font-size:30px;"><a href="#" style="cursor:default;">most smiles</a></div>
			  <div style="clear:both"></div> 
		  </div>
		  <div class="todolist_bt"><img src="images/banet_4.png" alt="" /></div>
		</div>
		<div style="width:958px;">
		<?php
		$selExpReward=mysql_query("SELECT * FROM tbl_app_expert_reward_photo ORDER BY exp_reward_id");					
		$rewardCnt='';
		if(mysql_num_rows($selExpReward)>0){
		   $pagePhotosArray=array();
		   while($selExpRewardRow=mysql_fetch_array($selExpReward)){							
				$exp_reward_id=$selExpRewardRow['exp_reward_id'];
				$user_id=$selExpRewardRow['user_id'];
				$cherryboard_id=$selExpRewardRow['cherryboard_id'];
				$photo_title=ucwords(trim($selExpRewardRow['photo_title']));
				$photo_name=$selExpRewardRow['photo_name'];
				$photo_path='images/expertboard/reward/'.$photo_name;
				$expertboard_id=getFieldValue('expertboard_id','tbl_app_expert_cherryboard','cherryboard_id='.$cherryboard_id);
			 if($expertboard_id>0){	
				$expertTitle=getFieldValue('expertboard_title','tbl_app_expertboard','expertboard_id='.$expertboard_id);
				$goalDays=getFieldValue('goal_days','tbl_app_expertboard','expertboard_id='.$expertboard_id);
				$DayType=getDayType($expertboard_id);
				$userDetail=getUserDetail($user_id);
				$userName=$userDetail['name'];
				$TotalCheers=(int)countCheers($expertboard_id,'expertboard');
				
				if(is_file($photo_path)){
					$rewardCnt.='<div class="bottom_box_main">
					<div class="main_box">
						<div class="day_img">
						<a href="expert_cherryboard.php?cbid='.$cherryboard_id.'">
						<img src="'.$photo_path.'" height="150px" width="209px" title="'.$userName.'" data-tooltip="sticky'.$exp_reward_id.'" />
						</a></div>
						<div class="bottom_box_text"><strong>'.$photo_title.'</strong><br/></div>
					   <div class="bottom_healthy">
						 <div class="bottom_healthy_im"><img src="images/box.png" alt="" /></div>
						 <div class="bottom_healthy_12">'.$TotalCheers.' cheers!</div>
					   <div style="clear:both"></div>
					   </div>
				   </div>
				   <div class="padding"></div>
				   </div>';
				   $pagePhotosArray[$exp_reward_id]=$photo_path;
				}
			  }
		   }
		}
		echo $rewardCnt;
		?>   
		</div><!-- div -->
   </div><!-- bottom_main -->
<div style="clear:both"></div>
</div>
 <div id="mystickytooltip" class="stickytooltip">
   <?php
   $pagePhotoEffect='';
   foreach($pagePhotosArray as $photoId=>$photoUrl){
		$imgInfo=getimagesize($photoUrl);
		$imgWidth=(int)($imgInfo[0]*3);
		$imgHeight=(int)($imgInfo[1]*3);
   		$pagePhotoEffect.='<div id="sticky'.$photoId.'" class="atip">
			<img src="'.$photoUrl.'" width="'.$imgWidth.'px" height="'.$imgHeight.'px"/>
			</div>';
   }
   echo $pagePhotoEffect;
   ?>
	</div>
<div style="padding-bottom:60px;"></div>
<?php include('site_footer.php');?>
