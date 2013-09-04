<?php
error_reporting(0);
include_once "fbmain.php";
include('include/app-common-config.php'); 
?>
<!-- START ADD STORY PHOTO FORM -->
<form name="frmaddphoto" id="frmaddphoto" action="app_services.php" method="post" enctype="multipart/form-data">
 <table>
 <tr>
	 <td>Select Photo :</td>
	 <td><input type="file" name="image_attach" id="image_attach" /></td>
 </tr>
 <tr>
	 <td>Enter Type :</td>
	 <td><input type="text" name="type" id="type"/><!--add_story_photo--></td>
 </tr>
 <tr>
	 <td>Enter FB_ID :</td>
	 <td><input type="text" name="fb_id" id="fb_id"/><!--suresh fb_id=100005132283550--></td>
 </tr>
 <tr>
	 <td>Enter Story Id :</td>
	 <td><input type="text" name="story_id" id="story_id"/><!--609--></td>
 </tr>
 <tr>
	 <td>Enter Photo Day :</td>
	 <td><input type="text" name="photo_day" id="photo_day"/><!--1--></td>
 </tr>
 <tr>
	 <td>Enter Photo Title :</td>
	 <td><input type="text" name="photo_title" id="photo_title"/><!--First Day Picture--></td>
 </tr>
 <tr>
 <td align="center" colspan="2"><input type="submit" name="add_photo" id="add_photo" value="Add Photo" /></td>
 </tr> 
 </table>
</form>
<!-- END ADD STORY PHOTO FORM -->