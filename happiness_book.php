<?php
include_once "fbmain.php";
include('include/app-common-config.php');
$userDetail=getUserDetail(USER_ID,'uid');
$photo_url=$userDetail['photo_url'];
$user_name=$userDetail['name'];
?>
<?php include('site_header.php');?>
     

<!-- ================================== -->
<script type="text/javascript" src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="book/turn.min.js"></script>

<style type="text/css">
#magazine{
	width:1152px;
	height:552px;
}
#magazine .turn-page{
	background-color:#ccc;
	background-size:100% 100%;
}
</style>
<div class="welcome_main" style="background-image:url('images/welcome-to-bg.png')">
     <div class="main_top" style="width:1152px">
       <div class="activate_friends_main"><img src="images/activate_-friends-logo.png" alt="" /></div>
     </div>
     <div style="clear:both"></div>

<?php
$CatArray=array(1=>'Love',2=>'Money',3=>'Career',4=>'Fun',5=>'Community',6=>'Beauty',7=>'Wellness');
?>
<!-- START PAGES -->
<div id="magazine" style="margin-left:50px" style="background-image:url('images/welcome-to-bg.png')">

<!-- START PAGE 1 --> 
	<div class="activate_friends_main_top" style="background-image:url('images/welcome-to-bg.png')">
     <div class="book_tabs_main_left" style="background-image:url('images/welcome-to-bg.png')">
          
      </div>
      
      <div class="book_tabs_main" style="background-image:url('images/welcome-to-bg.png')">
          
          <div class="book_tabs_left_love"></div>
          <div class="book_tabs_love"><a href="#">Love</a></div>
          <div class="book_tabs_right_love"></div>
          
          <div class="book_tabs_left"></div>
          <div class="book_tabs"><a href="#">Money</a></div>
          <div class="book_tabs_right"></div>
          
          <div class="book_tabs_left"></div>
          <div class="book_tabs"><a href="#">Career</a></div>
          <div class="book_tabs_right"></div>
          
          <div class="book_tabs_left"></div>
          <div class="book_tabs"><a href="#">Fun</a></div>
          <div class="book_tabs_right"></div>
          
          <div class="book_tabs_left"></div>
          <div class="book_tabs"><a href="#">Community</a></div>
          <div class="book_tabs_right"></div>
          
          <div class="book_tabs_left"></div>
          <div class="book_tabs"><a href="#">Beauty</a></div>
          <div class="book_tabs_right"></div>
          
          <div class="book_tabs_left"></div>
          <div class="book_tabs"><a href="#">Wellness</a></div>
          <div class="book_tabs_right"></div>
          
      </div>
       <div style="clear:both"></div>
      
      <div class="activate_friends_bg">
        <div class="book_page_left">
	     <div class="book_profile_text" style="padding: 11px 12px;width:100px"><img src="<?=$photo_url?>" width="100px" height="100px" /></div>
                                        <?=$user_name?>
          <div class="life_story_book_text">Life Story Book</div>
        </div>
        
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
         SCENE<br /><br />

         DREAM WEDDING WEDDING<br /><br />

         FAVORITE WEDDING MOVIE<br />
         SCENE</div>
         
         
        </div>
      </div>
     </div>

<!-- END PAGE 1 -->

<!-- START PAGE 2 --> 
	<div class="activate_friends_main_top" style="background-image:url('images/welcome-to-bg.png')">
     <div class="book_tabs_main_left" style="background-image:url('images/welcome-to-bg.png')">
           <div class="book_tabs_left"></div>
			  <div class="book_tabs"><a href="#">Love</a></div>
			  <div class="book_tabs_right"></div>
		   
		   <div class="book_tabs_left_love"></div>
          <div class="book_tabs_love"><a href="#">Money</a></div>
          <div class="book_tabs_right_love"></div>
		  
      </div>
      
      <div class="book_tabs_main" style="background-image:url('images/welcome-to-bg.png')">
          
          <div class="book_tabs_left_love"></div>
          <div class="book_tabs_love"><a href="#">Career</a></div>
          <div class="book_tabs_right_love"></div>
          
          <div class="book_tabs_left"></div>
          <div class="book_tabs"><a href="#">Fun</a></div>
          <div class="book_tabs_right"></div>
          
          <div class="book_tabs_left"></div>
          <div class="book_tabs"><a href="#">Community</a></div>
          <div class="book_tabs_right"></div>
          
          <div class="book_tabs_left"></div>
          <div class="book_tabs"><a href="#">Beauty</a></div>
          <div class="book_tabs_right"></div>
          
          <div class="book_tabs_left"></div>
          <div class="book_tabs"><a href="#">Wellness</a></div>
          <div class="book_tabs_right"></div>
          
      </div>
       <div style="clear:both"></div>
      
      <div class="activate_friends_bg">
        <div class="book_page_left">
        <div class="chapter_love">Chapter - Money</div>
        </div>
        
        <div class="book_page_right">
        
         <div class="chapter_love">Chapter - Career</div>
         <div class="book_right_text">
         
         </div>
      </div>
     </div>
	 
	 </div>

