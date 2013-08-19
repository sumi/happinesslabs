<?php
	include('include/common_vars.php');
	if(!session_id()) {
	  session_start();
	}
	if($_SERVER['SERVER_NAME']=="localhost"){
		require_once("./DB/initDB.php");
		require_once("./DB/registerDB.php");
		define('SITE_PATH','http://localhost/cherryfull/cherry/');
	}else{
		require_once("DB/initDB.php");
		require_once("DB/registerDB.php");
		define('SITE_PATH','https://www.happinesslabs.com/');
	}
	$rDB=new registerDB();
	$allUserTypes=$rDB->getAllUserTypes();
?>