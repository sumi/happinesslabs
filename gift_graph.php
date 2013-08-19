<?php
include_once "fbmain.php";
include('include/app-common-config.php');
$gift_id=$_GET['gid'];
?>
<?php include('site_header.php');?>
<?php
$gift_id=$_GET['gift_id'];
$type=$_GET['type'];
?>
<!-- [if IE]><script language="javascript" type="text/javascript" src="./excanvas.js"></script><![endif]-->
		<link rel="stylesheet" type="text/css" href="graph/jquery.jqplot.css" />
		<script language="javascript" type="text/javascript" src="graph/jquery-1.3.2.min.js"></script>
		<script language="javascript" type="text/javascript" src="graph/jquery.jqplot.js"></script>
		<script language="javascript" type="text/javascript" src="graph/plugins/jqplot.canvasTextRenderer.js"></script>
		<script language="javascript" type="text/javascript" src="graph/plugins/jqplot.canvasAxisTickRenderer.js"></script>
		<script language="javascript" type="text/javascript" src="graph/plugins/jqplot.dateAxisRenderer.js"></script>
		
<!--Body Start-->
	<div id="wrapper" style="width:1050px">
	<div id="div_goal_">
<?php
$campaign_id=(int)getFieldValue('campaign_id','tbl_app_gift','gift_id='.$gift_id);
if($campaign_id>0){
	$compain_owner_id=(int)getFieldValue('user_id','tbl_app_gift','gift_id='.$campaign_id);
}else{
	$compain_owner_id=(int)getFieldValue('user_id','tbl_app_gift','gift_id='.$gift_id);
}
	
