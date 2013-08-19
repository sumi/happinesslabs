<?php
include_once "fbmain.php";
include('include/app-common-config.php');
$cherryboard_id=$_GET['cbid'];
$mainExpCherryId=(int)getFieldValue('cherryboard_id','tbl_app_expert_cherryboard','cherryboard_id="'.$cherryboard_id.'" and main_board="1"');
$expertboard_id=getFieldValue('expertboard_id','tbl_app_expert_cherryboard','cherryboard_id='.$cherryboard_id);
$expOwner_id=getFieldValue('user_id','tbl_app_expertboard','expertboard_id='.$expertboard_id);
$expCreator_id=getFieldValue('user_id','tbl_app_expert_cherryboard','cherryboard_id='.$cherryboard_id);
$DayType=getDayType($expertboard_id);
$checkIsExpertBoard=(int)getFieldValue('cherryboard_id','tbl_app_expert_cherryboard','expertboard_id='.$expertboard_id.' and user_id='.USER_ID);
$expertboard_cehrry_id=getFieldValue('cherryboard_id','tbl_app_expert_cherryboard','expertboard_id='.$expertboard_id.' and main_board="1"');
$msg='';
$sort='asc';

//UPLOAD EXPERT PHOTO
$msg="";
if(isset($_SESSION['insert_photo_id'])){
   //START SHARE PHOTO IN ALBUM
	$AlbumDetail=getFieldsValueArray('fb_album_id,cherryboard_title','tbl_app_expert_cherryboard','cherryboard_id='.$cherryboard_id);
	$AlbumId=$AlbumDetail[0];
	$cherryboard_title=$AlbumDetail[1];
	
	$photoDetail=getFieldsValueArray('photo_title,photo_name','tbl_app_expert_cherry_photo','photo_id='.$_SESSION['insert_photo_id']);
	$comment=stripslashes($photoDetail[0]);
	$photo_name=stripslashes($photoDetail[1]);
	//echo "==>".$_SESSION['insert_photo_id']."==".$comment."==".$photo_name."==".$AlbumId;	
	if($AlbumId!="0"&&$AlbumId!=""){
		unset($_SESSION['insert_photo_id']);
		$facebook->setFileUploadSupport(true);  
		# File is relative to the PHP doc  
		$file = "@".realpath("images/expertboard/temp/".$photo_name);  
		$args = array(  
			'message' => $comment.' - 30DaysNew', 
			"access_token" => $_SESSION['fb_access_token'],  
			"image" => $file  
		);  
		$data = $facebook->api('/'.$AlbumId.'/photos', 'post', $args);
		//echo "Photo Id :".$upload_photo['id']; // The id of your newly uploaded pic.
		$upload_photoID=$data['id'];
	}
	//END SHARE PHOTO IN ALBUM
	$msg="<span class=\"fgreen\">Photo added successfully.</span>";	
}

//START	ADD REWARD CODE
if(isset($_POST['btnAddReward'])){
	$totalReward=(int)$_POST['totalDyndiv'];
	
	for($i=1;$i<=$totalReward;$i++){
		$rewardTitle='reward_title'.$i;
		$reward_title=addslashes(trim($_POST[$rewardTitle]));
		
		$rewardPhoto='reward_photo'.$i;
		$reward_photo= getPhotoName($_FILES[$rewardPhoto]['name']);
		$uploadTempdir = 'images/expertboard/reward/'.$reward_photo; 
		$uploaddir = 'images/expertboard/reward/'.$reward_photo;
		$checkPHoto=(int)getFieldValue($reward_photo,'tbl_app_expert_reward_photo','cherryboard_id='.$cherryboard_id);
		if($reward_title!=''&&$reward_photo!=''&&$checkPHoto==0){
			if(move_uploaded_file($_FILES[$rewardPhoto]['tmp_name'],$uploadTempdir)){					
				$ins_sel="INSERT INTO `tbl_app_expert_reward_photo` (`exp_reward_id`, `user_id`, `cherryboard_id`, `photo_title`, `photo_name`, `record_date`) VALUES (NULL, '".USER_ID."', '".$cherryboard_id."', '".$reward_title."', '".$reward_photo."', CURRENT_TIMESTAMP)";
				$ins_sql=mysql_query($ins_sel);
			}	
		}
	}
}
//END ADD REWARD CODE

//START DELETE EXPERT CODE
$delExpId=(int)$_GET['delExpId'];
if($delExpId>0){
	$delExpertboard=mysql_query("DELETE FROM tbl_app_expertboard WHERE expertboard_id=".$delExpId." and user_id=".USER_ID);
	if($delExpertboard){
	   $delGoalExpertDays=mysql_query("DELETE FROM tbl_app_expertboard_days WHERE expertboard_id=".$delExpId);
	   $cherryboard_id=getFieldValue('cherryboard_id','tbl_app_expert_cherryboard','expertboard_id='.$delExpId);
	   if($cherryboard_id>0){
	   		deleteExpertBoard($cherryboard_id);
	   		echo '<script language="javascript">document.location=\'expertboard.php\'</script>';
	   }
	}
}
//END DELETE EXPERT CODE
//START EDIT EXPERT CODE
if(isset($_POST['btnEditExpert'])){
	$expertboard_title=trim($_POST['title']);
	$expertboard_detail=trim(addslashes($_POST['detail']));
	$expertId=(int)$_POST['expertId'];
	$category_id=(int)$_POST['category_id1'];
	$number_days=(int)$_POST['number_days'];
	$price=$_POST['price'];
	
	if($expertboard_title!=''&&$expertboard_detail!=''&&$category_id>0&&$number_days>0&&trim($price)!=""){
		$editExpBoard="UPDATE tbl_app_expertboard SET 
						category_id= '".$category_id."',
						expertboard_title='".$expertboard_title."',
						expertboard_detail='".$expertboard_detail."',
						goal_days='".$number_days."',
						price='".$price."' WHERE expertboard_id='".$expertId."'";
		$editQry=mysql_query($editExpBoard);
		
		//update goal days with title
		 $totalConfigDays=getFieldValue('count(expertboard_day_id)','tbl_app_expertboard_days','expertboard_id='.$expertId);
		 if($totalConfigDays!=$number_days){
			//when increase days in goal days
			if($number_days>$totalConfigDays){
				for($i=($totalConfigDays+1);$i<=$number_days;$i++){
					$addDays="INSERT INTO `tbl_app_expertboard_days` (`expertboard_day_id`, `expertboard_id`, `day_no`, `day_title`, `record_date`) VALUES (NULL, '".$expertboard_id."', '".$i."', 'Day ".$i."', CURRENT_TIMESTAMP)";
					$addDaysSql=mysql_query($addDays);
				}
			}
			//when decrease days in goal days
			if($number_days<$totalConfigDays){
				for($i=($number_days+1);$i<=$totalConfigDays;$i++){
					
					$delDays="delete from `tbl_app_expertboard_days` where day_no=".$i." and expertboard_id=".$expertboard_id;
					$delDaysSql=mysql_query($delDays);
				}
			}
			
		 }
		
	}
}
//END EDIT EXPERT CODE
//START UPDATE DAY CONFIG CODE
if(isset($_POST['btnAddDays'])){
	$selDays=mysql_query("SELECT * FROM tbl_app_expertboard_days where expertboard_id=".$expertboard_id." ORDER BY expertboard_day_id");
	while($row=mysql_fetch_array($selDays)){
		 $expertboardDayId=$row['expertboard_day_id'];
		 $day_title='day_'.$expertboardDayId;
		 $dayTitle=trim($_POST[$day_title]);
		 $updateDay=mysql_query("UPDATE tbl_app_expertboard_days SET day_title='".$dayTitle."' WHERE expertboard_day_id=".$expertboardDayId);		
	}
}
//END UPDATE DAY CONFIG CODE
?>
<?php
include('site_header.php');

