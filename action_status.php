<?php 
include('common_script/admin_connection.php');
include('common_script/sessioncheck.php');
$action_id=$_GET['action_id'];
$action_status=$_GET['action_status'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
	   <title>Quotation Mail</title>
       <link rel="shortcut icon" href="../favicon.ico" />
       <link href="../css/styles_.css" rel="stylesheet" type="text/css" />
       <script type='text/javascript' src='../js/jquery-1.5.1.min.js'></script>
</head>
<body>
<?php
	$errMsg="";
	$msg="";
	if(isset($_POST['action_id'])){
		$action_id=$_POST['action_id'];
		$action_status=$_POST['action_status'];
		$comment=addslashes($_POST['comment']);
		if($action_id>0){
			$updateSql=mysql_query("update user_action set action_status='".$action_status."' where action_id=".$action_id);
			if($comment!=""){
				$idadmin=getFieldValue('id_admin','user_action','action_id='.$action_id);
				$insQuery="INSERT INTO `user_action_status` (`comment_id`, `id_admin`, `action_id`, `action_status`, `comment`, `record_date`) VALUES (NULL, '".$idadmin."', '".$action_id."', '".$action_status."', '".$comment."', '".date('Y-m-d')."')";
				$insQuerySql=mysql_query($insQuery);
			}
			$msg='Status changed successfully.';
		}	
	}
	?>
	<form name="frm" action="" method="post" enctype="multipart/form-data">
	<input type="hidden" name="action_id" id="action_id" value="<?=$_GET['action_id']?>" />
	<table height="125px" align="center" cellspacing="0" cellpadding="0" border="0" style="font-size:14px;">
	<tr><td colspan="3">&nbsp;</td></tr>
	<?php
	if($msg!=""){
	?>
	<tr>
		<td colspan="3">
		<font color="#009900"><strong><?=$msg?></strong></font>
		</td>
		</tr>
	<tr>
	<tr>
	<td colspan="3" align="center">
	<a title="Close" class="links" href="javascript:void(0);" style="text-decoration:none" onclick="javascript:opener.location.reload();window.close();"><div style="background-color:#666; width:70px; text-align:center; padding:4px;-webkit-border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px; -moz-border-radius: 3px;"><font color="#FFFFFF">Close</font></div></a></td>
	</tr>
	<?php
	}else{
	?>
	<td align="right">
	<strong>Action Status :</strong>
	</td>
	<td>&nbsp;<?=getActionStatus('action_status',$action_status)?></td>
	</tr>
	<tr>
	<td valign="top" align="right">
	<strong>&nbsp;Comment :</strong>
	</td>
	<td colspan="2">
	&nbsp;<textarea name="comment" cols="30px" rows="4"  id="comment"></textarea>&nbsp;</td>
	</tr>
	<tr>
	<td>&nbsp;</td>
	<td colspan="2">
		<table><tr><td>
		<a title="Sent" class="links" href="javascript:void(0);" style="text-decoration:none" onclick="javascript:document.frm.submit();"><div style="background-color:#666; width:70px; text-align:center; padding:4px;-webkit-border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px; -moz-border-radius: 3px;"><font color="#FFFFFF">Change</font></div></a></td>
		<td><a title="Close" class="links" href="javascript:void(0);" style="text-decoration:none" onclick="javascript:window.close();"><div style="background-color:#666; width:70px; text-align:center; padding:4px;-webkit-border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px; -moz-border-radius: 3px;"><font color="#FFFFFF">Close</font></div></a></td>
		</tr></table>
	</td>	
	</tr>
	<?php
	}
	?>
	</table>
	</form>
</body>
</html>
