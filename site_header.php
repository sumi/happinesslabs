<!DOCTYPE HTML>
<html><head>
<meta charset="utf-8" />
<title>Fun challenges for BIG rewards</title>
<style type="text/css">
<!--
@import url("css/30daysnew_style.css");
@import url("css/common_style.css");
@import url("css/photo_block.css");
@import url("css/happinesslabs_js.css"); 
@import url("css/happinesslabs.css");
body{ background:repeat-x left top #eeeaec;}
-->
</style>
<?php
	$stringVar='';
	if($_GET['rs']==1){
		unset($_SESSION['redirect']);
	}
	if(isset($_SESSION['redirect'])){
		$scriptNameArray=explode("/",$_SESSION['redirect']);
		$lastElement=(count($scriptNameArray)-1);
		$scriptName=$scriptNameArray[$lastElement];
		if($scriptName=="savetag.php"){
			unset($_SESSION['redirect']);
			$stringVar='index_detail.php';
		}else{
			$stringVar=$_SESSION['redirect'];
		}
	}else{
		$stringVar='index_detail.php';
	}
?>
<script src="js/jquery-1.5.1.min.js" type="text/javascript"></script>
<script type="text/javascript" src="js/common.js"></script>
<?php if(SCRIPT_NAME=="index.php"||SCRIPT_NAME=="cherryboard.php"||SCRIPT_NAME=="setup2.php"||SCRIPT_NAME=="expert_cherryboard.php"||SCRIPT_NAME=="experts.php"||SCRIPT_NAME=="gift_profile.php"){ ?>
	<script type="text/javascript" src="js/Ajaxfileupload-jquery-1.3.2.js" ></script>
	<script type="text/javascript" src="js/ajaxupload.3.5.js" ></script>
	<script language="javascript" type="text/javascript">
	$(function(){
			var btnUpload=$('#me');
			var files=$('#files');
			new AjaxUpload(btnUpload,{
				action:'<?=(SCRIPT_NAME=="cherryboard.php"?'uploadPhoto.php':'expert_uploadPhoto.php')?>',
				name: 'uploadfile',
				onComplete: function(file, response){
					document.getElementById('div_up_photo').innerHTML=response;
				}
			});			
		});
	</script>
	<!-- JS for the photo slider -->
	<!-- <script type="text/javascript" src="scripts/autocore_002.js"></script>
	<script type="text/javascript" src="scripts/autocore.js"></script> -->
	<!-- Code for the space issue -->
	<link rel="stylesheet" type="text/css" href="board_slider/style.css" />
	<script type="text/javascript" src="board_slider/jquery.js"></script>

<!-- START PHOTO TAGGING AND MOUSEOVER AND MOUSEOUT EVENT JS CODE -->	
<?php include('include/phototagging.php'); ?>
<!-- END OF PHOTO TAGGING AND MOUSEOVER AND MOUSEOUT EVENT JS CODE -->
<script type="text/javascript" src="js/ddaccordion.js"></script>
<script type="text/javascript" src="js/commentmenu.js"></script>
<?php } ?>
<script type="text/javascript" src="scripts/jquery_002.js"></script>
<script type="text/javascript" src="scripts/jquery.js"></script>
<script type="text/javascript" src="scripts/stickytooltip.js"></script><!-- photo mous hover effect -->
<script type="text/javascript">
	//var k = jQuery.noConflict(); ===> top : 100, 
	$(function() {
		$('a[rel*=leanModal]').leanModal({closeButton: ".modal_close" });		
	});
</script>
<!--AUTO CLICK LEANMODAL SCRIPT
<script type="text/javascript">
	$(function() {
		$('a[rel*=trigger_id]').leanModal({ top : 100, closeButton: ".modal_close" }).trigger('click');		
	});
</script>-->

<script language="javascript">
	var name = "#floatMenu";
	var menuYloc = null;
	
		$(document).ready(function(){
			menuYloc = parseInt($(name).css("top").substring(0,$(name).css("top").indexOf("px")))
			$(window).scroll(function () { 
				offset = menuYloc+$(document).scrollTop()+"px";
				$(name).animate({top:offset},{duration:500,queue:false});
			});
		}); 
	 </script>
<!-- Photo Slider css -->
<link rel="stylesheet" type="text/css" href="board_slider/slider2/style.css" />
<!-- End Header Menu Tooltip -->
</head>
<body>
<!-- START FB LOGIN CODE -->
<div id="fb-root"></div>
<script language="javascript">
window.fbAsyncInit = function() {
    FB.init({
        appId   : '<?php echo APPID?>',
        oauth   : true,
        status  : true, // check login status
        cookie  : true, // enable cookies to allow the server to access the session
        xfbml   : true // parse XFBML
    });
	 FB.Event.subscribe('auth.login', function(response) {
                    // do something with response		
		document.location.href ="<?=$stringVar?>";		
                });
	FB.Event.subscribe('auth.logout', function(response) {
		// do something with response
		document.location.href = "index.php";
	});

 };

function fb_login(){
    FB.login(function(response) {

        if (response.authResponse) {
            console.log('Welcome!  Fetching your information.... ');
            //console.log(response); // dump complete info
            access_token = response.authResponse.accessToken; //get access token
            user_id = response.authResponse.userID; //get FB UID

            FB.api('/me', function(response) {
                user_email = response.email; //get user email
          // you can store this data into your database             
            });

        } else {
            //user hit cancel button
            console.log('User cancelled login or did not fully authorize.');

        }
    }, {
        scope: 'publish_stream,email'
    });
}
function fb_logout() {
        FB.logout(function (response) {
            //Do what ever you want here when logged out like reloading the page
            document.location.href="index.php?tp=logout";
        });
 }
	
(function() {
    var e = document.createElement('script');
    e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
    e.async = true;
    document.getElementById('fb-root').appendChild(e);
}());
</script>
<!-- END FB LOGIN CODE -->

<!--START HEADER CODE-->
<!-- <script type="text/javascript" src="js/dropdown.js"></script> -->
  <!-- START LOGIN USER HEADER SECTION -->
  <?php if(FB_ID>0){ ?>
  <div class="main_bg">
    <div class="main_top">      
      <div class="logo"><a href="index_detail.php"><img src="images/logo_1.png" alt="" /></a></div>		
	   <!-- <div class="text_1">
	   		<div id="sample_attach_menu_parent" class="sample_attach">
			<a href="#">Happiness<br />Bank</a>
			</div>
			<div id="sample_attach_menu_child">
			<a class="sample_attach" href="add_happy_experience.php">Add Happy Experience</a>
			<a class="sample_attach" href="add_unhappy_experience.php">Add Unhappy Experience</a>
			</div>
       </div> -->
       
	   <!-- <div class="tell" style="padding-top: 35px;">
	   		<div id="sample_attach_menu_parent" class="sample_attach">
			<a href="happiness_book.php">My Life<br/>Story Book of<br/>Happy Living</a>
			</div>
			<div id="sample_attach_menu_child">
			<a class="sample_attach_one" rel="leanModal" href="#create_expert_board">My Life Story</a>
			<a class="sample_attach_one" href="customer_happy_story.php">Customer Happy Story</a>
			<a class="sample_attach_one" rel="leanModal" href="#add_story_template">Add Story Template</a>
		    </div>	  
       </div> 
       <div class="tell"><a href="win_rewards.php" title="Happy Rewards For You">University of<br />
                                       Happy Living</a>
       </div>
       <div class="tell"><a href="ask_experts.php" title="Happy Stories To Inspire">Bank of<br />
                                       Happy Living</a>
      </div> -->
	  <div class="tell" style="padding-left:100px">&nbsp;</div>
	  <div class="tell"><a href="happiness_book.php" title="Tools for Heart" class="personPopupTrigger1"><img src="images/heart_icon.png"></a>
	  
       </div>
       <div class="tell"><a href="ask_experts.php" title="Tools for Mind" class="personPopupTrigger"><img src="images/mind_icon.png"></a>
      </div>
        <div class="img_top">
         <div class="img_ima"><a href="index_detail.php"><img src="<?php echo PHOTO_URL;?>" alt="" /></a></div>
         <div class="img_logout"><a href="javascript:void();" onClick="fb_logout();" title="Logout">Logout</a>
		 </div>
        </div>
        <a href="index_detail.php" style="text-decoration:none;color:#e04e32;font-size:14px;">
		 Welcome<br />
         <?php echo FIRST_NAME.'&nbsp;'.LAST_NAME;?></a>
    </div>
    <div style="clear:both"></div>
  </div> 
  <!-- START HOME PAGE (WITHOUT LOGIN USER) HEADER SECTION --> 
  <?php }else{ ?>
 <div class="mine_top_bg">
    <div class="mine_top_index">
      <div class="logo_home"><img src="images/logo_1.png" alt="" /></div>
      <div class="logo_text">&nbsp;</div>
      <div class="tell_mine">
	 <!--  <div class="tell" style="padding-top: 46px;">
			<div id="sample_attach_menu_parent">
			<a href="#">My Life<br/>Story Book of<br/>Happy Living</a>
			</div>
			<div id="sample_attach_menu_child" style="z-index:111;">
			<a <?=(FB_ID>0?'rel="leanModal" href="#create_expert_board"':'href="javascript:void(0);"')?> class="sample_attach_one">My Life Story</a>
			<a class="sample_attach_one" href="customer_happy_story.php">Customer Happy Story</a>
			<a <?=(FB_ID>0?'rel="leanModal" href="#add_story_template"':'href="javascript:void(0);"')?> class="sample_attach_one">Add Story Template</a>
			</div>
      </div>
       <div class="tell"><a href="win_rewards.php" title="Happy Rewards For You">University of<br />
                                       Happy Living</a>
       </div>
       <div class="tell"><a href="ask_experts.php" title="Happy Stories To Inspire">Bank of<br />
                                       Happy Living</a>
      </div> -->
	   <div class="tell" style="padding-left:100px">&nbsp;</div>
	   <div class="tell"><a href="happiness_book.php" title="Tools for Heart"><img src="images/heart_icon.png"></a>
       </div>
       <div class="tell"><a href="ask_experts.php" title="Tools for Mind"><img src="images/mind_icon.png"></a>
      </div>
      </div>
        <div class="facebook">
         <div class="facebook_left"></div>
         <div class="facebook_bottom"><a href="#" onClick="fb_login();">login with facebook</a></div>
         <div class="facebook_right"></div>
        </div>
	 </div>
   <div style="clear:both"></div>
   </div>
  <?php } ?>
<script type="text/javascript">
	at_attach("sample_attach_menu_parent", "sample_attach_menu_child", "hover", "y", "pointer");
</script>
<!--END HEADER CODE-->	
<!-- START EXPERT BOARD CODE AND DIV -->	
<?php
$var='';
$varClose='';
if($_GET['type']=='request'){
	$var='inline';
	$varClose='ask_experts.php';
}else{
	$var='none';
	$varClose='#';
}
?>
<!-- START CREATE HAPPY STORY AND SUB STORY CODE --> 
<form action="expertboard.php" method="post" name="frmexpert" enctype="multipart/form-data">
<input type="hidden" name="create_from" id="create_from" value="header"/>	 
<input type="hidden" name="cherryboard_parent_id" id="cherryboard_parent_id" value="<?=$cherryboard_id?>"/>	 
<div class="CreateStor_main" style="display: <?=$var?>; position: fixed; opacity: 1; z-index: 11000; left: 50%; margin-left: -330px; top: 20px;background-color:#FFFFFF" id="create_expert_board">
  <div class="border_top_main">
       <div class="CreateStor_left">
         <div class="CreateStor_top">
          <div class="CreateStor_img"><img src="<?php echo PHOTO_URL;?>" height="35" width="37" alt="<?php echo FIRST_NAME.'&nbsp;'.LAST_NAME;?>" /></div>
          <div class="CreateStor_text">
		  <?php echo FIRST_NAME.'&nbsp;'.LAST_NAME;?> - Tell A New Happy Story</div>
		   <div class="msg_red" id="div_frm_expmsg"></div>
         </div>
         
         <div class="project_main">
		  <div class="project_left" id="div_story_title">
          <div class="project_left_2">1</div>
		  </div>
          <div class="project_right">Project title: <input name="title" id="title" type="text" onFocus="ajax_action('focus_story_title','div_story_title','stype=title');" style="border:1px solid #818284; color:#999999; font-size:16px; padding:5px 5px 4px 5px; width:250px;"/></div>
         </div>
         
         <div class="project_main">
		  <div class="project_left" id="div_story_category">
          <div class="project_left_2">2</div>
		  </div>
          <div class="project_right">Story catagory: <?=getCategoryList(0,'onFocus="ajax_action(\'focus_story_category\',\'div_story_category\',\'stype=category\');"','category_id1')?>
           </div>
         </div>
         <div class="project_main">
		  <div class="project_left" id="div_story_about">
          <div class="project_left_2">3</div>
		  </div>
          <div class="what_top">What is your happy story about?</div>
          <div class="what_bottom"><textarea name="detail" id="detail" onFocus="ajax_action('focus_story_about','div_story_about','stype=about');" style="color:#999999; font-size:16px; padding:5px 5px 4px 10px; width:340px; height:90px; border:1px solid #818284;"></textarea></div>
         </div>
         <div class="project_main">
		  <div class="project_left" id="div_story_day_type">
          <div class="project_left_2">4</div>
		  </div>
          <div class="how_main">
              <div class="how_box">How will you tell your story?</div>
              <div class="day"><input name="day_type" onClick="ajax_action('focus_story_daytype','div_story_day_type','stype=daytype');javascript:document.getElementById('living_narrative').value='0';document.getElementById('div_day_type').style.display='inline';" id="day_type" type="radio" value="1" /> Day-by-Day</div>
              <div class="day"><input name="day_type" id="day_type" onClick="ajax_action('focus_story_daytype','div_story_day_type','stype=daytype');javascript:document.getElementById('living_narrative').value='0';document.getElementById('div_day_type').style.display='inline';" type="radio" value="2" />Item-By-Item</div>
			  <div class="day"><input name="day_type" id="day_type" onClick="ajax_action('focus_story_daytype','div_story_day_type','stype=daytype');javascript:document.getElementById('living_narrative').value='0';document.getElementById('div_day_type').style.display='inline';" type="radio" value="3" />Step-By-Step</div>
              <div class="day" id="div_day_type" style="display:inline">Approximate number of days/items/steps: <input name="number_days" id="number_days" type="text" value="1" onFocus="ajax_action('focus_story_daytype','div_story_day_type','stype=daytype');" style="border:1px solid #818284; color:#999999; font-size:16px; padding:4px 5px 3px 5px; width:80px;"/></div>
              <div class="day">
			  <input id="living_narrative" name="living_narrative" type="hidden" value="0"/>
			  <input id="day_type" name="day_type" onClick="ajax_action('focus_story_daytype','div_story_day_type','stype=daytype');javascript:document.getElementById('living_narrative').value='1';document.getElementById('div_day_type').style.display='none';" type="radio" value="4"/> living narrative (By dates entered)</div>
           </div>
            <div style="clear:both"></div>
         </div>
          <div class="project_main">
		   <div class="project_left" id="div_story_board_price">
           <div class="project_left_2">5</div>
		   </div>
           <div class="storyboard_main">
            <div class="storyboard_left">Storyboard price?</div>
            <div class="storyboard_left">
			 <input id="is_board_price" name="is_board_price" type="hidden" value="0"/>
<input name="chk_is_board_price" id="chk_is_board_price" type="radio" value="1" onClick="ajax_action('focus_story_price','div_story_board_price','stype=boardprice');javascript:document.getElementById('divPrice').style.display='inline';document.getElementById('is_board_price').value='1';" />            
Price board</div>
            <div class="storyboard_left"><input name="chk_is_board_price" id="chk_is_board_price" type="radio" value="0" onClick="ajax_action('focus_story_price','div_story_board_price','stype=boardprice');javascript:document.getElementById('divPrice').style.display='none';document.getElementById('is_board_price').value='0';" /> 
            Non-price board</div><br/>
			 <div style="display:none;padding-left:150px;" id="divPrice">
				<Strong>Price :</Strong>
                <input type="text" name="price" id="price" value="0" style="width:50px;">	
				</div>
           </div>
          </div>
          <div class="project_main">
		   <div class="project_left" id="div_story_board_type">
           <div class="project_left_2">6</div>
		   </div>
           <div class="storyboard_main">
            <div class="storyboard_left">Storyboard type?</div>
            <div class="storyboard_left"><input name="board_type" id="board_type" type="radio" onClick="ajax_action('focus_story_type','div_story_board_type','stype=boardtype');" value="0" /> 
            Public</div>
            <div class="storyboard_left"><input name="board_type" id="board_type" type="radio" onClick="ajax_action('focus_story_type','div_story_board_type','stype=boardtype');" value="1" /> 
            Private</div>
           </div>
         </div>
       <div style="clear:both"></div>
    </div>
       <div class="CreateStor_right">
         <div class="CreateStor_top_img"><a class="modal_close" href="<?=$varClose?>" title="close"></a></div>
         <div class="sample_text" style="width:270px;">Sample Page</div>
         <div class="happiness_img"><img src="images/img_screen.png" alt="" /></div>
         <div class="create_left">
          <div class="create_left_bg">
		  <input type="hidden" name="btnCreateExpert" id="btnCreateExpert" value="Create">
		  <a href="javascript:document.frmexpert.submit();" onClick="javascript:return CheckFormValidation('div_frm_expmsg','title#Enter Title,category_id1#Select Category#0,detail#Enter detail#Enter detail,day_type#Please Select Days/Items/Steps,chk_is_board_price#Please Select Your Board Price ,number_days#Please Enter Days/Items/Steps#0,price#Please Enter Price#0,board_type#Please Select Board Type');">create!</a></div>
          <div class="create_left_img"><img src="images/ban.png" alt=""  height="48"/></div> 
         <div style="clear:both"></div>
         </div>
         <div class="create_right"><img src="images/img_1.png" alt="" /></div>
    </div>
       <div style="clear:both"></div>
  </div>
  <div style=" clear:both"></div>
</div>
</form>
<!-- END CREATE HAPPY STORY AND SUB STORY CODE -->
<!-- START CREATE STORY TEMLATES --> 
<form action="expertboard.php" method="post" name="frmexpert1" enctype="multipart/form-data">
<input type="hidden" name="create_from" id="create_from" value="header"/>	 
<input type="hidden" name="cherryboard_parent_id" id="cherryboard_parent_id" value="<?=$cherryboard_id?>"/>	 
<div class="CreateStor_main" style="display: <?=$var?>; position: fixed; opacity: 1; z-index: 11000; left: 50%; margin-left: -330px; top: 20px;background-color:#FFFFFF" id="add_story_template">
  <div class="border_top_main">
       <div class="CreateStor_left">
         <div class="CreateStor_top">
          <div class="CreateStor_img"><img src="<?php echo PHOTO_URL;?>" height="35" width="37" alt="<?php echo FIRST_NAME.'&nbsp;'.LAST_NAME;?>" /></div>
          <div class="CreateStor_text">
		  <?php echo FIRST_NAME.'&nbsp;'.LAST_NAME;?> - Add Story Template</div>
		   <div class="msg_red" id="div_frm_expmsg"></div>
         </div>
         
         <div class="project_main">
		  <div class="project_left" id="div_story_title">
          <div class="project_left_2">1</div>
		  </div>
          <div class="project_right">Project title: <input name="title" id="title" type="text" onFocus="ajax_action('focus_story_title','div_story_title','stype=title');" style="border:1px solid #818284; color:#999999; font-size:16px; padding:5px 5px 4px 5px; width:250px;"/></div>
         </div>
         
         <div class="project_main">
		  <div class="project_left" id="div_story_category">
          <div class="project_left_2">2</div>
		  </div>
          <div class="project_right">Story catagory: <?=getCategoryList(0,'onFocus="ajax_action(\'focus_story_category\',\'div_story_category\',\'stype=category\');"','category_id1')?>
           </div>
         </div>
         <div class="project_main">
		  <div class="project_left" id="div_story_about">
          <div class="project_left_2">3</div>
		  </div>
          <div class="what_top">What is your happy story about?</div>
          <div class="what_bottom"><textarea name="detail" id="detail" onFocus="ajax_action('focus_story_about','div_story_about','stype=about');" style="color:#999999; font-size:16px; padding:5px 5px 4px 10px; width:340px; height:90px; border:1px solid #818284;"></textarea></div>
         </div>
         <div class="project_main">
		  <div class="project_left" id="div_story_day_type">
          <div class="project_left_2">4</div>
		  </div>
          <div class="how_main">
              <div class="how_box">How will you tell your story?</div>
              <div class="day"><input name="day_type" onClick="ajax_action('focus_story_daytype','div_story_day_type','stype=daytype');javascript:document.getElementById('living_narrative').value='0';document.getElementById('div_day_type').style.display='inline';" id="day_type" type="radio" value="1" /> Day-by-Day</div>
              <div class="day"><input name="day_type" id="day_type" onClick="ajax_action('focus_story_daytype','div_story_day_type','stype=daytype');javascript:document.getElementById('living_narrative').value='0';document.getElementById('div_day_type').style.display='inline';" type="radio" value="2" />Item-By-Item</div>
			  <div class="day"><input name="day_type" id="day_type" onClick="ajax_action('focus_story_daytype','div_story_day_type','stype=daytype');javascript:document.getElementById('living_narrative').value='0';document.getElementById('div_day_type').style.display='inline';" type="radio" value="3" />Step-By-Step</div>
              <div class="day" id="div_day_type" style="display:inline">Approximate number of days/items/steps: <input name="number_days" id="number_days" type="text" value="1" onFocus="ajax_action('focus_story_daytype','div_story_day_type','stype=daytype');" style="border:1px solid #818284; color:#999999; font-size:16px; padding:4px 5px 3px 5px; width:80px;"/></div>
              <div class="day">
			  <input id="living_narrative" name="living_narrative" type="hidden" value="0"/>
			  <input id="day_type" name="day_type" onClick="ajax_action('focus_story_daytype','div_story_day_type','stype=daytype');javascript:document.getElementById('living_narrative').value='1';document.getElementById('div_day_type').style.display='none';" type="radio" value="4"/> living narrative (By dates entered)</div>
           </div>
            <div style="clear:both"></div>
         </div>
          <div class="project_main">
		   <div class="project_left" id="div_story_board_price">
           <div class="project_left_2">5</div>
		   </div>
           <div class="storyboard_main">
            <div class="storyboard_left">Storyboard price?</div>
            <div class="storyboard_left">
			 <input id="is_board_price" name="is_board_price" type="hidden" value="0"/>
<input name="chk_is_board_price" id="chk_is_board_price" type="radio" value="1" onClick="ajax_action('focus_story_price','div_story_board_price','stype=boardprice');javascript:document.getElementById('divPrice').style.display='inline';document.getElementById('is_board_price').value='1';" />            
Price board</div>
            <div class="storyboard_left"><input name="chk_is_board_price" id="chk_is_board_price" type="radio" value="0" onClick="ajax_action('focus_story_price','div_story_board_price','stype=boardprice');javascript:document.getElementById('divPrice').style.display='none';document.getElementById('is_board_price').value='0';" /> 
            Non-price board</div><br/>
			 <div style="display:none;padding-left:150px;" id="divPrice">
				<Strong>Price :</Strong>
                <input type="text" name="price" id="price" value="0" style="width:50px;">	
				</div>
           </div>
          </div>
          <div class="project_main">
		   <div class="project_left" id="div_story_board_type">
           <div class="project_left_2">6</div>
		   </div>
           <div class="storyboard_main">
            <div class="storyboard_left">Storyboard type?</div>
            <div class="storyboard_left"><input name="board_type" id="board_type" type="radio" onClick="ajax_action('focus_story_type','div_story_board_type','stype=boardtype');" value="0" /> 
            Public</div>
            <div class="storyboard_left"><input name="board_type" id="board_type" type="radio" onClick="ajax_action('focus_story_type','div_story_board_type','stype=boardtype');" value="1" /> 
            Private</div>
           </div>
         </div>
       <div style="clear:both"></div>
    </div>
       <div class="CreateStor_right">
         <div class="CreateStor_top_img"><a class="modal_close" href="<?=$varClose?>" title="close"></a></div>
         <div class="sample_text" style="width:270px;">Sample Page</div>
         <div class="happiness_img"><img src="images/img_screen.png" alt="" /></div>
         <div class="create_left">
          <div class="create_left_bg">
		  <input type="hidden" name="btnCreateExpert11" id="btnCreateExpert111" value="Create">
		  <a href="javascript:document.frmexpert.submit();" onClick="javascript:return CheckFormValidation('div_frm_expmsg','title#Enter Title,category_id1#Select Category#0,detail#Enter detail#Enter detail,day_type#Please Select Days/Items/Steps,chk_is_board_price#Please Select Your Board Price ,number_days#Please Enter Days/Items/Steps#0,price#Please Enter Price#0,board_type#Please Select Board Type');">create!</a></div>
          <div class="create_left_img"><img src="images/ban.png" alt=""  height="48"/></div> 
         <div style="clear:both"></div>
         </div>
         <div class="create_right"><img src="images/img_1.png" alt="" /></div>
    </div>
       <div style="clear:both"></div>
  </div>
  <div style=" clear:both"></div>
</div>
</form>
<!-- END CREATE STORY TEMLATES --> 