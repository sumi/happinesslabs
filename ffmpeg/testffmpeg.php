<?php
//extension_loaded('ffmpeg') or die('Error in loading ffmpeg');
//	header("video/x-mp4");
	echo "Starting ffmpeg...\n\n";
//	putenv("LD_LIBRARY_PATH=/usr/local/lib/");
//	echo passthru("ffmpeg -f image2 -r 1 -pattern_type glob -i '*.jpg' -c:v libx264 out1.mp4");
$output = shell_exec("ffmpeg -f image2 -r 1 -pattern_type glob -i '*.jpg' -c:v libx264 out1.mp4");
echo "<pre>$output</pre>";
?>
