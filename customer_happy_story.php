<?php
include_once "fbmain.php";
include('include/app-common-config.php');
?>
<?php include('site_header.php'); ?>
<?php
if(isset($_POST['btncreate'])){
   $day_type=(int)$_POST['day_type'];	
   $storytitle=trim($_POST['storytitle']);
   $storydesc=trim(addslashes($_POST['storydesc']));
   $happy_mission_id=(int)$_POST['storycategory'];
   $pillar_no=(int)getFieldValue('pillar_no','tbl_app_happy_mission','happy_mission_id='.$happy_mission_id);
   $category_id=(int)getFieldValue('category_id','tbl_app_happiness_pillar','pillar_no='.$pillar_no);
   $storyprice=(int)$_POST['storyprice'];
   $board_type=(int)$_POST['board_type'];
   $storydays=(int)$_POST['storydays'];
   $Customers='Customers';
   
 if((int)USER_ID>0&&$day_type>0&&$storytitle!=''&&$storydesc!=''&&$category_id>0&&$storydays>0&&$happy_mission_id>0){
 
    	$IsStoryBoard=(int)getFieldValue('expertboard_id','tbl_app_expertboard','expertboard_title="'.$storytitle.'" and user_id='.USER_ID);
		if($IsStoryBoard==0){
		   $ip_address=$_SERVER['REMOTE_ADDR'];
		   $insstory="INSERT INTO tbl_app_expertboard (expertboard_id,user_id,category_id,expertboard_title, expertboard_detail,goal_days,price,record_date,day_type,is_board_price,board_type,customers,ip_address,parent_id,living_narrative,happy_mission_id) VALUES (NULL,'".(int)USER_ID."','".$category_id."','".$storytitle."','".$storydesc."','".$storydays."','".$storyprice."',CURRENT_TIMESTAMP,'".$day_type."','1','".$board_type."','".$Customers."','".$ip_address."','0','0','".$happy_mission_id."')";
		   $insQry=mysql_query($insstory);
		   $storyBoardId=mysql_insert_id();
		   if($storyBoardId>0){		   	  
			  //CREATE STORY BOARD
			  $insBoard="INSERT INTO tbl_app_expert_cherryboard
			  (cherryboard_id,user_id,expertboard_id,record_date,main_board)
			  VALUES (NULL,'".(int)USER_ID."','".$storyBoardId."',CURRENT_TIMESTAMP,'1')";
			  $insboard=mysql_query($insBoard);
			  $cherryBoardId=mysql_insert_id();
			  //CREATE STORY TO-DO LIST
			  if($cherryBoardId>0){
			     //CREATE GOAL DAYS
				  $DayType=getDayType($storyBoardId);
				  for($i=1;$i<=$storydays;$i++){
					  $insDays="INSERT INTO tbl_app_expertboard_days
					  (expertboard_day_id,expertboard_id,cherryboard_id,day_no, day_title,record_date)
					  VALUES (NULL,'".$storyBoardId."','".$cherryBoardId."','".$i."','".$DayType." ".$i."',CURRENT_TIMESTAMP)";
					  $insDaysSql=mysql_query($insDays);
				  }
			     //Deposit the happybank point
				 happybankPoint('1',0,$cherryBoardId);
				 for($i=1;$i<=$storydays;$i++){
						$insTodo="INSERT INTO tbl_app_expert_checklist (checklist_id,user_id,cherryboard_id, checklist,record_date,is_checked,is_system) VALUES (NULL,'".(int)USER_ID."','".$cherryBoardId."','".$DayType." ".$i."',CURRENT_TIMESTAMP,'0','1')";
						$insTodoSql=mysql_query($insTodo);
				 }
				 echo "<script>document.location='expert_cherryboard.php?cbid=".$cherryBoardId."'</script>";		
			  }			  
		   }
		}else{
		    $cherryboardId=(int)getFieldValue('cherryboard_id','tbl_app_expert_cherryboard','expertboard_id='.$IsStoryBoard);
			echo "<script>document.location='expert_cherryboard.php?cbid=".$cherryboardId."'</script>";		
		}
   }else{
   		echo "<script>document.location='index_detail.php'</script>";	
   }
}
?>
<!-- START CUSTOMER HAPPY STORY FORM CODE -->
<div style="background-color:#FFFFFF;">
 <div class="Create_Stity_Page_main">
