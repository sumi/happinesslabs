<?php
//exec("montage b.jpg c.jpg \ -mode Concatenate -tile x1  montage_cat.jpg");
//exec("montage a.jpg b.jpg c.jpg -geometry +2+2 montage_geom.jpg");
exec("convert a.jpg b.jpg c.jpg  +append -quality 75 'montage_geom.jpg'");
?>
<br />
<img src="a.jpg" />
<br />
<img src="b.jpg"/>
<br />
<img src="c.jpg"/>
===============<br />================
<img src="montage_geom.jpg"/>