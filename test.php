<?php
include_once "fbmain.php";
include('include/app-common-config.php');

$tagCnt='';
$cherryboard_id=116;
$selTagType=mysql_query("SELECT * FROM tbl_app_tag_type ORDER By tag_type_id");
while($selTagTypeRow=mysql_fetch_array($selTagType)){
	  $tagTypeId=(int)$selTagTypeRow['tag_type_id'];	
	  $tagTypeName=trim($selTagTypeRow['tag_type_name']);
	  $tagCnt.='<div style="color:blue;font-size:20px;"><strong>'.$tagTypeName.'</strong></div>';
	  //START FETCH PHOTO TAG DATA
	  $cnt=1;
	  $selTag=mysql_query("SELECT * FROM tbl_app_tag_photo WHERE tag_type=".$tagTypeId." AND cherryboard_id=".$cherryboard_id);
	  while($selTagRow=mysql_fetch_array($selTag)){
		    $tagId=(int)$selTagRow['tag_id'];	
			$cherryboardId=(int)$selTagRow['cherryboard_id'];	
			$photoId=(int)$selTagRow['photo_id'];	
			$tagTitle=trim(ucwords($selTagRow['tag_title']));
			$tagPhoto=trim($selTagRow['tag_photo']);
			$tagPhotoPath='images/expertboard/tag/'.$tagPhoto;			
			$tagCnt.='<div style="color:#006633;text-align:center;display:inline;">
			('.$cnt.')&nbsp;'.($tagPhoto!=''?''.$tagTitle.'<br/><img src="'.$tagPhotoPath.'" height="50" width="50"><br/>':''.$tagTitle.'<br/>').'</div>';
			$cnt++;
	  }
}
echo $tagCnt;
?>