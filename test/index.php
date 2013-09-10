<?php
exec("montage a.jpg b.jpg c.jpg \ -mode Concatenate -tile x1  montage_cat.jpg");
?>
<br />
<img src="a.jpg" height="300" width="300" />
<br />
<img src="b.jpg" height="300" width="300" />
<br />
<img src="c.jpg" height="300" width="300" />
===============<br />================
<img src="montage_cat.jpg" height="300" width="300" />