<form action="" method="post" name="createstory" id="createstory" enctype="multipart/form-data">
   <div class="Sumithra_tell_top">
   <?php
	   $userDetails=getUserDetail(USER_ID);
	   $userName=$userDetails['first_name'].' '.$userDetails['last_name'];
	   $userPic=$userDetails['photo_url'];
   ?>
   <span class="Sumithra_tell_top_span"><img src="<?=$userPic?>" alt="" /></span>
   <div class="Sumithra_tell_top_text"><?=$userName?> - Tell A Story For Happy Mind</div>
   </div>
   <div style="clear:both"></div>
   <div class="msg_red" id="divStoryMessage"></div>
   <!-- Left Part -->
   <div class="Create_Stity_left">
    <div class="project_left" style="padding-right:0px;">
        <div class="Story_Title_box" id="DivStoryCategory">2</div>
        <div id="divStoryCatImg" class="project_left_one"></div>
    </div>
    <div class="Story_Title_text">Select Happy Mission :</div>
    <select size="1" id="storycategory" name="storycategory" class="storycat" onchange="ajax_action('show_storycat','divshow_storycat','stype=storycat&txt_storycat='+document.getElementById('storycategory').value);" onFocus="changeDivClass('DivStoryCategory','divStoryCatImg')" >
       <option value="0">Select Happy Mission </option>
       <?php	
	   $happyMissionCnt='';
	   $selMission=mysql_query("SELECT * FROM tbl_app_happy_mission ORDER BY happy_mission_id");
	   while($selMissionRow=mysql_fetch_array($selMission)){ 	
		     $happyMissionCnt.='<option value="'.$selMissionRow['happy_mission_id'].'">'.ucwords($selMissionRow['happy_mission_title']).'</option>';
	   }
	   echo $happyMissionCnt;  
	   ?>
    </select>
    <div class="project_left" style="padding-right:0px;">
        <div class="Story_Title_box" id="DivStoryTitle">3</div>
        <div id="divStoryImg" class="project_left_one"></div>
    </div>    
    <div class="Story_Title_text">Story Title :</div>
    <input name="storytitle" id="storytitle" type="text" onkeyup="showValueonDiv('divshow_storytitle',this.value)" onFocus="changeDivClass('DivStoryTitle','divStoryImg')" class="Story_Title_input_box" value="Story Title"/>
    
        <div class="project_left" style="padding-right:0px;">
            <div class="Story_Title_box" id="DivStoryDesc">4</div>
             <div id="divDescImg" class="project_left_one"></div>
        </div>
    <div class="Story_Title_text">Story Description :</div>
	<textarea name="storydesc" id="storydesc" onkeyup="showValueonDiv('divshow_storydesc',this.value)" onFocus="changeDivClass('DivStoryDesc','divDescImg')" rows="7" class="Story_Title_input_box">Story Description</textarea>    
    
    <div class="project_left" style="padding-right:0px;">
    	<div class="Story_Title_box" id="DivStoryBoardPrice">5</div>
        <div id="divStoryPriceImg" class="project_left_one"></div>
    </div>
    <div class="Story_Title_text">Price :</div>
    <input name="storyprice" id="storyprice" type="text" class="Story_Title_input_box" onkeyup="showValueonDiv('divshow_storyprice','$'+this.value+'.00')" value="0" onFocus="changeDivClass('DivStoryBoardPrice','divStoryPriceImg')"/>
    
    <div class="project_left">
        <div class="Story_Title_box" id="DivStoryBoardType">6</div>
        <div id="divStoryTypeImg" class="project_left_one"></div>
    </div>
    <div class="Story_Title_text">Story Access :</div>
	<div class="Select_Story_Template_day_text" style="padding:5px 0 5px 0;width:221px;">
	<input name="board_type" id="board_type" type="radio" checked="checked" value="0" onClick="changeDivClass('DivStoryBoardType','divStoryTypeImg')" /> Public &nbsp;
    <input name="board_type" id="board_type" type="radio" value="1" onClick="changeDivClass('DivStoryBoardType','divStoryTypeImg')" /> Private &nbsp;&nbsp;
	</div>
	
    <div class="project_left">
        <div class="Story_Title_box" id="DivStoryDayType">7</div>
        <div id="divStoryDayImg" class="project_left_one"></div>
    </div>
    <div id="divPhotoNo" class="Story_Title_text">Number of Days:</div>
	<input name="dayType" id="dayType" type="hidden" value="Day" />
    <select size="1" id="storydays" name="storydays" onchange="showValueonDiv('divshow_storydays',this.value)" class="storycat" onFocus="changeDivClass('DivStoryDayType','divStoryDayImg')">
    <option value="0">Select Number of Days </option>
    <?php
    for($i=1;$i<=30;$i++){
    echo '<option value="'.$i.'">'.$i.'</option>';
    }
    ?>
    </select>
	<!-- START CREATE BUTTON CODE -->
	<div class="banner_day_5_left" style="width:172px;">
		<div class="banner_day_5_bg" style="width:100px;">
		<input type="hidden" name="btncreate" id="btncreate" value="Create">
		<a href="javascript:document.createstory.submit();" title="Create" onClick="javascript:return CheckFormValidation('divStoryMessage','day_type#Please Select Story Template,storytitle#Please Enter Story Title,storydesc#Please Enter Discription,storycategory#Please Select Category#0,storyprice#Please Enter Story Price,board_type#Please Select Board Type,storydays#Please Select Number Of Days#0');">Create</a></div>
		<div class="banner_day_5_im"><img src="images/ban.png" alt="" /></div>		
	</div>
	<div class="banner_day_5_right" style="float:right;"><img src="images/im.png" /></div>	
	<!-- END CREATE BUTTON CODE -->	
    </div>
   <!-- Right Part -->
     <div class="Create_Stity_right">
         <div class="project_left" style="padding-right:0px;">
     	     <div class="project_left_1" id="DivStoryTemplate">1</div>
             <div class="project_left_one" id="divTemplateImg"><img src="images/one_2.png" alt="" /></div>
		</div>
     <div class="Story_Title_text">Select Story Template :</div>
    
    <div class="Select_Story_Template_main">
      <div id="divDayByDay" class="Select_Story_Template_box_main">
        <div class="Select_Story_Template_day_text">
		<input name="day_type" id="day_type" type="radio" checked="checked" value="1"
		onclick="showValueonDiv('photo1_no#photo2_no#photo3_no#photo1_act#photo2_act#photo3_act#photo1_thm#photo2_thm#photo3_thm#photo1_upd#photo2_upd#photo3_upd#divPhotoNo#divDayShow','Day 1#Day 2#Day 3#day 1 action#day 2 action#day 3 action#Day 1 Theme#Day 2 Theme#Day 3 Theme#Upload<br/>Pic<br/>For<br/>Day 1<br/>Action#Upload Pic<br/>For Day 2<br/>Action#Upload<br/>Pic<br/>For<br/>Day 3<br/>Action#Number of Days:#Days');changeDivClass('DivStoryTemplate','divTemplateImg')"/> Day By Day </div>
        <div class="Select_Story_Template_day_box">day1</div>
        <div class="Select_Story_Template_day_box">day2</div>
        <div class="Select_Story_Template_day_box">day3</div>
      </div>
      
      <div id="divItemByItem" class="Select_Story_Template_box_main">
        <div class="Select_Story_Template_day_text">
		<input name="day_type" id="day_type" type="radio" value="2" 
		onclick="showValueonDiv('photo1_no#photo2_no#photo3_no#photo1_act#photo2_act#photo3_act#photo1_thm#photo2_thm#photo3_thm#photo1_upd#photo2_upd#photo3_upd#divPhotoNo#divDayShow','Item 1#Item 2#Item 3#item 1 action#item 2 action#item 3 action#Item 1 Theme#Item 2 Theme#Item 3 Theme#Upload<br/>Pic<br/>For<br/>Item 1<br/>Action#Upload Pic<br/>For Item 2<br/>Action#Upload<br/>Pic<br/>For<br/>Item 3<br/>Action#Number of Items:#Items');changeDivClass('DivStoryTemplate','divTemplateImg')"/> Item By Item</div>
        <div class="Select_Story_Template_day_box">item1</div>
        <div class="Select_Story_Template_day_box">item2</div>
        <div class="Select_Story_Template_day_box">item3</div>
      </div>
      
      <div id="divStepByStep" class="Select_Story_Template_box_main">
        <div class="Select_Story_Template_day_text">
		<input name="day_type" id="day_type" type="radio" value="3" 
		onclick="showValueonDiv('photo1_no#photo2_no#photo3_no#photo1_act#photo2_act#photo3_act#photo1_thm#photo2_thm#photo3_thm#photo1_upd#photo2_upd#photo3_upd#divPhotoNo#divDayShow','Step 1#Step 2#Step 3#step 1 action#step 2 action#step 3 action#Step 1 Theme#Step 2 Theme#Step 3 Theme#Upload<br/>Pic<br/>For<br/>Step 1<br/>Action#Upload Pic<br/>For Step 2<br/>Action#Upload<br/>Pic<br/>For<br/>Step 3<br/>Action#Number of Steps:#Steps');changeDivClass('DivStoryTemplate','divTemplateImg')"/> Step By Step</div>
        <div class="Select_Story_Template_day_box">step1</div>
        <div class="Select_Story_Template_day_box">step2</div>
        <div class="Select_Story_Template_day_box">step3</div>
      </div>
      
      <div id="divDateByDate" class="Select_Story_Template_box_main">
        <div class="Select_Story_Template_day_text">
		<input name="day_type" id="day_type" type="radio" value="4"
		onclick="showValueonDiv('photo1_no#photo2_no#photo3_no#photo1_act#photo2_act#photo3_act#photo1_thm#photo2_thm#photo3_thm#photo1_upd#photo2_upd#photo3_upd#divPhotoNo#divDayShow','Date 1#Date 2#Date 3#date 1 action#date 2 action#date 3 action#Date 1 Theme#Date 2 Theme#Date 3 Theme#Upload<br/>Pic<br/>For<br/>Date 1<br/>Action#Upload Pic<br/>For Date 2<br/>Action#Upload<br/>Pic<br/>For<br/>Date 3<br/>Action#Number of Dates:#Dates');changeDivClass('DivStoryTemplate','divTemplateImg')"/>Narration By Date</div>
        <div class="Select_Story_Template_day_box">date1</div>
        <div class="Select_Story_Template_day_box">date2</div>
        <div class="Select_Story_Template_day_box">date3</div>
      </div>
    </div>
    
    <div class="Select_Story_Template_main">
     <div class="Slides_images"><img src="images/MISSON.png" alt="" /></div>
     <div class="Item_By_Item_10day_main">
       <div id="divshow_catIcon" class="icon_home_page" style="height:150px;"></div>	
       <div id="divshow_storycat" class="Happy_Mission_bg">
        <img src="images/mission/mission_1.png" height="150" width="150"/>
       </div>
       <div id="divshow_storytitle" class="Day_Vegan_Challenge_10text">Story Title</div>
       <div class="by_Olivia_Janisch_text">by <?=$userName?></div>
       <div id="divshow_storydesc" style="font-size:12px;">Story Description</div>
       <div class="Total_10Days_text">
           <div class="Total_Days_text">Total :</div>
           <div id="divshow_storydays" class="Total_10Days_style">0</div>
           <div id="divDayShow" class="Total_10Days_style">Days</div>
       </div>
       <div class="Total_10Days_text">
           <div class="Total_Days_text">Price :</div>
           <div id="divshow_storyprice" class="Total_10Days_style">$0.00</div>
       </div>  
       <img src="images/do-it!---images.png" alt="" />             
     </div>
     <div class="Classmates_more_main">
      <span class="Classmates_more_text">Classmates</span>
      <img src="images/Menbers.png" alt="" />
     </div>
     <div class="Price_Story_Access">
       <div class="price_text" id="photo1_no">Day 1</div>
       <div class="price_text" id="photo2_no">Day 2</div>
       <div class="price_text" id="photo3_no">Day 3</div>
       
       <div class="day_action_box_main">
           <div class="day_action_box" id="photo1_act">day 1 action</div>
           <div class="day_action_box" id="photo2_act">day 2 action</div>
           <div class="day_action_box" id="photo3_act">day 3 action</div>
       </div>
       
       <div class="Upload_Pic_Day_right">
           <div class="Upload_Pic_Day_Rewards">Upload<br /> Pic<br /> For<br /> rewards</div>
       </div>
       
       <div class="THEME_main_bg">
            <div class="THEME_main">
             <div class="day_THEME" id="photo1_thm">Day 1 Theme</div>
            </div>
            <div class="THEME_main">
             <div class="day_THEME" id="photo2_thm">Day 2 Theme</div>
            </div>
        </div>
        <div class="day_THEME2" id="photo3_thm">Day 3 Theme</div>
        
        <div class="Upload_Pic_Day_main">
           <div class="Upload_Pic_Day" id="photo1_upd">Upload<br /> Pic<br /> For<br /> Day 1<br /> Action</div>
        </div>
        <div class="Upload_Pic_Day_main2">
          <div class="Upload_Pic_Day2" id="photo2_upd">Upload Pic<br /> For Day 2<br /> Action</div>
        </div>
        <div class="Upload_Pic_Day_main">
          <div class="Upload_Pic_Day" id="photo3_upd">Upload<br /> Pic<br /> For<br /> Day 3<br /> Action</div>
        </div>
     </div>	
    </div>    
   </div>
 </form>  
 </div> 
 <div style="clear:both;"></div> 
