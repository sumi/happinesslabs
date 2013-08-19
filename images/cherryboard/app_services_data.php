<?php
header('Content-Type: application/json');
include('include/app-db-connect.php');
include('include/app_functions.php');
$tblName=$_GET['tbl'];
$tblFields=$_GET['tblFields'];
$fb_id=trim($_GET['fb_id']);
$user_id=0;
if($fb_id!=""){
	$user_id=getUserId_by_FBid($fb_id);
}
$cherry_id=(int)($_GET['cherry_id']);
$photo_id=(int)($_GET['photo_id']);
$tblFields_array=explode(',',$_GET['tblFields']);
$tblData=array();
//READ PHOTO COMMENT
//http://30daysnew.com/app_services_data.php?type=ReadPhotoComment&photo_id=
if($_GET['type']=="ReadPhotoComment"){
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
}
//READ BOARD MEMBERS
//http://30daysnew.com/app_services_data.php?type=BoardMembers&cherry_id=
if($_GET['type']=="BoardMembers"){ 

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

}else{
//URL : http://30daysnew.com/app_services_data.php?tbl=tbl_app_cherryboard&tblFields=cherryboard_id,cherryboard_title&fb_id=
	if($tblName!=""){
		$whereCnd='';
		if($user_id>0){
			$whereCnd.="user_id=".$user_id;
		}
		if($cherry_id>0){
			if($whereCnd!=""){$whereCnd.=" and ";}
			$whereCnd.="cherryboard_id=".$cherry_id;
		}
		if(trim($_GET['whereCnd'])!=""){
			$whereCnd.=$_GET['whereCnd'];
		}
		if($whereCnd!=""){
			$whereCnd=" where ".$whereCnd;
		}
		
		if(trim($_GET['orderby'])!=""){
			$whereCnd.=$_GET['orderby'];
		}
		if(trim($_GET['limit'])!=""){
			$whereCnd.=$_GET['limit'];
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
$jsonData=array(array("data"=>$tblData));
$jsonData=json_encode($jsonData);
$jsonData=substr($jsonData,1);
$jsonData=substr($jsonData,0,(strlen($jsonData)-1));
echo $jsonData;
?>