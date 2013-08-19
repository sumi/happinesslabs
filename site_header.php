<!DOCTYPE HTML>
<html>
<head>
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
		$stringVar=$_SESSION['redirect'];
	}else{
		$stringVar='index_detail.php';
	}
?>
<script type="text/javascript" src="js/common.js"></script>
<?php if(SCRIPT_NAME=="index.php"||SCRIPT_NAME=="cherryboard.php"||SCRIPT_NAME=="setup2.php"||SCRIPT_NAME=="expert_cherryboard.php"||SCRIPT_NAME=="experts.php"||SCRIPT_NAME=="gift_profile.php"){ ?>
	<script type="text/javascript" src="js/Ajaxfileupload-jquery-1.3.2.js" ></script>
	<script type="text/javascript" src="js/ajaxupload.3.5.js" ></script>
	<script language="javascript" type="text/javascript">
	$(function(){
			var btnUpload=$('#me');
			var files=$('#files');
			new AjaxUpload(btnUpload, {
				action:'<?=(SCRIPT_NAME=="cherryboard.php"?'uploadPhoto.php':'expert_uploadPhoto.php')?>',
				name: 'uploadfile',
				onComplete: function(file, response){
					document.getElementById('div_up_photo').innerHTML=response;
				}
			});
			
		});
	</script>
	<!-- JS for the photo slider -->
	<script type="text/javascript" src="scripts/autocore_002.js"></script>
	<script type="text/javascript" src="scripts/autocore.js"></script>
	<!-- Code for the space issue -->
	<link rel="stylesheet" type="text/css" href="board_slider/style.css" />
	<script type="text/javascript" src="board_slider/jquery.js"></script>
	<!-- JS For The Images Mouse Over & Mouse Out -->
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js">
	</script>
	<script type="text/javascript" src="js/ddaccordion.js"></script>
	<script type="text/javascript">
	ddaccordion.init({
		headerclass: "silverheader", //Shared CSS class name of headers group
		contentclass: "submenu", //Shared CSS class name of contents group
		revealtype: "mouseover", //Reveal content when user clicks or onmouseover the header? Valid value: "click", "clickgo", or "mouseover"
		mouseoverdelay: 200, //if revealtype="mouseover", set delay in milliseconds before header expands onMouseover
		collapseprev: true, //Collapse previous content (so only one open at any time)? true/false
		defaultexpanded: [0], //index of content(s) open by default [index1, index2, etc] [] denotes no content
		onemustopen: true, //Specify whether at least one header should be open always (so never all headers closed)
		animatedefault: false, //Should contents open by default be animated into view?
		persiststate: true, //persist state of opened contents within browser session?
		toggleclass: ["", "selected"], //Two CSS classes to be applied to the header when it's collapsed and expanded, respectively ["class1", "class2"]
		togglehtml: ["", "", ""], //Additional HTML added to the header when it's collapsed and expanded, respectively  ["position", "html1", "html2"] (see docs)
		animatespeed: "fast", //speed of animation: integer in milliseconds (ie: 200), or keywords "fast", "normal", or "slow"
		oninit:function(headers, expandedindices){ //custom code to run when headers have initalized
			//do nothing
		},
		onopenclose:function(header, index, state, isuseractivated){ //custom code to run whenever a header is opened or closed
			//do nothing
		}
	})
</script>
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
<?php if(SCRIPT_NAME=="index.php"){ ?>
<script type="text/javascript" src="scripts/jquery.js"></script>
<script src="scripts/modernizr.js"></script>
<script>window.jQuery || document.write('<script src="scripts/jquery-1.7.min.js">\x3C/script>')</script>
<script defer src="scripts/jquery.flexslider.js"></script>
  <script type="text/javascript">
    $(function(){
      SyntaxHighlighter.all();
    });
    $(window).load(function(){
      $('.flexslider').flexslider({
        animation: "slide",
        start: function(slider){
          $('body').removeClass('loading');
        }
      });
    });
  </script>
  <style type="text/css">
  body,td,th 		{ font-size: 14px; font-family: Arial, Helvetica, sans-serif; margin:0px; color:#333333; background:#F6F4F4;}
  </style>
<?php } ?>
<?php if(SCRIPT_NAME=="gift_profile.php"){ ?>
<!--[if IE]><script language="javascript" type="text/javascript" src="./excanvas.js"></script><![endif]-->
		<!-- <link rel="stylesheet" type="text/css" href="graph/jquery.jqplot.css" />
		<script language="javascript" type="text/javascript" src="graph/jquery-1.3.2.min.js"></script>
		<script language="javascript" type="text/javascript" src="graph/jquery.jqplot.js"></script>
		<script language="javascript" type="text/javascript" src="graph/plugins/jqplot.canvasTextRenderer.js"></script>
		<script language="javascript" type="text/javascript" src="graph/plugins/jqplot.canvasAxisTickRenderer.js"></script>
		<script language="javascript" type="text/javascript" src="graph/plugins/jqplot.dateAxisRenderer.js"></script>
		-->
		<!-- END: load jqplot -->

	<?php } ?>
<!-- Photo Slider css -->
<link rel="stylesheet" type="text/css" href="board_slider/slider2/style.css" />
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

<!-- END  FB LOGIN CODE -->
<!--Header Start-->
<?php if(SCRIPT_NAME=="index.php"||SCRIPT_NAME=="expert_cherryboard.php"||SCRIPT_NAME=="ask_experts.php"||SCRIPT_NAME=="win_rewards.php"||SCRIPT_NAME=="index_detail.php"){ ?>
<!-- START MAIN TOP SECTION -->
  <?php if(FB_ID>0){ ?>
  <div class="main_bg">
    <div class="main_top">      
      <div class="logo"><a href="index_detail.php"><img src="images/logo_1.png" alt="" /></a></div>
       <div class="text_1"><a rel="leanModal" href="#create_expert_board" title="Tell a Happy Story">Tell a<br />
                                       Happy <br />
                                       Story</a>
       </div>
       <div class="text_1"><a href="win_rewards.php" title="Happy Rewards For You">Happy <br />
                                       Rewards<br />
                                       For You</a>
       </div>
       <div class="text_1"><a href="ask_experts.php" title="Happy Stories To Inspire">Happy <br />
                                       Stories<br />
                                       To Inspire</a>
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
  <?php }else{ ?>
 <div class="mine_top_bg">
    <div class="mine_top_index">
      <div class="logo_home"><img src="images/logo_1.png" alt="" /></div>
      <div class="logo_text">&nbsp;</div>
      <div class="tell_mine">
      <div class="tell">
      <a <?=(FB_ID>0?'rel="leanModal" href="#create_expert_board"':'href="index_detail.php"')?> title="Tell a Happy Story">Tell a<br />Happy<br />Story</a>
      </div>
      <div class="tell">
      <a href="win_rewards.php" title="Win Rewards">Win<br />Rewards</a>
      </div>
         
      <div class="tell">
      <a href="ask_experts.php" title="Happy Stories">Happy<br />Stories</a>
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
      
<?php }else{ ?>
	<div id="header">
	<div id="header_cherryboard">
		<div id="link_container" style="top: 20px;display:none">
		<?php if(FB_ID>0){ ?>
		<table>
		<tr><td valign="top">
		<a href="gifts.php?type=gift" title="Rewards" style="color:#000000">Rewards</a><br/>
		<a href="expertboard.php" title="Challenges" style="color:#000000">Challenges</a><!--Experts-->
		</td>
		<td>
		<a rel="leanModal" href="#add_new_compaign" title="Create Reward" style="color:#000000">Create Reward</a>
		<br/>
		<a rel="leanModal" href="#create_expert_board" title="Create Challenge" style="color:#000000">Create Challenge</a>
		<!-- <br/>
		<a rel="leanModal" href="#create_goal_board" title="Create Goal Board" style="color:#000000;">Create Goal</a> -->
		</td>
		<td valign="top">
		<a href="gifts.php?type=campaign" title="My Rewards" style="color:#000000;">My Rewards</a>
		<br/>
		<a href="expertboard.php?type=expert" title="My Challenges" style="color:#000000;">My Challenges</a>
		</td>
		</tr>
		</table>
		<?php } ?>
		</div>
    	<div class="logo" style="margin:0px;padding-top:0px;"><a href="index_detail.php">
		<img src="images/logo_new_small.png" alt="30daysnew" title="30daysnew"></a></div>
		<div id="link_container" style="margin-left:469px;top: 19px;">
		<table>
		<tr>
		<td valign="top" align="left" style="background-color:#B2B3B1">
		<a <?=(FB_ID>0?'rel="leanModal" href="#create_expert_board"':'href="index_detail.php"')?> title="Create Create Story" style="color:#000000;padding:0px;font-weight:normal;font-size:17px;">Tell<br/>Happy Story</a>
		</td>
		<td style="background-color:#B2B3B1">&nbsp;<img src="images/v-line.png" height="40px" width="3px">&nbsp;</td>
		<td valign="top" align="left" style="background-color:#B2B3B1">
		<a href="win_rewards.php" title="Win Rewards" style="color:#000000;padding:0px;font-weight:normal;font-size:17px">Happy<br/>Rewards</a>
		</td>
		<td style="background-color:#B2B3B1">&nbsp;<img src="images/v-line.png" height="40px" width="3px">&nbsp;</td>
		<td valign="top" align="left" style="background-color:#B2B3B1">
		<a href="ask_experts.php" title="Ask Expert" style="color:#000000;padding:0px;font-weight:normal;font-size:17px">Happy<br/>Stories</a>
		</td>
		</tr>
		</table>
		</div>
		
		 <?php if(FB_ID>0){ ?>
		<div style="float:right; position:absolute; top:-5px; color:#FFFFFF; font-size:15px; margin:17px 0px 3px 844px;"><a href="#" onClick="fb_logout()" class="red_link_14 smalltext1" style="color:#F84503"><strong>Logout</strong></a></div>
		
		<div class="userbox"><a href="index_detail.php"><img src="<?php echo PHOTO_URL;?>"></a><br/>
		<a href="index_detail.php" title="Profile" style="color:#000000;padding:0px;font-weight:normal;font-size:13px;text-decoration:none"><?php echo FIRST_NAME.'&nbsp;'.LAST_NAME;?></a>
		</div>
	
		<?php  }else{ ?>
		<a href="#" class="btn_blue right" onClick="fb_login();">login with facebook</a>
		<?php } ?>
	</div>
	</div>
<?php } ?>	
<!--Header End-->
<!-- START ADD CAMPAIGN CODE AND DIV -->	
<form action="gifts.php" method="post" name="frmgift" enctype="multipart/form-data">
	<div style="display: none; position: fixed; opacity: 1; z-index: 11000; left: 50%; margin-left: -330px; top: 100px;height:460px;overflow:auto;" id="add_new_compaign" class="popup_div">
		        <a class="modal_close" href="#" title="close"></a>
                <div class="msg_red" id="div_frm_msg"></div>
				<div align="center" class="head_20">Create Reward</div><br>
                <div class="red_circle">1</div><strong>Campaign Title</strong>:
	<input type="text" name="campaign_title" id="campaign_title" value="" style="margin-bottom:5px;margin-left:11px;"><br>
	<div class="red_circle">2</div><strong>Campaign Detail</strong>:
    <textarea id="campaign_detail"  name="campaign_detail" class="search_1" style="height:35px;width:300px;    margin-bottom:5px;vertical-align:top" onFocus="if(this.value=='Enter campaign detail') this.value='';" onBlur="if(this.value=='') this.value='Enter campaign detail';">Enter campaign detail</textarea><br>
				<div class="red_circle">3</div><strong>Category</strong>:&nbsp;<span style="margin-left:51px;"><?=getCategoryList()?></span>
				<br><br>
				<div class="red_circle">4</div><strong>Campaign Type</strong>:&nbsp;&nbsp;<input type="radio" name="campaign_type" id="campaign_type" checked="checked" value="1">&nbsp;Private&nbsp;&nbsp;<input type="radio" name="campaign_type" id="campaign_type" value="0">&nbsp;Public
				<br><br>
	            <div class="red_circle">5</div><strong>Sponsor</strong>:&nbsp;&nbsp;&nbsp;
                <input type="radio" name="sponsor" id="sponsor" value="1" checked="checked" style="margin-left:41px;">&nbsp;I want to find sponsor for this reward.&nbsp;&nbsp;<br/><input type="radio" name="sponsor" id="sponsor" value="2" style="margin-left:120px;">&nbsp;I want to sponsor this reward&nbsp;<br/><input type="radio" name="sponsor" id="sponsor" value="3" style="margin-left:153px;">&nbsp;I want to reward this to myself 
				 <br><br>
				<div class="red_circle">6</div><strong>Sponsored by</strong>:
         <input type="text" name="sponsor_name" id="sponsor_name" value="" style="margin-left:18px;margin-bottom:5px;width:170px;"><br>
				 <div class="red_circle">7</div><strong>Sponsor url</strong>:
              <input type="text" name="sponsorship_url" id="sponsorship_url" value="" style="margin-left:32px;margin-bottom:5px;"><br>
				 <div class="red_circle">8</div><strong>Sponsor logo</strong>&nbsp;:&nbsp;
                <input name="sponsor_logo" id="sponsor_logo" type="file" style="margin-left:12px;margin-bottom:5px;"><br>
				 <br>
				 <div class="red_circle">9</div><strong>Number of days</strong>:
               <input type="text" name="goal_days" id="goal_days" value="" style="margin-bottom:5px;width:50px;margin-left:5px;"><br>
				 <div class="red_circle">10</div><strong>No. of strikes</strong>:
               <input type="text" name="miss_days" id="miss_days" value="" style="width:50px;margin-bottom:5px;margin-left:14px;"><br>
				<div class="red_circle">11</div><strong>Reward title</strong>:
<input type="text" name="gift_title" id="gift_title" value="" style="margin-left:24px;margin-bottom:5px;"><br>
   	            <div class="red_circle">12</div><strong>Reward photo</strong>&nbsp;:&nbsp;
           <input name="gift_photo" id="gift_photo" type="file" style="margin-left:2px;margin-bottom:10px;"><br>
				<input type="submit" class="btn_small right" id="btnAddCampaign" onClick="javascript:return CheckFormValidation('div_frm_msg','campaign_title#Enter Campaign Title,campaign_detail#Enter campaign detail#Enter campaign detail,category_id#Select Category#0,sponsor_name#Enter Sponsor By,sponsorship_url#Enter valid url#,sponsor_logo#Select Sponsor Logo#,goal_days#Enter Number Of Days#,miss_days#Enter Number Of Strikes #,gift_title#Enter Reward Title,gift_photo#Select Reward Photo#');" value="Add Reward" name="btnAddCampaign" />
  </div>
  </form>
<!-- END ADD CAMPAIGN CODE AND DIV -->	
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
<input type="hidden" name="parent_id" id="parent_id" value="<?=$expertboard_id?>"/>	 
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

<!-- <form action="expertboard.php" method="post" name="frmexpert11" enctype="multipart/form-data">
	<div style="display: <?=$var?>; position: fixed; opacity: 1; z-index: 11000; left: 50%; margin-left: -330px; top: 100px;" id="create_expert_board1" class="popup_div">
		        <a class="modal_close" href="<?=$varClose?>" title="close"></a>
                <div class="msg_red" id="div_frm_expmsg"></div>
				<div align="center" class="head_20">Tell Happy Story</div><br>
				<input type="hidden" name="create_from" id="create_from" value="header"/>	 
				<input type="hidden" name="parent_id" id="parent_id" value="<?=$expertboard_id?>"/>	 
                <div class="red_circle">1</div><strong>Title</strong>:
				<input type="text" name="title" id="title" value="" style="margin-bottom:5px;margin-left:75px;">
				<br>
				<div class="red_circle">2</div><strong>Detail</strong>:&nbsp;
				<textarea id="detail"  name="detail" class="search_1" style="height:35px;width:300px;margin-bottom:5px;vertical-align:top;margin-left:62px;padding: 1px 1px;" onFocus="if(this.value=='Enter detail') this.value='';" onBlur="if(this.value=='') this.value='Enter detail';">Enter detail</textarea><br>
				<div class="red_circle">3</div><strong>Category</strong>:&nbsp;<span style="margin-left:44px;"><?=getCategoryList(0,'','category_id1')?></span><br><br>
				<div class="red_circle">4</div><strong>Board</strong>:&nbsp;<span style="margin-left:61px;">
				<input type="radio" onClick="javascript:document.getElementById('divDay').innerHTML='Days';" checked="checked" value="1" name="day_type" id="day_type">Day board&nbsp;&nbsp;<input type="radio" onClick="javascript:document.getElementById('divDay').innerHTML='Items';" value="2" name="day_type" id="day_type">Item board
				</span><br><br>
				<span style="margin-left:30px;"><strong>Number of <span id="divDay">Days</span></strong>:
               	<input type="text" name="number_days" id="number_days" value="" style="margin-bottom:5px;width:50px;"></span><br>
				 <div class="red_circle">5</div><strong>Board Price</strong>:
               <span style="margin-left:24px;">	<input type="radio" value="1" name="is_board_price" id="is_board_price" onClick="javascript:document.getElementById('divPrice').style.display='inline';">Price board&nbsp;&nbsp;<input type="radio" value="0" checked="checked" name="is_board_price" id="is_board_price" onClick="javascript:document.getElementById('divPrice').style.display='none';">Non-price board</span><br><br>
			    <div style="margin-left:34px;display:none" id="divPrice">
				<strong>Price</strong>:
                <input type="text" name="price" id="price" value="0" style="width:50px;margin-bottom:5px;margin-left:73px;">	
				<br>
				</div>
			    <div class="red_circle">6</div><strong>Board Type</strong>:
               	<span style="margin-left:29px;"><input type="radio" value="0" name="board_type" checked="checked" id="board_type">Public&nbsp;&nbsp;<input type="radio" value="1" name="board_type" id="board_type">Private</span><br>
				<input type="submit" class="btn_small right" id="btnCreateExpert" onClick="javascript:return CheckFormValidation('div_frm_expmsg','title#Enter Title,category_id1#Select Category#0,detail#Enter detail#Enter detail,number_days#Enter Number Of Days,price#Please Enter Price#');" value="Create" name="btnCreateExpert" />
  </div>
  </form>-->
<!-- END CREATE EXPERT BOARD CODE AND DIV  -->	

<!-- START EXPERT BOARD CODE AND DIV -->	
<form action="expertboard.php" method="post" name="frmgoal" enctype="multipart/form-data">
	<div style="display: none; position: fixed; opacity: 1; z-index: 11000; left: 50%; margin-left: -330px; top: 100px;" id="create_goal_board" class="popup_div">
		        <a class="modal_close" href="#" title="close"></a>
                <div class="msg_red" id="div_frm_expgoalmsg"></div>
				<div align="center" class="head_20">Create Goal Board</div><br>
                <div class="red_circle">1</div><strong>Title</strong>:
	<input type="text" name="goal_title" id="goal_title" value="" style="margin-bottom:5px;margin-left:75px;"><br>
	<div class="red_circle">2</div><strong>Detail</strong>:&nbsp;
    <textarea id="goal_detail"  name="goal_detail" class="search_1" style="height:35px;width:300px;margin-bottom:5px;vertical-align:top;margin-left:62px;" onFocus="if(this.value=='Enter detail') this.value='';" onBlur="if(this.value=='') this.value='Enter detail';">Enter detail</textarea><br>
				<div class="red_circle">3</div><strong>Category</strong>:&nbsp;<span style="margin-left:44px;"><?=getCategoryList(0,'','category_id2')?></span><br><br>
				 <div class="red_circle">4</div><strong>Number of days</strong>:
               <input type="text" name="goal_number_days" id="goal_number_days" value="" style="margin-bottom:5px;width:50px;"><br>
				<input type="submit" class="btn_small right" id="btnCreateGoalBoard" onClick="javascript:return CheckFormValidation('div_frm_expgoalmsg','goal_title#Enter Title,goal_detail#Enter detail#Enter detail,category_id2#Select Category#0,goal_number_days#Enter Number Of Days');" value="Create Goal Board" name="btnCreateGoalBoard" />
  </div>
  </form>
<!-- END CREATE EXPERT BOARD CODE AND DIV  -->