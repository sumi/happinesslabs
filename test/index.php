<?php
//exec("convert photo.jpg -modulate 120,10,100 -fill '#222b6d' -colorize 20 -gamma 0.5 -contrast -contrast photo1.jpg");

require 'instagraph.php';
 
try
{
    $instagraph = Instagraph::factory('photo.jpg', 'output.jpg');
}
catch (Exception $e) 
{
    echo $e->getMessage();
    die;
}
 
$instagraph->effect1(); // name of the filter
?>
<img src="output.jpg" height="300" width="300" />
<?php

try
{
    $instagraph = Instagraph::factory('photo.jpg', 'output1.jpg');
}
catch (Exception $e) 
{
    echo $e->getMessage();
    die;
}
 
$instagraph->effect2(); // name of the filter
?>

<img src="output1.jpg" height="300" width="300" />