//Expert Cheryboard Detail
	$cherrySel=mysql_query("select * from tbl_app_expert_cherryboard where cherryboard_id=".$cherryboard_id);
	while($cherryRow=mysql_fetch_array($cherrySel)){
		$BuyerDetail=getUserDetail($cherryRow['user_id']);
		$BuyerName=$BuyerDetail['name'];
		$BuyerPic=$BuyerDetail['photo_url'];
	}
	
?>
<div style="background:#FFFFFF; margin:0px auto;">
<div id="wrapper" style="padding-top: 97px;margin: 0 auto 0;">
<?php
 $expertCnt='';
	  $sel_expert=mysql_query("SELECT * FROM tbl_app_expertboard WHERE expertboard_id=".$expertboard_id);
	  while($fetchExpertRow=mysql_fetch_array($sel_expert)){
	  		//$expertboard_id=(int)$row['expertboard_id'];
			$expertboard_title=ucwords(trim($fetchExpertRow['expertboard_title']));
			$expertboard_detail=trim(stripslashes($fetchExpertRow['expertboard_detail']));
			$customers=trim($fetchExpertRow['customers']);
			$category_id=ucwords($cherryRow['category_id']);
			$category_name=getFieldValue('category_name','tbl_app_category','category_id='.$category_id);
			$user_id=(int)$fetchExpertRow['user_id'];
			$userDetail=getUserDetail($user_id);
			$userOwnerFbId=$userDetail['fb_id'];
			$userName=$userDetail['name'];
			$is_board_price=(int)$fetchExpertRow['is_board_price'];
			$board_type=(int)$fetchExpertRow['board_type'];
			$main_BoardId=(int)getFieldValue('cherryboard_id','tbl_app_expert_cherryboard','expertboard_id="'.$expertboard_id.'" and main_board="1"');
			
			$goal_days=(int)$fetchExpertRow['goal_days'];
			$price=$fetchExpertRow['price'];
			//$expertPicPath='images/expert.jpg';
			$profile_picture=trim($fetchExpertRow['profile_picture']);
			if($profile_picture!=''){
				$expertPicPath='images/expertboard/profile/'.$profile_picture;
			}else{
				$expertPicPath='https://graph.facebook.com/'.$userOwnerFbId.'/picture?type=large';
			}									
			$expert_detail='';
			if(strlen($expertboard_detail)>100){
				$expert_detail=''.substr($expertboard_detail,0,100).'...<a href="javascript:void(0);" style="text-decoration:none;color:#990000" onclick="ajax_action(\'get_more_expert\',\'div_more_expert_'.$expertboard_id.'\',\'expertboard_id='.$expertboard_id.'\')">More</a>';
			}else{
				$expert_detail=$expertboard_detail;
			}
			$created_cherryboard_id=(int)getFieldValue('cherryboard_id','tbl_app_expert_cherryboard','expertboard_id="'.$expertboard_id.'" and main_board="0" AND user_id='.USER_ID);
			
			
			
			//START INVITE SECTION
			 $FriendsCnt='<div id="my_cherryleaders" style="text-align: left; width: 150px;">Your Companions&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.(getExpCreator('Expert',$cherryboard_id,USER_ID)==1?'<a href="#" id="invite_frnd" class="gray_link_15">+</a><input type="hidden" name="cherryboard_id" id="cherryboard_id" value="'.$cherryboard_id.'" />':'').'<input type="hidden" name="cherryboard_key" id="cherryboard_key" value="0" /><br>
			 <div id="div_goal_followers">';
				$selQuery="select a.meb_id,b.user_id,b.fb_photo_url from tbl_app_expert_cherryboard_meb a,tbl_app_users b where a.req_user_fb_id=b.facebook_id and a.is_accept='1' and a.cherryboard_id=".$cherryboard_id." group by b.user_id limit 10";
				$selSqlQ=mysql_query($selQuery);
				$FriendsArray=array();
				if(mysql_num_rows($selSqlQ)>0){
					$cnt=0;
					while($rowTbl=mysql_fetch_array($selSqlQ)){
						$FriendsArray[]=$rowTbl['user_id'];
						$meb_id=$rowTbl['meb_id'];
						if($cnt==5){$FriendsCnt.='<br/>';}
						$FriendsCnt.='<div class="small_thumb_container">
						<div class="img_big_container1">
							<div class="feedbox_holder">
								<div class="actions">'.(getExpCreator('Expert',$cherryboard_id,USER_ID)==1?'<a class="delete" href="#" onclick="ajax_action(\'delete_goal_followers\',\'div_goal_followers\',\'cherryboard_id='.$cherryboard_id.'&meb_id='.$meb_id.'\')" ><img src="images/delete.png"></a>':'').'</div>
							</div>
							<img src="'.$rowTbl['fb_photo_url'].'" class="thumb">
						</div>
						</div>';
						$cnt++;
					}
					
				}else{
					$FriendsCnt.='<strong>No Companions</strong>';
				}
				//echo $FriendsCnt;
			$FriendsCnt.='</div><br><br>
			<div id="div_goal_recent_followers">';
				$selQuery="select meb_id,req_user_fb_id from tbl_app_expert_cherryboard_meb where is_accept='0' and cherryboard_id=".$cherryboard_id." order by meb_id desc limit 10";
				$selSqlQ=mysql_query($selQuery);
				if(mysql_num_rows($selSqlQ)>0){
					$FriendsCnt.='<p>Companions Request</p>';
					$cnt=0;
					while($rowTbl=mysql_fetch_array($selSqlQ)){
						if($cnt==5){$FriendsCnt.='<br/>';}
						$meb_id=$rowTbl['meb_id'];
						$fb_photo_url=getFriendPhoto($rowTbl['req_user_fb_id']);
						$FriendsCnt.='<div class="small_thumb_container">
						<div class="img_big_container1">
							<div class="feedbox_holder">
								<div class="actions">'.(getExpCreator('Expert',$cherryboard_id,USER_ID)==1?'<a class="delete" href="#" onclick="ajax_action(\'delete_goal_recent_followers\',\'div_goal_recent_followers\',\'cherryboard_id='.$cherryboard_id.'&meb_id='.$meb_id.'\')" ><img src="images/delete.png"></a>':'').'</div>
							</div>
							<img src="'.$fb_photo_url.'" class="thumb">
						</div>
						</div>';
						$cnt++;
					}
					
				}
				//echo $FriendsCnt;
			 $FriendsCnt.='</div>
			 </div>';
			//END INVITE SECTION
			
			
			if($expertPicPath!=""){
				$countShare=(int)getFieldValue('count(link_id)','tbl_app_expert_link','cherryboard_id='.$cherryboard_id);
				$expertCnt.='<table align="center" border="0" width="100%">';
							//PHOTO SLIDER
							$exportPhoto=mysql_query("select cherryboard_id,photo_title,photo_name,photo_day from tbl_app_expert_cherry_photo where cherryboard_id=".$cherryboard_id);
							$totalExpPhotos=(int)mysql_num_rows($exportPhoto);
							if($totalExpPhotos==0){
								$exportPhoto=mysql_query("select cherryboard_id,photo_title,photo_name,photo_day from tbl_app_expert_cherry_photo where cherryboard_id=".$expertboard_cehrry_id);
								$totalExpPhotos=(int)mysql_num_rows($exportPhoto);
							}
							if($totalExpPhotos>0){
								$MainSlide='';
								$IconSlide='';
								$cnt=0;
								while($exportPhotoRow=mysql_fetch_array($exportPhoto)){
									$photo_title=trim(ucwords($exportPhotoRow['photo_title']));
									if($photo_title!=""){$photo_title=' - '.$photo_title;}
									$photo_name=$exportPhotoRow['photo_name'];
									$photo_day=$exportPhotoRow['photo_day'];
									$photoTitle=getDayType($expertboard_id).' '.$photo_day.$photo_title;
									
									$photoPath='images/expertboard/'.$photo_name;
									if(is_file($photoPath)){
										$MainSlide.='<li><img src="'.$photoPath.'" alt="'.$photoTitle.'" title="'.$photoTitle.'" id="wows1_'.$cnt.'"/></li>';
										$IconSlide.='<a href="#" title="'.$photoTitle.'"><img src="'.$photoPath.'" alt="'.$photoTitle.'"/>'.$cnt.'</a>';
										$cnt++;
									}	
								}

								$expertCnt.='<tr>
								 <td colspan="6" align="center">
									<div id="wowslider-container1">
									<p style="float:right;position:relative;z-index:9;padding:0px;margin:0px;"><img src="'.$expertPicPath.'" height="75" width="75" title="'.$userName.'"></p>
									<div class="ws_images">
										<ul>'.$MainSlide.'</ul>
									</div>
									<div class="ws_bullets" style="display:none">
										<div>'.$IconSlide.'</div>
									</div>
									</td>';
							}
							$expertCnt.='<tr>
							    <td width="150px" valign="top" align="center">
									'.$FriendsCnt.'
								</td>
							    <td width="100px" valign="top" align="center">
									<font size="+1"><strong>Share</strong></font><br/>
									<a rel="leanModal" href="#sendThankYou" title="Send Thank You" class="msg"><img src="images/send-email-button.jpg" title="Sent Email"></a><br/>
									<!-- <div id="div_fb_postbtn">
									<strong>'.$countShare.'</strong><br/>
									 <img style="cursor:pointer" src="images/fb_share_btn.png" height="27px"  width="101px" onclick="postToFeedExp(); return false;"/>
									</div> -->
									<!-- FB Code -->
									<div style="float: center; margin: 4px;">
<a name="fb_share" type="box_count" expr:share_url="data:post.canonicalUrl" href="http://www.facebook.com/sharer.php">Share</a><script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" type="text/javascript"></script><br/><br/>
									<!-- Twitter Code -->
									<a href="https://twitter.com/share" class="twitter-share-button" data-url="http://30daysnew.com/expert_cherryboard.php?cbid='.$cherryboard_id.'" data-via="'.$expertboard_title.'" data-lang="en" data-related="anywhereTheJavascriptAPI" data-count="vertical">Tweet</a>
									<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
									</div>

									
								</td>
								<td align="center" width="50px">
									 <div id="div_expert_picture'.$expertboard_id.'"> 
									 <div class="img_big_container" style="text-align: center;">
									 <div class="send_message">
										<div class="actions1">'.($user_id==USER_ID?'<a href="javascript:void(0);" class="msg" onclick="javascript:document.getElementById(\'subtype\').value=\'change_profile_pic\';document.getElementById(\'photo_upload\').style.display=\'inline\';">Change Photo</a>':'').'</div>
									 </div>
								  <img src="'.$expertPicPath.'" height="180" width="180" title="'.$userName.'">
									  <br/>
									  '.$userName.'
									</div>
									</div>
								</td>
								<td valign="top" width="200px">
								   <div id="div_exp_title_'.$expertboard_id.'"> 							   
								   <font size="+1"><a '.($expOwner_id==USER_ID?'href="javascript:void(0);"  ondblclick="ajax_action(\'edt_exp_title\',\'div_exp_title_'.$expertboard_id.'\',\'stype=add&fieldname=expertboard_title&expertboard_id='.$expertboard_id.'&user_id='.USER_ID.'\')" title="Edit Title"':' href="expert_cherryboard.php?cbid='.$main_BoardId.'"').' class="cleanLink"><strong>'.$expertboard_title.'</strong></a></div><br>
								   <div id="div_more_expert_'.$expertboard_id.'">
								   '.($expOwner_id==USER_ID?'<a href="javascript:void(0);" ondblclick="ajax_action(\'edt_exp_detail\',\'div_more_expert_'.$expertboard_id.'\',\'stype=add&fieldname=expertboard_detail&expertboard_id='.$expertboard_id.'&user_id='.USER_ID.'\')" title="Edit Detail" class="cleanLink">':'').' '.(trim($expert_detail)!=''?''.trim($expert_detail).'':'No expert details').'</a>					          
								   </div>
								   <br><div id="div_exp_day_'.$expertboard_id.'"> '.($expOwner_id==USER_ID?'<a href="javascript:void(0);" ondblclick="ajax_action(\'edt_exp_goal_day\',\'div_exp_day_'.$expertboard_id.'\',\'stype=add&expertboard_id='.$expertboard_id.'&user_id='.USER_ID.'\')" title="Edit Day" class="cleanLink"> ':'').' <font size="+1"><strong>Total : '.$goal_days.' '.$DayType.'s </strong></font></a></div>';
								   if($is_board_price==1){
								   		$expertCnt.='<div id="div_exp_price_'.$expertboard_id.'"> '.($expOwner_id==USER_ID?'<a href="javascript:void(0);" ondblclick="ajax_action(\'edt_exp_price\',\'div_exp_price_'.$expertboard_id.'\',\'stype=add&fieldname=price&expertboard_id='.$expertboard_id.'&user_id='.USER_ID.'\')" title="Edit Price" class="cleanLink">':'').'<font size="+1"><strong> Price : $'.$price.'</strong></font></a></div>';
								   }
								   
								   $expertCnt.='<br/>
								   <table>
								   <tr>
								   ';
								   $check_main_id=(int)getFieldValue('cherryboard_id','tbl_app_expert_cherryboard','cherryboard_id="'.$cherryboard_id.'" and main_board="1"');
								   if($check_main_id>0){
								   
										if($created_cherryboard_id>0&&$expOwner_id!=USER_ID){
										   $expertCnt.='<td><a href="expert_cherryboard.php?cbid='.$created_cherryboard_id.'" name="View Goal" class="btn_small" title="View Goal">View Goal</a>';
										   $expertCnt.='<a href="expertboard.php?eid='.$expertboard_id.'&type=copy" name="Copy" class="btn_small" title="Copy" style="padding-left:20px;">Copy</a></td>';
										}else{
										   if($board_type==0&&$expOwner_id!=USER_ID){
										   		if($price>0){
													$expertCnt.='<td>';	
													//START Paypal buy button
													$expertCnt.='
													<form name="_xclick" action="'.PAYPAL_URL.'" method="post">
													<input type="hidden" name="cmd" value="_xclick">
													<input type="hidden" name="business" value="'.BUSINESS_PAYPAL_EMAIL.'">
													<input type="hidden" name="item_name" value="'.$expertboard_title.'">
													<input type="hidden" name="amount" value="'.$price.'">
													<input type="hidden" name="currency_code" value="USD">
													<input type="hidden" name="quantity" value="1">
													<input type="hidden" name="return" value="http://www.30daysnew.com/expertboard.php?eid='.$expertboard_id.'&type=doit" />
													<input type="hidden" name="cancel_return" value="http://www.30daysnew.com/expert_cherryboard.php?cbid='.$cherryboard_id.'" />
													<input type="image" src="images/paypal.png" border="0" name="submit" alt="Make payments with PayPal - it\'s fast, free and secure!">
													</form>';
													//END Paypal buy button
													$expertCnt.='</td>';	
												}else{
													$expertCnt.='<td><a href="expertboard.php?eid='.$expertboard_id.'&type=doit" name="Doit" class="btn_small" title="Do-It">Do-It</a></td>';
												}	
											}
										}
										
										
									}
								$expertCnt.='</tr></table>';	
								$expertCnt.='<br/>';
						//DO-IT CUSTOMER SECTION								
						$expertCnt.='</td><td valign="top" width="135px">
						<div id="div_exp_customer_'.$expertboard_id.'">';
						$expertCnt.='<font size="+1"><a href="expert_customer.php?cbid='.$cherryboard_id.'"
						style="text-decoration:none;color:#000000;"/><strong>'.ucwords($customers).'</strong></a>
						</font>'.($expOwner_id==USER_ID?'<img src="images/edit.png" height="10px" 
						style="cursor:pointer" ondblclick="ajax_action(\'edit_exp_customer\',\'div_exp_customer_'
						.$expertboard_id.'\',\'stype=eadd&fieldname=customers&expertboard_id='.$expertboard_id.
						'&user_id='.USER_ID.'&cbid='.$cherryboard_id.'\')" width="10px" title="Edit '.ucwords($customers).'" />':'').'';
						$expertCnt.='</div>';
						$selCustomer="SELECT DISTINCT user_id FROM tbl_app_expert_cherryboard WHERE expertboard_id=".$expertboard_id;		
						$selExpCustomer=mysql_query($selCustomer);
						if(mysql_num_rows($selExpCustomer)>0){
							while($fetchExpCustomer=mysql_fetch_array($selExpCustomer)){
								$customerUserId=(int)$fetchExpCustomer['user_id'];
								$expBoardId=(int)getFieldValue('cherryboard_id','tbl_app_expert_cherryboard','expertboard_id='.$expertboard_id.' and user_id='.$customerUserId);
								$customerDetail=getFieldsValueArray('first_name,last_name,fb_photo_url','tbl_app_users','user_id='.$customerUserId);
								$customer_name=$customerDetail[0].''.$customerDetail[1];
								$customer_photo=$customerDetail[2];
								$expertCnt.=''.($customerUserId>0?'<a href="expert_cherryboard.php?cbid='.$expBoardId.'"><img src="'.$customer_photo.'" title='.$customer_name.' style="margin-bottom:0px;width:50px;height:50px;" /></a>&nbsp;&nbsp;':'').'';
							}							
						}else{
							$expertCnt.='<strong>No Customers</strong>';
						}
						//ANALYTICS AND DELETE ICON SECTION PART
						if($expertboard_id>0){
							  $totalExpert=(int)getFieldValue('count(cherryboard_id)','tbl_app_expert_cherryboard','expertboard_id='.$expertboard_id.' and main_board="0"');
							  if($expCreator_id==USER_ID){
							  	$expertCnt.='</td>
								<td valign="top" style="width:175px;">
								<a rel="leanModal" href="#create_expert_board" onclick="javascript:document.getElementById(\'create_from\').value=\'sub_story\';" title="Sub Story" class="btn_small">Sub Story</a></td>';
							  }
							  if($expOwner_id==USER_ID){
									$expertCnt.='<td valign="top" style="width:180px;">	
										<a id="go" rel="leanModal" href="#add_reward" name="add_reward" title="+ Add Reward"><img title="Add Reward" style="padding-right:5px;" src="images/add.png" height="10px" width="10px"></a>
										<a href="expert_graph.php?ebid='.$cherryboard_id.'" name="test" title="Analytics"><img title="Analytics" style="padding-right:5px;" src="images/analytic.png" height="10px" width="10px"></a>
										<a onclick="return delExpert('.$totalExpert.')" href="expert_cherryboard.php?delExpId='.$expertboard_id.'"><img title="Delete" src="images/new_close.png" height="10px" width="10px"></a>';
								}
							}	
								$expertCnt.='</td></tr>';							
						 $expertCnt.='</table>';
						 ?>
						 <script>
								function postToFeedExp() {			
										// calling the API ...
										var objExp = {
										  method: 'feed',
										  redirect_uri: 'http://30daysnew.com/expert_cherryboard.php?cbid=<?=$cherryboard_id?>',
										  link: 'http://30daysnew.com/expert_cherryboard.php?cbid=<?=$cherryboard_id?>',
										  picture: 'http://30daysnew.com/images/expert.jpg',
										  name: '<?=ucwords($expertboard_title)?>',
										  caption: '<?=ucwords($userName)?>',
										  description: '<?=$expert_detail?>'
										};
								//http://30daysnew.com/images/expert.jpg
										function callbackExp(response) {
										  //document.location='http://30daysnew.com/gift_profile.php?gid=<=$gift_id?>&gp=yes';
										  var post_id=response['post_id'];
										  ajax_action('fb_link_post_exp','div_fb_postbtn','cherryboard_id=<?=$cherryboard_id?>&post_id='+post_id);	  
										}
								
										FB.ui(objExp, callbackExp);
									  }
							</script>
								<?php
			}
	  }
	  echo $expertCnt;	  
