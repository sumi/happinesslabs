<?php
include_once "fbmain.php";
include('include/app-common-config.php');
$cherryboard_id=$_GET['ebid'];
$expertboard_id=(int)getFieldValue('expertboard_id','tbl_app_expert_cherryboard','cherryboard_id='.$cherryboard_id);
if($expertboard_id>0){
	$expert_owner_id=(int)getFieldValue('user_id','tbl_app_expertboard','expertboard_id='.$expertboard_id);
}

?>
<?php include('site_header.php');?>
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
if($expert_owner_id==USER_ID||USER_ID==26){
	
		if($cherryboard_id>0){
			$main_board=getFieldValue('main_board','tbl_app_expert_cherryboard','cherryboard_id='.$cherryboard_id);
			if($main_board==1){
					$ExpertDetail=getFieldsValueArray('expertboard_title,expertboard_detail,user_id','tbl_app_expertboard','expertboard_id='.$expertboard_id);
					$expertboard_title=ucwords($ExpertDetail[0]);
					$expertboard_detail=$ExpertDetail[1];
					$user_id=$ExpertDetail[2];
					$UserDetail=getUserDetail($user_id,'uid');
					$UserFbId=$UserDetail['fb_id'];
					$UserName=$UserDetail['name'];
					
					$expertPicPath='https://graph.facebook.com/'.$UserFbId.'/picture?type=large';
					$ExpertCnt='<div class="field_container1">
							  <div align="center">
								<a href="expert_cherryboard.php?cbid='.$cherryboard_id.'"><img src="'.$expertPicPath.'" width="100" height="100" class="profile_img_big1"></a><br><br>
								<div class="feed_comment1"><a href="expert_cherryboard.php?cbid='.$cherryboard_id.'" style="text-decoration:none;color:#000000"><strong>'.$expertboard_title.'</strong></a></div>
								'.$UserName.'
							 </div>
						  </div>';
					echo $ExpertCnt;
					 
					 //START GRAPH
					 if($user_id==USER_ID||USER_ID==26){
						//Graph for the created expertboard board date wise
						$selExpert=mysql_query("SELECT cherryboard_id,count(`cherryboard_id`) as ExpertTotal,date_format(`record_date`,'%Y-%m-%d') as Expert_date FROM `tbl_app_expert_cherryboard` where `cherryboard_id`=".$cherryboard_id." group by `Expert_date`");
						
							$ExpertDays='';
							while($rowExpert=mysql_fetch_array($selExpert)){
								
								$ExpertDate=$rowExpert['Expert_date'];
								$goalTotal=(int)$rowExpert['ExpertTotal'];
								if($ExpertDays!=""){$ExpertDays.=", ";}
								$ExpertDays.="['".$ExpertDate."', ".$goalTotal."]";
								
							}
						?>	  
						  <!-- USER JOINED PEOPLE -->
						 <div class="field_container1" style="width:115px;"><strong>1. Customers</strong></div>
						  <div class="field_container1" style="width:750px;text-align:center"> 
		
							<table width="100%">
							<tr>
							<td><strong>No</strong></td>
							<td><strong>Customer</strong></td>
							<td><strong>Location</strong></td>
							<td><strong>Join Date</strong></td>
							<td>&nbsp;</td>
							</tr>
							<?php
							$selQuery="SELECT b.user_id,b.facebook_id,b.first_name,b.last_name,b.fb_photo_url,b.location,date_format(a.record_date,'%b %d, %Y') as JoinDate,a.cherryboard_id FROM tbl_app_expert_cherryboard a,tbl_app_users b where a.user_id=b.user_id and a.expertboard_id=".$expertboard_id." and a.main_board='0' group by a.user_id order by a.record_date";
							$selExpert=mysql_query($selQuery);
						
							$ReportData='';
							$cnt=1;
							while($rowExpert=mysql_fetch_array($selExpert)){
								$user_id=$rowExpert['user_id'];
								$cherryboard_id=$rowExpert['cherryboard_id'];
								$facebook_id=$rowExpert['facebook_id'];
								$user_name=ucwords($rowExpert['first_name'].' '.$rowExpert['last_name']);
								$fb_photo_url=$rowExpert['fb_photo_url'];
								$location=ucwords($rowExpert['location']);
								$user_photo='<div class="Expert" style="vertical-align:bottom;padding:0px 3px 0px 10px;"><img src="'.$fb_photo_url.'"  class="imgsmall" title='.$user_name.' style="margin-bottom:0px;width: 25px;height: 25px;" /></div>';
								$JoinDate=$rowExpert['JoinDate'];
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
								
								$ReportData.='<tr>
								<td valign="top">'.$cnt.'.</td>
								<td align="center" valign="top">'.$user_photo.''.$user_name.'</td>
								<td valign="top">'.$location.'</td>
								<td valign="top">'.$JoinDate.'</td>
								<td valign="top"><a href="expert_cherryboard.php?cbid='.$cherryboard_id.'" title="View Board" style="text-decoration:none;" />View Board</a></td>
								</tr>';
								$cnt++;
							}
							echo $ReportData;
							?>							
							</table>
							
						  </div> 
					<?php
					  }
					 //END GRAPH
			}else{
				
					$ExpertDetail=getFieldsValueArray('Expert_title,Expert_photo,sponsor_url,user_id','tbl_app_Expert','Expert_id='.$Expert_id);
					
					$Expert_title=ucwords($ExpertDetail[0]);
					$Expert_photo=$ExpertDetail[1];
					$sponsorship_url=$ExpertDetail[2];
					$user_id=$ExpertDetail[3];
					$ExpertCnt='<div class="field_container1">
							  <div align="center">
								<a href="Expert_profile.php?gid='.$Expert_id.'"><img src="images/Expert/'.$Expert_photo.'" width="100" height="100" class="profile_img_big1"></a><br><br>
								<div class="feed_comment1"><a href="Expert_profile.php?gid='.$Expert_id.'" style="text-decoration:none;color:#000000"><strong>'.$Expert_title.'</strong></a></div><br>
								<a class="gray_link" href="'.$sponsorship_url.'" target="_blank">'.$sponsorship_url.'</a>
							 </div>
						  </div>';
					echo $ExpertCnt;
					 
					 //START GRAPH
					 
						//Graph for the created goal board on the compain Expert date wise
						$selExpert=mysql_query("SELECT Expert_id,count(`cherry_Expert_id`) as ExpertTotal,date_format(`record_date`,'%Y-%m-%d') as Expert_date FROM `tbl_app_cherry_Expert` where `Expert_id`=".$Expert_id." group by `Expert_date`");
						
							$ExpertDays='';
							while($rowExpert=mysql_fetch_array($selExpert)){
								
								$ExpertDate=$rowExpert['Expert_date'];
								$goalTotal=(int)$rowExpert['ExpertTotal'];
								if($ExpertDays!=""){$ExpertDays.=", ";}
								$ExpertDays.="['".$ExpertDate."', ".$goalTotal."]";
								
							}
						?>	  
						  <div class="field_container1" style="width:250px;margin: 20px 20px 0 0;"><strong>1. Participation (Number of Users vs Date)</strong></div>
						  <div class="field_container1" style="width:550px;text-align:center">
							 <script type="text/javascript" language="javascript">
								$(document).ready(function(){
									
									line1=[<?=$ExpertDays?>];
									
									plot1 = $.jqplot('chart', [line1], {
										title:'Reward : <?=ucwords($Expert_title)?>',
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
							$selQuery="SELECT b.user_id,b.facebook_id,b.first_name,b.last_name,b.fb_photo_url,b.location,date_format(a.record_date,'%b %d, %Y') as JoinDate,a.cherryboard_id FROM tbl_app_cherry_Expert a,tbl_app_users b where a.user_id=b.user_id and a.Expert_id=".$Expert_id." group by a.user_id order by a.record_date";
							$selExpert=mysql_query($selQuery);
						
							$ReportData='';
							$cnt=1;
							while($rowExpert=mysql_fetch_array($selExpert)){
								$user_id=$rowExpert['user_id'];
								$cherryboard_id=$rowExpert['cherryboard_id'];
								$facebook_id=$rowExpert['facebook_id'];
								$user_name=ucwords($rowExpert['first_name'].' '.$rowExpert['last_name']);
								$fb_photo_url=$rowExpert['fb_photo_url'];
								$location=ucwords($rowExpert['location']);
								$user_photo='<div class="Expert" style="vertical-align:bottom;padding:0px 3px 0px 10px;"><img src="'.$fb_photo_url.'"  class="imgsmall" title='.$user_name.' style="margin-bottom:0px;width: 25px;height: 25px;" /></div>';
								$JoinDate=$rowExpert['JoinDate'];
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
								$CompainDetail=getFieldsValueArray('goal_days,miss_days','tbl_app_Expert','Expert_id='.$Expert_id);
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