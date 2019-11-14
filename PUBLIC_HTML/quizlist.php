<?php
session_start();
$course = $_GET['course'];
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
    <title>quiz list</title>
</head>
<body>
<h2>Quiz List</h2>


<?php
try{
        $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);

        $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
	$stmt = $pdo->prepare('SELECT qids, title FROM quiz WHERE course = ? AND teacher = ?');
        $stmt->execute(array($course, $username));
        $cols = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "quiz list in ".$course;
        echo '<ol>';
        if ($cols != false){
            foreach($cols as $col) {
                echo "<li><a href=\"result.php?id=".$col['qids']."\">".$col['title']."</li>";
            }
        } else {
            echo "No quiz created";
        }
        echo '</ol>';
    }catch (PDOException $e){
        echo "something wrong";
    }
    ?>

<button value="Go Back"><a href="teacher.php">Go Back</a></button>
