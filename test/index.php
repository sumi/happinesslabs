<!doctype html>
 
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>jQuery UI Droppable - Default functionality</title>
  <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
  <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
  <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  <link rel="stylesheet" href="/resources/demos/style.css" />
  <style>
  .draggable { float: left; cursor:move; }
  .draggable1 { float: left; cursor:move; }
  
#resizable { width: 100px; padding: 0.5em; }
  #resizable h3 { text-align: center; margin: 0; }
  
  #set { clear:both; float:left; width: 368px; height: 120px; }
 .ui-widget-content {
    background: url("images/ui-bg_flat_75_ffffff_40x100.png") repeat-x scroll 50% 50%;
    border: 1px solid #AAAAAA;
    color: #222222;
}
  </style>
  <script>
  $(function() {
    $( "#draggable1" ).draggable();
	$( "#draggable2" ).draggable();
	$( "#draggable3" ).draggable();
	$( "#draggable4" ).draggable();
	$( "#draggable5" ).draggable();
	$( "#draggable6" ).draggable();
	$( "#draggable7" ).draggable();
	$( "#draggable8" ).draggable();
	$( "#draggable9" ).draggable();
	$( "#resizable" ).resizable();
	$( "#set div" ).draggable({ stack: "#set div" });
  });
  
  
  </script>
</head>
<body>
[Arrange Picture as per your creativity]
<img src="full.png" style="float:right">
<div id="set" style="float:left"> 
<div id="draggable1" class="draggable ui-widget-content" style="border: 0px solid #AAAAAA;">
  <img src="1.png" height="100" width="300">
</div>
<div id="draggable2" class="draggable ui-widget-content" style="border: 0px solid #AAAAAA;">
  <img src="2.png" height="200" width="200">
</div>
<div id="draggable3" class="draggable ui-widget-content" style="border: 0px solid #AAAAAA;">
  <img src="3.png" height="200" width="200">
</div>

<div id="draggable4" class="draggable ui-widget-content" style="border: 0px solid #AAAAAA;">
  <img src="bird_3.png" height="100" width="100">
</div>

<div id="draggable5" class="draggable ui-widget-content" style="border: 0px solid #AAAAAA;">
  <img src="bird_1.png" height="100" width="100">
</div>

<div id="draggable6" class="draggable ui-widget-content" style="border: 0px solid #AAAAAA;">
  <img src="bird_2.png" height="100" width="100">
</div>

<div id="draggable7" class="draggable ui-widget-content" style="border: 0px solid #AAAAAA;">
  <img src="bird_3.png" height="100" width="100">
</div>

<div id="draggable8" class="draggable ui-widget-content" style="border: 0px solid #AAAAAA;">
  <img src="bird_3.png" height="100" width="100">
</div>

<div id="draggable9" class="draggable ui-widget-content" style="border: 0px solid #AAAAAA;">
 	<div id="resizable" class="ui-widget-content">
  		<font style="color:#CC0000;font-family:Verdana, Arial, Helvetica, sans-serif;font-size:16px">Seabird fly</font>
	</div>
</div>


 
</div> 

 
</body>
</html>