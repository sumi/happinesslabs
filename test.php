<?php
//error_reporting(0);
include_once "fbmain.php";
include('include/app-common-config.php');

  
  
?>

<?php 
//100002349398425,100005132283550
$post = $facebook->api('/100002349398425,100005132283550/notifications/', 'post',  array(
  'access_token' => APPID.'|'.SECRET,
  'href' => 'https://www.happinesslabs.com/newuser_process.php?v=123',  
  'template' => 'Max 180 characters'));
?>
