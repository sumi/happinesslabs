<?php
include_once "fbmain.php";
include('include/app-common-config.php');
?>
<?php include('site_header.php'); ?>
<!-- Include stylesheet happiness book -->
<link rel="stylesheet" type="text/css" href="css/happiness_book.css" />
<!-- START MIDDLE SECTION -->
<div class="relationship_bg" style="padding:50px 0; background-color:#FFFFFF;height:auto;">
<div id="magazine" style="margin:auto;">
<div class="welcome_main" style="background-color:#FFFFFF">
<!-- FRONT/RIGHT PAGE -->
<div class="activate_friends_main_top" style="width:569px">
    <div class="book_tabs_main_page">
    <?php
    $selPillar=mysql_query("SELECT title FROM tbl_app_happiness_pillar WHERE parent_id=0 ORDER BY pillar_no");
    while($selPillarRow=mysql_fetch_array($selPillar)){
     	  $title=trim(ucwords($selPillarRow['title']));    
     	  echo '<div class="book_tabs_left"></div>
                <div class="book_tabs"><a href="#">'.$title.'</a></div>
                <div class="book_tabs_right"></div>';
    }
    ?>
    </div>
	<div style="clear:both"></div>          
    <div class="activate_friends_bg">
    	<div class="happy_mission_main" style="margin-left:576px;">
           <!--<div class="happy_mission_text">
           <a href="happy_mission.php">happy mission</a></div>-->
           <div class="happy_mission_text"><a href="#">products</a></div>
           <div class="happy_mission_text"><a href="#">people</a></div>
           <div class="happy_mission_text"><a href="#">places</a></div>
           <div class="happy_mission_text"><a href="#">plans</a></div>
        </div>
        <div class="book_page_right_new1">
        <?php
		$selMissionsCnt='';
		//START SELECT NEW USER MISSIONS
		$selUsrMission=mysql_query("SELECT * FROM tbl_app_user_happy_mission WHERE user_id=".$_SESSION['USER_ID']);
		while($selUsrMissionRow=mysql_fetch_array($selUsrMission)){			  
			  $user_mission_id=(int)$selUsrMissionRow['user_mission_id'];
			  $pillar_no=(int)$selUsrMissionRow['pillar_no'];
			  $happy_mission_id=trim($selUsrMissionRow['happy_mission_id']);
			  $missionIdsArr=explode(',',$happy_mission_id);
			  
			  foreach($missionIdsArr as $missId){
				 $missionPic='images/mission/mission_'.$missId.'.png';
				 $selMissionsCnt.='<div style="height:180px;">
				 <div class="friends_box_img_new" style="float:left;">
				 <img src="'.$missionPic.'" height="150" width="150" />
				 </div>';
				 //GET STORYBOARD DETAILS 
				 $selStory=mysql_query("SELECT expertboard_id,user_id,expertboard_title FROM tbl_app_expertboard WHERE happy_mission_id=".$missId);
				 while($selStoryRow=mysql_fetch_array($selStory)){
					   $expertboard_id=(int)$selStoryRow['expertboard_id'];
					   $user_id=(int)$selStoryRow['user_id'];
					   $expertboard_title=trim(ucwords($selStoryRow['expertboard_title']));
					   $userDetail=getUserDetail($user_id,'uid');
					   $photoUrl=$userDetail['photo_url'];
					   $ownerName=$userDetail['name'];
					   //CHECK STORYBOARD IS PUBLISHED OR NOT PUBLISHED
					   $is_publish=(int)getFieldValue('is_publish','tbl_app_expert_cherryboard','expertboard_id='.$expertboard_id.' AND user_id='.$user_id);
					  
					   if($expertboard_title!=''&&$user_id>0&&$is_publish==1){
						  $selMissionsCnt.='<div style="float:left;padding-right:5px;">'.$expertboard_title.'</div>';
						  $selMissionsCnt.='<div style="float:left;padding-right:5px;"><img src="'.$photoUrl.'" height="30px" width="30px" title="'.$ownerName.'" style="border:1px solid #6A6A6A;"/></div>';
						  $selMissionsCnt.='<div style="float:left;">
						  <a href="#" title="Join" style="text-decoration:none;">
						  Join</a></div><br/><br/>';							  
					   }
				 }	
				 $selMissionsCnt.='</div>';			 
			  }			  			
		}
		$selMissionsCnt.='<div style="padding-bottom:35px;"></div>';
		echo $selMissionsCnt;
		?>	          
        </div>        
    </div>
</div>
<!-- START LEFT PAGE -->
<div class="activate_friends_main_top" id="div_newuser_mission">
    <div class="book_tabs_main_page_left" style="margin-top:22px;"></div>
	<div style="clear:both"></div>  
    	<div class="happy_mission_main_left">
           <div class="happy_mission_text_left">
           <a href="happy_mission.php">happy mission</a></div>
           <!--<div class="happy_mission_text"><a href="#">products</a></div>
           <div class="happy_mission_text"><a href="#">people</a></div>
           <div class="happy_mission_text"><a href="#">places</a></div>
           <div class="happy_mission_text"><a href="#">plans</a></div>-->
        </div>    
    <div class="activate_friends_bg">
        <div class="book_page_right">
        <div style="font-size:xx-large;color:#FF0000;margin-top:150px;"> My <br/> Happy <br/> Missions </div>    
        </div>      
    </div>
</div>
<!-- END OF LEFT PAGE -->   
</div>
</div>
</div>
<div style="clear:both"></div>
<div style="padding-bottom:60px;"></div>
<!-- END MIDDLE SECTION -->
<?php include('fb_invite.php');?>
<?php include('site_footer.php');?>