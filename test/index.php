<html>
<head>
<script>
function changeDivFontColor(font_color)
{
	document.getElementById('imgText').style.color = font_color;
	document.getElementById('txtcomment').style.color = font_color;
}
function changeDivFont(font_name)
{
	document.getElementById('imgText').style.fontFamily = font_name;
	document.getElementById('txtcomment').style.fontFamily = font_name;
}
function changeDivFontSize(font_size)
{
	document.getElementById('imgText').style.fontSize = font_size;
	document.getElementById('txtcomment').style.fontSize = font_size+"px";
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
  <style>
.main{ width:290px;}
.bg_images{
	color:#FFFFFF; text-align:center; top:-10px; cursor: help;
    display:block;
    opacity: 0.8;
    position:absolute;
	z-index:1000;
	top:10px;
}
.draggable { width: 100px; height: 30px; padding: 0.5em; float: left; margin:5px; }
#files { border:2px solid #ccc;width:300px;height:225px; }
</style>
</head>
<body>
<table>
<tr>
<td>
<div>
<div id="files"><img alt="" src="a.jpg" width="300px" height="225px">
	<div class="bg_images ui-widget-content" id="imgText" style="font-family:Arial;font-size:14px;">Happinesslabs</div>
	
</div>
</div>
</td>
<td>
<form  method="post">
<table>
<tr>
<td>
<input type="hidden" value="0" name="imgLeft" id="imgLeft" />
<input type="hidden" value="0" name="imgTop" id="imgTop" />
Color:
<select id="chg_color" name="chg_color" onChange="changeDivFontColor(this.value)">
<option value="white">White</option>
<option value="red">Red</option>
<option value="green">Green</option>
</select>
Font:
<select id="chg_font" name="chg_font" onChange="changeDivFont(this.value)">
<option value="Arial">Arial</option>
<option value="Times New Roman">Times NR</option>
<option value="Verdana">Verdana</option>
<option value="Courier New">Courier</option>
</select>
Size:
<select id="chg_size" name="chg_size" onChange="changeDivFontSize(this.value)">
<option value="14">14px</option>
<option value="16">16px</option>
<option value="18">18px</option>
<option value="24">24px</option>
</select>
</td>
</tr>
<tr>
<td>
<textarea onKeyPress="javascript:document.getElementById('imgText').innerHTML=document.getElementById('txtcomment').value;" id="txtcomment" class="textfield" rows="5" name="txtcomment" style="font-family:Arial;font-size:12px;">Happinesslabs</textarea>
<input type="submit" value="Upload" onClick="calculateSubmit()" name="btnsubmit" />
</td>
</tr>
</table>
</form>
</td>
<td>
<?php
if(isset($_POST['btnsubmit'])){
	//print_r($_POST);
 $txtcomment=$_POST['txtcomment'];
 $chg_color=$_POST['chg_color'];
 $chg_font=$_POST['chg_font'];
 $imgLeft=$_POST['imgLeft'];
 $imgTop=$_POST['imgTop'];	
	
 $image_magick = "convert"; 
 $font_selection = $chg_font; 

  $source_image = "a.jpg"; 
  $target_image = "b.jpg"; 
  $text = $txtcomment; 

  $ImgSize = getimagesize($source_image);
  $ImgWidth=$ImgSize[0];
  $ImgHeight=$ImgSize[1];
  
  $TopFontSize=round($ImgWidth/16);
  $TopLeftPed=$imgLeft;
  $TopTopPed=$imgTop;
  
  $command = $image_magick.' -resize '.$ImgWidth.' "'.$source_image.'" '.' -font "'.$font_selection.'" -pointsize '.$TopFontSize.' -fill '.$chg_color.' '.' -draw "text '.$TopLeftPed.', '.$TopTopPed.' \''.$text.'\'"  "'.$target_image.'"';
  passthru($command);
  echo '<img src="b.jpg"/>';
}  
 ?>
</td>
</tr>
</table>
</body>
</html>