?>
	<!-- <div class="right">
	 <table>
	 <tr><td><span class="desciption">Start Date</span></td></tr>
	 <tr><td><span class="desciption">Price</span></td></tr>
	 <tr><Td><?=($price!="0"?'$'.$price:'')?></Td></tr>
	 <tr><Td style="padding-top:20px;">
	 <a href="#" class="btn_red" id="buy_board_'<?=$cherryboard_id?>">Buy</a>
	 </Td></tr>
    </table>
	</div>
	<div id="left_container">
      
        <div id="my_cherryleaders"><a href="#" id="invite_frnd" class="gray_link_15 right">+</a>Expertboard Followers<input type="hidden" name="cherryboard_id" id="cherryboard_id" value="<?php echo $cherryboard_id;?>" /><input type="hidden" name="cherryboard_key" id="cherryboard_key" value="0" /><br>
	 <div id="div_goal_followers">
	 <?php
	//FRIENDS BLOCK
	    $FriendsCnt='';
		$selQuery="select a.meb_id,b.user_id,b.fb_photo_url from tbl_app_expert_cherryboard_meb a,tbl_app_users b where a.req_user_fb_id=b.facebook_id and a.is_accept='1' and a.cherryboard_id=".$cherryboard_id." group by b.user_id limit 10";
		$selSqlQ=mysql_query($selQuery);
		$FriendsArray=array();
		if(mysql_num_rows($selSqlQ)>0){
			$cnt=0;
			while($rowTbl=mysql_fetch_array($selSqlQ)){
				$FriendsArray[]=$rowTbl['user_id'];
				$meb_id=$rowTbl['meb_id'];
				if($cnt==5){$FriendsCnt.='<br/>';}
				$FriendsCnt.='<div class="small_thumb_container">
				<div class="img_big_container">
					<div class="feedbox_holder">
						<div class="actions"><a class="delete" href="#" onclick="ajax_action(\'delete_goal_followers\',\'div_goal_followers\',\'cherryboard_id='.$cherryboard_id.'&meb_id='.$meb_id.'\')" ><img src="images/delete.png"></a></div>
					</div>
					<img src="'.$rowTbl['fb_photo_url'].'" class="thumb">
				</div>
				</div>';
				$cnt++;
			}
			
		}else{
			$FriendsCnt.='<strong>No Followers</strong>';
		}
		//echo $FriendsCnt;
	?>
	</div>
	<div id="div_goal_recent_followers">
	<?php
	
		$FriendsCnt='';
		$selQuery="select meb_id,req_user_fb_id from tbl_app_expert_cherryboard_meb where is_accept='0' and cherryboard_id=".$cherryboard_id." order by meb_id desc limit 10";
		$selSqlQ=mysql_query($selQuery);
		if(mysql_num_rows($selSqlQ)>0){
			$FriendsCnt.='<br/><br/><p>Follower Request</p>';
			$cnt=0;
			while($rowTbl=mysql_fetch_array($selSqlQ)){
				if($cnt==5){$FriendsCnt.='<br/>';}
				$meb_id=$rowTbl['meb_id'];
				$fb_photo_url=getFriendPhoto($rowTbl['req_user_fb_id']);
				$FriendsCnt.='<div class="small_thumb_container">
				<div class="img_big_container">
					<div class="feedbox_holder">
						<div class="actions"><a class="delete" href="#" onclick="ajax_action(\'delete_goal_recent_followers\',\'div_goal_recent_followers\',\'cherryboard_id='.$cherryboard_id.'&meb_id='.$meb_id.'\')" ><img src="images/delete.png"></a></div>
					</div>
					<img src="'.$fb_photo_url.'" class="thumb">
				</div>
				</div>';
				$cnt++;
			}
			
		}
		//echo $FriendsCnt;
	?>
	</div>
	 </div>
       
     <div id="my_cherryleaders"><a href="experts.php?cbid=<?php echo $cherryboard_id;?>" class="gray_link_15 right">+</a>Inspirational Experts<br>
	 <div id="div_goal_experts">
   <?php
	//Experts BLOCK
		$selQuery="select a.expert_id,b.user_id,b.fb_photo_url from tbl_app_cherryboard_expert a,tbl_app_users b where a.user_id=b.user_id and a.cherryboard_id=".$cherryboard_id." and is_accept='1' group by b.user_id order by a.expert_id desc limit 10";
		$selSqlQ=mysql_query($selQuery);
		$ExpertsCnt='';
		if(mysql_num_rows($selSqlQ)>0){
			$cnt=0;
			while($rowTbl=mysql_fetch_array($selSqlQ)){
				$expert_id=$rowTbl['expert_id'];
				if($cnt==5){$ExpertsCnt.='<br/>';}
				$ExpertsCnt.='<div class="small_thumb_container">
				<div class="img_big_container">
					<div class="feedbox_holder">
						<div class="actions"><a class="delete" href="#" onclick="ajax_action(\'delete_goal_experts\',\'div_goal_experts\',\'cherryboard_id='.$cherryboard_id.'&expert_id='.$expert_id.'\')" ><img src="images/delete.png"></a></div>
					</div>
					<img src="'.$rowTbl['fb_photo_url'].'" class="thumb">
				</div>
				</div>';
				$cnt++;
			}
			
		}else{
			$ExpertsCnt.='<strong>No Experts</strong>';
		}
		//echo $ExpertsCnt;
	?>
	</div>
	 </div>
	
	
	 <table>
	 <tr><td><span class="desciption">Desciption</span></td></tr>
	 <tr><td><?=substr($expertboard_title,0,300)?></td></tr>
	 <tr><td><span class="desciption">My Expertise</span></td></tr>
	 <tr><td><span class="desciption">This is good for</span></td></tr>
	</table>
	 
    </div>
	<div id="middle_wrapper" style="margin-left:250px; width:470px;" >
    	<table width="100%">
		<?php if($msg!=""){ ?>
		<tr><td><font color="#009900;font-size:12px"><?php echo $msg;?></font></td></tr>
		<?php }	?>
    	<tr><td align="center"><h1><?php echo $expertboard_title;?></h1></td></tr>
	 	<tr><td align="center">Goal Type: <?=ucwords($category_name)?></td></tr>
		<tr><td align="center">&nbsp;</td></tr>
		<tr><td align="center">&nbsp;</td></tr>
		<tr><td align="center">&nbsp;</td></tr>
		</table>
  </div> -->
   <div class="clear"></div>
