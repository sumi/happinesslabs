<?php
include_once "fbmain.php";	
include('include/app-common-config.php');

//LOGOUT SECTION
if($_GET['tp']=="logout"){
	unset($_SESSION['redirect']);
	unset($_SESSION['FB_ID']);
	unset($_SESSION['USER_ID']);
	define('FB_ID',0);
	session_destroy();
}

//START READ REQUEST and approve goal friend request
if(isset($_GET['request_ids'])){
	if($_GET['request_ids']!=""){
		$request_ids_array=explode(',',$_GET['request_ids']);
		foreach($request_ids_array as $request_ids){
			//approve GOAL board request
			$req_user_fb_id=getFieldsValueArray('req_user_fb_id,is_accept','tbl_app_cherryboard_meb','request_ids="'.$request_ids.'"');
			if($req_user_fb_id[0]!=""&&$req_user_fb_id[1]==0){
				$updSel="update tbl_app_cherryboard_meb set is_accept='1' where request_ids='".$request_ids."'";
				$upd=mysql_query($updSel);
				//delete request id
				if($upd){
				
					$delete_success = $facebook->api('/'.$request_ids,'DELETE');
				
				}
			}
			
			//approve EXPERT board followers
			$req_exp_user_fb_id=getFieldsValueArray('req_user_fb_id,is_accept','tbl_app_expert_cherryboard_meb','request_ids="'.$request_ids.'"');
			if($req_exp_user_fb_id[0]!=""&&$req_exp_user_fb_id[1]==0){
				$updSel="update tbl_app_expert_cherryboard_meb set is_accept='1' where request_ids='".$request_ids."'";
				$upd=mysql_query($updSel);
				//delete request id
				if($upd){
				   $delete_success = $facebook->api('/'.$request_ids,'DELETE');
				}   
			}
		}
	}
}	
//END READ REQUEST and approve goal friend request


if(FB_ID>0){ 
	echo "<script>document.location='index_detail.php';</script>";
}

?>
<?php include('site_header.php');?>
<!--Body Start--> 
   <!-- START MAIN BOTTOM SECTION -->
<?php
//161,160,159,151,150,149,148,141,140,121,119,117,116,114,113,112,110,109,108,107,106,105,
$cnt=0;
$MainSlide='';
$IconSlide='';
$storyPhoto=mysql_query("select cherryboard_id,expertboard_id from tbl_app_expert_cherryboard where cherryboard_id 
in (851) order by cherryboard_id desc");
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
					$MainSlide.='<li><img src="'.$photoPath.'" alt="'.$photoTitle.'" title="'.$photoTitle.'" id="wows1_'.$cnt.'"/></li>';
					$IconSlide.='<a href="#" title="'.$photoTitle.'"><img src="'.$photoPath.'" alt="'.$photoTitle.'"/>'.$cnt.'</a>';
					$MainSlidePhotoArr[$cnt]=$photoPath;
					$cnt++;
				}	
			}
		}	
}		
?>
    <div class="mine_bottom_bg" style="text-align:center">
	<div class="mine_bottom" style="padding-top:10px;padding-bottom:60px">

       <div id="wowslider-container1">
				<div class="ws_images">
					<ul><?=$MainSlide?></ul>
				</div>
				<div class="ws_bullets" style="display:none">
					<div><?=$IconSlide?></div>
				</div>
		</div>
	</div>	
      <div style="clear:both"></div>
    </div>
<!--Body End-->
<script type="text/javascript" src="board_slider/wowslider.js"></script>
<script type="text/javascript" src="board_slider/script.js"></script>
<?php include('site_footer.php');?>