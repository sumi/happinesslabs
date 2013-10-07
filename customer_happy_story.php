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
   $storycategory=(int)$_POST['storycategory'];
   $storyprice=(int)$_POST['storyprice'];
   $board_type=(int)$_POST['board_type'];
   $storydays=(int)$_POST['storydays'];
   $Customers='Customers';
   
 if((int)USER_ID>0&&$day_type>0&&$storytitle!=''&&$storydesc!=''&&$storycategory>0&&$storydays>0){
 
    	$IsStoryBoard=(int)getFieldValue('expertboard_id','tbl_app_expertboard','expertboard_title="'.$storytitle.'" and user_id='.USER_ID);
		if($IsStoryBoard==0){
		   $ip_address=$_SERVER['REMOTE_ADDR'];
		   $insstory="INSERT INTO tbl_app_expertboard (expertboard_id,user_id,category_id,expertboard_title, expertboard_detail,goal_days,price,record_date,day_type,is_board_price,board_type,customers,ip_address,parent_id,living_narrative) VALUES (NULL,'".(int)USER_ID."','".$storycategory."','".$storytitle."','".$storydesc."','".$storydays."','".$storyprice."',CURRENT_TIMESTAMP,'".$day_type."','1','".$board_type."','".$Customers."','".$ip_address."','0','0')";
		   $insQry=mysql_query($insstory);
		   $storyBoardId=mysql_insert_id();
		   if($storyBoardId>0){
		   	  //CREATE GOAL DAYS
		   	  $DayType=getDayType($storyBoardId);
			  for($i=1;$i<=$storydays;$i++){
				  $insDays="INSERT INTO tbl_app_expertboard_days (expertboard_day_id,expertboard_id,day_no, day_title,record_date) VALUES (NULL,'".$storyBoardId."','".$i."','".$DayType." ".$i."',CURRENT_TIMESTAMP)";
				  $insDaysSql=mysql_query($insDays);
			  }
			  //CREATE STORY BOARD
			  $insBoard="INSERT INTO tbl_app_expert_cherryboard
			  (cherryboard_id,user_id,expertboard_id,record_date,main_board)
			  VALUES (NULL,'".(int)USER_ID."','".$storyBoardId."',CURRENT_TIMESTAMP,'1')";
			  $insboard=mysql_query($insBoard);
			  $cherryBoardId=mysql_insert_id();
			  //CREATE STORY TO-DO LIST
			  if($cherryBoardId>0){
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
    <div class="project_left" id="DivStoryTitle" style="padding-right:0px;">
    <div class="Story_Title_box">2</div>
    </div>
    <div class="Story_Title_text">Story Title :</div>
    <input name="storytitle" id="storytitle" type="text" onkeypress="ajax_action('show_storytitle','divshow_storytitle','stype=storytitle&txt_storytitle='+document.getElementById('storytitle').value);" onFocus="ajax_action('focus_story_title','DivStoryTitle','stype=StoryTitle');" class="Story_Title_input_box" value="Story Title"/>
    
    <div class="project_left" id="DivStoryAbout" style="padding-right:0px;">
    <div class="Story_Title_box">3</div>
    </div>
    <div class="Story_Title_text">Story Description :</div>
	<textarea name="storydesc" id="storydesc" onkeypress="ajax_action('show_storydesc','divshow_storydesc','stype=storydesc&txt_storydesc='+document.getElementById('storydesc').value);" onFocus="ajax_action('focus_story_about','DivStoryAbout','stype=about');" class="Story_Title_input_box">Story Description</textarea>
    
    <div class="project_left" id="DivStoryCategory" style="padding-right:0px;">
	<div class="Story_Title_box">4</div>
	</div>
    <div class="Story_Title_text">Story Category :</div>
    <select size="1" id="storycategory" name="storycategory" class="storycat" onFocus="ajax_action('focus_story_category','DivStoryCategory','stype=StoryCat');">
       <option value="0">Select Story Category </option>
       <?php
	   $catArray=array(1=>2,2=>19,3=>21,4=>24,5=>29,6=>30,7=>31);	
	   $catCnt=''; 
	   foreach($catArray as $catid){
	   	  $selCat=mysql_query("SELECT * FROM tbl_app_category WHERE category_id=".$catid);
		  while($selCatRow=mysql_fetch_array($selCat)){ 	
	   	  	$catCnt.='<option value="'.$catid.'">'.ucwords($selCatRow['category_name']).'</option>';
		  }
	   }
	   echo $catCnt;  
	   ?>
    </select>
    <div class="project_left" id="DivStoryBoardPrice" style="padding-right:0px;">
    <div class="Story_Title_box">5</div>
    </div>
    <div class="Story_Title_text">Price :</div>
    <input name="storyprice" id="storyprice" type="text" class="Story_Title_input_box" onkeypress="ajax_action('show_storyprice','divshow_storyprice','stype=storyprice&txt_storyprice='+document.getElementById('storyprice').value);" value="0" onFocus="ajax_action('focus_story_price','DivStoryBoardPrice','stype=boardprice')"/>
    
    <div class="project_left" id="DivStoryBoardType">
    <div class="Story_Title_box">6</div>
    </div>
    <div class="Story_Title_text">Story Access :</div>
	<div class="Select_Story_Template_day_text" style="padding:5px 0 5px 0;width:221px;">
	<input name="board_type" id="board_type" type="radio" checked="checked" value="0" onClick="ajax_action('focus_story_type','DivStoryBoardType','stype=boardtype');" /> Public &nbsp;
    <input name="board_type" id="board_type" type="radio" value="1" onClick="ajax_action('focus_story_type','DivStoryBoardType','stype=boardtype');" /> Private &nbsp;&nbsp;
	</div>
	
    <div class="project_left" id="DivStoryDayType">
    <div class="Story_Title_box">7</div>
    </div>
    <div class="Story_Title_text">Number of Days:</div>
	<input name="dayType" id="dayType" type="hidden" value="Day" />
    <select size="1" id="storydays" name="storydays" onchange="ajax_action('show_storydays','divshow_storydays','stype=storydays&txt_storydays='+document.getElementById('storydays').value+'&dayType='+document.getElementById('dayType').value);" class="storycat" onFocus="ajax_action('focus_story_daytype','DivStoryDayType','stype=DayType')">
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
     <div class="project_left" id="DivStoryTemplate" style="padding-right:0px;">
     <div class="project_left_1">1</div>
	 <div class="project_left_one"><img src="images/one_2.png" alt="" /></div>
     </div>
     <div class="Story_Title_text">Select Story Template :</div>
    
    <div class="Select_Story_Template_main"><!--style="this.style.backgroundColor='#CCCCCC';"-->
      <div id="divDayByDay" class="Select_Story_Template_box_main">
        <div class="Select_Story_Template_day_text">
		<input name="day_type" id="day_type" type="radio" checked="checked" value="1"
		onclick="ajax_action('selDay','divDayHeading','stype=dayselect');"/> Day By Day </div>
        <div class="Select_Story_Template_day_box">day1</div>
        <div class="Select_Story_Template_day_box">day2</div>
        <div class="Select_Story_Template_day_box">day3</div>
      </div>
      
      <div id="divItemByItem" class="Select_Story_Template_box_main">
        <div class="Select_Story_Template_day_text">
		<input name="day_type" id="day_type" type="radio" value="2" 
		onclick="ajax_action('selItem','divDayHeading','stype=itemselect');"/> Item By Item</div>
        <div class="Select_Story_Template_day_box">item1</div>
        <div class="Select_Story_Template_day_box">item2</div>
        <div class="Select_Story_Template_day_box">item3</div>
      </div>
      
      <div id="divStepByStep" class="Select_Story_Template_box_main">
        <div class="Select_Story_Template_day_text">
		<input name="day_type" id="day_type" type="radio" value="3" 
		onclick="ajax_action('selStep','divDayHeading','stype=stepselect');"/> Step By Step</div>
        <div class="Select_Story_Template_day_box">step1</div>
        <div class="Select_Story_Template_day_box">step2</div>
        <div class="Select_Story_Template_day_box">step3</div>
      </div>
      
      <div id="divDateByDate" class="Select_Story_Template_box_main">
        <div class="Select_Story_Template_day_text">
		<input name="day_type" id="day_type" type="radio" value="4"
		onclick="ajax_action('selDate','divDayHeading','stype=dateselect');"/>Narration By Date</div>
        <div class="Select_Story_Template_day_box">date1</div>
        <div class="Select_Story_Template_day_box">date2</div>
        <div class="Select_Story_Template_day_box">date3</div>
      </div>
    </div>
    
    <div class="Select_Story_Template_main">
     <div class="Slides_images"><img src="images/image-day.png" alt="" /></div>
     <div class="Item_By_Item_10day_main">
       <div id="divshow_storytitle" class="Day_Vegan_Challenge_10text">Story Title</div>
       <div class="by_Olivia_Janisch_text">by <?=$userName?></div>
       <div id="divshow_storydesc" style="font-size:12px;">Story Description</div>
       <div id="divshow_storydays" class="Total_10Days_text">Total : <span class="Total_10Days_style">1 Day</span></div>
       <div id="divshow_storyprice" class="Total_10Days_text">Price : $0.00</div>  
       <img src="images/do-it!---images.png" alt="" />             
     </div>
     <div class="Classmates_more_main">
      <span class="Classmates_more_text">Classmates</span>
      <img src="images/classmates-img.png" alt="" />
     </div>
     <div id="divDayHeading" class="Price_Story_Access">
       <div class="price_text">Day 1</div>
       <div class="price_text">Day 2</div>
       <div class="Number_of_Days">Day 3</div>
     </div>	
    </div>    
   </div>
 </form>  
 </div> 
 <div style="clear:both;"></div> 
</div>
<!-- END CUSTOMER HAPPY STORY FORM CODE -->
<?php include('site_footer.php');?> 