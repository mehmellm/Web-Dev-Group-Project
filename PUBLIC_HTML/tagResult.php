<?php
session_start();
if ($_SESSION["username"]==""){
        header("Location: sqlLogin.php");
}
$tagdata = $_GET['id'];
$tag = $_GET['tag'];
$db['host'] = "brahe.canisius.edu";  // DBサーバのURL
$db['user'] = "CSC380f";  // ユーザー名
$db['pass'] = "paris99";  // ユーザー名のパスワード
$db['dbname'] = "CSC380f";  // データベース名
$username = $_SESSION['username'];
?>

<html>
<head>
<link rel="stylesheet" href="SecondStylesheet.css">
</head>
<body>


<?php
    echo '<h2>'.$tag.'</h2>';
    $ids = explode(",",$tagdata);
    echo '<ul>';
    try{
            $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);
            $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
            foreach ($ids as $id){
		//echo 'here';
		//echo $id;
                $stmt = $pdo->prepare('SELECT title,qids FROM quiz WHERE qids = ?');
                $stmt->execute(array($id));
                $cols = $stmt->fetch(PDO::FETCH_ASSOC);
                echo "<li><a href='result.php?id=".$cols['qids']."'>".$cols['title']."</a></li>";
	    }
	    echo "</ul>";
            $errorMessage ='successfully released';
        }catch (PDOException $e){
            echo "something wrong";
        }

?>

<button value="go back"><a href="tags.php">go back</a></button>

</body>
</html>
