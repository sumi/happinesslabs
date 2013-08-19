<?php 
include("fbmain.php");
include('include/app-common-config.php');
?>
<?php include('site_header.php'); ?>
<?php
$ErrMsg='';
$SucMsg='';
if(isset($_POST['cherryboard_key'])){
	$cherryboard_key=$_POST['cherryboard_key'];
	$category_id=$_POST['category_id'];
	if($cherryboard_key!=""){
		$resolution_title=trim($_POST['resolution_title']);
		$makeover=trim($_POST['makeover']);
		$qualified=trim($_POST['qualified']);
		$help_people=trim($_POST['help_people']);
		$price=trim($_POST['price']);
		if($resolution_title!=""){
			$cherryboard_id=(int)getFieldValue('cherryboard_id','tbl_app_expert_cherryboard','cherryboard_title="'.$resolution_title.'"');
			if($cherryboard_id==0){

				$insRes="INSERT INTO `tbl_app_expert_cherryboard` (`cherryboard_id`, `user_id`, `cherryboard_title`,category_id, makeover, qualified, help_people, price) VALUES (NULL, '".USER_ID."', '".addslashes($resolution_title)."', '".$category_id."', '".$makeover."', '".$qualified."', '".$help_people."', '".$price."')";
				$insSql=mysql_query($insRes);
				$cherryboard_id=mysql_insert_id();
				if($cherryboard_id>0){
					$selTemp=mysql_query("select * from tbl_app_temp_expert_cherryboard_meb where cherryboard_key='".$cherryboard_key."'");
					while($selTempRow=mysql_fetch_array($selTemp)){
						$req_user_fb_id=$selTempRow['req_user_fb_id'];
						$meb_id=$selTempRow['meb_id'];
						$request_ids=$selTempRow['request_ids'];
						$insMeb="INSERT INTO `tbl_app_expert_cherryboard_meb` (`meb_id`, `cherryboard_id`, `user_id`, `req_user_fb_id`,request_ids,`is_accept`) VALUES (NULL, '".$cherryboard_id."', '".$user_id."', '".$req_user_fb_id."', '".$request_ids."', '0')";
						$insMebSql=mysql_query($insMeb);
						if($insMebSql>0){
							$delMeb=mysql_query("delete from tbl_app_temp_expert_cherryboard_meb where meb_id=".$meb_id);
						}
					}

					//START FB Created Album
						$facebook->setFileUploadSupport(true);
						//Create an album
						$album_details = array(
							'message'=> 'New expert album in 30daysnew!!',
							'name'=> ucwords($resolution_title)
						);
						$create_album = $facebook->api('/me/albums', 'post', $album_details); 
						$fbAlbumId=$create_album['id'];
						$update_albumid=mysql_query("update tbl_app_expert_cherryboard set fb_album_id='".$fbAlbumId."' where cherryboard_id=".$cherryboard_id);
					//END FB Created Album	
					?>
					<script>document.location='expert_cherryboard.php?cbid=<?php echo $cherryboard_id;?>';</script>
					<?php
				}
			}else{
				$ErrMsg='Expertboard is already exist';
			}	
		}else{
			$ErrMsg='Plese Enter Expertboard';
		}
	}
}


?>
<form action="" method="post" name="frmAddCherry">
<input type="hidden" name="cherryboard_key" id="cherryboard_key" value="<?php echo rand();?>" />
<input type="hidden" name="cherryboard_id" id="cherryboard_id" value="0" />
 
<div id="wrapper">
	<div class="wrapper_600"><div align="center" class="head_20">Thanks <?=FIRST_NAME?> for lending your expertise to others!</div>
	  <ol>
	    <li>Choose the category in which you are an expert in<br>
	      <br>
	        <label>
	        <?php echo getCategoryList();?>
          </label>
              <br>
              <br>
        </li>
        <li>Create a title for your expertboard for others to see<br>
          <br>
          <label>
          <input name="resolution_title" id="resolution_title" type="text" class="input_450">
		  <br></label>
          &nbsp;<br>
          <br>
        </li>
		 <li>Describe your 30day makeover<br>
          <br>
          <label>
		  <textarea name="makeover" id="makeover" class="input_450"></textarea>
		  <br></label>
          &nbsp;<br>
          <br> <br> <br>
        </li>
		 <li>Describe why you are qualified for this 30day make over?<br>
          <br>
          <label>
		  <textarea name="qualified" id="qualified" class="input_450"></textarea>
          <br></label>
          &nbsp;<br>
         <br> <br> <br>
        </li>
		 <li>Describe how your 30day makeover can help people?<br>
          <br>
          <label>
		   <textarea name="help_people" id="help_people" class="input_450"></textarea>
		  <br></label>
          &nbsp;<br>
          <br> <br> <br>
        </li>
		 <li>Price<br>
          <br>
          <label>
          <input name="price" id="price" type="text" class="input_200">
		  <br></label>
          &nbsp;<br>
          <br>
        </li>
	    <li>Invite your top followers to see your inspirational updates<br>
			<div id="div_goal_recent_followers" style="padding-top:5px"></div><br><br>
			<a href="#" class="gray_link" id="invite_frnd">+Add followers</a>
	    </li>
	  </ol>
      <br>
  <div class="right">
    <label>
    <input name="Reset" type="button" class="btn_small" id="button" onclick="javascript:document.location='index_detail.php';" value="Cancel">
    </label> 
&nbsp;
<label>
<input name="button2" type="button" class="btn_small" id="button2" onclick="javascript:save_add_cherryboard();" value="Create my Expertboard!">
</label>
</div>
<div class="clear"></div>
  </div>
<div class="clear"></div>
</div> 
</form> 
<?php include('fb_expert_invite.php');?>
<?php include('site_footer.php');?>