<?php
     $debug_file = "/tmp/CSC380g";

     function debug($x) 
     {
         file_put_contents($debug_file, $x."\n", FILE_APPEND);
     }

     function show_array($array) 
     {
         $s = "";
         foreach ($_POST as $key => $value) 
	 {
              $s .= $key . " => " . $value . "\n";
         }
         return $s;
     }

     function debugshowpost() 
     {
         debug (show_array($_POST));
     }
?>
