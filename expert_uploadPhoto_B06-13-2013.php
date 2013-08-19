<?php
include_once "fbmain.php";
include('include/app-common-config.php');
error_reporting(0);
$type=$_REQUEST['type'];
$cherryboard_id=$_REQUEST['cherryboard_id'];
$expertboard_id=getFieldValue('expertboard_id','tbl_app_expert_cherryboard','cherryboard_id='.$cherryboard_id);

//Buyer Detail
$Buyer_id=getFieldValue('user_id','tbl_app_expert_cherryboard','cherryboard_id='.$cherryboard_id);
$BuyerDetail=getUserDetail($Buyer_id);
$BuyerName=$BuyerDetail['name'];


$new_rotate_degree=90;
$rotate_img='round_arrow_90.jpg';

$uploaddir = 'images/expertboard/temp/';
$fname=$_FILES['uploadfile']['name'];
$fname=str_replace(' ','_',$fname);
$fname=str_replace('-','_',$fname);
$fname=str_replace('(','_',$fname);
$fname=str_replace(')','_',$fname);
$file = $uploaddir ."cherry-".$fname; 
//photo cancel
if($type=="cancel"){
	unlink($_REQUEST['file_name']);
}else if (move_uploaded_file($_FILES['uploadfile']['tmp_name'],$file)) { 
    echo "<div class=\"comment_box\">
		  <Table><tr><Td>
		  <div id=\"files\"><img src=\"".$file."\" alt=\"\" height=\"100\" width=\"100\" class=\"image\" /></div>
		  </td><Td>
	<textarea name=\"txtcomment\" rows=\"5\" class=\"textfield\" id=\"txtcomment\" onfocus=\"if(this.value=='Write your comment here...') this.value='';\" onblur=\"if(this.value=='') this.value='Write your comment here...';\">Write your comment here...</textarea> 
	</td></tr>
	<tr><td>
		  <div class=\"styleall\"><a href=\"#\" onclick=\"photo_cancel('expert','".$file."')\" class=\"right gray_link\">
		  <img src=\"images/close_small1.png\"> Cancel</a>
		  </div> 	
		  </td><td>
		  <input name=\"button\" type=\"button\" onclick=\"add_photo('expert','".$file."')\" value=\"Post\" title=\"Post\" class=\"btn_small right\">
		  </td>
		  </tr>
	</table>
		  <div class=\"clear\"></div></div>";
}else if($type=="rotate") {
	$photo_id=(int)$_GET['photo_id'];
	if($photo_id>0){
		$file_name=getFieldValue('photo_name','tbl_app_expert_cherry_photo','photo_id='.$photo_id);
		$rotate_degree=$_GET['rotate_degree'];
		$rotate_img='round_arrow_90.jpg';
		$new_rotate_degree=90;
		$newFileName=rand().'_'.$file_name;
		if($rotate_degree==90){
			$new_rotate_degree=180;
			$rotate_img='round_arrow_180.jpg';
		}else if($rotate_degree==180){
			$new_rotate_degree=270;
			$rotate_img='round_arrow_270.jpg';
		}else if($rotate_degree==270){
			$new_rotate_degree=360;
			$rotate_img='round_arrow_0.jpg';
		}
		$old_uploaddir='images/expertboard/temp/'.$file_name;
		$new_uploaddir='images/expertboard/temp/'.$newFileName;
		
		$uploaddir='images/expertboard/'.$newFileName;
	    $uploaddirThumb='images/expertboard/thumb/'.$newFileName;
	    
		
	    //Rotate Image
	    //for local due to ImageMagic not working in local
	    if($_SERVER['SERVER_NAME']=="localhost"){
			$last_line=copy($old_uploaddir,$new_uploaddir);
			$last_line=copy($old_uploaddir,$uploaddir);
			$last_line=copy($old_uploaddir,$uploaddirThumb);
	    }else{
			$command='convert -rotate 90 '.$old_uploaddir.' '.$new_uploaddir;
			passthru($command);
			$thumb_command=$ImageMagic_Path."convert ".$new_uploaddir." -thumbnail 195 x 195 ".$uploaddir;
			$last_line=system($thumb_command, $retval);
			$thumb_command_thumb=$ImageMagic_Path."convert ".$new_uploaddir." -thumbnail 60 x 60 ".$uploaddirThumb;
			$last_line=system($thumb_command_thumb, $retval);
	    }
		
			$upd_qry="update `tbl_app_expert_cherry_photo` set photo_name='".$newFileName."' where photo_id=".$photo_id;
			$upd_qrySql=mysql_query($upd_qry);
			if($upd_qrySql){
				unlink($old_uploaddir);
				unlink('images/expertboard/'.$file_name);
				unlink('images/expertboard/thumb/'.$file_name);
			}	
		
		//PHOTO DISPLAY SECTION
			$photoCnt='';
			$selphoto=mysql_query("select * from tbl_app_expert_cherry_photo where photo_id=".$photo_id);
			while($selphotoRow=mysql_fetch_array($selphoto)){
				$user_id=$selphotoRow['user_id'];
				$photo_id=$selphotoRow['photo_id'];
				$swap_id=$photo_id;
				$photo_title=ucwords(stripslashes($selphotoRow['photo_title']));
				$photo_name=$selphotoRow['photo_name'];
				$record_date=$selphotoRow['new_record_date'];
				$photoPath='images/expertboard/'.$photo_name;
				$photo_day=(int)$selphotoRow['photo_day'];
				if($photo_title==""){
					$photo_title='<div style="width:180px;height:18px">&nbsp;</div>';
				}
				
				if(is_file($photoPath)){
				   $photoCnt.='
 					<div class="day_container">Day '.$photo_day.'</div>
						  <div class="tag_container">
							<div class="comment_box1" id="photo_title'.$photo_id.'"><a href="javascript:void(0);" '.($user_id==USER_ID?'ondblclick="ajax_action(\'upd_photo_title\',\'photo_title'.$photo_id.'\',\'stype=eadd&photo_id='.$photo_id.'\')"':'').'title="Edit Comment" class="cleanLink">'.$photo_title.'</a></div><div class="clear"></div>
								<div class="info_box">
									<div class="score">'.$photo_day.'</div>
									<div class="date">'.$record_date.'</div>
								 </div>
								 <div class="b_arrow"></div>
							 <div class="clear"></div>
						 </div>';
						$photoCnt.='<div class="top1">
									<div class="img_big_container3" id="div'.$i.'_'.$swap_id.'" '.($user_id==$_SESSION['USER_ID']?'ondrop="drop(event,\''.$i.'_'.$swap_id.'\')" ondragover="allowDrop(event,\''.$i.'_'.$swap_id.'\')':'').'"> 
									<div class="feedbox_holder">';
								    if($user_id==USER_ID){
									 	$photoCnt.='<div class="actions"><a class="delete" href="#" onclick="photo_action(\'del_expert_photo\','.$cherryboard_id.','.$photo_id.')"><img src="images/delete.png"></a></div>';		
									 }
									
			         $photoCnt.='</div> 
									<img src="'.$photoPath.'" id="drag'.$i.'_'.$swap_id.'" draggable="true" ondragstart="drag(event,\''.$i.'_'.$swap_id.'\')">
									</div>'; 
					
					    $photoCnt.='<div id="div_cherry_comment_'.$photo_id.'">';
						//CHEER SECTION
						$photoCnt.=expert_cheers_section($cherryboard_id,$photo_id,$photo_day);
						//QUESTION/ANSWER SECTION
						$photoCnt.=expert_question_section($cherryboard_id,$photo_id,$photo_day);
						//COMMENT SECTION
						$photoCnt.=expert_comment_section($cherryboard_id,$photo_id,$photo_day);


							
					$current_userPic=getFieldValue('fb_photo_url','tbl_app_users','user_id='.USER_ID);	     
					$photoCnt.='</div><div class="add1">
						 <div class="add_img"><img src="'.$current_userPic.'" class="img_small" /></div>
						 <div class="add_txt">
						 <textarea name="cherry_comment_'.$photo_id.'" class="input_comments" id="cherry_comment_'.$photo_id.'" onfocus="if(this.value==\'Add a comment...\') this.value=\'\';" onblur="if(this.value==\'\') this.value=\'Add a comment...\';" style="height: 29px;width:130px;">Add a comment...</textarea>
						 
						 </div>
						 <div class="add_btn"><img style="cursor:pointer" src="images/btn_comment.png" onclick="return add_cherry_comment(event,\'add_cherry_expert_comment\',\''.$cherryboard_id.'\',\''.$photo_id.'\',\''.USER_ID.'\',document.getElementById(\'cherry_comment_'.$photo_id.'\').value)">
					</div></div>';
					}
				}
	}
	echo $photoCnt=$type.'##===##divPhoto_'.$photo_id.'##===##'.$photoCnt;
	exit(0);
}
//End of if
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
if($type=="add"||$type=="del_expert_photo"||$type=="exp_photo_refresh"||$type=="rotate"){
 $sort=$_GET['sort'];

// $NewphotoCnt='<div style="position: absolute;margin-left:665px;"><table><tr><td><a title="Sort" name="Sort" href="javascript:void(0);" onclick="javascript:ajax_action(\'exp_photo_refresh\',\'right_container\',\'cherryboard_id='.$cherryboard_id.'&sort='.($sort=="asc"?'desc':'asc').'\')">'.($sort=="asc"?'<img id="des" src="images/des.jpg" height="35" width="35"/>':'<img id="asc" src="images/asc.jpg" height="35" width="35"/>').'</a></td><td><img id="rotate_asc" src="images/transparent.png" height="35" width="35"/></td><td><strong>'.$BuyerName.'</strong></td></tr></table></div>';
 $NewphotoCnt='<div align="center"><table><tr><td><strong>'.$BuyerName.'</strong></td></tr></table></div>';
 //DAYS TITLE
  $selDays=mysql_query("select day_title from tbl_app_expertboard_days where expertboard_id=".$expertboard_id." order by day_no");
		  $DaysTitleArr=array();
		  if(mysql_num_rows($selDays)>0){
		  	  $cntDay=1;
			  while($selDaysRow=mysql_fetch_array($selDays)){
				$DaysTitleArr[$cntDay]=$selDaysRow['day_title'];
				$cntDay++;
			  }
			  
		  }
//EXPERT BOARD PHOTOS
		  $qryphoto="select * from tbl_app_expert_cherry_photo where cherryboard_id=".$cherryboard_id." order by photo_id desc";
		  $selphoto=mysql_query($qryphoto);
		  $cntPhoto=mysql_num_rows($selphoto);
		  $photoDayArr=array();
		  if($cntPhoto>0){
			while($selphotoRow1=mysql_fetch_array($selphoto)){
				$photo_id=$selphotoRow1['photo_id'];
				$photo_day=((int)$selphotoRow1['photo_day']);
				$photoDayArr[$photo_id]=$photo_day;
			}	
		  
		  }
		 $photoDayArr = array_unique($photoDayArr);
		
	   $GoalDays=getExpertGoalDays($cherryboard_id);
	   $photoCntArray=array();
		for($i=1;$i<=$GoalDays;$i++){	
 		   $photoCnt='';
		   $swap_id=0;
		   if(in_array($i,$photoDayArr)){
			$selphoto=mysql_query("select * from tbl_app_expert_cherry_photo where cherryboard_id=".$cherryboard_id." and photo_day='".$i."' order by photo_id desc");
			
			while($selphotoRow=mysql_fetch_array($selphoto)){
				$photo_id=$selphotoRow['photo_id'];
				$user_id=$selphotoRow['user_id'];
				$swap_id=$photo_id;
				$photo_title=ucwords(stripslashes($selphotoRow['photo_title']));
				$photo_name=$selphotoRow['photo_name'];
				$record_date=$selphotoRow['new_record_date'];
				$photoPath='images/expertboard/'.$photo_name;
				$photo_day=(int)$selphotoRow['photo_day'];
				if($photo_title==""){
					$photo_title='<div style="width:180px;height:18px">&nbsp;</div>';
				}
				
				if(is_file($photoPath)){
				   $photoCnt.='<table border="0" height="100%" id="divPhoto_'.$photo_id.'">
					<tr>
					<td>
				      <div class="field_container2" style="margin:0px;padding:0px;">
				   
 					<div class="day_container">Day '.$photo_day.'</div>
						  <div class="tag_container">
							<div class="comment_box1" id="photo_title'.$photo_id.'"><a href="javascript:void(0);" '.($user_id==USER_ID?'ondblclick="ajax_action(\'upd_photo_title\',\'photo_title'.$photo_id.'\',\'stype=eadd&photo_id='.$photo_id.'\')"':'').' title="Edit Comment" class="cleanLink">'.$photo_title.'</a></div><div class="clear"></div>
								<div class="info_box">
									<div class="score">'.$DaysTitleArr[$photo_day].'</div>
									<div class="date">'.$record_date.'</div>
								 </div>
								 <div class="b_arrow"></div>
							 <div class="clear"></div>
						 </div>
						 </div>  
					 </td>
					 </tr>';
						$photoCnt.='<tr>
					     <td height="100%" class="top_td">	 
						 <div class="field_container2" style="margin:0px;padding:0px;">
									<div class="img_big_container3" id="div'.$i.'_'.$swap_id.'" '.($user_id==$_SESSION['USER_ID']?'ondrop="drop(event,\''.$i.'_'.$swap_id.'\')" ondragover="allowDrop(event,\''.$i.'_'.$swap_id.'\')':'').'"> 
									<div class="feedbox_holder">';
								    if($user_id==USER_ID){
									 	$photoCnt.='<div class="actions"><a class="delete" href="#" onclick="photo_action(\'del_expert_photo\','.$cherryboard_id.','.$photo_id.')"><img src="images/delete.png"></a></div>';		
									 }
									
					         $photoCnt.='
							        </div> 
									<img src="'.$photoPath.'" id="drag'.$i.'_'.$swap_id.'" draggable="true" ondragstart="drag(event,\''.$i.'_'.$swap_id.'\')">
									</div>'; 
					
					    $photoCnt.='<div id="div_cherry_comment_'.$photo_id.'">';
						//CHEER SECTION
						$photoCnt.=expert_cheers_section($cherryboard_id,$photo_id,$photo_day);
						//QUESTION/ANSWER SECTION
						$photoCnt.=expert_question_section($cherryboard_id,$photo_id,$photo_day);
						//COMMENT SECTION
						$photoCnt.=expert_comment_section($cherryboard_id,$photo_id,$photo_day);


					$current_userPic=getFieldValue('fb_photo_url','tbl_app_users','user_id='.USER_ID);	     
					$photoCnt.='</div><div class="add1">
						 <div class="add_img"><img src="'.$current_userPic.'" class="img_small" /></div>
						 <div class="add_txt">
						 <textarea name="cherry_comment_'.$photo_id.'" class="input_comments" id="cherry_comment_'.$photo_id.'" onfocus="if(this.value==\'Add a comment...\') this.value=\'\';" onblur="if(this.value==\'\') this.value=\'Add a comment...\';" style="height: 29px;width:130px;">Add a comment...</textarea>
						 
						 </div>
						 <div class="add_btn"><img style="cursor:pointer" src="images/btn_comment.png" onclick="return add_cherry_comment(event,\'add_cherry_expert_comment\',\''.$cherryboard_id.'\',\''.$photo_id.'\',\''.USER_ID.'\',document.getElementById(\'cherry_comment_'.$photo_id.'\').value)">
					</div></div>';
						if($i==1){
							$photoCnt.='<div style="padding-bottom:35px;"></div>';	  
						}
						$photoCnt.='</td></tr></table>';
					    $photoCntArray[$i]=$photoCnt;
						
					}
				}	
				
			}else{
			  	 $photoPath='images/cherryboard/no_image.png'; 
				 $photoCnt.='<table border="0" height="100%">
				<tr>
				<td>
				  <div class="field_container2" style="margin:0px;padding:0px;">
					   
						<div class="day_container">Day '.$i.'</div>
				  <div class="tag_container">
					<div class="comment_box1" id="photo_title'.$i.'">No Photo</div><div class="clear"></div>
						<div class="info_box">
							<div class="score">'.$DaysTitleArr[$i].'</div>
							<div class="date">&nbsp;</div>
						 </div>
						 <div class="b_arrow"></div>
					 <div class="clear"></div>
				 </div>
				 </div>
				</td>
				</tr>
				<tr>
					 <td height="100%" class="top_td">	 
						 <div class="field_container2" style="margin:0px;padding:0px;">
					   
							<div id="div'.$i.'_'.$swap_id.'" ondrop="drop(event,\''.$i.'_'.$swap_id.'\')" ondragover="allowDrop(event,\''.$i.'_'.$swap_id.'\')" style="background-image:url('.$photoPath.');cursor:pointer;height:192px;width:192px;" onclick="javascript:document.getElementById(\'photo_day\').value=\''.$i.'\';document.getElementById(\'photo_upload\').style.display=\'inline\';" src="'.$photoPath.'">

							 </div>
						   <div id="div_cherry_comment">';
								
						$photoCnt.='</div>';
						$photoCnt.='</div>';
						$photoCnt.='</td></tr></table>';
						$photoCntArray[$i]=$photoCnt;		
			  }
			   
			}
		$NewphotoCnt='<div style="position: absolute;margin-left:665px;"><table><tr><td><a title="Sort" name="Sort" href="javascript:void(0);" onclick="javascript:ajax_action(\'exp_photo_refresh\',\'right_container\',\'cherryboard_id='.$cherryboard_id.'&sort='.($sort=="asc"?'desc':'asc').'\')">'.($sort=="asc"?'<img title="Descending" src="images/des.jpg" height="35" width="35"/>':'<img title="Ascending" src="images/asc.jpg" height="35" width="35"/>').'</a></td><td><img id="rotate_asc" src="images/transparent.png" height="35" width="35"/></td></tr></table></div>';	
		$NewphotoCnt.='<table border="0"><tr>';
		if($sort=="asc"){
			for($i=1;$i<=$GoalDays;$i++){
				$NewphotoCnt.='<td valign="top" height="100%">'.$photoCntArray[$i].'</td>';
				if($i%3==0){$NewphotoCnt.='</tr><tr>';}
			}
		}else{
			$cnt=1;
			for($i=$GoalDays;$i>=1;$i--){
				$NewphotoCnt.='<td valign="top" height="100%">'.$photoCntArray[$i].'</td>';
				if($cnt%3==0){$NewphotoCnt.='</tr><tr>';}
				$cnt++;
			}
		}		
		$NewphotoCnt.='</tr>
		<tr><td colspan="3" style="height:50px">&nbsp;</td></tr>
		</table>';
}			

