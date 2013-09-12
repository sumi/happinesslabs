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
			//START LIKE AND UNLIKE BUTTON CODE
			$likeCnt='';
			$isLike=(int)getFieldValue('is_like','tbl_app_expertboard_likes','cherryboard_id='.$cherryboard_id.' AND user_id='.USER_ID);
			$likeCnt.='<div id="div_like_'.$cherryboard_id.'" style="margin-top:11px;">
					   <a href="download.php?cherryboard_id='.$cherryboard_id.'" title="Download" target="_blank" style="text-decoration:none;margin-left:55px;margin-right:5px;float:none;vertical-align:top;" class="button">Download</a>';
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
		    $photoCnt=0;
		    if(mysql_num_rows($selPhoto)>0){
			    while($selPhotoRow=mysql_fetch_array($selPhoto)){
					$photo_name=$selPhotoRow['photo_name'];
					$photoPath='images/expertboard/'.$photo_name;
					if(is_file($photoPath)){
						$photoArray[]=$photoPath;
						$photoCnt++;
					}
					//if($photoCnt==4){break;}		
			    }		
		    }else{
				$photoArray[]=$expertPicPath;
				$photoCnt=1;
		    }
			//CHECK ARRAY EMPTY OR NOT
			$photoArray=array_filter($photoArray);
			if(empty($photoArray)){
				$photoArray[]=$expertPicPath;
				$photoCnt=1;
			}
			$arrayLength=count($photoArray);
			//START MERGE IMAGE SECTION
			$imgMergeCnt='';					
			if($_SERVER['SERVER_NAME']=="localhost"){
				if($photoCnt==1){				
					$imgMergeCnt.='<div class="single_div">
					<a href="expert_cherryboard.php?cbid='.$cherryboard_id.'"><img src="'.$photoArray[0].'" height="150px" width="209px" data-tooltip="sticky'.$newCnt.'"/></a>
					</div>';
					$idCnt=$newCnt.'_'.$cherryboard_id;
					$pagePhotosArray[$idCnt][$subCnt]=$photoArray[0];
		  			$subCnt++;
				}else if($photoCnt==2){
					$imgMergeCnt.='<div class="half_div_left"><a href="expert_cherryboard.php?cbid='.$cherryboard_id.'"><img src="'.$photoArray[0].'" height="150px" width="102px" data-tooltip="sticky'.$newCnt.'"/></a></div>';		
					$idCnt=$newCnt.'_'.$cherryboard_id;
					$pagePhotosArray[$idCnt][$subCnt]=$photoArray[0];
		  			$subCnt++;			
					$imgMergeCnt.='<div class="half_div_right"><a href="expert_cherryboard.php?cbid='.$cherryboard_id.'"><img src="'.$photoArray[1].'" height="150px" width="102px" data-tooltip="sticky'.$newCnt.'"/></a></div>';
					$idCnt=$newCnt.'_'.$cherryboard_id;
					$pagePhotosArray[$idCnt][$subCnt]=$photoArray[1];
		  			$subCnt++;
				}else if($photoCnt==3){
					$imgMergeCnt.='<div class="single_div_half"><a href="expert_cherryboard.php?cbid='.$cherryboard_id.'"><img src="'.$photoArray[0].'" height="75px" width="209px" data-tooltip="sticky'.$newCnt.'"/></a></div>';		
					$idCnt=$newCnt.'_'.$cherryboard_id;
					$pagePhotosArray[$idCnt][$subCnt]=$photoArray[0];
		  			$subCnt++;				
					$imgMergeCnt.='<div class="half_div_left" style="height:75px;"><a href="expert_cherryboard.php?cbid='.$cherryboard_id.'"><img src="'.$photoArray[1].'" height="75px" width="102px" data-tooltip="sticky'.$newCnt.'"/></a></div>';
					$idCnt=$newCnt.'_'.$cherryboard_id;
					$pagePhotosArray[$idCnt][$subCnt]=$photoArray[1];
		  			$subCnt++;
					$imgMergeCnt.='<div class="half_div_right" style="height:75px;"><a href="expert_cherryboard.php?cbid='.$cherryboard_id.'"><img src="'.$photoArray[2].'" height="75px" width="102px" data-tooltip="sticky'.$newCnt.'"/></a></div>';
					$idCnt=$newCnt.'_'.$cherryboard_id;
					$pagePhotosArray[$idCnt][$subCnt]=$photoArray[2];
		  			$subCnt++;
				}else{
					$imgMergeCnt.='<div class="single_div_half"><a href="expert_cherryboard.php?cbid='.$cherryboard_id.'"><img src="'.$photoArray[0].'" height="75px" width="209px" data-tooltip="sticky'.$newCnt.'"/></a></div>';		
					$idCnt=$newCnt.'_'.$cherryboard_id;
					$pagePhotosArray[$idCnt][$subCnt]=$photoArray[0];
		  			$subCnt++;
					$imgMergeCnt.='<div class="half_div_left" style="height:75px;width:68px;"><a href="expert_cherryboard.php?cbid='.$cherryboard_id.'"><img src="'.$photoArray[1].'" height="75px" width="68px" data-tooltip="sticky'.$newCnt.'""/></a></div>';
					$idCnt=$newCnt.'_'.$cherryboard_id;
					$pagePhotosArray[$idCnt][$subCnt]=$photoArray[1];
		  			$subCnt++;
					$imgMergeCnt.='<div class="half_div_left" style="height:75px;width:67px;"><a href="expert_cherryboard.php?cbid='.$cherryboard_id.'"><img src="'.$photoArray[2].'" height="75px" width="67px" data-tooltip="sticky'.$newCnt.'"/></a></div>';
					$idCnt=$newCnt.'_'.$cherryboard_id;
					$pagePhotosArray[$idCnt][$subCnt]=$photoArray[2];
		  			$subCnt++;
					$imgMergeCnt.='<div class="half_div_right" style="height:75px;width:68px;"><a href="expert_cherryboard.php?cbid='.$cherryboard_id.'"><img src="'.$photoArray[3].'" height="75px" width="68px" data-tooltip="sticky'.$newCnt.'"/></a></div>';
					$idCnt=$newCnt.'_'.$cherryboard_id;
					$pagePhotosArray[$idCnt][$subCnt]=$photoArray[3];
		  			$subCnt++;
					if($photoCnt>4){
					   for($i=4;$i<$arrayLength;$i++){
						   $idCnt=$newCnt.'_'.$cherryboard_id;
						   $pagePhotosArray[$idCnt][$subCnt]=$photoArray[$i];
						   $subCnt++;
					   }
					}
				}
			}else{ //START IMAGE MEGIC SECTION
				//getResizeImgRatio(imagepath,width,height);
				if($photoCnt==1){	
					$imgData=getResizeImgRatio($photoArray[0],209,150);
					$NewWidth=$imgData['width'];
					$NewHeight=$imgData['height'];			
					$imgMergeCnt.='<div class="single_div" style="border: 1px solid #000000;">
					<a href="expert_cherryboard.php?cbid='.$cherryboard_id.'"><img src="'.$photoArray[0].'" height="'.$NewHeight.'" width="'.$NewWidth.'" data-tooltip="sticky'.$newCnt.'"/></a>
					</div>';
					$idCnt=$newCnt.'_'.$cherryboard_id;
					$pagePhotosArray[$idCnt][$subCnt]=$photoArray[0];
		  			$subCnt++;
				}else if($photoCnt==2){
					$imgData=getResizeImgRatio($photoArray[0],102,150);
					$NewWidth=$imgData['width'];
					$NewHeight=$imgData['height'];
					$imgMergeCnt.='<div class="half_div_left"><a href="expert_cherryboard.php?cbid='.$cherryboard_id.'"><img src="'.$photoArray[0].'" height="'.$NewHeight.'" width="'.$NewWidth.'" data-tooltip="sticky'.$newCnt.'"/></a></div>';
					$idCnt=$newCnt.'_'.$cherryboard_id;
					$pagePhotosArray[$idCnt][$subCnt]=$photoArray[0];
		  			$subCnt++;
					$imgData1=getResizeImgRatio($photoArray[1],102,150);
					$NewWidth1=$imgData1['width'];
					$NewHeight1=$imgData1['height'];
					$imgMergeCnt.='<div class="half_div_right"><a href="expert_cherryboard.php?cbid='.$cherryboard_id.'"><img src="'.$photoArray[1].'" height="'.$NewHeight1.'" width="'.$NewWidth1.'" data-tooltip="sticky'.$newCnt.'"/></a></div>';
					$idCnt=$newCnt.'_'.$cherryboard_id;
					$pagePhotosArray[$idCnt][$subCnt]=$photoArray[1];
		  			$subCnt++;
				}else if($photoCnt==3){
					$imgData=getResizeImgRatio($photoArray[0],209,75);
					$NewWidth=$imgData['width'];
					$NewHeight=$imgData['height'];
					$imgMergeCnt.='<div class="single_div_half"><a href="expert_cherryboard.php?cbid='.$cherryboard_id.'"><img src="'.$photoArray[0].'" height="'.$NewHeight.'" width="'.$NewWidth.'" data-tooltip="sticky'.$newCnt.'"/></a></div>';	
					$idCnt=$newCnt.'_'.$cherryboard_id;
					$pagePhotosArray[$idCnt][$subCnt]=$photoArray[0];
		  			$subCnt++;			
					$imgData1=getResizeImgRatio($photoArray[1],102,75);
					$NewWidth1=$imgData1['width'];
					$NewHeight1=$imgData1['height'];	
					$hVar1=0;
					if($NewHeight1<75){$hVar1=78;}else{$hVar1=75;}			
					$imgMergeCnt.='<div class="half_div_left" style="height:'.$hVar1.'px;"><a href="expert_cherryboard.php?cbid='.$cherryboard_id.'"><img src="'.$photoArray[1].'" height="'.$NewHeight1.'" width="'.$NewWidth1.'" data-tooltip="sticky'.$newCnt.'"/></a></div>';
					$idCnt=$newCnt.'_'.$cherryboard_id;
					$pagePhotosArray[$idCnt][$subCnt]=$photoArray[1];
		  			$subCnt++;
					$imgData2=getResizeImgRatio($photoArray[2],102,75);
					$NewWidth2=$imgData2['width'];
					$NewHeight2=$imgData2['height'];
					$hVar2=0;
					if($NewHeight2<75){$hVar2=78;}else{$hVar2=75;}
					$imgMergeCnt.='<div class="half_div_right" style="height:'.$hVar2.'px;"><a href="expert_cherryboard.php?cbid='.$cherryboard_id.'"><img src="'.$photoArray[2].'" height="'.$NewHeight2.'" width="'.$NewWidth2.'" data-tooltip="sticky'.$newCnt.'"/></a></div>';
					$idCnt=$newCnt.'_'.$cherryboard_id;
					$pagePhotosArray[$idCnt][$subCnt]=$photoArray[2];
		  			$subCnt++;
				}else{
					$imgData=getResizeImgRatio($photoArray[0],209,75);
					$NewWidth=$imgData['width'];
					$NewHeight=$imgData['height'];			
					$imgMergeCnt.='<div class="single_div_half"><a href="expert_cherryboard.php?cbid='.$cherryboard_id.'"><img src="'.$photoArray[0].'" height="'.$NewHeight.'" width="'.$NewWidth.'" data-tooltip="sticky'.$newCnt.'"/></a></div>';
					$idCnt=$newCnt.'_'.$cherryboard_id;
					$pagePhotosArray[$idCnt][$subCnt]=$photoArray[0];
		  			$subCnt++;
					$imgData1=getResizeImgRatio($photoArray[1],68,75);
					$NewWidth1=$imgData1['width'];
					$NewHeight1=$imgData1['height'];
					$hVar1=0;
					if($NewHeight1<75){$hVar1=78;}else{$hVar1=75;}
					$imgMergeCnt.='<div class="half_div_left" style="height:'.$hVar1.'px;width:68px;"><a href="expert_cherryboard.php?cbid='.$cherryboard_id.'"><img src="'.$photoArray[1].'" height="'.$NewHeight1.'" width="'.$NewWidth1.'" data-tooltip="sticky'.$newCnt.'""/></a></div>';
					$idCnt=$newCnt.'_'.$cherryboard_id;
					$pagePhotosArray[$idCnt][$subCnt]=$photoArray[1];
		  			$subCnt++;
					$imgData2=getResizeImgRatio($photoArray[2],67,75);
					$NewWidth2=$imgData2['width'];
					$NewHeight2=$imgData2['height'];
					$hVar2=0;
					if($NewHeight2<75){$hVar2=78;}else{$hVar2=75;}
					$imgMergeCnt.='<div class="half_div_left" style="height:'.$hVar2.'px;width:67px;"><a href="expert_cherryboard.php?cbid='.$cherryboard_id.'"><img src="'.$photoArray[2].'" height="'.$NewHeight2.'" width="'.$NewWidth2.'" data-tooltip="sticky'.$newCnt.'"/></a></div>';
					$idCnt=$newCnt.'_'.$cherryboard_id;
					$pagePhotosArray[$idCnt][$subCnt]=$photoArray[2];
		  			$subCnt++;
					$imgData3=getResizeImgRatio($photoArray[3],67,75);
					$NewWidth3=$imgData3['width'];
					$NewHeight3=$imgData3['height'];
					$hVar3=0;
					if($NewHeight3<75){$hVar3=78;}else{$hVar3=75;}
					$imgMergeCnt.='<div class="half_div_right" style="height:'.$hVar3.'px;width:68px;"><a href="expert_cherryboard.php?cbid='.$cherryboard_id.'"><img src="'.$photoArray[3].'" height="'.$NewHeight3.'" width="'.$NewWidth3.'" data-tooltip="sticky'.$newCnt.'"/></a></div>';
					$idCnt=$newCnt.'_'.$cherryboard_id;
					$pagePhotosArray[$idCnt][$subCnt]=$photoArray[3];
		  			$subCnt++;
					if($photoCnt>4){
					   for($i=4;$i<$arrayLength;$i++){
						   $idCnt=$newCnt.'_'.$cherryboard_id;
						   $pagePhotosArray[$idCnt][$subCnt]=$photoArray[$i];
						   $subCnt++;
					   }
					}
				}		
			}
  			
			if($userOwnerFbId!=""){
				if($cherryboard_id>0){
					$giftCnt.='<div class="w2 h1 masonry-brick">
					<div class="bottom_box_main">
					<div class="main_box">
						<div class="bottom_box_text"><strong>'.$expertboard_title.'</strong><br/></div>
						<div class="main_div">'.$imgMergeCnt.'</div>'.$likeCnt.'												
					   <div class="bottom_healthy">
						 <div class="bottom_healthy_12" style="padding-top:5px;"><strong>By</strong>&nbsp;:&nbsp;<img src="'.$ownerPic.'" height="25px" width="25px"/>&nbsp;'.$userName.'<br/><strong>Price&nbsp;:&nbsp;</strong>'.$price.'&nbsp;<span style="padding-left:85px;">&nbsp;</span><strong>'.$DayType.'&nbsp;:</strong>&nbsp;'.$goal_days.'<br/></div>
					   <div style="clear:both"></div>
					   </div>					   					   
				   </div>
				   <div class="padding"></div>
				   </div></div>';
				   /*$pagePhotosArray[$newCnt]=$photoArray[0];//$expertPicPath*/
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
   foreach($pagePhotosArray as $photoCnt=>$subPhotoArray){
		$array_cherry=explode('_',$photoCnt);
		$photoCnt=$array_cherry[0];
		$cherryboard_id=$array_cherry[1];
		$pagePhotoEffect.='<div id="sticky'.$photoCnt.'" class="atip">';
		//<div style="height:30px;padding-top:3px;"><a href="download.php?cherryboard_id='.$cherryboard_id.'" title="Download" target="_blank" style="text-decoration:none;margin-right:5px;" class="button">Download</a></div>
		$imgCnt=1;
		foreach($subPhotoArray as $subCnt=>$photoUrl){
		  if($subCnt>241&&$subCnt<298){}else{
			$imgData=getResizeImgRatio($photoUrl,250,250);
			$NewWidth=$imgData['width'];
			$NewHeight=$imgData['height'];	 		
			$pagePhotoEffect.='<img src="'.$photoUrl.'" height="'.$NewHeight.'" width="'.$NewWidth.'" />';
			if($imgCnt==2){
			   $pagePhotoEffect.='<br/>';
			   $imgCnt=0;
			}
			//if($imgCnt==4){break;}
			$imgCnt++;	
		  }		
		}
		$pagePhotoEffect.='</div>';
   }
   echo $pagePhotoEffect;
   ?>
   <!--<div class="stickystatus"></div>-->
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
<?php
/*foreach($pagePhotosArray as $photoCnt=>$subPhotoArray){
	echo "<br/>======>".$photoCnt;
	foreach($subPhotoArray as $subCnt=>$photoUrl){
		echo "<br/>Sub :".$subCnt;
		if($subCnt>241&&$subCnt<298){
			echo 'No Image';
		}else{
		echo '<img src="'.$photoUrl.'" height="50" width="50" /><br/>';
		}				
	}
}*/
?>
<?php include('fb_invite.php');?>
<?php include('site_footer.php');?>