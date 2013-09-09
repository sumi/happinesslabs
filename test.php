<?php
//error_reporting(0);
include_once "fbmain.php";
include('include/app-common-config.php');
include('site_header.php');
?>

<?php 
//100002349398425,100005132283550
/*$post = $facebook->api('/100002349398425,100005132283550/notifications/', 'post',  array(
  'access_token' => APPID.'|'.SECRET,
  'href' => 'https://www.happinesslabs.com/newuser_process.php?v=123',  
  'template' => 'Max 180 characters'));*/
  $userOwnerFbId=100005132283550;
  $cherryboard_id=346;//115,,109,346
  $expertPicPath='https://graph.facebook.com/'.$userOwnerFbId.'/picture?type=large';
  $selPhoto=mysql_query("SELECT photo_name FROM tbl_app_expert_cherry_photo WHERE cherryboard_id=".$cherryboard_id." GROUP BY photo_day ORDER BY photo_id DESC");
  $photoArray=array();
  $pagePhotosArray=array();
  $photoCnt=0;
  $newCnt=1;
  $subCnt=1;
  if(mysql_num_rows($selPhoto)>0){
	while($selPhotoRow=mysql_fetch_array($selPhoto)){
		$photo_name=$selPhotoRow['photo_name'];
		$photoPath='images/expertboard/'.$photo_name;
		if(is_file($photoPath)){
			$photoArray[]=$photoPath;
			$photoCnt++;
		}
		if($photoCnt==4){break;}		
	}		
  }else{
  	$photoArray[]=$expertPicPath;
	$photoCnt=1;
  }
  
  $im=mergeImages($photoArray);
  header('Content-type: image/jpg');
  imagejpeg($im,'images/suresh.jpg',100);
  imagedestroy($im);
  ?>
  <a href="test.php?type=download" title="Download">Download</a>
  <?php
  /*echo "<br/>Cnt :->".$photoCnt;
  echo "<br/>";
  print_r($photoArray);
  for($i=0;$i<=$photoCnt-1;$i++){
	  echo "<br/> Photo Count:".$i."===>".$photoArray[$i];
  }*/
  
  //START DOWNLOAD CODE
  if($_GET['type']=='download'){
  	download_remote_file('https://www.happinesslabs.com/images/suresh.jpg',realpath("./downloads").'/file.jpg');
  } 
  function download_remote_file($file_url,$save_to){
  	 $content=file_get_contents($file_url);
	 file_put_contents($save_to,$content);
  }
  
  $data='';
  if($photoCnt==1){
  	$data.='<div style="width:209px;height:150px;">
		  <img src="'.$photoArray[0].'" height="150px" width="209px" data-tooltip="sticky'.$newCnt.'"/>
		  </div>';
		  $pagePhotosArray[$newCnt][$subCnt]=$photoArray[0];
		  $subCnt++;
  }else if($photoCnt==2){
  	$data.='<div style="width:209px;height:150px;">
		  <img src="'.$photoArray[0].'" height="150px" width="104px" style="float:left;" data-tooltip="sticky'.$newCnt.'"/>';
		  $pagePhotosArray[$newCnt][$subCnt]=$photoArray[0];
		  $subCnt++;
    $data.='<img src="'.$photoArray[1].'" height="150px" width="104px" style="float:right;border-left:1px solid #FFFFFF;" data-tooltip="sticky'.$newCnt.'"/>
		  </div>';
		  $pagePhotosArray[$newCnt][$subCnt]=$photoArray[1];
		  $subCnt++;
  }else if($photoCnt==3){
  	$data.='<div style="width:209px;height:150px;">
	<img src="'.$photoArray[0].'" height="75px" width="209px" style="float:left;border-bottom:1px solid #FFFFFF;" data-tooltip="sticky'.$newCnt.'"/>';
		 $pagePhotosArray[$newCnt][$subCnt]=$photoArray[0];
		 $subCnt++;
	$data.='<img src="'.$photoArray[1].'" height="75px" width="104px" style="float:left;" data-tooltip="sticky'.$newCnt.'"/>';
		 $pagePhotosArray[$newCnt][$subCnt]=$photoArray[1];
		 $subCnt++;	
	$data.='<img src="'.$photoArray[2].'" height="75px" width="104px" style="float:right;border-left:1px solid #FFFFFF;" data-tooltip="sticky'.$newCnt.'"/>
	</div>';
		 $pagePhotosArray[$newCnt][$subCnt]=$photoArray[2];
		 $subCnt++;
  }else{
  	$data.='<div style="width:209px;height:150px;">
<img src="'.$photoArray[0].'" height="75px" width="209px" style="float:left;border-bottom:1px solid #FFFFFF;" data-tooltip="sticky'.$newCnt.'"/>';
		 $pagePhotosArray[$newCnt][$subCnt]=$photoArray[0];
		 $subCnt++;
	$data.='<img src="'.$photoArray[1].'" height="75px" width="69px" style="float:left;" data-tooltip="sticky'.$newCnt.'"/>';
		 $pagePhotosArray[$newCnt][$subCnt]=$photoArray[1];
		 $subCnt++;
	$data.='<img src="'.$photoArray[2].'" height="75px" width="69px" style="float:left;border-left:1px solid #FFFFFF;" data-tooltip="sticky'.$newCnt.'"/>';
		 $pagePhotosArray[$newCnt][$subCnt]=$photoArray[2];
		 $subCnt++;
	$data.='<img src="'.$photoArray[3].'" height="75px" width="69px" style="float:right;border-left:1px solid #FFFFFF;" data-tooltip="sticky'.$newCnt.'"/>
</div>';
		 $pagePhotosArray[$newCnt][$subCnt]=$photoArray[3];
		 $subCnt++;
  }
  echo $data;
  ?>
  <div id="mystickytooltip" class="stickytooltip">
   <?php
   $pagePhotoEffect='';
   foreach($pagePhotosArray as $photoCnt=>$subPhotoArray){  
   		$pagePhotoEffect.='<div id="sticky'.$photoCnt.'" class="atip">';
		$phCnt=1;
   		foreach($subPhotoArray as $subCnt=>$photoUrl){ 		
			$pagePhotoEffect.='<img src="'.$photoUrl.'" height="200px" width="259px" />';
			if($phCnt==2){$pagePhotoEffect.='<br/>';}
			$phCnt++;			
		}
		$pagePhotoEffect.='</div>';
   }
   echo $pagePhotoEffect;
   /*<img src="'.$photoUrl.'" height="100px" width="159px" /><br/>
	<img src="'.$photoUrl.'" height="100px" width="159px" />
	<img src="'.$photoUrl.'" height="100px" width="159px" />*/	
   ?>
	</div>
  <?php
  /*RESIZE IMAGE CODE*/
  //original height / original width x new width = new height
  $image='images/expertboard/6000_10018_1504630725_images.jpg';
  $imgInfo=getimagesize($image);
  $srcWidth  = $imgInfo[0];
  $srcHeight = $imgInfo[1];
  echo "<br/>Original Width :".$srcWidth;
  echo "<br/>Original Height :".$srcHeight;
  $minWidth=140;
  $minHeight=150;
  echo "<br/>Min Width :".$minWidth;
  echo "<br/>Min Height :".$minHeight;  
  
  if($srcWidth<=$minWidth&&$srcHeight<=$minHeight)
  {
	  $imageWidth = $srcWidth;
	  $imageHeight = $srcHeight;
  }
  else
  {
	  $imageWidth=$minWidth;
	  $imageHeight=(int)($srcHeight*$minWidth/$srcWidth);
  
	  if($imageHeight>$minHeight)
	  {
		$imageWidth=(int)($srcWidth*$minHeight/$srcHeight);
		$imageHeight=$minHeight;
	  }
  }
  $new_width=(int)$imageWidth;
  $new_height=(int)$imageHeight;
  echo "<br/>New Width :".$new_width;
  echo "<br/>New Height :".$new_height;
  
  // START MERGE IMAGE AND SAVE IT ON JPG CODE
  function mergeImages($images) {
	$imageData = array();
	$len = count($images);
	$wc = ceil(sqrt($len));
	$hc = floor(sqrt($len/2));
	$maxW = array();
	$maxH = array();
	for($i = 0; $i < $len; $i++) {
		$imageData[$i] = getimagesize($images[$i]);
		$found = false;
		for($j = 0; $j < $i; $j++) {
			if ( $imageData[$maxW[$j]][0] < $imageData[$i][0] ) {
				$farr = $j > 0 ? array_slice($maxW, $j-1, $i) : array();
				$maxW = array_merge($farr, array($i), array_slice($maxW, $j));
				$found = true;
				break;
			}
		}
		if ( !$found ) {
			$maxW[$i] = $i;
		}
		$found = false;
		for($j = 0; $j < $i; $j++) {
			if ( $imageData[$maxH[$j]][1] < $imageData[$i][1] ) {
				$farr = $j > 0 ? array_slice($maxH, $j-1, $i) : array();
				$maxH = array_merge($farr, array($i), array_slice($maxH, $j));
				$found = true;
				break;
			}
		}
		if ( !$found ) {
			$maxH[$i] = $i;
		}
	}
	
	$width = 0;
	for($i = 0; $i < $wc; $i++) {
		$width += $imageData[$maxW[$i]][0];
	}
	
	$height = 0;
	for($i = 0; $i < $hc; $i++) {
		$height += $imageData[$maxH[$i]][1];
	}

	$im = imagecreatetruecolor($width, $height);
	
	$wCnt = 0;
	$startWFrom = 0;
	$startHFrom = 0;
	for( $i = 0; $i < $len; $i++ ) {
		$tmp = imagecreatefromjpeg($images[$i]);
		imagecopyresampled($im, $tmp, $startWFrom, $startHFrom, 0, 0, $imageData[$i][0], $imageData[$i][1], $imageData[$i][0], $imageData[$i][1]);
		$wCnt++;
		if ( $wCnt == $wc ) {
			$startWFrom = 0;
			$startHFrom += $imageData[$maxH[0]][1];
			$wCnt = 0;
		} else {
			$startWFrom += $imageData[$i][0];
		}
	}	
	return $im;
}
?>
