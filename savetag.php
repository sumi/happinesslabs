<?php
error_reporting(0);
include_once "fbmain.php";
include('include/app-common-config.php');
$pic_id=(int)$_POST['pic_id'];
if($_GET['type']=="insert")
{  
  $tag_photo=$_FILES['file']['name'];
  if($tag_photo!=''){
  	 $tag_photo=rand().'_'.$_FILES['file']['name'];
  }
  
  $tmp_file_name=$_FILES['file']['tmp_name'];
  $uploadDirPath='images/expertboard/tag/'.$tag_photo;  
  if($_SERVER['SERVER_NAME']=="localhost"){
  	$retval=move_uploaded_file($tmp_file_name,$uploadDirPath);  
  }else{
  	$thumb_command=$ImageMagic_Path."convert ".$tmp_file_name." -thumbnail 100 x 100 ".$uploadDirPath;
	$last_line=system($thumb_command,$retval);
  }
  //if($retval){
	 $name=$_POST['name'];
	 $tagtype=$_POST['tagtype'];
	 $tag_x=(int)$_POST['pic_x'];
	 $tag_y=(int)$_POST['pic_y'];
	 $cherryboard_id=(int)getFieldValue('cherryboard_id','tbl_app_expert_cherry_photo','photo_id='.$pic_id);
	 $sql="INSERT INTO  tbl_app_tag_photo
	 (tag_id,cherryboard_id,photo_id,user_id,tag_type,tag_title,tag_photo,tag_x,tag_y) VALUES (NULL,'".$cherryboard_id."','".$pic_id."','".(int)USER_ID."','".$tagtype."','".$name."','".$tag_photo."','".$tag_x."','".$tag_y."')";
	 $qry=mysql_query($sql);
	 exit(0);
  //}
}
/*if($_POST['type']=="remove")
{
  $tag_id=$_POST['tag_id'];
  $sql="DELETE FROM tbl_app_expert_tag_photo WHERE tag_id='".$tag_id."'";
  $qry=mysql_query($sql);
}*/
//START FETCH TAG DATA
if($_POST['type']=="display"){
  $selTag=mysql_query("SELECT * FROM tbl_app_tag_photo WHERE photo_id=".$pic_id." ORDER BY tag_id");
  while($selTagRow=mysql_fetch_array($selTag)){
  		$tag_id=(int)$selTagRow['tag_id'];	
	    $tag_title=trim(ucwords($selTagRow['tag_title']));	
		$tag_photo=trim($selTagRow['tag_photo']);	
	    $tag_x=(int)$selTagRow['tag_x'];	
	    $tag_y=(int)$selTagRow['tag_y'];
		$tagPhotoPath='images/expertboard/tag/'.$tag_photo;
	    $tagY=$tag_y-25;
		$tagX=$tag_x+10;
  		echo '<div id="divHover" rel="'.$tag_id.'" class="tagview1 type1" style="left:'.$tag_x.'px;top:'.$tag_y.'px;"></div><div class="tagview" style="left:'.$tagX.'px;top:'.$tagY.'px;" id="view_'.$tag_id.'">'.($tag_photo!=''?'<img src="'.$tagPhotoPath.'" height="100" width="100"><br/>'.$tag_title.'':''.$tag_title.'').'</div>';
  }
}
?>
