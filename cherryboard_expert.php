<?php
include_once "fbmain.php";
include('include/app-common-config.php');
$cherryboard_id=$_GET['cbid'];

//ADD PHOTO
$msg='';
if(isset($_POST['btnAddPhoto'])){
	$user_id=USER_ID;	
	$cherryboard_id=$_GET['cbid'];	
	$photo_name=trim($_FILES['image_attach']['name']);
	$photo_title=addslashes($_POST['photo_title']);
	
	if($photo_name!=""){
		$Photo_Source = $_FILES['image_attach']['tmp_name'];
		$FileName = rand().'_'.$photo_name;
		$ImagePath = "images/cherryboard/".$FileName;
		$CopyImage=copy($Photo_Source,$ImagePath);
		if($CopyImage){
			$insMeb="INSERT INTO `tbl_app_expert_cherry_photo` (`photo_id`, `user_id`, `cherryboard_id`, photo_title, `photo_name`, `record_date`) VALUES (NULL, '".$user_id."', '".$cherryboard_id."', '".$photo_title."', '".$FileName."', '".date('Y-m-d')."')";
			$insMebSql=mysql_query($insMeb);
			if($insMebSql){
				$msg="Photo uploaded successfully.";
			}
		}
	}
}

?>
<?php include('site_header.php');?>
<?php
	//Cheryboard Detail
	$cherrySel=mysql_query("select * from tbl_app_expert_cherryboard where cherryboard_id=".$cherryboard_id);
	while($cherryRow=mysql_fetch_array($cherrySel)){
		$cherrry_title=ucwords($cherryRow['cherryboard_title']);
	}
?>
<div id="wrapper">
	<div class="right">
<div id="my_cherryleaders">Gifts<br>
    <img src="images/img_day_thumb.jpg" class="thumb"><img src="images/img_day_thumb2.jpg" class="thumb"><img src="images/img_day_thumb3.jpg" class="thumb"><img src="images/img_day_thumb4.jpg" class="thumb"><img src="images/img_day_thumb.jpg" class="thumb"><br>
</div>
<br>
<br>
<div id="my_cherryleaders">Monthly Specials<br>
    <img src="images/img_day_thumb5.jpg" class="thumb"></div>
</div>
	<div id="left_container">
     <!--my cheery Experts start-->
        <div id="my_cherryleaders">Inspirational Experts<br>
    <?php
		$selQuery="select b.user_id,b.fb_photo_url from tbl_app_expert_cherryboard_meb a,tbl_app_users b where a.req_user_fb_id=b.facebook_id and a.cherryboard_id=".$cherryboard_id." limit 10";
		$selSqlQ=mysql_query($selQuery);
		$FriendsArray=array();
		if(mysql_num_rows($selSqlQ)>0){
			$cnt=1;
			while($rowTbl=mysql_fetch_array($selSqlQ)){
				$FriendsArray[]=$rowTbl['user_id'];
				if($cnt==6){echo "<br/>";}
				echo '<img src="'.$rowTbl['fb_photo_url'].'" class="thumb">';
				$cnt++;
			}
			
		}else{
			echo "No Friends";
		}
	?>
	 </div>
        <!--my cheery Experts End-->
        
    </div>
	
    <div id="middle_wrapper" style="margin-left:250px; width:470px;" >
    <?php if($msg!=""){ ?>
		<h1 style="font-size:12px"><font color="#009900"><?php echo $msg;?></font></h1>
		<?php }	?>
    	<h1><?php echo $cherrry_title;?></h1><br>
    <form name="form1" method="post" action="" enctype="multipart/form-data">
          <label><input name="photo_title" onblur="if(this.value=='') this.value='add photo comment';" onfocus="if(this.value=='add photo comment')  this.value='';" value="add photo comment" type="text" class="input_450" id="photo_title"></label><input name="btnAddPhoto" type="submit" value="Post" title="Post" class="btn_small">
      <br>
      <br>
	  <input type="file" name="image_attach" />
      <!-- <a href="#" class="red_link_14">+add a photo</a> -->
    </form><br>
  </div>
   <div class="clear"></div>
