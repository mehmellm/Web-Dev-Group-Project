<?php
require_once 'Class_Selector_Creater.php';
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
$takers = array();

try{
        $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);
        $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

        $stmt = $pdo->prepare('SELECT taker FROM quiztaker WHERE quiztitle = ? AND course = ?');
        $stmt->execute(array($title,$course));
        $quiz = $stmt->fetchAll(PDO::FETCH_ASSOC);
        //echo var_dump($quiz);
	foreach($quiz as $row){
        	array_push($takers, $row['taker']);
	}
} catch (PDOException $e){
        echo "something wrong";
}
?>

<?php
if (isset($_POST["change"])) {
    
    $checker = $_POST['checker'];
    $user = $_POST['sel'];

    $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);
    $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
    
	//echo "<script>alert('".$user."')</script>"; 
	//echo "<script>alert('".$checker."')</script>";
	//echo "<script>alert('".$title."')</script>";
	//echo "<script>alert('".$course."')</script>";
    try{
        $stmt= $pdo->prepare("UPDATE quiztaker SET checker = ?  WHERE taker = ? AND quiztitle = ? AND course = ?");
        $stmt->execute(array($checker,$user,$title,$course));

        echo "<script>alert('Successfully changed')</script>";
    }catch (PDOException $e){
        echo "<script>alert('Something Wrong')</script>";
    }

}
?>


    
    <body>
    <form id='change' name=change method='post' action = <?php echo "'option.php?course=".$course."&title=".$title."'"; ?>>
        <?php
	if (count($takers) > 0){
	    echo '<br>please select a student<br>';
	//echo var_dump($takers);
		$sel = new Select_Creater($takers);
        $sels = $sel->creater();
	echo $sels;
	echo '<br>please choose modification<br>';
        echo '<select name="checker">';
        echo '<option>correct</option>';
        echo '<option>wrong</option>';
        echo '</select>';		
        }else{
	 	echo "There is no member!";
	}
        ?>
		<br>
        <input type='submit' value='submit' id="change" name="change">
	</form>
    <button value="Go Back"><a href=<?php echo "'result.php?id=".$qids."'";?>>Go Back</a></button>
    </body>
</html>

