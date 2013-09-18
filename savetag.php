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

if($_POST['type']=="remove")
{
  $tag_id=$_POST['tag_id'];
  $sql="DELETE FROM tbl_app_expert_tag_photo WHERE tag_id='".$tag_id."'";
  $qry=mysql_query($sql);
}

// fetch all tags
$sql = "SELECT * FROM tbl_app_expert_tag_photo ORDER BY tag_id";//WHERE pic_id=".$pic_id."
$qry = mysql_query($sql);
$rs = mysql_fetch_array($qry);

if($rs){
  do{
    echo '<li rel="'.$rs['tag_id'].'"><a>'.$rs['tag_title'].'</a> (<a class="remove">Remove</a>)</li>';
  }while($rs=mysql_fetch_array($qry));
}
?>
