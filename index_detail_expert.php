<?php
	include_once "fbmain.php";
	include('include/app-common-config.php');
?>
<?php include('site_header.php');
if($_GET['msg']=="addche"){
	$msg="Cherryboard added successfully.";
}
?>	
<!--Body Start-->
<div id="body_container" style="padding-top:100px;">
	<div class="wrapper">
      <div id="checklist">
      	<div align="center">
        	<img src="<?php echo PHOTO_URL;?>" width="100" height="100" class="profile_img_big1">
			<br>
   	        <br>
   	        <span class="head_20"><strong><?php echo FIRST_NAME;?> <?php echo LAST_NAME;?></strong></span><br><?php echo LOCATION;?></div><br>
        <div class="feed_comment"><strong>Expert in</strong> healthy eating,<br>dancing, traveling</div>
              <div class="feed_comment"><strong>Help with</strong> family, career,<br>friendships</div>              
              <br>
              <div align="center"><a href="#" class="blue_btn_small">Follow</a></div>
              <br>
              <br>
      <div class="right">55 following</div> 12 followers</div>
	  <div id="right_container">
      <?php
	  $selCherry=mysql_query("select * from tbl_app_expert_cherryboard");//where user_id=".USER_ID
	  if(mysql_num_rows($selCherry)>0){
	  	$cnt=1;
	  	while($selCherryRow=mysql_fetch_array($selCherry)){
			$cherryboard_id=$selCherryRow['cherryboard_id'];
			$cherryboard_title=ucwords($selCherryRow['cherryboard_title']);
			
			$selphoto=mysql_query("select * from tbl_app_expert_cherry_photo where cherryboard_id=".$cherryboard_id." order by photo_id desc");
			$phtotoArray=array();
			if(mysql_num_rows($selphoto)>0){
				while($selphotoRow=mysql_fetch_array($selphoto)){
					$photo_name=$selphotoRow['photo_name'];
					$photoPath='images/cherryboard/'.$photo_name;
					if(is_file($photoPath)){
						$phtotoArray[]=$photoPath;
					}
				}	
			}else{
				$phtotoArray[]='images/cherryboard/no_image.jpg';
			}		
	  ?>
		<div class="field_container">
        	<?php //echo '<img src="images/tag_expert.png" width="80" height="71" class="tag_expert">'; ?>
        	<div align="center"><strong><a href="cherryboard_expert.php?cbid=<?php echo $cherryboard_id;?>" style="text-decoration:none;color:#000000"><?php echo $cherryboard_title;?></a></strong></div><br>
            <img src="<?php echo $phtotoArray[0];?>" height="195" width="195"><br><br>
			<?php
			for($i=1;$i<count($phtotoArray);$i++){
			?>
			<img src="<?php echo $phtotoArray[$i];?>" class="img_thumb" style="margin: 0 3px 0 0;">
			<?php } ?>
	        <div align="center"><a href="#" class="blue_btn_small">Follow</a><img src="images/img_day_thumb2.jpg" width="30" height="30" class="img_small_1"></div><br>
	   </div>
     <?php 
	 		$cnt++;
	 	}
	 } ?>     
      </div>
	  <div class="clear"></div>        
  </div>
</div>

<!--Gray body End-->
<!--Body End-->
<?php include('site_footer.php');?>