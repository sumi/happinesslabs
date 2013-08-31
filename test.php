<?php
error_reporting(0);
include_once "fbmain.php";
include('include/app-common-config.php');
$message='';

  $user_id = USER_ID;

  $apprequest_url ="https://graph.facebook.com/".$user_id."/apprequests?message='NewInvite'"."&data='INSERT_STRING_DATA'&".$_SESSION['fb_access_token']."&method=post";

  $result = file_get_contents($apprequest_url);
  echo("App Request sent?", $result);
?>
