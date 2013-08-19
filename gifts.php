<?php
	include_once "fbmain.php";
	include('include/app-common-config.php');
	$type=trim($_GET['type']);
		
//START ADD GIFT CODE AND DIV
if(isset($_POST['btnAddCampaign'])){
		$gift_title=trim($_POST['gift_title']);
		$campaign_title=trim(addslashes($_POST['campaign_title']));
		$campaign_detail=trim($_POST['campaign_detail']);
		$category_id=(int)$_POST['category_id'];
		$sponsor_name=trim($_POST['sponsor_name']);
		$sponsor=(int)$_POST['sponsor'];
		$campaign_type=(int)$_POST['campaign_type'];
		
		$file_name= rand().'_'.trim($_FILES['gift_photo']['name']);
		$file_name=str_replace(' ','_',$file_name);
		$file_name=str_replace('-','_',$file_name);
		
		$sponsor_file_name= rand().'_'.trim($_FILES['sponsor_logo']['name']);
		$sponsor_file_name=str_replace(' ','_',$sponsor_file_name);
		$sponsor_file_name=str_replace('-','_',$sponsor_file_name);
		
		$goal_days=(int)$_POST['goal_days'];
		$miss_days=(int)$_POST['miss_days'];
		$sponsorship_url=$_POST['sponsorship_url'];
		
		$uploadTempdir = 'images/gift/temp/'.$file_name; 
		$uploaddir = 'images/gift/'.$file_name; 
		
		$sponsor_uploadTempdir = 'images/gift/temp/'.$sponsor_file_name; 
		$sponsor_uploaddir = 'images/gift/'.$sponsor_file_name;
		
		if(trim($gift_title)!=""&&$category_id>0&&$file_name!=""&&$goal_days>0){
			$checkGift=(int)getFieldValue('gift_id','tbl_app_gift','gift_title="'.$gift_title.'" and category_id="'.$category_id.'"');
			if($checkGift==0){		
				 //gift photo upload
				 if(move_uploaded_file($_FILES['gift_photo']['tmp_name'],$uploadTempdir)){					
					if($_SERVER['SERVER_NAME']=="localhost"){
						$retval=copy($uploadTempdir,$uploaddir);
					}else{
						$thumb_command=$ImageMagic_Path."convert ".$uploadTempdir." -thumbnail 150 x 150 ".$uploaddir;
						$last_line=system($thumb_command, $retval);
					}
				//sponsor photo upload	
				  if(move_uploaded_file($_FILES['sponsor_logo']['tmp_name'],$sponsor_uploadTempdir)){		
						if($_SERVER['SERVER_NAME']=="localhost"){
							$RetVal=copy($sponsor_uploadTempdir,$sponsor_uploaddir);
						}else{
							$Thumb_Command=$ImageMagic_Path."convert ".$sponsor_uploadTempdir." -thumbnail 150 x 150 ".$sponsor_uploaddir;
							$last_line=system($Thumb_Command, $RetVal);
						}
					//START CREATE COMPAIN
					 $ins_sel="INSERT INTO tbl_app_gift (gift_id,category_id,gift_title,gift_photo,is_system, sponsor,record_date,goal_days,miss_days,sponsor_url,user_id,campaign_title,campaign_detail,sponsor_logo,sponsor_name,campaign_type)
					VALUES (NULL,'".$category_id."','','','1','".$sponsor."', CURRENT_TIMESTAMP,'".$goal_days."','".$miss_days."','".$sponsorship_url."','".(int)USER_ID."','".$campaign_title."','".$campaign_detail."','".$sponsor_file_name."','".$sponsor_name."','".$campaign_type."')";
					$ins_sql=mysql_query($ins_sel);	
					$new_gift_id=mysql_insert_id();
					if($new_gift_id>0){
						//START CREATE REWARD
						$ins_reward="INSERT INTO tbl_app_gift (gift_id,category_id,gift_title,gift_photo,is_system, sponsor,record_date,goal_days,miss_days,sponsor_url,user_id,campaign_title,campaign_detail,sponsor_logo,sponsor_name,campaign_type,campaign_id)
						VALUES (NULL,'".$category_id."','".$gift_title."','".$file_name."','1','".$sponsor."', CURRENT_TIMESTAMP,'".$goal_days."','".$miss_days."','".$sponsorship_url."','".USER_ID."','".$campaign_title."','".$campaign_detail."','".$sponsor_file_name."','".$sponsor_name."','".$campaign_type."','".$new_gift_id."')";
						$ins_rewardSql=mysql_query($ins_reward);	
						if($new_gift_id>0){
							for($i=1;$i<=$goal_days;$i++){
								$insDays="INSERT INTO `tbl_app_campaign_days` (`campaign_day_id`, `campaign_id`, `day_no`, `day_title`, `record_date`) VALUES (NULL, '".$new_gift_id."', '".$i."', 'Day ".$i."', CURRENT_TIMESTAMP)";
								$insDaysSql=mysql_query($insDays);
							}
						}
						//END CREATE REWARD
						echo "<script>document.location='gift_profile.php?gid=".$new_gift_id."';</script>";
					 }	
				  }				
				}
			}	
		}
	}
