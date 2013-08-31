<?php
//error_reporting(0);
//include_once "fbmain.php";
//include('include/app-common-config.php');

  
  
?>

<?php 

  $app_id = APPID;
  $app_secret = SECRET;

  $token_url = "https://graph.facebook.com/oauth/access_token?" .
    "client_id=" . $app_id .
    "&client_secret=" . $app_secret .
    "&grant_type=client_credentials";

  $app_access_token = file_get_contents($token_url);

  $user_id = '100001211022842';

  $apprequest_url ="https://graph.facebook.com/" .
    $user_id .
    "/apprequests?message=nice" . 
    "&data=good&"  .   
    $app_access_token . "&method=post";

  $result = file_get_contents($apprequest_url);
  echo("Request id number: ", $result);

?>