</div>
</div>
<div id="body_container">
	<div class="wrapper">
        
		<div id="checklist">		
		<?php
		//START REWARD SECTION
		if($expOwner_id==USER_ID){
		 	//echo '<h2 style="padding-bottom:0px">Rewards</h2>';
		}else{
		    //echo "<h2>Rewards</h2>";
		}
		$expRewardCnt='';
		$selQuery=mysql_query("select * from tbl_app_expert_reward_photo where cherryboard_id=".$cherryboard_id);
		if(mysql_num_rows($selQuery)>0){
			$cnt=1;
			while($selQueryRow=mysql_fetch_array($selQuery)){
				$expRewardId=$selQueryRow['exp_reward_id'];
				$expUserId=$selQueryRow['user_id'];
				$reward_title=$selQueryRow['photo_title'];
				$reward_photo='images/expertboard/reward/'.$selQueryRow['photo_name'];
				if($cnt==2){echo "<br>";$cnt=1;}				
				if($expOwner_id==USER_ID){  
				$expRewardCnt.='<table><tr><td><div style="padding-left:211px;height:11px;">
			   <a onclick="ajax_action(\'del_exp_reward\',\'div_del_exp_reward\',\'expRewardId='.$expRewardId.
			   '&cherryboard_id='.$cherryboard_id.'&user_id='.USER_ID.'\')" 
				href="javascript:void(0);"><img src="images/delete.png"></a></div></td></tr></table>';
				} 
				$expRewardCnt.='<div class="img_big_container" style="text-align:center">';
				$expRewardCnt.='<div id="div_change_exp_reward_photo'.$expRewardId.'">
								<div class="send_message">
								<div class="actions1" style="left:15px;top:80px;">'.($expOwner_id==USER_ID?
								'<a href="javascript:void(0);" onclick="javascript:document.getElementById(\'exp_reward_id\').value='.$expRewardId.';javascript:document.getElementById(\'subtype\').value=\'change_reward_pic\';document.getElementById(\'photo_upload\').style.display=\'inline\';" class="msg">Change Photo</a>':'').'</div></div>
								<img width="180px" src="'.$reward_photo.'" alt='.$reward_title.'></div>';
				$expRewardCnt.='<div id="div_edit_exp_reward_title'.$expRewardId.'">
				 				'.($expOwner_id==USER_ID?'<a href="javascript:void(0);" ondblclick="ajax_action(\'edit_exp_reward_title\',\'div_edit_exp_reward_title'.$expRewardId.'\',\'expRewardId='.$expRewardId.
								'&user_id='.USER_ID.'&cherryboard_id='.$cherryboard_id.'&stype=eadd\')"
								 title="Edit Title" style="text-decoration:none;color:#000000;">':
								 '').'&nbsp;'.ucwords($reward_title).'&nbsp;</a></div></div>';
				$cnt++;
			}
		}else{
			$expRewardCnt.='No Reward<br/><br/>';
		}
		//echo '<div id="div_del_exp_reward">'.$expRewardCnt.'</div>';
		//END REWARD SECTION
		?>
		<h2>To-Do List</h2>
		<?php if($expOwner_id==USER_ID){ ?>
          <input name="txt_checklist" id="txt_checklist" type="text" onfocus="if(this.value=='add something to To-Do List') this.value='';" onblur="if(this.value=='') this.value='add something to To-Do List';" class="input_200" value="add something to To-Do List">
          <input name="Submit" type="button" onclick="ajax_action('add_expert_checklist','div_checklist','cherryboard_id=<?=$cherryboard_id;?>&txt_checklist='+document.getElementById('txt_checklist').value+'&user_id=<?=USER_ID?>&cuid=<?=USER_ID?>');" value="Post" title="Post" class="btn_small" style="margin:0px;">
          <br>
          <br>
		  <?php } ?>
		  <div id="div_checklist">
		  <?php
		  //TO-DO LIST BLOCK
			$checkCnt='';
			//CALL FUNCTION GET TODOLIST ITEMS
			$checkCnt.=getToDoListItem($cherryboard_id);
			echo $checkCnt;
		 ?>
		</div>
		<br/><Br/>
		<div id="inspir_feed1">
		<h2>Bought Users</h2>
		<Table>
		<?php
		//BOUGHT SECTION
		$RemainDay=getExpertboardRemainDays($cherryboard_id);
		$sel_buy=mysql_query('select a.user_id,b.first_name,b.last_name,b.fb_photo_url from tbl_app_expert_buy a,tbl_app_users b where a.user_id=b.user_id and a.cherryboard_id='.$cherryboard_id);
		if(mysql_num_rows($sel_buy)>0){
			$cnt=1;
			while($row_buy=mysql_fetch_array($sel_buy)){
				$userid=$row_buy['user_id'];
				$name=ucwords($row_buy['first_name'].'&nbsp;'.$row_buy['last_name']);
				$fb_photo_url=$row_buy['fb_photo_url'];
				$checkIn='';
				if($RemainDay<=30){
					$checkIn='<a href="javascript:void(0);" id="exp_checkin_mail_'.$userid.'" onclick="ajax_action(\'exp_checkin_mail\',\'exp_checkin_mail_'.$userid.'\',\'cherryboard_id='.$cherryboard_id.'&user_id='.$userid.'\')" id="exp_checkin_msg" class="gray_link">Day&nbsp;'.(int)(30-$RemainDay).'&nbsp;Check&nbsp;In!</a>';
				}
		
				echo '<tr><td>'.$cnt.'.</td><td>'.$name.'</td><td><img class="img_small" title="'.$name.'" src="'.$fb_photo_url.'"></td><td>'.$checkIn.'</td></tr>';
				$cnt++;
			}
		}else{
			echo '<tr><td colspan="4"><div class="feed"><strong>No User<strong></div></td></tr>';
		}
		?>		
		</Table>
		</div>
		<br/><Br/>
		<div id="inspir_feed1">
          <?php
		 echo UserExpertFeedSection('expertboard',$cherryboard_id);
		?>
      </div>
      </div>
	 	  <div id="right_container" style="position: absolute;margin-left:275px;width:720px;">
	  	<div style="position: absolute;"><table border="0"><tr><td><a title="Sort" name="Sort" href="javascript:void(0);" onclick="javascript:ajax_action('exp_photo_refresh','right_container','cherryboard_id=<?=$cherryboard_id?>&sort=desc')"><img title="Descending" src="images/des.jpg" height="35" width="35"/></a></td><td><img id="rotate_asc" src="images/transparent.png" height="25" width="25"/></td></table></div>
			<div style="position: absolute;margin-left:655px;">
			<table border="0">
			<?php if($main_BoardId!=$cherryboard_id){?>
			<tr><td><img src="<?=$BuyerPic?>" class="imgsmall" style="width:25px;height:25px;" />&nbsp;</td><td valign="top"><strong><?=$BuyerName?></strong></td></tr>
			<?php }
				  if($expOwner_id==USER_ID){
			?>
			<tr><td style="padding-left:20px;padding-top:350px;"><a href="javascript:void(0);" onclick="ajax_action('increase_expdays_items','div_exp_day_<?=$expertboard_id?>','cherryboard_id=<?=$cherryboard_id?>&user_id=<?=USER_ID?>')" title="Add <?=$DayType?>" class="gray_link_15">+</a></td></tr>
			<?php }	?>
			</table>
			</div>
	  	  <?php
		   
		 //DAYS TITLE
		  $selDays=mysql_query("select day_title from tbl_app_expertboard_days where expertboard_id=".$expertboard_id." order by day_no");
		  $DaysTitleArr=array();
		  if(mysql_num_rows($selDays)>0){
		  	  $cntDay=1;
			  while($selDaysRow=mysql_fetch_array($selDays)){
				$DaysTitleArr[$cntDay]=$selDaysRow['day_title'];
				$cntDay++;
			  }
			  
		  }
		  //EXPERT BOARD PHOTOS
		  $qryphoto="select * from tbl_app_expert_cherry_photo where cherryboard_id=".$cherryboard_id." order by photo_id desc";
		  $selphoto=mysql_query($qryphoto);
		  $cntPhoto=mysql_num_rows($selphoto);
		  $photoDayArr=array();
		  if($cntPhoto>0){
			while($selphotoRow1=mysql_fetch_array($selphoto)){
				$photo_id=$selphotoRow1['photo_id'];
				$photo_day=((int)$selphotoRow1['photo_day']);
				$photoDayArr[$photo_id]=$photo_day;
			}	
		  
		  }
		 $photoDayArr = array_unique($photoDayArr);
		  	
	   $GoalDays=getExpertGoalDays($cherryboard_id);
	   $expUser_id=(int)getFieldValue('user_id','tbl_app_expert_cherryboard','cherryboard_id='.$cherryboard_id);	
	   $photoCntArray=array();
	   for($i=1;$i<=$GoalDays;$i++){	
 		   
		   $swap_id=0;
		   if(in_array($i,$photoDayArr)){
				$selphoto=mysql_query("select * from tbl_app_expert_cherry_photo where cherryboard_id=".$cherryboard_id." and photo_day='".$i."' order by photo_id");
				$sub_day=1;
				$sub_photoCntArray=array();
				$totalPhoto=mysql_num_rows($selphoto);
				while($selphotoRow=mysql_fetch_array($selphoto)){
					$photo_id=$selphotoRow['photo_id'];
					$user_id=$selphotoRow['user_id'];
					$swap_id=$photo_id;
					$photo_title=ucwords(stripslashes($selphotoRow['photo_title']));
					$photo_name=$selphotoRow['photo_name'];
					$record_date=$selphotoRow['new_record_date'];
					$photoPath='images/expertboard/'.$photo_name;
					$photo_day=(int)$selphotoRow['photo_day'];
					if($photo_title==""){
						$photo_title='<div style="width:180px;height:18px">&nbsp;</div>';
					}
					if(is_file($photoPath)){
					   $photoCnt='';
					   if($totalPhoto>1){
					  	 $printDay=$photo_day.'.'.$sub_day;
					   }else{ $printDay=$photo_day; }
					   $photoCnt.='
					  <table border="0" class="newtd" id="divPhoto_'.$photo_id.'">
						<tr>
						<td>
						  <div class="field_container2" style="margin:0px;padding:0px;">
						  <div class="day_container">'.$DayType.' '.$printDay.'&nbsp;'.($user_id==USER_ID?'<img src="images/upload.png" onclick="javascript:document.getElementById(\'photo_day\').value='.$i.';document.getElementById(\'photo_upload\').style.display=\'inline\';" height="15" width="15" style="vertical-align:middle;cursor:pointer;" title="Add Your Picture" />':'').' </div>
							  <div class="tag_container">
								<div class="comment_box1" id="photo_title'.$photo_id.'"> '.($user_id==USER_ID?'<a href="javascript:void(0);" ondblclick="ajax_action(\'upd_photo_title\',\'photo_title'.$photo_id.'\',\'stype=eadd&photo_id='.$photo_id.'&user_id='.USER_ID.'\')" title="Edit Comment" class="cleanLink">':'').''.$photo_title.'</a></div><div class="clear"></div>
									<div class="info_box">
										<div id="div_photo_day'.$photo_day.'"><div class="score">
										'.($expOwner_id==USER_ID?'<a href="javascript:void(0);"  ondblclick="ajax_action(\'edt_exp_photo_day\',\'div_photo_day'.$photo_day.'\',\'stype=add&photo_day='.$photo_day.'&expertboard_id='.$expertboard_id.'&user_id='.USER_ID.'\')" title="Edit Day Title" style="text-decoration:none;color:#FFFFFF;">':'').'&nbsp;'.$DaysTitleArr[$photo_day].'&nbsp;</a></div></div>
										
										<div class="date">'.$record_date.'</div>
									 </div>
									 <div class="b_arrow"></div>
								 <div class="clear"></div>
							   </div>
							  </div>  
						 </td>
						 </tr>
						 <tr>
						 <td height="100%" class="top_td">	 
							 <div class="field_container2" style="margin:0px;padding:0px;">
							 ';
							$photoCnt.='<div class="img_big_container3" id="div'.$i.'_'.$swap_id.'" '.($user_id==USER_ID?'ondrop="drop(event,\''.$i.'_'.$swap_id.'\')" ondragover="allowDrop(event,\''.$i.'_'.$swap_id.'\')':'').'"> 
										<div class="feedbox_holder">';
										if($user_id==USER_ID){
											$photoCnt.='<div class="actions"><a class="delete" href="#" onclick="photo_action(\'del_expert_photo\','.$cherryboard_id.','.$photo_id.')"><img src="images/delete.png"></a></div>';		
										 }
										$photoCnt.='</div>'; 
								if($mainExpCherryId==$cherryboard_id&&$checkIsExpertBoard>0&&$expOwner_id!=USER_ID){
									if($checkIsExpertBoard>0){
										$photoCnt.='<input type="hidden" name="cherryboard_id" id="cherryboard_id" value="'.$checkIsExpertBoard.'" />';
									}
										$photoCnt.='<div class="send_message">
										<div class="actions1"><a href="javascript:void(0);" title="Add Your Picture" class="msg" onclick="javascript:document.getElementById(\'photo_day\').value='.$i.';document.getElementById(\'photo_upload\').style.display=\'inline\';" >Add Your Picture</a></div></div>';
								}
										 $photoCnt.='<img src="'.$photoPath.'" id="drag'.$i.'_'.$swap_id.'" draggable="true" ondragstart="drag(event,\''.$i.'_'.$swap_id.'\')">								
								</div>'; 
						
							$photoCnt.='<div id="div_cherry_comment_'.$photo_id.'">';
							//CHEER SECTION
							$photoCnt.=expert_cheers_section($cherryboard_id,$photo_id,$photo_day);
							//ADD EXPERT NOTES SECTION
							if($expUser_id==USER_ID){
							$photoCnt.=expert_notes_section($cherryboard_id,$photo_id,$photo_day);
							}
							//QUESTION/ANSWER SECTION
							$photoCnt.=expert_question_section($cherryboard_id,$photo_id,$photo_day);							
							//COMMENT SECTION
							$photoCnt.=expert_comment_section($cherryboard_id,$photo_id,$photo_day);
							
			$current_userPic=getFieldValue('fb_photo_url','tbl_app_users','user_id='.USER_ID);				
			$photoCnt.='</div><div class="add1">
				 <div class="add_img"><img src="'.$current_userPic.'" class="img_small" /></div>
				 <div class="add_txt">
				 <textarea name="cherry_comment_'.$photo_id.'" class="input_comments" id="cherry_comment_'.$photo_id.'" onfocus="if(this.value==\'Add a comment...\') this.value=\'\';" onblur="if(this.value==\'\') this.value=\'Add a comment...\';" style="height: 29px;width:130px;">Add a comment...</textarea>			 
				 </div>
				 <div class="add_btn"><img style="cursor:pointer" src="images/btn_comment.png" onclick="return add_cherry_comment(event,\'add_cherry_expert_comment\',\''.$cherryboard_id.'\',\''.$photo_id.'\',\''.USER_ID.'\',document.getElementById(\'cherry_comment_'.$photo_id.'\').value)"></div>
		</div>';
							if($i==1){
								$photoCnt.='<div style="padding-bottom:35px;"></div>';	  
							}
							$photoCnt.='</td></tr></table>';
							$sub_photoCntArray[$sub_day]=$photoCnt;
							$sub_day++;	
						}
					}
					$photoCntArray[$i]=$sub_photoCntArray;
			}else{
				 $photoCnt='';
				 $sub_photoCntArray=array();
			  	 $photoPath='images/cherryboard/no_image.png'; 
				 $photoCnt.='
				 <table border="0" class="newtd">
				<tr>
				<td>
				  <div class="field_container2" style="margin:0px;padding:0px;">
				  <div class="day_container">'.$DayType.' '.$i.'</div>
				  <div class="tag_container">
					<div class="comment_box1" id="photo_title'.$i.'">No Photo</div><div class="clear"></div>
						<div class="info_box">
							<div class="score">'.$DaysTitleArr[$i].'</div>
							<div class="date">&nbsp;</div>
						 </div>
						 <div class="b_arrow"></div>
					 <div class="clear"></div>
				 </div>
				</div>
				</td>
				</tr>
				<tr>
					 <td height="100%" class="top_td">	 
						 <div class="field_container2" style="margin:0px;padding:0px;">
							<div id="div'.$i.'_'.$swap_id.'" style="background-image:url('.$photoPath.');cursor:pointer;height:192px;width:192px;" '.($expUser_id==USER_ID?'ondrop="drop(event,\''.$i.'_'.$swap_id.'\')" ondragover="allowDrop(event,\''.$i.'_'.$swap_id.'\')" onclick="javascript:document.getElementById(\'photo_day\').value='.$i.';document.getElementById(\'photo_upload\').style.display=\'inline\';"':'').' src="'.$photoPath.'">
							 </div>
						   <div id="div_cherry_comment">';
						$photoCnt.='</div></div>';
						$photoCnt.='</div>';
				        $photoCnt.='</td></tr></table>';		
						$sub_photoCntArray[1]=$photoCnt;
						$photoCntArray[$i]=$sub_photoCntArray;
			  }
			}
		$NewphotoCnt='';
		$NewphotoCnt='<table border="0"><tr>';
		if($sort=="asc"){
			$cnt=1;
			for($i=1;$i<=$GoalDays;$i++){
				foreach($photoCntArray[$i] as $photosection){
					$NewphotoCnt.='<td valign="top" style="height:100%">'.$photosection.'</td>';
					if($cnt%3==0){$NewphotoCnt.='</tr><tr>';}
					$cnt++;
				}				
			}
		}else{
			$cnt=1;
			for($i=$GoalDays;$i>=1;$i--){
				foreach($photoCntArray[$i] as $photosection){
					$NewphotoCnt.='<td valign="top" style="height:100%">'.$photosection.'</td>';
					if($cnt%3==0){$NewphotoCnt.='</tr><tr>';}
					$cnt++;
				}
			}
		}		
		$NewphotoCnt.='</tr>
		<tr><td colspan="3" style="height:50px;">&nbsp;</td></tr>
		</table>';
  		echo $NewphotoCnt;
		 ?>
       </div>
