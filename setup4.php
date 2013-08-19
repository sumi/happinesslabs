<?php
include_once "fbmain.php";
include('include/app-common-config.php');
?>
<?php include('site_header.php');?>	
<?php
if(count($_SESSION['select_gifts'])>0&&count($_SESSION['select_goals'])>0){

		$chk_gift=$_SESSION['select_gifts'];
		$chk_goals=$_SESSION['select_goals'];
		$chk_checklist=$_SESSION['select_checklist'];
		$cnt=0;
		foreach($chk_goals as $cherryboard_id){
		  if($cherryboard_id>0){
		  	$chk_giftDetail=explode('_',$chk_gift[$cnt]);
			$cat_id=$chk_giftDetail[0];
			$gift_id=$chk_giftDetail[1];
			$chkCatId=getFieldValue('category_id','tbl_app_system_cherryboard','cherryboard_id='.$cherryboard_id);
			//echo "<br>===>".$chkCatId."=====".$cat_id."==".$cherryboard_id;
		  	if($chkCatId==$cat_id){
				//CREATE CHERRYBOARD
				$CherryboardArr=getFieldsValueArray('cherryboard_title','tbl_app_system_cherryboard','cherryboard_id='.$cherryboard_id);
				$resolution_title=$CherryboardArr[0];
				$insRes="INSERT INTO `tbl_app_cherryboard` (`cherryboard_id`, `user_id`, `cherryboard_title`,category_id) VALUES (NULL, '".USER_ID."', '".addslashes($resolution_title)."','".$cat_id."')";
				$insSql=mysql_query($insRes);
				$new_cherryboard_id=mysql_insert_id();
				
				//CREATE GIFT
				$insGift="INSERT INTO `tbl_app_cherry_gift` (`cherry_gift_id`, `gift_id`, `cherryboard_id`, `user_id`, `record_date`) VALUES (NULL, '".$gift_id."', '".$new_cherryboard_id."', '".USER_ID."', CURRENT_TIMESTAMP)";					mysql_query($insGift);
				//CREATE CHECKLIST
				$selChk=mysql_query("select checklist from tbl_app_system_checklist where cherryboard_id=".$cherryboard_id);
				while($selChkRow=mysql_fetch_array($selChk)){
						$insChecklist="INSERT INTO `tbl_app_checklist` (`checklist_id`,user_id, `cherryboard_id`, `checklist`, `record_date`, `is_checked`) VALUES (NULL, '".USER_ID."', '".$new_cherryboard_id."', '".$selChkRow['checklist']."', CURRENT_TIMESTAMP, '0')";
						mysql_query($insChecklist);
				}
				//ADD AS MEMBER
				//$insMeb="INSERT INTO tbl_app_cherryboard_meb (`meb_id`, `cherryboard_id`, `user_id`, `req_user_fb_id`, request_ids, `is_accept`) VALUES (NULL, '".$new_cherryboard_id."', '".USER_ID."', '".FB_ID."','00000', '1')";
				//mysql_query($insMeb);
			}		
		  }	
		 
			$cnt++;
		}
		$update_status=mysql_query("update tbl_app_users set system_page='1' where user_id=".USER_ID);
		//SHARE CREATED GOALBOARD ON FB WALL
		if($update_status){
			//START share into facebook wall
			$category_name=getFieldValue('category_name','tbl_app_category','category_id='.$cat_id);
			$giftPhoto=getFieldValue('gift_photo','tbl_app_gift','gift_id='.$gift_id);
			$giftTitle=getFieldValue('gift_title','tbl_app_gift','gift_id='.$gift_id);
			
			$resolution_title=ucwords($resolution_title);
			//'access_token' =>$_SESSION['fb_access_token']
			$post_wall_array=array('message' => 'Created goal storyboard '.$resolution_title.' to change life in the next 30 days.','name' => $resolution_title,'description' => 'Upload 1 picture a day for 30 days to create beautiful goal storyboard. Win '.$giftTitle.' at the end of 30 days.','caption' => '','picture' => 'http://30daysnew.com/images/gift/'.$giftPhoto,'link' => 'http://30daysnew.com/cherryboard.php?cbid='.$new_cherryboard_id,'properties' => array(array('text' => 'View Goal Storyboard', 'href' => 'http://30daysnew.com/cherryboard.php?cbid='.$new_cherryboard_id),),);
			include('post_fb_wall.php');
			$fb_post_id=0;
			if(isset($_SESSION['fb_post_id'])&&$_SESSION['fb_post_id']!=0){
				$fb_post_id=$_SESSION['fb_post_id'];
			}
			//END share into facebook wall 
				
			//START FB Created Album
				$facebook->setFileUploadSupport(true);
				//Create an album
				$album_details = array(
					'message'=> 'New album '.ucwords($resolution_title).' added into 30daysnew',
					'name'=> ucwords($resolution_title)
				);
				$create_album = $facebook->api('/me/albums', 'post', $album_details); 
				$fbAlbumId=$create_album['id'];
				$update_albumid=mysql_query("update tbl_app_cherryboard set fb_album_id='".$fbAlbumId."',fb_post_id='".$fb_post_id."' where cherryboard_id=".$new_cherryboard_id);
				// Upload a pictures
			//END FB Created Album	
		}
		$msg='<font color="#009966">Gift and Goal updated with user</font>';
		unset($_SESSION['select_gifts']);
		unset($_SESSION['select_goals']);
		unset($_SESSION['select_checklist']);
		
		echo "<script>document.location='cherryboard.php?cbid=".$new_cherryboard_id."';</script>";
		
}

