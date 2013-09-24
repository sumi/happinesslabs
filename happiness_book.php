<?php
include_once "fbmain.php";
include('include/app-common-config.php');
?>
<?php include('site_header.php');?>

     

<!-- ================================== -->
<script type="text/javascript" src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="book/turn.min.js"></script>

<style type="text/css">
body{
	background:#ccc;
}
#magazine{
	width:576px;
	height:552px;
}
#magazine .turn-page{
	background-color:#ccc;
	background-size:100% 100%;
}
</style>
<div class="welcome_main" style="background-image:url('images/welcome-to-bg.png')">
     <div class="main_top">
       <div class="activate_friends_main"><img src="images/activate_-friends-logo.png" alt="" /></div>
     </div>
     <div style="clear:both"></div>

<div id="magazine" style="margin-left: 353px;">
<?php
$CatArray=array('Love','Money','Career','Community','Beauty','Wellness');
for($page=1;$page<=6;$page++){ ?>
<div class="main_top" style="background-image:url('images/welcome-to-bg.png')">
      
      <div class="book_tabs_main">
          
          <div class="<?=($page==1?'book_tabs_left_love':'book_tabs_left')?>"></div>
          <div class="<?=($page==1?'book_tabs_love':'book_tabs')?>"><a href="#">Love</a></div>
          <div class="<?=($page==1?'book_tabs_right_love':'book_tabs_right')?>"></div>
		  
		  <div class="<?=($page==2?'book_tabs_left_love':'book_tabs_left')?>"></div>
          <div class="<?=($page==2?'book_tabs_love':'book_tabs')?>"><a href="#">Money</a></div>
          <div class="<?=($page==2?'book_tabs_right_love':'book_tabs_right')?>"></div>
		  
		   <div class="<?=($page==3?'book_tabs_left_love':'book_tabs_left')?>"></div>
          <div class="<?=($page==3?'book_tabs_love':'book_tabs')?>"><a href="#">Career</a></div>
          <div class="<?=($page==3?'book_tabs_right_love':'book_tabs_right')?>"></div>
		  
		   <div class="<?=($page==4?'book_tabs_left_love':'book_tabs_left')?>"></div>
          <div class="<?=($page==4?'book_tabs_love':'book_tabs')?>"><a href="#">Community</a></div>
          <div class="<?=($page==4?'book_tabs_right_love':'book_tabs_right')?>"></div>
		  
		   <div class="<?=($page==5?'book_tabs_left_love':'book_tabs_left')?>"></div>
          <div class="<?=($page==5?'book_tabs_love':'book_tabs')?>"><a href="#">Beauty</a></div>
          <div class="<?=($page==5?'book_tabs_right_love':'book_tabs_right')?>"></div>
		  
		   <div class="<?=($page==6?'book_tabs_left_love':'book_tabs_left')?>"></div>
          <div class="<?=($page==6?'book_tabs_love':'book_tabs')?>"><a href="#">Wellness</a></div>
          <div class="<?=($page==6?'book_tabs_right_love':'book_tabs_right')?>"></div>
          
      </div>
       <div style="clear:both"></div>
      
      <div class="activate_friends_bg">
        <div class="book_page_right">
        
         <div class="chapter_love">Chapter - <?=$CatArray[($page-1)]?></div>
          <div class="book_right_text" style="display:<?=($page==1?'inline':'none')?>">
          
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
         
         
         <div class="book_right_friends_text">
         
      <div class="thumbnail-item">
		<a href="#">Share The good news with 
         friends</a><br />
		<div class="tooltip">
			<div class="tooltip_img"><img src="images/friend-images.png" alt="" /></div>
            <div class="tooltip_text">Maura Patel</div> 
			<span class="overlay"></span>
		</div> 
	</div>
       
    
    
      <div class="thumbnail-item">
		<a href="#">Share The good news with 
         friends</a><br />
		<div class="tooltip">
			<div class="tooltip_img"><img src="images/friend-images.png" alt="" /></div>
            <div class="tooltip_text">Vishal Patel</div> 
            <div class="tooltip_img"><img src="images/friend-images1.png" alt="" /></div>
            <div class="tooltip_text">Maura Patel</div> 
			<span class="overlay"></span>
        </div> 
	</div>
       
      <div class="thumbnail-item">
		<a href="#">Share The good news with 
         friends</a><br />
		<div class="tooltip">
			<div class="tooltip_img"><img src="images/friend-images1.png" alt="" /></div>
            <div class="tooltip_text">Vijay Patel</div> 
            <div class="tooltip_img"><img src="images/friend-images.png" alt="" /></div>
            <div class="tooltip_text">Maura Patel</div> 
            <div class="tooltip_img"><img src="images/friend-images1.png" alt="" /></div>
            <div class="tooltip_text">Vishal Patel</div> 
			<span class="overlay"></span>
		</div> 
	</div>
       
    
    
      <div class="thumbnail-item">
		<a href="#">Share The good news with 
         friends</a><br />
		<div class="tooltip">
			<div class="tooltip_img"><img src="images/friend-images.png" alt="" /></div>
            <div class="tooltip_text">Vijay Patel</div> 
            <div class="tooltip_img"><img src="images/friend-images1.png" alt="" /></div>
            <div class="tooltip_text">Maura Patel</div> 
            <div class="tooltip_img"><img src="images/friend-images.png" alt="" /></div>
            <div class="tooltip_text">Vijay Patel</div> 
            <div class="tooltip_img"><img src="images/friend-images1.png" alt="" /></div>
            <div class="tooltip_text">Maura Patel</div> 
			<span class="overlay"></span>
		</div> 
	</div>
    
         <a href="#">add to my life story book</a><br /><br />
         <a href="#">add to my life story book</a><br /><br />
         <a href="#">add to my life story book</a><br /><br />
         <a href="#">add to my life story book</a></div>
         
        </div>
      </div>
     </div>
<?php 
}
 ?>
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
