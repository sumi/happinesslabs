<?php
	 //PAPAL VARS
	   define('PAYPAL_URL','https://www.sandbox.paypal.com/in/cgi-bin/webscr');
	   define('BUSINESS_PAYPAL_EMAIL','vijay2patel-facilitator@gmail.com');
	   
	   //SITE VARIABLES
	   $site_url=$_SERVER['SERVER_NAME'];
	   define('SITE_URL',$site_url);
	   if($site_url=='30daysnew.com'){
		 define('REGARDS','30Daysnew.com');
	   }else{
		 define('REGARDS','HappinessLabs.com');
	   }	
?>
