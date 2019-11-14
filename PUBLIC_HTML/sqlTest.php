<?php

session_start();

$db['host'] = "brahe.canisius.edu";  // DBサーバのURL
$db['user'] = "CSC380f";  // ユーザー名
$db['pass'] = "paris99";  // ユーザー名のパスワード
$db['dbname'] = "CSC380f";  // データベース名

// エラーメッセージ、登録完了メッセージの初期化
$errorMessage = "";
$signUpMessage = "";

// ログインボタンが押された場合
if (isset($_POST["signUp"])) {
    // 1. ユーザIDの入力チェック
    if (empty($_POST["username"])) {  // 値が空のとき
        $errorMessage = 'type user ID';
    } else if (empty($_POST["password"])) {
        $errorMessage = 'type password';
    } else if (empty($_POST["password2"])) {
        $errorMessage = 'type password';
    }else if (empty($_POST["level"])) {
        $errorMessage = 'choose level';
    }else if (empty($_POST["name"])) {
        $errorMessage = 'type name';
    }else if (empty($_POST["email"])) {
        $errorMessage = 'type email';
    }


    if ( !empty($_POST["email"]) && !empty($_POST["name"]) &&!empty($_POST["level"]) && !empty($_POST["username"]) && !empty($_POST["password"]) && !empty($_POST["password2"]) && $_POST["password"] === $_POST["password2"]) {
        if ($_POST["fac"] == 'teacher' && $_POST["level"] != 'N/A'){
            $signUpMessage = 'Teacher must choose N/A in level section.';  
        }else if ($_POST["fac"] == 'admin' && $_POST["level"] != 'N/A'){
            $signUpMessage = 'Admin user must choose N/A in level section.'; 
        }else if ($_POST["fac"] == 'guest' && $_POST["level"] != 'N/A'){
            $signUpMessage = 'Guest user must choose N/A in level section.';
        }else{
            // 入力したユーザIDとパスワードを格納
            $name = $_POST["name"];
            $email = $_POST["email"];
            $level = $_POST["level"];
            $username = $_POST["username"];
            $password = $_POST["password"];
            $fac = $_POST["fac"];
            $pw = trim($_POST["password"]);
            $pw_enc = sha1($pw);

            // 2. ユーザIDとパスワードが入力されていたら認証する
            $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);

            // 3. エラー処理
            try {
                $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
                if ($fac == "admin"){
                    $stmt = $pdo->prepare("SELECT * FROM user WHERE name = ?");
                    $stmt->execute(array($username));
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    if ($row == false){
                        $stmt = $pdo->prepare("SELECT * FROM user WHERE fac = 'admin'");
                        $stmt->execute();
                        if ($stmt == false){
                            $stmt = $pdo->prepare("INSERT INTO user(email,realname,name,level,password,fac) VALUES (?, ?, ?, ?, ?, ?)");
                            $stmt->execute(array($email,$name, $username, $level, $pw_enc,$fac));  
                            $userid = $pdo->lastinsertid();
                            header('Location: sqlLogin.php');
                            exit();
                        }else{
                            $signUpMessage = 'There is already admin user';
                        }
    
                    }else{
                        $signUpMessage = 'The username is alredy taken';      
                    }
                }else{
                    $stmt = $pdo->prepare('SELECT * FROM user WHERE name = ?');
                    $stmt->execute(array($username));
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    if ($row == false){
                        $stmt = $pdo->prepare("INSERT INTO user(email,realname,name,level,password,fac) VALUES (?, ?, ?, ?, ?, ?)");
                        $stmt->execute(array($email,$name, $username, $level, $pw_enc,$fac));  
                        $userid = $pdo->lastinsertid();
                        header('Location: sqlLogin.php');
                        exit();
                    }else{
                        $signUpMessage = 'The username is alredy taken';      
                    }
                }

            } catch (PDOException $e) {
                $errorMessage = 'DB Error';
            }
        }
    } else if($_POST["password"] != $_POST["password2"]) {
        $errorMessage = 'passwords did not match';
    }
}
?>

<!doctype html>
<html>
    <head>
            <meta charset="UTF-8">
            <title>registration</title>
		<link rel="stylesheet" href="FirstStylesheet.css">
    </head>
    <script type="text/javascript">
        function clicked() {
            if (document.getElementById('name').value == "" || document.getElementById('username').value == ""
            || document.getElementById('email').value == ""|| document.getElementById('password').value == ""
            || document.getElementById('password2').value == ""){
                alert('Please make sure fill all fields')
                return false
            } else {
                if (document.getElementById('name').value.includes(";") || document.getElementById('username').value.includes(";")
                || document.getElementById('email').value.includes(";")|| document.getElementById('password').value.includes(";")
                || document.getElementById('password2').value.includes(";")){
                    alert('";" cannot be used')
                    return false;
                }else {
                    return true;
                }
            }
        }
    </script>
    <body>
	<img src="derk.png" alt="Welcome to Der Klicken!">
        <h1>registration</h1>
        <form id="loginForm" name="loginForm" action="" method="POST">
            <fieldset>
                <legend>registration form</legend>
                <div><font color="#ff0000"><?php echo htmlspecialchars($errorMessage, ENT_QUOTES); ?></font></div>
                <div><font color="#0000ff"><?php echo htmlspecialchars($signUpMessage, ENT_QUOTES); ?></font></div>
                <label for="name">name</label><input type="text" id="name" name="name"  value="<?php if (!empty($_POST["username"])) {echo htmlspecialchars($_POST["name"], ENT_QUOTES);} ?>">
                <br>
                <label for="username">username</label><input type="text" id="username" name="username"  value="<?php if (!empty($_POST["username"])) {echo htmlspecialchars($_POST["username"], ENT_QUOTES);} ?>">
               <br>
                <label for="email">email</label><input type="email" id="email" name="email" value="" >
                <br>
		 <br>
                <label for="password">password</label><input type="password" id="password" name="password" value="" >
                <br>
                <label for="password2">password (confirmation)</label><input type="password" id="password2" name="password2" value="">
                <br>
                <select name ='fac'>
                    <option>stud</option>
                    <option>teacher</option>
		    <option>admin</option>
		    <option>guest</option>
                </select>
                <select name ='level'>
                    <option>N/A</option>
		    <option>freshman</option>
                    <option>sophmore</option>
                    <option>junior</option>
                    <option>senior</option>
                </select>
                <input type="submit" id="signUp" name="signUp" value="register" onclick="return clicked();">
            </fieldset>
        </form>
        <br>
        <form action="sqlLogin.php">
            <input type='submit' value='go to login'>
        </form>
    </body>
</html>

