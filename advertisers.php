<?php
include("fbmain.php");
include('include/app-common-config.php');
?>
<?php include('site_header.php');?>
<!--Body Start-->
<div id="wrapper_main" style="padding-top: 100px;">
<h1 title="About">For Advertisers:</h1><br>
<img src="images/img_about.jpg" width="267" height="217" class="right">
<div class="head_24">We believe that products and services that make peoples lives better deserve a center stage.
</div>
<br/>
<br>
<br>
<span class="redish">That center stage is people's hearts.</span><br>
<br>
<br/>
<table width="50%" border="0" cellpadding="5" cellspacing="0">
<tr>
<td colspan="2">If you have a product or service that you believe makes a difference in peoples lives then HappinessLabs is the place for you.</td>
</tr>
<tr>
<tr><td colspan="2"><strong>Show happiness:</strong></td>
</tr>
<tr><td colspan="2">Start by showing a  happy story.<br/>Create a happy story showing how your product/service makes a difference in peoples lives.</td></tr>
<tr>
<tr><td colspan="2"><strong>Inspire and multiply happiness:</strong></td>
</tr>
<tr><td colspan="2">Invite your fans, friends, customers to join and inspired by the happy story you created.<br/>
Inspiration moves people to take action and create happy stories in their own lives.</td></tr>
</table>
<br><br><br>
<?php
if(FB_ID==0){ 
echo '<a href="#" class="btn_blue" onClick="fb_login();">connect with facebook</a>';
}
?>
</div>
<br><br><br>
<!--Body End-->
<?php include('site_footer.php');?>