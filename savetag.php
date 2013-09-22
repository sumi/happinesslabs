<?php
error_reporting(0);
include_once "fbmain.php";
include('include/app-common-config.php');
$pic_id=(int)$_POST['pic_id'];
if($_POST['type']=="insert")
{  
  $name=$_POST['name'];
  $tag_x=$_POST['pic_x'];
  $tag_y=$_POST['pic_y'];
  $cherryboard_id=(int)getFieldValue('cherryboard_id','tbl_app_expert_cherry_photo','photo_id='.$pic_id);
  $sql="INSERT INTO  tbl_app_expert_tag_photo(tag_id,cherryboard_id,photo_id,user_id,tag_title,tag_x,tag_y) VALUES (NULL,'".$cherryboard_id."','".$pic_id."','".(int)USER_ID."','".$name."','".$tag_x."','".$tag_y."')";
  $qry=mysql_query($sql);
  exit(0);
}
/*if($_POST['type']=="remove")
{
  $tag_id=$_POST['tag_id'];
  $sql="DELETE FROM tbl_app_expert_tag_photo WHERE tag_id='".$tag_id."'";
  $qry=mysql_query($sql);
}*/
//START FETCH TAG DATA
if($_POST['type']=="display"){
  $selTag=mysql_query("SELECT * FROM tbl_app_expert_tag_photo WHERE photo_id=".$pic_id." ORDER BY tag_id");
  while($selTagRow=mysql_fetch_array($selTag)){
  		$tag_id=(int)$selTagRow['tag_id'];	
	    $tag_title=trim(ucwords($selTagRow['tag_title']));	
	    $tag_x=(int)$selTagRow['tag_x'];	
	    $tag_y=(int)$selTagRow['tag_y'];
	    $tagY=$tag_y-25;
  		echo '<div id="divHover" rel="'.$tag_id.'" class="tagview1 type1" style="left:'.$tag_x.'px;top:'.$tag_y.'px;"></div><div class="tagview" style="left:'.$tag_x.'px;top:'.$tagY.'px;" id="view_'.$tag_id.'">'.$tag_title.'</div>';
  }
}
?>
