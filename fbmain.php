<?php
	error_reporting(0);
	if(!session_id()) {
	  session_start();
	}
 if($_SERVER['SERVER_NAME']=="localhost"){
	   //local
	   define('APPID','265461380188910');
	   define('SECRET','e2e50d6ba78148497dd0c73559b4663a');
	   define('BASE_URL','http://localhost/cherryfull/');
 }else{
	   //30days
	   /*define('APPID','266480013479415');
	   define('SECRET','289db30fbe5b27e17bc84fb1093e7d2a');
	   define('BASE_URL','http://www.30daysnew.com/');*/
	   //Happinesslabs
	   define('APPID','270066236468902');
	   define('SECRET','8f429441bccc9a8f744b40398d3407c7');
	   define('BASE_URL','http://www.happinesslabs.com/');
	   
	   
  }
    try{
        include_once "facebook.php";
    }
    catch(Exception $o){
        echo '<pre>';
        print_r($o);
        echo '</pre>';
    }
    // Create our Application instance.
    $facebook = new Facebook(array(
      'appId'  => APPID,
      'secret' => SECRET,
      'cookie' => true,
	  'fileUpload' => true
    ));
    $session = 1;//$facebook->getSession();
 	try {
        $uid = $facebook->getUser();
        $fbme = $facebook->api('/me');
      } catch (FacebookApiException $e) {
          d($e);
      }
	 
	/* echo "----===----";
	 print_r($fbme);
	 echo "----===---";*/
	// Session based graph API call.
	if($uid){
	    define('FB_ID',$uid);
		$_SESSION['FB_ID']=$uid;
		if(!isset($_SESSION['fb_access_token'])){
			$_SESSION['fb_access_token']=$facebook->getAccessToken();
		}
    }else{
		define('FB_ID',0);
		$_SESSION['FB_ID']=0;
		unset($_SESSION['fb_access_token']);	
	}
	
    function d($d){
	
       /*echo '<pre>';
        print_r($d);
        echo '</pre>';*/
    }

?>
