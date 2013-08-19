<?php
include_once "fbmain.php";
include('include/app-common-config.php');


$_SESSION['select_goals']=array();

if(!isset($_SESSION['select_checklist'])){
	$_SESSION['select_checklist']=array();
}

//print_r($_SESSION['select_gifts']);
include('site_header.php');

if(isset($_POST['btnGift'])){
	$chk_gift=implode(',',$_POST['chk_gift']);
}
$goal_arr_key=0;
$goalCatArr=$_SESSION['select_gifts'][$goal_arr_key];
$giftArr=explode('_',$goalCatArr);
$category_id=$giftArr[0];
?>
<!--Body Start-->
<form action="" method="post" name="frmsetup3">
<input type="hidden" name="totalDyndiv" id="totalDyndiv" value="1" />
<div id="wrapper">
	<div class="wrapper_820">
    <!-- <div class="bottom_fixed"><a href="#" class="btn_small">Submit</a></div> -->
    <div align="center" class="head_20">Select the goal template or <a href="add_cherryboard.php" style="text-decoration:none" ><span class="dark_20">add the goal</span></a> that you would like to work towards in the next 30 days.</div>
	  <br>
<!-- START ADD CATEGORY--- -->
<?php
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
		}
	}
?>	
		<div style="display: none; position: fixed; opacity: 1; z-index: 11000; left: 50%; margin-left: -330px; top: 200px;" id="add_template" class="popup_div" align="center">
                <a class="modal_close" href="#" title="close"></a>
                <span class="head_20">Add Template</span><br>
				<div id="setup_add_category" class="msg_red">&nbsp;</div>
				<table>
				<tr><td>Template : </td><td><input type="text" name="tmp_title" onblur="if(this.value=='') this.value='Enter template title';" onfocus="if(this.value=='Enter template title') this.value='';" value="<?=$tmp_title?>" style="width:191px;" /></td></tr>
				<tr><td valign="top">Checklist : </td><td>
				<table>
					<tr id="DynDiv1">
						<td>1.</td><td><input type="text" name="chklist1" onblur="if(this.value=='') this.value='Enter checklist';" onfocus="if(this.value=='Enter checklist') this.value='';" value="Enter checklist" /></td>
						<td><a href="javascript:void(0);" onclick="showDynamicDiv('DynDiv','totalDyndiv');" style="text-decoration:none">+ Add</a>
					</td>
					</tr>
					<?php
					for($p=2;$p<=5;$p++){
					?>
					<tr id="DynDiv<?=$p?>" style="display:none">
						<td><?=$p?>.</td><td colspan="2"><input type="text" name="chklist<?=$p?>" onblur="if(this.value=='') this.value='Enter checklist';" onfocus="if(this.value=='Enter checklist') this.value='';" value="Enter checklist" /></td>
					</tr>
				<?php }?>
				</table>
				</td></tr>
				<tr><td>&nbsp;</td><td><input type="submit" class="btn_small" value="Add Template" name="btnAddTemp" /></td></tr>
				</table>
	 </div>
<!-- END ADD CATEGORY--- -->
<!-- START ADD CHECKLIST--- -->
<?php
if(isset($_POST['btnAddChk'])){
	$cherryboard_id=$_POST['cherryboard_id'];
	$totalDyndiv=(int)$_POST['totalDyndiv'];
	$cnt=1;
	for($i=1;$i<=$totalDyndiv;$i++){
		$chklistName='newchklist'.$i;
		$chklist=trim($_POST[$chklistName]);
		if($chklist!="Enter checklist"&&$cherryboard_id>0){
			$insChklist="INSERT INTO `tbl_app_system_checklist` (`checklist_id`, `cherryboard_id`, `checklist`, `record_date`, `is_checked`) VALUES (NULL, '".$cherryboard_id."', '".$chklist."', CURRENT_TIMESTAMP, '0')";
			$insChklistSql=mysql_query($insChklist);
			$cnt++;
		}
	}	
}
?>	
		<div style="display: none; position: fixed; opacity: 1; z-index: 11000; left: 50%; margin-left: -330px; top: 200px;" id="add_checklist" class="popup_div" align="center">
                <a class="modal_close" href="#" title="close"></a>
                <span class="head_20">Add Checklist</span><br>
				<div id="setup_add_category" class="msg_red">&nbsp;</div>
				<table>
				<tr><td>Template : </td><td><?=getSystemGoalBoardList($category_id,$cherryboard_id)?></td></tr>
				<tr><td valign="top">Checklist : </td><td>
				<table>
					<tr id="DynDiv1">
						<td>1.</td><td><input type="text" name="newchklist1" onblur="if(this.value=='') this.value='Enter checklist';" onfocus="if(this.value=='Enter checklist') this.value='';" value="Enter checklist" /></td>
						<td><a href="javascript:void(0);" onclick="showDynamicDiv('NewDynDiv','totalDyndiv');" style="text-decoration:none">+ Add</a>
					</td>
					</tr>
					<?php
					for($p=2;$p<=5;$p++){
					?>
					<tr id="NewDynDiv<?=$p?>" style="display:none">
						<td><?=$p?>.</td><td colspan="2"><input type="text" name="newchklist<?=$p?>" onblur="if(this.value=='') this.value='Enter checklist';" onfocus="if(this.value=='Enter checklist') this.value='';" value="Enter checklist"  /></td>
					</tr>
				<?php }?>
				</table>
				</td></tr>
				<tr><td>&nbsp;</td><td><input type="submit" class="btn_small" value="Add Checklist" name="btnAddChk" /></td></tr>
				</table>
	 </div>
