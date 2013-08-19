<?php
	if($fb_id==""){$fb_id=$_GET['fb_id'];}	
	if($first_name==""){$first_name=$_GET['first_name'];}	
	if($last_name==""){$last_name=$_GET['last_name'];}	
	if($email==""){$email=$_GET['email'];}	
	
	if($fb_id!=""&&t_name!=""&&$last_name!=""&&$email!=""){
		$check_user=mysql_query("select user_id from tbl_app_users where facebook_id='".$fb_id."'");
		$exist_user=(int)mysql_num_rows($check_user);
		
		if($exist_user==0){
			$ins_query="INSERT INTO `tbl_app_users` (`user_id`, `first_name`, `last_name`, `email_id`, `facebook_id`, `join_date`) VALUES (NULL, '".$first_name."', '".$last_name."', '".$email."', '".$fb_id."', '".date('Y-m-d')."')";
			$ins_sql=mysql_query($ins_query) or die(mysql_error());
			if($ins_query){
				//echo "Yes";
			}else{
				//echo "No";
			}
		}else{
			//echo "Exist";
		}	
	}
?>
