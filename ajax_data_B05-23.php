<?php 
error_reporting(0);
//include_once "fbmain.php";
//include('include/app-common-config.php');
include('include/app-db-connect.php');
include('include/app_functions.php');

$type=$_GET['type'];
$div_name=$_GET['div_name'];
$ajax_data='';

//Swap images
if($type=="swap_image"){
	$img_sort=$_GET['img_sort'];
    $imgswap_from=explode('_',$_GET['imgswap_from']);
	$imgswap_to=explode('_',$_GET['imgswap_to']);
	$from_photo_day=$imgswap_from[0];
	$from_photo_id=$imgswap_from[1];
	$to_photo_day=$imgswap_to[0];
	$to_photo_id=$imgswap_to[1];
	$swap_frm_div='photo_title';
	$swap_frm_title='';
	$swap_to_div='photo_title';
	$swap_to_title='';
	
	if($from_photo_id>0&&$to_photo_id==0){
		//update photo day
		$swap_type='new_swaped';
		$updtCherry=mysql_query("update tbl_app_expert_cherry_photo set photo_day=".$to_photo_day." where photo_id=".$from_photo_id);
		if($updtCherry){
			$ImgDetailArray=getFieldsValueArray('cherryboard_id,photo_title','tbl_app_expert_cherry_photo','photo_id='.$from_photo_id);
			$cherryboard_id=$ImgDetailArray[0];
			$swap_frm_div='photo_title'.$from_photo_id;
			$swap_frm_title='No Photo';
			$swap_to_div='photo_title'.$to_photo_day;
			$swap_to_title=$ImgDetailArray[1];
		}	
			
	}else if($from_photo_id>0&&$to_photo_id>0){

		$swap_type='new_swaped';
		if($from_photo_id>0){
			$upddelFromPhoto=mysql_query("update tbl_app_expert_cherry_photo set photo_day=".$to_photo_day." where photo_id=".$from_photo_id);
			if($upddelFromPhoto){
				$fromImgDetailArray=getFieldsValueArray('cherryboard_id,photo_title','tbl_app_expert_cherry_photo','photo_id='.$from_photo_id);
				$cherryboard_id=$fromImgDetailArray[0];
				$swap_to_div='photo_title'.$from_photo_id;
				$swap_to_title=$fromImgDetailArray[1].'&nbsp;';
			}	
		}
		if($to_photo_id>0){
			$upddelFromToPhoto=mysql_query("update tbl_app_expert_cherry_photo set photo_day=".$from_photo_day." where photo_id=".$to_photo_id);
			if($upddelFromToPhoto){
				$toImgDetailArray=getFieldsValueArray('cherryboard_id,photo_title','tbl_app_expert_cherry_photo','photo_id='.$to_photo_id);
				$cherryboard_id=$toImgDetailArray[0];
				$swap_frm_div='photo_title'.$to_photo_id;
				$swap_frm_title=$toImgDetailArray[1].'&nbsp;';
			}	
		}
			
	}
	
	$ajax_data=$type."##===##".$div_name."##===##0##===##".$swap_type."##===##".$swap_frm_div."##===##".$swap_frm_title."##===##".$swap_to_div."##===##".$swap_to_title."##===##".$cherryboard_id."##===##".$img_sort;
	echo $ajax_data;
}
//update expert photo title
if($type=="upd_photo_title"){
	$stype=$_GET['stype'];
	$photo_id=$_GET['photo_id'];
	$tpadd='add';
	$tpsave='save';
	if($stype=="eadd"||$stype=="esave"){
		$tblName='tbl_app_expert_cherry_photo';
		$tpadd='eadd';
		$tpsave='esave';
	}else{
		$tblName='tbl_app_cherry_photo';
	}
	if($stype=="add"||$stype=="eadd"){
		$photoTitle=getFieldValue('photo_title',$tblName,'photo_id='.$photo_id);
		$ajax_data.='<textarea onblur="ajax_action(\'upd_photo_title\',\'photo_title'.$photo_id.'\',\'stype='.$tpsave.'&photo_id='.$photo_id.'&upd_title=\'+this.value)" id="cherry_comment'.$photo_id.'" class="input_comments" name="cherry_comment'.$photo_id.'" style="height:30px;">'.$photoTitle.'</textarea>';
		$ajax_data=$type."##===##".$div_name."##===##".$ajax_data;
	}
	if($stype=="save"||$stype=="esave"){
		$photo_id=$_GET['photo_id'];
		$updateTitle=$_GET['upd_title'];
		$update_title=mysql_query("update ".$tblName." set photo_title='".$updateTitle."'  where photo_id=".$photo_id);
		$ajax_data.=getLimitString($updateTitle,55);
		$ajax_data=$type."##===##".$div_name."##===##".$ajax_data;
	}
	echo $ajax_data;
}
//EXPERT CHECKIN MAIL
if($type=="exp_checkin_mail"){
	$cherryboard_id=$_GET['cherryboard_id'];
	$user_id=$_GET['user_id'];
	if($cherryboard_id>0&&$user_id>0){
			$UserDetail=getFieldsValueArray('email_id,first_name,last_name','tbl_app_users','user_id='.$user_id);
			$email_id=$UserDetail['email_id'];
			$first_name=$UserDetail['first_name'];
			$last_name=$UserDetail['last_name'];
			
			$cherryboard_title=getFieldValue('cherryboard_title','tbl_app_expert_cherryboard','cherryboard_id='.$cherryboard_id);
			$RemainDay=getExpertboardRemainDays($cherryboard_id);
			//mail to user
			$to      = $email_id;
			$subject = ucwords($cherryboard_title).' - Day '.(int)(30-$RemainDay).' Check In! ';
			$message = '<table>
						<tr><td>Hi '.ucwords($first_name.' '.$last_name).',</td></tr>
						<tr><td>&nbsp;</td></tr>
						<tr><td>Please check your expertboard <a href="http://30daysnew.com/expert_cherryboard.php?cbid='.$cherryboard_id.'"><strong>'.ucwords($cherryboard_title).'</strong></a>.</td></tr>
						</table>';
			SendMail($email_id,$subject,$message);
			$msg_data='<span class="msg_green">Mail&nbsp;Sent.</span>';
	}
	$ajax_data=$type."##===##".$div_name."##===##".$msg_data;
	echo $ajax_data;
}
//BUY EXPERT BOARD
if($type=="buy_board"||$type=="buy_board_exp"){
	$msg_data='';
	$cherryboard_id=$_GET['cherryboard_id'];
	$user_id=$_GET['user_id'];
	if($cherryboard_id>0&&$user_id>0){
		$ins_sel="INSERT INTO `tbl_app_expert_buy` (`buy_id`, `user_id`, `cherryboard_id`, `buy_date`) VALUES (NULL, '".$user_id."', '".$cherryboard_id."', '".date('Y-m-d')."')";
		$ins_sql=mysql_query($ins_sel);
		$msg_data='<font class="msg_green">Thanks for buy.</font>';
	}else{
		$msg_data='<font class="msg_red">Invalid Request.</font>';
	}
		
	$ajax_data=$type."##===##".$div_name."##===##".$msg_data."##===##".$cherryboard_id;
	echo $ajax_data;
}

