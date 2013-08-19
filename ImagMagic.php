<?php
function GetImageWidth1($ImagePath)
{
		$image_array = getimagesize($ImagePath);
		return $orignal_image_width=$image_array[0];
}
if(isset($_POST["add"]))
{
						$DirName="testimg/";
						if(!is_dir($DirName))  
						{
								mkdir($DirName,0777); 
						}
						if(is_dir($DirName))
						{
							chmod($DirName, 0777);		// Check DIR exist.
						}
		
		
		$Var_img="txtimage";
		$ext=strtoupper(substr($_FILES[$Var_img]['name'],strlen($_FILES[$Var_img]['name'])-4));
		if($_FILES[$Var_img]['name']!="" && ($ext==".JPG" || $ext==".GIF" || $ext==".PNG" || $ext==".BMP" || $ext=="JPEG"))
		{
			$imagename=$_FILES[$Var_img]['name'];
			$filePath=$DirName.$imagename; 
			$d=copy($_FILES[$Var_img]['tmp_name'],$filePath);
			if($d)
			{
				$pathToThumbs=$DirName;
				$imagename1="Thumbnail_".$imagename;
				$pathToThumbs=$pathToThumbs.$imagename1;
				$curent_image_width=GetImageWidth1($filePath);

	//			$ImageMagic_Path="/usr/local/ImageMagick/bin/";
				$thumb_command=$ImageMagic_Path."convert ".$filePath." -thumbnail 100 x 100 ".$pathToThumbs;
				$last_line=system($thumb_command, $retval);
				$message.="Insert photos successfully.<br/>";
			}
			else
			{
				$message.="Can not upload Image";
			}
			
		}
		echo "<strong>".$message."</strong>";
}
//==>/usr/local/ImageMagick/bin/convert
?>

<html> <head> <title>Test for ImageMagick</title> </head>
<body> <?
function alist ($array) {  //This function prints a text array as an html list.
  $alist = "<ul>";
  for ($i = 0; $i < sizeof($array); $i++) {
    $alist .= "<li>$array[$i]";
  }
  $alist .= "</ul>";
  return $alist;
}
exec("convert -version", $out, $rcode); //Try to get ImageMagick "convert" program version number.
echo "Version return code is $rcode <br>"; //Print the return code: 0 if OK, nonzero if error.
echo alist($out); //Print the output of "convert -version"
//Additional code discussed below goes here.
//print image magic path
echo "<br><br>Image Magic Path is:<pre>";
system("type convert"); 
echo "</pre>";
?>
<form method="post" action="" name="frmphoto" enctype="multipart/form-data" >
<table border="0" cellpadding="0" cellspacing="0">
	<tr><td colspan="2">Test Uploading Image and generating it's thumbnail</td>
    <tr>
    	<td>Upload Image : </td>
        <td><input type="file" name="txtimage" id="txtimage" /></td>
    <tr>
    <tr><td colspan="2">&nbsp;</td>
    <tr>
    	<Td>&nbsp;</Td>
    	<td><input type="submit" name="add" value="Upload" /></td>
    </tr>						
</table>
</form>

 </body> </html>
