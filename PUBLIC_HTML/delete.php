<?php
session_start();
$db['host'] = "brahe.canisius.edu";  // DBサーバのURL
$db['user'] = "CSC380f";  // ユーザー名
$db['pass'] = "paris99";  // ユーザー名のパスワード
$db['dbname'] = "CSC380f";  // データベース名
$username = $_SESSION['username'];

if (isset($_POST["delete"])) {

if (!empty($_POST["delete"])) {
    try{
        $course = $_GET["course"];
        $title = $_GET["title"];
	//echo $course;
	echo $username;
        $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);
        $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
        $stmt = $pdo->prepare("DELETE FROM quiz WHERE teacher = ? AND title = ? AND course = ?");
        $stmt->execute(array($username,$title,$course));
        $errorMessage ='successfully released';
        header('Location: quizlist.php?course='.$course);
    }catch (PDOException $e){
        echo "something wrong";
    }
}
}
?>
