<?php
error_reporting(0);
include_once "fbmain.php";
include('include/app-common-config.php');
//include('site_header.php');
?>
<?php
	   $cherryboard_id=(int)$_GET['cherryboard_id'];
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
			//if($photoCnt==4){break;}		
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
		//echo '<img src="'.$destPath.'"/>';
	}//-tile x2 === -tile x4	
	//START DOWNLOAD IMAGE SCRIPT
	//unlink($destPath);
	$FileName=$urlArray[(count($destPath)-1)];
	header("Content-Type: image/jpeg");
	header("Cache-Control: no-cache");
	header("Accept-Ranges: none");
	header("Content-Disposition: attachment; filename=\"".$FileName."\"");
	readfile($destPath);
?>