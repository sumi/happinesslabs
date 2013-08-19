<?php
include_once "fbmain.php";	
include('include/app-common-config.php');

include('site_header.php');
?>
<!--Body Start-->
<div id="wrapper">
	<div class="gray_28" style="background-color:#E4E4E4;height:40px;">
		<div align="center" style="font-size:20px;height:30px;padding-top:10px;margin-left:100px;margin-right:100px;">Fun challenges for BIG rewards</div>
	</div>
	<div style="background-color:#D6D6D6;height:50px;">
		<div align="center" style="font-size:16px;height:25px;padding-top:10px;margin-left:100px;margin-right:100px;">
		1)&nbsp;Join a challenge&nbsp;2)&nbsp;Create your picture story&nbsp;3)&nbsp;Win rewards
		</div>
	</div>
	<div id="right_container" style="width:960px;padding-top:10px;">
	<div class="mini masonry" id="mini-container">
	<?php
	//SELECT LIST OF REWARDS 
	$selrewards=mysql_query("select * from tbl_app_gift where gift_id>50 and campaign_id!='0' order by gift_id");
	$totalRewards=mysql_num_rows($selrewards);
	  if($totalRewards>0){
	  	while($selRewardsRow=mysql_fetch_array($selrewards)){
			$gift_id=$selRewardsRow['gift_id'];
			$campaignId=(int)getFieldValue('campaign_id','tbl_app_gift','gift_id='.$gift_id);
			$campaign_title=ucwords($selRewardsRow['campaign_title']);
			$gift_title=ucwords($selRewardsRow['gift_title']);
			$sponsor_name=ucwords($selRewardsRow['sponsor_name']);
			$sponsor_url=$selRewardsRow['sponsor_url'];
			$gift_photo=$selRewardsRow['gift_photo'];
			$sponsor_logo=$selRewardsRow['sponsor_logo'];
			$giftPath='images/gift/'.$gift_photo;
			$sponsorPath='images/gift/'.$sponsor_logo;
			$campaign_id=(int)$selRewardsRow['campaign_id'];
			$goal_days=(int)$selRewardsRow['goal_days'];
	?>
	
	<!--<a href="gift_profile.php?gid=<?php echo $gift_id;?>" style="text-decoration:none;color:#000000"><?php echo $campaign_title.' - '.$gift_title;?></a>&nbsp;<a href="index_detail.php?dgbid=<?php echo $gift_id;?>" onclick="return confirm('Are you sure to delete this reward?')"><img src="images/delete.png"></a>-->
	
			<div class="field_container" style="margin-left:20px;">
				<div align="center"><strong><a href="gift_profile.php?gid=<?=$campaign_id?>" style="text-decoration:none;color:#000000"><?php echo $campaign_title;?></a>&nbsp;<!--<a href="#"><img src="images/delete.png"></a>--></strong></div><br>
				<img src="<?php echo $giftPath;?>" height="195" width="195"><br><br>
				<font style="color:#DACB25;font-weight:bold"><?=$goal_days?>&nbsp;days</font>
				<br><br>
				<a style="text-decoration:none;color:#990000" href="#"><?php //$sponsor_url; ?>
				<div class="gift" style="vertical-align:bottom;padding:0px 0px 0px 0px;">
				<img class="imgsmall" title="<?=$sponsor_name?>" style="margin-bottom:0px;"
				 src="<?=$sponsorPath?>"><span style="padding-left:5px;"><?php echo $sponsor_name; ?></span>
				</div>
				</a>
		   </div>
		 <?php
		}
	 }
	//SELECT LIST OF CHALLENGES
	$selChallenge=mysql_query("SELECT * FROM tbl_app_expertboard ORDER BY expertboard_id");
	  $totalChallange=mysql_num_rows($selChallenge);
	  if($totalChallange>0){
	  	while($selChallengeRow=mysql_fetch_array($selChallenge)){
			$user_id=(int)$selChallengeRow['user_id'];
			$expertboard_id=(int)$selChallengeRow['expertboard_id'];
			$expertboard_title=ucwords(trim($selChallengeRow['expertboard_title']));
			$goal_days=(int)$selChallengeRow['goal_days'];
			$userDetail=getUserDetail($user_id);
			$userOwnerFbId=$userDetail['fb_id'];
			$userName=$userDetail['name'];
			$expertPicPath='https://graph.facebook.com/'.$userOwnerFbId.'/picture?type=large';
			if($expertPicPath==''){
				$expertPicPath='images/expert.jpg';
			}
			$Owner_cherryboard_id=(int)getFieldValue('cherryboard_id','tbl_app_expert_cherryboard','expertboard_id="'.$expertboard_id.'" and main_board="1" limit 1');
			?>
			<div class="field_container" style="margin-left:20px;">
				<div align="center"><strong><a href="expert_cherryboard.php?cbid=<?=$Owner_cherryboard_id?>" style="text-decoration:none;color:#000000">
				<?php echo $expertboard_title;?></a>&nbsp;<!--<a href="#"><img src="images/delete.png"></a>-->
				</strong></div><br>
				<img src="<?php echo $expertPicPath;?>" height="195" width="195"><br><br>
				<font style="color:#DACB25;font-weight:bold"><?=$goal_days?>&nbsp;days</font>
				<br/><br/>
				<span style="padding-left:5px;"><a style="text-decoration:none;color:#990000" href="#"><?php echo $userName; ?></a></span>
		   </div>
		 <?php
	 	}
	 }	 
	  ?>
	  </div>
      </div>
    <div class="clear"></div>
</div>
<script src="js/masonry.js"></script>
<script>
  window.onload = function() {
    
    var miniWall = new Masonry( document.getElementById('mini-container'), {
      columnWidth: 20,
      foo: 'bar'
    });
        
  };
</script>
<!--Body End-->
<?php include('site_footer.php');?>