<?php
error_reporting(0);
include_once "fbmain.php";
include('include/app-common-config.php');
?>
<?php
	   $cherryboard_id=(int)$_GET['cherryboard_id'];
	   $type=trim($_GET['type']);
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
	$destPath='images/download/happiness.jpg';
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
			$post_data=array('message' => $photoCaption,'source' => '@'.realpath($destPath));
			$apiResponse=$facebook->api('/me/photos','POST',$post_data);
			echo '<strong><font color="#006633">Story infographics image share on your facebook wall please see it on facebook.</strong></font>';		
		  }catch(FacebookApiException $e){
			echo $e;
		  }
	   } 
	}//OAuthException: An active access token must be used to query information about the current user.
	//START SEND ON EMAIL CODE
	/*if($type=='email'){
	   $path='@'.realpath($destPath);
	   $emailId=trim(getFieldValue('email_id','tbl_app_users','user_id='.USER_ID));
	   $from="info@30daysnew.com";
	   $visitor_email="suresh.uniquewebinfo@gmail.com";
	   $subject="Test mail for story infographics image";
	   $message=new Mail_mime(); 
	   $message->setTXTBody($text);		 
	   $message->addAttachment($path);		 
	   $body=$message->get();		 
	   $extraheaders=array("From"=>$from,"Subject"=>$subject,"Reply-To"=>$visitor_email);		 
	   $headers=$message->headers($extraheaders);		 
	   $mail=Mail::factory("mail");		 
	   $mail->send($to,$headers,$body);
	}*/
?>