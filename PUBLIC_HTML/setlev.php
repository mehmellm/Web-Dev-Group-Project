<?php
session_start();
$title = $_GET['title'];
$course = $_GET['course'];
$qids = $_GET['qids'];
//echo $title;
$username = $_SESSION['username'];

if ($_SESSION["username"]==""){
        header("Location: sqlLogin.php");
}

$db['host'] = "brahe.canisius.edu";  // DBサーバのURL
$db['user'] = "CSC380f";  // ユーザー名
$db['pass'] = "paris99";  // ユーザー名のパスワード
$db['dbname'] = "CSC380f";  // データベース名

?>
<script src="./jquery.js">
<script>
      window.onpageshow = function(){
        $("lev",).reset();
      };
    </script>
<!DOCTYPE html>
<html lang=“ja”>
<head>
    <meta charset=“UTF-8”>
	<link rel="stylesheet" href="SecondStylesheet.css">

    <title>option</title>
</head>
<h1></h1>
<body>
<?php echo '<h2>'.$course.'</h2>';?>
<?php echo '<h2>'.$title.'</h2>';?>

<?php
if (isset($_POST["change"])) {
    
    $lev = $_POST['lev'] * 1;

    $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);
    $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
    
    try{
        $stmt= $pdo->prepare("UPDATE quiztaker SET checker = 'correct' WHERE levRates <= ? AND quiztitle = ? AND course = ?");
        $stmt->execute(array($lev,$title,$course));

	$stmt= $pdo->prepare("UPDATE quiztaker SET checker = 'wrong' WHERE levRates >= ? AND quiztitle = ? AND course = ?");
        $stmt->execute(array($lev,$title,$course));

        $stmt= $pdo->prepare("UPDATE quiz SET setlev = ? WHERE title = ? AND course = ?");
        $stmt->execute(array($lev,$title,$course));

        echo "<script>alert('Successfully changed')</script>";
    }catch (PDOException $e){
        echo "<script>alert('Something Wrong')</script>";
    }

}
?>


    
    <body>
    <form id='change' name='change' method='post' action = <?php echo "'setlev.php?course=".$course."&title=".$title."'"; ?>>
        <?php
	    echo '<br>please choose levRate<br>';
        echo '<select id="lev" name="lev">';
        echo '<option>0</option>';
        echo '<option>1</option>';
        echo '<option>2</option>';
        echo '<option>3</option>';
        echo '</select>';		
        ?>
		<br>
        <input type='submit' value='submit' id="change" name="change">
	</form>
	<!--<button type="button" onclick="history.back()">back</button>-->
    <button value="Go Back"><a href=<?php echo "'result.php?id=".$qids."'";?>>Go Back</a></button>
    </body>
</html>

