<?php
//exec("montage a.jpg b.jpg \ c.jpg -mode Concatenate -tile x1  montage_cat.jpg");
//exec("montage a.jpg -geometry +2+2 montage_geom.jpg");//Single Image
//exec("convert a.jpg b.jpg +append -quality 100 -geometry +2+2 'montage_geom.jpg'");//Two Image
exec("convert a.jpg b.jpg +append c.jpg -append -quality 100  -geometry +2+2 'montage_geom.jpg'");//Three Image
//exec("montage a.jpg b.jpg c.jpg d.jpg -quality 100 -geometry +2+2 montage_geom.jpg");//Four Image
?>
<br />
<img src="a.jpg" />
<br />
<img src="b.jpg"/>
<br />
<img src="c.jpg"/>
===============<br />================
<img src="montage_geom.jpg"/>