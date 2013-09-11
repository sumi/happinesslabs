<?php
error_reporting(0);
include_once "fbmain.php";
include('include/app-common-config.php');
include('site_header.php');
?>
<?php
	$Text=urldecode($_REQUEST['cluster']);
    $photoArray=json_decode($Text);
	$len=count($photoArray);
	echo "Len :".$len;
	print_r($photoArray);
	if($len==1){
		exec("montage ".$photoArray[0]." -geometry +2+2 happiness.jpg");
		echo "<br/>Create 1";
		echo '<img src="happiness.jpg"/>';
	}else if($len==2){
	   exec("convert ".$photoArray[0]." ".$photoArray[1]." +append -quality 100 -geometry +2+2 'happiness.jpg'");
	    echo "<br/>Create 2";
		echo '<img src="happiness.jpg"/>';
	}else if($len==3){
		exec("convert ".$photoArray[0]." ".$photoArray[1]." +append ".$photoArray[2]." -append -quality 100  -geometry +2+2 'happiness.jpg'");
		echo "<br/>Create 3";
		echo '<img src="happiness.jpg"/>';
	}else{
		exec("montage ".$photoArray[0]." ".$photoArray[1]." ".$photoArray[2]." ".$photoArray[3]." -quality 100 -geometry +2+2 happiness.jpg");
		echo "<br/>Create 4";
		echo '<img src="happiness.jpg"/>';
	}
?>