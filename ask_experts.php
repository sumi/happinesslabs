<?php //jquery resize image to fit div
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
			  <div class="todolist_left_1" style="font-size:21px;"><a href="customer_happy_story.php">Create story for happy mind</a></div>
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
	$sel=mysql_query("SELECT a.*,b.cherryboard_id FROM tbl_app_expertboard a,tbl_app_expert_cherryboard b WHERE ".$whereCnd." ORDER BY expertboard_id");					
	$pagePhotosArray=array();
	if(mysql_num_rows($sel)>0){
		$newCnt=1;
		$subCnt=1;
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
			//START GET DATE FORMATE
			$record_date=date_create($row['record_date']);
			$created_date=date_format($record_date,'m/d/Y');
			//START LIKE AND UNLIKE BUTTON CODE
			$likeCnt='';
			$isLike=(int)getFieldValue('is_like','tbl_app_expertboard_likes','cherryboard_id='.$cherryboard_id.' AND user_id='.USER_ID);
		   $likeCnt.='<div id="div_like_'.$cherryboard_id.'" style="margin-top:11px;">					   	
					   <a rel="leanModal" href="#sendMailContent" onclick="setStoryVal('.$cherryboard_id.')" title="Email"><img src="images/send-email-button.jpg" style="margin-left:5px;vertical-align:top;" alt="Email" width="58" height="20"/></a>
						<a title="Share On Facebook" href="download.php?cherryboard_id='.$cherryboard_id.'&type=fbshare" target="_blank"><img src="images/fb_share_btn.png" style="margin-left:5px;vertical-align:top;" alt="Share on Facebook" width="58" height="20"/></a>	
					   <a href="download.php?cherryboard_id='.$cherryboard_id.'&type=download" title="Download"  target="_blank" style="text-decoration:none;margin-left:2px;margin-right:0px;float:none;vertical-align:top;" class="button">Download</a>';
			if($isLike==1){
				$like_id=(int)getFieldValue('like_id','tbl_app_expertboard_likes','cherryboard_id='.$cherryboard_id.' AND is_like="1" AND user_id='.USER_ID);
				if($like_id>0){
				$likeCnt.='<img src="images/set_like.png" height="35px" width="35px" title="Like" />&nbsp;<a href="javascript:void(0);" onclick="ajax_action(\'unlike_story\',\'div_like_'.$cherryboard_id.'\',\'cherryboard_id='.$cherryboard_id.'&user_id='.(int)USER_ID.'&like_id='.$like_id.'\');" title="Unlike"><img src="images/unlike.png" height="35px" width="35px" title="Unlike" /></a>';
				}
			}else if($isLike==2){
				$like_id=(int)getFieldValue('like_id','tbl_app_expertboard_likes','cherryboard_id='.$cherryboard_id.' AND is_like="2" AND user_id='.USER_ID);
				if($like_id>0){
				$likeCnt.='<a href="javascript:void(0);" onclick="ajax_action(\'like_story\',\'div_like_'.$cherryboard_id.'\',\'cherryboard_id='.$cherryboard_id.'&user_id='.(int)USER_ID.'&like_id='.$like_id.'\');" title="Like"><img src="images/like.png" height="35px" width="35px" title="Like" /></a>&nbsp;<img src="images/set_unlike.png" height="35px" width="35px" title="Unlike" />';
				}
			}else{
				$likeCnt.='<a href="javascript:void(0);" onclick="ajax_action(\'like_story\',\'div_like_'.$cherryboard_id.'\',\'cherryboard_id='.$cherryboard_id.'&user_id='.(int)USER_ID.'\');" title="Like"><img src="images/like.png" height="35px" width="35px" title="Like" /></a>&nbsp;<a href="javascript:void(0);" onclick="ajax_action(\'unlike_story\',\'div_like_'.$cherryboard_id.'\',\'cherryboard_id='.$cherryboard_id.'&user_id='.(int)USER_ID.'\');" title="Like"><img src="images/unlike.png" height="35px" width="35px" title="Unlike" /></a>';
			}
			$likeCnt.='</div>';
			//START SELECT STORY PHOTO CODE
			$expertPicPath='https://graph.facebook.com/'.$userOwnerFbId.'/picture?type=large';
			$ownerPic='https://graph.facebook.com/'.$userOwnerFbId.'/picture?type=small';
			$selPhoto=mysql_query("SELECT photo_name FROM tbl_app_expert_cherry_photo WHERE cherryboard_id=".$cherryboard_id." GROUP BY photo_day ORDER BY photo_id DESC");
		    $photoArray=array();
		    if(mysql_num_rows($selPhoto)>0){
			    while($selPhotoRow=mysql_fetch_array($selPhoto)){
					$photo_name=$selPhotoRow['photo_name'];
					$photoPath='images/expertboard/'.$photo_name;
					if(is_file($photoPath)){
						$photoArray[]=$photoPath;
					}		
			    }		
		    }else{
				$photoArray[]=$expertPicPath;
		    }
			//CHECK ARRAY EMPTY OR NOT
			$photoArray=array_filter($photoArray);
			if(empty($photoArray)){
				$photoArray[]=$expertPicPath;
			}
			$arrayLength=count($photoArray);			
		    for($i=0;$i<$arrayLength;$i++){
			   $idCnt=$newCnt.'_'.$cherryboard_id;
			   $pagePhotosArray[$idCnt][$subCnt]=$photoArray[$i];
			   $subCnt++;
		    }  			
			if($userOwnerFbId!=""){
			   if($cherryboard_id>0){
			      $giftCnt.='<div class="w2 h1 masonry-brick">
				  <div class="bottom_box_main" style="margin-bottom:15px;" data-tooltip="sticky'.$newCnt.'">
				  <div class="top_main_book"></div>
					  <div class="bottom_main_book">
					  <div class="img_box_book"><a href="expert_cherryboard.php?cbid='.$cherryboard_id.'">
					  <img src="'.$ownerPic.'" height="30" width="25"/></a></div>
					  <div class="text_Life_Story_Book">Life Story Book</div>
					  <div class="name_text">'.$userName.'</div>
					  <div class="story_title_text">Story title : '.$expertboard_title.'</div>
					  <div class="story_title_text">Number of '.$DayType.'&nbsp;:&nbsp;'.$goal_days.' </div>
					  <div class="story_title_text">Price :&nbsp;'.$price.'</div>
					  <div class="story_title_text">Created date :&nbsp;'.$created_date.'</div>
					  </div>
				 </div>
			     </div>';
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
   
   <div id="mystickytooltip" class="stickytooltip" style="overflow:scroll;height:450px;">
   <div class="stickystatus"></div>
   <?php   
   $pagePhotoEffect='';
   foreach($pagePhotosArray as $photoCnt=>$subPhotoArray){
		$array_cherry=explode('_',$photoCnt);
		$photoCnt=$array_cherry[0];
		$cherryboard_id=$array_cherry[1];
		$tagDetails=getTagDetails($cherryboard_id);
		$pagePhotoEffect.='<div id="sticky'.$photoCnt.'" class="atip">';
		$pagePhotoEffect.='<div style="float:right;border:2px solid #131d1d; padding:8px; background-color:#FFFFFF;">'.$tagDetails.'</div>';
		$pagePhotoEffect.='<div style="float:left;background-color:#FFFFFF;">';
		$imgCnt=1;
		foreach($subPhotoArray as $subCnt=>$photoUrl){
			$imgData=getResizeImgRatio($photoUrl,250,250);
			$NewWidth=$imgData['width'];
			$NewHeight=$imgData['height'];	 		
			$pagePhotoEffect.='<img src="'.$photoUrl.'" height="'.$NewHeight.'" width="'.$NewWidth.'" />';
			if($imgCnt==2){
			   $pagePhotoEffect.='<br/>';
			   $imgCnt=0;
			}
			$imgCnt++;
		}
		$pagePhotoEffect.='</div>';
		$pagePhotoEffect.='</div>';
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
<!-- START SEND EMBEDDED IMAGE MAIL CODE -->
<form action="download.php?type=email" method="post" name="frmsndmail" enctype="multipart/form-data" onSubmit="return ValidateForm();">
<div style="display:none; position: fixed; opacity: 1; z-index: 11000; left: 50%; margin-left: -330px; top: 100px; width:500px;border:5px solid #000000;" id="sendMailContent" class="popup_div">
		<a class="modal_close" href="javascript:return false;" onclick= title="close"></a>
		<div align="center" class="email_header">Send Email</div><br>
		<span style="padding-left:20px;"><strong>Email Id</strong>:
		<input type="text" style="width:380px;margin-left:25px;" name="email_id" id="email_id"/></span>
		<br><br>	
		<input type="hidden" name="story_id" id="story_id" value="" />	
		<input type="submit" style="margin-left:221px;" class="btn_small" id="btnsend" value="Send"
		name="btnsend"/>
</div>
</form>
<!-- END SEND EMBEDDED IMAGE MAIL CODE -->
<script src="js/masonry.js"></script>
<script>
  window.onload = function() {    
    var miniWall = new Masonry(document.getElementById('mini-container'), {
      columnWidth: 20,
      foo: 'bar'
    });        
  };
</script>
<script type="text/javascript">
function setStoryVal(story_id){
    document.getElementById('story_id').value=story_id;
}

function echeck(str){
	var at="@"
	var dot="."
	var lat=str.indexOf(at)
	var lstr=str.length
	var ldot=str.indexOf(dot)
	if(str.indexOf(at)==-1){
	   alert("Invalid E-mail ID")
	   return false
	}
	if(str.indexOf(at)==-1||str.indexOf(at)==0||str.indexOf(at)==lstr){
	   alert("Invalid E-mail ID")
	   return false
	}
	if(str.indexOf(dot)==-1||str.indexOf(dot)==0||str.indexOf(dot)==lstr){
		alert("Invalid E-mail ID")
		return false
	}
	if(str.indexOf(at,(lat+1))!=-1){
		alert("Invalid E-mail ID")
		return false
	}
	if(str.substring(lat-1,lat)==dot || str.substring(lat+1,lat+2)==dot){
		alert("Invalid E-mail ID")
		return false
	}
	if(str.indexOf(dot,(lat+2))==-1){
		alert("Invalid E-mail ID")
		return false
	}		
	if(str.indexOf(" ")!=-1){
		alert("Invalid E-mail ID")
		return false
	}
	return true					
}
function ValidateForm(){
	var emailID=document.frmsndmail.email_id		
	if((emailID.value==null)||(emailID.value=="")){
		alert("Please Enter your Email ID")
		emailID.focus()
		return false
	}
	if(echeck(emailID.value)==false){
		emailID.value=""
		emailID.focus()
		return false
	}
	return true
}
</script>
<?php
/*foreach($pagePhotosArray as $photoCnt=>$subPhotoArray){
	echo "<br/>======>".$photoCnt;
	foreach($subPhotoArray as $subCnt=>$photoUrl){
		echo "<br/>Sub :".$subCnt;
		echo '<img src="'.$photoUrl.'" height="50" width="50" /><br/>';
	}
}*/
?>
<?php include('fb_invite.php');?>
<?php include('site_footer.php');?>