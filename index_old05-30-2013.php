<?php
include_once "fbmain.php";	
include('include/app-common-config.php');

if($_GET['tp']=="logout"){
	unset($_SESSION['redirect']);
	unset($_SESSION['FB_ID']);
	unset($_SESSION['USER_ID']);
	define('FB_ID',0);
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
<?php include('site_header.php');
//slide images array
$selSlide=mysql_query("select * from tbl_app_slider order by slider_sequence");
$slideArray=array();
$currentDay=date('j');
$cnt=1;
while($selSlideRow=mysql_fetch_array($selSlide)){
	$slideArray[$cnt]['slider_title']=$selSlideRow['slider_title'];
	$slideArray[$cnt]['slider_image']=$selSlideRow['slider_image'];
	$cnt++;
}
?>
<!--Body Start-->
	<div id="wrapper">
	<div align="center" class="gray_28">Changing lives made easy &amp; fun with 1 picture a day</div>
    <div class="leftside">
    	<img src="images/logo_new.png" width="269" height="119"><br><br><span class="head_20">Post pictures for 30 days, win<br>rewards, and see results</span><br><br>
	    <a href="#" class="btn_blue" onClick="fb_login();">connect with facebook</a><br>
	</div>
    <div class="rightside">
        <section class="slider">
        <div class="flexslider">
          <ul class="slides">
		  	<li><img src="images/slider/<?php echo $slideArray[1]['slider_image'];?>" title="<?php echo $slideArray[1]['slider_title'];?>" width="538" height="380" /></li>
            <li><img src="images/slide2.jpg" title="<?php echo $slideArray[$currentDay]['slider_title'];?>" width="538" height="380" /></li>
 	    	<li><img src="images/slide3.jpg" title="<?php echo $slideArray[1]['slider_title'];?>" width="538" height="380" /></li>
          </ul>
        </div>
      </section>
        </div>
        <div class="clear"></div>
</div>
<!--Body End-->
<?php include('site_footer.php');?>