if($compain_owner_id==USER_ID||USER_ID==26){
	
		if($gift_id>0){
		
			if($type=="compain"){
					$giftDetail=getFieldsValueArray('gift_title,gift_photo,sponsor_url,user_id','tbl_app_gift','gift_id='.$gift_id);
					
					$gift_title=ucwords($giftDetail[0]);
					$gift_photo=$giftDetail[1];
					$sponsorship_url=$giftDetail[2];
					$user_id=$giftDetail[3];
					$GiftCnt='<div class="field_container1">
							  <div align="center">
								<a href="gift_profile.php?gid='.$gift_id.'"><img src="images/gift/'.$gift_photo.'" width="100" height="100" class="profile_img_big1"></a><br><br>
								<div class="feed_comment1"><a href="gift_profile.php?gid='.$gift_id.'" style="text-decoration:none;color:#000000"><strong>'.$gift_title.'</strong></a></div><br>
								<a class="gray_link" href="'.$sponsorship_url.'" target="_blank">'.$sponsorship_url.'</a>
							 </div>
						  </div>';
					echo $GiftCnt;
					 
					 //START GRAPH
					 if($user_id==USER_ID||USER_ID==26){
						//Graph for the created goal board on the compain gift date wise
						$selGift=mysql_query("SELECT gift_id,count(`cherry_gift_id`) as giftTotal,date_format(`record_date`,'%Y-%m-%d') as gift_date FROM `tbl_app_cherry_gift` where `gift_id`=".$gift_id." group by `gift_date`");
						
							$giftDays='';
							while($rowGift=mysql_fetch_array($selGift)){
								
								$giftDate=$rowGift['gift_date'];
								$goalTotal=(int)$rowGift['giftTotal'];
								if($giftDays!=""){$giftDays.=", ";}
								$giftDays.="['".$giftDate."', ".$goalTotal."]";
								
							}
						
						//reward Graph for the created goal board on the compain gift date wise
						$selGift1=mysql_query("SELECT gift_id FROM `tbl_app_gift` where `campaign_id`=".$gift_id);
						while($selRow1=mysql_fetch_array($selGift1)){
					
							$sub_gift_id=$selRow1['gift_id'];	
							$selGift=mysql_query("SELECT gift_id,count(`cherry_gift_id`) as giftTotal,date_format(`record_date`,'%Y-%m-%d') as gift_date FROM `tbl_app_cherry_gift` where `gift_id`=".$sub_gift_id." group by `gift_date`");
							while($rowGift=mysql_fetch_array($selGift)){
								
								$giftDate=$rowGift['gift_date'];
								$goalTotal=(int)$rowGift['giftTotal'];
								if($giftDays!=""){$giftDays.=", ";}
								$giftDays.="['".$giftDate."', ".$goalTotal."]";
								
							}	
						}
						?>	  
						 <Table>
						 <tr>
						 <td> 
						  <div class="field_container1" style="width:550px;text-align:center">
							 <script type="text/javascript" language="javascript">
								$(document).ready(function(){
									
									line1=[<?=$giftDays?>];
									
									plot1 = $.jqplot('chart', [line1], {
										title:'Campaign : <?=ucwords($gift_title)?>',
										axes:{
											xaxis:{
												renderer:$.jqplot.DateAxisRenderer, 
												min:'January 01, 2013', 
												
												rendererOptions:{
													tickRenderer:$.jqplot.CanvasAxisTickRenderer},
													tickOptions:{formatString:'%m-%#d-%Y', fontSize:'10pt', fontFamily:'Tahoma', angle:-40, fontWeight:'normal', fontStretch:1}
											}
										},
										series:[{lineWidth:4, markerOptions:{style:'square'}}]
									});
									
									});
									</script>
								 <div id="chart" style="margin-top:20px; margin-left:20px; width:500px; height:300px;"></div>
								
						  </div>
						  </td>
						  <td valign="top">
						  <!-- SUBSCRIBER PEOPLE -->
						  <div class="field_container1" style="width:300px;text-align:center">
						  <table width="100%">
							<tr>
							<td><strong>No</strong></td>
							<td><strong>Photo</strong></td>
							<td><strong>Name</strong></td>
							<td><strong>Date Joined</strong></td>
							</tr>
							<?php
							$arrayGiftIdsArr=array($gift_id);
							$sel_data=mysql_query("select gift_id from tbl_app_gift where campaign_id=".$gift_id."");
							while($sel_dataRow=mysql_fetch_array($sel_data)){
								$arrayGiftIdsArr[]=$sel_dataRow['gift_id'];
							}
							$arrayGiftIds=implode(',',$arrayGiftIdsArr);
							
							$selQuery="SELECT b.user_id,b.facebook_id,b.first_name,b.last_name,b.fb_photo_url,b.location,date_format(a.record_date,'%b %d, %Y') as JoinDate,a.cherryboard_id FROM tbl_app_cherry_gift a,tbl_app_users b where a.user_id=b.user_id and a.gift_id in(".$arrayGiftIds.") group by a.user_id order by a.record_date";
							$selGift=mysql_query($selQuery);
						
							$ReportData='';
							$cnt=1;
							while($rowGift=mysql_fetch_array($selGift)){
								$user_id=$rowGift['user_id'];
								$user_name=ucwords($rowGift['first_name'].' '.$rowGift['last_name']);
								$fb_photo_url=$rowGift['fb_photo_url'];
								$user_photo='<div class="gift" style="vertical-align:bottom;padding:0px 3px 0px 10px;"><img src="'.$fb_photo_url.'"  class="imgsmall" title='.$user_name.' style="margin-bottom:0px;width: 25px;height: 25px;" /></div>';
								$JoinDate=$rowGift['JoinDate'];
								
								$ReportData.='<tr>
								<td>'.$cnt.'</td>
								<td align="center">'.$user_photo.'</td>
								<td>'.$user_name.'</td>
								<td>'.$JoinDate.'</td>
								</tr>';
								$cnt++;
							}
							echo $ReportData;
							?>
							
							</table>
						  </div>
						  </td>
						  </tr>
						  </Table>
					<?php
					  }
					 //END GRAPH
			}else{
				
					$giftDetail=getFieldsValueArray('gift_title,gift_photo,sponsor_url,user_id','tbl_app_gift','gift_id='.$gift_id);
					
					$gift_title=ucwords($giftDetail[0]);
					$gift_photo=$giftDetail[1];
					$sponsorship_url=$giftDetail[2];
					$user_id=$giftDetail[3];
					$GiftCnt='<div class="field_container1">
							  <div align="center">
								<a href="gift_profile.php?gid='.$gift_id.'"><img src="images/gift/'.$gift_photo.'" width="100" height="100" class="profile_img_big1"></a><br><br>
								<div class="feed_comment1"><a href="gift_profile.php?gid='.$gift_id.'" style="text-decoration:none;color:#000000"><strong>'.$gift_title.'</strong></a></div><br>
								<a class="gray_link" href="'.$sponsorship_url.'" target="_blank">'.$sponsorship_url.'</a>
							 </div>
						  </div>';
					echo $GiftCnt;
					 
					 //START GRAPH
					 
						//Graph for the created goal board on the compain gift date wise
						$selGift=mysql_query("SELECT gift_id,count(`cherry_gift_id`) as giftTotal,date_format(`record_date`,'%Y-%m-%d') as gift_date FROM `tbl_app_cherry_gift` where `gift_id`=".$gift_id." group by `gift_date`");
						
							$giftDays='';
							while($rowGift=mysql_fetch_array($selGift)){
								
								$giftDate=$rowGift['gift_date'];
								$goalTotal=(int)$rowGift['giftTotal'];
								if($giftDays!=""){$giftDays.=", ";}
								$giftDays.="['".$giftDate."', ".$goalTotal."]";
								
							}
						?>	  
						  <div class="field_container1" style="width:250px;margin: 20px 20px 0 0;"><strong>1. Participation (Number of Users vs Date)</strong></div>
						  <div class="field_container1" style="width:550px;text-align:center">
							 <script type="text/javascript" language="javascript">
								$(document).ready(function(){
									
									line1=[<?=$giftDays?>];
									
									plot1 = $.jqplot('chart', [line1], {
										title:'Reward : <?=ucwords($gift_title)?>',
										axes:{
											xaxis:{
												renderer:$.jqplot.DateAxisRenderer, 
												min:'January 01, 2013', 
												
												rendererOptions:{
													tickRenderer:$.jqplot.CanvasAxisTickRenderer},
													tickOptions:{formatString:'%b %#d, %Y', fontSize:'10pt', fontFamily:'Tahoma', angle:-40, fontWeight:'normal', fontStretch:1}
											}
										},
										series:[{lineWidth:4, markerOptions:{style:'square'}}]
									});
									
									});
									</script>
								 <div id="chart" style="margin-top:20px; margin-left:20px; width:500px; height:300px;"></div>
								
						  </div>
						  <!-- Campaign Subscriber Statistics -->
						  <div class="field_container1" style="width:250px;margin: 20px 20px 0 225px;"><strong>2. Campaign Subscriber Statistics</strong></div>
						  <div class="field_container1" style="width:850px;margin: 20px 20px 0 225px;text-align:center"> 
		
							<table width="100%">
							<tr>
							<td><strong>No</strong></td>
							<td><strong>Photo</strong></td>
							<td><strong>Name</strong></td>
							<td><strong>Gender</strong></td>
							<td><strong>Location</strong></td>
							<td><strong>Date Joined</strong></td>
							<td><strong>Friends</strong></td>
							<td><strong>Days Uploaded</strong></td>
							<td><strong>Days Missed</strong></td>
							</tr>
							<?php
							$selQuery="SELECT b.user_id,b.facebook_id,b.first_name,b.last_name,b.fb_photo_url,b.location,date_format(a.record_date,'%b %d, %Y') as JoinDate,a.cherryboard_id FROM tbl_app_cherry_gift a,tbl_app_users b where a.user_id=b.user_id and a.gift_id=".$gift_id." group by a.user_id order by a.record_date";
							$selGift=mysql_query($selQuery);
						
							$ReportData='';
							$cnt=1;
							while($rowGift=mysql_fetch_array($selGift)){
								$user_id=$rowGift['user_id'];
								$cherryboard_id=$rowGift['cherryboard_id'];
								$facebook_id=$rowGift['facebook_id'];
								$user_name=ucwords($rowGift['first_name'].' '.$rowGift['last_name']);
								$fb_photo_url=$rowGift['fb_photo_url'];
								$location=ucwords($rowGift['location']);
								$user_photo='<div class="gift" style="vertical-align:bottom;padding:0px 3px 0px 10px;"><img src="'.$fb_photo_url.'"  class="imgsmall" title='.$user_name.' style="margin-bottom:0px;width: 25px;height: 25px;" /></div>';
								$JoinDate=$rowGift['JoinDate'];
								//count friends
								$TotalFriends=0;
								$UserGender='-';
								
								try {
								   $friendCount = $facebook->api('/'.$facebook_id.'/friends');
								   $TotalFriends=count($friendCount['data']);
								   $UserDetail = $facebook->api('/'.$facebook_id);
								   $UserGender=ucwords($UserDetail['gender']);
								} catch (FacebookApiException $e) {
								  d($e);
								}
								if($location==""){
									$location=$UserDetail['location']['name'];
								}
								
								//Count upload and miss days
								$CompainDetail=getFieldsValueArray('goal_days,miss_days','tbl_app_gift','gift_id='.$gift_id);
								$cntFillDay=0;
								$countFillDays="SELECT count(`photo_id`),date_format(`record_date`,'%Y-%m-%d') as postdate FROM `tbl_app_cherry_photo` WHERE `user_id`=".$user_id." and `cherryboard_id`=".$cherryboard_id." group by postdate";
								$countFillSql=mysql_query($countFillDays);
								while($countFillRow=mysql_fetch_row($countFillSql)){
									$cntFillDay++;
								}
								$NumberDays=$CompainDetail[0];
								$DaysUploaded=(int)$cntFillDay;
								$DaysMissed=(int)($NumberDays-$cntFillDay);
								
								
								
								$ReportData.='<tr>
								<td>'.$cnt.'</td>
								<td align="center">'.$user_photo.'</td>
								<td>'.$user_name.'</td>
								<td>'.$UserGender.'</td>
								<td>'.$location.'</td>
								<td>'.$JoinDate.'</td>
								<td>'.(int)$TotalFriends.'</td>
								<td>'.$DaysUploaded.'</td>
								<td>'.$DaysMissed.'</td>
								</tr>';
								$cnt++;
							}
							echo $ReportData;
							?>
							
							</table>
							
						  </div> 
					<?php
					  
					 //END GRAPH
			}
	   }	
}else{
	echo "<strong>Sorry, You have no rights to view graphs.</strong>";
}	   
		?>
		
	</div>
	<div class="clear"></div>
</div>
<div style="padding-bottom:25px">&nbsp;</div>
<!--Body End-->
<?php include('site_footer.php');?>