<!-- END PAGE 2  -->

<!-- START PAGE 3 --> 
	<div class="activate_friends_main_top" style="background-image:url('images/welcome-to-bg.png')">
     <div class="book_tabs_main_left" style="background-image:url('images/welcome-to-bg.png')">
          <div class="book_tabs_left"></div>
		  <div class="book_tabs"><a href="#">Love</a></div>
		  <div class="book_tabs_right"></div>
		   
		  <div class="book_tabs_left"></div>
          <div class="book_tabs"><a href="#">Money</a></div>
          <div class="book_tabs_right"></div>
		  
		  <div class="book_tabs_left_love"></div>
          <div class="book_tabs_love"><a href="#">Career</a></div>
          <div class="book_tabs_right_love"></div>
		  
      </div>
      
      <div class="book_tabs_main" style="background-image:url('images/welcome-to-bg.png')">
          
          <div class="book_tabs_left_love"></div>
          <div class="book_tabs_love"><a href="#">Fun</a></div>
          <div class="book_tabs_right_love"></div>
          
          <div class="book_tabs_left"></div>
          <div class="book_tabs"><a href="#">Community</a></div>
          <div class="book_tabs_right"></div>
          
          <div class="book_tabs_left"></div>
          <div class="book_tabs"><a href="#">Beauty</a></div>
          <div class="book_tabs_right"></div>
          
          <div class="book_tabs_left"></div>
          <div class="book_tabs"><a href="#">Wellness</a></div>
          <div class="book_tabs_right"></div>
          
      </div>
       <div style="clear:both"></div>
      
		  <div class="activate_friends_bg">
			<div class="book_page_left">
			<div class="chapter_love">Chapter - Career</div>
		  </div>
        
		  <div class="book_page_right">
			
			 <div class="chapter_love">Chapter - Fun</div>
			 <div class="book_right_text">
			 
		  </div>
      </div>
     </div>
	 
	 </div>

<!-- END PAGE 3  -->

<!-- START PAGE 4 --> 
	<div class="activate_friends_main_top" style="background-image:url('images/welcome-to-bg.png')">
     <div class="book_tabs_main_left" style="background-image:url('images/welcome-to-bg.png')">
          <div class="book_tabs_left"></div>
		  <div class="book_tabs"><a href="#">Love</a></div>
		  <div class="book_tabs_right"></div>
		   
		  <div class="book_tabs_left"></div>
          <div class="book_tabs"><a href="#">Money</a></div>
          <div class="book_tabs_right"></div>
		  
		  <div class="book_tabs_left"></div>
          <div class="book_tabs"><a href="#">Career</a></div>
          <div class="book_tabs_right"></div>
		  
		   <div class="book_tabs_left_love"></div>
          <div class="book_tabs_love"><a href="#">Fun</a></div>
          <div class="book_tabs_right_love"></div>
		  
      </div>
      
      <div class="book_tabs_main" style="background-image:url('images/welcome-to-bg.png')">
          
          <div class="book_tabs_left_love"></div>
          <div class="book_tabs_love"><a href="#">Community</a></div>
          <div class="book_tabs_right_love"></div>
          
          <div class="book_tabs_left"></div>
          <div class="book_tabs"><a href="#">Beauty</a></div>
          <div class="book_tabs_right"></div>
          
          <div class="book_tabs_left"></div>
          <div class="book_tabs"><a href="#">Wellness</a></div>
          <div class="book_tabs_right"></div>
          
      </div>
       <div style="clear:both"></div>
      
		  <div class="activate_friends_bg">
			<div class="book_page_left">
			<div class="chapter_love">Chapter - Fun</div>
		  </div>
        
		  <div class="book_page_right">
			
			 <div class="chapter_love">Chapter - Community</div>
			 <div class="book_right_text">
			 
		  </div>
      </div>
     </div>
	 
	 </div>

