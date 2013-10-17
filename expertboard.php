<?php
include_once "fbmain.php";
include('include/app-common-config.php');
//$expertBoardId=(int)$_GET['eid'];
$cherryboard_id=$_GET['cbid'];
$expertBoardId=(int)getFieldValue('expertboard_id','tbl_app_expert_cherryboard','cherryboard_id='.$cherryboard_id);
$type=trim($_GET['type']);

//START EXPERTBOARD COPY CODE
if($expertBoardId>0&&$type=='copy'&&USER_ID>0&&$cherryboard_id>0){	
  $newCherryBoardId=CopyExpertBoard($expertBoardId,$cherryboard_id);
  //deposit for the copy
	$ownerId=getOwnerFbId($newCherryBoardId);
	happybankPoint('3',$ownerId,$newCherryBoardId);
  echo "<script>document.location='expert_cherryboard.php?cbid=".$newCherryBoardId."'</script>";		
}
//START CREATE EXPERTBOARD/STORYBOARD AND SUBSTORYBOARD CODE
if(isset($_POST['btnCreateExpert'])||isset($_POST['btnCreateStory'])){

	if(isset($_POST['btnCreateExpert'])){
	   $expertboard_title=trim($_POST['title']);
	   $expertboard_detail=trim(addslashes($_POST['detail']));
	   $category_id=(int)$_POST['category_id1'];
	   $is_board_price=(int)$_POST['is_board_price'];
	   $price=$_POST['price'];
	}
	//START CREATE CUSTOMER STORY SECTION
	if(isset($_POST['btnCreateStory'])){
	   $expertboard_title=trim($_POST['story_title']);
	   $expertboard_detail=trim(addslashes($_POST['story_detail']));
	   $category_id=(int)$_POST['category_id2'];
	   $is_board_price=(int)$_POST['IsBoardPrice'];
	   $price=$_POST['story_price'];
	}	
	$day_type=(int)$_POST['day_type'];
	$number_days=(int)$_POST['number_days'];	
	$board_type=(int)$_POST['board_type'];
	//GET SUB STORY FIELDS VALUE
	$create_from=trim($_POST['create_from']);
	$cherryboard_parent_id=(int)$_POST['cherryboard_parent_id'];
	$parent_id=(int)getFieldValue('expertboard_id','tbl_app_expert_cherryboard','cherryboard_id='.$cherryboard_parent_id);
	if($day_type==4){
		$day_type=1;
		$number_days=1;
	}
				
	if($create_from=='header'||$create_from==''){ $parent_id=0; }
	
	if($is_board_price==1){
		$Customers='Customers';
	}else{
		$Customers='People';
	}
	
	if((int)USER_ID>0&&$expertboard_title!=''&&$expertboard_detail!=''&&$category_id>0&&$number_days>0){
		$checkExpBoard=(int)getFieldValue('expertboard_id','tbl_app_expertboard','expertboard_title="'.$expertboard_title.'" and user_id='.USER_ID);
		if($checkExpBoard==0){
			$ip_address=$_SERVER['REMOTE_ADDR'];
			$insExpBoard="INSERT INTO tbl_app_expertboard (expertboard_id,user_id,category_id,expertboard_title, expertboard_detail,goal_days,price,record_date,day_type,is_board_price,board_type,customers,ip_address,parent_id,living_narrative) VALUES (NULL,'".(int)USER_ID."','".$category_id."','".$expertboard_title."','".$expertboard_detail."','".$number_days."','".$price."',CURRENT_TIMESTAMP,'".$day_type."','".$is_board_price."','".$board_type."','".$Customers."','".$ip_address."','".$parent_id."','".$living_narrative."')";
			$insQry=mysql_query($insExpBoard);
			$NewexpertBoardId=mysql_insert_id();
			if($NewexpertBoardId>0){				
				//new main goal board
				if($cherryboard_parent_id>0){	
					$cherryBoardId=$cherryboard_parent_id;
				}else{ $cherryBoardId=0; }						
				$insExpBoard=mysql_query("INSERT INTO tbl_app_expert_cherryboard (cherryboard_id,user_id,expertboard_id, category_id,cherryboard_title,record_date,makeover,qualified,help_people,start_date,price,fb_album_id,main_board,parent_id)
				VALUES (NULL, '".(int)USER_ID."','".$NewexpertBoardId."','0','', CURRENT_TIMESTAMP,'','','','','0','','1','".$cherryBoardId."')");
				$GoalBoardId=mysql_insert_id();
				if($GoalBoardId>0){
					//GET DAY TYPE
					$DayType=getDayType($NewexpertBoardId);
					//created goal days
					for($i=1;$i<=$number_days;$i++){
						$insDays="INSERT INTO tbl_app_expertboard_days
						(expertboard_day_id,expertboard_id,cherryboard_id,day_no,day_title,record_date)
						VALUES (NULL,'".$NewexpertBoardId."','".$GoalBoardId."','".$i."','".$DayType." ".$i."',CURRENT_TIMESTAMP)";
						$insDaysSql=mysql_query($insDays);
					}
					//Deposit the happybank point
					happybankPoint('1',0,$GoalBoardId);
					//Create Goal To-Do List
					for($i=1;$i<=$number_days;$i++){
						$insTodo="INSERT INTO tbl_app_expert_checklist (checklist_id,user_id,cherryboard_id, checklist,record_date,is_checked,is_system) VALUES (NULL,'".(int)USER_ID."','".$GoalBoardId."','".$DayType." ".$i."',CURRENT_TIMESTAMP,'0','1')";
						$insTodoSql=mysql_query($insTodo);
					}
					echo "<script>document.location='expert_cherryboard.php?cbid=".$GoalBoardId."'</script>";		
				}
			}	
		}
	}else{
		echo "<script>document.location='index_detail.php'</script>";	
	}
}
//END CREATE EXPERT CODE
//START CREATE EXPERTBOARD BUY(Do-It) CODE
if($expertBoardId>0&&$type=='doit'&&$cherryboard_id>0){
	$lastCreatedId=createExpertboard($expertBoardId,$cherryboard_id);
	//deposit for the do-it
	$ownerId=getOwnerFbId($lastCreatedId);
	happybankPoint('2',$ownerId,$lastCreatedId);
	echo "<script type=\"text/javascript\">document.location.href='expert_cherryboard.php?cbid=".$lastCreatedId."';</script>";
}
//END CREATE EXPERTBOARD BUY(Do-It) CODE	
?>
<?php include('site_header.php'); ?>	
<!--Body Start-->
<div id="wrapper">
<?php
	$userExpertBoardIds=mysql_query("SELECT expertboard_id FROM tbl_app_expert_cherryboard WHERE user_id=".USER_ID." ORDER BY cherryboard_id");
	
	$userExpertBoardIdsArr=array();
	while($userExpertBoardRows=mysql_fetch_array($userExpertBoardIds)){
		$userExpertboardId=$userExpertBoardRows['expertboard_id'];
		if($userExpertboardId>0){
			$userExpertBoardIdsArr[]=$userExpertboardId;		
		}
	}
	//print_r($userExpertBoardIdsArr);
	if($type=='expert'){
		$sel=mysql_query("SELECT * FROM tbl_app_expertboard WHERE user_id=".USER_ID." OR expertboard_id IN(".implode(',',$userExpertBoardIdsArr).") ORDER BY expertboard_id");
	}else{
		$sel=mysql_query("SELECT * FROM tbl_app_expertboard ORDER BY expertboard_id");
	}	
	$giftCnt='<table border="0"><tr>';
	if(mysql_num_rows($sel)>0){
	    $cnt=1;
		while($row=mysql_fetch_array($sel)){
			if($cnt==7){$giftCnt.='</tr><tr>';$cnt=1;}			
			$user_id=(int)$row['user_id'];
			$expertboard_id=(int)$row['expertboard_id'];
			$expertboard_title=ucwords(trim($row['expertboard_title']));
			$userDetail=getUserDetail($user_id);
			$userOwnerFbId=$userDetail['fb_id'];
			$userName=$userDetail['name'];
			
			//$expertPicPath='images/expert.jpg';
			$expertPicPath='https://graph.facebook.com/'.$userOwnerFbId.'/picture?type=large';
			
				
			$cherryboard_id=(int)getFieldValue('cherryboard_id','tbl_app_expert_cherryboard','expertboard_id="'.$expertboard_id.'" and main_board="0" AND user_id='.USER_ID);
			//expert_profile.php?eid='.$expertboard_id.'
			if($expertPicPath!=""){
				$Owner_cherryboard_id=(int)getFieldValue('cherryboard_id','tbl_app_expert_cherryboard','expertboard_id="'.$expertboard_id.'" and main_board="1" limit 1');
				$giftCnt.='<td><div class="gift_center">
				<a href="expert_cherryboard.php?cbid='.$Owner_cherryboard_id.'">
				<img src="'.$expertPicPath.'" class="imgbig" title="'.$userName.'"></a><br>
				'.$userName.'<br/>
				'.($expertboard_title!=''?'<strong>'.getLimitString($expertboard_title,50).'</strong><br>':'').'<br>';
				/*if($cherryboard_id>0){
				   $giftCnt.='<a href="expert_cherryboard.php?cbid='.$cherryboard_id.'" name="View Goal" class="btn_small" title="View Goal">View Goal</a>';
				}else{*/
				   $giftCnt.='<a href="expert_cherryboard.php?cbid='.$Owner_cherryboard_id.'" name="View" class="btn_small" title="View">View</a>';
				//}
				$giftCnt.='</div></td>';
		    }
			$cnt++;
		}

		for($i=$cnt;$i<=3;$i++){
			$giftCnt.='<td>&nbsp;</td>';
		}
	}else{
		$giftCnt.='<td><strong>'.($type=='expert'?'No Challenge':'No Challenges').'</strong></td>';
	}
	echo $giftCnt.'</tr></table>';
//END EXPERT PART CODE	
  ?>  
<div class="clear"></div>
</div>
<!--Gray body End-->
<!--Body End-->
<?php include('site_footer.php');?> 