<?php
error_reporting(0);
header('Content-Type: application/json');
include('include/app-db-connect.php');
include('include/app_functions.php');
$tblName=$_REQUEST['tbl'];
$tblFields=$_REQUEST['tblFields'];
$fb_id=trim($_REQUEST['fb_id']);
$user_id=0;
if($fb_id!=""){
	$user_id=getUserId_by_FBid($fb_id);
}
$cherry_id=(int)($_REQUEST['cherry_id']);
$photo_id=(int)($_REQUEST['photo_id']);
$tblFields_array=explode(',',$_REQUEST['tblFields']);
$tblData=array();
$type=$_REQUEST['type'];

//Vijay FB ID : 100002349398425



if($type=="user_profile"||$type=="all_stories"||$type=="story_detail"){
//GET USER PROFILE BOARD
//http://happinesslabs.com/app_services_data.php?type=user_profile&fb_id=
//http://happinesslabs.com/app_services_data.php?type=all_stories&fb_id=
//http://happinesslabs.com/app_services_data.php?type=story_detail&fb_id=&story_id=

	$fb_id=$_REQUEST['fb_id'];
	$user_id=getUserId_by_FBid($fb_id);	
	if($user_id>0&&$fb_id!=""){
		$whereCnd='';
		if($type=="user_profile"){
			$selJoinedBoards=mysql_query("select cherryboard_id from tbl_app_expert_cherryboard_meb where req_user_fb_id='".$fb_id."' and is_accept='1'");
			$JoinedBoardsArray=array();
			while($rowJoinedBoards=mysql_fetch_array($selJoinedBoards)){
				$JoinedBoardsArray[]=$rowJoinedBoards['cherryboard_id'];
			}
			$JoinedBoardsCnd='';
			if(count($JoinedBoardsArray)>0){
				$JoinedBoardsId=implode(',',$JoinedBoardsArray);
				$JoinedBoardsCnd=' OR b.cherryboard_id in ('.$JoinedBoardsId.')';
			}	
			$whereCnd=" and (b.user_id='".$user_id."' ".$JoinedBoardsCnd.")";
			
		}else if($type=="story_detail"){
			$story_id=(int)$_REQUEST['story_id'];
			$whereCnd=" and  b.cherryboard_id='".$story_id."'";
		}else if($type=="all_stories"){
			$whereCnd=" and  b.is_publish='1'";
		}
		$selExpert=mysql_query("select a.expertboard_id,a.expertboard_title, a.category_id,a.expertboard_detail,a.goal_days, a.price,b.user_id, b.cherryboard_id from tbl_app_expertboard a,tbl_app_expert_cherryboard b where a.expertboard_id=b.expertboard_id ".$whereCnd." group by b.expertboard_id order by b.cherryboard_id");
		$expertCnt='';
		if(mysql_num_rows($selExpert)>0){
			while($selExpertRow=mysql_fetch_array($selExpert)){
				$cherryboard_id=$selExpertRow['cherryboard_id'];
				$expertboard_title=ucwords($selExpertRow['expertboard_title']);
				$expertboard_id=$selExpertRow['expertboard_id'];
				$category_id=$selExpertRow['category_id'];
				$userId=$selExpertRow['user_id'];
				$goal_days=$selExpertRow['goal_days'];
				
				$rowArray=array();
				$rowArray['id']=$cherryboard_id;
				$rowArray['title']=$expertboard_title;
				$UserDetail=getUserDetail($userId,'uid');
				$rowArray['owner_name']=ucwords($UserDetail['name']);
				$rowArray['owner_photo']=ucwords($UserDetail['photo_url']);
				$rowArray['category_name']=ucwords(getFieldValue('category_name','tbl_app_category','category_id='.$category_id));
				$rowArray['story_days']=(int)$goal_days;
				$rowArray['price']=$selExpertRow['price'];
				$rowArray['detail']=stripslashes($selExpertRow['expertboard_detail']);
				$totalCheers=getFieldValue('count(cheers_id)','tbl_app_expert_cherryboard_cheers','cherryboard_id='.$cherryboard_id);
				$rowArray['total_cheers']=(int)$totalCheers;
								
				$phtotoArray=array();
				$dayMsgArray=array();
				for($i=1;$i<=$goal_days;$i++){
				$selphoto=mysql_query("select photo_name from tbl_app_expert_cherry_photo where cherryboard_id=".$cherryboard_id." and photo_day='".$i."' order by photo_id desc limit 1");
					if(mysql_num_rows($selphoto)>0){
						while($selphotoRow=mysql_fetch_array($selphoto)){
							$photoPath='images/expertboard/'.$selphotoRow['photo_name'];
							if(is_file($photoPath)){
								$phtotoArray[]=SITE_PATH.$photoPath;
							}else{
								$phtotoArray[]=SITE_PATH.'images/expertboard/no_image.jpg';
							}
							
						}	
					}else{
						$phtotoArray[]=SITE_PATH.'images/expertboard/no_image.jpg';
					}
					$dayMsgArray[]=getFieldValue('day_title','tbl_app_expertboard_days','expertboard_id='.$expertboard_id.' and day_no='.$i);
				}
				$rowArray['photos']=$phtotoArray;
				$rowArray['photos_dayMsg']=$dayMsgArray;
				$tblData[]=$rowArray;
				 
			}
		}else{
			$tblData[]='No Story Boards';
		}
	}else{
			$tblData[]='Invalid Data';
	}
	
//GET EXPERTS LIST
//http://happinesslabs.com/app_services_data.php?type=expertsList&fb_id=
}else if($_REQUEST['type']=="expertsList"&&$_REQUEST['fb_id']!=""){
	$fb_id=$_REQUEST['fb_id'];
	$get_user_id=getUserId_by_FBid($fb_id);	
	$selExpert=mysql_query("select cherryboard_id,user_id,cherryboard_title from tbl_app_expert_cherryboard  order by cherryboard_id");
	$expertCnt='';
	if(mysql_num_rows($selExpert)>0&&$user_id>0){
		while($selExpertRow=mysql_fetch_array($selExpert)){
			
			$user_id=$selExpertRow['user_id'];
			$cherryboard_id=ucwords($selExpertRow['cherryboard_id']);
			$userDetailArr=getFieldsValueArray('first_name,last_name,fb_photo_url,location','tbl_app_users','user_id='.$user_id);
			$totalFollowers=(int)getFieldValue('count(meb_id)','tbl_app_expert_cherryboard_meb','cherryboard_id='.$cherryboard_id.' and is_accept="1"');
			
			 $checkBuy=(int)getFieldValue('buy_id','tbl_app_expert_buy','user_id='.$get_user_id.' and cherryboard_id='.$cherryboard_id);
			 
			 
			$rowArray=array();
			$rowArray['user_id']=$selExpertRow['user_id'];
			$rowArray['first_name']=$userDetailArr[0];
			$rowArray['last_name']=$userDetailArr[1];
			$rowArray['fb_photo_url']=$userDetailArr[2];
			$rowArray['location']=$userDetailArr[3];
			$rowArray['cherryboard_id']=$cherryboard_id;
			$rowArray['cherryboard_title']=ucwords($selExpertRow['cherryboard_title']);
			$rowArray['totalFollowers']=$totalFollowers;
			$rowArray['checkBuy']=$checkBuy;
			$tblData[]=$rowArray;
			 
		}
	}else{
		echo 'No Experts';
		exit(0);
	}


}else if($_REQUEST['type']=="ReadPhotoComment"){
//READ PHOTO COMMENT
//http://happinesslabs.com/app_services_data.php?type=ReadPhotoComment&photo_id=

	$selQuery="select user_id,cherry_comment from tbl_app_cherry_comment where	photo_id=".$photo_id." order by comment_id desc";
		$selSqlQ=mysql_query($selQuery) or die('Error');
		if(mysql_num_rows($selSqlQ)>0){
			$cnt=1;
			while($rowTbl=mysql_fetch_array($selSqlQ)){
				$rowArray=array();
				$photoUrl=stripslashes(getFieldValue('fb_photo_url','tbl_app_users','user_id='.$rowTbl['user_id']));
				$rowArray['user_id']=$rowTbl['user_id'];
				$rowArray['fb_photo_url']=$photoUrl;
				$rowArray['cherry_comment']=stripslashes($rowTbl['cherry_comment']);
				$tblData[]=$rowArray;
			}
		}else{
			echo "No Records Found";
			exit(0);
		}

}else if($_REQUEST['type']=="BoardMembers"){ 
//READ BOARD MEMBERS
//http://happinesslabs.com/app_services_data.php?type=BoardMembers&cherry_id=

	$selQuery="select b.fb_photo_url from tbl_app_cherryboard_meb a,tbl_app_users b where a.req_user_fb_id=b.facebook_id and a.cherryboard_id=".$cherry_id;
		$selSqlQ=mysql_query($selQuery) or die('Error');
		if(mysql_num_rows($selSqlQ)>0){
			$cnt=1;
			while($rowTbl=mysql_fetch_array($selSqlQ)){
				$rowArray=array();
				$rowArray['fb_photo_url']=$rowTbl['fb_photo_url'];
				$tblData[]=$rowArray;
			}
			
		}else{
			echo "No Records Found";
			exit(0);
		}

//READ user all boards id with latest photo
//http://happinesslabs.com/app_services_data.php?type=userboardsphoto&fb_id=
}else if($_REQUEST['type']=="userboardsphoto"){ 

		$selQuery="select cherryboard_id from tbl_app_cherryboard where user_id=".$user_id;
		$selSqlQ=mysql_query($selQuery) or die('Error');
		if(mysql_num_rows($selSqlQ)>0){
			$cnt=1;
			while($rowTbl=mysql_fetch_array($selSqlQ)){
				$cherryboard_id=$rowTbl['cherryboard_id'];
				$rowArray=array();
				$rowArray['cherryboard_id']=$cherryboard_id;
				$selQuery1=mysql_query("select photo_title,photo_name from tbl_app_cherry_photo where cherryboard_id=".$cherryboard_id." order by record_date desc limit 1");
				if(mysql_num_rows($selQuery1)>0){
					while($rowTbl1=mysql_fetch_array($selQuery1)){
						$rowArray['photo_title']=$rowTbl1['photo_title'];
						$rowArray['photo_name']=$rowTbl1['photo_name'];
					}
				}else{
					$rowArray['photo_title']='';
					$rowArray['photo_name']='empty_story_board.png';
				}
				
				$tblData[]=$rowArray;
			}
			
		}else{
			echo "No Records Found";
			exit(0);
		}

}else if($_REQUEST['type']!="add_user"){
//URL : http://happinesslabs.com/app_services_data.php?tbl=tbl_app_cherryboard&tblFields=cherryboard_id,cherryboard_title&fb_id=
	if($tblName!=""){
		$whereCnd='';
		if($user_id>0&&$tblName!="tbl_app_cherry_photo"){
			$whereCnd.="user_id=".$user_id;
		}
		if($cherry_id>0){
			if($whereCnd!=""){$whereCnd.=" and ";}
			$whereCnd.="cherryboard_id=".$cherry_id;
		}
		if(trim($_REQUEST['whereCnd'])!=""){
			$whereCnd.=$_REQUEST['whereCnd'];
		}
		
		if($tblName=="tbl_app_cherry_photo"){
			$tblFields_array[]='photo_day';
			$Board_record_date=getFieldValue('record_date','tbl_app_cherryboard','cherryboard_id='.$cherry_id);
			$tblFields.=",(DATEDIFF(record_date,'".$Board_record_date."')+1) as photo_day";
		}
		
		if($whereCnd!=""){
			$whereCnd=" where ".$whereCnd;
		}
		
		if(trim($_REQUEST['orderby'])!=""){
			$whereCnd.=' order by '.$_REQUEST['orderby'];
			if(trim($_REQUEST['sortby'])!=""){
				$whereCnd.=' '.$_REQUEST['sortby'];
			}
			
		}
		if(trim($_REQUEST['limit'])!=""){
			$whereCnd.=' limit '.$_REQUEST['limit'];
		}
		
				
		$selQuery="select ".$tblFields." from ".$tblName." ".$whereCnd;
		$selSqlQ=mysql_query($selQuery) or die('Error');
		if(mysql_num_rows($selSqlQ)>0){
			$cnt=1;
			while($rowTbl=mysql_fetch_array($selSqlQ)){
				$rowArray=array();
				for($i=0;$i<=(count($tblFields_array)-1);$i++){
					if(trim($rowTbl[$i])!=""&&trim($rowTbl[$i])!="\n"){
						$rowArray[$tblFields_array[$i]]=stripslashes($rowTbl[$i]);
					}
				}
				
				
				if($tblName=="tbl_app_cherryboard"){
					$cherryboard_id=$rowTbl['cherryboard_id'];
					$gift_id=(int)getFieldValue('gift_id','tbl_app_cherry_gift','cherryboard_id='.$cherryboard_id);
					
					if($gift_id>0){
						$giftArray=getFieldsValueArray('gift_id,gift_title,gift_photo','tbl_app_gift','gift_id='.$gift_id);
						$rowArray['gift_id']=$giftArray[0];
						$rowArray['gift_title']=$giftArray[1];
						$rowArray['gift_photo']=$giftArray[2];
					}	
			
					$locationArray=getFieldsValueArray('location,checkin_time','tbl_app_cherry_checkin','cherryboard_id='.$cherryboard_id.' order by location_id desc limit 1');
					$rowArray['location']=$locationArray[0];
					$rowArray['checkin_time']=$locationArray[1];
				}
				if($tblName=="tbl_app_expert_cherryboard"){
					$cherryboard_id=$cherry_id;
					$expertboard_id=(int)getFieldValue('expertboard_id','tbl_app_expert_cherryboard','cherryboard_id='.$cherryboard_id);
					
					if($expertboard_id>0){
						$expArray=getFieldsValueArray('category_id,expertboard_title,expertboard_detail,goal_days,price','tbl_app_expertboard','expertboard_id='.$expertboard_id);
						$rowArray['category_id']=$expArray[0];
						$rowArray['expertboard_title']=$expArray[1];
						$rowArray['expertboard_detail']=$expArray[2];
						$rowArray['goal_days']=$expArray[3];
						$rowArray['price']=$expArray[4];
					}
				}
				
				if(count($rowArray)>0){
					$tblData[]=$rowArray;
				}
			}
		}else{
			echo "No Records Found";
			exit(0);
		}
	}
}

//echo "<pre>";
//print_r($tblData);
$jsonData=array(array("data"=>$tblData));
$jsonData=json_encode($jsonData);
$jsonData=substr($jsonData,1);
$jsonData=substr($jsonData,0,(strlen($jsonData)-1));
echo $jsonData;
?>