<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8" />
<title>Achieve goals and win gifts</title>
<style type="text/css">
<!--
@import url("css/cherryfull_style.css");
@import url("css/common_style.css");
-->
</style>
<script type="text/javascript" src="js/common.js"></script>
<?php if(SCRIPT_NAME=="cherryboard.php"||SCRIPT_NAME=="expert_cherryboard.php"){ ?>
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
				//$('<span></span>').appendTo('#files').html('<img src="images/<?=(SCRIPT_NAME=="cherryboard.php"?'cherryboard':'expertboard')?>/temp/cherry-'+file+'" alt="" height="100" width="100" class="image" /><br />').addClass('success');

				//Refresh feed section
				//var cherryboard_id=document.getElementById('cherryboard_id').value
				//alert(cherryboard_id);
				//ajax_action('refresh_inspir_feed','inspir_feed1','cherryboard_id='+cherryboard_id);
				
			}
		});
		
		
	});
</script>
<!-- JS for the photo slider -->
<script type="text/javascript" src="scripts/autocore_002.js"></script>
<script type="text/javascript" src="scripts/autocore.js"></script>
<?php } ?>
<?php if(SCRIPT_NAME!="setup2.php"||SCRIPT_NAME!="setup3.php"){ ?>
<script type="text/javascript" src="scripts/jquery_002.js"></script>
<script type="text/javascript" src="scripts/jquery.js"></script>
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
<?php } ?>	 
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

<link type="text/css" href="css/date_picker.css" rel="stylesheet" />	
<script type='text/javascript' src='js/jquery-1.5.1.min.js'></script>
<script type="text/javascript" src="js/jquery-ui-1.7.3.custom.min.js"></script>
 
</head>
<body>
<!--Header Start-->
	<?php if(SCRIPT_NAME=="index.php"||SCRIPT_NAME=="team.php"||SCRIPT_NAME=="about.php"||SCRIPT_NAME=="contact_us.php"){ ?>
		<div id="header">
			<div id="header_cherryboard">
				<?php if(SCRIPT_NAME!="index.php"){ ?><div class="logo"><img src="images/logo_new_small.png" alt="30daysnew" title="30daysnew"></div><?php }?>
				<?php if(FB_ID>0){ ?>
				<div id="link_container"><a href="experts.php" title="Experts">Experts</a>&#8226;<a href="setup2.php" title="Gifts">Gifts</a></div>
				<?php }else{?>
				<a href="#" class="btn_blue right" onClick="fb_login();">login with facebook</a>
				<?php } ?>
			</div>
		</div>
	<?php }else if(SCRIPT_NAME!="index.php"){ ?>
	<div id="header">
	<div id="header_cherryboard">
		<div id="link_container"><a href="experts.php" class="activemenu" title="Experts">Experts</a>&#8226;<a href="setup2.php" title="Gifts">Gifts</a><!-- &#8226;<a href="index_detail.php" title="Goalboards">Goalboards</a>--></div>
    	<div class="logo"><img src="images/logo_new_small.png" alt="30daysnew" title="30daysnew"></div>
		 <?php if(FB_ID>0){ ?>
		<div style="float:right; position:absolute; top:-5px; color:#FFFFFF; font-size:15px; margin:5px 0px 3px 920px;"><a href="#" onClick="fb_logout()" class="red_link_14 smalltext1"><strong>Logout</strong></a></div>
		
		<div class="userbox"><a href="index_detail.php"><img src="<?php echo PHOTO_URL;?>" class="profile_img"></a><a href="index_detail.php" style="text-decoration:none"><?php echo FIRST_NAME.' '.LAST_NAME;?></a><br><a href="setup2.php" class="gray_link">+Goal Storyboard</a><br><a href="add_cherryboard_expert.php" class="gray_link">+Expert Storyboard</a><br><a href="#" class="gray_link">+Gift</a></div>
	</div>
		<?php  }else{ ?>
		<a href="#" class="btn_blue right" onClick="fb_login();">login with facebook</a>
		<?php } ?>
	</div>
  <?php } ?>
<!--Header End-->