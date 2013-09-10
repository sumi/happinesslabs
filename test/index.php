<?php
exec("montage b.jpg c.jpg \ -mode Concatenate -tile x1  montage_cat.jpg");
?>
<br />
<img src="a.jpg" />
<br />
<img src="b.jpg"/>
<br />
<img src="c.jpg"/>
===============<br />================
<img src="montage_cat.jpg"/>