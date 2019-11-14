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
<h2>Admin Page</h2>
<br>

<button value="Change Password"><a href="changePass.php">Change Password</a></button>
<br><br>

<button value="Delete User"><a href="deleteAccount.php">Delete User</a></button>
<br><br>

<button value="Change Role"><a href="changeRole.php">ChangeRole</a></button>
<br>
<br>
<button value="logout"><a href="logout.php">Logout</a></button>

</body>
</html>

