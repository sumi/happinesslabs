<?php
error_reporting(0);
include_once "fbmain.php";
include('include/app-common-config.php');
$message='';
	echo "-->".$_SESSION['fb_access_token'];
 echo"==>".$user_id = FB_ID;

  $apprequest_url ="https://graph.facebook.com/100002349398425/apprequests?message='NewInvite'&data='INSERT_STRING_DATA'&".$_SESSION['fb_access_token']."&method=post";

  $result = file_get_contents($apprequest_url);
  echo $result;
  
  
?>

<?php 
/*
  $app_id = APPID;
  $app_secret = SECRET;

  $token_url = "https://graph.facebook.com/oauth/access_token?" .
    "client_id=" . $app_id .
    "&client_secret=" . $app_secret .
    "&grant_type=client_credentials";

  $app_access_token = file_get_contents($token_url);

  $user_id = '';

  $apprequest_url ="https://graph.facebook.com/" .
    $user_id .
    "/apprequests?message=’INSERT_UT8_STRING_MSG’" . 
    "&data=’INSERT_STRING_DATA’&"  .   
    $app_access_token . “&method=post”;

  $result = file_get_contents($apprequest_url);
  echo(“Request id number: ”, $result);
  */
?>
