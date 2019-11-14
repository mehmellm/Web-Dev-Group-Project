<?php
//require 'password.php';  
session_start();
if ($_SESSION["username"]==""){
    header("Location: sqlLogin.php");
}
$db['host'] = "brahe.canisius.edu";  // DBサーバのURL
$db['user'] = "CSC380f";  // ユーザー名
$db['pass'] = "paris99";  // ユーザー名のパスワード
$db['dbname'] = "CSC380f";  // データベース名

$username = $_SESSION['username'];
// エラーメッセージの初期化
$errorMessage = "";

// ログインボタンが押された場合
if (isset($_POST["delete"])) {
    // 1. ユーザIDの入力チェック
    if (empty($_POST["userid"])) {  // emptyは値が空のとき
        $errorMessage = 'type user id';
    } 
    if (!empty($_POST["userid"])) {
        // 入力したユーザIDを格納
        $userid = $_POST["userid"];

        // 2. ユーザIDとパスワードが入力されていたら認証する
        $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);

        // 3. エラー処理
        try {
            $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

            $stmt = $pdo->prepare('SELECT * FROM user WHERE name = ?');
            $stmt->execute(array($userid));
	    $row = $stmt->fetch(PDO::FETCH_ASSOC);
	   
            if ($row != false){
                $stmt = $pdo->prepare('DELETE FROM user WHERE name =?');
                $stmt->execute(array($userid));
		header("Location: admin.php"); 
            	exit();
            } else {
                $errorMessage = 'userID is incorrect';
            }
            } catch (PDOException $e) {
                $errorMessage = 'DB error';
            }
    }
}
?>

<!doctype html>
<html>
    <head>
            <meta charset="UTF-8">
<link rel="stylesheet" href="SecondStylesheet.css">

            <title>Delete</title>
    </head>
    <script type="text/javascript">
    var username = '<?php echo $username; ?>';
    function clicked() {
        if (document.getElementById('userid').value == username ){
                alert('admin user cannot delete your account')
        }else{  
            if (confirm('Do you want to delete?')) {
                return true
            }
            else {
                return false;
            }
        }
    }

    </script>
    <body>
        <h1>Account Delete</h1>
        <form id="deleteForm" name="deleteForm" action="" method="POST">
            <fieldset>
                <legend>Account Delete</legend>
                <div><font color="#ff0000"><?php echo htmlspecialchars($errorMessage, ENT_QUOTES); ?></font></div>
                <label for="userid">Username:</label><input type="text" id="userid" name="userid" placeholder="username" value="<?php if (!empty($_POST["userid"])) {echo htmlspecialchars($_POST["userid"], ENT_QUOTES);} ?>">
                <br>
                <input type="submit" id="delete" name="delete" onclick="return clicked();" value="delete">
            </fieldset>
        </form>
        <br>
    </body>
<button value="go back"><a href="admin.php">go back</a></button>


</html>
