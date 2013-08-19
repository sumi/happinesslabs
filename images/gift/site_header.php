<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8" />
<title>Achieve goals and win gifts</title>
<style type="text/css">
<!--
@import url("css/30daysnew_style.css");
@import url("css/common_style.css");
body{ background:repeat-x left top #eeeaec;}
-->
</style>
<script type="text/javascript" src="js/common.js"></script>
<?php if(SCRIPT_NAME=="test.php"){ ?>
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
				}
			});
			
			
		});
	</script>
<?php } ?>
<?php if(SCRIPT_NAME=="cherryboard.php"||SCRIPT_NAME=="expert_cherryboard.php"||SCRIPT_NAME=="setup2.php"||SCRIPT_NAME=="experts.php"){ ?>
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
				}
			});
			
			
		});
	</script>
	<!-- JS for the photo slider -->
	<script type="text/javascript" src="scripts/autocore_002.js"></script>
	<script type="text/javascript" src="scripts/autocore.js"></script>
	<!-- Code for the space issue -->
	<script src="http://code.jquery.com/jquery-latest.js"></script>
	<script type="text/javascript">
	var colCount = 0;
	var colWidth = 0;
	var margin = 20;
	var windowWidth = 0;
	var blocks = [];
	
	$(function(){
		$(window).resize(setupBlocks);
	});
	
	function setupBlocks() {
		//windowWidth = $(window).width();
		//alert(windowWidth);
		<?php if(SCRIPT_NAME=="experts.php"){ ?>
			windowWidth = 1004;
			colWidth = $('.field_container1').outerWidth();
		<?php }else{ ?>
			windowWidth = 900;
			colWidth = $('.field_container2').outerWidth();
		<?php }?>
		blocks = [];
		console.log(blocks);
		colCount = Math.floor(windowWidth/(colWidth+margin*2));
		for(var i=0;i<colCount;i++){
			blocks.push(margin);
		}
		positionBlocks();
	}
	
	function positionBlocks() {
		<?php if(SCRIPT_NAME=="experts.php"){ ?>
			$('.field_container1').each(function(){
		<?php }else{ ?>
			$('.field_container2').each(function(){
		<?php }?>
			var min = Array.min(blocks);
			var index = $.inArray(min, blocks);
			var leftPos = margin+(index*(colWidth+margin));
			$(this).css({
				'left':leftPos+'px',
				'top':min+'px'
			});
			blocks[index] = min+$(this).outerHeight()+margin;
		});	
	}

	
	// Function to get the Min value in Array
	Array.min = function(array) {
		return Math.min.apply(Math, array);
	};
	</script>
<?php } ?>
<script type="text/javascript" src="scripts/jquery_002.js"></script>
<script type="text/javascript" src="scripts/jquery.js"></script>
<script type="text/javascript">
	$(function() {
		$('a[rel*=leanModal]').leanModal({ top : 100, closeButton: ".modal_close" });		
	});
</script>
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
</head>
<body<?=(SCRIPT_NAME=="experts.php"||SCRIPT_NAME=="cherryboard.php"||SCRIPT_NAME=="expert_cherryboard.php"?' onLoad="setupBlocks();"':'')?>>

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
                    document.location.href = "index_detail.php";
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
            document.location.href = "index.php?tp=logout";
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
	<?php if(SCRIPT_NAME=="index.php"||SCRIPT_NAME=="team.php"||SCRIPT_NAME=="about.php"||SCRIPT_NAME=="contact_us.php"){ ?>
		<div id="header">
			<div id="header_cherryboard">
				<?php if(SCRIPT_NAME!="index.php"){ ?><div class="logo"><img src="images/logo_new_small.png" alt="30daysnew" title="30daysnew"></div><?php }?>
				<?php if(FB_ID>0){ ?>
				<!-- <a href="experts.php" title="Experts">Experts</a> -->
				<div id="link_container"><a href="gifts.php" title="Gifts">Gifts</a>&#8226;<a href="experts.php" title="Experts">Experts</a>&#8226;<a href="goals.php" title="Goalboards">Goals</a></div>
				<?php }else{?>
				<a href="#" class="btn_blue right" onClick="fb_login();">login with facebook</a>
				<?php } ?>
			</div>
		</div>
	<?php }else if(SCRIPT_NAME!="index.php"){ ?>
	<div id="header">
	<div id="header_cherryboard">
		<div id="link_container"><a href="gifts.php" title="Gifts">Gifts</a>&#8226;<a href="experts.php" title="Experts">Experts</a>&#8226;<a href="goals.php" title="Goalboards">Goals</a></div>
    	<div class="logo"><img src="images/logo_new_small.png" alt="30daysnew" title="30daysnew"></div>
		 <?php if(FB_ID>0){ ?>
		<div style="float:right; position:absolute; top:-5px; color:#FFFFFF; font-size:15px; margin:5px 0px 3px 920px;"><a href="#" onClick="fb_logout()" class="red_link_14 smalltext1"><strong>Logout</strong></a></div>
		
		<div class="userbox"><a href="index_detail.php"><img src="<?php echo PHOTO_URL;?>" class="profile_img"></a><a href="index_detail.php" style="text-decoration:none;font-size: 12px;">&nbsp;&nbsp;<?php echo FIRST_NAME.' '.LAST_NAME;?></a><br><a href="setup2.php" class="gray_link">+Goal Storyboard</a><br><a href="add_cherryboard_expert.php" class="gray_link">+Expert Storyboard</a><br><a href="gifts.php" class="gray_link">+Gifts</a></div>
	</div>
		<?php  }else{ ?>
		<a href="#" class="btn_blue right" onClick="fb_login();">login with facebook</a>
		<?php } ?>
	</div>
  <?php } ?>
  
<!--Header End-->
<?php
//START ADD GIFT VODE AND DIV
	if(isset($_POST['gift_title'])){
		$gift_title=$_POST['gift_title'];
		$category_id=$_POST['category_id'];
		$sponsor=(int)$_POST['sponsor'];
		$file_name= rand().'_'.trim($_FILES['gift_photo']['name']);
		$file_name=str_replace(' ','_',$file_name);
		$file_name=str_replace('-','_',$file_name);
		
		$uploadTempdir = 'images/gift/temp/'.$file_name; 
		$uploaddir = 'images/gift/'.$file_name; 
		
		if($gift_title!="Enter gift title"&&$gift_title!=""&&$category_id>0&&$file_name!=""){
			if(move_uploaded_file($_FILES['gift_photo']['tmp_name'], $uploadTempdir)){
				
				if($_SERVER['SERVER_NAME']=="localhost"){
					$retval=copy($uploadTempdir,$uploaddir);
				}else{
					$thumb_command=$ImageMagic_Path."convert ".$uploadTempdir." -thumbnail 150 x 150 ".$uploaddir;
					$last_line=system($thumb_command, $retval);
					unlink($uploadTempdir);
				}				
				$ins_sel="INSERT INTO `tbl_app_gift` (`gift_id`, `category_id`, `gift_title`, `gift_photo`, `is_system`, sponsor, `record_date`) VALUES (NULL, '".$category_id."', '".$gift_title."', '".$file_name."', '1', '".$sponsor."', CURRENT_TIMESTAMP)";
				$ins_sql=mysql_query($ins_sel);
				
			}
		}
	}
	?>
	<form action="" method="post" name="frmgift" enctype="multipart/form-data">
	<div style="display: none; position: fixed; opacity: 1; z-index: 11000; left: 50%; margin-left: -330px; top: 200px;" id="add_gift">
		        <a class="modal_close" href="#" title="close"></a>
                <div align="center" class="head_20">It’s Easy!</div><br><br>
                <div class="red_circle">1</div><strong>Create a gift</strong><br><br>               
                <textarea id="gift_title"  name="gift_title" class="search_1" style="height:50px;width:400px;" onFocus="if(this.value=='Enter gift title') this.value='';" onBlur="if(this.value=='') this.value='Enter gift title';">Enter gift title</textarea><div align="right">&nbsp;</div>
	            <div class="red_circle">2</div><strong>Category</strong><br><br>
                <?=getCategoryList()?>
				<br><br>
				 <div class="red_circle">3</div><strong>Sponsor</strong><br><br>
                 <input type="radio" name="sponsor" id="sponsor" value="1">&nbsp;I want to gift.&nbsp;<input type="radio" name="sponsor" id="sponsor" style="padding-left:200px;" checked="checked" value="1">&nbsp;I want to sponsor.
			    <br><br>
   	            <div class="red_circle">4</div><strong>Upload an Image</strong><br><br>
                <input name="gift_photo" type="file"><br>
				<input type="button" class="btn_small right" id="btnAddGift" onClick="javascript:document.frmgift.submit();" value="Add Gift" name="btnAddGift" />
  </div>
  </form>
<!-- END ADD GIFT VODE AND DIV -->
  <!-- 
 <div style="display: none; position: fixed; opacity: 1; z-index: 11000; left: 50%; margin-left: -330px; top: 200px;" id="add_gift" align="center">
                <a class="modal_close" href="#" title="close"></a>
                <span class="head_20">Add Gift</span><br><br>
				<form action="" method="post" name="frmgift" enctype="multipart/form-data">
				<table>
                    <tr>
                    	<td>Category: </td>
                        <td><?=getCategoryList()?></td>
                    </tr>
                    <tr>
                    	<td>Gift Title: </td>
                        <td><input type="text" name="gift_title" onBlur="if(this.value=='') this.value='Enter gift title';" onFocus="if(this.value=='Enter gift title') this.value='';" value="Enter gift title" /></td>
                    </tr>
                    <tr>
                    	<td>Gift Photo: </td>
                        <td><input type="file" name="gift_photo" /></td>
                    </tr>
                    <tr>
                    	<td></td>
                        <td><input type="button" class="btn_small" id="btnAddGift" onClick="javascript:document.frmgift.submit();" value="Add Gift" name="btnAddGift" /></td>
                    </tr>
                </table>
				</form>
	 </div> -->
	