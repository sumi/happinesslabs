<?php 
error_reporting(0);
//include_once "fbmain.php";
//include('include/app-common-config.php');
include('include/app-db-connect.php');
include('include/app_functions.php');

$type=$_GET['type'];
$div_name=$_GET['div_name'];
$ajax_common_data='';

//START SET REWARD AND EXPERT SESSION CODE
if($type=="set_reward_session"){	  
	 $url=trim($_GET['url']);
	 //START SET REWARD AND CHALLENGE SESSION
	 if($url!=''){ 
	 	$_SESSION['redirect']=$url;
	 }
	 $ajax_common_data=$type."##===##".$div_name."##===##".$ajax_common_data;
	 echo $ajax_common_data;  
}
?>