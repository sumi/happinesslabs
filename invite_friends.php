<?php 
include("fbmain.php");
include('include/app-common-config.php');
$type=$_REQUEST['type'];
$gtype=$_REQUEST['gtype'];
$cherryboard_id=$_REQUEST['cherryboard_id'];

if($gtype=="expert"){
	$tbl_meb='tbl_app_expert_cherryboard_meb';
	$tbl_temp_meb='tbl_app_temp_expert_cherryboard_meb';
}else{
	$tbl_meb='tbl_app_cherryboard_meb';	
	$tbl_temp_meb='tbl_app_temp_cherryboard_meb';
}
//delete request
if($type=="del_sel_expert_followers"){
	$div_name=$_REQUEST['div_name'];
	$cherryboard_key=$_REQUEST['cherryboard_key'];
	$get_user_fb_id=$_REQUEST['req_user_fb_id'];
	if($get_user_fb_id!=""){
		//delete old request
		/* $request_ids=getFieldValue('request_ids','tbl_app_temp_expert_cherryboard_meb','req_user_fb_id='.$get_user_fb_id);
		$delete_request = $facebook->api('/'.$request_ids,'DELETE');
		*/
		$del_req=mysql_query("delete from tbl_app_temp_expert_cherryboard_meb where req_user_fb_id='".$get_user_fb_id."'");
	}
	$selTemp=mysql_query("select * from tbl_app_temp_expert_cherryboard_meb where cherryboard_key='".$cherryboard_key."'");
	$frndCnt='';
	while($selTempRow=mysql_fetch_array($selTemp)){
		$req_user_fb_id=$selTempRow['req_user_fb_id'];
		$json_string=file_get_contents('https://graph.facebook.com/'.$req_user_fb_id.'?fields=name');
		$array = json_decode($json_string, true);
		$friendName=$array['name'];

		$frndCnt.='<a class="red_tag" href="#" onclick="ajax_action(\'del_sel_expert_followers\',\'div_invite_add_friends\',\'req_user_fb_id='.$req_user_fb_id.'&cherryboard_key='.$cherryboard_key.'\')">'.$friendName.'</a>';
	}
	echo $gtype.'##===##'.$div_name.'##===##'.$frndCnt;
	exit(0);	
	
}else if($cherryboard_id>0){
	//Add friends from cherryboard profile page
	if(isset($_REQUEST['request_ids'])){
	  $user_id=getUserId_by_FBid($_REQUEST['uid']);
	  $cherryboard_key=$_REQUEST['cherryboard_key'];
	  $Arrayids=explode(',',$_REQUEST['request_ids']);
	  $cnt=1;
	  foreach($Arrayids as $req_user_fbArr){
		 if($cnt<=10){
			$req_user_fb_id=explode('_',$req_user_fbArr);
			$chkUser=(int)getFieldValue('meb_id',$tbl_meb,'req_user_fb_id='.$req_user_fb_id[1].' and cherryboard_id='.$cherryboard_id);
			if($chkUser==0){
				$insMeb="INSERT INTO ".$tbl_meb." (meb_id,cherryboard_id,user_id,req_user_fb_id,request_ids,is_accept) VALUES (NULL,'".$cherryboard_id."','".$user_id."','".$req_user_fb_id[1]."','".$req_user_fb_id[0]."','0')";
				$ins_sql=mysql_query($insMeb);
				//=====happinessbank points
				$fnResult=happybankPoint('4',$req_user_fb_id[1],(int)$cherryboard_id);
				
				//=========> START SEND EMAIL CODE <============
				//GET REQUEST USER DETAILS
			    $requestUserId=(int)getFieldValue('user_id','tbl_app_users','facebook_id='.$req_user_fb_id[1]);
				$requestUserDetails=getUserDetail($requestUserId);
				$RequestUserName=$requestUserDetails['first_name'].' '.$requestUserDetails['last_name'];
				$requestEmailId=$requestUserDetails['email_id'];
				//GET SENDER DETAILS
				$senderUserDetails=getUserDetail($user_id);
				$SenderName=$senderUserDetails['first_name'].' '.$senderUserDetails['last_name'];					
				//GET EXPERT STORY BOARD DETAIL
				$expertboard_id=(int)getFieldValue('expertboard_id','tbl_app_expert_cherryboard','cherryboard_id='.$cherryboard_id);
				$expertboard_title=ucwords(trim(getFieldValue('expertboard_title','tbl_app_expertboard','expertboard_id='.$expertboard_id)));
				//SEND EMAIL CODE
				$to = $requestEmailId;
				$subject = $SenderName.' Invited You.';
				$message = '<table>
							<tr><td>&nbsp;</td></tr>
							<tr><td>Dear '.$RequestUserName.',</td></tr>
							<tr><td>&nbsp;</td></tr>
							<tr><td>'.$SenderName.'&nbsp;invited you to the story&nbsp;"'.$expertboard_title.'"&nbsp;<a href="'.SITE_URL.'/expert_cherryboard.php?cbid='.$cherryboard_id.'"><strong>Click here</strong></a> to accept his/her invitation.</td></tr>
							<tr><td>&nbsp;</td></tr>
							<tr><td>Love,</td></tr>
							<tr><td>'.REGARDS.'</td></tr>
							</table>';
				SendMail($to,$subject,$message);
			}	
		}
		$cnt++;
	  }		
	}
//START INVITE FRIENDS REQUEST CODE	
}else if($cherryboard_id==0||$gtype=="request"||($cherryboard_id==0&&$gtype=="request")){
	if(isset($_REQUEST['request_ids'])){
	   $user_id=getUserId_by_FBid($_REQUEST['uid']);
	   $cherryboard_key=$_REQUEST['cherryboard_key'];
	   $Arrayids=explode(',',$_REQUEST['request_ids']);
	   $cnt=1;
	   foreach($Arrayids as $invite_user_fbArr){
		  if($cnt<=10){
			$invite_user_fb_id=explode('_',$invite_user_fbArr);
			$chkUser=(int)getFieldValue('invite_user_id','tbl_app_user_invite','invite_user_fb_id="'.$invite_user_fb_id[1].'" and user_id='.$user_id);
			if($chkUser==0){
				$insUser="INSERT INTO tbl_app_user_invite(invite_user_id,user_id,invite_user_fb_id, invite_ids,is_accept) VALUES (NULL,'".$user_id."','".$invite_user_fb_id[1]."','".$invite_user_fb_id[0]."','0')";
				$ins_sql=mysql_query($insUser);
				//=====happinessbank points
				$fnResult=happybankPoint('5',$req_user_fb_id[1],0);			
			}	
		  }
		  $cnt++;
	  }
	}
}else{
	//ADD friends while creating cherryboard
	$frndCnt='';
	if (isset($_REQUEST['request_ids'])){
		$user_id=$_REQUEST['uid'];
		$cherryboard_key=$_REQUEST['cherryboard_key'];
		$Arrayids=explode(',',$_REQUEST['request_ids']);
		$cnt=1;
		
		//delete old request
		/*$delSel=mysql_query("select request_ids from tbl_app_temp_expert_cherryboard_meb where user_fb_id='".$user_id."'");
		while($delSelRow=mysql_fetch_array($delSel)){
			$request_ids=$delSelRow['request_ids'];
			$delete_request = $facebook->api('/'.$request_ids,'DELETE');	
		}
		$del_old_req=mysql_query("delete from tbl_app_temp_expert_cherryboard_meb where user_fb_id='".$user_id."'");
		*/
		//add into temp table
		foreach($Arrayids as $req_user_fbArr){
			if($cnt<=10){
				$req_user_fb_id=explode('_',$req_user_fbArr);
				$ins="INSERT INTO ".$tbl_temp_meb." (`meb_id`, `cherryboard_key`, `user_fb_id`, `req_user_fb_id`,request_ids) VALUES (NULL, '".$cherryboard_key."', '".$user_id."', '".$req_user_fb_id[1]."','".$req_user_fb_id[0]."')";
				$ins_sql=mysql_query($ins);
				
				$json_string=file_get_contents('https://graph.facebook.com/'.$req_user_fb_id[1].'?fields=name');
				$array = json_decode($json_string, true);
				$friendName=$array['name'];

				$frndCnt.='<a class="red_tag" href="#" onclick="ajax_action(\'del_sel_expert_followers\',\'div_invite_add_friends\',\'req_user_fb_id='.$req_user_fb_id[1].'&cherryboard_key='.$cherryboard_key.'\')">'.$friendName.'</a>';
			}
			$cnt++;
		}
	}
	echo $frndCnt;
}
?>