?>
<!--Body Start-->
<div id="wrapper">
	<div class="wrapper_820"><div align="center" class="head_20"><font color="#006600"><?php echo $msg;?></font><br/>Invite friends to help you stick to your goals</div>
	  <br>

              <a class="modal_close" href="#" title="close"></a><span class="red_14"><br>
                </span>
              <div class="gray_box">
               	  <div class="right"><input type="text" value="search friends" class="search_field" onFocus="if(this.value=='search friends') this.value='';" onBlur="if(this.value=='') this.value='search friends';" ></div><br><br><br>
				  <div class="scrollbox">
                   	  <div class="section"><input name="" type="checkbox" value=""><img src="images/img_small1.jpg">Alondra Fliguerooa</div>
                   	  <div class="section"><input name="" type="checkbox" value=""><img src="images/img_small2.jpg">Abby Chapin</div>
                   	  <div class="section"><input name="" type="checkbox" value=""><img src="images/img_small3.jpg">Adam Copeland</div>
                   	  <div class="section"><input name="" type="checkbox" value=""><img src="images/img_small4.jpg">Adam Copeland</div>
                   	  <div class="section"><input name="" type="checkbox" value=""><img src="images/img_small5.jpg">Adam Pecoraro</div>
                   	  <div class="section"><input name="" type="checkbox" value=""><img src="images/img_small6.jpg">Adam Trujilllo</div>
                   	  <div class="section"><input name="" type="checkbox" value=""><img src="images/img_small7.jpg">Adenilyi Harrison</div>
                   	  <div class="section"><input name="" type="checkbox" value=""><img src="images/img_small8.jpg">Adrian Morales</div>
                   	  <div class="section"><input name="" type="checkbox" value=""><img src="images/img_small9.jpg">Adrian S Ulloa</div>
                   	  <div class="section"><input name="" type="checkbox" value=""><img src="images/img_small1.jpg">Adrien De Leener</div>
                   	  <div class="section"><input name="" type="checkbox" value=""><img src="images/img_small2.jpg">Adrienne Katz</div>
                   	  <div class="section"><input name="" type="checkbox" value=""><img src="images/img_small3.jpg">Alda Faz</div>
                   	  <div class="section"><input name="" type="checkbox" value=""><img src="images/img_small4.jpg">Alondra Fliguerooa</div>
                   	  <div class="section"><input name="" type="checkbox" value=""><img src="images/img_small5.jpg">Alondra Fliguerooa</div>
                   	  <div class="section"><input name="" type="checkbox" value=""><img src="images/img_small6.jpg">Alondra Fliguerooa</div>
                  </div>                   
              </div>
              <br>
              <br>
	      <input name="button4" type="submit" class="blue_btn_small right" id="button4" value="Add" title="Add" />
	      </form>
              <br>
	  <br>
	  <div class="clear"></div>
  </div>
<div class="clear"></div>
</div>
<!--Gray body Start-->
<!--Gray body End-->
<!--Body End-->
<!--Footer Start-->
<?php include('site_footer.php');?>