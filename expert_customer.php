<?php
include_once "fbmain.php";
include('include/app-common-config.php');
$cherryboard_id=$_GET['cbid'];
$expertboard_id=getFieldValue('expertboard_id','tbl_app_expert_cherryboard','cherryboard_id='.$cherryboard_id);
?>
<?php
include('site_header.php');
?>
<div id="main">
	<div id="wrapper" style="background-color:#FFFFFF;padding-top:100px;">
<?php
$expertCnt='';
$selCustomer="SELECT DISTINCT user_id FROM tbl_app_expert_cherryboard WHERE expertboard_id=".$expertboard_id;		
	$selExpCustomer=mysql_query($selCustomer);
	if(mysql_num_rows($selExpCustomer)>0){
	
		while($fetchExpCustomer=mysql_fetch_array($selExpCustomer)){			
			$customerUserId=(int)$fetchExpCustomer['user_id'];
			$cherryBoardId=(int)getFieldValue('cherryboard_id','tbl_app_expert_cherryboard','expertboard_id='.$expertboard_id.' and user_id='.$customerUserId);
			//CUSTOMER PROFILE PICTURE SECTION
			$customerDetail=getUserDetail($customerUserId);
			$customerFbId=$customerDetail['fb_id'];
			$customerName=$customerDetail['name'];
			$expertPicPath='https://graph.facebook.com/'.$customerFbId.'/picture?type=large';
		    $expertCnt.=''.($customerUserId>0?'<img src="'.$expertPicPath.'" 
			style="padding-left:30px;padding-bottom:5px;height:150px;width:200px;" />':'').'';
			//EXPERT BOARD PHOTO DAYS
		    $qryphoto="select * from tbl_app_expert_cherry_photo where cherryboard_id=".$cherryBoardId." order by photo_id";
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
			//$photoDayArr = array_slice($photoDayArr,1,3);
		for($i=1;$i<=3;$i++){
		  if(in_array($i,$photoDayArr)){
			//CUSTOMER PICTURE SECTION
			$selCustomerPic=mysql_query("SELECT photo_name FROM tbl_app_expert_cherry_photo WHERE cherryboard_id=".$cherryBoardId." and photo_day='".$i."' ORDER BY photo_id");
			while($selCustomerPicRow=mysql_fetch_array($selCustomerPic)){
				$photoName=$selCustomerPicRow['photo_name'];
				$photoPath='images/expertboard/'.$photoName;
				if(is_file($photoPath)){
					$expertCnt.='<img src="'.$photoPath.'" 
					style="padding-left:30px;padding-bottom:5px;height:150px;width:200px;" />';
				}
			}			
		  }else{
		  	$photoPath='images/cherryboard/no_image.png'; 
		  	$expertCnt.='<img src="'.$photoPath.'" 
					style="padding-left:30px;padding-bottom:5px;height:150px;width:200px;" />';
		  }		  
		 }
		 $expertCnt.='<br/>';
		}					
	}else{
		$expertCnt.='<strong>No Customers In This ExpertBoard</strong>';
	}
	echo $expertCnt;
?>
	<br/><br/>
	</div>
    <div class="clear"></div>
</div> <!--<hr style="color:#851D40;background-color:#851D40;height:5px;" />-->
<?php //include('fb_expert_invite.php');?>
<?php include('site_footer.php');?> 