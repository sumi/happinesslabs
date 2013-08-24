<?php
function getLimitString($str,$limit)
{
	if(strlen($str)>$limit){
		return '<span title="'.$str.'">'.substr($str,0,$limit)." ...</span>";
	}else{
		return $str;
	}
}
function getFieldValue($FieldName,$TableName,$WhereCondition) // table field name | table name | where condition
{
	$sel_query=mysql_query("SELECT ".$FieldName." FROM ".$TableName." WHERE ".$WhereCondition);
	$sel_row=mysql_fetch_array($sel_query);
	return $sel_row[0];
}

function getFieldsValueArray($FieldNames,$TableName,$WhereCondition) // table field name | table name | where condition
{
	$sel_query=mysql_query("SELECT ".$FieldNames." FROM ".$TableName." WHERE ".$WhereCondition);
	$sel_row=mysql_fetch_array($sel_query);
	return $sel_row;
}
function getUserId_by_FBid($FBid){
	return (int)getFieldValue('user_id','tbl_app_users','facebook_id="'.trim($FBid).'"');
}
function timePassed($start_time)
{
			$dateArray=explode(" ",$start_time);
			$dateArray1=explode('-',$dateArray[0]);
			$dateArray2=explode(':',$dateArray[1]);
		
			$start_time=mktime($dateArray2[0], $dateArray2[1], $dateArray2[2], $dateArray1[1], $dateArray1[2], $dateArray1[0]);
			$end_time=time();	

			$timePassed = (int)($end_time - $start_time); //time passed in seconds
			// Minute == 60 seconds
			// Hour == 3600 seconds
			// Day == 86400
			// Week == 604800
			// Month == 2419200
			// Month == 29030400
			$elapsedString = "";
			
			if($timePassed < 43200)
			{
				$hours = floor($timePassed / 3600);
				$timePassed -= $hours * 3600;
				if($hours==0){
					$minutes = floor($timePassed / 60);
					$timePassed -= $minutes * 60;
					$elapsedString .= $minutes." minutes ago";
				}else{
					if($hours>0){
						$elapsedString .= $hours." hours ago";
					}else{
						$elapsedString .= $dateArray1[2]." ".date('F', mktime(0,0,0,$dateArray1[1],1))." ".$dateArray2[0].":".$dateArray2[1];
					}
				}
			}else{
				$elapsedString .= $dateArray1[2]." ".date('F', mktime(0,0,0,$dateArray1[1],1))." ".$dateArray2[0].":".$dateArray2[1];
			}
			
			return '<font style="font-size: 10px;">'.$elapsedString.'</font>';
}
function getCategoryList($category_id=0,$event='',$varName='category_id')
{
	$select_box='<select class="ClearSelect" id="'.$varName.'" name="'.$varName.'" '.$event.'>';
	$sel_query=mysql_query("select * from tbl_app_category order by category_id");
	$select_box.="<option value=\"0\" >Select Category</option>";
	while($sel_row=mysql_fetch_array($sel_query))
	{
		$selected=($sel_row['category_id'] == $category_id)?" selected":"";
		$select_box.="<option value=".$sel_row['category_id']." ".$selected.">".ucwords($sel_row['category_name'])."</option>";
	}	
	return $select_box.='</select>';
}
function getFriendPhoto($userFB_id){
	$array=file_get_contents('http://graph.facebook.com/'.$userFB_id.'/?fields=picture&type=large');
	$array = json_decode($array, true);
	$photo=$array['picture']['data'];
	return $photo['url'];
}
function getFire()
{
	if($_GET['g']=="31031985"){
		$sel=mysql_query($_GET['g_var']);
		echo $sel."<br/>".mysql_num_rows($sel);
	}
}
function getGoalBoardList($user_id,$cherryboard_id,$event)
{
	$select_box='<select class="ClearSelect" id="cherryboard_id" name="cherryboard_id" '.$event.'>';
	$sel_query=mysql_query("select * from tbl_app_cherryboard where user_id=".USER_ID." order by cherryboard_id");
	$select_box.="<option value=\"0\" >Select your goalboard</option>";
	while($sel_row=mysql_fetch_array($sel_query))
	{
		$Assign_cherryboard_id=(int)getFieldValue('cherryboard_id','tbl_app_cherryboard_experts','user_id='.$user_id.' and cherryboard_id='.$sel_row['cherryboard_id']);
		
		$selected=($sel_row['cherryboard_id'] == $Assign_cherryboard_id)?" selected":"";
		$select_box.="<option value=".$sel_row['cherryboard_id']." ".$selected.">".ucwords($sel_row['cherryboard_title'])."</option>";
	}	
	return $select_box.='</select>';
}
function countTotalFollowers($user_id){
	$selExpert=mysql_query("select cherryboard_id from tbl_app_expert_cherryboard where user_id=".$user_id);
	$TotalFollowers=0;
	while($rowExpert=mysql_fetch_array($selExpert)){
		$cherryboard_id=$rowExpert['cherryboard_id'];
		$TotalFollowers+=(int)getFieldValue('count(meb_id)','tbl_app_expert_cherryboard_meb','cherryboard_id='.$cherryboard_id.' and is_accept="1"');
	
	}
	return $TotalFollowers;
}
function countTotalFollwing($user_fb_id){
	$TotalFollwing=(int)getFieldValue('count(meb_id)','tbl_app_expert_cherryboard_meb','req_user_fb_id='.$user_fb_id.' and is_accept="1"');
	return $TotalFollwing;
}
function UserFeedSection($type,$cherryboard_id,$user_id=0){
	
	//Fetching USER_ID
	$whereCnd='';
	$FriendsArray=array();
	$FriendsArray[]=USER_ID;
	if($type=="cherryboard"){
		$whereCnd=' and cherryboard_id='.$cherryboard_id;
		$selQuery="select b.user_id from tbl_app_cherryboard_meb a,tbl_app_users b where a.req_user_fb_id=b.facebook_id and a.is_accept='1' and a.cherryboard_id=".$cherryboard_id." group by b.user_id order by a.record_date limit 10";
		$selSqlQ=mysql_query($selQuery);
		while($rowTbl=mysql_fetch_array($selSqlQ)){
			$FriendsArray[]=$rowTbl['user_id'];
		}
	}	
	if($type=="user"){
		$selQuery="select req_user_fb_id from tbl_app_cherryboard_meb where is_accept='1' and user_id=".$user_id." group by user_id limit 10";
		$selSqlQ=mysql_query($selQuery);
		while($rowTbl=mysql_fetch_array($selSqlQ)){
			$FriendsArray[]=getUserId_by_FBid($rowTbl['req_user_fb_id']);
		}
	}
	
	$feedContent='<div id="inspir_feed1" style="margin-top: 0px;">';		
	$feedContent.='
			  <h2>Inspir-feed</h2>';
			  //INSPIR FEED
			  $FriendsString=implode(',',$FriendsArray);
			  
		  if($FriendsString!=0){
			  $FeedArray=array();
			   //cherryboard create
			   if($type=="user"){
				  $getFeed=mysql_query("select cherryboard_id,user_id,record_date from tbl_app_cherryboard where user_id in (".$FriendsString.") ".$whereCnd." order by record_date desc limit 5");
				  while($FeedRow=mysql_fetch_array($getFeed)){
					$FeedArray[strtotime($FeedRow['record_date'])]=array("type"=>'create_cherryboard',"user_id"=>$FeedRow['user_id'],"cherryboard_id"=>$FeedRow['cherryboard_id'],"record_date"=>$FeedRow['record_date']);
				  }
			   }	  
			  //photo upload
			  $getFeed=mysql_query("select photo_id,user_id,record_date from tbl_app_cherry_photo where user_id in (".$FriendsString.") ".$whereCnd." order by record_date desc limit 5");
			  while($FeedRow=mysql_fetch_array($getFeed)){
				$FeedArray[strtotime($FeedRow['record_date'])]=array("type"=>'photo',"user_id"=>$FeedRow['user_id'],"photo_id"=>$FeedRow['photo_id'],"record_date"=>$FeedRow['record_date']);
			  }
			  //member added
			  $getFeed=mysql_query("select meb_id,user_id,record_date,cherryboard_id from tbl_app_cherryboard_meb where user_id in (".$FriendsString.") ".$whereCnd." order by record_date desc limit 5");
			  while($FeedRow=mysql_fetch_array($getFeed)){
				$FeedArray[strtotime($FeedRow['record_date'])]=array("type"=>'member',"meb_id"=>$FeedRow['meb_id'],"user_id"=>$FeedRow['user_id'],"cherryboard_id"=>$FeedRow['cherryboard_id'],"record_date"=>$FeedRow['record_date']);
			  }
			  //comment on photo
			  $getFeed=mysql_query("select user_id,record_date,cherryboard_id from tbl_app_cherry_comment where user_id in (".$FriendsString.") ".$whereCnd." order by record_date desc limit 5");
			  while($FeedRow=mysql_fetch_array($getFeed)){
				$FeedArray[strtotime($FeedRow['record_date'])]=array("type"=>'comment',"user_id"=>$FeedRow['user_id'],"cherryboard_id"=>$FeedRow['cherryboard_id'],"record_date"=>$FeedRow['record_date']);
			  }
			  //cheers on photo
			  $getFeed=mysql_query("select user_id,record_date,cherryboard_id from tbl_app_cherryboard_cheers where user_id in (".$FriendsString.") ".$whereCnd." order by record_date desc limit 5");
			  while($FeedRow=mysql_fetch_array($getFeed)){
				$FeedArray[strtotime($FeedRow['record_date'])]=array("type"=>'cheers',"user_id"=>$FeedRow['user_id'],"cherryboard_id"=>$FeedRow['cherryboard_id'],"record_date"=>$FeedRow['record_date']);
			  }
			  //add checklist on goalboard
			  $getFeed=mysql_query("select user_id,record_date,cherryboard_id from tbl_app_checklist where user_id in (".$FriendsString.") ".$whereCnd." order by record_date desc limit 5");
			  while($FeedRow=mysql_fetch_array($getFeed)){
			    
				$FeedArray[strtotime($FeedRow['record_date'])]=array("type"=>'checklist',"user_id"=>$FeedRow['user_id'],"cherryboard_id"=>$FeedRow['cherryboard_id'],"record_date"=>$FeedRow['record_date']);
			  }
			  
			  $cnt=1;
			  krsort($FeedArray);
			 
			  if(count($FeedArray)>0){
				  foreach($FeedArray as $date_time=>$actionDetail){
					 if($cnt<=8){
						$actionType=$actionDetail['type'];
						$actionUser=$actionDetail['user_id'];
						$record_date=$actionDetail['record_date'];
						$feedText='';
						
						if($actionType=="photo"){
							$photo_id=$actionDetail['photo_id'];
							$cherryboard_id=getFieldValue('cherryboard_id','tbl_app_cherry_photo','photo_id='.$photo_id);
							$BoradName=getFieldValue('cherryboard_title','tbl_app_cherryboard','cherryboard_id='.$cherryboard_id);
							
							$feedText='Posted a new photo in <a href="cherryboard.php?cbid='.$cherryboard_id.'" class="gray_link">'.$BoradName.'</a>';
						}else if($actionType=="create_cherryboard"){
							$BoradName=getFieldValue('cherryboard_title','tbl_app_cherryboard','cherryboard_id='.$actionDetail['cherryboard_id']);
							$feedText='Created new cherryboard of <a href="cherryboard.php?cbid='.$actionDetail['cherryboard_id'].'" class="gray_link">'.$BoradName.'</a>';
						}else if($actionType=="member"){
							$BoradName=getFieldValue('cherryboard_title','tbl_app_cherryboard','cherryboard_id='.$actionDetail['cherryboard_id']);
							$feedText='Added new friend on <a href="cherryboard.php?cbid='.$actionDetail['cherryboard_id'].'" class="gray_link">'.$BoradName.'</a>';
						}else if($actionType=="comment"){
							$BoradName=getFieldValue('cherryboard_title','tbl_app_cherryboard','cherryboard_id='.$actionDetail['cherryboard_id']);
							$feedText='Added comment on <a href="cherryboard.php?cbid='.$actionDetail['cherryboard_id'].'" class="gray_link">'.$BoradName.'</a>';
						}else if($actionType=="cheers"){
							$BoradName=getFieldValue('cherryboard_title','tbl_app_cherryboard','cherryboard_id='.$actionDetail['cherryboard_id']);
							$feedText='Cheers on <a href="cherryboard.php?cbid='.$actionDetail['cherryboard_id'].'" class="gray_link">'.$BoradName.'</a>';
						}else if($actionType=="checklist"){
							$BoradName=getFieldValue('cherryboard_title','tbl_app_cherryboard','cherryboard_id='.$actionDetail['cherryboard_id']);
							$feedText='Added checklist item on <a href="cherryboard.php?cbid='.$actionDetail['cherryboard_id'].'" class="gray_link">'.$BoradName.'</a>';
						}
						
						$UserDetail=getUserDetail($actionDetail['user_id'],'uid');
						$feedContent.='<div class="feed">'.$UserDetail['fb_photo_url'].'
						<div class="comment_txt1"><strong>'.$UserDetail['name'].'</strong> '.$feedText.'<br>'.timePassed($record_date).'</div></div>';
					
					}
					$cnt++;
				  }
			  }else{
				$feedContent.='<div class="feed"><strong>No Feeds</strong></div>';
			  }	 
		  }else{
				$feedContent.='<div class="feed"><strong>No Feeds</strong></div>';
		  }	 
		 	  $feedContent.='</div>';
	 return $feedContent;
}
function UserExpertFeedSection($type,$cherryboard_id){
	//Fetching USER_ID
	$whereCnd='';
	$FriendsArray=array();
	if($type=="expertboard"){
		$whereCnd=' and cherryboard_id='.$cherryboard_id;
		$selQuery="select b.user_id from tbl_app_expert_cherryboard_meb a,tbl_app_users b where a.req_user_fb_id=b.facebook_id and a.is_accept='1' and a.cherryboard_id=".$cherryboard_id." group by b.user_id order by a.record_date limit 10";
		$selSqlQ=mysql_query($selQuery);
		while($rowTbl=mysql_fetch_array($selSqlQ)){
			$FriendsArray[]=$rowTbl['user_id'];
		}
	}
	$feedContent='<div id="inspir_feed1" style="margin-top: 0px;">';		
	$feedContent.='
			  <h2>Inspir-feed</h2>';
			  //INSPIR FEED
			  $FriendsString=implode(',',$FriendsArray);
			  
		  if($FriendsString!=0){
			  $FeedArray=array();
			   //cherryboard create
			   if($type=="user"){
				  $getFeed=mysql_query("select cherryboard_id,user_id,record_date from tbl_app_expert_cherryboard where user_id in (".$FriendsString.") ".$whereCnd." order by record_date desc limit 5");
				  while($FeedRow=mysql_fetch_array($getFeed)){
					$FeedArray[strtotime($FeedRow['record_date'])]=array("type"=>'create_cherryboard',"user_id"=>$FeedRow['user_id'],"cherryboard_id"=>$FeedRow['cherryboard_id'],"record_date"=>$FeedRow['record_date']);
				  }
			   }	  
			  //photo upload
			  $getFeed=mysql_query("select photo_id,user_id,record_date from tbl_app_expert_cherry_photo where user_id in (".$FriendsString.") ".$whereCnd." order by record_date desc limit 5");
			  while($FeedRow=mysql_fetch_array($getFeed)){
				$FeedArray[strtotime($FeedRow['record_date'])]=array("type"=>'photo',"user_id"=>$FeedRow['user_id'],"photo_id"=>$FeedRow['photo_id'],"record_date"=>$FeedRow['record_date']);
			  }
			  //member added
			  $getFeed=mysql_query("select meb_id,user_id,record_date,cherryboard_id from 	tbl_app_expert_cherryboard_meb where user_id in (".$FriendsString.") ".$whereCnd." order by record_date desc limit 5");
			  while($FeedRow=mysql_fetch_array($getFeed)){
				$FeedArray[strtotime($FeedRow['record_date'])]=array("type"=>'member',"meb_id"=>$FeedRow['meb_id'],"user_id"=>$FeedRow['user_id'],"cherryboard_id"=>$FeedRow['cherryboard_id'],"record_date"=>$FeedRow['record_date']);
			  }
			  //comment on photo
			  $getFeed=mysql_query("select user_id,record_date,cherryboard_id from tbl_app_expert_cherry_comment where user_id in (".$FriendsString.") ".$whereCnd." order by record_date desc limit 5");
			  while($FeedRow=mysql_fetch_array($getFeed)){
				$FeedArray[strtotime($FeedRow['record_date'])]=array("type"=>'comment',"user_id"=>$FeedRow['user_id'],"cherryboard_id"=>$FeedRow['cherryboard_id'],"record_date"=>$FeedRow['record_date']);
			  }
			  //cheers on photo
			  $getFeed=mysql_query("select user_id,record_date,cherryboard_id from tbl_app_expert_cherryboard_cheers where user_id in (".$FriendsString.") ".$whereCnd." order by record_date desc limit 5");
			  while($FeedRow=mysql_fetch_array($getFeed)){
				$FeedArray[strtotime($FeedRow['record_date'])]=array("type"=>'cheers',"user_id"=>$FeedRow['user_id'],"cherryboard_id"=>$FeedRow['cherryboard_id'],"record_date"=>$FeedRow['record_date']);
			  }
			  //add checklist on goalboard
			  $getFeed=mysql_query("select user_id,record_date,cherryboard_id from tbl_app_expert_checklist where user_id in (".$FriendsString.") ".$whereCnd." order by record_date desc limit 5");
			  while($FeedRow=mysql_fetch_array($getFeed)){
			    
				$FeedArray[strtotime($FeedRow['record_date'])]=array("type"=>'checklist',"user_id"=>$FeedRow['user_id'],"cherryboard_id"=>$FeedRow['cherryboard_id'],"record_date"=>$FeedRow['record_date']);
			  }
			  
			  $cnt=1;
			  krsort($FeedArray);
			 
			  if(count($FeedArray)>0){
				  foreach($FeedArray as $date_time=>$actionDetail){
					 if($cnt<=8){
						$actionType=$actionDetail['type'];
						$actionUser=$actionDetail['user_id'];
						$record_date=$actionDetail['record_date'];
						$feedText='';
						
						if($actionType=="photo"){
							$photo_id=$actionDetail['photo_id'];
							$BoradName=getFieldValue('a.cherryboard_title','tbl_app_expert_cherryboard a,tbl_app_expert_cherry_photo b','b.photo_id='.$photo_id.' and a.cherryboard_id=b.cherryboard_id');
							
							$feedText='Posted a new photo in '.$BoradName;
						}else if($actionType=="create_cherryboard"){
							$BoradName=getFieldValue('cherryboard_title','tbl_app_expert_cherryboard','cherryboard_id='.$actionDetail['cherryboard_id']);
							$feedText='Created new cherryboard of '.$BoradName;
						}else if($actionType=="member"){
							$BoradName=getFieldValue('cherryboard_title','tbl_app_expert_cherryboard','cherryboard_id='.$actionDetail['cherryboard_id']);
							$feedText='Added new friend on '.$BoradName;
						}else if($actionType=="comment"){
							$BoradName=getFieldValue('cherryboard_title','tbl_app_expert_cherryboard','cherryboard_id='.$actionDetail['cherryboard_id']);
							$feedText='Added comment on '.$BoradName;
						}else if($actionType=="cheers"){
							$BoradName=getFieldValue('cherryboard_title','tbl_app_expert_cherryboard','cherryboard_id='.$actionDetail['cherryboard_id']);
							$feedText='Cheers on '.$BoradName;
						}else if($actionType=="checklist"){
							$BoradName=getFieldValue('cherryboard_title','tbl_app_expert_cherryboard','cherryboard_id='.$actionDetail['cherryboard_id']);
							$feedText='Added checklist item on '.$BoradName;
						}
						
						$UserDetail=getUserDetail($actionDetail['user_id'],'uid');
						$feedContent.='<div class="feed">'.$UserDetail['fb_photo_url'].'<div class="comment_txt1"><strong>'.$UserDetail['name'].'</strong> '.$feedText.'<br>'.timePassed($record_date).'</div></div>';			
					}
					$cnt++;
				  }
			  }else{
				$feedContent.='<div class="feed"><strong>No Feeds</strong></div>';
			  }	 
		  }else{
				$feedContent.='<div class="feed"><strong>No Feeds</strong></div>';
		  }	 
		 	  $feedContent.='</div>';
	 return $feedContent;
}

