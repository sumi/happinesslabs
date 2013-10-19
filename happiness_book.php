<?php
include_once "fbmain.php";
include('include/app-common-config.php');
$userDetail=getUserDetail(USER_ID,'uid');
$photo_url=$userDetail['photo_url'];
$user_name=$userDetail['name'];
?>
<?php include('site_header.php');?>     
<!-- ================================== -->
<!-- Include stylesheet happiness book -->
<link rel="stylesheet" type="text/css" href="css/happiness_book.css" />

<script type="text/javascript" src="book/turn-jquery.js"></script>
<script type="text/javascript" src="book/turn.js"></script>

<div class="relationship_bg" style="padding:50px 0; background-color:#FFFFFF;">
<div id="magazine" style="margin:auto;">
<?php
$pillarCnt=1;
$pillarArray=array();
$pillarTitleArray=array();
$selPillar=mysql_query("SELECT pillar_no,title FROM tbl_app_happiness_pillar WHERE parent_id=0 ORDER BY pillar_no");
while($selPillarRow=mysql_fetch_array($selPillar)){
	 $pillar_no=$selPillarRow['pillar_no'];
	 $title=ucwords($selPillarRow['title']);
	 $pillarArray[$pillarCnt]=$pillar_no;
	 $pillarTitleArray[$pillarCnt]=$title;
	 $pillarCnt++;
}

