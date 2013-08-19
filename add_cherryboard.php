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
		if($resolution_title!=""){
			$cherryboard_id=(int)getFieldValue('cherryboard_id','tbl_app_cherryboard','cherryboard_title="'.$resolution_title.'"');
			if($cherryboard_id==0){

				$insRes="INSERT INTO `tbl_app_cherryboard` (`cherryboard_id`, `user_id`, `cherryboard_title`,category_id) VALUES (NULL, '".USER_ID."', '".addslashes($resolution_title)."','".$category_id."')";
				$insSql=mysql_query($insRes);
				$cherryboard_id=mysql_insert_id();
				if($cherryboard_id>0){
					$selTemp=mysql_query("select * from tbl_app_temp_cherryboard_meb where cherryboard_key='".$cherryboard_key."'");
					while($selTempRow=mysql_fetch_array($selTemp)){
						$req_user_fb_id=$selTempRow['req_user_fb_id'];
						$meb_id=$selTempRow['meb_id'];
						$request_ids=$selTempRow['request_ids'];
						$insMeb="INSERT INTO `tbl_app_cherryboard_meb` (`meb_id`, `cherryboard_id`, `user_id`, `req_user_fb_id`,request_ids,`is_accept`) VALUES (NULL, '".$cherryboard_id."', '".$user_id."', '".$req_user_fb_id."', '".$request_ids."', '0')";
						$insMebSql=mysql_query($insMeb);
						if($insMebSql){
							$delMeb=mysql_query("delete from tbl_app_temp_cherryboard_meb where meb_id=".$meb_id);
						}	
					}
				
					//SHARE ON FB WALL
					$category_name=getFieldValue('category_name','tbl_app_category','category_id='.$category_id);
					try {
						$ret_obj = $facebook->api('/me/feed', 'POST',
													array(
													  'link' => 'www.30daysnew.com',
													  'message' => 'Created new goal storyboard of '.ucwords($resolution_title).' for '.ucwords($category_name),
												 ));
						//echo '<pre>Post ID: ' . $ret_obj['id'] . '</pre>';
					  }catch(FacebookApiException $e) {
						
					  }   
				
					//DELETE MEB FROM TEMP TABLE
					?>
					 <script type="text/javascript">document.location='cherryboard.php?cbid=<?php echo $cherryboard_id;?>';</script>
					<?php
				
				}
				
			}else{
				$ErrMsg='Cherryboard is already exist';
			}	
		}else{
			$ErrMsg='Plese Enter Cherryboard';
		}
	}
}


?>
<form action="" method="post" name="frmAddCherry">
<input type="hidden" name="cherryboard_key" id="cherryboard_key" value="<?php echo rand();?>" />
<input type="hidden" name="cherryboard_id" id="cherryboard_id" value="0" />
<!-- 
<div id="wrapper_main" align="center">
    	<div id="white_container">
          <h2 align="center">Hello there Andrea! Let's start inspiring each other!</h2>
		  <ol>
            <li>
              <br>
			  <textarea rows="4" name="resolution_title" class="textarea" onblur="if(this.value=='') this.value='enter a resolution, goal, or idea (ie get healthy or stay connected with my family)';" onfocus="if(this.value=='enter a resolution, goal, or idea (ie get healthy or stay connected with my family)') this.value='';" id="resolution_title">enter a resolution, goal, or idea (ie get healthy or stay connected with my family)</textarea>
              <div align="right">50 characters</div>
              <br>
            </li>
            <li><strong> Invite 10 cherryleaders to cheer you on!</strong><br><br>
              <a href="#" id="invite_frnd" class="btn_darkred" title="Add Cherryleaders">Add Cherryleaders</a>
            </li>
          </ol>
          <br>
<br>

          <a href="#" class="blue_btn_small right" onclick="javascript:save_add_cherryboard();">Create my Cherryboard!</a><br>
<br>
<br>
<br>
<br>
          <br>
         </div>
        <div class="clear"></div>
 </div> -->
 
<div id="wrapper">
	<div class="wrapper_600"><div align="center" class="head_20">Hello there <?=FIRST_NAME?>! Let's start inspiring each other!</div>
	  <ol>
	    <li>Choose the type of goal you'd like us to help you accomplish!<br>
	      <br>
	        <label>
	        <?php echo getCategoryList();?>
          </label>
              <br>
              <br>
        </li>
        <li>Enter Goal Name<br>
          <br>
          <label>
          <input name="resolution_title" id="resolution_title" type="text" class="input_450">
		  <br></label>
          &nbsp;<br>
          <br>
        </li>
	    <li>Invite your top friends and family to cheer you on!<br>
    <br>
      <a href="#" id="invite_frnd" class="btn_small_gray">Add friends</a>
	    </li>
	  </ol>
      <br>
  <div class="right">
    <label>
    <input name="Reset" type="button" class="btn_small" id="button" onclick="javascript:document.location='index_detail.php';" value="Cancel">
    </label> 
&nbsp;
<label>
<input name="button2" type="button" class="btn_small" id="button2" onclick="javascript:save_add_cherryboard();" value="Create my Cherryboard!">
</label>
</div>
<div class="clear"></div>
  </div>
<div class="clear"></div>
</div> 
</form> 
<?php include('fb_invite.php');?>
<?php include('site_footer.php');?>