//ADD SETUP CATEGORY
if($type=="add_category"){
	$msg_data='';
	$category_name=$_GET['category_name'];
	if($category_name!="Enter category name"&&$category_name!=""){
		$ins_sel="INSERT INTO `tbl_app_category` (`category_id`, `category_name`, `record_date`) VALUES (NULL, '".$category_name."', '".date('Y-m-d')."')";
		$ins_sql=mysql_query($ins_sel);
		$msg_data='Success';
	}else{
		$msg_data='Invalid category name';
	}
		
	$ajax_data=$type."##===##".$div_name."##===##".$msg_data;
	echo $ajax_data;
}
//REFRESH INSPIR FEED
if($type=="refresh_inspir_feed"||$type=="refresh_expert_inspir_feed"){
	$cherryboard_id=$_GET['cherryboard_id'];
	$feed_data='';
	if($cherryboard_id>0){
		if($type=="refresh_inspir_feed"){
			$feed_data=UserFeedSection('cherryboard',$cherryboard_id);
		}else{
			$feed_data=UserFeedSection('expertboard',$cherryboard_id);
		}
		
	}	
	$ajax_data=$type."##===##".$div_name."##===##".$feed_data;
	echo $ajax_data;
}
//DELETE MONTHLY SPECIAL
if($type=="delete_goal_monthly_specials"){
	 $cherryboard_id=$_GET['cherryboard_id'];
	 $cherry_gift_id=$_GET['cherry_gift_id'];
	 if($cherry_gift_id>0){
	 	$mysql_delete=mysql_query("delete from tbl_app_cherry_gift where cherry_gift_id=".$cherry_gift_id);
	 }
	 	$cherrySel=mysql_query("select a.cherry_gift_id,b.gift_photo,b.gift_title from tbl_app_cherry_gift a,tbl_app_gift b where a.gift_id=b.gift_id and a.cherryboard_id=".$cherryboard_id." group by a.gift_id");
	$MonthSpeCnt='';
	if(mysql_num_rows($cherrySel)>0){
		while($cherryRow=mysql_fetch_array($cherrySel)){
			$cherry_gift_id=$cherryRow['cherry_gift_id'];
			$gift_photo=$cherryRow['gift_photo'];
			$gift_title=$cherryRow['gift_title'];
			$MonthSpeCnt.='<div class="small_thumb_container">
				<div class="img_big_container">
					<div class="feedbox_holder">
						<div class="actions">
						<a class="delete" href="#" onclick="ajax_action(\'delete_goal_monthly_specials\',\'div_goal_monthly_specials\',\'cherryboard_id='.$cherryboard_id.'&cherry_gift_id='.$cherry_gift_id.'\')" ><img src="images/delete.png"></a></div>
					</div>
					<img src="images/gift/'.$gift_photo.'" class="thumb" height="30" width="30" title="'.$gift_title.'">
				</div>
				</div>';
		}
	}else{
		$MonthSpeCnt.='<strong>No Monthly Specials</strong>';
	}
	$ajax_data=$type."##===##".$div_name."##===##".$MonthSpeCnt;
	echo $ajax_data;
}
//DELETE GOAL RECENT FRIENDS
if($type=="delete_goal_recent_friends"||$type=="sel_goal_recent_friends"||$type=="delete_goal_recent_followers"||$type=="sel_goal_recent_followers"){
	 
	 $FriendsCnt='';
	 $recentLbl='Request Friends';
	 $tblName='tbl_app_cherryboard_meb';
	 $delTypeName='delete_goal_recent_friends';
	 $divName='div_goal_recent_friends';
	 if($type=="sel_goal_recent_followers"||$type=="delete_goal_recent_followers"){
	 	$tblName='tbl_app_expert_cherryboard_meb';
		$recentLbl='Request Followers';
		$delTypeName='delete_goal_recent_followers';
		$divName='div_goal_recent_followers';
	}
	 $cherryboard_id=$_GET['cherryboard_id'];
	 $meb_id=$_GET['meb_id'];
	 $get_user_id=$_GET['user_id'];
	 if($meb_id>0){
	 	$mysql_delete=mysql_query("delete from ".$tblName." where meb_id=".$meb_id);
	 }
	 	
		$selQuery="select meb_id,user_id,req_user_fb_id from ".$tblName." where is_accept='0' and cherryboard_id=".$cherryboard_id." order by meb_id desc limit 10";
		$selSqlQ=mysql_query($selQuery);
		if(mysql_num_rows($selSqlQ)>0){
			$FriendsCnt.='<p>'.$recentLbl.'</p>';
			$cnt=0;
			while($rowTbl=mysql_fetch_array($selSqlQ)){
				if($cnt==5){$FriendsCnt.='<br/>';}
				$meb_id=$rowTbl['meb_id'];
				$user_id=$rowTbl['user_id'];
				$fb_photo_url=getFriendPhoto($rowTbl['req_user_fb_id']);
				$FriendsCnt.='<div class="small_thumb_container">
					<div class="feedbox_holder">
						<div class="actions">'.($user_id==$get_user_id?'<a class="delete" href="#" onclick="ajax_action(\''.$delTypeName.'\',\''.$divName.'\',\'cherryboard_id='.$cherryboard_id.'&meb_id='.$meb_id.'\')" ><img src="images/delete.png"></a>':'').'</div>
					</div>
					<img src="'.$fb_photo_url.'" class="thumb">
				</div>';
				$cnt++;
			}
			
		}
	 $ajax_data=$type."##===##".$div_name."##===##".$FriendsCnt;
	 echo $ajax_data;
}	 
//DELETE GOAL FRIENDS
if($type=="delete_goal_friends"||$type=="delete_goal_followers"){

	 $tblName='tbl_app_cherryboard_meb';
	 $divName='div_goal_friends';
	 $delType='delete_goal_friends';
	 $lblBlock='Friends';
	 if($type=="delete_goal_followers"){
	 	$tblName='tbl_app_expert_cherryboard_meb';
		 $divName='div_goal_followers';
		 $delType='delete_goal_followers';
		  $lblBlock='Followers';
	 }
	 	
	 $cherryboard_id=$_GET['cherryboard_id'];
	 $meb_id=$_GET['meb_id'];
	 if($meb_id>0){
	 	$mysql_delete=mysql_query("delete from ".$tblName." where meb_id=".$meb_id);
	 }
	 	$FriendsCnt='';
		$selQuery="select a.meb_id,b.user_id,b.fb_photo_url from ".$tblName." a,tbl_app_users b where a.req_user_fb_id=b.facebook_id and a.cherryboard_id=".$cherryboard_id." group by b.user_id limit 10";
		$selSqlQ=mysql_query($selQuery);
		$FriendsArray=array();
		if(mysql_num_rows($selSqlQ)>0){
			$cnt=0;
			while($rowTbl=mysql_fetch_array($selSqlQ)){
				$FriendsArray[]=$rowTbl['user_id'];
				$meb_id=$rowTbl['meb_id'];
				if($cnt==5){$FriendsCnt.='<br/>';}
				$FriendsCnt.='<div class="small_thumb_container">
				<div class="img_big_container">
					<div class="feedbox_holder">
						<div class="actions"><a class="delete" href="#" onclick="ajax_action(\''.$delType.'\',\''.$divName.'\',\'cherryboard_id='.$cherryboard_id.'&meb_id='.$meb_id.'\')" ><img src="images/delete.png"></a></div>
					</div>
					<img src="'.$rowTbl['fb_photo_url'].'" class="thumb">
				</div>
				</div>';
				$cnt++;
			}
			
		}else{
			$FriendsCnt.='<strong>No '.$lblBlock.'</strong>';
		}
	 $ajax_data=$type."##===##".$div_name."##===##".$FriendsCnt;
	 echo $ajax_data;
}	 
//DELETE GOAL EXPERT
if($type=="delete_goal_experts"){
	 $cherryboard_id=$_GET['cherryboard_id'];
	 $experts_id=$_GET['experts_id'];
	 if($experts_id>0){
	 	$mysql_delete=mysql_query("delete from tbl_app_cherryboard_experts where experts_id=".$experts_id);
	 }
	$selQuery="select a.experts_id,b.user_id,b.fb_photo_url from tbl_app_cherryboard_experts a,tbl_app_users b where a.user_id=b.user_id and a.cherryboard_id=".$cherryboard_id." order by a.experts_id desc limit 10";
		$selSqlQ=mysql_query($selQuery);
		$ExpertsCnt='';
		if(mysql_num_rows($selSqlQ)>0){
			$cnt=0;
			while($rowTbl=mysql_fetch_array($selSqlQ)){
				$experts_id=$rowTbl['experts_id'];
				if($cnt==5){$ExpertsCnt.='<br/>';}
				$ExpertsCnt.='<div class="small_thumb_container">
				<div class="img_big_container">
					<div class="feedbox_holder">
						<div class="actions"><a class="delete" href="#" onclick="ajax_action(\'delete_goal_experts\',\'div_goal_experts\',\'cherryboard_id='.$cherryboard_id.'&experts_id='.$experts_id.'\')" ><img src="images/delete.png"></a></div>
					</div>
					<img src="'.$rowTbl['fb_photo_url'].'" class="thumb">
				</div>
				</div>';
				$cnt++;
			}
			
		}else{
			$ExpertsCnt.='<strong>No Experts</strong>';
		}
	 
	 $ajax_data=$type."##===##".$div_name."##===##".$ExpertsCnt;
	 echo $ajax_data;
}
if($type=="add_goal_expert"){
	 $cherryboard_id=$_GET['cherryboard_id'];
	 $user_id=$_GET['user_id'];
	 if($user_id>0){
	 		$checkExp=(int)getFieldValue('experts_id','tbl_app_cherryboard_experts','cherryboard_id='.$cherryboard_id.' and user_id='.$user_id);
			if($checkExp==0){
				$upd_expert=mysql_query("INSERT INTO `tbl_app_cherryboard_experts` (`experts_id`, `cherryboard_id`, `user_id`, `record_date`) VALUES (NULL, '".$cherryboard_id."', '".$user_id."', CURRENT_TIMESTAMP)");
			}	
		$ajax_data="Expert added"; 
	 }
	$ajax_data=$type."##===##".$div_name."##===##".$ajax_data."##===##".$cherryboard_id;
	echo $ajax_data;
}

