<?php
if(count($post_wall_array)>0){
	try {
		$_SESSION['fb_post_id']=0;
		$ret_obj = $facebook->api('/me/feed', 'POST',$post_wall_array);
		$_SESSION['fb_post_id']=$ret_obj['id'];
		$msgStatus=1;
	
	}catch(FacebookApiException $e) {
	
			//print_r($e);
			$msgStatus=0;
	
	}

}

/*$post_wall_array=array('message' => 'Uploaded New Photo ','name' => $cherryboard_title,'description' => 'Changing lives made easy & fun with 1 picture a day','caption' => 'Achieve goals and win gifts','picture' => 'http://30daysnew.com/images/logo_new.png','link' => 'http://30daysnew.com','properties' => array(array('text' => 'View Goal Storyboard', 'href' => 'http://30daysnew.com/cherryboard.php?cbid='.$cherryboard_id),),
);*/
//print_r($post_wall_array);
?>