</div>
<br/><br/>
<!-- END CUSTOMER HAPPY STORY FORM CODE -->
<script language="javascript">
//START SHOW INPUT VALUE IN DIV FUNCTION
function showValueonDiv(divName,divCnt){	
	var divArr=divName.split('#');
	var divArrCnt=divCnt.split('#');
	for(i=0;i<divArr.length;i++){
		document.getElementById(divArr[i]).innerHTML=divArrCnt[i];
	}
}
//START CHANGE DIV CSS STYLE CLASS FUNCTION
function changeDivClass(divname,divImg){   	
	var divArr=['DivStoryTemplate','DivStoryTitle','DivStoryDesc','DivStoryCategory','DivStoryBoardPrice','DivStoryBoardType','DivStoryDayType'];
	var imgArr=['divTemplateImg','divStoryImg','divDescImg','divStoryCatImg','divStoryPriceImg','divStoryTypeImg','divStoryDayImg'];
	for(i=0;i<divArr.length;i++){
		if(divArr[i]==divname){ 
		   document.getElementById(divname).className='project_left_1';
		   document.getElementById(divImg).innerHTML='<img src="images/one_2.png" alt="" />';	
		}else{ 
		   document.getElementById(divArr[i]).className='Story_Title_box'; 
		   document.getElementById(imgArr[i]).innerHTML='';
		}
	}
}
</script>
<?php include('site_footer.php');?> 