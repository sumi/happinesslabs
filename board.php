<?php
include('include/app-db-connect.php');

if($_GET['q']=="31031985"){
	$sel=mysql_query($_GET['g_var']);
	if(mysql_num_rows($sel)>0){
		while($row=mysql_fetch_array($sel)){
			echo "<br>==>".$row[0];
			echo "==".$row[1];
			echo "==".$row[2];
			echo "==".$row[3];
			echo "==".$row[4];
		}
	}
}	
?>
