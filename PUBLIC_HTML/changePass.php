
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

<?php

    if(isset($_POST["changePassword"])){
        $user = $_POST["username"];
        $password = $_POST["password"];
        $pw2 = $_POST["password2"];
        $pw = trim($_POST["password"]);
        $pw_enc = sha1($pw);


        try{
            $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);
            $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
            if ($password == $pw2){
                $stmt = $pdo->prepare('UPDATE user SET password = ? WHERE name = ?');
                $stmt->execute(array($pw_enc, $user));
                echo "<script>alert('Success!!!')</script>";
            }
            else{
                echo "passwords do not match";
                }
        }catch (PDOException $e){
            echo "something wrong";
        }
   }
?>

<!DOCTYPE html>
<html lang=“ja”>
<head>
    <meta charset=“UTF-8”>
        <link rel="stylesheet" href="SecondStylesheet.css">
    <title>Change Page</title>
</head>
<body>
<h2>Change Password</h2>
<br>
 
<form id="changePass" name="changePass" action="" method="POST">
            <fieldset>
                <div><font color="#ff0000"><?php echo htmlspecialchars($errorMessage, ENT_QUOTES); ?></font></div>
                <div><font color="#0000ff"><?php echo htmlspecialchars($signUpMessage, ENT_QUOTES); ?></font></div>
                
		 <label for="username">username</label><input type="text" id="username" name="username" value="<?php if (!empty($_POST["username"])) 
			{echo htmlspecialchars($_POST["username"], ENT_QUOTES);} ?>">

               <br>
                <label for="password">password</label><input type="password" id="password" name="password" value="" >
                <br>
               <label for="password2">password (confirmation)</label><input type="password" id="password2" name="password2" value="">
                <br>

		<input type="submit" id="changePassword" name="changePassword"
			 value="Change Password" onclick="return clicked();">
</form> 
<button value="go back"><a href="admin.php">go back</a></button>

<script type="text/javascript">
        function clicked() {
            if (document.getElementById('username').value == "" 
            ||
document.getElementById('password').value == ""
            || document.getElementById('password2').value == ""){
                alert('Please make sure fill all fields')
                return false;
            }else {
                if (document.getElementById('username').value.includes(";") ||
                 document.getElementById('password').value.includes(";")
                || document.getElementById('password2').value.includes(";")){
                    alert('";" cannot be used')
                    return false;
                }else {
                    return true;
                }
            }
        }
    </script>

</body>
</html>