<input type="hidden" name="img_sort" id="img_sort" value="<?=$_GET['sort']?>">
<input type="hidden" name="imgswap_from" id="imgswap_from" value="">
<input type="hidden" name="imgswap_to" id="imgswap_to" value="">
<script>
function allowDrop(ev,id)
{
	ev.preventDefault();
}

function drag(ev,id)
{	
	ev.dataTransfer.setData("Text",ev.target.id);
	document.getElementById('imgswap_from').value=id;
}

function drop(ev,id)
{	
	document.getElementById('imgswap_to').value=id;
	
	var img_from=document.getElementById('imgswap_from').value;
	var img_to=document.getElementById('imgswap_to').value;
	var img_sort =document.getElementById('img_sort').value;
	
	ev.preventDefault();
	var data=ev.dataTransfer.getData("Text");
	ev.target.appendChild(document.getElementById(data));
	ajax_action('swap_image','imgswap_to','imgswap_from='+img_from+'&imgswap_to='+img_to+'&img_sort='+img_sort);
}

function dragTodoList(ev,id)
{	
	ev.dataTransfer.setData("Text",ev.target.id);
	document.getElementById('imgswap_from').value=id;
}

function dropTodoList(ev,id)
{	
	document.getElementById('imgswap_to').value=id;
	
	var img_from=document.getElementById('imgswap_from').value;
	var img_to=document.getElementById('imgswap_to').value;
	
	ev.preventDefault();
	var data=ev.dataTransfer.getData("Text");
	ev.target.appendChild(document.getElementById(data));
	ajax_action('swap_todolist','div_checklist','imgswap_from='+img_from+'&imgswap_to='+img_to);
}
</script>
	   <div class="clear"></div>        
  </div>
