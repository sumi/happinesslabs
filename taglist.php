<?php
error_reporting(0);
include_once "fbmain.php";
include('include/app-common-config.php');
// fetch all tags
//$pic_id=(int)$_GET['pic_id'];
$sql="SELECT * FROM tbl_app_expert_tag_photo"; //WHERE pic_id=".$pic_id;
$qry=mysql_query($sql);
$rs=mysql_fetch_array($qry);

if($rs){
  do{
    echo '<div class="tagview" style="left:'.$rs['tag_x'].'px;top:'.$rs['tag_y'].'px;" id="view_'.$rs['tag_id'].'"> <span style="background-color:#3476CE;">'.$rs['tag_title'].'&nbsp;</span></div>';
  }while($rs=mysql_fetch_array($qry));
}
?>