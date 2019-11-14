<?php
	session_start();

	$coursename = $_GET['coursename'];

	$fa = fopen("courselist.txt","r");
	$text = fread($fa,filesize("courselist.txt"));
	fclose($fa);
	$lines = explode("\n",$text);
	
	foreach ($lines as $line) 
	{
    		if ($line == $coursename)
		{
        		header("Location: fcourse.php");
        		exit();
    		}		
	}

	$f = fopen("courselist.txt","a");
	fwrite($f, $coursename."\n");
	fclose($f);

/*
SQL codes goes here
*/

	header("Location: teacher.php");
	exit();
?>