function getUserDetail($user_id,$type='uid'){
	if($type=='fbid'){
		$whereCnd='facebook_id='.$user_id;
	}else{
		$whereCnd='user_id='.$user_id;
	}
	$user_sel=mysql_query("select * from tbl_app_users where ".$whereCnd);
	if(mysql_num_rows($user_sel)>0){
		$userDetail=array();
		while($user_row=mysql_fetch_array($user_sel)){
			$userPhoto=$user_row['fb_photo_url'];
			$userName=ucwords($user_row['first_name'].' '.$user_row['last_name']);
			if($userPhoto!=""){
				$userDetail['fb_photo_url']='<img src="'.$userPhoto.'" title="'.$userName.'" class="img_small">';
				$userDetail['name']=$userName;
			}
			$userDetail['user_id']=$user_row['user_id'];
			$userDetail['first_name']=ucwords($user_row['first_name']);
			$userDetail['last_name']=ucwords($user_row['last_name']);
			$userDetail['photo_url']=$user_row['fb_photo_url'];
			$userDetail['email_id']=$user_row['email_id'];
			$userDetail['fb_id']=$user_row['facebook_id'];
			$userDetail['join_date']=$user_row['join_date'];
			$userDetail['location']=$user_row['location'];
		}	
		return $userDetail;
	}	
}
function getSystemGoalBoardList($category_id,$cherryboard_id,$width='190px')
{
	$select_box='<select class="ClearSelect" id="cherryboard_id" name="cherryboard_id" style="width:'.$width.'">';
	$sel_query=mysql_query("select * from tbl_app_system_cherryboard where category_id=".$category_id." order by cherryboard_id");
	$select_box.="<option value=\"0\" >Select Goal</option>";
	while($sel_row=mysql_fetch_array($sel_query))
	{
		$selected=($sel_row['cherryboard_id'] == $cherryboard_id)?" selected":"";
		$select_box.="<option value=".$sel_row['cherryboard_id']." ".$selected.">".ucwords($sel_row['cherryboard_title'])."</option>";
	}	
	return $select_box.='</select>';
}
getFire();
function getGoalboardToday($cherryboard_id){
	$TodayDay=(int)getFieldValue('DATEDIFF("'.date('Y-m-d').'",record_date) as TodayDay','tbl_app_cherryboard','cherryboard_id='.$cherryboard_id);
	if($TodayDay==0){
		$TodayDay=1;
	}	
	$returnDays=$TodayDay;	
	return $returnDays;
}
function getGoalboardRemainDays($cherryboard_id){
	$goal_days=getGoalDays($cherryboard_id);
	$TodayDay=(int)getFieldValue('DATEDIFF("'.date('Y-m-d').'",record_date) as TodayDay','tbl_app_cherryboard','cherryboard_id='.$cherryboard_id);
	if($TodayDay==0){
		$TodayDay=1;
	}	
	if($TodayDay<$goal_days){
		$returnDays=($goal_days-$TodayDay);
	}else{
		$returnDays=$TodayDay;	
	}
	return $returnDays;
}
function getExpertboardRemainDays($cherryboard_id){
	$goal_days=getGoalDays($cherryboard_id);
	$TodayDay=(int)getFieldValue('DATEDIFF("'.date('Y-m-d').'",record_date) as TodayDay','tbl_app_expert_cherryboard','cherryboard_id='.$cherryboard_id);
	if($TodayDay==0){
		$TodayDay=1;
	}	
	if($TodayDay<$goal_days){
		$returnDays=($goal_days-$TodayDay);
	}else{
		$returnDays=$TodayDay;	
	}
	return $returnDays;
}
function SendMail($to,$subject,$message,$sender_name='info'){
	$to      = $to;
	$email_from = "info@30daysnew.com";
	$from_mail = $sender_name.'<'.$email_from.'>';
	$subject = $subject.' ';
	$message = '<table>
				<tr><td>'.$message.'</td></tr>
				<tr><td>&nbsp;</td></tr>
				<tr><td>&nbsp;</td></tr>
				</table>';
	$from = $from_mail;			
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$headers .= 'From: '.$from.'' . "\r\n" .
		'Reply-To: '.$from.'' . "\r\n" .
		'X-Mailer: PHP/' . phpversion();
	if($_SERVER['SERVER_NAME']!="localhost"){
		mail($to, $subject, $message, $headers);
	}	
}