<!-- END PAGE 4  -->

<!-- START PAGE 5 --> 
	<div class="activate_friends_main_top" style="background-image:url('images/welcome-to-bg.png')">
     <div class="book_tabs_main_left" style="background-image:url('images/welcome-to-bg.png')">
          <div class="book_tabs_left"></div>
		  <div class="book_tabs"><a href="#">Love</a></div>
		  <div class="book_tabs_right"></div>
		   
		  <div class="book_tabs_left"></div>
          <div class="book_tabs"><a href="#">Money</a></div>
          <div class="book_tabs_right"></div>
		  
		  <div class="book_tabs_left"></div>
          <div class="book_tabs"><a href="#">Career</a></div>
          <div class="book_tabs_right"></div>
		  
		 <div class="book_tabs_left"></div>
          <div class="book_tabs"><a href="#">Fun</a></div>
          <div class="book_tabs_right"></div>
		  
		  <div class="book_tabs_left_love"></div>
          <div class="book_tabs_love"><a href="#">Community</a></div>
          <div class="book_tabs_right_love"></div>
		  
      </div>
      
      <div class="book_tabs_main" style="background-image:url('images/welcome-to-bg.png')">
          
         
          
          <div class="book_tabs_left_love"></div>
          <div class="book_tabs_love"><a href="#">Beauty</a></div>
          <div class="book_tabs_right_love"></div>
          
          <div class="book_tabs_left"></div>
          <div class="book_tabs"><a href="#">Wellness</a></div>
          <div class="book_tabs_right"></div>
          
      </div>
       <div style="clear:both"></div>
      
		  <div class="activate_friends_bg">
			<div class="book_page_left">
			<div class="chapter_love">Chapter - Community</div>
		  </div>
        
		  <div class="book_page_right">
			
			 <div class="chapter_love">Chapter - Beauty</div>
			 <div class="book_right_text">
			 
		  </div>
      </div>
     </div>
	 
	 </div>

<!-- END PAGE 5 -->

<!-- START PAGE 6 --> 
	<div class="activate_friends_main_top" style="background-image:url('images/welcome-to-bg.png')">
     <div class="book_tabs_main_left" style="background-image:url('images/welcome-to-bg.png')">
          <div class="book_tabs_left"></div>
		  <div class="book_tabs"><a href="#">Love</a></div>
		  <div class="book_tabs_right"></div>
		   
		  <div class="book_tabs_left"></div>
          <div class="book_tabs"><a href="#">Money</a></div>
          <div class="book_tabs_right"></div>
		  
		  <div class="book_tabs_left"></div>
          <div class="book_tabs"><a href="#">Career</a></div>
          <div class="book_tabs_right"></div>
		  
		 <div class="book_tabs_left"></div>
          <div class="book_tabs"><a href="#">Fun</a></div>
          <div class="book_tabs_right"></div>
		  
		  <div class="book_tabs_left"></div>
          <div class="book_tabs"><a href="#">Community</a></div>
          <div class="book_tabs_right"></div>
		  
		   <div class="book_tabs_left_love"></div>
          <div class="book_tabs_love"><a href="#">Beauty</a></div>
          <div class="book_tabs_right_love"></div>
		  
      </div>
      
      <div class="book_tabs_main" style="background-image:url('images/welcome-to-bg.png')">
          
          
          <div class="book_tabs_left_love"></div>
          <div class="book_tabs_love"><a href="#">Wellness</a></div>
          <div class="book_tabs_right_love"></div>
          
      </div>
       <div style="clear:both"></div>
      
		  <div class="activate_friends_bg">
			<div class="book_page_left">
			<div class="chapter_love">Chapter - Beauty</div>
		  </div>
        
		  <div class="book_page_right">
			
			 <div class="chapter_love">Chapter - Wellness</div>
			 <div class="book_right_text">
			 
		  </div>
      </div>
     </div>
	 
	 </div>

<!-- END PAGE 6 -->


<!-- END PAGES -->
</div>


<script type="text/javascript">

	$(window).ready(function() {
		$('#magazine').turn({
							display: 'single',
							acceleration: true,
							gradients: !$.isTouch,
							elevation:50,
							when: {
								turned: function(e, page) {
									/*console.log('Current view: ', $(this).turn('view'));*/
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

</script>


<div style="padding-bottom:60px;"></div>
</div>
<?php include('site_footer.php');?>
