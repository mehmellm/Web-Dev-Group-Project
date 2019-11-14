<?php
$username = $_POST["username"];
$refnum = $_POST["refnum"];
$pw1 = $_POST["password1"];
$pw2 = $_POST["password2"];

$db['host'] = "brahe.canisius.edu";  // DBサーバのURL
$db['user'] = "CSC380f";  // ユーザー名
$db['pass'] = "paris99";  // ユーザー名のパスワード
$db['dbname'] = "CSC380f";  // データベース名

$pw = trim($pw1);
$pw_enc = sha1($pw);

/*
$username = "Mark";
$refnum = "2891404146328568";
$pw1 = "cats";
$pw2 = "cats";
*/

//print("username=$username from the get line");
//print("refnum=$refnum from the get line");

/*
if ($pw1 != $pw2) {
     echo "Passwords do not match.  Nothing changed.";
     return;
}
*/

$text = file_get_contents("./request.txt", true);

$lastline = "";

foreach (explode("\n", $text) as $line) {
    $pieces = explode(";", $line);
    //echo $line;
    if ($username == $pieces[0]) {
         $lastline = $line;
         //print("\nFound in line = $lastline");
    }
}

if ($lastline == "") {
    print ("Didn't find a request for this username: $username");
    return;
}

$pieces = explode(";", $lastline);
//echo "\npieces[2] = ". $pieces[2];
if ($refnum != $pieces[2]) {
    echo "Refnum is illegal or expired.  Try again.";
    return;
}else{

    try{
        $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);
        $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
            $stmt = $pdo->prepare('UPDATE user SET password = ? WHERE name = ?');
            $stmt->execute(array($pw_enc, $username));
            echo "<script>alert('Success!!!')</script>";
            echo "Change password to $pw1";
    }catch (PDOException $e){
        echo "something wrong";
    }
}
?>

