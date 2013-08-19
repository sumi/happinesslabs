<?php
include_once "fbmain.php";
include('include/app-common-config.php');
$cherryboard_id=$_GET['cbid'];
?>
<?php include('site_header.php');?>
<!--Body Start-->
	<div id="wrapper">
	<div id="div_goal_expert">
	<?php
	// and cherryboard_id=".$cherryboard_id
	$selExpert=mysql_query("select cherryboard_id,user_id,cherryboard_title from tbl_app_expert_cherryboard  order by cherryboard_id");
	//where user_id!=".USER_ID."
	$expertCnt='';
	if(mysql_num_rows($selExpert)>0){
		while($selExpertRow=mysql_fetch_array($selExpert)){
			$user_id=$selExpertRow['user_id'];
			$cherryboard_title=ucwords($selExpertRow['cherryboard_title']);
			$cherryboard_id=ucwords($selExpertRow['cherryboard_id']);
			$userDetailArr=getFieldsValueArray('first_name,last_name,fb_photo_url,location','tbl_app_users','user_id='.$user_id);
			$stringVar="cherryboard_id='+this.value+'&user_id=".$user_id;
			
			$totalFollowers=(int)getFieldValue('count(meb_id)','tbl_app_expert_cherryboard_meb','cherryboard_id='.$cherryboard_id.' and is_accept="1"');
			
			$expertCnt.='<div class="field_container1">
			  <div align="center">
				<img src="'.$userDetailArr[2].'" width="100" height="100" class="profile_img_big1"><br><br>
				<strong><a href="expert_cherryboard.php?cbid='.$cherryboard_id.'" class="head_20" style="text-decoration:none">'.$cherryboard_title.'</a></strong><br>'.ucwords($userDetailArr[3]).'</div><br>
				<div class="feed_comment1"><strong>Expert is </strong>'.$userDetailArr[0].' '.$userDetailArr[1].'</div><br>
			 '.$totalFollowers.' followers<br><br>&nbsp;&nbsp;<a class="blue_btn_small" href="#">&nbsp;Love&nbsp;</a>&nbsp;<a href="#" class="btn_red">Buy</a><br><br>
		  </div>';
		  /*<a href="#" onclick="add_goal_expert(\'add_goal_expert\','.$cherryboard_id.','.$expert_id.')" class="blue_btn_small">Follow</a>*/
		}
	}else{
		$expertCnt.='<strong>No Experts</strong>';
	}
	echo $expertCnt;
	?>
	</div>
	<div class="clear"></div>
</div>
<!--Body End-->
<?php include('site_footer.php');?>