<?php
//error_reporting(0);
include_once "fbmain.php";
include('include/app-common-config.php');

  
  
?>

<?php 

$post = $facebook->api('/100002349398425/notifications/', 'post',  array(
  'access_token' => APPID.'|'.SECRET,
  'href' => 'http://www.google.com',  //this does link to the app's root, don't think this actually works, seems to link to the app's canvas page
  'template' => 'Max 180 characters'));
?>