</div>
<!-- START ADD PHOTO--- -->
		<div style="display: none; position: fixed; opacity: 1; z-index: 11000; left: 50%; margin-left: -165px; top: 150px;width:450px;" id="photo_upload" align="center" class="popup_div">
                <a class="modal_close" href="javascript:void(0);" title="close" onclick="javascript:document.getElementById('photo_upload').style.display='none';"></a>
                <span class="head_20">Upload Photo</span><br>
				<div id="setup_add_category" class="msg_red">&nbsp;</div>
				<form action="" method="post" name="frmphoto<?=$cherryboard_id?>" enctype="multipart/form-data">
				<input type="hidden" name="photo_day" id="photo_day" value="1" />
				<input type="hidden" name="exp_reward_id" id="exp_reward_id" value="1" />
				<input type="hidden" name="subtype" id="subtype" value="exp" />
				<input type="hidden" name="user_id" id="user_id" value="<?=USER_ID?>" />
				<div id="div_up_photo"></div>
				<div id="me" class="red_link_14">+ Add a Photo (3MB)</div>				
				</form>
	 </div>
<!-- END ADD PHOTO--- -->
<!-- START SEND THANK YOU CODE -->
<form action="" method="post" name="frmsndthank" enctype="multipart/form-data">
<div style="display: none; position: fixed; opacity: 1; z-index: 11000; left: 50%; margin-left: -330px; top: 100px; width:500px;border:5px solid #000000;" id="sendThankYou" class="popup_div">
		<a class="modal_close" href="#" title="close"></a>	
		<div class="msg_red" id="div_frm_sndmsg"></div>
		<div id="div_send_thankYou">
			<div align="center" class="email_header">Send Email</div><br>
			<span style="padding-left:20px;"><strong>Email</strong>:
			<input type="text" style="width:380px;margin-left:25px;" name="email_id" id="email_id" onblur="if(this.value=='') this.value='Enter Email';" onfocus="if(this.value=='Enter Email') this.value='';" value="Enter Email" /></span><br><br>
			<span style="padding-left:20px;"><strong>Subject</strong>:
			<input type="text" style="width:380px;margin-left:10px;" name="subject" id="subject" onblur="if(this.value=='') this.value='Enter Subject';" onfocus="if(this.value=='Enter Subject') this.value='';" value="Enter Subject" /></span><br><br>
			<table><tr>
			<td valign="top" style="padding-left:15px;"><strong>Message</strong>:</td>
			<td><textarea style="width:380px;" rows="8" name="message" id="message" onblur="if(this.value=='') this.value='Enter Message';" onfocus="if(this.value=='Enter Message') this.value='';">Enter Message</textarea>
			</td></tr></table>
			<br>
			<input type="button" style="margin-left:210px;" class="btn_small" id="btnsend" onClick="ajax_action('sendThankYou_Expert','div_send_thankYou','cherryboard_id=<?=$cherryboard_id;?>&email_id='+document.getElementById('email_id').value+'&subject='+document.getElementById('subject').value+'&message='+document.getElementById('message').value+'&user_id=<?=USER_ID?>');" value="Send" name="btnsend" />
		</div>
