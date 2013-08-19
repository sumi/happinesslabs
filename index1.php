<?php
include_once "fbmain.php";

// Create our Application instance (replace this with your appId and secret).
$facebook = new Facebook(array(
  'appId'  => '127392867415509',
  'secret' => '854d7ee47977834c24f001d762603d8d',
));

// Get User ID
$user = $facebook->getUser();

// We may or may not have this data based on whether the user is logged in.
//
// If we have a $user id here, it means we know the user is logged into
// Facebook, but we don't know if the access token is valid. An access
// token is invalid if the user logged out of Facebook.

if ($user) {
  try {
    // Proceed knowing you have a logged in user who's authenticated.
    $user_profile = $facebook->api('/me');
  } catch (FacebookApiException $e) {
    error_log($e);
    $user = null;
  }
}

// Login or logout url will be needed depending on current user state.
if ($user) {
  $logoutUrl = $facebook->getLogoutUrl();
} else {
  $loginUrl = $facebook->getLoginUrl();
}

// This call will always work since we are fetching public data.
//$naitik = $facebook->api('/viaviweb');
 //echo $naitik['name']; //show profile user name
if(count($user_profile)>0){
	$email=getFieldValue('email','uc_fb_contact','fb_id="'.$user_profile['id'].'"');
	if($email==""){
		$insCnt="INSERT INTO `uc_fb_contact` (`fb_contact_id`,	fb_id,  `email`, `first_name`, `last_name`, `record_date`) VALUES (NULL, '".$user_profile['id']."', '".$user_profile['email']."', '".$user_profile['first_name']."', '".$user_profile['last_name']."', '".date('Y-m-d')."')";
		$insCntSl=mysql_query($insCnt);
	}
	
	echo "Welcome to ".$user_profile['first_name']." ".$user_profile['last_name'];

}
?>