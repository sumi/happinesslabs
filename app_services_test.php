<?php 
include('include/app-db-connect.php');
include('include/app_functions.php');
$type=$_GET['type'];

//ADD USER
//URL : http://cherryfull.com/app_services.php?type=add_user&fb_id=&first_name=&last_name=&email=&fb_photo_url=
if($type=="add_user"){	
	$fb_id=$_GET['fb_id'];
	$first_name=$_GET['first_name'];
	$last_name=$_GET['last_name'];
	$email=$_GET['email'];
	$fb_photo_url=$_GET['fb_photo_url'];
	$location=$_GET['location'];
	if($fb_id!=""&&$first_name!=""&&$last_name!=""&&$email!=""){
		$check_user=mysql_query("select user_id from tbl_app_users where facebook_id='".$fb_id."'");
		$exist_user=(int)mysql_num_rows($check_user);
		if($exist_user==0){
			$ins_query="INSERT INTO `tbl_app_users` (`user_id`, `first_name`, `last_name`, `email_id`, `facebook_id`, `join_date`, fb_photo_url, location) VALUES (NULL, '".$first_name."', '".$last_name."', '".$email."', '".$fb_id."', '".date('Y-m-d')."', '".$fb_photo_url."', '".$location."')";
			
			//$ins_sql=mysql_query($ins_query) or die(mysql_error());
			if($ins_query){
				echo "Inserted";
			}else{
				echo "Error";
			}
		}else{
			echo "Exist";
		}	
	}
}
?>
