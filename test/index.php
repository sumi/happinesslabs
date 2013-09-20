<html>
<head>
<script>
function movePosTop()
{
	var topPos=(parseInt(document.getElementById("bottomPos").value)-10);
	if(topPos>0){
		document.getElementById("b1").style.top=topPos+"px";
		document.getElementById("bottomPos").value=topPos;
	}	
}
function movePosBottom()
{
	var bottomPos=(parseInt(document.getElementById("bottomPos").value)+10);
	if(bottomPos<220){
		//alert(bottomPos);
		document.getElementById("b1").style.top=bottomPos+"px";
		document.getElementById("bottomPos").value=bottomPos;
	}
}

</script>
</head>
<body>

<style>
.files{}
.main{ width:290px;}
.bg_images{
	background-color:#333333; color:#FFFFFF; text-align:center; top:-10px; cursor: help;
    display:block;
    opacity: 0.8;
    position:absolute;
	padding:0 5px 5px 5px;
	width:290px;
	z-index:1000;
	top:10px;}
</style>
<div class="main">
<table>
<tr>
<Td valign="top">
<div id="files"><img alt="" src="a.jpg">
<div class="bg_images" id="b1" style="font-size:20px">Happinesslabs</div>
</div>
</div>
</Td>
<td>
<form  method="post">
<input type="hidden" name="bottomPos" id="bottomPos" value="0" />
<input type="button" onClick="movePosTop()" value="UP">
<input type="button" onClick="movePosBottom()" value="DOWN">
<textarea onKeyPress="javascript:document.getElementById('b1').innerHTML=document.getElementById('txtcomment').value;" id="txtcomment" class="textfield" rows="5" name="txtcomment">Happinesslabs</textarea>
<?php
if(isset($_POST['btnsubmit'])&&$_POST['bottomPos']>0){
 $image_magick = "convert"; 
  $font_selection = "bebas.ttf"; 

  $source_image = "a.jpg"; 
  $target_image = "b.jpg"; 
  $text = $_POST['txtcomment']; 

  $ImgSize = getimagesize($source_image);
  $ImgWidth=$ImgSize[0];
  $ImgHeight=$ImgSize[1];
  
  $TopFontSize=round($ImgWidth/16);
  $TopLeftPed=0;//round($ImgWidth/3);
  $TopTopPed=$_POST['bottomPos'];//round($ImgHeight/5);
  
  $command = $image_magick.' -resize '.$ImgWidth.' "'.$source_image.'" '.' -font "'.$font_selection.'" -pointsize '.$TopFontSize.' -fill white '.' -draw "text '.$TopLeftPed.', '.$TopTopPed.' \''.$text.'\'"  "'.$target_image.'"';
  passthru($command);

	echo '<img src="b.jpg"/>';
}  
 ?>
<br/><Br/>
<input type="submit" value="Go" name="btnsubmit" />
</form>
</td>
</tr>
</table>
</body>
</html>