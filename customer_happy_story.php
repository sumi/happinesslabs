<?php
include_once "fbmain.php";
include('include/app-common-config.php');
?>
<?php include('site_header.php'); ?>
<!-- START CUSTOMER HAPPY STORY FORM CODE -->
<div id="main">
	<div id="wrapper" style="padding-top:10px;">
	<form action="expertboard.php" method="post" name="frmcuststory" enctype="multipart/form-data">
		<div class="border_top_main">
			<div class="CreateStor_left" style="padding-left:100px;">
				<div class="CreateStor_top">
					<div class="CreateStor_img"><img src="<?php echo PHOTO_URL;?>" height="35" width="37" alt="<?php echo FIRST_NAME.'&nbsp;'.LAST_NAME;?>" /></div>
					<div class="CreateStor_text" style="padding-top:3px;">
					<?php echo FIRST_NAME.'&nbsp;'.LAST_NAME;?> - Tell A New Happy Story</div>
					<div class="msg_red" id="div_frm_storymsg"></div>
         		</div>
				<div class="project_main">
				  <div class="project_left" id="DivStoryTitle">
				  <div class="project_left_2">1</div>
				  </div>
				  <div class="project_right">Project title: <input name="story_title" id="story_title" type="text" onFocus="ajax_action('focus_story_title','DivStoryTitle','stype=title');" style="border:1px solid #818284; color:#999999; font-size:16px; padding:5px 5px 4px 5px; width:250px;"/></div>
				</div>
				<div class="project_main">
			  		<div class="project_left" id="DivStoryCategory">
			  		<div class="project_left_2">2</div>
			  		</div>
			  		<div class="project_right">Story catagory: <?=getCategoryList(0,'onFocus="ajax_action(\'focus_story_category\',\'DivStoryCategory\',\'stype=category\');"','category_id2')?>
			   		</div>
				</div>
				<div class="project_main">
				  <div class="project_left" id="DivStoryAbout">
				  <div class="project_left_2">3</div>
				  </div>
				  <div class="what_top">What is your happy story about?</div>
				  <div class="what_bottom"><textarea name="story_detail" id="story_detail" onFocus="ajax_action('focus_story_about','DivStoryAbout','stype=about');" style="color:#999999; font-size:16px; padding:5px 5px 4px 10px; width:340px; height:90px; border:1px solid #818284;"></textarea></div>
				</div>
				<div class="project_main">
				  <div class="project_left" id="DivStoryDayType">
				  <div class="project_left_2">4</div>
				  </div>
				  <div class="how_main" style="float:left;">
					  <div class="how_box">How will you tell your story?</div>
					  <div class="day"><input name="day_type" onClick="ajax_action('focus_story_daytype','DivStoryDayType','stype=daytype');javascript:document.getElementById('living_narrative').value='0';document.getElementById('DivDayType').style.display='inline';" id="day_type" type="radio" value="1" /> Day-by-Day</div>
					  <div class="day"><input name="day_type" id="day_type" onClick="ajax_action('focus_story_daytype','DivStoryDayType','stype=daytype');javascript:document.getElementById('living_narrative').value='0';document.getElementById('DivDayType').style.display='inline';" type="radio" value="2" />Item-By-Item</div>
					  <div class="day"><input name="day_type" id="day_type" onClick="ajax_action('focus_story_daytype','DivStoryDayType','stype=daytype');javascript:document.getElementById('living_narrative').value='0';document.getElementById('DivDayType').style.display='inline';" type="radio" value="3" />Step-By-Step</div>
					  <div class="day" id="DivDayType" style="display:inline">Approximate number of days/items/steps: <input name="number_days" id="number_days" type="text" value="1" onFocus="ajax_action('focus_story_daytype','DivStoryDayType','stype=daytype');" style="border:1px solid #818284; color:#999999; font-size:16px; padding:4px 5px 3px 5px; width:80px;"/></div>
					  <div class="day">
					  <input id="living_narrative" name="living_narrative" type="hidden" value="0"/>
					  <input id="day_type" name="day_type" onClick="ajax_action('focus_story_daytype','DivStoryDayType','stype=daytype');javascript:document.getElementById('living_narrative').value='1';document.getElementById('DivDayType').style.display='none';" type="radio" value="4"/> living narrative (By dates entered)</div>
				   </div>
					<div style="clear:both"></div>
				 </div>
				 <div class="project_main">
				   <div class="project_left" id="DivStoryBoardPrice">
				   <div class="project_left_2">5</div>
				   </div>
				   <div class="storyboard_main">
					<div class="storyboard_left">Storyboard price?</div>
					<div class="storyboard_left">
					 <input id="IsBoardPrice" name="IsBoardPrice" type="hidden" value="0"/>
		<input name="chk_is_board_price" id="chk_is_board_price" type="radio" value="1" onClick="ajax_action('focus_story_price','DivStoryBoardPrice','stype=boardprice');javascript:document.getElementById('div_price').style.display='inline';document.getElementById('IsBoardPrice').value='1';" />            
		Price board</div>
					<div class="storyboard_left"><input name="chk_is_board_price" id="chk_is_board_price" type="radio" value="0" onClick="ajax_action('focus_story_price','DivStoryBoardPrice','stype=boardprice');javascript:document.getElementById('div_price').style.display='none';document.getElementById('IsBoardPrice').value='0';" /> 
					Non-price board</div><br/>
					 <div style="display:none;padding-left:150px;" id="div_price">
						<Strong>Price :</Strong>
						<input type="text" name="story_price" id="story_price" onFocus="ajax_action('focus_story_price','DivStoryBoardPrice','stype=boardprice');" value="0" style="width:50px;">	
					  </div>
				   </div>
				  </div><br/>
				  <div class="project_main">
				   <div class="project_left" id="DivStoryBoardType">
				   <div class="project_left_2">6</div>
				   </div>
				   <div class="storyboard_main">
					<div class="storyboard_left">Storyboard type?</div>
					<div class="storyboard_left"><input name="board_type" id="board_type" type="radio" onClick="ajax_action('focus_story_type','DivStoryBoardType','stype=boardtype');" value="0" /> 
					Public</div>
					<div class="storyboard_left"><input name="board_type" id="board_type" type="radio" onClick="ajax_action('focus_story_type','DivStoryBoardType','stype=boardtype');" value="1" /> 
					Private</div>
				    </div>
				   </div>				
			</div><!-- End of story left -->
			<div class="CreateStor_right">
				 <div class="sample_text" style="width:270px;">Sample Page</div>
				 <div class="happiness_img"><img src="images/img_screen.png" alt="" /></div>
				 <div class="create_left" style="padding-left:587px;">
				  <div class="create_left_bg">
				  <input type="hidden" name="btnCreateStory" id="btnCreateStory" value="Create">
				  <a href="javascript:document.frmcuststory.submit();" onClick="javascript:return CheckFormValidation('div_frm_storymsg','story_title#Enter Story Title,category_id2#Select Category#0,story_detail#Enter story about#Enter story about,day_type#Please Select Days/Items/Steps,chk_is_board_price#Please Select Your Board Price ,number_days#Please Enter Days/Items/Steps#0,story_price#Please Enter Price#0,board_type#Please Select Board Type');">create!</a></div>
				  <div class="create_left_img"><img src="images/ban.png" alt=""  height="48"/></div> 
				 <div style="clear:both"></div>
				 </div>
				 <div class="create_right"><img src="images/img_1.png" alt="" /></div>
			</div>
		<div style="clear:both"></div>
		</div>
	</form>
	<br/><br/><br/>
	</div>
    <div class="clear"></div>
</div>
<!-- END CUSTOMER HAPPY STORY FORM CODE -->
<?php include('site_footer.php');?> 