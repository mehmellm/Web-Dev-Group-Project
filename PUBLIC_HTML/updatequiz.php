<?php
session_start();
$course = $_GET['course'];
$title = $_GET['title'];
$username = $_SESSION['username'];

if ($_SESSION["username"]==""){
        header("Location: sqlLogin.php");
}

$qids = $_SESSION['qids'];

$db['host'] = "brahe.canisius.edu";  // DBサーバのURL
$db['user'] = "CSC380f";  // ユーザー名
$db['pass'] = "paris99";  // ユーザー名のパスワード
$db['dbname'] = "CSC380f";  // データベース名

$result = array();
$waiting = array();
$tags = "";
$quiz = "";
$multjudger = $_SESSION['multjudger'];
$multa = "";
$multb = "";
$multc = "";
$multd = "";
$ans = "";
?>

<?php
if (isset($_POST["up"])) {
    if (empty($_POST["up"])) {  // emptyは値が空のとき
        $errorMessage = 'choose quiz to release';
    }

    $course = $_POST["course"];
    $title = $_POST["title"];
    $tags = $_POST["tags"];
    $quiz = $_POST["question"];
    $ans = $_POST["answer"];
    $multa = $_POST["a"];
    $multb = $_POST["b"];
    $multc = $_POST["c"];
    $multd = $_POST["d"];
    //echo $tags;
    if (!empty($_POST["up"])) {
        try{
            $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);
            $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
            if ($multjudger == true){
                $stmt = $pdo->prepare('UPDATE quiz SET course=?, title=?, tags=?, quiz=?, answer=?, 
                multa=?,multb=?,multc=?,multd=?, WHERE qids = ?');
                $stmt->execute(array($course,$title,$tags,$quiz,$ans,$multa,$multb,$multc,$multd,$qids));
            }else{
                $stmt = $pdo->prepare('UPDATE quiz SET course=?, title=?, tags=?, quiz=?, answer=? WHERE qids = ?');
                $stmt->execute(array($course,$title,$tags,$quiz,$ans,$qids));
            }
        }catch (PDOException $e){
            echo "something wrong";
        }
    }
}
?>

<?php
try{
        $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);

        $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

        $stmt = $pdo->prepare('SELECT qids, tags,mult,multa,multb,multc,multd,quiz, answer FROM quiz WHERE title = ? AND course = ?');
        $stmt->execute(array($title,$course));
        $quiz = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($quiz as $row) {
            $_SESSION['qids'] = $row['qids'];
            $qids = $row['qids'];
	    $tags = $row['tags'];
            $quiz = $row['quiz'];
            if ($row['mult'] == 'yes'){
                $multjudger = 1;
                $_SESSION["multjudger"] = 1;
                $multa = $row['multa'];
                $multb = $row['multb'];
                $multc = $row['multc'];
                $multd = $row['multd'];
            }else{
		//echo 'here';
                $multjudger = 0;
                $_SESSION["multjudger"] = 0;
            }
	    $ans = $row['answer'];
        }
} catch (PDOException $e){
        echo "something wrong";
    }
//echo $multjudger;// = $_SESSION["multjudger"];
?>

<!DOCTYPE html>
<html lang=“ja”>
<head>
    <meta charset=“UTF-8”>

    <title>quiz result</title>
	<link rel="stylesheet" href="SecondStylesheet.css">
</head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js">
<script src="http://code.jquery.com/jquery-latest.min.js"></script>
<script>
 //alert('here')
 var  title = '<?php echo $title; ?>';
 var  quiz = '<?php echo $quiz; ?>'; 
 var  course = '<?php echo $course; ?>';
 var  tags = '<?php echo $tags; ?>'; 
 var  multa = '<?php echo $multa; ?>';
 var  multb = '<?php echo $multb; ?>';
 var  multc = '<?php echo $multc; ?>';
 var  multd = '<?php echo $multd; ?>';
 var  ans = '<?php echo $ans; ?>';
 
 $(document).ready(function(){
  getFile()
 })
 function getFile() {
    //alert('here')
    $("#title").val(title)
    $("#tags").val(tags)
    $("#course").val(course)
    $("#question").val(quiz)
    <?php if ($multjudger){?>
        $("#a").val(multa)
        $("#b").val(multb)
        $("#c").val(multc)
        $("#d").val(multd)
    <?php }?>
    $("#answer").val(ans)
 }

 function upd() {
    if (document.getElementById('course').value == "" || document.getElementById('question').value == ""
    || document.getElementById('title').value == ""  || document.getElementById('answer').value == "" 
    || document.getElementById('tags').value == ""){
        alert('fullfill allfield')
	return false
	}else{
	if (confirm('Do you want to update?(if you already released the quiz and some students answered, this updates  will not be affected for them)')) {
           upform.submit();
       } else {
           return false;
       }
	}
 }
</script>
<body>
<h2>Quiz Result</h2>
<form id="upform" onsubmit="return upd()" name="upform" action="" method="POST">
course: <input type="text" id="course" name="course" value="" readonly>
<br>
title: <input type="text" id="title" name="title" value="">
<br>
tags: <input type="text" id="tags" name="tags" value="">
<br>
question:<br>
<textarea type="text" name="question" id="question" value=""></textarea>
<br>
<?php if ($multjudger){ ?>
    choice a: <input type="text" id="a" name="a" value="">
    <br>
    choice b: <input type="text" id="b" name="b" value="">
    <br>
    choice c: <input type="text" id="c" name="c" value="">
    <br>
    choice d: <input type="text" id="d" name="d" value="">
<br>
<?php } ?>
answer: <input type="text" id="answer" name="answer" value="">

<input type="button" onclick="getFile()" value="Reset" />
<input type="submit" name="up" value="update"/>
</form>
<button value="Go Back"><a href=<?php echo "'result.php?id=".$qids."'";?>>Go Back</a></button>