<!-- END ADD CHECKLIST--- -->
	  <div style="text-align:center"><a id="go" rel="leanModal" href="#add_template" name="test" class="btn_small" title="+ Add Category">+ Add Template</a>&nbsp;<a id="go" rel="leanModal" href="#add_checklist" name="test" class="btn_small" title="+ Add Checklist">+ Add Checklist</a></div>
<br>
        	  <br>
		<input type="hidden" name="gosetup4" id="gosetup4" value="0"/>	  
		<div id="div_sys_goals">	  
		  <?php
			$GoalCnt='';	 
				 
			 $selGift=mysql_query("select * from tbl_app_system_cherryboard where category_id=".$category_id." order by cherryboard_id");
			  while($rowGift=mysql_fetch_array($selGift)){
				$cherryboard_id=$rowGift['cherryboard_id'];
				$cherryboard_title=$rowGift['cherryboard_title'];
				
				  $GoalCnt.='<div class="setup_achive"><input name="chk_goals_'.$cherryboard_id.'" id="chk_goals_'.$cherryboard_id.'" type="checkbox" value="'.$cherryboard_id.'" onclick="select_goal(\'select_goal\',this.value,this.name)" class="checkbox1">
					<div class="box">
					  <div class="head"><strong>'.$cherryboard_title.'</strong></div>
					  <strong>Checklist</strong><br><br>';
					$selCheckList=mysql_query("select * from tbl_app_system_checklist where cherryboard_id=".$cherryboard_id." order by checklist_id");
					while($rowCheckList=mysql_fetch_array($selCheckList)){
						$checklist_id=$rowCheckList['checklist_id'];
						$checklist=$rowCheckList['checklist'];
						//$GoalCnt.='<div class="list"><label><input type="checkbox" name="chk_list_'.$checklist_id.'" id="chk_list_'.$checklist_id.'" value="'.$cherryboard_id.'_'.$checklist_id.'" onclick="select_checklist(\'select_checklist\',this.value,this.name)" class="checkboxes"></label>'.$checklist.'</div>';
						$GoalCnt.='<div class="list"><label><input type="checkbox" disabled="disabled" name="chk_list_'.$checklist_id.'" id="chk_list_'.$checklist_id.'" value="'.$cherryboard_id.'_'.$checklist_id.'"  class="checkboxes"></label>'.$checklist.'</div>';
					}
				  $GoalCnt.='<br>
					</div>
				  </div>';
			  }
			  //if($_SESSION['select_cat']!=''){$_SESSION['select_cat'].=',';}
			  //$_SESSION['select_cat'].=$gifts_detail;
			 
		    if($goal_arr_key==(count($_SESSION['select_gifts'])-1)){
				$GoalCnt.='<div class="clear"></div><input type="button" onclick="goto_step4()" value="Create Goalboard" class="right btn_small"><input type="button" onclick="javascript:document.location=\'setup2.php\';" value="Previous" class="right btn_small">';
			 }else{
			$GoalCnt.='<div class="clear"></div><input type="button" onclick="goto_step3_next(1)" value="Previous" class="right btn_small"><input type="button" onclick="javascript:document.location=\'setup2.php\';" value="Previous" class="right btn_small">';			
			}
			echo $GoalCnt;  
	 ?>     
	 </div>
		      <br>
	  <br>
	  <div class="clear"></div>
  </div>
<div class="clear"></div>
</div>
</form>
<!--Gray body Start-->
<!--Gray body End-->
<!--Body End-->
<?php include('site_footer.php');?>