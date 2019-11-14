<?php
//require 'password.php';  
session_start();

$db['host'] = "brahe.canisius.edu";  // DBサーバのURL
$db['user'] = "CSC380f";  // ユーザー名
$db['pass'] = "paris99";  // ユーザー名のパスワード
$db['dbname'] = "CSC380f";  // データベース名

// エラーメッセージの初期化
$errorMessage = "";

// ログインボタンが押された場合
if (isset($_POST["login"])) {
    // 1. ユーザIDの入力チェック
    if (empty($_POST["username"])) {  // emptyは値が空のとき
        $errorMessage = 'type user id';
    } else if (empty($_POST["password"])) {
        $errorMessage = 'type pass';
    }

    if (!empty($_POST["username"]) && !empty($_POST["password"])) {
        // 入力したユーザIDを格納
        $username = $_POST["username"];

        // 2. ユーザIDとパスワードが入力されていたら認証する
        $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);

        // 3. エラー処理
        try {
            $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

            $stmt = $pdo->prepare('SELECT * FROM user WHERE name = ?');
            $stmt->execute(array($username));
	    $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $pw = trim($_POST["password"]);
 	   
	    
	    $pw_enc = sha1($pw);
	   
	    if ($row['password'] == $pw_enc){
		$fac = $row['fac'];
		$_SESSION['username']= $username;
		if($fac == "stud"){
            header("Location: user.php"); 
            exit();  // 処理終了
        } else if ($fac == "teacher"){
            header("Location: teacher.php"); 
            exit();  // 処理終了
        }else if ($fac == "admin"){
            header("Location: admin.php"); 
            exit();  // 処理終了
        }else if ($fac == "guest"){
            header("Location: guest.php");
            exit();  // 処理終了
        }
		} else {
                    // 認証失敗
                    $errorMessage = 'username or password is incorrect';
                }
        } catch (PDOException $e) {
            $errorMessage = 'DB Error';
        }
    }
}
?>

<!doctype html>
<html>
    <head>
            <meta charset="UTF-8">
            <title>Login</title> 
    		<link rel="stylesheet" href="FirstStylesheet.css">
		<style>
			a{margin-left:5px;}
		</style>
    </head>
    <script type="text/javascript">
        function clicked() {
            if (document.getElementById('username').value == "" || document.getElementById('password').value == ""){
                alert('Please make sure fill all fields')
                return false;
            }else {
                if (document.getElementById('username').value.includes(";") || document.getElementById('password').value.includes(";")){
                    alert('";" cannot be used')
                    return false;
                }else {
                    return true;
                }
            }
        }
    </script>
    <body>
	<img src="derk.png" alt="Welcome to Der Klicken">
        <h1>Login</h1>
        <form id="loginForm" name="loginForm" action="" method="POST">
            <fieldset>
                <legend>Login</legend>
                <div><font color="#ff0000"><?php echo htmlspecialchars($errorMessage, ENT_QUOTES); ?></font></div>
                <label for="username">username</label><input type="text" id="username" name="username" placeholder="username" value="<?php if (!empty($_POST["username"])) {echo htmlspecialchars($_POST["username"], ENT_QUOTES);} ?>">
                <br>
                <label for="password">password</label><input type="password" id="password" name="password" value="" placeholder="password">
                <br>
                <input type="submit" id="login" name="login" value="login" onclick="return clicked();">
            </fieldset>
        </form>
	<a href="./PWRESET/changepw.html">forget password?</a>
	<br>
	<br>
        <form action="sqlTest.php">
            <fieldset>          
                <legend>Register</legend>
                <input type="submit" value="register">
            </fieldset>
        </form>
    </body>
</html>

