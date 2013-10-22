<?php
include_once "fbmain.php";
include('include/app-common-config.php');
$userDetail=getUserDetail(USER_ID,'uid');
$photo_url=$userDetail['photo_url'];
$user_name=$userDetail['name'];
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
        <div class="book_page_right1" style="width:570px;">
        	<div class="book_profile_text"><img src="<?=$photo_url?>" height="100px" width="100px" /></div>
        	<?=$user_name?>
        	<div class="life_story_book_text">Life Story Book</div>
        </div>
    </div>
</div>         
<!-- START MIDDLE MISSION BOOK ARROW -->
<div class="middle_mission_div" id="div_mission_middle_arrow" style="display:none;">
<div class="wellness_button_images"></div>
<div class="wellness_button" style="padding:0px;margin:20px 0 10px 0;">
<a href="javascript:void(0);">Move my<br/> happy missions<br/> into my happy<br/> life story book</a>
</div>
</div>
<!-- END OF MIDDLE MISSION BOOK ARROW --> 
<!-- START LEFT PAGE -->
<div class="activate_friends_main_top" id="div_newuser_mission">
    <div class="book_tabs_main_page_left">    
    <?php
    $selPillar=mysql_query("SELECT title,pillar_no FROM tbl_app_happiness_pillar WHERE parent_id=0 ORDER BY pillar_no");
    while($selPillarRow=mysql_fetch_array($selPillar)){
     	  $title=trim(ucwords($selPillarRow['title'])); 
		  $pillar_no=$selPillarRow['pillar_no'];   
		  $_SESSION['mission_'.$pillar_no]='';
		 echo '<div class="book_tabs_left'.($pillar_no==1?'_love':'').'"></div>
               <div class="book_tabs'.($pillar_no==1?'_love':'').'">
			   <div class="process_box"><a href="javascript:void(0);" onclick="ajax_action(\'newuser_happy_mission\',\'div_newuser_mission\',\'pillar_no='.$pillar_no.'\');">'.$title.'</a>
			   </div>
			   </div>
               <div class="book_tabs_right'.($pillar_no==1?'_love':'').'"></div>';
		 
    }
    ?>
    </div>
	<div style="clear:both"></div>      
    <div class="activate_friends_bg">
        <div class="book_page_right_new">
        <div style="padding-top:5px;width:530px;margin:auto;padding-bottom:35px;">
        <table border="0">
        <tr>
        <?php
			$happyMissionCnt='';
			$cnt=1;
	   		$selMission=mysql_query("SELECT * FROM tbl_app_happy_mission WHERE pillar_no=1 ORDER BY happy_mission_id");
	   		while($selMissionRow=mysql_fetch_array($selMission)){ 
				  $happy_mission_id=(int)$selMissionRow['happy_mission_id'];
				  $PillarNo=(int)$selMissionRow['pillar_no'];
				  $happy_mission_title=trim(ucwords($selMissionRow['happy_mission_title']));
				  $happyMissionCnt.='<td>';
				  $happyMissionCnt.='<div style="margin:0 0 0 75px;"><input type="checkbox" id="happy_mission[]" name="happy_mission[]" onclick="ajax_action(\'chk_newuser_mission\',\'div_newuser_mission\',\'pillar_no='.$PillarNo.'&happy_mission_id='.$happy_mission_id.'\');" style="height:20px;width:20px;"></div>';
				  
				  $happyMissionCnt.='<div class="friends_box_img_new"><img src="images/mission/mission_'.$happy_mission_id.'.png" width="150px" height="150px" title="'.$happy_mission_title.'"/></div>';
				  $happyMissionCnt.='</td>';
				  if($cnt==3){$happyMissionCnt.='</tr><tr>'; $cnt=0;}
				  $cnt++;
	   		}
	   		echo $happyMissionCnt;  
		?> 	
        </tr>
        </table>
        </div>      
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
<script type="text/javascript">
//GET CHECKBOX VALUE FUNCTION
function getCheckedBoxes(chkboxName,pillarNo){
	var checkboxes=document.getElementsByName(chkboxName);
	for(var i=0;i<checkboxes.length;i++){
		if(checkboxes[i].checked){
		   document.getElementById('div_checkbox_checked_'+pillarNo).className='process_box_checked';
		   return true;
		}else{
		   document.getElementById('div_checkbox_checked_'+pillarNo).className='process_box';
		   return false;
		}
	}
}
</script>
<?php include('fb_invite.php');?>
<?php include('site_footer.php');?>