</div>
<div id="body_container">
	<div class="wrapper">
        <div id="checklist"><h2>Checklist</h2>
          <input name="txt_checklist" id="txt_checklist" type="text" onfocus="if(this.value=='add something to your checklist') this.value='';" onblur="if(this.value=='') this.value='add something to your checklist';" class="input_200" value="add something to your checklist">
          </label><input name="Submit" type="button" onclick="add_checklist('add_checklist_expert',document.getElementById('txt_checklist').value,<?php echo $cherryboard_id;?>)" value="Post" title="Post" class="btn_small" style="margin:0px;">
          <br>
          <br>
		  <div id="div_checklist">
		  <?php
			 $selchk=mysql_query("select *,date_format(record_date,'%m-%d-%Y') as record_date from tbl_app_expert_checklist where cherryboard_id=".$cherryboard_id." order by checklist_id desc limit 10");
			$checkCnt='';
			while($selchkRow=mysql_fetch_array($selchk)){
				$checklist=$selchkRow['checklist'];
				$record_date=$selchkRow['record_date'];
				$checkCnt.='<div class="box_container">'.$checklist.'<span class="smalltext">added '.$record_date.'</span></div><br/><br/>';
			}
			echo $checkCnt;
		 ?>
		</div>
      </div>
	  <div id="right_container" style="width:440px;">
	  	  <?php
			 $selphoto=mysql_query("select * from tbl_app_expert_cherry_photo where cherryboard_id=".$cherryboard_id." order by photo_id desc");
			$phtotoCnt='';
			while($selphotoRow=mysql_fetch_array($selphoto)){
				$photo_id=$selphotoRow['photo_id'];
				$photo_title=ucwords(stripslashes($selphotoRow['photo_title']));
				$photo_name=$selphotoRow['photo_name'];
				$photoPath='images/cherryboard/'.$photo_name;
				if(is_file($photoPath)){
				?><div class="field_container"><img src="<?php echo $photoPath;?>" height="195" width="195"><br><?php echo $photo_title;?><br><br><br>
				<div id="div_cherry_comment_<?php echo $photo_id;?>">
				0 Cheers
				<?php
				$selCmt=mysql_query("select * from tbl_app_expert_cherry_comment where photo_id=".$photo_id." order by comment_id desc limit 2");
				$TotalCmt=mysql_num_rows($selCmt);
				echo '<div class="right">'.(int)$TotalCmt.' Comments</div>';
				if($TotalCmt>0){
				  while($cmtRow=mysql_fetch_array($selCmt)){
				   $userPhotoArray=getFieldsValueArray('first_name,last_name,fb_photo_url','tbl_app_users','user_id='.$cmtRow['user_id']);
				   $UserName=$userPhotoArray[0].' '.$userPhotoArray[1];
				   $UserPhoto=$userPhotoArray[2];
				   $PhotoComment=stripslashes($cmtRow['cherry_comment']);
				   ?>
							<div class="comment" style="height:35"><img src="<?php echo $UserPhoto;?>" height="30" width="30" class="img_thumb1"><strong><?php echo $UserName;?></strong>&nbsp;&nbsp;<?php echo $PhotoComment;?><br /></div>
			<?php 
				}
			}?>
			
			</div>
					 <label>
					  <textarea name="cherry_comment_<?php echo $photo_id;?>" class="input_comments" id="cherry_comment_<?php echo $photo_id;?>" onfocus="if(this.value=='Leave your comment here') this.value='';" onblur="if(this.value=='') this.value='Leave your comment here';">Leave your comment here</textarea>
					  <br><br>
					  <input name="Submit2" type="button" onclick="add_cherry_comment('add_cherry_comment_expert','<?php echo $cherryboard_id;?>','<?php echo $photo_id;?>','<?php echo USER_ID;?>',document.getElementById('cherry_comment_<?php echo $photo_id;?>').value)" value="Post" title="Post" class="btn_small right" style="margin:0px;">
					  <a href="#" class="red_link_14">+give cheers!</a><br>
					  <br>
					  </label>
				</div>	
					<?php
				}
			}
			echo $phtotoCnt;
		 ?>
            
       </div>
	   <div id="inspir_feed">
          <h2>Inspir-feed</h2>
		  <?php
		  $FriendsString=implode(',',$FriendsArray);
		  $getPhotoFeed=mysql_query("select user_id,record_date from tbl_app_expert_cherry_photo where user_id in (".$FriendsString.")");
		  $FeedArray=array();
		  while($PhotoFeedRow=mysql_fetch_array($getPhotoFeed)){
		  	$FeedArray[$PhotoFeedRow['record_date']]=array("type"=>'photo',"user_id"=>$getPhotoFeed['user_id'],"photo_id"=>$getPhotoFeed['photo_id']);
		  }
		  foreach($FeedArray as $actionArray){
		  	foreach($actionArray as $actionDetail){
				$actionUser=$actionDetail['user_id'];
				echo '<div class="feed"><img src="images/img_cherryleaders6.jpg" width="30" height="30" class="imgthumb"><strong>Lila Kooklan</strong> posted a new photo in career savvy<br>5 minutes ago</div>';
			}
		  }
		  ?>
		  <div class="feed"><img src="images/img_cherryleaders6.jpg" width="30" height="30" class="imgthumb"><strong>Lila Kooklan</strong> posted a new photo in career savvy<br>5 minutes ago</div>
      </div>
        <div class="clear"></div>        
  </div>
</div>

<?php include('site_footer.php');?>