if($type=="del_expert_photo"||$type=="exp_photo_refresh"||$type=="rotate"){
	$photoCnt=$type.'##===##right_container##===##'.$NewphotoCnt;
}
	
 $photoCnt.='<!-- START ADD PHOTO--- -->
		<div style="display: none; position: fixed; opacity: 1; z-index: 11000; left: 50%; margin-left: -165px; top: 200px;width:390px;" id="photo_upload" align="center" class="popup_div">
                <a class="modal_close" href="javascript:void(0);" title="close" onclick="javascript:document.getElementById(\'photo_upload\').style.display=\'none\';"></a>
                <span class="head_20">Upload Photo</span><br>
				<div id="setup_add_category" class="msg_red">&nbsp;</div>
				<form action="" method="post" name="frmphoto" enctype="multipart/form-data">
				<input type="hidden" name="photo_day" id="photo_day" value="1" />
				<table>
				<tr>
				<td><textarea onblur="if(this.value==\'\') this.value=\'Write your comment here...\';" onfocus="if(this.value==\'Write your comment here...\') this.value=\'\';" id="txtcomment" class="textfield" rows="3" name="txtcomment" style="width:290px">Write your comment here...</textarea>
				<br/><span style="font-size:10px;margin-left: 2px;" class="comment_txt1">Max allowed 3MB</span>
				</td>
				</tr>
				<tr>
				<td>
				<input type="file" name="exp_photo_name" id="exp_photo_name" />
				</td>
				</tr>
				<tr>
				<td align="center">
				<input type="button" onclick="javascript:document.frmphoto.submit();" value="Upload Photo" name="btnExpUploadPhoto" id="btnExpUploadPhoto" class="btn_small" title="Upload Photo" />
				</td>
				</tr>
				</table>
				</form>
	 </div>
	  <!-- END ADD PHOTO--- -->';
	  		echo $photoCnt;

?>


