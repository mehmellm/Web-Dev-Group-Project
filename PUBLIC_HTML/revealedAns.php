<?php
session_start();
$course = $_GET['course'];
$title = $_GET['title'];
//echo $title;
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
    <title></title>
</head>
<body>
<?php echo '<h2>'.$course.'</h2>';?>
<?php echo '<h2>'.$title.'</h2>';?>

<?php
$question = '';
$mult = "";
$multa = "";
$multb = "";
$multc = "";
$multd = "";
$correctans = "";

try{
        $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);

        $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

        $stmt = $pdo->prepare('SELECT mult,multa,multb,multc,multd,quiz, answer FROM quiz WHERE revealable="yes" AND title = ? AND course = ?');
        $stmt->execute(array($title,$course));
        $quiz = $stmt->fetchAll(PDO::FETCH_ASSOC);
        //echo var_dump($quiz);
	if ($quiz == false){
	echo 'illegal process';
	exit();
	}
        foreach ($quiz as $row) {
            //echo var_dump($row);
            $question = $row['quiz'];
            $mult = $row['mult']; 
	    if ($row['mult'] == 'yes'){
	    	$multa = $row['multa'];
		    $multb = $row['multb'];
		    $multc = $row['multc'];
		    $multd = $row['multd'];	
	    }
            $correctans = $row['answer'];
        }
} catch (PDOException $e){
        echo "something wrong";
    }
?>


<!DOCTYPE html>
<html>
    
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
    </head>
    <body>
        <?php
	    echo 'question';
	    echo '<br>'; 
            echo $question;
	    echo '<br><br>'; 
            if ($mult == 'yes'){
                echo '<p>choice a: '.$row['multa']."</p>";
		        echo '<p>choice b: '.$row['multb']."</p>";
		        echo '<p>choice c: '.$row['multc']."</p>";
                echo '<p>choice d: '.$row['multd']."</p>";
                echo '<p style="color: red">                answer:  ';
                echo $correctans.'</p>';
            }else{
                echo '<p style="color: red">               answer:  ';
                echo $correctans.'</p>';;
            }
        
        ?>
		<br>
    <button value="Go Back"><a href="user.php">Go Back</a></button>
    </body>
</html>
