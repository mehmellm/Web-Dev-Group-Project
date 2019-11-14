<?php
session_start();

$coursename = $_GET['coursename'];
$username = $_SESSION['username'];
$db['host'] = "brahe.canisius.edu";  // DBサーバのURL
$db['user'] = "CSC380f";  // ユーザー名
$db['pass'] = "paris99";  // ユーザー名のパスワード
$db['dbname'] = "CSC380f";  // データベース名

if (isset($_GET["register"])) {
    // 1. ユーザIDの入力チェック
    if (empty($_GET["coursename"])) {  // 値が空のとき
        $errorMessage = 'type coursename';
    }
    if (!empty($_GET["coursename"]) && $_GET["coursename"] != 'default') {        
        try {
	    $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);
            $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

            $stmt = $pdo->prepare('SELECT * FROM course WHERE user = ? AND course = ?');
            $stmt->execute(array($username,$coursename));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
	    if ($row == false){
                $inst = $pdo->prepare("INSERT INTO course(course,user) VALUES (?, ?)");
                $inst->execute(array($coursename, $username));
		$errorMessage = 'succesfully registered';    
            }else{
                $errorMessage = 'you have already registered';
            }  
        } catch (PDOException $e) {
            $errorMessage = 'DB Error';
        }
    } else {
        $errorMessage = 'choose course';
    }
}
?>

<!doctype html>
<html>
<head>
<link rel="stylesheet" href="SecondStylesheet.css">
</head>
<body>
<div><font color="#ff0000"><?php echo htmlspecialchars($errorMessage, ENT_QUOTES); ?></font></div>
<h2>Student Course Registration</h2>
<form method=”GET”>    
    <?php 
    $db['host'] = "brahe.canisius.edu";  // DBサーバのURL
$db['user'] = "CSC380f";  // ユーザー名
$db['pass'] = "paris99";  // ユーザー名のパスワード
$db['dbname'] = "CSC380f";  // データベース名
    $username = $_SESSION['username'];
    try{
	$dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);

        $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
	//$stmt = $pdo->prepare('SELECT course FROM courselist');
        $stmt = 'SELECT course FROM courselist';
        $cols = $pdo->query($stmt);
        //$cols = $stmt->fetch(PDO::FETCH_ASSOC);
	echo '<h3>Available Courses<br></h3>';
        echo '<select name = "coursename">';
        echo "<option> default </option>";
        $stmt = $pdo->prepare('SELECT * FROM course WHERE user = ? AND course = ?');
        if ($cols != false){
            foreach($cols as $col) {
                $stmt->execute(array($username,$col['course']));
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($row == false){
                    echo "<option>".$col['course']."</option>";
                }
            }
        } else {
            echo "something went wrong";
        }
    }catch (PDOException $e){
        echo "something went wrong";
    }
    ?>
 <select>
<input type="submit" id="register" name="register" value="register">
<button value="logout"><a href="user.php">Go back</a></button>
</form>
</body>
</html>
