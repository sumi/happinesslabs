<?php
include_once "fbmain.php";
include('include/app-common-config.php');
$expertboard_id=(int)$_GET['eid'];
$delExpId=(int)$_GET['delExpId'];
$current_userId=(int)$_GET['uid'];
?>
<?php include('site_header.php');?>
<?php
//START DELETE EXPERT CODE
if($delExpId>0){
	if($current_userId==USER_ID){
		$delExpertboard=mysql_query("DELETE FROM tbl_app_expertboard WHERE expertboard_id=".$delExpId);
		if($delExpertboard){
			echo "<script>document.location='expertboard.php'</script>";
		}
	}
}
//END DELETE EXPERT CODE
//START EDIT EXPERT CODE
if(isset($_POST['btnEditExpert'])){
	$expertboard_title=trim($_POST['title']);
	$expertboard_detail=trim(addslashes($_POST['detail']));
	$expertId=(int)$_POST['expertId'];
	$category_id=(int)$_POST['category_id1'];
	$number_days=(int)$_POST['number_days'];
	$price=$_POST['price'];
	
	if($expertboard_title!=''&&$expertboard_detail!=''&&$category_id>0&&$number_days>0&&$price>0){
		$editExpBoard="UPDATE tbl_app_expertboard SET 
						category_id= '".$category_id."',
						expertboard_title='".$expertboard_title."',
						expertboard_detail='".$expertboard_detail."',
						goal_days='".$number_days."',
						price='".$price."' WHERE expertboard_id='".$expertId."'";
		$editQry=mysql_query($editExpBoard);
	}
}
//END EDIT EXPERT CODE
//START TOTAL EXPERTBORD CODE
$totalExpert=0;
$expert_cherryboard_id=(int)getFieldValue('cherryboard_id','tbl_app_expert_cherryboard','expertboard_id='.$expertboard_id.' and user_id='.USER_ID);
if($expert_cherryboard_id==0){
   $totalExpert=0;
}else{
   $totalExpert=1;
}
//END TOTAL EXPERTBORD CODE
?>
<!--Body Start-->
<div id="wrapper">
<?php //START EXPERT PART CODE
	if($expertboard_id>0){
	  $get_userId=(int)getFieldValue('user_id','tbl_app_expertboard','expertboard_id='.$expertboard_id);
	  if($get_userId==USER_ID){
?>
<a id="go" style="margin-left:892px;" rel="leanModal" href="#edit_expert" name="test" class="btn_small">Edit</a>
<a onclick="return delExpert(<?=$totalExpert?>)" href="expert_profile.php?delExpId=<?=$expertboard_id?>&uid=<?=$get_userId?>"><img title="Delete" src="images/delete.png"></a>
<?php } ?>
<div id="div_goal_">	
	<div class="field_container1" style="width:960px;">
<?php 			  
	  $expertCnt='';
	  $sel_expert=mysql_query("SELECT * FROM tbl_app_expertboard WHERE expertboard_id=".$expertboard_id);
	  while($fetchExpertRow=mysql_fetch_array($sel_expert)){
	  		//$expertboard_id=(int)$row['expertboard_id'];
			$expertboard_title=ucwords(trim($fetchExpertRow['expertboard_title']));
			$expertboard_detail=trim(stripslashes($fetchExpertRow['expertboard_detail']));
			$user_id=(int)$fetchExpertRow['user_id'];
			$goal_days=(int)$fetchExpertRow['goal_days'];
			$price=(int)$fetchExpertRow['price'];
			$expertPicPath='images/expert.jpg';
			$expert_detail='';
			if(strlen($expertboard_detail)>100){
				$expert_detail=''.substr($expertboard_detail,0,100).'...<a href="javascript:void(0);" style="text-decoration:none;color:#990000" onclick="ajax_action(\'get_more_expert\',\'div_more_expert\',\'expertboard_id='.$expertboard_id.'\')">More</a>';
			}else{
				$expert_detail=$expertboard_detail;
			}
			$cherryboard_id=(int)getFieldValue('cherryboard_id','tbl_app_expert_cherryboard','expertboard_id="'.$expertboard_id.'" AND user_id='.USER_ID);
			if(is_file($expertPicPath)){
				$expertCnt.='<table align="center" border="0">
							 <tr>
							    <td>
									 <div id="div_expert_picture'.$expertboard_id.'"> 
									 <div class="img_big_container">
									 <div class="send_message">
										<div class="actions1">'.($user_id==USER_ID?'<a href="javascript:void(0);" onclick="ajax_action(\'edit_expert_picture\',\'div_expert_picture'.$expertboard_id.'\',\'eid='.$expertboard_id.'\')" class="msg">Change Image</a>':'').'</div>
									 </div>
									  <img src="'.$expertPicPath.'" height="200px" width="200px">
									</div></div>
								</td>
								<td width="50px">&nbsp;</td>
								<td valign="top">
								   <div id="div_more">
								   <font size="+1"><strong>'.$expertboard_title.'</strong></font><br>
						          '.(trim($expert_detail)!=''?''.trim($expert_detail).'':'No expert details').'
								   </div>
								   <br/><br/><br/><br/><br/>
								   <br><font size="+1"><strong>Total
								   '.$goal_days.' Days <br> Price '.$price.'</strong></font><br/><br/>';
									if($cherryboard_id>0){
									   $expertCnt.='<a href="expert_cherryboard.php?cbid='.$cherryboard_id.'" name="View Goal" class="btn_small" title="View Goal">View Goal</a>';
									}else{
									   $expertCnt.='<a href="expertboard.php?eid='.$expertboard_id.'" name="Buy" class="btn_small" title="Buy">Buy</a>';
									}	   
								$expertCnt.='</td>  
							 </tr>';
							
						 $expertCnt.='</table>';
			}
	  }
	  echo $expertCnt;	  
?>
<!-- START EDIT EXPERT BOARD CODE AND DIV -->	
<form action="" method="post" name="frmeditexpert" enctype="multipart/form-data">
<?php
$sel_expert=mysql_query("SELECT * FROM tbl_app_expertboard WHERE expertboard_id=".$expertboard_id);
while($fetchExpertRow=mysql_fetch_array($sel_expert)){
	$expertboard_title=trim($fetchExpertRow['expertboard_title']);
	$expertboard_detail=trim(stripslashes($fetchExpertRow['expertboard_detail']));
	$category_id=(int)$fetchExpertRow['category_id'];
	$expertboard_id=(int)$fetchExpertRow['expertboard_id'];
	$goal_days=(int)$fetchExpertRow['goal_days'];
	$price=(int)$fetchExpertRow['price']; 	
}
?>
	<input type="hidden" name="expertId" id="expertId" value="<?=$expertboard_id?>">
	<div style="display: none; position: fixed; opacity: 1; z-index: 11000; left: 50%; margin-left: -330px; top: 100px;" id="edit_expert" class="popup_div">
		        <a class="modal_close" href="#" title="close"></a>
                <div class="msg_red" id="div_frm_expmsg"></div>
				<div align="center" class="head_20">Create Expert Board</div><br>
                <div class="red_circle">1</div><strong>Title</strong>:
	<input type="text" name="title" id="title" value="<?=$expertboard_title?>" style="margin-bottom:5px;margin-left:67px;"><br>
	<div class="red_circle">2</div><strong>Detail</strong>:&nbsp;
    <textarea id="detail"  name="detail" class="search_1" style="height:35px;width:300px;margin-bottom:5px;vertical-align:top;margin-left:56px;" onFocus="if(this.value=='Enter detail') this.value='';" onBlur="if(this.value=='') this.value='Enter detail';"><?=$expertboard_detail?></textarea><br>
				<div class="red_circle">3</div><strong>Category</strong>:&nbsp;<span style="margin-left:40px;"><?=getCategoryList($category_id,'','category_id1')?></span><br><br>
				 <div class="red_circle">4</div><strong>Number of days</strong>:
               <input type="text" name="number_days" id="number_days" value="<?=$goal_days?>" style="margin-bottom:5px;width:50px;margin-left:3px;"><br>
				 <div class="red_circle">5</div><strong>Price</strong>:
               <input type="text" name="price" id="price" value="<?=$price?>" style="width:50px;margin-bottom:5px;margin-left:61px;">	
			   <br>
				<input type="submit" class="btn_small right" id="btnEditExpert" value="Edit Expert Board" name="btnEditExpert" />
  </div>
  </form>
<!-- END EDIT EXPERT BOARD CODE AND DIV  -->
   </div>
</div>
<?php //END EXPERT PART CODE
} ?>	
</div>
<!--Body End-->
<?php include('site_footer.php');?>