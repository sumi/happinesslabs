<script type="text/javascript" src="js/common.js"></script>
<?php 
include('include/app-db-connect.php');
include('include/app_functions.php');
$type=$_GET['type'];
//Add Category
if($type=="add_category"){
	$category_name='Enter category name';
	if(isset($_POST['btnAddCat'])){
		$category_name=$_POST['category_name'];
		if($category_name!="Enter category name"&&$category_name!=""){
			$ins_sel="INSERT INTO `tbl_app_category` (`category_id`, `category_name`, `record_date`) VALUES (NULL, '".$category_name."', '".date('Y-m-d')."')";
			$ins_sql=mysql_query($ins_sel);
			?>
			<script language="javascript">opener.location.reload();window.close();</script>
			<?php
		}
	}
	?>
	<form action="" method="post" name="frmadd">
	<table>
	<tr><td><font style="font-size:16px;font-weight:bold">Add Category</font></td></tr>
	<tr><td><input type="text" name="category_name" onblur="if(this.value=='') this.value='Enter category name';" onfocus="if(this.value=='Enter category name') this.value='';" value="<?=$category_name?>" /></td></tr>
	<tr><td align="center"><input type="submit" value="Add Category" name="btnAddCat" /></td></tr>
	</table>
	</form>
	<?php
}
//Add Gift
if($type=="add_gift"){
	$gift_title='Enter gift title';
	if(isset($_POST['btnAddCat'])){
		$gift_title=$_POST['gift_title'];
		$category_id=$_POST['category_id'];
		$file_name= "cherry-".trim($_FILES['gift_photo']['name']);
		$uploadTempdir = './images/gift/temp/'.$file_name; 
		$uploaddir = './images/gift/'.$file_name; 
		
		if($gift_title!="Enter gift title"&&$gift_title!=""&&$category_id>0&&$file_name!=""){
			
			if(move_uploaded_file($_FILES['gift_photo']['tmp_name'], $uploadTempdir)){
				if($_SERVER['SERVER_NAME']=="localhost"){
					$retval=copy($uploadTempdir,$uploaddir);
				}else{
					$thumb_command=$ImageMagic_Path."convert ".$uploadTempdir." -thumbnail 150 x 150 ".$uploaddir;
					$last_line=system($thumb_command, $retval);
				}				
				$ins_sel="INSERT INTO `tbl_app_gift` (`gift_id`, `category_id`, `gift_title`, `gift_photo`, `is_system`, `record_date`) VALUES (NULL, '".$category_id."', '".$gift_title."', '".$file_name."', '1', CURRENT_TIMESTAMP)";
				$ins_sql=mysql_query($ins_sel);
				?>
				<script language="javascript">opener.location.reload();window.close();</script>
				<?php
			}	
		}
	}
	?>
	<form action="" method="post" name="frmadd" enctype="multipart/form-data">
	<table>
	<tr><td colspan="2"><font style="font-size:16px;font-weight:bold">Add Gift</font></td></tr>
	<tr><td>Category : </td><td><?=getCategoryList()?></td></tr>
	<tr><td>Gift Title : </td><td><input type="text" name="gift_title" onblur="if(this.value=='') this.value='Enter gift title';" onfocus="if(this.value=='Enter gift title') this.value='';" value="<?=$gift_title?>" /></td></tr>
	<tr><td>Gift Photo : </td><td><input type="file" name="gift_photo" /></td></tr>
	<tr><td>&nbsp;</td><td><input type="submit" value="Add Gift" name="btnAddCat" /></td></tr>
	</table>
	</form>
	<?php
}
//Add template
if($type=="add_template"){
	$category_id=$_GET['cid'];
	$tmp_title='Enter template title';
	if(isset($_POST['btnAddTemp'])){
		$tmp_title=$_POST['tmp_title'];
		if($tmp_title!="Enter template title"&&$category_id>0){
			$ins_sel="INSERT INTO `tbl_app_system_cherryboard` (`cherryboard_id`, `user_id`, `category_id`, `cherryboard_title`, `record_date`) VALUES (NULL, '0', '".$category_id."', '".$tmp_title."', CURRENT_TIMESTAMP);";
			$ins_sql=mysql_query($ins_sel);
			$cherryboard_id=mysql_insert_id();
			$totalDyndiv=(int)$_POST['totalDyndiv'];
			for($i=1;$i<=$totalDyndiv;$i++){
				$chklistName='chklist'.$i;
				$chklist=trim($_POST[$chklistName]);
				if($chklist!="Enter checklist"&&$cherryboard_id>0){
					$insChklist="INSERT INTO `tbl_app_system_checklist` (`checklist_id`, `cherryboard_id`, `checklist`, `record_date`, `is_checked`) VALUES (NULL, '".$cherryboard_id."', '".$chklist."', CURRENT_TIMESTAMP, '0')";
					$insChklistSql=mysql_query($insChklist);
				}
			}	
			?>
			<script language="javascript">opener.location.reload();window.close();</script>
			<?php
				
		}
	}
	?>
	<form action="" method="post" name="frmadd" enctype="multipart/form-data">
	<table>
	<tr><td colspan="2"><font style="font-size:16px;font-weight:bold">Add Template</font></td></tr>
	<tr><td>Template : </td><td><input type="text" name="tmp_title" onblur="if(this.value=='') this.value='Enter template title';" onfocus="if(this.value=='Enter template title') this.value='';" value="<?=$tmp_title?>" /></td></tr>
	<tr><td valign="top">Checklist : </td><td>
	<table>
		<tr id="DynDiv1">
			<td>1.</td><td><input type="text" name="chklist1" onblur="if(this.value=='') this.value='Enter checklist';" onfocus="if(this.value=='Enter checklist') this.value='';" value="Enter checklist" /></td>
			<td><a href="javascript:void(0);" onclick="showDynamicDiv();" style="text-decoration:none">+ Add</a>
		<input type="hidden" name="totalDyndiv" id="totalDyndiv" value="1" /></td>
		</tr>
		<?php
		for($p=2;$p<=10;$p++){
		?>
		<tr id="DynDiv<?=$p?>" style="display:none">
			<td><?=$p?>.</td><td colspan="2"><input type="text" name="chklist<?=$p?>" onblur="if(this.value=='') this.value='Enter checklist';" onfocus="if(this.value=='Enter checklist') this.value='';" value="Enter checklist" /></td>
		</tr>
	<?php }?>
	</table>
	</td></tr>
	<tr><td>&nbsp;</td><td><input type="submit" value="Add Template" name="btnAddTemp" /></td></tr>
	</table>
	</form>
	<?php
}
//Add Checklist
if($type=="add_chklist"){
	$category_id=$_GET['cid'];
	if(isset($_POST['btnAddChk'])){
		$cherryboard_id=$_POST['cherryboard_id'];
		$category_id=$_GET['cid'];
		$totalDyndiv=(int)$_POST['totalDyndiv'];
		$cnt=1;
		for($i=1;$i<=$totalDyndiv;$i++){
			$chklistName='chklist'.$i;
			$chklist=trim($_POST[$chklistName]);
			if($chklist!="Enter checklist"&&$cherryboard_id>0){
				$insChklist="INSERT INTO `tbl_app_system_checklist` (`checklist_id`, `cherryboard_id`, `checklist`, `record_date`, `is_checked`) VALUES (NULL, '".$cherryboard_id."', '".$chklist."', CURRENT_TIMESTAMP, '0')";
				$insChklistSql=mysql_query($insChklist);
				$cnt++;
			}
		}	
		if($cnt>1){
		?>
		<script language="javascript">opener.location.reload();window.close();</script>
		<?php
		}
	}
	?>
	<form action="" method="post" name="frmadd" enctype="multipart/form-data">
	<table>
	<tr><td colspan="2"><font style="font-size:16px;font-weight:bold">Add Checklist</font></td></tr>
	<tr><td>Template : </td><td><?=getSystemGoalBoardList($category_id,$cherryboard_id)?></td></tr>
	<tr><td valign="top">Checklist : </td><td>
	<table>
		<tr id="DynDiv1">
			<td>1.</td><td><input type="text" name="chklist1" onblur="if(this.value=='') this.value='Enter checklist';" onfocus="if(this.value=='Enter checklist') this.value='';" value="Enter checklist" /></td>
			<td><a href="javascript:void(0);" onclick="showDynamicDiv();" style="text-decoration:none">+ Add</a>
		<input type="hidden" name="totalDyndiv" id="totalDyndiv" value="1" /></td>
		</tr>
		<?php
		for($p=2;$p<=10;$p++){
		?>
		<tr id="DynDiv<?=$p?>" style="display:none">
			<td><?=$p?>.</td><td colspan="2"><input type="text" name="chklist<?=$p?>" onblur="if(this.value=='') this.value='Enter checklist';" onfocus="if(this.value=='Enter checklist') this.value='';" value="Enter checklist" /></td>
		</tr>
	<?php }?>
	</table>
	</td></tr>
	<tr><td>&nbsp;</td><td><input type="submit" value="Add Checklist" name="btnAddChk" /></td></tr>
	</table>
	</form>
	<?php
}
//Send photo thankyou
if($type=="thankyou"){
	$cherryboard_id=$_GET['cherryboard_id'];
	if(isset($_POST['btnSend'])){
		$send_email=$_POST['send_email'];
		if($send_email!="Enter Email"&&$cherryboard_id>0){
			$send_emailArr=explode(',',$send_email);
			foreach($send_emailArr as $email_id){
				if($email_id!=""){
					//mail to user
					$GoalDetail=getFieldsValueArray('user_id,cherryboard_title','tbl_app_cherryboard','cherryboard_id='.$cherryboard_id);
					$user_id=$GoalDetail[0];
					$goal_title=$GoalDetail[1];
					$GiftName=getFieldValue('b.gift_title','tbl_app_cherry_gift a,tbl_app_gift b','a.gift_id=b.gift_id and a.cherryboard_id='.$cherryboard_id);
					
					$user_nameDetail=getFieldsValueArray('first_name,last_name','tbl_app_users','user_id='.$user_id);
					$user_name=ucwords($user_nameDetail[0].' '.$user_nameDetail[1]);
					$to      = $email_id;
					
					$GoalToday=getGoalboardDays($cherryboard_id);
					
					$subject = $goal_title.' : '.$GoalToday.' days out of 30 days! ';
					$message = '<table>
								<tr><td>Dear '.$first_name.',</td></tr>
								<tr><td>&nbsp;</td></tr>
								<tr><td>You are able to stick to your goal <strong>'.$goal_title.'</strong> for '.$GoalToday.' days. You gave only '.(30-$GoalToday).' more days to do to win the gift <strong>'.$GiftName.'</strong>.</td></tr>
								<tr><td>&nbsp;</td></tr>
								<tr><td>Keep it up!</td></tr>
								<tr><td>&nbsp;</td></tr>
								<tr><td>Love</td></tr>
								<tr><td>30daysNEW Team</td></tr>
								</table>';
					$headers  = 'MIME-Version: 1.0' . "\r\n";
					$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
					$headers .= 'From: info@30daysnew.com' . "\r\n" .
						'Reply-To: info@30daysnew.com' . "\r\n" .
						'X-Mailer: PHP/' . phpversion();
						//echo $to."========".$subject."========".$message."========".$headers;
					$sentMail=mail($to, $subject, $message, $headers);
				}	
			  }			
			?>
			<script language="javascript">window.close();</script>
			<?php
		}
	}
	?>
	<form action="" method="post" name="frmadd">
	<table>
	<tr><td><font style="font-size:16px;font-weight:bold">Email</font></td><td><input type="text" name="send_email" onblur="if(this.value=='') this.value='Enter Email';" onfocus="if(this.value=='Enter Email') this.value='';" value="<?=$category_name?>" /></td></tr>
	<tr><td>&nbsp;</td><td align="center"><input type="submit" value="Send" name="btnSend" /></td></tr>
	</table>
	</form>
	<?php
}

?>
