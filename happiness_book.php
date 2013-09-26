<?php
include_once "fbmain.php";
include('include/app-common-config.php');
$userDetail=getUserDetail(USER_ID,'uid');
$photo_url=$userDetail['photo_url'];
$user_name=$userDetail['name'];
?>
<?php include('site_header.php');?>
     

<!-- ================================== -->
<script type="text/javascript" src="book/turn-jquery.js"></script>
<script type="text/javascript" src="book/turn.js"></script>

<style type="text/css">
#magazine{
	width:1152px;
	height:552px;
}
#magazine .turn-page{
	background-color:#ccc;
	background-size:100% 100%;
}
<!-- Start Book -->
@charset "utf-8";
/* CSS Document */

.welcome_main{background-color:#FFFFFF;}
.book_tabs_main{float:left; z-index:200px; width:566px; padding-left:10px;}
.book_tabs_left_love{float:left; background-image:url(images/book-top-img_left_hover.png); background-repeat:no-repeat; width:14px; height:21px;}

.book_tabs_love{float:left;}
.book_tabs_love a{background-image:url(images/book-top-img_hover.png); text-align:center; padding:3px 2px 2px 2px; font-size:12px; display:block; color:#000000; text-decoration:none; border-top:1px solid #f1f1f1;}
.book_tabs_love a:hover{color:#FF0000;}
.book_tabs_right_love{float:left; background-image:url(images/book-top-img_right_hover.png); background-repeat:no-repeat; width:14px; height:21px;}

.book_tabs_left{float:left; background-image:url(images/book-top-img_left.png); background-repeat:no-repeat; width:14px; height:21px;}
.book_tabs{float:left;}
.book_tabs a{background-image:url(images/book-top-img.png); text-align:center; padding:2px 6px; font-size:12px; display:block; color:#000000; text-decoration:none;}
.book_tabs a:hover{color:#FFFFFF;}
.book_tabs_right{float:left; background-image:url(images/book-top-img_right.png); background-repeat:no-repeat; width:14px; height:21px;}

.book_tabs_main{float:right; z-index:200px;}
.activate_friends_bg{ height:575px; margin-bottom:50px;}
.book_page_left{float:left; background-image:url(images/book-story.png); background-repeat:no-repeat; width:576px; font-size:18px; height:580px; text-align:center; box-shadow: 0 0 25px #d3d3d3;}
.book_profile_text{border:#CCCCCC 2px solid; width:134px; text-align:center; margin:120px 0 5px 210px; padding:20px 0; font-size:16px; color:#bdbdbd;}
.life_story_book_text{font-family:Brush Script Std; font-size:18px; margin-top:50px;}
.book_page_right{float:right; background-image:url(images/book-story.png); background-repeat:no-repeat; width:560px; font-size:18px; height:514px; text-align:center; box-shadow: 0 0 25px #d3d3d3;}
.book_page_right1{float:left; background-image:url(images/book-story1.png); background-repeat:no-repeat; width:560px; font-size:18px; height:514px; text-align:center; box-shadow: 0 0 25px #d3d3d3;}
.book_tabs_main{float:left; z-index:200px; width:566px; padding-left:10px;}
.book_tabs_main_page{float:right; z-index:200px; }
.book_tabs_main_page_left{float:right; z-index:200px; width:576px; padding-left:10px;}
.book_tabs_main_left{float:left; z-index:200px; width:576px;}
.chapter_love{font-size:20px; text-align:left; padding:70px 0 0 30px;}
.book_right_text{ float:left; text-align:left; width:176px; margin:20px 0 0 60px; font-size:12px;}
.activate_friends_main_top{float:right; width:576px; margin:auto;}
.activate_friends_bg_right{float:right; background-image:url(../images/book-story1.png); background-repeat:no-repeat; width:545px; height:575px; margin-left:18px; padding:7px; margin-bottom:50px; box-shadow: 0 0 25px #d3d3d3;}

</style>


<div class="relationship_bg" style="padding:50px 0; background-color:#FFFFFF;">
<div id="magazine" style="margin:auto;">

<!-- PAGE 0 -->

<div class="welcome_main" style="background-color:#FFFFFF">
     <div style="clear:both"></div>
     <div class="activate_friends_main_top" style="width:569px">
       <div class="book_tabs_main_page">
          
          <div class="book_tabs_left_love"></div>
          <div class="book_tabs_love"><a href="#" onclick="trun_next()">Love</a></div>
          <div class="book_tabs_right_love"></div>
          
          <div class="book_tabs_left"></div>
          <div class="book_tabs"><a href="#" onclick="trun_next()">Money</a></div>
          <div class="book_tabs_right"></div>
          
          <div class="book_tabs_left"></div>
          <div class="book_tabs"><a href="#" onclick="trun_next()">Career</a></div>
          <div class="book_tabs_right"></div>
          
          <div class="book_tabs_left"></div>
          <div class="book_tabs"><a href="#" onclick="trun_next()">Fun</a></div>
          <div class="book_tabs_right"></div>
          
          <div class="book_tabs_left"></div>
          <div class="book_tabs"><a href="#" onclick="trun_next()">Community</a></div>
          <div class="book_tabs_right"></div>
          
          <div class="book_tabs_left"></div>
          <div class="book_tabs"><a href="#" onclick="trun_next()">Beauty</a></div>
          <div class="book_tabs_right"></div>
          
          <div class="book_tabs_left"></div>
          <div class="book_tabs"><a href="#" onclick="trun_next()">Wellness</a></div>
          <div class="book_tabs_right"></div>
          
      </div>
       <div style="clear:both"></div>
      
      <div class="activate_friends_bg">
        <div class="book_page_right1" style="width:554px;">
		    <div class="book_profile_text"><img src="<?=$photo_url?>" height="100px" width="100px" /></div>
                                        <?=$user_name?>
    	      <div class="life_story_book_text">Life Story Book</div>
		</div>
      </div>
     </div>
   </div>

<!-- PAGE 1 -->
<div class="welcome_main" style="background-color:#FFFFFF">
     <div style="clear:both"></div>
     <div class="activate_friends_main_top">
       <div class="book_tabs_main_page_left">
          
          <div class="book_tabs_left_love"></div>
          <div class="book_tabs_love"><a href="#" onclick="trun_privious()">Love</a></div>
          <div class="book_tabs_right_love"></div>
          
      </div>
       <div style="clear:both"></div>
      
      <div class="activate_friends_bg">
        <div class="book_page_right">
        
         <div class="chapter_love">Chapter - LOVE</div>
         <div class="book_right_text">
          
         FIRST KISS<br /><br />

         PERFECT KISS<br /><br />
    
         FAVORITE MOVIE KISS SCENE<br /><br />

         FIRST DATE<br /><br />

         DREAM DATE<br /><br />

         FAVORITE MOVIE DATE SCENE<br /><br />
  
         FIRST LOVE MAKING<br /><br />

         DREAM LOVE MAKING<br /><br />

         FAVORITE LOVE MAKING MOVIE <br />
         SCENE
</div>
         
        </div>
      
      </div>
     </div>
   </div>

<!-- PAGE 2 -->

<div class="welcome_main" style="background-color:#FFFFFF">
     <div style="clear:both"></div>
     <div class="activate_friends_main_top">
       <div class="book_tabs_main_page">
          
          
          <div class="book_tabs_left_love"></div>
          <div class="book_tabs_love"><a href="#" onclick="trun_next()">Money</a></div>
          <div class="book_tabs_right_love"></div>
          
          <div class="book_tabs_left"></div>
          <div class="book_tabs"><a href="#" onclick="trun_next()">Career</a></div>
          <div class="book_tabs_right"></div>
          
          <div class="book_tabs_left"></div>
          <div class="book_tabs"><a href="#" onclick="trun_next()">Fun</a></div>
          <div class="book_tabs_right"></div>
          
          <div class="book_tabs_left"></div>
          <div class="book_tabs"><a href="#" onclick="trun_next()">Community</a></div>
          <div class="book_tabs_right"></div>
          
          <div class="book_tabs_left"></div>
          <div class="book_tabs"><a href="#" onclick="trun_next()">Beauty</a></div>
          <div class="book_tabs_right"></div>
          
          <div class="book_tabs_left"></div>
          <div class="book_tabs"><a href="#" onclick="trun_next()">Wellness</a></div>
          <div class="book_tabs_right"></div>
          
      </div>
       <div style="clear:both"></div>
      
      <div class="activate_friends_bg">
        <div class="book_page_right1">
        
         <div class="chapter_love">Chapter - Money</div>
          <div class="book_right_text">
        
        </div>
         
        </div>
      
      </div>
     </div>
   </div>

<!-- START PAGES -->
<!-- PAGE 4 -->
<div class="welcome_main" style="background-color:#FFFFFF">
     <div style="clear:both"></div>
     <div class="activate_friends_main_top">
       <div class="book_tabs_main_page_left">
          
          <div class="book_tabs_left"></div>
          <div class="book_tabs"><a href="#" onclick="trun_privious()">Love</a></div>
          <div class="book_tabs_right"></div>
		  
		   <div class="book_tabs_left_love"></div>
          <div class="book_tabs_love"><a href="#" onclick="trun_privious()">Money</a></div>
          <div class="book_tabs_right_love"></div>
          
      </div>
       <div style="clear:both"></div>
      
      <div class="activate_friends_bg">
        <div class="book_page_right">
        
         <div class="chapter_love">Chapter - Money</div>
         <div class="book_right_text">
        </div>
         
        </div>
      
      </div>
     </div>
   </div>


<div class="welcome_main" style="background-color:#FFFFFF">
     <div style="clear:both"></div>
     <div class="activate_friends_main_top">
       <div class="book_tabs_main_page">
          
          
          <div class="book_tabs_left_love"></div>
          <div class="book_tabs_love"><a href="#" onclick="trun_next()">Career</a></div>
          <div class="book_tabs_right_love"></div>
          
          <div class="book_tabs_left"></div>
          <div class="book_tabs"><a href="#" onclick="trun_next()">Fun</a></div>
          <div class="book_tabs_right"></div>
          
          <div class="book_tabs_left"></div>
          <div class="book_tabs"><a href="#" onclick="trun_next()">Community</a></div>
          <div class="book_tabs_right"></div>
          
          <div class="book_tabs_left"></div>
          <div class="book_tabs"><a href="#" onclick="trun_next()">Beauty</a></div>
          <div class="book_tabs_right"></div>
          
          <div class="book_tabs_left"></div>
          <div class="book_tabs"><a href="#" onclick="trun_next()">Wellness</a></div>
          <div class="book_tabs_right"></div>
          
      </div>
       <div style="clear:both"></div>
      
      <div class="activate_friends_bg">
        <div class="book_page_right1">
        
         <div class="chapter_love">Chapter - Career</div>
          <div class="book_right_text">
        
        </div>
         
        </div>
      
      </div>
     </div>
   </div>


<div class="welcome_main" style="background-color:#FFFFFF">
     <div style="clear:both"></div>
     <div class="activate_friends_main_top">
       <div class="book_tabs_main_page_left">
          
          <div class="book_tabs_left"></div>
          <div class="book_tabs"><a href="#" onclick="trun_privious()">Love</a></div>
          <div class="book_tabs_right"></div>
		  
		   <div class="book_tabs_left"></div>
          <div class="book_tabs"><a href="#" onclick="trun_privious()">Money</a></div>
          <div class="book_tabs_right"></div>
		  
		    <div class="book_tabs_left_love"></div>
          <div class="book_tabs_love"><a href="#" onclick="trun_privious()">Career</a></div>
          <div class="book_tabs_right_love"></div>
          
      </div>
       <div style="clear:both"></div>
      
      <div class="activate_friends_bg">
        <div class="book_page_right">
        
         <div class="chapter_love">Chapter - Career</div>
         <div class="book_right_text">
        </div>
         
        </div>
      
      </div>
     </div>
   </div>


<div class="welcome_main" style="background-color:#FFFFFF">
     <div style="clear:both"></div>
     <div class="activate_friends_main_top">
       <div class="book_tabs_main_page">
          
          <div class="book_tabs_left_love"></div>
          <div class="book_tabs_love"><a href="#" onclick="trun_next()">Fun</a></div>
          <div class="book_tabs_right_love"></div>
          
          <div class="book_tabs_left"></div>
          <div class="book_tabs"><a href="#" onclick="trun_next()">Community</a></div>
          <div class="book_tabs_right"></div>
          
          <div class="book_tabs_left"></div>
          <div class="book_tabs"><a href="#" onclick="trun_next()">Beauty</a></div>
          <div class="book_tabs_right"></div>
          
          <div class="book_tabs_left"></div>
          <div class="book_tabs"><a href="#" onclick="trun_next()">Wellness</a></div>
          <div class="book_tabs_right"></div>
          
      </div>
       <div style="clear:both"></div>
      
      <div class="activate_friends_bg">
        <div class="book_page_right1">
        
         <div class="chapter_love">Chapter - Fun</div>
          <div class="book_right_text">
        
        </div>
         
        </div>
      
      </div>
     </div>
   </div>


<div class="welcome_main" style="background-color:#FFFFFF">
     <div style="clear:both"></div>
     <div class="activate_friends_main_top">
       <div class="book_tabs_main_page_left">
          
          <div class="book_tabs_left"></div>
          <div class="book_tabs"><a href="#" onclick="trun_privious()">Love</a></div>
          <div class="book_tabs_right"></div>
		  
		   <div class="book_tabs_left"></div>
          <div class="book_tabs"><a href="#" onclick="trun_privious()">Money</a></div>
          <div class="book_tabs_right"></div>
		  
		    <div class="book_tabs_left"></div>
          <div class="book_tabs"><a href="#" onclick="trun_privious()">Career</a></div>
          <div class="book_tabs_right"></div>
		  
		     <div class="book_tabs_left_love"></div>
          <div class="book_tabs_love"><a href="#" onclick="trun_privious()">Fun</a></div>
          <div class="book_tabs_right_love"></div>
          
      </div>
       <div style="clear:both"></div>
      
      <div class="activate_friends_bg">
        <div class="book_page_right">
        
         <div class="chapter_love">Chapter - Fun</div>
         <div class="book_right_text">
        </div>
         
        </div>
      
      </div>
     </div>
   </div>


<div class="welcome_main" style="background-color:#FFFFFF">
     <div style="clear:both"></div>
     <div class="activate_friends_main_top">
       <div class="book_tabs_main_page">
          
          <div class="book_tabs_left_love"></div>
          <div class="book_tabs_love"><a href="#" onclick="trun_next()">Community</a></div>
          <div class="book_tabs_right_love"></div>
          
          <div class="book_tabs_left"></div>
          <div class="book_tabs"><a href="#" onclick="trun_next()">Beauty</a></div>
          <div class="book_tabs_right"></div>
          
          <div class="book_tabs_left"></div>
          <div class="book_tabs"><a href="#" onclick="trun_next()">Wellness</a></div>
          <div class="book_tabs_right"></div>
          
      </div>
       <div style="clear:both"></div>
      
      <div class="activate_friends_bg">
        <div class="book_page_right1">
        
         <div class="chapter_love">Chapter - Community</div>
          <div class="book_right_text">
        
        </div>
         
        </div>
      
      </div>
     </div>
   </div>

<div class="welcome_main" style="background-color:#FFFFFF">
     <div style="clear:both"></div>
     <div class="activate_friends_main_top">
       <div class="book_tabs_main_page_left">
          
          <div class="book_tabs_left"></div>
          <div class="book_tabs"><a href="#" onclick="trun_privious()">Love</a></div>
          <div class="book_tabs_right"></div>
		  
		   <div class="book_tabs_left"></div>
          <div class="book_tabs"><a href="#" onclick="trun_privious()">Money</a></div>
          <div class="book_tabs_right"></div>
		  
		    <div class="book_tabs_left"></div>
          <div class="book_tabs"><a href="#" onclick="trun_privious()">Career</a></div>
          <div class="book_tabs_right"></div>
		  
		     <div class="book_tabs_left"></div>
          <div class="book_tabs"><a href="#" onclick="trun_privious()">Fun</a></div>
          <div class="book_tabs_right"></div>
		  
		     <div class="book_tabs_left_love"></div>
          <div class="book_tabs_love"><a href="#" onclick="trun_privious()">Community</a></div>
          <div class="book_tabs_right_love"></div>
          
      </div>
       <div style="clear:both"></div>
      
      <div class="activate_friends_bg">
        <div class="book_page_right">
        
         <div class="chapter_love">Chapter - Community</div>
         <div class="book_right_text">
        </div>
         
        </div>
      
      </div>
     </div>
   </div>


<div class="welcome_main" style="background-color:#FFFFFF">
     <div style="clear:both"></div>
     <div class="activate_friends_main_top">
       <div class="book_tabs_main_page">
          
          <div class="book_tabs_left_love"></div>
          <div class="book_tabs_love"><a href="#" onclick="trun_next()">Beauty</a></div>
          <div class="book_tabs_right_love"></div>
          
          <div class="book_tabs_left"></div>
          <div class="book_tabs"><a href="#" onclick="trun_next()">Wellness</a></div>
          <div class="book_tabs_right"></div>
          
      </div>
       <div style="clear:both"></div>
      
      <div class="activate_friends_bg">
        <div class="book_page_right1">
        
         <div class="chapter_love">Chapter - Beauty</div>
          <div class="book_right_text">
        
        </div>
         
        </div>
      
      </div>
     </div>
   </div>

<div class="welcome_main" style="background-color:#FFFFFF">
     <div style="clear:both"></div>
     <div class="activate_friends_main_top">
       <div class="book_tabs_main_page_left">
          
          <div class="book_tabs_left"></div>
          <div class="book_tabs"><a href="#" onclick="trun_privious()">Love</a></div>
          <div class="book_tabs_right"></div>
		  
		   <div class="book_tabs_left"></div>
          <div class="book_tabs"><a href="#" onclick="trun_privious()">Money</a></div>
          <div class="book_tabs_right"></div>
		  
		    <div class="book_tabs_left"></div>
          <div class="book_tabs"><a href="#" onclick="trun_privious()">Career</a></div>
          <div class="book_tabs_right"></div>
		  
		     <div class="book_tabs_left"></div>
          <div class="book_tabs"><a href="#" onclick="trun_privious()">Fun</a></div>
          <div class="book_tabs_right"></div>
		  
		     <div class="book_tabs_left"></div>
          <div class="book_tabs"><a href="#" onclick="trun_privious()">Community</a></div>
          <div class="book_tabs_right"></div>
		  
		     <div class="book_tabs_left_love"></div>
          <div class="book_tabs_love"><a href="#" onclick="trun_privious()">Beauty</a></div>
          <div class="book_tabs_right_love"></div>
          
      </div>
       <div style="clear:both"></div>
      
      <div class="activate_friends_bg">
        <div class="book_page_right">
        
         <div class="chapter_love">Chapter - Beauty</div>
         <div class="book_right_text">
        </div>
         
        </div>
      
      </div>
     </div>
   </div>

<div class="welcome_main" style="background-color:#FFFFFF">
     <div style="clear:both"></div>
     <div class="activate_friends_main_top">
       <div class="book_tabs_main_page">
          
          <div class="book_tabs_left_love"></div>
          <div class="book_tabs_love"><a href="#" onclick="trun_next()">Wellness</a></div>
          <div class="book_tabs_right_love"></div>
          
      </div>
       <div style="clear:both"></div>
      
      <div class="activate_friends_bg">
        <div class="book_page_right1">
        
         <div class="chapter_love">Chapter - Wellness</div>
          <div class="book_right_text">
        
        </div>
         
        </div>
      
      </div>
     </div>
   </div>

<?php
$CatArray=array(0=>'Cover',1=>'Love',2=>'Money',3=>'Career',4=>'Fun',5=>'Community',6=>'Beauty',7=>'Wellness');

function bookMenu($activeMenu,$fromMenu,$ToMenu){


}

foreach($CatArray as $category_id=>$category_name){
	
}
?>

</div>

</div>
<div style="clear:both"></div>

<script type="text/javascript">

	$(window).ready(function() {
		$('#magazine').turn({
							display: 'double',
							acceleration: true,
							gradients: !$.isTouch,
							elevation:50,
							when: {
								turned: function(e, page) {
									console.log('Current view: ', $(this).turn('view'));
								}
							}
						});
	});
	
	
	$(window).bind('keydown', function(e){
		
		if (e.keyCode==37)
			$('#magazine').turn('previous');
		else if (e.keyCode==39)
			$('#magazine').turn('next');
	});

	function trun_next(){
		$('#magazine').turn('next');
	}
	function trun_privious(){
		$('#magazine').turn('previous');
	}
</script>


<div style="padding-bottom:60px;"></div>
<?php include('site_footer.php');?>
