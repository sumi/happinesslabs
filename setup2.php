<?php
include_once "fbmain.php";
include('include/app-common-config.php');

$_SESSION['select_gifts']=array();

?>
<?php include('site_header.php');?>	
<!--Body Start-->
<form method="post" name="frmsetup" enctype="multipart/form-data">
<div id="wrapper">
	<div class="wrapper_960">
	  <div align="center" class="head_20">Select a gift for motivation.<br/> Update a picture daily to your goal storyboard for 30 days to win the gift.<br>
	 </div>
	 <div align="center" class="head_20">
	 <!-- START ADD CATEGORY--- -->
		<a id="go" rel="leanModal" href="#add_category" name="test" class="btn_small" title="+ Add Category">+ Add Category</a>
		<div style="display: none; position: fixed; opacity: 1; z-index: 11000; left: 50%; margin-left: -330px; top: 200px;" id="add_category" align="center">
                <a class="modal_close" href="#" title="close"></a>
                <span class="head_20">Add Category</span><br>
				<div id="setup_add_category" class="msg_red">&nbsp;</div>
				<input type="text" name="category_name" id="category_name" onBlur="if(this.value=='') this.value='Enter category name';" onFocus="if(this.value=='Enter category name') this.value='';" value="Enter category name" />
				<input type="button" onclick="javascript:ajax_action('add_category','setup_add_category','category_name='+document.getElementById('category_name').value)" value="Add Category" name="btnAddCat" class="btn_small" id="button4" title="Add Category" />
	 </div>
<!-- END ADD CATEGORY--- -->
	<!-- START ADD GIFT--- -->
	
            <div style="display: none; opacity: 0.5;" id="lean_overlay"></div>      
      <a id="go" rel="leanModal" href="#add_gift" name="test" class="btn_small" title="+ Add Campaign">+ Add Campaign</a>

	 <!-- END ADD GIFT--- -->
            <div style="display: none; opacity: 0.5;" id="lean_overlay"></div>
	  </div>
	     <input type="hidden" name="gosetup3" id="gosetup3" value="<?=(int)$_SESSION['select_gifts']?>"/>
		<div id="div_get_gifts">
			<div class="left" style="padding:20px 0px; display:block; width:100%;">
				<?php
				$sel_query=mysql_query("select * from tbl_app_category order by category_id");
				$categoryCnt='';
				while($sel_row=mysql_fetch_array($sel_query))
				{
					if($categoryCnt==""){
						$selectedColor=' style="background: none repeat scroll 0 0 #C02336;color: #FFFFFF;"';
						$whereCnd=" and category_id=".$sel_row['category_id'];
					}else{
						$selectedColor='';
					}	
					$categoryCnt.='<a href="#" onclick="ajax_action(\'get_gifts\',\'div_get_gifts\',\'category_id='.$sel_row['category_id'].'&user_id='.USER_ID.'\')" class="gray_tag" '.$selectedColor.'>'.ucwords($sel_row['category_name']).'</a>';
				}	
				echo $categoryCnt;
				?>
			  </div>
			  <div id="div_msg" style="float:left;background:#C02336;color:#FFFFFF;font-size:12px"></div>
<div class="clear"></div>
		  <?php
		  $GiftCnt='';
		  $selGift=mysql_query("select * from tbl_app_gift where is_system='1' ".$whereCnd." order by gift_id");
		  if(mysql_num_rows($selGift)>0){
			  while($rowGift=mysql_fetch_array($selGift)){
				$gift_id=$rowGift['gift_id'];
				$gift_title=$rowGift['gift_title'];
				$gift_photo=$rowGift['gift_photo'];
				$category_id=$rowGift['category_id'];
				$campaign_title=ucwords($rowGift['campaign_title']);
				$sponsor_name=ucwords($rowGift['sponsor_name']);
				$sponsor_logo=$rowGift['sponsor_logo'];
				$sponsorPath='images/gift/'.$sponsor_logo;
				$cherryboard_id=(int)getFieldValue('cherryboard_id','tbl_app_cherry_gift','gift_id='.$gift_id);
				
				$GiftCnt.='<div class="gift1"><a href="gift_profile.php?gid='.$gift_id.'"><img src="images/gift/'.$gift_photo.'" class="imgbig"></a><br>
				<strong>'.$gift_title.'</strong><br>';
				//winner photo
				$selWin="select a.user_id,b.fb_photo_url,b.first_name,b.last_name from tbl_app_cherry_gift a,tbl_app_users b where a.gift_id='".$gift_id."' and a.user_id=b.user_id group by a.user_id order by a.cherry_gift_id limit 5";
				 $sqlWin=mysql_query($selWin);
				 $winnersPhoto='';	
			     if(mysql_num_rows($sqlWin)>0){
				 	while($selWinRow=mysql_fetch_array($sqlWin)){
						$imgUrl=$selWinRow['fb_photo_url'];
						$winner_title=$selWinRow['first_name'].' '.$selWinRow['last_name'];
						if(trim($imgUrl!='')){						
				 		$winnersPhoto.='<img src="'.$imgUrl.'" alt="'.$winner_title.'" style="cursor:pointer" title="'.$winner_title.'" class="imgsmall">';
						}
				   }
				 }
				if($winnersPhoto!=""){
					$GiftCnt.='Recent Winners<br/><div style="text-align:center">'.$winnersPhoto.'</div>';
				}
				$GiftCnt.='
				'.(trim($campaign_title)!=''?'<a href="cherryboard.php?cbid='.$cherryboard_id.'" style="text-decoration:none;color:#990000">'.$campaign_title.'</a><br>':'').'
				'.(trim($sponsor_name)!=''?'Sponsored by :'.$sponsor_name.'<br>':'').'
				'.(is_file($sponsorPath)?'<img src="'.$sponsorPath.'" class="imgsmall"><br>':'').'
				<div class="thumb_container">
				<div class="clear">
				  <label>';
				 $GiftCnt.='<input type="checkbox" '.(in_array($category_id.'_'.$gift_id,$_SESSION['select_gifts'])?'checked="checked"':'').' onclick="select_gift(\'select_gift\',this.value,this.name)" name="chk_gift_'.$gift_id.'" value="'.$category_id.'_'.$gift_id.'" id="chk_gift_'.$gift_id.'">
				  </label>
				</div></div>    
			  </div>';
			 } 
		 }else{
		 	$GiftCnt.='No Gift';
		 }
		 
		 $GiftCnt.='<div class="clear"></div><input type="button" onclick="goto_step3();" name="btnGift" value="Next" class="right btn_small">';
		  
		 echo $GiftCnt;
		 ?>
	  </div>
	  <div class="clear"></div> 
  </div>
<div class="clear"></div> 
</div>
</form>
<!--Gray body Start-->
<!--Gray body End-->
<!--Body End-->
<!--Footer Start-->
<?php include('site_footer.php');?>