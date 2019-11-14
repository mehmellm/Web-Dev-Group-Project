<?php
	function dbg($x) {
        //  change the filemame
         file_put_contents("debug.txt", $x."\n", FILE_APPEND);
     }

     dbg("Hi there");
?>

