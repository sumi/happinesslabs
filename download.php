<?php
error_reporting(0);
include_once "fbmain.php";
include('include/app-common-config.php');
?>
<?php	   
	   $type=trim($_GET['type']);
	   if($type=='email'){
	   	  include('site_header.php');	
	   	  $cherryboard_id=(int)$_POST['story_id'];
		  $rnd=rand();
		  $destPath='images/download/'.$rnd.'_happiness.jpg';
	   }else{
	   	  $cherryboard_id=(int)$_GET['cherryboard_id'];
		  $destPath='images/download/happiness.jpg';
	   }
	   if($cherryboard_id>0){
	   	  $user_id=(int)getFieldValue('user_id','tbl_app_expert_cherryboard','cherryboard_id='.$cherryboard_id);
	   if($user_id>0){
	   	  $userOwnerFbId=trim(getFieldValue('facebook_id','tbl_app_users','user_id='.$user_id));
	   }
	   if($userOwnerFbId!=''){
	   	  $expertPicPath='https://graph.facebook.com/'.$userOwnerFbId.'/picture?type=large';
	   }
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
		}		
	  }else{
		$photoArray[]=$expertPicPath;
		$photoCnt=1;
	  } 	
	}	
	$len=count($photoArray);
	if($len==1){
		exec("montage ".$photoArray[0]." -geometry +2+2 ".$destPath."");
	}else if($len==2){
	   exec("convert ".$photoArray[0]." ".$photoArray[1]." +append -quality 100 -geometry +2+2 ".$destPath."");
	}else if($len==3){
		exec("convert ".$photoArray[0]." ".$photoArray[1]." +append ".$photoArray[2]." -append -quality 100  -geometry +2+2 ".$destPath."");
	}else{
		$strVar='';
		for($i=0;$i<=$len;$i++){
			$strVar.=$photoArray[$i].' ';
		}
		exec("montage ".$strVar." -quality 100 -geometry +2+2 ".$destPath."");
	}
		
	//START DOWNLOAD IMAGE SCRIPT
	if($type=='download'){
		//unlink($destPath);
		$FileName=$urlArray[(count($destPath)-1)];
		header("Content-Type: image/jpeg");
		header("Cache-Control: no-cache");
		header("Accept-Ranges: none");
		header("Content-Disposition: attachment; filename=\"".$FileName."\"");
		readfile($destPath);
	}
	
	//START SHARE ON FACEBOOK CODE
	if($type=='fbshare'){
	   if(FB_ID!=''){	   	
		  try{
			$photoCaption='Share story infographics image on facebook';	 
			$post_data=array('message' => $photoCaption, 'access_token' => $_SESSION['fb_access_token'],
			'source' => '@'.realpath($destPath));
			$apiResponse=$facebook->api('/me/photos','POST',$post_data);
			echo '<strong><font color="#006633">Story infographics image share on your facebook wall please see it on facebook.</strong></font>';		
		  }catch(FacebookApiException $e){
			echo $e;
		  }
	   } 
	}
		
	//START SEND ON EMAIL CODE
	if($type=='email'){
	   $emailId=trim($_POST['email_id']);
	   if($emailId==''){
	   	  $emailId=trim(getFieldValue('email_id','tbl_app_users','user_id='.USER_ID));
	   }	   
	   $to=$emailId;	
	   $path='https://www.happinesslabs.com/'.$destPath;		
	   $subject="Sent story infographic image on email";
	   $message='<table>
				 <tr><td>Hi,</td></tr>
				 <tr><td>&nbsp;</td></tr>
				 <tr><td><img src="'.$path.'" /></td></tr>
				 <tr><td>&nbsp;</td></tr>
				 <tr><td>Love</td></tr>
				 <tr><td>'.REGARDS.'</td></tr>
				 </table>';
	   SendMail($to,$subject,$message);
	   if(SendMail){
	   echo '<strong><font color="#006633">Story infographics image sent on email.</strong></font>';
	   }
	}
?>