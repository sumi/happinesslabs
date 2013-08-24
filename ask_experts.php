<?php
include_once "fbmain.php";
include('include/app-common-config.php');
$category_id=(int)$_GET['category_id'];
?>
<?php include('site_header.php');?>
<script language="javascript" type="text/javascript" src="js/niceforms.js"></script>
<style type="text/css" media="screen">@import url(niceforms-default.css);</style>
<!--  START TOP SECTION -->
<div class="banner-listof_bg" style="padding: 0 200px;">
	<form action="vars.php" method="post" class="niceform" target="_blank" style="padding-left:480px;">
	<span class="niceform">
	<?php echo getCategoryList($category_id,'onchange="javascript:document.location=\'ask_experts.php?category_id=\'+this.value;"','category_id'); ?>	
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
		<div class="bottom_box_main">
			<div class="todo" style="border:none;">
			  <div class="todolist_left_1"><a href="#" style="cursor:default;">most smiles</a></div>
			  <div style="clear:both"></div> 
			</div>
			<div class="todolist_bt"><img src="images/banet_4.png" alt="" /></div>		
		</div>
		<div class="bottom_box_main" style="float:right;width:340px;margin-left:15px;">
			<div class="todo" style="border:none;width:340px;">
			  <div class="todolist_left_1" style="font-size:21px;">
			  <a rel="leanModal" href="#sendRequest" title="Send Request to Tell a Story">
			  Send Request to Tell a Story By Email</a>
			  </div>
			  <div style="clear:both"></div> 
			</div>		
		</div>
		<div class="bottom_box_main" style="float:right;width:340px;">
			<div class="todo" style="border:none;width:340px;">
			  <div class="todolist_left_1" style="font-size:21px;">
			  <a href="#" id="invite_frnd" title="Send Request to Tell a Story">Send Request to Tell a Story to Facebook Friends</a>
			  <input type="hidden" name="cherryboard_key" id="cherryboard_key" value="0" />
			  <input type="hidden" name="cherryboard_id" id="cherryboard_id" value="0" />
			  </div>
			  <div style="clear:both"></div> 
			</div>		
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
	$sel=mysql_query("SELECT a.*,b.cherryboard_id FROM tbl_app_expertboard a,tbl_app_expert_cherryboard b WHERE ".$whereCnd." ORDER BY expertboard_id");					
	$pagePhotosArray=array();
	if(mysql_num_rows($sel)>0){
		$newCnt=1;
		while($row=mysql_fetch_array($sel)){
				
			$user_id=(int)$row['user_id'];
			$expertboard_id=(int)$row['expertboard_id'];
			$cherryboard_id=(int)$row['cherryboard_id'];
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
			$expertPicPath='https://graph.facebook.com/'.$userOwnerFbId.'/picture?type=large';//$cherryboard_id=(int)getFieldValue('cherryboard_id','tbl_app_expert_cherryboard','expertboard_id="'.$expertboard_id.'" and main_board="0" AND user_id='.USER_ID);
			if($userOwnerFbId!=""){		//$Owner_cherryboard_id=(int)getFieldValue('cherryboard_id','tbl_app_expert_cherryboard','expertboard_id="'.$expertboard_id.'" and main_board="1" limit 1');
				if($cherryboard_id>0){
					$TotalCheers=countCheers($expertboard_id,'expertboard');
					$giftCnt.='
					<div class="w2 h1 masonry-brick">
					<div class="bottom_box_main">
					<div class="main_box">
						<div class="day_img">
						<a href="expert_cherryboard.php?cbid='.$cherryboard_id.'">
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
	</div>

   <div style="padding-bottom:50px;"></div>
<!-- START SEND REQUEST TO TELL A STORY CODE -->
<form action="" method="post" name="frmsndrequest" enctype="multipart/form-data">
<div style="display: none; position: fixed; opacity: 1; z-index: 11000; left: 50%; margin-left: -330px; top: 100px; width:500px;border:5px solid #000000;" id="sendRequest" class="popup_div">
		<a class="modal_close" href="#" title="close"></a>	
		<div class="msg_red" id="div_frm_sndmsg"></div>
		<div id="div_send_request">
			<div align="center" class="email_header">Send Request</div><br>
			<span style="padding-left:20px;"><strong>Email</strong>:
			<input type="text" style="width:380px;margin-left:25px;" name="email_id" id="email_id" onblur="if(this.value=='') this.value='Enter Email';" onfocus="if(this.value=='Enter Email') this.value='';" value="Enter Email" /></span><br><br>
			<span style="padding-left:20px;"><strong>Subject</strong>:
			<input type="text" style="width:380px;margin-left:10px;" name="subject" id="subject" onblur="if(this.value=='') this.value='Enter Subject';" onfocus="if(this.value=='Enter Subject') this.value='';" value="Enter Subject" /></span><br><br>
			<table><tr>
			<td valign="top" style="padding-left:15px;"><strong>Message</strong>:</td>
			<td><textarea style="width:380px;" rows="8" name="message" id="message" onblur="if(this.value=='') this.value='Enter Message';" onfocus="if(this.value=='Enter Message') this.value='';">Enter Message</textarea>
			</td></tr></table>
			<br>
			<input type="button" style="margin-left:210px;" class="btn_small" id="btnsend" onClick="ajax_action('sendStoryRequest','div_send_request','email_id='+document.getElementById('email_id').value+'&subject='+document.getElementById('subject').value+'&message='+document.getElementById('message').value+'&user_id=<?=USER_ID?>');" value="Send" name="btnsend" />
		</div>
</div>
</form>
<!-- END SEND REQUEST TO TELL A STORY CODE -->
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