<?php
$x = explode(",","cat,dog,bird");
$y = join(";",$x);
print($y);
print("\n");

array_shift($x);
print_r($x);


?>