//EDIT/SAVE GOAL TITLE
if($type=="edit_goal_title"){
	 $cherryboard_id=$_GET['cherryboard_id'];
	 $getTitle=getFieldValue('cherryboard_title','tbl_app_cherryboard','cherryboard_id='. $cherryboard_id); 
	  
	 $ajax_data.='<input type="text" name="goal_edit_title" id="goal_edit_title" value="'.$getTitle.'"/>&nbsp;<img src="images/save.png" height="20" style="cursor:pointer" onclick="edit_goal(\'save_goal_title\','.$cherryboard_id.')" width="20" title="Save" />';
	  echo $ajax_data;
	  
}else if($type=="save_goal_title"){
	$edit_title=$_GET['edit_title'];
	$cherryboard_id=$_GET['cherryboard_id'];
	$updateTitle=mysql_query("update tbl_app_cherryboard set cherryboard_title='".$edit_title."' where cherryboard_id=".$cherryboard_id);
	 $ajax_data.=$edit_title.'&nbsp;<img src="images/edit.png" height="16" style="cursor:pointer" onclick="edit_goal(\'edit_goal_title\','.$cherryboard_id.')" width="16" title="Edit" />';
	 //share into facebook
	try {
		$ret_obj = $facebook->api('/me/feed', 'POST',
									array(
									  'link' => 'www.30daysnew.com',
									  'message' => 'Updated Goal Storyboard '.ucwords($edit_title),
								 ));
	  }catch(FacebookApiException $e) {
		
	  }
	  echo $ajax_data;
}
//EDIT/SAVE GOAL DAYS AND STRIKES DAY
if($type=="edit_goal_day"){
	 $cherryboard_id=$_GET['cherryboard_id'];
	 $gift_id=(int)getFieldValue('gift_id',' tbl_app_cherry_gift','cherryboard_id='.$cherryboard_id);
	 $campaignDetail=getFieldsValueArray('goal_days,miss_days','tbl_app_gift','gift_id='.$gift_id);
	 $goal_days=$campaignDetail[0];
	 $miss_days=$campaignDetail[1]; 
	  
	 $ajax_data.='Days : <input type="text" name="edit_goal_days" id="edit_goal_days" value="'.$goal_days.'" size="10" />&nbsp;&nbsp;Strikes : <input type="text" name="edit_miss_days" id="edit_miss_days" value="'.$miss_days.'" size="10" />&nbsp;<img src="images/save.png" height="20" style="cursor:pointer" onclick="ajax_action(\'save_goal_day\',\'div_edit_days\',\'cherryboard_id='.$cherryboard_id.'\')" width="20" title="Save" />';
	 $ajax_data=$type."##===##".$div_name."##===##".$ajax_data;
	 echo $ajax_data;
	 //;ajax_action(\'photo_refresh\',\'right_container\',\'cherryboard_id='.$cherryboard_id.'&sort=desc\')	  
}else if($type=="save_goal_day"){
	$edit_goal_days=$_GET['edit_goal_days'];
	$edit_miss_days=$_GET['edit_miss_days'];
	$cherryboard_id=$_GET['cherryboard_id'];
	$gift_id=(int)getFieldValue('gift_id',' tbl_app_cherry_gift','cherryboard_id='.$cherryboard_id);
	if($edit_goal_days>0&&$edit_miss_days>0&&$gift_id>0){
		$updateDay=mysql_query("update tbl_app_gift set goal_days='".$edit_goal_days."', miss_days='".$edit_miss_days."' where gift_id=".$gift_id);
	}
	 $ajax_data.='Days&nbsp;:&nbsp;'.$edit_goal_days.'&nbsp; Strikes &nbsp;:&nbsp;'.$edit_miss_days.'&nbsp;&nbsp;<img src="images/edit.png" height="16" style="cursor:pointer" onclick="ajax_action(\'edit_goal_day\',\'div_edit_days\',\'cherryboard_id='.$cherryboard_id.'\')" width="16" title="Edit" />'; 
	 $ajax_data=$type."##===##".$div_name."##===##".$ajax_data;
	 echo $ajax_data;
}
//EDIT/SAVE EXPERT TITLE
if($type=="edit_expert_title"){
	 $cherryboard_id=$_GET['cherryboard_id'];
	 $getTitle=getFieldValue('cherryboard_title','tbl_app_expert_cherryboard','cherryboard_id='. $cherryboard_id); 
	  
	 $ajax_data.='<input type="text" name="goal_edit_title" id="goal_edit_title" value="'.$getTitle.'"/>&nbsp;<img src="images/save.png" height="20" style="cursor:pointer" onclick="edit_goal(\'save_expert_title\','.$cherryboard_id.')" width="20" title="Save" />';
	  echo $ajax_data;
}else if($type=="save_expert_title"){
	$edit_title=$_GET['edit_title'];
	$cherryboard_id=$_GET['cherryboard_id'];
	$updateTitle=mysql_query("update tbl_app_expert_cherryboard set cherryboard_title='".$edit_title."' where cherryboard_id=".$cherryboard_id);
	 $ajax_data.=$edit_title.'&nbsp;<img src="images/edit.jpg" height="20" style="cursor:pointer" onclick="edit_goal(\'edit_expert_title\','.$cherryboard_id.')" width="20" title="Edit" />';
	  echo $ajax_data;

}
//SELECT CHECKLIST
if($type=="select_checklist"){
	  $checklist_id=$_GET['checklist_id'];
	  $uncheck=$_GET['uncheck'];
	  
	 if($uncheck=="true"){
	 	$_SESSION['select_checklist'] = array_diff($_SESSION['select_checklist'], array($checklist_id));
	 }else{
	    array_push($_SESSION['select_checklist'],$checklist_id);
	    $ajax_data="0##===##0";
	  }	  
	  echo $ajax_data;
}
//SELECT GOALS
if($type=="select_goal"){
	  $cherryboard_id=$_GET['cherryboard_id'];
	  $uncheck=$_GET['uncheck'];
	  
	 if($uncheck=="true"){
	 	$_SESSION['select_goals'] = array_diff($_SESSION['select_goals'], array($cherryboard_id));
	 }else{
	 
		  $select_goals=$_SESSION['select_goals'];
		  $existGoalId=0;
		  
		  $checkCatId=getFieldValue('category_id','tbl_app_system_cherryboard','cherryboard_id='.$cherryboard_id);
		  foreach($_SESSION['select_goals'] as $goal_id){
		  	  $checkGoalId=getFieldValue('category_id','tbl_app_system_cherryboard','cherryboard_id='.$goal_id);
			  if($checkCatId==$checkGoalId){
			  	 $existGoalId=$goal_id;
			  }	
		  }	  
		
		  if($existGoalId==0){
		      array_push($_SESSION['select_goals'],$cherryboard_id);
			  $ajax_data="0##===##0";
		  }else{
			  $cherryboard_title=GetFieldValue('cherryboard_title','tbl_app_system_cherryboard','cherryboard_id='.$existGoalId);
			  $ajax_data=$cherryboard_id."##===##".$cherryboard_title." goal already selected";
		  }
	  }	  
	  echo $ajax_data;
}
//GET GOALS
if($type=="get_goals"&&$_GET['goal_arr_key']!=""){
		$goal_arr_key=$_GET['goal_arr_key'];
		$goalCatArr=$_SESSION['select_gifts'][$goal_arr_key];
		$giftArr=explode('_',$goalCatArr);
		$category_id=$giftArr[0];
			 
		 $selGift=mysql_query("select * from tbl_app_system_cherryboard where category_id=".$category_id." order by cherryboard_id");
		  while($rowGift=mysql_fetch_array($selGift)){
			$cherryboard_id=$rowGift['cherryboard_id'];
			$cherryboard_title=$rowGift['cherryboard_title'];
			
			  $GoalCnt.='<div class="setup_achive"><input name="chk_goals_'.$cherryboard_id.'" id="chk_goals_'.$cherryboard_id.'" type="checkbox" value="'.$cherryboard_id.'" onclick="select_goal(\'select_goal\',this.value,this.name)" class="checkbox1" '.(in_array($cherryboard_id,$_SESSION['select_goals'])?'checked="checked"':'').'>
				<div class="box">
				  <div class="head"><strong>'.$cherryboard_title.'</strong></div>
				  <strong>Checklist</strong><br><br>';
				$selCheckList=mysql_query("select * from tbl_app_system_checklist where cherryboard_id=".$cherryboard_id." order by checklist_id");
				while($rowCheckList=mysql_fetch_array($selCheckList)){
					$checklist_id=$rowCheckList['checklist_id'];
					$checklist=$rowCheckList['checklist'];
					$chk_list_value=$cherryboard_id.'_'.$checklist_id;
					//$GoalCnt.='<div class="list"><label><input type="checkbox" '.(in_array($chk_list_value,$_SESSION['select_checklist'])?'checked="checked"':'').' name="chk_list_'.$checklist_id.'" id="chk_list_'.$checklist_id.'" value="'.$chk_list_value.'" onclick="select_checklist(\'select_checklist\',this.value,this.name)" class="checkboxes"></label>'.$checklist.'</div>';
					$GoalCnt.='<div class="list"><label><input type="checkbox" disabled="disabled" name="chk_list_'.$checklist_id.'" id="chk_list_'.$checklist_id.'" value="'.$cherryboard_id.'_'.$checklist_id.'"  class="checkboxes"></label>'.$checklist.'</div>';
				}
			  $GoalCnt.='<br>
				</div>
			  </div>';
		  }
		  
		 $GoalCnt.='<div class="clear"></div><div>'; 
		 if($goal_arr_key==count($_SESSION['select_gifts'])-1){
		 	$GoalCnt.='<input type="button" onclick="goto_step4()" value="Create Goalboard" class="btn_small">';
		 }else{
			$GoalCnt.='<input type="button" onclick="goto_step3_next('.($goal_arr_key+1).')" value="Next" class="btn_small">';
			
		}	
		if($goal_arr_key>0){
			$GoalCnt.='<input type="button" onclick="goto_step3_next('.($goal_arr_key-1).')"  value="Previous" class="btn_small">';		
		}
		$GoalCnt.='</div>';	
		echo $GoalCnt;  

}
//SELECT GIFTS
if($type=="select_gift"){
	  $gift_id=$_GET['gift_id'];
	  $uncheck=$_GET['uncheck'];
	  $getArr=explode('_',$gift_id);
	 
	 if($uncheck=="true"){
	 	$_SESSION['select_gifts'] = array_diff($_SESSION['select_gifts'], array($gift_id));
	 }else{
	 
		  $gifts_arr=$_SESSION['select_gifts'];
		  $existGiftId=0;
		  if(count($gifts_arr)>0){
			  foreach($gifts_arr as $giftValue){
				$catArr=explode('_',$giftValue);
				if($catArr[0]==$getArr[0]){
					$existGiftId=$catArr[1];
				}
				
			  }
		  }	  
		
		  if($existGiftId==0){
		      //array_push($_SESSION['select_gifts'],$gift_id);
			  $_SESSION['select_gifts'] = array($gift_id);
			  $ajax_data="0##===##0";
		  }else{
			  //$catName=GetFieldValue('category_name','tbl_app_category','category_id='.$getArr[0]);
			  //$GiftName=GetFieldValue('gift_title','tbl_app_gift','gift_id='.$existGiftId);
			  $ajax_data=$getArr[1]."##===##&nbsp;Gift is selected and if u want to select a different gift unselect the secleted gift.&nbsp;";
		  }
	  }	  
	  echo $ajax_data;
}
//GET GIFTS
if($type=="get_gifts"&& $_GET['category_id']>0){
		$user_id=$_GET['user_id'];
	    $get_category_id=$_GET['category_id'];
	  
	  /* Restriction for the show only created non category goal
	  	$sel_cat=mysql_query("select category_id from tbl_app_cherryboard where user_id=".(int)$user_id." group by category_id order by category_id");
		$cat_array=array('0');
		while($sel_catrow=mysql_fetch_array($sel_cat))
		{
			$cat_array[]=$sel_catrow['category_id'];
		}
		$cat_array=implode(',',$cat_array);
		$sel_query=mysql_query("select * from tbl_app_category where category_id not in(".$cat_array.") order by category_id");
	  */
	  		$ajax_data.='<div class="left" style="padding:20px 0px; display:block; width:100%;">';
			$sel_query=mysql_query("select * from tbl_app_category order by category_id");
			$categoryCnt='';
			while($sel_row=mysql_fetch_array($sel_query))
			{
				if($sel_row['category_id']==$get_category_id){
					$selectedColor=' style="background: none repeat scroll 0 0 #C02336;color: #FFFFFF;"';
				}else{
					$selectedColor='';
				}	
				
				$ajax_data.='<a href="#" onclick="ajax_action(\'get_gifts\',\'div_get_gifts\',\'category_id='.$sel_row['category_id'].'&user_id='.USER_ID.'\')" class="gray_tag" '.$selectedColor.'>'.ucwords($sel_row['category_name']).'</a>';
			}	
	 $ajax_data.='</div>';
	 
	 
	 
	  $selGift=mysql_query("select * from tbl_app_gift where is_system='1' and category_id='".$get_category_id."' order by gift_id");
	  if(mysql_num_rows($selGift)>0){
		  while($rowGift=mysql_fetch_array($selGift)){
			$gift_id=$rowGift['gift_id'];
			$gift_title=$rowGift['gift_title'];
			$gift_photo=$rowGift['gift_photo'];
			$category_id=$rowGift['category_id'];
			$campaign_title=ucwords($rowGift['campaign_title']);
			$sponsor_name=ucwords($rowGift['sponsor_name']);
			$sponsor_logo=$rowGift['sponsor_logo'];
			$sponsorPath='images/gift/'.$sponsor_logo;
			$cherryboard_id=(int)getFieldValue('cherryboard_id','tbl_app_cherry_gift','gift_id='.$gift_id);
			$ajax_data.='
			<div class="gift1"><a href="gift_profile.php?gid='.$gift_id.'"><img src="images/gift/'.$gift_photo.'" class="imgbig"></a><br><strong>'.$gift_title.'</strong>';		
			
			//winner photo
				$selWin="select a.user_id,b.fb_photo_url,b.first_name,b.last_name from tbl_app_cherry_gift a,tbl_app_users b where a.gift_id='".$gift_id."' and a.user_id=b.user_id group by a.user_id order by a.cherry_gift_id limit 5";
				 $sqlWin=mysql_query($selWin);
				 $winnersPhoto='';	
			     if(mysql_num_rows($sqlWin)>0){
				 	while($selWinRow=mysql_fetch_array($sqlWin)){
				 		$imgUrl=$selWinRow['fb_photo_url'];
						$winner_title=$selWinRow['first_name'].' '.$selWinRow['last_name'];
						if(trim($imgUrl!='')){						
				 		$winnersPhoto.='<img src="'.$imgUrl.'" alt="'.$winner_title.'" style="cursor:pointer" title="'.$winner_title.'" class="imgsmall">';
						}
					}
				 }
				if($winnersPhoto!=""){
					$ajax_data.='Recent Winners<br/><div class="thumb_container">'.$winnersPhoto;
				}else{
					$ajax_data.='<div class="thumb_container">';
				}
			
			$ajax_data.=''.(trim($campaign_title)!=''?'<a href="cherryboard.php?cbid='.$cherryboard_id.'"
			  style="text-decoration:none;color:#990000">'.$campaign_title.'</a><br>':'').'
				'.(trim($sponsor_name)!=''?'Sponsored by :'.$sponsor_name.'<br>':'').'
				'.(is_file($sponsorPath)?'<img src="'.$sponsorPath.'" class="imgsmall"><br>':'').'
			<div class="clear">
			  <label>';
			$ajax_data.='<input type="checkbox" '.(in_array($category_id.'_'.$gift_id,$_SESSION['select_gifts'])?'checked="checked"':'').' onclick="select_gift(\'select_gift\',this.value,this.name)" name="chk_gift_'.$gift_id.'" value="'.$category_id.'_'.$gift_id.'" id="chk_gift_'.$gift_id.'">
			  </label>
			</div></div>    
		  </div>';
		  $GiftCnt.='<div class="gift1"><img src="images/gift/'.$gift_photo.'" class="imgbig"><br><strong>'.$gift_title.'</strong>';
		  
				//winner photo
				$selWin="select a.user_id,b.fb_photo_url,b.first_name,b.last_name from tbl_app_cherry_gift a,tbl_app_users b where a.gift_id='".$gift_id."' and a.user_id=b.user_id group by a.user_id order by a.cherry_gift_id limit 5";
				 $sqlWin=mysql_query($selWin);
				 $winnersPhoto='';	
			     if(mysql_num_rows($sqlWin)>0){
				 	while($selWinRow=mysql_fetch_array($sqlWin)){
				 		$winnersPhoto.='<img src="'.$selWinRow['fb_photo_url'].'" alt="'.$selWinRow['first_name'].' '.$selWinRow['last_name'].'" style="cursor:pointer" title="'.$selWinRow['first_name'].' '.$selWinRow['last_name'].'" class="small_thumb1">';
					}
				 }
				if($winnersPhoto!=""){
					$GiftCnt.='<br/>Recent Winners<br/><div style="text-align:center">'.$winnersPhoto.'</div>';
				}
				$GiftCnt.='
				<div class="thumb_container">
				<div class="clear">
				  <label>';
				 $GiftCnt.='<input type="checkbox" '.(in_array($category_id.'_'.$gift_id,$_SESSION['select_gifts'])?'checked="checked"':'').' onclick="select_gift(\'select_gift\',this.value,this.name)" name="chk_gift_'.$gift_id.'" value="'.$category_id.'_'.$gift_id.'" id="chk_gift_'.$gift_id.'">
				  </label>
				</div></div>    
			  </div>';
		  
		  
		 } 
		 
		$ajax_data.='<div class="clear"></div><input type="button" onclick="goto_step3('.($_SESSION['select_gifts']==""?'0':'1').');" name="btnGift" value="Next" class="right btn_small">';
	 }else{
	 	$ajax_data.='No Gifts';
	 }
	$ajax_data=$type."##===##".$div_name."##===##".$ajax_data;
	echo $ajax_data;
}


//ADD CHECKLIST
if($type=="add_checklist"||$type=="add_expert_checklist"||$type=="remove_checklist"||$type=="remove_expert_checklist"||$type=="checked_checklist"||$type=="checked_expert_checklist"){
	
	$tbl_name='tbl_app_checklist';
	$chk_type='checked_checklist';
	$chk_remove='remove_checklist';
	
	if($type=="add_expert_checklist"||$type=="remove_expert_checklist"||$type=="checked_expert_checklist"){
		$tbl_name='tbl_app_expert_checklist';
		$chk_type='checked_expert_checklist';
		$chk_remove='remove_expert_checklist';
	}

	$txt_checklist=addslashes($_GET['txt_checklist']);
	$cherryboard_id=$_GET['cherryboard_id'];
	$user_id=(int)$_GET['user_id'];
	$currentUserId=(int)$_GET['cuid'];
	//CHECKED CHECKLIST
	if($type=="checked_checklist"||$type=="checked_expert_checklist"){	
		$checklist_id=$_GET['checklist_id'];
		$checkVal=$_GET['checkVal'];
		if($checklist_id>0){
			$updCheck=mysql_query("update ".$tbl_name." set is_checked='".$checkVal."' where checklist_id=".$checklist_id);
		}
	}
	//REMOVE CHECKLIST
	if($type=="remove_checklist"||$type=="remove_expert_checklist"){
		$checklist_id=$_GET['checklist_id'];
		$delChecklist=mysql_query("delete from ".$tbl_name." where checklist_id=".$checklist_id);
	}
	//ADD CHECKLIST
	if(($type=="add_checklist"||$type=="add_expert_checklist")&&$txt_checklist!=""&&$txt_checklist!="add something to your checklist"){
		$ins_query="INSERT INTO ".$tbl_name." (`checklist_id`, user_id, `cherryboard_id`, `checklist`) VALUES (NULL, '".$user_id."', '".$cherryboard_id."', '".$txt_checklist."')";
		$ins_sql=mysql_query($ins_query);
		$currentUserId=$user_id;
	}	
		
	 	$selchk=mysql_query("select *,date_format(record_date,'%m-%d-%Y') as record_date from ".$tbl_name." where cherryboard_id=".$cherryboard_id." order by checklist_id desc limit 10") or die(mysql_error());
		$checkCnt='';
		while($selchkRow=mysql_fetch_array($selchk)){
			$checklist_id=$selchkRow['checklist_id'];
			$checklist=$selchkRow['checklist'];
			$record_date=$selchkRow['record_date'];
			$is_checked=$selchkRow['is_checked'];
			$user_id=$selchkRow['user_id'];
			$ajax_data.='<div class="box_container" style="width: 230px;"><label><input type="checkbox" id="chkfield_'.$checklist_id.'"  name="chkfield_'.$checklist_id.'" '.($is_checked==1?'checked="checked"':'').' value="1" onclick="checked_checklist(\''.$chk_type.'\',\'div_checklist\',\'cherryboard_id='.$cherryboard_id.'&checklist_id='.$checklist_id.'&cuid='.$currentUserId.'\',\'chkfield_'.$checklist_id.'\')" class="checkbox"></label>&nbsp;'.$checklist.'<br/><span class="smalltext">added '.$record_date.'&nbsp;'.($user_id==$currentUserId?'<img src="images/close_small1.png"  onclick="ajax_action(\''.$chk_remove.'\',\'div_checklist\',\'cherryboard_id='.$cherryboard_id.'&checklist_id='.$checklist_id.'&cuid='.$currentUserId.'\')" style="cursor:pointer">':'').'</span></div>';
		}
		$ajax_data=$type."##===##".$div_name."##===##".$ajax_data."##===##".$cherryboard_id;
		echo $ajax_data;
}


//ADD CHERRYBOARD COMMENT
if($type=="add_cherry_comment"||$type=="del_cherry_comment"||$type=="add_cheers"||$type=="add_cherry_expert_comment"||$type=="del_cherry_expert_comment"||$type=="add_expert_cheers"){
	$tbl_name='tbl_app_cherry_comment';
	$tbl_cheers_name='tbl_app_cherryboard_cheers';
	$typeName='del_cherry_comment';
	$typeAddCheers='add_cheers';
	if($type=="add_cherry_expert_comment"||$type=="del_cherry_expert_comment"||$type=="add_expert_cheers"){
		$tbl_name='tbl_app_expert_cherry_comment';
		$typeName='del_cherry_expert_comment';
		$tbl_cheers_name='tbl_app_expert_cherryboard_cheers';
		$typeAddCheers='add_expert_cheers';
	}
	
	$cherryboard_id=(int)$_GET['cherryboard_id'];
	$photo_id=(int)$_GET['photo_id'];
	$user_id=(int)$_GET['user_id'];
	$cherry_comment=addslashes($_GET['cherry_comment']);
	
		if($type=="add_cheers"||$type=="add_expert_cheers"){
			$ins_query="INSERT INTO ".$tbl_cheers_name." (`cheers_id`, `photo_id`, `user_id`, `cherryboard_id`) VALUES (NULL, '".$photo_id."', '".$user_id."','".$cherryboard_id."')";
			$ins_sql=mysql_query($ins_query);
		}	
		
		//ADD COMMENT
		if(($type=="add_cherry_comment"||$type=="add_cherry_expert_comment")&&$cherry_comment!=""&&$photo_id>0){
			$ins_query="INSERT INTO ".$tbl_name." (`comment_id`, `cherryboard_id`, `photo_id`, `user_id`, `cherry_comment`) VALUES (NULL, '".$cherryboard_id."', '".$photo_id."', '".$user_id."', '".$cherry_comment."')";
			$ins_sql=mysql_query($ins_query);
		}	
		if($type=="del_cherry_comment"||$type=="del_cherry_expert_comment"){
			$comment_id=(int)$_GET['comment_id'];
			$del_query="delete from ".$tbl_name." where comment_id=".$comment_id;
			$del_sql=mysql_query($del_query);
		}	
		
	$photoCnt='';
	//START EXPERT CHERRYBOARD COMMENT AND CHEERS PATRS
	if($type=="add_expert_cheers"||$type=="add_cherry_expert_comment"||$type=="del_cherry_expert_comment"){
						$photoCnt.='<div class="bottom1">';
						$TotalCmt=getFieldValue('count(photo_id)',$tbl_name,'photo_id='.$photo_id);
						$TotalCheers=getFieldValue('count(cheers_id)',$tbl_cheers_name,'photo_id='.$photo_id);
						$checkCheers=(int)getFieldValue('user_id',$tbl_cheers_name,'photo_id='.$photo_id.' and user_id='.$user_id);
							if($checkCheers==0){
								$cheersLink='<div class="likes"><a href="javascript:void(0);" onclick="add_cherry_cheers(\''.$typeAddCheers.'\',\''.$photo_id.'\',\''.$cherryboard_id.'\',\''.$user_id.'\')" class="likes">+give cheers!</a></div>';
							}else{
								if($type=="add_cheers"||$type=="add_expert_cheers"){
									$cheersLink='<div class="likes"><span class="fgreen">Cheered!!</span></div>';
								}
							}
							$photoCnt.=$cheersLink.'<div class="coment" id="div_photo_cheers_'.$photo_id.'">'.(int)$TotalCheers.' Cheers &nbsp;&nbsp;'.(int)$TotalCmt.' Comments</div><br></div>';
							if($TotalCmt>0){
							  $selCmt=mysql_query("select * from ".$tbl_name." where photo_id=".$photo_id." order by comment_id desc limit 2");
							  while($cmtRow=mysql_fetch_array($selCmt)){
								   $userPhotoArray=getFieldsValueArray('first_name,last_name,fb_photo_url','tbl_app_users','user_id='.$cmtRow['user_id']);
								   $UserName=$userPhotoArray[0].' '.$userPhotoArray[1];
								   $UserPhoto=$userPhotoArray[2];
								   $comment_id=$cmtRow['comment_id'];
								   $PhotoComment=stripslashes($cmtRow['cherry_comment']);
								   $photoCnt.='<div class="leandro">
									 <div class="maryfeed"><div class="action"><a class="delete" href="javascript:void(0);" onclick="add_cherry_comment(event,\''.$typeName.'\','.$cherryboard_id.','.$photo_id.','.$cmtRow['user_id'].','.$comment_id.')"><img src="images/delete.png" title="Delete"></a></div></div>
									 <div class="leandro_1"><img src="'.$UserPhoto.'" class="img_small" /></div>
							<div class="leandro_2"><strong>'.$UserName.'</strong>&nbsp;'.$PhotoComment.'</div>
							</div>';
							  }
							}
			$photoCnt.='</div>';
	}else{
	//START CHERRYBOARD COMMENT AND CHEERS PATRS
				$photoCnt.='<div class="bottom">';
				$TotalCmt=getFieldValue('count(photo_id)',$tbl_name,'photo_id='.$photo_id);
						$TotalCheers=getFieldValue('count(cheers_id)',$tbl_cheers_name,'photo_id='.$photo_id);
						$checkCheers=(int)getFieldValue('user_id',$tbl_cheers_name,'photo_id='.$photo_id.' and user_id='.$user_id);
						if($checkCheers==0){
							$cheersLink='<div class="likes"><a href="javascript:void(0);" onclick="add_cherry_cheers(\''.$typeAddCheers.'\',\''.$photo_id.'\',\''.$cherryboard_id.'\',\''.$user_id.'\')" class="likes">+give cheers!</a></div>';
						}else{
							if($type=="add_cheers"||$type=="add_expert_cheers"){
								$cheersLink='<div class="likes"><span class="fgreen">Cheered!!</span></div>';
							}
						}
						$photoCnt.=$cheersLink.'<div class="coment" id="div_photo_cheers_'.$photo_id.'">'.(int)$TotalCheers.' Cheers &nbsp;&nbsp;'.(int)$TotalCmt.' Comments</div><br></div>';
							if($TotalCmt>0){
							  $selCmt=mysql_query("select * from ".$tbl_name." where photo_id=".$photo_id." order by comment_id desc limit 2");
							  while($cmtRow=mysql_fetch_array($selCmt)){
								   $userPhotoArray=getFieldsValueArray('first_name,last_name,fb_photo_url','tbl_app_users','user_id='.$cmtRow['user_id']);
								   $UserName=$userPhotoArray[0].' '.$userPhotoArray[1];
								   $UserPhoto=$userPhotoArray[2];
								   $comment_id=$cmtRow['comment_id'];
								   $PhotoComment=getLimitString(stripslashes($cmtRow['cherry_comment']),60);
				   				   $photoCnt.='<div class="mary">
									 <div class="maryfeed"><div class="action"><a class="delete" href="javascript:void(0);" onclick="add_cherry_comment(event,\''.$typeName.'\','.$cherryboard_id.','.$photo_id.','.$cmtRow['user_id'].','.$comment_id.')"><img src="images/delete.png" title="Delete"></a></div></div>
									 <div class="mary_1"><img src="'.$UserPhoto.'" class="img_small" /></div>
									 <div class="mary_2"><strong>'.$UserName.'</strong>&nbsp;'.$PhotoComment.'</div>
									 </div>';
								   
							  }
							}
						$photoCnt.='</div>';	
		}					
		$ajax_data.=$photoCnt;
		echo $photo_id.'###'.$ajax_data.'###'.$cherryboard_id;
}
//Get More Link
if($type=="get_more"){
	$gift_id=(int)$_GET['gift_id'];		
	if($gift_id>0){
		//Add Gift Link On DB
	$sel_gift=mysql_query("select campaign_detail,campaign_title from tbl_app_gift where gift_id=".$gift_id."");
				while($fetchGiftRow=mysql_fetch_array($sel_gift)){
					$campaign_detail=trim($fetchGiftRow['campaign_detail']);
					$campaign_title=ucwords($fetchGiftRow['campaign_title']);
					$ajax_data.='<font size="+1"><strong>'.$campaign_title.'</strong></font><br>
								'.($campaign_detail!=''?''.$campaign_detail.'':'No campaign details').'';
				}
			}
	$ajax_data=$type."##===##".$div_name."##===##".$ajax_data;
	echo $ajax_data;
}
//Post Link On Facebook
if($type=="fb_link_post"){
	$gift_id=(int)$_GET['gift_id'];
	$post_id=trim($_GET['post_id']);
	$user_id=(int)$_GET['user_id'];	
	//$cherryboard_id=(int)getFieldValue('cherryboard_id','tbl_app_cherry_gift','gift_id='.$gift_id);
	if($gift_id>0&&$user_id>0&&$post_id!=''){
		$ins_query=mysql_query("INSERT INTO tbl_app_gift_link 
						(link_id,user_id,gift_id,fb_post,twitter_post,pintrest_post,record_date) 
			VALUES (NULL,".$user_id.",".$gift_id.",'1','0','0','".date('Y-m-d')."')");
	}
	$ajax_data.='<img style="cursor:pointer" src="images/fb_thanks.png" width="101px" />&nbsp;
				<!--  <img src="images/twitter.jpg" width="101px"/>&nbsp;
				 <img src="images/pinterest.jpg" width="101px"/> -->';
	$ajax_data=$type."##===##".$div_name."##===##".$ajax_data;
	echo $ajax_data;
}
//Delete Campaign Checklist
if($type=="delete_chk"){
	$campaign_chk_id=(int)$_GET['campaign_chk_id'];
	$campaign_id=(int)$_GET['campaign_id'];
	if($campaign_chk_id>0&&$campaign_id>0){
	$del_query=mysql_query("DELETE FROM tbl_app_campaign_checklist WHERE campaign_chk_id=".$campaign_chk_id."");
		if($del_query){
			$cnt=1;
			$selchk=mysql_query("select * from tbl_app_campaign_checklist where campaign_id=".$campaign_id." order by campaign_chk_id");
			while($selchkRow=mysql_fetch_array($selchk)){
				$checklist=$selchkRow['checklist'];
				$campaign_ChkId=$selchkRow['campaign_chk_id'];
				$ajax_data.='<div class="box_container"><span>'.$cnt.'.&nbsp;'.$checklist.'</span>&nbsp;&nbsp;<a class="delete" onclick="ajax_action(\'delete_chk\',\'chk_div\',\'campaign_chk_id='.$campaign_ChkId.'&campaign_id='.$campaign_id.'\')" href="#"><img src="images/delete.png"></a></div>';
				$cnt++;
		    }
	    }
	}
	$ajax_data=$type."##===##".$div_name."##===##".$ajax_data;
	echo $ajax_data;
}
if($type=="edit_reward_title"){
	 $reward_id=(int)$_GET['rid'];
	 $reward_title=trim(getFieldValue('gift_title','tbl_app_gift','gift_id='.$reward_id));
	  
	 $ajax_data.='<input type="text" name="edit_reward_title" id="edit_reward_title" value="'.$reward_title.'" />&nbsp;<img src="images/save.png" height="20" style="cursor:pointer" onclick="ajax_action(\'save_reward_title\',\'div_reward'.$reward_id.'\',\'rid='.$reward_id.'\')" width="20" title="Save" />';
	 $ajax_data=$type."##===##".$div_name."##===##".$ajax_data;
	 echo $ajax_data;  
}else if($type=="save_reward_title"){
	$edit_reward_title=trim($_GET['edit_reward_title']);
	$reward_id=(int)$_GET['rid'];
	if($edit_reward_title!=''&&$reward_id>0){
		$updateDay=mysql_query("update tbl_app_gift set gift_title='".$edit_reward_title."' where gift_id=".$reward_id);
	}
	 $ajax_data.=''.ucwords($edit_reward_title).'&nbsp;&nbsp;<img src="images/edit.png" height="16" style="cursor:pointer" onclick="ajax_action(\'edit_reward_title\',\'div_reward'.$reward_id.'\',\'rid='.$reward_id.'\')" width="16" title="Edit" />'; 
	 $ajax_data=$type."##===##".$div_name."##===##".$ajax_data;
	 echo $ajax_data;
}
if($type=="edit_reward_picture"){
	 $reward_id=(int)$_GET['rid'];
	 $gift_photo=trim(getFieldValue('gift_photo','tbl_app_gift','gift_id='.$reward_id));
	  
	 $ajax_data.='<img src="images/gift/'.$gift_photo.'" height="100" width="100" /><form action="" method="post" name="frmrwd" enctype="multipart/form-data"><input type="file" name="edit_reward_picture" id="edit_reward_picture" size="15px" /><input type="hidden" id="save_reward_id" name="save_reward_id" value="'.$reward_id.'"/><input type="submit" class="btn_small right" id="saveEditPic" name="saveEditPic" value="save"/></form>';
	 $ajax_data=$type."##===##".$div_name."##===##".$ajax_data;
	 echo $ajax_data;  
}
//Delete Campaign Experts
if($type=="delete_cmp_expert"){
	 $expertId=(int)$_GET['expertId'];
	 if($expertId>0){
	 	$del_query=mysql_query("DELETE FROM tbl_app_campaign_experts WHERE campaign_expert_id=".$expertId."");
	 }
	 if($del_query){
		 $selExp=mysql_query("select expert_photo,expert_name,campaign_id from tbl_app_campaign_experts");
		 while($selExpRow=mysql_fetch_array($selExp)){
			   $expertPic=$selExpRow[0];
			   $expertName=$selExpRow[1];
			   $campaign_id=$selExpRow[2];
			   $expPicPath='images/gift/'.$expertPic;
			   $user_id=(int)getFieldValue('user_id','tbl_app_gift','gift_id='.$campaign_id);
		 if(is_file($expPicPath)){	 
		 $ajax_data.='<div class="img_big_container1">
					 <div class="feedbox_holder">
					 <div class="actions">'.($user_id==USER_ID?'<a href="javascript:void(0);"
					 onclick="ajax_action(\'delete_cmp_expert\',\'div_delete_exp\',\'expertId='.$expertId.'\')" class="delete"><img src="images/delete.png" title="Delete"></a>':'').'</div></div>
					 <img src="'.$expPicPath.'"  class="imgsmall" style="margin-top:4px;width: 40px;height: 40px;" title="'.$expertName.'" />&nbsp;</div>';
		 }
		}			 
	 }
	 $ajax_data=$type."##===##".$div_name."##===##".$ajax_data;
	 echo $ajax_data;  
}
//START SEND THANK YOU MAIL CODE
if($type=="sendThankYou"){
	 $ajax_data='';
	 $cherryboard_id=(int)$_GET['cherryboard_id'];
	 $send_email=trim($_GET['send_email']);
	 if($send_email!="Enter Email"&&$cherryboard_id>0){
	 	$send_emailArr=explode(',',$send_email);
		foreach($send_emailArr as $email_id){
			if($email_id!=""){
				//mail to user
				$GoalDetail=getFieldsValueArray('user_id,cherryboard_title','tbl_app_cherryboard','cherryboard_id='.$cherryboard_id);
				$user_id=$GoalDetail[0];
				$goal_title=$GoalDetail[1];
				$GiftName=getFieldValue('b.gift_title','tbl_app_cherry_gift a,tbl_app_gift b','a.gift_id=b.gift_id and a.cherryboard_id='.$cherryboard_id);
				
			    $user_nameDetail=getFieldsValueArray('first_name,last_name','tbl_app_users','user_id='.$user_id);
				$user_name=ucwords($user_nameDetail[0].' '.$user_nameDetail[1]);
				$to = $email_id;				
				$GoalToday=getGoalboardToday($cherryboard_id);				
				$subject=$goal_title.' : '.$GoalToday.' days out of 30 days! ';					
				$message='<table>
							<tr><td>Dear '.$first_name.',</td></tr>
							<tr><td>&nbsp;</td></tr>
							<tr><td>You are able to stick to your goal <strong>'.$goal_title.'</strong> for '.$GoalToday.' days. You gave only '.(30-$GoalToday).' more days to do to win the gift <strong>'.$GiftName.'</strong>.</td></tr>
							<tr><td>&nbsp;</td></tr>
							<tr><td>Keep it up!</td></tr>
							<tr><td>&nbsp;</td></tr>
							</table>';
				//$ajax_data.=$to."========".$subject."========".$message."========".$headers;
				SendMail($to,$subject,$message);
				$sentMail=1;
				if($sentMail){
				 	$msg='<div class="msg_green" style="padding-left:74px;">Mail sent successfully</div>';
				}else{
					$msg='<div class="msg_red" style="padding-left:74px;">Mail sending error...</div>';
				}
			}
						
		}
	 }else{
				$msg='<div class="msg_red" style="padding-left:74px;">Please enter valid email</div>';
			}
	 $ajax_data.='
				  <div id="div_send_thankYou">
						<div align="center" class="head_20">Send Thank You</div><br>
						'.$msg.'
						<span style="padding-left:30px;"><strong>Email</strong>:
				<input type="text" name="send_email" id="send_email" onblur="if(this.value==\'\') this.value=\'Enter Email\';" onfocus="if(this.value==\'Enter Email\') this.value=\'\';" value="Enter Email" /></span><br>	
					   <br>
						<input type="button" style="margin-left:110px;" class="btn_small" id="btnsend" onClick="ajax_action(\'sendThankYou\',\'div_send_thankYou\',\'cherryboard_id='.$cherryboard_id.'&send_email=\'+document.getElementById(\'send_email\').value);" value="Send" name="btnsend" />
					</div>';	
	 $ajax_data=$type."##===##".$div_name."##===##".$ajax_data;
	 echo $ajax_data;  
}
//START EXPERT SEND THANK YOU MAIL
if($type=="sendThankYou_Expert"){
	 $ajax_data='';
	 $cherryboard_id=(int)$_GET['cherryboard_id'];
	 $user_id=(int)$_GET['user_id'];
	 $send_email=trim($_GET['send_email']);
	 if($send_email!="Enter Email"&&$cherryboard_id>0){
	 	$send_emailArr=explode(',',$send_email);
		foreach($send_emailArr as $email_id){
			if($email_id!=""){
				//mail to user
				$ExpBoardDetail=getExpGoalDetail($cherryboard_id);
				$goal_title=ucwords($ExpBoardDetail['expertboard_title']);
				
			    $user_nameDetail=getFieldsValueArray('first_name,last_name','tbl_app_users','user_id='.$user_id);
				$user_name=ucwords($user_nameDetail[0].' '.$user_nameDetail[1]);
				$to = $email_id;				
				$subject='Join my Expert Board, '.$goal_title;
				$message='<table>
							<tr><td>Hi,</td></tr>
							<tr><td>&nbsp;</td></tr>
							<tr><td>I created "'.$goal_title.'" - Expert Board, to help my friends with my experience.</td></tr>
							<tr><td>I would appreciate if you can join by <a href="http://30daysnew.com/expert_cherryboard.php?cbid='.$cherryboard_id.'">Clicking Here</a>.</td></tr>
							<tr><td>&nbsp;</td></tr>
							<tr><td>Thanks</td></tr>
							<tr><td>'.$user_name.'</td></tr>
							</table>';
				//$ajax_data.=$to."========".$subject."========".$message;
				SendMail($to,$subject,$message);
				$sentMail=1;
				if($sentMail){
				 	$msg='<div class="msg_green" style="padding-left:74px;">Mail sent successfully</div>';
				}else{
					$msg='<div class="msg_red" style="padding-left:74px;">Mail sending error...</div>';
				}
			}
						
		}
	 }else{
				$msg='<div class="msg_red" style="padding-left:74px;">Please enter valid email</div>';
			}
	 $ajax_data.='
				  <div id="div_send_thankYou">
						<div align="center" class="head_20">Send Thank You</div><br>
						'.$msg.'
						<span style="padding-left:30px;"><strong>Email</strong>:
				<input type="text" name="send_email" id="send_email" onblur="if(this.value==\'\') this.value=\'Enter Email\';" onfocus="if(this.value==\'Enter Email\') this.value=\'\';" value="Enter Email" /></span><br>	
					   <br>
						<input type="button" style="margin-left:110px;" class="btn_small" id="btnsend" onClick="ajax_action(\'sendThankYou_Expert\',\'div_send_thankYou\',\'cherryboard_id='.$cherryboard_id.'&send_email=\'+document.getElementById(\'send_email\').value);" value="Send" name="btnsend" />
					</div>';	
	 $ajax_data=$type."##===##".$div_name."##===##".$ajax_data;
	 echo $ajax_data;  
}
?>