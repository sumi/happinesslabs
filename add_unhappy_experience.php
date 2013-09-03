<?php
include_once "fbmain.php";
include('include/app-common-config.php');
?>
<?php include('site_header.php'); ?>
<div id="main">
	<div id="wrapper" style="background-color:#FFFFFF;padding-top:10px;">
	<form name="frmUnhappyExp" id="frmUnhappyExp" action="" method="post" enctype="multipart/form-data">
		<table border="0" align="center">
		<tr>
			<td><strong>Category</strong> : </td>
			<td><?=getCategoryList(0,'style="border:1px solid #33CCFF; color:#999999; font-size:16px; width:260px;"','category_id1')?></td>
		</tr>
		<tr>
			<td><strong>Who</strong> : </td>
			<td><input name="who" id="who" type="text" style="border:1px solid #33CCFF; color:#999999; font-size:16px; padding:5px 5px 4px 5px; width:250px;"/></td>
		</tr>
		<tr>
			<td><strong>Where</strong> : </td>
			<td><input name="where" id="where" type="text" style="border:1px solid #33CCFF; color:#999999; font-size:16px; padding:5px 5px 4px 5px; width:250px;"/></td>
		</tr>
		<tr>
			<td><strong>When</strong> : </td>
			<td><input name="when" id="when" type="text" style="border:1px solid #33CCFF; color:#999999; font-size:16px; padding:5px 5px 4px 5px; width:250px;"/></td>
		</tr>
		<tr>
			<td><strong>What</strong> : </td>
			<td><input name="what" id="what" type="text" style="border:1px solid #33CCFF; color:#999999; font-size:16px; padding:5px 5px 4px 5px; width:250px;"/></td>
		</tr>
		<tr>
			<td valign="top"><strong>Why</strong> : </td>
			<td><textarea name="why" id="why" style="color:#999999; font-size:16px; padding:5px 5px 4px 5px; width:250px; height:50px; border:1px solid #33CCFF;"></textarea></td>
		</tr>
		<tr>
			<td><strong>How</strong> : </td>
			<td><textarea name="how" id="how" style="color:#999999; font-size:16px; padding:5px 5px 4px 5px; width:250px; height:50px; border:1px solid #33CCFF;"></textarea></td>
		</tr>
		<tr>
			<td valign="top"><strong>How to avoid next</strong> : </td>
			<td><textarea name="howtoavoidnext" id="howtoavoidnext" style="color:#999999; font-size:16px; padding:5px 5px 4px 5px; width:250px; height:50px; border:1px solid #33CCFF;"></textarea></td>
		</tr>
		<tr>
			<td><strong>Give Unhappy Points (1)</strong> : </td>
			<td><input name="unhappypoint1" id="unhappypoint1" type="text" style="border:1px solid #33CCFF; color:#999999; font-size:16px; padding:5px 5px 4px 5px; width:250px;"/></td>
		</tr>
		<tr>
			<td><strong>Give Unhappy Points (2)</strong> : </td>
			<td><input name="unhappypoint1" id="unhappypoint1" type="text" style="border:1px solid #33CCFF; color:#999999; font-size:16px; padding:5px 5px 4px 5px; width:250px;"/></td>
		</tr>
		<tr><td>&nbsp;</td></tr>
		<tr>
			<td colspan="2"><input type="submit" name="btnunhappy" id="btnunhappy" value="Add Unhappy Experience" class="button" style="padding:5px;"/></td>
		</tr>
		</table>
	</form>
	<br/><br/><br/>
	</div>
    <div class="clear"></div>
</div>
<?php include('site_footer.php');?> 