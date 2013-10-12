<?php
include_once "fbmain.php";
include('include/app-common-config.php');
$userDetail=getUserDetail(USER_ID,'uid');
$photo_url=$userDetail['photo_url'];
$user_name=$userDetail['name'];
?>
<?php include('site_header.php');?>
<style type="text/css">
.Upload_Photo_bg{width:667px; height:258px; margin:auto; border:2px solid #cccccc; padding:10px 8px;
 border-radius:5px 5px 5px 5px;
-webkit-border-radius:5px 5px 5px 5px;
-moz-border-radius:5px 5px 5px 5px;}

.Upload_Photo_left{float:left; width:263px; font-family:Arial, Helvetica, sans-serif; font-size:12px;}
.Upload_Photo_left_bottom{float:left; width:192px; font-family:Arial, Helvetica, sans-serif; font-size:12px; margin-right:10px;}
.left_Scumbag_bg{float:left; margin-right:3px; border:1px solid #cecece; font-size:12px; font-family:Arial, Helvetica, sans-serif;}
.left_Scumbag_img{width:192px; height:192px; margin:10px 0;}
.left_Scumbag_text{text-align:center; margin-top:10px;}

.Upload_Photo_right{float:left; width:400px; font-family:Arial, Helvetica, sans-serif;}
.Upload_Photo_right1{float:left; width:465px; font-family:Arial, Helvetica, sans-serif;}
.right_Upload_one{float:left; font-size:16px; margin-left:55px;}
.right_Upload_Popular_main{float:left; width:575px; margin-top:10px;}
.right_Upload_Popular{float:left;}
.right_Upload_Popular a{color:#888888; text-decoration:none; padding:3px 5px; background-color:#eeeeee; display:inline-block; border-top:1px solid #888888; border-left:1px solid #888888; border-right:1px solid #888888; margin-right:5px;
 border-radius:4px 4px 0px 0px;
-webkit-border-radius:4px 4px 0px 0px;
-moz-border-radius:4px 4px 0px 0px;}
.right_Upload_Popular a:hover{color:#333333; border-top:1px solid #333333; border-left:1px solid #333333; border-right:1px solid #888888;}
.right_Upload_Search{width:186px; margin-left:15px; padding:5px; height:12px; color:#aeaeae; border:1px solid #cccccc;}
.right_Upload_image{ border: 1px solid #cccccc; width:464px; height:58px; overflow-x: scroll; overflow-y: hidden; padding-top: 3px; white-space: nowrap; padding:5px 0;}
.right_Upload_Font{float:left; font-size:14px; color:#666666; margin:15px 5px 15px 0;}
.right_Upload_select{float:left; border:1px solid #999999; background-color:#f4f4f4; margin:7px 15px 0px 0;}
.right_Upload_input_box{float:left; width:250px; border:1px solid #999999; height:28px; font-size:14px; color:#b1b1b1; padding:5px;}
.right_Upload_input_bg{float:left; background-color:#cccccc; width:200px; height:41px;}
.right_Upload_Font_box{float:left; font-size:14px; color:#383838; margin:12px 5px 15px 5px;}
.right_Upload_Color_box{float:left; border:1px solid #383838; width:60px; background-color:#f4f4f4; margin:10px 0px 0px 0;}
.right_Upload_icon{float:left; width:50px; height:50px; margin-right:5px;}
.right_Upload_Phptp{float:left; font-size:16px; color:#bf1e2e; margin-top:22px;}
.right_Upload_Cancel{float:left; width:90px;}
.right_Upload_Cancel a{float:left; background-color:#cccccc; padding:5px 10px; margin:12px 10px 0 10px; box-shadow: 0 3px 0px #929292; font-size:16px; color:#525252; text-decoration:none; text-align:center;
 border-radius:5px 5px 5px 5px;
-webkit-border-radius:5px 5px 5px 5px;
-moz-border-radius:5px 5px 5px 5px;}
.right_Upload_Cancel a:hover{background-color:#acacac; box-shadow: 0 3px 0px #cccccc; color:#FFFFFF;}

.foo{   
    float: left;
    width: 20px;
    height: 20px;
    margin: 5px 5px 5px 5px;
    border-width: 1px;
    border-style: solid;
    border-color: rgba(0,0,0,.2);
}	
.right_Upload_Font_icon{font-size:14px; color:#666666; margin-top:5px;}
</style>
<!-- ================================ -->
 <style>
.main{ width:290px;}
.bg_images{
	color:#FFFFFF; text-align:center; top:-20px; cursor:move;
    display:block;
    opacity: 0.8;
    z-index:1000;
}
.draggable { width: 100px; height: 30px; padding: 0.5em; float: left; margin:5px; }
#files { border:2px solid #ccc;width:192px;height:192px; }
</style>     
<script>
function changeDivFontColor(font_color)
{
	document.getElementById('imgText').style.color = font_color;
}
function changeDivFont(font_name)
{
	document.getElementById('imgText').style.fontFamily = font_name;
}
function changeDivFontSize(font_size)
{
	document.getElementById('imgText').style.fontSize = font_size;
}

function calculateSubmit(){
	var p = $( "#imgText" );
	var position = p.position();
	document.getElementById('imgLeft').value=position.left;
	document.getElementById('imgTop').value=position.top;
	//alert("left: " + position.left + ", top: " + position.top);
	return true;
}
</script>
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  <script>
  $(function() {
    $("#imgText").draggable({ containment: "#files", scroll: false });
  });
  </script>
 
<?php
$pid=$_GET['pid'];
$pquery=mysql_query("select * from tbl_app_expert_cherry_photo where photo_id=".$pid);
while($pqueryRow=mysql_fetch_array($pquery)){
	$file_name=$pqueryRow['photo_name'];
	$txtcomment='default text';
}
?>
<div class="Upload_Photo_bg" style="background-color:#FFFFFF">
 <input type="hidden" value="0" name="imgLeft" id="imgLeft" />
 <input type="hidden" value="0" name="imgTop" id="imgTop" />
 <input type="hidden" value="<?=$file_name?>" name="file_name" id="file_name" />
 <input type="hidden" value="90" name="rotate_degree" id="rotate_degree" />
 <input type="hidden" value="" name="load_dir" id="load_dir" />
   
   <div class="Upload_Photo_left_bottom">
    <div class="left_Scumbag_img">
	
	<div id="files"><img alt="" id="photo_img" src="images/expertboard/<?=$file_name?>" width="192px" height="192px">
		<div class="bg_images ui-widget-content" id="imgText" style="font-family:Arial;font-size:14px;color:#FFFFFF">
		Happinesslabs</div>
		
	</div>


     <div class="left_Scumbag_text">Max allowed 3MB</div>
     </div>
   </div>
     
    <div class="Upload_Photo_right1">
     <div class="right_Upload_Popular_main">
      <div class="right_Upload_image">
      <img src="images/expertboard/demo_img/demo_img1.jpg" width="42" height="44"  alt="" onclick="changeImage('demo_img1.jpg')" style="cursor:pointer" />
      <img src="images/expertboard/demo_img/demo_img2.jpg" width="42" height="44"  alt="" onclick="changeImage('demo_img2.jpg')" style="cursor:pointer" />
	  <img src="images/expertboard/demo_img/demo_img3.jpg" width="42" height="44"  alt="" onclick="changeImage('demo_img3.jpg')" style="cursor:pointer" />
	  <img src="images/expertboard/demo_img/demo_img4.jpg" width="42" height="44"  alt="" onclick="changeImage('demo_img4.jpg')" style="cursor:pointer" />
	  <img src="images/expertboard/demo_img/demo_img5.jpg" width="42" height="44"  alt="" onclick="changeImage('demo_img5.jpg')" style="cursor:pointer" />
	  <img src="images/expertboard/demo_img/demo_img6.jpg" width="42" height="44"  alt="" onclick="changeImage('demo_img6.jpg')" style="cursor:pointer" />
	  <img src="images/expertboard/demo_img/demo_img7.jpg" width="42" height="44"  alt="" onclick="changeImage('demo_img7.jpg')" style="cursor:pointer" />
	  <img src="images/expertboard/demo_img/demo_img8.jpg" width="42" height="44"  alt="" onclick="changeImage('demo_img8.jpg')" style="cursor:pointer" />
	  <img src="images/expertboard/demo_img/demo_img9.jpg" width="42" height="44"  alt="" onclick="changeImage('demo_img9.jpg')" style="cursor:pointer" />
      </div>
	  <div class="right_Upload_Font_icon">
	  <img src="images/test/uploadIcon.jpg" height="50" alt="" /></div>
      <div class="right_Upload_Font">Font</div>
	  <select id="chg_font" class="right_Upload_select" name="chg_font" onChange="changeDivFont(this.value)">
<option value="Arial">Arial</option>
<option value="Times New Roman">Times NR</option>
<option value="Verdana">Verdana</option>
<option value="Courier New">Courier</option>
</select>
      <div class="right_Upload_Font">Fontsize</div>
      <select class="right_Upload_select" id="chg_size" name="chg_size" onChange="changeDivFontSize(this.value)">
<option value="14px">14px</option>
<option value="16px">16px</option>
<option value="18px">18px</option>
<option value="24px">24px</option>
</select>
     </div>
     <textarea onkeyup="javascript:document.getElementById('imgText').innerHTML=document.getElementById('txtcomment').value;" id="txtcomment" rows="5" name="txtcomment" style="font-family:Arial;font-size:12px;" class="right_Upload_input_box">Happinesslabs</textarea>
      <div class="right_Upload_input_bg">
          <div class="right_Upload_Font_box">Color</div>
      		<select id="font_color" name="font_color" onChange="changeDivFontColor(this.value)" class="right_Upload_select">
				<option value="white">White</option>
				<option value="black">Black</option>
				<option value="red">Red</option>
				<option value="green">Green</option>
				</select>
      </div>
      
      <div class="right_Upload_Popular_main">
        <div class="right_Upload_icon" style="width:35px"><img height="35" width="35" id="rotate_img" alt="" onclick="rotate_photo1()" style="cursor:pointer" src="images/round_arrow_90.jpg"></div>
        <div class="right_Upload_icon"><img height="50" width="50" onclick="photo_filter1('effect1')" style="cursor:pointer" src="images/filter/effect1.jpg"></div>
        <div class="right_Upload_icon"><img height="50" width="50" onclick="photo_filter1('effect2')" style="cursor:pointer" src="images/filter/effect2.jpg"></div>
        <div class="right_Upload_icon"><img height="50" width="50" onclick="photo_filter1('effect3')" style="cursor:pointer" src="images/filter/effect3.jpg"></div>
        <div class="right_Upload_icon"><img height="50" width="50" onclick="photo_filter1('effect4')" style="cursor:pointer" src="images/filter/effect4.jpg"></div>
        <div class="right_Upload_icon"><img height="50" width="50" onclick="photo_filter1('effect0')" style="cursor:pointer" src="images/filter/effect0.jpg"></div>
		 <div class="right_Upload_Cancel"><a href="#">Cancel</a></div>
        <div class="right_Upload_Cancel"><a href="#">Post</a></div>
		</div>
      
	   
   </div>
   
 </div>
<div style="padding-bottom:60px;"></div>
<script language="javascript">
function changeImage(file_name) {
        document.getElementById("photo_img").src='images/expertboard/demo_img/'+file_name;
		document.getElementById('file_name').value=file_name;
		document.getElementById("load_dir").value='demo_img/';
}
function rotate_photo1()
{	
	showLoadingImg('rotate_img');
	var file_name=document.getElementById('file_name').value;
	var rotate_degree=document.getElementById('rotate_degree').value;
	var load_dir=document.getElementById('load_dir').value;
	var url = "upload_photo_editor.php?type=rotate&file_name="+file_name+"&rotate_degree="+rotate_degree+"&load_dir="+load_dir;
	//alert(url);
	http2.open("GET", url , true); 
	http2.onreadystatechange = handleHttpResponse_rotate_photo1; 
	http2.send(null);	
}
function handleHttpResponse_rotate_photo1()
{    
	if (http2.readyState == 4) 
	{ 
	  if(http2.status==200) 
	  { 
		var results=http2.responseText;
		results=results.replace(/(\r\n|\n|\r)/gm,"");
		//alert(results);
		results_array=results.split('##===##');
		var action_type=results_array[0];
		var file_name=results_array[1];
		var rotate_degree=results_array[2];
		var rotate_img=results_array[3];
		
		document.getElementById('file_name').value=file_name;
		document.getElementById('rotate_degree').value=rotate_degree;
		document.getElementById('load_dir').value='temp/';
		document.getElementById('photo_img').src='images/expertboard/temp/'+file_name;
		document.getElementById('rotate_img').src='images/'+rotate_img;
		
	  } 
	} 
}
function photo_filter1(filter_type)
{	
	showLoadingImg('rotate_img');
	var file_name=document.getElementById('file_name').value;
	var load_dir=document.getElementById('load_dir').value;
	var url = "upload_photo_editor.php?type=filter&file_name="+file_name+"&filter_type="+filter_type+"&load_dir="+load_dir;	
	//alert(url);
	http2.open("GET", url , true); 
	http2.onreadystatechange = handleHttpResponse_photo_filter1; 
	http2.send(null);	
}
function handleHttpResponse_photo_filter1()
{    
	if (http2.readyState == 4) 
	{ 
	  if(http2.status==200) 
	  { 
		var results=http2.responseText;
		results=results.replace(/(\r\n|\n|\r)/gm,"");
		//alert(results);
		results_array=results.split('##===##');
		var action_type=results_array[0];
		var file_name=results_array[1];
		
		document.getElementById('file_name').value=file_name;
		document.getElementById('load_dir').value='temp/';
		document.getElementById('photo_img').src='images/expertboard/temp/'+file_name;
		document.getElementById('rotate_img').src='images/round_arrow_90.jpg';
	  } 
	} 
}
</script>
<?php include('site_footer.php');?>
