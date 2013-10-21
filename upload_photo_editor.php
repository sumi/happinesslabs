<?php
include('include/app-db-connect.php');
include('include/app_functions.php');
require('include/instagraph.php');
$type=$_REQUEST['type'];

$uploaddir='images/expertboard/temp/';

if(isset($_FILES['uploadfile']['name'])&&$_FILES['uploadfile']['name']!=""){
	$fname=$_FILES['uploadfile']['name'];
	$fsize=$_FILES['uploadfile']['size'];
	$fname=str_replace(' ','_',$fname);
	$fname=str_replace('-','_',$fname);
	$fname=str_replace('(','_',$fname);
	$fname=str_replace(')','_',$fname);
	$fname=rand().'_'.$fname;
	$file = $uploaddir.$fname; 
	$MAX_FILE_SIZE=3145728;//3MB Size
	//photo cancel
	if($fsize>$MAX_FILE_SIZE){
		$message = 'File size invalid.'; 
		echo '<script type="text/javascript">alert("'.$message.'");</script>';
		exit(0);
	}else{
		if (move_uploaded_file($_FILES['uploadfile']['tmp_name'],$file)) {
		$_SESSION['fname']=$fname;
		$ajaxData=$fname.'##===##'.$file;
		echo $ajaxData;
		exit(0);
		}
	}

}

if($type=="rotate"){
	$file_name=$_REQUEST['file_name'];
	$rotate_degree=$_REQUEST['rotate_degree'];
	$load_dir=trim($_REQUEST['load_dir']);
	$new_rotate_degree=90;
	$rotate_img='round_arrow_90.jpg';
	$newFileName=rand().'_'.$file_name;
	
	$uploadPath = 'images/expertboard/'.$load_dir.$file_name;
	$uploadNewPath = $uploaddir.$newFileName;
	
	
	
	
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
	//Rotate Image
    $command='convert -rotate 90 '.$uploadPath.' '.$uploadNewPath;
	passthru($command);
	
	$photoCnt=$type.'##===##'.$newFileName.'##===##'.$new_rotate_degree.'##===##'.$rotate_img;
	echo $photoCnt;
	exit(0);
}else if($type=="filter") {
	$file_name=$_REQUEST['file_name'];
	$filter_type=$_REQUEST['filter_type'];
	$load_dir=trim($_REQUEST['load_dir']);
	
	$newFileName=rand().'_'.$file_name;
	$uploadPath = 'images/expertboard/'.$load_dir.$file_name;
	$uploadNewPath = $uploaddir.$newFileName;
	//Filter Image
	
    if($filter_type!=""){
		try
		{
			$instagraph = Instagraph::factory($uploadPath, $uploadNewPath);
		}
		catch (Exception $e) 
		{
			echo $e->getMessage();
			die;
		}
		 
		if($filter_type=="effect1"){ 
			$instagraph->effect1();
		}else if($filter_type=="effect2"){ 
			$instagraph->effect2();
		}else if($filter_type=="effect3"){ 
			$instagraph->effect3();
		}else if($filter_type=="effect4"){ 
			$instagraph->effect4();
		}else if($filter_type=="effect5"){ 
			$instagraph->effect5();
		}else{
			//LIKE AS ORIGNAL
			$instagraph->effect0();
		}
	}
	
	$photoCnt=$type.'##===##'.$newFileName;
	echo $photoCnt;
	exit(0);
}//End of if


