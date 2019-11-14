<?php
session_start();
$creater = $_SESSION["username"];
if(!$_GET["quizname"] == ""){
    echo "yes";
    if(!$_GET["question"] == ""){
            if(!$_GET["answer"] == ""){
                    $fa = fopen("quiz.txt","a");
                    fwrite($fa,$creater);
                    fwrite($fa,",$%#");
                    fwrite($fa,$_GET["quizname"]);
                    fwrite($fa,",$%#");
                    fwrite($fa,$_GET["question"]);
                    fwrite($fa,",$%#");
                    fwrite($fa,$_GET["answer"]);
                    fwrite($fa,"===\n");
                    fclose($fa);
                    $_SESSION["status"] ='success';
                    header("Location: teacher.php");
		    exit();
            }
            $_SESSION["status"] = 'error';
            header("Location: teacher.php");
	    exit();
    }
    $_SESSION["status"] = 'error';
    header("Location: teacher.php");
    exit();
}
$_SESSION["status"] = 'error';
header("Location: teacher.php");
exit();
?>