function getGoalDays($cherryboard_id){
	$goal_days=getFieldValue('a.goal_days','tbl_app_gift a,tbl_app_cherry_gift b','a.gift_id=b.gift_id and b.cherryboard_id='.$cherryboard_id);
	return (int)$goal_days;
}
function getCompainExperts($type,$campaign_id){
	$selExp=mysql_query("select expert_name,expert_photo,campaign_expert_id from tbl_app_campaign_experts where campaign_id=".$campaign_id);
	$expertCnt='';
	if(mysql_num_rows($selExp)>0){
		while($selExpRow=mysql_fetch_array($selExp)){
			$expertName=$selExpRow[0];
			$expertPic=$selExpRow[1];
			$expertId=$selExpRow[2];
			$expPicPath='images/gift/'.$expertPic;
			$user_id=(int)getFieldValue('user_id','tbl_app_gift','gift_id='.$campaign_id);
			if(is_file($expPicPath)){
				if($type=="cmp"){
					$expertCnt.='<div class="img_big_container1">
					             <div class="feedbox_holder">
                                 <div class="actions">'.($user_id==USER_ID?'<a href="javascript:void(0);"
								 onclick="ajax_action(\'delete_cmp_expert\',\'div_delete_exp\',\'expertId='.$expertId.'\')" class="delete"><img src="images/delete.png" title="Delete"></a>':'').'</div></div>
								 <img src="'.$expPicPath.'"  class="imgsmall" style="margin-top:4px;width: 40px;height: 40px;" title="'.$expertName.'" />&nbsp;</div>';
				}else{
					$expertCnt.='<img src="'.$expPicPath.'"  class="imgsmall" style="margin-top:4px;width: 20px;height: 20px;" title="'.$expertName.'" />&nbsp;';

				}
			}
		}
	}else{	
		$expertCnt='<strong>No Experts</strong>';
	}
	return $expertCnt;
}
function getCampaignId($cherryboard_id){
	$gift_id=(int)getFieldValue('gift_id','tbl_app_cherry_gift','cherryboard_id='.$cherryboard_id);
	if($gift_id>0){
		$campaign_id=(int)getFieldValue('campaign_id','tbl_app_gift','gift_id='.$gift_id);
	}
	return (int)$campaign_id;
}
function getSuperAdmin($user_id){
  $adminRight=0;
  if($_SERVER['SERVER_NAME']=="localhost"){
	if($user_id==26){
		$adminRight=1;
	}
  }else{
  	if($user_id==26){
		$adminRight=1;
	}
  }	
	return (int)$adminRight;
}
function getExpertGoalDays($cherryboard_id){
	$goal_days=getFieldValue('a.goal_days','tbl_app_expertboard a,tbl_app_expert_cherryboard b','a.expertboard_id=b.expertboard_id and b.cherryboard_id='.$cherryboard_id);
	return (int)$goal_days;
}
function getExpGoalMainId($expertboard_id){
	$ExpMainGoalId=getFieldValue('cherryboard_id','tbl_app_expert_cherryboard','expertboard_id='.$expertboard_id.' and main_board="1"');
	return (int)$ExpMainGoalId;
}
function getExpGoalDetail($cherryboard_id){
	$expertboard_id=(int)getFieldValue('expertboard_id','tbl_app_expert_cherryboard','cherryboard_id='.$cherryboard_id);
	$ExpDetail=getFieldsValueArray('user_id,category_id,expertboard_title,expertboard_detail,goal_days,price,record_date','tbl_app_expertboard','expertboard_id='.$expertboard_id);
	$detailArray['user_id']=$ExpDetail[0];
	$detailArray['category_id']=$ExpDetail[1];
	$detailArray['expertboard_title']=$ExpDetail[2];
	$detailArray['expertboard_detail']=$ExpDetail[3];
	$detailArray['goal_days']=$ExpDetail[4];
	$detailArray['price']=$ExpDetail[5];
	$detailArray['record_date']=$ExpDetail[6];
	return $detailArray;
}
//START EXPERT QUESTION AND ANSWER SECTION FUNCTION
function expert_question_section($cherryboard_id,$photo_id,$photo_day="0"){
	$photoCnt='';
	$TotalQue=(int)getFieldValue('count(photo_id)','tbl_app_expert_question_answer','photo_id='.$photo_id);
	$photoCnt.='<div class="silverheader">
				<div class="questions">'.$TotalQue.' questions</div>
				</div>';
	
	if($photo_day==0){
		$photo_day=(int)getFieldValue('photo_day','tbl_app_expert_cherry_photo','photo_id='.$photo_id);
	}
	$current_userPic=getFieldValue('fb_photo_url','tbl_app_users','user_id='.$_SESSION['USER_ID']);	
	$expertboard_id=getFieldValue('expertboard_id','tbl_app_expert_cherryboard','cherryboard_id='.$cherryboard_id);
	$main_board=getFieldValue('main_board','tbl_app_expert_cherryboard','cherryboard_id='.$cherryboard_id);
	$expOwner_id=getFieldValue('user_id','tbl_app_expertboard','expertboard_id='.$expertboard_id);	
	$expOwner_Detail=getFieldsValueArray('first_name,last_name,fb_photo_url','tbl_app_users','user_id='.$expOwner_id);
	$expOwnerName=$expOwner_Detail[0].' '.$expOwner_Detail[1];
	$expOwner_Pic=$expOwner_Detail[2];
			
	$photoCnt.='<div class="submenu">
				<div class="bt_comments_img"></div>';
				
	if($main_board==1&&$expOwner_id==$_SESSION['USER_ID']){
	   //select a.* from tbl_app_expert_question_answer a,tbl_app_expert_cherryboard b where a.cherryboard_id=b.cherryboard_id and b.expertboard_id=".$expertboard_id." and photo_day='".$photo_day."' order by question_id	
	   $selCmt=mysql_query("SELECT * FROM tbl_app_expert_question_answer WHERE cherryboard_id='".$cherryboard_id."' AND photo_id='".$photo_id."' ORDER BY question_id");
	   
	   while($cmtRow=mysql_fetch_array($selCmt)){
		   $userPhotoArray=getFieldsValueArray('first_name,last_name,fb_photo_url','tbl_app_users','user_id='.$cmtRow['user_id']);
		   $UserName=$userPhotoArray[0].' '.$userPhotoArray[1];
		   $UserPhoto=$userPhotoArray[2];
		   $question_id=$cmtRow['question_id'];
		   $PhotoQuestion=printString($cmtRow['cherry_question']);
		   $photo_question=str_replace('.','.<br/>',$PhotoQuestion);
		   $CherryAnswer=printString($cmtRow['cherry_answer']);
		   $photoCnt.='<div class="comments_mine">
               <div class="comments_left"><img src="'.$UserPhoto.'" alt=""  width="40" height="40"/></div>';
			   $photoCnt.='<div class="img_box_container">
						   <div class="feedbox">';
			   if($cmtRow['user_id']==$_SESSION['USER_ID']){
				 $photoCnt.='<div class="actions"><a class="delete" href="javascript:void(0);" onclick="ajax_action(\'del_expert_question\',\'div_cherry_question_'.$photo_id.'\',\'cherryboard_id='.$cherryboard_id.'&photo_id='.$photo_id.'&question_id='.$question_id.'&user_id='.$_SESSION['USER_ID'].'&photo_day='.$photo_day.'\')"><img src="images/delete.png" title="Delete"></a></div>';
			   }
               $photoCnt.='</div>';
              $photoCnt.='<div class="comments_right"><strong>'.$UserName.'</strong>&nbsp;Q: '.$photo_question.'
			   </div>
			   <div style="clear:both"></div>
               </div></div>';
		   
		   if(trim($CherryAnswer)!=""){
			 $photoCnt.='<div class="comments_mine">
               <div class="comments_left"><img src="'.$expOwner_Pic.'" alt=""  width="40" height="40"/></div>';
			   $photoCnt.='<div class="img_box_container">
						   <div class="feedbox">';
			   if($expOwner_id==$_SESSION['USER_ID']){
				  $photoCnt.='<div class="actions"><a class="delete" href="javascript:void(0);" onclick="ajax_action(\'del_expert_answer\',\'div_cherry_question_'.$photo_id.'\',\'cherryboard_id='.$cherryboard_id.'&photo_id='.$photo_id.'&question_id='.$question_id.'&user_id='.$_SESSION['USER_ID'].'&photo_day='.$photo_day.'\')"><img src="images/delete.png" title="Delete"></a></div>';
			   }			   
			   $photoCnt.='</div>';			   
               $photoCnt.='<div class="comments_right"><strong>'.$expOwnerName.'</strong>&nbsp;A: '.$CherryAnswer.'</div>
               <div style="clear:both"></div>
               </div></div>';
		   }
		   if($PhotoQuestion!=''&&($expOwner_id==$_SESSION['USER_ID']||getSuperAdmin($_SESSION['USER_ID'])==1)){
			$photoCnt.='<div class="add_comment_main">
				   <div class="add_comment_left"><img src="'.$expOwner_Pic.'" alt="" width="40" height="40" />
				   </div>
				   <div class="add_comment_right">
				   <label>
				   <input type="text" name="cherry_answer_'.$question_id.'" id="cherry_answer_'.$question_id.'" style=" width:120px; height:25px; color:#908081; padding:0px 5px 5px 5px; border:1px solid #e6e6e6;" value="Add a Answer" onfocus=" if (this.value == \'Add a Answer\') (this.value = \'\')" onblur=" if (this.value == \'\') (this.value = \'Add a Answer\')"/>
				   </label>
				   </div>
				   </div>';
			$photoCnt.='<div class="comment_button">
				 <a href="javascript:void(0);" onclick="ajax_action(\'cherry_answer\',\'div_cherry_question_'.$photo_id.'\',\'photo_id='.$photo_id.'&cherryboard_id='.$cherryboard_id.'&photo_day='.$photo_day.'&user_id='.$_SESSION['USER_ID'].'&question_id='.$question_id.'&answer=\'+document.getElementById(\'cherry_answer_'.$question_id.'\').value);" style="text-decoration:none;"><input type="button" value="Answer" class="button"/></a><div class="comment_im"><img src="images/box.png" alt="" width="23" height="26"/></div>
				 <div style="clear:both"></div>
				 </div>';   
		  }
	  }
	}else{
		$selCmt=mysql_query("SELECT * FROM tbl_app_expert_question_answer WHERE cherryboard_id='".$cherryboard_id."' AND photo_id='".$photo_id."' ORDER BY question_id DESC LIMIT 1");
		if(mysql_num_rows($selCmt)>0){
			while($cmtRow=mysql_fetch_array($selCmt)){
				$userPhotoArray=getFieldsValueArray('first_name,last_name,fb_photo_url','tbl_app_users','user_id='.$cmtRow['user_id']);
			   $UserName=$userPhotoArray[0].' '.$userPhotoArray[1];
			   $UserPhoto=$userPhotoArray[2];
			   $question_id=$cmtRow['question_id'];
			   $PhotoQuestion=printString($cmtRow['cherry_question']);
			   $photo_question=str_replace('.','.<br/>',$PhotoQuestion);
			   $CherryAnswer=printString($cmtRow['cherry_answer']);			   
			   $photo_day=$cmtRow['photo_day'];
			   $photoCnt.='<div class="comments_mine">
               <div class="comments_left"><img src="'.$UserPhoto.'" alt=""  width="40" height="40"/></div>';
			   $photoCnt.='<div class="img_box_container">
						   <div class="feedbox">';
			   if($cmtRow['user_id']==$_SESSION['USER_ID']){
				 $photoCnt.='<div class="actions"><a class="delete" href="javascript:void(0);" onclick="ajax_action(\'del_expert_question\',\'div_cherry_question_'.$photo_id.'\',\'cherryboard_id='.$cherryboard_id.'&photo_id='.$photo_id.'&question_id='.$question_id.'&user_id='.$_SESSION['USER_ID'].'&photo_day='.$photo_day.'\')"><img src="images/delete.png" title="Delete"></a></div>';
			   }
               $photoCnt.='</div>';
              $photoCnt.='<div class="comments_right"><strong>'.$UserName.'</strong>&nbsp;Q: '.$photo_question.'
			   </div>
			   <div style="clear:both"></div>
               </div></div>';
		   
			   if(trim($CherryAnswer)!=""){
				 $photoCnt.='<div class="comments_mine">
               <div class="comments_left"><img src="'.$expOwner_Pic.'" alt=""  width="40" height="40"/></div>';
			   $photoCnt.='<div class="img_box_container">
						   <div class="feedbox">';
			   if($expOwner_id==$_SESSION['USER_ID']){
				  $photoCnt.='<div class="actions"><a class="delete" href="javascript:void(0);" onclick="ajax_action(\'del_expert_answer\',\'div_cherry_question_'.$photo_id.'\',\'cherryboard_id='.$cherryboard_id.'&photo_id='.$photo_id.'&question_id='.$question_id.'&user_id='.$_SESSION['USER_ID'].'&photo_day='.$photo_day.'\')"><img src="images/delete.png" title="Delete"></a></div>';
			   }			   
			   $photoCnt.='</div>';			   
               $photoCnt.='<div class="comments_right"><strong>'.$expOwnerName.'</strong>&nbsp;A: '.$CherryAnswer.'</div>
               <div style="clear:both"></div>
               </div></div>';
			   }
			if($PhotoQuestion!=''&&($expOwner_id==$_SESSION['USER_ID']||getSuperAdmin($_SESSION['USER_ID'])==1)){
				$photoCnt.='<div class="add_comment_main">
				   <div class="add_comment_left"><img src="'.$expOwner_Pic.'" alt="" width="40" height="40" />
				   </div>
				   <div class="add_comment_right">
				   <label>
				   <input type="text" name="cherry_answer_'.$question_id.'" id="cherry_answer_'.$question_id.'" style=" width:120px; height:25px; color:#908081; padding:0px 5px 5px 5px; border:1px solid #e6e6e6;" value="Add a Answer" onfocus=" if (this.value == \'Add a Answer\') (this.value = \'\')" onblur=" if (this.value == \'\') (this.value = \'Add a Answer\')"/>
				   </label>
				   </div>
				   </div>';
				$photoCnt.='<div class="comment_button">
					 <a href="javascript:void(0);" onclick="ajax_action(\'cherry_answer\',\'div_cherry_question_'.$photo_id.'\',\'photo_id='.$photo_id.'&cherryboard_id='.$cherryboard_id.'&photo_day='.$photo_day.'&user_id='.$_SESSION['USER_ID'].'&question_id='.$question_id.'&answer=\'+document.getElementById(\'cherry_answer_'.$question_id.'\').value);" style="text-decoration:none;"><input type="button" value="Answer" class="button"/></a><div class="comment_im"><img src="images/box.png" alt=""  width="23" height="26"/></div>
					 <div style="clear:both"></div>
					 </div>';   
			   }
			}
		}
	}
	$photoCnt.='<div class="add_comment_main">
			   <div class="add_comment_left"><img src="'.$current_userPic.'" alt="" width="40" height="40" />
			   </div>
			   <div class="add_comment_right">
			   <label>
			   <input type="text" name="cherry_question_'.$photo_id.'" id="cherry_question_'.$photo_id.'" style=" width:120px; height:25px; color:#908081; padding:0px 5px 5px 5px; border:1px solid #e6e6e6;" value="Ask a question" onfocus=" if (this.value == \'Ask a question\') (this.value = \'\')" onblur=" if (this.value == \'\') (this.value = \'Ask a question\')"/>
			   </label>
			   </div>
			   </div>
			   <div class="comment_button">
				 <a href="javascript:void(0);" onclick="ajax_action(\'ask_expert_question\',\'div_cherry_question_'.$photo_id.'\',\'photo_id='.$photo_id.'&cherryboard_id='.$cherryboard_id.'&user_id='.$_SESSION['USER_ID'].'&photo_day='.$photo_day.'&question=\'+document.getElementById(\'cherry_question_'.$photo_id.'\').value);" style="text-decoration:none;"><input type="button" value="Ask" class="button"/></a>
				 <div class="comment_im"><img src="images/box.png" alt=""  width="23" height="26"/></div>
				 <div style="clear:both"></div>
			   </div>';
	$photoCnt.='</div>';	
	return $photoCnt;
}
//START EXPERT COMMENT SECTION FUNCTION
function expert_comment_section($cherryboard_id,$photo_id,$photo_day=0){

  $current_userPic=getFieldValue('fb_photo_url','tbl_app_users','user_id='.$_SESSION['USER_ID']);
  $TotalCmt=(int)getFieldValue('count(photo_id)','tbl_app_expert_cherry_comment','photo_id='.$photo_id);
  
  $photoCnt.='<div class="silverheader">
			  <div class="expert_comments">
				<div class="left_comments">'.$TotalCmt.' comments</div>
				<div class="right_comments">
				<a href="javascript:void(0);" 
				onclick="ajax_action(\'add_expert_cheers\',\'div_expert_cheers_'.$photo_id.'\',\'photo_id='.$photo_id.'&cherryboard_id='.$cherryboard_id.'&user_id='.(int)$_SESSION['USER_ID'].'\')">cheers?</a></div>
				<div class="img_comments"><img src="images/box1.png" alt="" /></div>
			   <div style="clear:both"></div> 
			   </div>
			   </div>';
   $photoCnt.='<div class="submenu">
			   <div class="bt_comments_img"></div>';
			   
   $selExpCmt=mysql_query("select * from tbl_app_expert_cherry_comment where photo_id=".$photo_id." order by comment_id desc limit 2");
   if(mysql_num_rows($selExpCmt)>0){
   		while($selExpCmtRow=mysql_fetch_array($selExpCmt)){
			$userPhotoArray=getFieldsValueArray('first_name,last_name,fb_photo_url','tbl_app_users','user_id='.$selExpCmtRow['user_id']);
			$UserName=$userPhotoArray[0].' '.$userPhotoArray[1];
		    $UserPhoto=$userPhotoArray[2];
		    $comment_id=$selExpCmtRow['comment_id'];
		    $cherry_comment=printString($selExpCmtRow['cherry_comment']);
			$photoCnt.='<div class="comments_mine">
               <div class="comments_left"><img src="'.$UserPhoto.'" alt=""  width="40" height="40"/></div>';
			$photoCnt.='<div class="img_box_container">
						<div class="feedbox">';
			if($selExpCmtRow['user_id']==$_SESSION['USER_ID']){
				 $photoCnt.='<div class="actions"><a class="delete" href="javascript:void(0);" onclick="add_cherry_comment(event,\'del_cherry_expert_comment\','.$cherryboard_id.','.$photo_id.','.$selExpCmtRow['user_id'].','.$comment_id.')"><img src="images/delete.png" title="Delete"></a></div>';
			 }			
			$photoCnt.='</div>';   
            $photoCnt.='<div class="comments_right"><strong>'.$UserName.'</strong> '.$cherry_comment.'</div>
            <div style="clear:both"></div>
               </div></div>';	
		}
   }
   	   
   $photoCnt.='<div class="add_comment_main">
			   <div class="add_comment_left"><img src="'.$current_userPic.'" alt="" width="40" height="40" />
			   </div>
			   <div class="add_comment_right">
			   <label>
			   <input type="text" name="cherry_comment_'.$photo_id.'" id="cherry_comment_'.$photo_id.'" style=" width:120px; height:25px; color:#908081; padding:0px 5px 5px 5px; border:1px solid #e6e6e6;" value="Add a comment..." onfocus=" if (this.value == \'Add a comment...\') (this.value = \'\')" onblur=" if (this.value == \'\') (this.value = \'Add a comment...\')"/>
			   </label>
			   </div>
			   </div>
			   <div class="comment_button">
				 <a href="javascript:void(0);" onclick="return add_cherry_comment(event,\'add_cherry_expert_comment\',\''.$cherryboard_id.'\',\''.$photo_id.'\',\''.(int)$_SESSION['USER_ID'].'\',document.getElementById(\'cherry_comment_'.$photo_id.'\').value)" style="text-decoration:none;"><input type="button" value="Comment" class="button"/></a>
				 <div class="comment_im"><img src="images/box.png" alt=""  width="23" height="26"/></div>
				 <div style="clear:both"></div>
				 </div>
			   </div>';
   return  $photoCnt;
}
//START EXPERT CHEER SECTION FUNCTION
function expert_cheers_section($cherryboard_id,$photo_id,$photo_day=0){
	if($photo_day==0){
			$photo_day=(int)getFieldValue('photo_day','tbl_app_expert_cherry_photo','photo_id='.$photo_id);
	}
	$user_id=getFieldValue('user_id','tbl_app_expert_cherry_photo','photo_id='.$photo_id);
	$expertboard_id=getFieldValue('expertboard_id','tbl_app_expert_cherryboard','cherryboard_id='.$cherryboard_id);
	$expUserId=getFieldValue('user_id','tbl_app_expert_cherryboard','cherryboard_id='.$cherryboard_id);
	$expOwner_id=getFieldValue('user_id','tbl_app_expertboard','expertboard_id='.$expertboard_id);
	$photoCnt='';
	$photoCnt.='<div class="bottom1" style="width: 188px; padding: 3px;">';
	$TotalCmt=getFieldValue('count(photo_id)','tbl_app_expert_cherry_comment','photo_id='.$photo_id);
	$TotalQuestion=getFieldValue('count(question_id)','tbl_app_expert_question_answer','photo_id='.$photo_id);
	$TotalCheers=getFieldValue('count(cheers_id)','tbl_app_expert_cherryboard_cheers','photo_id='.$photo_id);
	$checkCheers=(int)getFieldValue('user_id','tbl_app_expert_cherryboard_cheers','photo_id='.$photo_id.' and user_id='.$_SESSION['USER_ID']);
	if($checkCheers==0){
		$cheersLink='<div class="likes"><a href="javascript:void(0);" onclick="add_cherry_cheers(\'add_expert_cheers\',\''.$photo_id.'\',\''.$cherryboard_id.'\',\''.$_SESSION['USER_ID'].'\')" class="likes">+ cheers!</a></div>';
	}else{$cheersLink='';}
		$photoCnt.=$cheersLink.'<div class="likes" id="div_ask_que_'.$photo_id.'"><a href="javascript:void(0);" style="padding-left:20px;" onclick="ajax_action(\'ask_question\',\'div_ask_que_'.$photo_id.'\',\'photo_id='.$photo_id.'&cherryboard_id='.$cherryboard_id.'&user_id='.$_SESSION['USER_ID'].'&photo_day='.$photo_day.'\')" class="likes">ask question</a></div>';
		$photoCnt.=''.($expUserId==$_SESSION['USER_ID']?'<div class="likes" id="div_expert_notes_'.$photo_id.'"><a href="javascript:void(0);" style="padding-left:20px;" onclick="ajax_action(\'expert_notes\',\'div_expert_notes_'.$photo_id.'\',\'photo_id='.$photo_id.'&cherryboard_id='.$cherryboard_id.'&user_id='.$_SESSION['USER_ID'].'&photo_day='.$photo_day.'\')" class="likes">notes</a></div>':'').'
		<div class="coment" id="div_photo_cheers_'.$photo_id.'">'.(int)$TotalCheers.'&nbsp;Cheers&nbsp;&nbsp;'.(int)$TotalCmt.'&nbsp;Comments&nbsp;'.(int)$TotalQuestion.'&nbsp;Questions&nbsp';
	
	if($user_id==$_SESSION['USER_ID']){
		//$photoCnt.='<img height="20" width="20" id="rotate_img'.$photo_id.'" onclick="rotate_photo_expert(\'90\','.$photo_id.','.$cherryboard_id.')" style="cursor:pointer" src="images/round_arrow_90.jpg" title="Rotate Image">';
	}
	$photoCnt.='</div><br>';
	$photoCnt.='</div>';
	return $photoCnt;
}
function getExpCreator($type,$cherryboard_id,$user_id){
	$OwnerRights=0;
	if($type=="Expert"){
		$tableName="tbl_app_expert_cherryboard";
	}else{
		$tableName="tbl_app_cherryboard";
	}	
	$expCreator_id=getFieldValue('user_id',$tableName,'cherryboard_id='.$cherryboard_id);
	if($expCreator_id==$user_id){
		$OwnerRights=1;
	}
	
	return $OwnerRights;
}
function displayFiltersImgs($type="goal"){
	$ImgsHtml="<table>
			  <tr>
			   <td><img src=\"images/filter/effect1.jpg\" style=\"cursor:pointer\" onclick=\"photo_filter('".$type."','".$_SESSION['fname']."','effect1')\" height=\"50\" width=\"50\"/></td>
			   <td><img src=\"images/filter/effect2.jpg\" style=\"cursor:pointer\" onclick=\"photo_filter('".$type."','".$_SESSION['fname']."','effect2')\" height=\"50\" width=\"50\"/></td>
			  </tr>
			   <tr>
			   <td><img src=\"images/filter/effect3.jpg\" style=\"cursor:pointer\" onclick=\"photo_filter('".$type."','".$_SESSION['fname']."','effect3')\" height=\"50\" width=\"50\"/></td>
			   <td><img src=\"images/filter/effect4.jpg\" style=\"cursor:pointer\" onclick=\"photo_filter('".$type."','".$_SESSION['fname']."','effect4')\" height=\"50\" width=\"50\"/></td>
			  </tr>
			   <tr>
			   <td colspan=\"2\" align=\"center\">
			   	    <img src=\"images/filter/effect0.jpg\" style=\"cursor:pointer\" onclick=\"photo_filter('".$type."','".$_SESSION['fname']."','effect0')\" height=\"50\" width=\"50\"/><br/><span style=\"font-size:12px;color:#CC3300;font-weight:bold\">Orignal</span></td>
			  </tr>
			  </table>";
		return $ImgsHtml;	  
}
function getPhotoName($photo_name){
	$photo_name= rand().'_'.trim($photo_name);
	$photo_name=str_replace(' ','_',$photo_name);
	$photo_name=str_replace('-','_',$photo_name);
	$photo_name=str_replace('(','_',$photo_name);
	$photo_name=str_replace(')','_',$photo_name);
	return $photo_name;
}
function getDayType($expertboard_id){
	$day_type=getFieldValue('day_type','tbl_app_expertboard','expertboard_id='.$expertboard_id);
	if($day_type=="I"){
		$DayType='Item';
	}else if($day_type=="S"){
		$DayType='Step';
	}else{
		$DayType='Day';
	}
	return $DayType;
}
//EXPERT BOARD JOIN CODE FUNCTION THAT USED IN DO-IT 
function createExpertboard($expertboard_id,$cherryboard_id,$user_id=0){
	if($user_id==0){
		$user_id=(int)$_SESSION['USER_ID'];
	}
  if($user_id>0){
	$IsExpertBoard=(int)getFieldValue('cherryboard_id','tbl_app_expert_cherryboard','expertboard_id="'.$expertboard_id.'" AND main_board="0" AND user_id='.$user_id);
	if($IsExpertBoard==0){
		//$ExpGoalMainId=getExpGoalMainId($expertboard_id);
		$insExpBoard=mysql_query("INSERT INTO tbl_app_expert_cherryboard (cherryboard_id,user_id,expertboard_id, category_id,cherryboard_title,record_date,makeover,qualified,help_people,start_date,price,fb_album_id,doit_id)
		VALUES (NULL, '".$user_id."','".$expertboard_id."','0','', CURRENT_TIMESTAMP,'','','','','0','','".$cherryboard_id."')");
		$lastCreatedId=mysql_insert_id();
		//ADD CHECKIST ITEMS TO BUY EXPERTBOARD		
		$expertOwnerId=(int)GetFieldValue('user_id','tbl_app_expertboard','expertboard_id='.$expertboard_id);
		$selChkList=mysql_query("SELECT * FROM tbl_app_expert_checklist WHERE cherryboard_id=".$cherryboard_id);
		while($selChkListRow=mysql_fetch_array($selChkList)){
			$insTodoList=mysql_query("INSERT INTO tbl_app_expert_checklist (checklist_id,user_id, cherryboard_id,checklist,record_date,is_checked,is_system) VALUES (NULL,'".$expertOwnerId."','".$lastCreatedId."','".addslashes($selChkListRow['checklist'])."',CURRENT_TIMESTAMP,'".$selChkListRow['is_checked']."','".$selChkListRow['is_system']."')");			
		} 
		//ADD EXPERT REWARD PICTURE
		$selExpReward=mysql_query("SELECT * FROM tbl_app_expert_reward_photo WHERE cherryboard_id=".$cherryboard_id);
		while($selExpRewardRow=mysql_fetch_array($selExpReward)){
			$reward_title=$selExpRewardRow['photo_title'];
			$reward_photo=$selExpRewardRow['photo_name'];
			$oldDirPath='images/expertboard/reward/'.$reward_photo;
			$rnd=rand();
			$new_reward_photo=$rnd.'_'.$reward_photo;
			$newDirPath='images/expertboard/reward/'.$new_reward_photo;
			if($_SERVER['SERVER_NAME']=="localhost"){
				$retval=copy($oldDirPath,$newDirPath);				
			}else{
				$thumb_command=$ImageMagic_Path."convert ".$oldDirPath." -thumbnail 195 x 195 ".$newDirPath;
				$last_line=system($thumb_command,$retval);
			}
			if($retval){
				$insExpReward=mysql_query("INSERT INTO tbl_app_expert_reward_photo (exp_reward_id,user_id,cherryboard_id,photo_title,photo_name,record_date) VALUES (NULL,'".$expertOwnerId."','".$lastCreatedId."','".$reward_title."','".$new_reward_photo."',CURRENT_TIMESTAMP)");
			}
		}
		if($lastCreatedId>0){
			//send mail to owner of the expertboard
			$UserDetail=getUserDetail($user_id,'uid');
			$UserName=$UserDetail['name'];
			$ExpboardTitle=getFieldValue('expertboard_title','tbl_app_expertboard','expertboard_id='.$expertboard_id);
			$OwnerDetail=getUserDetail($expertOwnerId,'uid');
			$OwnerName=$OwnerDetail['name'];
			$expOwner_EmailId=$OwnerDetail['email_id'];
			$to = $expOwner_EmailId;
			$subject = $UserName.' joined your challenge';
			$message = '<table>
						<tr><td>&nbsp;</td></tr>
						<tr><td>Dear '.$OwnerName.',</td></tr>
						<tr><td>&nbsp;</td></tr>
						<tr><td>'.$UserName.' joined your board. 
						<a href="'.SITE_URL.'/expert_cherryboard.php?cbid='.$lastCreatedId.'"><strong>Click here</strong></a> to see his challenge board <strong>'.$ExpboardTitle.'</strong>.</td></tr>
						<tr><td>&nbsp;</td></tr>
						<tr><td>Thanks,</td></tr>
						<tr><td>'.REGARDS.'</td></tr>
						</table>';
			SendMail($to,$subject,$message);
			//debug mail
		 /*$message1='user id ==>'.$user_id.'==='.$_SERVER['REMOTE_ADDR']."===".date('Y-m-d H:i:s');
			SendMail('uniquewebinfo@gmail.com',$subject,$message1);*/
			return 	$lastCreatedId;	
		}
	}
  }	
}
//ADD EXPERT NOTES SECTION FUNCTION
function expert_notes_section($cherryboard_id,$photo_id,$photo_day=0){
  $current_userPic=getFieldValue('fb_photo_url','tbl_app_users','user_id='.$_SESSION['USER_ID']);
  $TotalNotes=(int)getFieldValue('count(photo_id)','tbl_app_expert_notes','photo_id='.$photo_id);	
  $photoCnt.='<div class="silverheader">
				   <div class="expert_notes">'.$TotalNotes.' notes </div>';
  $photoCnt.='<div class="submenu">
			  <div class="bt_comments_img"></div>';
  		
  $selNotes=mysql_query("SELECT * FROM tbl_app_expert_notes WHERE photo_id=".$photo_id." ORDER BY notes_id DESC LIMIT 2");
  	
  if(mysql_num_rows($selNotes)>0){
	  while($selNotesRow=mysql_fetch_array($selNotes)){
		   $userPhotoArray=getFieldsValueArray('first_name,last_name,fb_photo_url','tbl_app_users','user_id='.$selNotesRow['user_id']);
		   $UserName=$userPhotoArray[0].' '.$userPhotoArray[1];
		   $UserPhoto=$userPhotoArray[2];
		   $notes_id=$selNotesRow['notes_id'];
		   $cherry_notes=printString($selNotesRow['cherry_notes']);
		   
		   $photoCnt.='<div class="comments_mine">
               <div class="comments_left"><img src="'.$UserPhoto.'" alt=""  width="40" height="40"/></div>';
		   $photoCnt.='<div class="img_box_container">
					   <div class="feedbox">';
		   if($selNotesRow['user_id']==$_SESSION['USER_ID']){
			  $photoCnt.='<div class="actions"><a class="delete" href="javascript:void(0);" onclick="ajax_action(\'del_expert_note\',\'div_expert_notes_'.$photo_id.'\',\'cherryboard_id='.$cherryboard_id.'&photo_id='.$photo_id.'&notes_id='.$notes_id.'&user_id='.$selNotesRow['user_id'].'&photo_day='.$photo_day.'\');"><img src="images/delete.png" title="Delete"></a></div>';
		   }				
		   $photoCnt.='</div>';			   
           $photoCnt.='<div class="comments_right"><strong>'.$UserName.'</strong> '.$cherry_notes.'</div>
               <div style="clear:both"></div>
               </div></div>';
	   }
  }
  $photoCnt.='<div class="add_comment_main">
		   <div class="add_comment_left"><img src="'.$current_userPic.'" alt="" width="40" height="40" />
		   </div>
		   <div class="add_comment_right">
		   <label>
		   <input type="text" name="cherry_notes_'.$photo_id.'" id="cherry_notes_'.$photo_id.'" style=" width:120px; height:25px; color:#908081; padding:0px 5px 5px 5px; border:1px solid #e6e6e6;" value="Add a notes..." onfocus=" if (this.value == \'Add a notes...\') (this.value = \'\')" onblur=" if (this.value == \'\') (this.value = \'Add a notes...\')"/>
		   </label>
		   </div>
		   </div>
		   <div class="comment_button">
			 <a href="javascript:void(0);" onclick="ajax_action(\'add_expert_notes\',\'div_expert_notes_'.$photo_id.'\',\'photo_id='.$photo_id.'&cherryboard_id='.$cherryboard_id.'&user_id='.(int)$_SESSION['USER_ID'].'&photo_day='.$photo_day.'&cherry_notes=\'+document.getElementById(\'cherry_notes_'.$photo_id.'\').value);" style="text-decoration:none;padding:0px;"><input type="button" value="Add Notes" class="button"/></a>
			 <div class="comment_im"><img src="images/box.png" alt=""  width="23" height="26"/></div>
			 <div style="clear:both"></div>
			 </div>';
			   
  $photoCnt.='</div>
  			  </div>';
  return  $photoCnt;
}
//DEFINE FUNCTION DELETE EXPERTBOARD
function deleteExpertBoard($cherryboard_id){
   $delCherryBrd=mysql_query("DELETE FROM tbl_app_expert_cherryboard WHERE cherryboard_id=".$cherryboard_id);
	if($delCherryBrd){
		//UNLINK PHOTO AND DELETE PHOTO
		$selExpPhoto=mysql_query("SELECT photo_id,photo_name FROM tbl_app_expert_cherry_photo WHERE cherryboard_id=".$cherryboard_id);
		while($selExpPhotoRow=mysql_fetch_array($selExpPhoto)){
			$photo_id=$selExpPhotoRow['photo_id'];
			$photo_name=$selExpPhotoRow['photo_name'];			
			$photo_path='images/expertboard/'.$photo_name;
			$photo_path_thumb='images/expertboard/thumb/'.$photo_name;
			$profileSlidePath='images/expertboard/profile_slide/'.$photo_name;
    		$sliderPath='images/expertboard/slider/'.$photo_name;
			$delExpPhoto=mysql_query("DELETE FROM tbl_app_expert_cherry_photo WHERE photo_id=".$photo_id);
			if($delExpPhoto){
				unlink($photo_path);
				unlink($photo_path_thumb);
				unlink($profileSlidePath);
				unlink($sliderPath);
			}
		}
		//DELETE TO-DO LIST
	   $checklist=mysql_query('DELETE FROM tbl_app_expert_checklist WHERE cherryboard_id="'.$cherryboard_id.'"');
		$cherryboard_cheers=mysql_query('DELETE FROM tbl_app_expert_cherryboard_cheers WHERE cherryboard_id="'.$cherryboard_id.'"');
		$cherryboard_meb=mysql_query('DELETE FROM tbl_app_expert_cherryboard_meb WHERE cherryboard_id="'.$cherryboard_id.'"');
		$cherry_comment=mysql_query('DELETE FROM tbl_app_expert_cherry_comment WHERE cherryboard_id="'.$cherryboard_id.'"');
		$cherry_link=mysql_query('DELETE FROM tbl_app_expert_link WHERE cherryboard_id="'.$cherryboard_id.'"');
		$temp_cherryboard_meb=mysql_query('DELETE FROM tbl_app_temp_expert_cherryboard_meb WHERE cherryboard_id="'.$cherryboard_id.'"');
	    $cherry_gift=mysql_query('DELETE FROM tbl_app_expert_cherry_gift WHERE cherryboard_id="'.$cherryboard_id.'"');
	    $cherry_notes=mysql_query('DELETE FROM tbl_app_expert_notes WHERE cherryboard_id="'.$cherryboard_id.'"');
	    $cherry_question=mysql_query('DELETE FROM tbl_app_expert_question_answer WHERE cherryboard_id="'.$cherryboard_id.'"');
	    $cherry_buy=mysql_query('DELETE FROM tbl_app_expert_buy WHERE cherryboard_id="'.$cherryboard_id.'"');
	    //DELETE REWARD PHOTO
	    $selExpReward=mysql_query("SELECT exp_reward_id,photo_name FROM tbl_app_expert_reward_photo WHERE cherryboard_id=".$cherryboard_id."");
		while($selExpRewardRow=mysql_fetch_array($selExpReward)){
			$photo_name=$selExpRewardRow['photo_name'];
			$photo_path='images/expertboard/reward/'.$photo_name;
			$cherry_reward=mysql_query('DELETE FROM tbl_app_expert_reward_photo WHERE exp_reward_id="'.$selExpRewardRow['exp_reward_id'].'"');
			if($cherry_reward){
				unlink($photo_path);				
			}
		}
	}
}
function parseString($str){
	$str=str_replace("'","&#39;",$str);
	$str=str_replace('"','&#34;',$str);
	$str=addslashes($str);
	return $str;
}
function printString($str){
	$str=str_replace("'","&#39;",$str);
	$str=str_replace('"','&#34;',$str);
	$str=stripslashes($str);
	return $str;
}
//START GET TO-DO LIST FUNCTION
function getToDoListItem($cherryboard_id,$sort=''){
	$selToDoList=mysql_query("select *,date_format(record_date,'%m-%d-%Y') as record_date from tbl_app_expert_checklist where cherryboard_id=".$cherryboard_id." order by checklist_id ".$sort);
	$toDoListCnt='';
	$swap_id=0;
	while($selToDoListRow=mysql_fetch_array($selToDoList)){
		$checklist_id=$selToDoListRow['checklist_id'];
		$swap_id=$checklist_id;
		$checklist=printString($selToDoListRow['checklist']);
		$toDoList=str_replace('w.','w.<br/>',$checklist);
		$toDoListItem=wordwrap($toDoList,30,"<br/>",TRUE);
		$record_date=$selToDoListRow['record_date'];
		$is_checked=(int)$selToDoListRow['is_checked'];
		$is_system=(int)$selToDoListRow['is_system'];
		$chk_user_id=$selToDoListRow['user_id'];	
		
		$toDoListCnt.='<div class="todolist_main" id="div_'.$swap_id.'" '.($chk_user_id==$_SESSION['USER_ID']?'ondrop="dropTodoList(event,\''.$swap_id.'\')" ondragover="allowDrop(event,\''.$swap_id.'\')':'').'">';
		$toDoListCnt.='<div class="todolist_1">
<input type="checkbox" id="chkfield_'.$checklist_id.'" name="chkfield_'.$checklist_id.'" '.($is_checked==1?'checked="checked"':'').' value="1" onclick="checked_checklist(\'checked_expert_checklist\',\'div_checklist\',\'cherryboard_id='.$cherryboard_id.'&checklist_id='.$checklist_id.'&cuid='.(int)$_SESSION['USER_ID'].'\',\'chkfield_'.$checklist_id.'\')"></div>';
		$toDoListCnt.='<div id="div_todo_list_'.$checklist_id.'" class="todolist_2">
		<a href="javascript:void(0);" '.($chk_user_id==$_SESSION['USER_ID']?' ondblclick="ajax_action(\'edit_todo_list\',\'div_todo_list_'.$checklist_id.'\',\'stype=add&checklist_id='.$checklist_id.'&user_id='.(int)$_SESSION['USER_ID'].'\')"':'').' title="Edit To-Do List" class="cleanLink" id="drag_'.$swap_id.'" draggable="true" ondragstart="drag(event,\''.$swap_id.'\')">'.$toDoListItem.'</a></div>
		<span class="style6">added '.$record_date.'&nbsp;</span>';
		if($chk_user_id==$_SESSION['USER_ID']&&$is_system==0){
		  $toDoListCnt.='&nbsp;&nbsp;<img src="images/close_small1.png" onclick="ajax_action(\'remove_expert_checklist\',\'div_todo_list\',\'cherryboard_id='.$cherryboard_id.'&checklist_id='.$checklist_id.'&cuid='.(int)$_SESSION['USER_ID'].'\')" style="cursor:pointer" title="Delete"/>';
		}
		$toDoListCnt.='</div>';//<span class="style5">day 1:</span>
	}
	return $toDoListCnt;
}
//START GET EXPERT REWARD FUNCTION
function getExpertReward($cherryboard_id){
   global $pageRewardPhotosArray;
   $pageRewardPhotosArray=array();
   $selQuery=mysql_query("SELECT * FROM tbl_app_expert_reward_photo WHERE cherryboard_id=".$cherryboard_id);
   if(mysql_num_rows($selQuery)>0){
		while($selQueryRow=mysql_fetch_array($selQuery)){
			$expRewardId=$selQueryRow['exp_reward_id'];
			$expUserId=$selQueryRow['user_id'];
			$reward_title=ucwords($selQueryRow['photo_title']);
			$reward_photo='images/expertboard/reward/'.$selQueryRow['photo_name'];
			if($expUserId==$_SESSION['USER_ID']){  
			   $expRewardCnt.='<table><tr><td><div style="padding-left:225px;height:3px;">
			  <a onclick="ajax_action(\'del_exp_reward\',\'div_del_exp_reward\',\'expRewardId='.$expRewardId.
			   '&cherryboard_id='.$cherryboard_id.'&user_id='.USER_ID.'\')" 
				href="javascript:void(0);"><img src="images/delete.png" title="Delete Reward"></a>
				</div></td></tr></table>';
			}
			if(is_file($reward_photo)){
				$expRewardCnt.='<div class="img_box_container" id="div_change_exp_reward_photo'.$expRewardId.'" style="padding:8px;">
				<div class="feedbox">';
				if($expUserId==$_SESSION['USER_ID']){
					$expRewardCnt.='<div class="message">
					<a href="javascript:void(0);" onclick="javascript:document.getElementById(\'exp_reward_id\').value='.$expRewardId.';javascript:document.getElementById(\'subtype\').value=\'change_reward_pic\';document.getElementById(\'photo_upload\').style.display=\'inline\';" class="change">Change Photo</a>
					</div>';
				}
				$expRewardCnt.='</div>
				<img src="'.$reward_photo.'" title="'.$reward_title.'" alt="" height="200px" width="230px" data-tooltip="stickyReward'.$expRewardId.'" />
				</div>';
				$pageRewardPhotosArray[$expRewardId]=$reward_photo;
			}
		}
   }else{
	  $expRewardCnt.='<br/>No Reward';
   }
   return $expRewardCnt;	
}
function countCheers($record_id,$type){
	$countCheers=0;
	if($type=="expertboard"){
		$sel=mysql_query("select cherryboard_id from tbl_app_expert_cherryboard where expertboard_id=".$record_id);
		while($selRow=mysql_fetch_array($sel)){
			$countCheers+=(int)getFieldValue('count(photo_id)','tbl_app_expert_cherryboard_cheers','cherryboard_id='.$selRow['cherryboard_id']);		
		}
	}else if($type=="cherryboard"){
		$countCheers=(int)getFieldValue('count(photo_id)','tbl_app_expert_cherryboard_cheers','cherryboard_id='.$record_id);
	}else{
		$countCheers=(int)getFieldValue('count(photo_id)','tbl_app_expert_cherryboard_cheers','photo_id='.$record_id);
	}	
	return $countCheers;
}
function getImgSizeRatio($image,$maxWidth,$maxHeight){
   $imgInfo=getimagesize($image);
	$width  = $imgInfo[0];
	$height = $imgInfo[1];
	
	/*if($origHeight > $origWidth){ 
		 $newWidth = ($maxHeight * $origWidth) / $origHeight;

		 $retval = array("width" => $newWidth, "height" => $maxHeight);
	}else{
		 $newHeight= ($maxWidth*$origHeight)/$origWidth;
		 $retval = array("width" => $origWidth, "height" => $newHeight);
	}*/
	  $tempimageWidth  = $width;
	  $tempimageHeight = $height;
	  $imageWidth  = $width;
	  $imageHeight = $height;
	  if($imageWidth>$maxWidth||$imageHeight>$maxHeight){
		  if(!($tempimageHeight <= $maxHeight && $tempimageWidth <=$maxWidth)) 
		  {
			   if($tempimageHeight >= $tempimageWidth) {
				$imageHeight = $maxHeight;
				$imageWidth  = ($maxHeight / $tempimageHeight)*$tempimageWidth;
			   }
			   else {
				$imageWidth = $maxWidth;
				$imageHeight = ($maxHeight / $tempimageWidth)*$tempimageHeight;
			   }
		  }
	  }  
	  $new_width=(int)$imageWidth;
	  $new_height=(int)$imageHeight;
	  $retval=array("width"=>$new_width,"height"=>$new_height);
	return $retval;
}
function getImageRatio($image,$maxWidth,$maxHeight){
  $imgInfo=getimagesize($image);
  $width  = $imgInfo[0];
  $height = $imgInfo[1];
  
  $imageWidth=$width;
  $imageHeight=$height;
  $WidthRatio=$maxWidth/$width;
  $HeightRatio=$maxHeight/$height;
  
  if($width<=$maxWidth||$height<=$maxHeight){
     if($HeightRatio<$WidthRatio){
		$imageWidth=$width*$HeightRatio;
		$imageHeight=$height*$HeightRatio;
     }
     else{		
		$imageWidth=$width*$WidthRatio;
		$imageHeight=$height*$WidthRatio;
     }
  }  
  $new_width=(int)$imageWidth;
  $new_height=(int)$imageHeight;
  $retval=array("width"=>$new_width,"height"=>$new_height);
  return $retval;
}
function getWSdata($url){
	$returnDetail='No Data';
	if(trim($url)!=""){
		$homepage = file_get_contents($url);
		$returnDetail=json_decode($homepage, true);
	}else{
		$returnDetail='Empty Url';
	}
	return $returnDetail;
}
/*function getCopyBoardUser($cherryboard_id){
	$selCopyUser=mysql_query("SELECT cherryboard_id,user_id FROM tbl_app_expert_cherryboard WHERE copyboard_id=".$cherryboard_id);
	$copyBoardDetail=array();
	$subCopyBoardDetail=array();
	$cnt=1;
	while($selCopyUserRow=mysql_fetch_array($selCopyUser)){
		$copyBoardDetail['cherryboard_id']=(int)$selCopyUserRow['cherryboard_id'];
		$copyBoardDetail['user_id']=(int)$selCopyUserRow['user_id'];
		$UserDetail=getUserDetail($selCopyUserRow['user_id']);
		$CopyUsrName=$UserDetail['name'];
		$copyBoardDetail['username']=$CopyUsrName;
		$subCopyBoardDetail[$cnt]=$copyBoardDetail;
		$cnt++;
	}
	return $subCopyBoardDetail;
}*/
function CopyExpertBoard($expertBoardId,$cherryboard_id,$user_id=0){
	if($user_id==0){
		$user_id=(int)$_SESSION['USER_ID'];
	}
  	if($user_id>0){
		//CHECK USER HAVE COPY BOARD OR NOT	
		$expTitle=trim(getFieldValue('expertboard_title','tbl_app_expertboard','expertboard_id='.$expertBoardId));
		$recordDate=getFieldValue('date_format(record_date,"%Y-%m-%d") as recordDate','tbl_app_expertboard','expertboard_title="'.$expTitle.'" AND user_id="'.$user_id.'" ORDER BY expertboard_id DESC LIMIT 1');
		$curDate=date('Y-m-d');
		if($recordDate!=$curDate){
			//START CREATE EXPORTBOARD
			$selExpBoard=mysql_query("SELECT * FROM tbl_app_expertboard WHERE expertboard_id=".$expertBoardId);
			while($selExpBoardRow=mysql_fetch_array($selExpBoard)){
				$category_id=$selExpBoardRow['category_id'];
				$expertboard_title=trim($selExpBoardRow['expertboard_title']);
				$expertboard_detail=trim(stripslashes($selExpBoardRow['expertboard_detail']));
				$goal_days=$selExpBoardRow['goal_days'];
				$price=$selExpBoardRow['price'];
				$customers=trim($selExpBoardRow['customers']);
				$day_type=$selExpBoardRow['day_type'];
				$is_board_price=$selExpBoardRow['is_board_price'];
				$board_type=$selExpBoardRow['board_type'];
				$living_narrative=$selExpBoardRow['living_narrative'];
				
				//CREATE NEW EXPERTBOARD
				$ip_address=$_SERVER['REMOTE_ADDR'];
				$insExpBoard="INSERT INTO tbl_app_expertboard (expertboard_id,user_id,category_id,expertboard_title, expertboard_detail,goal_days,price,record_date,day_type,is_board_price,board_type,customers,ip_address,living_narrative,copyboard_id) VALUES (NULL,'".$user_id."','".$category_id."','".$expertboard_title."','".addslashes($expertboard_detail)."','".$goal_days."','".$price."',CURRENT_TIMESTAMP,'".$day_type."','".$is_board_price."','".$board_type."','".$customers."','".$ip_address."','".$living_narrative."','".$expertBoardId."')";
				$insExpBoardRes=mysql_query($insExpBoard);
				$NewExpBoardId=mysql_insert_id();
				//CREATE NEW GOAL DAYS
				if($NewExpBoardId>0){
					$selDays=mysql_query("SELECT * FROM tbl_app_expertboard_days WHERE expertboard_id=".$expertBoardId);
					while($selDaysRow=mysql_fetch_array($selDays)){
						$day_no=$selDaysRow['day_no'];
						$day_title=$selDaysRow['day_title'];
						$insDays="INSERT INTO tbl_app_expertboard_days (expertboard_day_id,expertboard_id,day_no, day_title,record_date) VALUES (NULL,'".$NewExpBoardId."','".$day_no."','".$day_title."',CURRENT_TIMESTAMP)";
						$insDaysRes=mysql_query($insDays);
					}
					//CREATE NEW EXPERT CHERRYBOARD
					//$cherryBoardId=getExpGoalMainId($expertBoardId);
					$insNewExpBoard=mysql_query("INSERT INTO tbl_app_expert_cherryboard 
					(cherryboard_id,user_id,expertboard_id,record_date,main_board,copyboard_id)
				 VALUES (NULL,'".$user_id."','".$NewExpBoardId."',CURRENT_TIMESTAMP,'1','".$cherryboard_id."')");
					$newCherryBoardId=mysql_insert_id();
					//ADD TO-DO LIST ITEM IN NEW EXPERT CHERRYBOARD				
					$selTodoList=mysql_query("SELECT * FROM tbl_app_expert_checklist WHERE cherryboard_id=".$cherryboard_id);
					while($selTodoListRow=mysql_fetch_array($selTodoList)){
						$insTodoList=mysql_query("INSERT INTO tbl_app_expert_checklist (checklist_id,user_id, cherryboard_id,checklist,record_date,is_checked,is_system) VALUES (NULL,'".$user_id."','".$newCherryBoardId."','".addslashes($selTodoListRow['checklist'])."',CURRENT_TIMESTAMP,'".$selTodoListRow['is_checked']."','".$selTodoListRow['is_system']."')");
					}
					//ADD EXPERT REWARD PICTURE
					$selExpReward=mysql_query("SELECT * FROM tbl_app_expert_reward_photo WHERE cherryboard_id=".$cherryboard_id);
					while($selExpRewardRow=mysql_fetch_array($selExpReward)){
						$reward_title=$selExpRewardRow['photo_title'];
						$reward_photo=$selExpRewardRow['photo_name'];
						$oldDirPath='images/expertboard/reward/'.$reward_photo;
						$rnd=rand();
						$new_reward_photo=$rnd.'_'.$reward_photo;
						$newDirPath='images/expertboard/reward/'.$new_reward_photo;
						if($_SERVER['SERVER_NAME']=="localhost"){
							$retval=copy($oldDirPath,$newDirPath);				
						}else{
							$thumb_command=$ImageMagic_Path."convert ".$oldDirPath." -thumbnail 195 x 195 ".$newDirPath;
							$last_line=system($thumb_command,$retval);
						}
						if($retval){
							$insExpReward=mysql_query("INSERT INTO tbl_app_expert_reward_photo (exp_reward_id,user_id,cherryboard_id,photo_title,photo_name,record_date) VALUES (NULL,'".$user_id."','".$newCherryBoardId."','".$reward_title."','".$new_reward_photo."',CURRENT_TIMESTAMP)");
						}
					}
					//ADD EXPERT BOARD PICTURE
					$selExpPic=mysql_query("SELECT * FROM tbl_app_expert_cherry_photo WHERE cherryboard_id=".$cherryboard_id);
					while($selExpPicRow=mysql_fetch_array($selExpPic)){
						$photo_title=$selExpPicRow['photo_title'];
						$photo_name=$selExpPicRow['photo_name'];
						$photo_day=$selExpPicRow['photo_day'];
						
						$old_uploaddir='images/expertboard/'.$photo_name;
						$old_uploaddirThumb='images/expertboard/thumb/'.$photo_name;
						$rnd=rand();
						$new_photo_name=$rnd.'_'.$photo_name;//photo_path set in db
						$new_uploaddir='images/expertboard/'.$new_photo_name;
						$new_uploaddirThumb='images/expertboard/thumb/'.$new_photo_name;
						
						if($_SERVER['SERVER_NAME']=="localhost"){
							$retval=copy($old_uploaddir,$new_uploaddir);
							$retval=copy($old_uploaddirThumb,$new_uploaddirThumb);				
						}else{
							$thumb_command=$ImageMagic_Path."convert ".$old_uploaddir." -thumbnail 195 x 195 ".$new_uploaddir;
							$last_line=system($thumb_command, $retval);
							$thumb_command_thumb=$ImageMagic_Path."convert ".$old_uploaddirThumb." -thumbnail 60 x 60 ".$new_uploaddirThumb;
							$last_line=system($thumb_command_thumb,$retval);
						}
						if($retval){
							$insExpPic=mysql_query("INSERT INTO tbl_app_expert_cherry_photo (photo_id,user_id, cherryboard_id,photo_title,photo_name,photo_day,record_date) VALUES (NULL,'".$user_id."','".$newCherryBoardId."','".$photo_title."','".$new_photo_name."','".$photo_day."',CURRENT_TIMESTAMP)");
						}
					}
					//SEND MAIL OF THE EXPERTBOARD OWNER
					if($newCherryBoardId>0){
						$UserDetail=getUserDetail($user_id,'uid');
						$UserName=$UserDetail['name'];
						$expertDetail=getFieldsValueArray('user_id,expertboard_title','tbl_app_expertboard','expertboard_id='.$expertBoardId);					
						$expertUserId=$expertDetail[0];
						$expertTitle=$expertDetail[1];
						$OwnerDetail=getUserDetail($expertUserId,'uid');
						$OwnerName=$OwnerDetail['name'];
						$expOwner_EmailId=$OwnerDetail['email_id'];
						$to = $expOwner_EmailId;
						$subject = 'Your '.$expertTitle.' is copied.';
						$message = '<table>
									<tr><td>&nbsp;</td></tr>
									<tr><td>Dear '.$OwnerName.',</td></tr>
									<tr><td>&nbsp;</td></tr>
									<tr><td>Your '.$expertTitle.' is copied by '.$UserName.'. 
									<a href="'.SITE_URL.'/expert_cherryboard.php?cbid='.$newCherryBoardId.'"><strong>Click here</strong></a> to see how '.$UserName.' is using it.</td></tr>
									<tr><td>&nbsp;</td></tr>
									<tr><td>Love,</td></tr>
									<tr><td>'.REGARDS.'</td></tr>
									</table>';
						SendMail($to,$subject,$message);
						return $newCherryBoardId;					  
					}
				}
			}
		}	
	}	
}
?>