</div>
</form>
<!-- END SEND THANK YOU CODE -->  
<!-- Add Reward -->
		<div style="display: none; position: fixed; opacity: 1; z-index: 11000; left: 50%; margin-left: -330px; top: 200px;" id="add_reward" class="popup_div" align="center">
                <a class="modal_close" href="#" title="close"></a>
                <span class="head_20">Add Reward</span><br>
				<div id="setup_add_category" class="msg_red">&nbsp;</div>
				<form action="" method="post" name="data" id="data" enctype="multipart/form-data">
				<input type="hidden" name="totalDyndiv" id="totalDyndiv" value="1" />
				<table>
				<tr id="DynDiv1">
				<td>
					<table>
					<tr>
						<td>1. Reward Title : </td><td><input type="text" name="reward_title1" /></td>
					</tr>
					<tr>	
						<td>Reward Photo : </td>
						<td><input type="file" name="reward_photo1"/>&nbsp;
						<a href="javascript:void(0);" onclick="showDynamicDiv('DynDiv','totalDyndiv');" style="text-decoration:none">+ Add</a></td>
					</tr>
					</table>
				  </td>
				  </tr>	
					<?php
					for($p=2;$p<=5;$p++){
					?>
						<tr id="DynDiv<?=$p?>" style="display:none">
						<td>
							<table>
							<tr>
					<td><?=$p?>. Reward Title : </td><td><input type="text" name="reward_title<?=$p?>" /></td>
							</tr>
							<tr>	
								<td>Reward Photo : </td>
								<td><input type="file" name="reward_photo<?=$p?>"/></td>
							</tr>
							</table>
						  </td>
						  </tr>		
					<?php }?>
			<tr><td><input type="submit" class="btn_small" value="Add Reward" name="btnAddReward" style="margin-left:150px" /></td></tr>
				</table>
				</form>
	 </div>
<!-- END ADD Reward -->
<script type="text/javascript" src="board_slider/wowslider.js"></script>
<script type="text/javascript" src="board_slider/script.js"></script>
<?php include('fb_expert_invite.php');?>
<?php include('site_footer.php');?>