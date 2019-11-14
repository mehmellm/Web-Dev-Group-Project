<?php
session_start();
$quiz_arr = $_SESSION['quiz'];
if ($_SESSION['username'] == ""){
header("Location: sqlLogin.php");
exit();
}
$db['host'] = "brahe.canisius.edu";  // DBサーバのURL
$db['user'] = "CSC380f";  // ユーザー名
$db['pass'] = "paris99";  // ユーザー名のパスワード
$db['dbname'] = "CSC380f";  // データベース名
$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang=“ja”>
<head>
    <meta charset=“UTF-8”>
	<link rel="stylesheet" href="SecondStylesheet.css">
    <title>Document</title>
</head>
<button><a href="./stud_reg_course.php">Register new course</a></button>
<button><a href="./stud_message.php">chat</a></button>
<body>
<h2 style="text-decoration:underline;">Student Home Page</h2>

<?php

echo "</br>Currently Logged In","</br></br>";
echo "Username : ",$_SESSION["username"],"</br>";
echo "faculity : student</br>";
?>

<?php
try{
        $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);

        $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
        $stmt = $pdo->prepare('SELECT quiztitle, course FROM quiztaker WHERE taker = ?');
        $stmt->execute(array($username));
        $taken = $stmt->fetchAll(PDO::FETCH_ASSOC);
        //echo var_dump($courses);
        $taken_quiz = array();
        if ($taken != false){
            foreach($taken as $quizname) {
                $d = $quizname['quiztitle']."  (".$quizname['course'].")"; 
                array_push($taken_quiz,$d);
            }
        }
        //echo var_dump($taken_quiz);

        $stmt = $pdo->prepare('SELECT course FROM course WHERE user = ?');
        $stmt->execute(array($username));
        $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
        //echo var_dump($courses);
	    $quiz_arr = array();
        if ($courses != false){
            foreach($courses as $course){
		//echo $course['course'];
                $stmt = $pdo->prepare('SELECT title, course FROM quiz WHERE relstatus = "releasing" AND course = ?');
		$stmt->execute(array($course['course']));
                $cols = $stmt->fetchAll(PDO::FETCH_ASSOC);
                //echo var_dump($cols);
		if (!empty($cols)){
		    //echo 'here';
                    $quiz_arr = array_merge($quiz_arr, $cols);
                }
        }

        //echo var_dump($quiz_arr);

        $allquiz = array();
        if ($quiz_arr != false){
            foreach($quiz_arr as $col) {
                $d = $col['title']."  (".$col['course'].")"; 
                array_push($allquiz,$d);
            }
        }
	//echo var_dump($allquiz);
	//echo var_dump($taken_quiz);

        $quiz_arr = array_diff($allquiz, $taken_quiz);
	    echo "<br>";
            echo "your available quiz";
        }else{
            echo 'no quiz available';
        }
        
    }catch (PDOException $e){
        echo "something wrong!!";
    }
    echo '<br>';
    echo '<br>';
    ?>


    
    <?php
	//echo var_dump($quiz_arr);
	echo '<ol>';
	if (!empty($quiz_arr)){
                foreach($quiz_arr as $col) {
                    $temp = explode("  ",$col);
                    $temp[1] = substr($temp[1],1,-1);
                    //echo var_dump($col);
                    //echo var_dump($temp[0]);
                    echo "<li><a href=\"takequiz.php?title=". $temp[0] ."&course=".$temp[1]."\">".$col."</a></li>";
                }
                echo '</ol>';
        }else{
	echo 'no quiz available';
	}
/*try{
        $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);
        $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

        $stmt = $pdo->prepare('SELECT course FROM course WHERE user = ?');
        $stmt->execute(array($username));
        $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
        //echo var_dump($courses);
	    $quiz_arr = array();
        if ($courses != false){
            foreach($courses as $course){
		//echo $course['course'];
                $stmt = $pdo->prepare('SELECT title, course FROM quiz WHERE revealable="no" AND course = ?');
		$stmt->execute(array($course['course']));
                $cols = $stmt->fetchAll(PDO::FETCH_ASSOC);
                //echo var_dump($cols);
		if (!empty($cols)){
		    //echo 'here';
                    $quiz_arr = array_merge($quiz_arr, $cols);
		    //echo var_dump($quiz_arr);
                }
        }

        $allquiz = array();
        if ($quiz_arr != false){
            foreach($quiz_arr as $col) {
                $d = $col['title'] . "  (".$col['course'].")"; 
                array_push($allquiz,$d);
            }
        }
	//echo var_dump($allquiz);
	//echo var_dump($taken_quiz);

        $quiz_arr = array_diff($allquiz, $taken_quiz);

            echo "available quiz";
            echo '<ol>';
	    //echo var_dump($quiz_arr);
	    //echo $quiz_arr;
            if (!empty($all_quiz)){
                foreach($all_quiz as $col) {
                    $temp = explode("  ",$col);
                    $temp[1] = substr($temp[1],1,-1);
		    //echo var_dump($col);
	 	    //echo var_dump($temp[0]);
                    echo "<li><a href=\"takequiz.php?title=". $temp[0] ."&course=".$temp[1]."\">".$col."</a></li>";
                }
	        echo '</ol>';
            } else {
                echo "no quiz available";
            }
        }else{
            echo 'no quiz available';
        }
        
    }catch (PDOException $e){
        echo "something wrong!!";
    }*/
    echo '<br>';
    echo '<br>';
    ?>


    
    <?php
    //reveable
try{
        $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);
        $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

        $stmt = $pdo->prepare('SELECT course FROM course WHERE user = ?');
        $stmt->execute(array($username));
        $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
        //echo var_dump($courses);
	    $quiz_arr = array();
        if ($courses != false){
            foreach($courses as $course){
		//echo $course['course'];
                $stmt = $pdo->prepare('SELECT title, course FROM quiz WHERE revealable="yes" AND course = ?');
		$stmt->execute(array($course['course']));
                $cols = $stmt->fetchAll(PDO::FETCH_ASSOC);
                //echo var_dump($cols);
		if (!empty($cols)){
		    //echo 'here';
                    $quiz_arr = array_merge($quiz_arr, $cols);
		    //echo var_dump($quiz_arr);
                }
        }

        $allquiz = array();
        if ($quiz_arr != false){
            foreach($quiz_arr as $col) {
                $d = $col['title'] . "  (".$col['course'].")"; 
                array_push($allquiz,$d);
            }
        }
	//echo var_dump($allquiz);
	//echo var_dump($taken_quiz);
	//echo var_dump($quiz_arr);
        //$quiz_arr = $quiz_arr[0];
        //$quiz_arr = array_diff($allquiz, $taken_quiz);

            echo "revealed quiz";
            echo '<ol>';
	    //echo var_dump($quiz_arr);
	    //echo $allquiz;
            if (!empty($allquiz)){
                foreach($allquiz as $col) {
                    $temp = explode("  ",$col);
                    $temp[1] = substr($temp[1],1,-1);
		    //echo var_dump($col);
	 	    //echo var_dump($temp[0]);
                    echo "<li><a href=\"revealedAns.php?title=". $temp[0] ."&course=".$temp[1]."\">".$col."</a></li>";
                }
	        echo '</ol>';
            } else {
                echo "no quiz available";
            }
        }else{
            echo 'no quiz available';
        }
        
    }catch (PDOException $e){
        echo "something wrong!!";
    }
    echo '<br>';
    echo '<br>';
    ?>

<button value="logout"><a href="logout.php">logout</a></button>


</body>

