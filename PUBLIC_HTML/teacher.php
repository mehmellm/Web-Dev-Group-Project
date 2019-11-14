<?php
session_start();
if ($_SESSION["username"]==""){
        header("Location: sqlLogin.php");
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
    <title>Teacher Page</title>
</head>
<body>
<h2>Teacher Home Page</h2>
<p>
<?php
echo "Currently logged in as:</br></br>";
echo "Username : ",$_SESSION["username"],"</br>";
echo "Faculity : teacher","</br></br>";
?>
</p>
<button value="create class"><a href="createcourse.php">Create Course</a></button>
<br>
<button value="release control"><a href="release.php">Release Control</a></button>
<br>
<button value="reveal control"><a href="revealable.php">Revealable Control</a></button>
<br>
<button value="chat"><a href="chat_teacher.php">Chat</a></button>
<br>
<button value="tags control"><a href="tags.php">Tag Control</a></button>
<br><p>


<?php
if ($_SESSION["status"] == 'success'){
        echo '';
 echo 'quiz was successfully created';
}
if ($_SESSION["status"] == 'error'){
        echo '';
        echo 'quiz was not created';
}

?>

<?php
try{
        $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);

        $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
	$stmt = $pdo->prepare('SELECT course FROM course WHERE user = ?');
        $stmt->execute(array($username));
        $cols = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "Your Course";
        echo '<ol>';
        if ($cols != false){
            foreach($cols as $col) {
                echo "<li><a href=\"quizlist.php?course=". $col['course'] ."\">".$col['course']."</a></li>";
            }
        } else {
            echo "Something went wrong";
        }
        echo '</ol>';
    }catch (PDOException $e){
        echo "Something wrong";
    }
    ?>
<br>
<p>Create Quiz</p>
<ol>
<li><a href="createquiz.php">Create normal quiz</a></li>
<li><a href="createmultquiz.php">Create multiple choice quiz</a></li>
</ol>
<button value="logout"><a href="logout.php">Logout</a></button>
</body>
</html>