?>
<?php include('site_header.php');
if($_GET['msg']=="addche"){
	$msg="Cherryboard added successfully.";
}
?>	
<!--Body Start-->
<div id="wrapper">
<?php
	$userGiftIds=mysql_query("select gift_id from tbl_app_cherry_gift where user_id=".USER_ID." and gift_id>50");
	$userCampaignIdsArr=array();
	$userRewardsIdsArr=array();
	while($userGiftRoes=mysql_fetch_array($userGiftIds)){
		$usergift_id=$userGiftRoes['gift_id'];
		$campaignId=(int)getFieldValue('campaign_id','tbl_app_gift','gift_id='.$usergift_id);
		if($campaignId>0){
			$userCampaignIdsArr[]=$campaignId;		
		}
		$userRewardsIdsArr[]=$usergift_id;
	}
	//print_r($userCampaignIdsArr);
	if($type=='gift'||getSuperAdmin(USER_ID)==1){
		$sel=mysql_query("select * from tbl_app_gift where gift_id>50 and campaign_id!='0' order by gift_id");
	}else if($type=='campaign'){
		$sel=mysql_query("select * from tbl_app_gift where gift_id>50 and campaign_id!='0' and gift_id in (".implode(',',$userRewardsIdsArr).") order by gift_id");
	}
	$giftCnt='<table border="0"><tr>';
	$newCherryboardId=0;
	if(mysql_num_rows($sel)>0){
	    $cnt=1;
		while($row=mysql_fetch_array($sel)){
			if($cnt==4){$giftCnt.='</tr><tr>';$cnt=1;}
			
			$gift_id=$row['gift_id'];
			$campaignId=(int)getFieldValue('campaign_id','tbl_app_gift','gift_id='.$gift_id);
			$campaign_title=ucwords($row['campaign_title']);
			$gift_title=ucwords($row['gift_title']);
			$sponsor_name=ucwords($row['sponsor_name']);
			$gift_photo=$row['gift_photo'];
			$sponsor_logo=$row['sponsor_logo'];
			$giftPath='images/gift/'.$gift_photo;
			$sponsorPath='images/gift/'.$sponsor_logo;
			$campaign_id=(int)$row['campaign_id'];
			$compainDivVar='#tblgift'.$gift_id;
			
			
			if(is_file($giftPath)){
				$giftCnt.='<td><div class="gift_center">
				<a href="gift_profile.php?gid='.$campaignId.''.$compainDivVar.'">
				<img src="'.$giftPath.'" class="imgbig"></a><br>
				'.(trim($campaign_title)!=''?''.getLimitString($campaign_title,50).'<br>':'').'
				'.$gift_title.'<br>
				'.(trim($sponsor_name)!=''?'<strong>Sponsored by :</strong><br/>'.$sponsor_name.'<br>':'').'
				'.(is_file($sponsorPath)?'<a href="gift_profile.php?gid='.$campaignId.'"><img src="'.$sponsorPath.'" class="imgsmall"></a><br>':'<br>');
				
				$campaignId=(int)getFieldValue('campaign_id','tbl_app_gift','gift_id='.$gift_id);
				//print_r($userCampaignIdsArr);
				if(in_array($campaignId,$userCampaignIdsArr)){
					$cherryboard_id=(int)getFieldValue('cherryboard_id','tbl_app_cherry_gift','gift_id="'.$gift_id.'" AND user_id='.USER_ID);
					if($cherryboard_id>0){
						$giftCnt.='<a href="cherryboard.php?cbid='.$cherryboard_id.'" name="View Goal" class="btn_small" title="Join">View Goal</a>';
					}
				}else{
					  $giftCnt.='<a href="gift_profile.php?jid='.$gift_id.'" name="Join" class="btn_small" title="Join">Join</a>';
				}
				$giftCnt.='</div></td>';
		    }
			$cnt++;
		}

		for($i=$cnt;$i<=3;$i++){
			$giftCnt.='<td>&nbsp;</td>';
		}
	}else{
		$giftCnt.='<td><strong>'.($type=='campaign'?'No Reward':'No Rewards').'</strong></td>';
	}
	echo $giftCnt.'</tr></table>';	
?>
<div class="clear"></div>
</div>
<!--Gray body End-->
<!--Body End-->
<?php include('site_footer.php');?> 