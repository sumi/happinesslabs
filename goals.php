<?php
include_once "fbmain.php";
include('include/app-common-config.php');
$cherryboard_id=$_GET['cbid'];
?>
<?php include('site_header.php');?>
<!--Body Start-->
	<div id="wrapper">
	<div id="div_goal_">
	<?php
	
	$sel=mysql_query("select cherryboard_id,user_id,cherryboard_title from tbl_app_cherryboard  order by cherryboard_id");
	
	$Cnt='';
	if(mysql_num_rows($sel)>0){
		while($selRow=mysql_fetch_array($sel)){
			$user_id=$selRow['user_id'];
			$cherryboard_title=ucwords($selRow['cherryboard_title']);
			$cherryboard_id=ucwords($selRow['cherryboard_id']);
			$userDetailArr=getFieldsValueArray('first_name,last_name,fb_photo_url,location','tbl_app_users','user_id='.$user_id);
			
			$photo_name=getFieldValue('photo_name','tbl_app_cherry_photo','cherryboard_id='.$cherryboard_id.' order by photo_id desc limit 1');
			$photo_path='images/cherryboard/thumb/'.$photo_name;
			if(!is_file($photo_path)){
			 $photo_path='images/cherryboard/no_image.jpg';
			}
			
			$Cnt.='<div class="field_container1">
			  <div align="center">
				<img src="'.$photo_path.'" width="100" height="100" class="profile_img_big1"><br><br>
				<strong><a href="cherryboard.php?cbid='.$cherryboard_id.'" class="head_20" style="text-decoration:none">'.$cherryboard_title.'</a></strong><br>'.ucwords($userDetailArr[3]).'</div><br>
				<div class="feed_comment1"><strong>Created by </strong>'.$userDetailArr[0].' '.$userDetailArr[1].'</div><br>
			 <br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="cherryboard.php?cbid='.$cherryboard_id.'" class="btn_red">View Goal</a><br><br>
		  </div>';
		 
		}
	}else{
		$Cnt.='<strong>No Goal</strong>';
	}
	echo $Cnt;
	?>
	</div>
	<div class="clear"></div>
</div>
<!--Body End-->
<?php include('site_footer.php');?>