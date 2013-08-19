<?php
	include('include/app-db-connect.php');

	$scriptNameArray=explode("/",$_SERVER['PHP_SELF']);
	$lastElement=(count($scriptNameArray)-1);
	$scriptName=$scriptNameArray[$lastElement];
	define('SCRIPT_NAME',$scriptName);
	$withoutLoginScripts=array('index.php','about.php','team.php','events.php','contact_us.php','sql.php','gifts.php','advertisers.php');
	if(FB_ID==0&&!in_array(SCRIPT_NAME,$withoutLoginScripts)){
		$_SESSION['redirect'] = $_SERVER['REQUEST_URI'];
		if(isset($_SESSION['redirect'])){	
			header('Location: index.php');
			echo '<script language="javascript">document.location.href=\'index.php\';</script>';
		}
	}
	include('include/app_functions.php');
	  
	  	//find photo url
		$pic_square='';
		try{
            //or you can use $uid = $fbme['id'];
 		    $fql    =   "select pic_square from user where uid=" .FB_ID;
            $param  =   array(
                'method'    => 'fql.query',
                'query'     => $fql,
                'callback'  => ''
            );

            $fqlResult   =   $facebook->api($param);
			$pic_square=$fqlResult[0]['pic_square'];
        }
        catch(Exception $o){
            d($o);
        }
		
	  	//print_r($fbme);
		//other user fields
		$first_name=$fbme['first_name'];
		$last_name=$fbme['last_name'];
		$email=$fbme['email'];
		$fb_photo_url=$pic_square;
		$location=str_replace(', ',',',$fbme['location']['name']);
		//START ADD NEW USER
		 $CheckUser=getFieldValue('user_id','tbl_app_users','facebook_id="'.FB_ID.'"');
		  //$CheckUser=0;
		  if($CheckUser==0){ //check user is exist or not
			if($first_name!=""&&$email!=""){
				$ins_query="INSERT INTO `tbl_app_users` (`user_id`, `first_name`, `last_name`, `email_id`, `facebook_id`, `join_date`, fb_photo_url, location) VALUES (NULL, '".$first_name."', '".$last_name."', '".$email."', '".FB_ID."', '".date('Y-m-d')."', '".$fb_photo_url."', '".$location."')";
				$ins_sql=mysql_query($ins_query) or die(mysql_error());
			}
		 }else{
			if($first_name!=""&&$email!=""){
				$upd_query="UPDATE `tbl_app_users` set `first_name`='".$first_name."', `last_name`='".$last_name."', `email_id`='".$email."', fb_photo_url='".$fb_photo_url."', location='".$location."' where facebook_id='".FB_ID."'";
				$upd_sql=mysql_query($upd_query) or die(mysql_error());
			}
		}			
			
		$userDetail=getFieldsValueArray('first_name,last_name,email_id,fb_photo_url,location','tbl_app_users','facebook_id="'.FB_ID.'"');
	
		$first_name=$userDetail['first_name'];
		$last_name=$userDetail['last_name'];
		$email=$userDetail['email'];
		$fb_photo_url=$userDetail['fb_photo_url'];
		$location=$userDetail['location'];  	
			define('FIRST_NAME',$first_name);
			define('LAST_NAME',$last_name);
			define('EMAIL',$email);
			define('PHOTO_URL',$fb_photo_url);
			define('LOCATION',$location);
	  	  
	  //END ADD NEW USER
	   $user_id=(int)getFieldValue('user_id','tbl_app_users','facebook_id="'.FB_ID.'"');
	   define('USER_ID',$user_id);
	   $_SESSION['USER_ID']=$user_id;  	  
?>
