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
$errorMessage = "";
$signUpMessage = "";


if (isset($_POST["change"])) {
    if (empty($_POST["username"])) {  
        $errorMessage = 'type user ID';
    }


    if ( !empty($_POST["username"])) {
        if ($_POST["fac"] == 'teacher' && $_POST["level"] != 'N/A'){
            $signUpMessage = 'Teacher must choose N/A in level section.';  
        }else{
            $name = $_POST["username"];
            $fac = $_POST["fac"];
            $level = $_POST["level"];

            $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);
	    $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
            // 3. エラー処理
            try {
		//echo $name;
                $stmt = $pdo->prepare('SELECT * FROM user WHERE name = ?');
                $stmt->execute(array($name));
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
		//echo 'here';
                if ($row == false){
                    $signUpMessage = 'The username is incorect';
                }else{
                    $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
                    $stmt = $pdo->prepare('UPDATE user SET level=?, fac=? WHERE name = ?');
                    $stmt->execute(array($level,$fac,$name));
                    header('Location: admin.php');
                    exit();
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
		<link rel="stylesheet" href="SecondStylesheet.css">
    </head>
    <script type="text/javascript">
        var username = '<?php echo $username; ?>';
        function clicked() {
	if (document.getElementById('userid').value != ""){
            if (document.getElementById('userid').value == username ){
                    alert('admin user cannot delete your account')
		    return false
            }else{  
                if (confirm('Do you want to delete?')) {
                    return true
                }
                else {
                    return false;
                }
            }
        }else{
		alert('fill the textfield')
	}
        }
    </script>
    <body>
        <h1>Change Role</h1>
        <form id="changeForm" name="changeForm" action="" method="POST">
            <fieldset>
                <legend>Change Role</legend>
                <div><font color="#ff0000"><?php echo htmlspecialchars($errorMessage, ENT_QUOTES); ?></font></div>
                <div><font color="#0000ff"><?php echo htmlspecialchars($signUpMessage, ENT_QUOTES); ?></font></div>
                <br>
                <label for="username">username</label><input type="text" id="username" name="username"  value="<?php if (!empty($_POST["username"])) {echo htmlspecialchars($_POST["username"], ENT_QUOTES);} ?>">
                <br>
                
                <select name ='fac'>
                    <option>stud</option>
                    <option>teacher</option>
                </select>
                <select name ='level'>
                    <option>N/A</option>
		            <option>freshman</option>
                    <option>sophmore</option>
                    <option>junior</option>
                    <option>senior</option>
                </select>
                <input type="submit" id="change" name="change" value="change" onclick="return clicked();">
            </fieldset>
        </form>
        <br>
        <form action="sqlLogin.php">
            <input type='submit' value='go to login'>
        </form>
    </body>
<button value="go back"><a href="admin.php">go back</a></button>

</html>