$cnt=1;
foreach($pillarArray as $pillar_no){	
	if($cnt==1){
?>
		<!-- FRONT PAGE -->
		<div class="welcome_main" style="background-color:#FFFFFF">
			 <div style="clear:both"></div>
			 <div class="activate_friends_main_top" style="width:569px">
			   <div class="book_tabs_main_page">
			   <?php
			   $pillarCnt=1;
			   $selPillar=mysql_query("SELECT title FROM tbl_app_happiness_pillar WHERE parent_id=0 ORDER BY pillar_no");
			   while($selPillarRow=mysql_fetch_array($selPillar)){
					 $title=trim(ucwords($selPillarRow['title']));
					
						echo '<div class="book_tabs_left"></div>
							  <div class="book_tabs"><a href="#" onclick="trun_next()">'.$title.'</a></div>
							  <div class="book_tabs_right"></div>';
					 
					 $pillarCnt++;
			   }
			   ?>
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

		<?php
	}

	//LEFT SIDE
	?>
	<!-- PAGE 1 -->
<div class="welcome_main" style="background-color:#FFFFFF">
     <div style="clear:both"></div>
     <div class="activate_friends_main_top">
       <div class="book_tabs_main_page_left">
       <?php
	   for($i=1;$i<=$cnt;$i++){
			echo '<div class="book_tabs_left'.($i==$cnt?'_love':'').'"></div>
				  <div class="book_tabs'.($i==$cnt?'_love':'').'"><a href="#" onclick="trun_privious()">'.$pillarTitleArray[$i].'</a></div>
				  <div class="book_tabs_right'.($i==$cnt?'_love':'').'"></div>';
	   }
	   ?>
      </div>
       <div style="clear:both"></div>
      
      <div class="activate_friends_bg">
        <div class="book_page_right">        
         <div class="chapter_love">Chapter - <?=$pillarTitleArray[$cnt]?></div>
         <div class="book_right_text">
       <?php
	   $selSubPlr=mysql_query("SELECT * FROM tbl_app_happiness_pillar WHERE parent_id=".$pillarArray[$cnt]." ORDER BY pillar_no");
	   while($selSubPlrRow=mysql_fetch_array($selSubPlr)){
	   		 $pillar_no=(int)$selSubPlrRow['pillar_no'];
	   		 $title=trim(ucwords($selSubPlrRow['title']));
			 echo $title."<br />";
			 $selQry=mysql_query("SELECT * FROM tbl_app_life_story_book_template WHERE pillar_no=".$pillar_no);
		     while($selQryRow=mysql_fetch_array($selQry)){
				   $cherryboard_id=(int)$selQryRow['cherryboard_id'];	
				   $subTitle=trim(ucwords($selQryRow['title']));
				   if($cherryboard_id>0){
					  $checkBoard=(int)getFieldValue('cherryboard_id','tbl_app_expert_cherryboard','doit_id="'.$cherryboard_id.'" AND user_id='.USER_ID);
					  $expertboard_id=(int)getFieldValue('expertboard_id','tbl_app_expert_cherryboard','cherryboard_id='.$cherryboard_id);
				   }
				   if($checkBoard==0&&$cherryboard_id>0){
					  echo '<div id="div_doit_story">&nbsp;&nbsp;&nbsp;'.$subTitle.'&nbsp;<a href="javascript:void(0);" style="text-decoration:none;" onclick="ajax_action(\'doit_story\',\'div_doit_story\',\'cherryboard_id='.$cherryboard_id.'&expertboard_id='.$expertboard_id.'&user_id='.USER_ID.'\')">Share the good news with friends</a></div>';
				   }else if($checkBoard>0&&$cherryboard_id>0){
					  echo '&nbsp;&nbsp;&nbsp;'.$subTitle.'&nbsp;<a href="expert_cherryboard.php?cbid='.$checkBoard.'" style="text-decoration:none;">View story</a><br />';
				   }else{
					  echo '&nbsp;&nbsp;&nbsp;'.$subTitle.'<br/>';
				   }					 
		     }
	   }
	   ?>
		</div>         
        </div>      
      </div>
     </div>
   </div>
	<?php
	
	//RIGHT SIDE
	if($cnt<count($pillarArray)){
	?>
	<div class="welcome_main" style="background-color:#FFFFFF">
     <div style="clear:both"></div>
     <div class="activate_friends_main_top">
       <div class="book_tabs_main_page">         
          <?php
			  for($i=$cnt+1;$i<=count($pillarArray);$i++){
				echo '<div class="book_tabs_left'.($i==($cnt+1)?'_love':'').'"></div>
				  <div class="book_tabs'.($i==($cnt+1)?'_love':'').'"><a href="#" onclick="trun_next()">'.$pillarTitleArray[$i].'</a></div>
				  <div class="book_tabs_right'.($i==($cnt+1)?'_love':'').'"></div>';

		   
		   }
		   ?>
      </div>
       <div style="clear:both"></div>
      
      <div class="activate_friends_bg">
        <div class="book_page_right1">
        
         <div class="chapter_love">Chapter - <?=$pillarTitleArray[$cnt+1]?></div>
         <div class="book_right_text">
         <?php
	   $selSubPlr=mysql_query("SELECT * FROM tbl_app_happiness_pillar WHERE parent_id=".$pillarArray[$cnt+1]." ORDER BY pillar_no");
		 while($selSubPlrRow=mysql_fetch_array($selSubPlr)){
		 	   $pillar_no=(int)$selSubPlrRow['pillar_no'];
			   $title=trim(ucwords($selSubPlrRow['title']));
			   echo $title."<br />";
			   $selQry=mysql_query("SELECT * FROM tbl_app_life_story_book_template WHERE pillar_no=".$pillar_no);
		       while($selQryRow=mysql_fetch_array($selQry)){
				   $cherryboard_id=(int)$selQryRow['cherryboard_id'];	
				   $subTitle=trim(ucwords($selQryRow['title']));
				   if($cherryboard_id>0){
					  $checkBoard=(int)getFieldValue('cherryboard_id','tbl_app_expert_cherryboard','doit_id="'.$cherryboard_id.'" AND user_id='.USER_ID);
					  $expertboard_id=(int)getFieldValue('expertboard_id','tbl_app_expert_cherryboard','cherryboard_id='.$cherryboard_id);
				   }
				   if($checkBoard==0&&$cherryboard_id>0){
					  echo '<div id="div_doit_story">&nbsp;&nbsp;&nbsp;'.$subTitle.'&nbsp;<a href="javascript:void(0);" style="text-decoration:none;" onclick="ajax_action(\'doit_story\',\'div_doit_story\',\'cherryboard_id='.$cherryboard_id.'&expertboard_id='.$expertboard_id.'&user_id='.USER_ID.'\')">Share the good news with friends</a></div>';					  
				   }else if($checkBoard>0&&$cherryboard_id>0){
					  echo '&nbsp;&nbsp;&nbsp;'.$subTitle.'&nbsp;<a href="expert_cherryboard.php?cbid='.$checkBoard.'" style="text-decoration:none;">View story</a><br />';
				   }else{
					  echo '&nbsp;&nbsp;&nbsp;'.$subTitle.'<br/>';
				   }					 
		       }
		 }
		 ?> 
         </div>
        </div>
      </div>
     </div>
   </div>
	<?php
	}
	$cnt++;
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
