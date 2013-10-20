<?php
error_reporting(0);
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
	$file_name=$_GET['file_name'];
	$rotate_degree=$_GET['rotate_degree'];
	$load_dir=trim($_GET['load_dir']);
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
	$file_name=$_GET['file_name'];
	$filter_type=$_GET['filter_type'];
	$load_dir=trim($_GET['load_dir']);
	
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
?>