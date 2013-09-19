<?php
if(isset($_POST['btnsubmit'])){
 $image_magick = "convert"; 
  $font_selection = "bebas.ttf"; 

  $source_image = "a.jpg"; 
  $target_image = "b.jpg"; 
  $text = $_POST['phototext']; 

  $ImgSize = getimagesize($source_image);
  $ImgWidth=$ImgSize[0];
  $ImgHeight=$ImgSize[1];
  
  $TopFontSize=round($ImgWidth/8);
  $TopLeftPed=0;//round($ImgWidth/3);
  $TopTopPed=100;//round($ImgHeight/5);
  
  echo $command = $image_magick.' -resize '.$ImgWidth.' "'.$source_image.'" '.' -font "'.$font_selection.'" -pointsize '.$TopFontSize.' -fill white '.' -draw "text '.$TopLeftPed.', '.$TopTopPed.' \''.$text.'\'"  "'.$target_image.'"';
  passthru($command);
}  
 ?>
<img src="a.jpg" /><br/><Br/>
<form  method="post">
<input type="text" name="phototext" value="" />
<input type="submit" value="Go" name="btnsubmit" />
</form>
<img src="b.jpg" />