<?php
include_once "fbmain.php";
include('include/app-common-config.php');
error_reporting(0);
$type=$_REQUEST['type'];
$cherryboard_id=$_REQUEST['cherryboard_id'];

$uploaddir = 'images/expertboard/temp/'; 
$file = $uploaddir ."cherry-".basename($_FILES['uploadfile']['name']); 
$file_name= "cherry-".$_FILES['uploadfile']['name'];
//photo cancel
if($type=="cancel"){
	unlink($_REQUEST['file_name']);
}else if (move_uploaded_file($_FILES['uploadfile']['tmp_name'], $file)) { 
    echo "<input name=\"button\" type=\"button\" onclick=\"add_photo('expert','".$file."')\" value=\"Post\" title=\"Post\" class=\"btn_small right\">
		  <div class=\"comment_box\">
		  <div id=\"files\"><img src=\"".$file."\" alt=\"\" height=\"100\" width=\"100\" class=\"image\" /></div>
	<textarea name=\"txtcomment\" rows=\"5\" class=\"textfield\" id=\"txtcomment\" onfocus=\"if(this.value=='Write your comment here...') this.value='';\" onblur=\"if(this.value=='') this.value='Write your comment here...';\">Write your comment here...</textarea> 
		  <div class=\"styleall\"><a href=\"#\" onclick=\"photo_cancel('expert','".$file."')\" class=\"right gray_link\">
		  <img src=\"images/close_small1.png\"> Cancel</a>
		  </div> 	
		  <div class=\"clear\"></div></div>";
}//End of if
//=================> Start Insert Code <===================
if($type=="add"){
   $rnd=rand();
   $file_name=$_REQUEST['file_name'];
   $user_id=$_REQUEST['user_id'];
   $comment=$_REQUEST['comment'];
   $val=explode("-",$file_name);
   $photo_name=$rnd.'_'.$val[1];//photo_path set in db
   $uploaddir='images/expertboard/'.$photo_name;
   $uploaddirThumb='images/expertboard/thumb/'.$photo_name;
   $old_uploaddir='images/expertboard/temp/cherry-'.$val[1];
   
   //for local due to ImageMagic not working in local
   if($_SERVER['SERVER_NAME']=="localhost"){
   		$retval=copy($old_uploaddir,$uploaddir);
		$retval=copy($old_uploaddir,$uploaddirThumb);
   }else{
	    $thumb_command=$ImageMagic_Path."convert ".$old_uploaddir." -thumbnail 195 x 195 ".$uploaddir;
		$last_line=system($thumb_command, $retval);
   		$thumb_command_thumb=$ImageMagic_Path."convert ".$old_uploaddir." -thumbnail 60 x 60 ".$uploaddirThumb;
		$last_line=system($thumb_command_thumb, $retval);
   }
   if($retval){
   		if($comment=="Write your comment here..."){
			$comment='';
		}
    	$insert_qry="INSERT INTO `tbl_app_expert_cherry_photo`(`photo_id`, `user_id`, `cherryboard_id`, `photo_title`, `photo_name`) VALUES ('',".$user_id.",".$cherryboard_id.",'".$comment."','".$photo_name."')";
   
   		$insert_qry_res=mysql_query($insert_qry);
		unlink($old_uploaddir);
		echo "<span class=\"fgreen\">Photo added successfully.</span>";
		echo "##===##";
   }else{
    echo "Photo Inserting Error...";
	unlink($_REQUEST['file_name']);
   } 
}//end of Submit  

//DELETE PHOTO
if($type=="del_expert_photo"&&$_GET['del_photo_id']>0){
	$del_photo_id=$_GET['del_photo_id'];
	$photo_name=getFieldValue('photo_name','tbl_app_expert_cherry_photo','photo_id='.$del_photo_id);
	$photo_path='images/expertboard/'.$photo_name;
	if(is_file($photo_path)){
		unlink($photo_path);
		$del_photo=mysql_query('delete from tbl_app_expert_cherry_photo where photo_id='.$del_photo_id);
	}
}

