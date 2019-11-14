<?php
session_start();
$qids = $_GET['id'];
$course = "";
$title = "";
$username = $_SESSION['username'];

if ($_SESSION["username"]==""){
        header("Location: sqlLogin.php");
}

$db['host'] = "brahe.canisius.edu";  // DBサーバのURL
$db['user'] = "CSC380f";  // ユーザー名
$db['pass'] = "paris99";  // ユーザー名のパスワード
$db['dbname'] = "CSC380f";  // データベース名

$result = array();
$waiting = array();
?>

<!DOCTYPE html>
<html lang=“ja”>
<head>
    <meta charset=“UTF-8”>
<link rel="stylesheet" href="SecondStylesheet.css">
    <title>quiz result</title>
</head>
<body>
<h2>quiz result</h2>

<?php
try{
        $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);

        $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

        $stmt = $pdo->prepare('SELECT title, course, mult,multa,multb,multc,multd,quiz, answer FROM quiz WHERE qids = ?');
        $stmt->execute(array($qids));
        $quiz = $stmt->fetchAll(PDO::FETCH_ASSOC);
        //echo var_dump($stud);
        foreach ($quiz as $row) {
            //echo var_dump($row);
	    $title = $row['title']; 
	    $course = $row['course'];
	    echo "course: ".$course;
	    echo '<br>';
	    echo "title: ".$title;
            echo '<p><h3>question</h3>'.$row['quiz']."</p>";
	    if ($row['mult'] == 'yes'){
	    	echo '<p>choice a: '.$row['multa']."</p>";
		echo '<p>choice b: '.$row['multb']."</p>";
		echo '<p>choice c: '.$row['multc']."</p>";
		echo '<p>choice d: '.$row['multd']."</p>";	
	    }
            echo '<br>   <p>correct answer: '.$row['answer']."</p>";
        }
} catch (PDOException $e){
        echo "something wrong";
}
?>

<?php
try{
        $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);

        $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

        $stmt = $pdo->prepare('SELECT user FROM course WHERE course = ?');
        $stmt->execute(array($course));
        $stud = $stmt->fetchAll(PDO::FETCH_ASSOC);
        //echo var_dump($stud);
        $allstud = array();
        foreach ($stud as $row) {
            //echo $row['user'];
	    //echo $username;
		if ($row['user'] != $username){
            array_push($allstud,$row['user']);
		}
        }
    

	$stmt = $pdo->prepare('SELECT datetime, taker, answer, checker, levRates FROM quiztaker WHERE quiztitle = ? AND course = ?');
        $stmt->execute(array($title, $course));
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $took = array();
        foreach ($result as $row) {
            //echo var_dump($row);
            array_push($took,$row['taker']);
        }

	//echo var_dump($allstud);
	//echo var_dump($took);
        $waiting = array_diff($allstud, $took);
	//echo var_dump($waiting);
    }catch (PDOException $e){
        echo "something wrong";
    }

function html_table($data = array())
{
if ($data != null){
    $rows = array();
    //echo var_dump($data);
    foreach ($data as $row) {
	//echo var_dump($row);
	$cells = array();
        $cells[] = "<td>".$row['taker']."</td>"."<td>".$row['checker']."</td>"."<td>".$row['levRates']."</td>"."<td>".$row['answer']."</td>"."<td>".$row['datetime']."</td>";
        $rows[] = "<tr>" . implode('', $cells) . "</tr>";
    }
    return "<table class='hci-table'><tr><th>Student</th><th>Score</th><th>levy Rates</th><th>answer</th><th>time</tr>" . implode('', $rows) . "</table>";
}else{ echo '<p>nobody took this quiz</p>';}
}

function stud($data = array())
{
if ($data != null){
    $rows = array();
    //echo var_dump($data);
    foreach ($data as $row) {
	//echo var_dump($row);
	$cells = array();
    $cells[] = "<td>".$row."</td>";
    $rows[] = "<tr>" . implode('', $cells) . "</tr>";
    }
    return "<table class='hci-table'><tr><th>Student Who did not take the quiz</th></tr>" . implode('', $rows) . "</table>";
}
}
?>
<script type="text/javascript">
    function clicked() {
       if (confirm('Do you want to kill it?')) {
           killfrom.submit();
       } else {
           return false;
       }
    }
</script>

<br>   
 <form id="delete" name="delete" <?php echo 'action="delete.php?course='.$course.'&title='.$title.'"'; ?> method="POST">
<br>        
<button value="Go Back"><?php echo "<a href=updatequiz.php?course=".$course."&title=".$title.'&qids='.$qids.">"; ?>Update quiz</a></button>

<input type="submit" name="delete" value="delete" onclick="return clicked()">
    	<br>
	<br>
	<button value="Go option Page"><?php echo '<a href="option.php?course='.$course.'&title='.$title.'&qids='.$qids.'">Option (chnage grade per person)</a>'; ?></button>
<br>
        <button value="Go option Page"><?php echo '<a href="setlev.php?course='.$course.'&title='.$title.'&qids='.$qids.'">Option (set levRates)</a>'; ?></button>  
 </form>
<button><a href="teacher.php">back</a></button>
 <?php echo html_table($result); ?>
    <br>
    <?php echo stud($waiting); ?>
    <br>
    
    </body>
</html>

