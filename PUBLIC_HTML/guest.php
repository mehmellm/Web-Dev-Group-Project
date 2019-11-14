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
 
<body>
<h2 style="text-decoration:underline;">Student Home Page</h2>

<?php
echo "</br>Currently Logged In","</br></br>";
echo "Username : ",$_SESSION["username"],"</br>";
echo "Faculity : Guest</br>";
?>

<p>Guest only can see the courses <p>

<?php
    try{
        $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);
        $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

	    /*$stmt = $pdo->prepare("SELECT A.user
        FROM course A
       "// ((INNER JOIN course B ON A.name = B.user WHERE B.user = ? AND A.fac = 'teacher')
        ."INNER JOIN course C ON A.course = C.course WHERE C.user = ?;");*/
	$stmt = $pdo->prepare("SELECT A.user, A.course
        FROM course A
        INNER JOIN user U ON A.user = U.name WHERE U.fac = 'teacher' ORDER BY A.course;");
        $stmt->execute();
        $cols = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if ($cols != false){
            echo "<ul>";
            foreach($cols as $col) {
                echo "<li>".$col['user']."--".$col['course']."</li>";
            }
            echo "</ul>";
        } else {
            echo "No course is being offered";
        }
    }catch (PDOException $e){
        echo "something wrong";
    }
?>

<button value="logout"><a href="logout.php">Logout</a></button>


</body>