//EXPERTBOARD PHOTOS
			 $selphoto=mysql_query("select *,date_format(record_date,'%m/%d/%Y') as record_date  from tbl_app_expert_cherry_photo where cherryboard_id=".$cherryboard_id." order by photo_id desc");
			$photoCnt='';
			$cntPhoto=mysql_num_rows($selphoto);
			while($selphotoRow=mysql_fetch_array($selphoto)){
				$photo_id=$selphotoRow['photo_id'];
				$photo_title=ucwords(stripslashes($selphotoRow['photo_title']));
				$photo_name=$selphotoRow['photo_name'];
				$record_date=$selphotoRow['record_date'];
				$photoPath='images/expertboard/'.$photo_name;
				if(is_file($photoPath)){
				   $photoCnt.='<div class="field_container2">
				   
				   <div class="day_container">Day '.$cntPhoto.'</div>
						  <div class="tag_container">
							<div class="comment_box1">'.stripslashes($photo_title).'</div><div class="clear"></div>
								<div class="info_box">
									<div class="score">Day '.$cntPhoto.'</div>
									<div class="date">'.$record_date.'</div>
								 </div>
								 <div class="b_arrow"></div>
							 <div class="clear"></div>
						 </div>
						 
					<div class="img_big_container">
							<div class="feedbox_holder">
								<div class="actions"><a class="delete" href="#" onclick="photo_action(\'del_expert_photo\','.$cherryboard_id.','.$photo_id.')"><img src="images/delete.png"></a></div>
							 </div>
							  <img src="'.$photoPath.'">
						 </div>
						 
					   <div id="div_cherry_comment_'.$photo_id.'">';
							$TotalCmt=getFieldValue('count(photo_id)','tbl_app_expert_cherry_comment','photo_id='.$photo_id);
							$TotalCheers=getFieldValue('count(cheers_id)','tbl_app_expert_cherryboard_cheers','photo_id='.$photo_id);
							$checkCheers=(int)getFieldValue('user_id','tbl_app_expert_cherryboard_cheers','photo_id='.$photo_id.' and user_id='.USER_ID);
							if($checkCheers==0){
								$cheersLink='<a href="javascript:void(0);" onclick="add_cherry_cheers(\'add_expert_cheers\',\''.$photo_id.'\',\''.$cherryboard_id.'\',\''.USER_ID.'\')" class="red_link_14" style="font-size:12px;">+give cheers!</a>';
							}else{$cheersLink='';}
								$photoCnt.=$cheersLink.'<div class="right smalltext1" id="div_photo_cheers_'.$photo_id.'">'.(int)$TotalCheers.' Cheers &nbsp;&nbsp;'.(int)$TotalCmt.' Comments</div><br><br>';
							if($TotalCmt>0){
							  $selCmt=mysql_query("select * from tbl_app_expert_cherry_comment where photo_id=".$photo_id." order by comment_id desc limit 2");
							  while($cmtRow=mysql_fetch_array($selCmt)){
								   $userPhotoArray=getFieldsValueArray('first_name,last_name,fb_photo_url','tbl_app_users','user_id='.$cmtRow['user_id']);
								   $UserName=$userPhotoArray[0].' '.$userPhotoArray[1];
								   $UserPhoto=$userPhotoArray[2];
								   $comment_id=$cmtRow['comment_id'];
								   $PhotoComment=stripslashes($cmtRow['cherry_comment']);
								  
								   
								   $photoCnt.='<div class="comment2" style="height:35">
									  <div class="feedbox_holder">
										<div class="actions"><a class="delete" href="javascript:void(0);" onclick="add_cherry_comment(event,\'del_cherry_expert_comment\','.$cherryboard_id.','.$photo_id.','.$cmtRow['user_id'].','.$comment_id.')"><img src="images/delete.png"></a></div>
									  </div>
									  <img src="'.$UserPhoto.'" height="30" width="30" class="img_thumb1"><strong>'.$UserName.'</strong>&nbsp;&nbsp;'.$PhotoComment.'</div>';
							  }
							}
					$photoCnt.='</div>
						  <textarea name="cherry_comment_'.$photo_id.'" class="input_comments" id="cherry_comment_'.$photo_id.'" onfocus="if(this.value==\'Leave your comment here\') this.value=\'\';" onblur="if(this.value==\'\') this.value=\'Leave your comment here\';" onkeypress="return add_cherry_comment(event,\'add_cherry_expert_comment\',\''.$cherryboard_id.'\',\''.$photo_id.'\',\''.USER_ID.'\',document.getElementById(\'cherry_comment_'.$photo_id.'\').value)">Leave your comment here</textarea>
						  </div>';
				}
			
				$cntPhoto--;
			}
			echo $photoCnt;
//Inspir-feed

?>