if($type=="add_upd_photo"){
   $rnd=rand();
   $photo_id=$_REQUEST['photo_id'];
   $file_name=$_REQUEST['file_name'];
   $comment=$_REQUEST['comment'];
   $imgLeft=$_REQUEST['imgLeft'];
   $imgTop=$_REQUEST['imgTop'];
   $load_dir=$_REQUEST['load_dir'];
   $chg_font=$_REQUEST['chg_font'];
   $chg_size=$_REQUEST['chg_size'];
   $font_color=$_REQUEST['font_color'];
	
   $photo_name=$rnd.'_'.$file_name;//photo_path set in db
   $uploaddir='images/expertboard/'.$photo_name;
   $uploadProfileSlide='images/expertboard/profile_slide/'.$photo_name;
   $uploaddirThumb='images/expertboard/thumb/'.$photo_name;
   $uploaddirSliderThumb='images/expertboard/slider/'.$photo_name;
   $old_uploaddir='images/expertboard/'.$load_dir.$file_name;
   
   $cherryboard_id=getFieldValue('cherryboard_id','tbl_app_expert_cherry_photo','photo_id='.$photo_id);
   //for local due to ImageMagic not working in local
   if($_SERVER['SERVER_NAME']=="localhost"){
   		$retval=copy($old_uploaddir,$uploaddir);
		$retval1=copy($old_uploaddir,$uploadProfileSlide);
		$retval2=copy($old_uploaddir,$uploaddirThumb);
		$retval3=copy($old_uploaddir,$uploaddirSliderThumb);
   }else{
   		//profile page part
	    $thumb_command=$ImageMagic_Path."convert ".$old_uploaddir." -thumbnail 219 x 219 ".$uploaddir;
		$last_line1=system($thumb_command, $retval);
		
		//profile multiple 2 time
	    $thumb_command=$ImageMagic_Path."convert ".$old_uploaddir." -thumbnail 438 x 438 ".$uploadProfileSlide;
		$last_line2=system($thumb_command, $retval1);
   		//thumb part
		$thumb_command_thumb=$ImageMagic_Path."convert ".$old_uploaddir." -thumbnail 60 x 60 ".$uploaddirThumb;
		$last_line3=system($thumb_command_thumb, $retval2);
		//Slider Part
		$imgInfo1=getImageRatio($old_uploaddir,900,500);
		$NewImgW1=$imgInfo1['width'];
		$NewImgH1=$imgInfo1['height'];
		$thumb_command_slide_thumb=$ImageMagic_Path."convert ".$old_uploaddir." -thumbnail ".$NewImgW1." x ".$NewImgH1." ".$uploaddirSliderThumb;
		$last_line4=system($thumb_command_slide_thumb, $retval3);
		
		//Adding Empty BG with photo
		if($NewImgW1<(900-80)||$NewImgW1<(500-60)){
			$bg_command_slide_thumb="convert images/expertboard/slider_emptyBG.jpg -gravity Center ".$uploaddirSliderThumb." -compose Over -composite ".$uploaddirSliderThumb;
			$last_line5=system($bg_command_slide_thumb, $retval4);
		}
		
   }
   if($retval){
	  	//START CHANGE STORY PICTURE
	 	$expStoryPic=trim(getFieldValue('photo_name','tbl_app_expert_cherry_photo','photo_id='.$photo_id));
		$expStoryPicPath='images/expertboard/'.$expStoryPic;
		$expStoryPicProfile='images/expertboard/profile_slide/'.$expStoryPic;
   		$expStoryPicThumb='images/expertboard/thumb/'.$expStoryPic;
   		$expStoryPicSlider='images/expertboard/slider/'.$expStoryPic;
		$updtQry="UPDATE tbl_app_expert_cherry_photo SET photo_title='".$comment."',photo_name='".$photo_name."' WHERE photo_id=".$photo_id;
		$updtQryRes=mysql_query($updtQry);
		//update title detail
		$photo_title_id=(int)getFieldValue('photo_title_id','tbl_app_photo_title_size','photo_id='.$photo_id);
		if($photo_title_id>0){
			$insTitle="update `tbl_app_photo_title_size` set `top`='".$imgTop."', `left`='".$imgLeft."', `font_type`='".$chg_font."', `font_color`='".$font_color."', `chg_size`='".$chg_size."' where photo_title_id=".$photo_title_id;
			$insTitleSql=mysql_query($insTitle);
		}else{
			$insTitle="INSERT INTO `tbl_app_photo_title_size` (`photo_title_id`, `photo_id`, `top`, `left`, `font_type`, `font_color`, `font_size`, `record_date`) VALUES (NULL, '".$photo_id."', '".$imgTop."', '".$imgLeft."', '".$chg_font."', '".$font_color."', '".$chg_size."', '".date('y-m-d')."')";
			$insTitleSql=mysql_query($insTitle);
		}
		
		
		if($updtQryRes){
		  	unlink($expStoryPicPath);
			unlink($expStoryPicProfile);
			unlink($expStoryPicThumb);
			unlink($expStoryPicSlider);
			unlink($old_uploaddir);
		}				
	 	echo $type.'##===##'.$cherryboard_id;
		exit(0);
   }else{
		echo "Photo Inserting Error...";
		unlink($_REQUEST['file_name']);
		exit(0);
   }
}

?>